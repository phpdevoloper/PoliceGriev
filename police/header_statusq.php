<?php
error_reporting(0);
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
header('X-Frame-Options: DENY');
header('X-Frame-Options: SAMEORIGIN');

ob_start();
session_start();
 
//Expire the session if user is inactive for 30
//minutes or more.
$expireAfter = 30;
 
//Check to see if our "last action" session
//variable has been set.
if(isset($_SESSION['last_action'])){
    
    //Figure out how many seconds have passed
    //since the user was last active.
    $secondsInactive = time() - $_SESSION['last_action'];
    
    //Convert our minutes into seconds.
    $expireAfterSeconds = $expireAfter * 60;
    
    //Check to see if they have been inactive for too long.
    if($secondsInactive >= $expireAfterSeconds){
        //User has been inactive for too long.
        //Kill their session.
        session_unset();
        session_destroy();
    }
    
}
 
//Assign the current timestamp as the user's
//latest activity
$_SESSION['last_action'] = time();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">


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
#t3{
	font-size: 18px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
#t4{
	font-size: 22px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
#t5{
	font-size: 16px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
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
 margin-top: 16px;
 font-weight: bold;
 letter-spacing: 2px;
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

<section id="header" class="wrapper header-wrapper top_header" style="background-color:#00cefd"><a class="initiative_logo" target="_blank" rel="noopener" title="Click here to visit - www.tn.gov.in" href="http://www.tn.gov.in/">
	<div class="container header-container">
    	
        
<!--img src="images/b.jpg" class="img-responsive img-thumbnail tnlogo" alt="Tamil Nadu Police Logo" style="padding: 0; overflow: hidden; width: 100%; height: 70%;"-->        
    <div class="clear_text"></div>
        <div class="header-right call_now_head clearfix home_header" >
            <div class="right-content clearfix">
                <div class="float-top-element">
                	 <span ><h1 class="call_now" id="v3"><?php echo $lang['GOVT_TITLE']; ?></h1></span>
					 <br>
					 <br>
					 <br>
                </div>  
				<div class="float-top-element">
                	 <span ><h1 class="call_now2" id="v4"><?php echo $lang['PPP_TITLE']; ?></h1> 
                </div>
				<div class="float-top-element">
                	 <span ><h1 class="fo_si" id="v5"><?php echo $lang['PROJECT_NAME']; ?></h1> 
                </div>
            </div>
        </div>
    </div></a>
</section>