<?php 
$_SESSION['logged_in'] = true; //set you've logged in
$_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
$_SESSION['expire_time'] = 60; //expire time in seconds: three hours (you must change this)
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
header('X-Frame-Options: DENY');
header('X-Frame-Options: SAMEORIGIN');
header("location:online_welcome.php");
?>
