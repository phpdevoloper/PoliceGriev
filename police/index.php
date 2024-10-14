<?php 
ini_set("session.cookie_httponly",1); 
session_start();
ob_start();
//print_r($_POST);
//print_r($_SERVER['HTTP_REFERER']);
//print_r($_SERVER);exit;
if(($_POST!==array())){
	echo '<h2>Forbidden!</h2>';
	echo '<p>403 Error Found.</p>';
	exit;
}
if($_FILES!==array()){
	echo '<h2>Forbidden!</h2>';
	echo '<p>403 Error Found.</p>';
	exit;
} 
// if($_SERVER['HTTP_HOST']!='14.139.183.34'){
// 	echo '<h2>Forbidden!</h2>';
// 	echo '<p>403 Error Found.</p>';
// 	exit;
// }

$nonce = random_bytes(32);
$_SESSION['non']=base64_encode($nonce);
$non=$_SESSION['non'];
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='$non'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='$non'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}
header("Content-Security-Policy: object-src 'self'; script-src 'self' 'nonce-".$_SESSION['non']."'", TRUE);
include("db.php");
include("common_fun.php"); 
include_once 'common_lang.php';
if($_SESSION['non']==''){$non=base64_encode($nonce);}
//echo "===============".$_SESSION['lang'];
if (!isset($_SESSION['something_done'])) 
	{
		do_something();
		$_SESSION['something_done'] = true;
	}
function randomPrefix($length) 
	{ 
		$random= ""; 
		srand((double)microtime()*1000000); 
		$data = "AbcDE123IJKLMN67QRSTUVWXYZ"; 
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; 
		$data .= "0FGH45OP89"; 
		for($i = 0; $i < $length; $i++) 
		{ 
			$random .= substr($data, (rand()%(strlen($data))), 1); 
		} 
		return $random; 
		
	} 

function do_something()
	{
	  	$_SESSION['itno']=rand(1,100);
		$_SESSION['salt']=randomPrefix(20);
		$_SESSION["prev_src"]='';
		//$_SESSION['token1']=session_id();
		$_SESSION["attempts"]=0;
		unset($_SESSION["pagetoken"]);
		$_SESSION["pagetoken"]=randomPrefix(20);
	}
	
/*  	switch ($lang) {
  case 'E':
  $lang_file = 'lang.E.php';
  echo "1".$lang_file;
  exit;
  break;

  case 'T':
  $lang_file = 'lang.T.php';
  echo "2".$lang_file;
  exit;
  break;

  default:
  $lang_file = 'lang.E.php';
  echo "3".$lang_file;
  exit;
}  */


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="theme-color" content="#317EFB"/>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0	 maximum-scale=6.0 user-scalable=no">
<link rel="apple-touch-icon" href="assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="assets/images/favicon/favicon.png">
<title><?php echo $lang['PAGE_TITLE']; ?></title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css"> 
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- font Awesome -->
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- font Awesome -->
<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery.md5.min.js"></script>
<script nonce='<?php echo $non; ?>' LANGUAGE="Javascript" SRC="js/md5.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery-migrate-3.4.0.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" src="assets/js/jquery.flexslider.js"></script>
<script nonce='<?php echo $non; ?>' src="bootstrap/js/modalbootstrap.min.js"></script>
<link href="assets/css/form1.css" rel="stylesheet" media="all">
<link href="assets/css/base1.css" rel="stylesheet" media="all">
<link href="assets/css/own_responsive.css" rel="stylesheet" media="all">
<link href="assets/css/base-responsive1.css" rel="stylesheet" media="all">
<link href="assets/css/grid.css" rel="stylesheet" media="all">
<link href="assets/css/font.css" rel="stylesheet" media="all">
<link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="assets/css/flexslider.css" rel="stylesheet" media="all">
<link href="assets/css/1megamenu.css" rel="stylesheet" media="all" />
<link href="assets/css/print.css" rel="stylesheet" media="print" />
<link href="theme/css/site.css" rel="stylesheet" media="all">
<link href="theme/css/site-responsive.css" rel="stylesheet" media="all">
<link href="theme/css/ma5gallery.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="bootstrap/mycss/dpk.css">
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery-migrate-3.4.0.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" src="js/jquery.md5.min.js"></script>
<script nonce='<?php echo $non; ?>' LANGUAGE="Javascript" SRC="js/md5.js"></script>
<script nonce='<?php echo $non; ?>' type="text/javascript" language="javascript">   
function disableBackButton() {
	window.history.forward()
}  
disableBackButton();  
window.onload=disableBackButton();  
window.onpageshow=function(evt) { if(evt.persisted) disableBackButton() }  
window.onunload=function() { void(0) }  
</script>

<script nonce='<?php echo $non; ?>'>
function killChars(strWord) {
    var strWords = strWord.value;
   
    var badChars = new Array("|", "()", ";", "/..", "../", "=", "\\", "*",".","[","]","-","&","^","!","@","#","$","%","_","{}","{","}",
            "/*", "*/", "%1", "%2", "%3", ".htm", ".html", "xp_", "alert",
            ".HTM", ".HTML", "--", "XP_", "ALERT","char(", "ascii(", "union",
            "having", "group by", "order by", "xp_", "0x", "cast(",    "insert into",
            "delete from", "delete", "drop", "exec(", "declare", "@@", "sp_", "insert",
            "update", "select", "1=1", "(",    ")", "+", "or", ",", ":", "|",  "case",
              "'", "\"", "<", ">", "script", "UNION", "HAVING", "GROUP BY",
            "ORDER BY", "INSERT INTO", "DELETE FROM", "DELETE", "DROP", "DECLARE",
            "INSERT", "UPDATE", "SELECT", "OR", "CASE", "SCRIPT");
   
    var tStrWord = strWord.value.toString();
    var spliting = [];
    var spliting = tStrWord.split("");
   
    var newChars = null;
    newChars = strWords;
   
    for(j = 0; j < spliting.length; j++){
    for (i = 0; i < badChars.length; i++) {
        newChars = newChars.replace(badChars[i], "");
    }
        strWord.value = newChars;
  }
}

$(window).on("load", function() {
  $('#flexslider').flexslider({
    animation: "slide",
    animationLoop: false,
    itemWidth: 210,
    itemMargin: 5
  });
});
</script>


<style>
.blink {
  animation: blink-animation 1s steps(5, start) infinite;
  -webkit-animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
.co-left
{
	color: #fff;
    line-height: 22px;
	cursor: pointer;
}
.call_now {
    font-size: 18px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 26px;
}
.call_now1 {
    font-size: 20px;
    letter-spacing: 2px;
    position: relative;
    font-family: Tahoma;
}
.call_now2 {
    font-size: 25px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 26px;
}
.call_now3 {
    font-size: 11px;
	letter-spacing: 2px;
	position: relative;
	font-family: times new roman;
	font-weight: bold;
	top: 8px;
}
.call_now::before {
    content: none;
    position: absolute;
    top: -50px;
    left: -12px;
}
.log_wid {
	width: 170px;
	float: left;
}
.bt_se{
	margin-top: 6px;
    margin-left: 0px;
	height: 31px;
}
.common-left ul li.ministry a:hover {
    color: #CA0C5C !important;
}
.fo_si {
 font-size: 21px;
}
.relo {
	width: 46px;
	float: left;
}
.catc {
	width: 80px;
    margin-left: 0px;
    float: left;
	
}
.cec {
	width: 100%;
	float: left;
	margin-bottom: 20px;
}
.re_img {
	margin-left: 0px;
    margin-top: 0px;
}

@media(min-width:1440px) and (max-width:2254px)
{
	#myTopnav{
		display:none;
	}
	.flex-control-nav {
		left: 190px;
		right: auto;
    }
   .logo {
    margin-left: 57px;
   }
   .initiative_logo {
	 margin-right: -290px; 
   }
   .header-right {
	margin-left: 287px !important;
	width: 78%;	
	}
	.call_now2 {
		width: 68%;
	}
}
@media(min-width:800px)
{
	.loginme1{
		top:150px;
		margin-left:20px;
		position:inherit;
	}.loginme2{
		top:180px;
		margin-left:20px;
		position:inherit;
	}.loginme3{
		top:210px;
		margin-left:20px;
		position:inherit;
	}
}
input:focus {
color:red;
}
#submit_otp:hover {
    background-color: #F37A0B;
}
.reg_btn:hover {
  border: 1px solid;
  box-shadow: inset 0 0 20px rgba(255, 255, 255, .5), 0 0 20px rgba(255, 255, 255, .2);
  outline-color: rgba(255, 255, 255, 0);
  outline-offset: 15px;
  text-shadow: 1px 1px 2px #427388; 
}

.tooltip1 {
    position: relative;
    display: inline-block;
   
}
.tooltip1 .tooltiptext {
    visibility: hidden;
    width: 500px;
    background-color: #E1D6D6;
    color: #000000;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
	font-size: 12px;
	margin-top: -7px;
}
.tooltip1:hover .tooltiptext {
    visibility: visible;
}
.red-tooltip + .tooltip > .tooltip-inner {background-color: #E1D6D6; font-size:13px;color:#000000;font-weight: bold;}

/* @media screen and (-webkit-min-device-pixel-ratio:0) { 
  .re_log1 {
   font-size: 7px;   
    }
} */
.topnav.responsive {position: relative;display:none;}
@media (min-width: 601px)and (max-width: 1030px)
{
	#myTopnav{
		display:none;
}
#mark_menu{
		display:none;
}
}
 @media (min-width: 1030px)
{
	#myTopnav{
		display:none;
	}
	#mark_menu{
		display:none;
	}
  .flex-control-nav {
    left: 950px;
    right: auto;
    }
  .banner-wrapper .flex-pauseplay {
   left: 1655px;
    right: auto;
   }
.container.common-container {
    max-width: 150px;
}

} 
@media(max-width:601px)
{
	#loginme1,#loginme2,#loginme3,#mark{
		display:none;
	}
	#imgget{
		position:inherit;
	}  
	#myTopnav{
		display:block;
	}#mark_menu{
		display:block;
	}
	.topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
   // right: 0;
    top: 0;
  }
  .topnav.responsive a,nav {
    float: none;
    display: block;
    //text-align: left;
  }
.modal {
	right:0;
}

}
.common-left ul li.ministry {
    border-left: 1px solid #f9ebeb;
}
.container.common-container {
    max-width: 1262px;
}

.common-left ul li.gov-india a:hover {
    color: #FFFFFF !important;
}
 .min_bor {
    border: 1px solid #f9ebeb !important;
    width: 29px;
    background: #000000;
}
.common-left ul li.ministry a:hover {
    color: #FFFFFF !important;
    border-left-color: #FFFFFF !important;
}
.min_bor a {
    color: #ffffff !important;
}
.common-left a:focus {
	color:#FFFFFF !important;
}
#e_co {
    font-size: 11px;
	line-height: 2px;
}
#t_co {
    font-size: 11px;
	line-height: 2px;
}
#a {
    font-size: 12px;
}
#a_p {
    font-size: 12px;
}
#a_m {
    font-size: 12px;
}
#a_c {
    font-size: 12px;
}
.reg_box2 {
	background-repeat: no-repeat;
	width: 400px;
}

.ba_co1 {
	background-color: #e1297f;
}
.ba_co2 {
	background-color: #6946a0;
	margin-top: -38px;
}
.ba_co3 {
	background-color: #2740e5;
}
.ba_co4 {
	background-color: #06880e;
}
.modal-footer {
	text-align: center;
}
.footer1 {
	text-align: center;
}

.blinking{
	animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
	0%{		color: #FFFFFF;	}
	49%{	color: transparent;	}
	50%{	color: transparent;	}
	99%{	color:transparent;	}
	100%{	color: #000;	}
}

.flash {
   animation-name: flash;
    animation-duration: 0.2s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    animation-direction: alternate;
    animation-play-state: running;
}

@keyframes flash {
    from {color: red;}
    to {color: #FFFFFF;}
}
.common-left.common-left-ind li:first-child {
	line-height: 7px;
}
.common-left ul li.ministry {
	line-height: 7px;
}
#v27:hover {
		color: #ff0037 !important;
		text-decoration: none;
		cursor: pointer;
	}


</style>
<script nonce='<?php echo $non; ?>'>
/* function blinker() {
	$('.blinking').fadeOut(1000);
	$('.blinking').fadeIn(1000);
}
//setInterval(blinker, 10000); */
$(function() {
    $('.blinking').delay(5000).show().fadeOut('slow');
});
</script>
<title>
</title>

<style>

/* .wrapper{
	//background-image:linear-gradient(150deg, #f27059 0%,  #e32121 100%) !important;
	background-color:#F27059;
	
} */
.,common-wrapper{
	border: 1px solid #716F6F;
	text-align:right;
	color: #f2f2f2;
	padding: 14px 16px;
	font-size: 14px;
	

}

.scroo_1,scroo_2,scroo_4,scroo_2_m11{
		background-color: #f2dfad;
		//background-image:linear-gradient(150deg, #378686 0%, #28163e 100%) !important;
}

.sidenav{
	background-color: #f2dfad;
	//background-image:linear-gradient(150deg, #378686 0%, #28163e 100%) !important;
	//background-image:linear-gradient(150deg, #000000 0%, #81a4cd 100%) !important;
}

.topnav-centered a {
	display:inline-block;
    padding:10px;
}
.ol,.ul{
	margin-bottom:0px;
}
.tnc{
	background-color: #fff;
	color:#e1297f;
	border: 1px solid #a;
	width: 29vw;
	margin-left: 4px;
	padding: 3px;
	margin-top: -5px;
	line-height: 40px;
}
.topnav-centered {
	background-color: #a3a3a3;
    border-width:1px solid #a3a3a3;
    list-style:none;
	overflow:auto;
    text-align:center;
}
.topnav-centered li{
    display:inline;
	font-size: 14px;
	font-weight: 600;
	padding: 9px 10px;
	cursor: pointer;
}
a.btn {
  -webkit-appearance: button;
  -moz-appearance: button;
  appearance: button;
}
.slideshow-container img {
	height: 100vh;
	width: 100vw;
}
.leftthing{
	max-width:15vw;
    //width:25vw;
    float:left;
	margin-left: 12%;
    position: fixed;
	left:18vw;
	top:25vh;
}
.centerthing{
	max-width:15vw;
	//width:25vw;
	float:left;
	margin-left: 2%;
    position: fixed;
	left:38vw;
	top:25vh;
}
.rightthing{
	max-width:15vw;
	//width:25vw;
	float:left;
	margin-left: 11%;
    position: fixed;
	left:40vw;
	top:25vh;
}

</style>
<script nonce='<?php echo $non; ?>'>
/* function blinker() {
	$('.blinking').fadeOut(1000);
	$('.blinking').fadeIn(1000);
}
//setInterval(blinker, 10000); */
$(function() {
    $('.blinking').delay(5000).show().fadeOut('slow');
});
</script>
</head>
<script nonce='<?php echo $non; ?>'>
$(document).ready(function(){//javascript:reloadCaptcha_off()
 document.getElementById("relo").onclick = function(){
	reloadCaptcha_off();
};
document.getElementById("relo2").onclick = function(){
	reloadCaptcha();
}; 
});
</script>
<body onload="noBack();" >
<div class="wrapper common-wrapper" id="he_colour1" style="background-color:#f2dfad">
	<div class="container common-container">
    	<div class="common-left common-left-ind clearfix">
          <ul>
			<li class="gov-india" id="gov_india">
			<a title="Click here - English Version" href="index.php?lang=E" class="ho_en" ><span id="e_co">English Version</span></a>
			</li>
			<li class="ministry" id="ministry1">
			<a title="Click here - Tamil Version" href="index.php?lang=T" ><span id="t_co">தமிழ் பதிப்பு</span></a>
			</li>
			<li class="ministry min_bac" id="ministry2">
			<a href="#" title="Increase font size" id="a"><span >A<sup>+</sup></span></a>
			</li>
			<li class="ministry" id="ministry3">
			<a href="#" title="Reset font size"id="a_p"><span>A<sup>&nbsp;</sup></span></a>
			</li>
			<li class="ministry" id="ministry4">
			<a href="#" title="Decrease font size" id="a_m"><span>A<sup>-</sup></span></a>
			</li>
			<li class="ministry min_bor" id="ministry5">
			<a title="Change Colour Contrast"  href="#" id="a_c"><span>A</span></a>
			</li>
          </ul>
        </div>
    </div>
</div>
<script nonce='<?php echo $non; ?>'>
document.getElementById("a").addEventListener("click", function() {
	changeFontSize('content','2')
});
document.getElementById("a_p").addEventListener("click", function() {
	resetFontSize('content');
	resetFontSizet('content');
});
document.getElementById("a_m").addEventListener("click", function() {
	changeFontSize1('content','-2');
});
document.getElementById("a_c").addEventListener("click", function() {
	set_all_colour();
});
</script>
<div class="globalnav-bg">
<? include("header _status.php");  ?>
<? //("header_marquee.php");  ?>

<div class="scroo_1" id="mark">
<div>
		<a href="index.php" class="scroo_2 note_home scroo_211" style="background-color: white; color: #e1297f;text-decoration: none;"><!--span class="glyphicon glyphicon-home  note_home" style="color: #e1297f;margin-left: 12px;"></span--><img src="theme/images/icon_home.png" class="home_img" > <span><?php echo $lang['HOME'];?></span></a>
</div>
<script nonce='<?php echo $non; ?>'>
$(document).ready(function(){//javascript:reloadCaptcha_off()
 document.getElementById("marq123").onmouseover = function(){
	this.stop();
};
document.getElementById("marq123").onmouseout = function(){
	this.start();
}; 
});
</script>
	<div class="scroo_3 " >
	<marquee  id="marq123" style="font-size:15px;"><?php echo $lang['SM_MSG1'];?></marquee>
	</div>

	<div class="scroo_4">
	<p id='more' class="scroo_2_m note_home scroo_2_m11" style="background-color: white;color: #e1297f;width: 8vw;"><img src="theme/images/icon_gallery.png" class="gallery_img" ><!--span class="glyphicon glyphicon-align-justify" style="margin-left: 12px;"></span --> <?php echo $lang['MORE'];?> </p>
	
	</div>
	
	<div style="clear:both;"></div>
	<div id="mySidenav" class="sidenav" >
  <a id='closeb' href="#" class="closebtn">x</a>
  <a id="sn1" href="photogallery.php" ><?php echo $lang['PHOTO_GALLERY2']; ?></a>
  <a id="sn1" href="Terms_Conditions.php" ><?php echo $lang['TERMS_CONDITIONS']; ?></a>
  <a id="sn1" href="faq.php" ><?php echo $lang['FAQ']; ?></a>
  <a id="sn1" href="help.php" ><?php echo $lang['HELP']; ?></a>
  <!--<a href="contact_us.php" ><?php //echo $lang['CONTACT_US']; ?></a> -->
  <!--a href="feed_back.php" ><?php //echo $lang['FEED_BACK']; ?></a-->
</div>
</div>

<!--<div class="topnav-centered" id="loginme">
          
			<div class="required-field-block animated bounceInRight leftthing"  onmouseover = "show0()" onmouseout="show_out0(this)"  data-toggle="modal" data-target="#offical">
			<div class="position_a3 ma_log" id="m1">Officials Login</div>
			<img src="theme/images/us11.png"  alt="Grievanceimg" rel="noopener" title="Click here to Login ..."  draggable="false" style="height:8vh;width:16vw;" >
			</div>
			<div class="required-field-block animated bounceInRight centerthing"  onmouseover = "show()" onmouseout="show_out(this)" data-toggle="modal" data-target="#myModal">
			<div class="position_a3 ma_log " id="m2">Petition Status</div>
			<img src="theme/images/us222.png" rel="noopener" title="Click here to Check Petition Status ..."  alt="Grievanceimg"  draggable="false" style="height:8vh;width:16vw;" >
			</div>
			<div class="required-field-block animated bounceInRight rightthing"  onmouseover = "show1()" onmouseout="show_out1(this)" data-toggle="modal" data-target="#online">
			<div class="position_a3 ma_log " id="m3">Online Petition</div>
			<img src="theme/images/us333.png" rel="noopener" title="Click here to submit Online Petitions - Only for the Public"  alt="Grievanceimg" draggable="false" style="height:8vh;width:16vw;">
			</div>
			

        </div>-->
<script nonce='<?php echo $non; ?>'>
function openNav() {
    document.getElementById("mySidenav").style.width = "275px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
window.addEventListener("click", function (event) {
var left = event.pageX, top = event.pageY;
 if(left<1000){
  closeNav();
 }
});
</script>
<style>

.slideshow-container img {
  width: 100%;
  height: auto;
  position:relative;
}
.topnav1 {
  overflow: hidden;
  background-color:  #f2dfad;
  padding: 0px;
  line-height: 0px;
  border-bottom: 1px solid #fff;
}

.topnav1 a {
  float: left;
  display: block;
  color: red;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  border: 1px solid #C0C0C0;
}

.topnav1 a:hover {
  background-color: #c3b091;
  color: black;
}

.topnav1 a.active {
  background-color: #4CAF50;
  color: white;
}

.topnav1 .icon {
  display: none;
}

#sn1{
	color:#ff4c00;
}
@media screen and (min-width: 800px) {
#lim {
  border: 1px solid #C0C0C0;
}
}
@media screen and (max-width: 800px) {
  .topnav1 a:not(:first-child) {display: none;}
   .topnav1 nav.icon,.topnav1 a.icon{
    float: right;
    display: block;
	border:0px;
  }
  .topnav1 nav{
	  border:0px;
  }
}

@media screen and (max-width: 800px) {
  .topnav1.responsive {position: relative;}
  .topnav1.responsive .icon {
    position: absolute;
   // right: 0;
    top: 0;
  }
  .topnav1.responsive a,nav {
    float: none;
    display: block;
    //text-align: left;
  }
}

body:not(.modal-open){
  padding-right: 0px !important;
}

.test.modal-open {
    overflow: auto;
 }
</style>

<div id="mark_menu" class="topnav1 responsive">

  
  <a href="index.php">Home</a>
 
  <a href="photogallery.php" ><?php echo $lang['PHOTO_GALLERY2']; ?></a>
  <a href="Terms_Conditions.php" ><?php echo $lang['TERMS_CONDITIONS']; ?></a>
  <a href="faq.php" ><?php echo $lang['FAQ']; ?></a>
  <a href="help.php" ><?php echo $lang['HELP']; ?></a>
</div>
<div class="topnav responsive" id="myTopnav" style="text-align:center;margin:1px">

  <a><img src="<?php echo $lang['off_log_img'];?>"  alt="Grievanceimg" rel="noopener" title="Click here to Login ..."  draggable="false" style="height:10vh;width:100vw;overflow:visible;position:static;z-index: 201;top:175px;" data-toggle="modal" data-target="#offical"></a>
  <a><img src="<?php echo $lang['off_log_img1'];?>" rel="noopener" title="Click here to Check Petition Status ..."  alt="Grievanceimg"  draggable="false" style="height:10vh;width:100vw;overflow:visible;position:relative;z-index: 201;top:2px;" data-toggle="modal" data-target="#myModal"></a>
  <a><img src="<?php echo $lang['off_log_img2'];?>" rel="noopener" title="Click here to submit Online Petitions - Only for the Public"  alt="Grievanceimg" draggable="false" style="height:10vh;width:100vw;overflow:visible;position:relative;z-index: 201;top:4px;" data-toggle="modal" data-target="#online"></a>
  </a>
</div>

<script nonce='<?php echo $non; ?>'>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>
<section class="wrapper-container banner-wrapper" id="happay_hiden" >
    <div id="service_flexslider" class="flexslider slideshow-container">
       <ul class="slides" >
        	<li class="pos_rel">
				<img src="images/slide/s1.jpg" alt = "slide1" class="img_banner">
			</li >
        	<li class="pos_rel">
				<img src="images/slide/s2.jpg" alt = "slide2" class="img_banner">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/s3.jpg" alt = "slide3" class="img_banner">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/s4.jpg" alt = "slide4" class="img_banner">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/s5.jpg" alt = "slide5" class="img_banner" >
			</li>
			<li class="pos_rel">
				<img src="images/slide/s6.jpg" alt = "slide6" class="img_banner">
			</li>
			<li class="pos_rel">
				<img src="images/slide/s7.jpg" alt = "slide7" class="img_banner">
			</li>
       </ul>
    </div>
<script nonce='<?php echo $non; ?>'>
$(document).ready(function(){//javascript:reloadCaptcha_off()
 document.getElementById("loginme1").onmouseover = function(){
	show0();
};
document.getElementById("loginme1").onmouseout = function(){
	show_out0(this);
}; 
 document.getElementById("loginme2").onmouseover = function(){
	show();
};
document.getElementById("loginme2").onmouseout = function(){
	show_out(this);
}; 
});
</script>
	<div class="required-field-block animated bounceInRight leftthing " data-toggle="modal" data-target="#offical">
			<!--div class="position_a3 ma_log " id="m1" >Officials Login</div-->
			<img src="<?php echo $lang['off_log_img'];?>"  id="loginme1" alt="Grievanceimg" rel="noopener" title="Click here to Login ..."  draggable="false" style="height:6vh;width:18vw;overflow:visible;position:static;z-index: 201;" >
			</div>
			<div class="required-field-block animated bounceInRight rightthing"  id="loginme2" data-toggle="modal" data-target="#myModal">
			<!--div class="position_a3 ma_log " id="m2">Petition Status</div-->
			<img src="<?php echo $lang['off_log_img1'];?>" rel="noopener" title="Click here to Check Petition Status ..."  alt="Grievanceimg"  draggable="false" style="height:6vh;width:18vw;overflow:visible;position:relative;z-index: 201;" >
			</div>
			<!--<div class="required-field-block animated bounceInRight rightthing"  id="loginme3" onmouseover = "show1()" onmouseout="show_out1(this)" data-toggle="modal" data-target="#online">-->
			<!--div class="position_a3 ma_log " id="m3">Online Petition</div-->
			<!--<img src="<?php echo $lang['off_log_img2'];?>" rel="noopener" title="Click here to submit Online Petitions - Only for the Public"  alt="Grievanceimg" draggable="false" style="height:6vh;width:18vw;overflow:visible;position:relative;z-index: 201;">
			</div>-->
<div class="reg_box_but">
<button class="reg_btn reg_btn_res" id="imgget" style="position: inherit;"><?php echo $lang['GALLERY'];?> </button>
</div>

<!--<div class="container" >
        <div class="reg_box  animated bounceInRight">
		<h3 class="r_title" ><?php //echo $lang['Main_Menu_TITLE']; ?><span class="title_light"></span> </h3>
		<div class="required-field-block animated bounceInRight"  onmouseover = "show0()" onmouseout="show_out0(this)"  data-toggle="modal" data-target="#offical">
			<div class="position_a3 ma_log" id="m1"><?php //echo $lang['DEPARTMENT_OFFICIALS_LOGIN_MENU_BUTTON']; ?></div>
			<img src="theme/images/us11.png"  alt="Grievanceimg" rel="noopener" title="Click here to Login ..."  draggable="false" >
		</div>
		 
		<div class="required-field-block animated bounceInRight"  onmouseover = "show()" onmouseout="show_out(this)" data-toggle="modal" data-target="#myModal">
			<div class="position_a3 ma_log" id="m2"><?php //echo $lang['CHECK_PETITION_STATUS_MENU_BUTTON']; ?></div>
			<img src="theme/images/us222.png" rel="noopener" title="Click here to Check Petition Status ..."  alt="Grievanceimg"  draggable="false" >
		</div>
		
		<div class="required-field-block animated bounceInRight"  onmouseover = "show1()" onmouseout="show_out1(this)" data-toggle="modal" data-target="#online">
			<div class="position_a3" id="m3"><?php //echo $lang['ONLINE_PETITION_MENU_BUTTON']; ?></div>
			<img src="theme/images/us333.png" rel="noopener" title="Click here to submit Online Petitions - Only for the Public"  alt="Grievanceimg" draggable="false" >
		</div>
		
		<div class="required-field-block animated bounceInRight"  onmouseover = "show2()" onmouseout="show_out2(this)" data-toggle="modal" data-target="#nri"><a href="https://locahost/police/nri/">
			<div class="position_a3" id="m3"><?php //echo $lang['NRI_ONLINE_PETITION_MENU_BUTTON']; ?></div>
			<img src="theme/images/nri.png" rel="noopener" title="Click here to submit NRI Online Petitions - Only for the Public"  alt="Grievanceimg" draggable="false" ></a>
		</div>
		
        </div>        
 </div>-->
 <div id="copy" class="pos_rel1_v" style="display:none;" style="position:auto;">
		<div class="pos_abs1_v_t"> 
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
					<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_copy_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC1']; ?><?php echo $lang['SLIDE_copy_policy_DESC2']; ?><?php echo $lang['SLIDE_copy_policy_DESC3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC3']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div> 
	<div id="service" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
					<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SERVICE_DESK_TITLE1']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK3']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<div id="service_m" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SERVICE_DESK_TITLE1']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK2']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<div id="privacy_policy" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Policy_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_3']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	 <div id="Hyper_Linking" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_h">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Hyperlink_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_1']; ?></p> 
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_2']; ?></p> 
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_4']; ?></p> 
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	 <div id="Disclaimer" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Disclaimer_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_4']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_5']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<style>
	.col_dd
	{
		color: #000000 !important;
		//font-size: 14px !important;
	}
	.mar{
		margin-top:20px;
	}
	</style>
 <div id="team" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t">
				<h1 class="ppp animated flipInY col_dd" ><?php echo $lang['SLIDE_Team_Conditions_TITLE']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC1']; ?></p>
				<h1 class="ppp animated flipInY col_dd" style="font-size: 15px;"><?php echo $lang['SLIDE_Team_Conditions_TITLE1']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC2']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC3']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC4']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC5']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC6']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC7']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC8']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC9']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC10']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC11']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC12']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC13']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC14']; ?></p>
	    </div>
			<img src="theme/images/11.jpg" alt = "slide3"  class="sw">
		</div>
	</div>
	
  <div id="img0" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v1 ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_Officials_Login_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_Officials_Login_DESC']; ?></p>
					</div>
			<img src="theme/images/22.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
 <div id="img1" class="pos_rel1_v " style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_ONLINE_STATUS_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_ONLINE_STATUS_DESC']; ?></p>
					</div>
			<img src="theme/images/33.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
	 <div id="img2" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_ONLINE_PETITION_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_ONLINE_PETITION_DESC']; ?></p>
					</div>
			<img src="theme/images/44.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
 <div id="img3" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['NRI_hov1']; ?></h1>
						<p><?php echo $lang['NRI_hov2']; ?></p>
					</div>
			<img src="theme/images/55.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
</section>


<section class="wrapper banner-wrapper"  id="happay_hiden1" style="display:none;">
    <div id="gallery_flexslider" class="flexslider" >
       <ul class="slides" >
	   <li class="pos_rel">
				<img src="images/slide/slider1.jpg" alt = "slide6" class="img_banner1">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/slider2.jpg" alt = "ministerimg" class="img_banner1">
			</li >
        	<li class="pos_rel">
				<img src="images/slide/slider3.jpg" alt = "slide2" class="img_banner1">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/slider4.jpg" alt = "slide3" class="img_banner1">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/slider5.jpg" alt = "slide4" class="img_banner1">
			</li>
        	<li class="pos_rel">
				<img src="images/slide/slider6.jpg" alt = "slide5" class="img_banner1" >
			</li>
			<!--li class="pos_rel">
				<img src="theme/images/banner/slide6_gallery.jpg" alt = "slide6" class="img_banner1">
			</li-->
       </ul>
    </div>
<div class="reg_box_but">
	<button class="reg_btn reg_btn_res1" id="imgget1" style="position: inherit;"> <?php echo $lang['Service'];?> </button>
</div>

 <div id="copy1" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_copy_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC1']; ?><?php echo $lang['SLIDE_copy_policy_DESC2']; ?><?php echo $lang['SLIDE_copy_policy_DESC3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_copy_policy_DESC3']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div> 
	<div id="service" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SERVICE_DESK_TITLE1']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK2']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<div id="privacy_policy1" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Policy_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Policy_policy_3']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	 <div id="Hyper_Linking" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_h">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Hyperlink_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_1']; ?></p> 
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_2']; ?></p> 
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_4']; ?></p> 
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div> 
	<div id="Hyper_Linking1" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_h">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Hyperlink_policy_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_1']; ?></p> 
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_2']; ?></p> 
						<p class="c animated flipInX col_dd" style="font-weight: bold;"><?php echo $lang['SLIDE_Hyperlink_Policy_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Hyperlink_Policy_4']; ?></p> 
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	 <div id="Disclaimer" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Disclaimer_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_4']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_5']; ?></p>
						
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div> 
	<div id="Disclaimer1" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SLIDE_Disclaimer_TITLE']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_2']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_3']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_4']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SLIDE_Disclaimer_5']; ?></p>
						
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<div id="service_desk_c" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t ma_ce8_c">
						<h1 class="ppp s animated flipInY col_dd"><?php echo $lang['SERVICE_DESK_TITLE1']; ?></h1>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK1']; ?></p>
						<p class="c animated flipInX col_dd"><?php echo $lang['SERVICE_LINK2']; ?></p>
					</div>
			<img src="theme/images/11.jpg" alt = "slide3" class="sw">
		</div>
	</div>
	<style>
	.col_dd
	{
		color: #000000 !important;
		/*font-size: 14px !important;*/
	}

	</style>
 <div id="team" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t">
				<h1 class="ppp animated flipInY col_dd" ><?php echo $lang['SLIDE_Team_Conditions_TITLE']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC1']; ?></p>
				<h1 class="ppp animated flipInY col_dd" style="font-size: 15px;"><?php echo $lang['SLIDE_Team_Conditions_TITLE1']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC2']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC3']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC4']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC5']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC6']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC7']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC8']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC9']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC10']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC11']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC12']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC13']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC14']; ?></p>
	    </div>
			<img src="theme/images/11.jpg" alt = "slide3"  class="sw">
		</div>
	</div>
	<div id="team1" class="pos_rel1_v" style="display:none;">
		<div class="pos_abs1_v_t">
		<div class="pos_abs_t ma_ce7 ma_ce8_t">
				<h1 class="ppp animated flipInY col_dd" ><?php echo $lang['SLIDE_Team_Conditions_TITLE']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC1']; ?></p>
				<h1 class="ppp animated flipInY col_dd" style="font-size: 15px;"><?php echo $lang['SLIDE_Team_Conditions_TITLE1']; ?></h1>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC2']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC3']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC4']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC5']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC6']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC7']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC8']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC9']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC10']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC11']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC12']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC13']; ?></p>
				<p class="animated flipInX col_dd"><?php echo $lang['SLIDE_Team_Conditions_DESC14']; ?></p>
	    </div>
			<img src="theme/images/11.jpg" alt = "slide3"  class="sw">
		</div>
	</div>
	
  <div id="img0" class="pos_rel1_v mar" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v1 ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_Officials_Login_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_Officials_Login_DESC']; ?></p>
					</div>
			<img src="theme/images/22.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
 <div id="img1" class="pos_rel1_v mar" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_ONLINE_STATUS_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_ONLINE_STATUS_DESC']; ?></p>
					</div>
			<img src="theme/images/33.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
	 <div id="img2" class="pos_rel1_v mar" style="display:none;">
		<div class="pos_abs1_v_1">
		<div class="pos_abs_v ma_ce7 ma_ce8">
						<h1 class="ppp"><?php echo $lang['SLIDE_ONLINE_PETITION_TITLE']; ?></h1>
						<p><?php echo $lang['SLIDE_ONLINE_PETITION_DESC']; ?></p>
					</div>
			<img src="theme/images/44.jpg" alt = "slide3" class="img_banner">
		</div>
	</div>
</section>



<script nonce='<?php echo $non; ?>'>
/* document.getElementById("v27").on("mouseout", function() {
	document.getElementById('team').style.display = 'none';
}); */

$(document).ready(function(){
document.getElementById("v27").onmouseover = function(){
	document.getElementById('team').style.display = 'block';
};
document.getElementById("v27").onmouseout = function(){
	document.getElementById('team').style.display = 'none';
};
document.getElementById("v26").onmouseover = function(){
	document.getElementById('Hyper_Linking').style.display = 'block';
};
document.getElementById("v26").onmouseout = function(){
	document.getElementById('Hyper_Linking').style.display = 'none';
};
document.getElementById("v25").onmouseover = function(){
	document.getElementById('copy').style.display = 'block';
};
document.getElementById("v25").onmouseout = function(){
	document.getElementById('copy').style.display = 'none';
};
document.getElementById("v24").onmouseover = function(){
	document.getElementById('privacy_policy').style.display = 'block';
};
document.getElementById("v24").onmouseout = function(){
	document.getElementById('privacy_policy').style.display = 'none';
};
document.getElementById("v23").onmouseover = function(){
	document.getElementById('Disclaimer').style.display = 'block';
};
document.getElementById("v23").onmouseout = function(){
	document.getElementById('Disclaimer').style.display = 'none';
};
document.getElementById("v35").onmouseover = function(){
	document.getElementById('service').style.display = 'block';
};
document.getElementById("v35").onmouseout = function(){
	document.getElementById('service').style.display = 'none';
};
document.getElementById("more").onclick = function(){
	openNav();
};
document.getElementById("closeb").onclick = function(){
	closeNav();
};
document.getElementById("closeb1").onclick = function(){
	this.parentElement.style.display='none';
};
document.getElementById("closeb1").onclick = function(){
	this.parentElement.style.display='none';
};
document.getElementById("security_code").onKeyPress = function(){
	 return buttonsubmit1(event);
};
document.getElementById("security_code").onblur = function(){
	 checkValue();
};
document.getElementById("security_code_offical").onKeyPress = function(){
	 return buttonsubmit3(event);
};
document.getElementById("username").onchange = function(){
	  username_chk();
};
document.getElementById("petition_no").onblur = function(){
	  killChars(this);
};
});
</script>
<div class="all_footer" id="he_colour3">
	<div class="nic_link">
		<p id="v27"><?php echo $lang['TEAMS_CONDITION']; ?></p>
	</div>
	<div class="nic_link1">
		<p id="v26"><?php echo $lang['HYPERLINK_POLICY']; ?></p>
	</div>
	<div class="nic_cen">
	<p id="v25"><?php echo $lang['COPYRIGHT_POLICY']; ?></p>
	</div>
	
	<div class="nic_cen1">
	<p id="v24"><?php echo $lang['PRIVACY_POLICY']; ?></p>
	</div>
	<div class="link">
		<div class="txt_r">
           <p id="v23"><?php echo $lang['DISCLAMIER']; ?></p>
		</div>
	</div>
	<div class="link">
		<div class="txt_r">
           <a target="_blank" target="_blank" style="text-decoration: none;" rel="noopener" href="https://servicedesk.nic.in/" title="Click here to visit - servicedesk.nic.in"> <p id="v35"><?php echo $lang['SERVICE_DESK']; ?></p></a>
		</div>
	</div>
</div>

<div class="all_footer1" id="he_colour4">
	<div class="nic_link_f">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.tn.nic.in" style="text-decoration: none;"><img src="theme/images/nic_tn.png" title="Click here to visit - www.tn.nic.in" rel="noopener" style="width: 7vw;"> </a>
	&nbsp;&nbsp;&nbsp;
	</div>
	<div class="nic_cen_f">
	<p id="v22" class="col_w" style="font-weight: bold;color:#fff;"><span id="v28"  style="font-weight: bold;"><?php echo $lang['SERVICE_NIC']; ?></span><br><?php echo $lang['SERVICE_NIC1']; ?><?php echo $lang['SERVICE_NIC2']; ?>&nbsp;<?php echo $lang['Browsers_Version']; ?>|<?php echo $lang['SERVICE_DESK_UPDATE']; ?>&nbsp;&nbsp;<?php echo $lang['SERVICE_DESK_DATE']; ?>&nbsp;&nbsp;<?php echo $lang['SERVICE_DESK_DATE1']; ?>  </p>
	</div>
	<div class="link_f">
		<div class="txt_r">
		<!--a target="_blank" target="_blank" style="text-decoration: none;" rel="noopener" href="https://servicedesk.nic.in/" title="Click here to visit - servicedesk.nic.in"><img src="theme/images/nsd.png" style="width: 25%;"> </a-->
	&nbsp;&nbsp;&nbsp;
	<a target="_blank" href="http://www.nic.in" title="Click here to visit - www.nic.in"><img src="theme/images/logo.png" style="width: 7vw;height: 2.5vw;"> </a>
		</div>
	</div>
</div>

<input type="hidden" name="lang" id="lang" value="<?php echo $_SESSION["lang"];?>" />

<!-- Modal POPUP Official Login Start-->
<form id="login_form_offcials" name="login_form_offcials" action="LoginActionofficals.php" method="post">
 <div class="modal fade" id="offical" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm check_pet reg_box2">
      <div class="modal-content ba_co3">
        <div class="modal-header">
		<button type="button" class="btn btn-default x_v" title="Click here to Close"  data-dismiss="modal">X</button>
          <!--button type="button" class="close int_close" data-dismiss="modal">&times;</button-->
          <h3 class="r_title1"><?php echo $lang['DEPARTMENT_OFFICIALS_LOGIN_MENU_BUTTON']; ?><br> <span class="title_light"></span></h3>
        </div>
		 <?php
          $ptoken = hash('sha256',session_id() . $_SESSION['salt']);
		  $_SESSION['formptoken']=$ptoken;
        ?>
		<input type="hidden" name="encr" id="encr" value="<?php echo($ptoken);?>" />
		<div>
		
       <div class="modal-body note_col">
		<div class="required-field-block">
         <input type="text" id="username" class="login_val" name="username" title="User Id" placeholder="<?php echo $lang['USER_ID_TITLE']; ?>" maxlength="30"  autocomplete="off" autofocus / >
		 <div class="required-icon"> <div class="text">*</div> </div>
		</div>
       </div>
	   <div style= "margin-top: -20px;"></div> 
	   <div class="modal-body note_col">
		<div class="required-field-block">
         <input type="password" id="pwd" class="login_val" name="pwd"  title="password" placeholder="<?php echo $lang['PASSWORD_TITLE']; ?>" maxlength="30"  autocomplete="off" autofocus / ><i class="glyphicon glyphicon-eye-open" id="togglePassword" style="margin-left: -50px;font-size: large;color: #828295; cursor: pointer;margin-top: 11px;position: relative;z-index: 2;"></i>
		 <div class="required-icon"> <div class="text">*</div> </div>
		</div>
       </div>
	  <div style= "margin-top: -20px;"></div> 
	 
	<div class="modal-body note_col">
	  <div class="required-field-block txt_wid">
         <input type="text" class="login_val1" id="security_code_offical" name="security_code_offical" title="Enter Security Code"  placeholder="<?php echo $lang['SECURITY_CODE_TITLE']; ?>" maxlength="6"   autocomplete="off"  / >
		 <div class="required-icon rg_zero1"> <div class="text">*</div> </div>
	  </div>
	 
	  <div class="txt_wid1">
	  <a HREF="#" id="relo"><img src="theme/images/reload.png" class="re_img" alt ="reload" rel="noopener" title="Clik to Reload"></a>
	  </div>
	  <div class="txt_wid2">
	  <img src="captcha_off.php" class="bt_se" id="captcha_off"  alt ="captcha" > 
	  <input type="hidden" name="capchaval1" id="capchaval" value="<?php echo substr($_SESSION['key'],0,5); ?>" />
	  </div>
	  </div>
	  
		<input type="hidden" name="lang" value="E" />
	  
        <div class="modal-footer">
         <button type="button" class="reg_btn" id="sub_mit" title="Click here to Submit"><?php echo $lang['SUBMIT_BUTTON_TITLE']; ?></button>
		  <button type="button" class="reg_btn fn_cl" id="reset_off" title="Click here to Reset" ><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
        </div>
		
		</div>
		
      </div>
    </div>
  </div>
  
   <!--input type="hidden" name="lang" value="E" /-->
  </form>
  
  	<form name="view_status" id="view_status" action="index.php" method="post">
	 <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm check_pet reg_box2">
      <div class="modal-content ba_co1">
        <div class="modal-header">
		<button type="button" class="btn btn-default x_v"  title="Click here to Close"  data-dismiss="modal">X</button>
          <!--button type="button" class="close int_close" data-dismiss="modal">&times;</button-->
          <h3 class="r_title1"><?php echo $lang['LOGIN_TITLE']; ?><br> <span class="title_light"></span> </h3>
        </div>
		
       <div class="modal-body note_col">
		<div class="required-field-block">
         <input type="text" id="petition_no" class="login_val" name="petition_no"  title="Enter Petition Number" placeholder="<?php echo $lang['PETITION_NO_TITLE']; ?>" maxlength="30"  autocomplete="off" autofocus / >
		 <div class="required-icon"> <div class="text">*</div> </div>
		</div>
       </div>
	  <div style= "margin-top: -20px;"></div> 
	 
	<div class="modal-body note_col">
	  <div class="required-field-block txt_wid">
         <input type="text" class="login_val1" id="security_code" name="security_code" title="Enter Security Code"  placeholder="<?php echo $lang['SECURITY_CODE_TITLE']; ?>" maxlength="6" autocomplete="off"/ >
		 <div class="required-icon rg_zero1"> <div class="text">*</div> </div>
	  </div>
	 
	  <div class="txt_wid1">
	  <a HREF="#" id="relo2"><img src="theme/images/reload.png" class="re_img" rel="noopener" alt ="reload" title="Clik to Reload"></a>
	  </div>
	  <div class="txt_wid2">
	  <img src="captcha.php" class="bt_se" id="captcha"  alt ="captcha" > 
  <input type="hidden" name="capchaval" id="capchaval" value="<?php echo $ResultStr; ?>" />
	  </div>
	  
	  </div>
	 
  
	<div class="footer1" >
	 <div class="alert" id="alertDiv"  for="msg1" style="display:none;">							  
	 <font></font>&nbsp 
		 <span class="closebtn" id='closeb1'>&times;</span> 
	</div>	
	</div>	
		
	  
        <div class="modal-footer">
         <button type="button" class="reg_btn" id="sub_mit1" title="Click here to Submit" ><?php echo $lang['SUBMIT_BUTTON_TITLE']; ?></button>
		  <button type="button" class="reg_btn fn_cl" id="reset_s" title="Click here to Reset" ><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
        </div>
		
      </div>
    </div>
  </div>
 <!-- Modal POPUP End Status-->
</form>
  <form id="login_form" name="login_form" action="LoginAction_online.php" method="post">
 <!-- Modal POPUP Start Online-->
	
	 <div class="modal fade" id="online" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm check_pet reg_box2">
      <div class="modal-content ba_co2">
        <div class="modal-header">
          <button type="button" class="btn btn-default x_v" title="Click here to Close" data-dismiss="modal" >X</button>
          <h3 class="r_title1"><?php echo $lang['LOGIN_TITLE_ONLINE']; ?><br> <span class="title_light"></span> </h3>
        </div>
		 <div class="al_c">
       <div class="modal-body note_col">
		<div class="required-field-block">
         <input type="text" id="mobilenumber" class="login_val" name="mobilenumber"  title="Enter Mobile Number" placeholder="<?php echo $lang['MOBILE_NO_TITLE']; ?>" maxlength="10"  autocomplete="off" autofocus / >
		 <div class="required-icon"> <div class="text">*</div> </div>
		</div>
       </div>
	  <div style= "margin-top: -20px;"></div> 
	 
	<div class="modal-body note_col">
	  <div class="required-field-block txt_wid">
         <input type="text" class="login_val1" id="security_code_online" name="security_code" title="Enter Security Code"  placeholder="<?php echo $lang['SECURITY_CODE_TITLE']; ?>" maxlength="6"   autocomplete="off" / >
		 <div class="required-icon rg_zero1"> <div class="text">*</div> </div>
	  </div>
	 
	  <div class="txt_wid1">
	  <a HREF="javascript:reloadCaptcha_online()"><img src="theme/images/reload.png" class="re_img" alt ="reload" rel="noopener" title="Clik to Reload"></a>
	  </div>
	  <div class="txt_wid2">
	  <img src="captcha_online.php" class="bt_se" id="captcha_online"  alt ="captcha" > 
  <input type="hidden" name="capchaval2" id="capchaval2" value="<?php echo $ResultStr; ?>" />
	  </div>
	  
	  </div>
	  <div class="modal-body note_col">
	  <div class="required-field-block txt_wid">
        <!-- <input type="password" disabled="disabled" class="login_val1"onblur="killChars(this);" id="otp" name="otp" maxlength="6" title="Enter OTP" placeholder="<?php //echo $lang['OTP_TITLE']; ?>" 
			onKeyPress="return buttonsubmit2(event);  "  
			onKeyUp="return numbersonly_ph1(event);" autocomplete="off" autofocus >-->
		<input type="password" class="login_val1" id="otp" name="otp" maxlength="6" title="Enter OTP" placeholder="<?php echo $lang['OTP_TITLE']; ?>" autocomplete="off" autofocus >	
			
		 <div class="required-icon rg_zero1"> <div class="text">*</div> </div>
	  </div>
	  
<script nonce='<?php echo $non; ?>'>
$(document).ready(function(){
document.getElementById("mobilenumber").onchange = function(){
	mob_chk();
};
document.getElementById("mobilenumber").onKeyPress = function(){
	return numbersonly_ph1(event);
};
document.getElementById("otp").onKeyPress = function(){
	return buttonsubmit2(event);
};
document.getElementById("otp").onKeyUp = function(){
	return numbersonly_ph1(event);
};
document.getElementById("otp").onblur = function(){
	killChars(this);
};
document.getElementById("generate_otp").onclick = function(){
	checkAndSaveMobile();
};
document.getElementById("resend_otp").onclick = function(){
	resendOtp();
};
});
</script>
	<div class="ge_otp_wi">
		  <button type="button" class="ge_otp " id="generate_otp" title="Click here to Generate OTP"><?php echo $lang['SEND_OTP_BUTTON_TITLE']; ?></button>
		   <button type="button" class="ge_otp " id="resend_otp" style="display:none;" title="Click here to Resend OTP"><?php echo $lang['RESEND_OTP_BUTTON_TITLE']; ?></button>
		 </div>
	  </div>
	  
	 
    </div>
	<div class="footer1" >
	 <div class="alert" id="alertDiv"  for="msg1" style="display:none;">							  
	 <font></font>&nbsp 
		 <span class="closebtn" id="closeb2">&times;</span> 
	</div>	
	</div>	
		
	  
<script nonce='<?php echo $non; ?>'>
$(document).ready(function(){//javascript:reloadCaptcha_off()
document.getElementById("sub_mit").onclick = function(){
	return validateFormofficals('<?php echo $_SESSION['salt'];?>','<?php echo $_SESSION['itno'];?>','<?php echo $_SESSION['itno'];?>');
};
document.getElementById("sub_mit1").onclick = function(){
	return checkValue();
};
document.getElementById("sub_mit2").onclick = function(){
	validateForm();
};
document.getElementById("reset_off").onclick = function(){
  document.getElementById("username").value="";
  document.getElementById("pwd").value="";
  document.getElementById("security_code_offical").value="";
  document.getElementById("login_form_offcials").reset();
};
document.getElementById("reset_on").onclick = function(){
  document.getElementById("resend_otp").innerHTML = "Generate OTP"; 
  document.getElementById("mobilenumber").value="";
  document.getElementById("security_code_online").value="";
  document.getElementById("otp").value="";
  document.getElementById("login_form").reset();
};
document.getElementById("reset_s").onclick = function(){
  document.getElementById("petition_no").value="";
  document.getElementById("view_status").reset();
};
});
</script>
        <div class="modal-footer">
         <button type="button" class="reg_btn" id="sub_mit2" title="Click here to Submit"><?php echo $lang['SIGNIN_BUTTON_TITLE']; ?></button>
		  <button type="button" class="reg_btn fn_cl" id="reset_on" title="Click here to Reset"><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
        </div>
		
      </div>
    </div>
  </div>
  
  <!-- Modal POPUP Start-->
	
	 <div class="modal fade" id="myModal2" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="user_name_not_valid" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col2 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['USER_NAME_NOT_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
	 <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col2 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_LENGTH_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
   <div class="modal fade" id="myModal1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title note_col"><?php echo $lang['MODAL_MOBILE_NO_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ALLOWED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
 
  <div class="modal fade" id="myModal9" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="myModal10" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
    <div class="modal fade" id="myModal_nri" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="officals_security_code" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
	
	<div class="modal fade" id="myModal11" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="myModal6" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col3 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_GENERATE_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="myModal7" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_LENGTH_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($_SESSION['error_msg'])) { ?>
	 <div class="modal fade" id="Login_Fail_Alert" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col bor-mod" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $_SESSION['error_msg'];//$lang['MODAL_OTP_LOGIN_FAILED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

    <?php //if (!(isset($_SESSION['error_msg']))) { ?>
	
    <!--div class="modal fade" id="myModal12" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['NOTE_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['NOTE_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div-->
  <?php //} ?>
  <?php unset($_SESSION['error_msg']); ?>
  
   <?php
  //if (isset($_SESSION['error_msg1'])) { ?>
	 <!--div class="modal fade" id="Login_Fail_Alert_username_password" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['MODAL_OTP_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_USERNAME_PASSWORD_LOGIN_FAILED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div-->
<?php //} ?>
  <?php //unset($_SESSION['error_msg1']); ?>
  <div class="modal fade" id="myModal13" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
	
  </div>
    <div class="modal fade" id="myModal14" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="myModal15" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_NOT_SENT_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
    <div class="modal fade" id="myModal16" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="myModal17" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php //echo zxcvzxcv;//$lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="not_sent" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_FILE']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal POPUP End-->
<!--p id="demo" ></p-->
<!-- Modal POPUP End Online--> 
</form>

	 <div class="modal fade" id="myModal2" role="dialog" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="user_name_not_valid" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col2 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['USER_NAME_NOT_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
	 <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col2 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_LENGTH_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
   <div class="modal fade" id="myModal1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title note_col"><?php echo $lang['MODAL_MOBILE_NO_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ALLOWED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
 
  <div class="modal fade" id="myModal9" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="myModal10" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
    <div class="modal fade" id="myModal_nri" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="officals_security_code" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_VALID_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
	
	<div class="modal fade" id="myModal11" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="myModal6" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col3 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_GENERATE_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="myModal7" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_mr_top">
      <div class="modal-content modal_col1 bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_LENGTH_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($_SESSION['error_msg'])) { ?>
	 <div class="modal fade" id="Login_Fail_Alert" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col bor-mod" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_LOGIN_FAILED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

    <?php //if (!(isset($_SESSION['error_msg']))) { ?>
	
    <!--div class="modal fade" id="myModal12" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['NOTE_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['NOTE_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div-->
  <?php //} ?>
  <?php unset($_SESSION['error_msg']); ?>
  
   <?php
  //if (isset($_SESSION['error_msg1'])) { ?>
	 <!--div class="modal fade" id="Login_Fail_Alert_username_password" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['MODAL_OTP_TITLE']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_USERNAME_PASSWORD_LOGIN_FAILED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div-->
<?php //} ?>
  <?php //unset($_SESSION['error_msg1']); ?>
  <div class="modal fade" id="myModal13" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_MOBILE_NO_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
	
  </div>
    <div class="modal fade" id="myModal14" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_SECURITY_CODE_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="myModal15" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_NOT_SENT_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
    <div class="modal fade" id="myModal16" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="myModal17" role="dialog">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php //echo zxcvzxcv;//$lang['MODAL_OTP_ENTER_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="not_sent" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_OTP_FILE']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal POPUP End-->
<!--p id="demo" ></p-->
<!-- Modal POPUP End Online--> 
</form>
<!-- Modal POPUP Officals Start--> 
<div class="modal fade" id="model_gallery" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col">Gallery</h4>
        </div>
        <div class="modal-body note_col">
          <p>Gallery</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="model_service" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col">Service</h4>
        </div>
        <div class="modal-body note_col">
          <p>Service</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
<div class="modal fade" id="officel_login_1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['User_ID']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="officel_login_2" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['User_characters_lessthan']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="officel_login_3" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['User_Name']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['User_characters_greaterthan']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="officel_password_1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['Password_ID']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="officel_password_2" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['Password_Name']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['Password_characters_lessthan']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="officel_password_3" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['Password_Name']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['Password_characters_greaterthan']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="officel_security_code" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['security_code_ID']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div> 
  <div class="modal fade" id="nri_login_1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['SELECT_COUNTRY_NAME']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="nri_login_2" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['ENTER_MOBILE_NO_NRI']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
 <div class="modal fade" id="nri_login_3" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['SECURITY_CODE_TITLE_NRI']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="india_nri" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width modal_mr_top">
      <div class="modal-content modal_col bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['India_2']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
  
<!-- Modal POPUP Officals End--> 


<input type="hidden" id="demo1" >
<input type="hidden" id="error_msg" name="error_msg" value="<?php echo $_SESSION['error_msg']; ?>">
  <input type="hidden" id="demo2" >
 <input type="hidden" id="error_msg1" name="error_msg1" value="<?php echo $_SESSION['error_msg1']; ?>">
  <input type="hidden" id="demo" >

  <input type="hidden" id="nri_d" >
 <?php
 if(isset($_SESSION['error_msg1']))
{
?>
<div class="modal fade" id="Login_Fail_Alert" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col bor-mod" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $_SESSION['error_msg1']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
<!--div align="center" >
<span style="color:#A94442;"><script nonce='<?php echo $non; ?>'>alert('<?php echo $_SESSION['error_msg1']; ?>');</script></span>
</div-->
<?php
unset($_SESSION['error_msg1']);
session_destroy();
header('Refresh:2,url=index.php');
}  
?>   
<?php
if(isset($_SESSION['error_msg1']))
{
?>
 <div class="modal fade" id="Login_Fail_Alert_username_password" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal_width">
      <div class="modal-content modal_col" style="margin-top: 199px !important;">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center note_col"><?php echo $lang['MODAL_USERNAME_PASSWORD_LOGIN_FAILED']; ?></h4>
        </div>
        <div class="modal-body note_col">
          <p><?php echo $lang['MODAL_USERNAME_PASSWORD_LOGIN_FAILED_DESC']; ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal"><?php echo $lang['OK_TITLE']; ?></button>
        </div>
      </div>
    </div>
  </div>
<?php
unset($_SESSION['error_msg1']);
//session_destroy();
//header('Refresh:2,url=index.php');
} 
?>  
  
<script nonce='<?php echo $non; ?>' type="text/javascript" src="assets/js/jquery.flexslider.js"></script>
<script nonce='<?php echo $non; ?>' src="bootstrap/js/modalbootstrap.min.js"></script>

<script nonce='<?php echo $non; ?>' type="text/javascript">
//$("#myTopnav").css("display", "none");

function selectNri() {
	var phonecode = document.getElementById('phonecode').value;
	if (phonecode == 99) {
		//alert("Citizens of India are allowed to enter petition through Online Petition!!");
		$('#india_nri').modal('show');
		$('#indian_nri').modal({ backdrop: 'static', keyboard: false })
		return false;
	} else {
		document.getElementById('mobilenumber_nri').trigger( "focus" );
		
	}
}
  function reloadCaptcha_nri(){
	document.login_form_nri.security_code_nri.value="";
	document.getElementById('captcha_nri').src = 'captcha_nri.php?' + Math.random();
}

function validateFormNri() {
	
	if($('#country_id').val()==""){
		$('#nri_login_1').modal('show');
		$('#nri_login_1').modal({ backdrop: 'static', keyboard: false })
		$('#phonecode').trigger( "focus" );
		return false;
	} else if($('#mobilenumber_nri').val() == "") {
		$('#nri_login_2').modal('show');
		$('#nri_login_2').modal({ backdrop: 'static', keyboard: false })
		$('#mobilenumber_nri').trigger( "focus" );
		return false;
	} else if($('#security_code_nri').val() == "") {
		$('#nri_login_3').modal('show');
		$('#nri_login_3').modal({ backdrop: 'static', keyboard: false })
		$('#security_code_nri').trigger( "focus" );

	} else {
		var tex_captch = document.getElementById("security_code_nri").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);
				if(myObj[0]=='F') {
					document.getElementById("security_code_nri").value="";
					//$('#security_code_nri').trigger( "focus" );
					//document.getElementById("nri_d").innerHTML = myObj[0];
					//alert("Error");
					$('#myModal_nri').modal('show');
					$('#myModal_nri').modal({ backdrop: 'static', keyboard: false })
					$('#security_code_nri').trigger( "focus" );
				} else {
					document.getElementById("country").value=$('#country_id').val();
					document.login_form_nri.submit();
				}
			}

		};
		xmlhttp.open("GET", "captcha_validation_nri.php?q="+tex_captch, true);
		xmlhttp.send();
	}
}
/*function validateFormNri()
{
	if($('#country_id').val()==""){
		$('#nri_login_1').modal('show');
		$('#nri_login_1').modal({ backdrop: 'static', keyboard: false })
		$('#phonecode').trigger( "focus" );
		return false;
	} else if($('#mobilenumber_nri').val() == "") {
		$('#nri_login_2').modal('show');
		$('#nri_login_2').modal({ backdrop: 'static', keyboard: false })
		$('#mobilenumber_nri').trigger( "focus" );
		return false;
	} else if($('#security_code_nri').val() == "") {
		$('#nri_login_3').modal('show');
		$('#nri_login_3').modal({ backdrop: 'static', keyboard: false })
		$('#security_code_nri').trigger( "focus" );

	} else {
		var tex_captch = document.getElementById("security_code_nri").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj[0]=='F') {
				document.getElementById("security_code_nri").value="";
				//$('#security_code_nri').trigger( "focus" );
				//document.getElementById("nri_d").innerHTML = myObj[0];
				//alert("Error");
				$('#myModal_nri').modal('show');
				$('#myModal_nri').modal({ backdrop: 'static', keyboard: false })
				$('#security_code_nri').trigger( "focus" );		
			} else {
				document.getElementById("country").value=$('#country_id').val();
				document.login_form_nri.submit();
			}
		}
	}
	
	
}
*/
  $('#flexCarousel').flexslider({
        animation: "slide",
        animationLoop: true,
        itemWidth: 200,
        itemMargin: 10,
        minItems: 2,
        maxItems: 5,
		slideshow: 1,
		move: 1,
		controlNav: false,
        start: function(slider){
          $('body').removeClass('loading');
		  if (slider.pagingCount === 1) slider.addClass('flex-centered');
        }
      });



/* Online Start */
var error_msg = document.getElementById("error_msg").value;
$(window).load(function()
{	
	$('#myModal12').modal('show');	
	
});
$('#myModal12').modal({
    backdrop: 'static',
    keyboard: false
})
	  function mob_chk() {
 mob_no=document.getElementById("mobilenumber").value.length;
	  if(mob_no < 10)
	 {
		$('#myModal').modal('show'); //Mobile Number cannot be less than 10 characters.
		$('#myModal').modal({ backdrop: 'static', keyboard: false })
		document.getElementById("mobilenumber").value="";
		document.getElementById("mobilenumber").trigger( "focus" );
		return false;
	 } 
}	 

//const togglePassword = document.querySelector();
  const password = document.querySelector('#pwd');

  $('#togglePassword').on('mousedown', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
}).on('mouseup mouseleave', function() {
        const type = password.getAttribute('type') === 'text' ? 'password' : 'password';
    password.setAttribute('type', type);
	
        });

function username_chk() {
	var username = document.getElementById("username").value;
	var expr = /^[a-zA-Z0-9_.]{5,30}$/;
	if (!expr.test(username)) { //user_name_not_valid
		$('#user_name_not_valid').modal('show'); //Mobile Number cannot be less than 10 characters.
		$('#user_name_not_valid').modal({ backdrop: 'static', keyboard: false })
		document.getElementById("username").value="";
		document.getElementById("username").trigger( "focus" );
	}
}

function numbersonly_ph(e,t)
{
	
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.trigger('blur');}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		
		if((unicode<48||unicode>57)&& unicode !=43) {
		    $('#myModal1').modal('show');
			$('#myModal1').modal({ backdrop: 'static', keyboard: false })
			document.getElementById("mobilenumber").value="";
			return false
		}
	}
}

    $("#imgget").on("click", function() {
		$("#happay_hiden1").css("display", "block");
		$("#happay_hiden").css("display", "none");
		$("#he_colour3").css("display", "none");
		$("#he_colour3_gallery").css("display", "block");	
		$("#loginme").css("display", "none")
				$("#myTopnav").css("display", "none");;
	
		
		$('#gallery_flexslider').flexslider({
			animation: "slide",
			controlNav: true,
			pausePlay: true,
			start: function(slider){
			$('body').removeClass('loading');
			}
		});

    });
	
	$("#imgget1").on("click", function() {
		$("#he_colour3").css("display", "block");
		$("#he_colour3_gallery").css("display", "none");
		$("#happay_hiden").css("display", "block");
		$("#happay_hiden1").css("display", "none");
		$("#loginme").css("display", "block");
				$("#myTopnav").css("display", "");
    });
function validateForm() {

	if($('#mobilenumber').val()=="" && $('#otp').val()==""){
	    $('#myModal2').modal('show');
		$('#myModal2').modal({ backdrop: 'static', keyboard: false })
		$('#mobilenumber').trigger( "focus" );
		return false;
	}
	else if($('#mobilenumber').val() == null || $('#mobilenumber').val() =="")
	{
	    $('#myModal3').modal('show');  
		$('#mobilenumber').trigger( "focus" );
		return false;
	}
	else if($('#mobilenumber').val().length < 10)
	{
		$('#myModal4l').modal('show');
		document.getElementById("mobilenumber").value="";
		$('#mobilenumber').trigger( "focus" );
		return false;
	}
	else if($('#mobilenumber').val().length > 10)
	{
		$('#myModal5').modal('show');
		document.getElementById("mobilenumber").value="";
		$('#mobilenumber').trigger( "focus" );
		return false;
	}
	else if($('#security_code_online').val() == "")
	{
	
		$('#myModal9').modal('show'); 
		$('#myModal9').modal({ backdrop: 'static', keyboard: false })
		$('#security_code_online').trigger( "focus" );
		return false;
	}
	else if($('#otp').val() == "")
	{
		$('#myModal6').modal('show');
		$('#myModal6').modal({ backdrop: 'static', keyboard: false })
		$('#pwd').trigger( "focus" );
		return false;
	}
	else if($('#otp').val().length < 6)
	{
		$('#myModal7').modal('show');
		$('#myModal7').modal({ backdrop: 'static', keyboard: false })
		document.getElementById("pwd").value="";
		$('#pwd').trigger( "focus" );
		return false;
	}
	else if($('#otp').val().length > 6)
	{
		$('#myModal8').modal('show');
		document.getElementById("pwd").value="";
		$('#pwd').trigger( "focus" );
		return false;
	}
	else if($('#security_code_online').val() == null || $('#security_code_online').val() == "")
	{
		$('#myModal9').modal('show');
		$('#myModal2').modal({ backdrop: 'static', keyboard: false })
		$('#security_code_online').trigger( "focus" );
		return false;
		}
	else
	{ 
		var tex_captch = document.getElementById("security_code_online").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj[0]=='F') {			
			document.getElementById("security_code_online").value="";
			$('#security_code_online').trigger( "focus" );
			document.getElementById("demo1").innerHTML = myObj[0];
			$('#myModal10').modal('show');
			$('#myModal10').modal({ backdrop: 'static', keyboard: false })
			}
		} else {
			document.login_form.submit();
		}
	};
	xmlhttp.open("GET", "captcha_validation_online.php?q="+tex_captch, true);
	xmlhttp.send(); 	 
	}
 	return true;
}

var send_failure = 0;
var otp_resend;
function checkAndSaveMobile() {	
	mob_no=document.getElementById("mobilenumber").value;
	sec_cod=document.getElementById("security_code_online").value;
	var param = "mode=chk_mobile"+"&mobile_no="+mob_no;
	if (mob_no == "") {
		$('#myModal13').modal('show');
		$('#mobilenumber').trigger( "focus" );
	} else if (sec_cod =="") {
		$('#myModal14').modal('show');
		$('#security_code').trigger( "focus" );
	} else {
		var tex_captch = document.getElementById("security_code_online").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);
				if(myObj[0]=='F') {			
					document.getElementById("security_code_online").value="";
					$('#security_code_online').trigger( "focus" );
					document.getElementById("demo1").innerHTML = myObj[0];
					$('#myModal10').modal('show');
					$('#myModal10').modal({ backdrop: 'static', keyboard: false })
				} else {
					$.ajax({
						type: "POST",
						dataType: "xml",
						url: "check_save_mobile_action.php",  
						data: param,  
						
						beforeSend: function(){
							//alert( "AJAX - beforeSend()" );
						},
						complete: function(){
							//alert( "AJAX - complete()" );
						},
						success: function(xml){
							// we have the response
							document.getElementById("otp").disabled = false;
							count = $(xml).find('count').eq(0).text();
							
							otp_resend = $(xml).find('otp').eq(0).text();
							//alert(otp);
							if (count > 0) {
								$('#myModal11').modal('show');
								$('#myModal11').modal({ backdrop: 'static', keyboard: false })
								//alert("Please enter the OTP received on your mobile");
								document.getElementById("generate_otp").style.display = 'none';
								document.getElementById("resend_otp").style.display = '';
							} else {
								//alert("OTP is not sent this time");
								$('#myModal15').modal('show');
								$('#myModal15').modal({ backdrop: 'static', keyboard: false })
								//send_failure = 0;
								
								document.getElementById("generate_otp").style.display = 'none';
								document.getElementById("resend_otp").style.display = '';
								
							}
							
						},  
						error: function(e){  
							//alert('Error: ' + e);
/*							document.getElementById("otp").disabled = false;
							$('#myModal11').modal('show');
							$('#myModal11').modal({ backdrop: 'static', keyboard: false })
							document.getElementById("generate_otp").style.display = 'none';
							document.getElementById("resend_otp").style.display = '';
*/
						}
					});//ajax end
				
				}
			}
		};
		xmlhttp.open("GET", "captcha_validation_online.php?q="+tex_captch, true);
		xmlhttp.send(); 
	}
}

function resendOtp() {
	mob_no=document.getElementById("mobilenumber").value;
	var param = "mode=resend_otp"+"&mobile_no="+mob_no+"&otp="+otp_resend;
	//alert(param);
	if (mob_no != "") {
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "check_save_mobile_action.php",  
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				count = $(xml).find('count').eq(0).text();				
				if (count > 0) {
				$('#myModal16').modal('show');
				$('#myModal16').modal({ backdrop: 'static', keyboard: false })
					//alert("Please enter the OTP received on your mobile");	
					document.getElementById("generate_otp").style.display = 'none';
					document.getElementById("resend_otp").style.display = '';					
				} else {
				$('#not_sent').modal('show');
				$('#not_sent').modal({ backdrop: 'static', keyboard: false })
					//alert("OTP is not sent this time");
					document.getElementById("generate_otp").style.display = 'none';
					document.getElementById("resend_otp").style.display = '';
					
				}
				
			},  
			error: function(e){  
				//alert('Error: ' + e);  
/*
				$('#myModal16').modal('show');
				$('#myModal16').modal({ backdrop: 'static', keyboard: false })
				document.getElementById("generate_otp").style.display = 'none';
				document.getElementById("resend_otp").style.display = '';
*/
			}
		});//ajax end	
	} else {
		alert("Enter your mobile number");
		$('#mobilenumber').trigger( "focus" );
	}
}
 function v_reset_online() {
 document.getElementById("resend_otp").innerHTML = "Generate OTP"; 
  document.getElementById("mobilenumber").value="";
  document.getElementById("security_code_online").value="";
  document.getElementById("otp").value="";
  document.getElementById("login_form").reset();
  }
  
function v_reset_officals() {
  document.getElementById("username").value="";
  document.getElementById("pwd").value="";
  document.getElementById("security_code_offical").value="";
  document.getElementById("login_form_offcials").reset();
  }
   function v_reset_nri() {
  document.getElementById("phonecode").value="";
  document.getElementById("mobilenumber").value="";
  document.getElementById("security_code_nri").value="";
  document.getElementById("login_form_nri").reset();
  }
  <!-- Official Login Start -->
  
  function reloadCaptcha_online(){
	document.login_form.security_code.value="";
	document.getElementById('captcha_online').src = 'captcha_online.php?' + Math.random();
}




function validateFormofficals(strSalt,strit)
					{
			 //debugger;
				var pass=document.getElementById("pwd").value;
				var ck_password =/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*-_/]).*$/
   				var errors = [];
				$('#togglePassword').hide();
				if($('#username').val()=="" && $('#pwd').val()==""){
				$('#officel_login_1').modal('show');
				$('#officel_login_1').modal({ backdrop: 'static', keyboard: false })
					$('#username').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#username').val() == null || $('#username').val() =="")
				{
					$('#officel_login_1').modal('show');
					$('#officel_login_1').modal({ backdrop: 'static', keyboard: false })
					$('#username').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#username').val().length < 5)
				{
					$('#officel_login_2').modal('show');
					$('#officel_login_2').modal({ backdrop: 'static', keyboard: false })
					document.getElementById("username").value="";
					$('#username').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#username').val().length > 30)
				{
					$('#officel_login_3').modal('show');
					$('#officel_login_3').modal({ backdrop: 'static', keyboard: false })
					document.getElementById("username").value="";
					$('#username').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#pwd').val() == "")
				{
					$('#officel_password_1').modal('show');
					$('#officel_password_1').modal({ backdrop: 'static', keyboard: false })
					$('#pwd').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#pwd').val().length < 6)
				{
					$('#officel_password_2').modal('show');
					$('#officel_password_2').modal({ backdrop: 'static', keyboard: false })
					document.getElementById("pwd").value="";
					$('#pwd').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				else if($('#pwd').val().length > 30)
				{
					$('#officel_password_3').modal('show');
					$('#officel_password_3').modal({ backdrop: 'static', keyboard: false })
					document.getElementById("pwd").value="";
					$('#pwd').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				}
				 else if($('#security_code_offical').val() == null || $('#security_code_offical').val() == "")
				{
					$('#officel_security_code').modal('show');
					$('#officel_security_code').modal({ backdrop: 'static', keyboard: false })
					$('#security_code').trigger( "focus" );
					$('#togglePassword').show();
					return false;
				} 
				else
				{ 
 					var strEncPwd=new String(encryptPwd1(document.getElementById("pwd").value, strSalt,strit));
					document.getElementById("pwd").value=strEncPwd;
					//alert("going to submit");
					//document.login_form_offcials.submit();
					var tex_captch = document.getElementById("security_code_offical").value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var myObj = JSON.parse(this.responseText);
					if(myObj[0]=='F') {
					document.getElementById("username").value="";
					document.getElementById("pwd").value="";
					document.getElementById("security_code_offical").value="";
					$('#security_code_offical').trigger( "focus" );
					document.getElementById("demo2").innerHTML = myObj[0];
					$('#officals_security_code').modal('show');
					$('#officals_security_code').modal({ backdrop: 'static', keyboard: false })
					}
					else {
						 document.login_form_offcials.submit();
						return false;
					}
				}
			};
			xmlhttp.open("GET", "captcha_validation_officals.php?q="+tex_captch, true);
			xmlhttp.send();
					
				 }
 				return true;
				
			}
			
function encryptPwd1(strPwd, strSalt,strit)
			{
			//debugger;
				var strNewSalt=new String(strSalt);
				
				if (strPwd=="" || strSalt=="")
				{
					return null;
				}
				 
				var strEncPwd;
				var strPwdHash = MD5(strPwd);
				 
				var strMerged = strSalt+strPwdHash;
				 
				 
				var strMerged1 = MD5(strMerged);
				 
				return strMerged1;
				 
 			}
/* Online End */
//  Official Login End 

function reloadCaptcha(){
	document.view_status.security_code.value="";
	document.getElementById('captcha').src = 'captcha.php?' + Math.random();
}
function reloadCaptcha_off(){
	document.login_form_offcials.security_code_offical.value="";
	document.getElementById('captcha_off').src = 'captcha_off.php?' + Math.random();
}
/* function checkValue_online() {
//document.getElementById("myModal9").style.display = "none";
    var tex_captch = document.getElementById("security_code_online").value;

	var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
		if(myObj[0]=='F') {
		
		document.getElementById("security_code_online").value="";
		$('#security_code_online').trigger( "focus" );
		document.getElementById("demo1").innerHTML = myObj[0];
		$('#myModal10').modal('show');
		$('#myModal10').modal({ backdrop: 'static', keyboard: false })
		}
    }
};
xmlhttp.open("GET", "captcha_validation_online.php?q="+tex_captch, true);
xmlhttp.send();

} */

function checkValue() {
if ($.trim($('#petition_no').val())=='')	{
		$('#alertDiv font').html("<?php echo $lang['ENTER_PETITION_NUMBER']; ?>");
		$('#alertDiv').show();
		$('#petition_no').trigger( "focus" );
		return false;
	} else if($.trim($('#security_code').val())=='') {
		$('#alertDiv font').html("<?php echo $lang['ENTER_SECURITY_CODE']; ?>");
		$('#alertDiv').show();
		$('#security_code').trigger( "focus" );
		return false;
	}
	
    var tex_captch = document.getElementById("security_code").value;
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.open("GET", "captcha_validation.php?q="+tex_captch, true);
	xmlhttp.onreadystatechange = function() {
		
    if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
		if(myObj[0]=='F') {
		document.getElementById("security_code").value="";
		document.getElementById("demo").innerHTML = myObj[0];
		 $('#alertDiv font').html("<?php echo $lang['ENTER_VALID_SECURITY_CODE']; ?>");
	     $('#alertDiv').show();
		 return false;
		} else {
			 document.view_status.method="post";
			 document.view_status.action = "print_status_intermediate_page.php"
			 document.view_status.submit();
			return false;
		}
    };
};

xmlhttp.send(null);
}


function v_reset() {
  document.getElementById("petition_no").value="";
  document.getElementById("view_status").reset();
  }

$(window).load(function(){
// Slider1						
 $('#service_flexslider').flexslider({
        animation: "slide",
		controlNav: true,
		pausePlay: true,
        start: function(slider){
        $('body').removeClass('loading');
        }
	});
});

$(window).load(function(){
// Slider2						
 
});
function buttonsubmit1(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode
    if (unicode == 13) {
        checkValue();
    }
}

function buttonsubmit2(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
	//alert("2222"+unicode);
    if (unicode == 13) {
        validateForm();
    }
}

function buttonsubmit3(e) {
//alert("Hai");
    var unicode = e.charCode ? e.charCode : e.keyCode;
	//alert("2222"+unicode);
    if (unicode == 13) {
        validateFormofficals();
    }

}

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
	
});
function show0() {
	document.getElementById('img0').style.display = 'block';
} 
function show_out0() {
	document.getElementById('img0').style.display = 'none';
} 
function show() {
	document.getElementById('img1').style.display = 'block';
} 
function show_out() {
	document.getElementById('img1').style.display = 'none';
} 
function show1() {
	document.getElementById('img2').style.display = 'block';
} 
function show_out1() {
	document.getElementById('img2').style.display = 'none';
} 
function show2() {
	document.getElementById('img3').style.display = 'block';
} 
function show_out2() {
	document.getElementById('img3').style.display = 'none';
} 
function team() {
	document.getElementById('team').style.display = 'block';
} 
 function team_condition() {
	document.getElementById('team').style.display = 'none';
} 
function team1() {
	document.getElementById('team1').style.display = 'block';
} 
 function team_condition1() {
	document.getElementById('team1').style.display = 'none';
} 
function copy_policy() {
	document.getElementById('copy').style.display = 'block';
} 
function copy_policy1() {
	document.getElementById('copy').style.display = 'none';
}
function copy_policy2() {
	document.getElementById('copy1').style.display = 'block';
} 
function copy_policy3() {
	document.getElementById('copy1').style.display = 'none';
}
function privacy() {
	document.getElementById('privacy_policy').style.display = 'block';
} 
function privacy1() {
	document.getElementById('privacy_policy').style.display = 'none';
}
function privacy2() {
	document.getElementById('privacy_policy1').style.display = 'block';
} 
function privacy3() {
	document.getElementById('privacy_policy1').style.display = 'none';
}
function HYPERLINK_POLICY1() {
	document.getElementById('Hyper_Linking').style.display = 'block';
} 
 function HYPERLINK_POLICY2() {
	document.getElementById('Hyper_Linking').style.display = 'none';
} 
 function HYPERLINK_POLICY3() {
	document.getElementById('Hyper_Linking1').style.display = 'block';
} 
 function HYPERLINK_POLICY4() {
	document.getElementById('Hyper_Linking1').style.display = 'none';
} 
function Disclamier1() {
	document.getElementById('Disclaimer').style.display = 'block';
} 
function Disclamier2() {
	document.getElementById('Disclaimer').style.display = 'none';
}
function Disclamier3() {
	document.getElementById('Disclaimer1').style.display = 'block';
} 
function Disclamier4() {
	document.getElementById('Disclaimer1').style.display = 'none';
}
function service1() {
	document.getElementById('service').style.display = 'block';
} 
function service2() {
	document.getElementById('service').style.display = 'none';
}
function service_desk1() {
	document.getElementById('service_desk_c').style.display = 'block';
} 
function service_desk2() {
	document.getElementById('service_desk_c').style.display = 'none';
}
// font size increase decrease start
var colour_change=false;
function set_all_colour() {
	if(colour_change==true)
	{
		document.getElementById("he_colour1").style.background = "#f2dfad";
		document.getElementById("he_colour1").style.borderBottomColor = "#000000";
		document.getElementById("header").style.background = "#95342e";
		//document.getElementById("mySidenav").style.background = "#81a4cd";
		document.getElementById("he_colour3").style.background = "#f2dfad";
		document.getElementById("he_colour4").style.background = "#95342e";
		document.getElementById("mark").style.background = "#f2dfad";
		document.getElementById("marq123").style.color = "#ffffff";
		//document.getElementById("marq123").style.color = "#000000";
		document.getElementById("v27").style.color = "#000000";
		document.getElementById("v26").style.color = "#000000";
		document.getElementById("v25").style.color = "#000000";
		document.getElementById("v24").style.color = "#000000";
		document.getElementById("v23").style.color = "#000000";
		document.getElementById("v35").style.color = "#000000";
		document.getElementById("e_co").style.color = "#000000";
		document.getElementById("t_co").style.color = "#000000";
		document.getElementById("a").style.color = "#000000";
		document.getElementById("a_p").style.color = "#000000";
		document.getElementById("a_m").style.color = "#000000";
		document.getElementById("a_c").style.background = "#000000";
		document.getElementById("mySidenav").style.background = "#f2dfad";
		document.getElementById("sn1").style.color = "#ffffff";
		document.getElementById("a_c").style.color = "#ffffff";
		document.getElementById("marq123").style.color = "#000000";
	
		colour_change=false;
	}
	else{
	colour_change=true;
		document.getElementById("mark").style.background = "#000000";
		document.getElementById("he_colour1").style.background = "#000000";
		document.getElementById("he_colour1").style.borderBottomColor = "#FFFFFF";
		document.getElementById("header").style.background = "#000000";
		document.getElementById("v27").style.color = "#FFFFFF";
		document.getElementById("v26").style.color = "#FFFFFF";
		document.getElementById("v25").style.color = "#FFFFFF";
		document.getElementById("v24").style.color = "#FFFFFF";
		document.getElementById("v23").style.color = "#FFFFFF";
		document.getElementById("v35").style.color = "#FFFFFF";
		document.getElementById("e_co").style.color = "#FFFFFF";
		document.getElementById("t_co").style.color = "#FFFFFF";
		document.getElementById("a").style.color = "#FFFFFF";
		document.getElementById("a_p").style.color = "#FFFFFF";
		document.getElementById("a_m").style.color = "#FFFFFF";
		document.getElementById("a_c").style.background = "#FFFFFF";
		document.getElementById("a_c").style.color = "#000000";
		document.getElementById("marq123").style.color = "#FFFFFF";
		document.getElementById("mySidenav").style.background = "#000000";
		document.getElementById("sn1").style.color = "#000000";
		
		document.getElementById("he_colour3").style.background = "#000000";
		document.getElementById("he_colour3_gallery").style.background = "#000000";
		document.getElementById("he_colour4").style.background = "#000000";
		
		document.getElementById("he_colour4").style.borderTop = "1px solid #FFFFFF";
		document.getElementById("he_colour3").style.borderTop = "1px solid #FFFFFF";
		document.getElementById("he_colour3_gallery").style.borderTop = "1px solid #FFFFFF";
	}
	
}

 function changeFontSize(element,step){
 var curFont1 = parseInt($('p').css('font-size'));
 //alert(curFont1);
  if(curFont1<24) {
 //alert(curFont1);  
        step = parseInt(step,10);
        //var el = $('p,a,h1:not("#eNag")');
        var el = $('p,div,a,table,label,textarea,select,input,radio,checkbox,h1,h4,span');
        $.each(el,function(){
            var curFont = parseInt($(this).css('font-size'));
            $(this).css('font-size',(curFont+step)+'px');
		
        });
		}
    }
     
function changeFontSize1(element,step){
//alert("Hai");
 var curFont1 = parseInt($('p').css('font-size'));
 //alert(curFont1);
  if(curFont1>16) {
        step = parseInt(step,10);
        //var el = $('p,a,h1:not("#eNag")');
        var el = $('p,div,a,table,label,textarea,select,input,radio,checkbox,h1,h4,span');
        $.each(el,function(){
		//document.getElementById("v3").style.fontSize = "18px";
            var curFont = parseInt($(this).css('font-size'));
            $(this).css('font-size',(curFont+step)+'px');
		
        });
		}
    }	

    function resetFontSize(element){
        /* var el = $('p,div,h4,table,textarea,label,select,input,radio,checkbox,h1,span');
        $.each(el,function(){
		// document.getElementById("po_a1").style.fontSize = "15px";
		//document.getElementById("po_a2").style.fontSize = "14px";
		//document.getElementById("po_a3").style.fontSize = "15px";
		//document.getElementById("po_a4").style.fontSize = "14px";
		//document.getElementById("po_a5").style.fontSize = "14px";
		//document.getElementById("po_a6").style.fontSize = "14px";
		//document.getElementById("po_a7").style.fontSize = "14px";
		//document.getElementById("po_a8").style.fontSize = "14px";
		//document.getElementById("po_a9").style.fontSize = "14px";  
		//document.getElementById("po_a91").style.fontSize = "14px";  
		document.getElementById("a").style.fontSize = "11px";
		document.getElementById("a_p").style.fontSize = "11px";
		document.getElementById("a_m").style.fontSize = "11px";
		document.getElementById("a_c").style.fontSize = "11px";
		document.getElementById("e_co").style.fontSize = "11px";
		document.getElementById("t_co").style.fontSize = "11px";  */
		//document.getElementById("v1").style.fontSize = "21px";
		//document.getElementById("v2").style.fontSize = "15px";
		 //document.getElementById("v3").style.fontSize = "18px";
		//document.getElementById("v4").style.fontSize = "25px";
		//document.getElementById("v5").style.fontSize = "19px";
		//document.getElementById("v6").style.fontSize = "11px";
		//document.getElementById("v6a").style.fontSize = "11px";
		//document.getElementById("v7").style.fontSize = "11px";
		//document.getElementById("v8").style.fontSize = "11px";  
		/* document.getElementById("v9").style.fontSize = "21px";
		document.getElementById("v10").style.fontSize = "15px";
		document.getElementById("v11").style.fontSize = "21px";
		document.getElementById("v12").style.fontSize = "15px";
		document.getElementById("v13").style.fontSize = "21px";
		document.getElementById("v14").style.fontSize = "15px"; */
		//document.getElementById("v15").style.fontSize = "21px";
		/* document.getElementById("v16").style.fontSize = "15px";
		document.getElementById("v17").style.fontSize = "21px";
		document.getElementById("v18").style.fontSize = "15px";
		document.getElementById("v19").style.fontSize = "21px";
		document.getElementById("v20").style.fontSize = "15px";
		document.getElementById("m1").style.fontSize = "15px";
		document.getElementById("m2").style.fontSize = "15px";
		document.getElementById("m3").style.fontSize = "15px";
		document.getElementById("v21").style.fontSize = "11px";
		document.getElementById("v22").style.fontSize = "11px";
		document.getElementById("v23").style.fontSize = "11px";
		document.getElementById("v35").style.fontSize = "11px";
		document.getElementById("v24").style.fontSize = "11px";
		document.getElementById("v25").style.fontSize = "11px";
		document.getElementById("v26").style.fontSize = "11px"; 
		document.getElementById("v27").style.fontSize = "11px";
		document.getElementById("v28").style.fontSize = "11px";
		document.getElementById("v29").style.fontSize = "11px";
		document.getElementById("v30").style.fontSize = "11px";
		document.getElementById("v31").style.fontSize = "11px";
		document.getElementById("v32").style.fontSize = "11px";
		document.getElementById("v33").style.fontSize = "11px";
		//document.getElementById("v34").style.fontSize = "11px";
		document.getElementById("v36").style.fontSize = "11px"; */
		 //$(this).css('font-size','14px');
       /*  }); */
	   document.location.reload(false);
    }
	  function resetFontSizet(element){
        /* var el = $('p,div,h4,table,textarea,label,select,input,radio,checkbox,h1,span');
        $.each(el,function(){ */
		//document.getElementById("foot_last").style.fontSize = "11px";
		// document.getElementById("t3").style.fontSize = "18px";
		/* document.getElementById("t4").style.fontSize = "22px";
		document.getElementById("t5").style.fontSize = "16px"; */ 
		 /* document.getElementById("po_t1").style.fontSize = "11px";
		 document.getElementById("po_t2").style.fontSize = "11px";
		
		document.getElementById("po_t3").style.fontSize = "11px";
		document.getElementById("po_t4").style.fontSize = "11px";
		//document.getElementById("po_t5").style.fontSize = "11px";
		//document.getElementById("po_t6").style.fontSize = "11px";
		document.getElementById("po_t7").style.fontSize = "11px";
		document.getElementById("po_t8").style.fontSize = "11px";
		document.getElementById("po_t9").style.fontSize = "11px";		
		document.getElementById("po_t10").style.fontSize = "11px";		
		document.getElementById("po_t11").style.fontSize = "11px";	 */
		/* document.getElementById("menu1").style.fontSize = "13px";	
		document.getElementById("menu2").style.fontSize = "13px";	
		document.getElementById("menu3").style.fontSize = "13px";	 */
		//document.getElementById("footer1").style.fontSize = "11px";	
		//document.getElementById("footer2").style.fontSize = "10px";	
		//document.getElementById("footer3").style.fontSize = "10px";	
		/* document.getElementById("he_t1").style.fontSize = "18px";	
		document.getElementById("he_t2").style.fontSize = "22px";  */
          //  $(this).css('font-size','12px');
        /* });*/
    }  
// font size increase decrease end

$('#Login_Fail_Alert').modal('show');
  setTimeout(function() {
    $('#Login_Fail_Alert').modal('hide');
}, 6000); // 2 seconds.   

$('#Login_Fail_Alert_username_password').modal('show');
  setTimeout(function() {
    $('#Login_Fail_Alert_username_password').modal('hide');
}, 6000); // 2 seconds.   
 
  function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
</script>

</script>
</body>
</html>
