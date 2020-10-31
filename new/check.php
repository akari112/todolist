<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);

if(isset($_SESSION['join'])){
	$name = $_SESSION['join']['name'];
	$email = $_SESSION['join']['email'];
	$pass = $_SESSION['join']['pass'];
}else{
	header('Location: signup.php');
	exit();
}
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;

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
    <h1>登録内容確認</h1>
  </div>
  <div class="content">

  <form action="ok.php" method="post">
    <input type="hidden" name="token" value="<?php echo $token;?>">
    <input type="hidden" name="name" value="<?php echo $name;?>">
    <input type="hidden" name="email" value="<?php echo $email;?>">
    <input type="hidden" name="pass" value="<?php echo $pass;?>">
    <input type="hidden" name="action" value="submit"/>
    <h4>ニックネーム</h4>
    <p><?php echo $name;?></p>
    <h4>メールアドレス</h4>
    <p><?php echo $email;?></p>
    <h4>パスワード</h4>
    <p>【表示されません】</p>    
    <input class="btn" name="con" type="submit" value="登録する"/>
  </form>
    <button class="back_btn" onclick="location.href='signup.php?action=rewrite'">書き直す</button>  
  </div>
</div>
</body>
</html>
