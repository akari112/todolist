<?php
require('.db.php');
// ログイン確認
function first(){
  if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
    $_SESSION['time'] = time()+7200;
  } else {
    header('Location: login.php');
    exit();
  }
}
// HTML変換
function h($str) {
  $stri = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  return $stri;
}
// バリデーション
function validation() {
	$error = array();
	// 氏名
	if(isset($_POST['name']) && empty($_POST['name'])) {
		$error['name']['blank'] = "名前を入力してください。";

	} elseif(isset($_POST['name']) && 20 < mb_strlen($_POST['name']) ) {
		$error['name']['strlen'] = "名前は20文字以内で入力してください。";
	}
	// メールアドレス
	if(isset($_POST['email']) && empty($_POST['email']) ) {
		$error['email']['blank'] = "メールアドレスを入力してください。";

	} elseif(isset($_POST['email']) && !preg_match( '/^[0-9a-z_.\/?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $_POST['email']) ) {
		$error['email']['data'] = "メールアドレスは正しい形式で入力してください。";
  }
  //パスワード
  if(isset($_POST['pass']) && empty($_POST['pass'])) {
		$error['pass']['blank'] = "パスワードを入力してください。";

	} elseif(isset($_POST['pass']) && 6 > mb_strlen($_POST['pass']) ) {
		$error['pass']['length'] = "パスワードは8文字以上で入力してください。";
	}
	return $error;
}
//改行
function sanitize_br($str){
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

// リファラー取得
function referer(){
  $referer = $_SERVER['HTTP_REFERER'];
  $url = parse_url($referer);
  $path = explode('/',$url['path']);
  $url = array_pop($path);
  return $url;
}

?>