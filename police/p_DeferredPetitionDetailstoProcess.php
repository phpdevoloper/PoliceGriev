<?PHP 
error_reporting(0);
session_start();
include("header_menu.php");
include("db.php");
include("common_date_fun.php");
include("pm_common_js_css.php");
include("common_form_function.js");

if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
  echo "<script> alert('Timed out. Please login again');self.close();</script>";	   
   
   exit;
}

	if(stripQuotes(killChars($_POST['petition_id']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id']));
	else if(stripQuotes(killChars($_POST['petition_id1']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id1']));
	else if(stripQuotes(killChars($_POST['petition_id2']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id2']));
	else if(stripQuotes(killChars($_POST['petition_id3']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id3']));
	else if(stripQuotes(killChars($_POST['petition_id4']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id4']));
	else if(stripQuotes(killChars($_POST['petition_id5']!="")))
		$received_petition_id = stripQuotes(killChars($_POST['petition_id5']));
if(is_int($received_petition_id)==FALSE){
	$petition_id = $received_petition_id;	
}
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='p_PetitionProcessDetails.php') order by ordering";

$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title style="display:none;">Petition Processing Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/moment.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
window.onunload = function(){
   window.opener.focus(); 
}
function closePopup() {
	window.opener.focus(); 
	window.close();
}
function peocessPetition() {
	document.getElementById("process_petition").style.display='';
	document.getElementById("all_link").style.display='none';
	var action = document.getElementById("action").value;
	var petition_id = document.getElementById("petition_id").value;
	//alert("petition_id:::"+petition_id);
	$.ajax({
		type: "post",
		url: "pm_process_pending_petitions.php",
		cache: false,
		data: {source_frm : 'populate_actions',action:action,petition_id:petition_id},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("action_type").innerHTML=html;
		}
	});	
}
function populateOfficers() {
	var petition_id = document.getElementById("petition_id").value;
	var dept_id = document.getElementById("dept_id").value;
	var griev_district_id = document.getElementById("griev_district_id").value;
	var action_type = document.getElementById("action_type").value;
	var action = document.getElementById("action").value;
	if (action_type == "") {
		document.getElementById("concerned_officer").options.length=0;
		html='select name="concerned_officer" id="concerned_officer" class="select_style"><option value="">--Select--</option></select>';
		document.getElementById("concerned_officer").innerHTML=html;
	} else if (action_type == "T" || action_type == "A" || action_type == "R") {
		document.getElementById("off_code").style.display="none";
		document.getElementById("all_link").style.display="none";
		document.getElementById("off_name").style.display="none";
	} else {
		if (action_type != "F") {
			document.getElementById("all_link").style.display="none";
		} else {
			document.getElementById("all_link").style.display="";
		}
		$.ajax({
			type: "post",
			url: "pm_process_pending_petitions.php",
			cache: false,
			data: {source_frm : 'populate_officers',petition_id:petition_id,action_type:action_type,dept_id:dept_id,griev_district_id:griev_district_id,action:action},
			error:function(){ alert("some error occurred") },
			success: function(html){
				document.getElementById("concerned_officer").innerHTML=html;
				if (action_type == 'Q' || action_type == 'N' || action_type == 'C' || action_type == 'E'|| action_type == 'I'|| action_type == 'S') {
					concernedOffId();
				}
			}
		});	
	}
}
function get_all_officer_list(){
	var griev_sub_code=document.getElementById("griev_subcode").value;
	var griev_district_id = document.getElementById("griev_district_id").value;
	var griev_taluk_id = document.getElementById("griev_taluk_id").value;
	
	var griev_rev_village_id = document.getElementById("griev_rev_village_id").value;
	var griev_block_id = document.getElementById("griev_block_id").value;
	var griev_lb_village_id = document.getElementById("griev_lb_village_id").value;
	var griev_lb_urban_id = document.getElementById("griev_lb_urban_id").value;
	var griev_division_id = document.getElementById("griev_division_id").value;
	var griev_subdivision_id = document.getElementById("griev_subdivision_id").value;
	var griev_circle_id = document.getElementById("griev_circle_id").value;
	var off_level_pattern_id = document.getElementById("off_level_pattern_id").value;
	var dept_id = document.getElementById("dept_id").value;
	var hid_off_loc_id=document.getElementById("hid_off_loc_id").value;
	var off_level_id=document.getElementById("off_level_id").value;
	var source_id=document.getElementById("source_id").value;

	//alert("source_id::::"+source_id);
	if(off_level_pattern_id==1)
	{
		 if(off_level_id==2)
		 {
			var loc_id = griev_taluk_id;
			var griv_loc_off_level_id = 4; 
	 	 }
		 else if(off_level_id==3)
		 {
			 var loc_id = griev_taluk_id;
			var griv_loc_off_level_id = 4;  
		 }
		 else if(off_level_id==4)
		 { 
		 	var loc_id = griev_rev_village_id;
			var griv_loc_off_level_id = 8;
		 }
	}
	else if(off_level_pattern_id==2)
	{
	 	  if(off_level_id==2)
		  {
				var loc_id = griev_block_id;
				var griv_loc_off_level_id = 6;	
	 	  }
		  else if(off_level_id==6)
		  { 
				var loc_id = griev_lb_village_id;
				var griv_loc_off_level_id = 9;
		  }
	}
	else if(off_level_pattern_id==3)
	{
	 	  if(off_level_id==2)
		  {
				var loc_id = griev_lb_urban_id;
				var griv_loc_off_level_id = 7;	
	 	  }
		  
	}
	else if(off_level_pattern_id==4)
	{
		  if(off_level_id==1)
		  {
		  		var loc_id = 33;
				var griv_loc_off_level_id = 1;
	 	  }
		  else if(off_level_id==2)
		  {
		  	if (griev_division_id != '') {
				var loc_id = griev_division_id;
				var griv_loc_off_level_id = 10;
			} else {
				var loc_id = griev_district_id;
				var griv_loc_off_level_id = 10;
			}
	 	  }
		   else if(off_level_id==10)
		  {
		  		var loc_id = griev_subdivision_id;
				var griv_loc_off_level_id = 11;
		  }
		  else if(off_level_id==11)
		  {
		    	var loc_id = griev_circle_id;
				var griv_loc_off_level_id = 12;
		  }
		  
	}
	var disposing_officer = $('#userId').val();	
	openForm("Get_all_officer_Form.php?open_form=P1&off_loc_id="+loc_id+"&hid_pattern_id="+off_level_pattern_id
		+"&griev_sub_code="+griev_sub_code+"&department_id="+dept_id+"&district_id="+griev_district_id
		+"&disp_officer="+disposing_officer+"&griv_loc_off_level_id="+griv_loc_off_level_id+"&source="+source_id, "office_design_search");	
																	
	 
}
function concernedOffId() {
	document.getElementById("off_d_id").value = $("#concerned_officer").val();	
	
}
function p1_returnDesignationSearch(petition_id, userID, offLoc_designName){
	$('#off_id').val(userID);
	$('#off_d_id').val(userID);
	$('#off_name').val(offLoc_designName);
	document.getElementById("off_name").style.display="";
	document.getElementById("concerned_officer").style.display="none";
}
function saveProcess() {
	var desig_id = document.getElementById("concerned_officer").value;
	var petition_id = document.getElementById("petition_id").value;
	var action_type = document.getElementById("action_type").value;
	var off_id = $('#off_id').val();
	var off_d_id = $('#off_d_id').val();
	var concerned_officer = $('#concerned_officer').val();
	var file_no = $('#file_no').val();
	var file_date = $('#file_date').val();
	var file_date = $('#file_date').val();
	var remarks = $('#remarks').val();
	var conc_off = '';
	if (concerned_officer != '') {
		conc_off = concerned_officer;
	} else if (off_id != '') {
		conc_off = off_id;
	}
	if (conc_off == '') {
		alert('No officer is selected for this opeeration. Kindly check');
	} else {
		var strconfirm = confirm("Are you sure process the Petition?");
		if (strconfirm == true) {			
			$.ajax({
				type: "POST",
				dataType: "xml",
				url: "pm_pet_detail_entry_get_dept_action.php",  
				data: "mode=process_deferred_petition"+"&petition_id="+petition_id+"&conc_off="+off_d_id+"&action_type="+action_type+"&file_no="+file_no+"&file_date="+file_date+"&remarks="+remarks,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					var count = $(xml).find('result').eq(0).text();
					if (count > 0) {
						alert("Petition is processed successfully");
						self.close();
					}
				}
			});
		} else {
			return false;
		}
	}
	
}
</script>

</head>
<body>
<?php

	$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name, subsource_name,fwd_office_level_name,griev_type_name, griev_subtype_name,dept_name, grievance, canid, comm_doorno, 
	comm_aptmt_block, comm_street, comm_area, griev_district_name, griev_taluk_name, griev_rev_village_name, comm_pincode, comm_email, comm_phone, comm_mobile, comm_district_name,comm_taluk_name,comm_rev_village_name, griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_tname, griev_taluk_tname,griev_rev_village_tname, griev_block_name, griev_lb_village_name, griev_lb_urban_name,griev_division_name,griev_circle_name,	griev_subdivision_name, griev_pincode,aadharid,pet_type_name,comm_state_name,comm_country_name,	pet_community_name,pet_community_tname, petitioner_category_name, petitioner_category_tname,passport_number,griev_district_id,griev_taluk_id, griev_rev_village_id,  griev_block_id, griev_lb_village_id, griev_lb_urban_id, griev_division_id, griev_subdivision_id,griev_circle_id,off_level_pattern_id,dept_id,griev_subtype_id,source_id
	FROM vw_pet_master WHERE petition_id=".$petition_id."";
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);	
?>
<div class="contentMainDiv" style="width:98%;background-color:#bc7676;margin-right:auto;margin-left:auto;">
<div class="contentDiv" style="background-color:#F4CBCB;">
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">
        	<?PHP echo $label_name[14];//Petition Processing Details?>
        </td>
    </tr>
	<tr>
    	<td colspan="2" class="heading">
        	<?PHP echo $label_name[15];//Petition Details?>
        </td>
    </tr>
<?PHP 
if(sizeof($rowarray)!=0)
{	
	foreach($rowarray as $row)
	{
		
		if ($row['subsource_name'] != '') {
			$source_name_details = $row['source_name'].' - '.$row['subsource_name'];
		} else {
			$source_name_details = $row['source_name'];
		}
		
		$griev_district_id=$row['griev_district_id'];
		$griev_taluk_id=$row['griev_taluk_id'];
		$griev_rev_village_id=$row['griev_rev_village_id'];
		$griev_block_id=$row['griev_block_id'];
		$griev_lb_village_id=$row['griev_lb_village_id'];
		$griev_lb_urban_id=$row['griev_lb_urban_id'];
		$griev_division_id=$row['griev_division_id'];
		$griev_subdivision_id=$row['griev_subdivision_id'];
		$griev_circle_id=$row['griev_circle_id'];
		$off_level_pattern_id=$row['off_level_pattern_id'];
		$dept_id=$row['dept_id'];
		$griev_subtype_id=$row['griev_subtype_id'];
		$petition_id=$row['petition_id'];
		$source_id=$row['source_id'];
	?>
     <!-- Petition Details Building Block : Begins Here-->
	<input type="hidden" name="griev_district_id" id="griev_district_id" value="<?PHP echo $griev_district_id; ?>"  />
	<input type="hidden" name="griev_taluk_id" id="griev_taluk_id" value="<?PHP echo $griev_taluk_id; ?>"  />
	<input type="hidden" name="griev_rev_village_id" id="griev_rev_village_id" value="<?PHP echo $griev_rev_village_id; ?>"  />
	<input type="hidden" name="griev_block_id" id="griev_block_id" value="<?PHP echo $griev_block_id; ?>"  />
	<input type="hidden" name="griev_lb_village_id" id="griev_lb_village_id" value="<?PHP echo $griev_lb_village_id; ?>"  />
	<input type="hidden" name="griev_lb_urban_id" id="griev_lb_urban_id" value="<?PHP echo $griev_lb_urban_id; ?>"  />
	<input type="hidden" name="griev_division_id" id="griev_division_id" value="<?PHP echo $griev_division_id; ?>"  />
	<input type="hidden" name="griev_subdivision_id" id="griev_subdivision_id" value="<?PHP echo $griev_subdivision_id; ?>"  />
	<input type="hidden" name="griev_circle_id" id="griev_circle_id" value="<?PHP echo $griev_circle_id; ?>"  />
	<input type="hidden" name="off_level_pattern_id" id="off_level_pattern_id" value="<?PHP echo $off_level_pattern_id; ?>"  />
	<input type="hidden" name="dept_id" id="dept_id" value="<?PHP echo $dept_id; ?>"  />
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?PHP echo $userProfile->getOff_level_id(); ?>"/>
    <input type="hidden" name="hid_off_loc_id" id="hid_off_loc_id" value="<?php echo $userProfile->getOff_loc_id();?>" />	
    <input type="hidden" name="griev_subcode" id="griev_subcode" value="<?php echo $griev_subtype_id;?>" />
	<input type="hidden" name="petition_id" id="petition_id" value="<?php echo $petition_id;?>" />	
	<input type="hidden" name="source_id" id="source_id" value="<?php echo $source_id;?>" />	
	<input type="hidden" name="userId" id="userId" value="<?PHP echo $_SESSION['USER_ID_PK']; ?>"/>
    
	<tr>
		<td><?PHP echo $label_name[0];//Petition No and Date?></td> 
		<td><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']. ' ('.$row['pet_type_name'].')'; ?></td>
	</tr><tr>	
		<td><?PHP echo $label_name[1];//Source Name & Sub Source Name?></td>
		<td><?php echo $source_name_details.', '.$row['fwd_office_level_name'];?></td>
	</tr>
    <tr>
		<td><?PHP echo $label_name[3];//Department?></td>
        <td ><?php echo $row['dept_name'];?></td>
	</tr>

	<tr>
    	<td><?PHP echo $label_name[2];//Petition Main Type and Petition Sub Type?></td>
		<td><?php echo $row['griev_type_name'].' & '.$row['griev_subtype_name'];?></td>
	</tr>	
    	
	
    <tr>
    	<td><?PHP echo $label_name[4];//Petition Details?></td>
		<td><?php echo $row['grievance'];?></td>
	
	</tr>
      
    
   
    <?PHP
		if($row['griev_taluk_name']!="") {
			$pet_off_address = $row['griev_rev_village_name'].$label_name[22].', '. $row['griev_taluk_name'].$label_name[21].', '.$row['griev_district_name'].$label_name[20];
		} else if ($row['griev_block_name']!="") {
			$pet_off_address = $row['griev_lb_village_name'].$label_name[23].', '. $row['griev_block_name'].$label_name[24].', '.$row['griev_district_name'].$label_name[20]; //Block Village Panchayat
		} else if($row['griev_lb_urban_name']!="") {
			$pet_off_address = $row['griev_district_name'].$label_name[20].', '. $row['griev_lb_urban_name'].$label_name[25];   //Urban Local Body
		} else {
			$pet_off_address = '';
			//griev_circle_name,griev_subdivision_name,griev_division_name
			if ($row['griev_circle_name']!="") {
				$pet_off_address .= $row['griev_circle_name'];
			}
			if ($row['griev_subdivision_name']!="") {
				$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_subdivision_name'] : $row['griev_subdivision_name'];
			}
			if ($row['griev_division_name']!="") {
				$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_division_name'] : $row['griev_division_name'];
			}
			$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_district_name'].$label_name[20] : $row['griev_district_name'].$label_name[20];
		}   //Office
	?>
    <tr>
    	<td><?PHP echo $label_name[5];//Petition Office Address?></td>
        <td><?php echo $pet_off_address;?></td>
	</tr>	
    
	<tr>
    	<td><?PHP echo $label_name[17].' & '.$label_name[18];//Petitioner Name,Father / Spouse Name and Address?></td>
		<td><?php echo $row['petitioner_name'].', '.$label_name[18].': '.$row['father_husband_name'];?></td>
	</tr>

	<?php
		$community_category_name = "";
		if($row['pet_community_name']!="")
			$community_category_name.=$label_name[38].': '.$row['pet_community_name'];
		else
			$community_category_name.=$label_name[38].': ---';
		
		if($row['petitioner_category_name']!="") {
			$community_category_name.=", ".$label_name[39].': '.$row['petitioner_category_name'];
		} else {
			$community_category_name.=", ".$label_name[39].': ---';
		}
	?>
	<?php if ((strlen(trim($community_category_name))) > 0) { ?>
	<tr>
	<td><?php echo  $label_name[37]//Petitioner Community and Category;?></td>
	<td><?PHP echo $community_category_name; ?></td>
	</tr>
	<tr>
	<?php } ?>
	<tr>
		
		<?php 
			if ($row['comm_doorno'] != '' && $row['comm_street'] != '') {
				$address = $row['comm_rev_village_name'].$label_name[22].', '.$row['comm_taluk_name'].$label_name[21].', '.$row['comm_district_name'].$label_name[20].', '.$row['comm_state_name'].', '.$row['comm_country_name'];
				$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
			} else {
				$address = $row['comm_rev_village_name'].$label_name[22].', '.$row['comm_taluk_name'].$label_name[21].', '.$row['comm_district_name'].$label_name[20].', '.$row['comm_state_name'].', '.$row['comm_country_name'];
			}
			
		?>
    	<td><?PHP echo $label_name[19];//Address?></td>
		<td><?php echo $address;?></td>
	</tr>
	
	<tr <?php echo ($row['passport_number'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[41];//Passport Number?></td>
        <td><?php echo $row['passport_number'];?></td>        
	</tr> 
        
    <tr <?php echo ($row['comm_mobile'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[7];//Mobile Number?></td>
        <td><?php echo $row['comm_mobile'];?></td>        
	</tr>    
	<tr <?php echo ($row['comm_email'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[40];//Mobile Number?></td>
        <td><?php echo $row['comm_email'];?></td>        
	</tr>
    <tr>
    	<td class="sub_heading" style="text-align:left !important;">Petition Document</td>
        <td style="color:blue; text-decoration:underline">
        <?php
			$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row['petition_id']."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	
			
	?>
    <?php
		foreach($doc_row as $key){
		?>
			<span id="span_dwnd" onClick="download_document(<?php echo $key['doc_id']; ?>,'P')"><?php echo $key['doc_name']; ?></span>

<!--    	<img src="images/download.png" onclick="download_document(<?php //echo $key['doc_id']; ?>)"/>-->
        
    <?php } ?><script>
	function download_document(url,src){
		//window.location.href="http://locahost/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
		window.location.href="http://localhost/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
	}
				</script>
        </td>
    </tr>  
    
	<tr>
    	<td class="sub_heading" style="text-align:left !important;">Action Document</td>
        <td style="color:blue; text-decoration:underline">
        <?php
			$query_doc = "select action_doc_id,action_doc_name from pet_action_doc where petition_id in('".$row['petition_id']."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	
			
	?>
    <?php
		foreach($doc_row as $key){
		?>
			<span id="span_dwnd" onClick="download_document(<?php echo $key['action_doc_id']; ?>,'A')"><?php echo $key['action_doc_name']; ?></span>

        
    <?php } ?><script>
	function download_document(url,src){
		//window.location.href="http://locahost/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
		window.location.href="http://14.139.183.34/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;

	}
				</script>
        </td>
    </tr>  
	
     <!-- Petitioner Details Building Block : Ends Here-->   	    
    <?PHP
	}
	?>
</tbody>
</table>

<table class="gridTbl">
	<thead>
    	<tr>
            <th colspan="7" class="emptyTR">
                <?PHP echo $label_name[16];//Processing Details?>
            </th>
        </tr>
		<tr>
			<th><?PHP echo $label_name[8];//Action Taken Date & Time?></th>
			<th><?PHP echo $label_name[9];//Action Type?></th>
			<th><?PHP echo $label_name[10];//File No. & File Date?></th>
			<th><?PHP echo $label_name[11];//Action Remarks?></th>
            <th><?PHP echo $label_name[12];//Action Taken By?></th>
            <th><?PHP echo $label_name[13];//Addressed To?></th>
        </tr>
	</thead>
	
	<tbody>
		
<?php
 	$query=" SELECT action_type_name, file_no, to_char(file_date, 'DD/MM/YYYY') as file_date, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt_fmt, 
	action_entby, dept_desig_name, off_level_dept_name, off_loc_name AS location,	
	to_whom, dept_desig_name1,off_level_dept_name1, off_loc_name1 AS location1
	FROM vw_pet_actions
	WHERE petition_id=$petition_id
   	ORDER BY action_entdt desc";

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo $rowArr = $result->fetch(PDO::FETCH_NUM);
	foreach($rowarray as $row)
	{

		?>
        <tr>
            <td><?php echo $row['action_entdt_fmt'];?></td>
            <td><?php echo $row['action_type_name'];?></td>
			<td><?php echo !empty($row['file_no'])? $row['file_no']."<br>".$row['file_date'] : "";?></td>
            <td><?php echo $row['action_remarks'];?></td>
			<td><?php echo $row['dept_desig_name'].', ' .$row['off_level_dept_name'].', ' .$row['location'];?></td>
			<td><?php echo !empty($row['dept_desig_name1'])?$row['dept_desig_name1']. ', ' .$row['off_level_dept_name1'].', ' .$row['location1'] : "";?></td>			
        </tr>
		<?php
	}
	if(sizeof($rowarray)==0)
	{
		?>
		<tr>
			<td colspan="7">No action taken so far</td>
		</tr>
	<?PHP
	}
	
}else {
	?>
    <tr>
			<td colspan="7" style="font-size:18px; text-align:center;"><?PHP echo "No Records Found "; //No Records Found ?></td>
	</tr>
 <?php } ?>

    	<tr>
			<td colspan="7" class="emptyTR"></td>
		</tr>
		
		
		<tr>
			<td colspan="7" class="btn">
			<input type="button" name="close" id="close" value="<?PHP echo 'Back'; //View. ?>"  onClick="closePopup();"/> 
			<input type="button" name="process" id="process" value="<?PHP echo 'Process'; //View. ?>"  onClick="peocessPetition();"/> 
			<input type="hidden" name="petition_id" id="petition_id" value="<?PHP echo $petition_id;?>"/> 
			<input type="hidden" name="dept_id" id="dept_id" value="<?PHP echo $dept_id;?>" /> 
			<input type="hidden" name="griev_district_id" id="griev_district_id" value="<?PHP echo $griev_district_id;?>" /> 
			<input type="hidden" name="action" id="action" value="T5"/> 
			</td>
		</tr>
</tbody>
</table>
		
		<table class="gridTbl" id="process_petition" style="display:none;">		
	<thead>
    	<tr>
            <th colspan="5" class="emptyTR">
                <?PHP echo 'Processing the Petition';//Processing Details?>
            </th>
        </tr>
		<tr>
			<th width="15%"><?PHP echo 'Current Action';//Action Taken Date & Time?></th>
			<th width="15%"><?PHP echo 'Addressed To';//Action Type?></th>
			<th width="25%"><?PHP echo 'File No. & File Date';//File No. & File Date?></th>
			<th width="45%"><?PHP echo 'Remarks';//Action Taken By?></th>
        </tr>
	</thead>
	<tbody>	
	<tr>
		<td>
		<select name="action_type" id="action_type" class="select_style" onchange="populateOfficers();">
		<option value="">--Select--</option>
		</select>
		</td>
		<td>
		<select name="concerned_officer" id="concerned_officer" class="select_style" onchange="concernedOffId();">
		<option value="">--Select--</option>
		</select>
        <input type="text" name="off_name" id="off_name" disabled="disabled" style="display:none;" data_valid='no'/>
		<input type="hidden" name="off_d_id" id="off_d_id" data_valid='no' onKeyPress="return numbersonly(event);" onblur="selectConcernedOfficer();" maxlength="5" style="width: 75px;"/> </span>
		<a id="all_link" href="javascript:get_all_officer_list();" style="display:none;"><?PHP echo 'Get All Officers'; //Officer Code ?> </a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		</td>
		<td><input type="text" name="file_no" id="file_no" maxlength="50" style='width:170px' value="<?php echo $file_no; ?>"><br><br>
		<input type="text" name="file_date" id="file_date" maxlength="25" style='width:120px' value="<?php echo $file_date; ?>"></td>
		<td><textarea id="remarks" name="remarks"><?php echo $remarks; ?></textarea></td>
		</tr>
		<tr><td colspan="5" class="btn">
		<input type="button" id="petition_process" style="width:140px" value="Save Process" onclick="saveProcess()"></td>
		</tr>
	</tbody>
</table>

</div>
</div>
 
<?php
	include("footer.php"); 
?>