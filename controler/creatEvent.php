<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 09/05/2017
 * Time: 10:44 PM
 */

$service = new Google_Service_Calendar($client);

$event = new Google_Service_Calendar_Event(array(
    'summary' => 'Google I/O 2015',
    'location' => '800 Howard St., San Francisco, CA 94103',
    'description' => 'A chance to hear more about Google\'s developer products.',
    'start' => array(
        'dateTime' => '2015-05-28T09:00:00-07:00',
        'timeZone' => 'Europe/London',
    ),
    'end' => array(
        'dateTime' => '2015-05-28T17:00:00-07:00',
        'timeZone' => 'Europe/London',
    ),
    'reminders' => array(
        'useDefault' => FALSE,
        'overrides' => array(
            array('method' => 'popup', 'minutes' => 24 * 60),
            array('method' => 'popup', 'minutes' => 60),
        ),
    ),
));

$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);