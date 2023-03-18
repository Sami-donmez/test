<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.1.6
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2023 KONKORD DIGITAL
 */

namespace App\Calendar;

use App\Models\Activity;
use App\Models\Calendar;
use Illuminate\Support\Carbon;
use App\Models\Synchronization;
use App\Contracts\Synchronizable;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\Events;
use Illuminate\Support\Facades\URL;
use App\Events\CalendarSyncFinished;
use Google\Service\Calendar\Channel;
use App\Contracts\SynchronizesViaWebhook;
use App\Innoclapps\Facades\Google as Client;
use Google\Service\Exception as GoogleException;
use App\Contracts\Repositories\ActivityRepository;
use App\Contracts\Repositories\CalendarRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contracts\Repositories\SynchronizationRepository;
use App\Innoclapps\Contracts\Repositories\OAuthAccountRepository;

class GoogleCalendarSync extends CalendarSynchronization implements Synchronizable, SynchronizesViaWebhook
{
    /**
     * @var \App\Contracts\Repositories\SynchronizationRepository
     */
    protected SynchronizationRepository $synchronizations;

    /**
     * @var \App\Innoclapps\Contracts\Repositories\OAuthAccountRepository
     */
    protected OAuthAccountRepository $accounts;

    /**
     * @var \App\Contracts\Repositories\ActivityRepository
     */
    protected ActivityRepository $activities;

    /**
     * Cancelled status name
     */
    const STATUS_CANCELLED = 'cancelled';

    /**
     * @var string
     */
    protected string $webHookUrl;

    /**
     * Initialize new OutlookCalendarSync class
     *
     * @param \App\Models\Calendar $calendar
     */
    public function __construct(protected Calendar $calendar)
    {
        $this->synchronizations = resolve(SynchronizationRepository::class);
        $this->accounts         = resolve(OAuthAccountRepository::class);
        $this->activities       = resolve(ActivityRepository::class);
        $this->webHookUrl       = URL::asAppUrl('webhook/google');
    }

    /**
     * Synchronize the data for the given synchronization
     */
    public function synchronize(Synchronization $synchronization) : void
    {
        $pageToken = null;
        $syncToken = $synchronization->token;
        Client::connectUsing($this->calendar->email);
        $service = Client::calendar();

        do {
            $tokens = compact('pageToken', 'syncToken');

            try {
                $queryString = $this->listQueryString($tokens, $syncToken);

                /** @var \Google\Service\Calendar\Events */
                $list = $service->events->listEvents($this->calendar->calendar_id, $queryString);

                // We will store the default reminders when the list of events is retrieved
                // Google provides the default reminders per calendar in the listEvents request
                // see https://gsuite-developers.googleblog.com/2012/01/calendar-api-v3-best-practices.html
                // The defaults for the given calendar are included at the top of any event listing result.
                // This way, reminder settings for all events in the result can be determined by the client without
                // having to make the additional API call to the corresponding entry in the Calendar List collection.
                $this->storeDefaultReminders($list);
            } catch (GoogleException $e) {
                // https://developers.google.com/calendar/api/guides/errors#410_gone
                if ($e->getCode() === 410) {
                    $this->synchronize(
                        $this->synchronizations->update(['token' => null], $synchronization->getKey())
                    );

                    break;
                } elseif ($e->getCode() === 401) {
                    $this->setAccountRequiresAuth();

                    break;
                }

                throw $e;
            }

            if ($this->processChangedEvents($list->getItems())) {
                $changesPerformed = true;
            }

            $pageToken = $list->getNextPageToken();
        } while ($pageToken);

        $this->synchronizations->updateLastSyncDate($synchronization->getKey(), [
            'token' => $list->getNextSyncToken(),
        ]);

        CalendarSyncFinished::dispatchIf(
            $changesPerformed ?? false,
            $synchronization->synchronizable
        );
    }

    /**
     * Process the changed events
     *
     * @param \Google\Service\Calendar\Event[] $events
     *
     * @return bool
     */
    protected function processChangedEvents(array $events) : bool
    {
        foreach ($events as $event) {
            // Handle deleted events
            if (strtolower($event->getStatus()) === static::STATUS_CANCELLED) {
                $this->handleDeletedEvent($event);
                $changesPerformed = true;

                continue;
            }

            // Exclude events marked as private e.q. appointment to doctor
            if ($event->getVisibility() === 'private') {
                continue;
            }

            // Recurring events are not supported
            if ($this->isRecurring($event)) {
                continue;
            }

            list($model, $guestsUpdated) = $this->processViaChange(
                $this->attributesFromEvent($event),
                $this->determineUser($event->getCreator()?->getEmail(), $this->calendar->user),
                $event->getId(),
                $event->getICalUID(),
                $this->calendar
            );

            if ($model->wasRecentlyCreated || $model->wasChanged() || $guestsUpdated) {
                $changesPerformed = true;
            }
        }

        return $changesPerformed ?? false;
    }

    /**
     * Create attributes for the activity from the given event
     */
    protected function attributesFromEvent(Event $event) : array
    {
        $dueDate  = $this->parseDatetime($event->getStart());
        $endDate  = $this->parseDatetime($event->getEnd());
        $isAllDay = $this->isAllDay($event);

        return [
            'title'                   => $event->getSummary() ?: '(No Title)',
            'description'             => $event->getDescription(),
            'due_date'                => $dueDate->format('Y-m-d'),
            'due_time'                => ! $isAllDay ? $dueDate->format('H:i') . ':00' : null,
            'end_date'                => $endDate->format('Y-m-d'),
            'end_time'                => ! $isAllDay ? $endDate->format('H:i') . ':00' : null,
            'reminder_minutes_before' => $this->determineReminderMinutesBefore($event),
            'guests'                  => collect($event->getAttendees())->map(function ($attendee) {
                return [
                    'email' => $attendee->getEmail(),
                    'name'  => $attendee->getDisplayName(),
                ];
            })->all(),
        ];
    }

    /**
     * Determine reminder minutes before for the given event
     */
    protected function determineReminderMinutesBefore(Event $event) : int|null
    {
        // There are no reminders on Holidays calendars
        if (is_null($event->getReminders())) {
            return null;
        }

        // First, we will check if the event is actually using the default
        // calendar reminders, if yes, we will take the default calendar reminders
        // from the calendar data attribute otherwise, we will just use the event configured reminders
        if ($event->getReminders()->getUseDefault()) {
            // From the configured reminders, we will retrieve the min reminder
            // minutes to use in Concord as reminder_minutes_before
            $defaults = array_values($this->calendar->data['defaultReminders']);

            if (count($defaults) > 0) {
                return min($defaults);
            }
        }

        return collect($event->getReminders())->mapWithKeys(function ($reminder) {
            return [$reminder->getMethod() => $reminder->getMinutes()];
        })->values()->min();
    }

    /**
     * Remove event
     */
    protected function handleDeletedEvent(Event $event) : void
    {
        // NOTE: icaluid is not available on deleted events
        if ($activity = $this->activities->findBySyncEventId($event->getId())) {
            if ($activity->user->can('delete', $activity)) {
                try {
                    $this->activities->deleteWithoutRemovingFromCalendar($activity);
                } catch (ModelNotFoundException $e) {
                }
            } else {
                $this->activities->deleteSynchronization($activity, $event->getId(), $this->calendar->getKey());
            }
        }
    }

    /**
     * Update event in the calendar from the given activity
     */
    public function updateEvent(Activity $activity, string $eventId) : void
    {
        $this->handleRequestExceptions(function () use ($activity, $eventId) {
            Client::calendar()->events->update(
                $this->calendar->calendar_id,
                $eventId,
                $this->eventPayload($activity)
            );
        });
    }

    /**
     * Create event in the calendar from the given activity
     */
    public function createEvent(Activity $activity) : void
    {
        $this->handleRequestExceptions(function () use ($activity) {
            $event = Client::calendar()->events->insert(
                $this->calendar->calendar_id,
                $this->eventPayload($activity)
            );

            $this->activities->addSynchronization($activity, $event->getId(), $this->calendar->getKey(), [
                'icaluid' => $event->getICalUID(),
            ]);
        });
    }

    /**
     * Update event from the calendar for the given activity
     */
    public function deleteEvent(int $activityId, string $eventId) : void
    {
        $this->handleRequestExceptions(function () use ($eventId, $activityId) {
            try {
                Client::calendar()->events->delete($this->calendar->calendar_id, $eventId);
            } catch (GoogleException $e) {
                // https://developers.google.com/calendar/api/guides/errors#410_gone
                throw_if($e->getCode() !== 410, $e);
            }

            // We will check if the ModelNotFoundException is throw
            // It may happen if the deleteEvent is queued with closure via the
            // repository delete method the activity to be actual deleted,
            // in this case, we won't need to clear the synchronizations as they are already cleared
            try {
                $activity = $this->activities->find($activityId);

                $this->activities->deleteSynchronization($activity, $eventId, $this->calendar->getKey());
            } catch (ModelNotFoundException $e) {
            }
        });
    }

    /**
     * Create event payload for create/update from the given activity
     */
    protected function eventPayload(Activity $activity) : Event
    {
        return new Event(GoogleEventPayload::make($activity, $this->calendar)->toArray());
    }

    /**
     * Check whether the given Google event is recurring event
     */
    protected function isRecurring(Event $event) : bool
    {
        return ! is_null($event->getRecurrence());
    }

    /**
     * Check whether the given Google event is all day event
     */
    protected function isAllDay(Event $event) : bool
    {
        return ! is_null($event->getStart()->date);
    }

    /**
     * Get the list event endpoint query string
     */
    protected function listQueryString(array $tokens, ?string $syncToken) : array
    {
        $timeMin = $this->calendar->startSyncFrom()->format(\DateTimeInterface::RFC3339);

        // Can't use params and $syncToken at the same time, as the params
        // are remembered in the $syncToken, we don't need them, only on the initial sync
        return array_merge($tokens, ! $syncToken ? [
            'showDeleted'  => true,
            'timeMin'      => $timeMin,
            'singleEvents' => false, // exclude the recurrence child
        ] : []);
    }

    /**
     * Parse the given date
     */
    protected function parseDatetime(object $googleDatetime) : Carbon
    {
        $rawDatetime = $googleDatetime->dateTime ?: $googleDatetime->date;

        return Carbon::parse($rawDatetime)->timezone(config('app.timezone'));
    }

    /**
     * Set account requires authentication to the calendar OAuth account
     */
    protected function setAccountRequiresAuth() : void
    {
        $this->accounts->setRequiresAuthentication((int) $this->calendar->access_token_id);
    }

    /**
     * Store the calendar default reminders
     */
    protected function storeDefaultReminders(Events $list) : void
    {
        $currentReminders = $this->calendar->data['defaultReminders'] ?? null;

        $defaultReminders = collect($list->getDefaultReminders())->mapWithKeys(function ($reminder) {
            return [$reminder->getMethod() => $reminder->getMinutes()];
        })->all();

        // We will check if the reminders are not yet set or they are actually changed
        // from the last time they were saved to save one query each minute
        if (is_null($currentReminders) || count(array_diff($defaultReminders, $currentReminders)) > 0) {
            Calendar::unguarded(function () use ($defaultReminders) {
                $updated = app(CalendarRepository::class)->update([
                    'data' => array_merge($this->calendar->data ?? [], ['defaultReminders' => $defaultReminders]),
                ], $this->calendar->getKey());

                $this->calendar->data = $updated->data;
            });
        }
    }

    /**
     * Make a request and catch common exceptions
     */
    protected function handleRequestExceptions(\Closure $callback) : void
    {
        Client::connectUsing($this->calendar->email);

        try {
            $callback();
        } catch (GoogleException $e) {
            if ($e->getCode() === 401) {
                $this->setAccountRequiresAuth();

                return;
            } elseif ($e->getCode() === 404) {
                return;
            }

            throw $e;
        }
    }

    /**
     * Subscribe for changes for the given synchronization
     */
    public function watch(Synchronization $synchronization) : void
    {
        try {
            $response = Client::connectUsing($this->calendar->email)
                ->calendar()
                ->events->watch(
                    $this->calendar->calendar_id,
                    $this->createSubscriptionInstance($synchronization)
                );

            $this->synchronizations->markAsWebhookSynchronizable(
                $response->getResourceId(),
                Carbon::createFromTimestampMs($response->getExpiration()),
                $synchronization
            );
        } catch (GoogleException) {
            // If we reach an error at this point, it is likely that
            // push notifications are not allowed for this resource
            // or the application URL is not accessible or with invalid SSL certificate.
            // Instead we will sync it manually at regular interval.
        }
    }

    /**
     * Unsubscribe from changes for the given synchronization
     */
    public function unwatch(Synchronization $synchronization) : void
    {
        // If resource_id is null then the synchronization
        // does not have an associated Google Channel and
        // therefore there is nothing to stop at this point.
        if (! $this->calendar->synchronization->isSynchronizingViaWebhook()) {
            return;
        }

        try {
            Client::connectUsing($this->calendar->email)
                ->calendar()
                ->channels
                ->stop($this->createSubscriptionInstance($synchronization));

            $this->synchronizations->unmarkAsWebhookSynchronizable($synchronization);
        } catch (GoogleException) {
        }
    }

    /**
     * Create new Google Channel subscription instance
     */
    protected function createSubscriptionInstance(Synchronization $synchronization) : Channel
    {
        $channel = new Channel;
        $channel->setId($synchronization->id);
        $channel->setResourceId($synchronization->resource_id);
        $channel->setType('web_hook');
        $channel->setAddress($this->webHookUrl);

        return $channel;
    }
}
