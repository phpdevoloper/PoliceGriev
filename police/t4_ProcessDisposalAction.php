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
 
if($mode=='p5_search'){
   
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
	if($userProfile->getDesig_roleid() == 5){
		$codn1=" AND pet_type_id=!4";
		$countcond.= " AND pet_type_id!=4";
	}else{
		$codn1="";
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
	}
	 */
	  
	
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
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_cc."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row[dept_user_id];
		}
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
			$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
		} else {
			$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
		}
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	
	$codnExOwnOffPet = "";
	//if logged in user is boss of the office then Exclude own office pettion 
	if($userProfile->getPet_disposal()){

		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$_SESSION['USER_ID_PK'].") = TRUE";

	} else if ($userProfile->getDesig_roleid() == 5) {
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = TRUE";
	}
	$inSql="SELECT row_number() OVER (ORDER BY cc.pet_action_id) as rownum ,dd.lnk_docs,cc.* from
	(SELECT a.pet_action_id, a.fwd_remarks, a.petition_id, a.action_entby, a.action_type_code, 
	a.action_type_name, a.to_whom, a.petition_no,a.petition_date, a.pet_entdt,
	a.off_location_design, a.off_loc_id, a.pet_entby, a.fwd_date,a.source_id, a.source_name, a.griev_type_id, 
	a.griev_type_name, a.griev_subtype_id, a.griev_subtype_name,a.dept_id,a.griev_district_id,a.off_loc_id, a.grievance,a.pet_type_id,a.canid, a.pet_address, a.gri_address,a.file_no,a.file_date,a.pet_type_name,
	a.comm_mobile,a.comm_email,first_action_remarks,
	a.pet_loc_id, a.off_level_id, a.dept_off_level_pattern_id, a.off_level_dept_id
	-- last actions on petitions with given action_type_codes and addressed to us
	FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a 
	--inner join pet_master b on b.petition_id=a.petition_id
	".$codnExOwnOffPet.$pet_entby_cond.")cc".$codn." LEFT JOIN 
	(select b.petition_id, string_agg(pet_ext_link_name || ': '||pet_ext_link_no,', ' order by b.petition_id,b.pet_master_ext_link_id) as lnk_docs 
	FROM pet_master_ext_link b 
	LEFT JOIN lkp_pet_ext_link_type c on c.pet_ext_link_id=b.pet_ext_link_id
	group by b.petition_id) dd on dd.petition_id=cc.petition_id";
	 

	//echo $inSql;
	$listquery = 'SELECT petition.* FROM ('.$inSql.')petition 
	WHERE petition.rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))).' and petition.rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	
	//$llquery = $listquery;
	$result = $db->query($listquery);	
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
		echo $page->generateXMLTag('dept_id', $row['dept_id']);
		echo $page->generateXMLTag('griev_district_id', $row['griev_district_id']);
		echo $page->generateXMLTag('off_loc_id', $row['off_loc_id']);
		echo $page->generateXMLTag('pet_type_name', $row['pet_type_name']);
		/* echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']); */
		
		echo $page->generateXMLTag('pet_address', $row['pet_address']);
		echo $page->generateXMLTag('gri_address', $row['gri_address']);
		echo $page->generateXMLTag('file_no', $row['file_no']);
		echo $page->generateXMLTag('file_date', $row['file_date']);
		echo $page->generateXMLTag('comm_mobile', $row['comm_mobile']);		
		echo $page->generateXMLTag('comm_email', $row['comm_email']);		
		//echo $page->generateXMLTag('source_id', $row['source_id']);	

		echo $page->generateXMLTag('pet_loc_id', $row['pet_loc_id']);
		echo $page->generateXMLTag('off_level_id', $row['off_level_id']);
		echo $page->generateXMLTag('dept_off_level_pattern_id', $row['dept_off_level_pattern_id']);
		echo $page->generateXMLTag('off_level_dept_id', $row['off_level_dept_id']);		
		echo $page->generateXMLTag('lnk_docs', $row['lnk_docs']);		
		
		$actTypeCode="";
		
		if($row['action_type_code']=='N'){
			if($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_disposal()){
				$actTypeCode .= "'A', 'R'";
			}
			if($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_forward()){
				$actTypeCode .= $actTypeCode==""? "'F'": ", 'F'";
			}
		}
		else{
			if($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_disposal()){
				$actTypeCode .= "'A', 'R', 'F', 'Q'";
			}
		}
		if($userProfile->getDesig_roleid()==5){
			$actTypeCode .= "'A', 'R', 'F', 'Q'";
		}
		if($actTypeCode!=""){
			$query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.")";
			$result = $db->query($query);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			//print_r($rowarray);
			foreach($rowarray as $row1)
			{
				echo $page->generateXMLTag('acttype_code_'.$row['petition_id'], $row1['action_type_code']);
				echo $page->generateXMLTag('acttype_desc_'.$row['petition_id'], $row1['action_type_name']);
			}
		}
	}
	
	$sql_count ="SELECT count(a.pet_action_id)
	FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a".$codnExOwnOffPet.$pet_entby_cond.$countcond;
	

	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation

	echo "</response>";
	}
}
//Further Action Required then forwarded to lower office
else if($mode=='p5_act_type'){

	$form_tocken=stripQuotes(killChars($_POST["form_tocken"]));	 
	$off_loc_id=stripQuotes(killChars($_POST["p5_off_loc_id"]));
	$griev_district_id=stripQuotes(killChars($_POST["p5_griev_district_id"]));	
	$district_id=$userProfile->getDistrict_id();
	$off_level_id=$userProfile->getOff_level_id();
	$district=stripQuotes(killChars($_POST["district"])); 
	$p5_action_taken_by=stripQuotes(killChars($_POST["p5_action_taken_by"]));
	$pet_id=stripQuotes(killChars($_POST["p5_petition_id"])); 
	$dept_id=stripQuotes(killChars($_POST["p5_dept_id"]));
	
	$get_pet_off = 'select -99 as pet_off_loc_id'; 
	$griv_loc_off_level_id=$userProfile->getOff_level_id();	
	
	$pet_loc_id=stripQuotes(killChars($_POST["pet_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	
	$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$pet_id."";
	$res=$db->query($disp_officer_sql);
	$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
	foreach($rowArrDisp as $rowDisp) {
		$fwd_officer = $rowDisp['l_action_entby'];
	}
	
	if($_SESSION['formptoken'] != $form_tocken)
	{
	   header('Location: logout.php');
	   exit; 
	}
	else
	{
		echo "<response>";	
	
	if(stripQuotes(killChars($_POST["p5_act_type_code"]))=='Q'){
		$query = "SELECT dept_user_id, off_loc_name ||' / '|| dept_desig_name AS off_location_design,
		off_loc_tname ||' / '|| dept_desig_tname AS off_location_tdesign
		FROM vw_usr_dept_users_v_sup
		WHERE dept_user_id=".stripQuotes(killChars($_POST["p5_action_taken_by"]));
	}
	else if(stripQuotes(killChars($_POST["p5_act_type_code"]))=='F')
	{
			
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
			/* $query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
			from vw_usr_dept_users_v_sup a1
			where dept_id=".$dept_id.$condition."
			and dept_desig_role_id in (2,3)
			and (off_level_id>=".$userProfile->getOff_level_id()." or off_level_id<=".$off_level_id.")
			and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
			and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id!=".$fwd_officer."
			and dept_user_id not in 
			(
			select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
			".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
			from pet_action_first_last where petition_id=".$pet_id."
			) order by dept_user_id"; */

			$query="select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign,off_level_dept_id,off_level_dept_name
			from vw_usr_dept_users_v_sup a1
			where dept_id=".$dept_id.$condition."
			and dept_desig_role_id in (2,3)
			and (off_level_id>=".$userProfile->getOff_level_id()." or off_level_id<=".$off_level_id.")
			and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
			and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id not in 
			(
			select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
			".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
			from pet_action_first_last where petition_id=".$pet_id."
			) and dept_off_level_pattern_id=$dept_off_level_pattern_id order by off_level_dept_id";
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
				echo $page->generateXMLTag('off_location_design', $row['off_location_design']);	
			}
			echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
			echo "<off_level_name>".$row['off_level_dept_name']."</off_level_name>";
		}
	
		
	}
	
	
	//echo $query;
	
		
	echo "</response>";
 }

else if($mode=='p5_disposal_save'){
	 
	 $form_tocken=stripQuotes(killChars($_POST["form_tocken"]));
	 $ip=$_SERVER['REMOTE_ADDR']; 
	  if($_SESSION['formptoken'] != $form_tocken)
	  {
		   header('Location: logout.php');
		   exit;
	  }
	 else{
	$petActSnoArr = stripQuotes(killChars($_POST["p5_pet_act_sno"]));
	$petSnoArr = stripQuotes(killChars($_POST["p5_pet_sno"]));
	$actTypeArr = stripQuotes(killChars($_POST["p5_act_type_code"]));
	$fwdUrReplyArr = stripQuotes(killChars($_POST["p5_fwd_ur_reply"]));
	$file_noArr = stripQuotes(killChars($_POST["p5_file_no"]));
	$file_dateArr = stripQuotes(killChars($_POST["p5_file_date"]));
	$remarkArr = stripQuotes(killChars($_POST["p5_remark"]));
	
	$commmobileArr = stripQuotes(killChars($_POST["p5_comm_mobile"]));
	$commEmailArr = stripQuotes(killChars($_POST["p5_comm_email"]));
	$petSourceArr = stripQuotes(killChars($_POST["p5_source_id"]));
	$petitionnoArr = stripQuotes(killChars($_POST["p5_pet_no"]));
	
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
	
	if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 
	|| $userProfile->getOff_level_id()==11 || $userProfile->getOff_level_id()==13 
	|| $userProfile->getOff_level_id()==42 || $userProfile->getOff_level_id()==44
	|| $userProfile->getOff_level_id()==46) {
	  $query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address) VALUES (?, ?, ?, ?, ?, ?, current_timestamp, ?, ?)');
	  $query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code, file_no, file_date, action_remarks, action_entby, action_entdt, to_whom,action_ip_address,data_entby) VALUES (?, ?, ?, ?, ?, ?, current_timestamp, ?, ?,?)');
											 
	$i=0;$f_count=0;$n_count=0;$c_count=0;$i_count=0;$s_count=0;$t_count=0;$q_count=0;$a_count=0;$r_count=0;$e_count=0;$zz=0;$fc_count=0;
	$falg=false;
	$pet_id_array = array();
	$pet_act_array = array();
	$pet_mob_array = array();
	$pet_no_array = array();
	$no_of_rec = 0;
	foreach($petSnoArr as $petSno) {
		$no_of_rec++;
		$today = $page->currentTimeStamp();

		$f_dt=explode('/',$file_dateArr[$i]);
		$day=$f_dt[0];
		$mnth=$f_dt[1];
		$yr=$f_dt[2];
    	$file_date=$yr.'-'.$day.'-'.$mnth;
		
		
		$array = array($petSno, $actTypeArr[$i], ($file_noArr[$i])? $file_noArr[$i] : NULL , 
		($file_dateArr[$i])? $file_date : NULL, 
		($remarkArr[$i])? $remarkArr[$i] : NULL, $action_ent, (empty($fwdUrReplyArr[$i])?null:$fwdUrReplyArr[$i]),$ip,$_SESSION['USER_ID_PK']);
		// For after save msg display
		//print_r($commEmailArr);
		$response='';
		

		
		if ($response == '') {
			if($query->execute($array)>0){
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
						break;
					case 'S':
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
		}
		$i++;
		$falg=false;
		$zz++;
			
		
		
	}
	  $string = rtrim(implode(',', $pet_id_array), ',');
	  //echo $string;
	  if ($string != "") {
			$sql = "select petition_id,petition_no,comm_mobile,comm_email,source_id from pet_master where petition_id in  (".$string.") ";
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			$arr_pos=0;
			foreach($rowarray as $row){
				$pet_mob_array[$arr_pos] = $row['comm_mobile'];
				$pet_source_array[$arr_pos] = $row['source_id'];
				$pet_email_array[$arr_pos] = $row['comm_email'];
				$pet_no_array[$arr_pos] = $row['petition_no'];
				$arr_pos++;
			}
			//print_r($pet_email_array);
			//print_r($pet_no_array);
			//print_r(pet_no_array);
			//print_r(pet_act_array);
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
				$pet_source = $pet_source_array[$val];
				
				if ($pet_source == -3) {
					smtpmailer($email, $from, $name, $subj, $message);
				} else {
					if ($mobile_no!="" && ((int) substr($mobile_no, 0, 1) >=6 && (int) substr($mobile_no, 0, 1) <=9)) {
						 //SMS($mobile_no,$message,$ucode,$ct_id);
					}
				}
						
			}
	  }
	  
			
	   echo "<response>".$count;
		if($count>0){			//msg
			
			echo '<tot>Total no. of Petition(s) '.$count.'</tot>
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
			
	
	else {
		if ($no_of_rec == 1 && $response != '') {
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
		} else {
			echo '<msg>Petition(s) process failed!!!</msg>
					<fc>Fail Count : '.$fc_count.'</fc>';
			echo '<status>F</status>';
		}
	}
	echo "</response>";
	
			
   }
  }
}
?>
