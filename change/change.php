<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
date_default_timezone_set("Asia/Tokyo");
first();
$id = $_SESSION['id'];
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
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
  <a class="active" href="../main.php">ToDoリスト</a>
  <a class="" href="../reward.php">ご褒美</a>
</div>
<div class="cha">
  <div>
    <h1 class="edi_title">会員情報変更</h1>
    <p>変更したい項目を選択してください</p>
  </div>

   <hr>
    <p><a href="email.php">メールアドレス</a></p>
    <p><?php echo $user['email'];?></p>
   <hr>
    <p><a href="pass.php">パスワード</a></p>
    <p>＊＊＊＊＊</p>
   <hr>
  <button class="btn" onclick="location.href='../main.php'">戻る</button>
  <br><br>
</div>
</body>
</html>
