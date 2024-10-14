<?php 
error_reporting(0);
ob_start();
session_start();
include('db.php');
include("common_date_fun.php");
$source_frm =$_POST['source_frm'];  
if($source_frm=='check_petno')
{  
	$petno=stripQuotes(killChars(trim($_POST['pet_no'])));
	$qua_sql = "select distinct petition_no from pet_master where petition_no='".$petno."'";
	$qua_rs=$db->query($qua_sql);
	echo $num_rows=$qua_rs->rowCount();
} 
?>

