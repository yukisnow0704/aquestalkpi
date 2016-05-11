<?php
	$url = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=";
	$url .= "https://goo.gl/6SdYiQ";
	# https://goo.gl/o2IFFc テクノロジー関係

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

  	$news = array();

	$json = file_get_contents($url, false, $context);
	$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
	$dates = json_decode($json, true);

	#print_r($dates);
	$talkdate = "最新のニュースをお伝えします。";

	for ($i=0; $i <= 3; $i++) {
		$news[$i] = $dates["responseData"]["feed"]["entries"][$i]["title"];
		$talkdate .= $news[$i];
		$talkdate .= "。";
	}

	$talkdate .= "以上です。";

	echo $talkdate;
	exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");