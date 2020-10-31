<?php 
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');
date_default_timezone_set("Asia/Tokyo");
first();
$id = $_SESSION['id'];
// ユーザー情報
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
}
// トークン確認、投稿DB登録
if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
  unset($_SESSION['token']);
  if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['point']) && !empty($_POST['point'])){
    if(!isset($_POST['deadline']) || empty($_POST['deadline'])){
      $deadline = NULL;
    }else{
      $deadline = h($_POST['deadline']);
    }
    if(!isset($_POST['detail']) || empty($_POST['detail'])){
      $detail = NULL;
    }else{
      $detail = h($_POST['detail']);
    }
    $title = h($_POST['title']);
    $point = h($_POST['point']);
    $priority = h($_POST['priority']);

    $lists = $db->prepare('INSERT INTO lists SET user=?, title=?, detail=?, achieve=0, point=?, priority=?, deadline=?, created=NOW()');
    $lists->execute(array(
      $id,
      $title,
      $detail,
      $point,
      $priority,
      $deadline
    ));
  }
}else {
  $notoken = '不正なアクセスです。';
}
// 毎日ポイントグラフ登録
$today = date('Y-m-d');
$month = date('m');
$graphs = $db->prepare('SELECT * FROM graph WHERE day=? AND user=?');
$graphs->execute(array($today,$id));
if ($graphs->fetch() === false) {
  $stmt = $db->prepare('INSERT INTO graph SET user=?, points=0, day=?, month=?');
  $stmt->execute(array(
    $id,
    $today,
    $month
  ));
}

// ソート機能
if(isset($_POST['sort']) && !empty($_POST['sort'])){
  if($_POST['sort'] == 1){
    $sort = 'priority';
  }elseif($_POST['sort'] == 2){
    $sort = 'deadline';
  }elseif($_POST['sort'] == 3){
    $sort = 'created';
  }elseif($_POST['sort'] == 4){
    $sort = 'point';
  }
}else{
  $sort = 'priority';
}

// リスト表示
if(!empty($user)){
  $lists = $db->prepare("SELECT * FROM lists WHERE user=? AND achieve=0 ORDER BY $sort ASC");
  $lists->bindParam(1,$user['id'],PDO::PARAM_INT);
  $lists->execute();
  $list = $lists->fetchAll();
// 完了済みリスト
  $oklists = $db->prepare('SELECT * FROM lists WHERE user=? AND achieve=1 ORDER BY priority ASC');
  $oklists->bindParam(1,$user['id'],PDO::PARAM_INT);
  $oklists->execute();
  $oklist = $oklists->fetchAll();
  $oknum = count($oklist);
}

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
  <a class="active" href="main.php">ToDoリスト</a>
  <a class="" href="reward.php">ご褒美</a>
</div>
<p class="addbtn">タスクの追加<button class="plusbtn" onclick="location.href='add.php'"><i class="fas fa-plus"></i></button></p>

<form class="sort_form" action="main.php" method="post">
  <select class="sort" name="sort" id="">
    <option value="1" <?php if($sort == 'priority'){echo 'selected';}?>>重要度順</option>
    <option value="2" <?php if($sort == 'deadline'){echo 'selected';}?>>締切順</option>
    <option value="3" <?php if($sort == 'created'){echo 'selected';}?>>新着順</option>
    <option value="4" <?php if($sort == 'point'){echo 'selected';}?>>ポイント順</option>
  </select>
  <button class="sort_btn" type="submit">変更</button>
</form>

<div class="lists">
  <div>
    <hr>
    <?php foreach($list as $lis):?>
      <div class="priority<?php echo $lis['priority']?> list">
        <a class="check" href="check_on.php?id=<?php echo $lis['id']?>"><i class="far fa-check-circle"></i></a>
        <p class="point"><i class="fas fa-coins"></i><?php echo $lis['point'];?></p>
        <p class="title" onClick="openDetail();"><?php echo $lis['title'];?></p>
        <a class="edit" href="edit.php?id=<?php echo $lis['id']?>"><i class="fas fa-edit"></i></a>
        <a class="trash" href="delete.php?id=<?php echo $lis['id']?>" onClick="return delete_alert();"><i class="fas fa-trash-alt"></i></a>
        <?php if(isset($lis['deadline']) && $lis['deadline'] !== '0000-00-00'):?>
          <br>
        <div class="deadline">
          <p><?php if('0000-00-00' !== $lis['deadline'])echo '〆'.$lis['deadline'];?></p>
        </div>
        <?php endif;?>
        <?php if(isset($lis['detail'])):?>
        <div class="detail" id="detail">
          <p><?php echo sanitize_br($lis['detail']);?></p>
        </div>
        <?php endif?>
      </div>
      <hr>
    <?php endforeach;?>
  </div>

  <p class="openlists" onClick="listToggle();">完了済み(<?php echo $oknum?>件) <i class="fas fa-chevron-down"></i></p>
  <hr>
  <div id="open_lists" class="ok_lists">
    <?php foreach($oklist as $list):?>
      <div class="oklist">
      <p class="title"><?php echo $list['title'];?></p>
      <a class="trash" href="delete.php?id=<?php echo $list['id']?>" class="trash"><i class="fas fa-trash-alt"></i></a>
      <a class="trash" href="restore.php?id=<?php echo $list['id']?>" class="restore"><i class="fas fa-reply-all"></i></a>
      </div>
      <hr>
    <?php endforeach;?>
  </div>
</div>
</body>
<script src="main.js"></script>
</html>
