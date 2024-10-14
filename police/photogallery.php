<?php 
ini_set("session.cookie_httponly",1);
session_start();
ob_start();

include("db.php");
include("common_fun.php"); 
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
<script>
function myFunction(x) {
  if (x.matches) { // If media query matches
    $("#c1").removeClass('container1 responsive');
    $("#c2").removeClass('container2 responsive');
    $("#c1").addClass('container');
    $("#c2").addClass('container');
  } else {
    $("#c1").removeClass('container');
    $("#c2").removeClass('container');
    $("#c1").addClass('container1 responsive');
    $("#c2").addClass('container2 responsive');
  }
}

var x = window.matchMedia("(max-width: 1030px)")
myFunction(x) // Call listener function at run time
x.addListener(myFunction)
</script>
<body onload="noBack();" >

<? include("header _status.php");  ?>
<? include("header_marquee.php");  ?>



	<div class="photo_g">
		<h1><?php echo $lang['PHOTO_GALLERY1']; ?></h1>
	</div>
	<div style="clear:both;"></div>

<div id="c1" class="container1 responsive">
<!--div class="p_g_1 p_g_padd">
	  <img src="theme/images/banner/HR&CE1.jpg" alt="EZHILAGAM" class="image1" style="width:100%">
	  <div class="middle1">
		<div class="text1"><?php echo $lang['LAUNCHING_GDP']; ?><BR><span class="p_g_p"><?php echo $lang['LAUNCHING_PPP']; ?></span></div>
	  </div> 
	</div-->
	<!--<div class="p_g_1 p_g_padd">
	  <img src="theme/images/banner/slider1.jpg" class="image1" style="width:100%">
	  <div class="middle1">
		<div class="text1"><?php echo $lang['LAUNCHING_GDP']; ?><BR><span class="p_g_p"><?php echo $lang['LAUNCHING_HR_CE']; ?></span></div>
	  </div> 
	</div>-->
	
	<div class="p_g_2 p_g_padd responsive">
	   <img src="images/slide/slider1.jpg"  class="image3" style="width:100%">
	  <div class="middle3">
		<!--<div class="text3"><?php echo $lang['Ezhilagam_img']; ?></div>-->
	  </div>
	</div>
	
	<div class="p_g_3 p_g_padd responsive">
	   <img src="images/slide/slider2.jpg"  class="image5" style="width:100%">
	  <div class="middle5">
		<!--<div class="text5"><?php echo $lang['Grievance_Day_Meeting_Ariyalur']; ?></div>-->
	  </div>
	</div>
	
	<div class="p_g_4 p_g_padd responsive">  
	   <img src="images/slide/slider3.jpg"  class="image7" style="width:100%">
	  <div class="middle7">
		<!--<div class="text7"><?php echo $lang['Video_Conference']; ?></div>-->
	  </div>
	</div>
	
	
</div>
<div id="c2" class="container2 responsive"> 

	<!--<div class="p_g_5 p_g_padd">
	  <img src="theme/images/banner/slider2.jpg"  class="image2" style="width:100%">
	  <div class="middle2">
		<div class="text2"><?php echo $lang['LAUNCHING_GDP']; ?><BR><span class="p_g_p"><?php echo $lang['LAUNCHING_PPP']; ?></span></div>
	  </div>
	</div>-->
	
	<div class="p_g_6 p_g_padd responsive">	
	   <img src="images/slide/slider4.jpg"  class="image4" style="width:100%">
	  <div class="middle4">
		<!--<div class="text4"><?php echo $lang['Collectorate_img']; ?></div>-->
	  </div>
	</div>  
	
	<div class="p_g_7 p_g_padd responsive">	
	   <img src="images/slide/slider5.jpg"  class="image6" style="width:100%">
	  <div class="middle6">
		<!--<div class="text6"><?php echo $lang['Grievance_Day_Meeting_Thiruvannamalai']; ?></div>-->
	  </div>
	</div>
	<div class="p_g_8 responsive">	
	   <img src="images/slide/slider6.jpg" class="image8" style="width:100%">
	  <div class="middle8">
		<!--<div class="text8"><?php echo $lang['Video_Conference']; ?></div>-->
	  </div>
	</div>
</div>


<? include("footer_ppp.php");  ?>
</body>
</html>

