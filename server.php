<?php
$scriptInvokedFromCli =
    isset($_SERVER['argv'][0]) && $_SERVER['argv'][0] === 'server.php';

if($scriptInvokedFromCli) {
    $tmp = fopen("count.txt","w");
    fwrite($tmp, "0");
    fclose($tmp);

    $ip_address = "133.88.121.141";
    $port = getenv('PORT');
    if (empty($port)) {
        $port = "3000";
    }
    echo 'starting server on port '. $port . PHP_EOL;
    exec("php -S ". $ip_address . ":" . $port . " server.php");
} else {
    return routeRequest();
}

function routeRequest()
{
    $uri = $_SERVER["REQUEST_URI"];
    $tmp = fopen("count.txt", "r");
    $count = fgets($tmp);
    fclose($tmp);

    if ($uri == "/") {
        echo file_get_contents('./public/index.html');
    } elseif($uri == "/stop") {
        exec("php ./prog/stop.php");
        exec("php ./prog/center.php");
    } elseif($uri == "/jquery-3.1.1.min.js") {
        echo file_get_contents('./public/jquery-3.1.1.min.js');
    } elseif($uri == "/img/leftUp.png") {
        echo file_get_contents('./img/leftUp.png');
    } elseif($uri == "/img/up.png") {
        echo file_get_contents('./img/up.png');
    } elseif($uri == "/img/rightUp.png") {
        echo file_get_contents('./img/rightUp.png');
    } elseif($uri == "/img/leftDown.png") {
        echo file_get_contents('./img/leftDown.png');
    } elseif($uri == "/img/down.png") {
        echo file_get_contents('./img/down.png');
    } elseif($uri == "/img/rightDown.png") {
        echo file_get_contents('./img/rightDown.png');
    } elseif($uri == "/down") {
        exec("php ./prog/down.php");
    } elseif($uri == "/up") {
        exec("php ./prog/up.php");
    } elseif($uri == "/right") {
        exec("php ./prog/right.php");
    } elseif($uri == "/left") {
        exec("php ./prog/left.php");
    } elseif($uri == "/leftUp") {
        exec("php ./prog/left.php");
        sleep(0.2);
        exec("php ./prog/up.php");
    } elseif($uri == "/leftDown") {
        exec("php ./prog/left.php");
        sleep(0.2);
        exec("php ./prog/down.php");
    } elseif($uri == "/rightUp") {
        exec("php ./prog/right.php");
        sleep(0.2);
        exec("php ./prog/up.php");
    } elseif($uri == "/rightDown") {
        exec("php ./prog/right.php");
    } elseif($uri == "/left") {
        exec("php ./prog/left.php");
    } elseif($uri == "/leftUp") {
        exec("php ./prog/left.php");
        sleep(0.2);
        exec("php ./prog/up.php");
    } elseif($uri == "/leftDown") {
        exec("php ./prog/left.php");
        sleep(0.2);
        exec("php ./prog/down.php");
    } elseif($uri == "/rightUp") {
        exec("php ./prog/right.php");
        sleep(0.2);
        exec("php ./prog/up.php");
    } elseif($uri == "/rightDown") {
        exec("php ./prog/right.php");
        sleep(0.2);
        exec("php ./prog/down.php");
    } elseif($uri == "/say") {
        exec("aplay -D plughw:2,0 ./prog/see_here.wav");
    } elseif($uri == "/start") {
        $count += 1;
        if($count == 1){
            exec("/home/pi/aquestalkpi/AquesTalkPi アクセスが開始しました。 | aplay -D plughw:3,0");
            exec("php ./prog/on.php");
        } else {
            exec("/home/pi/aquestalkpi/AquesTalkPi ". $count ."名がアクセスしています。  | aplay -D plughw:3,0");
        }
        $tmp = fopen("count.txt","w");
        fwrite($tmp, $count);
        fclose($tmp);
    } elseif($uri == "/end") {
        $count -= 1;
        if($count == 0){
            exec("/home/pi/aquestalkpi/AquesTalkPi アクセスが終了しました。 | aplay -D plughw:3,0");
            exec("php ./prog/off.php");
        } else {
            exec("/home/pi/aquestalkpi/AquesTalkPi １名のアクセスが解除されました。 | aplay -D plughw:3,0");
        }
        $tmp = fopen("count.txt","w");
        fwrite($tmp, $count);
        fclose($tmp);
    } elseif (preg_match('/\/talk(\?.*)?/', $uri)) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $_POST['message'];
            $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $message . " | aplay -D plughw:2,0";
            exec($messagePath);
        }
    } else {
        return false;
    }
}
