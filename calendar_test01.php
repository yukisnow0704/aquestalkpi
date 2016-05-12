<?php

define('CALENDAR_ID', 'harbtdevp52dfi4ef4khbpoqd0@group.calendar.google.com');
define('API_KEY','AIzaSyB_Ak2uHbz_C8clmDG2olzz_5oEAKCa8PA');
define('API_URL', 'https://www.googleapis.com/calendar/v3/calendars/'.CALENDAR_ID.'/events?key='.API_KEY.'&singleEvents=true');

$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
	),
    	"ssl" => array(
    		'verify_peer' => false,
    		'verify-peer_name' => false
    )
));

$t = mktime(0, 0, 0, 4, 1, 2016);
$t2 = mktime(0, 0, 0, 3, 31, 2017);

$params = array();
$params[] = 'orderBy=startTime';
$params[] = 'timeMin=' .urlencode(date('c', $t));
$params[] = 'timeMax=' .urlencode(date('c', $t2));

$url = API_URL.'&'.implode('&', $params);

$results = file_get_contents($url, false);#, $context);

$json = json_decode($results, true);

$plan_list = array();

print_r($json);

for ($i=0; $i < count($json['items']); $i++) {
	$plan_list[$i]['name'] = $json['items'][$i]['summary'];
	$plan_list[$i]['start_date'] = $json['items'][$i]['start']['dateTime'];
	$plan_list[$i]['end_date'] = $json['items'][$i]['end']['dateTime'];
	$plan_list[$i]['user_email'] = $json['items'][$i]['creator']['email'];
	$plan_list[$i]['user_name'] = $json['items'][$i]['creator']['displayName'];
}

print_r($plan_list);

for ($i=0; $i < count($plan_list); $i++) {
	$talkdata = '';
	$talkdata .= $plan_list[$i]['user_name'];
	$talkdata .= $plan_list[$i]['start_date'];
	$talkdata .= $plan_list[$i]['end_date'];
	if ($plan_list[$i]['name'] == '') {
		$talkdata .= '不明な用事';
	}
	else{
		$talkdata .= $plan_list
		[$i]['name'];
	}

	echo $talkdata;
	exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
}
