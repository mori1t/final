<?php
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;
$pdo = new PDO($dsn, $db[user], $db[pass]) ; 
$sql = "CREATE TABLE titest"
." ("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."username TEXT,"
."bukatu TEXT,"
."place TEXT,"
."file TEXT,"
."date DATETIME"
.");";
$stmt = $pdo -> query($sql) ; 
$abc = 'SELECT * FROM titest ORDER BY id ASC' ;
$results = $pdo -> query($abc) ;
foreach ($results as $row) {
	    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',' ;
    echo $row['username'].',' ;
    echo $row['bukatu'].',' ;
    echo $row['place'].',' ;
    echo $row['file'].',' ;
    echo $row['date']."<br>" ;
	} ;
?>