<?php
ob_start();
session_start();
$pagetitle="Registers";
include("header_menu.php");
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>

<!-- Date Picker css-->
<link rel="stylesheet" href="css/jquery.datepick.css" media="screen" type="text/css">
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<label style="display:none">
<img src="images/calendar.gif" id="calImg">
</label>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{
	setDatePicker('p_from_pet_date');
	setDatePicker('p_to_pet_date');
	
	var fdate = $("#fromdate").val();
	var tdate = $("#todate").val();
	
	if (fdate == '')
		addDate();	
	else 
	{
		$("#p_from_pet_date").val(fdate);
		$("#p_to_pet_date").val(tdate);
	}
	
	if ($("#htaluk").val() != '' && $("#htaluk").val() != 'undefined') {
			loadPetRevVillage();
	}
	
	if ($("#hgstype").val() != '' && $("#hgstype").val() != 'undefined') {
			loadSubTypes();
	}
	load_department();
	$("#p_search").click(function(){
		if (chk_form()) {
			searchData();
		}
	});
	
	$("#p_clear").click(function(){
		p_clearSerachParams();
	});
	
	if ($("#hoff_level_id").val() == 1) {
		document.getElementById("taluk").options.length = 1;
		document.getElementById("p_taluk").options.length = 1;	
	}
});

function p_clearSerachParams() {
	document.getElementsByTagName('select').value = '';
	document.getElementById("p_taluk").value = "";
	document.getElementById('p_rev_village').options.length = 1;
	document.getElementById("rev_tr").style.display='none';
	document.getElementById("rural_tr").style.display='none';
	document.getElementById("urban_tr").style.display='none';
	document.getElementById("office_tr").style.display='none';
	document.getElementById("dept").value = "";
	document.getElementById("p_source").value = "";
	document.getElementById("gtype").value = "";
	document.getElementById("gstype").value = "";
	document.getElementById("petition_type").value = "";
	document.getElementById("pet_community").value = "";
	document.getElementById("special_category").value = "";
	addDate();
}
function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_from_pet_date");
	setDateFormat(date, "#p_to_pet_date");
}

function chk_form() {
    var fromdt=document.getElementById("p_from_pet_date").value;
	var todt=document.getElementById("p_to_pet_date").value;
    
	if(fromdt=="")
	{
		alert("Select Any From Date");
		document.getElementById("p_from_pet_date").focus();
		return false; 
	}
	if(todt=="")
	{
		alert("Select Any To Date");
		document.getElementById("p_to_pet_date").focus();
		return false; 
	}
	
	frDateArray =  fromdt.split("/");
	toDateArray =  todt.split("/");
	fromDate = new Date(frDateArray[2],frDateArray[1],frDateArray[0]);
	toDate = new Date(toDateArray[2],toDateArray[1],toDateArray[0]);
	
	if (fromDate > toDate) {
		alert("From date can not be greater than To date");
		return false;
	} else {
	    
	    return true;
		 
	 }
	 
}

function searchData() {
	var fromdt=document.getElementById("p_from_pet_date").value;
	var todt=document.getElementById("p_to_pet_date").value;
	var pat = document.getElementById("pat_id").value;
	var dept_id = $('#dept').val();
	
	var locname = "";
	if (dept_id != "") {
		var dname = $("select[id=dept] option:selected").text();
		locname = "Department: "+dname;
	}
	if (pat == 1) {
		var taluk = $('#taluk').val();
		var rv = $('#rev_village').val();
		if (taluk != '') {
			var tname = $("select[id=taluk] option:selected").text();
			locname = locname + " - Taluk: "+tname;
		}
		if (rv != '') {
			var vname = $("select[id=rev_village] option:selected").text();
			locname = locname +  " - " + "Revenue Village: "+vname;
		}
		
	} else if (pat == 2) {
		var block = $('#block').val();
		var pv = $('#p_village').val();
		if (block != '') {
			var tname = $("select[id=block] option:selected").text();
			locname = locname + " - Block: "+tname;
		}
		if (pv != '') {
			var vname = $("select[id=p_village] option:selected").text();
			locname = locname +  " - " + "Village Panchayat: "+vname;
		}
	} else if (pat == 3) {
		var urban = $('#urban_body').val();
		
		if (urban != '') {
			var tname = $("select[id=block] option:selected").text();
			locname = locname + " - Urban Body: "+tname;
		}
	} else if (pat == 4) {
		var office = $('#office').val();
		
		if (office != '') {
			var tname = $("select[id=office] option:selected").text();
			locname = locname + " - Office: "+tname;
		}
	} 
	document.getElementById("off_detail").value = locname;
	document.p_registers.action = "p_register_details.php";
	document.p_registers.target= "_blank";
	document.p_registers.submit();
}
function validatedate(inputText,elementid){
   
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
   
if(inputText.value.match(dateformat))  
{  
	  document.profile.inputText.focus();  
	  
	  var opera1 = inputText.value.split('/');  
	  var opera2 = inputText.value.split('-');  
	  lopera1 = opera1.length;  
	  lopera2 = opera2.length;  
	    
	  if (lopera1>1)  
	  {  
	  var pdate = inputText.value.split('/');  
	  }  
	  else if (lopera2>1)  
	  {  
	  var pdate = inputText.value.split('-');  
	  }  
	  var mm  = parseInt(pdate[0]);  
	  var dd = parseInt(pdate[1]);  
	  var yy = parseInt(pdate[2]);  
	    
	  var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];  
	  if (mm==1 || mm>2)  
	  {  
	  if (dd>ListofDays[mm-1])  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
	  if (mm==2)  
	  {  
	  var lyear = false;  
	  if ( (!(yy % 4) && yy % 100) || !(yy % 400))   
	  {  
	  lyear = true;  
	  }  
	  if ((lyear==false) && (dd>=29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  if ((lyear==true) && (dd>29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
}  
  else  
  {  
  	alert("Invalid date format!");  
    document.getElementById(elementid).value=""; 
    document.getElementById(elementid).focus(); 
  return false;  
  }  
}
function loadSubTypes() {
var gval = $("#gtype").val();
optTag = "<option value=''>-- <?php if($_SESSION['lang']=='E'){ echo 'Select Petition Sub Category'; }else{echo 'தேர்ந்தெடு';} ?> --</option>"
var gsval = $("#hgstype").val();
		
		if(gval!=""){
	 		$.ajax({
	  			type: "post",
	  			url: "pm_petition_detail_entry_action.php",
	  			cache: false,
	  			data: {source_frm : 'griev_subcategory',griev_main_code : gval,gsval:gsval},
	  			error:function(){ alert("some error occurred") },
	  			success: function(html){
	 				document.getElementById("gstype").innerHTML=html;
						 //get_officer_list();
				 }
	  
			});
		} else {
			$("#gstype").empty().append(optTag);
		}
}

function load_department()
{ 
	var params="mode=load_dept";
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "rptoffice_reports_action.php",
		data: params,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
var optionTag= "<option value=''>-- <?php if($_SESSION['lang']=='E'){ echo 'Select Department'; }else{echo 'தேர்ந்தெடு';} ?>--</option>";
			$(xml).find('dept_id').each(function(i) // for loop
			{
				var dept_val = $(xml).find('dept_id').eq(i).text();
				var val = $(xml).find('dept_id').eq(i).text()+"-"+$(xml).find('off_level_pattern_id').eq(i).text();
				var desc = $(xml).find('dept_name').eq(i).text();
				var off_level_dept_id = $(xml).find('off_level_dept_id').eq(i).text();
				$('#offlevel_distdept_idhid').val(off_level_dept_id);
					optionTag += "<option value='"+val+"'>"+desc+"</option>";
			});
			$("#dept").empty();
			$("#dept").append(optionTag); 
		 	
			var optionTag1;
			
			if($('#off_level_id').val() >2 ) {
			
			$(xml).find('dist_id').each(function(i) // for loop
			{
				var val = $(xml).find('dist_id').eq(i).text();
				var desc = $(xml).find('dist_name').eq(i).text();
				
				  optionTag1 += "<option value='"+val+"'>"+desc+"</option>";
			});
			$("#dist").empty();
			$("#dist").append(optionTag1);
			
		} 
		
	},  
	error: function(e){  
			//alert('Error: ' + e);  
	} 
 });//ajax end

} 

function resetAllLocations() {
	document.getElementById("taluk").value='';
	document.getElementById("rev_village").value='';
	document.getElementById("block").value='';
	document.getElementById("p_village").value='';
	document.getElementById("urban_body").value='';
	document.getElementById("office").value='';
}

function loadOfficeLocation() {
//var vSkillText = vSkill.options[vSkill.selectedIndex].innerHTML;
	var dept = $('#dept').val();
	if (dept == '') {
		resetAllLocations();
		document.getElementById("rev_tr").style.display='none';
		document.getElementById("rural_tr").style.display='none';
		document.getElementById("urban_tr").style.display='none';
		document.getElementById("office_tr").style.display='none';
	} else {
		depts = dept.split('-');
		pattern = depts[1];
		document.getElementById("pat_id").value=pattern;
		document.getElementById("dept_id").value= depts[0];
		if (pattern == '1') {
			document.getElementById("rev_tr").style.display='';
			document.getElementById("rural_tr").style.display='none';
			document.getElementById("urban_tr").style.display='none';
			document.getElementById("office_tr").style.display='none';
		} else if (pattern == '2') {
			document.getElementById("rev_tr").style.display='none';
			document.getElementById("rural_tr").style.display='';
			document.getElementById("urban_tr").style.display='none';
			document.getElementById("office_tr").style.display='none';
		} else if (pattern == '3') {
			document.getElementById("rev_tr").style.display='none';
			document.getElementById("rural_tr").style.display='none';
			document.getElementById("urban_tr").style.display='';
			document.getElementById("office_tr").style.display='none';
		} else if (pattern == '4') {
			loadOffice(depts[0]);
			document.getElementById("rev_tr").style.display='none';
			document.getElementById("rural_tr").style.display='none';
			document.getElementById("urban_tr").style.display='none';
			document.getElementById("office_tr").style.display='';
		}
	}
	
	
}
function loadRevVillage() {
	var taluk=$('#taluk').val();
	
	if ($('#hoff_level_id').val() == 1) {
		var dist=$('#district').val();	
	} else {
		var dist=$('#dist_id').val();	
	}
	
	if(taluk==''){
		$("#taluk").val('');
		document.getElementById("rev_village").options.length = 1;
		return false;
	} 
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'village',talukid : taluk,distid : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("rev_village").innerHTML=html;
		}
	});
}
function loadPetRevVillage() {
	var taluk=$('#p_taluk').val();	
	var rv=$('#hrevvill').val();
	if ($('#hoff_level_id').val() == 1) {
		var dist=$('#district').val();	
	} else {
		var dist=$('#dist_id').val();	
	}
	if(taluk==''){
		$("#p_taluk").val('');
		document.getElementById("p_rev_village").options.length = 1;
		return false;
	} 
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'village',talukid : taluk,distid : dist,rv : rv},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("p_rev_village").innerHTML=html;
		}
	});
}
function get_village_panchayat()
{
	block=$('#block').val();
	if (block == '') {
		document.getElementById("p_village").options.length = 1;
		return false;
	}
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'village_panchayat',blockid : block},
		error:function(){ alert("some error occurred") },
		success: function(html){ 
			document.getElementById("p_village").innerHTML=html;	  	
		}
	});
}
function loadTalukDetails() {
	var dist=$('#district').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_p_taluk', dist : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("p_taluk").innerHTML=html;
		}
	});
	loadPetOfficeLaukDetails();	
}
function loadPetOfficeLaukDetails() {
	var dist=$('#district').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_taluk', dist : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("taluk").innerHTML=html;
		}
	});
	//loadPetOfficeLaukDetails();	
}

function loadOffice(dept_id) {
	var dist=$('#dist_id').val();
	dept = dept_id;
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'populate_office',dept : dept, dist : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("office").innerHTML=html;
		}
		});	
}
</script>
<?php

$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='p_PetitionProcessedByUsForm.php') order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}

$fromdate = $_POST["fromdate"];
$todate = $_POST["todate"];

$gtype = $_POST["h_gtype"];
$gstype = $_POST["h_gstype"];
$source = $_POST["h_source"];

$tk = $_POST["h_taluk"];
$rv = $_POST["h_rvill"];

?>
<form method="post" name="p_registers" id="p_registers" style="background-color:#F4CBCB;">
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[36]//Petition/Application Registers?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="formTbl" style="border-top: 1px solid #000000;">
      <tbody>
      <tr>
      <td class="from_to_dt" colspan="4" style="text-align:center;"><span><label><b><?PHP echo $label_name[2];//Petition Period?> : </b></label></span>&nbsp;&nbsp;
	  <?PHP echo $label_name[3];//From?>&nbsp;&nbsp;
	  <input type="text" name="p_from_pet_date" id="p_from_pet_date" maxlength="12" style="width: 90px;"  
          onchange="return validatedate(p_from_pet_date,'p_from_pet_date'); "/> &nbsp;&nbsp;&nbsp;&nbsp;
         
		  
	<?PHP echo $label_name[4];;//To Date?>&nbsp;&nbsp;<input type="text" name="p_to_pet_date" id="p_to_pet_date" maxlength="12" 
          style="width: 90px;" onchange="return validatedate(p_to_pet_date,'p_to_pet_date'); "/>
      </td>
	 </tr>
	 
	 <tr>
	 <td><?PHP echo $label_name[8];//Source?></td>
      <td>
          <select name="p_source" id="p_source">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Source"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
          //$query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";
			$query = "-- petition form: sources combo
					SELECT DISTINCT(a.source_id), b.source_name, b.source_tname
					FROM usr_dept_desig_sources a
					JOIN lkp_pet_source b ON b.source_id = a.source_id
					WHERE a.dept_desig_id = ".$userProfile->getDept_desig_id()." order by b.source_name" ;
		  $query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";			
          $result = $db->query($query);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
							if ($source == $row['source_id'])
								echo "<option value='".$row['source_id']."' selected>".$row['source_name']."</option>";
							else
								echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
						}else{
							if ($source == $row['source_id'])
								echo "<option value='".$row['source_id']."' selected>".$row['source_tname']."</option>";
							else
								echo "<option value='".$row['source_id']."'>".$row['source_tname']."</option>";
						}
          }
          ?>
          </select>
      </td>
     <td><?PHP echo $label_name[29];//Department?></td>
      <td>
          <select name="dept" id="dept" onChange="loadOfficeLocation();">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Department"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          
          </select>
      </td>	  
	  </tr>
	  <?php if ($userProfile->getOff_level_id()==1) { ?>
	 	<tr id="district_row">
		<td><?php echo "District";?></td>
		<td colspan="3">
		<select name="district" id="district" onchange="loadTalukDetails()">
        <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select District"; }else{echo "தேர்ந்தெடு";} ?> --</option>
		<?php 
			$sql="SELECT district_id, district_name,district_tname  FROM mst_p_district order by district_id";
			$result = $db->query($sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
							echo "<option value='".$row['district_id']."'>".$row['district_name']."</option>";
						}else{
							echo "<option value='".$row['district_id']."'>".$row['district_tname']."</option>";	
						}
          }
		?>
		</td>
		</tr>	
	  <?php } ?>
	  <tr id="rev_tr" style="display:none;">
	  <td>
	  <?php echo $label_name[30];?>
	  </td>
	  <td>
	   <select name="taluk" id="taluk" class="select_style" onchange="loadRevVillage();">
       <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Taluk"; }else{echo "தேர்ந்தெடு";} ?> --</option>
	  <?php
		if ($userProfile->getOff_level_id()==1) {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk  order by taluk_name";
		}
		 else if ($userProfile->getOff_level_id()==4) {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." and taluk_id=".$userProfile->getTaluk_id()." order by taluk_name";
		 } else {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." order by taluk_name";
		 }
		 $result = $db->query($taluk_sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['taluk_id']."'>".$row['taluk_name']."</option>";
						}else{
						echo "<option value='".$row['taluk_id']."'>".$row['taluk_tname']."</option>";	
						}
          }
	  ?>
	  </select>
	  </td>
	  
	   <td>
	   <?php echo $label_name[34];?>
	  </td>
	  <td>
		<select name="rev_village" id="rev_village" class="select_style">
			<option value="">--Select Revenue Village--</option>
		</select>
	  </td>	  
	  </tr>
	  
	  <tr id="rural_tr" style="display:none;">
	  <td>
	  <?php echo $label_name[31];?>
	  </td>
	  <td>
	  <select name="block" id="block" onChange="get_village_panchayat();"   class="select_style">
			<option value="">--Select Block--</option>
			<?PHP 
			//if($userProfile->getOff_level_id()==6){
				$query="SELECT block_id, block_name FROM mst_p_lb_block ";	
				//District Level
				if($userProfile->getOff_level_id()==2){
					$query .= " WHERE district_id=".$userProfile->getDistrict_id();
				}
				// Block Level
				else if($userProfile->getOff_level_id()==6){
					$query .=" WHERE block_id=".$userProfile->getBlock_id();
				}
					$query .= "  ORDER BY block_name";
				$result=$db->query($query);				
				while($rowArray = $result->fetch(PDO::FETCH_BOTH))
				{
					print("<option value='".$rowArray["block_id"]."' >".$rowArray["block_name"]."</option>");
				}
			 //}
			if($userProfile->getOff_level_id()==2){
			?>
			<!--option value="">--Select--</option-->
            <?PHP
			}
			?>
			</select>
	  </td>
	  
	   <td>
	   <?php echo $label_name[35];?>
	  </td>
	  <td>
	  <select name="p_village" id="p_village"  class="select_style">
	<option value="">--Select--</option>	  
            <?PHP
			if($userProfile->getOff_level_id()==6){
				$query="SELECT lb_village_id,lb_village_name,lb_village_tname FROM mst_p_lb_village WHERE block_id=".$userProfile->getBlock_id()." ORDER BY lb_village_name";	
				$result=$db->query($query);				
				while($rowArray = $result->fetch(PDO::FETCH_BOTH))
				{
					print("<option value='".$rowArray["lb_village_id"]."' >".$rowArray["lb_village_name"]."</option>");
				}
			}
			else{
			?> <!--<option value="">--Select--</option> -->
            <?PHP
			}
			?>
	  </td>	  
	  </tr>
	  
	  <tr id="urban_tr" style="display:none;">
	  <td>
	  <?php echo $label_name[32];?>
	  </td>
	  <td>
	  <select name="urban_body" id="urban_body"  class="select_style"> 
            <option value="">--Select--</option>
			<?PHP 
			$query="";
			//if($userProfile->getOff_level_id()==7){
				$query="SELECT lb_urban_id, lb_urban_name FROM mst_p_lb_urban";
				$codn="";
				//District Level
				if($userProfile->getOff_level_id()==2){
					$codn .= " WHERE district_id=".$userProfile->getDistrict_id();
				}
			 	//Urban Level
				else if($userProfile->getOff_level_id()==7){
					$codn .= " WHERE lb_urban_id=".$userProfile->getLb_urban_id();
				}
			 	$query .= $codn;
				$query .= " ORDER BY lb_urban_name";
				
				$result=$db->query($query);				
				while($rowArray = $result->fetch(PDO::FETCH_BOTH))
				{
					print("<option value='".$rowArray["lb_urban_id"]."' >".$rowArray["lb_urban_name"]."</option>");
				}			
			//}
			if($userProfile->getOff_level_id()==2){
			?>
				<!--option value="">--Select--</option-->
			<?PHP 
			}
			?>
			</select>
	  </td>
	  
	   <td colspan="2">	   
	  </td>	  
	  </tr>
	  
	  <tr id="office_tr" style="display:none;">
	  <td>
	  <?php echo $label_name[33];?>
	  </td>
	  <td>
	  <select name="office" id="office" class="select_style"/> 
	  </td>
	  
	   <td colspan="2">	   
	  </td>	  
	  </tr>
	  
	  <tr>
	  <td><?PHP echo $label_name[27];//Grievance Type?></td>
      <td>
          <select name="gtype" id="gtype" onchange="loadSubTypes();">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Petition Main Category"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
         /*if($userProfile->getDept_coordinating() && $userProfile->getOff_coordinating())
			{
				$gre_sql = "-- user of a coordinating dept. and coordinating office
							SELECT DISTINCT(griev_type_id), griev_type_code, 
							griev_type_name, griev_type_tname
							FROM vw_usr_dept_griev_subtype ORDER BY griev_type_name";
			}
			else  
			{
				$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
							griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
							dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";		
			}*/
			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
						griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
						dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";	
          $result = $db->query($gre_sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
							if($gtype==$row['griev_type_id'])
								echo "<option value='".$row['griev_type_id']."' selected>".$row[griev_type_name]."</option>";
							else
								echo "<option value='".$row['griev_type_id']."'>".$row[griev_type_name]."</option>";
						}else{
							if($gtype==$row['griev_type_id'])
								echo "<option value='".$row['griev_type_id']."' selected>".$row['griev_type_tname']."</option>";	
							else
								echo "<option value='".$row['griev_type_id']."'>".$row['griev_type_tname']."</option>";	
						}
          }
          ?>
          </select>
      </td>
	  <td><?PHP echo $label_name[28];//Grievance Sub Type?></td>
      <td>
          <select name="gstype" id="gstype">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Petition Sub Category"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          </select>
      </td>
	  </tr>
	  
	   <tr id="petitioner_address">
	 <td><?PHP echo $label_name[25];//Petitioner Taluk?></td>
	 <td>
	  <select name="p_taluk" id="p_taluk" class="select_style" onchange="loadPetRevVillage();">
       
	  <?php
		if ($userProfile->getOff_level_id()==1) {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk  order by taluk_name"; ?>
			<option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Taluk"; }else{echo "தேர்ந்தெடு";} ?> --</option>
			
		 <?php }
		 else if ($userProfile->getOff_level_id()==4) {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." and taluk_id=".$userProfile->getTaluk_id()." order by taluk_name";
		 } else {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." order by taluk_name";
			?>
			<option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Taluk"; }else{echo "தேர்ந்தெடு";} ?> --</option>
			<?php 
		 }
		 $result = $db->query($taluk_sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
							if ($tk == $row['taluk_id'])
								echo "<option value='".$row['taluk_id']."' selected>".$row['taluk_name']."</option>";
							else
								echo "<option value='".$row['taluk_id']."'>".$row['taluk_name']."</option>";
						}else{
							if ($tk == $row['taluk_id'])
								echo "<option value='".$row['taluk_id']."' selected>".$row['taluk_tname']."</option>";
							else
								echo "<option value='".$row['taluk_id']."'>".$row['taluk_tname']."</option>";
						}
          }
	  ?>
	  </select>
	 </td>
	 <td><?PHP echo $label_name[26];//Petitioner Revenue Village?></td>
	 <td>
	 <select name="p_rev_village" id="p_rev_village" class="select_style">
			<option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Revenue Village"; }else{echo "தேர்ந்தெடு";} ?> --</option>
			<?php
				if ($userProfile->getOff_level_id()==4) {
					$sql="select distinct rev_village_id,rev_village_name,rev_village_tname from mst_p_rev_village where taluk_id=".$userProfile->getTaluk_id()." order by rev_village_name";
					$result = $db->query($sql);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
					  $villname = $row['rev_village_name'];	
					  if($_SESSION["lang"]=='E'){
							if ($rv == $row['rev_village_id'])
								echo "<option value='".$row['rev_village_id']."' selected>$villname</option>";
							else
								echo "<option value='".$row['rev_village_id']."'>$villname</option>";
						}else{
							if ($rv == $row['rev_village_id'])
								echo "<option value='".$row['rev_village_id']"' selected>".$row['rev_village_tname']."</option>";
							else
								echo "<option value='".$row['rev_village_id']."'>".$row['rev_village_tname']."</option>";	
						}		
					}			
				}
			?>
		</select>
	 </td>
	 
	 </tr>
	 
	 <tr>
	  <td><?PHP echo $label_name[42];//Grievance Type?></td>
      <td>
          <select name="actiontype" id="actiontype">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select"; }else{echo "தேர்ந்தெடு";} ?> --</option>
		  <option value="P"><?php if($_SESSION['lang']=='E'){ echo "Pending"; }else{echo "நிலுவை";} ?></option>
          <?PHP 
          $sql = "SELECT action_type_code, action_type_name, action_type_tname FROM lkp_action_type where action_type_code in ('A','R') order by action_type_code";
          $result = $db->query($sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
            if($_SESSION["lang"]=='E'){
				echo "<option value='".$row['action_type_code']."'>".$row['action_type_name']."</option>";
			}else{
				echo "<option value='".$row['action_type_code']."'>".$row['action_type_name']."</option>";	
			}
          }
          ?>
          </select>
      </td>
	  
	  <td><?PHP echo $label_name[43];//Grievance Type?></td>
      <td>
          <select name="petition_type" id="petition_type">
            	<option value="">-- Select --</option>
                <?PHP 
					$query="SELECT pet_type_id, pet_type_name, pet_type_tname, enabling, ordering
									FROM lkp_pet_type order by pet_type_id";
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_name']."</option>";
						}else{
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_tname']."</option>";	
						}
					}
				?>
            </select>
      </td>
	  
	  </tr>
	  
	  <tr>
	  <td><?PHP echo $label_name[44];//Grievance Type?></td>
      <td>
          <select name="pet_community" id="pet_community" data_valid='no' class="select_style">
	<option value="">--Select Community--</option>	
	<?php
		$community_sql = "SELECT pet_community_id, pet_community_name, pet_community_tname FROM lkp_pet_community order by pet_community_id";
		$community_rs=$db->query($community_sql);
		while($community_row = $community_rs->fetch(PDO::FETCH_BOTH))
		{
			/*$petcommunityname=$gen_row["pet_community_name"];
			$gentname=$gen_row["pet_community_name"];*/
			if($_SESSION["lang"]=='E')
			{
				$pet_community_name=$community_row["pet_community_name"];
			}else{
				$pet_community_name=$community_row["pet_community_tname"];
			}
			print("<option value='".$community_row["pet_community_id"]."' >".$pet_community_name."</option>");

		}
		
	?>
	</select>
      </td>
	  
	  <td><?PHP echo $label_name[45];//Grievance Type?></td>
      <td>
          <select name="special_category" id="special_category">
			<option value="">-- Select Special Category --</option>
			<?php
				$petitioner_category_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category order by petitioner_category_id";
				$petitioner_category_rs=$db->query($petitioner_category_sql);
				while($petitioner_category_row = $petitioner_category_rs->fetch(PDO::FETCH_BOTH))
				{
					/*$petcommunityname=$gen_row["pet_community_name"];
					$gentname=$gen_row["pet_community_name"];*/
					if($_SESSION["lang"]=='E')
					{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_name"];
					}else{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_tname"];
					}
					print("<option value='".$petitioner_category_row["petitioner_category_id"]."' >".$petitioner_category_name."</option>");

				}
			?>
			</select>
      </td>
	  
	  </tr>
	  
	  <tr>
      <td colspan="4" class="btn" align="center">
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[37];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[10];//Clear?>" class="button"/>
      </td>
      </tr>
      </tbody> <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
      <input type="hidden" name="petition_id" id="petition_id" />
	  <input type="hidden" name="dist_id" id="dist_id"  value="<?php echo $userProfile->getDistrict_id();?>"/>
	  <input type="hidden" name="pat_id" id="pat_id" />
	  <input type="hidden" name="off_detail" id="off_detail" />
	  <input type="hidden" name="dept_id" id="dept_id" />
	  
	  
	  <input type="hidden" name="fromdate" id="fromdate" value="<?php echo $fromdate;?>"/>
	  <input type="hidden" name="todate" id="todate" value="<?php echo $todate;?>"/>	  
	  <input type="hidden" name="hgtype" id="hgtype" value="<?php echo $gtype;?>"/>
	  <input type="hidden" name="hgstype" id="hgstype" value="<?php echo $gstype;?>"/>
	  <input type="hidden" name="hsource" id="hsource" value="<?php echo $source;?>"/>
	  <input type="hidden" name="htaluk" id="htaluk" value="<?php echo $tk;?>"/>
	  <input type="hidden" name="hrevvill" id="hrevvill" value="<?php echo $rv;?>"/>
	  <input type="hidden" name="hoff_level_id" id="hoff_level_id" value="<?php echo $userProfile->getOff_level_id();?>"/>
	  
      </table> 
      
      
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>