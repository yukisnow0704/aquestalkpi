<?php
	$talkdate = "長澤くんは、";
	exec("/home/pi/aquestalkpi/AquesTalkPi -s 300 '".$talkdate."' | aplay");
	for ($i=0; $i < 100 ; $i++) { 
		$talkdate = "デブ、";
		exec("/home/pi/aquestalkpi/AquesTalkPi -s 300 '".$talkdate."' | aplay");
	}
