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
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_cc."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
			$dept_user_id =  $_SESSION['USER_ID_PK'];
		} 
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
if($mode=='p3_search'){
	
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
	$ptype=stripQuotes(killChars($_POST["ptype"]));
	/* $conc_off=stripQuotes(killChars($_POST["conc_off"])); */
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
	 
	$codn='';
	$countcond='';
	if(!empty($p_from_pet_date)){
		$codn.= $codn=='' ? " WHERE cc.petition_date >= '".$p_from_pet_date."'::date" :
										" AND cc.petition_date >= '".$p_from_pet_date."'::date";
		$countcond.= $countcond=='' ? " and a.petition_date >= '".$p_from_pet_date."'::date" :
										" AND a.petition_date >= '".$p_from_pet_date."'::date";										
	}
	if(!empty($p_to_pet_date)){
		$codn.= $codn=='' ? " WHERE cc.petition_date >= '".$p_to_pet_date."'::date" :
										" AND cc.petition_date <= '".$p_to_pet_date."'::date";
		$countcond.= $countcond=='' ? " and a.petition_date >= '".$p_to_pet_date."'::date" :
										" AND a.petition_date <= '".$p_to_pet_date."'::date";									
	}
	if(!empty($p_petition_no)){
		$codn.= $codn=='' ? " WHERE cc.petition_no  LIKE '%".$p_petition_no."%'" :
										" AND cc.petition_no  LIKE '%".$p_petition_no."%'";
		$countcond.= $countcond=='' ? " and a.petition_no  LIKE '%".$p_petition_no."%'" :
										" AND a.petition_no  LIKE '%".$p_petition_no."%'";										
	}
	if(!empty($p_source)){
		$codn.= $codn=='' ? " WHERE cc.source_id=".$p_source :" AND cc.source_id=".$p_source;
		$countcond.= $countcond=='' ? " and a.source_id=".$p_source :" AND a.source_id=".$p_source;
	}
	
	/* if(!empty($dept)){
		$codn.= $codn=='' ? " WHERE cc.dept_id=".$dept :" AND cc.dept_id=".$dept;
		$countcond.= $countcond=='' ? " and a.dept_id=".$dept :" AND a.dept_id=".$dept;
	} */
	
	if(!empty($gtype)){
		$codn.= $codn=='' ? " WHERE cc.griev_type_id=".$gtype :" AND cc.griev_type_id=".$gtype;
		$countcond.= $countcond=='' ? " and a.griev_type_id=".$gtype :" AND a.griev_type_id=".$gtype;
	}
	
	if(!empty($ptype)){
		$codn.= $codn=='' ? " WHERE cc.pet_type_id=".$ptype :" AND cc.pet_type_id=".$ptype;
		$countcond.= $countcond=='' ? " and a.pet_type_id=".$ptype :" AND a.pet_type_id=".$ptype;
	}
	/* if(!empty($conc_off)){
		$codn.= $codn=='' ? " WHERE cc.action_entby=".$conc_off :" AND cc.action_entby=".$conc_off;
		$countcond.= $countcond=='' ? " and a.action_entby=".$conc_off :" AND a.action_entby=".$conc_off;
	} */
	if($userProfile->getDesig_roleid() == 5){
		$codn1=" AND pet_type_id!=4";
		$countcodn1=" AND pet_type_id!=4";
	}else{
		$codn1="";
	}
	//$codnExOwnOffPet = "";
	//if logged in user is boss of the office then Exclude own office pettion 
	
	if($userProfile->getPet_disposal()){
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$_SESSION['USER_ID_PK'].") = FALSE";
	}
	
	$inSql="SELECT row_number() OVER (ORDER BY cc.pet_action_id) as rownum,* from 
	(SELECT a.pet_action_id, a.fwd_remarks, a.petition_id, a.action_entby, a.action_type_code, a.action_type_name, a.to_whom, a.petition_no, 
	a.petition_date, a.pet_entdt, a.off_location_design, a.off_loc_id, a.pet_entby, a.fwd_date, a.source_id, a.source_name, a.griev_type_id, a.griev_type_name, a.griev_subtype_id, a.griev_subtype_name,a.dept_id, a.griev_district_id,a.grievance, a.canid, a.pet_address, a.gri_address,a.file_date,a.file_no,a.pet_type_id,a.pet_type_name,a.comm_mobile,
	a.pet_loc_id, a.off_level_id, a.dept_off_level_pattern_id, a.off_level_dept_id,first_action_remarks
	--,pet_ext_link_id,pet_ext_link_no
	-- last actions on petitions with given action_type_codes and addressed to us
	FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a ".$codnExOwnOffPet.")cc".$codn; 

	$query = 'SELECT * FROM (
	'.$inSql.'
	)petition
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). 'and petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))).$codn1;

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
		
		
		echo $page->generateXMLTag('file_date', $row['file_date']);
		echo $page->generateXMLTag('file_no', $row['file_no']);

		echo $page->generateXMLTag('pet_loc_id', $row['pet_loc_id']);
		echo $page->generateXMLTag('off_level_id', $row['off_level_id']);
		echo $page->generateXMLTag('dept_off_level_pattern_id', $row['dept_off_level_pattern_id']);
		echo $page->generateXMLTag('off_level_dept_id', $row['off_level_dept_id']);
		
	//	echo $page->generateXMLTag('pet_ext_link_id', $row[pet_ext_link_id]);
		//echo $page->generateXMLTag('pet_ext_link_no', $row[pet_ext_link_no]);
				
		$actTypeCode="";
		if($row['action_type_code']=='N'){
			$actTypeCode="'N'";
			if($_SESSION['LOGIN_LVL']==NON_BOTTOM && $userProfile->getPet_forward()){
					$actTypeCode .= ", 'F', 'C', 'I', 'S'";
			}
		}
		else{
			$actTypeCode .= "'C','I','S', 'E', 'T'";
			if($_SESSION['LOGIN_LVL']==NON_BOTTOM && $userProfile->getPet_forward()){
				$actTypeCode .= ", 'Q','F'";
			}
		}
		if($actTypeCode!=""){
			 echo $query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.")";
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
		$sql_count = "SELECT count(a.pet_action_id)
FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a".$codnExOwnOffPet.$countcond.$countcodn1;
}else{
	$sql_count = "SELECT count(a.pet_action_id)
FROM fn_Petition_Action_Taken(".$_SESSION['USER_ID_PK'].",array['C','E','N','I','S']) a".$codnExOwnOffPet.$countcond;
}

	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	
	echo "</response>";
  }
}
else if($mode=='p3_act_type'){
	
 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
 $p3_act_type_code = stripQuotes(killChars($_POST["p3_act_type_code"]));
 if($_SESSION['formptoken'] != $form_tocken)
  {
	   header('Location: logout.php');
	   exit;
  }
 else{
	echo "<response>";
	
	$griev_sub_type_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
	$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));	
	$p3_action_entby = stripQuotes(killChars($_POST['p3_action_entby']));	
	$p3_petition_id=stripQuotes(killChars($_POST["p3_petition_id"]));
	$p3_griev_district_id=stripQuotes(killChars($_POST["p3_griev_district_id"]));
	$dept_id=stripQuotes(killChars($_POST["dept_id"]));
	
	$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	
	$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$p3_petition_id."";
	$res=$db->query($disp_officer_sql);
	$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
	foreach($rowArrDisp as $rowDisp) {
		$fwd_officer = $rowDisp[l_action_entby];
	}
		
	if($p3_act_type_code == 'Q') {
		
	    $query = "SELECT dept_user_id, off_loc_name ||' / '|| dept_desig_name AS off_location_design
			FROM vw_usr_dept_users_v_sup
			WHERE dept_user_id=".stripQuotes(killChars($_POST["p3_action_taken_by"]));	
	} else if($p3_act_type_code == 'F') {
/*
Sql stetement from petition entry screen
$sql="select off_hier from vw_usr_dept_users_v_sup 
where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id." and 
off_level_id=".$off_level_id." and off_loc_id=".$petition_office_loc_id." 
and dept_desig_role_id=3 ".$condition." order by dept_user_id limit 1";
*/
		//User Profile
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
		if ($off_level_id == null && $pet_loc_id == null) {
			

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
			/* if ($up_dept_off_level_pattern_id == 'null'){
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

			//dept_id should not hard coded..s hould come from Form; dept_off_level_pattern_id=".$dept_off_level_pattern_id.",
			//$dept_off_level_pattern_id will be null for head office petitions and therefore to be dealt with like $condition in the petition entry screen. To be done in all the five tabs.
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
/* 			$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
			from vw_usr_dept_users_v_sup a1
			where dept_id=".$dept_id.$condition." 
			and dept_desig_role_id in (2,3) 
			--and(off_level_id>=".$userProfile->getOff_level_id()." or off_level_id<=".$off_level_id.")
			and (off_level_id>=".$userProfile->getOff_level_id().")
			and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
			and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id!=".$fwd_officer."
			and dept_user_id not in 
			(
			select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
			".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
			from pet_action_first_last where petition_id=".$p3_petition_id."
			) order by dept_user_id"; */

			$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
			from vw_usr_dept_users_v_sup a1
			where dept_id=".$dept_id.$condition." 
			and dept_desig_role_id in (2,3) 
			--and(off_level_id>=".$userProfile->getOff_level_id()." or off_level_id<=".$off_level_id.")
			and (off_level_id>=".$userProfile->getOff_level_id().")
			and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
			and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id not in 
			(
			select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
			".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
			from pet_action_first_last where petition_id=".$p3_petition_id."
			) order by dept_user_id";
		}
		
	}
	else 
	{

		$p3_petition_id=stripQuotes(killChars($_POST["p3_petition_id"]));
		$p3_action_entby = stripQuotes(killChars($_POST['p3_action_entby']));
		//chk petition owner office location 		
		$chkPetOwnQuery= "SELECT fn_pet_origin_same_from_office(".$p3_petition_id.",".$_SESSION['USER_ID_PK'].")";
			
		$result = $db->query($chkPetOwnQuery);
		$rowArr = $result->fetchall(PDO::FETCH_ASSOC);
		$pet_owner='';
		foreach($rowArr as $row){
			$pet_owner=$row['fn_pet_origin_same_from_office'];
		}		
		
		$codn = '';
		if($pet_owner){//i.e., if the origin (office) of the petition is same as the last action-on-petition-taken-office
			$codn = " AND pet_disposal";
		}

		$query="SELECT dept_user_id, off_loc_name||'/ '||dept_desig_name AS off_location_design, off_loc_tname ||'/ '||dept_desig_tname AS off_location_tdesign--,off_level_dept_id,off_level_dept_name 
		FROM vw_usr_dept_users_desig WHERE dept_user_id in
		(SELECT aa.action_entby FROM
		(SELECT petition_id, pet_action_id, action_type_code, action_entby, to_whom, action_entdt,
		cast (rank() OVER (PARTITION BY petition_id, to_whom ORDER BY pet_action_id DESC)as integer) rnk
		FROM pet_action where petition_id=".$p3_petition_id." and action_type_code in ('F','Q') and to_whom=".$dept_user_id.") aa
		WHERE aa.rnk=1)";
		  		
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
else if($mode=='p3_fwd_reply_temp_save'){
	
$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
$ip=$_SERVER['REMOTE_ADDR'];
if($_SESSION['formptoken'] != $form_tocken)
	  {
		   header('Location: logout.php');
		   exit;
		     
	  }
	 else{

	$petActSnoArr = stripQuotes(killChars($_POST["p3_pet_act_sno"]));
	$petSnoArr = stripQuotes(killChars($_POST["p3_pet_sno"]));
	$actTypeArr = stripQuotes(killChars($_POST["p3_act_type_code"]));
	$fwdUrReplyArr = stripQuotes(killChars($_POST["p3_fwd_ur_reply"]));
	$file_noArr = stripQuotes(killChars($_POST["p3_file_no"]));
	$file_dateArr = stripQuotes(killChars($_POST["p3_file_date"]));
	$remarkArr = stripQuotes(killChars($_POST["p3_remark"]));
	$firCsrArr = stripQuotes(killChars($_POST["p3_fir_no"]));
	$p3_fir_year = stripQuotes(killChars($_POST["p3_fir_year"]));
	$firCsrcircle = stripQuotes(killChars($_POST["p3_fir_circle"]));
	$firCsrdist = stripQuotes(killChars($_POST["p3_fir_csr_dist"]));
	//print_r($fwdUrReplyArr); 
	if( $userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 ||  $userProfile->getOff_level_id()==11 ||  $userProfile->getOff_level_id()==13 || $userProfile->getOff_level_id()==42 ||  $userProfile->getOff_level_id()==44 ) {
	  $query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)');
	  
	  $query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address,data_entby) VALUES (?, ?, ?, ?, ?, ?, current_timestamp, ?,?,?)');
							 
											 
	$i=0;$f_count=0;$n_count=0;$c_count=0;$t_count=0;$i_count=0;$s_count=0;$q_count=0;$a_count=0;$r_count=0;$e_count=0;$fc_count=0;
	$falg=false;
	foreach($petSnoArr as $petSno) {
		
		$f_dt=explode('/',$file_dateArr[$i]);
		$day=$f_dt[0];
		$mnth=$f_dt[1];
		$yr=$f_dt[2];
    	$file_date=$yr.'-'.$day.'-'.$mnth;
		
		$today = $page->currentTimeStamp();
		//echo "====>>".$today;
		$array = array($petSno, $actTypeArr[$i], ($file_noArr[$i])? $file_noArr[$i] : NULL , 
		($file_dateArr[$i])?$file_date: NULL, ($remarkArr[$i])? $remarkArr[$i] : NULL, $dept_user_id, 
		( empty($fwdUrReplyArr[$i])? NULL : $fwdUrReplyArr[$i]),$ip,$_SESSION['USER_ID_PK']);
		
		if($query->execute($array)>0){
			$count++;//no. of petition processed.
			switch($actTypeArr[$i]){
				case 'F':
					$f_count++;
					break;
				case 'N':
					$n_count++;
					//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
					//$result = $db->query($sql);
					break;
				case 'T':
					$t_count++;
					break;
				case 'I':
					$i_count++;
				$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=1";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",1,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p3_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=1,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i]." ,fir_csr_year='".$p3_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=1";
					$result = $db->query($sql);
				}
				
					break;
				case 'S':
					$sql="select * from pet_master_ext_link where petition_id=".$petSno." and pet_ext_link_id=2";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="insert into pet_master_ext_link(petition_id,pet_ext_link_id,pet_ext_link_no,lnk_entby,district_id,circle_id,fir_csr_year) values(".$petSno.",2,".$firCsrArr[$i].",".$_SESSION['USER_ID_PK'].",".$firCsrdist[$i].",".$firCsrcircle[$i].",'".$p3_fir_year[$i]."')";
					$result = $db->query($sql);
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=2,pet_ext_link_no=".$firCsrArr[$i].", lnk_modby=".$_SESSION['USER_ID_PK'].", district_id=".$firCsrdist[$i].",circle_id=".$firCsrcircle[$i].", fir_csr_year='".$p3_fir_year[$i]."' where petition_id=".$petSno." and pet_ext_link_id=2";
					$result = $db->query($sql);
				}
					$s_count++;
					break;
				case 'C':
					$c_count++;
					//$sql="UPDATE pet_action_first_last set forwarding_officers = array_remove(forwarding_officers, ".$_SESSION['USER_ID_PK'].") where petition_id=".$petSno."";
					//$result = $db->query($sql);
					break;
				case 'Q':
					$q_count++;
					break;
				case 'A':
					$a_count++;
					break;
				case 'R':
					$r_count++;
					break;
				case 'E':
					$e_count++;
					break;
			}
		} else {
			$fc_count++;
		}
		$i++;
		$falg=false;
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
				<q>Further Action Required : '.$q_count.'</q>
				<a>Accepted : '.$a_count.'</a>
				<r>Rejected : '.$r_count.'</r>
				<e>Endorse the Reply : '.$e_count.'</e>
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
