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

if(!empty($_SESSION['new']['email'])){
  $email = $_SESSION['new']['email'];
  unset($_SESSION['new']);
}elseif(!empty($_SESSION['new']['pass'])){
  $pass = $_SESSION['new']['pass'];
  unset($_SESSION['new']);
}else{
	header('Location: change.php');
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
  <div class="title">
    <h1>変更内容確認</h1>
  </div>
  <div class="content">

  <form action="cha_ok.php" method="post">
    <input type="hidden" name="token" value="<?php echo $token;?>">
    <input type="hidden" name="email" value="<?php echo $email;?>">
    <input type="hidden" name="pass" value="<?php echo $pass;?>">
    <input type="hidden" name="action" value="submit"/>

    <?php if(!empty($email)):?>
      <p><?php echo $email;?></p>
    <?php elseif(!empty($pass)):?>
      <p>*****</p>
    <?php endif;?>
    <input class="btn" type="submit" value="変更する"/>
  </form>
    <button class="b_btn" onclick="location.href='change.php'">戻る</button>  
  </div>
</div>
</body>
</html>
