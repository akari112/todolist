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

$error = array();

if(!empty($_POST)){
  $error = validation();

  if(empty($error)){
    if($user['pass'] !== sha1($_POST['pass'])){
      $error['pass']['miss'] = '*パスワードが間違っています';
    }elseif($_POST['npass1'] !== $_POST['npass2']){
      $error['pass']['not'] = '*パスワードが同じではありません';
    }
  }
  if(empty($error)){
    $pass = h($_POST['pass']);
    $_SESSION['new']['pass'] = sha1($pass);

    header('Location: cha_con.php');
		exit();
  }
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
  <h1>パスワード変更</h1>
  <form action="" method="post">
    <label class="lab1" for="pass">現在のパスワード</label>
    <br>
    <input class="inp" type="password" name="pass" id="pass" maxlength="20"><br>
    <?php if(!empty($error['pass']['miss'])):?>
        <p class="error"><?php echo $error['pass']['miss'];?></p>
      <?php endif;?>

    <label class="lab" for="password">パスワード(8文字以上)</label>
    <input class="inp" id="password" type="password" name="npass1" maxlength="20" value=""/><br>
      <label class="lab1" for="npass2">新しいパスワード(再入力)</label>
    <br>
    <input class="inp" type="password" name="npass2" id="password" maxlength="20"><br>
      <?php if(!empty($error['pass']['not'])):?>
        <p class="error"><?php echo $error['pass']['not'];?></p>
      <?php endif;?>
      <?php if(!empty($error['pass']['length'])):?>
        <p class="error">*<?php echo $error['pass']['length'];?></p>
      <?php endif;?>
      <?php if(!empty($error['pass']['blank'])):?>
        <p class="error">*<?php echo $error['pass']['blank'];?></p>
      <?php endif;?>

      <div><input class="btn" type="submit" value="確認する"/></div>
  </form>
  <button class="b_btn" onclick="location.href='change.php'">戻る</button>

</div>
</body>
</html>
