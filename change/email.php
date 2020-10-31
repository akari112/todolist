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
	// 重複チェック
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM user WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			$error['email']['dub'] = '*このメールアドレスはすでに使用されています。';
		}
  }
  if(empty($error)){
    $_SESSION['new']['email'] = h($_POST['email']);

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


<div class="cha">
  <h1>メールアドレス変更</h1>
    <form action="" method="post">
      <p>現在のメールアドレス</p>
      <p><p><?php echo $user['email'];?></p></p>

        <label class="lab" for="email">変更したいメールアドレス</label>
        <input class="inp" id="email" type="text" name="email" size="35" maxlength="255" value="<?php if($_POST){echo h($_POST['email']);}?>" />
          <?php if(!empty($error['email']['blank'])):?>
            <p class="error">*<?php echo $error['email']['blank'];?></p>
          <?php elseif(!empty($error['email']['data'])):?>
            <p class="error">*<?php echo $error['email']['data'];?></p>          
          <?php elseif(!empty($error['email']['dub'])):?>
            <p class="error"><?php echo $error['email']['dub'];?></p>  
          <?php endif;?><br>

        <div><input class="btn" name="con" type="submit" value="確認する"/></div>
    </form>
  <button class="b_btn" onclick="location.href='change.php'">戻る</button>
</div>
</body>
</html>
