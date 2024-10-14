<?php
	
if(!isset($_SERVER['HTTP_REFERER'])){
	echo '<h2>Forbidden!</h2>';
	echo '<p>403 Error Found.</p>';
	exit;
}
session_start();

$RandomStr = md5(microtime());// md5 to generate the random string

$ResultStr = substr($RandomStr,0,5);//trim 5 digit 

$NewImage =imagecreatefromjpeg("images/captcha_background.jpg");//image create by existing image and as back ground 

//$LineColor = imagecolorallocate($NewImage,255,0,0);//line color 
$TextColor = imagecolorallocate($NewImage, 000, 000, 000);//text color-white

//imageline($NewImage,1,1,90,40,$LineColor);//create line 1 on image 
//imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image 

imagestring($NewImage, 70, 15, 5, $ResultStr, $TextColor);// Draw a random string horizontally 

$_SESSION['key2'] = $ResultStr;// carry the data through session

header("Content-type: image/jpeg");// out out the image 

imagejpeg($NewImage);//Output image to browser 

imagedestroy($NewImage);

?>

