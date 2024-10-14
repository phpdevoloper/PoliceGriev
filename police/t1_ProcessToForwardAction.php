<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
//include("newSMS.php");
include('sms_airtel_code.php');
include("otp_mail_send.php");
$name = 'NRI Grievance Petition Portal';
$subj = 'NRI Grievance Petition Portal - Petition Disposed'; 

$userProfile = unserialize($_SESSION['USER_PROFILE']);
$mode=$_POST["mode"];
if($mode=='p1_search') {	
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));		  

	if($_SESSION['formptoken'] != $form_tocken) {
	header('Location: logout.php');
	exit; 
	} else { 
		echo "<response>";	 
		//chk logged in user is coordinate department, coordinate office & coordinate designation then only list petitions
		//	if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && ($userProfile->getDept_coordinating() || $userProfile->getDept_id() == 12))
		if(true)
		{	 
			$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
			$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
			$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
			$p_source=stripQuotes(killChars($_POST["p_source"]));
			$dept=stripQuotes(killChars($_POST["dept"]));
			$gtype=stripQuotes(killChars($_POST["gtype"]));
			$ptype=stripQuotes(killChars($_POST["ptype"]));
			$pet_community=stripQuotes(killChars($_POST["pet_community"]));
			$special_category=stripQuotes(killChars($_POST["special_category"]));
			/*----------- For Validate the date format -----------*/

			$dt1=explode('/',$pfrompetdate);
			$day=$dt1[0];
			$mnth=$dt1[1];
			$yr=$dt1[2];
			$p_frompetdate=$yr.'-'.$mnth.'-'.$day;
		
			if (!preg_match($date_regex, $p_frompetdate)) { //$date_regex declared in common_date_fun.php
			 // echo '<br>Your hire date entry does not match the YYYY-MM-DD required format.<br>';
				$p_from_pet_date = '';
			} else {
				//echo '<br>Your date is set correctly<br>'; 
				$p_from_pet_date = "$p_frompetdate";     
			}
			
			$dt2=explode('/',$ptopetdate);
			$day=$dt2[0];
			$mnth=$dt2[1];
			$yr=$dt2[2];
			$p_topetdate=$yr.'-'.$mnth.'-'.$day;
		
			if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
			 // echo '<br>Your hire date entry does not match the YYYY-MM-DD required format.<br>';
				$p_to_pet_date = '';
			} else {
				//echo '<br>Your date is set correctly<br>'; 
				$p_to_pet_date = "$p_topetdate";     
			}
		
 /*------------------------------- End of Validate the date format ------------------------------------------------  */

/*		Important ::: Not to be Deleted		

		$agri_sql = "SELECT a.dept_desig_id, case when (".$userProfile->getOff_loc_id()."=any(a.agri_districts)) then true else false end as agri from usr_dept_desig_disp_sources a
		where a.source_id=11";
	
		$result = $db->query($agri_sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row)
		{
			$agri_desig = $row[dept_desig_id];
			$agri_dist = $row[agri];			
		}
		$agri_condition = "";


		if ($agri_dist == true) {
			//$agri_condition = " a.dept_id=".$agri_dept." and ".$userProfile->getDept_desig_id()=$agri_desig;
			if ($userProfile->getDept_desig_id() == $agri_desig)
				$agri_condition = " a.dept_id=6 and ";
			else
				$agri_condition = " a.dept_id!=6 and a.dept_id!=12 and ";
		} else {
			$agri_condition = " ".$userProfile->getDept_desig_id()."=17 and a.dept_id!=12 and ";
		}
		*/
		$codn='';
		
		if(!empty($p_from_pet_date)){
			$codn.=" AND a.petition_date::date  >= '".$p_from_pet_date."'::date";
		}
		if(!empty($p_to_pet_date)){
			$codn.=" AND a.petition_date::date  <= '".$p_to_pet_date."'::date";
		}
		if(!empty($p_petition_no)){
			$codn.= " AND a.petition_no LIKE '%".$p_petition_no."%'";
		}
		if(!empty($p_source)){
			$codn.= " AND a.source_id=".$p_source;
		}
		
		if(!empty($dept)){
			$codn.= "  AND a.dept_id=".$dept;
		//$countcond.= $countcond=='' ? " and a.dept_id=".$dept :" AND a.dept_id=".$dept;
		}
		if(!empty($gtype)){
			$codn.= " AND a.griev_type_id=".$gtype;
			//$countcond.= $countcond=='' ? " and a.griev_type_id=".$gtype :" AND cc.griev_type_id=".$gtype;
		}
		
		if (!empty($ptype)) {
			//$ptype
			$codn.= " AND a.pet_type_id=".$ptype;
		}
		
		if (!empty($pet_community)) {
			//$ptype
			$codn.= " AND a.pet_community_id=".$pet_community;
		}
		
		if (!empty($special_category)) {
			//$ptype
			$codn.= " AND a.petitioner_category_id=".$special_category;
		}
	if($userProfile->getDesig_roleid() == 5){
	$codn1=" AND pet_type_id!=4";
	$countcodn.= $countcodn=='' ? " WHERE pet_type_id!=4" :" AND pet_type_id!=4";
	}else{
		$codn1='';
	}

		$fwd_offr_cond='';
		$off_hier='['.$userProfile->getOff_hier().']';
		//print_r($userProfile);exit;
	$inSql = "select *,row_number() over (order by aa.petition_id,aa.petition_date) as rownum from
	(SELECT petition_id, petition_no, to_char(pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date,source_name, griev_type_id, griev_type_name, 
	griev_subtype_id, griev_subtype_name, grievance,
	a.dept_id,dept_name,	
	petitioner_name 
	|| ', ' || COALESCE (comm_rev_village_name,'') 
	|| ', ' || COALESCE (comm_taluk_name,'') 
	|| ', ' || COALESCE(comm_district_name,'') 
	|| ', ' || COALESCE(comm_state_name,'') 
	|| ', ' || COALESCE(comm_country_name,'') AS pet_address,
	
	CASE WHEN zone_id IS NOT NULL THEN zone_name
	WHEN range_id IS NOT NULL THEN range_name
	WHEN griev_district_id IS NOT NULL THEN griev_district_name
	WHEN griev_taluk_id IS NOT NULL THEN griev_rev_village_name||', '||griev_taluk_name
	WHEN griev_block_id IS NOT NULL THEN griev_lb_village_name||', '||griev_block_name
	WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_name
	WHEN griev_division_id IS NOT NULL THEN griev_division_name
	WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_name
	WHEN griev_circle_id IS NOT NULL THEN griev_circle_name
	END AS gri_address,
	CASE WHEN zone_id IS NOT NULL THEN zone_id
	WHEN range_id IS NOT NULL THEN range_id
	WHEN griev_district_id IS NOT NULL THEN griev_district_id	
	WHEN griev_taluk_id IS NOT NULL THEN griev_taluk_id
	WHEN griev_block_id IS NOT NULL THEN griev_block_id
	WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_id
	WHEN griev_division_id IS NOT NULL THEN griev_division_id
	WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_id
	WHEN griev_circle_id IS NOT NULL THEN griev_circle_id	
	END AS off_loc_id,griev_district_id,off_level_pattern_id,
	pet_type_id,pet_type_name,pet_type_tname,comm_email,
	COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, 
	a.griev_subdivision_id, a.griev_circle_id,".$userProfile->getState_id().") as pet_loc_id,
	off_level_id, dept_off_level_pattern_id,b.off_level_dept_id,
	fn_off_loc_hierarchy(b.dept_id,dept_off_level_pattern_id,b.off_level_id,COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,".$userProfile->getState_id().")) as off_loc_hier,a.fwd_office_level_id
	FROM vw_pet_master a
	inner join usr_dept_off_level b on b.off_level_dept_id=a.off_level_dept_id
	where 
	NOT EXISTS (
	SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
	)) aa 
	where fwd_office_level_id=".$userProfile->getOff_level_id()." 
	and off_loc_hier[1:".$userProfile->getOff_level_id()."]=(array".$off_hier.")[1:".$userProfile->getOff_level_id()."]
	ORDER BY aa.petition_id";		
	 if($sub_level == 1||$sub_level == 14){
	$fwd_office_level_id=7;
}else if($sub_level == 4||$sub_level == 15||$sub_level == 20){
	$fwd_office_level_id=9;
}else if($sub_level == 5||$sub_level == 16){
	$fwd_office_level_id=11;
}else if($sub_level == 6||$sub_level == 17||$sub_level == 21){
	$fwd_office_level_id=13;
}  
if($userProfile->getOff_loc_id!=''){
	$aaaa="and forward_location=".$userProfile->getOff_loc_id."";
}else{
	$aaaa='';
}
	//echo $inSql;
	$query = 'select * from (
	'.$inSql.'
	)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). 'and petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	$query="select * from 
(
	select *,off_loc_hier[fwd_office_level] as submission_location,row_number() over (order by aa.petition_id,aa.petition_date) as rownum 

from 

(SELECT petition_id, petition_no, to_char(pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date,source_name, griev_type_id, griev_type_name, griev_subtype_id, griev_subtype_name, grievance, a.dept_id,dept_name, petitioner_name || ', Door No.:' || COALESCE (a.comm_doorno,'') || ', ' || COALESCE (a.comm_street,'') || ', ' || COALESCE(a.comm_area,'') || ', Pincode - ' || COALESCE(a.comm_pincode,a.griev_pincode) || '.' AS pet_address, CASE WHEN zone_id IS NOT NULL THEN zone_name WHEN range_id IS NOT NULL THEN range_name WHEN griev_district_id IS NOT NULL THEN griev_district_name WHEN griev_taluk_id IS NOT NULL THEN griev_rev_village_name||', '||griev_taluk_name WHEN griev_block_id IS NOT NULL THEN griev_lb_village_name||', '||griev_block_name WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_name WHEN griev_division_id IS NOT NULL THEN griev_division_name WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_name WHEN griev_circle_id IS NOT NULL THEN griev_circle_name END AS gri_address, CASE WHEN zone_id IS NOT NULL THEN zone_id WHEN range_id IS NOT NULL THEN range_id WHEN griev_district_id IS NOT NULL THEN griev_district_id WHEN griev_taluk_id IS NOT NULL THEN griev_taluk_id WHEN griev_block_id IS NOT NULL THEN griev_block_id WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_id WHEN griev_division_id IS NOT NULL THEN griev_division_id WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_id WHEN griev_circle_id IS NOT NULL THEN griev_circle_id END AS off_loc_id,griev_district_id,off_level_pattern_id, pet_type_id,pet_type_name,pet_type_tname,comm_email, COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,33) as pet_loc_id, b.off_level_id, b.dept_off_level_pattern_id,b.off_level_dept_id, fn_off_loc_hierarchy(b.dept_id,b.dept_off_level_pattern_id,b.off_level_id,COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,33)) as off_loc_hier,a.fwd_office_level_id
 ,c.off_level_id as fwd_office_level,b.off_level_id as submission_level
FROM vw_pet_master a 
left join usr_dept_off_level b on b.off_level_dept_id=a.off_level_dept_id 
 left join usr_dept_off_level c on c.off_level_dept_id=a.fwd_office_level_id
where NOT EXISTS ( SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id )
)aa
)
bbb
where 
bbb.fwd_office_level_id=
".$userProfile->getOff_level_dept_id()." and bbb.submission_location =".$userProfile->getOff_loc_id()." 
and submission_level=".$userProfile->getOff_level_id()."
ORDER BY bbb.petition_id";//print_r($userProfile);exit;
$query="select * from(select * from ( select *,coalesce(off_loc_hier[submission_level],pet_loc_id) as submission_location,coalesce(off_loc_hier[fwd_office_level],29) as forward_location,row_number() over 
			   (order by aa.petition_id,aa.petition_date) as rownum from 
			   (SELECT petition_id, petition_no, to_char(pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date,source_name, griev_type_id, griev_type_name, griev_subtype_id, griev_subtype_name, grievance, a.dept_id,dept_name, petitioner_name || ', Door No.:' || COALESCE (a.comm_doorno,'') || ', ' || COALESCE (a.comm_street,'') || ', ' || COALESCE(a.comm_area,'') || ', Pincode - ' || COALESCE(a.comm_pincode,a.griev_pincode) || '.' AS pet_address, CASE WHEN zone_id IS NOT NULL THEN zone_name WHEN range_id IS NOT NULL THEN range_name WHEN griev_district_id IS NOT NULL THEN griev_district_name WHEN griev_taluk_id IS NOT NULL THEN griev_rev_village_name||', '||griev_taluk_name WHEN griev_block_id IS NOT NULL THEN griev_lb_village_name||', '||griev_block_name WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_name WHEN griev_division_id IS NOT NULL THEN griev_division_name WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_name WHEN griev_circle_id IS NOT NULL THEN griev_circle_name else  
			   'Tamil Nadu' END AS gri_address, CASE WHEN zone_id IS NOT NULL THEN zone_id WHEN range_id IS NOT NULL THEN range_id WHEN griev_district_id IS NOT NULL THEN griev_district_id WHEN griev_taluk_id IS NOT NULL THEN griev_taluk_id WHEN griev_block_id IS NOT NULL THEN griev_block_id WHEN griev_lb_urban_id IS NOT NULL THEN griev_lb_urban_id WHEN griev_division_id IS NOT NULL THEN griev_division_id WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_id WHEN griev_circle_id IS NOT NULL THEN griev_circle_id else 29 END AS off_loc_id,griev_district_id,off_level_pattern_id, pet_type_id,pet_type_name,pet_type_tname,comm_email, COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,29) as pet_loc_id, b.off_level_id, b.dept_off_level_pattern_id,b.off_level_dept_id, fn_off_loc_hierarchy(b.dept_id,b.dept_off_level_pattern_id,b.off_level_id,COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,29)) as off_loc_hier,a.fwd_office_level_id ,c.off_level_id as fwd_office_level,b.off_level_id as submission_level FROM vw_pet_master a left join usr_dept_off_level b on b.off_level_dept_id=a.off_level_dept_id left join usr_dept_off_level c on c.off_level_dept_id=a.fwd_office_level_id where NOT EXISTS ( SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id )$codn )aa ) bbb 
where bbb.fwd_office_level_id= ".$userProfile->getOff_level_dept_id()."  and fwd_office_level=".$userProfile->getOff_level_id()." $aaaa $codn1
ORDER BY bbb.petition_id  ) test where off_loc_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."";
	//echo "<br>".$query."</br>";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

		foreach($rowarray as $row)
		{
			echo $page->generateXMLTag('rownum', $row['rownum']);
			echo $page->generateXMLTag('petition_id', $row['petition_id']);
			
		$link_query = "select fn_chk_link_pet_status as stat from fn_chk_link_pet_status(".$row['petition_id'].")";
  
		$link_res = $db->query($link_query);
		$link_rowarr = $link_res->fetchall(PDO::FETCH_ASSOC);
		foreach($link_rowarr as $link_row)
		{
			echo $page->generateXMLTag('link_stat', $link_row['stat']);
		}
			echo $page->generateXMLTag('petition_no', $row['petition_no']);
			echo $page->generateXMLTag('petition_date', $row['petition_date']);
			echo $page->generateXMLTag('petitioner_name', $row['petitioner_name']);
			echo $page->generateXMLTag('to_whom', $row['to_whom']);
			
			echo $page->generateXMLTag('source_name', $row['source_name']);
			echo $page->generateXMLTag('grievance', $row['grievance']);
			echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
			echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
			echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
			echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
			echo $page->generateXMLTag('off_loc_id', $row['off_loc_id']);
			echo $page->generateXMLTag('dept_id', $row['dept_id']);
			echo $page->generateXMLTag('dept_name', $row['dept_name']);
			//echo $page->generateXMLTag('off_loc_id', $row['off_loc_id']);
			echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
			echo $page->generateXMLTag('off_level_pattern_id', $row['off_level_pattern_id']);
			
			echo $page->generateXMLTag('pet_address', $row['pet_address']);
			echo $page->generateXMLTag('gri_address', $row['gri_address']);
			
			echo $page->generateXMLTag('pet_loc_id', $row['pet_loc_id']);
			echo $page->generateXMLTag('off_level_id', $row['off_level_id']);
			echo $page->generateXMLTag('dept_off_level_pattern_id', $row['dept_off_level_pattern_id']);
			echo $page->generateXMLTag('off_level_dept_id', $row['off_level_dept_id']);
			
				/*$gre_type="SELECT griev_type_id, griev_type_name
					FROM lkp_griev_type ORDER BY griev_type_name";*/
// existing griev_type and griev_subtype in pet_master + their own griev_types 	; dept_id hard coding to be removed			
					
			$gre_type = "SELECT DISTINCT(griev_type_id), griev_type_code,
			griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype
			WHERE dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";
			$resultType = $db->query($gre_type);
			$rowarrayType = $resultType->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarrayType as $rowType)
			{
				echo $page->generateXMLTag('gre_id_'.$row['rownum'], $rowType['griev_type_id']);
				echo $page->generateXMLTag('gre_desc_'.$row['rownum'], $rowType['griev_type_name']);
			}			
			$gre_sub_type = "SELECT distinct(griev_subtype_id), griev_subtype_code,
			griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
			WHERE dept_id = ".$userProfile->getDept_id()." and griev_type_id=".$row['griev_type_id']." 
			ORDER BY griev_subtype_name";
			$resultSubType = $db->query($gre_sub_type);
			$rowarraySubType = $resultSubType->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarraySubType as $rowSubType)
			{
				echo $page->generateXMLTag('id_'.$row['rownum'], $rowSubType['griev_subtype_id']);
				echo $page->generateXMLTag('desc_'.$row['rownum'], $rowSubType['griev_subtype_name']);
			}
			$dept_sql = "select distinct(dept_id),dept_name,dept_tname from vw_usr_dept where dept_id=".$row['dept_id']."";
			$deptresult = $db->query($dept_sql);
			$rowarraydept = $deptresult->fetchall(PDO::FETCH_ASSOC);			
			foreach($rowarraydept as $rowDept)
			{
				echo $page->generateXMLTag('dept_id_'.$row['rownum'], $rowDept['dept_id']);
				echo $page->generateXMLTag('dept_desc_'.$row['rownum'], $rowDept['dept_name']);
			}
		}
		if ($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9) {  // Included for Registration Department
			$sql_count = "SELECT count(petition_id)
			FROM vw_pet_master a
			WHERE  NOT EXISTS (
				SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
			) and (source_id < 0) and ".$fwd_offr_cond." AND a.dept_id=".$userProfile->getDept_id().$codn;
		} 
		//echo $sql_count;
		$rs = $db->query($inSql);
		$count=$result->rowCount();
		//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
		echo "<count>".$count."</count>";
		echo $page->paginationXML($count,stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation 
		if(($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_act_ret()) || $userProfile->getDesig_roleid() == 5){
		 
			$actTypeCode='';
			if($userProfile->getPet_disposal() || $userProfile->getDesig_roleid() == 5){
				$actTypeCode .= "'A', 'R'";
			}
			if($userProfile->getOff_level_id() == 7 || $userProfile->getOff_level_id() == 9 || $userProfile->getOff_level_id() == 11 || $userProfile->getOff_level_id() == 13){
				$actTypeCode .= ",'D'";
			}
			if($userProfile->getPet_forward()){
				$actTypeCode .= $actTypeCode==''?"'F'":" ,'F'";
			}
			
			
		} 

		if($actTypeCode!=''){
			echo $query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.") order by action_type_name";
			$result = $db->query($query);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

			foreach($rowarray as $row)
			{
				echo $page->generateXMLTag('acttype_code', $row['action_type_code']);
				echo $page->generateXMLTag('acttype_desc', $row['action_type_name']);
			}
		}
	}
	echo "</response>";
	}
}
else if($mode=='p1_act_type'){
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken) {
		header('Location: logout.php');
		exit;
	} else {
		echo "<response>";
		
		$petition_id=stripQuotes(killChars($_POST["petition_id"]));
		$dept_id=stripQuotes(killChars($_POST["dept_id"]));
		$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
		$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
		$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
		$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
		
		$sql="select off_hier from vw_usr_dept_users_v_sup 
		where dept_id=1 and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()." and 
		off_level_id=".$off_level_id." and off_loc_id=".$pet_loc_id."
		and dept_desig_role_id in (2,3) and dept_off_level_pattern_id=".$dept_off_level_pattern_id."
		order by dept_user_id limit 1";

		$rs = $db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$off_hier = $row['off_hier'];			
		}
		
		$off_hier=str_replace("{","",$off_hier);
		$off_hier=str_replace("}","",$off_hier);
		$off_hier='['.$off_hier.']';
		
		$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
		a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
		from vw_usr_dept_users_v_sup a1
		where dept_id=".$dept_id." and dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
		and dept_desig_role_id in (2,3) and (off_level_id<=".$off_level_id." 
		and off_level_id>=".$userProfile->getOff_level_id().")
		and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
		and dept_user_id!=".$_SESSION['USER_ID_PK']." order by dept_user_id";
		echo $query;
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row){
			echo $page->generateXMLTag('dept_user_id', $row['dept_user_id']);
			if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
			}else{
				echo $page->generateXMLTag('off_location_design', $row['off_location_tdesign']);
			}
			echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
			echo "<off_level_name>".$row['off_level_dept_name']."</off_level_name>";
		}
		echo "</response>";
	}
	
}
else if($mode=='p1_Fwd'){
	 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 $ip=$_SERVER['REMOTE_ADDR']; 
  if($_SESSION['formptoken'] != $form_tocken)
  {
	 header('Location: logout.php');
	 exit;
  }
 else{
	//$_SESSION['USER_ID_PK'] = ''; 
	if ($_SESSION['USER_ID_PK'] != null || $_SESSION['USER_ID_PK'] != '') {
	$petSnoArr = stripQuotes(killChars($_POST["pet_sno"]));
	$userSnoArr = stripQuotes(killChars($_POST["p1_user_sno"]));
	$enquserSnoArr = stripQuotes(killChars($_POST["p1_enq_user_sno"]));	
	$file_noArr = stripQuotes(killChars($_POST["p1_file_no"]));
	$file_dateArr = stripQuotes(killChars($_POST["p1_file_date"]));
	$remarkArr = stripQuotes(killChars($_POST["p1_remark"]));
	$p1_gri_type = stripQuotes(killChars($_POST["p1_gri_type"]));
	$p1_gri_sub_type = stripQuotes(killChars($_POST["p1_gri_sub_type"]));
	$p1_dept = stripQuotes(killChars($_POST["p1_dept"]));
	$actTypeCodeArr = stripQuotes(killChars($_POST["p1_act_type_code"]));
if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$codn_cc='';
		if ($userProfile->getOff_level_id()==7) {
		$codn_cc=' and dept_user_id=(select dept_user_id from usr_dept_users where dept_desig_id=(select sup_dept_desig_id from usr_dept_desig where dept_desig_id=(select dept_desig_id from usr_dept_users where dept_user_id='.$_SESSION["USER_ID_PK"].')))';
		}
		$sql="select a.dept_user_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_cc."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	 // Or condition newly added for Registration Department Enhancement 25-10-2017
	if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 || $userProfile->getOff_level_id()==11 || $userProfile->getOff_level_id()==13 || $userProfile->getOff_level_id()==42) {
	//Forward petitions
	$i=0;$f_count=0;$n_count=0;$c_count=0;$d_count=0;$t_count=0;$q_count=0;$a_count=0;$r_count=0;$e_count=0;$zz=0;$fc_count=0;
	$pet_id_array = array();
	$pet_act_array = array();
	$pet_mob_array = array();
	$pet_no_array = array();
	$no_of_rec = 0;
	foreach($petSnoArr as $petSno) {
		try {			
			$today = $page->currentTimeStamp();
			$no_of_rec++;

			$f_dt=explode('/',$file_dateArr[$i]);
			$day=$f_dt[0];
			$mnth=$f_dt[1];
			$yr=$f_dt[2];
			$file_date=$yr.'-'.$day.'-'.$mnth;
		
			//Query to insert into pet_action
			$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, to_whom, action_entby, action_entdt,action_ip_address,data_entby) VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp, ?,?)');	
			$array = array($petSno, $actTypeCodeArr[$i], ($file_noArr[$i])? $file_noArr[$i] : NULL , ($file_dateArr[$i])?$file_date: NULL, (empty($remarkArr[$i])?null:$remarkArr[$i]), (empty($userSnoArr[$i])?null:$userSnoArr[$i]),$dept_user_id, $ip, $_SESSION['USER_ID_PK']);
				
			
			//query to update pet_master	
			$queryUpdate = $db->prepare('UPDATE pet_master SET griev_type_id=?, griev_subtype_id=?, dept_id=?, pet_entby=? WHERE petition_id=?');
			$arrayUpdate = array($p1_gri_type[$i], $p1_gri_sub_type[$i], $p1_dept[$i], $_SESSION['USER_ID_PK'], $petSno);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->beginTransaction();
			$query->execute($array);
			
			if ($enquserSnoArr[$i] != '') {
				$query_second = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, to_whom, action_entby, action_entdt,action_ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp, ?)');	
				$array_second = array($petSno, 'F', ($file_noArr[$i])? $file_noArr[$i] : NULL , ($file_dateArr[$i])?$file_date: NULL, (empty($remarkArr[$i])?null:$remarkArr[$i]), (empty($enquserSnoArr[$i])?null:$enquserSnoArr[$i]), (empty($userSnoArr[$i])?null:$userSnoArr[$i]), $ip);
				$query_second->execute($array_second);
			}
					
			$queryUpdate->execute($arrayUpdate);
			$db->commit();

			$count++;//no. of petition processed Forwarded/Accepted/Rejected/Delegated
			switch($actTypeCodeArr[$i]){
				case 'F':
					$f_count++;
					break;
				case 'D':
					$d_count++;
					break;
				case 'N':
					$n_count++;
					break;
				case 'T':
					$t_count++;
					break;
				case 'C':
					$c_count++;
					break;
				case 'Q':
					$q_count++;
					break;
				case 'A':
					$a_count++;
					$pet_id_array[$zz]=$petSno;
					$pet_act_array[$zz]="A";
					break;
				case 'R':
					$r_count++;
					$pet_id_array[$zz]=$petSno;
					$pet_act_array[$zz]="R";
					break;
				case 'E':
					$e_count++;
					break;
			}			
		} catch(Exception $e) {
			//echo "Exception>>>>>>>>>>>>>>>>>>>>>>>>";
			//echo $e->getMessage();
			$db->rollBack();
			$fc_count++;
		}
		$i++;
		$zz++;
	}
	
	$string = rtrim(implode(',', $pet_id_array), ',');
	  //echo $pet_id_array;
	  if ($string != "") {
			$sql = "select petition_id,petition_no,comm_mobile,comm_email,source_id from pet_master where petition_id in  (".$string.") ";
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			$arr_pos=0;
			foreach($rowarray as $row){
				$pet_mob_array[$arr_pos] = $row['comm_mobile'];
				$pet_email_array[$arr_pos] = $row['comm_email'];
				$source_id_array[$arr_pos] = $row['source_id'];
				$pet_no_array[$arr_pos] = $row['petition_no'];
				$arr_pos++;
			}
			for ($val=0;$val<count($pet_no_array);$val++) {
			   
			    if ($_SESSION['lang'] == 'E') {
					if ($pet_act_array[$val] == 'A') {
				   		//$message = "Your Petition No. ".$pet_no_array[$val]." is accepted. URL to check the status: https://locahost/police";
				   		$message = "Your Petition No ".$pet_no_array[$val]." is accepted. URL to check the status: https://locahost/police\n";
						$ct_id="1007986408450576562";
					} else if ($pet_act_array[$val] == 'R') {
						//$message = "Your Petition No. ".$pet_no_array[$val]." is rejected. URL to check the status: https://locahost/police";
						$message = "Your Petition No ".$pet_no_array[$val]." is rejected. URL to check the status: https://locahost/police\n";
						$ct_id="1007326601562468629";
					}
					$ucode='0';	
				} else {
					if ($pet_act_array[$val] == 'A') {
				   		//$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." ஏற்றுக் கொள்ளப்பட்டது. தங்கள் மனுவின் நிலையை "." https://locahost/police "." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
				   		$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." ஏற்றுக் கொள்ளப்பட்டது. தங்கள் மனுவின் நிலையை https://locahost/police என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம் - தமிழ்நாடு மின்ஆளுமை முகமை.";
						$ct_id="1007271408188676800";
					} else if ($pet_act_array[$val] == 'R') {
						//$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." நிராகரிக்கப்பட்டது. தங்கள் மனுவின் நிலையை "." https://locahost/police "." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
						$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." நிராகரிக்கப்பட்டது. தங்கள் மனுவின் நிலையை https://locahost/police என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம் - தமிழ்நாடு மின்ஆளுமை முகமை.";
						$ct_id="1007534771924397850";
					}
					$ucode='2';
					
				}
				$mobile_no = $pet_mob_array[$val];
				$email = $pet_email_array[$val];
				$source_id = $source_id_array[$val];
				
				if ($source_id == -3) {
					//echo "111111111111111111111111111111111";
					smtpmailer($email, $from, $name, $subj, $message);
				} else {
					if ($mobile_no!="" && ((int) substr($mobile_no, 0, 1) >=6 && (int) substr($mobile_no, 0, 1) <=9)) {
						 SMS($mobile_no,$message,$ucode,$ct_id);
					}
				}
							
			}
	  }
	  
	echo "<response>";
	if($count>0){
		echo '<tot>Total no. of Petition(s) '.$count.'</tot>
			<f>Forwarded : '.$f_count.'</f>
			<d>Delegated : '.$d_count.'</d>
			<n>Unrelated so Returned : '.$n_count.'</n>
			<c>Action Taken : '.$c_count.'</c>
			<t>Temporary Reply : '.$t_count.'</t>
			<q>Further Action Required : '.$q_count.'</q>
			<a>Accepted : '.$a_count.'</a>
			<r>Rejected : '.$r_count.'</r>
			<e>Endorse the Reply : '.$e_count.'</e>
			<fc>Fail Count : '.$fc_count.'</fc>
			<status>S</status>';  
	}
	else {
		/*if ($no_of_rec == 1 && $response != '') {
			echo '<tot>Processed no. of Petition(s): '.$count.'</tot>
			<f>Forwarded : '.$f_count.'</f>
			<d>Delegated : '.$d_count.'</d>
			<n>Unrelated so Returned : '.$n_count.'</n>
			<c>Action Taken : '.$c_count.'</c>
			<t>Temporary Reply : '.$t_count.'</t>
			<q>Further Action Required : '.$q_count.'</q>
			<a>Accepted : '.$a_count.'</a>
			<r>Rejected : '.$r_count.'</r>
			<e>Endorse the Reply : '.$e_count.'</e>
			<fc>Fail Count : '.$fc_count.'</fc>
			<status>S</status>';
		} else {*/
		echo '<msg>Petition(s) process failed!!!</msg>
		<fc>Fail Count : '.$fc_count.'</fc>';
		echo '<status>F</status>';
		//}
	}
	echo "</response>";
    }
  } else {
		echo "<response>";
		echo '<status>L</status>'; 
		echo "</response>";	
  }
 }
}
else if($mode=='p1_get_sub_type'){
	
  $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 
  if($_SESSION['formptoken'] != $form_tocken)
  {
	 header('Location: logout.php');
	 exit;
  }
  else{
		$griev_id = stripQuotes(killChars($_POST["griev_id"]));
	
	    $gre_sub_type="SELECT griev_subtype_id, griev_subtype_name
				FROM lkp_griev_subtype
				WHERE enabling AND griev_type_id=".$griev_id." 
				ORDER BY griev_subtype_name";
			$resultSubType = $db->query($gre_sub_type);
			$rowarraySubType = $resultSubType->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarraySubType as $rowSubType)
			{
				echo $page->generateXMLTag('griev_subtype_id', $rowSubType['griev_subtype_id']);
				echo $page->generateXMLTag('griev_subtype_name',$rowSubType['griev_subtype_name']);
			}
			echo "</response>";
		}
}
else if($mode=='p1_get_dept'){
	
  $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 
  if($_SESSION['formptoken'] != $form_tocken)
  {
	 header('Location: logout.php');
	 exit;
  }
  else{
		$griev_sub_id = stripQuotes(killChars($_POST["griev_sub_id"]));
	
	      $dept="select a.dept_id, a.dept_name, a.dept_tname, b.off_level_pattern_id from vw_usr_dept_griev_subtype a
join usr_dept b on a.dept_id = b.dept_id 
where a.griev_subtype_id = ".$griev_sub_id."";

		 $sql_count = "select count(a.dept_id) from vw_usr_dept_griev_subtype a
			join usr_dept b on a.dept_id = b.dept_id 
			where a.griev_subtype_id = ".$griev_sub_id."";
			
			$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);

			$resultSubType = $db->query($dept);
			$rowarraySubType = $resultSubType->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarraySubType as $rowSubType)
			{
				echo $page->generateXMLTag('dept_id', $rowSubType['dept_id']);
				echo $page->generateXMLTag('dept_name', $rowSubType['dept_name']);
				echo $page->generateXMLTag('count', $count[0]);
			}
			echo "</response>";
		}
}

else if($mode=='p1_get_officer'){
	
  $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 
  if($_SESSION['formptoken'] != $form_tocken)
  {
	 header('Location: logout.php');
	 exit;
  }
  else{
		$district_id=$userProfile->getDistrict_id();
	    $off_level_id=$userProfile->getOff_level_id();
	
	     $griev_sub_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
	//$off_pattern_id=stripQuotes(killChars($_POST["off_pattern_id"]));
	     $off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
	
	
	 $get_pattrn="select a.dept_id, b.off_level_pattern_id from usr_dept_griev_subtype a
join usr_dept b on a.dept_id = b.dept_id 
where a.griev_subtype_id = ".$griev_sub_id."";

			$result_pattrn = $db->query($get_pattrn);
			$rowarray_pattrn = $result_pattrn->fetchall(PDO::FETCH_ASSOC);
			
			foreach($rowarray_pattrn as $row_pattrn)
			{
				   $off_pattern_id = $row_pattrn['off_level_pattern_id'];
				 
			}
			
	$inSql = "
			  select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, row_number() over (order by off_level_dept_id) as rownum, off_loc_id
from vw_usr_dept_users_v_sup
where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
and dept_id in (select wsq.dept_id from usr_dept_griev_subtype wsq where wsq.griev_subtype_id = ".$griev_sub_id.")
and off_level_dept_id=".$userProfile->getOff_level_dept_id()." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_act_ret and dept_user_id!=".$_SESSION['USER_ID_PK']."

union

select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id,row_number() over (order by off_level_dept_id) as rownum, off_loc_id
from vw_usr_dept_users_v_sup
where 
off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
and dept_desig_id=s_dept_desig_id 
and dept_id in (select wsq.dept_id from usr_dept_griev_subtype wsq where wsq.griev_subtype_id = ".$griev_sub_id.") 
and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id().")
and 
(
 case ".$off_pattern_id." -- grievance location's office level pattern
 when 1 then (off_level_pattern_id = 1 and off_loc_id = ".$off_loc_id.") -- for revenue pattern; 3 is the taluk_id from the pet_master record
 when 2 then (off_level_pattern_id = 2 and off_loc_id = ".$off_loc_id.") -- for rural pattern; 3 is the block_id from the pet_master record
 when 3 then (off_level_pattern_id = 3 and off_loc_id = ".$off_loc_id.") -- for urban pattern; 3 is the griev_lb_urban_id from the pet_master record
 when 4 then (off_level_pattern_id = 4 and off_loc_id = ".$off_loc_id.") -- for urban pattern; 3 is the division_id from the pet_master record
 else true
 end
)
and dept_pet_process and off_pet_process and pet_act_ret

union

select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id,row_number() over (order by off_level_dept_id) as rownum, off_loc_id
from vw_usr_dept_users_v_sup
where 
off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."  
and dept_desig_id=s_dept_desig_id 
and dept_id in (select wsq.dept_id from usr_dept_griev_subtype wsq where wsq.griev_subtype_id = ".$griev_sub_id.") 
and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id().")
and 
(
 case ".$off_pattern_id." -- grievance location's office level pattern
 when 1 then (off_level_pattern_id = 1 and off_loc_id = (select rdo_id from mst_p_taluk where taluk_id=".$off_loc_id.") and off_level_dept_id=3) -- for revenue pattern; 3 is the taluk_id from the pet_master record
 when 2 then (off_level_pattern_id = 2 and off_loc_id = ".$off_loc_id.") -- for rural pattern; 3 is the block_id from the pet_master record
 when 3 then (off_level_pattern_id = 3 and off_loc_id = ".$off_loc_id.") -- for urban pattern; 3 is the griev_lb_urban_id from the pet_master record
 when 4 then (off_level_pattern_id = 4 and off_loc_id = ".$off_loc_id.") -- for urban pattern; 3 is the division_id from the pet_master record
 else true
 end
)
and dept_pet_process and off_pet_process and pet_act_ret ) off_design ORDER BY off_level_id"; 				
//echo inSql;
	  $query = 'select * from ('.$inSql .'
				) off_level
				WHERE off_level.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). 'and off_level.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
  $sql_count = 'SELECT COUNT(dept_user_id) FROM ('.$inSql .'
				) off_level';
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
//if($count==1){
 
	echo "<response>";
			foreach($rowarray as $row)
			{
				echo $page->generateXMLTag('dept_user_id', $row['dept_user_id']);
				echo $page->generateXMLTag('dept_desig_name', $row['dept_desig_name']);
				echo $page->generateXMLTag('dept_desig_id',$rowSubType['dept_desig_id']);
			}
			echo "</response>";
//}
}
}
?>
