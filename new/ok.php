<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);

if(!empty($_SESSION['token']) && !empty($_POST['token']) && $_SESSION['token'] === $_POST['token']){
	unset($_SESSION['token']);

	$name = $_POST['name'];
	$email = $_POST['email'];
	$pass = $_POST['pass'];

	$stmt = $db->prepare('INSERT INTO user SET name=?, email=?, pass=?, created=NOW()');
	$stmt->execute(array(
		$name,
		$email,
		$pass
	));
	unset($_SESSION['join']);
} else {
	header('Location: signup.php');
	exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="new.css"/>
	<title>ToDoList|会員登録</title>
</head>
<body>
<header>
	<div class="head">
		<h1>ToDoList</h1>
		<button class="" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>

<div class="main">
	<div class="title">
		<h1>新規アカウント登録完了</h1>
	</div>

	<div>
		<p>ご登録ありがとうございました。</p>
    <p>新規アカウント登録が完了しました</p>
    <button class="btn" onclick="location.href='../login.php'">ログインする</button>
	</div>

</div>
</body>
</html>
