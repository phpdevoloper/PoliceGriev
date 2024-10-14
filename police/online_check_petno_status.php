<?php 
error_reporting(0);
ob_start();
session_start();
include('db.php');
include("common_date_fun.php");

$petition_no =$_POST['petition_no'];  

	$usr_mobile=stripQuotes(killChars(trim($_SESSION['USER_ID_PK'])));

	$qua_sql = "select comm_mobile from pet_master where petition_no='".$petition_no."'";
			
	$qua_rs=$db->query($qua_sql);
	//$rowArr = $qua_rs->fetch(PDO::FETCH_BOTH);
	$rowarray = $qua_rs->fetchall(PDO::FETCH_ASSOC);

	foreach($rowarray as $row) {
		$mobile = $row['comm_mobile'];
	}
	//echo ">>>>>>>>>>>>>>>>".$mobile;
	//exit;
		 if ($mobile == $usr_mobile){
			echo true;	
		}else{
			echo false;
		} 

?>

