<?PHP 
session_start();
//include("db.php");
$pagetitle="Petition Processing";
include('header_menu.php');
include("db.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Petition Processing</title>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{
	loadEnq();
	$("#Save").click(function(){
		forwardProcess();
	});
	
	$("#act_type_code").change(function(){
		if($("#act_type_code").val()=='F'){
			document.getElementById('p4_fir_csr_dist').style.display = "none";
			 
		}else if($("#act_type_code").val()=='I' || $("#act_type_code").val()=='S'){
			document.getElementById('p4_fir_csr_dist').style.display = "block";
			$("#p4_fir_dist").val('');
			$("#fir_circle").val('');
			$("#p4_fir_year").val('');
			$("#p4_fir_no").val('');
			load_ext_dist();
			load_fir_det($("#act_type_code").val());
			if($("#p4_fir_dist").val()==''){
			document.getElementById("fir_circle").innerHTML="<option selected>--Select Police Station--</option>";
			$("#fir_circle").val();
			}
			
		}else if($("#act_type_code").val()=='R'){
			document.getElementById('p4_fir_csr_dist').style.display = "none";
			if($("#remark").val()==''){
				alert('Enter Remarks for Rejection');
			}
		}else{ 
			document.getElementById('p4_fir_csr_dist').style.display = "none";
			$("#address_to").val("");
			$('#address_to').hide(); 
			$('#all_off').hide(); 			
		}
	});
});

function load_fir_det(fir_csr){
	pet=$('#petition_id1').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'fir_det',pet:pet,fir_csr:fir_csr},
		error:function(){ alert("") },
		success: function(html){
			//document.getElementById("p4_fir_dist").innerHTML=html;
			x=html.split(',');
			$("#p4_fir_dist").val(x[1]);//alert($("#p4_fir_dist").val()!='');
			if($("#p4_fir_dist").val()!=''){
			load_ext_ps();
			setTimeout(function() {
			$("#p4_fir_year").val(x[3]);
			$("#p4_fir_no").val(x[4]);
			$("#fir_circle").val(x[2]);
			},300);
			}
			
		}
	});	
}

function load_ext_dist() {
	ef_off=$('#supervisory_officer').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_ext_dist',ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("p4_fir_dist").innerHTML=html;			
		}
	});	
	
}

function load_ext_ps() {
	district=$('#p4_fir_dist').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_police_station',district:district},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("fir_circle").innerHTML=html;			
		}
	});	
	
}

function forwardProcess(){
	if($("#act_type_code").val()==""){
		alert("Please select current action type");
		return false;	
	}
	else if($("#act_type_code").val()=="F" && $("#address_to").val()=="" && $('#user_sno').val()==""){
		alert("Please select Addressed To");
		return false;	
	}else{
	if($("#act_type_code").val()=="I" || $("#act_type_code").val()=="S"){
		if($('#p4_fir_dist').val()==''){
			alert("Select District");
			return false;	
		}if($('#fir_circle').val()==''){
			alert("Select Police Station");
			return false;	
		}if($('#p4_fir_year').val()==''){
			alert("Enter FIR/CSR Year");
			return false;	
		}if($('#p4_fir_no').val()==''){
			alert("Enter FIR/CSR No.");
			return false;	
		}
	}else if($("#act_type_code").val()=="R"){
		if($('#remark').val()==''){
			alert("Please Enter Remarks for Rejection");
			return false;
		}
	}
		var param="mode=Fwd";
		param += "&pet_action_id="+$("#pet_action_id").val();
		param += "&act_type_code="+$("#act_type_code").val();
		param += "&address_to="+$("#address_to").val();
		param += "&p1_design="+ $('#user_sno').val();
		param += "&fir_dist="+$("#p4_fir_dist").val();
		param += "&fir_circle="+$("#fir_circle").val();
		param += "&fir_year="+$("#p4_fir_year").val();
		param += "&fir_no="+$("#p4_fir_no").val();
		param += "&petition_id="+$("#petition_id1").val();
		param += "&remark="+$("#remark").val();
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "pm_pet_processing_action.php",
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 			
				var msg = $(xml).find('msg').eq(0).text();
				var status = $(xml).find('status').eq(0).text();
				alert(msg);
				if(status=='S'){
					$("#dataGrid").empty();
				}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
	}
	
}

function searchOfficeDesign(petition_id, griev_type_id){
	openForm("OfficeDesignSearchForm.php?open_form=P1&petition_id="+petition_id+"&griev_type_id="+griev_type_id, "office_design_search");
}

function returnDesignationSearch(petition_id, userID, offLoc_designName){
	$('#user_sno').val(userID);
	$('#design').val(offLoc_designName);
}

function openPetitionStatusReport(petition_id){
	document.getElementById("petition_id").value=petition_id;
	document.petition_process.target = "Map";
	document.petition_process.method="post";  
	document.petition_process.action = "p_PetitionProcessDetails.php";
	
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process.submit();
	}  
}
function p2_searchOfficeDesignation(pet_id,pet_action_id, off_loc_id) {
	var griev_type_id = $('#griev_type_id').val();
	var griev_sub_type_id = $('#p2_griev_subtype_id').val();
	var dept_id = $('#deptid').val();
	openForm("p1_OfficeDesignSearchForm.php?open_form=PE&petition_id="+pet_id+"&griev_type_id="+griev_type_id+"&griev_sub_type_id="+griev_sub_type_id+"&off_loc_id="+off_loc_id+"&dept_id="+dept_id+"&pet_action_id="+pet_action_id, "office_design_search");
	}
function pe_returnDesignationSearch(petition_id,p_act_id, userID, offLoc_designName){
	$('#user_sno').val(userID);
	$('#p1_design').val(offLoc_designName);
	document.getElementById('p1_design').style.display = "block";
	document.getElementById('address_to').style.display = "none";
	document.getElementById('p1_design').disabled = true;
}	
	
function loadEnq() {
	
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'enquiry_default'},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("address_to").innerHTML=html;			
		}
	});	
	
}
</script>
</head>

<body>
<?php 
 
include("menu_home.php");

?>
<form action="pm_pet_processing_action.php" name="petition_process" method="post">
<input type="hidden" id="pet_action_id" name="pet_action_id" value="<?PHP echo $_POST['pet_act_id'];?>"/>

<div class="form_heading">
	<div class="heading">Petition Processing</div>
</div>
	      
<div class="contentMainDiv">
	<div class="contentDiv">
<table class="existRecTbl" style="display: none;">
	<thead>
	<tr>
		<th>Existing Details</th>
		<th>Page&nbsp;Size<select class="pageSize" id="pageSize" name="pageSize">
				<option selected="selected" value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
			</select>
		</th>
	</tr>
	</thead>
</table>
<?PHP 
	$sql = "SELECT  a.pet_action_id,a.action_remarks fwd_remarks,a.action_entby,a.to_whom,a.action_type_code,a.action_type_name,
	TO_CHAR(a.action_entdt,'dd/mm/yyyy') as fwd_date,
	a.petition_id, a.petition_no, TO_CHAR(a.petition_date,'dd/mm/yyyy')as petition_date,
	a.source_name, a.griev_type_id, a.griev_type_name, a.griev_subtype_id, a.griev_subtype_name, a.grievance,
	CONCAT('No: ',comm_doorno ,', ', comm_street,', <br>', comm_area,',<br>', ' Pincode - ',comm_pincode,'.') as pet_address,
	CASE WHEN a.griev_taluk_id IS NOT NULL THEN a.griev_rev_village_id||', '||a.griev_taluk_name
	WHEN a.griev_block_id IS NOT NULL THEN a.griev_lb_village_name||', '||a.griev_block_name
	WHEN a.griev_lb_urban_id IS NOT NULL THEN a.griev_lb_urban_name
	END || ', '||a.griev_district_name AS gri_address,a.griev_district_id,
	row_number() over (order by a.petition_date, a.petition_id) as rownum
	FROM vw_pet_action a
	WHERE a.pet_action_id=".$_POST['pet_act_id'];
	
	/* $sql = "SELECT petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, grievance, CONCAT('No: ',comm_doorno ,', ', comm_street,', <br>', comm_area,',<br>', ' Pincode - ',comm_pincode,'.') as pet_address, comm_mobile, dept_name, pet_type_name,off_level_dept_name,
	row_number() over (order by a.petition_date, a.petition_id) as rownum
	FROM vw_pet_action a
	WHERE a.pet_action_id=".$_POST['pet_act_id']; */
	
		$result=$db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_BOTH);
		$petition_id=0;		
		
		
?>

<table class="gridTbl" style="border-top-style:solid;">
	<thead>
		<tr>
        	<th>Petition No. &amp; Date</th>
			<th>Petitioner's Address</th>
            <th>Source</th>
            <th>Grievance</th>
            <th>Grievance Type &amp; Sub Type</th>
            <th>Current Action</th>
            <th style="width: 260px;">Addressed To</th>
            <th style="width: 300px;">Current Remarks</th>
		</tr>
	</thead>
	<tbody id="dataGrid">
    	<?PHP
		foreach($rowarray as $row){
		$petition_id = $row['petition_id'];
		$pet_action_id = $row['pet_action_id'];
		$griev_district_id = $row['griev_district_id'];
		?>
		
		<input type="hidden" name="petition_id1" id="petition_id1" value="<?PHP echo $petition_id;?>">
		<input type="hidden" name="griev_type_id" id="griev_type_id" value="<?PHP echo $row['griev_type_id'];?>">
		<input type="hidden" name="griev_subtype_id" id="griev_subtype_id" value="<?PHP echo $row['griev_subtype_id'];?>">
		<input type="hidden" name="deptid" id="deptid" value="<?php echo $userProfile->getDept_id();?>">
		<input type='hidden' name='user_sno' id='user_sno'/>
    	<tr>
        	<td>
                <a href="javascript:openPetitionStatusReport('<?PHP echo $row['petition_id'];?>');" title='Petition Process Report'>
                   <?PHP echo $row['petition_no'];?> &amp; Dt. <?PHP echo $row['petition_date'];?>
                </a>
            </td>
            <td><?PHP echo $row['pet_address'];?></td>
            <td><?PHP echo $row['source_name'];?></td>
            <td><?PHP echo $row['grievance'];?></td>
            <td><?PHP echo $row['griev_type_name']."& ".$row['griev_subtype_name'];?></td>
            <td>
            	<?PHP 
				$actTypeCode="";
				if($userProfile->getPet_forward()){
					$actTypeCode ="'F'";
				}
				if($_SESSION[LOGIN_LVL]==NON_BOTTOM && $userProfile->getPet_disposal()){
					$actTypeCode .= $actTypeCode==""? "'A', 'R'" : ", 'A', 'R'";
				}
				if($_SESSION[LOGIN_LVL]==BOTTOM && $userProfile->getPet_disposal()){
					$actTypeCode ="'A', 'R'";
					if($userProfile->getOff_level_id()==46){
						$actTypeCode ="'R', 'I', 'S'";
						}
				}
				if($actTypeCode!=""){
					$query = "SELECT action_type_code, action_type_name FROM lkp_action_type WHERE action_type_code IN(".$actTypeCode.")";
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
				}
				?>
            	<select name="act_type_code" id="act_type_code">
                	<option value="">-- Action Type Code --</option>
                    <?PHP 
					foreach($rowarray as $row1)
					{?>
                    	<option value="<?PHP echo $row1['action_type_code']; ?>"><?PHP echo $row1['action_type_name']; ?></option>
                   	<?PHP }?>
                </select>
            </td>
            <td>
            	<?PHP
			/* 	
				if ($userProfile->getOff_level_id( )== 1) {
					$query = "select dept_user_id, off_loc_name ||' / '||dept_desig_name as off_location_design, off_level_id
					from vw_usr_dept_users_v_sup
					where off_hier[2]=".$griev_district_id." 
					and dept_id=".$userProfile->getDept_id()." and off_level_dept_id=2 
					and off_loc_id=".$griev_district_id." and pet_act_ret and dept_user_id!=".$_SESSION['USER_ID_PK']."					
					union
					select dept_user_id, off_loc_name ||' / '||dept_desig_name as off_location_design, off_level_id
					from vw_usr_dept_users_v_sup
					where 
						case
						when ".($userProfile->getDept_coordinating()?'TRUE':'FALSE')." and ".($userProfile->getOff_coordinating()?'TRUE':'FALSE')." then
						(
							off_hier[2]=".$griev_district_id." 
							and dept_desig_id=s_dept_desig_id 
							and
							(
								(
									dept_id != ".$userProfile->getDept_id()." 
									and off_level_id=2
									and off_loc_id=".$griev_district_id."
								) 
								or 
								(
									sup_off_loc_id1=".$griev_district_id." or sup_off_loc_id2=".$griev_district_id."
								)
							)
						) and dept_pet_process and off_pet_process and pet_act_ret
						
						when ".($userProfile->getDept_coordinating()?'TRUE':'FALSE')." and ".($userProfile->getOff_coordinating()?'TRUE':'FALSE')." = false then
						( 
							off_hier[2]=".$griev_district_id."
							and dept_id=".$userProfile->getDept_id()." and dept_desig_id=s_dept_desig_id 
							and 
							(
								sup_off_loc_id1=".$griev_district_id." or sup_off_loc_id2=".$griev_district_id."
							) 
						) and dept_pet_process and off_pet_process and pet_act_ret
						end
						ORDER BY off_level_id";	
						
				}else {
					$query = "select dept_user_id, off_loc_name ||' / '||dept_desig_name as off_location_design, off_level_id
					from vw_usr_dept_users_v_sup
					where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
					and dept_id=".$userProfile->getDept_id()." and off_level_dept_id=".$userProfile->getOff_level_dept_id()." 
					and off_loc_id=".$userProfile->getOff_loc_id()." and pet_act_ret and dept_user_id!=".$_SESSION['USER_ID_PK']."					
					union
					select dept_user_id, off_loc_name ||' / '||dept_desig_name as off_location_design, off_level_id
					from vw_usr_dept_users_v_sup
					where 
						case
						when ".($userProfile->getDept_coordinating()?'TRUE':'FALSE')." and ".($userProfile->getOff_coordinating()?'TRUE':'FALSE')." then
						(
							off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
							and dept_desig_id=s_dept_desig_id 
							and
							(
								(
									dept_id != ".$userProfile->getDept_id()." 
									and off_level_id=".$userProfile->getOff_level_id()." 
									and off_loc_id=".$userProfile->getOff_loc_id()."
								) 
								or 
								(
									sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()."
								)
							)
						) and dept_pet_process and off_pet_process and pet_act_ret
						
						when ".($userProfile->getDept_coordinating()?'TRUE':'FALSE')." and ".($userProfile->getOff_coordinating()?'TRUE':'FALSE')." = false then
						( 
							off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."
							and dept_id=".$userProfile->getDept_id()." and dept_desig_id=s_dept_desig_id 
							and 
							(
								sup_off_loc_id1=".$userProfile->getOff_loc_id()." or sup_off_loc_id2=".$userProfile->getOff_loc_id()."
							) 
						) and dept_pet_process and off_pet_process and pet_act_ret
						end
						ORDER BY off_level_id";	
						//echo "11111".$query;exit;	
				}				  
										
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC); */
					?>
                    <select name="address_to" id="address_to" style='display:none;'>
                		<option value="">-- Address To --</option>
                    <?PHP
					/* foreach($rowarray as $row){
						?><option value="<?PHP echo $row[dept_user_id];?>"><?PHP echo $row[off_location_design]; ?></option>
					<?PHP 
					} */
					?>
            		</select>
					
		
					<input type='text' name='p1_design' id='p1_design' style='display:none'/>
	<span id="p4_fir_csr_dist" style="display:none"><b>FIR/CSR Details:</b><select name="p4_fir_csr_dist" id="p4_fir_dist" onchange="load_ext_ps();">
	<option value="" selected="">--Select District--</option>
	</select><br><br><select name="p4_fir_circle" id="fir_circle"><option>--Select Police Station--</option></select><br><br><input name="p4_fir_year" id="p4_fir_year" style="width:20%" placeholder="Year" maxlength="4">&emsp;<input name="p4_fir_no" id="p4_fir_no" style="width:60%" placeholder="FIR/CSR Number" maxlength="150">
	</span>
	<!--a id="all_off" href="javascript:p2_searchOfficeDesignation(<?php echo $petition_id?>,<?php echo $pet_action_id?>,<?php echo $userProfile->getOff_loc_id()?>);">
			Get All Officer List</a-->
            </td>  
            <td>
            	<textarea id="remark" name="remark"></textarea>
            </td>
        </tr>
        <?PHP
		}?>
    </tbody>
</table>
<table class="paginationTbl">
	<tbody>
		<tr style="display: none;" id="pageFooter1">
			<td id="previous"></td>
			<td>Page<select class="pageNoList" name="pageNoList" id="pageNoList"></select><span id="noOfPageSpan"></span></td>
			<td id="next"></td>
		</tr>
		<tr style="display: none;" id="pageFooter2"><td class="emptyTR" colspan="3"></td>
		</tr>
        <tr>
        	<td class="emptyTR" colspan="3">
				<input type="hidden" name="petition_id" id="petition_id" />
            	<input type="button" name="Save" id="Save" value="Save" class="button">
				
		
            </td>
		</tr>
	</tbody>
</table>
</div>
</div>
</form>

</body>
</html>