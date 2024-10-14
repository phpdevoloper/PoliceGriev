<?php
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
?>
<!DOCTYPE html>
<body >
 <style>
.call_now {
    font-size: 18px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 15px;
	font-family: "Open Sans", sans-serif;
	width: 64%;
}

.call_now2 {
    font-size: 25px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 15px;
	font-family: "Open Sans", sans-serif;
}

.fo_si {
 font-size: 19px;
 width: 64%;
}
@media(min-width:1440px) and (max-width:2254px)
{
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
 </style>
 <?php include("../ticker.php"); ?>
<section id="header" class="wrapper header-wrapper top_header" >
	<div class="container header-container" >
    	<a  target="_blank" rel="noopener" title="Click here to visit - www.tn.gov.in" href="http://www.tn.gov.in/"><h1 class="logo" ></h1></a>
        <a class="initiative_logo" target="_blank" rel="noopener" title="Click here to visit - www.tn.gov.in" href="http://www.tn.gov.in/">
        <img src="theme/images/logo4.png"   alt="tnlogo"/>
        </a>
    <div class="clear_text"></div>
        <div class="header-right call_now_head clearfix home_header">
            <div class="right-content clearfix">
                <div class="float-top-element">
                	 <span class="call_now" id="v3"><?php echo $lang['GOVT_TITLE']; ?></span>
                </div>  
				<div class="float-top-element">
                	 <span class="call_now2" id="v4"><?php echo $lang['PPP_TITLE']; ?><br> 
					 <label class="fo_si" id="v5"><?php echo $lang['Online_Petition_Button_nri']; ?></label></span>
                </div>
            </div>
        </div>
    </div>
</section>



