<?PHP
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("UserProfile.php");
include("Pagination.php");
include("common_date_fun.php");
 
$userProfile = unserialize($_SESSION['USER_PROFILE']);
  
$mode=$_POST["mode"];

if($mode=='get_pet_for_mobile') {   
	$mobile_number = stripQuotes(killChars($_POST['mobile_number']));
	$sql = "select count(comm_mobile) as count from pet_master where comm_mobile = '".$mobile_number."'";

	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{   
		echo "<count>".$row['count']."</count>";
	}
	echo "</response>";
} else if($mode=='get_petition_details') {   
	$petition_id = stripQuotes(killChars($_POST['petition_id']));
	$sql="SELECT petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, comm_doorno, comm_aptmt_block, comm_street, comm_area,comm_district_id, comm_district_name, comm_taluk_id, comm_taluk_name, comm_rev_village_id, comm_rev_village_name, coalesce(comm_pincode,griev_pincode) as comm_pincode, comm_mobile, org_petition_no,  dept_name, pet_type_name,gender_id,idtype_id,id_no,comm_email,petitioner_category_id, pet_community_id,grievance,pet_type_id,griev_type_id,griev_subtype_id,coalesce(org_petition_no,petition_no) as pet_no FROM vw_pet_master where petition_id='".$petition_id."'";

	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{   
		echo "<petitioner_initial>".$row['petitioner_initial']."</petitioner_initial>";
		echo "<petitioner_name>".$row['petitioner_name']."</petitioner_name>";
		echo "<father_husband_name>".$row['father_husband_name']."</father_husband_name>";
		echo "<gender_id>".$row['gender_id']."</gender_id>";
		echo "<comm_doorno>".$row['comm_doorno']."</comm_doorno>";
		echo "<comm_street>".$row['comm_street']."</comm_street>";
		echo "<comm_area>".$row['comm_area']."</comm_area>";
		echo "<comm_pincode>".$row['comm_pincode']."</comm_pincode>";
		echo "<comm_dist>".$row['comm_district_id']."</comm_dist>";
		echo "<comm_taluk_id>".$row['comm_taluk_id']."</comm_taluk_id>";
		echo "<comm_rev_village_id>".$row['comm_rev_village_id']."</comm_rev_village_id>";
		echo "<idtype_id>".$row['idtype_id']."</idtype_id>";
		echo "<id_no>".$row['id_no']."</id_no>";
		echo "<comm_email>".$row['comm_email']."</comm_email>";
		echo "<petitioner_category_id>".$row['petitioner_category_id']."</petitioner_category_id>";
		echo "<pet_community_id>".$row['pet_community_id']."</pet_community_id>";
		echo "<grievance>".$row['grievance']."</grievance>";
		echo "<pet_type_id>".$row['pet_type_id']."</pet_type_id>";
		echo "<griev_type_id>".$row['griev_type_id']."</griev_type_id>";
		echo "<griev_subtype_id>".$row['griev_subtype_id']."</griev_subtype_id>";
		echo "<pet_no>".$row['pet_no']."</pet_no>";
	}
	echo "</response>";
} else if($mode=='get_petitioner_details') {   
	$comm_mobile = stripQuotes(killChars($_POST['mobile_number']));
	$sql="SELECT petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, comm_doorno, comm_aptmt_block, comm_street, comm_area,comm_district_id, comm_district_name, comm_taluk_id, comm_taluk_name, comm_rev_village_id, comm_rev_village_name, coalesce(comm_pincode,griev_pincode) as comm_pincode, comm_mobile, org_petition_no,  dept_name, pet_type_name,gender_id,idtype_id,id_no,comm_email,petitioner_category_id, pet_community_id,pet_type_id FROM vw_pet_master where comm_mobile='".$comm_mobile."'  order by petition_id asc limit 1";

	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{   
		echo "<petitioner_initial>".$row['petitioner_initial']."</petitioner_initial>";
		echo "<petitioner_name>".$row['petitioner_name']."</petitioner_name>";
		echo "<father_husband_name>".$row['father_husband_name']."</father_husband_name>";
		echo "<gender_id>".$row['gender_id']."</gender_id>";
		echo "<comm_doorno>".$row['comm_doorno']."</comm_doorno>";
		echo "<comm_street>".$row['comm_street']."</comm_street>";
		echo "<comm_area>".$row['comm_area']."</comm_area>";
		echo "<comm_pincode>".$row['comm_pincode']."</comm_pincode>";
		echo "<comm_dist>".$row['comm_district_id']."</comm_dist>";
		echo "<comm_taluk_id>".$row['comm_taluk_id']."</comm_taluk_id>";
		echo "<comm_rev_village_id>".$row['comm_rev_village_id']."</comm_rev_village_id>";
		echo "<idtype_id>".$row['idtype_id']."</idtype_id>";
		echo "<id_no>".$row['id_no']."</id_no>";
		echo "<comm_email>".$row['comm_email']."</comm_email>";
		echo "<petitioner_category_id>".$row['petitioner_category_id']."</petitioner_category_id>";
		echo "<pet_community_id>".$row['pet_community_id']."</pet_community_id>";
/* 		echo "<grievance>".$row['grievance']."</grievance>";
		echo "<pet_type_id>".$row['pet_type_id']."</pet_type_id>";
		echo "<griev_type_id>".$row['griev_type_id']."</griev_type_id>";
		echo "<griev_subtype_id>".$row['griev_subtype_id']."</griev_subtype_id>";
		echo "<pet_no>".$row['pet_no']."</pet_no>"; */
	}
	echo "</response>";
}else if($mode=='get_pattern_id') {   
	$dept_id = stripQuotes(killChars($_POST['dept_id']));
	$sql = "select off_level_pattern_id from usr_dept where dept_id = ".$dept_id."";

	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{   
		echo "<off_level_pattern_id>".$row['off_level_pattern_id']."</off_level_pattern_id>";
	}
	echo "</response>";
} else if($mode=='get_no_of_offices') {

	$office_level = stripQuotes(killChars($_POST['office_level']));
	$sql='select off_level_id from usr_dept_off_level where off_level_dept_id='.$office_level.'';
	$rs=$db->query($sql);
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$off_level_id=$row["off_level_id"];
	}
	if ($off_level_id == 7) {
		$sql="select distinct off_loc_id,off_loc_name,off_level_dept_name || ' - '|| off_loc_name as off_loc_name,off_level_dept_tname || ' - '|| off_loc_tname as  off_loc_tname 
		from vw_usr_dept_users_v_sup where off_level_id in (7)";
	} else if ($off_level_id == 9) {
		$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone order by off_loc_id";		
	} else if ($off_level_id == 11) {
		$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range order by off_loc_id";
	} else if ($off_level_id == 13) {
		$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where district_id > 0 order by off_loc_id";
	} else if ($off_level_id == 42) {
		$sql="select division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname from mst_p_sp_division where dept_id=1 order by off_loc_id";
	} else if ($off_level_id == 44) {
		$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname from mst_p_sp_subdivision where dept_id=1 order by off_loc_id";
	} else if ($off_level_id == 46) {
		$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname from mst_p_sp_circle where dept_id=1 order by off_loc_id";
	}
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	echo "<count>".$result->rowCount()."</count>";
	echo "</response>";
} else if($mode=='griev_dept')  { //get officer list 
	$griev_sub_id=stripQuotes(killChars($_POST['griev_sub_id']));
	$hid_pattern_id=stripQuotes(killChars($_POST['hid_pattern_id']));
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_loc_id=stripQuotes(killChars($_POST['loc_id']));
	$department_id=stripQuotes(killChars($_POST['department_id']));
	$whom=stripQuotes(killChars($_POST['whom']));

	$off_sql = "select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
	from vw_usr_dept_users_v_sup
	where 
	off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."
	and dept_id=".$department_id.
	" and (sup_off_loc_id1=".$userProfile->getOff_loc_id().
	" or sup_off_loc_id2=".$userProfile->getOff_loc_id().
	" or off_loc_id=".$userProfile->getOff_loc_id().")
	 and off_level_id >= ".$userProfile->getOff_level_id()."
	and 
	(
	 case ".$hid_pattern_id." -- grievance location's office level pattern
	 when 1 then (off_level_pattern_id = 1 and (off_loc_id = ".$off_loc_id." or off_loc_id = 
		 case ".$userProfile->getOff_level_id()."
		 when 2 then (select rdo_id from mst_p_taluk where taluk_id=".$off_loc_id.")
		 when 3 then (".$off_loc_id.")
		 when 4 then (select firka_id from mst_p_rev_village where rev_village_id=".$off_loc_id.")
		 else null
		 end)
	 ) -- for revenue pattern; 3 is the taluk_id from the pet_master record
	 when 2 then (off_level_pattern_id = 2 and off_loc_id = ".$off_loc_id.") -- for rural pattern; 3 is the block_id from the pet_master record
	 when 3 then (off_level_pattern_id = 3 and off_loc_id = ".$off_loc_id.") -- for urban pattern; 3 is the griev_lb_urban_id from the pet_master record
	 when 4 then (off_level_pattern_id = 4 and off_loc_id = ".$off_loc_id. " and dept_id=".$department_id.") -- for Special pattern pattern; 3 is the division_id from the pet_master record
	 else true
	 end
	)
	and dept_pet_process and off_pet_process and pet_act_ret";

	$off_rs=$db->query($off_sql);
	if(!$off_rs)
	{
		print_r($db->errorInfo());
	}		          
?>
<select name="concerned_officer" id="concerned_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style">
<option value="">--Select--</option>
<?php
while($off_row = $off_rs->fetch(PDO::FETCH_BOTH))
{
	$con_off_ename=$off_row["dept_desig_name"].', '.$off_row["off_level_dept_name"].', '.$off_row["off_loc_name"];
	$con_off_tname=$off_row["dept_desig_tname"].', '.$off_row["off_level_dept_tname"].', '.$off_row["off_loc_tname"];
	if($_SESSION["lang"]=='E'){
		$con_officer_ename=ucfirst(strtolower($con_off_ename));
	}else{
		$con_officer_ename=$con_off_tname;	
	}
	if ($whom==$off_row["dept_user_id"])
	print("<option value='".$off_row["dept_user_id"]."'>".$con_officer_ename."</option>");
	else
	print("<option value='".$off_row["dept_user_id"]."' >".$con_officer_ename."</option>");
}?>
</select>
<?php
} else if ($mode=='reopen_petition') {
	
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	$sup_off=stripQuotes(killChars($_POST['sup_off']));
	$conc_off=stripQuotes(killChars($_POST['conc_off']));
	$user_id=stripQuotes(killChars($_POST['action_entby']));
	$today = $page->currentTimeStamp();
	$pet_act_id = 'F';
	if ($sup_off != "" || $sup_off != null) {
	$sql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_remarks) VALUES (".$petition_id.",'".$pet_act_id."',". $user_id.",'".$today."',".$sup_off.",'Petition Re-opened and sent to Supervisory Officer')";	
	$result=$db->query($sql); 
	}
	
	if ($conc_off != "" || $conc_off != null) {
	$sql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_remarks) VALUES (".$petition_id.",'".$pet_act_id."',". $sup_off.",'".$today."',".$conc_off.",'Petition Re-opened and sent to Enquiry Officer')";	
	$result1=$db->query($sql);
	}
	if($sup_off==' && $conc_off =='){
	$pet_act_id = 'T';
	$sql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_remarks) VALUES (".$petition_id.",'".$pet_act_id."',". $user_id.",'".$today."',null,'Petition is Re-opened')";	
	$result1=$db->query($sql);
	}
	echo "<response>";
	if ($result || $result1) {
		//$upd_sql="update pet_master set pet_type_id=6 where petition_id=".$petition_id."";	
		//$upd_res=$db->query($upd_sql);
		echo $page->generateXMLTag('result',1);
	} else {
		echo $page->generateXMLTag('result', 0);
	}
	echo "</response>";
	
} else if ($mode=='process_petition') {
	//echo "111111111111111";
	$action_type=stripQuotes(killChars($_POST['action_type']));
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	$conc_off=stripQuotes(killChars($_POST['conc_off']));
	$user_id=$userProfile->getDept_user_id();
	$today = $page->currentTimeStamp();	
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	
	$file_no=stripQuotes(killChars($_POST['file_no']));
	$file_date=stripQuotes(killChars($_POST['file_date']));
	$remarks=stripQuotes(killChars($_POST['remarks']));
	//echo $file_date;
	if ($file_date != "") {
		$f_dt=explode('/',$file_date);
		$day=$f_dt[1];
		$mnth=$f_dt[0];
		$yr=$f_dt[2];
		$f_date=$yr.'-'.$day.'-'.$mnth;
		$f_date = "'".$f_date."'";
	} else {
		$f_date = "null";
	}
		
	$sql="SELECT petition_id, l_pet_action_id,l_action_type_code from pet_action_first_last where petition_id=".$petition_id."";
	$rs=$db->query($sql);
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$l_action_type_code=$row["l_action_type_code"];
		$l_pet_action_id=$row["l_pet_action_id"];
	}
	/*if ($action_type == 'F') {
		$file_no = null;
		$f_date = 'null';
		$remarks = null;
	}*/
	//$f_date = ($file_date == null) ? $f_date : "null";
	if ($l_action_type_code == 'T') {
		$sql="UPDATE pet_action set action_type_code='".$action_type."', action_entdt=current_timestamp, action_entby=".$userProfile->getDept_user_id().",action_remarks='".$remarks."' where petition_id=".$petition_id." and pet_action_id=".$l_pet_action_id."";
	} else {
		$conc_off = empty($conc_off)? "NULL":$conc_off;
		$sql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,file_no,file_date,action_remarks) 
		VALUES (".$petition_id.",'".$action_type."',". $user_id.",current_timestamp,".$conc_off.",'".$file_no."',".$f_date."::date,'".$remarks."')";	
	}
	$result=$db->query($sql);
	
	echo "<response>";
	if ($result) {
		echo $page->generateXMLTag('result',1);
	} else {
		echo $page->generateXMLTag('result', 0);
	}
	echo "</response>";
	
} else if ($mode=='process_deferred_petition') {
	//echo "111111111111111";
	$action_type=stripQuotes(killChars($_POST['action_type']));
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	$conc_off=stripQuotes(killChars($_POST['conc_off']));
	$user_id=$userProfile->getDept_user_id();
	$today = $page->currentTimeStamp();	
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	
	$file_no=stripQuotes(killChars($_POST['file_no']));
	$file_date=stripQuotes(killChars($_POST['file_date']));
	$remarks=stripQuotes(killChars($_POST['remarks']));
	
	$f_dt=explode('/',$file_date);
	$day=$f_dt[1];
	$mnth=$f_dt[0];
	$yr=$f_dt[2];
	$f_date=$yr.'-'.$day.'-'.$mnth;
		
	$sql="SELECT petition_id, l_pet_action_id,l_action_type_code from pet_action_first_last where petition_id=".$petition_id."";
	$rs=$db->query($sql);
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$l_action_type_code=$row["l_action_type_code"];
		$l_pet_action_id=$row["l_pet_action_id"];
	}
	/*if ($action_type == 'F') {
		$file_no = null;
		$f_date = 'null';
		$remarks = null;
	}*/
	if ($l_action_type_code == 'T') {
		$sql="UPDATE pet_action set action_type_code='".$action_type."', action_entdt=current_timestamp, to_whom=".$conc_off." 
		where petition_id=".$petition_id." and pet_action_id=".$l_pet_action_id."";
	} else {
		$sql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,file_no,file_date,action_remarks) 
		VALUES (".$petition_id.",'".$action_type."',". $user_id.",'".$today."',".$conc_off.",'".$file_no."','".$f_date."'::date,'".$remarks."')";	
	}
	$result=$db->query($sql);
	
	echo "<response>";
	if ($result) {
		echo $page->generateXMLTag('result',1);
	} else {
		echo $page->generateXMLTag('result', 0);
	}
	echo "</response>";
	
} else if($mode=='get_subdivision_present') {
	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	$district_id=$_POST["district"];
	$district_condition = ($district_id == '') ? 'and district_id='.$userProfile->getDistrict_id() : 'and district_id='.$district_id;
	//echo 
	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  FROM mst_p_sp_subdivision where division_id=".$gre_division." and dept_id=".$dept_id.$district_condition."";
	$rs=$db->query($sub_div_sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='get_subdivision_exists') {
	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	$district_id=$_POST["district"];
	$district_condition = ($district_id == '') ? ' and district_id='.$userProfile->getDistrict_id() : ' and district_id='.$district_id;
	$sub_div_cond = ($gre_division != '') ? ' and division_id='.$gre_division : '';

	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  FROM mst_p_sp_subdivision where dept_id=".$dept_id.$sub_div_cond.$district_condition."";
	
	$rs=$db->query($sub_div_sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='get_circle_exists') {
	$dept_id=$_POST["dept_id"];
	$gre_subdivision=$_POST["gre_subdivision"];

	$gre_subdivision_condition = (strlen(trim($gre_subdivision)) > 0) ? ' and subdivision_id='.$gre_subdivision : '';
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where dept_id=".$dept_id.$gre_subdivision_condition."";
		
	$rs=$db->query($sub_div_sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='get_circle_exists_online') {
	$dept_id=$_POST["dept_id"];
	$gre_subdivision=$_POST["gre_subdivision"];

	$gre_division_condition = ($gre_division == '') ? ' and division_id='.$gre_division : '';
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where subdivision_id=".$gre_subdivision." and dept_id=".$dept_id."";
		
	$rs=$db->query($sub_div_sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='get_circle_exists_for_division') {
	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	$district=$_POST["district"];
	$griev_subdivision_id=$_POST["griev_subdivision_id"];

	$gre_division_condition = ($gre_division == '') ? '': 'and division_id='.$gre_division;
	$gre_subdivision_condition = ($griev_subdivision_id == '') ? '':' and subdivision_id='.$griev_subdivision_id;
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where division_id=".$gre_division." and dept_id=".$dept_id."";
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where dept_id=".$dept_id.$gre_division_condition.$gre_subdivision_condition;
		
	$rs=$db->query($sub_div_sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='division_exists') {
	$dept_id=$_POST["dept_id"];
	$district=$_POST["district"];
 
	$sql = "select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
	usr_dept b on a.dept_id=b.dept_id
	where district_id=". $district." and a.dept_id=".$dept_id." 
	union  
	select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
	usr_dept b on a.dept_id=b.dept_id
	where district_id=". $district." and a.dept_id=-99 and not exists (select distinct subdivision_id from mst_p_sp_subdivision a inner join 
	usr_dept b on a.dept_id=b.dept_id
	where district_id=". $district." and a.dept_id=".$dept_id.")";
		
	$rs=$db->query($sql);
	echo "<response>";
	echo $page->generateXMLTag('result',$rs->rowCount());
	echo "</response>";

} else if($mode=='get_disposing_officer_details') {
	$disposing_officer=$_POST["disposing_officer"];
	if ($disposing_officer == '') {
		$disposing_officer=$userProfile->getDept_user_id();
	}	
	$disp_officer_dept_sql = "select off_level_id, off_loc_id from vw_usr_dept_users_v where dept_user_id=".$disposing_officer;
		
	$disp_officer_dept_rs = $db->query($disp_officer_dept_sql);
	$rowarray = $disp_officer_dept_rs->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<off_level_id>".$row['off_level_id']."</off_level_id>";
		echo "<off_loc_id>".$row['off_loc_id']."</off_loc_id>";
	}
	echo "</response>";

} else if($mode=='get_proposed_action') {
	$petition_id=$_POST["petition_id"];
	$sql="select count(*) as cnt FROM fn_Petition_Action_Taken(".$_SESSION['USER_ID_PK'].",array['F','Q']) a where a.petition_id=".$petition_id;
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row)
	{
		$cnt = $row['cnt'];
	}
	if ($cnt == 1) {
		echo "<response><action>T2</action></response>";
	} else {
		$sql="select count(*) as cnt FROM fn_Petition_Action_Taken(".$_SESSION['USER_ID_PK'].",array['C','E','N','I','S']) a where a.petition_id=".$petition_id;
		$rs = $db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row)
		{
			$cnt = $row['cnt'];
		}
		if ($cnt == 1) {
			$sql="select * from fn_pet_origin_from_myself(".$petition_id.",".$_SESSION['USER_ID_PK'].") as res";
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$res = $row["res"];			
			}
			if ($res) {
				echo "<response><action>T4</action></response>";
			} else {
				echo "<response><action>T3</action></response>";
			}		
		}
		else {
			echo "<response><action>T0</action></response>";
		}
		
	}
} else if($mode=='link_petition') {
	$petition_id=$_POST["petition_id"];
	$link_documet_id=$_POST["link_documet_id"];
	$link_documet_no=$_POST["link_documet_no"]; //current_timestamp
	$ip=$_SERVER['REMOTE_ADDR'];

	$sql="INSERT INTO pet_master_ext_link(petition_id, pet_ext_link_id, pet_ext_link_no, lnk_entby, lnk_entdt, ent_ip_address) VALUES (".$petition_id.",".$link_documet_id.",'". $link_documet_no."', ".$_SESSION['USER_ID_PK'].", current_timestamp, '".$ip."')";	
	$result=$db->query($sql);
		
	echo "<response>";
	if ($result) {
		echo $page->generateXMLTag('result',1);
	} else {
		echo $page->generateXMLTag('result', 0);
	}
	echo "</response>";
} else if($mode=='remove_link') {
	$petition_id=$_POST["petition_id"];
	$link_id=$_POST["link_id"]; //current_timestamp
	$ip=$_SERVER['REMOTE_ADDR'];

	$sql="DELETE FROM pet_master_ext_link WHERE petition_id=".$petition_id." and pet_master_ext_link_id=".$link_id."";
	$result=$db->query($sql);
		
	echo "<response>";
	if ($result) {
		echo $page->generateXMLTag('result',1);
	} else {
		echo $page->generateXMLTag('result', 0);
	}
	echo "</response>";
} else if($mode=='check_pet_no') {
	echo "<response>";
	$link_documet_id=$_POST["link_documet_id"];
	$link_documet_no=$_POST["link_documet_no"]; //current_timestamp
	$ip=$_SERVER['REMOTE_ADDR'];

	if ($link_documet_id == 3) {
		$sql="select petition_id FROM pet_master where petition_no='".$link_documet_no."'"; //pet_master_ext_link
		$result=$db->query($sql);
		$num_rows=$result->rowCount();
		if ($num_rows == 0) {
			echo $page->generateXMLTag('exists',0);
		} else {
			echo $page->generateXMLTag('exists',1);
			$sql="select petition_id FROM pet_master_ext_link where pet_ext_link_no='".$link_documet_no."'"; 			$result=$db->query($sql);
			$num_rows=$result->rowCount();
			if ($num_rows == 1) {
				echo $page->generateXMLTag('linked',1);
			} else {
				echo $page->generateXMLTag('linked',0);
				$sql="select petition_id FROM pet_master_ext_link where petition_id=(select petition_id from pet_master where petition_no='".$link_documet_no."')";
				$result=$db->query($sql);
				$num_rows=$result->rowCount();
				if ($num_rows == 1) {
					echo $page->generateXMLTag('master',1);
				} else {
					echo $page->generateXMLTag('master',0);
				}
				
			}
		}
	}
	echo "</response>";
}
?>
