<?php

// DBへ接続
$mongo = new Mongo();

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