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
		"Fukuroi",
		"Shimizu"
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

		$ariadates[$aria]['date'] = $datas['list'][0]['dt'];
		echo date("Y-m-d H:i:s", $ariadates[$aria]['date']);
	}

	#exec("amixer cset numid=3 1");
	
#	foreach ($arias as $aria) {
#		$talkdate = "";
#		switch ($aria) {
#			case 'Hamamatsu':
#				$talkdate = '浜松';
#				break;
#			case 'Fukuroi':
#				$talkdate = '袋井';
#				break;
#			case 'Shimizu':
#				$talkdate = '清水';
#				break;
#			
#			default:
#				$aria = '場所不明';
#				break;
#		}

#		$talkdate .= $ariadates[$aria]['weather'];
#		$talkdate .= "です。現在の気温は、";
#		$talkdate .= $ariadates[$aria]['temp'];
#		$talkdate .= "度です。また、湿度、風速は、";
#		$talkdate .= $ariadates[$aria]['humidity'];
#		$talkdate .= "パーセント、秒速";
#		$talkdate .= $ariadates[$aria]['wind_speed'];
#		$talkdate .= "メートルとなっています。";
		
#		if ($ariadates[$aria]['temp'] <= 13) {
		#echo $talkdate;
#		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
#		exec("php /home/pi/source/aquestalkpi/motion_test03");
#	}