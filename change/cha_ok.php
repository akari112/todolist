<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);
first();
$id = $_SESSION['id'];
// ユーザー情報
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
}

if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
	unset($_SESSION['token']);

	if(!empty($_POST)){	
		if(!empty($_POST['email'])){
			$stmt = $db->prepare('UPDATE user SET email=? WHERE id=?');
			$stmt->execute(array(
				$_POST['email'],
				$id
			));
		}elseif(!empty($_POST['pass'])){
			$stmt = $db->prepare('UPDATE user SET pass=? WHERE id=?');
			$stmt->execute(array(
				$_POST['pass'],
				$id
			));
		}
	}
} else {
	header('Location: change.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="../main.css"/>
	<title>ToDoList</title>
</head>
<body>
<header>
  <div class="points">
    <p class="head_p"><i class="fas fa-coins"></i><?php echo $user['points'];?>p</p>
  </div>
  <h1>Todolist</h1>
  <div class="big">
    <a class="graph_icon" href="../graph.php"><i class="fas fa-chart-line"></i></a>
    <p><a href="change.php">変更</a></p>
    <p class="head_log"><a href="../logout.php">ログアウト</a></p>
  </div>
  <div class="mini">
    <a class="graph_icon" href="../graph.php"><i class="fas fa-chart-line"></i></a>
    <a class="graph_icon" href="change.php"><i class="fas fa-exchange-alt"></i></a>
    <a class="graph_icon" href="../logout.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>

<div class="ran">
  <a class="" href="../daily.php">日課</a>
  <a class="" href="../main.php">ToDoリスト</a>
  <a class="" href="../reward.php">ご褒美</a>
</div>

<div class="main">
  <h1 class="edi_title">会員情報変更</h1>

  <p class="cha_p">変更が完了しました</p>

  <button class="btn" onclick="location.href='../main.php'">戻る</button>
</div>
</body>
</html>
