<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
$userProfile->getOff_desig_emp_name();
$mode=$_POST["mode"];
if($mode=='p1_search'){
	$district_id=$userProfile->getDistrict_id();
	$griev_sub_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
	$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
	$griev_district_id=stripQuotes(killChars($_POST["griev_district_id"]));
	$department_id=stripQuotes(killChars($_POST["dept_id"]));
	$petition_id=stripQuotes(killChars($_POST["petition_id"]));
	$act_type_code=stripQuotes(killChars($_POST["act_type_code"]));
	
	$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	
	
	if ($disp_officer == '') {
		$disp_officer = $userProfile->getDept_user_id();
	}
/*
Sql stetement from petition entry screen
$sql="select off_hier from vw_usr_dept_users_v_sup 
where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
and dept_desig_role_id=3 ".$condition." order by dept_user_id limit 1";
*/
//dept_id should not hard coded..s hould come from Form; dept_off_level_pattern_id=".$dept_off_level_pattern_id.",
//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
		$up_off_level_id=$userProfile->getOff_level_id();
		$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
		$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
		$up_dept_id=$userProfile->getDept_id();
		$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
		$up_off_level_dept_id=$userProfile->getOff_level_dept_id();
		if ($up_dept_off_level_pattern_id == ''){
			$up_dept_off_level_pattern_id='null';
		}

		if ($dept_off_level_pattern_id == 'null' ||$dept_off_level_pattern_id == ''){
		$condition = "  ";	 
	} else {					
		$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$dept_off_level_pattern_id.")";	
	}
	
/*		if ($up_dept_off_level_pattern_id == 'null'){
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
		}*/

	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=".$department_id." and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()." and 
	off_level_id=".$off_level_id." and off_loc_id=".$pet_loc_id."
	and dept_desig_role_id in (2,3) ".$condition."
	order by dept_user_id limit 1";
	
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row['off_hier'];			
	}

	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	//$subr_off_level_id is next level to logged in user
	if($dept_off_level_pattern_id==4){
		if($up_off_level_id==7){
			$subr_off_level_id=9;
		}else if($up_off_level_id==9){
			$subr_off_level_id=13;
		}else if($up_off_level_id==13){
			$subr_off_level_id=42;
		}else if($up_off_level_id==42){
			$subr_off_level_id=46;
		}
	}else {
		if($up_off_level_id==7){
			$subr_off_level_id=9;
		}else if($up_off_level_id==9){
			$subr_off_level_id=11;
		}else if($up_off_level_id==11){
			$subr_off_level_id=13;
		}else if($up_off_level_id==13){
			$subr_off_level_id=42;
		}else if($up_off_level_id==42){
			$subr_off_level_id=46;
		}
	}
//------------------------------------------
	if($off_level_id>$subr_off_level_id){
	// submission level > enquiry filing level > petition level (enquiry officer)
		$codn=" and off_level_id<".$off_level_id."";
	}else if($subr_off_level_id==$off_level_id) {
	// submission level > enquiry filing level = petition level (enquiry officer)
		if($dept_off_level_pattern_id==3 && $up_dept_off_level_pattern_id=='null' && $off_level_id==9){
			//for dgp, ACOP only
			$codn=" and off_level_id<".$off_level_id."";
		}else{
			$codn=" and off_level_id<=".$off_level_id."";
		}
	}
	$codn.=" and pet_act_ret";
	if($act_type_code=='D'){
		$codn.=" and pet_disposal";
	}
	if($dept_off_level_pattern_id==3){
		$codn.=" and off_level_dept_id>=".$userProfile->getOff_level_dept_id()."";
	}
/*
	if($subr_off_level_id!=$off_level_id && $subr_off_level_id>$off_level_id && $subr_off_level_id!=7){
		//logged in user to circle / division / zone / range and petition not in state level
		//user next level and petition level are same
		//echo '11'.">>".$subr_off_level_id.'>>>'.$off_level_id;
		$codn=" and off_level_id<=".$off_level_id."";
	}else if($subr_off_level_id==$off_level_id && $subr_off_level_id==7 && $off_level_id!=9){
		//logged in user(not in zone) to next level office and user next level in state level and petition not in zone
		//for cop madurai and chennai except igp 
		//echo '12'.">>".$subr_off_level_id.'>>>'.$off_level_id;
		//$off_level_id=9;
		$codn=" and off_level_id<=$off_level_id";
	}else if($subr_off_level_id==$off_level_id && $subr_off_level_id!=7){
		//user next level and petition level same
		//user next level not in state level(for dgp office)
		//	echo '13'.">>".$subr_off_level_id.'>>>'.$off_level_id.">>".$up_dept_off_level_pattern_id;
		if($dept_off_level_pattern_id==3 && $up_dept_off_level_pattern_id=='null'){
			//for dgp, ACOP only
			$codn=" and off_level_id<".$off_level_id."";
		}else{
			$codn=" and off_level_id<=".$off_level_id."";
		}
	}else{
		//logged in user level greater than/equal to petition level and petition in state level
		//user next level and petition level not same 
		//	echo '14'.">>".$subr_off_level_id.'>>>'.$off_level_id;
		$codn=" and off_level_id<".$off_level_id."";
	} */

	//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
	$query="select a1.dept_user_id, a1.dept_desig_id, a1.off_loc_name, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
	a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup a1
	where dept_id=".$department_id.$condition."
	and dept_desig_role_id in (2,3) and 
	(off_level_id>=".$userProfile->getOff_level_id().")
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
	and dept_user_id!=".$_SESSION['USER_ID_PK']." order by dept_user_id";
//echo $subr_off_level_id.'>>>>>>>'.$off_level_id;
	
	$query="select a1.dept_user_id, a1.dept_desig_id, a1.off_loc_name, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
	a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup a1
	where dept_id=".$department_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>".$up_off_level_id." 
	$codn
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]  
	and dept_user_id != ".$_SESSION['USER_ID_PK']." 
	and COALESCE(enabling,true)
	order by off_level_dept_id,off_level_id,dept_desig_name";
	//echo 'qqqqqqq';	echo $griev_district_id;exit;
	
	//echo $query;
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<dept_user_id>".$row['dept_user_id']."</dept_user_id>";
		echo "<off_location>".$row['off_loc_name']."</off_location>";
		echo "<dept_desig_name>".$row['off_location_design']."</dept_desig_name>";
		echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
		echo "<off_level_name>".$row['off_level_dept_name']."</off_level_name>";
		echo "<off_tlocation>".$row['off_loc_tname']."</off_tlocation>";
		echo "<dept_desig_id>".$row['dept_desig_id']."</dept_desig_id>";
	}
	//echo "<sql>$query</sql>";
	$sql_count = 'SELECT COUNT(dept_user_id) FROM ('.$query .'
	) off_level';
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "</response>";
} else if($mode=='p1_enq_search'){
	$district_id=$userProfile->getDistrict_id();
	//$off_level_id=$userProfile->getOff_level_id();
	$griev_sub_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
	$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
	$griev_district_id=stripQuotes(killChars($_POST["griev_district_id"]));
	$department_id=stripQuotes(killChars($_POST["dept_id"]));
	$petition_id=stripQuotes(killChars($_POST["petition_id"]));
	$act_type_code=stripQuotes(killChars($_POST["act_type_code"]));
	$sup_officer=stripQuotes(killChars($_POST["sup_officer"]));  //Supervisory Officer
	
	$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	
	
	if ($disp_officer == '') {
		$disp_officer = $userProfile->getDept_user_id();
	}
/*
Sql stetement from petition entry screen
$sql="select off_hier from vw_usr_dept_users_v_sup 
where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
and dept_desig_role_id=3 ".$condition." order by dept_user_id limit 1";
*/
//dept_id should not hard coded..s hould come from Form; dept_off_level_pattern_id=".$dept_off_level_pattern_id.",
//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
		$up_off_level_id=$userProfile->getOff_level_id();
		$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
		$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
		$up_dept_id=$userProfile->getDept_id();
		$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
		$up_off_level_dept_id=$userProfile->getOff_level_dept_id();
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
	where dept_id=".$department_id." and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()." and 
	off_level_id=".$off_level_id." and off_loc_id=".$pet_loc_id."
	and dept_desig_role_id in (2,3) ".$condition."
	order by dept_user_id limit 1";
	
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row['off_hier'];			
	}

	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
	$sql="select off_level_id,off_level_dept_id from vw_usr_dept_users_v_sup where dept_user_id=".$sup_officer;
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	foreach($rowarray as $row)
	{
		$efo_off_level_id = $row['off_level_id'];
		$efo_off_level_dept_id = $row['off_level_dept_id'];
	} 
	//--------------------------------------------------
	if($efo_off_level_id==$off_level_id){
		$codn1="and off_level_id>=".$efo_off_level_id."";
	} else if($efo_off_level_id!=$off_level_id && $efo_off_level_id==7){
		//if adgp selected in enquiry filing officer, cop tn not available for enquiry officer because cop and adgp are in state level
		$codn1="and off_level_id>=".$efo_off_level_id."";
	}  else{
		$codn1="and off_level_id>".$efo_off_level_id."";
	}  
		if($dept_off_level_pattern_id==3){
		$codn1.=" and off_level_dept_id>=".$efo_off_level_dept_id;
		}
		$codn1.=" and dept_user_id!= ".$sup_officer;
		$codn1.=" and pet_act_ret";
	
	//--------------------------------------------------
	$query="select a1.dept_user_id, a1.dept_desig_id, a1.off_loc_name, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
	a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup a1
	where dept_id=".$department_id.$condition." 
	and dept_desig_role_id in (2,3) 
	$codn1
	and off_level_id<=".$off_level_id."
	and 
	off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and 
	dept_user_id != ".$_SESSION['USER_ID_PK']." 
	and COALESCE(enabling,true)
	order by off_level_dept_id,off_level_id,dept_desig_name";
	
	//echo $query;
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<dept_user_id>".$row['dept_user_id']."</dept_user_id>";
		echo "<off_location>".$row['off_loc_name']."</off_location>";
		echo "<dept_desig_name>".$row['off_location_design']."</dept_desig_name>";
		echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
		echo "<off_level_name>".$row['off_level_dept_name']."</off_level_name>";
		echo "<off_tlocation>".$row['off_loc_tname']."</off_tlocation>";
		echo "<dept_desig_id>".$row['dept_desig_id']."</dept_desig_id>";		
		
	}

	//echo "<sql>$query</sql>";
	$sql_count = 'SELECT COUNT(dept_user_id) FROM ('.$query .'
	) off_level';
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "</response>";
}

?>
