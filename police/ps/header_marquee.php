<?php 
ini_set("session.cookie_httponly",1);
session_start();
ob_start();

include("db.php");
//include("common_fun.php"); 
include_once 'common_lang.php';

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
<link href="assets/css/form.css" rel="stylesheet" media="all">
<link href="assets/css/base.css" rel="stylesheet" media="all">
<link href="assets/css/own_responsive.css" rel="stylesheet" media="all">
<link href="assets/css/base-responsive.css" rel="stylesheet" media="all">
<link href="assets/css/grid.css" rel="stylesheet" media="all">
<link href="assets/css/font.css" rel="stylesheet" media="all">
<link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="assets/css/flexslider.css" rel="stylesheet" media="all">
<link href="assets/css/megamenu.css" rel="stylesheet" media="all" />
<link href="assets/css/print.css" rel="stylesheet" media="print" />
<link href="theme/css/site.css" rel="stylesheet" media="all">
<link href="theme/css/site-responsive.css" rel="stylesheet" media="all">
<link href="theme/css/ma5gallery.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="bootstrap/mycss/dpk.css">
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

<script type="text/javascript" src="js/jquery.md5.min.js"></script>
<script LANGUAGE="Javascript" SRC="js/md5.js"></script>
</head>
<style>

/* .call_now {
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
} */
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

@media screen and (min-width: 600px) {
#lim {
  border: 1px solid #C0C0C0;
}
}
@media screen and (max-width: 600px) {
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

@media screen and (max-width: 600px) {
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
@media (min-width: 601px)and (max-width: 1030px)
{
	#mark_menu{
		display:block;
	}#mark{
		display:none;
	}
}
@media (min-width: 1030px)
{#mark_menu{
		display:none;
}
}
@media(max-width:601px)
{
#mark{
		display:none;
}
#mark_menu{
		display:block;
	}
}
</style>
<div id="mark_menu" class="topnav1 responsive">

  
  <a href="index.php">Home</a>
 
  <a href="photogallery.php" ><?php echo $lang['PHOTO_GALLERY2']; ?></a>
  <a href="Terms_Conditions.php" ><?php echo $lang['TERMS_CONDITIONS']; ?></a>
  <a href="faq.php" ><?php echo $lang['FAQ']; ?></a>
  <a href="help.php" ><?php echo $lang['HELP']; ?></a>
</div>
<body onload="noBack();" >
<div class="scroo_1" id="mark">
<div class="animated bounceInDown">
		<a href="index.php" class="scroo_2 note_home scroo_211" style="background-color: white; color: #e1297f;text-decoration: none;"><!--span class="glyphicon glyphicon-home  note_home" style="color: #e1297f;margin-left: 12px;"></span--><img src="theme/images/icon_home.png" class="home_img" > <span><?php echo $lang['HOME'];?></span></a>
</div>
	<div class="scroo_3 animated bounceInDown" >
	<marquee   style="font-size:15px;" onmouseover="this.stop();" onmouseout="this.start();"><?php echo $lang['Marquee'];?></marquee>
	</div>

	<div class="scroo_4 animated bounceInDown">
	<p class="scroo_2_m note_home scroo_2_m11" style="background-color: white;color: #e1297f;width: 8%;" onclick="openNav()"><img src="theme/images/icon_gallery.png" class="gallery_img" ><!--span class="glyphicon glyphicon-align-justify" style="margin-left: 12px;"></span -->  <?php echo $lang['MORE'];?> </p>
	</div>
	<div style="clear:both;"></div>
</div>

<div id="mySidenav" class="sidenav" >
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="photogallery.php" ><?php echo $lang['PHOTO_GALLERY2']; ?></a>
  <a href="Terms_Conditions.php" ><?php echo $lang['TERMS_CONDITIONS']; ?></a>
  <a href="faq.php" ><?php echo $lang['FAQ']; ?></a>
    <a href="help.php" ><?php echo $lang['HELP']; ?></a>
  <!--<a href="contact_us.php"><?php //echo $lang['CONTACT_US']; ?></a>-->
  <!--a href="feed_back.php" ><?php //echo $lang['FEED_BACK']; ?></a-->
</div>
<script>
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
</body>
</html>

