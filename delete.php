<?php
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);
error_reporting(E_ALL);

first();
$url = referer();
$id = $_SESSION['id']; 

 
if($url === 'main.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $did = $_GET['id'];
    // リスト情報取得
    $lists = $db->prepare('SELECT * FROM lists WHERE id=?');
    $lists->execute(array($did));
    $list = $lists->fetch();
  
    if($list['user'] === $_SESSION['id']){
      $del = $db->prepare('DELETE FROM lists WHERE id=?');
      $del->execute(array($did));
    }
    header("Location: main.php");
    exit();
  }
}elseif($url === 'daily.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $did = $_GET['id'];
    $lists = $db->prepare('SELECT * FROM every WHERE id=?');
    $lists->execute(array($did));
    $list = $lists->fetch();
    if($list['user'] === $_SESSION['id']){
      $del = $db->prepare('DELETE FROM every WHERE id=?');
      $del->execute(array($did));
    }
    header("Location: daily.php");
    exit();
  }
}elseif($url === 'reward.php'){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $did = $_GET['id'];
    $lists = $db->prepare('SELECT * FROM reward WHERE id=?');
    $lists->execute(array($did));
    $list = $lists->fetch();
    if($list['user'] === $_SESSION['id']){
      $del = $db->prepare('DELETE FROM reward WHERE id=?');
      $del->execute(array($did));
    }
    header("Location: reward.php");
    exit();
  }
}else{
  header("Location: main.php");
  exit();
}


?>
