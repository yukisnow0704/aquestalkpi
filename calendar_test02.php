<?php

define('CALENDAR_ID', 'v697mv8894olu7p52nld967mbs@group.calendar.google.com');
define('API_KEY','AIzaSyCbjR--_-hHAhZOUUp6p_AeNWCrgQOMgvQ');
define('API_URL', 'https://www.googleapis.com/calendar/v3/calendars/'.CALENDAR_ID.'/events?key='.API_KEY.'&singleEvents=true');
$tmp_fp = fopen('tmp.txt', 'r');
$tmp_name = fgets($tmp_fp);

echo $tmp_name;

fclose($tmp_fp);

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

$t = date("c");
$t2 = date("c",strtotime("+1 day"));

$params = array();
$params[] = 'orderBy=startTime';
$params[] = 'timeMin=' .urlencode($t);
$params[] = 'timeMax=' .urlencode($t2);

$url = API_URL.'&'.implode('&', $params);
echo $url;

$results = file_get_contents($url, false);#, $context);

$json = json_decode($results, true);

$plan_list = array();

print_r($json);

for ($i=0; $i < count($json['items']); $i++) {
	$plan_list[$i]['name'] = $json['items'][$i]['summary'];
	$plan_list[$i]['start_date'] = strtotime($json['items'][$i]['start']['dateTime']);
	$plan_list[$i]['end_date'] = strtotime($json['items'][$i]['end']['dateTime']);
	$plan_list[$i]['user_email'] = $json['items'][$i]['creator']['email'];
	$plan_list[$i]['user_name'] = $json['items'][$i]['creator']['displayName'];
}

print_r($plan_list);

for ($i=0; $i < count($plan_list); $i++) {
		$talkdate = '';
		$talkdate .= $plan_list[$i]['user_name'];
		$talkdate .= 'さんは、';
		$talkdate .= date("d日H時", $plan_list[$i]['start_date']);
		$talkdate .= 'から';
		$talkdate .= date("H時", $plan_list[$i]['end_date']);
		$talkdate .= 'まで';
		if ($plan_list[$i]['name'] == '') {
			$talkdate .= '不明な用事';
		}
		else{
			$talkdate .= $plan_list[$i]['name'];
		}
		$talkdate .= '、だそうです。';
		echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
}

if (count($plan_list) == 0) {
	exec("/home/pi/aquestalkpi/AquesTalkPi '誰も予定はありません。' | aplay");
}
