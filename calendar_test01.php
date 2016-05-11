<?php

define('CALENDAR_ID', 'harbtdevp52dfi4ef4khbpoqd0@group.calendar.google.com');
define('API_KEY','AIzaSyB_Ak2uHbz_C8clmDG2olzz_5oEAKCa8PA');
define('API_URL', 'https://www.googleapis.com/calendar/v3/calendars/'.CALENDAR_ID.'/events?key='.API_KEY.'&singleEvents=true');

$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
	)
));

$t = mktime(0, 0, 0, 4, 1, 2016);
$t2 = mktime(0, 0, 0, 3, 31, 2017);

$params = array();
$params[] = 'orderBy=startTime';
$params[] = 'timeMin=' .urlencode(date('c', $t));
$params[] = 'timeMax=' .urlencode(date('c', $t2));

$url = API_URL.'&'.implode('&', $params);

$results = file_get_contents($url, false, $context);

$json = json_decode($results, true);

var_dump($json);