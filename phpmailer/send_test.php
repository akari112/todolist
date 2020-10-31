<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'setting.php';
require_once('../function.php');
ini_set("display_errors",1);
header('X-FRAME-OPTIONS:DENY');
error_reporting(E_ALL);
session_start();

$page = '';

if(!empty($_SESSION['token']) && !empty($_POST['token']) && $_SESSION['token'] == $_POST['token']){
    unset($_SESSION['token']);
    unset($_SESSION['new']);

    //PHPMailerのインスタンス生成
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
    $mail->SMTPAuth = true;
    $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
    $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
    $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
    $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
    $mail->Port = SMTP_PORT; // 接続するTCPポート

    // メール内容設定
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
    $mail->addAddress($_POST['email'], $_POST['name']."様"); //受信者（送信先）を追加する
    $mail->Subject = MAIL_SUBJECT; // メールタイトル
    $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
    $body = $_POST['name']."様\nToListAPPの仮登録ありがとうございます。\n まだ会員登録は完了しておりませんので、次のURLにて本登録をよろしくお願いいたします。\n http://localhost:8888/todolist/new/signup.php";

    $mail->Body  = $body; // メール本文
    // メール送信の実行
    if(!$mail->send()) {
    	// echo 'メッセージは送られませんでした！';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        $page = 2;
    } else {
        $page = 1;
    }
}else{
    header('Location: ../new/new.php');
    exit();
}
?>
<?php if($page == 1):?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../new/new.css"/>
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
        <h1>メール送信</h1>
        <p>メール送信が完了しました。</p>
        <p>TODOLISTアプリから送られてきたURLから本登録お願いします。</p>
        <button class="btn" onclick="location.href='../login.php'">TOPへ</button>
    </div>
    </div>

</body>
</html>
<?php endif;?>
<?php if($page == 2):?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../new/new.css"/>
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
        <h1>エラー発生</h1>
        <p>メールが送信できませんでした</p>
        <p>恐れ入りますが、時間を置いてから再び仮登録お願いします。</p>
        <button class="btn" onclick="location.href='../login.php'">戻る</button>
    </div>
    </div>

</body>
</html>
<?php endif;?>