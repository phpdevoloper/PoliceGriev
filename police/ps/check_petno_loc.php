<?php 
ob_start();
session_start();
include('db.php');
include("common_date_fun.php");
$source_frm = $_POST['source_frm'];   
if($source_frm=='check_petno')
{  
	$num_rows = '';
	$petno=stripQuotes(killChars(trim($_POST['petno'])));
	$qua_sql = "select griev_district_id from pet_master where petition_no='".$petno."'";
	$qua_rs=$db->query($qua_sql);
	echo $num_rows=$qua_rs->rowCount();
} 

?>
 
