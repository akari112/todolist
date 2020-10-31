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
// トークン確認、投稿DB登録
if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
  unset($_SESSION['token']);
  if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['point']) && !empty($_POST['point'])){
    $title = h($_POST['title']);
    $point = h($_POST['point']);
    $lists = $db->prepare('INSERT INTO reward SET user=?, title=?, points=?, created=NOW()');
    $lists->execute(array(
      $id,
      $title,
      $point
    ));
  }
  header('Location: reward.php');
  exit();
}else {
  $notoken = '不正なアクセスです。';
  header('Location: reward.php');
  exit();
}
?>
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
// トークン確認、投稿DB登録
if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
  unset($_SESSION['token']);
  if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['point']) && !empty($_POST['point'])){
    $title = h($_POST['title']);
    $point = h($_POST['point']);
    $lists = $db->prepare('INSERT INTO reward SET user=?, title=?, points=?, created=NOW()');
    $lists->execute(array(
      $id,
      $title,
      $point
    ));
  }
  header('Location: reward.php');
  exit();
}else {
  $notoken = '不正なアクセスです。';
  header('Location: reward.php');
  exit();
}
?>