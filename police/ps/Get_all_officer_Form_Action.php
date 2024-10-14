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

if($mode=='p1_search') {
	//Basic Parameters
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_id=1;
	
	$dept_off_level_office_id=($dept_off_level_office_id == '0') ? '': $dept_off_level_office_id;
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == '0') ? '': $dept_off_level_pattern_id;
	
	//Search
	$district_id=stripQuotes(killChars($_POST['district_id']));  //Change off_id to district_id
	$off_name=stripQuotes(killChars($_POST['off_name']));
	$loc_first= stripQuotes(killChars($_POST['loc_first'])); //change to loc_first
	
	//User Profile
	$up_off_level_id=$userProfile->getOff_level_id();
	$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
	$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
	$up_dept_id=$userProfile->getDept_id();
	$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
	
	if ($dept_off_level_pattern_id == 1) {
		$condition="";
		if ($off_level_id == 42) { //Division
			if ($district_id != "") {
			$condition.=" and district_id=".$district_id."";
			}
			if ($off_name != "") {
				$condition.=" and lower(division_name) like '%".$off_name."%'";
			}
			if ($loc_first != "") {
				$loc_first = strtolower($loc_first);
				$condition.= " and lower(division_name) like '".$loc_first."%' "; 
			}
		} else if ($off_level_id == 46) { //Circle
		}
	} else if ($dept_off_level_pattern_id == 2) {
		
	} else if ($dept_off_level_pattern_id == 3) {
		
	} else if ($dept_off_level_pattern_id == 4) {
		
	}
	if ($off_level_id == 42) { //Division
		$condition=" ";
		if ($district_id != "") {
			$condition.=" and district_id=".$district_id."";
		}
		if ($off_name != "") {
			$condition.=" and lower(division_name) like '%".$off_name."%'";
		}
		if ($loc_first != "") {
			$loc_first = strtolower($loc_first);
			$condition.= " and lower(division_name) like '".$loc_first."%' "; 
		}
	} else if ($off_level_id == 44) { //Sub-division
		$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname from mst_p_sp_subdivision where dept_id=1 order by off_loc_id";
	} else if ($off_level_id == 46) { // Circle
		$condition=" ";
		if ($district_id != "") { //Connect with division and compare district_id
			$condition.=" and a.district_code in (select district_code from mst_p_district where district_id=".$district_id.")";

			$condition.=" and a.district_id=".$district_id."";
		}
		if ($off_name != "") {
			$condition.=" and lower(circle_name) like '%".$off_name."%'";
		}
		if ($loc_first != "") {
			$loc_first = strtolower($loc_first);
			$condition.= " and lower(circle_name) like '".$loc_first."%' "; 
		}
	}
	
	if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == '') { //DGP office condition	
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
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			//echo $sql
		}
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 1) {
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
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		}
	
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 2) {
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
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		}
	
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 3) {
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
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		}
	
	} else if ($up_off_level_pattern_id == 4 && $up_dept_off_level_pattern_id == 4) {
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
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
		}
	
	}
	
	//echo $sql;
	/*
	if ($off_level_id == 7) {
		$sql="select distinct off_loc_id,off_loc_name,off_level_dept_name || ' - '|| off_loc_name as off_loc_name,off_level_dept_tname || ' - '|| off_loc_tname as  off_loc_tname 
		from vw_usr_dept_users_v_sup where off_level_id in (7)";
	} else if ($off_level_id == 9) {
		$sql="select zone_id as off_loc_id,zone_name as off_loc_name,zone_tname as off_loc_tname from mst_p_sp_zone where dept_off_level_pattern_id=".$pattern_id." order by off_loc_id";		
	} else if ($off_level_id == 11) {
		$sql="select range_id as off_loc_id,range_name as off_loc_name,range_tname as off_loc_tname from mst_p_sp_range where dept_off_level_pattern_id=".$pattern_id." order by off_loc_id";
	} else if ($off_level_id == 13) {
		$sql="select district_id as off_loc_id,district_name as off_loc_name,district_tname as off_loc_tname from mst_p_district where district_id > 0 order by off_loc_id";
	} else if ($off_level_id == 42) {
		$sql="select division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname from mst_p_sp_division where dept_id=1".$condition." order by off_loc_id";
		
		if ($pattern_id == 4) {
			$join_condition = "inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
		} else {
			$join_condition = "inner join mst_p_sp_range c on c.range_id=a.range_id";
		}
		
		$sql="select a.division_id as off_loc_id,division_name as off_loc_name,division_tname as off_loc_tname
		from mst_p_sp_division a
		".$join_condition."
		where  c.dept_id=1 and c.dept_off_level_pattern_id=".$pattern_id.$condition;

	} else if ($off_level_id == 44) {
		$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname from mst_p_sp_subdivision where dept_id=1".$condition." order by off_loc_id";
		
		if ($pattern_id == 4) {
			$join_condition = "inner join mst_p_sp_zone c on c.zone_id=b.zone_id";
		} else {
			$join_condition = "inner join mst_p_sp_range c on c.range_id=b.range_id";
		}
			
		$sql="select subdivision_id as off_loc_id,subdivision_name as off_loc_name,subdivision_tname as off_loc_tname 
		from mst_p_sp_subdivision a
		inner join mst_p_sp_division b on b.division_id=a.division_id
		".$join_condition."
		where b.dept_id=1 and c.dept_off_level_pattern_id=".$pattern_id.$condition." order by off_loc_id";
		
	} else if ($off_level_id == 46) {
		$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname from mst_p_sp_circle where dept_id=1".$condition." order by off_loc_id";
		
		if ($pattern_id == 4) {
			$join_condition = "inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
		} else {
			$join_condition = "inner join mst_p_sp_range c on c.range_id=a.range_id";
		}
		
		$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
		from mst_p_sp_circle cir 
		inner join mst_p_sp_division a on a.division_id=cir.division_id
		".$join_condition."
		where  c.dept_id=1 and c.dept_off_level_pattern_id=".$pattern_id.$condition;
		
	}
	*/
	//echo $sql;
	$query = 'select * from (select *,row_number() over (order by off_loc_id,off_loc_name,off_loc_tname) as rownum from ('.$sql .') off_level)aa
	WHERE rownum >='.$page->getStartResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"]))). ' and rownum <= '.$page->getMaxResult(stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])))." order by off_loc_id";

	//echo $query;
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{
		$off_loc_name=str_replace('&','and',$row['off_loc_name']);
		
		echo "<off_loc_id>".$row['off_loc_id']."</off_loc_id>";
		echo "<off_loc_name>".$off_loc_name."</off_loc_name>";
		echo "<off_loc_tname>".$row['off_loc_tname']."</off_loc_tname>";
	}
	
	$sql_count = 'SELECT COUNT(off_loc_id) FROM ('.$sql .') off_level';
	$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "</response>";
}
	
?>
