<?php

$dsn = "データベース名" ;

$db[user] = "ユーザー名" ;

$db[pass] = "パスワード" ;



// エラーメッセージ、登録完了メッセージの初期化

$errorMessage = "";

$signupMessage = "";



if (!empty($_POST["signup"])) { // 登録ボタンが押された場合

	//空欄チェック

    if (empty($_POST["username"])) { //ユーザーネームが空

		$errorMessage = 'ユーザーネームを入力してください';

    } else if (empty($_POST["password"])) { //パスワードが空

  		$errorMessage = 'パスワードを入力してください';

    } else if (empty($_POST["password2"])) { //確認用パスワードが空

        $errorMessage = '確認用パスワードを入力してください';

    }



    if (!empty($_POST["username"]) and !empty($_POST["password"]) and !empty($_POST["password2"]) and $_POST["password"] === $_POST["password2"]) {

    	// 入力したユーザーネーム、パスワードを格納

    	$username = $_POST["username"];

    	$password = $_POST["password"];



    	// エラー処理

    	try {

        	 $pdo = new PDO($dsn, $db[user], $db[pass]);



	         //ユーザーネームの重複を確認

	         //$stmt に同じユーザー名をもつレコードの件数を取得

	         $stmt = "SELECT count(*) FROM tdtest WHERE username = '$username'";



	         //fetchColum() で件数を取り出している

	   	 $count = (int)$pdo->query($stmt)->fetchColumn();



	   	 if ($count > 0) {

			 $errorMessage = 'そのユーザー名は既に使用されています';

	   	 } else {

	       	         $sql = $pdo->prepare("INSERT INTO tdtest(username, password, password2) VALUES (:username, :password, :password2)");

	   		 $sql -> bindValue(':username', $username, PDO::PARAM_STR);

	   		 $sql -> bindValue(':password', $password, PDO::PARAM_STR);

             $sql -> bindValue(':password2', $password, PDO::PARAM_STR);

	       		 $sql->execute();

	       		 $signupMessage = '登録が完了しました！';

	         }

    	 } catch (PDOException $e) { //データベースに接続できなかったとき

        	$errorMessage = 'データベースエラー';

        	// $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）

        	// echo $e->getMessage();

    	  }

    } else if($_POST["password"] != $_POST["password2"]) {

		$errorMessage = 'パスワードが一致しません';

    }

}

?>
<html lang="ja">
<?php
header('Content-Type: text/html; charset=UTF-8');
echo '<form action="mission_6 sign up.php" method="post">
<p>ユーザー名: <input type="text" name="username" required></p>
<p>パスワード: <input type="password" name="password" required></p>
<p>確認用パスワード: <input type="password" name="password2" required></p>
<p><input type="submit" value="新規登録" name="signup"></p>
</form>
<a href="mission_6 login.php">ログイン</a>'
?>
<font color="red">
<?php 
echo htmlspecialchars($errorMessage, ENT_QUOTES);
?>
</font>
<font color="blue">
<?php 
echo htmlspecialchars($signupMessage, ENT_QUOTES);
?>
</font>

</html>
 