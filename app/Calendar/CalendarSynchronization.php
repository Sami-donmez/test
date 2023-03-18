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

use App\Models\User;
use App\Models\Contact;
use App\Models\Activity;
use App\Models\Calendar;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Innoclapps\Facades\ChangeLogger;
use App\Innoclapps\Fields\User as UserField;
use App\Contracts\Repositories\UserRepository;
use App\Contracts\Repositories\ContactRepository;
use App\Contracts\Repositories\ActivityRepository;
use App\Innoclapps\Contracts\Repositories\OAuthAccountRepository;

class CalendarSynchronization
{
    /**
     * Create or update activity
     */
    protected function processViaChange(array $data, User $user, string|int $eventId, string $iCalUID, Calendar $calendar) : array
    {
        $repository = resolve(ActivityRepository::class);

        UserField::setAssigneer($user);

        // Will help as well executing the changes faster to prevent webhooks overlapping
        UserField::skipNotification();

        ChangeLogger::disable();

        $guests = Arr::pull($data, 'guests', []);

        $model = tap($this->getActivityInstanceFromEvent($eventId, $data, $user, $calendar))->save();

        $syncAttributes = ['icaluid' => $iCalUID];

        if ($model->wasRecentlyCreated) {
            $repository->addSynchronization($model, $eventId, $calendar->getKey(), $syncAttributes);
        } else {
            // in case of previously synced events without icaluid, perform update
            $repository->updateSynchronization($model, $eventId, $calendar->getKey(), $syncAttributes);
        }

        $guestsChanges = $this->saveGuests(
            $this->determineGuestsForSaving($guests, $user),
            $repository,
            $model
        );

        ChangeLogger::enable();

        return [$model, count(array_filter($guestsChanges)) !== 0];
    }

    /**
     * Get activity instance from event
     */
    protected function getActivityInstanceFromEvent(int|string $eventId, array $attributes, User $user, Calendar $calendar) : Activity
    {
        /** @var ActivityRepository */
        $repository = app(ActivityRepository::class);

        $instance = $repository->findBySyncEventId($eventId);

        if ($instance && $instance->trashed() && $repository->restoreWithoutCreatingOnCalendar($instance->getKey())) {
            $instance = $repository->find($instance->getKey());
        }

        $attributes = array_merge($attributes, ! $instance ? [
            'user_id'             => $user->getKey(),
            'created_by'          => $user->getKey(),
            'owner_assigned_date' => now(),
            'activity_type_id'    => $calendar->activity_type_id,
        ]  : []);

        return ($instance ?: new Activity)->forceFill($attributes);
    }

    /**
     * Persists the guests in storage
     */
    protected function saveGuests(Collection $guests, ActivityRepository $repository, Activity $activity) : array
    {
        $changes      = $repository->saveGuestsWithoutNotifications($activity, $guests->all());
        $associations = collect([]);

        // We will check if the activity is new, if yes, we will associate
        // the activity to all contacts that were added as attendee
        if ($activity->wasRecentlyCreated) {
            $associations = $associations->merge($guests->whereInstanceOf(Contact::class));
        } else {
            // If the activity is not new, we will only associate the newly guest contacts
            // we associate only the newly guest contacts because the user may have dissociated
            // any contacts from the activity after it was added and we should respect that not re-associate the contacts again
            $associations = $associations->merge(
                collect($changes['attached'])->whereInstanceOf(Contact::class)
            );
        }

        if ($associations->isNotEmpty()) {
            $repository->syncWithoutDetaching($activity->getKey(), 'contacts', $associations->pluck('id'));
        }

        return $changes;
    }

    /**
     * Determine the guests for saving when processing the changed events
     */
    protected function determineGuestsForSaving(array $guests, User $user) : Collection
    {
        $users    = resolve(UserRepository::class);
        $contacts = resolve(ContactRepository::class);

        return collect($guests)->reject(
            fn ($attendee) => empty($attendee['email'])
        )->map(function ($attendee) use ($users, $contacts, $user) {
            if ($guest = $users->findByEmail($attendee['email'])) {
                return $guest;
            }

            if ($guest = $contacts->findByEmail($attendee['email'])) {
                return $guest;
            }

            return Contact::unguarded(function () use ($attendee, $user, $contacts) {
                $firstName = $attendee['email'];

                if ($attendee['name']) {
                    $nameParts = explode(' ', $attendee['name']);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? null;
                }

                return tap($contacts->create([
                    'first_name' => $firstName,
                    'last_name'  => $lastName ?? null,
                    'email'      => $attendee['email'],
                    'created_by' => $user->getKey(),
                    'user_id'    => $user->getKey(),
                ]), function ($contact) use ($user) {
                    $this->addChangelogGuestCreatedAsContact($contact, $user);
                });
            });
        });
    }

    /**
     * Add changelog when a guest is created as contact
     */
    protected function addChangelogGuestCreatedAsContact(Contact $contact, User $user) : void
    {
        $properties = [
            'icon' => 'Calendar',
            'lang' => [
                'key'   => 'contact.timeline.imported_via_calendar_attendee',
                'attrs' => [
                    'user' => $user->name,
                ],
            ],
        ];

        ChangeLogger::forceLogging()
            ->useModelLog()
            ->on($contact)
            ->byAnonymous()
            ->generic()
            ->withProperties($properties)->log();
    }

    /**
     * Determine the activity user from the given email address
     */
    protected function determineUser(?string $email, ?User $default = null) : ?User
    {
        if (empty($email)) {
            return $default;
        }

        // We will get the activity user from the given email, the email should be the organizer/creator of the event
        return app(OAuthAccountRepository::class)->limit(1)
            ->findWhere(['email' => $email])
            ->first()?->user ?? $default;
    }
}
