<?php 
ob_start();
session_start();
include('db.php');
include("Pagination.php");
include("common_date_fun.php");
include("UserProfile.php");

$userProfile = unserialize($_SESSION['USER_PROFILE']);
$designId = $userProfile->getDept_desig_id();
$mode = $_POST["mode"];
  

if($mode=='check_petno') {  
	$petno1=stripQuotes(killChars(trim($_POST['pet_no1'])));
	$petno2=stripQuotes(killChars(trim($_POST['pet_no2'])));
	$petno3=stripQuotes(killChars(trim($_POST['pet_no3'])));
	  
	$i = 0;
	$cond = "";
	if ($petno1!="")	 {
		$cond = "where a.petition_no in ('".$petno1."')";
		$i = 1;
	}
	if ($petno1!= "" && $petno2!="")	 {
		$cond = "where a.petition_no in ('".$petno1."','".$petno2."')";
		$i = 2;
	}
	if ($petno1!= "" && $petno2!="" && $petno3!="")	 {
		$cond = "where a.petition_no in ('".$petno1."','".$petno2."','".$petno3."')";
		$i = 3;
	}
	   
	$qua_sql = "select b.off_level_id,b.off_loc_id from pet_master a left join vw_usr_dept_users_v_sup b on b.dept_user_id = a.pet_entby  ".$cond."";

	$qua_rs=$db->query($qua_sql);
	$num_rows=$qua_rs->rowCount();
	$rowarray = $qua_rs->fetchall(PDO::FETCH_ASSOC);
			 	  
	echo "<response>";
	if(trim($num_rows)==$i){
		foreach($rowarray as $row){   
			$off_level_id=$row['off_level_id'];
			$off_loc_id=$row['off_loc_id'];

			if($userProfile->getOff_level_id() == $off_level_id && $userProfile->getOff_loc_id() == $off_loc_id)
			{
				if($griev_district_id=="" ) //District
					$result = 't';
				else {
					$result = 'f';
					break;		
				}							  
			}  
					 
		}
		echo $page->res_Status($result);  //$page->res_Status get from Pagination.php page
	}else {
			
		echo $page->res_Status('w'); //wrong petition no.   
	}
				
			echo "</response>";
 
 } 
?>
 
