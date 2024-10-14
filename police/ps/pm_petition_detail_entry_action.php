<?php 

ob_start();
session_start();
include('db.php');
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
$source_frm =$_POST['source_frm'];
//echo "get_disposing_officer";
/******************* Load Locations ************************/ 
if($source_frm=='loadConcernedLocations') {
	//Basic parameters	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == 0) ? '':$dept_off_level_pattern_id;
	$dept_off_level_office_id=($dept_off_level_office_id == 0) ? '':$dept_off_level_office_id;
	
	//User Profile
	$up_off_level_id=$userProfile->getOff_level_id();
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
	$up_dept_id=$userProfile->getDept_id();
	$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	
	//Concerned office parameters
	$conc_off_level_id=stripQuotes(killChars($_POST['conc_off_level_id']));
	$conc_off_level_dept_id=stripQuotes(killChars($_POST['conc_off_level_dept_id']));
	$conc_dept_off_level_office_id=stripQuotes(killChars($_POST['conc_dept_off_level_office_id']));
	
	$petition_office_loc_id=stripQuotes(killChars($_POST['petition_office_loc_id']));
	$pet_off_id=stripQuotes(killChars($_POST['pet_off_id']));
	
	$petition_office_loc_id=($petition_office_loc_id == '') ? $pet_off_id:$petition_office_loc_id;
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".$off_level_id.">>>>>>>>".$conc_off_level_id;
	//if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == ''){ // DGP Office
		if ($off_level_id == 46) { //Circle
			if ($conc_off_level_id == 46) {	
				$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
				from mst_p_sp_circle a
				where a.circle_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 44) {	
				$sql="select a.subdivision_id as off_loc_id,a.subdivision_name as off_loc_name,a.subdivision_tname as off_loc_tname 
				from mst_p_sp_subdivision a
				inner join mst_p_sp_circle b on b.subdivision_id=a.subdivision_id			
				where b.circle_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 42) {
				$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
				from mst_p_sp_division a
				inner join mst_p_sp_circle b on b.division_id=a.division_id			
				where b.circle_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 13) {
				$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name,a.district_tname as off_loc_tname 
				from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_circle c on c.division_id=b.division_id			
				where c.circle_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 11) {
				$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
				from mst_p_sp_range a 
				inner join mst_p_sp_division b on b.range_id=a.range_id
				inner join mst_p_sp_circle c on c.division_id=b.division_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." and c.circle_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 9) {		
				$sql="select a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a 
				inner join mst_p_sp_division b on b.zone_id=a.zone_id
				inner join mst_p_sp_circle c on c.division_id=b.division_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." and c.circle_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 44) { //Subdivision			
			if ($conc_off_level_id == 44) {
				$sql="select a.subdivision_id as off_loc_id,a.subdivision_name as off_loc_name,a.subdivision_tname as off_loc_tname 
				from mst_p_sp_subdivision a
				where a.subdivision_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 42) {
				$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
				from mst_p_sp_division a
				inner join mst_p_sp_subdivision b on b.division_id=a.division_id			
				where b.subdivision_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 13) {
				$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name,a.district_tname as off_loc_tname 
				from mst_p_district a
				inner join mst_p_sp_subdivision b on b.district_id=a.district_id			
				where b.subdivision_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 11) {
				$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
				from mst_p_sp_range a 
				inner join mst_p_sp_division b on b.range_id=a.range_id
				inner join mst_p_sp_subdivision c on c.division_id=b.division_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and c.subdivision_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 9) {
				$sql="select a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a 
				inner join mst_p_sp_division b on b.zone_id=a.zone_id
				inner join mst_p_sp_subdivision c on c.division_id=b.division_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and c.subdivision_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 42) {
			if ($conc_off_level_id == 42) {
				$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
				from mst_p_sp_division a
				where a.division_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 13) {
				$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name,a.district_tname as off_loc_tname 
				from mst_p_district a 
				inner join mst_p_sp_division b on b.district_id=a.district_id
				where b.division_id=".$petition_office_loc_id." order by off_loc_id";
			} else if ($conc_off_level_id == 11) {
				$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
				from mst_p_sp_range a 
				inner join mst_p_sp_division b on b.range_id=a.range_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and b.division_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 9) {
				$sql="select a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a 
				inner join mst_p_sp_division b on b.zone_id=a.zone_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and b.division_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 13) {
		    if ($conc_off_level_id == 13) {
				$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name,a.district_tname as off_loc_tname 
				from mst_p_district a 
				where a.district_id=".$petition_office_loc_id." order by off_loc_id";
			}else if ($conc_off_level_id == 11) {
				$sql="select distinct a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
				from mst_p_sp_range a 
				inner join mst_p_sp_division b on b.range_id=a.range_id
				inner join mst_p_district c on c.district_id=b.district_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and c.district_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 9) {
				$sql="select distinct a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a 
				inner join mst_p_sp_division b on b.zone_id=a.zone_id
				inner join mst_p_district c on c.district_id=b.district_id
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and c.district_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 11) {
			if ($conc_off_level_id == 11) {
				$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
				from mst_p_sp_range a 
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and a.range_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 9) {
				$sql="select distinct a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a
				inner join 	mst_p_sp_range b on b.zone_id=a.zone_id	
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and b.range_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 9) {
			if ($conc_off_level_id == 9) {
				$sql="select a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
				from mst_p_sp_zone a
				where a.dept_off_level_pattern_id=".$dept_off_level_pattern_id." 
				and a.zone_id=".$petition_office_loc_id."";
			} else if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		} else if ($off_level_id == 7) {
			if ($conc_off_level_id == 7) {
				$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
			}
		}
	//}
	//echo $sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="conc_office_loc_id" id="conc_office_loc_id" data_valid='no'  data-error="Please select Office" class="select_style" onChange="getOfficersForProcessing();">
<option value="">--Select--</option>
<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_loc_id=$row["off_loc_id"];
		$off_loc_name=$row["off_loc_name"];
		$off_loc_tname = $row["off_loc_tname"];
		if($_SESSION["lang"]=='E')
		{
			$off_loc_name = $off_loc_name;
		}else{
			$off_loc_name = $off_loc_tname;	
		}
		print("<option value='".$off_loc_id."'>".$off_loc_name."</option>");
	}
?>
</select>
<?php
}
if($source_frm=='loadLocationsForReports') {
	$office_level=stripQuotes(killChars($_POST['office_level']));
	$off_level=explode('-',$office_level);
	$off_level_id=$off_level[0];
	if($off_level_id==7){
		$param="state_id as loc_id,state_name as loc_name,state_tname as loc_tname";
		$table="mst_p_state";
		$loc="state_id";
	}else if($off_level_id==9){
		$param="zone_id as loc_id,zone_name as loc_name,zone_tname as loc_tname";
		$table="mst_p_sp_zone";
		$loc="zone_id";
	}else if($off_level_id==11){
		$param="range_id as loc_id,range_name as loc_name,range_tname";
		$table="mst_p_sp_range";
		$loc="range_id";
	}else if($off_level_id==13){
		$param="district_id as loc_id,district_name as loc_name,district_tname as loc_tname";
		$table="mst_p_district";
		$loc="district_id";
	}else if($off_level_id==42){
		$param="division_id as loc_id,division_name as loc_name,division_tname as loc_tname";
		$table="mst_p_sp_division";
		$loc="division_id";
	}
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	$ef_off=$_SESSION['USER_ID_PK'];
	if($ef_off!='' && $off_level_id!=''){
		$sql="select off_hier[".$off_level_id."] as loc_id from vw_usr_dept_users_v_sup where dept_user_id=".$ef_off;	
		$rs = $db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$loc_id=$row['loc_id'];
		}
	}
	if($office_level=='7-1-0'){
		$loc_id=29;
		}
	$office_sql="select ".$param." from ".$table." where ".$loc."=".$loc_id;
	
	$office_rs = $db->query($office_sql);
		$office_rowarray = $office_rs->fetchall(PDO::FETCH_ASSOC);
		foreach($office_rowarray as $office_row) {
			$loc_id=$office_row['loc_id'];
			$loc_name=$office_row['loc_name'];
			$loc_tname=$office_row['loc_tname'];
		}
		if($_SESSION["lang"]=='T')
		{
		print("<option value='".$loc_id."'>".$loc_tname."</option>");
		}else{
		print("<option value='".$loc_id."'>".$loc_name."</option>");
		}
}
if($source_frm=='loadLocations') {
	//Basic parameters	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$dept_id=1;
	
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == 0) ? '':$dept_off_level_pattern_id;
	$dept_off_level_office_id=($dept_off_level_office_id == 0) ? '':$dept_off_level_office_id;
	
	$up_off_level_id=$userProfile->getOff_level_id();
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
	$up_dept_id=$userProfile->getDept_id();
	$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	$condition=' order by off_loc_name';
	//echo $up_off_level_id.'>>>>>'.$off_level_id.'>>>>>'.$up_off_level_pattern_id.'>>>>>'.$up_dept_off_level_pattern_id;
	if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == '') { //DGP office condition
		if ($off_level_id == 7) {
		if($dept_off_level_pattern_id==3){
		$state=36;
		}else{
		$state=$userProfile->getState_id();
		}
			$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$state." order by off_loc_id";
		} else if ($off_level_id == 9) { //Zone
			$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
		} else if ($off_level_id == 11) {
			$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
		} else if ($off_level_id == 13) {//echo "1111111>>>>>>";
			$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where district_id > 0 and dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
			
			if($up_off_level_id == 7){
			$sql="SELECT off_loc_id,off_loc_name,off_loc_tname FROM public.vw_usr_dept_users_v_sup WHERE dept_off_level_pattern_id=".$dept_off_level_pattern_id." and off_level_id=".$off_level_id." and dept_desig_role_id=2";
			}
			
		} /*else if ($off_level_id == 42, 44, 46){*/
			if ($off_level_id == 42) { //Division
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
			from mst_p_sp_division a".$join_condition."
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		} else if ($off_level_id == 44) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			}
				
			$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
			from mst_p_sp_subdivision a
			inner join mst_p_sp_division b on b.division_id=a.division_id
			".$join_condition."
			where b.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition."";
		} else if ($off_level_id == 46) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			//echo $sql;
		}
		
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 1) { //IGP office condition
		if ($off_level_id == 9) { //Zone
			$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and zone_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
		} else if ($off_level_id == 11) {
			if ($up_off_level_id == 9) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and zone_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			} else if ($up_off_level_id == 11) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and range_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			}
			
		} else if ($off_level_id == 13) {
			if ($up_off_level_id == 9) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
				where c.zone_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 11) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_range c on c.range_id=b.range_id  
				where b.zone_id=".$userProfile->getZone_id()." and c.range_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 13) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id=".$userProfile->getOff_loc_id();
			}
			
		}else if ($off_level_id == 42) { //Division
				if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			if ($up_off_level_id == 7) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id>0 order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.zone_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 11) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where range_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 13) {
				
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.district_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}else if ($up_off_level_id == 42) {
				
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}
			
		} else if ($off_level_id == 44) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			}				
			$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
			from mst_p_sp_subdivision a
			inner join mst_p_sp_division b on b.division_id=a.division_id
			".$join_condition."
			where b.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition." order by off_loc_id";
		} else if ($off_level_id == 46) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_division b on a.division_id=b.division_id 
				inner join mst_p_sp_zone c ON c.zone_id = b.zone_id";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_division b on a.division_id=b.division_id 
				inner join mst_p_sp_zone c ON c.zone_id = b.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_division b on a.division_id=b.division_id 
				inner join mst_p_sp_zone c ON c.zone_id = b.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_division b on a.division_id=b.division_id 
				inner join mst_p_sp_zone c ON c.zone_id = b.zone_id";
			}
		if ($up_off_level_id == 7) {
				$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a $join_condition where c.zone_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 11) {
				$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a $join_condition where range_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 13) {
				
				$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a $join_condition where b.district_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 42) {
				
				$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a $join_condition where b.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 46) {
				
				echo $sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
				from mst_p_sp_circle a where a.circle_id=".$userProfile->getOff_loc_id();
			}
		} /*else if ($off_level_id == 42, 44, 46){are dealt with Get_all_officer_Form_Action.php}*/
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 2) { //IGP office condition
	
		if ($off_level_id == 7) {
			$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";
		} else if ($off_level_id == 9) { //Zone
			$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
		} else if ($off_level_id == 11) {
			if ($up_off_level_id == 7) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and zone_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			} else if ($up_off_level_id == 11) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and range_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			}
			
		} else if ($off_level_id == 13) {
			if ($up_off_level_id == 7) {
				$sql="SELECT off_loc_id,off_loc_name,off_loc_tname FROM public.vw_usr_dept_users_v_sup WHERE dept_off_level_pattern_id=".$dept_off_level_pattern_id." and off_level_id=".$off_level_id." and dept_desig_role_id=1";
			} else if ($up_off_level_id == 9) {
				$sql="SELECT off_loc_id,off_loc_name,off_loc_tname FROM public.vw_usr_dept_users_v_sup WHERE dept_off_level_pattern_id=".$dept_off_level_pattern_id." and off_level_id=".$off_level_id." and dept_desig_role_id=1";
			} else if ($up_off_level_id == 11) {
				$sql="SELECT off_loc_id,off_loc_name,off_loc_tname FROM public.vw_usr_dept_users_v_sup WHERE dept_off_level_pattern_id=".$dept_off_level_pattern_id." and off_level_id=".$off_level_id." and dept_desig_role_id=1";
			} else if ($up_off_level_id == 13) {
				$sql="SELECT off_loc_id,off_loc_name,off_loc_tname FROM public.vw_usr_dept_users_v_sup WHERE dept_off_level_pattern_id=".$dept_off_level_pattern_id." and off_level_id=".$off_level_id." and dept_desig_role_id=1 and off_loc_id=".$userProfile->getOff_loc_id()."";
			}
			
		}else if ($off_level_id == 42) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_range c on c.range_id=a.range_id ";
			}
			if ($up_off_level_id == 7) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where c.dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 11) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where c.range_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 13) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.district_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 42) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}
			
		} else if ($off_level_id == 46) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone b on b.zone_id=a.zone_id inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone b on b.zone_id=a.zone_id inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone b on b.zone_id=a.zone_id inner join mst_p_sp_range c on c.range_id=a.range_id ";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone b on b.zone_id=a.zone_id inner join mst_p_sp_range c on c.range_id=a.range_id ";
			}
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id 
			".$join_condition."
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		}
		
		/*else if ($off_level_id == 42, 44, 46){are dealt with Get_all_officer_Form_Action.php}*/
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 3) { //COP TN
		if ($off_level_id == 7) {
			$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";
		} else if ($off_level_id == 9) { //Zone
			$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
		} else if ($off_level_id == 11) {
			if ($up_off_level_id == 7) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and zone_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			} else if ($up_off_level_id == 11) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and range_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			}
			
		} else if ($off_level_id == 13) {
			if ($up_off_level_id == 7) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id>0 and dept_off_level_pattern_id=3 order by off_loc_name";
			} else if ($up_off_level_id == 9) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
				where c.zone_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 11) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_range c on c.range_id=b.range_id  
				where c.range_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 13) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id=".$userProfile->getOff_loc_id();
			}
			
		}else if ($off_level_id == 42) { //Division
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			if ($up_off_level_id == 7) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where c.dept_off_level_pattern_id=3 order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.zone_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 11) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where range_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 13) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.district_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 42) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}
			
		} else if ($off_level_id == 44) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			}				
			$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
			from mst_p_sp_subdivision a
			inner join mst_p_sp_division b on b.division_id=a.division_id
			".$join_condition."
			where b.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition." order by off_loc_id";
		} else if ($off_level_id == 46) {
			
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where c.dept_id=".$dept_id." and cir.circle_id=".$userProfile->getCircle_id()." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			if ($up_off_level_id == 13) {
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where a.district_id=".$userProfile->getOff_loc_id()." and c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		} else if ($up_off_level_id == 42) {
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where cir.division_id=".$userProfile->getOff_loc_id()." and c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		} 
			/* if ($up_off_level_id == 7) {
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where cir.division_id=".$userProfile->getOff_loc_id()." and c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		} */
		} /*else if ($off_level_id == 42, 44, 46){are dealt with Get_all_officer_Form_Action.php}*/
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 4) { //IGP office condition
		if ($off_level_id == 7) {
			$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";
		} else if ($off_level_id == 9) { //Zone
			$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
		} else if ($off_level_id == 11) {
			if ($up_off_level_id == 7) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and zone_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			} else if ($up_off_level_id == 11) {
				$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$dept_off_level_pattern_id." and range_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
			}
			
		} else if ($off_level_id == 13) {
			if ($up_off_level_id == 7) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id>0 order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
				where c.zone_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 11) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_range c on c.range_id=b.range_id  
				where c.range_id=".$userProfile->getOff_loc_id();
			} else if ($up_off_level_id == 13) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id=".$userProfile->getOff_loc_id();
			}
			
		}else if ($off_level_id == 42) { //Division
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			if ($up_off_level_id == 7) {
				$sql="select distinct a.district_id as off_loc_id,a.district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district a where a.district_id>0 order by off_loc_id";
			} else if ($up_off_level_id == 9) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.zone_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 11) {
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a where range_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 13) {
				
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.district_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 42) {
				
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			} else if ($up_off_level_id == 46) {
				
				$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
				from mst_p_sp_division a $join_condition where a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}
			
		} else if ($off_level_id == 44) {
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
			}				
			$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
			from mst_p_sp_subdivision a
			inner join mst_p_sp_division b on b.division_id=a.division_id
			".$join_condition."
			where b.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition." order by off_loc_id";
		} else if ($off_level_id == 46) {
			
			if ($dept_off_level_pattern_id == 1) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id ";
			} else if ($dept_off_level_pattern_id == 2) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 3) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			} else if ($dept_off_level_pattern_id == 4) {
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			 //if ($up_off_level_id == 46) {
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id." and cir.circle_id=".$userProfile->getCircle_id().$condition;
			if ($up_off_level_id == 42){
				 $sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where  a.division_id=".$userProfile->getOff_loc_id()." and  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			 }
		} /*else if ($off_level_id == 42, 44, 46){are dealt with Get_all_officer_Form_Action.php}*/
	}
	
	//echo $sql.$up_off_level_id;
	/*
	if ($dept_id=1 && $pattern_id=='' && $off_level_office_id=='' && $off_level_id==7) { //State
		$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";
	} else if ($dept_id=1 && $pattern_id==1 && $off_level_office_id=='' && $off_level_id==9) { //Zone
		$zone_condition=' and zone_id='.$userProfile->getOff_loc_id();
		if ($userProfile->getOff_level_dept_id() == 1) {
			$zone_condition = "";
		}
		$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$pattern_id.$zone_condition." order by off_loc_id";
	}  else if ($dept_id=1 && $pattern_id==1 && $off_level_office_id=='' && $off_level_id==11) { //Range
		$zone_condition=' and zone_id='.$userProfile->getOff_loc_id();
		if ($userProfile->getOff_level_dept_id() == 1) {
			$zone_condition = "";
		}
		$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$pattern_id.$zone_condition." order by off_loc_id";
	} else if ($dept_id=1 && $pattern_id==1 && $off_level_office_id=='' && $off_level_id==13) { //District
		---7,9,11
		
	}
	
	*/
	/*
	if ($off_level_id == 7) {
		$sql="select distinct off_loc_id,off_loc_name,off_level_dept_name || ' - '|| off_loc_name as off_loc_name,off_level_dept_tname || ' - '|| off_loc_tname as  off_loc_tname 
		from vw_usr_dept_users_v_sup where off_level_id in (7) and dept_off_level_pattern_id=".$pattern_id;

		$sql="select state_id as off_loc_id,state_name as off_loc_name,state_tname as off_loc_tname from mst_p_state where state_id=".$userProfile->getState_id()." order by off_loc_id";		
	} else if ($off_level_id == 9) {
		$zone_condition = '';
		if ($pattern_id == 1) {
			$zone_condition=' and zone_id='.$userProfile->getOff_loc_id();
		} 
		if ($userProfile->getOff_level_dept_id() == 1) {
			$zone_condition = "";
		}
		$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$pattern_id.$zone_condition." order by off_loc_id";		
	} else if ($off_level_id == 11) {
		$zone_condition = '';
		if ($pattern_id == 1) {
			$zone_condition=' and zone_id='.$userProfile->getOff_loc_id();
		}
		if ($userProfile->getOff_level_dept_id() == 1) {
			$zone_condition = "";
		}
		$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$pattern_id.$zone_condition." order by off_loc_id";
	} else if ($off_level_id == 13) {
		if ($userProfile->getOff_level_id() == 11) {
			if ($pattern_id == 1) {
				$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where range_id[1]=".$userProfile->getOff_loc_id();
			} else  if ($pattern_id == 2) {
				$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where range_id[2]=".$userProfile->getOff_loc_id();
			}
		} else if ($userProfile->getOff_level_id() == 13) {
			$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where district_id=".$userProfile->getOff_loc_id()." order by off_loc_id";
		} else {
			$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where district_id > 0 order by off_loc_id";
		}		
	} else if ($off_level_id == 42) {
		$sql="select division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname from mst_p_sp_division a 
		inner join mst_p_sp_range b on b.range_id=a.range_id 
		where a.dept_id=1 and b.dept_off_level_pattern_id=".$pattern_id." order by off_loc_id";
	} else if ($off_level_id == 44) {
		$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
		from mst_p_sp_subdivision a
		inner join mst_p_sp_division b on b.division_id=a.division_id
		inner join mst_p_sp_range c on c.range_id=b.range_id
		where b.dept_id=1 and c.dept_off_level_pattern_id=".$pattern_id." order by off_loc_id";
	} else if ($off_level_id == 46) {
		$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname 
		from mst_p_sp_circle cir
		inner join mst_p_sp_division a on a.division_id=cir.division_id
		inner join mst_p_sp_range c on c.range_id=a.range_id
		where a.dept_id=1 and dept_off_level_pattern_id=".$pattern_id." order by off_loc_id";
	}
	*/
	//echo $sql;exit;

	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	//echo $rs->rowCount();
?>
<select name="office_loc_id" id="office_loc_id" data_valid='yes'  data-error="Please select Office" class="select_style" onChange="getOfficersForProcessing();">
<?php if ($rs->rowCount() > 1) { ?>
<option value="">--Select--</option>
<?php
}
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_loc_id=$row["off_loc_id"];
		$off_loc_name=$row["off_loc_name"];
		$off_loc_tname = $row["off_loc_tname"];
		if($_SESSION["lang"]=='E')
		{
			$off_loc_name = $off_loc_name;
		}else{
			$off_loc_name = $off_loc_tname;	
		}
		print("<option value='".$off_loc_id."'>".$off_loc_name."</option>");
	}
?>
</select>
<?php
}
if($source_frm=='loadOfficeLevel') {
// usr_dept_off_level - off_level_dept_id
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	$off_level_dept_id_pm=stripQuotes(killChars($_POST['off_level_dept_id_pm']));
	
	$dept_off_level_pattern_cond="";
	$dept_off_level_office_cond="";
	
	//$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." or (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.")) order by off_level_dept_id";
	$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." or (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.")) order by off_level_dept_id";
	
	if ($pattern_id != "") {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
	} else {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}
	
	//echo  $sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="office_level" id="office_level" data_valid='yes' data-error="Please select Office Pattern" class="select_style" onChange="loadOfficeLocations()">
	<?php
		if($userProfile->getOff_level_id()!=46){
		?>
<option value="">--Select--</option>
		<?php	
		}

	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_level_id=$row["off_level_id"];
		$off_level_dept_id=$row["off_level_dept_id"];
		$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"]=='')? 0:$row["dept_off_level_office_id"];
		$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_tname = $row["off_level_dept_tname"];
		if($_SESSION["lang"]=='E')
		{
			$off_level_dept_name = $off_level_dept_name;
		}else{
			$off_level_dept_name = $off_level_dept_tname;	
		}
		if ($off_level_dept_id_pm == $off_level_dept_id)
		print("<option value='".$off_level."' selected>".$off_level_dept_name."</option>");
		else
		print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
	}	

?>
</select>
<?php
}  

if($source_frm=='loadLevelForReports') {
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	$off_level_dept_id_pm=stripQuotes(killChars($_POST['off_level_dept_id_pm']));	
	$dept_off_level_pattern_cond="";
	$dept_off_level_office_cond="";

	$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." or (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.")) order by off_level_dept_id";
	
	if ($pattern_id != "") {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id < ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.")
		union
		select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id =7 and dept_off_level_pattern_id is null) order by off_level_dept_id";
	} else {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id is null) union
		select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id =7 and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}
	if ($pattern_id == 4) {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.")
		union
		select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id =9 and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}
	//echo  $sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="office_level" id="office_level" data_valid='yes' data-error="Please select Office Pattern" class="select_style" onChange="loadOfficeLocationsForReport()">
<option value="">--Select Office Level--</option>
<?php	

	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_level_id=$row["off_level_id"];
		$off_level_dept_id=$row["off_level_dept_id"];
		$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"]=='')? 0:$row["dept_off_level_office_id"];
		$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_tname = $row["off_level_dept_tname"];
		if($_SESSION["lang"]=='E')
		{
			$off_level_dept_name = $off_level_dept_name;
		}else{
			$off_level_dept_name = $off_level_dept_tname;	
		}
		if ($off_level_dept_id_pm == $off_level_dept_id)
		print("<option value='".$off_level."' selected>".$off_level_dept_name."</option>");
		else
		print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
	}	

?>
</select>
<?php
}

if($source_frm=='concerned_office_level') {
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	$pet_off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	
	if ($pattern_id != "") {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id <= ".$pet_off_level_id." and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
	} else {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id <= ".$pet_off_level_id." and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="conc_office_level" id="conc_office_level" data_valid='yes' data-error="Please select Concerned Office Level" class="select_style" onChange="loadConcernedOfficeLocations()">
<option value="">--Select--</option>
<?php
while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_level_id=$row["off_level_id"];
		$off_level_dept_id=$row["off_level_dept_id"];
		$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"]=='')? 0:$row["dept_off_level_office_id"];
		$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_tname = $row["off_level_dept_tname"];
		if($_SESSION["lang"]=='E')
		{
			$off_level_dept_name = $off_level_dept_name;
		}else{
			$off_level_dept_name = $off_level_dept_tname;	
		}
		print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
	}
?>
</select>
<?php
}	
if($source_frm=='processingOfficers') { 
	$office_level=stripQuotes(killChars($_POST['office_level']));
	$office_loc_id=stripQuotes(killChars($_POST['office_loc_id']));
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	
	$off_level=explode('-',$office_level);
	$off_level_id=$off_level[0];
	$off_level_dept_id=$off_level[1];

	if ($userProfile->getOff_level_dept_id() == $off_level_dept_id) {
		$desig_role_condition = ' and dept_desig_role_id=3';
	} else {
		$desig_role_condition = ' and dept_desig_role_id in (2,3)';
	}
	
	$sql='select dept_user_id,off_loc_id,dept_desig_name, off_loc_name from vw_usr_dept_users_v_sup where off_level_dept_id='.$off_level_dept_id.' and off_loc_id='.$office_loc_id.$desig_role_condition.'';
	echo $sql;
	$rs=$db->query($sql);
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="concerned_officer" id="concerned_officer" data_valid='yes'  data-error="Please select Office" class="select_style" >
<option value="">--Select--</option>
<?php

	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$dept_desig_name=$row["dept_desig_name"];
		$off_loc_name=$row["off_loc_name"];
		
		print("<option value='".$row["dept_user_id"]."' >".$dept_desig_name." - ".$off_loc_name."</option>");
	}
?>
</select>
<?php
}
/******************* FOR GET COMMUNICATION TALUK ************************/ 
if($source_frm=='taluk') {  
	$distcode=stripQuotes(killChars($_POST['distid']));
	if($distcode!=0){
		$qua_sql = "select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id='$distcode' order by taluk_name";
		$qua_rs=$db->query($qua_sql);
		if(!$qua_rs) {
			print_r($db->errorInfo());
			exit;
		}	
	}
?>
<select name="comm_taluk" id="comm_taluk" data_valid='yes' data-error="Please select taluk" style="width:200px;" onChange="get_village();same_above_enab();"><!-- get_disable_block(); -->
<option value="0">--Select--</option>
<?php
while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
	$talukname=$qua_row["taluk_name"];
	$taluktname=$qua_row["taluk_tname"];
	if($_SESSION["lang"]=='E'){
		$taluk_name=$talukname;
	}else{
		$taluk_name=$taluktname;	
	}
	print("<option value='".$qua_row["taluk_id"]."' >".$taluk_name."</option>");
}
?>
</select>
<?php }
if($source_frm=='taluk_for_pet_id') { 
	$distcode=stripQuotes(killChars($_POST['distid']));
	$comm_taluk_id=stripQuotes(killChars($_POST['comm_taluk_id']));
	if($distcode!=0){
		$qua_sql = "select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id='$distcode' order by taluk_name";
		$qua_rs=$db->query($qua_sql);
		if(!$qua_rs) {
			print_r($db->errorInfo());
			exit;
		}	
	}
?>
<select name="comm_taluk" id="comm_taluk" data_valid='yes' data-error="Please select taluk" style="width:200px;" onChange="get_village();same_above_enab();"><!-- get_disable_block(); -->
<option value="0">--Select--</option>
<?php
while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
	$talukname=$qua_row["taluk_name"];
	$taluktname=$qua_row["taluk_tname"];
	if($_SESSION["lang"]=='E'){
		$taluk_name=$talukname;
	}else{
		$taluk_name=$taluktname;	
	}
	if ($comm_taluk_id == $qua_row["taluk_id"])
	print("<option value='".$qua_row["taluk_id"]."' selected>".$taluk_name."</option>");
	else
	print("<option value='".$qua_row["taluk_id"]."' >".$taluk_name."</option>");
}
?>
</select>
<?php

}

/******************* FOR GET COMMUNICATION VILLAGE ************************/
if($source_frm=='village') { 
	$distcode=stripQuotes(killChars($_POST['distid']));
	$talukcode=stripQuotes(killChars($_POST['talukid']));
	$rev=stripQuotes(killChars($_POST['rv']));			 
	if($talukcode!=0 && $distcode!=0){
		$qua_sql = "select distinct rev_village_id,rev_village_name,rev_village_tname from mst_p_rev_village where taluk_id='$talukcode' order by rev_village_name";
		$qua_rs=$db->query($qua_sql);
		if(!$qua_rs)
		{
			print_r($db->errorInfo());
			exit;
		}	
	}
			
?>
<select name="comm_rev_village" id="comm_rev_village" onChange="chkForFilled();" style="width:200px;" data_valid='yes' data-error="Please select revenue village">
<option value="">--Select Revenue Village--</option>
<?php  
while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH))
{
	$villname=$qua_row["rev_village_name"];
	$villtname=$qua_row["rev_village_tname"];
	if($_SESSION["lang"]=='E'){
		$vill_name=$villname;
	}else{
		$vill_name=$villtname;	
	}
	if ($rev == $qua_row["rev_village_id"])
		print("<option value='".$qua_row["rev_village_id"]."' selected>".$vill_name."</option>");
	else
		print("<option value='".$qua_row["rev_village_id"]."' >".$vill_name."</option>");
}
?>
</select>
                
<?php }

if($source_frm=='vill_for_pet_id') { 
	$distcode=stripQuotes(killChars($_POST['distid']));
	$talukcode=stripQuotes(killChars($_POST['talukid']));
	$comm_rev_village_id=stripQuotes(killChars($_POST['comm_rev_village_id']));			 
	if($talukcode!=0 && $distcode!=0){
		$qua_sql = "select distinct rev_village_id,rev_village_name,rev_village_tname from mst_p_rev_village where taluk_id='$talukcode' order by rev_village_name";
		$qua_rs=$db->query($qua_sql);
		if(!$qua_rs)
		{
			print_r($db->errorInfo());
			exit;
		}	
	}
			
?>
<select name="comm_rev_village" id="comm_rev_village" onChange="chkForFilled();" style="width:200px;" data_valid='yes' data-error="Please select revenue village">
<option value="">--Select Revenue Village--</option>
<?php  
while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH))
{
	$villname=$qua_row["rev_village_name"];
	$villtname=$qua_row["rev_village_tname"];
	if($_SESSION["lang"]=='E'){
		$vill_name=$villname;
	}else{
		$vill_name=$villtname;	
	}
	if ($comm_rev_village_id == $qua_row["rev_village_id"])
		print("<option value='".$qua_row["rev_village_id"]."' selected>".$vill_name."</option>");
	else
		print("<option value='".$qua_row["rev_village_id"]."' >".$vill_name."</option>");
}
?>
</select>
                
<?php }
if ($source_frm == 'reset_dist') {
	$dist_id=stripQuotes(killChars($_POST['distid']));	
	$sql="select distinct district_id,district_name,district_tname from mst_p_district where district_id=".$dist_id;
	$rs=$db->query($sql);
	if(!$rs)
	{
		print_r($db->errorInfo());
		exit;
	}	
?>
<select name="gre_dist" id="gre_dist" data_valid='no' data-error="Please select district" style="width:200px;" > 
<?php  
while($row = $rs->fetch(PDO::FETCH_BOTH))
{
	$distname=$row["district_name"];
	$disttname=$row["district_tname"];
	if($_SESSION["lang"]=='E'){
		$dist_name=$distname;
	}else{
		$dist_name=$disttname;
	}
	if($distid==$qua_row["district_id"]) {
		print("<option value='".$qua_row["district_id"]."' selected='selected'>".$dist_name."</option>");
	}
}
?>
</select>
<?php 
}

if($source_frm=='griev_details')
{  
$griev_code=stripQuotes(killChars($_POST['griev_code']));
$maj_code=substr($griev_code,0,2);
//echo $maj_code; ?>

          
           <tr>
           
			<td> 
			Grievance Main Category </td>
			<td>
<?php
            $sql = "SELECT griev_type_id,dept_id FROM lkp_griev_type where griev_type_code='$maj_code'";
			//echo $sql;
			$rs=$db->query($sql);
			$row = $rs->fetch(PDO::FETCH_BOTH);
			$gre_type_id=$row[0]; 
			 
			
			if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating()){
				 
				$gre_sql = "-- user of a coordinating dept. and cordinating office
							SELECT DISTINCT on (griev_type_id) griev_type_id, griev_type_code, griev_type_name, griev_type_tname  FROM vw_usr_dept_griev_subtype";
			}
			else  
			{
			 	$gre_sql = "SELECT DISTINCT on (griev_type_id) griev_type_id, griev_type_code, griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE dept_id = ".$userProfile->getDept_id()."";		
			}
			
			$gre_rs=$db->query($gre_sql);
			if(!$gre_rs)
			{
			print_r($db->errorInfo());
			exit;
			}		
			?>
			<select name="griev_maincode" id="griev_maincode" data_valid='yes'  onChange="get_sub_category();"  data-error="Please select maincategory">
			<option value="">--Select--</option>
			<?php  
			while($gre_row = $gre_rs->fetch(PDO::FETCH_BOTH))
			{
			$grename=$gre_row["griev_type_name"];
			$gretname=$gre_row["griev_type_tname"];
			if($_SESSION["lang"]=='E'){
			$gre_name=$grename;
			}else{
			$gre_name=$gretname;	
			}
			if($gre_type_id==$gre_row["griev_type_id"]){
			print("<option value='".$gre_row["griev_type_id"]."' selected='selected'>".$gre_name."</option>");
			}
			else{
			print("<option value='".$gre_row["griev_type_id"]."' >".$gre_name."</option>");
			}
			
			}
			?>
			</select>
			</td>
		  
			
			<!--------------------------------- FOR SUB CATEGORY -------------------------->
           
			<td>Grievance Sub Category  <span class="star">*</span></td>
			<td>
			<?php
			
			 $sql1 = "SELECT griev_subtype_id FROM lkp_griev_subtype where griev_subtype_code='$griev_code'";
			$rs1=$db->query($sql1);
			$row1 = $rs1->fetch(PDO::FETCH_BOTH);
			$gre_subtype_id=$row1[0];
			
			if($gre_type_id!=""){
			$gre_sub_sql = "select griev_subtype_id,griev_subtype_name,griev_subtype_tname from lkp_griev_subtype where griev_type_id='$gre_type_id' order by griev_subtype_name";
			$gre_sub_rs=$db->query($gre_sub_sql);
				if(!$gre_sub_rs)
				{
				print_r($db->errorInfo());
				exit;
				}	
			}	
			?>
			 <span id="div_sub_category">
			<select name="griev_subcode" id="griev_subcode" onchange="get_griev_code();" data_valid='yes'  data-error="Please select subcategory" class="select_style">
			<option value="">--Select--</option>
			<?php  
			while($gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH))
			{
				$gresub_typename=$gre_sub_row["griev_subtype_name"];
				$gresub_typetname=$gre_sub_row["griev_subtype_tname"];
				if($_SESSION["lang"]=='E'){
				$gre_sub_type_name=$gresub_typename;
				}else{
				$gre_sub_type_name=$gresub_typetname;	
				}
				if($gre_subtype_id==$gre_sub_row["griev_subtype_id"])
				{
				  print("<option value='".$gre_sub_row["griev_subtype_id"]."' selected='selected'>
				  ".$gre_sub_type_name."</option>");
				}
				else
				{
				   print("<option value='".$gre_sub_row["griev_subtype_id"]."' >".$gre_sub_type_name."</option>");
				}
			}
			?>
			</select>
		</span></td>
        </tr>
         
        
              
               
<?php
}
?>
<?php
if($source_frm=='griev_maindetails') {
	$griev_code=stripQuotes(killChars($_POST['griev_code']));
	$maj_code=substr($griev_code,0,2);
	$sql = "SELECT griev_type_id,dept_id FROM lkp_griev_type where griev_type_code='$maj_code'";
			echo $sql;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$gre_type_id=$row[0];
	if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating()){
				 
		$gre_sql = "-- user of a coordinating dept. and cordinating office
				SELECT DISTINCT on (griev_type_id) griev_type_id, griev_type_code, 
				griev_type_name, griev_type_tname  FROM vw_usr_dept_griev_subtype 
				where ".$userProfile->getOff_level_id()."=any(off_level_id)";
	}
	else  
	{
		$gre_sql = "SELECT DISTINCT on (griev_type_id) griev_type_id, griev_type_code, 
		griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype 
		WHERE dept_id = ".$userProfile->getDept_id()." and ".$userProfile->getOff_level_id()."=any(off_level_id)";		
	}
	echo $gre_sql;
	$gre_rs=$db->query($gre_sql);
	if(!$gre_rs)
	{
		print_r($db->errorInfo());
		exit;
	}		
	?>
	<select name="griev_maincode" id="griev_maincode" data_valid='no'  onChange="get_sub_category();"  data-error="Please select maincategory">
	<option value="">--Select--</option>
	<?php
	while($gre_row = $gre_rs->fetch(PDO::FETCH_BOTH))
			{
				$griev_type_name=$gre_row["griev_type_name"];
				$griev_type_tname=$gre_row["griev_type_tname"];
				if($_SESSION["lang"]=='E'){
				$griev_type_name=$griev_type_name;
				}else{
				$griev_type_name=$griev_type_tname;	
				}
				if($gre_type_id==$gre_row["griev_type_id"])
				{
				  print("<option value='".$gre_row["griev_type_id"]."' selected> ".$griev_type_name."</option>");
				}
				else
				{
				   print("<option value='".$gre_row["griev_type_id"]."' >".$griev_type_name."</option>");
				}
			}?>
			</select>

			<?php
}

?>


<?php
if($source_frm=='griev_subdetails') {
	$griev_code=stripQuotes(killChars($_POST['griev_code']));
	$maj_code=substr($griev_code,0,2);
	
	$sql = "SELECT griev_type_id,dept_id FROM lkp_griev_type where griev_type_code='$maj_code'";
			$rs=$db->query($sql);
			$row = $rs->fetch(PDO::FETCH_BOTH);
			$gre_type_id=$row[0];
			
   $sql1 = "SELECT griev_subtype_id FROM lkp_griev_subtype where griev_subtype_code='$griev_code'";
			$rs1=$db->query($sql1);
			$row1 = $rs1->fetch(PDO::FETCH_BOTH);
			$gre_subtype_id=$row1[0];
	if($gre_type_id!=""){
			$gre_sub_sql = "select griev_subtype_id,griev_subtype_name,griev_subtype_tname from lkp_griev_subtype where griev_type_id='$gre_type_id' order by griev_subtype_name";
			$gre_sub_rs=$db->query($gre_sub_sql);
				if(!$gre_sub_rs)
				{
				print_r($db->errorInfo());
				exit;
				}	
			}
	$gre_sub_rs=$db->query($gre_sub_sql);
	if(!$gre_sub_rs)
				{
				print_r($db->errorInfo());
				exit;
				}		
	?>
	<select name="griev_subcode" id="griev_subcode" onchange="get_griev_code();" data_valid='no'  data-error="Please select subcategory" class="select_style">
			<option value="">--Select--</option>
	<?php
	while($gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH))
			{
				$gresub_typename=$gre_sub_row["griev_subtype_name"];
				$gresub_typetname=$gre_sub_row["griev_subtype_tname"];
				if($_SESSION["lang"]=='E'){
				$gre_sub_type_name=$gresub_typename;
				}else{
				$gre_sub_type_name=$gresub_typetname;	
				}
				if($gre_subtype_id==$gre_sub_row["griev_subtype_id"])
				{
				  print("<option value='".$gre_sub_row["griev_subtype_id"]."' selected='selected'>
				  ".$gre_sub_type_name."</option>");
				}
				else
				{
				   print("<option value='".$gre_sub_row["griev_subtype_id"]."' >".$gre_sub_type_name."</option>");
				}
			}
			?>
			</select>

			<?php
}

?>
<?php
if($source_frm=='griev_subcategory')
{  
 
 $griev_main_id=stripQuotes(killChars($_POST['griev_main_code']));
 $gsval = $_POST['gsval'];
		/*if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating()){
			$gre_sub_sql = "SELECT griev_subtype_id, griev_subtype_code, 
			griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
			WHERE griev_type_id=".$griev_main_id." ORDER BY griev_subtype_name";
		}else{
			$gre_sub_sql = "SELECT distinct(griev_subtype_id), griev_subtype_code, 
			griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
			WHERE dept_id = ".$userProfile->getDept_id()." and griev_type_id=".$griev_main_id."
			ORDER BY griev_subtype_name";
		}*/
		$gre_sub_sql = "SELECT griev_subtype_id, griev_subtype_code, griev_subtype_name, griev_subtype_tname FROM lkp_griev_subtype
		WHERE griev_type_id=".$griev_main_id." ORDER BY griev_subtype_name";
						$gre_sub_rs=$db->query($gre_sub_sql);
						if(!$gre_sub_rs)
						{
						print_r($db->errorInfo());
						exit;
						}
		           ?>
			
			<select name="griev_subcode" id="griev_subcode"  onchange="get_griev_code(); " data_valid='yes'  data-error="Please select subcategory" class="select_style">
			<option value="">--Select Petition Sub Category--</option>
			<?php  
			while($gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH))
			{
			$gresub_typename=$gre_sub_row["griev_subtype_name"];
			$gresub_typetname=$gre_sub_row["griev_subtype_tname"];
			if($_SESSION["lang"]=='E'){
			$gre_sub_type_name=$gresub_typename;
			}else{
			$gre_sub_type_name=$gresub_typetname;	
			}
			if ($gsval == $gre_sub_row["griev_subtype_id"])
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' selected>".$gre_sub_type_name."</option>");
			else	
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' >".$gre_sub_type_name."</option>");
			}
			?>
			</select>
       
<?php
}

if($source_frm=='griev_sub_category')
{  
 
 $griev_main_id=stripQuotes(killChars($_POST['griev_main_code']));
 $griev_subtype_id=stripQuotes(killChars($_POST['griev_subtype_id']));
 $gsval = $_POST['gsval'];
		/*if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating()){
			$gre_sub_sql = "SELECT griev_subtype_id, griev_subtype_code, 
			griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
			WHERE griev_type_id=".$griev_main_id." ORDER BY griev_subtype_name";
		}else{
			$gre_sub_sql = "SELECT distinct(griev_subtype_id), griev_subtype_code, 
			griev_subtype_name, griev_subtype_tname FROM vw_usr_dept_griev_subtype
			WHERE dept_id = ".$userProfile->getDept_id()." and griev_type_id=".$griev_main_id."
			ORDER BY griev_subtype_name";
		}*/
		$gre_sub_sql = "SELECT griev_subtype_id, griev_subtype_code, griev_subtype_name, griev_subtype_tname FROM lkp_griev_subtype
		WHERE griev_type_id=".$griev_main_id." ORDER BY griev_subtype_name";
						$gre_sub_rs=$db->query($gre_sub_sql);
						if(!$gre_sub_rs)
						{
						print_r($db->errorInfo());
						exit;
						}
		           ?>
			
			<select name="griev_subcode" id="griev_subcode"  onchange="get_griev_code(); " data_valid='yes'  data-error="Please select subcategory" class="select_style">
			<option value="">--Select Petition Sub Category--</option>
			<?php  
			while($gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH))
			{
			$gresub_typename=$gre_sub_row["griev_subtype_name"];
			$gresub_typetname=$gre_sub_row["griev_subtype_tname"];
			if($_SESSION["lang"]=='E'){
			$gre_sub_type_name=$gresub_typename;
			}else{
			$gre_sub_type_name=$gresub_typetname;	
			}
			if ($griev_subtype_id == $gre_sub_row["griev_subtype_id"])
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' selected>".$gre_sub_type_name."</option>");
			else	
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' >".$gre_sub_type_name."</option>");
			}
			?>
			</select>
       
<?php
}
?>
<?php
if($source_frm=='griev_dept')  //get officer list
{
	$griev_sub_id=stripQuotes(killChars($_POST['griev_sub_id']));
	$hid_pattern_id=stripQuotes(killChars($_POST['hid_pattern_id'])); // petition dept pattern
	$griv_loc_off_level_id=stripQuotes(killChars($_POST['griv_loc_off_level_id']));
	$off_loc_id=stripQuotes(killChars($_POST['loc_id'])); // last level office location of the petition
	$department_id=stripQuotes(killChars($_POST['department_id']));
	$dist_id=stripQuotes(killChars($_POST['dist_id']));
	$disp_officer=stripQuotes(killChars($_POST['disp_officer']));
	$source_id=stripQuotes(killChars($_POST['source_id']));
	
	$disposing_officer_off_level_id=stripQuotes(killChars($_POST['disposing_officer_off_level_id']));
	$disposing_officer_off_loc_id=stripQuotes(killChars($_POST['disposing_officer_off_loc_id']));
	$pet_process=stripQuotes(killChars($_POST['pet_process']));														
	
	$disposing_officer_off_level_id = ($disposing_officer_off_level_id == '') ? $userProfile->getOff_level_id() : $disposing_officer_off_level_id;
	
	$disposing_officer_off_loc_id = ($disposing_officer_off_loc_id == '') ? $userProfile->getOff_loc_id() : $disposing_officer_off_loc_id;
	// officer code directly entered and used to populate the concerned officer name
	$off_d_id=stripQuotes(killChars($_POST['off_d_id']));

	if ($disp_officer == '') {
		$disp_officer = $userProfile->getDept_user_id();
	}
	$disp_officer_dept_sql = "select c.dept_id as dept_id from usr_dept_users a inner join usr_dept_desig b on b.dept_desig_id=a.dept_desig_id inner join usr_dept_off_level c on c.off_level_dept_id=b.off_level_dept_id where a.dept_user_id=".$disp_officer."";
	$disp_officer_dept_rs=$db->query($disp_officer_dept_sql);						  
	while($disp_officer_dept_row = $disp_officer_dept_rs->fetch(PDO::FETCH_BOTH)) {
		$disp_officer_dept_id=$disp_officer_dept_row["dept_id"];
	}						  

	$off_level_cond0='('.$disposing_officer_off_level_id.')';

	//$dept_coord_cond - not to allow the collectorate revenue dept. (i.e. coordinating dept.) officials to appear in the concerned officer dropdown
	$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $userProfile->getOff_coordinating() == 1 && $userProfile->getDept_id() == $department_id && $disp_officer_dept_id == $department_id && $userProfile->getOff_level_id()==2) ? ' and (not true) ':' and not coalesce(desig_coordinating,false) ';
	

	$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $userProfile->getOff_coordinating() == 1 && $userProfile->getDept_id() == $department_id && $disp_officer_dept_id == $department_id && $userProfile->getOff_level_id()==2 ) ? ' and (not true) ':'';
	
	$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $disposing_officer_off_level_id==2 && $disp_officer_dept_id == $department_id ) ? ' and (not true) ':'';
	
	$dept_coord_cond = '';

	if ($pet_process == 'D') {
		$disposal_condition = ' and pet_disposal ';
	}
	if ($userProfile->getOff_level_id()==1) {
		$off_level_cond='(2)';
		$off_hier_pos=2;
		$off_hier_loc= ($dist_id == '') ? $disposing_officer_off_loc_id : $dist_id;
		$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." and desig_coordinating ) or ";
		$sup_off_cond = '';
		$union_condition="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
		from vw_usr_dept_users_v_sup
		where off_hier[2] = ".$off_hier_loc." and off_level_id = 2 and pet_act_ret and dept_pet_process and off_pet_process ".$disposal_condition." and dept_desig_id=s_dept_desig_id and desig_coordinating and coalesce(enabling,true)

		union";
	}
	else if ($userProfile->getOff_level_id()==2 || $disposing_officer_off_level_id == 2) {
		$off_hier_pos=$disposing_officer_off_level_id;
		$off_hier_loc=$disposing_officer_off_loc_id;
		$hier_cond= " ";
		$sup_off_cond = " and (sup_off_loc_id1=".$disposing_officer_off_loc_id." or sup_off_loc_id2=".$disposing_officer_off_loc_id." or off_loc_id=".$off_loc_id.") ";
		$union_condition="";
	}
	else{
		$off_hier_pos=$disposing_officer_off_level_id;
		$off_hier_loc=$disposing_officer_off_loc_id;
		$hier_cond= " ";
		$sup_off_cond = " and (sup_off_loc_id1=".$disposing_officer_off_loc_id." or sup_off_loc_id2=".$disposing_officer_off_loc_id." or off_loc_id=".$off_loc_id.") ";
		$union_condition="";
	}

	$vw_usr_name='';
	if ($hid_pattern_id==1) {
		$vw_usr_name='vw_usr_dept_users_v_sup_p1';
		$vw_usr_filter=" (off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") 
		or row(off_level_id,off_loc_id) = any		
		(
		case ".$disposing_officer_off_level_id." -- logged in user's off level id 
		when 1 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$off_loc_id." union select row(2,district_id) from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id." union select row(2,b.district_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id.") 
			end
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select row(2,district_id) from mst_p_rdo where rdo_id=".$off_loc_id.")  
			when 4 then array(select row(3,rdo_id) from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$off_loc_id." union select row(3,b.rdo_id) from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id.") 
			end 
		when 3 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 4 then array(select row(4,taluk_id) from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select row(4,taluk_id) from mst_p_rev_village where rev_village_id=".$off_loc_id.") 
			end 
		when 4 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 8 then array(select row(5,firka_id) from mst_p_rev_village where rev_village_id=".$off_loc_id.") 
			end 
		else null end
		)";
	}
	else if ($hid_pattern_id==2) {
		$vw_usr_name='vw_usr_dept_users_v_sup_p2';
		$vw_usr_filter=" (off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any
		(
		case ".$disposing_officer_off_level_id." -- logged in user's off level id 
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 6 then array(select row(2,district_id) from mst_p_lb_block where block_id=".$off_loc_id.")  
			when 9 then array(select row(6,block_id) from mst_p_lb_village where lb_village_id=".$off_loc_id.")  
			end 
		else null end			
		)";
	}
	else if ($hid_pattern_id==3) {
		$vw_usr_name='vw_usr_dept_users_v_sup_p3';
		$vw_usr_filter=" ((off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or row(off_level_id,off_loc_id)=any(
		case ".$disposing_officer_off_level_id." -- logged in user's off level id 
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 7 then array(select row(2,district_id) from mst_p_lb_urban where lb_urban_id=".$off_loc_id.")  
			end 
		else null end			
		) )";
	}
	else if ($hid_pattern_id==4) {
		$vw_usr_name='vw_usr_dept_users_v_sup_p4';
		$vw_usr_filter=" dept_id=".$department_id." -- for Special pattern pattern; 3 is the division_id from the pet_master record
		and ((case when ".$griv_loc_off_level_id." = 10 and exists (select 1 from mst_p_sp_division where division_id=".$off_loc_id." and dept_id=-99) then (row(off_level_id,off_loc_id) = row(".$userProfile->getOff_level_id().",".$userProfile->getOff_loc_id()."))
		else (row(off_level_id,off_loc_id) = row(".$griv_loc_off_level_id.",".$off_loc_id."))
		end) or row(off_level_id,off_loc_id) = any (
		case ".$disposing_officer_off_level_id." -- logged in user's off level id 
		when 1 then 
			case ".$griv_loc_off_level_id."
			when 10 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id.")
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id." union select row(2,district_id) from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$off_loc_id." and a.dept_id=".$department_id.") 
			end  
		when 2 then 
			case ".$griv_loc_off_level_id."
			when 11 then array(select row(10,division_id) from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id.")  
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id." union select row(10,b.division_id) from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$off_loc_id." and a.dept_id=".$department_id.") 
			end  
		when 10 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 12 then array(select row(11,subdivision_id) from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id.") 
			end 
		else null end
		) )";
	}

	$insql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
	from ".$vw_usr_name."
	where off_hier[".$disposing_officer_off_level_id."] = ".$disposing_officer_off_loc_id." 
	and off_level_id in ".$off_level_cond0." 
	and pet_act_ret and dept_pet_process and off_pet_process ".$disposal_condition.$dept_coord_cond." and
	case
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then dept_id=".$disp_officer_dept_id." -- and dept_desig_id<>s_dept_desig_id
	else dept_id=".$department_id." 
	end
	and dept_user_id!=".$_SESSION['USER_ID_PK']." and coalesce(enabling,true)

	union
	
	".$union_condition."
	
	
	select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
	from ".$vw_usr_name." 
	where ".$hier_cond." 
	dept_id=".$department_id.$sup_off_cond." and off_level_id >= ".$disposing_officer_off_level_id." 
	and ( ".$vw_usr_filter." )

	and dept_pet_process and off_pet_process and pet_act_ret ".$disposal_condition." and ((dept_desig_id=s_dept_desig_id and off_level_id>".$disposing_officer_off_level_id.") or
	(off_level_id=".$disposing_officer_off_level_id." and
	case
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then true
	else false
	end
	)
	) and coalesce(enabling,true)
	
	union
	
	select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
	from ".$vw_usr_name." a
	where a.dept_id=".$department_id." and dept_pet_process ".$disposal_condition." and off_pet_process and pet_act_ret and exists (select 1 from usr_fwd_users_loc_mapping b where b.dept_id_logged_in=".$disp_officer_dept_id." and b.off_level_id_logged_in=".$disposing_officer_off_level_id." and ".$disposing_officer_off_loc_id."=any(b.off_loc_id_logged_in) and b.dept_id_concerned=".$department_id." and b.off_level_id_concerned>=".$disposing_officer_off_level_id." and a.off_loc_id=any(b.off_loc_id_concerned) and a.dept_desig_id=any(b.dept_desig_id_concerned))
	and coalesce(enabling,true)
	";
	
	if ($source_id ==5 && $department_id == 1) {
		$insql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
		from vw_usr_dept_users_v_sup_p1 where dept_user_id=".$disp_officer." and pet_disposal and pet_act_ret";
	}
  //echo $insql;	
		$off_sql="select * from (".$insql.") abc order by dept_id,off_level_id,off_level_dept_id,off_loc_name,dept_desig_name";
		$off_rs=$db->query($off_sql);
		if(!$off_rs)
		{
		print_r($db->errorInfo());
		}	
		           ?>
			
			<select name="concerned_officer" id="concerned_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style">
			<option value="">--Select--</option>
			<?php  
			$prev_dept_id= '';
			while($off_row = $off_rs->fetch(PDO::FETCH_BOTH))
			{
				if ($prev_dept_id <> $off_row["off_level_dept_id"]) {
					print("<optgroup label='".$off_row["off_level_dept_name"]."'>");
				}
					
			$con_off_ename=$off_row["dept_desig_name"].', '.$off_row["off_level_dept_name"].', '.$off_row["off_loc_name"];
			$con_off_tname=$off_row["dept_desig_tname"].', '.$off_row["off_level_dept_tname"].', '.$off_row["off_loc_tname"];
			if($_SESSION["lang"]=='E'){
			$con_officer_ename=$con_off_ename;
			}else{
			$con_officer_ename=$con_off_tname;	
			}
			if ($off_d_id ==$off_row["dept_user_id"] )
			print("<option value='".$off_row["dept_user_id"]."' selected>".$con_officer_ename."</option>");
			else
			print("<option value='".$off_row["dept_user_id"]."'>".$con_officer_ename."</option>");
			$prev_dept_id=$off_row["off_level_dept_id"];
			}?>
			</select>
	   
<?php
}
?>
<?php
if($source_frm=='get_officer_onload')  //get officer list
{ 
//echo "get_officer_onload############";
	
$griev_sub_id=stripQuotes(killChars($_POST['griev_sub_id']));
$hid_pattern_id=stripQuotes(killChars($_POST['hid_pattern_id']));
$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
$off_loc_id=stripQuotes(killChars($_POST['loc_id']));
$department_id=stripQuotes(killChars($_POST['department_id']));
$whom=stripQuotes(killChars($_POST['whom']));
$dist_id=stripQuotes(killChars($_POST['dist_id']));
$source_id=stripQuotes(killChars($_POST['source_id']));
$griv_loc_off_level_id=stripQuotes(killChars($_POST['griv_loc_off_level_id']));

/*
{"Form data":{"source_frm":"get_officer_onload","griev_sub_id":"505","hid_pattern_id":"4","loc_id":"","department_id":"15","off_level_id":"1","whom":"19248","dist_id":"","source_id":"29","griv_loc_off_level_id":"10"}}

*/

if ($disp_officer == '') {
	$disp_officer = $userProfile->getDept_user_id();
}
$dept_coord_cond = ($userProfile->getDept_coordinating() == 1 && $userProfile->getOff_coordinating() == 1 && $userProfile->getDept_id() == $department_id && $userProfile->getOff_level_id()==2) ? ' and (not true) ':'';
	$off_level_cond0='('.$userProfile->getOff_level_id().')';
	if ($userProfile->getOff_level_id()==1) {
		$off_level_cond='(2)';
		$off_hier_pos=2;
		$off_hier_loc=($dist_id == '') ? $userProfile->getOff_loc_id() : $dist_id;
		$hier_cond= " (off_hier[".$off_hier_pos."] = ".$off_hier_loc." and off_level_id in ".$off_level_cond." and desig_coordinating ) or ";
		$sup_off_cond = '';
		
		
	}
	else if ($userProfile->getOff_level_id()==2) {
		$off_level_cond='(2,3,4,6,7,10,11,12)';
		$off_hier_pos=$userProfile->getOff_level_id();
		$off_hier_loc=$userProfile->getOff_loc_id();
		$hier_cond= " ";
		$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$off_loc_id.") ";
	}
	else{
		$off_level_cond='(2,3,4,6,7,10,11,12)';
		$off_hier_pos=$userProfile->getOff_level_id();	
		$off_hier_loc=$userProfile->getOff_loc_id();
		$hier_cond= " ";
		$sup_off_cond = " and (sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()." or off_loc_id=".$off_loc_id.") ";
		
	}

	$disp_officer_dept_sql = "select c.dept_id as dept_id from usr_dept_users a inner join usr_dept_desig b on b.dept_desig_id=a.dept_desig_id inner join usr_dept_off_level c on c.off_level_dept_id=b.off_level_dept_id where a.dept_user_id=".$disp_officer."";
	$disp_officer_dept_rs=$db->query($disp_officer_dept_sql);						  
	while($disp_officer_dept_row = $disp_officer_dept_rs->fetch(PDO::FETCH_BOTH)) {
		$disp_officer_dept=$disp_officer_dept_row["dept_id"];
	}						  
	if ($griv_loc_off_level_id == 10) {
		$sql="select dept_id from mst_p_sp_division where division_id=".$off_loc_id."";
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {
			$dept_id=$row["dept_id"];
		}
		if ($dept_id == -99) {
			$griv_loc_off_level_id = $userProfile->getOff_level_id();
			$off_loc_id = $userProfile->getOff_loc_id();
		}
	}

/*
"loc_id":""; "dist_id":""
off_loc_id ; dist_id
*/
	
	$insql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id
	from vw_usr_dept_users_v_sup 
	where off_hier[".$userProfile->getOff_level_id()."] = ".$userProfile->getOff_loc_id()." 
	and off_level_id in ".$off_level_cond0." 
	and pet_act_ret and dept_pet_process and off_pet_process ".$dept_coord_cond." and
	case
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then dept_id=".$disp_officer_dept." -- and dept_desig_id<>s_dept_desig_id
	else dept_id=".$department_id." 
	end
	and dept_user_id!=".$_SESSION['USER_ID_PK']." and dept_user_id<>".$disp_officer." 

	union
	
	select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname,off_level_id, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
	from vw_usr_dept_users_v_sup 
	where ".$hier_cond." (off_hier[".$off_hier_pos."] = ".$off_hier_loc." 
	--added for HRCE purpose Nov 08, 2018
	or
	case ".$griv_loc_off_level_id."
	when 11 then off_hier[".$griv_loc_off_level_id."]=".$off_loc_id."
	when 12 then off_hier[".$griv_loc_off_level_id."]=".$off_loc_id."
	else null
	end
	or 
	case ".$griv_loc_off_level_id."
	when 12 then off_hier[11]=(select subdivision_id from mst_p_sp_circle where circle_id=".$off_loc_id.")
	else null
	end
	--added for HRCE purpose Nov 08, 2018
	) 
	and dept_id=".$department_id.$sup_off_cond." 
	and off_level_id >= ".$userProfile->getOff_level_id()." 
	and ( 
	
	case ".$hid_pattern_id." -- grievance location's office level pattern 
		
		when 1 then (off_level_pattern_id = 1 and ((off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or off_loc_id = any
		
		(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		when 1 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select district_id from mst_p_rdo where rdo_id=".$off_loc_id.")  
			when 4 then array(select rdo_id from mst_p_taluk where taluk_id=".$off_loc_id." union select district_id from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select taluk_id from mst_p_rev_village where rev_village_id=".$off_loc_id." union select b.rdo_id from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id." union select b.district_id from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 3 then array(select district_id from mst_p_rdo where rdo_id=".$off_loc_id.")  
			when 4 then array(select rdo_id from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select taluk_id from mst_p_rev_village where rev_village_id=".$off_loc_id." union select b.rdo_id from mst_p_rev_village a inner join mst_p_taluk b on b.taluk_id=a.taluk_id where a.rev_village_id=".$off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		when 3 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 4 then array(select taluk_id from mst_p_taluk where taluk_id=".$off_loc_id.")  
			when 8 then array(select taluk_id from mst_p_rev_village where rev_village_id=".$off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		when 4 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 8 then array(select firka_id from mst_p_rev_village where rev_village_id=".$off_loc_id.") 
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		
		else null end
		) ) )-- for revenue pattern; 3 is the taluk_id from the pet_master record
			
		when 2 then (off_level_pattern_id = 2 and ((off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or off_loc_id=any
		(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 6 then array(select district_id from mst_p_lb_block where block_id=".$off_loc_id.")  
			when 9 then array(select block_id from mst_p_lb_village where lb_village_id=".$off_loc_id.")  
			-- rev_village_id=1 : pet_master ; dept_id=1 : pet_master 
			end 
		
		else null end			
		) ) )-- for rural pattern; 3 is the block_id from the pet_master record 
	
		when 3 then (off_level_pattern_id = 3 and ((off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or off_loc_id=any(
		case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
		
		when 2 then 
			case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
			when 7 then array(select district_id from mst_p_lb_urban where lb_urban_id=".$off_loc_id.")  
			end 
		
		else null end			
		) ) )
		-- for urban pattern; 3 is the griev_lb_urban_id from the pet_master record 
	
		when 4 then (off_level_pattern_id = 4 and dept_id=".$department_id." -- for Special pattern pattern; 3 is the division_id from the pet_master record
		and ((off_loc_id = ".$off_loc_id." and off_level_id = ".$griv_loc_off_level_id.") or off_loc_id = any (
	
			case ".$userProfile->getOff_level_id()." -- logged in user's off level id 
			when 1 then 
				case ".$griv_loc_off_level_id."
				when 10 then array(select division_id from mst_p_sp_division where division_id=".$off_loc_id." and dept_id=".$department_id." union select district_id from mst_p_sp_division where division_id=".$off_loc_id." and dept_id=-99)
				when 11 then array(select division_id from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id." union select district_id from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id.")  
				when 12 then array(select subdivision_id from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id." union select b.division_id from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$off_loc_id." and a.dept_id=".$department_id.") 
				-- division_id=17 : pet_master ; dept_id=9 : pet_master 
				end  
			when 2 then 
				case ".$griv_loc_off_level_id."
				when 11 then array(select division_id from mst_p_sp_subdivision where subdivision_id=".$off_loc_id." and dept_id=".$department_id.")  
				when 12 then array(select subdivision_id from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id." union select b.division_id from mst_p_sp_circle a inner join  mst_p_sp_subdivision b on b.subdivision_id=a.subdivision_id where a.circle_id=".$off_loc_id." and a.dept_id=".$department_id.") 
				-- division_id=17 : pet_master ; dept_id=9 : pet_master 
				end  
			when 10 then 
				case ".$griv_loc_off_level_id." -- griv_loc_off_level_id
				when 12 then array(select subdivision_id from mst_p_sp_circle where circle_id=".$off_loc_id." and dept_id=".$department_id.") 
				-- division_id=17 : pet_master ; dept_id=9 : pet_master 
				end 
			else null end
		)
		) )
	else true end ) 
	and dept_pet_process and off_pet_process and pet_act_ret and ((dept_desig_id=s_dept_desig_id and off_level_id>".$userProfile->getOff_level_id().") or
	(off_level_id=".$userProfile->getOff_level_id()." and
	case
	when (select dept_desig_id from usr_dept_users where dept_user_id=".$disp_officer.")=(select dept_desig_id from usr_dept_desig_disp_sources where source_id=".$source_id.") then false
	else true
	end
	)
	) and dept_user_id<>".$disp_officer."";
		$off_sql="select * from (".$insql.") abc order by dept_id,off_level_id,off_level_dept_id,off_loc_name,dept_desig_name";
	//echo $insql;				 
		$off_rs=$db->query($off_sql);
		if(!$off_rs)
		{
		print_r($db->errorInfo());
		}	
   ?>
			
			<select name="concerned_officer" id="concerned_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style">
			<option value="">--Select--</option>
			<?php  
			$prev_dept_id= '';
			while($off_row = $off_rs->fetch(PDO::FETCH_BOTH))
			{
				if ($prev_dept_id <> $off_row["off_level_dept_id"]) {
					print("<optgroup label='".$off_row["off_level_dept_name"]."'>");
				}
			$con_off_ename=$off_row["dept_desig_name"].', '.$off_row["off_level_dept_name"].', '.$off_row["off_loc_name"];
			$con_off_tname=$off_row["dept_desig_tname"].', '.$off_row["off_level_dept_tname"].', '.$off_row["off_loc_tname"];
			if($_SESSION["lang"]=='E'){
			$con_officer_ename=$con_off_ename;
			}else{
			$con_officer_ename=$con_off_tname;	
			}
			if ($whom == $off_row["dept_user_id"] )
			print("<option value='".$off_row["dept_user_id"]."' selected>".$con_officer_ename."</option>");
			else
			print("<option value='".$off_row["dept_user_id"]."'>".$con_officer_ename."</option>");
			$prev_dept_id = $off_row["off_level_dept_id"];
			}
			?>
			</select>
	   
<?php
}
?>
<?php
//Grievance Code to show it in the textbox.
if($source_frm=='get_griev_code')
{ 
$griev_sub_code=stripQuotes(killChars($_POST['griev_sub_code']));
  
  $gre_sub_sql = "select griev_subtype_code from lkp_griev_subtype where griev_subtype_id='$griev_sub_code'";
			$rs1=$db->query($gre_sub_sql);
			$row1 = $rs1->fetch(PDO::FETCH_BOTH);
			$gre_subtype_id=$row1[0];
	?>	
		 <input type="text" name="griev_code" id="griev_code" value="<?php echo $gre_subtype_id; ?>" data_valid='no' data-error="Please enter grievance code" class="select_style" onchange="get_griev_detais();" maxlength="4" onKeyPress="return numbersonly(event);" />
<?php }
 ?>
 
<?php
//*********************** To fetch the sub source details *************************************//
if($source_frm == 'get_sub_source')
{
	$source_id = stripQuotes(killChars($_POST['source_id']));
	
	      $sub_source_sql = "-- petition form: subsources combo

						SELECT a.subsource_id, b.subsource_name, b.subsource_tname
						FROM usr_dept_desig_sources a
						JOIN lkp_pet_subsource b ON b.subsource_id = a.subsource_id
						WHERE a.dept_desig_id = ".$userProfile->getDept_desig_id()." and a.source_id=".$source_id." 
						and a.subsource_id is not null
						-- subsources are specifically given

						UNION

						SELECT a.subsource_id, a.subsource_name, a.subsource_tname
						FROM lkp_pet_subsource a WHERE EXISTS (SELECT * FROM usr_dept_desig_sources b where 
						b.dept_desig_id = ".$userProfile->getDept_desig_id()." and b.source_id=a.source_id 
						and b.subsource_id is null and a.source_id=".$source_id.")  ORDER BY subsource_name
						-- subsources are not specifically given";
	
	$res = $db->query($sub_source_sql);
	
	if ($res->rowCount() == 0){	
	
	?>
	<select name="sub_source" id="sub_source" data_valid='no' data-error="Please select Source" class="select_style" disabled>
			<option value="">--Select--</option>
    </select>
     <?php 
	 } else { 
	  
	 ?>

	<select name="sub_source" id="sub_source" data_valid='no' data-error="Please select Source" class="select_style">
			<option value="">--Select--</option>
    <?php
		while($row = $res->fetch(PDO::FETCH_BOTH))
		{
			$subsource_id = $row[0];
			if($_SESSION["lang"]=='E'){
			$subsource_name = $row[1];
			}else{
			$subsource_name = $row[2];
			}
			print("<option value='".$subsource_id."' >".$subsource_name."</option>");	
		}
	?>        
    </select>
<?php	
	 }
}
?>
<?php 
if($source_frm=='get_dept')
{  
			$griev_sub_code = stripQuotes(killChars($_POST['griev_sub_code']));
			 
			if($userProfile->getDept_coordinating()&& $userProfile->getOff_coordinating())
			{
				$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
				FROM usr_dept where dept_id>0 ORDER BY dept_name");
				$resultNum = $db->query("SELECT count(*) as no_of_rows FROM usr_dept where dept_id>0 ");
			}
			else 
			{
				$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
				FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name");
				$resultNum = $db->query("SELECT count(*) as no_of_rows FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ");
			 
			}
            	$row = $resultNum->fetch(PDO::FETCH_BOTH);
				$count = $row["no_of_rows"];
            ?>
				<select name="dept" id="dept" data_valid='yes' data-error="Please select taluk" style="width:200px;" onChange="get_pattern_id();">
                <?php if($count > 1){ ?> 
			    <option value="">--Select--</option>
                <? } ?>
                <?php  
					
                while($qua_row = $result->fetch(PDO::FETCH_BOTH))
                {
					$dept_name=$qua_row["dept_name"];
					$dept_tname=$qua_row["dept_tname"];
					
					if($_SESSION["lang"]=='E'){
					$dept_name=$dept_name;
					}else{
					$dept_name=$dept_tname;	
					}
					print("<option value='".$qua_row["dept_id"]."' >".$dept_name."</option>");
                }
                ?>
		        </select>
<?php }

if ($source_frm=='populate_office') {
	$dist = stripQuotes(killChars($_POST['dist']));
	$dept = stripQuotes(killChars($_POST['dept']));  //check for count
	//$hid_gre_division = stripQuotes(killChars($_POST['hid_gre_division']));  //check for count
	$division_condition = ($userProfile->getDivision_id()=="") ? " ": " and a.division_id=".$userProfile->getDivision_id();
	
	$sql = "select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=".$dept.$division_condition.
	" union "." 
	select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
	usr_dept b on a.dept_id=b.dept_id 	where district_id=". $dist." and a.dept_id=-99 and not exists (select 1 from mst_p_sp_subdivision a where district_id=". $dist." and a.dept_id=".$dept.$division_condition.") and not exists (select 1 from mst_p_sp_division a where district_id=". $dist." and a.dept_id=".$dept.$division_condition.")";

	$sql = "(select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=".$dept.$division_condition."  or exists (select 1 from usr_fwd_users_loc_mapping 
	where dept_id_concerned=".$dept." and off_level_id_concerned=10 and a.division_id=any(off_loc_id_concerned)) 
	union "." 
	select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
	usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=-99) order by division_id";
	//echo $sql;
	$rs=$db->query($sql);
	if (!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	?>

    <select name="gre_division" id="gre_division" data_valid="no"  onChange="get_officer_list();get_sub_division();" data-error="Please select office" class="select_style">
	<?php if ($userProfile->getOff_level_id() <= 10 || $rs->rowCount() == 0) { ?>
    <option value="">--Select--</option>
	<?php } ?>
    <?php
    while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$div_name = $row["division_name"];
		$dep_name =$row["dept_name"];
		$div_tname = $row["division_tname"];
		$dep_tname = $row["dept_tname"];
		if($_SESSION["lang"]=='E'){
			$dvname=$div_name." - ".$dep_name;
		}else{
			$dvname=$div_tname." - ".$dep_tname;	
		}
		print("<option value='".$row["division_id"]."'>".$dvname."</option>");	
    	
    }   
	?>
    </select>
    <?php
}
if($source_frm=='populate_office_onload') {
	$dist = stripQuotes(killChars($_POST['dist']));
	$dept = stripQuotes(killChars($_POST['dept']));
	$griev_division_id = stripQuotes(killChars($_POST['griev_division_id']));
	$sql="select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
			usr_dept b on a.dept_id=b.dept_id
			where district_id=". $dist." and a.dept_id=".$dept."";	
			
		$sql="select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
		usr_dept b on a.dept_id=b.dept_id
		where district_id=". $dist." and a.dept_id=".$dept.
		" union "." 
		select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
		usr_dept b on a.dept_id=b.dept_id
		where district_id=". $dist." and a.dept_id=-99";

		$sql = "(select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=".$dept.$division_condition."  or exists (select 1 from usr_fwd_users_loc_mapping 
		where dept_id_concerned=".$dept." and off_level_id_concerned=10 and a.division_id=any(off_loc_id_concerned)) 
		union "." 
		select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
		usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=-99) order by division_id";

	$rs=$db->query($sql);
	if (!$rs) {
		print_r($db->errorInfo());
		exit;
	}

	if ($rs->rowCount() == 0) {
		$sql = "select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
				usr_dept b on a.dept_id=b.dept_id
				where district_id=". $dist." and a.dept_id=10";	
		$rs=$db->query($sql);
		if (!$rs) {
			print_r($db->errorInfo());
			exit;
		}		
	}?>
    <select name="gre_division" id="gre_division" data_valid="no"  onChange="get_officer_list();get_sub_division();" data-error="Please select office" class="select_style">
    <option value="">--Select--</option>
    <?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$div_name = $row["division_name"];
		$dep_name = $row["dept_name"];
		$div_tname = $row["division_tname"];
		$dep_tname = $row["dept_tname"];
		if($_SESSION["lang"]=='E'){
			$dvname=$div_name." - ".$dep_name;
		}else{
			$dvname=$div_tname." - ".$dep_tname;	
		}
		/*if ($griev_division_id==$row["division_id"])
		print("<option value='".$row["division_id"]."' selected>".$dvname."</option>");
		else*/
		print("<option value='".$row["division_id"]."' >".$dvname."</option>");
    	
    } 
	?>
    </select>
    <?php 		
} 

if($source_frm=='populate_subdivision_onload')
{
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
	$dept_id=$_POST["dept"];
	$gre_division=$_POST["griev_division_id"];
	$district_id=$_POST["dist"];
	$griev_subdivision_id=$_POST["griev_subdivision_id"];
	
	$district_condition = ($district_id == '') ? 'and district_id='.$userProfile->getDistrict_id() : 'and district_id='.$district_id;
	
	$division_condition = ($gre_division == '') ? ' and division_id='.$gre_division : '';
	
	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  FROM mst_p_sp_subdivision where division_id=".$gre_division." and dept_id=".$dept_id.$district_condition."";
	
	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  FROM mst_p_sp_subdivision where dept_id=".$dept_id.$district_condition."";
	
	$rs=$db->query($sub_div_sql);
	?>
	<select name="gre_subdivision" id="gre_subdivision"  class="select_style" data_valid='no' onChange="loadCircle();get_officer_list();">
    <option value="">--Select--</option>
	<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$subdivision_id = $row["subdivision_id"];
		$subdivision_name = $row["subdivision_name"];
		$subdivision_tname = $row["subdivision_tname"];
		
		if($_SESSION["lang"]=='E'){
			$subdivision_name=$subdivision_name;
		}else{
			$subdivision_name=$subdivision_tname;	
		}
		if ($griev_subdivision_id == $row["subdivision_id"])
		print("<option value='".$row["subdivision_id"]."' selected>".$subdivision_name."</option>");
		else
		print("<option value='".$row["subdivision_id"]."'>".$subdivision_name."</option>");	
    	
    }
	?>
	</select>
	<?php
	
}

if($source_frm=='populate_circle_onload')
{
	//echo ">>>>>>>>>>>>>>>>>>>";
	$dept_id=$_POST["dept"];
	$gre_division=$_POST["griev_division_id"];
	$district_id=$_POST["dist"];
	$griev_subdivision_id=$_POST["griev_subdivision_id"];
	$griev_circle_id=$_POST["griev_circle_id"];
	$district_condition = ($district_id == '') ? 'and district_id='.$userProfile->getDistrict_id() : 'and district_id='.$district_id;

	?>
	<select name="gre_subdivision" id="gre_subdivision"  data_valid='no' class="select_style" onChange="get_officer_list();">
    <option value="">--Select--</option>
	<?php
	
		
	if ($griev_subdivision_id != '') {
		$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  
		FROM mst_p_sp_circle where subdivision_id=".$griev_subdivision_id." and dept_id=".$dept_id."";	
		$rs=$db->query($sub_div_sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {

			$circle_id = $row["circle_id"];
			$circle_name = $row["circle_name"];
			$circle_tname = $row["circle_tname"];
			
			if($_SESSION["lang"]=='E'){
				$circle_name=$circle_name;
			}else{
				$circle_name=$circle_tname;	
			}
			if ($griev_circle_id == $row["circle_id"])
			print("<option value='".$row["circle_id"]."' selected>".$circle_name."</option>");
			else
			print("<option value='".$row["circle_id"]."'>".$circle_name."</option>");	
			
		}
	}
	?>
	</select>
	<?php
	
}

if($source_frm=='load_p_taluk')
{
	$dist=$_POST["dist"];
	$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$dist." order by taluk_name";
	$rs=$db->query($taluk_sql);
	?>
	<select name="p_taluk" id="p_taluk" class="select_style" data_valid='no' onchange="loadPetRevVillage();">
    <option value="">--Select--</option>
	<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$taluk_id = $row["taluk_id"];
		$taluk_name = $row["taluk_name"];
		$taluk_tname = $row["taluk_tname"];
		
		if($_SESSION["lang"]=='E'){
			$tkname=$taluk_name;
		}else{
			$tkname=$taluk_tname;	
		}
		
		
		print("<option value='".$row["taluk_id"]."' >".$tkname."</option>");
    	
    }
	?>
	</select>
	<?php
	
}

if ($source_frm=='get_disposing_officer') {
	
	$source_id=$_POST["source_id"];
	
	if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
		$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
	} else {
		$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
	}	
	$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
	from vw_usr_dept_users_v_sup a
	inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
	where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true and source_id=".$source_id.$condition."";
	
	echo $sql;
	$rs=$db->query($sql);
	$count=$rs->rowCount();
	?>
	<?php //if ($userProfile->getOff_level_id() == 2 && $source_id == 5) { ?>
		<!--<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style" onChange="getDisposingOfficerDetails();">
		<option value="">--Select--</option>	-->
	<?php //} else { ?>	
		<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style">
	<?php //} ?>
	<?php 
	if ($count > 1) {
	}
	while ($row= $rs->fetch(PDO::FETCH_BOTH)) {
					$con_off_ename=$row["dept_desig_name"].', '.$row["off_level_dept_name"].', '.$row["off_loc_name"];
					$con_off_tname=$row["dept_desig_tname"].', '.$row["off_level_dept_tname"].', '.$row["off_loc_tname"];
					if($_SESSION["lang"]=='E'){
						$con_officer_ename=$con_off_ename;
					}else{
						$con_officer_ename=$con_off_tname;	
					}
		
						print("<option value='".$row["dept_user_id"]."'>".$con_officer_ename."</option>");
				}
				?>
	</select>
<?php
}
if($source_frm=='get_disposing_officer_for_taluk') {
	$source_id=$_POST["source_id"];
	$griev_taluk=$_POST["griev_taluk"];
	$sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
	from vw_usr_dept_users_v_sup where off_loc_id=".$griev_taluk." and dept_id=1 and off_level_id = 4 and dept_desig_id=s_dept_desig_id and pet_act_ret=true and pet_disposal=true and dept_coordinating and dept_desig_id=56";
	$rs=$db->query($sql);
	?>
	<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style">
	<?php
		while ($row= $rs->fetch(PDO::FETCH_BOTH)) {
					$con_off_ename=$row["dept_desig_name"].', '.$row["off_level_dept_name"].', '.$row["off_loc_name"];
					$con_off_tname=$row["dept_desig_tname"].', '.$row["off_level_dept_tname"].', '.$row["off_loc_tname"];
					if($_SESSION["lang"]=='E'){
						$con_officer_ename=$con_off_ename;
					}else{
						$con_officer_ename=$con_off_tname;	
					}
		
						print("<option value='".$row["dept_user_id"]."'>".$con_officer_ename."</option>");
				} ?>
				</select>
				<?php
}

if($source_frm=='get_disposing_officer_dept_change') {
	$source_id=$_POST["source_id"];
	$dept=$_POST["dept"];
	
		if ($dept == 1 && $source_id == 5) {
			if ($userProfile->getOff_level_id() == 4) {
				$sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
				from vw_usr_dept_users_v_sup where off_hier[2]=".$userProfile->getDistrict_id()." and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." and off_level_id = 4 and dept_desig_id=s_dept_desig_id and pet_act_ret=true and pet_disposal=true and dept_coordinating and dept_desig_id=56";
			} else {
				$sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
				from vw_usr_dept_users_v_sup where off_hier[2]=".$userProfile->getDistrict_id()." and dept_id=".$userProfile->getDept_id(). "  and  off_level_id = 4 and dept_desig_id=s_dept_desig_id and pet_act_ret=true and pet_disposal=true and dept_coordinating and dept_desig_id=56";
			}
				
		} else {
			$sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
			from vw_usr_dept_users_v_sup where off_hier[2]=".$userProfile->getDistrict_id()." and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getDistrict_id()." and off_level_id = 2 and dept_desig_id=s_dept_desig_id and pet_act_ret=true and pet_disposal=true and dept_desig_id=16";
		}
		//echo $sql;
	$rs=$db->query($sql);
	?>
	<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style">
	<?php 
	if ($dept == 1 && $source_id == 5) {
	?>
	<option value="">--Select--</option>
	<?php
	}
		while ($row= $rs->fetch(PDO::FETCH_BOTH)) {
					$con_off_ename=$row["dept_desig_name"].', '.$row["off_level_dept_name"].', '.$row["off_loc_name"];
					$con_off_tname=$row["dept_desig_tname"].', '.$row["off_level_dept_tname"].', '.$row["off_loc_tname"];
					if($_SESSION["lang"]=='E'){
						$con_officer_ename=$con_off_ename;
					}else{
						$con_officer_ename=$con_off_tname;	
					}
		
						print("<option value='".$row["dept_user_id"]."'>".$con_officer_ename."</option>");
				} ?>
				</select>
				<?php
}
if($source_frm=='get_subdivision')
{
	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	$district_id=$_POST["district"];
	//$hid_griev_subdivision_id=$_POST["hid_griev_subdivision_id"];
	
	$district_condition = ($district_id == '') ? ' and district_id='.$userProfile->getDistrict_id() : 'and district_id='.$district_id;
	$gre_division_cond=($gre_division != '') ? ' and division_id='.$gre_division : '';
	$gre_subdivision_cond=($userProfile->getOff_level_id() == 11) ? ' and subdivision_id='.$userProfile->getSubDivision_id() : '';
	if ($gre_division_cond != '') {
		$district_condition = '';
	}
	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  
	FROM mst_p_sp_subdivision where dept_id=".$dept_id.$district_condition.$gre_division_cond.$gre_subdivision_cond."";
	
	//division_id=".$gre_division." and dept_id=".$dept_id.$district_condition."";
	$rs=$db->query($sub_div_sql);
	?>
	<select name="gre_subdivision" id="gre_subdivision"  class="select_style" data_valid='no' onChange="loadCircle();get_officer_list();">
    <?php //if ($rs->rowCount() > 1) { ?>
	<option value="">--Select--</option>
	<?php
	//}
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$subdivision_id = $row["subdivision_id"];
		$subdivision_name = $row["subdivision_name"];
		$subdivision_tname = $row["subdivision_tname"];
		
		if($_SESSION["lang"]=='E'){
			$subdivision_name=$subdivision_name;
		}else{
			$subdivision_name=$subdivision_tname;	
		}
		print("<option value='".$row["subdivision_id"]."'>".$subdivision_name."</option>");
    		
    	
    }
	?>
	</select>
	<?php
	
}
if($source_frm=='load_circle')
{
	$dept_id=$_POST["dept_id"];
	$subdivision=$_POST["subdivision"];
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where subdivision_id=".$subdivision." and dept_id=".$dept_id."";
	$rs=$db->query($sub_div_sql);
	?>
	<select name="gre_circle" id="gre_circle"  class="select_style" data_valid='no' onChange="get_officer_list();">
    <option value="">--Select--</option>
	<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$circle_name = $row["circle_name"];
		$circle_tname = $row["circle_tname"];
		
		if($_SESSION["lang"]=='E'){
			$circle_name=$circle_name;
		}else{
			$circle_name=$circle_tname;	
		}
		print("<option value='".$row["circle_id"]."' >".$circle_name."</option>");
    	
    }
	?>
	</select>
	<?php
	
}
if($source_frm=='load_circle_for_division')
{
	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	
	$sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where division_id=".$gre_division." and dept_id=".$dept_id."";
	$rs=$db->query($sub_div_sql);
	?>
	<select name="gre_circle" id="gre_circle"  class="select_style" data_valid='no' onChange="get_officer_list();">
    <option value="">--Select--</option>
	<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$circle_name = $row["circle_name"];
		$circle_tname = $row["circle_tname"];
		
		if($_SESSION["lang"]=='E'){
			$circle_name=$circle_name;
		}else{
			$circle_name=$circle_tname;	
		}
		print("<option value='".$row["circle_id"]."' >".$circle_name."</option>");
    	
    }
	?>
	</select>
	<?php
	
}
if($source_frm=='getDept')
{
	$source_id=$_POST["source_id"];
	//if ($source_id == 26) {
	/*if ($source_id == 26 || $source_id == 42) {
		$sql="SELECT dept_id, dept_name, dept_tname, off_level_pattern_id FROM usr_dept where dept_id>0 ORDER BY dept_name";
	} else {
		$sql="SELECT dept_id, dept_name, dept_tname, off_level_pattern_id FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
	}*/
	
	if ($userProfile->getOff_level_id() == 1 || $userProfile->getOff_level_id() == 3 
	|| $userProfile->getOff_level_id() == 5 || $userProfile->getOff_level_id() == 4 
	|| $userProfile->getOff_level_id() == 6 || $userProfile->getOff_level_id() == 7 
	|| $userProfile->getOff_level_id() == 10 || $userProfile->getOff_level_id() == 11 
	||($userProfile->getOff_level_id()==2 && ($userProfile->getDept_id()==9 
	|| $userProfile->getDept_id()==12))) {
		if ($source_id == 5 || $source_id == 26 || $source_id == 42) {	 
			$sql= "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
			FROM usr_dept where dept_id>0 and COALESCE(enabling,true) ORDER BY dept_name";
		} else {
			$sql= "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
			FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." and COALESCE(enabling,true) ORDER BY dept_name";
		}
	} else 	{
		$sql= "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
		FROM usr_dept where dept_id>0 and COALESCE(enabling,true) ORDER BY dept_name";
	}
				
	$rs=$db->query($sql);
	?>
	<select name="dept" id="dept" onchange="get_pattern_id();" data_valid='yes' data-error="Please select Department" class="select_style">
     <option value="">--Select Department--</option>
	<?php
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {

			$dept_name=$row["dept_name"];
			$dept_tname=$row["dept_tname"];

			if($_SESSION["lang"]=='E'){
			$dept_name=$dept_name;
			}else{
			$dept_name=$dept_tname;	
			}
			print("<option value='".$row["dept_id"]."' >".$dept_name."</option>");
    	
		}
	?>
	</select>
	<?php
	
}
if($source_frm=='getTalukForSplCamp')
{
	$disposing_officer_off_level_id=$_POST["disposing_officer_off_level_id"];	
	$disposing_officer_off_loc_id=$_POST["disposing_officer_off_loc_id"];
	
	if ($disposing_officer_off_level_id == 2) {
		$sql="SELECT taluk_id, district_id, rdo_id, taluk_name,taluk_tname FROM mst_p_taluk where district_id=".$disposing_officer_off_loc_id."";
	} else if ($disposing_officer_off_level_id == 3) {
		$sql="SELECT taluk_id, district_id, rdo_id, taluk_name,taluk_tname FROM mst_p_taluk where rdo_id=".$disposing_officer_off_loc_id."";
	} else if ($disposing_officer_off_level_id == 4) {
		$sql="SELECT taluk_id, district_id, rdo_id, taluk_name,taluk_tname FROM mst_p_taluk where taluk_id=".$disposing_officer_off_loc_id."";
	}
	$rs=$db->query($sql);
	?>
	<select name="gre_taluk" id="gre_taluk" onChange="get_gre_village();get_officer_list();" data_valid='no' data-error="Please select Taluk" class="select_style">
     <option value="">--Select Taluk--</option>
	<?php
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {

			$taluk_name=$row["taluk_name"];
			$taluk_tname=$row["taluk_tname"];

			if($_SESSION["lang"]=='E'){
			$taluk_name=$taluk_name;
			}else{
			$taluk_name=$taluk_tname;	
			}
			print("<option value='".$row["taluk_id"]."' >".$taluk_name."</option>");
    	
		}
	?>
	</select>
	<?php
	
}
if($source_frm=='populate_division')
{
	$gre_subdivision=$_POST["gre_subdivision"];
	$sql = "select b.division_id,b.division_name,b.division_tname,c.dept_name,c.dept_tname 
	from mst_p_sp_subdivision a 
	inner join mst_p_sp_division b on b.division_id=a.division_id 
	inner join usr_dept c on c.dept_id=b.dept_id
	where a.subdivision_id=".$gre_subdivision;
	
	$rs=$db->query($sql);
	
	if ($rs->rowCount() == 0) {
	?>
	<select name="gre_division" id="gre_division" data_valid="no"  onChange="get_officer_list();" data-error="Please select office" class="select_style">
	<option value="">--Select--</option>
	</select>
	<?php 
	} else { ?>
	<select name="gre_division" id="gre_division" data_valid="no"  onChange="get_officer_list();" data-error="Please select office" class="select_style">
	<?php
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {

			$dept_name=$row["dept_name"];
			$dept_tname=$row["dept_tname"];
			$division_name=$row["division_name"];
			$division_tname=$row["division_tname"];

			if($_SESSION["lang"]=='E'){
			$division_name=$division_name.' - '.$dept_name;
			}else{
			$division_name=$division_tname.' - '.$dept_tname;	
			}
			print("<option value='".$row["division_id"]."' >".$division_name."</option>");
    	
		}
	?>
	</select>
	<?php
	}
} 
if($source_frm=='fixInitiatingOfficer') //source_id: source_id,f_action_entby:f_action_entby
{
	$source_id=$_POST["source_id"];
	$f_action_entby=$_POST["f_action_entby"];
	if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
		$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
	} else {
		$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
	}	
	$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
	from vw_usr_dept_users_v_sup a
	inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
	where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true and source_id=".$source_id.$condition."";

	$rs=$db->query($sql);
	$count=$rs->rowCount();
	?>
	<?php //if ($userProfile->getOff_level_id() == 2 && $source_id == 5) { ?>
		<!--<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style" onChange="getDisposingOfficerDetails();">
		<option value="">--Select--</option>	-->
	<?php //} else { ?>	
		<select name="disposing_officer" id="disposing_officer" data_valid='no' class="select_style">
	<?php //} ?>
	<?php 
	if ($count > 1) {
	}
	while ($row= $rs->fetch(PDO::FETCH_BOTH)) {
		$con_off_ename=$row["dept_desig_name"].', '.$row["off_level_dept_name"].', '.$row["off_loc_name"];
		$con_off_tname=$row["dept_desig_tname"].', '.$row["off_level_dept_tname"].', '.$row["off_loc_tname"];
		if($_SESSION["lang"]=='E'){
			$con_officer_ename=$con_off_ename;
		}else{
			$con_officer_ename=$con_off_tname;	
		}
		if ($f_action_entby == $row["dept_user_id"])
			print("<option value='".$row["dept_user_id"]."' selected>".$con_officer_ename."</option>");
		else	
			print("<option value='".$row["dept_user_id"]."'>".$con_officer_ename."</option>");
	}
?>
	</select>
<?php
}if($source_frm=='load_police_station'){
	$district=stripQuotes(killChars($_POST["district"]));
	if($ef_off!=''){
	$ef_off=stripQuotes(killChars($_POST["ef_off"]));
	}else{
		$ef_off=$_SESSION['USER_ID_PK'];
	}
			if($ef_off!=''){
				$sql="select dept_off_level_pattern_id,off_level_id,off_hier[42] as division_id,off_hier[46] as circle_id from vw_usr_dept_users_v_sup where dept_user_id=".$ef_off;		
				$rs = $db->query($sql);
				$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		if($row['division_id']!=''){
		$division_id = $row['division_id'];
		}
		if($row['circle_id']!=''){
		$circle_id = $row['circle_id'];
		}
	}$codn_log='';
	if($division_id!=''){
		$codn_log.=" and b.division_id=$division_id";
	}
	if($circle_id!=''){
		$codn_log.=" and circle_id=$circle_id";
	}
			}else{
				$codn_log='';
			}
			echo $gre_sql = "SELECT circle_id,circle_name,circle_tname FROM public.mst_p_sp_circle a inner join mst_p_sp_division b on a.division_id=b.division_id where b.district_id=".$district." $codn_log ORDER BY circle_name ASC ";	
			$gre_rs=$db->query($gre_sql);
			if(!$gre_rs)
			{
			print_r($db->errorInfo());
			exit;
			}		
			echo "<option value='' selected>--Select Police Station--</option>";
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH))
				{
					$circle_id=$row["circle_id"];
					$circle_name=$row["circle_name"];
					$circle_tname=$row["circle_tname"];
					if($_SESSION["lang"]=='T')
					{
						$circle_name = $circle_name;
					}else{
						$circle_name = $circle_tname;	
					}
					print("<option value='".$circle_id."'>".$circle_name." POLICE STATION</option>");
				}	
		
}if($source_frm=='load_ext_dist'){
	$ef_off=stripQuotes(killChars($_POST["ef_off"]));
	if($ef_off==''){
		$ef_off=$_SESSION['USER_ID_PK'];
	}
	$sql="select dept_off_level_pattern_id,off_level_id,off_hier[9] as zone_id,off_hier[11] as range_id,off_hier[13] as district_id from vw_usr_dept_users_v_sup 
	where dept_user_id=".$ef_off;		
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row['off_hier'];			
		$pattern = $row['dept_off_level_pattern_id'];			
		$off_level_id = $row['off_level_id'];			
		$zone_id = $row['zone_id'];			
		$range_id = $row['range_id'];			
		$district_id = $row['district_id'];
	}
	
	if($off_level_id==7){
				$codn_dis='';
			}else if($off_level_id==9){
				$codn_dis=" where zone_id[1]=".$zone_id;
			}else if($off_level_id==11){
				$codn_dis=" where zone_id[1]=".$zone_id." and range_id[1]=".$range_id;
			}else{
				$codn_dis=" where district_id=".$district_id;
			}
			if($userProfile->getDept_off_level_pattern_id()!=''){
				if($codn_dis!=''){
					$codn_dis.=" and dept_off_level_pattern_id = ".$userProfile->getDept_off_level_pattern_id();
				}else{
					$codn_dis=" where dept_off_level_pattern_id = ".$userProfile->getDept_off_level_pattern_id();
				}
			}
			echo $gre_sql = "SELECT district_id,district_name,district_tname FROM public.mst_p_district ".$codn_dis." ORDER BY district_name ASC ";	
			$gre_rs=$db->query($gre_sql);
			if(!$gre_rs)
			{
			print_r($db->errorInfo());
			exit;
			}		
			echo "<option value='' selected>--Select District--</option>";
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH))
				{
					$district_id=$row["district_id"];
					$district_name=$row["district_name"];
					$district_tname=$row["district_tname"];
					if($_SESSION["lang"]=='T')
					{
						$district_name = $district_name;
					}else{
						$district_name = $district_tname;	
					}
					print("<option value='".$district_id."'>".$district_name."</option>");
				}	
				
	
}if($source_frm=='enquiry_default'){
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
						print("<optgroup label='".$dept_label."' id='optgroup_".substr($off_level_dept_name,0,3)."'>" );
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
}if($source_frm=='update_fir_csr'){
	$pet=stripQuotes(killChars($_POST['pet']));
	/*$sql="SELECT pet_ext_link_id,pet_ext_link_no from pet_master_ext_link where petition_id=".$pet;
	$rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$pet_ext_link_id = $row["pet_ext_link_id"];
					$pet_ext_link_no = $row["pet_ext_link_no"];
				}
				
				$arr=(explode("/",$pet_ext_link_no));
				$ext_ps=$arr[0];
				$ext_no=$arr[1];
				$ext_year=$arr[2]; 
				$sql="SELECT circle_name,circle_tname,district_name,district_tname,c.district_id from mst_p_sp_circle a inner join mst_p_sp_division b on a.division_id=b.division_id   inner join mst_p_district c on b.district_id=c.district_id where circle_id=".$ext_ps;*/
				$sql="select * from pet_master_ext_link where petition_id=".$pet;
				$rs=$db->query($sql);
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$ext_ps = $row["circle_id"];
					$district_id = $row["district_id"];
					$ext_no = $row["pet_ext_link_no"];
					$pet_ext_link_id = $row["pet_ext_link_id"];
					$district_id = $row["district_id"];
					$ext_year = $row["fir_csr_year"];
				}
				echo ','.$ext_ps.','.$ext_no.','.$ext_year.','.$circle_name.','.$circle_tname.','.$district_name.','.$district_tname.','.$district_id.','.$pet_ext_link_id;
}if($source_frm=='fir_det'){
	$pet=stripQuotes(killChars($_POST['pet']));
	$fir_csr=stripQuotes(killChars($_POST['fir_csr']));
	if($fir_csr=='I'){
	$codn="and pet_ext_link_id=1";
		}
	if($fir_csr=='S'){
	$codn="and pet_ext_link_id=2";
		}
	$sql="SELECT pet_ext_link_no,district_id,circle_id,fir_csr_year from pet_master_ext_link where petition_id=".$pet.$codn;
				$rs=$db->query($sql);
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
			$pet_ext_link_no = $row["pet_ext_link_no"];
					$district_id = $row["district_id"];
					$circle_id = $row["circle_id"];
					$year = $row["fir_csr_year"];
				}
				echo ','.$district_id.','.$circle_id.','.$year.','.$pet_ext_link_no;
}if($source_frm=='load_fir_csr_ext'){
	$pet_action_id=stripQuotes(killChars($_POST['pet_action_id11']));
	$act=stripQuotes(killChars($_POST['act_id']));
	$sql="SELECT pet_ext_link_no,district_id,circle_id,fir_csr_year from pet_master_ext_link where petition_id=(select petition_id from pet_action where pet_action_id=$pet_action_id) and pet_ext_link_id=$act";
				$rs=$db->query($sql);
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$pet_ext_link_no = $row["pet_ext_link_no"];
					$district_id = $row["district_id"];
					$circle_id = $row["circle_id"];
					$year = $row["fir_csr_year"];
				}
				echo ','.$district_id.','.$circle_id.','.$year.','.$pet_ext_link_no;
}if($source_frm=='club_pet'){
$arr=stripQuotes(killChars($_POST['arr']));
$func=stripQuotes(killChars($_POST['func']));

	$length = count($arr);
	
	if($func!=''){
	if($length==1){
		$sql="select org_petition_no,petition_no from pet_master where petition_id=".$arr[0]."";
		$rs1=$db->query($sql);
		while($row = $rs1->fetch(PDO::FETCH_BOTH))
		{
		$org_petition_no1 = $row["org_petition_no"];
		$opetition_no1 = $row["petition_no"];
		}
		if($org_petition_no1==$opetition_no1){
			$i="First Petition";
			echo $i;
			exit;
		}
	}
	}
	if($func==''){
		$arr1=array_slice($arr, 1);
		$sql1="select count(petition_id) as link_cnt from (select petition_id from pet_master where org_petition_no in (select org_petition_no from pet_master where petition_id in (" . implode(',', $arr1) . ")))aaa";
		$rst1=$db->query($sql1);
		while($trow = $rst1->fetch(PDO::FETCH_BOTH))
		{
		if($trow["link_cnt"]>count($arr1)){
			echo "alert";exit;
		}
		}
	}
if(count($arr)>1){
	$sql="select org_petition_no,petition_no from pet_master where petition_id=".$arr[0]."";
}else{
	$sql="select petition_no from pet_master where petition_id=".$arr[0]."";
}
$rs=$db->query($sql);
	while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					if(count($arr)>1){
		$org_petition_no = $row["org_petition_no"];
		$opetition_no = $row["petition_no"];
					}else{
		$org_petition_no = $row["petition_no"];
		$opetition_no = '';
					}
				}
				//echo ','.$org_petition_no; 
	for ($i = 0; $i < $length; $i++) {
		$sql="update pet_master set org_petition_no='$org_petition_no' where petition_id = ".$arr[$i].";";
	$rs=$db->query($sql);
	if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				
	}

	echo $i;
	
}if($source_frm=='enquiry_officer'){
	$init_off=stripQuotes(killChars($_POST['init_off']));
	$pet_process=stripQuotes(killChars($_POST['pet_process']));
	$disp_off_cond="";
	if ($pet_process == 'D') {
		$disp_off_cond = " and coalesce(pet_disposal,false)";
	}$disp_off_cond.=" and dept_user_id!=1";
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
				 
				if ($up_dept_off_level_pattern_id == ''){
					$up_dept_off_level_pattern_id='null';
				}	
				if ($up_dept_off_level_pattern_id == 'null'){
					$condition = " ";	 
				} else {					
					$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$up_dept_off_level_pattern_id.")";	
				}
		$sql="select off_hier from vw_usr_dept_users_v_sup 
	where dept_user_id=".$init_off;		
	$rs = $db->query($sql);
	$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row) {
		$off_hier = $row['off_hier'];		
	}
	$off_hier=str_replace("{","",$off_hier);
	$off_hier=str_replace("}","",$off_hier);
	$off_hier='['.$off_hier.']';//echo $userProfile->getDept_off_level_pattern_id();
	if($userProfile->getDept_off_level_pattern_id()!=""){
		$off_hier_codn=" and off_hier[1:$up_off_level_id]=(array".$off_hier.")[1:$up_off_level_id] ";
		$off_loc_codn="and off_hier[".$up_off_level_id."]=".$userProfile->getOff_loc_id()."";
	}else{
		$off_hier_codn="";
		$off_loc_codn="";
	}
	if ($userProfile->getDept_desig_id() == 39 || $userProfile->getDept_desig_id() == 40 || $userProfile->getDept_desig_id() == 41){
		$s_sql="select sup_dept_desig_id from usr_dept_desig where dept_desig_id=".$userProfile->getDept_desig_id().";";
		$rs=$db->query($s_sql);
		$s_rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($s_rowarray as $s_row) {
			$s_dept_user_id =  $s_row['sup_dept_desig_id'];
		}
					$condition = " and dept_desig_id !=".$s_dept_user_id." ";	 
		}
				$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name, dept_off_level_pattern_name
				from vw_usr_dept_users_v_sup
				where dept_id=".$up_dept_id.$condition." 
				and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id."  and
				(case when ".$userProfile->getgriev_suptype_id()."=1 then griev_suptype_id in (2,3,1)
				 when ".$userProfile->getgriev_suptype_id()."=2 then griev_suptype_id in (2,1)
				 when ".$userProfile->getgriev_suptype_id()."=3 then griev_suptype_id in (3,1) end ) and dept_user_id not in (".$init_off.")
				and COALESCE(enabling,true) ".$off_loc_codn."
				and dept_user_id!=".$userProfile->getDept_user_id().$off_hier_codn.$disp_off_cond."
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
						print("<optgroup label='".$dept_label."' id='optgroup_".substr($off_level_dept_name,0,3)."'>" );
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