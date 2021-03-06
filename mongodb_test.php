<?php
$ip = '133.88.121.124';
// DBへ接続
$mongo = new Mongo($ip.":27017");

// データベースを指定
$db = $mongo->selectDB("test");

// コレクションを指定1
$col = $db->selectCollection("test1");
$fs = new MongoGridFS($db);

exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');

$outKey = $fs->put('out.wav');
$doc = array(
        'name' => 'car',
        'date' => date('Y-m-d H:i:s'),
        'key' => $outKey
);
$col->insert($doc);

// コレクションのドキュメントを全件取得
$cursor = $col->find();

// 表示
foreach ($cursor as $id =>$obj) {
	var_dump($obj);
}