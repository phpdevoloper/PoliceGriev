<?PHP
session_start();
include("db.php");
include("UserProfile.php");

$mobile_number = $_REQUEST['mobile_number'];
$petition_id = $_REQUEST['petition_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Office Designation Search</title>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{	
	$("#p1_submit").click(function(){
		submitToDesign();
	});
	$("#p1_exit").click(function(){
		submitToExit();
	});
	

});

function submitToDesign(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val() != ''){
		opener.p1_returnPetionDetails($('input[name=petition_id]:checked', '#p1_petition_search').val());
		Minimize();
	}
	else{
		alert("No petition is selected. Please select a petition to submit?");
		return false;
	}
}

function submitToExit() {
	Minimize();
}
function openPetitionStatusReport1(petition_id){
	openForm("p_PetitionProcessDetails.php?petition_id="+petition_id, "pp_status");
}
</script>
</head>
<body>
<form method="post" id="p1_petition_search">
<div class="contentMainDiv">
<div class="contentDiv">


<input type="hidden" name="mobile_number" id="mobile_number" value="<?PHP echo $_REQUEST['mobile_number']?>"/>
<input type="hidden" name="h_petition_id" id="h_petition_id" value="<?PHP echo $_REQUEST['petition_id']?>"/>
	
<table class="existRecTbl" style="border-top-style: solid;">
	<thead>
    <tr>
    	<th style="background-color: #BC7676; color: #FFFFFF; font-size: 150%;" colspan="2">Petitions already submitted by Mobile: <?php echo $_GET[mobile_number]; ?></th>
    </tr>
	</thead>
</table>
<table class="gridTbl">
	<thead>
		<tr>
			<th width="5%">Select</th>
			<th width="15%">Petition Number</th>
			<th>Petition Date</th>
			<th width="15%">Linking Petition Number</th>
			<th>Petitioner Name, Father Name and Address</th>
			<th>Grievance Type and Subtype</th>
			<th width="20%">Petition Detail</th>
			<th width="15%">Source</th>
		</tr>
	</thead>
	<tbody id="p1_dataGrid">
	<?php 
		 $sql="SELECT petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, grievance, comm_doorno, comm_aptmt_block, comm_street, comm_area,comm_district_id, comm_district_name, comm_taluk_id, comm_taluk_name, comm_rev_village_id, comm_rev_village_name, comm_pincode, comm_mobile, COALESCE(org_petition_no,petition_no) as org_petition_no,  dept_name, pet_type_name,off_level_dept_name FROM vw_pet_master where comm_mobile='".$mobile_number."' and petition_id!=".$petition_id." order by petition_id desc";
		
		$result = $db->query($sql);
		//$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		while($row = $result->fetch(PDO::FETCH_BOTH)) {
			$petition_id=$row["petition_id"];
			$petition_no=$row["petition_no"];
			$petition_date=$row["petition_date"];
			$petitioner_initial=$row["petitioner_initial"];
			$petitioner_name=$row["petitioner_name"];
			$father_husband_name=$row["father_husband_name"];
			$p_name=$petitioner_name.', '.$father_husband_name;
			$source_name=$row["source_name"];
			$griev_type_name=$row["griev_type_name"];
			$griev_subtype_name=$row["griev_subtype_name"];
			$grievance=$row["grievance"];
			$comm_doorno=$row["comm_doorno"];
			$comm_street=$row["comm_street"];
			$comm_area=$row["comm_area"];
			$comm_doorno=$row["comm_doorno"];
			$comm_district_id=$row["comm_district_id"];
			$comm_district_name=$row["comm_district_name"];
			$comm_taluk_id=$row["comm_taluk_id"];
			$comm_taluk_name=$row["comm_taluk_name"];
			$comm_rev_village_id=$row["comm_rev_village_id"];
			$comm_rev_village_name=$row["comm_rev_village_name"];
			$comm_pincode=$row["comm_pincode"];
			$org_petition_no=$row["org_petition_no"];
			$pet_type_name=$row["pet_type_name"];
			
			//.$comm_rev_village_name.', '.$comm_taluk_name.', '.$comm_district_name
			$comm_address = $comm_doorno.', '.$comm_street.', '.$comm_area.', '.'<br>Pincode- '.$comm_pincode.'.';
			?>
			<tr>
		<td><input type='radio' name='petition_id' value="<?php echo $petition_no; ?>"/></td>	
    	<td><a href="javascript:openPetitionStatusReport1(<?php echo $petition_id?>);" title='Petition Process Report'><?PHP echo $petition_no; //Pincode?></a></td>
    	<td><?PHP echo $petition_date; //Pincode?></td>
    	<td><?PHP echo $org_petition_no; //Pincode?></td>
    	<td><?PHP echo $p_name.', <br>'.$comm_address; //Pincode?></td>
    	<td><?PHP echo $griev_type_name.', '.$griev_subtype_name; //Pincode?></td>
    	<td><?PHP echo $grievance; //Pincode?></td>
    	<td><?PHP echo $source_name; //Pincode?></td>
 	</tr>
		<?php	
		}			
	?>
	
	</tbody>
	</table>

<table class="paginationTbl">
<tbody>
<tr>
<td colspan="3" class="emptyTR">
<input type="button" class="button" value="Submit" id="p1_submit" name="p1_submit">
<input type="button" class="button" value="Exit" id="p1_exit" name="p1_exit">
 <input type="hidden" name="petition_id1" id="petition_id1" />
</td>
</tr>
</tbody>
</table>
</div>
</div>
</form>
</body>
</html>
