<?PHP
session_start();
include("db.php");
//include("online_header_status.php");
//include("UserProfile.php");

$mobile_number = $_REQUEST['mobile_number'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Online old petition Search</title>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{	
	$("#p1_submit").click(function(){
		
	//alert(111);
		submitToDesign();
	});
	$("#p1_exit").click(function(){
		//alert(123);
		submitToExit();
	});
	$("#submit_copy_all").click(function(){
		submit_copy_all();
	});
	$("#submit_copy_per").click(function(){
		submit_copy_per();
	});

});
function submit_copy_all(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
	pet=$('input[name=petition_id]:checked', '#p1_petition_search').val();
		opener.p1_returnPetionDetails(pet);
		opener.document.getElementById('confirm_old').style.display='inline-grid';
	Minimize();
	}
}

function submit_copy_per(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		opener.p1_returnPetionorPersonalDetails($('#mobile_number').val());
		Minimize();
	}
}

function submitToDesign(){
	//alert("222222");
	//alert($('input[name=petition_id]:checked', '#p1_petition_search').val());
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		opener.document.getElementById('hid_pet_old').value="";
		opener.p1_returnPetionDetails($('input[name=petition_id]:checked', '#p1_petition_search').val());
		//if old petition not needed comment line below
		opener.document.getElementById('confirm_old').style.display='inline-grid';
		
		Minimize();
	}
	else{
		opener.document.getElementById('hid_pet_old').value="";
		var mobile_number = document.getElementById("mobile_number").value;
		//alert(mobile_number);
		var confirm = window.confirm("No petition is selected do you want to copy only the personal details?");
		if (confirm == true) {
			opener.p1_returnPetionorPersonalDetails(mobile_number);
			$('#hid_old_pet').val('');
		}
		
		Minimize();	
	}
}

function submitToExit() {
	var mobile_number = document.getElementById("mobile_number").value;
	//alert(mobile_number);
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		var confirm = window.confirm("Do you want to exit without any action?");
		if (confirm == true) {
			$('#hid_old_pet').val('');
			Minimize();
		}
	} else {
		var confirm = window.confirm("No petition is selected do you want to copy only the personal details?");
		if (confirm == true) {
			opener.p1_returnPetionorPersonalDetails(mobile_number);
			$('#hid_old_pet').val('');
		}
		Minimize();
	}
}
function openPetitionStatusReport1(petition_id){
	//alert();
	openForm("online_pet_details.php?lang=E&petition_id="+petition_id, "pp_status");
}
</script>
  <input type="hidden" id="language" value="<?php echo $lang['LANGUAGE']; ?>"><div class="se-pre-con"></div>
  <style>
  #header {
    border: 1px solid #000000;
    width: 100%;
    height: 141px;
    /* background-image: url(images/bg_3.png); */
    background-color: #95342e;
    color: #FFFFFF;
}
*{
	vertical-align:middle;
}
  </style>
</head>

<body >
 <style>
.call_now {
    font-size: 24px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 15px;
	font-family: "Open Sans", sans-serif;
	width: 64%;
}
#t3{
	font-size: 18px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
#t4{
	font-size: 22px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
#t5{
	font-size: 16px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	font-family: "Open Sans", sans-serif;
}
.call_now2 {
    font-size: 25px;
	letter-spacing: 2px;
	position: relative;
	font-weight: bold;
	top: 15px;
	font-family: "Open Sans", sans-serif;
}

.fo_si {
 font-size: 19px;
 margin-top: 16px;
 font-weight: bold;
 letter-spacing: 2px;
 width: 64%;
}
@media(min-width:1440px) and (max-width:2254px)
{
	.flex-control-nav {
		left: 190px;
		right: auto;
    }
   .logo {
    margin-left: 57px;
   }
   .initiative_logo {
	 margin-right: -290px; 
   }
   .header-right {
	margin-left: 287px !important;
	width: 78%;	
	}
	.call_now2 {
		width: 68%;
	}
}
 </style>
 <?php include("../ticker.php"); ?>

<form method="post" id="p1_petition_search">
<div class="contentMainDiv">
<div class="contentDiv">


<input type="hidden" name="mobile_number" id="mobile_number" value="<?PHP echo $_REQUEST['mobile_number']?>"/>
<link href="assets/css/base-responsive.css" rel="stylesheet" media="all">

<link href="theme/css/site.css" rel="stylesheet" media="all">
<table class="existRecTbl" style="border-top-style: solid;margin:unset;">
	<thead>
    <tr>
    	<th style="background-color: #BC7676; color: #FFFFFF; font-size: 150%;" colspan="2">Petitions already submitted by Mobile: <?php echo $_GET[mobile_number]; ?></th>
    </tr>
	</thead>
</table>
<table class="gridTbl" style="margin: unset;">
	<thead>
		<tr>
			<th width="5%">Select</th>
			<th width="15%">Petition Number</th>
			<th>Petition Date</th>
			<th>Petitioner Name, Father Name and Address</th>
			<th>Grievance Type and Subtype</th>
			<th width="20%">Petition Detail</th>
			<th width="15%">Source</th>
			<th width="15%">Status</th>
			<th width="15%">Submitted to</th>
			<th width="15%">Addressed to</th>
		</tr>
	</thead>
	<tbody id="p1_dataGrid">
	<?php 
		 $sql="SELECT a.petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, grievance, comm_doorno, comm_aptmt_block, comm_street, comm_area,comm_district_id, comm_district_name, comm_taluk_id, comm_taluk_name, comm_rev_village_id, comm_rev_village_name, coalesce(comm_pincode,griev_pincode) as comm_pincode, comm_mobile, COALESCE(org_petition_no,petition_no) as org_petition_no,  dept_name, pet_type_name,off_level_dept_name,fwd_off_level_dept_name,coalesce(griev_district_name,zone_name,range_name,griev_division_name,griev_circle_name,comm_state_name) as location_name,source_id,(split_part(COALESCE(org_petition_no,petition_no), '/',3)) as org_pet_align,c.action_type_name,l_action_type_code FROM vw_pet_master a left join pet_action_first_last b on a.petition_id=b.petition_id left join lkp_action_type c on c.action_type_code=b.l_action_type_code where comm_mobile='".$mobile_number."' order by petition_id";
		
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
			$action_type_name=$row["action_type_name"];
			if($row["l_action_type_code"]=='A'){
			$action_type_name="<lim style='color:#118e11;font-weight:bolder;'>".$row["action_type_name"]."</lim>";
			}else if($row["l_action_type_code"]=='R'){
			$action_type_name="<lim style='color:#bd0505;font-weight:bolder;'>".$row["action_type_name"]."</lim>";
			}else{
			$action_type_name="Under Process";
			}
			$org_petition_no=$row["org_petition_no"];
			$pet_type_name=$row["pet_type_name"];
			$off_level_dept_name=$row["off_level_dept_name"];
			$fwd_off_level_dept_name=$row["fwd_off_level_dept_name"];
			$location_name=$row["location_name"];
			$source_id=$row["source_id"];
			
			//.$comm_rev_village_name.', '.$comm_taluk_name.', '.$comm_district_name
			$comm_address = $comm_doorno.', '.$comm_street.', '.$comm_area.', '.'<br>Pincode- '.$comm_pincode.'.';
			if($source_id==-1){
		$off_location_design="  {$off_level_dept_name}, {$location_name} ";
			$off_location_desig_level=" {$row['fwd_off_level_dept_name']}.";
	}else if($source_id==1){
			$off_location_design=" Elected Representative / Other VIP";
			$off_location_desig_level=" Elected Representative / Other VIP Office.";
		}else if($source_id==2){
			$off_location_design=" DGP, Tamil Nadu";
			$off_location_desig_level=" DGP Office.";
		}else if($source_id==3){
			$off_location_design=" Commissioner, Tamil Nadu";
			$off_location_desig_level=" Commissioner Office.";
		}else if($source_id==4){
			$off_location_design=" SP";
			$off_location_desig_level=" SP Office.";
		}else if($source_id==5){
			$off_location_design=" IGP";
			$off_location_desig_level=" IGP Office.";
		}else if($source_id==6){
			$off_location_design=" DIG";
			$off_location_desig_level=" DIG Office.";
		}else if($source_id==7){
			$off_location_design=" JCOP";
			$off_location_desig_level=" JCOP Office.";
		}else if($source_id==8){
			$off_location_design=" ADGP CB-CID";
			$off_location_desig_level=" ADGP CB-CID Office.";
		}else if($source_id==9){
			$off_location_design=" IGP CB-CID";
			$off_location_desig_level=" IGP CB-CID Office.";
		}else if($source_id==10){
			$off_location_design=" DIG CB-CID";
			$off_location_desig_level=" DIG CB-CID Office.";
		}else if($source_id==11){
			$off_location_design=" SP CB-CID";
			$off_location_desig_level=" SP CB-CID Office.";
		}else if($source_id==12){
			$off_location_design=" Additional Commissioner";
			$off_location_desig_level=" Additional Commissioner Office.";
		}else if($source_id==13){
			$off_location_design=" Joint Commissioner"; 
			$off_location_desig_level="Joint Commissioner Office.";
		}else if($source_id==14){
			$off_location_design=" Deputy Commissioner";
			$off_location_desig_level="Deputy Commissioner Office.";
		}
		if($off_level_dept_name==''){
			$off_level_dept_name=$off_location_design ;
		}else{
			$off_level_dept_name=$off_level_dept_name.", ".$location_name;
		}
		if($fwd_off_level_dept_name==''){
			if($location_name!=''){
			$fwd_off_level_dept_name=$off_location_desig_level;
			}else{
				$fwd_off_level_dept_name=$off_location_desig_level;
			}
		}
			?>
			<tr>
		<td><input type='radio' name='petition_id' value="<?php echo $petition_id; ?>"/></td>	
    	<td><a style="color:#0000ff;text-decoration:underline;" href="javascript:openPetitionStatusReport1(<?php echo $petition_id?>);" title='Petition Process Report'><?PHP echo $petition_no; //Pincode?></a></td>
    	<td><?PHP echo $petition_date; //Pincode?></td>
    	<td><?PHP echo $p_name.', <br>'.$comm_address; //Pincode?></td>
    	<td><?PHP echo $griev_type_name.', '.$griev_subtype_name; //Pincode?></td>
    	<td><?PHP echo $grievance; //Pincode?></td>
    	<td><?PHP echo $source_name; //Pincode?></td>
    	<td><?PHP echo $action_type_name; //Pincode?></td>
    	<td><?PHP echo $fwd_off_level_dept_name;  //Pincode?></td>
    	<td><?PHP echo  $off_level_dept_name.".";//Pincode?></td>
 	</tr>
		<?php	
		}			
	?>
	
	</tbody>
	</table>

<table class="paginationTbl">
<tbody>
<tr>
<td colspan="3" class="emptyTR"style="vertical-align: middle;height:25px;">
<input type="button" class="button" value="To Copy Applicant Details" id="submit_copy_per" name="submit_copy_per" style="width:11%;vertical-align: middle;">&nbsp;
<input type="button" class="button" value="To Copy Applicant & Petition Details" id="submit_copy_all" name="submit_copy_all" style="width:15%;vertical-align: middle;">&nbsp;
<!--input type="button" class="button" value="Submit" id="p1_submit" name="p1_submit" style='vertical-align: middle;'-->
<input type="button" class="button" value="Exit" id="p1_exit" name="p1_exit" style='vertical-align: middle;'>
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
