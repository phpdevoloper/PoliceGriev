<?php 
$_SESSION['logged_in'] = true; //set you've logged in
$_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
$_SESSION['expire_time'] = 60; //expire time in seconds: three hours (you must change this)
include("header_menu.php");
include("menu_home.php");
?>
<div style="min-height:300px;">
	<div align="center" style="margin-top:150px;">
	
		<?php if ($_SESSION['lang'] == 'E') { ?>
    	<span style="font-weight:bold;font-size:26px;color: rgb(0,0,225);">Welcome to Police Station Complaint Redressal System - Tamil Nadu Police
</span>
		<?php } else { ?>
		<span style="font-weight:bold;font-size:22px;color: rgb(0,0,225);"> தமிழக அரசின் மனுப் பரிசீலனை முகப்பு (ம.ப.மு.)  தங்களை அன்புடன் வரவேற்கிறது</span>
		<?php } ?>
    </div>
</div>
<?php
 include('footer.php');
?>