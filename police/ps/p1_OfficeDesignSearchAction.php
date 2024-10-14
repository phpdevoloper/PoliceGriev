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
	$off_level_id=$userProfile->getOff_level_id();	
	$griev_sub_type_id=stripQuotes(killChars($_POST["griev_sub_type_id"]));
	$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
	$department_id=(int)stripQuotes(killChars($_POST["dept_id"]));
	$pet_id=stripQuotes(killChars($_POST["petition_id"])); 
	$action_entby= stripQuotes(killChars($_POST['action_entby']));
	$act_type_code= stripQuotes(killChars($_POST['act_type_code']));
	$pageSize = $_POST["page_size"];
	
	//Search Parameters on Pop-up Concerned Officers Screen
	$off_id= stripQuotes(killChars($_POST['off_id']));	
	$desig_name= stripQuotes(killChars($_POST['desig_name'])); //desig_first
	$desig_first= stripQuotes(killChars($_POST['desig_first'])); //desig_first 
	$dept_user_id_cond = "";
	
	if ($act_type_code == 'D') {
		$disposal_condition = ' and pet_disposal ';
	}
	
	if ($off_id != "") {
		$dept_user_id_cond = " and dept_user_id=".$off_id.""; 
	}
	if ($desig_name != "") {
		$desig_name = strtolower($desig_name);
		$dept_user_id_cond = " and lower(dept_desig_name) like '%".$desig_name."%'"; 
	}
	if ($desig_first != "") {
		$desig_first = strtolower($desig_first);
		$dept_user_id_cond = " and lower(dept_desig_name) like '".$desig_first."%'"; 
	}
	//Parameter based search ends here
	
	$get_pattrn="select a.dept_id, a.off_level_pattern_id from usr_dept a where a.dept_id = ".$department_id."";
	$result_pattrn = $db->query($get_pattrn);
	$rowarray_pattrn = $result_pattrn->fetchall(PDO::FETCH_ASSOC);

	foreach($rowarray_pattrn as $row_pattrn)
	{
		$off_pattern_id = $row_pattrn['off_level_pattern_id'];
	}
	$off_pattern_id = $userProfile->getOff_level_pattern_id();	
	if($off_pattern_id==1) {
		
		if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==3) {
			$get_pet_off="select COALESCE(griev_taluk_id,-99) as pet_off_loc_id,source_id from pet_master where petition_id = ".$pet_id."";
			$griv_loc_off_level_id=4;
		} else if($userProfile->getOff_level_id()==4 || $userProfile->getOff_level_id()==5) { 
			$get_pet_off="select COALESCE(griev_rev_village_id,-99) as pet_off_loc_id,source_id from pet_master where petition_id = ".$pet_id."";
			$griv_loc_off_level_id=8;
		}
		$result_pet_off = $db->query($get_pet_off);
		$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray_pet_off as $row_pet_off)
		{
			$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
			$source_id = $row_pet_off['source_id'];
			
		}

	} else if($off_pattern_id==2) {
		if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2) {
			$get_pet_off="select COALESCE(griev_block_id,-99) as pet_off_loc_id,source_id from pet_master where petition_id = ".$pet_id."";
			$griv_loc_off_level_id=6;
		} else if($userProfile->getOff_level_id()==6) { 
			$get_pet_off="select COALESCE(griev_lb_village_id,-99) as pet_off_loc_id,source_id from pet_master where petition_id = ".$pet_id."";
			$griv_loc_off_level_id=9;
		}
		$result_pet_off = $db->query($get_pet_off);
		$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray_pet_off as $row_pet_off)
		{
			$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
			$source_id = $row_pet_off['source_id'];
		}
	} else if($off_pattern_id==3) {
		if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==7){
			$get_pet_off="select COALESCE(griev_lb_urban_id,-99) as pet_off_loc_id,source_id from pet_master where petition_id = ".$pet_id."";
			$griv_loc_off_level_id=7;
		}
		$result_pet_off = $db->query($get_pet_off);
		$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray_pet_off as $row_pet_off)
		{
			$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
			$source_id = $row_pet_off['source_id'];
		}		
	} else if($off_pattern_id==4) {
		$sql = "select dept_id,coalesce(griev_district_id,-99) as griev_district_id,coalesce(griev_division_id,-99) as griev_division_id,coalesce(griev_subdivision_id,-99) as griev_subdivision_id,
		coalesce(griev_circle_id,-99) as griev_circle_id, COALESCE(griev_subcircle_id,-99) as griev_subcircle_id, 
		COALESCE(griev_unit_id,-99) as griev_unit_id, source_id from pet_master where petition_id=".$pet_id."";
		$rs = $db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row)
		{
			$dept_id = $row['dept_id'];
			$griev_district_id = $row['griev_district_id'];
			$griev_division_id = $row['griev_division_id'];
			$griev_subdivision_id = $row['griev_subdivision_id'];
			$griev_circle_id = $row['griev_circle_id'];
			$griev_subcircle_id = $row['griev_subcircle_id'];
			$griev_unit_id = $row['griev_unit_id'];
			$source_id = $row['source_id'];
		}
		if ($dept_id == -99) {
			$griv_loc_off_level_id=2;
		} else {
			if ($griev_unit_id != -99) {
				$pet_off_loc_id = $griev_unit_id;
				$griv_loc_off_level_id=14;
			} else if ($griev_subcircle_id != -99) {
				$pet_off_loc_id = $griev_subcircle_id;
				$griv_loc_off_level_id=13;
			} else if ($griev_circle_id != -99) {
				$pet_off_loc_id = $griev_circle_id;
				$griv_loc_off_level_id=12;
			} else if ($griev_subdivision_id != -99) {
				$pet_off_loc_id = $griev_subdivision_id;
				$griv_loc_off_level_id=11;
			} else if ($griev_division_id != -99) {
				$pet_off_loc_id = $griev_division_id;
				$griv_loc_off_level_id=10;
			} else if ($griev_district_id != -99) {
				$pet_off_loc_id = $griev_district_id;
				$griv_loc_off_level_id=2;
			}
		}
	}
	
	if ($disp_officer == '') {
		$disp_officer = $userProfile->getDept_user_id();
	}
	$disp_officer_dept_sql = "select c.dept_id as dept_id from usr_dept_users a inner join usr_dept_desig b on b.dept_desig_id=a.dept_desig_id inner join usr_dept_off_level c on c.off_level_dept_id=b.off_level_dept_id where a.dept_user_id=".$disp_officer."";
	$disp_officer_dept_rs=$db->query($disp_officer_dept_sql);						  
	while($disp_officer_dept_row = $disp_officer_dept_rs->fetch(PDO::FETCH_BOTH)) {
		$disp_officer_dept_id=$disp_officer_dept_row["dept_id"];
	}
	
	
	if ($userProfile->getOff_level_id( )== 1) {
	$get_pet_off="select griev_district_id from pet_master where petition_id = ".$pet_id."";
	$result_pet_off = $db->query($get_pet_off);
	$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray_pet_off as $row_pet_off) {
			$pet_district_id = $row_pet_off['griev_district_id'];
		}	
	}
	
	$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && 
	$userProfile->getOff_coordinating() == 1 && 
	$userProfile->getDept_id() == $department_id && 
	$disp_officer_dept_id == $department_id && 
	$userProfile->getOff_level_id()==2) ? ' and (not true) ':'';

	$off_level_cond0='('.$userProfile->getOff_level_id().')';
	if ($userProfile->getOff_level_id()==1) {
		$off_level_cond='(2)';
		$off_hier_pos=2;
		$off_hier_loc=$pet_district_id;
		$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." and desig_coordinating ) or ";
		$sup_off_cond = '';
	} else if ($userProfile->getOff_level_id()==2) {
		$off_level_cond='(2,3,4,6,7,10,11,12)';
		$off_hier_pos=$userProfile->getOff_level_id();
		$off_hier_loc=$userProfile->getOff_loc_id();
		$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." ) or ";
		$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
	} else {
		$off_hier_pos=$userProfile->getOff_level_id();	
		$off_hier_loc=$userProfile->getOff_loc_id();
		$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
		if ($userProfile->getOff_level_id()==3) {
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in (4,5) ) or ";
		} else if ($userProfile->getOff_level_id()==4) {
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in (5) ) or ";
		} else if ($userProfile->getOff_level_id()==5) {
			$hier_cond= " ";
		} else if ($userProfile->getOff_level_id()==6) {
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in (9) ) or ";
		} else if ($userProfile->getOff_level_id()==7) {
			$hier_cond= " ";
		} else if ($userProfile->getOff_level_id()==10) {
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in (11,12) ) or ";
		} else if ($userProfile->getOff_level_id()==11) {
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in (12) ) or ";
		}
	}
	
	$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$pet_id."";
	$res=$db->query($disp_officer_sql);
	$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
	foreach($rowArrDisp as $rowDisp) {
		$l_action_entby = $rowDisp['l_action_entby'];
	}
	$l_action_entby = ($l_action_entby == '') ? $disp_officer : $l_action_entby;
	$inSql=" select * from
	(select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
	from vw_usr_dept_users_v_sup 
	where off_hier[".$userProfile->getOff_level_id()."] = ".$userProfile->getOff_loc_id()." 
	and off_level_id in ".$off_level_cond0." 
	and pet_act_ret and dept_pet_process and off_pet_process ".$disposal_condition.$dept_user_id_cond.$dept_coord_cond." and
	case  
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then dept_id=".$disp_officer_dept_id." -- and dept_desig_id<>s_dept_desig_id
	else dept_id=".$department_id." 
	end
	and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id<>".$l_action_entby." and coalesce(enabling,true)

	union
	
	select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
	from vw_usr_dept_users_v_sup 
	where (".$hier_cond." 
	/*(
	case ".$off_pattern_id."
	when 4 then true
	else off_hier[".$off_hier_pos."] = ".$off_hier_loc."
	end
	)
	or */
	(dept_id=".$department_id.$sup_off_cond." and off_level_id >= ".$userProfile->getOff_level_id()." 
	and ( 
	
	case ".$off_pattern_id." -- grievance location's office level pattern 
		
		when 1 then (off_level_pattern_id = 1 and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") 
		or row(off_level_id,off_loc_id) = any		
		(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		when 1 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id." union select row(2,district_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id." union select row(2,b.district_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		when 3 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 4 then array(select row(4,taluk_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		when 4 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 8 then array(select row(5,firka_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		
		else null end
		) ) )-- for revenue pattern; 3 is the taluk_id from the pet_master record
			
		when 2 then (off_level_pattern_id = 2 and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any
		(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 6 then array(select row(2,district_id) from mst_p_lb_block where block_id=".$pet_off_loc_id.")  
			when 9 then array(select row(6,block_id) from mst_p_lb_village where lb_village_id=".$pet_off_loc_id.")  
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		
		else null end			
		) ) )-- for rural pattern; 3 is the block_id from the pet_master record 
	
		when 3 then (off_level_pattern_id = 3 and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 7 then array(select row(2,district_id) from mst_p_lb_urban where lb_urban_id=".$pet_off_loc_id.")  
			end 
		
		else null end			
		) ) )
		-- for urban pattern; 3 is the griev_lb_urban_id from the pet_master record 
	
		when 4 then (off_level_pattern_id = 4 and dept_id=".$department_id." -- for Special pattern pattern; 3 is the division_id from the pet_master record
		and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id) = any (
	
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		when 1 then 
			case ".$griv_loc_off_level_id."
			--when 10 then (select fn_no_div_div_ids(".$pet_off_loc_id.",".$department_id."))
			when 10 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$department_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$department_id.")
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$department_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$department_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$department_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$department_id.") 
			-- division_id=17 : pet_master ; dept_id=9 : pet_master 
			end  
		when 2 then 
			case ".$griv_loc_off_level_id."
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$department_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$department_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$department_id.") 
			-- division_id=17 : pet_master ; dept_id=9 : pet_master 
			end  
		when 10 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$department_id.") 
			-- division_id=17 : pet_master ; dept_id=9 : pet_master 
			end 
		else null end
	)
	) )
	else true end )))
	and dept_pet_process and off_pet_process and pet_act_ret ".$disposal_condition.$dept_user_id_cond." and ((dept_desig_id=s_dept_desig_id and off_level_id>".$userProfile->getOff_level_id().") or
	(off_level_id=".$userProfile->getOff_level_id()." and
	case
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then true 
	else true
	end
	)
	) and dept_user_id<>".$disp_officer." and coalesce(enabling,true)
	
	union
	
	select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, 
	off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, 
	off_level_dept_id, off_loc_id
	from vw_usr_dept_users_v_sup a
	inner join usr_fwd_users_loc_mapping b
	on b.dept_id_logged_in=".$userProfile->getDept_id()." and b.off_level_id_logged_in=".$userProfile->getOff_level_id()." and ".$userProfile->getOff_loc_id()."=any(b.off_loc_id_logged_in) 
	and a.dept_id=dept_id_concerned and a.off_level_id=off_level_id_concerned 
	and a.dept_desig_id=any(dept_desig_id_concerned) and a.off_loc_id=any(b.off_loc_id_concerned)
	where a.dept_pet_process and a.off_pet_process and a.pet_act_ret 
	".$disposal_condition.$dept_user_id_cond." and coalesce(a.enabling,true)
	) alloffr
	where dept_user_id not in (select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
	".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
	from pet_action_first_last where petition_id=".$pet_id.")";

	$query = 'select * from (select *,row_number() over (order by dept_id,off_level_id,off_level_dept_id,off_loc_name,dept_desig_name) as rownum from ('.$inSql .'
	) off_level)aa
	WHERE rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' and rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<dept_user_id>".$row['dept_user_id']."</dept_user_id>";
		echo "<off_location>".$row['off_loc_name']."</off_location>";
		echo "<off_level_name>".$row['off_level_dept_name']."</off_level_name>";
		echo "<off_tlocation>".$row['off_loc_tname']."</off_tlocation>";
		echo "<dept_desig_id>".$row['dept_desig_id']."</dept_desig_id>";
		echo "<dept_desig_name>".$row['dept_desig_name']."</dept_desig_name>";		
	}
	
	$sql_count = 'SELECT COUNT(dept_user_id) FROM ('.$inSql .'
				) off_level';
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo "<count>".$count[0]."</count>";
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));//pagnation
	echo "</response>";
}

?>
