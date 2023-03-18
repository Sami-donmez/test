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

use App\Models\Calendar;
use InvalidArgumentException;

class CalendarSyncManager
{
    /**
     * Create calendar synchronizer
     *
     * @param \App\Models\Calendar $calendar
     *
     * @return \App\Calendar\CalendarSynchronization&\App\Contracts\Calendarable\Synchronizable
     */
    public static function createClient(Calendar $calendar)
    {
        $method = 'create' . ucfirst($calendar->connection_type) . 'Driver';

        if (! method_exists(new static, $method)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve [%s] driver for [%s].',
                $method,
                static::class
            ));
        }

        return self::$method($calendar);
    }

    /**
     * Create the Google calendar sync driver
     *
     * @param \App\Models\Calendar $calendar
     *
     * @return \App\Calendar\GoogleCalendarSync
     */
    public static function createGoogleDriver(Calendar $calendar)
    {
        return new GoogleCalendarSync($calendar);
    }

    /**
     * Create the Outlook calendar sync driver
     *
     * @param \App\Models\Calendar $calendar
     *
     * @return \App\Calendar\OutlookCalendarSync
     */
    public static function createOutlookDriver(Calendar $calendar)
    {
        return new OutlookCalendarSync($calendar);
    }
}
