<?php
	$url = "http://api.openweathermap.org/data/2.5/weather";
	$ariadates = array();

	$context = stream_context_create(
  	array(
    	"http" => array(
      		"proxy" => "http://133.88.120.1:8585",
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

		$json = file_get_contents($ariaurl,$context);
		$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
		$datas = json_decode($json, true);

		$ariadates[$aria]['temp'] = $datas['main']['temp']-273.15;
		$ariadates[$aria]['temp_max'] = $datas['main']['temp_max']-273.15;
		$ariadates[$aria]['temp_min'] = $datas['main']['temp_min']-273.15;
		$ariadates[$aria]['weather'] = $datas['weather'][0]['main'];
		$ariadates[$aria]['humidity'] = $datas['main']['humidity'];
		$ariadates[$aria]['wind_speed'] = $datas['wind']['speed'];
		print_r($ariadates);
	}

	#exec("amixer cset numid=3 1");
	
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
		$talkdate .= $ariadates[$aria]['weather'];
		$talkdate .= "です。現在の気温は、";
		$talkdate .= $ariadates[$aria]['temp'];
		$talkdate .= "です。最高気温、最低気温は、";
		$talkdate .= $ariadates[$aria]['temp_max'];
		$talkdate .= "度、";
		$talkdate .= $ariadates[$aria]['temp_min'];
		$talkdate .= "度です。また、湿度、風速は、";
		$talkdate .= $ariadates[$aria]['humidity'];
		$talkdate .= "パーセント、秒速";
		$talkdate .= $ariadates[$aria]['wind_speed'];
		$talkdate .= "となっています。";
		
		if ($ariadates[$aria]['temp'] <= 13) {
			$talkdate .= "少し肌寒いですね。今日も頑張りましょう！！";
		};
		#echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
	}