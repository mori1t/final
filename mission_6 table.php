<?php
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;
$pdo = new PDO($dsn, $db[user], $db[pass]) ; 
$sql = "CREATE TABLE tdtest(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username TEXT, password TEXT, password2 TEXT)";
$stmt = $pdo -> query($sql) ; 
$abc = 'SELECT * FROM tdtest ORDER BY id ASC' ;
	$results = $pdo -> query($abc) ;
	foreach ($results as $row) {
	    //$rowの中にはテーブルのカラム名が入る
	    echo $row['id'].',' ;
	    echo $row['username'].',' ;
	    echo $row['password'].',' ;
	    echo $row['password2']."<br>" ;
	} ;
?>