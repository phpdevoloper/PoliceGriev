<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");

/* $userProfile = unserialize($_SESSION['USER_PROFILE']); 
$userProfile->getOff_desig_emp_name(); */
$mode=$_POST["mode"];

if($mode=='p1_search') {
	//Basic Parameters
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
 	/* $dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));  */
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$pre=stripQuotes(killChars($_POST['pre']));
	
 	/* $dept_off_level_office_id=($dept_off_level_office_id == '0') ? '': $dept_off_level_office_id;
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == '0') ? '': $dept_off_level_pattern_id;  */
	
	//Search
	$district_id=stripQuotes(killChars($_POST['district_id']));  //Change off_id to district_id
	/* $off_name=stripQuotes(killChars($_POST['off_name']));*/
	$loc_first= stripQuotes(killChars($_POST['loc_first'])); //change to loc_first 
	if($loc_first!=''){
		$loc_cond=" and ".$pre."_name LIKE '".$loc_first."%'";
	}else{
		$loc_cond='';
	}
	if ($pre=='division'){
	$query="select division_id,division_name,division_tname,enabling,ordering from mst_p_sp_division where district_id=".$district_id." ".$loc_cond." and dept_id=1 ;";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{		
		echo "<off_loc_id>".$row['division_id']."</off_loc_id>";
		echo "<off_loc_name>".$row['division_name']."</off_loc_name>";
		echo "<off_loc_tname>".$row['division_tname']."</off_loc_tname>";
	}
	
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "<sql>".$query."</sql>";
	echo "</response>";
}
else if ($pre=='subdivision'){
	$query="select subdivision_id,subdivision_name,subdivision_tname,enabling,ordering from mst_p_sp_subdivision where district_id=".$district_id." ".$loc_cond." and dept_id=1 ;";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{		
		echo "<off_loc_id>".$row['subdivision_id']."</off_loc_id>";
		echo "<off_loc_name>".$row['subdivision_name']."</off_loc_name>";
		echo "<off_loc_tname>".$row['subdivision_tname']."</off_loc_tname>";
	}
	
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "<sql>".$query."</sql>";
	echo "</response>";
	}else if ($pre=='circle'){
	$query="select circle_id,circle_name,circle_tname,enabling,ordering from mst_p_sp_circle  where district_code=(select district_code from mst_p_district where district_id=".$district_id.") ".$loc_cond." and dept_id=1 ;";
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<off_loc_id>".$row['circle_id']."</off_loc_id>";
		echo "<off_loc_name>".$row['circle_name']."</off_loc_name>";
		echo "<off_loc_tname>".$row['circle_tname']."</off_loc_tname>";
	}
	
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "<sql>".$query."</sql>";
	echo "</response>";
	}
}
?>
