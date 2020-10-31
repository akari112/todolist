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
// リファラーごとにリスト情報取得
if($url === 'main.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $eid = $_GET['id'];
    $edit = $db->prepare('SELECT * FROM lists WHERE user=? AND id=?');
    $edit->bindParam(1,$id,PDO::PARAM_INT);
    $edit->bindParam(2,$eid,PDO::PARAM_INT);
    $edit->execute();
    $edi = $edit->fetch();
  }else{
    header('Location: main.php');
    exit();
  }
}elseif($url === 'daily.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $eid = $_GET['id'];
    $edit = $db->prepare('SELECT * FROM every WHERE user=? AND id=?');
    $edit->bindParam(1,$id,PDO::PARAM_INT);
    $edit->bindParam(2,$eid,PDO::PARAM_INT);
    $edit->execute();
    $edi = $edit->fetch();
  }else{
    header('Location: daily.php');
    exit();
  }
}elseif($url === 'reward.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $eid = $_GET['id'];
    $edit = $db->prepare('SELECT * FROM reward WHERE user=? AND id=?');
    $edit->bindParam(1,$id,PDO::PARAM_INT);
    $edit->bindParam(2,$eid,PDO::PARAM_INT);
    $edit->execute();
    $edi = $edit->fetch();
  }else{
    header('Location: reward.php');
    exit();
  }
}else{
  header("Location: {$url}.php");
  exit();
}
// 元の重要度表示
if($url !== 'reward.php'){
  if($edi['priority'] === 1){
    $pri = '高';
  }elseif($edi['priority'] === 2){
    $pri = '中';
  }elseif($edi['priority'] === 3){
    $pri = '低';
  }
}
// トークン作成
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
  <a class="<?php if($url === 'daily.php'){echo 'active';}?>" href="daily.php">日課</a>
  <a class="<?php if($url === 'main.php'){echo 'active';}?>" href="main.php">ToDoリスト</a>
  <a class="<?php if($url === 'reward.php'){echo 'active';}?>" href="reward.php">ご褒美</a>
</div>

<div class="edit">
  <h1>タスク変更</h1>
  <form action="edit_ok.php" method="post">
    <input type="hidden" name="token" value="<?php echo $token;?>"> 
    <input type="hidden" name="referer" value="<?php echo $url;?>"> 
    <input type="hidden" name="id" value="<?php echo $edi['id'];?>"> 

    <label class="lab" for="title">タスク名</label>
    <input class="edit_title" id="title" class="title" type="text" name="title" value="<?php echo $edi['title']?>"><br>
    <?php if($url !== 'reward.php'):?>
      <label class="lab" for="detail">詳細</label>
      <textarea class="edit_detail" id="detail" name="detail" class="detail" rows="3" cols="30"><?php if(!empty($edi['detail'])){ echo $edi['detail'];}?></textarea><br>
      <label class="lab" for="priority">優先度</label>
      <select class="edit_priority" id="priority" class="priority" name="priority" id="">
        <option value="<?php echo $edi['priority']?>"><?php echo $pri;?>(元の優先度)</option>
        <option value="1">高</option>
        <option value="2">中</option>
        <option value="3">低</option>
      </select><br>
    <?php endif;?>
    <?php if($url === 'main.php'):?>
      <label class="lab" for="deadline">期限</label>
      <input class="edit_deadline" name="deadline" id="deadline" class="deadline" type="date" min="<?php echo date('Y-m-d');?>" value="<?php echo $edi['deadline']?>"><br>
    <?php endif;?>
      <label class="lab" for="point">ポイント数</label>
    <input class="edit_point" id="point" name="point" class="point" type="number" value="<?php echo $edi['point']?>"><br>
    <button class="btn" type="submit">保存</button>
  </form>
</div>



</body>
</html>
