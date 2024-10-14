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
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
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
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		$petTypeIdCond = " and pet_type_id != 4 ";
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
if($mode=='p4_search'){
	
	 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 
	if($_SESSION['formptoken'] != $form_tocken)
	  {
		   header('Location: logout.php');
		   exit;
		     
	  }
	 else{
		$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
		$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
		$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
		$p_source=stripQuotes(killChars($_POST["p_source"]));
		/* $dept=stripQuotes(killChars($_POST["dept"])); */
		$gtype=stripQuotes(killChars($_POST["gtype"]));
		$ptype=stripQuotes(killChars($_POST["ptype"]));
/*------------------------------- For Validate the date format------------------------------------------------  */
 
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
	
	$codn="";
	if(!empty($p_from_pet_date)){
		$codn.=" AND a.petition_date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.=" AND a.petition_date <= '".$p_to_pet_date."'::date";
	}
	if(!empty($p_petition_no)){
		$codn.= " AND a.petition_no  LIKE '%".$p_petition_no."%'";
	}
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
	}
	
	/* if(!empty($dept)){
			$codn.= "  AND a.dept_id=".$dept;		
	} */
	if(!empty($gtype)){
		$codn.= " AND a.griev_type_id=".$gtype;		
	}
		
	if(!empty($ptype)){
		$codn.= " AND a.pet_type_id=".$ptype;		
	}	
	if($userProfile->getDesig_roleid() == 5){
		$codn1=" AND pet_type_id!=4";
		$countcond1= " AND pet_type_id!=4";
	}else{
		$codn1="";
	}
		
//fn_pet_origin_from_myself(p_petition_id integer, p_to_whom integer)
	
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		$petTypeIdCond = " and pet_type_id != 4 ";
		
	$inSql="SELECT a.pet_action_id,a.action_remarks fwd_remarks,a.action_entby,a.to_whom,a.action_type_code,a.action_type_name,
	TO_CHAR(a.action_entdt,'dd/mm/yyyy') as fwd_date,
	a.petition_id, a.petition_no, --TO_CHAR(a.petition_date,'dd/mm/yyyy')as petition_date,
	to_char(a.pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date,a.source_id,
	a.source_name, a.griev_type_id, a.griev_type_name, a.griev_subtype_id, a.griev_subtype_name, a.grievance,
	a.petitioner_name||', '||coalesce(a.comm_rev_village_name,'')||', '||coalesce(a.comm_taluk_name,'')||', '||coalesce(a.comm_district_name,'')||', '||a.comm_state_name||', '||a.comm_country_name AS pet_address,
	TO_CHAR(a.file_date,'dd/mm/yyyy')as file_date,a.file_no,
	CASE WHEN a.griev_taluk_id IS NOT NULL THEN a.griev_rev_village_name||', '||a.griev_taluk_name
	WHEN a.griev_block_id IS NOT NULL THEN a.griev_lb_village_name||', '||a.griev_block_name
	WHEN a.griev_lb_urban_id IS NOT NULL THEN a.griev_lb_urban_name
	WHEN griev_division_id IS NOT NULL THEN griev_division_name,
	END || ', '||a.griev_district_name AS gri_address, off_loc_id,pet_dept_id as dept_id, a.griev_district_id,
	CASE WHEN fn_pet_origin_from_our_office(a.petition_id,".$dept_user_id.") THEN 'OWN' ELSE 'SUP' END AS pet_office,
	CASE WHEN fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") THEN 'SELF' ELSE 'RECD' END AS pet_owner,
	pet_loc_id,off_level_id, dept_off_level_pattern_id,off_level_dept_id,
	row_number() over (order by a.pet_action_id) as rownum
	FROM vw_pet_actions a
	WHERE a.action_type_code='T' AND a.action_entby=".$_SESSION['USER_ID_PK'].$codn."";					
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
	$inSql="SELECT a.pet_action_id,a.action_remarks fwd_remarks,a.action_entby,a.to_whom,
	a.action_type_code,a.action_type_name, TO_CHAR(a.action_entdt,'dd/mm/yyyy') as fwd_date, 
	a.petition_id, a.petition_no, to_char(a.pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date,
	a.source_id, a.source_name, a.griev_type_id, a.griev_type_name, 
	a.griev_subtype_id, a.griev_subtype_name, a.grievance, 
	a.petitioner_name||', '|| a.comm_doorno||', '||a.comm_street ||', '||a.comm_area||', Pincode: '||a.comm_pincode AS pet_address, TO_CHAR(a.file_date,'dd/mm/yyyy')as file_date,a.file_no,comm_mobile,pet_type_name,
	( 
	(CASE WHEN a.off_level_dept_id IS NOT NULL THEN a.office_name END) || ': '||
	(CASE WHEN a.zone_id IS NOT NULL THEN a.griev_zone_name 
	WHEN a.range_id IS NOT NULL THEN a.griev_range_name
	WHEN a.griev_district_id IS NOT NULL THEN a.griev_district_name
	WHEN griev_division_id IS NOT NULL THEN griev_division_name 
	WHEN griev_subdivision_id IS NOT NULL THEN griev_subdivision_name 
	WHEN griev_circle_id IS NOT NULL THEN griev_circle_name END 
	)) as  gri_address, 
	off_loc_id,pet_dept_id as dept_id, a.griev_district_id, 
	CASE WHEN fn_pet_origin_from_our_office(a.petition_id,".$dept_user_id.") THEN 'OWN' ELSE 'SUP' END AS pet_office, 
	CASE WHEN fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") THEN 'SELF' ELSE 'RECD' END AS pet_owner, 
	pet_loc_id,off_level_id, dept_off_level_pattern_id,off_level_dept_id,f_action_remarks, 
	row_number() over (order by a.pet_action_id) as rownum 
	FROM vw_pet_actions a 
	LEFT JOIN pet_action_first_last b on b.petition_id=a.petition_id 
	WHERE a.action_type_code='T' AND a.action_entby=".$dept_user_id.$codn.$petTypeIdCond."".$codn1;	
	
     $query = 'select * from (
			'.$inSql.'
		)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). 'and petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('first_action_remarks', $row['f_action_remarks']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		echo $page->generateXMLTag('pet_office', $row['pet_office']);
		echo $page->generateXMLTag('pet_owner', $row['pet_owner']);
		
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
		
		echo $page->generateXMLTag('source_id', $row['source_id']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);
		echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']);
		
		echo $page->generateXMLTag('off_loc_id', $row['off_loc_id']);
		echo $page->generateXMLTag('dept_id', $row['dept_id']);
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		echo $page->generateXMLTag('file_no', $row['file_no']);
		echo $page->generateXMLTag('file_date', $row['file_date']);
		
		echo $page->generateXMLTag('pet_loc_id', $row['pet_loc_id']);
		echo $page->generateXMLTag('off_level_id', $row['off_level_id']);
		echo $page->generateXMLTag('dept_off_level_pattern_id', $row['dept_off_level_pattern_id']);
		echo $page->generateXMLTag('off_level_dept_id', $row['off_level_dept_id']);
		
		$actTypeCode="";  //$row[petition_id]
		$sql_la = "select * from fn_pet_action_specific_one(".$row['petition_id'].",1)"; 
		$result_la = $db->query($sql_la);
		$rowarray_la = $result_la->fetchall(PDO::FETCH_ASSOC);
		$last_action = "";
		foreach($rowarray_la as $row_la)
		{
			$last_action = 	$row_la['action_type_code'];
			 
		}
		//echo "====================".$userProfile->getPet_forward();
		//down - F, Q  up - N, C, E  temp - T  disp - A, R
		if($row['pet_owner']=="SELF"){ //Own office petition
		if($userProfile->getOff_level_id()!=46){
			$actTypeCode .= "'A', 'R', 'T'";
			if($userProfile->getPet_forward() && $_SESSION['LOGIN_LVL']==NON_BOTTOM){
			$actTypeCode .= ",'F'";
			if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
				$actTypeCode .= ", 'Q'";
				}				
			}
		}else{
			$actTypeCode .= "'R', 'I', 'S'";
		}
		}
		else {
			$actTypeCode .= "'T', 'C', 'N','I','S'";
			if($userProfile->getPet_forward() && $_SESSION['LOGIN_LVL']==NON_BOTTOM){
				$actTypeCode .= ",'F'";
				if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
					$actTypeCode .= ", 'Q', 'E'";
				}
			}
		}
	
		if($actTypeCode!=""){
			$query = "SELECT distinct action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.") order by action_type_name";
			$result = $db->query($query);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarray as $row1)
			{
				
				echo $page->generateXMLTag('acttype_code_'.$row['petition_id'], $row1['action_type_code']);
				echo $page->generateXMLTag('acttype_desc_'.$row['petition_id'], $row1['action_type_name']);
			}
		} 
	}
if ($userProfile->getDesig_roleid() == 5) {
		$sql_count = "SELECT count(*) FROM pet_action_first_last a 	WHERE a.l_action_type_code='T' AND a.l_action_entby=".$dept_user_id.$codn.$countcodn1;
}else{
	$sql_count = "SELECT count(*) FROM pet_action_first_last a 	WHERE a.l_action_type_code='T' AND a.l_action_entby=".$_SESSION['USER_ID_PK'].$codn;
}

	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation 
	
	echo "</response>";
 }
}
//Getting Office location and user_id based on Action Type Code
else if($mode=='p4_act_type'){
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));

	if($_SESSION['formptoken'] != $form_tocken)
	{
		header('Location: logout.php');
		exit; 
	}
	else
	{
		echo "<response>";
		$griev_sub_type_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
		$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));		
		$pet_id=stripQuotes(killChars($_POST["p4_petition_id"])); 
		$dept_id=stripQuotes(killChars($_POST["dept_id"]));
		$p4_griev_district_id=stripQuotes(killChars($_POST["p4_griev_district_id"])); 	
		//echo ">>>>>>>>>>>>>>>>".$pet_id;
		$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
		$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
		$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
		$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
		$pet_owner=stripQuotes(killChars($_POST["pet_owner"]));
		
		$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$pet_id."";
		$res=$db->query($disp_officer_sql);
		$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowArrDisp as $rowDisp) {
			$fwd_officer = $rowDisp['l_action_entby'];
		}
	if($_POST["p4_act_type_code"]=='F')//Forward to lower office
	{
		//echo "1111111111111111111111111111111111";
		
		if ($disp_officer == '') {
			$disp_officer = $userProfile->getDept_user_id();
		}
//Sql stetement from petition entry screen
/*
$sql="select off_hier from vw_usr_dept_users_v_sup 
where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
and dept_desig_role_id=3 ".$condition." order by dept_user_id limit 1";
*/
		$up_off_level_id=$userProfile->getOff_level_id();
		$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
		$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
		$up_dept_id=$userProfile->getDept_id();
		$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
		
		if ($up_dept_off_level_pattern_id == ''){
			$up_dept_off_level_pattern_id='null';
		}	
		if ($up_dept_off_level_pattern_id == 'null'){
			$condition = " ";	 
		} else {					
			$condition = " and (dept_off_level_pattern_id=".$up_dept_off_level_pattern_id.")";	
		}
		if($up_off_level_id==11){
			$condition.=" and off_hier[9]=".$userProfile->getZone_id()." ";
		}
		//echo "#########################".$off_level_id."$$$$$$$$$$$$$$$$$$$".$pet_loc_id;
		if ($off_level_id == null) {
			//echo "#######################";
			$up_off_level_id=$userProfile->getOff_level_id();
			$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
			$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
			$up_dept_id=$userProfile->getDept_id();
			$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
			$up_off_level_dept_id=$userProfile->getOff_level_dept_id();

			/* echo $sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal";
			$rs=$db->query($sql);
			if(!$rs)
			{
			print_r($db->errorInfo());
			exit;
			}
			while($row = $rs->fetch(PDO::FETCH_BOTH))
			{
			$disposing_officer_id = $row["dept_user_id"];
			}
			if ($disposing_officer_id != null || $disposing_officer_id != '') {
			$disposal_officer_cond = " and dept_user_id !=".$disposing_officer_id."";
			}
			*/


			$sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and off_level_dept_id=".$userProfile->getOff_level_dept_id()." and pet_disposal".$condition."";
			$rs=$db->query($sql);
			if(!$rs)
			{
				print_r($db->errorInfo());
				exit;
			}
			while($row = $rs->fetch(PDO::FETCH_BOTH))
			{
				$disposing_officer_id = $row["dept_user_id"];
			}
			if ($disposing_officer_id != null || $disposing_officer_id != '') {
				$disposal_officer_cond = " and dept_user_id !=".$disposing_officer_id."";
			}
			
			$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
			from vw_usr_dept_users_v_sup a1
			where dept_id=".$up_dept_id.$condition." 
			and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." 
			and COALESCE(enabling,true) and off_hier[".$up_off_level_id."]=".$userProfile->getOff_loc_id()."
			and dept_user_id!=".$userProfile->getDept_user_id().$disposal_officer_cond."
			order by off_level_id,off_level_dept_id,dept_desig_id";
		} else {
			$sql="select off_hier from vw_usr_dept_users_v_sup 
			where dept_id=".$dept_id." and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()." and 
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

			if ($userProfile->getDesig_roleid() == 3) {
				$processing_offcr_cond=' and off_level_id !='.$userProfile->getOff_level_id();
			} 
			//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
			if ($off_level_id<=$userProfile->getOff_level_id()) {
				$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
				a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
				from vw_usr_dept_users_v_sup a1
				where dept_id=".$dept_id.$condition." 
				and dept_desig_role_id in (2,3) 
				and off_level_id>=".$userProfile->getOff_level_id()." 
				and off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."
				and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id!=".$fwd_officer."
				and dept_user_id not in 
				(
				select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
				".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
				from pet_action_first_last where petition_id=".$pet_id."
				) order by dept_user_id";
			} else {	
				$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
				a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
				from vw_usr_dept_users_v_sup a1
				where dept_id=".$dept_id.$condition." 
				and dept_desig_role_id in (2,3)
				and (off_level_id>=".$userProfile->getOff_level_id()." and off_level_id<=".$off_level_id.")
				and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
				and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id!=".$fwd_officer."
				and dept_user_id not in 
				(
				select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
				".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
				from pet_action_first_last where petition_id=".$pet_id."
				) order by dept_user_id";
			}
		}	 	
		
			
	}
	else if($_POST["p4_act_type_code"]=='C' || $_POST["p4_act_type_code"]=='N'|| $_POST["p4_act_type_code"]=='I'|| $_POST["p4_act_type_code"]=='S')
	{ 

		 $p4_petition_id=stripQuotes(killChars($_POST["p4_petition_id"]));

		$sql_la = "select * from fn_pet_action_specific_one(".$p4_petition_id.",1)"; 
		
		$result_la = $db->query($sql_la);
		$rowarray_la = $result_la->fetchall(PDO::FETCH_ASSOC);
		$last_action = "";
		$last_action_entby = "";
		foreach($rowarray_la as $row_la)
		{
			$last_action = 	$row_la['action_type_code'];
			$last_action_entby =  $row_la['action_entby'];
		}
		
		if ($last_action == 'F' || $last_action == 'Q') {
			$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
			from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$last_action_entby."";
		} else if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
			$query="SELECT dept_user_id, off_loc_name||'/ '||dept_desig_name AS off_location_design, off_loc_tname ||'/ '||dept_desig_tname AS off_location_tdesign 
			FROM vw_usr_dept_users_desig WHERE dept_user_id in

			(SELECT aa.action_entby FROM
			(SELECT petition_id, pet_action_id, action_type_code, action_entby, to_whom, action_entdt,
			cast (rank() OVER (PARTITION BY petition_id, to_whom ORDER BY pet_action_id DESC)as integer) rnk
			FROM pet_action where petition_id=".$p4_petition_id." and action_type_code in ('F','Q') and to_whom=".$dept_user_id.") aa
			WHERE aa.rnk=1)";
		}
		
	}
	else if($_POST["p4_act_type_code"]=='E')
	{
		 $p4_petition_id=stripQuotes(killChars($_POST["p4_petition_id"]));
		//chk petition owner office location 
		
		//GDP local petition
		$chkPetOwnQuery= "SELECT fn_pet_origin_same_from_office(".$p4_petition_id.",".$_SESSION['USER_ID_PK'].")";
			
		$result = $db->query($chkPetOwnQuery);
		$rowArr = $result->fetchall(PDO::FETCH_ASSOC);
		$pet_owner='';
		foreach($rowArr as $row){
			$pet_owner=$row[fn_pet_origin_same_from_office];
		}
		
		$codn = '';
		if($pet_owner){//i.e., if the origin (office) of the petition is same as the last action-on-petition-taken-office
			$codn = " and a1.pet_disposal";
		}
				
		$sql_la = "select * from fn_pet_action_specific_one(".$p4_petition_id.",1)"; 
		$result_la = $db->query($sql_la);
		$rowarray_la = $result_la->fetchall(PDO::FETCH_ASSOC);
		$last_action = "";
		$last_action_entby = "";
		foreach($rowarray_la as $row_la)
		{
			$last_action = 	$row_la['action_type_code'];
			$last_action_entby =  $row_la['action_entby'];
		}
				
		if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
			$query="SELECT dept_user_id, off_loc_name||'/ '||dept_desig_name AS off_location_design, off_loc_tname ||'/ '||dept_desig_tname AS off_location_tdesign 
			FROM vw_usr_dept_users_desig WHERE dept_user_id in

			(SELECT aa.action_entby FROM
			(SELECT petition_id, pet_action_id, action_type_code, action_entby, to_whom, action_entdt,
			cast (rank() OVER (PARTITION BY petition_id, to_whom ORDER BY pet_action_id DESC)as integer) rnk
			FROM pet_action where petition_id=".$p4_petition_id." and action_type_code in ('F','Q') and to_whom=".$_SESSION['USER_ID_PK'].") aa
			WHERE aa.rnk=1)";
		} 
	}
	else if($_POST["p4_act_type_code"]=='Q')
	{
		 $p4_petition_id=stripQuotes(killChars($_POST["p4_petition_id"]));
		//chk petition owner office location 
		
		//GDP local petition
		$chkPetOwnQuery= "SELECT fn_pet_origin_same_from_office(".$p4_petition_id.",".$_SESSION['USER_ID_PK'].")";
			
		$result = $db->query($chkPetOwnQuery);
	 	$rowArr = $result->fetchall(PDO::FETCH_ASSOC);
		$pet_owner='';
		foreach($rowArr as $row){
			$pet_owner=$row['fn_pet_origin_same_from_office'];
		}
		
		$codn = '';
		if($pet_owner){//i.e., if the origin (office) of the petition is same as the last action-on-petition-taken-office
			$codn = " and a1.pet_disposal";
		}
				
		$sql_la = "select * from fn_pet_action_specific_one(".$p4_petition_id.",1)"; 
		$result_la = $db->query($sql_la);
		$rowarray_la = $result_la->fetchall(PDO::FETCH_ASSOC);
		$last_action = "";
		$last_action_entby = "";
		foreach($rowarray_la as $row_la)
		{
			$last_action = 	$row_la['action_type_code'];
			$last_action_entby =  $row_la['action_entby'];
		}
				
		if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
			$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
			from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$last_action_entby."";
		} 
	}
	if($userProfile->getOff_level_id()==46){ //echo "aaaaa2";exit;
	if($pet_owner=='SELF'){
	$query="SELECT dept_user_id, off_loc_name||'/ '||dept_desig_name AS off_location_design, off_loc_tname ||'/ '||dept_desig_tname AS off_location_tdesign 
			FROM vw_usr_dept_users_desig WHERE dept_user_id in

			(SELECT aa.action_entby FROM
			(SELECT petition_id, pet_action_id, action_type_code, action_entby, to_whom, action_entdt,
			cast (rank() OVER (PARTITION BY petition_id, to_whom ORDER BY pet_action_id DESC)as integer) rnk
			FROM pet_action where petition_id=".$p4_petition_id.") aa
			WHERE aa.rnk=1)";
	
		}else{
			$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
			from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$last_action_entby."";
			}
	}
	//echo $query;
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
//Update temporary tab records updations
else if($mode=='p4_fwd_reply_temp_save'){
	//getting array of petition action sno from client
  $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
   $ip=$_SERVER['REMOTE_ADDR']; 	 
if($_SESSION['formptoken'] != $form_tocken)
	  {
		   header('Location: logout.php');
		   exit;
		     
	  }
	 else{	
	$petActSnoArr = stripQuotes(killChars($_POST["p4_pet_act_sno"]));
	$petSnoArr = stripQuotes(killChars($_POST["p4_pet_sno"]));
	$actTypeArr = stripQuotes(killChars($_POST["p4_act_type_code"]));
	$fwdUrReplyArr = stripQuotes(killChars($_POST["p4_fwd_ur_reply"]));
 	$file_noArr = stripQuotes(killChars($_POST["p4_file_no"]));
	$file_dateArr = stripQuotes(killChars($_POST["p4_file_date"]));
	$remarkArr = stripQuotes(killChars($_POST["p4_remark"]));
	$pet_owner = stripQuotes(killChars($_POST["pet_owner"]));
	
		$firCsrArr = stripQuotes(killChars($_POST["p4_fir_no"]));
		$p4_fir_year = stripQuotes(killChars($_POST["p4_fir_year"]));
		$firCsrcircle = stripQuotes(killChars($_POST["p4_fir_circle"]));
		$firCsrdist = stripQuotes(killChars($_POST["p4_fir_csr_dist"]));
	//print_r($_POST);exit;
	if ($userProfile->getDesig_roleid() == 5) {
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	$action_ent=$dept_user_id;
	
	//update to action entered date, action type code, action remarks and to whom user ID
	if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 
	|| $userProfile->getOff_level_id()==11 || $userProfile->getOff_level_id()==13 
	|| $userProfile->getOff_level_id()==42 || $userProfile->getOff_level_id()==44
	|| $userProfile->getOff_level_id()==46 ) {
	
	$queryUpdate = $db->prepare('UPDATE pet_action SET action_entdt=?, action_type_code=? , file_no=?, file_date=?, action_remarks=?, to_whom=?,action_ip_address=? WHERE pet_action_id=?');
	
	$queryUpdate = $db->prepare('UPDATE pet_action SET action_entdt=current_timestamp, action_type_code=? , file_no=?, file_date=?, action_remarks=?, to_whom=?,action_ip_address=?,action_entby=?,data_entby=? WHERE pet_action_id=?');
//if MIDDLE level officer then F, N, C, T action types else BOTTOM level officer N, C, T action types
	$i=0;$f_count=0;$n_count=0;$c_count=0;$i_count=0;$s_count=0;$t_count=0;$q_count=0;$a_count=0;$r_count=0;$e_count=0;$fc_count=0;$zz=0;
	$pet_id_array = array();
	$pet_act_array = array();
	$pet_mob_array = array();
	$pet_no_array = array();
	
		//print_r($_POST);
	foreach($petSnoArr as $petSno) {
		$today = $page->currentTimeStamp();
		$f_dt=explode('/',$file_dateArr[$i]);
		$day=$f_dt[0];
		$mnth=$f_dt[1];
		$yr=$f_dt[2];
    	$file_date=$yr.'-'.$day.'-'.$mnth;
		$actTypeArr_code='';
		
		if($userProfile->getOff_level_id()==46 && $pet_owner[$i]=='SELF'){
			switch($actTypeArr[$i]){
				case 'C':
					$actTypeArr_code='R';
					break;
				case 'I':
					$actTypeArr_code='A';
					break;
				case 'S':
					$actTypeArr_code='A';
					break;
				default :
					$actTypeArr_code=$actTypeArr[$i];
					break;
			}
		}else{
			$actTypeArr_code=$actTypeArr[$i];
		}
		//echo $i.".".$actTypeArr_code;
		$array = array($actTypeArr_code, ($file_noArr[$i])? $file_noArr[$i] : NULL , 
		($file_dateArr[$i])?$file_date: NULL,  ($remarkArr[$i])? $remarkArr[$i] : NULL, ( empty($fwdUrReplyArr[$i])?NULL:$fwdUrReplyArr[$i]),$ip,$action_ent,$_SESSION['USER_ID_PK'],$petActSnoArr[$i]);
		//print_r($array);
		// exit;
		if($queryUpdate->execute($array)>0){
			 
			$count++;//no. of petition processed.
			switch($actTypeArr[$i]){
				case 'F':
					$f_count++;
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
				case 'I':
					$i_count++;
				$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=1";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",1,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p4_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=1,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i]." ,fir_csr_year='".$p4_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=1";
					$result = $db->query($sql);
				}
				
					break;
				case 'S':
					$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=2";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",2,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p4_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=2,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i].", fir_csr_year='".$p4_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=2";
					$result = $db->query($sql);
				}
					$s_count++;
					break;
					
					$s_count++;
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
		} else {
			$fc_count++;
		}
		$i++;
	}
	$string = rtrim(implode(',', $pet_id_array), ',');
	
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
				   		$message = "Your Petition No. ".$pet_no_array[$val]." is accepted. URL to check the status: https://gdp.tn.gov.in/";
					} else if ($pet_act_array[$val] == 'R') {
						$message = "Your Petition No. ".$pet_no_array[$val]." is rejected. URL to check the status: https://gdp.tn.gov.in/";
					}	
				} else {
					if ($pet_act_array[$val] == 'A') {
				   		$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." ஏற்றுக் கொள்ளப்பட்டது. தங்கள் மனுவின் நிலையை "." https://gdp.tn.gov.in/"." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
					} else if ($pet_act_array[$val] == 'R') {
						$message = "தங்களுடைய மனு எண் ".$pet_no_array[$val]." நிராகரிக்கப்பட்டது. தங்கள் மனுவின் நிலையை "." https://gdp.tn.gov.in/"." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
					}
					
				}
				$mobile_no = $pet_mob_array[$val];
				$email = $pet_email_array[$val];
				$source_id = $source_id_array[$val];
				
				if ($source_id == -3) {
					//echo "111111111111111111111111111111111";
					//smtpmailer($email, $from, $name, $subj, $message);
				} else {
					if ($mobile_no!="" && ((int) substr($mobile_no, 0, 1) >=6 && (int) substr($mobile_no, 0, 1) <=9)) {
						 //SMS($mobile_no,$message,'0');
					}
				}
				//echo $mobile_no;
				/*if ($mobile_no != "") {
							$mdata = array(
				
							"username" => "tnrevadmin-gdp",
						  
							"password" => "tngdP@2281",	
							
							 
							 "senderid" =>"TNREVE",
						  
							 "smsservicetype" =>"singlemsg", 
						  
							 "mobileno" => $mobile_no,	 
						  
							 "bulkmobno" => "",
						  
							 "content" => $message
					   
					  );
					  //print_r($mdata);
					  post_to_url('http://msdgweb.mgov.gov.in/esms/sendsmsrequest', $mdata); 
				}	*/	
			
			
			
			}
	  }
	  
	echo "<response>";
	if($count>0){
		echo '<tot>Processed no. of Petition(s): '.$count.'</tot>
				<f>Forwarded : '.$f_count.'</f>
				<n>Unrelated so Returned : '.$n_count.'</n>
				<c>Action Taken : '.$c_count.'</c>
				<ir>Action Taken with FIR : '.$i_count.'</ir>
				<s>Action Taken with CSR : '.$s_count.'</s>
				<t>Temporary Reply : '.$t_count.'</t>
				<e>Endorsed Reply : '.$e_count.'</e>
				<a>Accepted : '.$a_count.'</a>
				<r>Rejected : '.$r_count.'</r>
				<fc>Fail Count : '.$fc_count.'</fc>';
				
		echo '<status>S</status>';
	}
	else{
		echo '<msg>Petition(s) process failed!!!</msg>
			  <fc>Fail Count : '.$fc_count.'</fc>';
		echo '<status>F</status>';
	}
	echo "</response>";
	}
}
}
?>
