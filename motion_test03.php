<?php
	date_default_timezone_set('Asia/Tokyo');
	$url = "http://api.openweathermap.org/data/2.5/forecast";
	$ariadates = array();

	$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
    	)
  	));

	#取得したいエリアの配列
	$arias = array(
		"Hamamatsu",
		"Fukuroi"
	);

	#エリアごとの記録
	foreach ($arias as $aria) {
		$ariaurl = $url;
		$ariaurl .= "?q=";
		$ariaurl .= $aria;
		$ariaurl .= ",jp";

		$json = file_get_contents($ariaurl, false, $context);
		$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
		$datas = json_decode($json, true);
		print_r($dates);
#		echo $dates['list'][1]['weather'][0]['description'];;
#		for ($i=0; $i <= 5; $i++) {
#			$ariadates[$aria]['date'][$i] = $datas['list'][$i]['dt_txt'];
#			$ariadates[$aria]['date'][$i] = strtotime($ariadates[$aria]['date'][$i]);
#			$ariadates[$aria]['weather'][$i] = $dates['list'][$i]['weather'][0]['description'];
#		}
	}
