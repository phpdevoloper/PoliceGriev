<?php
session_start();
ob_start();
include("db.php");
include("Pagination.php");

include_once 'common_lang.php';

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
	$_SESSION["attempts"]=0;
	unset($_SESSION["pagetoken"]);
	$_SESSION["pagetoken"]=randomPrefix(20);
}

$ip=$_SERVER['REMOTE_ADDR'];

if(isset($_POST["mobilenumber"]) && isset($_POST['otp']))
{
	$mobilenumber = pg_escape_string(strip_tags(trim($_POST["mobilenumber"])));
	$otimep = pg_escape_string(strip_tags(trim($_POST["otp"])));
 
	/*
	$sql = "select user_mobile,otp  from usr_online where user_mobile=?";
	$result = $db->prepare($sql);
	$result->execute(array($mobilenumber));
	$count = $result->rowCount();
	$rowarray = $result->fetch(PDO::FETCH_ASSOC);
	
	 
	$otp=$rowarray['otp'];
	*/ 
   	  
 
	//if($rowarray['user_mobile']==$mobilenumber && $otp==$otimep)
	if($mobilenumber=='9876543210' && $otimep=='234234')
	{

		$_SESSION['USER_ID_PK']=$mobilenumber;
		//ini_set('session.gc-maxlifetime', 1);//HERE TO SET SESSION TIME MAXIMUM OF 30 MINUTES
		ini_set('session.gc-maxlifetime', 1);//HERE TO SET SESSION TIME MAXIMUM OF 30 MINUTES
		echo "welcome_to_e_district.php";
		header("location:welcome_to_e_district_online.php");
		pg_close($db);
	} else {
		$_SESSION['error_msg'] = $lang['MODAL_OTP_LOGIN_FAILED_DESC'];											
		pg_close($db);
		header("Location: index.php");
	}
	
}else{
	header("Location: logout.php");	
}

?>