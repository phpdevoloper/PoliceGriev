<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$mode=$_POST["mode"];

if($mode=='p_search'){
	
 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
 if($_SESSION['formptoken'] != $form_tocken)
	  {
		    header('Location: logout.php');
			exit;
		     
	   }
	  else{
	$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));	  
	$p_can_id=stripQuotes(killChars($_POST["p_can_id"]));
	$p_aadharid=stripQuotes(killChars($_POST["p_aadharid"]));
	
	$p_from_pet_date=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$p_to_pet_date=stripQuotes(killChars($_POST["p_to_pet_date"]));
	
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	
	$p_griev=stripQuotes(killChars($_POST["p_griev"]));
	$p_griev_sub_type=stripQuotes(killChars($_POST["p_griev_sub_type"]));
	
	$p_griev_dist_id=stripQuotes(killChars($_POST["p_griev_dist_id"]));
	
	$p_griev_taluk_id=stripQuotes(killChars($_POST["p_griev_taluk_id"]));
	$p_griev_reve_village_id=stripQuotes(killChars($_POST["p_griev_reve_village_id"]));
	
	$p_block_id=stripQuotes(killChars($_POST["p_block_id"]));
	$p_vill_pan_id=stripQuotes(killChars($_POST["p_vill_pan_id"]));
	
	$p_griev_urban_id=stripQuotes(killChars($_POST["p_griev_urban_id"]));
	
	$p_griev_office_id=stripQuotes(killChars($_POST["p_griev_office_id"]));	
	
	$p_dept_id=stripQuotes(killChars($_POST["dept_id"]));			
	
	$p_petitioner_name=stripQuotes(killChars($_POST["p_petitioner_name"]));
	$p_mobile=stripQuotes(killChars($_POST["p_mobile"]));
	$p_petition_type=stripQuotes(killChars($_POST["p_petition_type"]));
	
	$pet_community=stripQuotes(killChars($_POST["pet_community"]));
	$special_category=stripQuotes(killChars($_POST["special_category"]));
	
	$p_action_type=stripQuotes(killChars($_POST["p_action_type"]));
	$source_from=stripQuotes(killChars($_POST["source_from"]));
	$key_words=stripQuotes(killChars($_POST["key_words"]));
	$order_by=stripQuotes(killChars($_POST["order_by"]));
	$codn='';
	
		$office_type=stripQuotes(killChars($_POST["office_type"]));
		$pattern_p=stripQuotes(killChars($_POST["pattern_p"]));
		$off_level_p=stripQuotes(killChars($_POST["off_level_p"]));
		$office_p=stripQuotes(killChars($_POST["office_p"]));
		$off_lvl_exp=explode('-',$off_level_p);
		$off_levelp=$off_lvl_exp[0];
		$off_level_dept_id_p=$off_lvl_exp[1];
	
	if($userProfile->getDept_coordinating()!=1 && $userProfile->getDesig_coordinating()!=1 && $userProfile->getOff_coordinating()!=1 ) {
		$codn=' WHERE a.dept_id='.$userProfile->getDept_id(). " AND a.to_whom=".$_SESSION['USER_ID_PK'].") ";
	} else if ($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !($userProfile->getDept_coordinating())) {
		$codn=' WHERE a.dept_id='.$userProfile->getDept_id(). "";
	}
	
	$fromdate=explode('/',$p_from_pet_date);
	$day=$fromdate[1];
	$mnth=$fromdate[0];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$p_to_pet_date);
	$day=$todate[1];
	$mnth=$todate[0];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
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
	

	if(!empty($p_from_pet_date)){
		$codn="WHERE a.petition_date::date >= '".$frm_dt."'::date" ;
	}		
	if(!empty($p_petition_no)){
		$codn.= $codn==''? "WHERE a.petition_no='".$p_petition_no."'" : " AND a.petition_no='".$p_petition_no."'";
	}
	if(!empty($p_to_pet_date)){
		$codn.= $codn==''? "WHERE a.petition_date::date <= '".$to_dt."'::date" : " AND a.petition_date::date <= '".$to_dt."'::date";
	}
	if(!empty($p_source)){
		$codn.= $codn==''? "WHERE a.source_id=".$p_source : " AND a.source_id=".$p_source;
	}

	if(!empty($p_griev)){
		$codn.= $codn==''? "WHERE a.griev_type_id=".$p_griev : " AND a.griev_type_id=".$p_griev;
	}
	if(!empty($p_griev_sub_type)){
		$codn.= $codn==''? "WHERE a.griev_subtype_id=".$p_griev_sub_type : " AND a.griev_subtype_id=".$p_griev_sub_type;
	}
	
	if (!empty($p_petitioner_name)) {
		$codn.= $codn==''? "where petitioner_name like '%".$p_petitioner_name."%'" : " and petitioner_name like '%".$p_petitioner_name."%'";	
	}
		
	if(!empty($p_mobile)){
		
		$codn.= $codn==''? "WHERE a.comm_mobile='".$p_mobile."'" : " AND a.comm_mobile='".$p_mobile."'";
	}
	if(!empty($p_petition_type)){
		
		$codn.= $codn==''? "WHERE a.pet_type_id='".$p_petition_type."'" : " AND a.pet_type_id='".$p_petition_type."'";
	}
	
	if(!empty($pet_community)){
		
		$codn.= $codn==''? "WHERE a.pet_community_id='".$pet_community."'" : " AND a.pet_community_id='".$pet_community."'";
	}
	
	if(!empty($special_category)){
		
		$codn.= $codn==''? "WHERE a.petitioner_category_id='".$special_category."'" : " AND a.petitioner_category_id='".$special_category."'";
	}
	if($userProfile->getDept_off_level_pattern_id()!=''){
		//$off_loc_col=",fn_off_loc_hierarchy(1,".$userProfile->getDept_off_level_pattern_id().",usr.off_level_id,COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_hier";
		
		$off_loc_col=",COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_id";
	}else{
		$off_loc_col="";
	}
	$pet_action_cond="";
	if($office_type==''){
		$office_type='O';
		}
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
		
	$actiontypecodn = "";
	
	if (!empty($source_from)) {
		if (!empty($p_action_type)) {
			$actiontypecodn = " and l_action_type_code='".$p_action_type."'";			
		} else {
			$actiontypecodn= " and l_action_type_code in ('A','R') " ;
		}
	} else {
		if(!empty($p_action_type)){
			
			if ($p_action_type == 'P') {
				$actiontypecodn= " and l_action_type_code not in ('A','R')" ;
			} else {
				$actiontypecodn = " and action_type_code='".$p_action_type."'";
			}
			
			
		}
	}
	$petition_description_condition = '';
	
	if(!empty($key_words)){
		//$key_words_array = explode('**',stripQuotes(killChars($key_words)));
		//SELECT count(*) from pet_master where 'legal & certificate'::tsquery @@ grievance::tsvector;
		$key_words_new=str_replace("**"," & ",$key_words);				
		$petition_description_condition = " and '".$key_words_new."'::tsquery @@ griev_key_words::tsvector ";

	}
	$order_by_cond = ' order by petition_id';
	if ($order_by == '') {
		$order_by_cond = ' order by petition_id';
	} else 	if ($order_by == 'P') {
		$order_by_cond = ' order by petition_id';
	} else 	if ($order_by == 'M') {
		$order_by_cond = ' order by comm_mobile,petition_id';
	} else 	if ($order_by == 'N') {
		$order_by_cond = ' order by pet_address,petition_id';
	}

	 $inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	 grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	 fwd_remarks, action_type_name,fwd_date, off_location_design, pend_period,pet_type_name,comm_mobile 
	 from fn_petition_details(array(select a.petition_id 
	 from pet_master a ".$codn.$petition_description_condition."))".$actiontypecodn.$order_by_cond." ";
	 
	 
	 
	$inSql="select petition_no, l_pet_action_id as pet_action_id,vw.petition_id, to_char(vw.petition_date, 'DD/MM/YYYY HH24:MI:SS')::character varying AS petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, trim(vw.petitioner_name::text) || ', '::text || COALESCE('Door No: '||vw.comm_doorno || ', ','')::text || COALESCE(vw.comm_street || ', ','')::text || COALESCE(vw.comm_area || ', ','')::text ||   COALESCE('Pincode - '||vw.comm_pincode || '. ','')::text AS pet_address, griev_district_id, act.l_action_remarks AS fwd_remarks, griev_subtype_id, at.action_type_name, to_char(act.l_action_entdt, 'DD/MM/YYYY HH24:MI:SS')::character varying AS fwd_date,CASE
			WHEN act.l_action_type_code = 'A'::bpchar OR act.l_action_type_code = 'R'::bpchar THEN '---'::character varying
			ELSE age(now()::date::timestamp with time zone, vw.petition_date::timestamp with time zone)::character varying
		END AS pend_period, l_action_entby,l_action_entdt, l_action_type_code as action_type_code,l_to_whom,pet_type_name, comm_mobile,COALESCE(usr.zone_id, usr.range_id ,usr.district_id, usr.division_id, usr.circle_id,usr.state_id) as off_loc_id,griev_key_words
	from 
	vw_pet_master vw inner join  pet_action_first_last act on act.petition_id=vw.petition_id 
	inner join vw_usr_dept_users usr on vw.pet_entby=usr.dept_user_id 
	left join lkp_action_type at ON at.action_type_code = act.l_action_type_code where vw.petition_id in
	(select a.petition_id from pet_master a 
	inner join  pet_action b on b.petition_id=a.petition_id ".$codn.$pet_type_codition.")".$pet_action_cond.$actiontypecodn.$petition_description_condition."";
	
	
	 $query = 'select * from 
	 (select aa.*, row_number() OVER()as rownum from ('.$inSql.') aa ORDER BY rownum) petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row)
	{	
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('petitioner_name', $row['petitioner_name']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);
		
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);	
		echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']);	
		
	}
	
	
	
	if(!empty($p_action_type)){
		
		if ($p_action_type == 'P') {
			$sql_count = "select count(*) from pet_master  a
			inner join pet_action_first_last b on b.petition_id=a.petition_id
			".$codn." and b.l_action_type_code not in ('A','R')";
						
		} else {
			$sql_count = "select count(*) from pet_master  a
			inner join pet_action_first_last b on b.petition_id=a.petition_id
			".$codn." and b.l_action_type_code = '".$p_action_type."'";
		}	
		
	} else {
		if (!empty($source_from)) {
			$sql_count = "select count(*) from pet_master  a
				inner join pet_action_first_last b on b.petition_id=a.petition_id
				".$codn.$petition_description_condition." and b.l_action_type_code in ('A','R')";
		} else {
			$sql_count = "select count(*) from pet_master a ".$codn.$petition_description_condition."";
		}
	}	$sql_count = "select count(*) from (".$query.") cccc;";

	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
	echo "</response>";
 }
} else if($mode=='sub_source'){
	
 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
if($_SESSION['formptoken'] != $form_tocken)
{
	header('Location: logout.php');
	 exit;
}
else{
	$gre_sub_src="SELECT subsource_id, subsource_name, subsource_tname
		FROM lkp_pet_subsource
		WHERE enabling AND source_id=".stripQuotes(killChars($_POST["src_sno"]))." 
		ORDER BY subsource_name";
		
	$resultSubType = $db->query($gre_sub_src);
	$rowarraySubType = $resultSubType->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarraySubType as $rowSubType)
	{
		echo $page->generateXMLTag('subsource_id', $rowSubType['subsource_id']);
		if($_SESSION["lang"]=='E'){
		echo $page->generateXMLTag('subsource_name', $rowSubType['subsource_name']);
		}else{
		echo $page->generateXMLTag('subsource_name', $rowSubType['subsource_tname']);	
		}
	}
	echo "</response>";
   }
} else if($mode=='griev_sub'){
	
 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
if($_SESSION['formptoken'] != $form_tocken)
{
	header('Location: logout.php');
	 exit;
}
else{

	if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating()){
		$gre_sub_type = "SELECT distinct(griev_subtype_id), griev_subtype_code, 
		griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
		WHERE griev_type_id=".stripQuotes(killChars($_POST["griev_sno"]))." ORDER BY griev_subtype_name";
	}else{
		$gre_sub_type = "SELECT distinct(griev_subtype_id), griev_subtype_code,
		griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
		WHERE dept_id = ".$userProfile->getDept_id()." and griev_type_id=".stripQuotes(killChars($_POST["griev_sno"]))." ORDER BY griev_subtype_name";
	}
		
	$resultSubType = $db->query($gre_sub_type);
	$rowarraySubType = $resultSubType->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarraySubType as $rowSubType)
	{
		echo $page->generateXMLTag('griev_subtype_id', $rowSubType['griev_subtype_id']);
		if($_SESSION["lang"]=='E'){
		echo $page->generateXMLTag('griev_subtype_name', $rowSubType['griev_subtype_name']);
		}else{
		echo $page->generateXMLTag('griev_subtype_name', $rowSubType['griev_subtype_tname']);	
		}
	}
	echo "</response>";
   }
} else if($mode=='rev_village'){
	$sql="SELECT rev_village_id, rev_village_name,rev_village_tname
  		FROM mst_p_rev_village WHERE taluk_id=".stripQuotes(killChars($_POST["taluk_sno"]))." 
		ORDER BY rev_village_name";
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo $page->generateXMLTag('rev_village_id', $row['rev_village_id']);
		if($_SESSION["lang"]=='E'){
		echo $page->generateXMLTag('rev_village_name', ucfirst(strtolower($row['rev_village_name'])));
		}else{
		echo $page->generateXMLTag('rev_village_name', $row['rev_village_tname']);	
		}
	}
	echo "</response>";
} else if($mode=='lb_village'){
	$sql="SELECT lb_village_id, lb_village_name,lb_village_tname
		FROM mst_p_lb_village WHERE block_id=".stripQuotes(killChars($_POST["block_sno"]))." 
		ORDER BY lb_village_name";
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		
		
		echo $page->generateXMLTag('lb_village_id', $row['lb_village_id']);
		if($_SESSION["lang"]=='E'){
		echo $page->generateXMLTag('lb_village_name', ucfirst(strtolower($row['lb_village_name'])));
		}else{
		echo $page->generateXMLTag('lb_village_name', $row['lb_village_tname']);	
		}
	}
	echo "</response>";
} else if($mode=='get_depts') {
		$greiv_sub_id = stripQuotes(killChars($_POST["griev_sub"])); 
		
		$sql = "select a.dept_id as dept_id, a.dept_name as dept_name, a.dept_tname as dept_tname, b.off_level_pattern_id as off_level_pattern_id from vw_usr_dept_griev_subtype a
		join usr_dept b on a.dept_id = b.dept_id where a.griev_subtype_id = ".$greiv_sub_id."";
	   
		$result = $db->query($sql);
		$rowArray = $result->fetchall(PDO::FETCH_ASSOC);
		
		echo "<response>";	
		foreach($rowArray as $row){
			echo $page->generateXMLTag('dept_id', $row['dept_id']);
			if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('dept_name', ucfirst(strtolower($row['dept_name'])));
			}else{
				echo $page->generateXMLTag('dept_name', $row['dept_tname']);	
			}	
		}
		
		echo "</response>";
	
} else if ($mode=='get_pattern') {
	$dept = stripQuotes(killChars($_POST["dept_id"]));
	$sql = "select dept_id,off_level_pattern_id from usr_dept where dept_id=".$dept;
	$res = $db->query($sql);
	$rowArray = $res->fetchall(PDO::FETCH_ASSOC); 	
	echo "<response>";
	foreach($rowArray as $row){
		echo $page->generateXMLTag('pattern', $row['off_level_pattern_id']);	
	}
	echo "</response>";
} else if($mode=='load_department') {
	$dept_sql = "select distinct a.dept_id, a.dept_name, a.dept_tname, b.off_level_pattern_id from vw_usr_dept_griev_subtype a
join usr_dept b on a.dept_id = b.dept_id order by b.off_level_pattern_id";
	$res = $db->query($dept_sql);
	$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($row_arr as $row) {
		echo $page->generateXMLTag('dept_id', $row['dept_id']);
		if($_SESSION["lang"]=='E'){
			echo $page->generateXMLTag('dept_name', ucfirst(strtolower($row['dept_name'])));
		}else{
			echo $page->generateXMLTag('dept_name', $row['dept_tname']);	
		}
	}
	echo "</response>";
} else if ($mode == 'get_taluk') {
	$dist = stripQuotes(killChars($_POST["dist"]));
	$sql = "SELECT taluk_id, taluk_name,taluk_tname FROM mst_p_taluk where district_id=".$dist." order by taluk_name";
	$res = $db->query($sql);
	$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($row_arr as $row) {
		echo $page->generateXMLTag('taluk_id', $row['taluk_id']);
		if($_SESSION["lang"]=='E'){
			echo $page->generateXMLTag('taluk_name', ucfirst(strtolower($row['taluk_name'])));
		}else{
			echo $page->generateXMLTag('taluk_tname', $row['taluk_tname']);	
		}
	}
	echo "</response>";
} else if ($mode == 'p_search_for_link') {
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
 if($_SESSION['formptoken'] != $form_tocken)
	  {
		    header('Location: logout.php');
			exit;
		     
	   }
	  else{
	$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));	  
	$p_can_id=stripQuotes(killChars($_POST["p_can_id"]));
	$p_aadharid=stripQuotes(killChars($_POST["p_aadharid"]));
	
	$p_from_pet_date=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$p_to_pet_date=stripQuotes(killChars($_POST["p_to_pet_date"]));
	
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	
	$p_griev=stripQuotes(killChars($_POST["p_griev"]));
	$p_griev_sub_type=stripQuotes(killChars($_POST["p_griev_sub_type"]));
	
	$p_griev_dist_id=stripQuotes(killChars($_POST["p_griev_dist_id"]));
	
	$p_griev_taluk_id=stripQuotes(killChars($_POST["p_griev_taluk_id"]));
	$p_griev_reve_village_id=stripQuotes(killChars($_POST["p_griev_reve_village_id"]));
	
	$p_block_id=stripQuotes(killChars($_POST["p_block_id"]));
	$p_vill_pan_id=stripQuotes(killChars($_POST["p_vill_pan_id"]));
	
	$p_griev_urban_id=stripQuotes(killChars($_POST["p_griev_urban_id"]));
	
	$p_griev_office_id=stripQuotes(killChars($_POST["p_griev_office_id"]));	
	
	$p_dept_id=stripQuotes(killChars($_POST["dept_id"]));			
	
	$p_petitioner_name=stripQuotes(killChars($_POST["p_petitioner_name"]));
	$p_mobile=stripQuotes(killChars($_POST["p_mobile"]));
	$p_petition_type=stripQuotes(killChars($_POST["p_petition_type"]));
	
	$pet_community=stripQuotes(killChars($_POST["pet_community"]));
	$special_category=stripQuotes(killChars($_POST["special_category"]));
	
	$p_action_type=stripQuotes(killChars($_POST["p_action_type"]));
	$source_from=stripQuotes(killChars($_POST["source_from"]));
	$key_words=stripQuotes(killChars($_POST["key_words"]));
	$order_by=stripQuotes(killChars($_POST["order_by"]));
	$codn='';
	
	/*
	if($userProfile->getDept_coordinating()!=1 && $userProfile->getDesig_coordinating()!=1 && $userProfile->getOff_coordinating()!=1 ) {
		$codn=' WHERE a.dept_id='.$userProfile->getDept_id(). " AND a.to_whom=".$_SESSION['USER_ID_PK'].") ";
	} else if ($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !($userProfile->getDept_coordinating())) {
		$codn=' WHERE a.dept_id='.$userProfile->getDept_id(). "";
	}
	*/
	$fromdate=explode('/',$p_from_pet_date);
	$day=$fromdate[1];
	$mnth=$fromdate[0];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$p_to_pet_date);
	$day=$todate[1];
	$mnth=$todate[0];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
			
	if(!empty($p_petition_no)){
		$codn.= ($codn=='') ? "WHERE a.petition_no='".$p_petition_no."'" : " AND a.petition_no='".$p_petition_no."'";
	}
	if(!empty($p_from_pet_date)){
		$codn.=  ($codn=='') ?"WHERE a.petition_date::date >= '".$frm_dt."'::date" : " AND a.petition_date::date >= '".$frm_dt."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= ($codn=='') ? "WHERE a.petition_date::date <= '".$to_dt."'::date" : " AND a.petition_date::date <= '".$to_dt."'::date";
	}
	if(!empty($p_source)){
		$codn.= ($codn=='') ? "WHERE a.source_id=".$p_source : " AND a.source_id=".$p_source;
	}

	if(!empty($p_griev)){
		$codn.= ($codn=='') ? "WHERE a.griev_type_id=".$p_griev : " AND a.griev_type_id=".$p_griev;
	}
	if(!empty($p_griev_sub_type)){
		$codn.= $codn==''? "WHERE a.griev_subtype_id=".$p_griev_sub_type : " AND a.griev_subtype_id=".$p_griev_sub_type;
	}
		
	if (!empty($p_petitioner_name)) {
		$codn.= ($codn=='') ? "where petitioner_name like '%".$p_petitioner_name."%'" : " and petitioner_name like '%".$p_petitioner_name."%'";	
	}
		
	if(!empty($p_mobile)){
		
		$codn.= ($codn=='') ? "WHERE a.comm_mobile='".$p_mobile."'" : " AND a.comm_mobile='".$p_mobile."'";
	}
	if(!empty($p_petition_type)){
		
		$codn.= ($codn=='') ? "WHERE a.pet_type_id='".$p_petition_type."'" : " AND a.pet_type_id='".$p_petition_type."'";
	}
	
	if(!empty($pet_community)){
		
		$codn.= ($codn=='') ? "WHERE a.pet_community_id='".$pet_community."'" : " AND a.pet_community_id='".$pet_community."'";
	}
	
	if(!empty($special_category)){
		
		$codn.= ($codn=='') ? "WHERE a.petitioner_category_id='".$special_category."'" : " AND a.petitioner_category_id='".$special_category."'";
	}
	
	$petition_description_condition = '';
	$codn.= ($codn=='') ?  " WHERE (a.pet_entby=".$_SESSION['USER_ID_PK']." or b.f_action_entby=".$_SESSION['USER_ID_PK'].")" : " AND (a.pet_entby=".$_SESSION['USER_ID_PK']." or b.f_action_entby=".$_SESSION['USER_ID_PK'].")";
	
	if(!empty($key_words)){
		//$key_words_array = explode('**',stripQuotes(killChars($key_words)));
		//SELECT count(*) from pet_master where 'legal & certificate'::tsquery @@ grievance::tsvector;
		$key_words_new=str_replace("**"," & ",$key_words);				
		$petition_description_condition = " and '".$key_words_new."'::tsquery @@ griev_key_words::tsvector ";

	}
	
	 $inSql="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	 grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	 fwd_remarks, action_type_name,fwd_date, off_location_design, pend_period,pet_type_name,comm_mobile 
	 from fn_petition_details(array(select a.petition_id 
	 from pet_master a 
	 inner join pet_action_first_last b on b.petition_id=a.petition_id
	 ".$codn.$petition_description_condition.")) order by petition_id";
	 
	$query = 'select * from 
	(select aa.*, row_number() OVER()as rownum from ('.$inSql.') aa ORDER BY rownum) petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' AND petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row)
	{	
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);
		echo $page->generateXMLTag('fwd_remarks', $row['fwd_remarks']);
		echo $page->generateXMLTag('fwd_date', $row['fwd_date']);
		echo $page->generateXMLTag('action_entby', $row['action_entby']);
		echo $page->generateXMLTag('off_location_design', $row['off_location_design']);
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		
		echo $page->generateXMLTag('rownum', $row['rownum']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('petition_date', $row['petition_date']);
		echo $page->generateXMLTag('petitioner_name', $row['petitioner_name']);
		echo $page->generateXMLTag('action_entdt', $row['action_entdt']);
		
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		echo $page->generateXMLTag('pend_period', $row['pend_period']);
		echo $page->generateXMLTag('subsource_remarks', $row['subsource_remarks']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);	
		echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']);	
		
	}
	
	
	
	if(!empty($p_action_type)){
		
		if ($p_action_type == 'P') {
			$sql_count = "select count(*) from pet_master  a
			inner join pet_action_first_last b on b.petition_id=a.petition_id
			".$codn." and b.l_action_type_code not in ('A','R')";
						
		} else {
			$sql_count = "select count(*) from pet_master  a
			inner join pet_action_first_last b on b.petition_id=a.petition_id
			".$codn." and b.l_action_type_code = '".$p_action_type."'";
		}	
		
	} else {
		if (!empty($source_from)) {
			$sql_count = "select count(*) from pet_master  a
				inner join pet_action_first_last b on b.petition_id=a.petition_id
				".$codn.$petition_description_condition." and b.l_action_type_code in ('A','R')";
		} else {
			$sql_count = "select count(*) from pet_master a ".$codn.$petition_description_condition."";
		}
	}	

	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
	echo "</response>";
 }
}
?>
