<?php
	date_default_timezone_set('Asia/Tokyo');
	$url = "http://api.openweathermap.org/data/2.5/forecast";
	require "weather_list_array.php";
	$ariadates = array();

	$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
    	)
  	));
	#time_nunber
	#9h = 0, 12h = 1, 15h = 3, 18h = 4, 21h = 5
	$time = 3;

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

		$json = file_get_contents($ariaurl, false,$context);
		$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
		$dates = json_decode($json, true);
		echo $dates['list'][1]['weather'][0]['description'];;
		for ($i=0; $i <= 5; $i++) {
			$ariadates[$aria]['date'][$i] = $dates['list'][$i]['dt_txt'];
			$ariadates[$aria]['date'][$i] = strtotime($ariadates[$aria]['date'][$i]);
			$ariadates[$aria]['weather'][$i] = $dates['list'][$i]['weather'][0]['id'];
		}
		print_r($ariadates);
	}

	exec("amixer cset numid=3 1");
	$talkdate = "続いて天気予報をお伝えします。";
	exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
	
	foreach ($arias as $aria) {
		$talkdate = "";
		switch ($aria) {
			case 'Hamamatsu':
				$talkdate = '浜松';
				break;
			case 'Fukuroi':
				$talkdate = '袋井';
				break;
			case 'Shimizu':
				$talkdate = '清水';
			break;
			
			default:
				$aria = '場所不明';
				break;
		}
		$talkdate .= "の天気は、";
		$talkdate .= date("H時", $ariadates[$aria]['date'][$time]);
		$talkdate .= "から";
		$talkdate .= $weather_list[$ariadates[$aria]['weather'][$time]];
		$talkdate .= "みたいです。";
		
		echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
		exec("php /home/pi/source/aquestalkpi/motion_test03");
	}