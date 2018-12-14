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

                $sql = $pdo->prepare("INSERT INTO thtest(username, otoshi, place, date) VALUES (:username, :otoshi, :place, :date)");

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

                $sql = $pdo->prepare("INSERT INTO thtest(username, otoshi, place, file, date) VALUES (:username, :otoshi, :place, :file, :date)");

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
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>post1</title>
    <link rel="stylesheet" href="test.css">
    <!--- 各投稿のときは ../stylesheet.css --->
</head>
<body>

    <!-- HTMLタグについては以下のサイトを参照 -->
    <!-- http://www.htmq.com/html5/ -->

    <!--- ヘッダー部分 --->
    <div class="header">
        <div class="header-left">
            <h1>落とし物</h1>
        </div>
        <div class="header-right">
            <ul>
                <li><a href="mission_6 main.php">メインページへ戻る</a></li>
                <li><a href="mission_6 logout.php">ログアウト</a></li>
            </ul>
        </div>
    </div>
    <!--- ヘッダー終わり --->
<div class="main">
<?php
session_start();
echo '<form action="" method="post" enctype="multipart/form-data">
<p><input type="hidden" value="'.$_SESSION["name"].'" name="username"></p>
<h3>落とし物:  <input type="text" name="otoshi" required></h3>
<h3>場所:      <input type="text" name="place"></h3>
<h3>ファイル：<input type="file" name="upfile" size="30" /></h3>
  <br />
  <input type="submit" value="送信" name="upload" />
</form>'
?>
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
$dsn = "データベース名" ;
$db[user] = "ユーザー名" ;
$db[pass] = "パスワード" ;
$abc = 'SELECT * FROM thtest ORDER BY id DESC' ;
$results = $pdo -> query($abc) ;
	foreach ($results as $row) {

		if($row['file']==NULL){
			echo '<table>
					<tbody>
                <tr>
                    <td rowspan="2">'.$row["id"].'</td>
                    <td rowspan="2">'.$row["username"].'</td>
                    <td>'.$row["otoshi"].'</td>
                    <td rowspan="2">'.$row["date"].'</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>'.$row["place"].'</td>
                    <td></td>
                </tr>
			</tbody>
			</table>';
		}else{		
			$extensionCheck=explode(".",$row['file']);
			//ファイルの拡張子を調べる
			if($extensionCheck[1]==="mp4"){
				$filetype="video";
			}else{
				$filetype="image";
			}
			if($filetype==="video"){
				echo'<table>
            <tbody>
                <tr>
                    <td rowspan="2">'.$row["id"].'</td>
                    <td rowspan="2">'.$row["username"].'</td>
                    <td>'.$row["otoshi"].'</td>
                    <td rowspan="2">'.$row["date"].'</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>'.$row["place"].'</td>
                    <td></td>
                </tr>
			</tbody>
			<tbody>
				<tr>
					<td colspan="4"><video src="files/'.$row['file'].'" controls></video></td>
            </tbody>
        	</table>';
				
			}else{
 			echo '<table>
            <tbody>
                <tr>
                    <td rowspan="2">'.$row["id"].'</td>
                    <td rowspan="2">'.$row["username"].'</td>
                    <td>'.$row["otoshi"].'</td>
                    <td rowspan="2">'.$row["date"].'</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>'.$row["place"].'</td>
                    <td></td>
                </tr>
			</tbody>
			<tbody>
				<tr>
					<td colspan="4"><image src="files/'.$row['file'].'"></image></td>
            </tbody>
        	</table>';
			}
		}
	}		
?>
</div>

</body>
</html>
