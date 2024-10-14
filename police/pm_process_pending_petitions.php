<?php
ob_start();
session_start();
include('db.php');
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
$source_frm =$_POST['source_frm'];

if($source_frm=='populate_actions')  {
	$action=stripQuotes(killChars($_POST['action']));
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	if ($action == 'T2') {
		$actTypeCode="'C', 'N', 'T','I','S'";
		if($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_forward()){		// Doubt $_SESSION[LOGIN_LVL]
			$actTypeCode .= ", 'F'";
		}
	} else if ($action == 'T3') {
		$actTypeCode="'F','Q','E','C', 'N', 'T','I','S'";
	} else if ($action == 'T4') {
		$actTypeCode="'F','Q','A','R', 'T'";
	} else if ($action == 'T5') {
		$sql="select CASE WHEN fn_pet_origin_from_our_office(".$petition_id.",".$_SESSION['USER_ID_PK'].") THEN 'OWN' ELSE 'SUP' END AS pet_office, CASE WHEN fn_pet_origin_from_myself(".$petition_id.",".$_SESSION['USER_ID_PK'].") THEN 'SELF' ELSE 'RECD' END AS pet_owner";
		$result = $db->query($sql);
		while($row = $result->fetch(PDO::FETCH_BOTH)) {
			$pet_office = $row["pet_office"];
			$pet_owner = $row["pet_owner"];
		}
		$sql_la = "select action_type_code from fn_pet_action_specific_one(".$petition_id.",1)"; 
		$result_la = $db->query($sql_la);
		$rowarray_la = $result_la->fetchall(PDO::FETCH_ASSOC);
		$last_action = "";
		foreach($rowarray_la as $row_la)
		{
			$last_action = 	$row_la['action_type_code'];
			 
		}
		if($pet_owner == "SELF"){ //Own office petition
			$actTypeCode .= "'A', 'R', 'T'";
			if($userProfile->getPet_forward() && $_SESSION['LOGIN_LVL']==NON_BOTTOM){
			$actTypeCode .= ",'F'";
			if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
				$actTypeCode .= ", 'Q'";
				}				
			}
		} else {
			$actTypeCode .= "'T', 'C', 'N','I','S'";
			if($userProfile->getPet_forward() && $_SESSION['LOGIN_LVL']==NON_BOTTOM){
				$actTypeCode .= ",'F'";
				if ($last_action == 'C' || $last_action == 'E' || $last_action == 'N'|| $last_action == 'I'|| $last_action == 'S') {
					$actTypeCode .= ", 'Q', 'E'";
				}
			}
		}
	}

	$query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.")
	 order by action_type_name";
	$result = $db->query($query);
	?>
	<select name="action_type" id="action_type" class="select_style" onchange="populateOfficers();">
	<option value="">--Select--</option>
	<?php 	
		while($row = $result->fetch(PDO::FETCH_BOTH)) {
			$action_type_name = $row["action_type_name"];
			print("<option value='".$row["action_type_code"]."'>".$action_type_name."</option>");
		}
	?>
	</select>
<?php	
} else if($source_frm=='populate_officers')  { //get officer list
	$petition_id=stripQuotes(killChars($_POST['petition_id']));
	$action_type=stripQuotes(killChars($_POST['action_type']));
	$petition_dept_id=stripQuotes(killChars($_POST['dept_id']));
	$griev_district_id=stripQuotes(killChars($_POST['griev_district_id']));
	$action=stripQuotes(killChars($_POST['action']));
	//echo "================".$action;
	if ($action == 'T2') {
		if ($action_type == 'C' || $action_type == 'N' || $action_type == 'I' || $action_type == 'S') {
			$sql = "select l_action_entby from pet_action_first_last where petition_id=".$petition_id."";
			$rs=$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$l_action_entby = $row["l_action_entby"];				
			}
			$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
			from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$l_action_entby."";
			$rs=$result = $db->query($query);
		?>
		<select name="concerned_officer" id="concerned_officer" class="select_style">
		<?php	
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$off_location_design = $row["off_location_design"];
				print("<option value='".$row["dept_user_id"]."'>".$off_location_design."</option>");
			}
		?>
		</select>	
		<?php
		}
		else if ($action_type == 'F') {
			$pattern_sql = "SELECT dept_id, off_level_pattern_id FROM usr_dept where dept_id=".$petition_dept_id."";
			$pattern_rs = $db->query($pattern_sql);
			$rowarray_pattern = $pattern_rs->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarray_pattern as $rowarray_pattern_row) {
				$off_pattern_id = $rowarray_pattern_row['off_level_pattern_id'];
			}
			$off_pattern_id = $userProfile->getOff_level_pattern_id();
			$griv_loc_off_level_id=$userProfile->getOff_level_id();
			if($off_pattern_id==1) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==3) {
					$get_pet_off="select COALESCE(griev_taluk_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=4;
				} else if($userProfile->getOff_level_id()==4 || $userProfile->getOff_level_id()==5) { 
					$get_pet_off="select COALESCE(griev_rev_village_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=8;
				}
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
			} 
			else if($off_pattern_id==2) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2) {
					$get_pet_off="select COALESCE(griev_block_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=6;
				} else if($userProfile->getOff_level_id()==6) { 
					$get_pet_off="select COALESCE(griev_lb_village_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=9;
				}
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
			}
			else if($off_pattern_id==3) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==7){
					$get_pet_off="select COALESCE(griev_lb_urban_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=7;
				}
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}		
			} 
			else if($off_pattern_id==4) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2) {
				$get_pet_off="select COALESCE(griev_division_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
				$griv_loc_off_level_id=10;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_subdivision_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=11;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
				}
				
				else if($userProfile->getOff_level_id()==10) { 
				
				$get_pet_off="select COALESCE(griev_subdivision_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=11;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_circle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
					$griv_loc_off_level_id=12;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
				} 
				
				else if($userProfile->getOff_level_id()==11) {
				$get_pet_off="select COALESCE(griev_circle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=12;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_subcircle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=13;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
				} 
				
				else if($userProfile->getOff_level_id()==12) {
				$get_pet_off="select COALESCE(griev_subcircle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=13;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_unit_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
					$griv_loc_off_level_id=14;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
				}

			} // p4
			$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$petition_id."";
			$res=$db->query($disp_officer_sql);
			$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
			foreach($rowArrDisp as $rowDisp) {
				$disp_officer = $rowDisp['l_action_entby'];
			}
			if ($userProfile->getOff_level_id()==1) {
				$off_level_cond='(2)';
				$off_hier_pos=2;
				//$off_hier_loc=$pet_district_id;
				$off_hier_loc= ($pet_district_id == '') ? $userProfile->getOff_loc_id() : $pet_district_id;
				$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." and desig_coordinating ) or ";
				$sup_off_cond = '';
			} else if ($userProfile->getOff_level_id()==2) {
				//$off_level_cond0='(2)';
				//$off_level_cond='(2,3,4,6,7,10,11,12)';
				$off_hier_pos=$userProfile->getOff_level_id();
				$off_hier_loc=$userProfile->getOff_loc_id();
				$hier_cond= " ";
				$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
			} else {
				//$off_level_cond='(2,3,4,6,7,10,11,12)';
				$off_hier_pos=$userProfile->getOff_level_id();	
				$off_hier_loc=$userProfile->getOff_loc_id();
				$hier_cond= " ";
				$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
			}
			$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $userProfile->getOff_coordinating() == 1 && $userProfile->getDept_id() == $petition_dept_id && $userProfile->getOff_level_id()==2) ? ' and (not true) ':' and not coalesce(desig_coordinating,false) '; // petition dept_id
			$vw_usr_name='';

			if ($off_pattern_id==1) {
				$vw_usr_name='vw_usr_dept_users_v_sup_p1';
				$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." 
				and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id) = any
				(
				case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
				when 1 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
				when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id." union select row(2,district_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
				when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id." union select row(2,b.district_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
				end
				when 2 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
				when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
				when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
				end 
				when 3 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 4 then array(select row(4,taluk_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
				when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
				end 
				when 4 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 8 then array(select row(5,firka_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
				end 
				else null end
				)";
			}
			else if ($off_pattern_id==2) {
				$vw_usr_name='vw_usr_dept_users_v_sup_p2';
				$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any
				(
				case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
				when 2 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 6 then array(select row(2,district_id) from mst_p_lb_block where block_id=".$pet_off_loc_id.")  
				when 9 then array(select row(6,block_id) from mst_p_lb_village where lb_village_id=".$pet_off_loc_id.")  
				end 
				else null end			
				)";
			}
			else if ($off_pattern_id==3) {
				$vw_usr_name='vw_usr_dept_users_v_sup_p3';
				$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") 
				or row(off_level_id,off_loc_id)=any(
				case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
				when 2 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 7 then array(select row(2,district_id) from mst_p_lb_urban where lb_urban_id=".$pet_off_loc_id.")  
				end 
				else null end			
				)";
			}
			else if ($off_pattern_id==4) {
				$vw_usr_name='vw_usr_dept_users_v_sup_p4';
				$vw_usr_filter=" dept_id=".$petition_dept_id." -- for Special pattern pattern; 3 is the division_id from the pet_master record
				and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id) = any (
				case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
				when 1 then 
				case ".$griv_loc_off_level_id."
				when 10 then array(select row(10,division_id) from mst_p_sp_division where division_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(2,district_id) from mst_p_sp_division where division_id=".$pet_off_loc_id." and dept_id=-99)
				when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.")  
				when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$petition_dept_id.") 
				end  
				when 2 then 
				case ".$griv_loc_off_level_id."
				when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.")  
				when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$petition_dept_id.") 
				end  
				when 10 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle 
				where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.") 
				end 
				else null end
				))";
			}

			$dept_coord_cond = '';
			$query="select * from
			(
			select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
			off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
			AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id 
			from ".$vw_usr_name." 
			where off_hier[".$userProfile->getOff_level_id()."] = ".$userProfile->getOff_loc_id()." 
			and dept_id=".$userProfile->getDept_id()." and (off_loc_id=".$userProfile->getOff_loc_id().")   --getDept_id
			and off_level_id = ".$userProfile->getOff_level_id()." 
			and dept_pet_process and off_pet_process and pet_act_ret ".$dept_coord_cond." and dept_user_id!=".$_SESSION['USER_ID_PK']."  and coalesce(enabling,true)
			and dept_user_id<>".$disp_officer." 
			and dept_user_id not in 
			(
			select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
			".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
			from pet_action_first_last where petition_id=".$petition_id."
			)

			union

			select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
			off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
			AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id 
			from ".$vw_usr_name." 
			where ".$hier_cond." 
			dept_id=".$petition_dept_id.$sup_off_cond." and off_level_id >= ".$userProfile->getOff_level_id()." 
			and 
			( ".$vw_usr_filter." )
			and dept_pet_process and off_pet_process and pet_act_ret and ((dept_desig_id=s_dept_desig_id and off_level_id>".$userProfile->getOff_level_id().") or
			(off_level_id=".$userProfile->getOff_level_id()." and (dept_desig_id=s_dept_desig_id or dept_desig_id<>s_dept_desig_id) ) ) 
			and dept_user_id<>".$_SESSION['USER_ID_PK']."  and coalesce(enabling,true)

			union

			select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
			off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
			AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id
			from ".$vw_usr_name." a
			where a.dept_id=".$petition_dept_id." and dept_pet_process and off_pet_process and pet_act_ret and 
			exists (select 1 from usr_fwd_users_loc_mapping b where b.dept_id_logged_in=".$userProfile->getDept_id()." and b.off_level_id_logged_in=".$userProfile->getOff_level_id()." and ".$userProfile->getOff_loc_id()."=any(b.off_loc_id_logged_in) 
			and b.dept_id_concerned=".$petition_dept_id." and b.off_level_id_concerned>=".$userProfile->getOff_level_id()." and a.off_loc_id=any(b.off_loc_id_concerned) and a.dept_desig_id=any(b.dept_desig_id_concerned))
			and coalesce(enabling,true)
			) abc 
			order by off_level_id,off_level_dept_id,off_loc_name,dept_desig_name";
			
			$result = $db->query($query);
			?>
			<select name="concerned_officer" id="concerned_officer" class="select_style">
			<option value="">--Select--</option>
			<?php
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$off_location_design = $row["off_location_design"];
				print("<option value='".$row["dept_user_id"]."'>".$off_location_design."</option>");
			}
			?>
			</select>
			<?php
			
			} // F

		} // T2
	else if ($action == 'T3') {

		if ($action_type == 'Q') {
			$sql = "select l_action_entby from pet_action_first_last where petition_id=".$petition_id."";
			$rs=$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$l_action_entby = $row["l_action_entby"];				
			}
			$query = "select a1.dept_user_id, a1.off_loc_name||'/ '||a1.dept_desig_name AS off_location_design,
			a1.off_loc_tname||'/ '||a1.dept_desig_tname AS off_location_tdesign
			from vw_usr_dept_users_v_sup a1 where a1.dept_user_id=".$l_action_entby."";
			$rs=$result = $db->query($query);
			?>
			<select name="concerned_officer" id="concerned_officer" class="select_style">
		<?php	
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$off_location_design = $row["off_location_design"];
				print("<option value='".$row["dept_user_id"]."'>".$off_location_design."</option>");
			} ?>
			</select>
		<?php 
		}
		
		else if ($action_type == 'C' || $action_type == 'N' || $action_type == 'E'|| $action_type == 'I'|| $action_type == 'S') {
			$query="SELECT dept_user_id, off_loc_name||'/ '||dept_desig_name AS off_location_design, off_loc_tname ||'/ '||dept_desig_tname AS off_location_tdesign 
			FROM vw_usr_dept_users_desig WHERE dept_user_id in
			(SELECT aa.action_entby FROM
			(SELECT petition_id, pet_action_id, action_type_code, action_entby, to_whom, action_entdt,
			cast (rank() OVER (PARTITION BY petition_id, to_whom ORDER BY pet_action_id DESC)as integer) rnk
			FROM pet_action where petition_id=".$petition_id." and action_type_code in ('F','Q') and to_whom=".$_SESSION['USER_ID_PK'].") aa
			WHERE aa.rnk=1)";
			$rs=$result = $db->query($query);
		?>
		<select name="concerned_officer" id="concerned_officer" class="select_style">
		<?	
			while($row = $result->fetch(PDO::FETCH_BOTH)) {
				$off_location_design = $row["off_location_design"];
				print("<option value='".$row["dept_user_id"]."'>".$off_location_design."</option>");
			}
		?>
		</select>
		<?php
		} 
		
		else if ($action_type == 'F') {
			$pattern_sql = "SELECT dept_id, off_level_pattern_id FROM usr_dept where dept_id=".$petition_dept_id."";
			$pattern_rs = $db->query($pattern_sql);
			$rowarray_pattern = $pattern_rs->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarray_pattern as $rowarray_pattern_row) {
				$off_pattern_id = $rowarray_pattern_row['off_level_pattern_id'];
			}
			$off_pattern_id = $userProfile->getOff_level_pattern_id();
			$griv_loc_off_level_id=$userProfile->getOff_level_id();
			if($off_pattern_id==1) {
			
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==3) {
					$get_pet_off="select COALESCE(griev_taluk_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=4;
				} else if($userProfile->getOff_level_id()==4 || $userProfile->getOff_level_id()==5) { 
					$get_pet_off="select COALESCE(griev_rev_village_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=8;
				}
				
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
			} 
			
			else if($off_pattern_id==2) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2) {
					$get_pet_off="select COALESCE(griev_block_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=6;
				} else if($userProfile->getOff_level_id()==6) { 
					$get_pet_off="select COALESCE(griev_lb_village_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=9;
				}
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
			}

			else if($off_pattern_id==3) {
				if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==7){
					$get_pet_off="select COALESCE(griev_lb_urban_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=7;
				}
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}		
			} 
			
			else if($off_pattern_id==4) {
			if($userProfile->getOff_level_id()==1 || $userProfile->getOff_level_id()==2) {
				$get_pet_off="select COALESCE(griev_division_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
				$griv_loc_off_level_id=10;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off[pet_off_loc_id];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_subdivision_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=11;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
			} 
			
			else if($userProfile->getOff_level_id()==10) { 
				
				$get_pet_off="select COALESCE(griev_subdivision_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=11;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_circle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
					$griv_loc_off_level_id=12;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
			} 
			
			else if($userProfile->getOff_level_id()==11) {
				$get_pet_off="select COALESCE(griev_circle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=12;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off[pet_off_loc_id];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_subcircle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";
					$griv_loc_off_level_id=13;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
			} 
			
			else if($userProfile->getOff_level_id()==12) {
				$get_pet_off="select COALESCE(griev_subcircle_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
				$griv_loc_off_level_id=13;
				$result_pet_off = $db->query($get_pet_off);
				$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray_pet_off as $row_pet_off)
				{
					$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
				}
				if ($pet_off_loc_id==-99){
					$get_pet_off="select COALESCE(griev_unit_id,-99) as pet_off_loc_id from pet_master where petition_id = ".$petition_id."";		
					$griv_loc_off_level_id=14;
					$result_pet_off = $db->query($get_pet_off);
					$rowarray_pet_off = $result_pet_off->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray_pet_off as $row_pet_off)
					{
						$pet_off_loc_id = $row_pet_off['pet_off_loc_id'];
					}	
				}
			}
		}

		$disp_officer_sql = "select l_action_entby from pet_action_first_last where  petition_id=".$petition_id."";
		$res=$db->query($disp_officer_sql);
		$rowArrDisp=$res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowArrDisp as $rowDisp) {
			$disp_officer = $rowDisp[l_action_entby];
		}
		if ($userProfile->getOff_level_id()==1) {
			$off_level_cond='(2)';
			$off_hier_pos=2;
			//$off_hier_loc=$pet_district_id;
			$off_hier_loc= ($pet_district_id == '') ? $userProfile->getOff_loc_id() : $pet_district_id;
			$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." and desig_coordinating ) or ";
			$sup_off_cond = '';
		} else if ($userProfile->getOff_level_id()==2) {
			//$off_level_cond0='(2)';
			//$off_level_cond='(2,3,4,6,7,10,11,12)';
			$off_hier_pos=$userProfile->getOff_level_id();
			$off_hier_loc=$userProfile->getOff_loc_id();
			$hier_cond= " ";
			$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
		} else {
			//$off_level_cond='(2,3,4,6,7,10,11,12)';
			$off_hier_pos=$userProfile->getOff_level_id();	
			$off_hier_loc=$userProfile->getOff_loc_id();
			$hier_cond= " ";
			$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$pet_off_loc_id.") ";
		}
		$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $userProfile->getOff_coordinating() == 1 && $userProfile->getDept_id() == $petition_dept_id && $userProfile->getOff_level_id()==2) ? ' and (not true) ':' and not coalesce(desig_coordinating,false) '; // petition dept_id
		$vw_usr_name='';
		//echo "333333333333333";
		if ($off_pattern_id==1) {
			$vw_usr_name='vw_usr_dept_users_v_sup_p1';
			$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." 
			and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id) = any
			(
			case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
			when 1 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id." union select row(2,district_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id." union select row(2,b.district_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
			end
			when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$pet_off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$pet_off_loc_id.") 
			end 
			when 3 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 4 then array(select row(4,taluk_id) from mst_p_taluk where taluk_id=".$pet_off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
			end 
			when 4 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 8 then array(select row(5,firka_id) from mst_p_rev_village where rev_village_id=".$pet_off_loc_id.") 
			end 
			else null end
			)";
		}
		else if ($off_pattern_id==2) {
			$vw_usr_name='vw_usr_dept_users_v_sup_p2';
			$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any
			(
			case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
			when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 6 then array(select row(2,district_id) from mst_p_lb_block where block_id=".$pet_off_loc_id.")  
			when 9 then array(select row(6,block_id) from mst_p_lb_village where lb_village_id=".$pet_off_loc_id.")  
			end 
			else null end			
			)";
		}
		else if ($off_pattern_id==3) {
			$vw_usr_name='vw_usr_dept_users_v_sup_p3';
			$vw_usr_filter="(off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") 
			or row(off_level_id,off_loc_id)=any(
			case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
			when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 7 then array(select row(2,district_id) from mst_p_lb_urban where lb_urban_id=".$pet_off_loc_id.")  
			end 
			else null end			
			)";
		}
		else if ($off_pattern_id==4) {
			$vw_usr_name='vw_usr_dept_users_v_sup_p4';
			$vw_usr_filter=" dept_id=".$petition_dept_id." -- for Special pattern pattern; 3 is the division_id from the pet_master record
			and ((off_loc_id = ".$pet_off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id) = any (
			case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
			when 1 then 
			case ".$griv_loc_off_level_id."
			when 10 then array(select row(10,division_id) from mst_p_sp_division where division_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(2,district_id) from mst_p_sp_division where division_id=".$pet_off_loc_id." and dept_id=-99)
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$petition_dept_id.") 
			end  
			when 2 then 
			case ".$griv_loc_off_level_id."
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$pet_off_loc_id." and a.dept_id=".$petition_dept_id.") 
			end  
			when 10 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle 
			where circle_id=".$pet_off_loc_id." and dept_id=".$petition_dept_id.") 
			end 
			else null end
			))";
		}
		$dept_coord_cond = '';
		$query="select * from
		(
		select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
		off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
		AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id 
		from ".$vw_usr_name." 
		where off_hier[".$userProfile->getOff_level_id()."] = ".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id()." and (off_loc_id=".$userProfile->getOff_loc_id().")   --getDept_id
		and off_level_id = ".$userProfile->getOff_level_id()." 
		and dept_pet_process and off_pet_process and pet_act_ret ".$dept_coord_cond." and dept_user_id!=".$_SESSION['USER_ID_PK']."  and coalesce(enabling,true)
		and dept_user_id<>".$disp_officer." 
		and dept_user_id not in 
		(
		select unnest(forwarding_officers[1:coalesce(array_position(forwarding_officers,
		".$_SESSION['USER_ID_PK']."),array_length(forwarding_officers,1))]) 
		from pet_action_first_last where petition_id=".$petition_id."
		)

		union

		select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
		off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
		AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id 
		from ".$vw_usr_name." 
		where ".$hier_cond." 
		dept_id=".$petition_dept_id.$sup_off_cond." and off_level_id >= ".$userProfile->getOff_level_id()." 
		and 
		( ".$vw_usr_filter." )
		and dept_pet_process and off_pet_process and pet_act_ret and ((dept_desig_id=s_dept_desig_id and off_level_id>".$userProfile->getOff_level_id().") or
		(off_level_id=".$userProfile->getOff_level_id()." and (dept_desig_id=s_dept_desig_id or dept_desig_id<>s_dept_desig_id) ) ) 
		and dept_user_id<>".$_SESSION['USER_ID_PK']."  and coalesce(enabling,true)

		union

		select dept_user_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, 
		off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, off_loc_name ||' / '||dept_desig_name
		AS off_location_design,off_loc_tname ||' / '||dept_desig_tname AS off_location_tdesign,off_level_id,off_level_dept_id
		from ".$vw_usr_name." a
		where a.dept_id=".$petition_dept_id." and dept_pet_process and off_pet_process and pet_act_ret and 
		exists (select 1 from usr_fwd_users_loc_mapping b where b.dept_id_logged_in=".$userProfile->getDept_id()." and b.off_level_id_logged_in=".$userProfile->getOff_level_id()." and ".$userProfile->getOff_loc_id()."=any(b.off_loc_id_logged_in) 
		and b.dept_id_concerned=".$petition_dept_id." and b.off_level_id_concerned>=".$userProfile->getOff_level_id()." and a.off_loc_id=any(b.off_loc_id_concerned) and a.dept_desig_id=any(b.dept_desig_id_concerned))
		and coalesce(enabling,true)
		) abc 
		order by off_level_id,off_level_dept_id,off_loc_name,dept_desig_name";
		
		$result = $db->query($query);
		?>
		<select name="concerned_officer" id="concerned_officer" class="select_style">
		<option value="">--Select--</option>
		<?php
		while($row = $result->fetch(PDO::FETCH_BOTH)) {
			$off_location_design = $row["off_location_design"];
			print("<option value='".$row["dept_user_id"]."'>".$off_location_design."</option>");
		}
		?>
		</select>
		<?php
		} // F

	} // T3
	else if ($action == 'T4') {		
	}  
	else if ($action == 'T5') {		
	}

}
?>
