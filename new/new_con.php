<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);
error_reporting(E_ALL);

$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;

if(isset($_SESSION['new'])){
  $name = $_SESSION['new']['name'];
  $email = $_SESSION['new']['email'];
}else{
  header('Location: new.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="new.css"/>
	<title>ToDolist</title>
</head>
<body>
<header>
	<div class="head">
		<h1>ToDolist</h1>
		<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>
<div class="main">
  <div class="title">
    <h1>仮登録内容確認</h1>
  </div>
  <div class="content">
    <form action="../phpmailer/send_test.php" method="post">
      <input type="hidden" name="token" value="<?php echo $token?>">
      <input type="hidden" name="name" value="<?php echo $name?>">
      <input type="hidden" name="email" value="<?php echo $email?>">
      <h2>名前</h2>
      <p><?php echo $name?></p>
      <h2>メールアドレス</h2>
      <p><?php echo $email?></p>
 
      <input class="btn" type="submit" value="送信する"/>
    </form>
    <button class="back_btn" onclick="location.href='new.php'">戻る</button>
  </div>
</div>
</body>
</html>
