<?php
ob_start();
session_start();
include('db.php');
include("UserProfile.php");
include("common_date_fun.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
$source_frm =$_POST['source_frm'];

if($source_frm=='loadOfficeLevel') {
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	
	if ($pattern_id == 3 || $pattern_id == 1 ) {
		if($userProfile->getOff_level_id()==7){
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and off_level_id <= 46 and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
		}else{
			$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and off_level_id <= 46 and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
		}
	} else if ($pattern_id != "") {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and off_level_id <= 46 and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
	} else {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}	

	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="office_level" id="office_level" class="select_style">
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
		if ($off_level_dept_id_pm == $off_level_dept_id)
		print("<option value='".$off_level."' selected>".$off_level_dept_name."</option>");
		else
		print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
	}	

?>
</select>
<?php
} else if($source_frm=='p_loadOfficeLevel') {
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
		
	if ($pattern_id == 3) {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and off_level_id <= 46 and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
	} else if ($pattern_id != "") {
		
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and off_level_id <= 46 and dept_off_level_pattern_id=".$pattern_id.") order by off_level_dept_id";
	} else {
		$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id is null) order by off_level_dept_id";
	}	
//echo $sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
?>
<select name="p_office_level" id="p_office_level" class="select_style" onchange="loadParticularOffice();">
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
		if ($off_level_dept_id_pm == $off_level_dept_id)
		print("<option value='".$off_level."' selected>".$off_level_dept_name."</option>");
		else
		print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
	}	

?>
</select>
<?php
} else if($source_frm=='p_loadOffice') {
	$pattern_id=stripQuotes(killChars($_POST['pattern_id']));
	$p_office_level=stripQuotes(killChars($_POST['p_office_level']));
	$up_off_level_id=$userProfile->getOff_level_id();
	
	$off_level=explode('-',$p_office_level);
	$off_level_id=$off_level[0];
	$off_level_dept_id=$off_level[1];
	//echo $up_off_level_id.$off_level_id;
	if ($up_off_level_id == 7) {
		if ($off_level_id == 7) {
		if($pattern_id==3){
			$sql="select a.state_id as off_loc_id,a.state_name as off_loc_name,a.state_tname as off_loc_tname 
			from mst_p_state a where a.state_id=36";
		}else{
		$sql="select a.state_id as off_loc_id,a.state_name as off_loc_name,a.state_tname as off_loc_tname 
			from mst_p_state a where a.state_id=29";
		}
		} elseif ($off_level_id == 9) {
			$sql="select a.zone_id as off_loc_id,a.zone_name as off_loc_name,a.zone_tname as off_loc_tname 
			from mst_p_sp_zone a where a.dept_off_level_pattern_id=".$pattern_id."";
		} else if ($off_level_id == 11) {
			$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
			from mst_p_sp_range a where a.dept_off_level_pattern_id=".$pattern_id.""; 
		} else if ($off_level_id == 13) {
			$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name,a.district_tname as off_loc_tname 
			from mst_p_district a where a.district_id > 0 and a.dept_off_level_pattern_id=".$pattern_id." order by off_loc_name";
		} else if ($off_level_id == 42) {
			$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
			from mst_p_sp_division a inner join mst_p_district b on a.district_id=b.district_id where b.dept_off_level_pattern_id=".$pattern_id." order by off_loc_name";
		} else if ($off_level_id == 46) {
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a  inner join mst_p_sp_division b1 on a.division_id=b1.division_id inner join mst_p_district b on b1.district_id=b.district_id where b.dept_off_level_pattern_id=".$pattern_id."order by off_loc_name";
		}
	} else if ($up_off_level_id == 9) {
		if ($off_level_id == 11) {
			$sql="select a.range_id as off_loc_id,a.range_name as off_loc_name,a.range_tname as off_loc_tname 
			from mst_p_sp_range a where a.dept_off_level_pattern_id=".$pattern_id." 
			and zone_id=".$userProfile->getOff_loc_id().""; 
		} else if ($off_level_id == 13) {
			$sql="select distinct(a.district_id) as off_loc_id,a.district_name as off_loc_name from mst_p_district a
			inner join mst_p_sp_division b on b.district_id=a.district_id
			inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
			where c.zone_id=".$userProfile->getOff_loc_id()." order by off_loc_name";
		} else if ($off_level_id == 42) {
			$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
			from mst_p_sp_division a where a.zone_id=".$userProfile->getZone_id()." order by off_loc_name";
		} else if ($off_level_id == 46) {
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a inner join mst_p_sp_division b on b.division_id=a.division_id where b.zone_id=".$userProfile->getZone_id()." order by off_loc_name";
		}
	} else if ($up_off_level_id == 11) {
		$sql="select distinct(a.range_id) as off_loc_id,a.range_name as off_loc_name from mst_p_sp_range a
		where a.range_id=".$userProfile->getRange_id()." and a.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id();
		 if ($off_level_id == 13) {
		$sql="select distinct(a.district_id) as off_loc_id,a.district_name as off_loc_name from mst_p_district a
		inner join mst_p_sp_division b on b.district_id=a.district_id
		inner join mst_p_sp_range c on c.range_id=b.range_id  
		where c.range_id=".$userProfile->getRange_id()." and c.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id();
		 }
		 if ($off_level_id == 42) {
			$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
			from mst_p_sp_division a inner join mst_p_sp_range c on c.range_id=a.range_id  where a.range_id=".$userProfile->getRange_id()." and c.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()." order by off_loc_id";
		} else if ($off_level_id == 46) {
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a inner join mst_p_sp_division b on b.division_id=a.division_id inner join mst_p_sp_range c on c.range_id=b.range_id  where b.range_id=".$userProfile->getRange_id()." and c.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()." order by off_loc_id";
		}
	} else if ($up_off_level_id == 13) {
		$sql="select a.district_id as off_loc_id,a.district_name as off_loc_name from mst_p_district a	where a.district_id=".$userProfile->getDistrict_id();
		
		if ($off_level_id == 42) {
		$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
			from mst_p_sp_division a inner join mst_p_sp_range c on c.range_id=a.range_id where a.enabling ";
			if($pattern_id==4){
			$sql="select a.division_id as off_loc_id,a.division_name as off_loc_name,a.division_tname as off_loc_tname 
			from mst_p_sp_division a inner join mst_p_district c on c.district_id=a.district_id where a.enabling";
				}
		if($userProfile->getDistrict_id()!=''){
		$sql.=" and a.district_id=".$userProfile->getDistrict_id()."";
		}
		if($userProfile->getDept_off_level_pattern_id()!=''){
		$sql.=" and c.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
		}
		$sql.=" order by off_loc_name";
		} else if ($off_level_id == 46) {
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a inner join mst_p_sp_division b on b.division_id=a.division_id inner join mst_p_sp_range c on c.range_id=b.range_id";
			if($pattern_id==4){
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a inner join mst_p_sp_division b on b.division_id=a.division_id inner join mst_p_district c on c.district_id=b.district_id";
				}
		if($userProfile->getDistrict_id()!=''){
		$sql.=" and b.district_id=".$userProfile->getDistrict_id()."";
		}
		if($userProfile->getDept_off_level_pattern_id()!=''){
		$sql.=" and c.dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
		}
		$sql.=" order by off_loc_name";
		}

	} else if ($up_off_level_id == 42) {
		if ($off_level_id == 46) {
			$sql="select a.circle_id as off_loc_id,a.circle_name as off_loc_name,a.circle_tname as off_loc_tname 
			from mst_p_sp_circle a inner join mst_p_sp_division b on b.division_id=a.division_id";
		if($userProfile->getDistrict_id()!=''){
		$sql.=" and b.district_id=".$userProfile->getDistrict_id()."";
		}
		$sql.=" order by off_loc_name";
		}

	}
	//echo "=====".$sql;
	$rs=$db->query($sql);
	
	if(!$rs) {
		print_r($db->errorInfo());  //office
		exit;
	}
?>	
<select name="office" id="office" class="select_style">
<option value="">--Select--</option>
<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$off_loc_id=$row["off_loc_id"];
		$off_loc_name=$row["off_loc_name"];
		print("<option value='".$off_loc_id."'>".$off_loc_name."</option>");	
	}
?>
</select>	
<?php
}
?>
