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
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Synchronization;
use Microsoft\Graph\Model\Event;
use App\Contracts\Synchronizable;
use Illuminate\Support\Facades\URL;
use App\Events\CalendarSyncFinished;
use Microsoft\Graph\Model\Subscription;
use App\Contracts\SynchronizesViaWebhook;
use GuzzleHttp\Exception\ClientException;
use App\Innoclapps\Facades\Microsoft as Api;
use Microsoft\Graph\Model\Event as EventModel;
use App\Contracts\Repositories\ActivityRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contracts\Repositories\SynchronizationRepository;
use App\Calendar\Exceptions\InvalidNotificationURLException;
use App\Innoclapps\Contracts\Repositories\OAuthAccountRepository;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OutlookCalendarSync extends CalendarSynchronization implements Synchronizable, SynchronizesViaWebhook
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
        $this->webHookUrl       = URL::asAppUrl('webhook/outlook-calendar');
        $this->synchronizations = resolve(SynchronizationRepository::class);
        $this->accounts         = resolve(OAuthAccountRepository::class);
        $this->activities       = resolve(ActivityRepository::class);
    }

    /**
     * Synchronize the data for the given synchronization
     */
    public function synchronize(Synchronization $synchronization) : void
    {
        Api::connectUsing($this->calendar->email);

        try {
            $iterator = Api::immutable(
                fn () => Api::createCollectionGetRequest($this->createEndpoint())->setReturnType(EventModel::class)
            );

            $events = Api::iterateCollectionRequest($iterator);

            $changesPerformed = $this->processChangedEvents($events ?? []);

            $this->synchronizations->updateLastSyncDate($synchronization->getKey());

            CalendarSyncFinished::dispatchIf($changesPerformed, $synchronization->synchronizable);
        } catch (IdentityProviderException) {
            $this->accounts->setRequiresAuthentication((int) $this->calendar->access_token_id);
        }
    }

    /**
     * Iterage over the changed events
     *
     * @param \Microsoft\Graph\Model\Event[] $events
     */
    protected function processChangedEvents(array $events) : bool
    {
        foreach ($events as $event) {
            list($model, $guestsUpdated) = $this->processViaChange(
                $this->attributesFromEvent($event),
                $this->determineUser($event->getOrganizer()?->getEmailAddress()?->getAddress(), $this->calendar->user),
                $event->getId(),
                $event->getICalUId(),
                $this->calendar
            );

            if ($model->wasRecentlyCreated || $model->wasChanged() || $guestsUpdated) {
                $changesPerformed = true;
            }
        }

        return $changesPerformed ?? false;
    }

    /**
     * Create attributes from event
     */
    protected function attributesFromEvent(Event $event) : array
    {
        $dueDate  = Carbon::parse($event->getStart()->getDateTime());
        $endDate  = Carbon::parse($event->getEnd()->getDateTime());
        $isAllDay = $event->getIsAllDay();

        return [
            'title'                   => $event->getSubject() ?? '(No Title)',
            'description'             => $event->getBody()->getContent(),
            'due_date'                => $dueDate->format('Y-m-d'),
            'due_time'                => ! $isAllDay ? $dueDate->format('H:i') . ':00' : null,
            'end_date'                => ($isAllDay ? $endDate->sub(1, 'second') : $endDate)->format('Y-m-d'),
            'end_time'                => ! $isAllDay ? $endDate->format('H:i') . ':00' : null,
            'reminder_minutes_before' => $event->getReminderMinutesBeforeStart(),
            'guests'                  => collect($event->getAttendees())->map(function ($attendee) {
                return [
                    'email' => $attendee['emailAddress']['address'],
                    'name'  => $attendee['emailAddress']['name'],
                ];
            })->all(),
        ];
    }

    /**
     * Subscribe for changes for the given synchronization
     */
    public function watch(Synchronization $synchronization) : void
    {
        $this->handleRequestExceptions(function () use ($synchronization) {
            try {
                $subscription = $this->createSubscriptionInstance($synchronization)->getProperties();

                $subscription = Api::immutable(
                    fn () => Api::createPostRequest('/subscriptions', $subscription)->setReturnType(Subscription::class)->execute()
                );

                $this->synchronizations->markAsWebhookSynchronizable(
                    $subscription->getId(),
                    $subscription->getExpirationDateTime(),
                    $synchronization
                );
            } catch (ClientException $e) {
                // We will throw an exceptions for invalid URL and won't allow the
                // user to sync without valid URL as the Outlook synchronization works only
                // with webhooks and cannot use the polling method as we cannot detect deleted events when polling
                if ($this->isInvalidExceptionUrlMessage($e->getMessage())) {
                    throw new InvalidNotificationURLException;
                }

                throw $e;
            }
        });
    }

    /**
     * Unsubscribe from changes for the given synchronization
     */
    public function unwatch(Synchronization $synchronization) : void
    {
        // perhaps subscription for some reason not created? e.q. for notificationUrl validation failed
        if ($resourceId = $synchronization->resource_id) {
            $this->handleRequestExceptions(function () use ($synchronization, $resourceId) {
                Api::immutable(
                    fn () => Api::createDeleteRequest('/subscriptions/' . $resourceId)->execute()
                );

                $this->synchronizations->unmarkAsWebhookSynchronizable($synchronization);
            });
        }
    }

    /**
     * Update event in the calendar from the given activity
     */
    public function updateEvent(Activity $activity, string $eventId) : void
    {
        $this->handleRequestExceptions(function () use ($activity, $eventId) {
            $endpoint = $this->endpoint('/' . $eventId);
            $payload = OutlookEventPayload::make($activity);

            Api::immutable(
                fn () => Api::createPatchRequest($endpoint, $payload)->execute()
            );
        });
    }

    /**
     * Create event in the calendar from the given activity
     */
    public function createEvent(Activity $activity) : void
    {
        $this->handleRequestExceptions(function () use ($activity) {
            $endpoint = $this->endpoint();
            $payload = new OutlookEventPayload($activity);

            $event = Api::immutable(
                fn () => Api::createPostRequest($endpoint, $payload)->setReturnType(EventModel::class)->execute()
            );

            $this->activities->addSynchronization($activity, $event->getId(), $this->calendar->getKey(), [
                'icaluid' => $event->getICalUId(),
            ]);
        });
    }

    /**
     * Update event from the calendar for the given activity
     */
    public function deleteEvent(int $activityId, string $eventId) : void
    {
        $this->handleRequestExceptions(function () use ($activityId, $eventId) {
            $endpoint = $this->endpoint('/' . $eventId);

            Api::immutable(fn () => Api::createDeleteRequest($endpoint)->execute());

            // We will check if the ModelNotFoundException is throw
            // It may happen if the deleteEvent is queued with closure via the
            // repository delete method the activity to be actuall deleted,
            // in this case, we won't need to clear the synchronizations as they are already cleared
            try {
                $activity = $this->activities->find($activityId);

                $this->activities->deleteSynchronization($activity, $eventId, $this->calendar->getKey());
            } catch (ModelNotFoundException $e) {
            }
        });
    }

    /**
     * Prepare the endpoint to retrieve the events
     */
    protected function createEndpoint() : string
    {
        $startFrom = new \DateTime($this->calendar->startSyncFrom());

        $endpoint = $this->endpoint();

        $endpoint .= '?$filter=createdDateTime ge ' . $startFrom->format('Y-m-d\TH:i:s\Z');
        $endpoint .= ' and type eq \'singleInstance\'';
        $endpoint .= ' and isDraft eq false';
        // There are times when I have a personal appointment during the work day that needs to be on my work calendar but not synced to Concord.
        // Having the ability to exclude calendar items marked as Private would solve this problem.
        $endpoint .= ' and sensitivity ne \'private\'';

        return $endpoint;
    }

    /**
     * Helper function to handle the requests common exception
     */
    protected function handleRequestExceptions(\Closure $callable) : void
    {
        Api::connectUsing($this->calendar->email);

        try {
            $callable();
        } catch (ClientException $e) {
            throw_if($e->getCode() !== 404, $e);
        } catch (IdentityProviderException $e) {
            $this->accounts->setRequiresAuthentication((int) $this->calendar->access_token_id);
        }
    }

    /**
     * Create new Microsoft Subscription instance
     */
    protected function createSubscriptionInstance(Synchronization $synchronization) : Subscription
    {
        return (new Subscription)->setChangeType('created,updated,deleted')
            ->setNotificationUrl($this->webHookUrl)
            ->setClientState($synchronization->id) // uuid;
            // https://docs.microsoft.com/en-us/graph/api/resources/subscription?view=graph-rest-1.0#maximum-length-of-subscription-per-resource-type
            ->setExpirationDateTime(now()->addDays(2))
            ->setResource($this->endpoint());
    }

    /**
     * Check whether the given exception message is invalid url
     */
    protected function isInvalidExceptionUrlMessage(string $message) : bool
    {
        return Str::of($message)->lower()->contains([
            'invalid notification url',
            'subscription validation request failed',
            '\'http\' is not supported',
            'the remote name could not be resolved',
        ]);
    }

    /**
     * Create endpoint for the calendar
     */
    protected function endpoint(string $glue = '') : string
    {
        return '/me/calendars/' . $this->calendar->calendar_id . '/events' . $glue;
    }
}
