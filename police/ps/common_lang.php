<?php
session_start();
header('Cache-control: private'); // IE 6 FIX

if(isSet($_GET['lang']))
{
//$lang = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $_GET['lang']);
if($_GET['lang']=='T'){
	$lang='T';
}else{
	$lang='E';
}

// register the session and set the cookie
$_SESSION['lang'] = $lang;

setcookie("lang", $lang, time() + (3600 * 24 * 30));
}
else if(isSet($_SESSION['lang']))
{
$lang = $_SESSION['lang'];
}
else if(isSet($_COOKIE['lang']))
{
$lang = $_COOKIE['lang'];
}
else
{
$lang = 'E';
//echo $lang;
}

switch ($lang) {
  case 'E':
  $lang_file = 'lang.E.php';
  break;

  case 'T':
  $lang_file = 'lang.T.php';
  break;

  default:
  $lang_file = 'lang.E.php';
}

include_once 'languages/'.$lang_file;
?>