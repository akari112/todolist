<?php 
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');

first();
$id = $_SESSION['id'];
if($id) {
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($id));
  $user = $users->fetch();
}
$url = referer();
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
	<title>Todolist</title>
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
  <a class="<?php if($url === 'daily.php'){echo 'active';}?>" href="daily.php">日課</a>
  <a class="<?php if($url === 'main.php'){echo 'active';}?>" href="main.php">ToDoリスト</a>
  <a class="<?php if($url === 'reward.php'){echo 'active';}?>" href="reward.php">ご褒美</a>
</div>

<div class="add">
  <h1>新しいタスクの追加</h1>
  <p>タスク名とポイント数のみ必須です</p>
  <?php if($url === 'main.php'):?>
   <form action="main.php" method="post">
  <?php elseif($url === 'daily.php'):?>
    <form action="daily.php" method="post">
  <?php endif;?>
      <input type="hidden" name="token" value="<?php echo $token;?>"> 
      
      <label class="lab" for="title">*タスク名</label>
      <input class="add_title" type="text" id="title" name="title" placeholder="新しいタスク"><br>
      <label class="lab" for="detail">詳細</label>
      <textarea class="add_detail" id="detail" name="detail" rows="3" cols="30"></textarea><br>
      <label class="lab" for="point">*ポイント数</label>
      <input class="add_point" id="point" name="point" type="number" placeholder="point">
      <label class="lab" for="priority">重要度</label>
      <select class="add_priority" id="priority" class="priority" name="priority">
        <option value="1">高</option>
        <option value="2" selected>中</option>
        <option value="3">低</option>
      </select><br>
      <?php if($url === 'main.php'):?>
        <label class="lab" for="deadline">期限</label>
        <input class="add_deadline" name="deadline" id="deadline" type="date" min="<?php echo date('Y-m-d');?>"><br>
      <?php endif;?>
      <button class="btn" type="submit">追加</button>
    </form>
</div>

</body>
</html>
