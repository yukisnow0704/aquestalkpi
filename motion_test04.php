<?php
	$url = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=https://goo.gl/kceVjB";

	$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
    	)
  	));

	#エリアごとの記録
	$json = file_get_contents($url, false);#, $context);
	$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
	$dates = json_decode($json, true);
	print_r($dates);
