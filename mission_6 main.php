<html lang="ja">
<head>
  <meta charset=“UFT-8”>
  <title>CSS</title>
  <style>h1{color:blue};</style>
  <style>h1{
background-color:#ddd;
width:500px;
height:80px;};</style>
</head>
<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if(!isset($_SESSION["name"])){
header("Location: mission_6 login.php");
}
echo '<h1>○○大学投稿サイト</h1>';
echo '<br>';
echo '<h2><カテゴリー></h2></br>';
echo '<a href="mission_6 post.php">落とし物</a>';
echo '<br>';
echo '<a href="mission_6 post2.php">部活動、サークルイベント告知</a>';
echo '<br>';
echo '<a href="mission_6 post3.php">学食</a>';
echo '<br>';
echo '    ';
echo '<br>';
echo '<a href="mission_6 logout.php">ログアウト</a></br>';
session_start();
echo '<h3>ようこそ ', $_SESSION["name"], ' さん。</h3>';
?>
</html>