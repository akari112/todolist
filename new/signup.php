<?php
session_start();
require('../.db.php');
require('../function.php');
header('X-FRAME-OPTIONS:DENY');

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
    $_SESSION['join']['email'] = h($_POST['email']);
    $_SESSION['join']['name'] = h($_POST['name']);
    $pass = h($_POST['pass']);
    $_SESSION['join']['pass'] = sha1($pass);

    header('Location: check.php');
		exit();
  }
}
// 書き直し
if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join'];
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
	<div class="head">
		<h1>ToDolist</h1>
		<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>

<div class="main">
  <div class="title">
    <h1>アカウント新規作成</h1>
    <p>仮登録ありがとうございました。</p>
    <p>下記の欄をご入力の上、確認ボタンを押してください。</p>
  </div>
  <div class="content">
    <form action="" method="post">
        <label class="lab" for="name">名前</label>
        <input id="name" type="text" name="name" size="35" maxlength="255" value="<?php if($_POST){echo h($_POST['name']);}?>" />
          <?php if(!empty($error['name']['blank'])):?>
            <p class="error">*<?php echo $error['name']['blank'];?></p>
          <?php endif;?><br>

        <label class="lab" for="email">メールアドレス</label>
        <input id="email" type="text" name="email" size="35" maxlength="255" value="<?php if($_POST){echo h($_POST['email']);}?>" />
          <?php if(!empty($error['email']['blank'])):?>
            <p class="error">*<?php echo $error['email']['blank'];?></p>
          <?php elseif(!empty($error['email']['data'])):?>
            <p class="error">*<?php echo $error['email']['data'];?></p>          
          <?php elseif(!empty($error['email']['dub'])):?>
            <p class="error"><?php echo $error['email']['dub'];?></p>
          <?php endif;?><br>

        <label class="lab" for="password">パスワード(6文字以上)</label>
        <input id="password" type="password" name="pass" maxlength="20" value=""/><br>
        <input type="checkbox" id="password-check"><label class="check_lab" for="password-check">パスワードを表示する</label>
        <script>
          const pwd = document.getElementById('password');
          const pwdCheck = document.getElementById('password-check');
          pwdCheck.addEventListener('change', function() {
            if(pwdCheck.checked) {
              pwd.setAttribute('type', 'text');
            } else {
              pwd.setAttribute('type', 'password');
            }
          }, false);
          </script> 
						<?php if(!empty($error['pass']['length'])):?>
							<p class="error">*<?php echo $error['pass']['length'];?></p>
						<?php endif;?>
						<?php if(!empty($error['pass']['blank'])):?>
							<p class="error">*<?php echo $error['pass']['blank'];?></p>
						<?php endif;?>
        <div><input class="btn" name="con" type="submit" value="確認する"/></div>
    </form>
  </div>
</div>
</body>
</html>
