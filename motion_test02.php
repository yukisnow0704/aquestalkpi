<?php
	$url = "http://api.openweathermap.org/data/2.5/weather";
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

		$ariadates[$aria]['temp'] = $datas['main']['temp']-273.15;
		$ariadates[$aria]['weather'] = $datas['weather'][0]['description'];
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
		switch ($ariadates[$aria]['weather']) {
			case 'clear sky':
				$talkdate .= "快晴みたい";
				break;
			case 'few clouds':
				$talkdate .= "雲がかかるよう";
				break;
			case 'scattered clouds':
				$talkdate .= "曇りみたい";
				break;
			case 'broken clouds':
				$talkdate .= "雨雲がかかるよう";
				break;
			case 'shower rain':
				$talkdate .= "にわか雨みたい";
				break;
			case 'rain':
				$talkdate .= "雨みたい";
				break;
			case 'thunderstrom':
				$talkdate .= "雷雨みたい";
				break;
			case 'snow':
				$talkdate .= "雪が降ってるみたい";
				break;
			case 'mist':
				$talkdate .= "霧がかってるみたい";
				break;
			default:
				$talkdate .= "どうやらAPIが頭おかしいみたい";
				break;
		}
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
		#echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
		exec("php /home/pi/source/aquestalkpi/motion_test03");
	}