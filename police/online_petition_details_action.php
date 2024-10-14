<?PHP
session_start();
include("db.php");
include("common_date_fun.php");

include_once 'common_lang.php';

$source_frm=$_POST["source_frm"];

if($source_frm=='gre_taluk')
{
	$district = stripQuotes(killChars($_POST['district']));
	$qua_sql = "select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$district." order by taluk_name";
	$qua_rs=$db->query($qua_sql);
	if(!$qua_rs) {
        print_r($db->errorInfo());
        exit;
    }

?>
<select name="gre_taluk" id="gre_taluk" data_valid='yes' data-error="Please select taluk" style="width:200px;" onChange="get_gre_village();">
<option value="" selected disabled>--Select--</option>
<?php
	 while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
		$talukname=$qua_row["taluk_name"];
		$taluktname=$qua_row["taluk_tname"]; 
		if($_SESSION["lang"]=='E')
		{
			$taluk_name=$talukname;
		}else if($_SESSION["lang"]=='T')
		{
			$taluk_name=$taluktname;	
		}else 
		{
			$taluk_name=$talukname;	
		}
		print("<option value='".$qua_row["taluk_id"]."' >".$taluk_name."</option>");
	 }
// Taluk End
?>
 </select>
 
<?php 
} else if($source_frm=='taluk')
{
	$district = stripQuotes(killChars($_POST['district']));
	$qua_sql = "select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$district." order by taluk_name";
	$qua_rs=$db->query($qua_sql);
	if(!$qua_rs) {
        print_r($db->errorInfo());
        exit;
    }
?>
<select name="comm_taluk" id="comm_taluk" data_valid='yes' data-error="Please select taluk" style="width:200px;" onChange="get_village();">
<option value="" selected disabled>--Select--</option>
<?php
	 while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
		$talukname=$qua_row["taluk_name"];
		$taluktname=$qua_row["taluk_tname"]; 
		if($_SESSION["lang"]=='E')
		{
			$taluk_name=$talukname;
		}else if($_SESSION["lang"]=='T')
		{
			$taluk_name=$taluktname;	
		}else 
		{
			$taluk_name=$talukname;	
		}
		print("<option value='".$qua_row["taluk_id"]."' >".$taluk_name."</option>");
	 }
?>
 </select>
<?php 
}//Sub Category Start
 else if($source_frm=='griev_subcategory')
{  
 	$griev_main_id=stripQuotes(killChars($_POST['griev_main_code']));
	$dept_id = stripQuotes(killChars($_POST['dept_id'])); 
	$fwd_office_level = stripQuotes(killChars($_POST['fwd_office_level']));
	
	if ($fwd_office_level == 10) {
		$gre_sub_sql = "SELECT a.griev_subtype_id, a.griev_subtype_code, a.griev_subtype_name, a.griev_subtype_tname
		FROM lkp_griev_subtype a WHERE a.griev_subtype_id in (SELECT griev_subtype_id FROM usr_dept_griev_subtype 
		WHERE dept_id=".$dept_id." and griev_type_id=".$griev_main_id." and 1 = ANY(off_level_id)) and a.enabling ORDER BY a.griev_subtype_name";
	} else if ($fwd_office_level == 20){
		$gre_sub_sql = "SELECT a.griev_subtype_id, a.griev_subtype_code, a.griev_subtype_name, a.griev_subtype_tname
		FROM lkp_griev_subtype a WHERE a.griev_subtype_id in (SELECT griev_subtype_id FROM usr_dept_griev_subtype 
		WHERE dept_id=".$dept_id." and griev_type_id=".$griev_main_id." and 2 = ANY(off_level_id)) and a.enabling ORDER BY a.griev_subtype_name";		
	} else {
		$gre_sub_sql = "SELECT a.griev_subtype_id, a.griev_subtype_code, a.griev_subtype_name, a.griev_subtype_tname
		FROM lkp_griev_subtype a WHERE a.griev_subtype_id in (SELECT griev_subtype_id FROM usr_dept_griev_subtype 
		WHERE dept_id=".$dept_id." and griev_type_id=".$griev_main_id." and (10 = ANY(off_level_id) or 11 = ANY(off_level_id))) and a.enabling ORDER BY a.griev_subtype_name";
	}

	$gre_sub_rs=$db->query($gre_sub_sql);
	if(!$gre_sub_rs)
	{
		print_r($db->errorInfo());
		exit;
	}
	?>
		
	<select name="griev_subcode" id="griev_subcode"   data_valid='yes'  data-error="Please select subcategory" class="select_style">
	<option value="" selected disabled>--Select--</option>
	<?php  
		while($gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH))
		{
			$gresub_typename=$gre_sub_row["griev_subtype_name"];
			$gresub_typetname=$gre_sub_row["griev_subtype_tname"];
			if($_SESSION["lang"]=='E')
			{
			$gre_sub_type_name=$gresub_typename;
			}else if($_SESSION["lang"]=='T')
			{
			$gre_sub_type_name=$gresub_typetname;	
			}else 
			{
			$gre_sub_type_name=$gresub_typename;	
			}
			
			if ($gsval == $gre_sub_row["griev_subtype_id"])
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' selected>".$gre_sub_type_name."</option>");
			else	
				print("<option value='".$gre_sub_row["griev_subtype_id"]."' >".$gre_sub_type_name."</option>");
			}
	?>
	</select>
<?php 
} //Sub Category End

//Revenue Village Start
 else if($source_frm=='gre_village') {  
			 
	$talukcode=stripQuotes(killChars($_POST['talukid']));
	if($talukcode!= ''){
		$qua_sql = "select distinct rev_village_id,rev_village_name,rev_village_tname from mst_p_rev_village where taluk_id='$talukcode' order by rev_village_name";
		$qua_rs=$db->query($qua_sql);
		if(!$qua_rs)
		{
			print_r($db->errorInfo());
			exit;
		}
		?>
		<select name="gre_rev_village" id="gre_rev_village" style="width:200px;" data_valid="no" data-error="Please select revenue village">
		<option value="" selected disabled>--Select--</option>
        <?php  
            while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH))
            {
				$villname=$qua_row["rev_village_name"];
				$villtname = $qua_row["rev_village_tname"];
				if($_SESSION["lang"]=='E')
				{
					$vill_name=$villname;
				}else if($_SESSION["lang"]=='T')
				{
					$vill_name = $villtname;
				}else
				{
					$vill_name = $villname;
				}
				print("<option value='".$qua_row["rev_village_id"]."' >".$vill_name."</option>");
            }
        ?>
	     </select>
<?php }	
}
//Revenue Village End
 else 	if($source_frm=='block')
{
	$district = stripQuotes(killChars($_POST['district']));
	$qua_sql = "SELECT block_id, block_name,block_tname FROM mst_p_lb_block  where district_id=".$district." order by block_name";
	$qua_rs=$db->query($qua_sql);
	if(!$qua_rs) {
        print_r($db->errorInfo());
        exit;
    }
?>
<select name="gre_block" id="gre_block" data_valid='no'  onChange="get_village_panchayat();" data-error="Please select taluk" class="select_style">
<option value="" selected disabled>--Select--</option>
<?php
	 while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
		$block_name=$qua_row["block_name"];
		$block_tname=$qua_row["block_tname"]; 
		if($_SESSION["lang"]=='E')
		{
			$block_name=$block_name;
		}else if($_SESSION["lang"]=='T')
		{
			$block_name=$block_tname;	
		}else 
		{
			$block_name=$block_name;	
		}
		print("<option value='".$qua_row["block_id"]."' >".$block_name."</option>");
	 }
?>
 </select>
<?php 
}  
else if($source_frm=='village_panchayat') 
{
			
	$blockcode=stripQuotes(killChars($_POST['blockid']));
			
    $qua_sql = "select distinct lb_village_id,lb_village_name,lb_village_tname from mst_p_lb_village where block_id='$blockcode' order by lb_village_name";
    $qua_rs=$db->query($qua_sql);
    if(!$qua_rs)
    {
        print_r($db->errorInfo());
        exit;
    }	
	?>
	<select name="gre_tp_village" id="gre_tp_village" onChange="get_officer_list();" data_valid="no" data-error="Please select village panchayat" style="width:200px;">
	<option value="" selected disabled>--Select--</option>
    <?php  
        while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH))
        {
			$lbvillname=$qua_row["lb_village_name"];
			$lbvilltname=$qua_row["lb_village_tname"];
			if($_SESSION["lang"]=='E')
			{
				$lbvill_name=$lbvillname;
			}else if($_SESSION["lang"]=='T')
			{
				$lbvill_name=$lbvilltname;	
			}else
			{
				$lbvill_name=$lbvillname;	
			}
			print("<option value='".$qua_row["lb_village_id"]."' >".$lbvill_name."</option>");
		}
        ?>
    </select>
<?php 
} else 	if($source_frm=='urban') {
	$district = stripQuotes(killChars($_POST['district']));
	$qua_sql = "SELECT lb_urban_id, lb_urban_name,lb_urban_tname FROM mst_p_lb_urban  where district_id=".$district." order by lb_urban_name";
	$qua_rs=$db->query($qua_sql);
	if(!$qua_rs) {
        print_r($db->errorInfo());
        exit;
    }
?>
<select name="gre_urban_body" id="gre_urban_body" data_valid='no'   data-error="Please select taluk" class="select_style">
<option value="" selected disabled>--Select--</option>
<?php
	 while($qua_row = $qua_rs->fetch(PDO::FETCH_BOTH)) {
		$lb_urban_name=$qua_row["lb_urban_name"];
		$lb_urban_tname=$qua_row["lb_urban_tname"]; 
		if($_SESSION["lang"]=='E')
		{
			$lb_urban_name=$lb_urban_name;
		}else if($_SESSION["lang"]=='T')
		{
			$lb_urban_name=$lb_urban_tname;	
		}
		else
		{
			$lb_urban_name=$lb_urban_name;	
		}
		print("<option value='".$qua_row["lb_urban_id"]."' >".$lb_urban_name."</option>");
	 }
	?>
 </select>
 <?php 
} else if ($source_frm=='populate_office') {
	$dist = stripQuotes(killChars($_POST['dist']));
	$dept = stripQuotes(killChars($_POST['dept']));

	$sql = "select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join usr_dept b on a.dept_id=b.dept_id where district_id=". $dist." and a.dept_id=".$dept." union 
	select distinct division_id,division_name,division_tname,dept_name,dept_tname from mst_p_sp_division a inner join 
	usr_dept b on a.dept_id=b.dept_id 	where district_id=". $dist." and a.dept_id=-99 and not exists (select 1 from mst_p_sp_subdivision a inner join usr_dept b on a.dept_id=b.dept_id 
	where district_id=". $dist." and a.dept_id=".$dept.")";	
	$rs=$db->query($sql);
	if (!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	?>

    <select name="gre_division" id="gre_division" data_valid="no"  onChange="get_officer_list();" data-error="Please select office" class="select_style">
    <option value="" selected disabled>--Select--</option>
    <?php
    while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$div_name = $row["division_name"];
		$dep_name =$row["dept_name"];
		$div_tname = $row["division_tname"];
		$dep_tname = $row["dept_tname"];
		if($_SESSION["lang"]=='E')
		{
			$dvname=$div_name." - ".$dep_name;
		}else 	if($_SESSION["lang"]=='T')
		{
			$dvname=$div_tname." - ".$dep_tname;	
		}
		else
		{
			$dvname=$div_name." - ".$dep_name;	
		}
		print("<option value='".$row["division_id"]."' >".$dvname."</option>");
    	
    }   
	?>
    </select>
<?php 
}// Main Category Start
 else if($source_frm=='get_grievance') { //  Included for Registration Department
	$dept_id = stripQuotes(killChars($_POST['dept_id'])); 
	$fwd_office_level = stripQuotes(killChars($_POST['fwd_office_level']));
	$fwd_office_level_cond = ($fwd_office_level == "") ? "" : " and ".$fwd_office_level."=any(off_level_id)";
    $dept_cond = '';
	//Greivance category based on department 10-11-2018
/*
SELECT a.griev_type_id, a.griev_type_code, a.griev_type_name, a.griev_type_tname
FROM lkp_griev_type a
WHERE a.griev_type_id in (SELECT griev_type_id FROM usr_dept_griev_subtype WHERE dept_id=1 and 1 = ANY(off_level_id)) and a.enabling
ORDER BY a.griev_type_name;
*/
	if ($fwd_office_level == 10) {
		$sql = "SELECT a.griev_type_id, a.griev_type_code, a.griev_type_name, a.griev_type_tname
				FROM lkp_griev_type a WHERE a.griev_type_id in (SELECT griev_type_id FROM usr_dept_griev_subtype 
				WHERE dept_id=".$dept_id." and 1 = ANY(off_level_id)) and a.enabling ORDER BY a.griev_type_name";
	} else if ($fwd_office_level == 20){
		$sql = "SELECT a.griev_type_id, a.griev_type_code, a.griev_type_name, a.griev_type_tname
				FROM lkp_griev_type a WHERE a.griev_type_id in (SELECT griev_type_id FROM usr_dept_griev_subtype 
				WHERE dept_id=".$dept_id." and 2 = ANY(off_level_id)) and a.enabling ORDER BY a.griev_type_name";
	} else {
		$sql = "SELECT a.griev_type_id, a.griev_type_code, a.griev_type_name, a.griev_type_tname
				FROM lkp_griev_type a WHERE a.griev_type_id in (SELECT griev_type_id FROM usr_dept_griev_subtype 
				WHERE dept_id=".$dept_id." and (10 = ANY(off_level_id) or 11 = ANY(off_level_id)) ) and a.enabling ORDER BY a.griev_type_name";
	}

	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="griev_maincode" id="griev_maincode" data_valid='yes' style="width:260px" onChange="get_sub_category();"  data-error="Please select Main category" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$grename=$row["griev_type_name"];
		$gretname = $row["griev_type_tname"];
		if($_SESSION["lang"]=='E')
		{
		$gre_name = $grename;
		}else if($_SESSION["lang"]=='T')
		{
		$gre_name = $gretname;	
		}else
		{
		$gre_name = $grename;	
		}

		print("<option value='".$row["griev_type_id"]."' >".$gre_name."</option>");
	}
	?>
	</select>
<?php 	
}
// Main Category End
else if($source_frm=='fwd_office_level') { //  Included for Registration Department
	$dept_id = stripQuotes(killChars($_POST['dept_id'])); 

	$sql = "SELECT fwd_office_level_id, fwd_office_level_name, fwd_office_level_tname FROM lkp_fwd_office_level where ".$dept_id."=any(dept_id) ORDER BY fwd_office_level_name";		
			  
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="fwd_office_level" id="fwd_office_level" data_valid='yes' style="width:260px" data-error="Please select Forward Office Level" class="select_style" onchange="loadGrievanceType();">
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$fwd_office_level_name=$row["fwd_office_level_name"];
		$fwd_office_level_tname = $row["fwd_office_level_tname"];
		if($_SESSION["lang"]=='E')
		{
		$fwd_office_level_name = $fwd_office_level_name;
		}else if($_SESSION["lang"]=='T')
		{
		$fwd_office_level_name = $fwd_office_level_tname;	
		}else
		{
		$fwd_office_level_name = $fwd_office_level_name;	
		}

		print("<option value='".$row["fwd_office_level_id"]."' >".$fwd_office_level_name."</option>");
	}
	?>
	</select>
<?php 	
} else if($source_frm=='get_subdivision') {

	$dept_id=$_POST["dept_id"];
	$gre_division=$_POST["gre_division"];
	$district_id=$_POST["district"];
	

	$gre_division_cond=($gre_division != '') ? ' and division_id='.$gre_division : '';
	
	$district_condition = ' and district_id='.$district_id ;
	$gre_division_cond=($gre_division != '') ? ' and division_id='.$gre_division : '';
	if ($gre_division_cond != '') {
		$district_condition = '';
	}
		
	$sub_div_sql="SELECT subdivision_id, district_id,  subdivision_name, subdivision_tname, dept_id  
	FROM mst_p_sp_subdivision where dept_id=".$dept_id.$district_condition.$gre_division_cond."";

	$rs=$db->query($sub_div_sql);
	?>
	<select class="se_box_with" name="gre_subdivision" id="gre_subdivision" onChange="getCircle();" data_valid='no'>
    <?php //if ($rs->rowCount() > 1) { ?>
	<option value="" selected disabled>--Select--</option>
	<?php
	//}
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		$subdivision_id = $row["subdivision_id"];
		$subdivision_name = $row["subdivision_name"];
		$subdivision_tname = $row["subdivision_tname"];
		
		if($_SESSION["lang"]=='E'){
			$subdivision_name=$subdivision_name;
		}else if ($_SESSION["lang"]=='T'){
			$subdivision_name=$subdivision_tname;	
		} else
		{
			$subdivision_name = $subdivision_name;	
		}
		print("<option value='".$row["subdivision_id"]."' >".$subdivision_name."</option>");
    	
    }
	?>
	</select>
	<?php
	
} else if($source_frm=='load_circle') {
	$dept_id=$_POST["dept_id"];
	$subdivision=$_POST["subdivision"];
	
	echo $sub_div_sql="SELECT circle_id, circle_name, circle_tname, dept_id  FROM mst_p_sp_circle where subdivision_id=".$subdivision." and dept_id=".$dept_id."";
	$rs=$db->query($sub_div_sql);
	?>
	<select name="gre_circle" id="gre_circle"  class="select_style" onChange="get_officer_list();">
    <option value="" selected disabled>--Select--</option>
	<?php
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {

		//$subdivision_id = $row["subdivision_id"];
		$circle_name = $row["circle_name"];
		$circle_tname = $row["circle_tname"];
		
		if($_SESSION["lang"]=='E'){
			$circle_name=$circle_name;
		}else if ($_SESSION["lang"]=='T'){
			 $circle_name=$circle_tname;	
		} else {
			$circle_name=$circle_name;	
		}
		
		
		print("<option value='".$row["circle_id"]."' >".$circle_name."</option>");
    	
    }
	?>
	</select>
	<?php
	
} else if($source_frm=='get_district') {
	$comm_state=$_POST["comm_state"];
	if ($comm_state == 33) {
		$sql="SELECT district_id, district_code, district_name, district_tname  FROM mst_p_district where state_id=".$comm_state." and district_id>-1 order by district_name";
		print("<option value='' selected disabled>--Select--</option>");
	} else {
		$sql="SELECT district_id, district_code, district_name, district_tname  FROM mst_p_district where district_id=-1";
	}
	
	$rs=$db->query($sql);
	?>
	<select class="se_box_with" name="comm_dist" id="comm_dist" data_valid='yes' data-error="Please select District">
    <?php
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$district_name=$row["district_name"];
		$district_tname=$row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
			$district_name=$district_name;
		}else if($_SESSION["lang"]=='T')
		{
			$district_name=$district_tname;
		}else 
		{
			$district_name=$district_name;
		}
		print("<option value='".$row["district_id"]."'>".$district_name."</option>");
	
	}
	?>
	</select>
	<?php
	
} else if($source_frm=='load_state') {
	$comm_country=$_POST["comm_country"];
	if ($comm_country == 99) {
		$sql="SELECT state_id, state_code, state_name, state_tname  FROM mst_p_state where state_id>-1";
		print("<option value='' selected disabled>--Select--</option>");
	} else {
		$sql="SELECT state_id, state_code, state_name, state_tname  FROM mst_p_state where state_id=-1";
	}
	
	$rs=$db->query($sql);
	?>
	<select class="se_box_with" name="comm_dist" id="comm_dist" data_valid='yes' data-error="Please select District">
    <?php
	while($row = $rs->fetch(PDO::FETCH_BOTH))
	{
		$state_name=$row["state_name"];
		$state_tname=$row["state_tname"];
		if($_SESSION["lang"]=='E')
		{
			$state_name=$state_name;
		}else if($_SESSION["lang"]=='T')
		{
			$state_name=$state_tname;
		}else 
		{
			$state_name=$state_name;
		}
		print("<option value='".$row["state_id"]."'>".$state_name."</option>");
	
	}
	?>
	</select>
	<?php
}
else if($source_frm=='po') { //  Included for Registration Department
	/* $sql = "select dept_off_level_pattern_id,dept_off_level_pattern_name,dept_off_level_pattern_tname,enabling,ordering from usr_dept_off_level_pattern where dept_off_level_pattern_id!=2 order by dept_off_level_pattern_id ;" ; */	
	$sql = "select a.dept_off_level_pattern_id,a.dept_off_level_pattern_name,a.dept_off_level_pattern_tname,
	b.off_level_dept_id,b.off_level_dept_name,b.off_level_dept_tname ,b.off_level_id
	from usr_dept_off_level_pattern a 
	inner join usr_dept_off_level b on b.dept_off_level_pattern_id=a.dept_off_level_pattern_id
	where b.dept_off_level_pattern_id!=2 order by a.dept_off_level_pattern_id,b.off_level_dept_id" ;
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="off_level" id="off_level" data_valid='yes' style="width:260px" data-error="Please select Office Level" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
	$off_level_id=$row["off_level_id"];
		$dept_off_level_pattern_id=$row["dept_off_level_pattern_id"];
		$dept_off_level_pattern_name=$row["dept_off_level_pattern_name"];
		$dept_off_level_pattern_tname = $row["dept_off_level_pattern_tname"];
		
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_tname = $row["off_level_dept_tname"];
		$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"]=='')? 0:$row["dept_off_level_office_id"];
		$off_level = $dept_off_level_pattern_id.'-'.$off_level_id.'-'.$row["off_level_dept_id"].'-'.$off_level_office_id;
		
		if($_SESSION["lang"]=='E')
		{
			$dept_off_level_pattern_name = $dept_off_level_pattern_name;
			$off_level_dept_name = $off_level_dept_name;
		}else if($_SESSION["lang"]=='T')
		{
			$dept_off_level_pattern_name = $dept_off_level_pattern_tname;	
			$off_level_dept_name = $off_level_dept_tname;	
		}else
		{
			$dept_off_level_pattern_name = $dept_off_level_pattern_name;	
			$off_level_dept_name = $off_level_dept_name;	
		}
		if ($prev_off_lvl_pattern_id<>$row["dept_off_level_pattern_id"]) {
			print("<optgroup label='".$dept_off_level_pattern_name."'>");
		}
		print("<option value='".$off_level."' >".$off_level_dept_name."</option>");
		$prev_off_lvl_pattern_id=$row["dept_off_level_pattern_id"]; 
	}
	?>
	</select>
<?php 	
}
	
else if($source_frm=='get_po') { //  Included for Registration Department
	$fwd_office_id = stripQuotes(killChars($_POST['fwd_office_id'])); 
    $dept_cond = '';
	//Greivance category based on department 10-11-2018
/*
SELECT a.griev_type_id, a.griev_type_code, a.griev_type_name, a.griev_type_tname
FROM lkp_griev_type a
WHERE a.griev_type_id in (SELECT griev_type_id FROM usr_dept_griev_subtype WHERE dept_id=1 and 1 = ANY(off_level_id)) and a.enabling
ORDER BY a.griev_type_name;
*/
		$sql = "select off_level_dept_id,off_level_dept_name,off_level_dept_tname,enabling,ordering from usr_dept_off_level where dept_off_level_pattern_id =".$fwd_office_id." order by off_level_dept_id";
	

	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="off_level" id="off_level" data_valid='yes' style="width:260px" data-error="Please select Office Level" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_name = $row["off_level_dept_tname"];
		if($_SESSION["lang"]=='E')
		{
		$off_level_dept_name = $off_level_dept_name;
		}else if($_SESSION["lang"]=='T')
		{
		$off_level_dept_name = $off_level_dept_tname;	
		}else
		{
		$off_level_dept_name = $off_level_dept_name;	
		}

		print("<option value='".$row["off_level_dept_id"]."' >".$off_level_dept_name."</option>");
	}
	?>
	</select>
<?php 	
}
	
else if($source_frm=='Submission_Level') { //  Included for Registration Department
$fwd_office_id = stripQuotes(killChars($_POST['fwd_office_id'])); 
	$off_level = stripQuotes(killChars($_POST['off_level'])); 
	if ($fwd_office_id==3){
		$sql = "select off_level_dept_id,off_level_dept_name,off_level_dept_tname,enabling,ordering from usr_dept_off_level where (dept_off_level_pattern_id =".$fwd_office_id." or dept_off_level_pattern_id is null) and (off_level_dept_id<".$off_level." and ordering <18) order by off_level_dept_id desc";
	}else if ($fwd_office_id==1){
		$sql = "select off_level_dept_id,off_level_dept_name,off_level_dept_tname,enabling,ordering from usr_dept_off_level where (dept_off_level_pattern_id =".$fwd_office_id." or dept_off_level_pattern_id is null) and (off_level_dept_id<".$off_level."  and ordering <7) order by off_level_dept_id desc";
	}else if ($fwd_office_id==4){
		$sql = "select off_level_dept_id,off_level_dept_name,off_level_dept_tname,enabling,ordering from usr_dept_off_level where (dept_off_level_pattern_id =".$fwd_office_id." or dept_off_level_pattern_id is null) and (off_level_dept_id<".$off_level."  and ordering <22) order by off_level_dept_id desc";
	}

	
	echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="off_level" id="off_level" data_valid='yes' style="width:260px" data-error="Please select Office Level" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$off_level_dept_name=$row["off_level_dept_name"];
		$off_level_dept_name = $row["off_level_dept_tname"];
		if($_SESSION["lang"]=='E')
		{
		$off_level_dept_name = $off_level_dept_name;
		}else if($_SESSION["lang"]=='T')
		{
		$off_level_dept_name = $off_level_dept_tname;	
		}else
		{
		$off_level_dept_name = $off_level_dept_name;	
		}

		print("<option value='".$row["off_level_dept_id"]."' >".$off_level_dept_name."</option>");
	}
	?>
	</select>
<?php 	
}
else if($source_frm=='loadzone') {
	
$pattern = stripQuotes(killChars($_POST['pattern'])); 
$sql = "select zone_id,zone_name,zone_tname,enabling,ordering from mst_p_sp_zone where (dept_off_level_pattern_id =".$pattern." ) order by zone_id";
$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo 
	"<option value='' selected >--Select--</option>";
	foreach($rowarray as $row)
	{   
		$zone_name=$row["zone_name"];
		$zone_tname = $row["zone_tname"];
		if($_SESSION["lang"]=='E')
		{
		$zone_name = $zone_name;
		}else if($_SESSION["lang"]=='T')
		{
		$zone_name = $zone_tname;	
		}else
		{
		$zone_name = $zone_name;	
		}

		print("<option value='".$row["zone_id"]."' >".$zone_name."</option>");
	}
}
else if($source_frm=='loaddistsp') {
	
$pattern = stripQuotes(killChars($_POST['pattern'])); 
$sql = "select district_id,district_name,district_tname,enabling,ordering from mst_p_district where (dept_off_level_pattern_id =".$pattern." ) order by district_name";
$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo 
	"<option value='' selected >--Select--</option>";
	foreach($rowarray as $row)
	{   
		$district_name=$row["district_name"];
		$district_tname = $row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
		$district_name = $district_name;
		}else if($_SESSION["lang"]=='T')
		{
		$district_name = $district_tname;	
		}else
		{
		$district_name = $district_name;	
		}

		print("<option value='".$row["district_id"]."' >".$district_name."</option>");
	}
}
else if($source_frm=='Load_Office') { //  Included for Registration Department
$fwd_office_id = stripQuotes(killChars($_POST['fwd_office_id'])); 
	$off_level = stripQuotes(killChars($_POST['off_level'])); 
	
	if($fwd_office_id==1 and $off_level==4){
		$fwd_office_id=1;
		$sql = "select zone_id,zone_name,zone_tname,enabling,ordering from mst_p_sp_zone where (dept_off_level_pattern_id =".$fwd_office_id." ) order by zone_id";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="zone" id="zone" data_valid='yes' style="width:260px" data-error="Please select Zone" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$zone_name=$row["zone_name"];
		$zone_tname = $row["zone_tname"];
		if($_SESSION["lang"]=='E')
		{
		$zone_name = $zone_name;
		}else if($_SESSION["lang"]=='T')
		{
		$zone_name = $zone_tname;	
		}else
		{
		$zone_name = $zone_name;	
		}

		print("<option value='".$row["zone_id"]."' >".$zone_name."</option>");
	}
	?>
	</select>
	<?php
	}
	elseif($fwd_office_id==1 and $off_level==5){
		$fwd_office_id=1;
		$sql = "select range_id,range_name,range_tname,enabling,ordering from mst_p_sp_range where (dept_off_level_pattern_id =".$fwd_office_id." ) order by range_id";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="range" id="range" data_valid='yes' style="width:260px" data-error="Please select range" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$range_name=$row["range_name"];
		$range_tname = $row["range_tname"];
		if($_SESSION["lang"]=='E')
		{
		$range_name = $range_name;
		}else if($_SESSION["lang"]=='T')
		{
		$range_name = $range_tname;	
		}else
		{
		$range_name = $range_name;	
		}

		print("<option value='".$row["range_id"]."' >".$range_name."</option>");
	}
	?>
	</select>
	<?php
	}elseif($fwd_office_id==1 and $off_level==6){
		$fwd_office_id=1;
		$sql = "select district_id,district_name,district_tname,enabling,ordering FROM mst_p_district where dept_off_level_pattern_id=$fwd_office_id order by case when district_id<0 then 1 else 0 end,district_name";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="district" id="district" data_valid='yes' style="width:260px" data-error="Please select district" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$district_name=$row["district_name"];
		$district_tname = $row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
		$district_name = $district_name;
		}else if($_SESSION["lang"]=='T')
		{
		$district_name = $district_tname;	
		}else
		{
		$district_name = $district_name;	
		}

		print("<option value='".$row["district_id"]."' >".$district_name."</option>");
	}
	?>
	</select>
	<?php
	}elseif($fwd_office_id==3 and $off_level==17){
		$fwd_office_id=3;
		$sql = "select district_id,district_name,district_tname,enabling,ordering FROM mst_p_district where dept_off_level_pattern_id=$fwd_office_id order by case when district_id<0 then 1 else 0 end,district_name";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="district" id="district" data_valid='yes' style="width:260px" data-error="Please select district" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$district_name=$row["district_name"];
		$district_tname = $row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
		$district_name = $district_name;
		}else if($_SESSION["lang"]=='T')
		{
		$district_name = $district_tname;	
		}else
		{
		$district_name = $district_name;	
		}

		print("<option value='".$row["district_id"]."' >".$district_name."</option>");
	}
	}
		/* $fwd_office_id=3;
		$sql = "select district_id,district_name,district_tname,enabling,ordering FROM mst_p_district order by case when district_id<0 then 1 else 0 end,district_name";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="district" id="district" data_valid='yes' style="width:260px" data-error="Please select district" class="select_style" >
	<option value="">--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$district_name=$row["district_name"];
		$district_tname = $row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
		$district_name = $district_name;
		}else if($_SESSION["lang"]=='T')
		{
		$district_name = $district_tname;	
		}else
		{
		$district_name = $district_name;	
		}

		print("<option value='".$row["district_id"]."' >".$district_name."</option>");
	} 
	?>
	</select>
	<?php*/
	elseif($fwd_office_id==1 and $off_level==7){return 1;}//division
	elseif($fwd_office_id==1 and $off_level==8){
	}
	else if($fwd_office_id==3 and $off_level==14){
		//empty
		//print('wait');
		print("<option value='29'>Tamil Nadu</option>");
	}	
	else if($fwd_office_id==3 and $off_level==18){
		//sub-division
		print("processing");
	}else if($fwd_office_id==4 and $off_level==21){return 1;}
		/* $fwd_office_id=4;
		$sql = "select district_id,district_name,district_tname,enabling,ordering FROM mst_p_district order by case when district_id<0 then 1 else 0 end,district_name";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="district" id="district" data_valid='yes' style="width:260px" data-error="Please select district" class="select_style" >
	<option value="">--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$district_name=$row["district_name"];
		$district_tname = $row["district_tname"];
		if($_SESSION["lang"]=='E')
		{
		$district_name = $district_name;
		}else if($_SESSION["lang"]=='T')
		{
		$district_name = $district_tname;	
		}else
		{
		$district_name = $district_name;	
		}

		print("<option value='".$row["district_id"]."' >".$district_name."</option>");
	}
	?>
	</select>
	<?php */
	else if($fwd_office_id==4 and $off_level==22){
		print("processing");
	}else if($fwd_office_id==4 and $off_level==23){

	}
	else if($fwd_office_id==3 and $off_level==15){
		$fwd_office_id=3;
		$sql = "select zone_id,zone_name,zone_tname,enabling,ordering from mst_p_sp_zone where (dept_off_level_pattern_id =".$fwd_office_id." ) order by zone_id";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="zone" id="zone" data_valid='yes' style="width:260px" data-error="Please select Zone" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$zone_name=$row["zone_name"];
		$zone_tname = $row["zone_tname"];
		if($_SESSION["lang"]=='E')
		{
		$zone_name = $zone_name;
		}else if($_SESSION["lang"]=='T')
		{
		$zone_name = $zone_tname;	
		}else
		{
		$zone_name = $zone_name;	
		}

		print("<option value='".$row["zone_id"]."' >".$zone_name."</option>");
	}
	?>
	</select>
	<?php
	}
	else if($fwd_office_id==4 and $off_level==20){
		$fwd_office_id=4;
		$sql = "select zone_id,zone_name,zone_tname,enabling,ordering from mst_p_sp_zone where (dept_off_level_pattern_id =".$fwd_office_id." ) order by zone_id";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="zone" id="zone" data_valid='yes' style="width:260px" data-error="Please select Zone" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$zone_name=$row["zone_name"];
		$zone_tname = $row["zone_tname"];
		if($_SESSION["lang"]=='E')
		{
		$zone_name = $zone_name;
		}else if($_SESSION["lang"]=='T')
		{
		$zone_name = $zone_tname;	
		}else
		{
		$zone_name = $zone_name;	
		}

		print("<option value='".$row["zone_id"]."' >".$zone_name."</option>");
	}
	?>
	</select>
	<?php
	}elseif($fwd_office_id==3 and $off_level==16){
		$fwd_office_id=3;
		$sql = "select range_id,range_name,range_tname,enabling,ordering from mst_p_sp_range where (dept_off_level_pattern_id =".$fwd_office_id." ) order by range_id";
	
	//echo $sql;		  
		      
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	?>
	<select name="range" id="range" data_valid='yes' style="width:260px" data-error="Please select range" class="select_style" >
	<option value="" selected disabled>--Select--</option>
	<?php
	foreach($rowarray as $row)
	{   
		$range_name=$row["range_name"];
		$range_tname = $row["range_tname"];
		if($_SESSION["lang"]=='E')
		{
		$range_name = $range_name;
		}else if($_SESSION["lang"]=='T')
		{
		$range_name = $range_tname;	
		}else
		{
		$range_name = $range_name;	
		}

		print("<option value='".$row["range_id"]."' >".$range_name."</option>");
	}
	}
	
	?>
	</select>
<?php 	
}else if($source_frm=='p1_search1') {

	//Basic Parameters
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));$pattern=$off_level_dept_id;
 	/* $dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));  */
	$dept_id=1;
	$pre=stripQuotes(killChars($_POST['pre']));
	
 	/* $dept_off_level_office_id=($dept_off_level_office_id == '0') ? '': $dept_off_level_office_id;
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == '0') ? '': $dept_off_level_pattern_id;  */
	
	//Search
	//$district_id=stripQuotes(killChars($_POST['district_id']));  //Change off_id to district_id
	/* $off_name=stripQuotes(killChars($_POST['off_name']));*/
	// $loc_first= stripQuotes(killChars($_POST['loc_first'])); //change to loc_first 
	 // if($loc_first!=''){
		// $loc_cond=" and ".$pre."_name LIKE '".$loc_first."%'";
	// }else{
		// $loc_cond='';
	// }
		if($pattern==1){
			$codn=" where b.dept_off_level_pattern_id=$pattern and b.district_id > 0 ";
		}else if($pattern==3){
			$codn=' where b.dept_off_level_pattern_id=$pattern and b.district_id > 0 ';
		}else if($pattern==4){
			$codn=' where b.dept_off_level_pattern_id=$pattern and b.district_id > 0 ';
		}else{
			$codn='';
		}
	//	echo $pattern.">>>>>>>".$codn;exit;
	if ($pre=='division'){//echo "111111".$pre;
	$query="select division_id,division_name,division_tname,enabling,ordering from mst_p_sp_division where district_id=".$district_id." ".$loc_cond." and dept_id=1 ;";
	$query="select distinct(b.district_id),b.district_name ,b.district_tname from mst_p_sp_division a inner join mst_p_district b on a.district_code=b.district_code $codn order by b.district_name;";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{		
		echo "<off_loc_id>$row[district_id]</off_loc_id>";
		echo "<off_loc_name>$row[district_name]</off_loc_name>";
		echo "<off_loc_tname>$row[district_tname]</off_loc_tname>";
	}
	
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "<sql>".$query."</sql>";
	echo "</response>";
}
else if ($pre=='subdivision'){
	$query="select subdivision_id,subdivision_name,subdivision_tname,enabling,ordering from mst_p_sp_subdivision where district_id=".$district_id." ".$loc_cond." and dept_id=1 ;";
	$query="select distinct(b.district_id),b.district_name ,b.district_tname from mst_p_sp_subdivision a inner join mst_p_district b on a.district_id=b.district_id $codn order by b.district_name;";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{	
		echo "<off_loc_id>$row[district_id]</off_loc_id>";
		echo "<off_loc_name>$row[district_name]</off_loc_name>";
		echo "<off_loc_tname>$row[district_tname]</off_loc_tname>";	
		
	}
	
	
	echo "<sql>".$query."</sql>";
	echo "</response>";/* echo "<off_loc_id>$row[subdivision_id]</off_loc_id>";
		echo "<off_loc_name>$row[subdivision_name]</off_loc_name>";
		echo "<off_loc_tname>$row[subdivision_tname]</off_loc_tname>"; */
		
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	}else if ($pre=='circle'){
	$query="select circle_id,circle_name,circle_tname,enabling,ordering from mst_p_sp_circle  where district_code=(select district_code from mst_p_district where district_id=".$district_id.") ".$loc_cond." and dept_id=1 ;";
	$query="select distinct(b.district_id),b.district_name ,b.district_tname from mst_p_sp_circle a inner join mst_p_district b on a.district_code=b.district_code  $codn order by b.district_name;";
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{
		/* echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>"; */
		echo "<off_loc_id>$row[district_id]</off_loc_id>";
		echo "<off_loc_name>$row[district_name]</off_loc_name>";
		echo "<off_loc_tname>$row[district_tname]</off_loc_tname>";
	}
	
	//$sql_count = 'SELECT COUNT(division_id) FROM ('.$sql .') off_level';
	//$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
	//echo $page->paginationXML($count[0],stripQuotes(killChars($_POST["page_no"])),stripQuotes(killChars($_POST["page_size"])));
	echo "<sql>".$query."</sql>";
	echo "</response>";
	}
}
else if($source_frm=='p1_search') {
	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	if($off_level_dept_id==1){
		$codn1=" and district_id!=2  and dept_off_level_pattern_id=$off_level_dept_id";
	}else if($off_level_dept_id==3){
		$codn1="and dept_off_level_pattern_id=$off_level_dept_id";
	}else if($off_level_dept_id==4){
		$codn1=" and district_id!=2 and dept_off_level_pattern_id=$off_level_dept_id";
	}
	$query="select distinct(district_id),district_name, district_tname from mst_p_district where district_id > 0 $codn1 order by district_name;";
//	echo ">>>>>a".$query;
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

	echo "<response>";
	foreach($rowarray as $row)
	{
		/* echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>"; */
		echo "<off_loc_id>$row[district_id]</off_loc_id>";
		echo "<off_loc_name>$row[district_name]</off_loc_name>";
		echo "<off_loc_tname>$row[district_tname]</off_loc_tname>";
	}
	echo "</response>";
	
}
else if($source_frm=='office') {

	//Basic Parameters
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));$pattern=$off_level_dept_id;
	if($pattern==1){
			$codn=" and district_code!='02'";
		}else if($pattern==3){
			$codn=' and district_id=2';
		}else if($pattern==4){
			$codn=' and district_id!=2';
		}else{
			$codn='';
		}
 	/* $dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));  */
	$dept_id=1;
	$pre=stripQuotes(killChars($_POST['pre']));
	$district1=stripQuotes(killChars($_POST['district']));
	if($pre=="division"){
	$query="select division_id,division_name,division_tname from mst_p_sp_division where district_id=".$district1." $codn and division_id>37";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
//echo $query;
	echo "<response>";
	foreach($rowarray as $row)
	{
		/* echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>"; */
		echo "<off_loc_id>$row[division_id]</off_loc_id>";
		echo "<off_loc_name>$row[division_name]</off_loc_name>";
		echo "<off_loc_tname>$row[division_tname]</off_loc_tname>";
	}
	echo "</response>";
	}else if($pre=="subdivision"){
	$query="select subdivision_id,subdivision_name,subdivision_tname from mst_p_sp_subdivision where district_id=".$district1." $codn";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
//echo $query;
	echo "<response>";
	foreach($rowarray as $row)
	{
		/* echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>"; */
		echo "<off_loc_id>$row[subdivision_id]</off_loc_id>";
		echo "<off_loc_name>$row[subdivision_name]</off_loc_name>";
		echo "<off_loc_tname>$row[subdivision_tname]</off_loc_tname>";
	}
	echo "</response>";
	}else if($pre=="circle"){
		$district1=str_pad($district1, 2, '0', STR_PAD_LEFT);
	$query="select circle_id,circle_name,circle_tname from mst_p_sp_circle where district_code='".$district1."'";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	//echo $query;exit;
	echo "<response>";
	foreach($rowarray as $row)
	{
		/* echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>"; */
		echo "<off_loc_id>$row[circle_id]</off_loc_id>";
		echo "<off_loc_name>$row[circle_name]</off_loc_name>";
		echo "<off_loc_tname>$row[circle_tname]</off_loc_tname>";
	}
	echo "</response>";
	}
}
else if($source_frm=='loadLocations') {
	//Basic parameters	
	$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
	$off_level_dept_id=stripQuotes(killChars($_POST['off_level_dept_id']));
	$dept_off_level_pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));
	$dept_off_level_office_id=stripQuotes(killChars($_POST['dept_off_level_office_id']));
	$dept_id=stripQuotes(killChars($_POST['dept_id']));
	$district=stripQuotes(killChars($_POST['district']));
	
	$dept_off_level_pattern_id=($dept_off_level_pattern_id == 0) ? '':$dept_off_level_pattern_id;
	$dept_off_level_office_id=($dept_off_level_office_id == 0) ? '':$dept_off_level_office_id;
	if($district!=''){
		$condition=" and a.district_id =$district";
	}
		 /*else if ($off_level_id == 42, 44, 46){*/
			if ($off_level_id == 42||$off_level_id == 17) { //Division //17-DC(SP)//42-division lvl
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
				$join_condition = " inner join mst_p_sp_zone c on c.zone_id=a.zone_id";
			}
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_division a on a.division_id=cir.division_id
			".$join_condition."
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			if ($dept_off_level_pattern_id == 46) {
			$sql="select circle_id as off_loc_id,circle_name as off_loc_name,circle_tname as off_loc_tname
			from mst_p_sp_circle cir 
			inner join mst_p_sp_subdivision a on a.subdivision_code=cir.division_code
			".$join_condition."
			where  c.dept_id=".$dept_id." and c.dept_off_level_pattern_id=".$dept_off_level_pattern_id.$condition;
			}
			//echo $sql
		}
		
	
	echo $sql;

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
?>