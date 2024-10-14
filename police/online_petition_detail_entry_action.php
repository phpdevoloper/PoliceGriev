<?PHP
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("common_date_fun.php");

$mode=$_POST["mode"];
if($mode=='get_pattern_id')
{   
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
} else if($mode=='get_grievance')
{  // Included for Registration Department 
	$dept_id = stripQuotes(killChars($_POST['dept_id']));

	$sql = "SELECT DISTINCT(griev_type_id),dept_id, griev_type_code,griev_type_name, griev_type_tname
			FROM vw_usr_dept_griev_subtype 
			where dept_id=".$dept_id."
			ORDER BY griev_type_name";
			  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="griev_maincode" id="griev_maincode" data_valid='yes' style="width:260px" onChange="get_sub_category();"  data-error="Please select Main category" class="select_style" >
	<option value="">--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$grename=$row["griev_type_name"];
		$gretname = $row["griev_type_tname"];
		if($_SESSION["lang"]=='E')
		{
		$gre_name = $grename;
		}else{
		$gre_name = $gretname;	
		}

		print("<option value='".$row["griev_type_id"]."' >".$gre_name."</option>");
	}
	?>
	</select>
<?php 
} 
?>