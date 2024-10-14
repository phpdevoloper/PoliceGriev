<?php
ob_start();
session_start();
$pagetitle="Print Dispsoal Letter";
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
	load_department();
	var fdate = $("#fromdate").val();
	var tdate = $("#todate").val();
	
	if (fdate == '')
		addDate();	
	else 
	{
		$("#p_from_pet_date").val(fdate);
		$("#p_to_pet_date").val(tdate);
	}
		
	$("#p_search").click(function(){
		$("#uploaded").val($('input[name=upload]:checked', '#ackmnt').val());
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$('#p2_pageNoList').change(function(){
		$("#uploaded").val($('input[name=upload]:checked', '#ackmnt').val());
		p2_loadGrid($('#p2_pageNoList').val(), $('#p2_pageSize').val());
	});
	
	$('#p2_pageSize').change(function(){
		$("#uploaded").val($('input[name=upload]:checked', '#ackmnt').val());
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$("#p_clear").click(function(){
		p_clearSerachParams();
	});
	
});

function p_clearSerachParams() {
	addDate();
}
function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_from_pet_date");
	setDateFormat(date, "#p_to_pet_date");
}

function p_searchParams(){
	$('#p2_dataGrid').empty();
	var param="&p_from_pet_date="+$('#p_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p_to_pet_date').val();
	param+="&p_source="+$('#p_source').val();
	param+="&dept="+$('#dept').val();  
	param+="&gtype="+$('#gtype').val();
	param+="&gstype="+$('#gstype').val();
	param+="&actiontype="+$('#actiontype').val();
	param+="&petitiontype="+$('#petition_type').val();
	param+="&pet_community="+$('#pet_community').val();
	param+="&special_category="+$('#special_category').val();
	//param+="&off_detail="+$('#off_detail').val();
	param+="&form_tocken="+$('#formptoken').val(); 
	//alert(param);
	return param;
}

function p2_loadGrid(pageNo, pageSize){
	
	document.getElementById("loadmessage").style.display='';
    //searchOfficeData();
	var param = "mode=p_search_action_taken"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+p_searchParams();
	

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionProcessedByUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			document.getElementById("loadmessage").style.display='none';
			 p_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
}

function p_createGrid(xml){
	
	$('#p2_dataGrid').empty();
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	if ($(xml).find('pet_action_id').length == 0) {
		alert("No records found for the given dates");
	}
	$(xml).find('pet_action_id').each(function(i)
	{
		
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		var pet_no=$(xml).find('petition_no').eq(i).text();
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+"<input type='checkbox' name='chk_p2_pet_action_id' id='chk_petition_id_"+pet_action_id+"' value='"+petition_id+"' onclick='handleClick(this);'>"+"</id>"+
		"<td>"+
			"<input type='hidden' name='p2_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p2_petition_id' id='p2_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			
		$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('source_name').eq(i).text()+"<br>"+$(xml).find('subsource_remarks').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+ "<br>Address: "                     +$(xml).find('gri_address').eq(i).text()+"<br><b>"+$(xml).find('pet_type_name').eq(i).text()+"</b></td>"+
		"<td>"+$(xml).find('action_type_name').eq(i).text()+" , On "
		+$(xml).find('fwd_date').eq(i).text()
		+"<br>Remarks:"+$(xml).find('fwd_remarks').eq(i).text()+
		"</td>"+
		"<td>"+$(xml).find('action_type_name').eq(i).text()+"</td>"+
				"</tr>");
	
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	drawPagination('p2_pageFooter1', 'p2_pageFooter2','p2_pageSize', 'p2_pageNoList', 'p2_next', 'p2_previous', 'p2_noOfPageSpan', 'p2_loadGrid', pageNo, pageSize, noOfPage);
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
					optionTag += "<option value='"+dept_val+"'>"+desc+"</option>";
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

function searchOfficeData() {
	
	var pat = document.getElementById("pat_id").value;
	var dept_id = $('#dept').val();
	
	if (pat == 1) {
		var taluk = $('#taluk').val();
		var rv = $('#rev_village').val();
		if (taluk != '') {
			document.getElementById("off_detail").value=taluk;
		}
		if (rv != '') {
			document.getElementById("off_detail").value=rv;
			
		}
		
	} else if (pat == 2) {
		var block = $('#block').val();
		var pv = $('#p_village').val();
		if (block != '') {
			document.getElementById("off_detail").value=block;
		}
		if (pv != '') {
			document.getElementById("off_detail").value = pv;
		}
	} else if (pat == 3) {
		var urban = $('#urban_body').val();
		
		if (urban != '') {
			document.getElementById("off_detail").value=urban;
		}
	} else if (pat == 4) {
		var office = $('#office').val();
		
		if (office != '') {
			document.getElementById("off_detail").value=office;
		}
	} 
	
	
}

function loadOfficeLocation() {
//var vSkillText = vSkill.options[vSkill.selectedIndex].innerHTML;
	var dept = $('#dept').val();
	if (dept == '') {
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
	var dist=$('#dist_id').val();
	
	
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

var array = [];
function handleClick(cb) {
	
	if (cb.checked) {
		array.push(cb.value);
	} else {
		var i = array.indexOf(cb.value);
		if(i != -1) {
			array.splice(i, 1);
		}
	}
}

function generateDisposalLetter() {
	var arrLen = array.length;
	if (arrLen == 0) {
		alert("Select atleat one Petition to generate disposal letter");
	} else {
		document.p_registers.action = "p_DisposalOrder.php";
		document.getElementById("pet_details").value = array;
		document.p_registers.submit();
	}
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
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[38]//Generate Disposing Letter?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="formTbl" style="border-top: 1px solid #000000;">
      <tbody>
      <tr>
      <td class="from_to_dt" colspan="4" style="text-align:center;"><span><label><b><?PHP echo $label_name[39] //Dispsoal Period;?> : </b></label></span>&nbsp;&nbsp;
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
					
			$query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";

			$result = $db->query($query);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarray as $row){
				if($_SESSION["lang"]=='E'){
					if ($source == $row['source_id'])
						echo "<option value='".$row['source_id']."' selected>".$row[source_name]."</option>";
					else
						echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
				} else {
					if ($source == $row[source_id])
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
          <select name="dept" id="dept">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Department"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          
          </select>
      </td>
	  	  
	  </tr>
	  
	  <tr id="rev_tr" style="display:none;">
	  <td>
	  <?php echo $label_name[30];?>
	  </td>
	  <td>
	   <select name="taluk" id="taluk" class="select_style" onchange="loadRevVillage();">
       <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Taluk"; }else{echo "தேர்ந்தெடு";} ?> --</option>
	  <?php
		 if ($userProfile->getOff_level_id()==4) {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." and taluk_id=".$userProfile->getTaluk_id()." order by taluk_name";
		 } else if ($userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==3){
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id=".$userProfile->getDistrict_id()." order by taluk_name";
		 } else {
			$taluk_sql="select distinct taluk_id,taluk_name,taluk_tname from mst_p_taluk where district_id<0 order by taluk_name"; 
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
					print("<option value='".$rowArray["block_id"]."' >".ucfirst(strtolower($rowArray["block_name"]))."</option>");
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
            <?PHP
			if($userProfile->getOff_level_id()==6){
				$query="SELECT lb_village_id,lb_village_name,lb_village_tname FROM mst_p_lb_village WHERE block_id=".$userProfile->getBlock_id()." ORDER BY lb_village_name";	
				$result=$db->query($query);				
				while($rowArray = $result->fetch(PDO::FETCH_BOTH))
				{
					print("<option value='".$rowArray["lb_village_id"]."' >".ucfirst(strtolower($rowArray["lb_village_name"]))."</option>");
				}
			}
			else{
			?><option value="">--Select--</option>
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
					print("<option value='".$rowArray["lb_urban_id"]."' >".ucfirst(strtolower($rowArray["lb_urban_name"]))."</option>");
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

			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
			griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
			dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";
          $result = $db->query($gre_sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
							if($gtype==$row[griev_type_id])
								echo "<option value='".$row['griev_type_id']."' selected>".$row['griev_type_name']."</option>";
							else
								echo "<option value='".$row['griev_type_id']."'>".$row['griev_type_name']."</option>";
						}else{
							if($gtype==$row[griev_type_id])
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
	  
	  <tr>
	  <td><?PHP echo $label_name[42];//Grievance Type?></td>
      <td>
          <select name="actiontype" id="actiontype">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
          $sql = "SELECT action_type_code, action_type_name, action_type_tname FROM lkp_action_type where action_type_code in ('A','R') order by action_type_code";
          $result = $db->query($sql);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
            if($_SESSION["lang"]=='E'){
				echo "<option value='".$row['action_type_code']."'>".$row['action_type_name']."</option>";
			}else{
				echo "<option value='".$row['action_type_code']."'>".$row['action_type_tname']."</option>";	
			}
          }
          ?>
          </select>
      </td>
	  
	 <td><?php echo $label_name[43]; //Petition Type?></td>
            <td><select name="petition_type" id="petition_type">
                <option value="">--Select Petition Type--</option>
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
              </select></td> 
			  
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
	  
	 <td><?php echo $label_name[45]; //Petition Type?></td>
            <td><select name="special_category" id="special_category">
			<option value="">-- Select Special Category --</option>
			<?php
				$petitioner_category_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category order by petitioner_category_id";
				$petitioner_category_rs=$db->query($petitioner_category_sql);
				while($petitioner_category_row = $petitioner_category_rs->fetch(PDO::FETCH_BOTH))
				{
					if($_SESSION["lang"]=='E')
					{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_name"];
					}else{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_tname"];
					}
					print("<option value='".$petitioner_category_row["petitioner_category_id"]."' >".$petitioner_category_name."</option>");

				}
			?>
			</select></td> 
			  
	  	  </tr>
		  
	  <tr>
      <td colspan="4" class="btn" align="center">
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[9];//Search?>" class="button"/>
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
	  <input type="hidden" name="h_dept_id" id="h_dept_id" />
	  <input type="hidden" name="pet_details" id="pet_details" />
	  
	  
	  <input type="hidden" name="fromdate" id="fromdate" value="<?php echo $fromdate;?>"/>
	  <input type="hidden" name="todate" id="todate" value="<?php echo $todate;?>"/>	  
	  <input type="hidden" name="hgtype" id="hgtype" value="<?php echo $gtype;?>"/>
	  <input type="hidden" name="hgstype" id="hgstype" value="<?php echo $gstype;?>"/>
	  <input type="hidden" name="hsource" id="hsource" value="<?php echo $source;?>"/>
	  <input type="hidden" name="htaluk" id="htaluk" value="<?php echo $tk;?>"/>
	  <input type="hidden" name="hrevvill" id="hrevvill" value="<?php echo $rv;?>"/>
	  
      </table> 
 
 <table class="existRecTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[24];//'Petition Details';?></th>
      <!--<th><?//PHP echo $label_name[11];//Existing Details?></th>-->
          <th><?PHP echo $label_name[12];//'Page Size';//Page&nbsp;Size?>
          <select name="p2_pageSize" id="p2_pageSize" class="pageSize">
          <!--<option value="5" selected="selected">5</option>-->
          <option value="15" selected="selected">15</option>
          <option value="30">30</option>
          <option value="50">50</option>
          </select>
      </th>
      </tr>
      </thead>
      </table>
 
<table class="gridTbl">
      <thead>
      <tr>
      <th style="width:3%;"><?PHP echo $label_name[40]; //Select ?></th>
      <th style="width:13%;"><?PHP echo $label_name[13]; //Petition No and Date ?></th>
      <th style="width:13%;"><?PHP echo $label_name[14]; //'Address'; ?></th>
      <th style="width:13%;"><?PHP echo $label_name[15].' and '.$label_name[23];//'Source' and 'Source Remarks'; ?></th>      
      <th style="width:20%;"><?PHP echo $label_name[16];//'Grievance'; ?></th>
	  <th style="width:14%;"><?PHP echo $label_name[17];//'Grievance Type, Sub Type and Address'; ?></th> 
	  <th style="width:17%;"><?PHP echo $label_name[18];//'Petition Status'; ?></th> 
	  <th style="width:5%;"><?PHP echo $label_name[42];//'Petition Status'; ?></th> 
      
      
      </tr>
      </thead>
      <tbody id="p2_dataGrid"></tbody>
      </table>
      <div id="loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
      <table class="paginationTbl">
      <tbody>
      <tr id="p2_pageFooter1" style="display: none;">
      <td id="p2_previous"></td>
      <td><?PHP echo $label_name[19];//Page?><select id="p2_pageNoList" name="p2_pageNoList" class="pageNoList"></select>
      <span id="p2_noOfPageSpan"></span></td>
      <td id="p2_next"></td>
      </tr>
      <tr id="p2_pageFooter2"><td colspan="3" class="emptyTR"></td>
      </tr>
	  
	  <tr>			
            <td class="btn" colspan="7">
			
			<input type="button" name="generate" id="generate" value="<?PHP echo $label_name[41] //'Generate'; //Save?>" onClick="return generateDisposalLetter();"/> &nbsp;
	</tr>		
	  
      <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
 

      </tbody>
      </table>
	  
      
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>