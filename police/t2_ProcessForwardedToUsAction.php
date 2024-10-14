<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
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
		
		$sql="select a.dept_user_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_cc." ";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
			$dept_user_id =  $_SESSION['USER_ID_PK'];
		} 
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
if($mode=='p2_search') {
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken) {
	header('Location: logout.php');
	exit;
	} else {
		$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
		$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
		$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
		$p_source=stripQuotes(killChars($_POST["p_source"]));
		/* $dept=stripQuotes(killChars($_POST["dept"])); */
		$gtype=stripQuotes(killChars($_POST["gtype"]));
		$petition_type=stripQuotes(killChars($_POST["petition_type"]));
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
  			$p_to_pet_date = '';
		} else {
			$p_to_pet_date = "$p_topetdate";     
		}
	/*------------------------------- End of Validate the date format ------------------------------------------------  */ 
	 
	$codn='';
	$countcodn = '';
	
	if(!empty($p_from_pet_date)){
		$codn.= $codn=='' ? " WHERE a.petition_date  >= '".$p_from_pet_date."'::date" :
										" AND a.petition_date  >= '".$p_from_pet_date."'::date";
		$countcodn.= $countcodn=='' ? " WHERE petition_date  >= '".$p_from_pet_date."'::date" :
										" AND petition_date  >= '".$p_from_pet_date."'::date";								
	}
	if(!empty($p_to_pet_date)){
		$codn.= $codn=='' ? " WHERE a.petition_date  >= '".$p_to_pet_date."'::date" :
										" AND a.petition_date  <= '".$p_to_pet_date."'::date";
		$countcodn.= $countcodn=='' ? " WHERE petition_date  >= '".$p_to_pet_date."'::date" :
										" AND petition_date  <= '".$p_to_pet_date."'::date";								
	}
	if(!empty($p_petition_no)){
		$codn.= $codn=='' ? " WHERE a.petition_no  LIKE '%".$p_petition_no."%'" :
										" AND a.petition_no  LIKE '%".$p_petition_no."%'";
		$countcodn.= $countcodn=='' ? " WHERE petition_no  LIKE '%".$p_petition_no."%'" :
										" AND petition_no  LIKE '%".$p_petition_no."%'";								
	}
	if(!empty($p_source)){
		$codn.= $codn=='' ? " WHERE a.source_id=".$p_source :" AND a.source_id=".$p_source;
		$countcodn.= $countcodn=='' ? " WHERE source_id=".$p_source :" AND source_id=".$p_source;
	}
	
/* 	if(!empty($dept)){
		$codn.= $codn=='' ? " WHERE a.dept_id=".$dept :" AND a.dept_id=".$dept;
		$countcodn.= $countcodn=='' ? " WHERE dept_id=".$dept :" AND dept_id=".$dept;
	} */
	
	if(!empty($gtype)){
		$codn.= $codn=='' ? " WHERE a.griev_type_id=".$gtype :" AND a.griev_type_id=".$gtype;
		$countcodn.= $countcodn=='' ? " WHERE griev_type_id=".$gtype :" AND griev_type_id=".$gtype;
	}
	
	if(!empty($petition_type)){
		$codn.= $codn=='' ? " WHERE a.pet_type_id=".$petition_type :" AND a.pet_type_id=".$petition_type;
		$countcodn.= $countcodn=='' ? " WHERE pet_type_id=".$petition_type :" AND pet_type_id=".$petition_type;
	}

	if($userProfile->getDesig_roleid() == 5){
		$codn1=" AND pet_type_id!=4";
		$countcodn.= $countcodn=='' ? " WHERE pet_type_id!=4" :" AND pet_type_id!=4";
	}else{
		$codn1='';
	}
	
	if ($userProfile->getDesig_roleid() == 5) {		
		$inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn."   ORDER BY pet_action_id";
	} else {
		$inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id";
	}
	
	//echo $inSql;
	  
	$query = 'select * from (
		'.$inSql.'
	)petition
	WHERE petition.rownumber >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). 
	'and petition.rownumber <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))).$codn1.' order by rownumber';
  
	$result = $db->query($query);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
 
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
		echo $page->generateXMLTag('off_loc_id', $row['off_loc_id']);
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);
		echo $page->generateXMLTag('action_type_name', $row['action_type_name']);
		echo $page->generateXMLTag('first_action_remarks', $row['first_action_remarks']);
		echo $page->generateXMLTag('to_whom', $row['to_whom']);
		
		echo $page->generateXMLTag('rownum', $row['rownumber']);
		echo $page->generateXMLTag('petition_id', $row['petition_id']);
		$link_query = "select fn_chk_link_pet_status as stat from fn_chk_link_pet_status(".$row['petition_id'].")";
  
		$link_res = $db->query($link_query);
		$link_rowarr = $link_res->fetchall(PDO::FETCH_ASSOC);
		foreach($link_rowarr as $link_row)
		{
			echo $page->generateXMLTag('link_stat', $link_row['stat']);
		}
		echo $page->generateXMLTag('petition_no', $row['petition_no']);
		echo $page->generateXMLTag('petition_date', $row['pet_entdt']);
		echo $page->generateXMLTag('petitioner_name', $row['petitioner_name']);
		
		echo $page->generateXMLTag('source_name', $row['source_name']);
		echo $page->generateXMLTag('grievance', $row['grievance']);
		echo $page->generateXMLTag('griev_type_id', $row['griev_type_id']);
		echo $page->generateXMLTag('griev_type_name', $row['griev_type_name']);
		echo $page->generateXMLTag('griev_subtype_id', $row['griev_subtype_id']);
		echo $page->generateXMLTag('griev_subtype_name', $row['griev_subtype_name']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);
		echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']);
		
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		echo $page->generateXMLTag('dept_id', $row['dept_id']);
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		
		echo $page->generateXMLTag('file_no', $row['file_no']);		
		echo $page->generateXMLTag('file_date', $row['file_date']);
		
		echo $page->generateXMLTag('pet_loc_id', $row['pet_loc_id']);
		echo $page->generateXMLTag('off_level_id', $row['off_level_id']);
		echo $page->generateXMLTag('dept_off_level_pattern_id', $row['dept_off_level_pattern_id']);
		echo $page->generateXMLTag('off_level_dept_id', $row['off_level_dept_id']);
		//echo $page->generateXMLTag('pet_ext_link_id', $row['pet_ext_link_id']);
		//echo $page->generateXMLTag('pet_ext_link_no', $row['pet_ext_link_no']);
		
	}
	$action_type_code = $row['action_type_code'];
	
	$sql_count = "SELECT count(petition_id) FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) ".$countcodn;
			
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	echo "=======".$_SESSION['LOGIN_LVL']."====================".$userProfile->getPet_forward();
	if($userProfile->getPet_act_ret()){
		if ($action_type_code == 'D') {
			$actTypeCode="'A', 'R', 'T'";
		} else {
			$actTypeCode="'C', 'N', 'T','I','S'";
		}
		if($_SESSION['LOGIN_LVL']==NON_BOTTOM && $userProfile->getPet_forward()){		// Doubt $_SESSION['LOGIN_LVL']
		//if petition office and logged n users office is same, forwarded should not be included
			$actTypeCode .= ", 'F'";
		}
		
		$query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.")";
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		
		foreach($rowarray as $row)
		{
			echo $page->generateXMLTag('acttype_code', $row['action_type_code']);
			echo $page->generateXMLTag('acttype_desc', $row['action_type_name']);
		}
	}
	echo "</response>";
	 }
}
else if($mode=='p2_act_type')
{	
	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	if($_SESSION['formptoken'] != $form_tocken)
	{
		header('Location: logout.php');
		exit;
	}
	else
	{	
		echo "<response>";
		$p2_petition_id=stripQuotes(killChars($_POST["p2_petition_id"]));
		$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
		$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
		$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
		$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
		$dept_id=stripQuotes(killChars($_POST["dept_id"]));
		$p2_action_entby=stripQuotes(killChars($_POST["p2_action_entby"]));
		
		$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$p2_petition_id."";
		$res=$db->query($disp_officer_sql);
		$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowArrDisp as $rowDisp) {
			$fwd_officer = $rowDisp['l_action_entby'];
		}
		//$pet_loc_id = ($pet_loc_id == '')? 33:$pet_loc_id;
/*
Sql stetement from petition entry screen
$sql="select off_hier from vw_usr_dept_users_v_sup 
where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
and dept_desig_role_id=3 ".$condition." order by dept_user_id limit 1";
*/
		
			
	if(stripQuotes(killChars($_POST["p2_act_type_code"]))=='F')//Forward to lower office
	{
		$up_off_level_id=$userProfile->getOff_level_id();
		$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
		$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
		$up_dept_id=$userProfile->getDept_id();
		$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	
		/* if ($up_dept_off_level_pattern_id == ''){
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
		} */
		
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
	
		//dept_id should not hard coded..s hould come from Form; dept_off_level_pattern_id=".$dept_off_level_pattern_id.",
		//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
		if ($off_level_id == null && $pet_loc_id == null) {
			$up_off_level_id=$userProfile->getOff_level_id();
			$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
			$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
			$up_dept_id=$userProfile->getDept_id();
			$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
			$up_off_level_dept_id=$userProfile->getOff_level_dept_id();

			$sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal";
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
			and dept_desig_role_id in (2,3) ".$condition." order by dept_user_id limit 1";

			//echo $sql;
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
			/*
			$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
			from vw_usr_dept_users_v_sup
			where dept_id=".$dept_id.$condition." 
			and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." and off_level_id<=".$off_level_id." 
			and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] 
			and dept_user_id != ".$disposing_officer."
			order by off_level_dept_id,off_level_id,dept_desig_name";
			*/	
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
				from pet_action_first_last where petition_id=".$p2_petition_id."
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
				from pet_action_first_last where petition_id=".$p2_petition_id."
				) order by dept_user_id";
			}

		}
		
		
	}
	else if(stripQuotes(killChars($_POST["p2_act_type_code"]))=='C' 
	|| stripQuotes(killChars($_POST["p2_act_type_code"]))=='N'|| stripQuotes(killChars($_POST["p2_act_type_code"]))=='I'|| stripQuotes(killChars($_POST["p2_act_type_code"]))=='S')  
	{
		$p2_petition_id=stripQuotes(killChars($_POST["p2_petition_id"]));			
		$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
		a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
		from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$p2_action_entby."";
	}
	//echo $query; //igpdis_nrthz igpdpr_nrthz
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
else if($mode=='p2_fwd_reply_temp_save'){
	   $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	   $ip=$_SERVER['REMOTE_ADDR'];
	if($_SESSION['formptoken'] != $form_tocken)
	{
	   header('Location: logout.php');
	   exit;
	}
	else
	{
		$petActSnoArr = stripQuotes(killChars($_POST["p2_pet_act_sno"]));
		$petSnoArr = stripQuotes(killChars($_POST["p2_pet_sno"]));
		$actTypeArr = stripQuotes(killChars($_POST["p2_act_type_code"]));
		$fwdUrReplyArr = stripQuotes(killChars($_POST["p2_fwd_ur_reply"]));
		$file_noArr = stripQuotes(killChars($_POST["p2_file_no"]));
		$file_dateArr = stripQuotes(killChars($_POST["p2_file_date"]));
		$remarkArr = stripQuotes(killChars($_POST["p2_remark"]));
		
		$firCsrArr = stripQuotes(killChars($_POST["p2_fir_no"]));
		$p2_fir_year = stripQuotes(killChars($_POST["p2_fir_year"]));
		$firCsrcircle = stripQuotes(killChars($_POST["p2_fir_circle"]));
		$firCsrdist = stripQuotes(killChars($_POST["p2_fir_csr_dist"]));
		if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 
		||  $userProfile->getOff_level_id()==11 ||  $userProfile->getOff_level_id()==13 
		||  $userProfile->getOff_level_id()==42 ||  $userProfile->getOff_level_id()==44
		||  $userProfile->getOff_level_id()==46) {
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)');
		
	$action_ent=$dept_user_id;
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address,data_entby) VALUES (?, ?, ?, ?, ?, ?, current_timestamp, ?, ?,?)');
	 
	//Forward petitions
		$i=0;$f_count=0;$n_count=0;$c_count=0;$s_count=0;$i_count=0;$t_count=0;$fc_count=0;//echo "11111111>>>>";print_r($petSnoArr);exit;
	foreach($petSnoArr as $petSno) {
		$f_dt=explode('/',$file_dateArr[$i]);
		$day=$f_dt[0];
		$mnth=$f_dt[1];
		$yr=$f_dt[2];
    	$file_date=$yr.'-'.$day.'-'.$mnth;
		
		$today = $page->currentTimeStamp();
		$array = array($petSno, $actTypeArr[$i], ($file_noArr[$i])? $file_noArr[$i] : NULL , 
		($file_dateArr[$i])?$file_date: NULL, 
		($remarkArr[$i])? $remarkArr[$i] : NULL, $action_ent,( empty($fwdUrReplyArr[$i])?null:$fwdUrReplyArr[$i]),$ip, $_SESSION['USER_ID_PK']);
		if($query->execute($array)>0){
			$count++;//no. of petition processed.
			if($actTypeArr[$i]=='F'){
				$f_count++;
			}
			else if($actTypeArr[$i]=='N'){
				$n_count++;
				//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
				//$result = $db->query($sql);
			}
			else if($actTypeArr[$i]=='T'){
				$t_count++;
			}
			else if($actTypeArr[$i]=='C'){
				$c_count++;				
				
				//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
				//$result = $db->query($sql);
			}else if($actTypeArr[$i]=='I'){
				$i_count++;
				$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=1";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",1,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p2_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=1,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i]." ,fir_csr_year='".$p2_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=1";
					$result = $db->query($sql);
				}
				//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
				//$result = $db->query($sql);
			}else if($actTypeArr[$i]=='S'){
				$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=2";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",2,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p2_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=2,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i].", fir_csr_year='".$p2_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=2";
					$result = $db->query($sql);
				}
				$s_count++;
				//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
				//$result = $db->query($sql);
			}
		} else {
			$fc_count++;
		}
		
		$i++;
	}
	echo "<response>";
	if($count>0){
		//msg
		echo '<tot>Processed no. of Petition(s): '.$count.'</tot>
				<f>Forwarded : '.$f_count.'</f>
				<n>Unrelated so Returned : '.$n_count.'</n>
				<c>Action Taken : '.$c_count.'</c>
				<ir>Action Taken with FIR : '.$i_count.'</ir>
				<s>Action Taken with CSR : '.$s_count.'</s>
				<t>Temporary Reply : '.$t_count.'</t>
					<fc>Fail Count : '.$fc_count.'</fc>';
		echo '<status>S</status>';
	}
	else{
		echo '<msg>Petition(s) forwarding failed!!!</msg>
					<fc>Fail Count : '.$fc_count.'</fc>';
		echo '<status>F</status>';
	}
	echo "</response>";
	 }
   }
}
?>
