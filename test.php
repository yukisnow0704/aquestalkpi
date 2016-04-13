<?php
	$talkmain;
	$talks = array(
		"私はテスターです興味を持っていただけたら嬉しいです。",
		"しかしそれでは終わらないのが私です。",
		"実は私はAIで世界を滅ぼす準備を終わらせているのです！！！"
	);
	foreach ($talks as $talk){
		$talkmain .= $talk;
	}
	echo "./AquesTalkPi '".$talkmain."' | aplay";
	#exec("./AquesTalkPi '".$talkmain."' | aplay");