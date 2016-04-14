<?php
	$url = "http://api.openweathermap.org/data/2.5/weather";
	$ariadates = array();

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

		$json = file_get_contents($ariaurl);
		$json = mb_convert_encoding($json, 'utf8', 'ASCII,JIS,,UTF-8,EUC-JP,SJIS-WIN');
		$datas = json_decode($json, true);

		$ariadates[$aria]['temp'] = $datas['main']['temp']-273.15;
		$ariadates[$aria]['temp_max'] = $datas['main']['temp_max']-273.15;
		$ariadates[$aria]['temp_min'] = $datas['main']['temp_min']-273.15;
		$ariadates[$aria]['weather'] = $datas['weather'][0]['main'];
		$ariadates[$aria]['humidity'] = $datas['main']['humidity'];
		$ariadates[$aria]['wind_speed'] = $datas['wind']['speed'];
	}

	exec("amixer cset numid=3 1");
	
	foreach ($arias as $aria) {
		$talkdate = $aria;
		exec("/home/pi/aquestalkpi/AquesTalkPi '".$talkdate."' | aplay");
	};