<?php
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');
date_default_timezone_set("Asia/Tokyo");
ini_set("display_errors",1);
error_reporting(E_ALL);

first();
$url = referer();
$id = $_SESSION['id']; 

if($url === 'main.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $restore_id = $_GET['id'];
    // リスト情報取得
    $lists = $db->prepare('SELECT * FROM lists WHERE id=?');
    $lists->execute(array($restore_id));
    $list = $lists->fetch();
    $restore_point = $list['point'];
    $achievementDate = $list['achievementDate'];

    if($list['user'] === $id){
      $stmt = $db->prepare('UPDATE lists SET achieve=0 WHERE id=?');
      $stmt->bindParam(1,$restore_id,PDO::PARAM_INT);
      $stmt->execute();
      // ユーザー情報
      $users = $db->prepare('SELECT * FROM user WHERE id=?');
      $users->execute(array($id));
      $user = $users->fetch();
      $user_points = $user['points'];
      // 合計のポイント数
      $sum = $user_points - $restore_point;
      // ポイント減
      $points = $db->prepare('UPDATE user SET points=? WHERE id=?');
      $points->bindParam(1,$sum,PDO::PARAM_INT);
      $points->bindParam(2,$id,PDO::PARAM_INT);
      $points->execute();

      // グラフテーブルに追加
      $graphs = $db->prepare('SELECT * FROM graph WHERE day=? AND user=?');
      $graphs->execute(array($achievementDate,$id));
      $graph = $graphs->fetch();
      $graph_points = $graph['points'];
      // 1日のポイント数
      $day_sum = $graph_points - $restore_point;
      // ポイント減
      $day_points = $db->prepare('UPDATE graph SET points=? WHERE day=? AND user=?');
      $day_points->bindParam(1,$day_sum,PDO::PARAM_INT);
      $day_points->bindParam(2,$achievementDate,PDO::PARAM_STR);
      $day_points->bindParam(3,$id,PDO::PARAM_INT);
      $day_points->execute();
    }
    header('Location: main.php');
    exit();
  }else{
    header('Location: main.php');
    exit();
  }
//daily.phpから来た場合
}elseif($url === 'daily.php'){
  if(isset($_GET['id']) || !empty($_GET['id'])){
    $restore_id = $_GET['id'];
    $lists = $db->prepare('SELECT * FROM every WHERE id=?');
    $lists->execute(array($restore_id));
    $list = $lists->fetch();
    $restore_point = $list['point'];
    if($list['user'] === $id){
      $stmt = $db->prepare('UPDATE every SET achieve=0 WHERE id=?');
      $stmt->bindParam(1,$restore_id,PDO::PARAM_INT);
      $stmt->execute();
      $users = $db->prepare('SELECT * FROM user WHERE id=?');
      $users->execute(array($id));
      $user = $users->fetch();
      $user_points = $user['points'];
      $sum = $user_points - $restore_point;
      $points = $db->prepare('UPDATE user SET points=? WHERE id=?');
      $points->bindParam(1,$sum,PDO::PARAM_INT);
      $points->bindParam(2,$id,PDO::PARAM_INT);
      $points->execute();
      // グラフテーブルに追加
      $today = date('Y-m-d');
      $graphs = $db->prepare('SELECT * FROM graph WHERE day=? AND user=?');
      $graphs->execute(array($today,$id));
      $graph = $graphs->fetch();
      $graph_points = $graph['points'];
      // 1日のポイント数
      $day_sum = $graph_points - $restore_point;
      // ポイント減
      $day_points = $db->prepare('UPDATE graph SET points=? WHERE day=? AND user=?');
      $day_points->bindParam(1,$day_sum,PDO::PARAM_INT);
      $day_points->bindParam(2,$today,PDO::PARAM_STR);
      $day_points->bindParam(3,$id,PDO::PARAM_INT);
      $day_points->execute();
    }
    header('Location: daily.php');
    exit();
  }else{
    header('Location: daily.php');
    exit();
  }
}

?>
