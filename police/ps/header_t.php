<?php
error_reporting(0);
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
header('X-Frame-Options: DENY');
header('X-Frame-Options: SAMEORIGIN');

ob_start();
//if(session_id() == '')
	//{
    //session_start();
//}

session_start();
// set timeout period in seconds
$inactive = 30*60;
// check to see if $_SESSION['timeout'] is set
if(isset($_SESSION['timeout']) ) {
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive)
        { session_destroy(); header("Location: logout.php"); }
}
$_SESSION['timeout'] = time();
//session_cache_expire (30);
//$cache_expire = session_cache_expire();


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
 
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
     <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <META http-equiv="X-Frame-Options" content="Deny" />
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-control" content="pre-check=0">
    <meta http-equiv="Cache-control" content="private">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache-control" content="no-store">
	<meta http-equiv="expires" content="Mon, 26 Jul 1997 05:00:00 GMT"/>
	<meta http-equiv="pragma" content="no-cache" />
	

    <title><?php if(isset($pagetitle) == ""){ echo "e-District: GDP"; } else { echo $pagetitle; } ?></title>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <?php
		if($_SESSION["lang"]=='T')
		{
	?>
    		<style>
				body{
					font-size:70% !important;
				}
				#usr_detail{
					height:36px !important;
				}
			</style>
    <?php			
		}
	?>
</head>
<script type = "text/javascript" >
$(document).ready(function(){
	burstCache();
});


function burstCache() {
	
if (!navigator.onLine) {
document.body.innerHTML = 'Loading...';
window.location = 'ErrorPage.html';
}
} </script>  
<body onload='burstCache()'>
	<div id="header" style="background-image:url(images/header-bg.jpg); background-size: 100% 84px; color:#EDEDED;">
		<div class="img">
		<!--img src="images/govt_embalam.PNG" height="80" width="85" /-->  
		<img src="images/tn_govt_logo.png" height="80" width="85" />
		</div>
		
		<div class="heading">
		<center> <h1>
        தமிழ்நாடு அரசு
        </h1></center>
		</div>
		
		<div class="small_heading">
		<center><h3>
       மனுப் பரிசீலனை முகப்பு
        </h3></center>
		</div>
	</div>
