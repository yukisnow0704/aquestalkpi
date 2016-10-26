<?php

// DBへ接続
$mongo = new Mongo("192.168.1.58:27017");

// データベースを指定
$db = $mongo->selectDB("sample1");

// コレクションを指定1
$col = $db->selectCollection("test1");

exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');

$out = file_get_contents('out.wav');
$doc = array(
        'name' => 'car',
        'date' => date(),
        'sound' => $out,
);

$col->insert($doc);

// コレクションのドキュメントを全件取得
$cursor = $col->find();

// 表示
foreach ($cursor as $id =>$obj) {
	var_dump($obj);
}