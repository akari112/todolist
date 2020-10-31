<?php
session_start();
require('.db.php');
require('function.php');
header('X-FRAME-OPTIONS:DENY');
ini_set("display_errors",1);
error_reporting(E_ALL);

first();
$id = $_SESSION['id']; 
$referer = h($_POST['referer']);

if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
  unset($_SESSION['token']);
  $referer = h($_POST['referer']);

  if($referer === 'main.php'){
    if(isset($_POST['id']) && !empty($_POST['id'])){
      $eid = h($_POST['id']);
  
      $title = h($_POST['title']);
      $detail = h($_POST['detail']);
      $point = h($_POST['point']);
      $priority = h($_POST['priority']);
      $deadline = h($_POST['deadline']);
      
      $lists = $db->prepare('UPDATE lists SET title=?, detail=?, point=?, priority=?, deadline=? WHERE id=? AND user=?');
      $lists->execute(array(
        $title,
        $detail,
        $point,
        $priority,
        $deadline,
        $eid,
        $id
      ));
      $lists->execute();
      header('Location: main.php');
      exit();
    }else{
      header('Location: main.php');
      exit();
    }
  }elseif($referer === 'daily.php'){
    if(isset($_POST['id']) && !empty($_POST['id'])){
      $eid = $_POST['id'];
      $title = h($_POST['title']);
      $detail = h($_POST['detail']);
      $point = h($_POST['point']);
      $priority = h($_POST['priority']);
      $lists = $db->prepare('UPDATE every SET title=?, detail=?, point=?, priority=? WHERE id=? AND user=?');
      $lists->execute(array(
        $title,
        $detail,
        $point,
        $priority,
        $eid,
        $id
      ));
      $lists->execute();
      header('Location: daily.php');
      exit();
    }else{
      header('Location: daily.php');
      exit();
    }
  }elseif($referer === 'reward.php'){
    if(isset($_POST['id']) && !empty($_POST['id'])){
      $eid = $_POST['id'];
      $title = h($_POST['title']);
      $point = h($_POST['point']);
      $lists = $db->prepare('UPDATE reward SET title=?, points=? WHERE id=? AND user=?');
      $lists->execute(array(
        $title,
        $point,
        $eid,
        $id
      ));
      $lists->execute();
      header('Location: reward.php');
      exit();
    }else{
      header('Location: reward.php');
      exit();
    }
  }
}else{
  header("Location: {$referer}");
  exit();
}
?>