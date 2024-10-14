<?php
session_start();
include("db.php");
$designId= $userProfile->getDept_desig_id();
$roleId = $userProfile->getDesig_roleid();
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
if($actual_link != 'welcome_to_e_district.php')
{
	$qry1 = "select menu_item_id from menu_item where menu_item_link='".$actual_link."'";
	$result1=$db->prepare($qry1);
	$result1->execute();
	if(!$result1)
	{
		print_r($db->errorInfo());	
	}
	$row1=$result1->fetch(PDO::FETCH_BOTH);
	$menu_item_id=$row1['menu_item_id'];

	//echo "=========================================";
	
	
	if ($roleId == 5) {
		$qry = "select a.menu_item_id, b.dept_desig_role_id from menu_item as a 
		inner join menu_role_desig_role as b on a.menu_item_id=b.menu_item_id 
		where b.dept_desig_role_id=(select dept_desig_role_id from usr_dept_desig where dept_desig_id=(select sup_dept_desig_id from usr_dept_desig where dept_desig_id=".$designId.")) and a.menu_item_id=".$menu_item_id."";
	} else {
		$qry = "select a.menu_item_id, b.dept_desig_role_id from menu_item as a 
		inner join menu_role_desig_role as b on a.menu_item_id=b.menu_item_id 
		where b.dept_desig_role_id=".$roleId." and a.menu_item_id=".$menu_item_id."";
	}
	//exit;
	$result=$db->prepare($qry);
	$result->execute();
	if(!$result)
	{
		print_r($db->errorInfo());
	}
	$row_count = $result->rowCount();
	if($userProfile->getSys_admin()){
		$qry = "select a.menu_item_id, b.dept_desig_role_id from menu_item as a 
		inner join menu_role_desig_role as b on a.menu_item_id=b.menu_item_id 
		where a.menu_item_id in (79,81)";
	//exit;
	$result=$db->prepare($qry);
	$result->execute();
	if(!$result)
	{
		print_r($db->errorInfo());
	}
	$row_count = $result->rowCount();
	}
	if($row_count==0)
	{
		pg_close($db);
		header('Location: logout.php');
		exit;
	}

}

?>