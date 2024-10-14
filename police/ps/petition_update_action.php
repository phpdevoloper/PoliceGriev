<?php 
//error_reporting(0);
ob_start();
session_start();

include('db.php');
include("Pagination.php");
include("common_date_fun.php");
include("UserProfile.php");

$userProfile = unserialize($_SESSION['USER_PROFILE']);
$designId = $userProfile->getDept_desig_id();
 $mode = $_POST["mode"];
  
if($mode=='check_petno')
{
	$petno=stripQuotes(killChars(trim($_POST['pet_no'])));
	$qua_sql = "select petition_id from pet_master  where petition_no='".$petno."'";
	$qua_rs=$db->query($qua_sql);
	while($petrow =  $qua_rs->fetch(PDO::FETCH_BOTH)) {	 
		$pet_id = $petrow['petition_id'];
	}
	echo "<response>";
	if ($pet_id != "") {		
		/* $sql="select l_action_entby action_entby, l_to_whom to_whom from pet_action_first_last where petition_id=".$pet_id." and fn_pet_origin_from_our_office(".$pet_id.",".$_SESSION['USER_ID_PK'].")"; */
		
		$sql="select f_action_entby, f_to_whom,l_action_entby, l_to_whom,f_action_type_code,l_action_type_code from pet_action_first_last where petition_id=".$pet_id." and fn_pet_origin_from_our_office(".$pet_id.",".$_SESSION['USER_ID_PK'].")";
		$rs = $db->query($sql);
		while($row =  $rs->fetch(PDO::FETCH_BOTH)) {
			$f_action_entby = $row['f_action_entby'];
			$f_to_whom = $row['f_to_whom'];
			$l_action_entby = $row['l_action_entby'];
			$l_to_whom = $row['l_to_whom'];
			$f_action_type_code = $row['f_action_type_code'];
			$l_action_type_code = $row['l_action_type_code'];
		}		
		/* if ($l_action_entby==$_SESSION['USER_ID_PK'] || $l_to_whom==$_SESSION['USER_ID_PK']) {
			echo $page->res_Status('t'); //Eligible to update
		} 
		
		else  */
		if ($f_action_entby==$_SESSION['USER_ID_PK'] && $f_to_whom==$l_action_entby && ($l_action_type_code == 'F' ||$l_action_type_code == 'Q')) {
			echo $page->res_Status('t'); //Eligible to update
			echo "<eo>$l_to_whom</eo>";
		} 
		
		else if ($f_action_entby == $l_action_entby && $f_to_whom ==  $l_to_whom && ($l_action_type_code == 'F' || $l_action_type_code == 'Q')) {
			echo $page->res_Status('t'); //Eligible to update
			echo "<eo></eo>";
		}
		
		else if ($f_action_entby == $l_action_entby && $f_to_whom == null && $l_to_whom == null && $l_action_type_code == 'T') {
			echo $page->res_Status('t'); //Eligible to update
			echo "<eo></eo>";
		}

		else if ($f_action_entby==$_SESSION['USER_ID_PK'] && $f_to_whom==$l_to_whom && ($l_action_type_code == 'N' ||$l_action_type_code == 'C' ||$l_action_type_code == 'E' ||$l_action_type_code == 'I' ||$l_action_type_code == 'S')) {
			echo $page->res_Status('t'); //Eligible to update
			echo "<eo>$l_action_entby</eo>";
		}
		
		else if ($f_action_entby==$_SESSION['USER_ID_PK'] && $f_action_entby == $l_to_whom && ($l_action_type_code == 'N' ||$l_action_type_code == 'C' ||$l_action_type_code == 'E'||$l_action_type_code == 'I' ||$l_action_type_code == 'S')) {
			echo $page->res_Status('t'); //Eligible to update
			echo "<eo></eo>";
		}
		
		else {
			$no_action_sql="select count(*) as no_action ,a.fwd_office_level_id from pet_master a where petition_id=".$pet_id."
			--and (coalesce(a.fwd_office_level_id,20)=20) 
			and NOT EXISTS (
			SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
			) group by a.fwd_office_level_id";
			$no_action_rs = $db->query($no_action_sql);
			while($no_action_row =  $no_action_rs->fetch(PDO::FETCH_BOTH)) {
				$no_action = $no_action_row['no_action'];
				$dept_off_level_id=$no_action_row['fwd_office_level_id'];
				
			}
			//&& dept_off_level_id
			if ($no_action > 0 && $dept_off_level_id==$userProfile->getOff_level_id()) {
				echo $page->res_Status('t'); //Eligible to update
			} else {
				echo $page->res_Status('f'); //Not eligible	
			}
		} 
		//2019/9005/17/456777/0205
	} else {
		echo $page->res_Status('w'); //wrong petition no. 	
	}
	echo "</response>";
}

?>
 
