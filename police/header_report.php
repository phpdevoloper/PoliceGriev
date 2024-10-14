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
        { session_destroy(); 
		//header("Location: logout.php"); 
		echo '<script type="text/javascript">window.location="logout.php"</script>';
	}
}
$_SESSION['timeout'] = time();
//session_cache_expire (30);
//$cache_expire = session_cache_expire();
include("db.php");
include("UserProfile.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);
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
<style>
.heading_new {
    height: 5px !important;
    font-size: 77% !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
	letter-spacing: 2px;
	width: 100% !important;
}
.small_heading_new {
    font-size: 16px !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
	letter-spacing: 2px;
	line-height: 28px;
	width: 100% !important;
	
}
.img {
	width: 7% !important;
	text-align: right;
}
.T_E {
	width: 30%;
	float: left;
	margin-left: 8px;
	margin-top: 9px;
}
.T_E_U {
	width: 58%;
	float: right;
	text-align: right;
	margin-right: 20px;
	margin-top: 9px;
}
.T_E_C {
	letter-spacing: 2px;
	line-height: 41px;
	font-size: 15px !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
.T_E_C_V {
	letter-spacing: 2px;
	line-height: 41px;
	font-size: 18px !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}

.T_E_C_P {
	letter-spacing: 2px;
	line-height: 18px;
	font-size: 16px !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
.T_E_C_P_V {
	letter-spacing: 2px;
	line-height: 19px;
	font-size: 15px !important;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
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

<body onload='burstCache()'>
<div><?php include("ticker.php"); ?> </div>
	<!--div id="header" style="background-image:url(images/header-bg.jpg); background-size: 100% 84px; color:#EDEDED; "-->
	<div id="header" style="background-color: #7044d3;color:#EDEDED; ">
		<div class="img">
		<!--img src="images/govt_embalam.PNG" height="80" width="85" /-->  
		<img src="assets/images/emblem-dark.png" height="80" width="85"  />
		
		</div>
	<div class="T_E">
		<div class="T_E_C"><?php
		if($_SESSION["lang"]=='T')
		{
			
		?>
			தமிழ்நாடு காவல் துறை
			
        <?php
		}else{
			
		?>
			Tamil Nadu Police
        <?php } ?></div>
		
		<div class="T_E_C_P">
		 <?php
		if($_SESSION["lang"]=='T')
		{
			
		?>
			மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) 
        <?php
		}else{
			
		?>
			Senior Police Officers Petition System (SPOPS)
        <?php } ?>
		</div>
	</div>
	<div class="T_E_U">	
		
		
		
		
		<div class="T_E_C_V">
		
        <?php
			if ($userProfile != null) {
				$sql="select off_level_id,off_level_dept_name,off_level_dept_tname 
						from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and off_level_id=".$userProfile->getOff_level_id()."";
						
				$sql="select off_level_id,off_level_dept_name,off_level_dept_tname,off_loc_name,off_loc_tname from vw_usr_dept_users_v where 
						--dept_id=1 and off_level_id=2 and 
						 dept_user_id=".$userProfile->getDept_user_id()."";		
				$result = $db->query($sql);
				//$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
				while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
					if($_SESSION["lang"]=='T'){
						$head_title = nl2br($rowArr['off_level_dept_tname'].' - '.$rowArr['off_loc_tname']);
					} else {
						//$head_title = $rowArr[off_level_dept_name].' - '.ucwords(strtolower($rowArr[off_loc_name]));
						$head_title = strtoupper($rowArr['off_level_dept_name'].' - '.$rowArr['off_loc_name']);
					}
				}
			} 
		?>
        <?php echo $head_title; ?>
       
		</div>
		<div class="T_E_C_P_V">
		
			  <?php
if($_SESSION['lang']=='E')
	{ echo 'User';}else{ echo 'பயனர்'; }?> : 
	 <?php if ($userProfile->getOff_desig_emp_name() != '')
	 	  echo $userProfile->getOff_desig_emp_name() .';'; 
		  echo $userProfile->getDept_desig_name();
		  /*.', '.
		  (
		  ($_SESSION['lang']=='E')?
		  $userProfile->getOff_level_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name())
		  :
		  nl2br ("\n".$userProfile->getOff_level_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name()))
		  )*/; 
		  
		  //echo $userProfile->getOff_desig_emp_name(); 
		  
		  ?>
		</div>
	</div>
</div>
