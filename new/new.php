<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);

$error = array();
if(!empty($_POST)){
  $error = validation();
	// メール重複チェック
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM user WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			$error['email']['dub'] = '指定されたメールアドレスは既に使用されています。';
		}
  }
  if(empty($error)){
    $_SESSION['new']['email'] = h($_POST['email']);
    $_SESSION['new']['name'] = h($_POST['name']);

    header('Location: new_con.php');
		exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="new.css"/>
	<title>ToDolist</title>
</head>
<body>
<header>
	<h1>ToDolist</h1>
	<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
</header>
<div class="main">
  <div class="title">
    <h1>アカウントの仮登録</h1>
    <p>お名前とメールアドレスをご入力の上、送信ボタンを押してください。</p>
    <p>入力したメールアドレスに仮登録メールが届きます。</p>
    <p>メールに記載されているURLから、登録を進めてください。</p>
  </div>
  <div class="content">
    <form action="" method="post">
        <label class="" for="name">名前</label>
        <input id="name" type="text" name="name" size="35" maxlength="255" value="<?php if($_POST){echo h($_POST['name']);}?>" />
          <?php if(!empty($error['name']['blank'])):?>
            <p class="error">*<?php echo $error['name']['blank'];?></p>
          <?php endif;?>
        <br>
        <label class="" for="email">メールアドレス</label>
        <input id="email" type="text" name="email" size="35" maxlength="255" value="<?php if($_POST){echo h($_POST['email']);}?>" />
          <?php if(!empty($error['email']['blank'])):?>
            <p class="error">*<?php echo $error['email']['blank'];?></p>
          <?php elseif(!empty($error['email']['dub'])):?>
            <p class="error">*<?php echo $error['email']['dub'];?></p>
          <?php elseif(!empty($error['email']['data'])):?>
            <p class="error">*<?php echo $error['email']['data'];?></p>
          <?php endif;?>
        <div><input class="btn" name="con" type="submit" value="送信する"/></div>
    </form>
  </div>
</div>
</body>
</html>
