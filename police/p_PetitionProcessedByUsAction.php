<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
//include("funSMS.php");
include("sms_airtel_code.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$mode=$_POST["mode"];

if($mode=='p_search'){
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken) {
		header('Location: logout.php');
		exit;
	} else {		  
		$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
		$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
		$pfrompetactdate=stripQuotes(killChars($_REQUEST["p_from_pet_act_date"]));
		$ptopetactdate=stripQuotes(killChars($_REQUEST["p_to_pet_act_date"]));
		$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
		$p_source=stripQuotes(killChars($_POST["p_source"]));
		$gtype=stripQuotes(killChars($_POST["gtype"]));
		$ptype=stripQuotes(killChars($_POST["ptype"]));
		$pet_action=stripQuotes(killChars($_POST["pet_action"]));
/* 		$dept=stripQuotes(killChars($_POST["dept"]));
		$pet_community=stripQuotes(killChars($_POST["pet_community"])); */		
		//$special_category=stripQuotes(killChars($_POST["special_category"]));
/*------------------------------- For Validate the date format------------------------------------------------  */		
	 	$dt1=explode('/',$pfrompetdate);
		$day=$dt1[0];
		$mnth=$dt1[1];
		$yr=$dt1[2];
    	$p_frompetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetdate)) { //$date_regex declared in common_date_fun.php
			$p_from_pet_date = '';
		} else {
			$p_from_pet_date = "$p_frompetdate";     
		}

		$dt2=explode('/',$ptopetdate);
		$day=$dt2[0];
		$mnth=$dt2[1];
		$yr=$dt2[2];
    	$p_topetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_date = '';
		} else {
			$p_to_pet_date = "$p_topetdate";     
		}
	  
	/*---------------------------------------------------------------*/
		$dt3=explode('/',$pfrompetactdate);
		$day=$dt3[0];
		$mnth=$dt3[1];
		$yr=$dt3[2];
    	$p_frompetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_from_pet_act_date = '';
		} else {
			$p_from_pet_act_date = "$p_frompetactdate";     
		}

		$dt4=explode('/',$ptopetactdate);
		$day=$dt4[0];
		$mnth=$dt4[1];
		$yr=$dt4[2];
    	$p_topetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_act_date = '';
		} else {
			$p_to_pet_act_date = "$p_topetactdate";     
		}
/*------------------------------- End of Validate the date format  ------------------------------------------------  */
	
	if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		//$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		$pet_type_codition = " and pet_type_id!=4 ";
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	
	$codn=" where b.action_entby=".$dept_user_id;
	$codn_cnt=" where a.action_entby=".$dept_user_id;
	
	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
	}
	if(!empty($p_from_pet_act_date)){
		$codn.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
	}
	if(!empty($p_to_pet_act_date)){
		$codn.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
	}
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
/* 	if(!empty($dept)){
		$codn.= " AND a.dept_id=".$dept;
		$codn_cnt.= " AND a.dept_id=".$dept;
	}
	 */
	if(!empty($gtype)){
		$codn.= " AND a.griev_type_id=".$gtype;
		$codn_cnt.= " AND a.griev_type_id=".$gtype;
	}
	if(!empty($ptype)){
		$codn.= " AND a.pet_type_id=".$ptype;
		$codn_cnt.= " AND a.pet_type_id=".$ptype;
	}
	
/* 	if(!empty($pet_community)){
		$codn.= " AND a.pet_community_id=".$pet_community;
		$codn_cnt.= " AND a.pet_community_id=".$pet_community;
	}
	
	if(!empty($special_category)){
		$codn.= " AND a.petitioner_category_id=".$special_category;
		$codn_cnt.= " AND a.petitioner_category_id=".$special_category;
	} */
	if(!empty($pet_action)){
		$pet_action_cond= " where action_type_code='".$pet_action."'";
		$codn_cnt.= " AND action_type_code='".$pet_action."'";
	}

	$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
	action_type_code,to_whom,pet_type_name, comm_mobile
	from fn_petition_details(array(select a.petition_id from pet_master a 
	inner join  pet_action b on b.petition_id=a.petition_id".$codn.$pet_type_codition."))".$pet_action_cond."";	

	
	$query = 'select * from (SELECT aaa.*, row_number() OVER()as rownum FROM(
	select * from (
	'.$inSql.'
	) aa ORDER BY aa.petition_id)aaa)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{										
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('subsource_name', $row['subsource_name']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);	
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);			
		echo $page->generateXMLTag('mobile', $row['comm_mobile']);			
	}
	
	$sql_count = "SELECT count(*) FROM
	(
	SELECT a.pet_action_id, a.petition_id, 
	cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
	FROM vw_pet_actions a
	".$codn_cnt.$pet_type_codition.") 
	aa
	WHERE aa.rnk=1";

	$sql_count = 'select count(*) from ('.$inSql.') aa';					
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	echo "</response>";
  }
} else if ($mode=='p_search_feedback') { 
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken) {
		header('Location: logout.php');
		exit;
	} else {
		$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
		$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
		$petition_no=stripQuotes(killChars($_POST["petition_no"]));
		$p_source=stripQuotes(killChars($_POST["p_source"]));
		$gtype=stripQuotes(killChars($_POST["gtype"]));
		$ptype=stripQuotes(killChars($_POST["ptype"]));
		$office_type=stripQuotes(killChars($_POST["office_type"]));
		$pattern_p=stripQuotes(killChars($_POST["pattern_p"]));
		$off_level_p=stripQuotes(killChars($_POST["off_level_p"]));
		$office_p=stripQuotes(killChars($_POST["office_p"]));
		$off_lvl_exp=explode('-',$off_level_p);
		$off_levelp=$off_lvl_exp[0];
		$off_level_dept_id_p=$off_lvl_exp[1];
		//print_r($_POST);
/*------------------------------- For Validate the date format------------------------------------------------  */		
	 	$dt1=explode('/',$pfrompetdate);
		$day=$dt1[0];
		$mnth=$dt1[1];
		$yr=$dt1[2];
    	$p_frompetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetdate)) { //$date_regex declared in common_date_fun.php
			$p_from_pet_date = '';
		} else {
			$p_from_pet_date = "$p_frompetdate";     
		}

		$dt2=explode('/',$ptopetdate);
		$day=$dt2[0];
		$mnth=$dt2[1];
		$yr=$dt2[2];
    	$p_topetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_date = '';
		} else {
			$p_to_pet_date = "$p_topetdate";     
		}
		
		$dt2=explode('/',$ptopetdate);
		$day=$dt2[0];
		$mnth=$dt2[1];
		$yr=$dt2[2];
    	$p_topetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_date = '';
		} else {
			$p_to_pet_date = "$p_topetdate";     
		}
	  
	/*---------------------------------------------------------------*/
		$dt3=explode('/',$pfrompetactdate);
		$day=$dt3[0];
		$mnth=$dt3[1];
		$yr=$dt3[2];
    	$p_frompetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_from_pet_act_date = '';
		} else {
			$p_from_pet_act_date = "$p_frompetactdate";     
		}

		$dt4=explode('/',$ptopetactdate);
		$day=$dt4[0];
		$mnth=$dt4[1];
		$yr=$dt4[2];
    	$p_topetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_act_date = '';
		} else {
			$p_to_pet_act_date = "$p_topetactdate";     
		}
/*------------------------------- End of Validate the date format  ------------------------------------------------  */
	
	if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		//$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		$pet_type_codition = " and pet_type_id!=4 ";
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	
	//$codn=" where b.action_entby=".$dept_user_id;
	//$codn=" where act.action_entby=".$dept_user_id;
	$codn_cnt=" where a.action_entby=".$dept_user_id;
	
	if(!empty($p_from_pet_date)){
		$codn=  " where a.petition_date::date >= '".$p_from_pet_date."'::date";
		//$codn.=  " AND vw.petition_date::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
		//$codn.= " AND vw.petition_date::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
	}
	if(!empty($p_from_pet_act_date)){
		$codn.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
		//$codn.= " AND act.action_entdt::date >= '".$p_from_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
	}
	if(!empty($p_to_pet_act_date)){
		$codn.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
		//$codn.= " AND act.action_entdt::date <= '".$p_to_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
	}
	if(!empty($p_source)){
		//$codn.= " AND a.source_id=".$p_source;
		$codn.= " AND vw.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
/* 	if(!empty($dept)){
		$codn.= " AND a.dept_id=".$dept;
		$codn_cnt.= " AND a.dept_id=".$dept;
	}
	 */
	if(!empty($gtype)){
		//$codn.= " AND a.griev_type_id=".$gtype;
		$codn.= " AND vw.griev_type_id=".$gtype;
		$codn_cnt.= " AND a.griev_type_id=".$gtype;
	}
	if(!empty($ptype)){
		//$codn.= " AND a.pet_type_id=".$ptype;
		$codn.= " AND vw.pet_type_id=".$ptype;
		$codn_cnt.= " AND a.pet_type_id=".$ptype;
	}
	
/* 	if(!empty($pet_community)){
		$codn.= " AND a.pet_community_id=".$pet_community;
		$codn_cnt.= " AND a.pet_community_id=".$pet_community;
	}
	
	if(!empty($special_category)){
		$codn.= " AND a.petitioner_category_id=".$special_category;
		$codn_cnt.= " AND a.petitioner_category_id=".$special_category;
	} */
	if(!empty($pet_action)){
		$pet_action_cond= " where action_type_code='".$pet_action."'";
		$codn_cnt.= " AND action_type_code='".$pet_action."'";
	}
	if($userProfile->getDept_off_level_pattern_id()!=''){
		//$off_loc_col=",fn_off_loc_hierarchy(1,".$userProfile->getDept_off_level_pattern_id().",usr.off_level_id,COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_hier";
		
		$off_loc_col=",COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_id";
	}else{
		$off_loc_col="";
	}
		$pet_action_cond= " and action_type_code in ('A','R')";
		if($office_type=='O'){
		$pet_action_cond.= " and vw.petition_id not in (select fb_petition_id from petitioner_feedback where fb_entby=".$dept_user_id.")";
		$pet_action_cond.= " and usr.off_level_dept_id=".$userProfile->getOff_level_dept_id();
		if($userProfile->getDept_desig_id()==76 ||$userProfile->getDept_desig_id()==77 ||$userProfile->getDept_desig_id()==78 ||$userProfile->getDept_desig_id()==79 ||$userProfile->getDept_desig_id()==80 ){
		if($_SESSION['USER_ID_PK']!=''){
			$pet_action_cond.= " and vw.pet_entby=".$_SESSION['USER_ID_PK'];
		}
		}else{
		if($userProfile->getDesig_roleid()==5){
			$codn.= " AND a.pet_type_id!=4";
			//$codn.= " AND vw.pet_type_id!=4";
		$pet_action_cond.= " and vw.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
		}
		}
		
		$pet_action_cond.= " and COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id)=".$userProfile->getOff_loc_id();
		}else if($office_type=='P'){
		$pet_action_cond.= " and vw.petition_id not in (select fb_petition_id from petitioner_feedback where fb_entby=".$dept_user_id.")";
		$pet_action_cond.= " and usr.off_level_dept_id=".$off_level_dept_id_p;
		$pet_action_cond.= " and COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id)=".$office_p;
		
		}

	$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
	action_type_code,to_whom,pet_type_name, comm_mobile
	from fn_petition_details(array(select a.petition_id from pet_master a 
	inner join  pet_action b on b.petition_id=a.petition_id".$codn.$pet_type_codition."))".$pet_action_cond."";	
	
	$inSql="select petition_no, l_pet_action_id as pet_action_id,vw.petition_id, to_char(vw.petition_date, 'DD/MM/YYYY HH24:MI:SS')::character varying AS petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, trim(vw.petitioner_name::text) || ', '::text || COALESCE('Door No: '||vw.comm_doorno || ', ','')::text || COALESCE(vw.comm_street || ', ','')::text || COALESCE(vw.comm_area || ', ','')::text ||   COALESCE('Pincode - '||vw.comm_pincode || '. ','')::text AS pet_address, griev_district_id, act.l_action_remarks AS fwd_remarks, griev_subtype_id, at.action_type_name, to_char(act.l_action_entdt, 'DD/MM/YYYY HH24:MI:SS')::character varying AS fwd_date,CASE
			WHEN act.l_action_type_code = 'A'::bpchar OR act.l_action_type_code = 'R'::bpchar THEN '---'::character varying
			ELSE age(now()::date::timestamp with time zone, vw.petition_date::timestamp with time zone)::character varying
		END AS pend_period, l_action_entby,l_action_entdt, l_action_type_code as action_type_code,l_to_whom,pet_type_name, comm_mobile,COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_id
	from 
	vw_pet_master vw inner join  pet_action_first_last act on act.petition_id=vw.petition_id 
	inner join vw_usr_dept_users usr on vw.pet_entby=usr.dept_user_id 
	left join lkp_action_type at ON at.action_type_code = act.l_action_type_code where vw.petition_id in
	(select a.petition_id from pet_master a 
	inner join  pet_action b on b.petition_id=a.petition_id ".$codn.$pet_type_codition.")".$pet_action_cond."";

	
	$query = 'select * from (SELECT aaa.*, row_number() OVER()as rownum FROM(
	select * from (
	'.$inSql.'
	) aa ORDER BY aa.petition_id)aaa)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{										
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('subsource_name', $row['subsource_name']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);	
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);			
		echo $page->generateXMLTag('mobile', $row['comm_mobile']);			
	}
	
	$sql_count = "SELECT count(*) FROM
	(
	SELECT a.pet_action_id, a.petition_id, 
	cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
	FROM vw_pet_actions a
	".$codn_cnt.$pet_type_codition.") 
	aa
	WHERE aa.rnk=1";

	$sql_count = 'select count(*) from ('.$inSql.') aa';					
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	$query = "SELECT fb_rating_id, fb_rating_name FROM lkp_feedback_rating order by fb_rating_id;";
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		
		foreach($rowarray as $row)
		{
			echo $page->generateXMLTag('acttype_code', $row['fb_rating_id']);
			echo $page->generateXMLTag('acttype_desc', $row['fb_rating_name']);
		}
	echo "</response>";
		
	  
	}
} else if ($mode=='p_search_officers') {
	$dept_id=stripQuotes(killChars($_POST["dept_id"]));
	$desig=stripQuotes(killChars($_POST["desig"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$levl=stripQuotes(killChars($_POST["levl"]));
	$dist_id=stripQuotes(killChars($_POST["dist_id"]));
	$cond="";
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
		
	}
	if($userProfile->getOff_level_id()==11){
		$cond.=" and off_hier[9]=".$userProfile->getZone_id();
		
	}
	if ($desig != "")	 {
		$cond.= " and dept_desig_name like '".$desig."%'";
		
	}
	$search_condition = '';
	if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && 
	   $userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() 
	   && $userProfile->getPet_disposal()) {
		   $search_condition = " off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id();
	   } else {
		  $search_condition = " off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." ";
		   //and off_loc_id=".$userProfile->getOff_loc_id();
		   
			//$search_condition = " off_hier[13]=".$userProfile->getDistrict_id()." ";
	   }
	if ($userProfile->getDept_user_id() == 1) {
		$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id
		from vw_usr_dept_users_v_sup
		where enabling ".$cond."
		order by off_level_id, off_loc_id,  dept_desig_id";
	}
	else
	{
		$sql="select dept_id,off_loc_name, off_level_dept_name, dept_desig_name, dept_desig_tname, user_name, user_name as password , dept_user_id
		from vw_usr_dept_users_v_sup
		where ".$search_condition.$cond. " and off_level_dept_id>=".$userProfile->getOff_level_dept_id()." and enabling
		order by off_level_id, off_loc_id,  dept_desig_id";	
	}
	//echo $sql;
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
else if ($mode=='p_search_gsubtype') {
	$gtype=stripQuotes(killChars($_POST["gtype"]));
	$cond="";
	if ($gtype != "")	 {
		$cond= " and a.griev_type_id=".$gtype." ";
	}

	if ($userProfile->getOff_level_id() == 1) {
		$sql = "select distinct a.griev_type_id,a.griev_subtype_id,a.griev_type_name,a.griev_type_tname,
		a.griev_subtype_name,a.griev_subtype_tname, a.griev_subtype_code 
		from vw_lkp_griev_subtype a
		left join vw_usr_dept_griev_subtype b on b.griev_type_id=a.griev_type_id and b.griev_subtype_id=a.griev_subtype_id
	    where dept_id = ".$userProfile->getDept_id()." and ".$userProfile->getOff_level_id()."=any(off_level_id)".$cond.
		" order by a.griev_type_id,a.griev_subtype_id";
	} else {
		$sql = "select distinct a.griev_type_id,a.griev_subtype_id,a.griev_type_name,a.griev_type_tname,
		a.griev_subtype_name,a.griev_subtype_tname, a.griev_subtype_code 
		from vw_lkp_griev_subtype a
		left join vw_usr_dept_griev_subtype b on b.griev_type_id=a.griev_type_id
	    where dept_id = ".$userProfile->getDept_id().$cond."  order by a.griev_type_id,a.griev_subtype_id";
	}	

	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_type_tname', $row['griev_type_tname']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('griev_subtype_tname', $row['griev_subtype_tname']);
		echo $page->generateXMLTag('griev_subtype_code', $row['griev_subtype_code']);
		
	}
	echo "</response>";
} else if ($mode=='p_ack_search') {
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken)
	  {
		    header('Location: logout.php');
		    exit;
	   }
	  else{	
	  
	$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
    $pfrompetactdate=stripQuotes(killChars($_REQUEST["p_from_pet_act_date"]));
	$ptopetactdate=stripQuotes(killChars($_REQUEST["p_to_pet_act_date"]));
	$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	
/*------------------------------- For Validate the date format------------------------------------------------  */	
	
	    //$pfrompetdate = "@#$%%^";
	 	$dt1=explode('/',$pfrompetdate);
		$day=$dt1[0];
		$mnth=$dt1[1];
		$yr=$dt1[2];
    	$p_frompetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetdate)) { //$date_regex declared in common_date_fun.php
   			$p_from_pet_date = '';
		} else {
			$p_from_pet_date = "$p_frompetdate";     
		}
		 
		$dt2=explode('/',$ptopetdate);
		$day=$dt2[0];
		$mnth=$dt2[1];
		$yr=$dt2[2];
    	$p_topetdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_date = '';
		} else {
			$p_to_pet_date = "$p_topetdate";     
		}
	  
	/*---------------------------------------------------------------*/
		$dt3=explode('/',$pfrompetactdate);
		$day=$dt3[0];
		$mnth=$dt3[1];
		$yr=$dt3[2];
    	$p_frompetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_from_pet_act_date = '';
		} else {
			$p_from_pet_act_date = "$p_frompetactdate";     
		}
		 
		$dt4=explode('/',$ptopetactdate);
		$day=$dt4[0];
		$mnth=$dt4[1];
		$yr=$dt4[2];
    	$p_topetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetactdate)) { //$date_regex declared in common_date_fun.php
   			$p_to_pet_act_date = '';
		} else {
			$p_to_pet_act_date = "$p_topetactdate";     
		}
/*------------------------------- End of Validate the date format  ------------------------------------------------  */
	
	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  "  where a.petition_date::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
	}
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
	$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
	action_type_code,to_whom,fwd_remarks
	from fn_petition_details(array(select a.petition_id from pet_master a 
	inner join vw_usr_dept_users_v c on c.dept_user_id=a.pet_entby
	where c.dept_id=".$userProfile->getDept_id()." 
	and c.off_level_dept_id=".$userProfile->getOff_level_dept_id()." and c.off_loc_id=".$userProfile->getOff_loc_id().$codn.")) ORDER BY petition_id desc ";
				
	$query = 'select * from (SELECT aaa.*, row_number() OVER()as rownum FROM ('.$inSql.') aaa) petition  '.	
	'WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))).' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
					
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('subsource_name', $row['subsource_name']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);	
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);	
	}
	
		$sql_count = "SELECT count(*) FROM
					(".$inSql.")  aa";
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo "Execution completed........";

	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
	echo "</response>";
  }

} else if ($mode=='p_search_upload') {
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken)
	  {
		    header('Location: logout.php');
		    exit;
	   }
	  else{	
	  
	$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
    $pfrompetactdate=stripQuotes(killChars($_REQUEST["p_from_pet_act_date"]));
	$ptopetactdate=stripQuotes(killChars($_REQUEST["p_to_pet_act_date"]));
	$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	$gtype=stripQuotes(killChars($_POST["gtype"]));
	$dept=stripQuotes(killChars($_POST["dept"]));
	$p_uploaded=stripQuotes(killChars($_POST["p_uploaded"]));
	
/*------------------------------- For Validate the date format------------------------------------------------  */	
	
	    //$pfrompetdate = "@#$%%^";
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
		 
		//$ptopetdate = "@#$%%^";
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
	  
	/*---------------------------------------------------------------*/
	    //$pfrompetactdate = "@#$%%^";
		$dt3=explode('/',$pfrompetactdate);
		$day=$dt3[0];
		$mnth=$dt3[1];
		$yr=$dt3[2];
    	$p_frompetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_frompetactdate)) { //$date_regex declared in common_date_fun.php
  		 // echo '<br>Your hire date entry does not match the YYYY-MM-DD required format.<br>';
   			$p_from_pet_act_date = '';
		} else {
			//echo '<br>Your date is set correctly<br>'; 
			$p_from_pet_act_date = "$p_frompetactdate";     
		}
		 
	    //$ptopetactdate = "@#$%%^";
		$dt4=explode('/',$ptopetactdate);
		$day=$dt4[0];
		$mnth=$dt4[1];
		$yr=$dt4[2];
    	$p_topetactdate=$yr.'-'.$mnth.'-'.$day;
	
		if (!preg_match($date_regex, $p_topetactdate)) { //$date_regex declared in common_date_fun.php
  		 // echo '<br>Your hire date entry does not match the YYYY-MM-DD required format.<br>';
   			$p_to_pet_act_date = '';
		} else {
			//echo '<br>Your date is set correctly<br>'; 
			$p_to_pet_act_date = "$p_topetactdate";     
		}
/*------------------------------- End of Validate the date format  ------------------------------------------------  */
	
	if(!empty($p_from_pet_act_date)){
		$codn.=  " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
		$codn_cnt.=  "  where b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
	}
	if(!empty($p_to_pet_act_date)){
		$codn.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
	}
	
	
	
	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  "  where a.petition_date::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
	}
	
	
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
	/*if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}*/
	
	if(!empty($dept)){
		$codn.= " AND a.dept_id=".$dept;
		$codn_cnt.= " AND a.dept_id=".$dept;
	}
	
	if(!empty($p_gtype)){
		$codn.= " AND a.griev_type_id=".$p_gtype;
		$codn_cnt.= " AND a.griev_type_id=".$p_gtype;
	}
	 	if ($p_uploaded == "no") {
			$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id,fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,action_type_code,to_whom,fwd_remarks
			from fn_petition_details(array(select a.petition_id from pet_master a 
			inner join  pet_action b on b.petition_id=a.petition_id
			where b.action_entby=".$_SESSION['USER_ID_PK']." AND action_type_code in ('A','C','E','I','S')".$codn." and 
			not exists (select * from pet_action_doc c where c.petition_id = a.petition_id))) order by pet_action_id desc";	
		} else {
			$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, 
			subsource_remarks,grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, 
			gri_address, griev_district_id,fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
			action_type_code,to_whom,fwd_remarks					 
			from fn_petition_details(array(select a.petition_id from pet_master a 
			inner join  pet_action b on b.petition_id=a.petition_id
			where b.action_entby=".$_SESSION['USER_ID_PK']." AND action_type_code in ('C','E','I','S')".$codn." and 
			exists (select * from pet_action_doc c where c.petition_id = a.petition_id)))  order by pet_action_id desc";	
		}

	$query = 'select * from (SELECT aaa.*, row_number() OVER()as rownum FROM('.$inSql.')aaa)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
					
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('subsource_name', $row['subsource_name']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);	
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);	
	}
	  $sql_count = "SELECT count(*) FROM
					(".$inSql.") 
					 aa";
		
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo "Execution completed........";

	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
echo "</response>";
  }

} else if ($mode=='p_delete_uploaded') {
		$pet1=stripQuotes(killChars($_POST["p1"]));
		$pet2=stripQuotes(killChars($_POST["p2"]));
		$pet3=stripQuotes(killChars($_POST["p3"]));
		$cond ="";
		if ($pet1!="")	 {
		   $cond = "where petition_no in ('".$pet1."')";
		   //$i = 1;
	   }
	   
	   if ($pet1!= "" && $pet2!="")	 {
		   $cond = "where petition_no in ('".$pet1."','".$pet2."')";
		   //$i = 2;
	   }
	   
	   if ($pet1!= "" && $pet2!="" && $pet3!="")	 {
		   $cond = "where petition_no in ('".$pet1."','".$pet2."','".$pet3."')";
		   //$i = 3;
	   }
	   echo "<response>";
	   if ($cond != ""){
			$sql="delete from pet_action_doc where petition_id in (select petition_id from pet_master ".$cond.")";
			$result=$db->query($sql);
			if ($result)
				echo "<count>1</count>";
			else
				echo "<count>0</count>";
	   }
	   else {
			echo "<count>0</count>";		   
	   }
	   echo "</response>";	
}
else if ($mode=='password_reset') {
	   $users=stripQuotes(killChars($_POST["userslist"]));
	   $userID=stripQuotes(killChars($_POST["userID"]));
	  $sql="update usr_dept_users set user_pwd='Password@1',user_pwd_encr=md5('Password@1'),modby=".$userID." where dept_user_id in(".$users.")";;
	   $result=$db->query($sql);
		echo "<response>";   
		if ($result)
			echo "<count>1</count>";
		else
			echo "<count>0</count>";
		echo "</response>";	
} else if ($mode=='p_search_action_taken') {
	
	$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	$gtype=stripQuotes(killChars($_POST["gtype"]));
	$gstype=stripQuotes(killChars($_POST["gstype"]));
	$dept=stripQuotes(killChars($_POST["dept"]));
	$actiontype=stripQuotes(killChars($_POST["actiontype"]));
	$petitiontype=stripQuotes(killChars($_POST["petitiontype"]));	
	$pet_community=stripQuotes(killChars($_POST["pet_community"]));
	$special_category=stripQuotes(killChars($_POST["special_category"]));
		
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
		 
		//$ptopetdate = "@#$%%^";
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


	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.l_action_entdt::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  "  where a.l_action_entdt::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.l_action_entdt::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.l_action_entdt::date <= '".$p_to_pet_date."'::date";
	}
	
	if(!empty($actiontype)){
		$codn.= " AND a.l_action_type_code='".$actiontype."' ";
		$codn_cnt.= " AND a.l_action_type_code='".$actiontype."' ";
	} else {
		$codn.= " AND a.l_action_type_code in ('A','R') ";
		$codn_cnt.= " AND a.l_action_type_code in ('A','R') ";
	}
	
	$grv_cond = "";
	$grv_cond_cnt = "";
	if(!empty($p_source)){
		$grv_cond .= (empty($grv_cond))? (" source_id=".$p_source) : (" AND source_id=".$p_source);
		$grv_cond_cnt .= (empty($grv_cond))? (" source_id=".$p_source) : (" AND source_id=".$p_source);
	}
			
	if(!empty($dept)){
		$grv_cond .= (empty($grv_cond))? (" dept_id=".$dept) : (" AND dept_id=".$dept);
		$grv_cond_cnt .= (empty($grv_cond))? (" dept_id=".$dept) : (" AND dept_id=".$dept);
		
	}
	
	if(!empty($gtype)){
		
		$grv_cond .= (empty($grv_cond))? (" griev_type_id=".$gtype) : (" AND griev_type_id=".$gtype);
		$grv_cond_cnt .= (empty($grv_cond))? (" griev_type_id=".$gtype) : (" AND griev_type_id=".$gtype);
		
	}	
	
	if(!empty($gstype)){
		
		$grv_cond .= (empty($grv_cond))? (" griev_subtype_id=".$gstype) : (" AND griev_subtype_id=".$gstype);
		$grv_cond_cnt .= (empty($grv_cond))? (" griev_type_id=".$gstype) : (" AND griev_subtype_id=".$gstype);
		
	}
	
	if(!empty($petitiontype)){
		
		$grv_cond .= (empty($grv_cond))? (" pet_type_id=".$petitiontype) : (" AND pet_type_id=".$petitiontype);
		$grv_cond_cnt .= (empty($grv_cond))? (" pet_type_id=".$petitiontype) : (" AND pet_type_id=".$petitiontype);
		
	}
	
	if(!empty($pet_community)){
		
		$grv_cond .= (empty($grv_cond))? (" pet_community_id=".$pet_community) : (" AND pet_community_id=".$pet_community);
		$grv_cond_cnt .= (empty($grv_cond))? (" pet_community_id=".$pet_community) : (" AND pet_community_id=".$pet_community);
		
	}
	
	if(!empty($special_category)){

		$grv_cond .= (empty($grv_cond))? (" petitioner_category_id=".$special_category) : (" AND petitioner_category_id=".$special_category);
		$grv_cond_cnt .= (empty($grv_cond))? (" petitioner_category_id=".$special_category) : (" AND petitioner_category_id=".$special_category);

	}
	
	$grv_cond = (empty($grv_cond))? $grv_cond : (" where ".$grv_cond);
	$grv_cond_cnt = (empty($grv_cond_cnt))? $grv_cond_cnt : (" where ".$grv_cond_cnt);
		
		
	if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() && $userProfile->getPet_disposal())
	{
		$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
		grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
		fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
		action_type_code,action_type_name, to_whom,fwd_remarks,pet_type_name
		from fn_petition_details(array(select a.petition_id from pet_action_first_last a
		where a.l_action_entby=".$userProfile->getDept_user_id().$codn."))".$grv_cond." order by petition_id";
	} else {
		$inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
		grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
		fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
		action_type_code,action_type_name, to_whom,fwd_remarks,pet_type_name
		from fn_petition_details(array(select a.petition_id from pet_action_first_last a
		where (a.f_to_whom=".$userProfile->getDept_user_id()." or 
		(a.f_action_entby=".$userProfile->getDept_user_id()." 
		and a.l_action_entby=".$userProfile->getDept_user_id()."))".
		$codn."))".$grv_cond." order by petition_id";		
	}
	
	$query = 'select * from (SELECT aaa.*, row_number() OVER()as rownum FROM('.$inSql.')aaa)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));					
	
	
	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
					
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('subsource_name', $row['subsource_name']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);	
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);	
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);
		
		
	}
				
	$sql_count = "SELECT count(*) FROM (".$inSql.") aa";	
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo "Execution completed........";

	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
echo "</response>";
} else if ($mode=='verify_otp') {
	//include("funSMS.php");
	$otp_val=stripQuotes(killChars($_POST["otp_val"]));
	$user_id=stripQuotes(killChars($_POST["user_id"]));
		
	$sql="select otp from usr_dept_users where  dept_user_id=".$user_id."";
		
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	foreach($rowarray as $row)
	{
		$otp = $row['otp'];
	}
	
	if ($otp_val == $otp) {
		$res = 0;	
	} else {
		$res = 1;
	}
	echo "<response>";
	echo "<result>".$res."</result>";
	echo "</response>";
	
} else if ($mode=='resend_otp') {
	//include("funSMS.php");
	$user_id=stripQuotes(killChars($_POST["user_id"]));
	$sql="select mobile,otp from usr_dept_users where  dept_user_id=".$user_id."";
		
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	foreach($rowarray as $row)
	{
		$mobile = $row['mobile'];
		$otp = $row['otp'];
	}
	

	//1007430885434076659 - Your OTP for this transaction is {#var#}. This is valid for next 10 Minutes Do not share this password with anyone - Tamil Nadu e-Governance Agency.
	$stringmsg = 'Your OTP for this transaction is '.$string.'. This is valid for next 10 Minutes. Do not share this password with anyone - Tamil Nadu e-Governance Agency.';
	$ct_id="1007936125570006824";
	
	$mobile_no = $mobile;
	if ($mobile_no != '') {
		//$strStatus = SMS($mobile,$stringmsg,'0');
		$strStatus = SMS($mobile,$stringmsg,'0',$ct_id);
	}
	//$_SESSION["otp2"] = $string;
	echo "<response>";
	echo "<status>".$strStatus."</status>";
	echo "</response>";
	
} else if ($mode=='save_feedback') {
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken)
	{
	   header('Location: logout.php');
	   exit;
	}
	else
	{
		$rating = stripQuotes(killChars($_POST["rating_code"]));
		$pet_sno = stripQuotes(killChars($_POST["pet_sno"]));
		$remark = stripQuotes(killChars($_POST["remark"]));
		$i=0;
	$today = $page->currentTimeStamp();
	$user_id=stripQuotes(killChars($_POST['userID']));
	$ent_ip_address=$_SERVER['REMOTE_ADDR'];
	$count1=0;
	foreach($pet_sno as $petSno) {
		$sql="insert into petitioner_feedback(fb_petition_id,fb_rating_id,fb_remarks,fb_entby,fb_entdt,fb_ent_ip_address) values(".$petSno.",".$rating[$i].",'".$remark[$i]."',".$user_id.",'".$today."'::date,'".$ent_ip_address."')";
		$result = $db->query($sql);
		if($result){
		$count1++;
		}
		$i++;
		}
	echo "<response>";
	if($count1>0){
		echo "<count>".$count1."</count>";
		}
	echo "</response>";
	}
}
?>
