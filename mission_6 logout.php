<?php

session_start();



//ログインしているかどうか

if (isset($_SESSION["name"])) {

	$errorMessage = "ログアウトしました";

} else {

	$errorMessage = "タイムアウトしました";

}



//セッション変数のクリア

$_SESSION = array();



//セッションクリア

session_destroy();

?>
<html lang="ja">
<font color="red">
<?php
header('Content-Type: text/html; charset=UTF-8');
 echo htmlspecialchars($errorMessage, ENT_QUOTES);
 echo '<br>';
 echo '<a href="mission_6 login.php">ログイン</a>' 
?>
</font>
</html>