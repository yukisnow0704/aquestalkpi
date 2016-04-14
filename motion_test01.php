<?php
	exec("amixer cset numid=3 1")
	$talkmain = "侵入者です！うーうーうー";
	#echo "./AquesTalkPi '".$talkmain."' | aplay";
	exec("./AquesTalkPi '".$talkmain."' | aplay");