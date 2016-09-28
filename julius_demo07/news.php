<?php
	$url = "http://howcollect.jp/list/index/category/928";
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

	$rss = file_get_contents($url, false);#, $context);
	$xml = simplexml_load_string($rss);
	$dates = get_object_vars($xml);

	$talkdate = "最新のニュースをお伝えします。";

	foreach ($dates['channel']->item as $item) {
		$talkdate .= $item->title;
		$talkdate .= "。";
	}

	$talkdate .= "以上です。";

	echo $talkdate;
	exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");