<?php

// DBへ接続
$mongo = new Mongo( "mongo://192.168.111.100:27017", array("replicaSet"=>true));

// データベースを指定
$db = $mongo->selectDB("sample1");

// コレクションを指定1
$col = $db->selectCollection("test1");

// コレクションのドキュメントを全件取得
$cursor = $col->find();

// 表示
foreach ($cursor as $id =>$obj) {
	var_dump($obj);
}