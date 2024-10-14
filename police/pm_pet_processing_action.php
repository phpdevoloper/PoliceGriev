<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
 $mode = $_POST["mode"];
  
if($mode=='Fwd')
{
//update to action entered date, action type code, action remarks and to whom user ID
if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 || $userProfile->getOff_level_id()==13 || $userProfile->getOff_level_id()==42 || $userProfile->getOff_level_id()==44
|| $userProfile->getOff_level_id()==46) {
	$address_to = stripQuotes(killChars(trim($_POST['address_to'])));
	$p1_design = stripQuotes(killChars(trim($_POST['p1_design'])));
	if ($address_to != "")
		$address_to =$address_to;
	else if ($p1_design != "")
		$address_to =$p1_design;
	else
		$address_to = 'null';
	$remark = stripQuotes(killChars(trim($_POST['remark'])));
	$act_type_code = stripQuotes(killChars(trim($_POST['act_type_code'])));
	$pet_action_id = stripQuotes(killChars(trim($_POST['pet_action_id'])));
	$queryUpdate = "UPDATE pet_action SET action_entdt=CURRENT_TIMESTAMP, action_type_code='".$act_type_code."', action_remarks='".$remark."', to_whom=".$address_to." WHERE pet_action_id=".$pet_action_id;
	$count = $db->exec($queryUpdate);
	
	if($act_type_code=='I' || $act_type_code=='S'){
		$fir_dist = stripQuotes(killChars(trim($_POST['fir_dist'])));
		$fir_circle = stripQuotes(killChars(trim($_POST['fir_circle'])));
		$fir_year = stripQuotes(killChars(trim($_POST['fir_year'])));
		$fir_no = stripQuotes(killChars(trim($_POST['fir_no'])));
		$petition_id = stripQuotes(killChars(trim($_POST['petition_id'])));
		if($petition_id==''){
		$petition_id = stripQuotes(killChars(trim($_POST['petition_id1'])));
		}
		$userId = $_SESSION['USER_ID_PK'];
		if($act_type_code=='I'){
			$pet_ext_link=1;
		}else if($act_type_code=='S'){
			$pet_ext_link=2;
		}
		$ip=$_SERVER['REMOTE_ADDR'];
		$sql1="select * from public.pet_master_ext_link where petition_id=".$petition_id." and pet_ext_link_id=".$pet_ext_link."";
		$rs1 = $db->query($sql1);
		$row_cnt1 = $rs1->rowCount();
		if($row_cnt1<=0){
	$sql="INSERT INTO public.pet_master_ext_link(
	petition_id, pet_ext_link_id, district_id, circle_id, pet_ext_link_no, fir_csr_year, lnk_entby, lnk_entdt, ent_ip_address)
	VALUES (".$petition_id.",".$pet_ext_link.",".$fir_dist.",".$fir_circle.",'".$fir_no."','".$fir_year."',".$userId.", current_timestamp, '".$ip."')";
	}else{
	$sql="Update public.pet_master_ext_link set district_id=".$fir_dist.",circle_id=".$fir_circle.",fir_csr_year='".$fir_year."',  pet_ext_link_no='".$fir_no."', lnk_entby=".$userId.", lnk_entdt=current_timestamp,ent_ip_address='".$ip."' where petition_id=".$petition_id." and pet_ext_link_id=".$pet_ext_link."";
	}
	$rs = $db->query($sql);
	$row_cnt = $rs->rowCount();
	echo "<response>";
	echo $page->reponseStatus($row_cnt,'U');
	echo "</response>";EXIT;
	}
	
	echo "<response>";
	echo $page->reponseStatus($count,'U');
	echo "</response>";
	
}
}
?>