<?php
	$url = "http://api.openweathermap.org/data/2.5/weather";
	$ariadates = array();
	$apikey = "f62fb373bf870eb8651d978ad211a1a9";
	require "weather_list_array.php";
	$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "tcp://133.88.120.1:8585",
      		"request_fulluri" => TRUE,
    	)
  	));

	#取得したいエリアの配列
	$tmp_fp = fopen('tmp.txt', 'r');
	$tmp_name = fgets($tmp_fp);
	fclose($tmp_fp);
	$aria = $tmp_name;
	switch ($aria) {
		case '浜松':
			$aria = 'Hamamatsu';
			break;
		case '袋井':
			$aria = 'Fukuroi';
			break;
		case '清水':
			$aria = 'Shimizu';
			break;
		
		default:
			$aria = '場所不明';
			break;
	}

	#エリアごとの記録
	$ariaurl = $url;
	$ariaurl .= "?q=";
	$ariaurl .= $aria;
	$ariaurl .= ",jp&APPID=";
	$ariaurl .= $apikey;
	echo $ariaurl;

	$json = file_get_contents($ariaurl, false, $context);
	$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
	$datas = json_decode($json, true);

	$ariadates[$aria]['temp'] = $datas['main']['temp']-273.15;
	$ariadates[$aria]['weather'] = $datas['weather'][0]['id'];
	$ariadates[$aria]['humidity'] = $datas['main']['humidity'];
	$ariadates[$aria]['wind_speed'] = $datas['wind']['speed'];
	
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

		$talkdate .= "は、";
		$talkdate .= $weather_list[$ariadates[$aria]['weather']];
		$talkdate .= "です。現在の気温は、";
		$talkdate .= $ariadates[$aria]['temp'];
		$talkdate .= "度です。また、湿度、風速は、";
		$talkdate .= $ariadates[$aria]['humidity'];
		$talkdate .= "パーセント、秒速";
		$talkdate .= $ariadates[$aria]['wind_speed'];
		$talkdate .= "メートルとなっています。";
		
		if ($ariadates[$aria]['temp'] <= 13) {
			$talkdate .= "少し肌寒いですね。今日も頑張りましょう！！";
		};
		echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
