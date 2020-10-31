<?php 
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');

first();
$id = $_SESSION['id'];
// ユーザー情報
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
}
// リスト表示
if(!empty($user)){
  $lists = $db->prepare('SELECT * FROM reward WHERE user=? AND achieve=0 ORDER BY points ASC');
  $lists->bindParam(1,$user['id'],PDO::PARAM_INT);
  $lists->execute();
  $list = $lists->fetchAll();
  // 完了済みリスト
  $oklists = $db->prepare('SELECT * FROM reward WHERE user=? AND achieve=1 ORDER BY points ASC');
  $oklists->bindParam(1,$user['id'],PDO::PARAM_INT);
  $oklists->execute();
  $oklist = $oklists->fetchAll();
  $oknum = count($oklist);
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
  <link rel="stylesheet" href="main.css"/>
	<title>ToDoList</title>
</head>
<body>
<header>
  <div class="points">
    <p class="head_p"><i class="fas fa-coins"></i><?php echo $user['points'];?>p</p>
  </div>
  <h1>Todolist</h1>
  <div class="big">
    <a class="graph_icon" href="graph.php"><i class="fas fa-chart-line"></i></a>
    <p><a href="change.php">変更</a></p>
    <p class="head_log"><a href="logout.php">ログアウト</a></p>
  </div>
  <div class="mini">
    <a class="graph_icon" href="graph.php"><i class="fas fa-chart-line"></i></a>
    <a class="graph_icon" href="change.php"><i class="fas fa-exchange-alt"></i></a>
    <a class="graph_icon" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>

<div class="ran">
  <a class="" href="daily.php">日課</a>
  <a class="" href="main.php">ToDoリスト</a>
  <a class="active" href="reward.php">ご褒美</a>
</div>

<div class="addreward">
  <form action="reward_add.php" method="post">
    <input type="hidden" name="token" value="<?php echo $token;?>">
    <input class="addpoint" type="number" name="point" placeholder="point">
    <input class="addtitle" type="text" name="title" placeholder="NewReward">
    <button class="plusbtn" type="submit"><i class="fas fa-plus"></i></button>
  </form>
</div>

<div class="lists">
  <hr>
  <?php foreach($list as $lis):?>
    <?php if($lis['points'] < $user['points']):?>
    <div class="list">
      <p class="point"><i class="fas fa-coins"></i><?php echo $lis['points'];?></p>
      <a class="title" href="check_on.php?id=<?php echo $lis['id']?>" onClick="return GetReward();"><?php echo $lis['title'];?></a>
      <a class="edit" href="edit.php?id=<?php echo $lis['id']?>"><i class="fas fa-edit"></i></a>
      <a class="trash" href="delete.php?id=<?php echo $lis['id']?>" onClick="return delete_alert();"><i class="fas fa-trash-alt"></i></a>
    </div>
    <hr>
    <?php endif;?>
  <?php endforeach;?>
 
  <p class="nopoint">ポイント不足</p>
  <hr>
  <?php foreach($list as $lis):?>
    <?php if($lis['points'] >= $user['points']):?>
      <div class="list">
        <p class="point"><i class="fas fa-coins"></i><?php echo $lis['points'];?></p>
        <p class="title"><?php echo $lis['title'];?></p>
        <a class="edit" href="edit.php?id=<?php echo $lis['id']?>"><i class="fas fa-edit"></i></a>
        <a class="trash" href="delete.php?id=<?php echo $lis['id']?>" onClick="return delete_alert();"><i class="fas fa-trash-alt"></i></a>
      </div>
      <hr>
    <?php endif;?>
  <?php endforeach;?>

  <p class="openlists" onClick="listToggle();">獲得済みご褒美(<?php echo $oknum?>件) <i class="fas fa-chevron-down"></i></p>
  <hr>
  <div class="ok_lists" id="open_lists">
    <?php foreach($oklist as $ok):?>
      <div class="oklist">
        <p class="point"><i class="fas fa-coins"></i><?php echo $ok['points'];?></p>
        <p class="title"><?php echo $ok['title'];?></p>
        <a href="delete.php?id=<?php echo $ok['id']?>" class="trash" onClick="return delete_alert();"><i class="fas fa-trash-alt"></i></a>
      </div>
      <hr>
    <?php endforeach;?>
  </div>
</div>
<script src="main.js"></script>
</body>
</html>
