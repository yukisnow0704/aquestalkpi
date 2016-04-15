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

		for ($i=0; $i <= 5; $i++) {
			$ariadates[$aria]['date'][$i] = $datas['list'][$i]['dt_txt'];
			$ariadates[$aria]['date'][$i] = strtotime($ariadates[$aria]['date'][$i]);
			$ariadates[$aria]['weather'][$i] = $dates['list'][$i]['weather'][0]['description'];
		}
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
		$talkdate .= "の天気は、" 
		$talkdate .= date("H時", $ariadates[$aria]['date'][4]);
		$talkdate .= "から";
		switch ($ariadates[$aria]['weather'][4]) {
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
		if ($ariadates[$aria]['weather'][4] == 'thunderstrom' || $ariadates[$aria]['weather'][4] == 'rain' || $ariadates[$aria]['weather'][4] == 'shower rain') {
			$talkdate .= "どうやら、雨が降るみたいなので傘を忘れないようにしてくださいね";
		}
		
		echo $talkdate;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
		exec("php /home/pi/source/aquestalkpi/motion_test03");
	}