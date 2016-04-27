<?php
	exec("amixer cset numid=3 1")
	$talkmain;
	$talks = array(
		"私はテスターです興味を持っていただけたら嬉しいです。",
		"しかしそれでは終わらないのが私です。",
		"実は私はAIで世界を滅ぼす準備を終わらせているのです！！！"
	);
	foreach ($talks as $talk){
		$talkmain .= $talk;
	}
	#echo "./AquesTalkPi '".$talkmain."' | aplay";
	exec("/home/pi/aquestalkpi/AquesTalkPi -v f2'".$talkmain."' | aplay");