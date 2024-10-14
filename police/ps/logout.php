<?php 
session_start();
$_SESSION['logout']=true;
$_COOKIE['logout']=true;
setcookie(session_name(),null,time()-3600);
//remove PHPSESSID from browser
// Unset all of the session variables.
$_SESSION = array();session_reset();
//clear session from disk
session_unset();     // unset $_SESSION variable for the run-time 
session_destroy();   // destroy session data in storage
header("Location: index.php");
exit;
?> 