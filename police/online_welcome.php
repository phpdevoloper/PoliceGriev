<?php 
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("common_date_fun.php"); 

include_once 'common_lang.php';

if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   ob_start();	
   echo "<script> alert('Timed out. Please login again');</script>";
   echo "<script type='text/javascript'> document.location = 'logout.php'; </script>";
   exit;
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['PAGE_TITLE']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0	 maximum-scale=6.0 user-scalable=no">
<meta name="theme-color" content="#317EFB"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="apple-touch-icon" href="assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="assets/images/favicon/favicon.png">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css"> 
<!-- font Awesome -->
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- font Awesome -->
<link href="assets/css/stylev1.css" rel="stylesheet" media="all">
<link href="assets/css/stylev1.css" rel="stylesheet" media="all">
<link href="assets/css/style.css" rel="stylesheet" media="all">
<link href="assets/css/base.css" rel="stylesheet" media="all">
<link href="assets/css/base-responsive.css" rel="stylesheet" media="all">
<link href="assets/css/grid.css" rel="stylesheet" media="all">
<link href="assets/css/font.css" rel="stylesheet" media="all">
<link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="assets/css/flexslider.css" rel="stylesheet" media="all">
<link href="assets/css/megamenu.css" rel="stylesheet" media="all" />
<link href="assets/css/print.css" rel="stylesheet" media="print" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>	
<link href="theme/css/site.css" rel="stylesheet" media="all">
<link href="theme/css/site-responsive.css" rel="stylesheet" media="all">
<link href="theme/css/ma5gallery.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="bootstrap/mycss/dpk.css">
<style>

 /* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
	/* .fo_si {
		width: 100% !important;
		
	}
	.call_now {
		width: 100% !important;
	}
	.call_now2 {
		width: 100% !important;
	} */
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
	./* fo_si {
		width: 100% !important;
	}
	.call_now {
		width: 100% !important;
	}
	.call_now2 {
		width: 100% !important;
	} */
	
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
	
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
	
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
	
} 

#span_dwnd {
	cursor:pointer;
	font-weight:bold;
	color: red;
	text-decoration:underline;
	float: left;
}
#span_dwnd:hover {
color: #0000FF;
}

.co-left {
	color: #fff;
    line-height: 22px;
	cursor: pointer;
}
.call_now {
    font-size: 20px;
	letter-spacing: 2px;
	position: relative;
	font-family: times new roman;
	font-weight: bold;
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
	font-family: times new roman;
	font-weight: bold;
	top: 8px;
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
	width: 59%;
	float: left;
}
.bt_se {
	margin-top: -61px;
    margin-left: 117px;
}
.common-left ul li.ministry a:hover {
    color: #CA0C5C !important;
}
.fo_si {
     font-size: 19px;
}

.relo {
	width: 97%;
	cursor: pointer;
}
.catc {
	width: 100%;
    margin-left: 52px;
}
.cec {
width: 89%;
}
.re_img {
	margin-left: -64px;
    margin-top: -4px;
}
@media(min-width:1440px) and (max-width:2254px)
{
	.flex-control-nav {
    left: 190px;
    right: auto;
    }
   .banner-wrapper .flex-pauseplay {
    left: 854px;
    right: auto;
}
   .logo
   {
    margin-left: -13px;
}
	
}
@media (min-width: 1200px)
{
  .flex-control-nav {
    left: 202px; 
    right: auto;
}
   .banner-wrapper .flex-pauseplay {
    left: 870px; 
    right: auto;
}
}
input:focus {
	color:red;
}
#submit_otp:hover {
    background-color: #F37A0B;
}
tr {
	font-size: 14px;
}
td {
	padding: 0px;
	font-size: 14px;
	color: #000000;
	padding-left: 10px;
	padding-bottom: 0px;
}
th {
	font-size: 15px;
	line-height: 8px;
}
.viewTbl{
	margin-bottom: 10px;
}



th {
	background: #DDDDDD;
	color: #000000;
}
table, th, td {
    font-family: "Open Sans", sans-serif;
}
.emptyTRV
{
	 width:100%;
	 text-align: right;
	 line-height: 9px;
}
body {
     background: #f2dfad;
    font-family: "Open Sans", sans-serif !important;
}
@page {
size:auto;margin:0mm;
}
.pad_t {
	padding: 9px;
}
@media(max-width:767px)
{
	.taple_scroll {
		overflow-x: scroll;
	}
	
}
.wel_2 {
	color: #000;
	font-size: 23px;
	font-weight: bold;
}
</style>
</head>
 <?php include('online_header_submission.php') ?>
 <!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #f2dfad;
  padding: 0px;
  line-height: 0px;
  border-bottom: 1px solid #fff;
}

.topnav a {
  float: left;
  display: block;
  color: red;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  border: 1px solid #C0C0C0;
  font-weight:bolder;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}

.topnav .icon {
  display: none;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
	position:auto;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
}
.wel_1 {
	width: 100%;
	text-align: center;
	padding: 20px;
	margin-top: 31px;
	margin-bottom: 31px;
}
.lim {
	border: 3px solid #3c8dbc;
	border-radius: 4px;
	background-color: #f2dfad;
	width: 75%;
	height: 200%;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: #95342e;
  color: white;
  text-align: center;
}

</style>
</head>
<body style="background-color:#ffffff">

<?php include("online_menu.php");?>
</div>
<br>
<br>
<br>
<br>
<div class='lim container'>
<div class="wel_1">
<br>
<br>
<br>
		<div>
			 <h1 class="wel_2" style="color:red;"><?php echo $lang['welcome']; ?></h1>
		</div>
<br>
<br>
<br>
</div>
</div>
<div class='footBottom'>
<p class="footer" id="footertbl" style="margin-bottom: 0px;">
Computerized By NATIONAL INFORMATICS CENTRE<!--  IT Support by: National Informatics Centre-->, TNSU, Chennai
</p>
</div>
<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

</body>
</html>
