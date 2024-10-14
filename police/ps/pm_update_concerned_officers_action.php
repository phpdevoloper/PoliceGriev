<?php
ob_start();
session_start();
include('db.php');
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$source_frm =$_POST['source_frm'];
if ($source_frm=='loadSupervisoryOfficers') {
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$disposing_officer=stripQuotes(killChars($_POST['disposing_officer']));
	$f_to_whom=stripQuotes(killChars($_POST['f_to_whom']));
	$supervisory_officer=stripQuotes(killChars($_POST['supervisory_officer']));
		
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == 0) ? '':$dept_off_level_pattern_id;
	$dept_off_level_office_id=($dept_off_level_office_id == 0) ? '':$dept_off_level_office_id;
	
	//User Profile
	$up_off_level_id=$userProfile->getOff_level_id();
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
	$up_dept_id=$userProfile->getDept_id();
	$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	$up_off_level_dept_id=$userProfile->getOff_level_dept_id();
	
	$petition_office_loc_id=stripQuotes(killChars($_POST['petition_office_loc_id']));
	$pet_off_id=stripQuotes(killChars($_POST['pet_off_id']));
	$petition_office_loc_id=($petition_office_loc_id == '') ? $pet_off_id:$petition_office_loc_id;

// 	
	if ($up_dept_off_level_pattern_id == ''){
		$up_dept_off_level_pattern_id='null';
	}	
	if ($up_dept_off_level_pattern_id == 'null'){
		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
			$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$dept_off_level_pattern_id.")"; 
		}
	} else {
		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
			$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
		}		
	}
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
	off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
	and dept_desig_role_id in (2,3) ".$condition." order by dept_user_id limit 1";
	
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_hier=$row["off_hier"];		
	}
	
	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." 
	and 
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then off_level_id<=".$off_level_id."
	else off_level_id<".$off_level_id." 
	end 
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and 
	case 
	when off_level_id=".$off_level_id." then dept_desig_role_id in (1,2)
	else true
	end
	and 
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then COALESCE(off_head,false) is false
	else true
	end
	and dept_user_id != ".$disposing_officer." and COALESCE(enabling,true)
	order by off_level_dept_id,off_level_id,dept_desig_name";
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="supervisory_officer" id="supervisory_officer" data_valid='yes'  data-error="Please select Office" class="select_style" >
<option value="">--Select Supervisory Officer--</option>
<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		if ($f_to_whom == $row["dept_user_id"])
			print("<option value='".$row["dept_user_id"]."' selected>".$dept_desig_name." - ".$off_loc_name."</option>");
		else
			print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
	}
?>
</select>
<?php 
}else if ($source_frm=='loadConcernedOfficers') {
	//Basic parameters	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$disposing_officer=stripQuotes(killChars($_POST['disposing_officer']));
	$disposing_officer=stripQuotes(killChars($_POST['disposing_officer']));
	$supervisory_officer=stripQuotes(killChars($_POST['supervisory_officer']));
	$l_to_whom=stripQuotes(killChars($_POST['l_to_whom']));
		
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == 0) ? '':$dept_off_level_pattern_id;
	$dept_off_level_office_id=($dept_off_level_office_id == 0) ? '':$dept_off_level_office_id;
	
	//User Profile
	$up_off_level_id=$userProfile->getOff_level_id();
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
	$up_off_level_dept_id=$userProfile->getOff_level_dept_id();
	$up_dept_id=$userProfile->getDept_id();
	$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	
	$petition_office_loc_id=stripQuotes(killChars($_POST['petition_office_loc_id']));
	$pet_off_id=stripQuotes(killChars($_POST['pet_off_id']));
	$petition_office_loc_id=($petition_office_loc_id == '') ? $pet_off_id:$petition_office_loc_id;

	if ($up_dept_off_level_pattern_id == ''){
		$up_dept_off_level_pattern_id='null';
	}	
	if ($up_dept_off_level_pattern_id == 'null'){
		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
//			$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$dept_off_level_pattern_id.")"; 
			$condition = " and (dept_off_level_pattern_id=".$dept_off_level_pattern_id.")"; 
		}
	} else {
/*		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
			$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
		}*/
			$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
	}
	
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
	off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
	and dept_desig_role_id in (2,3) ".$condition." order by dept_user_id limit 1";
	//echo $sql;
	
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_hier=$row["off_hier"];		
	}
	
	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	//echo ">>>>>>>>>>>>>>>>>".$supervisory_officer;
	if ($supervisory_officer != '') {
		$supervisory_officer_cond = " and dept_user_id != ".$supervisory_officer."";
		echo $sql='select off_level_id from vw_usr_dept_users_v_sup where dept_user_id='.$supervisory_officer;
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {
			$off_level_id=$row["off_level_id"];
		}
	}
	
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>=".$off_level_id." 
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then COALESCE(off_head,false) is false
	else true
	end	
	and dept_user_id != ".$disposing_officer.$supervisory_officer_cond."
	and COALESCE(enabling,true)
	order by off_level_dept_id,off_level_id,dept_desig_name";
	echo $sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="concerned_officer" id="concerned_officer" data_valid='yes'  data-error="Please select Officer" class="select_style" >
<option value="">--Select Enquiry Officer--</option>
<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		if ($l_to_whom == $row["dept_user_id"])
		print("<option value='".$row["dept_user_id"]."' selected>".$dept_desig_name." - ".$off_loc_name."</option>");
		else
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
	}
?>
</select>
<?php	
} 

?>
