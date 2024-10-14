<?php 
error_reporting(0);
ob_start();
include('db.php');
include("common_date_fun.php");
include('newSMS.php');
include_once 'common_lang.php';
$mode =$_POST['mode'];				 
  
 
if($mode=='chk_mobile')
{  	
	$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	
		
	$ip=$_SERVER['REMOTE_ADDR'];	
	$today = $date->format('Y-m-d H:i:s');
	
	$mobile_no=stripQuotes(killChars(trim($_POST['mobile_no'])));
	$qua_sql = "SELECT user_mobile, otp  FROM usr_online where user_mobile='".$mobile_no."'";
	$qua_rs=$db->query($qua_sql);
	$num_rows=$qua_rs->rowCount();
	$length = 6; 
	$chars = '1234567890';
    $chars_length = (strlen($chars) - 1);
    $string = $chars[rand(0, $chars_length)];
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        $r = $chars[rand(0, $chars_length)];
        $string .=  $r;
    }
		if($_SESSION["lang"]=='en')
		{
			$stringmsg = 'Your OTP to log in into Online GDP is '.$string.'';
		}
		else if($_SESSION["lang"]=='ta')
		{
			$stringmsg = 'இணையவழி மனுக்கள் மனுப் பரிசீலனை முகப்பு இல் உள்நுழைவு உங்கள் OTP எண் '.$string.'';
		}
		else{
			$stringmsg = 'Your OTP to log in into online gdp is '.$string.'';
		}
	$stringmsg = 'Your OTP to log in into online gdp is '.$string.'';

	if ($mobile_no != "") {
		$status = SMS($mobile_no,$stringmsg);		
	}
	
	if ($num_rows == 0) {
						
		$query = "INSERT INTO usr_online(user_mobile, otp,  enabling,entby, entdt, ent_ip_address) VALUES (?, ?,'true', ?, ?, ?)";
		$result = $db->prepare($query);
		$result->execute(array($mobile_no, $string, $mobile_no, $today, $ip));
		
	} else {
		$query = "UPDATE usr_online SET  otp=?, modby=?, moddt=?, mod_ip_address=? WHERE user_mobile=?";
		$result = $db->prepare($query);
		$result->execute(array($string,$mobile_no, $today, $ip,$mobile_no));
	}
	
	echo "<response>";
		if ($status == 0) {
			echo "<count>1</count>";
			echo "<otp>".$string."</otp>";
		} else {
			echo "<count>0</count>";
			echo "<otp>".$string."</otp>";
		}
	echo "</response>";
} 
else if ($mode=='resend_otp')
{
	$mobile_no=stripQuotes(killChars(trim($_POST['mobile_no'])));
	$string = stripQuotes(killChars(trim($_POST['otp'])));
	$stringmsg = 'Your OTP to log in into online gdp is '.$string.'';

	if ($mobile_no != "") {
		$status = SMS($mobile_no,$stringmsg);		
	}
	echo "<response>";
		if ($status == 0) {
			echo "<count>1</count>";
			echo "<otp>".$string."</otp>";
		} else {
			echo "<count>0</count>";
			echo "<otp>".$string."</otp>";
		}
	echo "</response>";
}
?>

