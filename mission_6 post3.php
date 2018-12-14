<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if(!isset($_SESSION["name"])){
header("Location: mission_6 login.php");
}
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;

try {

    $pdo = new PDO($dsn, $db[user], $db[pass]);
    if (!empty($_POST["upload"])) {

        try {

            //アップロードファイルの例外処理
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK: // OK
                case UPLOAD_ERR_NO_FILE: // ファイル未選択
                    break;
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                    throw new RuntimeException('その他のエラーが発生しました');
            }

            if ($_FILES['upfile']['error'] === 4) {

                $sql = $pdo->prepare("INSERT INTO tjtest(username, otoshi, place, date) VALUES (:username, :otoshi, :place, :date)");

                $username = $_POST["username"] ;
                $otoshi = $_POST["otoshi"] ;
                $datetime=date('Y/m/d H:i:s');

                $sql -> bindValue(':username', $username, PDO::PARAM_STR);
                $sql -> bindValue(':otoshi', $otoshi, PDO::PARAM_STR);
                $sql -> bindValue(':place', $_POST["place"], PDO::PARAM_STR);
                $sql -> bindValue(':date', $datetime, PDO::PARAM_STR);
                $sql->execute();

            } else {
                $mime = $_FILES["upfile"]["type"] ; //MIMEタイプを判定

                // 拡張子を決定
                switch ($mime) {
                    case "image/jpeg":
                        $extension = ".jpeg";
                        break;
                    case "image/png":
                        $extension = ".png";
                        break;
                    case "image/gif":
                        $extension = ".gif";
                        break;
                    case "video/mp4":
                        $extension = ".mp4";
                        break;
                    default:
                        throw new RuntimeException("非対応ファイルです");
                }

                $date = getdate(); //時刻を取得

                // バイナリデータと時刻を合わせてハッシュ化
                $hashname = hash("sha256", $rawData.$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"]);
                $filename = $hashname.$extension ;


                //ファイルを特定のフォルダへ移動
                if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $filename)) {

                } else {
                    $errorMessage = "ファイルをアップロードできません";
                }

                $sql = $pdo->prepare("INSERT INTO tjtest(username, otoshi, place, file, date) VALUES (:username, :otoshi, :place, :file, :date)");

                $username = $_POST["username"] ;
                $otoshi = $_POST["otoshi"] ;
                $file = $_POST["upfile"];
                $datetime=date('Y/m/d H:i:s');


                $sql -> bindValue(':username', $username, PDO::PARAM_STR);

                $sql -> bindValue(':otoshi', $otoshi, PDO::PARAM_STR);

                $sql -> bindValue(':place', $_POST["place"], PDO::PARAM_STR);

                $sql -> bindValue(':file', $filename, PDO::PARAM_STR);

                $sql -> bindValue(':date', $datetime, PDO::PARAM_STR);
                $sql->execute();
            }

        } catch (RuntimeException $e) {
        $errMessage = $e->getMessage();
        }

    }
} catch (PDOException $e) {
    $errMessage = $e->getMessage();
}
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>post</title>
</head>
<body>
<?php
session_start();
echo '<h2>＜学食＞</h2>
<form action="" method="post" enctype="multipart/form-data">
<p><input type="hidden" value="'.$_SESSION["name"].'" name="username"></p>
<p>メニュー名: <input type="text" name="otoshi" required></p>
<p>評価: <input type="text" name="place"></p>
  ファイル：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />
  <input type="submit" value="送信" name="upload" />
</form>'
?>
</body>
<div><font color="red">
<?php
echo htmlspecialchars($errorMessage, ENT_QUOTES);
?>
</font></div>
<div><font color="blue">
<?php
 echo htmlspecialchars($uploadMessage, ENT_QUOTES);
?>
</font></div>
<?php
echo sprintf($format, $filename);
?>
<?php
echo '<a href="mission_6 main.php">メインページへ戻る</a></br>';
?>
<?php
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;
$abc = 'SELECT * FROM tjtest ORDER BY id DESC' ;
$results = $pdo -> query($abc) ;
	foreach ($results as $row) {
		if($row['file']==NULL){
			echo $row['id'].',' ;
			echo $row['username'].',' ;
			echo $row['otoshi'].',' ;
			echo $row['place'].',' ;	
			echo $row['date']."<br>" ;
		}else{
			$extensionCheck=explode(".",$row['file']);
		//ファイルの拡張子を調べる
			if($extensionCheck[1]==="mp4"){
				$filetype="video";
			}else{
				$filetype="image";
			}
			if($filetype==="video"){
				echo $row['id'].',' ;
				echo $row['username'].',' ;
				echo $row['otoshi'].',' ;
				echo $row['place'].',' ;
				echo'<video src="files/'.$row['file'].'" controls></video>';
				echo $row['date']."<br>" ;
			}else{
				echo $row['id'].',' ;
				echo $row['username'].',' ;
				echo $row['otoshi'].',' ;
				echo $row['place'].',' ;
				echo'<image src="files/'.$row['file'].'"></image>';
				echo $row['date']."<br>" ;
			}
		}
	}		
?>
</html>
