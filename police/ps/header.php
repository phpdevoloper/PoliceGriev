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
    <!-- <META http-equiv="X-Frame-Options" content="Deny" /> -->
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

<style>
.heading_new {
    height: 5px !important;
    font-size: 77% !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
	letter-spacing: 2px;
	width: 100% !important;
	line-height: 28px;
}
.small_heading_new {
    font-size: 77% !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
	letter-spacing: 2px;
	line-height: 54px;
	width: 100% !important;
	
}
</style>

<script type = "text/javascript" >
$(document).ready(function(){
	burstCache();
});


function burstCache() {
	
if (!navigator.onLine) {
document.body.innerHTML = 'Loading...';
window.location = 'ErrorPage.html';
}
} 
</script>
<?php include("ticker.php"); ?>
<body onload='burstCache()'>

	<div id="header" style="background-image:url(images/header-bg.jpg); background-size: 100% 84px; color:#EDEDED;">
	<div class="flf" style="width:100%;">
	 <div class="img">
		<!--img src="images/govt_embalam.PNG" height="80" width="85" /-->  
		<img src="images/tn_govt_logo.png" height="80" width="85" />
		</div>
		
		<div class="heading heading_new">
		<center> <h1>
        Government of Tamil Nadu
        </h1></center>
		</div>
		
		<div class="small_heading small_heading_new">
		<center><h1>
        Petition Processing Portal (PPP)
        </h1></center>
		</div>
	  </div>
     <!-- <div class="flf" style="color:#ED0000;float:right;vertical-align: middle;padding-top:2%;width:60%;"> -->
	  <!--<a class="clink" href="http://10.163.30.9/ed_gdp_rpts_new/">Dashboard</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <a class="clink" href="http://10.163.30.9/ed_gdp_rpts_new/online">Online Petitions - For Public</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <a class="clink" href="http://10.163.30.9/ed_gdp_rpts_new/status">Petition Status</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  -->
	 <!-- <span style="font-size: 125%; color:#ffd11a;">Server will be shut down for maintenance between 2:00 PM to 3:00 PM</span>  -->
	  <!--<a class="clink" href="http://10.163.30.9/ed_gdp_rpts_new/index_login.php">Department Officials Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
	 <!-- <a class="clink" href="http://locahost/police/index_login.php">Department Officials Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  
	  
	  </div>
 
	</div>



