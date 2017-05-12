<?php

/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 10:54 PM
 */
require_once (__DIR__ . '/../google-api-php-client-2.1.3/vendor/autoload.php');
date_default_timezone_set("Europe/London");
class Event
{
    function add($client,$job){
        $service = new Google_Service_Calendar($client);

        $statTime = date("c", strtotime($job['StartDate']." ".$job['StartTime']));
        $endTime =  date("c", strtotime($job['EndDate']." ".$job['EndTime']));

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $job['ShortDesc'],
            'location' => $job['Building'],
            'description' => "Dress Code:- ".$job['DressCode']."\nSupervisor:- ".$job['Supervisor']."\n".$job['Info'],
            'start' => array(
                'dateTime' => $statTime,
                'timeZone' => 'Europe/London',
            ),
            'end' => array(
                'dateTime' => $endTime,
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
        $service->events->insert($calendarId, $event);
    }
}