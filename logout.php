<?php
session_start();
header('X-FRAME-OPTIONS:DENY');

$_SESSION = array();
if(ini_set('session.use._cookies')){
  $params = session_get_cookie_params();
  setcookie(session_name().'', time() - 50000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
setcookie('email', '', time()-7200);

header('Location: login.php');
exit();
?>