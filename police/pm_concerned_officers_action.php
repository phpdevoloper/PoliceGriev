<?php
ob_start();
session_start();
include('db.php');
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
$source_frm =$_POST['source_frm'];

if ($source_frm=='loadSupervisoryOfficers') {
	//Basic parameters	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$disposing_officer=stripQuotes(killChars($_POST['disposing_officer']));
	$pet_process=stripQuotes(killChars($_POST['pet_process']));

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
	$disposing_officer=($disposing_officer == '') ? $userProfile->getDept_user_id():$disposing_officer;
	
	//$off_level_id=($off_level_id == '')? $userProfile->getOff_level_id():$off_level_id;
	
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
//

	/*
	if ($dept_off_level_pattern_id == '') {
		$condition = " and dept_off_level_pattern_id is null "; 
	} else {
		$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
	}
	*/
    //$off_level_id = ($off_level_id == '') ? $up_off_level_id : $off_level_id;
	
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
	//echo $off_hier;
/*
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id<=".$off_level_id." 
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and 
	case 
	when off_level_id=".$userProfile->getOff_level_id()." then dept_desig_role_id != 2
	else true
	end
	order by off_level_dept_id,off_level_id,dept_desig_name";
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id<=".$off_level_id." 
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and 
	case 
	when off_level_id=".$up_off_level_id." and coalesce(dept_off_level_pattern_id,-1) = coalesce(".$up_dept_off_level_pattern_id.",-1) then dept_desig_role_id != 2
	when off_level_id=".$up_off_level_id." and coalesce(dept_off_level_pattern_id,-1) = coalesce(".$up_dept_off_level_pattern_id.",-1) then dept_desig_role_id in (2,3)
	else true
	end
	order by off_level_dept_id,off_level_id,dept_desig_name";

*/$disp_off_cond="";
	if($pet_process=='D'){
		$disposal_condition = ' and pet_disposal ';
	}
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." and off_level_id<=".$off_level_id." 
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id] and 
	case 
	when off_level_id=".$off_level_id." then dept_desig_role_id in (2,3)
	else true
	end
	and 
	case 
	when off_level_id=".$up_off_level_id." then COALESCE(off_head,false) is false
	else true
	end
	and dept_user_id != ".$disposing_officer."
	order by off_level_dept_id,off_level_id,dept_desig_name";
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>".$up_off_level_id." 
	and 
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then off_level_id<=".$off_level_id."
	else off_level_id<".$off_level_id." 
	end 
	and off_hier[1:off_level_id]=(array".$off_hier."::integer[])[1:off_level_id] and 
	case 
	when off_level_id=".$off_level_id." then dept_desig_role_id in (2,3)
	else true
	end
	and 
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then COALESCE(off_head,false) is false
	else true
	end
	and dept_user_id != ".$disposing_officer." and COALESCE(enabling,true) ".$disposal_condition." and
	(case when ".$userProfile->getgriev_suptype_id()."=1 then griev_suptype_id in (2,3,1)
	when ".$userProfile->getgriev_suptype_id()."=2 then griev_suptype_id in (2,1)
	when ".$userProfile->getgriev_suptype_id()."=3 then griev_suptype_id in (3,1) end )
	order by off_level_dept_id,off_level_id,dept_desig_name";
	
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>".$sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	
?>
<select name="supervisory_officer" id="supervisory_officer" data_valid='yes'  data-error="Please select Office" class="select_style" >
<option value="">--Select Enquiry Filing Officer--</option>
<?php
	$prev_off_level_dept_id = '';
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		if($pet_process=='D'){ 
		if($row["dept_user_id"]!=186){
		if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
			print("<optgroup label='".$row["off_level_dept_name"]."' id='optgroup_".substr($row["off_level_dept_name"],0,3)."'>");
		}
		
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];//echo "aaaaaa".$pet_process.$row["dept_user_id"];exit;
		
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");}
		}else{
			if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
			print("<optgroup label='".$row["off_level_dept_name"]."' id='optgroup_".substr($row["off_level_dept_name"],0,3)."'>");
		}
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];//echo "aaaaaa".$pet_process.$row["dept_user_id"];exit;
		
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
		}
		
		$prev_off_level_dept_id = $row["off_level_dept_id"];
	}
?>
</select>
<?php
} else if ($source_frm=='loadConcernedOfficers') {
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
	$dept_id='1';
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
	if($off_level_id!=''){
		$codn=" and off_level_id=$off_level_id";
	}else{
		$codn="";
	}
	
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=".$dept_id." and off_level_pattern_id=".$up_off_level_pattern_id."$codn and off_loc_id=".$petition_office_loc_id." 
	and dept_desig_role_id in (2,3) ".$condition." order by dept_user_id limit 1";
	//echo $sql.">>>>>>>";
	
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
	
	if ($supervisory_officer != '') {
		$supervisory_officer_cond = " and dept_user_id != ".$supervisory_officer."";
		$sql='select off_level_id from vw_usr_dept_users_v_sup where dept_user_id='.$supervisory_officer;
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {
			$off_level_id=$row["off_level_id"];
		}
	}
	$disposing_officer=($disposing_officer == '') ? $userProfile->getDept_user_id():$disposing_officer;
	
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>=".$off_level_id." 
	and off_hier[1:off_level_id]=(array".$off_hier."::integer[])[1:off_level_id] and
	case 
	when off_level_dept_id=".$up_off_level_dept_id." then COALESCE(off_head,false) is false
	else true
	end	
	and dept_user_id != ".$disposing_officer.$supervisory_officer_cond."
	and COALESCE(enabling,true)
	order by off_level_dept_id,off_level_id,dept_desig_name";
	//echo ">>>>>>".$sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="concerned_officer" id="concerned_officer" data_valid='yes'  data-error="Please select Officer" class="select_style" >
<option value="">--Select Enquiry Officer--</option>
<?php
$prev_off_level_dept_id = '';
					
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
						print("<optgroup label='".$row["off_level_dept_name"]."'>");
					}
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		if ($l_to_whom == $row["dept_user_id"])
		print("<option value='".$row["dept_user_id"]."' selected>".$dept_desig_name." - ".$off_loc_name."</option>");
		else
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
		$prev_off_level_dept_id = $row["off_level_dept_id"];
	}
?>
</select>
<?php	
} else if ($source_frm=='reopen_action') {
	//echo ">>>>>>>>>>>>>>>>>>>>>";
	$dept_id=stripQuotes(killChars($_POST["dept_id"]));
	$pet_loc_id=stripQuotes(killChars($_POST["griev_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	
	if ($up_dept_off_level_pattern_id == ''){
		$up_dept_off_level_pattern_id='null';
	}	
	if ($up_dept_off_level_pattern_id == 'null'){
		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
			$condition = " and (dept_off_level_pattern_id=".$dept_off_level_pattern_id.")"; 
		}
	} else {
		$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
	}
	
	if($off_level_id!=''){
		$codn=" and off_level_id=$off_level_id";
	}else{
		$codn="";
	}
	
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=1 and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()."$codn and off_loc_id=".$pet_loc_id."
	and dept_desig_role_id in (2,3)".$condition." order by dept_user_id limit 1";
	
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row[off_hier];			
	}

	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	
	$query="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup a1
	where dept_id=1".$condition." and dept_desig_role_id in (2,3) and (off_level_id<=".$off_level_id." 
	and off_level_id>=".$userProfile->getOff_level_id().")
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
	and dept_user_id!=".$_SESSION['USER_ID_PK']." order by dept_user_id";
	//echo $query;
	$result = $db->query($query);
	//$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
?>
<select name="supervisory_officer" id="supervisory_officer" onchange="loadConcernedOfficer();" class="select_style" >
<option value="">--Select Enquiry Filing Officer--</option>
<?php
	while($row = $result->fetch(PDO::FETCH_BOTH)) {
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
	}
?>
</select>
<?php 
} else if ($source_frm=='reopen_conc_action') {
	$dept_id=stripQuotes(killChars($_POST["dept_id"]));
	$pet_loc_id=stripQuotes(killChars($_POST["griev_loc_id"]));
	$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	$supervisory_officer=stripQuotes(killChars($_POST["supervisory_officer"]));
	
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	
	if ($up_dept_off_level_pattern_id == ''){
		$up_dept_off_level_pattern_id='null';
	}	
	if ($up_dept_off_level_pattern_id == 'null'){
		if ($dept_off_level_pattern_id == '') {
			$condition = " and dept_off_level_pattern_id is null "; 
		} else {
			$condition = " and (dept_off_level_pattern_id=".$dept_off_level_pattern_id.")"; 
		}
	} else {
		$condition = " and dept_off_level_pattern_id=".$dept_off_level_pattern_id."";
	}
	
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_id=1 and off_level_pattern_id=".$userProfile->getOff_level_pattern_id()." and 
	off_level_id=".$off_level_id." and off_loc_id=".$pet_loc_id."
	and dept_desig_role_id in (2,3)".$condition." order by dept_user_id limit 1";
	
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row[off_hier];			
	}

	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	
	if ($supervisory_officer != '') {
		$supervisory_officer_cond = " and dept_user_id != ".$supervisory_officer."";
		$sql='select off_level_id from vw_usr_dept_users_v_sup where dept_user_id='.$supervisory_officer;
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {
			$off_level_id=$row["off_level_id"];
		}
	}
	
	$query="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id
	from vw_usr_dept_users_v_sup a1
	where dept_id=1".$condition." and dept_desig_role_id in (2,3) and (off_level_id<=".$off_level_id." 
	and off_level_id>=".$userProfile->getOff_level_id().")
	and off_hier[1:off_level_id]=(array".$off_hier.")[1:off_level_id]
	and dept_user_id!=".$_SESSION['USER_ID_PK'].$supervisory_officer_cond." order by dept_user_id";
	//echo $query;
	$result = $db->query($query);
	//$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
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
<?php 
} else if ($source_frm=='load_Concerned_Officers') {
	$disposing_officer_id=stripQuotes(killChars($_POST["disposing_officer"]));
	$supervisory_officer=stripQuotes(killChars($_POST["supervisory_officer"]));
	$office_level=stripQuotes(killChars($_POST["office_level"]));
	$office_loc_id=stripQuotes(killChars($_POST["office_loc_id"]));
	$sql="select dept_user_id, dept_id, off_loc_id, off_level_id,off_level_dept_id, dept_off_level_pattern_id,off_level_dept_name from vw_usr_dept_users_v_sup where dept_user_id=".$supervisory_officer."";
	
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);$prev_off_level_dept_id = '';
	foreach($rowarray as $row) {
					
		$dept_id = $row['dept_id'];			
		$dept_user_id = $row['dept_user_id'];			
		$off_loc_id = $row['off_loc_id'];			
		$off_level_id = $row['off_level_id'];			
		$off_level_dept_id = $row['off_level_dept_id'];			
		$dept_off_level_pattern_id = $row['dept_off_level_pattern_id'];			
	}$disposal_officer_cond='';
	if ($disposing_officer_id != null || $disposing_officer_id != '') {
		$disposal_officer_cond = " and dept_user_id !=".$disposing_officer_id."";
	}
	if ($dept_off_level_pattern_id == 'null' ||$dept_off_level_pattern_id == ''){
		$condition = "  ";	 
	} else {					
		$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$dept_off_level_pattern_id.")";	
	}
	if($office_level !=''){
		$off_lev_cdn=" and off_level_id<=$office_level";

	}else{
		$off_lev_cdn='';
	}
	
	if($office_loc_id !=''){
		$off_loc_cdn=" and off_loc_id<=$office_loc_id";
		$off_loc_cdn='';
	}else{
		$off_loc_cdn='';
	}
	
	$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_user_id=".$supervisory_officer;		
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row['off_hier'];			
	}
	$up_off_level_id=$userProfile->getOff_level_id();
	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';
	$disposal_officer_cond.=" and dept_user_id!=1";
	$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name
	from vw_usr_dept_users_v_sup
	where dept_id=".$dept_id.$condition." 
	and dept_desig_role_id in (2,3) and off_level_id>=".$off_level_id." 
	and COALESCE(enabling,true) and off_hier[".$off_level_id."]=".$off_loc_id."
	and dept_user_id!=".$supervisory_officer.$disposal_officer_cond.$off_lev_cdn.$off_loc_cdn." and off_hier[1:$up_off_level_id]=(array".$off_hier.")[1:$up_off_level_id]
	order by off_level_dept_id,off_level_id,dept_desig_id, off_loc_name";
	?>
<select name="concerned_officer" id="concerned_officer" data_valid='yes'  data-error="Please select Officer" class="select_style" >
<option value="">--Select Enquiry Officer--</option>
<?php
	//echo $sql;
	$rs = $db->query($sql);
	//$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	$prev_off_level_dept_id = '';
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
						print("<optgroup label='".$row["off_level_dept_name"]."'>");
					}
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		if ($l_to_whom == $row["dept_user_id"])
		print("<option value='".$row["dept_user_id"]."' selected>".$dept_desig_name." - ".$off_loc_name."</option>");
		else
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
		$prev_off_level_dept_id=$row["off_level_dept_id"];
	}
	
}	
if($source_frm=='enquiry_default'){
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
				if ($up_dept_off_level_pattern_id == ''){
					$up_dept_off_level_pattern_id='null';
				}	
				if ($up_dept_off_level_pattern_id == 'null'){
					$condition = " ";	 
				} else {					
					$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$up_dept_off_level_pattern_id.")";	
				}
				
				$sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal".$condition."";
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
				
				$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name, dept_off_level_pattern_name
				from vw_usr_dept_users_v_sup
				where dept_id=".$up_dept_id.$condition." 
				and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." 
				and COALESCE(enabling,true) and off_hier[".$up_off_level_id."]=".$userProfile->getOff_loc_id()."
				and dept_user_id!=".$userProfile->getDept_user_id().$disposal_officer_cond."
				order by off_level_id,off_level_dept_id,dept_desig_id,off_loc_name";
				//echo $sql;exit;
				$rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				$prev_off_level_dept_id = '';
							print('<option value="">--Select Enquiry Filing Officer--</option>');
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
						$off_level_dept_name = $row["off_level_dept_name"];
						$dept_off_level_pattern_name = $row["dept_off_level_pattern_name"];
						$dept_label = $off_level_dept_name.' - '.$dept_off_level_pattern_name;
						print("<optgroup label='".$dept_label."'>");
					}
					$dept_user_id=$row["dept_user_id"];
					$dept_desig_name=$row["dept_desig_name"];
					//$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"] == '') ? 0:$row["dept_off_level_office_id"];
					//$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
					$off_loc_name=$row["off_loc_name"];
					//$off_level_dept_tname = $row["off_level_dept_tname"];
/* 					if($_SESSION["lang"]=='E')
					{
						$off_level_dept_name = $off_level_dept_name;
					}else{
						$off_level_dept_name = $off_level_dept_tname;	
					} */
					print("<option value='".$dept_user_id."'>".$dept_desig_name." - ".$off_loc_name."</option>");
					$prev_off_level_dept_id=$row["off_level_dept_id"];
				}
}
?>