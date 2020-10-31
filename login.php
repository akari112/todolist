<?php
require('.db.php');
session_start();
require('function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);
// emailがセットされているか
if(!empty($_COOKIE['email']) && $_COOKIE['email'] !== ''){
  $email = $_COOKIE['email'];
}
if(!empty($_POST)){
  $email = h($_POST['email']);
  $pass = h($_POST['pass']);
  $error = validation();
  
  if(!empty($email) && !empty($pass)){
    $login = $db->prepare('SELECT * FROM user WHERE email=? AND pass=?');
    $login->execute(array(
      $email,
      sha1($pass)
    ));
    $member = $login->fetch();

    if($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time()+7200;

      if($_POST['save'] === 'on'){
        setcookie('email', $email, time()+60*60*24*14);
      }
      header('Location: main.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css"/>
  <title>ToDoList｜ログイン</title>
</head>
<body>
<div class="login">
  <div class="head">
    <h1>Todolist</h1>
    <p>タスクを達成して、ポイントを貯めて</p>
    <p>ご褒美を目指して</p>
    <p>楽しくタスク管理しませんか？</p>
  </div>
  <div class="content">
    <form action="" method="post">
      <div>
          <label class="lab" for="email">メールアドレス</label>
          <input class="inp" id="email" type="text" name="email" maxlength="255" value="<?php if($_POST){echo h($_POST['email']);}?>" />
            <?php if(!empty($error['email']['blank'])):?>
              <p class="error">*<?php echo $error['email']['blank'];?></p>
            <?php endif;?><br>
          
          <label class="lab" for="pass">パスワード</label>
          <input class="inp" id="pass" type="password" name="pass" maxlength="20" value="" />
              <?php if(!empty($error['pass']['blank'])):?>
                <p class="error">*<?php echo $error['pass']['blank'];?></p>
              <?php endif;?>

        <?php if(!empty($error['login']) && $error['login'] === 'failed'):?>
          <p class="error">*ログインに失敗しました。メールアドレスとパスワードが間違っています。</p>
        <?php endif;?>          

        <div class="save">
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </div>
      </div>

      <div>
        <input class="btn" type="submit" value="ログインする" />
      </div>
    </form>

    <div id="lead">
      <p>----------新規登録がまだの方はこちらから----------</p>
      <a href="new/new.php"><button class="back_btn">新規登録をする</button></a>
    </div>
  </div>
</div>
</body>
</html>
