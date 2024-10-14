<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$mode=$_POST["mode"]; 
/* 
if ($mode=='p_search_officers') {
	$dept_id=stripQuotes(killChars($_POST["dept_id"]));
	$desig_first=stripQuotes(killChars($_POST["desig_first"]));
	
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$levl=stripQuotes(killChars($_POST["levl"]));
	$dist_id=stripQuotes(killChars($_POST["dist_id"]));
	$cond="";
	if ($levl != ""){
		if($levl=='above'){
		$cond.= " and off_level_id<=13";
		}else if($levl=='below'){
		$cond.= " and off_level_id>13";
		}
	}
	if ($dist_id != ""){
		$cond.= " and off_hier[13]=$dist_id ";
	}if ($dept_id != "")	 {
		$cond.= " and dept_id=".$dept_id."";
		
	}
	
	$desig_cond="";
	
	if ($desig_first != "")	 {
		$desig_cond= " and dept_desig_name like '".$desig_first."%'";
		
	}
	
	if ($userProfile->getOff_level_id() == 1) {
		$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id,mobile,email
		from vw_usr_dept_users_v_sup
		where off_hier[1]=33 and dept_id=".$userProfile->getDept_id()." and off_level_id=1".$desig_cond." order by off_level_id, off_loc_id,  dept_desig_name"	;
	} else {
		$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id,mobile,email
		from vw_usr_dept_users_v_sup
		where off_hier[2]=".$userProfile->getDistrict_id().$cond. " and off_level_dept_id>=".$userProfile->getOff_level_dept_id().$desig_cond."
		--order by off_level_dept_id, off_loc_name,  dept_desig_name
		 order by off_level_id, off_loc_id,  dept_desig_name";
	}
	/*$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id,mobile,email
	from vw_usr_dept_users_v_sup
	where off_level_dept_id=".$userProfile->getOff_level_dept_id().$cond.$desig_cond."
	order by off_level_id, off_loc_id,  dept_desig_name";
	*/
	if ($mode=='p_search_officers') {
	   $dept_id=stripQuotes(killChars($_POST["dept_id"]));
	$desig=stripQuotes(killChars($_POST["desig"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$levl=stripQuotes(killChars($_POST["levl"]));
	$dist_id=stripQuotes(killChars($_POST["dist_id"]));
	$desig_first=stripQuotes(killChars($_POST["desig_first"]));
	$cond="";
	if ($desig_first != "")	 {
		$desig_cond= " and dept_desig_name like '".$desig_first."%'";
		
	}
	
	if ($levl != ""){
		if($levl=='above'){
		$cond.= " and off_level_id<=13";
		}else if($levl=='below'){
		$cond.= " and off_level_id>=13";
		}
	}
	if ($dist_id != ""){
		$cond.= " and off_hier[13]=$dist_id ";
	}if ($dept_id != "")	 {
		$cond.= " and dept_id=".$dept_id."";
		
	}if($userProfile->getOff_level_id()==11){
		$cond.=" and off_hier[9]=".$userProfile->getZone_id();
		
	}if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && 
	   $userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() 
	   && $userProfile->getPet_disposal()) {
		   $search_condition = " off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id();
	   } else {
		  $search_condition = " off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." ";
		   //and off_loc_id=".$userProfile->getOff_loc_id();
		   
			//$search_condition = " off_hier[13]=".$userProfile->getDistrict_id()." ";
	   } 
	   if ($userProfile->getDept_user_id() == 1) {
	   $sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id,mobile,email
		from vw_usr_dept_users_v_sup
		where  enabling ".$cond."
		order by off_level_id, off_loc_id,  dept_desig_id";	
	   
	   }else{
		$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id,mobile,email
		from vw_usr_dept_users_v_sup
		where ".$search_condition.$cond. " and enabling and off_level_dept_id>=".$userProfile->getOff_level_dept_id().$desig_cond."
		order by off_level_id, off_loc_id,  dept_desig_id";	
	   }
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo $page->generateXMLTag('off_loc_name', $row['off_loc_name']);
		echo $page->generateXMLTag('off_level_dept_name', $row['off_level_dept_name']);
		echo $page->generateXMLTag('dept_desig_name', $row['dept_desig_name']);
		echo $page->generateXMLTag('dept_desig_tname', $row['dept_desig_tname']);
		echo $page->generateXMLTag('user_name', $row['user_name']);
		echo $page->generateXMLTag('dept_user_id', $row['dept_user_id']);	
		echo $page->generateXMLTag('mobile', $row['mobile']);	
		echo $page->generateXMLTag('email', $row['email']);	
	}
	
	if ($dept_id != "")	 {
		$sql_n="SELECT dept_id, dept_name  FROM usr_dept where  dept_id=".$dept_id."";
		$res= $db->query($sql_n);
		$rowarray = $res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			echo $page->generateXMLTag('dept_name', $row['dept_name']);	
		}
	} else {
		echo $page->generateXMLTag('dept_name', 'All Departments');
	}
	echo "</response>";
}
else if ($mode=='update_profile') {
	$mobile=stripQuotes(killChars($_POST["mobilearray"]));
	$email=stripQuotes(killChars($_POST["emailarray"]));
	$marray = explode(",", $mobile); 
	$earray = explode(",", $email); 
	$mnos = 0;
	$enos = 0;
	foreach($marray as $mobiles) {
		$data = explode("*", $mobiles);
		 $sql="UPDATE usr_dept_users set mobile='".$data[1]."' where dept_user_id=".$data[0]."";
		$result=$db->query($sql);
		if ($result) {
			$mnos = $mnos + 1;
		}
	}

	foreach($earray as $emails) {
		$data = explode("*", $emails);
		  $sql="UPDATE usr_dept_users set email='".$data[1]."' where dept_user_id=".$data[0]."";
		$result=$db->query($sql);
		if ($result) {
			$enos = $enos + 1;
		}
	}
		
	echo "<response>";   
		if (($mnos > 0) || ($enos > 0))
			echo "<count>1</count>";
		else
			echo "<count>0</count>";
	echo "</response>";
	
}

?>