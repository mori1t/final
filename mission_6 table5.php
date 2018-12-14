<?php
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;
$pdo = new PDO($dsn, $db[user], $db[pass]) ; 
$sql = "CREATE TABLE tjtest"
." ("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."username TEXT,"
."otoshi TEXT,"
."place TEXT,"
."file TEXT,"
."date DATETIME"
.");";
$stmt = $pdo -> query($sql) ; 
$abc = 'SELECT * FROM tjtest ORDER BY id ASC' ;
$results = $pdo -> query($abc) ;
foreach ($results as $row) {
	    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',' ;
    echo $row['username'].',' ;
    echo $row['otoshi'].',' ;
    echo $row['place'].',' ;
    echo $row['file'].',' ;
    echo $row['date']."<br>" ;
	} ;
?>