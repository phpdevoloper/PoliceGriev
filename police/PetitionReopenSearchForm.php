<?php
ob_start();
session_start();
$pagetitle="Petition Re-open";
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='1a2b'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='1a2b'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}
include("db.php"); 
include('header_menu.php');
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data


//header("Content-Security-Policy:default-src 'self' 'unsafe-inline'; object-src 'self'; script-src 'self' 'nonce-1a2b'", TRUE);
include("common_date_fun.php");
include("pm_common_js_css.php");
?>
<script nonce='1a2b' type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script nonce='1a2b' type="text/javascript" src="js/common_form_function.js"></script>
<link rel="stylesheet" href="css/style.css" type="text/css"/>

<!-- Date Picker css-->
<link rel="stylesheet" href="css/jquery.datepick.css" media="screen" type="text/css">
<script nonce='1a2b' type="text/javascript" src="js/jquery.datepick.js"></script>
<label style="display:none"> <img src="images/calendar.gif" id="calImg"> </label>
<script nonce='1a2b' type="text/javascript" charset="utf-8">


$(document).ready(function()
{
document.getElementById("p_from_pet_date").onchange = function(){
	return validatedate(p_from_pet_date,'p_from_pet_date');
};
document.getElementById("p_to_pet_date").onchange = function(){
	return validatedate(p_to_pet_date,'p_to_pet_date');
};
document.getElementById("p_griev_sub_type").onchange = function(){
	getDepts();
};
document.getElementById("p_mobile").onchange = function(){
	mob_chk();
};
document.getElementById("p_mobile").onKeyPress = function(){
	return numbersonly_ph(event);
};
document.getElementById("p_petitioner_name").onKeyPress = function(){
	return charactersonly(event);
};
document.getElementById("p_petition_no").onKeyPress = function(){
	return checkPetNo(event);
};
	setDatePicker('p_from_pet_date');
	setDatePicker('p_to_pet_date');
	setDatePicker('p_dob');
	addDate();
	var off_lvl_pattern = $('#off_level_pattern_id').val();
	var dept_coord = $('#dept_coord').val();
	var desig_coord = $('#desig_coord').val();
	var off_coord = $('#off_coord').val();

	
	$("#p_search").click(function(){
		p_loadGrid(1, $('#p_pageSize').val());
	});
	
	$('#p_pageNoList').change(function(){
		p_loadGrid($('#p_pageNoList').val(), $('#p_pageSize').val());
	});
	
	$('#p_pageSize').change(function(){
		p_loadGrid(1, $('#p_pageSize').val());
	});
	
	$("#p_clear").click(function(){
		p_clearSerachParams();
	});
	
		
	$('#p_griev').change(function(){
		if($('#p_griev').val()!=""){
			p_sub_griev($('#p_griev').val());
		}
		else{
			createCombobox("p_griev_sub_type", "Grievance Sub Type");
		}
	});

});

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_to_pet_act_date");
	
	newdate.setDate(newdate.getDate() - 7);
	var fromDate = new Date(newdate);
	setDateFormat(fromDate, "#p_from_pet_act_date");
}

function getDepts() {
	grev_sub=$('#p_griev_sub_type').val();
	griev_main=$('#p_griev').val();
	var param="mode=get_depts&griev_sub="+grev_sub+"&grev="+griev_main;
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionStatusReportsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response
			var temp = $(xml).find('dept_name').eq(0).text(); 
			populateComboBox(xml, 'p_griev_dept_id', (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'துறை':'--Select Department--', 'dept_id', 'dept_name', '');
		},  
		error: function(e){
			//alert('Error: ' + e);  
		} 
	});//aja
}

function p_sub_source(src_sno){
	var param="mode=sub_source&src_sno="+src_sno+"&form_tocken="+$('#formptoken').val();
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionStatusReportsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response
			var temp = $(xml).find('subsource_name').eq(0).text(); 			
			 populateComboBox(xml, 'p_sub_source', (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? '"கோரிக்கை பெற்ற வழி"':'Sub Source', 'subsource_id', 'subsource_name', '');
		},  
		error: function(e){
			//alert('Error: ' + e);  
		} 
	});//ajax end
}


function p_sub_griev(griev_sno){
	var param="mode=griev_sub&griev_sno="+griev_sno+"&form_tocken="+$('#formptoken').val();
	
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionStatusReportsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response
			var temp = $(xml).find('griev_subtype_name').eq(0).text(); 			
			 populateComboBox(xml, 'p_griev_sub_type', (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'கோரிக்கை துணை வகை':'Grievance Sub Type', 'griev_subtype_id', 'griev_subtype_name', '');
		},  
		error: function(e){
			//alert('Error: ' + e);  
		} 
	});//ajax end
}

function openPetitionStatusReport(petition_id){
	document.getElementById("petition_id").value=petition_id;
	document.petiton_status_report.target = "Map";
	document.petiton_status_report.method="post";  
	document.petiton_status_report.action = "p_ReopenPetitionProcessDetails.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petiton_status_report.submit();
	}  
}

function changeDateFormat(dt){
	var datearray = dt.split("/");
	var ndt = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
	return ndt;
}

function p_searchParams(){
	$('#p_dataGrid').empty();
	frdate = '';
	todate = '';
	var order_by = $('#order_by').val();
	if (order_by == '') {
		document.getElementById('order_by').value='P';
	}
	var src_from = 'reopen';
	if ($('#p_from_pet_date').val() != '') {
			frdate = changeDateFormat($('#p_from_pet_date').val());
	}
	if ($('#p_to_pet_date').val() != '') {
			todate = changeDateFormat($('#p_to_pet_date').val());
	}	 
		
	var param="&p_petition_no="+$('#p_petition_no').val();
	param+="&p_can_id="+$('#p_can_id').val();
	
	param+="&p_from_pet_date="+frdate;
	param+="&p_to_pet_date="+todate;
	
	param+="&p_source="+$('#p_source').val(); 
		
	param+="&p_griev="+$('#p_griev').val(); 
	param+="&p_griev_sub_type="+$('#p_griev_sub_type').val(); 
	
	param+="&p_petitioner_name="+$('#p_petitioner_name').val(); 
	param+="&p_mobile="+$('#p_mobile').val(); 
	param+="&p_aadharid="+$('#p_aadharid').val(); 
	param+="&p_petition_type="+$('#petition_type').val(); 
	param+="&p_action_type="+$('#action_type').val();
	param+="&order_by="+$('#order_by').val();
	param+="&pet_community="+$('#pet_community').val();
	param+="&special_category="+$('#special_category').val();
	param+="&key_words="+replaceString($('#key_words').val().replace(/  +/g, ' '));
	param+="&source_from="+src_from;
	param+="&form_tocken="+$('#formptoken').val();
	
	return param;
}
function escapeRegExp(string){

    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

}
function replaceAll(str, term, replacement) {
  var new_str = str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
  
  if (new_str.endsWith("**")) {
	  new_str = new_str.substring(0, new_str.length - 2);
  }
  return new_str;

}
function replaceString(keywords) {
	return replaceAll(keywords, ' ', '**');
}
function p_clearSerachParams(){
	
	document.petiton_status_report.action = "PetitionReopenSearchForm.php";
	document.petiton_status_report.method="post";
	document.petiton_status_report.target= "_self";
	document.petiton_status_report.submit();	
}

function resetDepartment() {
	var param="mode=load_department";
	$.ajax({
		type:"POST",
		dataType: "xml",
		url:"p_PetitionStatusReportsAction.php",
		data: param,  
		
		beforeSend: function(){},
		complete: function(){},
		success: function(xml){
			
			var temp = $(xml).find('dept_name').eq(0).text(); 
			populateComboBox(xml, 'p_griev_dept_id', (temp.charCodeAt(0)>=2944 && temp.charCodeAt(0)<=3071) ? 'துறை':'--Select Department--', 'dept_id', 'dept_name', '');
			}
		
		});
}

function p_loadGrid(pageNo, pageSize){
	document.getElementById("loadmessage").style.display='';

	var param = "mode=p_search"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+"&form_tocken="+$('#formptoken').val()
		+p_searchParams();
	

	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionStatusReportsAction.php",  
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
	
	$('#p_dataGrid').empty();
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	var len = ($(xml).find('petition_id').length);
	
	if (len == 0) {
		alert("No records found for this condition, Please check your Search conditions");
	} else {
		$(xml).find('petition_id').each(function(i)
		{
		
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('petition_date').eq(i).text();
		var source_name = $(xml).find('source_name').eq(i).text();
		var subsource_remarks = $(xml).find('subsource_remarks').eq(i).text();
		
		if (subsource_remarks == "") {
			source_name = source_name;
		} else {
			source_name = source_name +" &amp; "+subsource_remarks;
		}
		if ($(xml).find('off_location_design').eq(i).text() != "") {
			last_action_remarks = $(xml).find('off_location_design').eq(i).text()+" ; "+$(xml).find('action_type_name').eq(i).text()+" on "+$(xml).find('fwd_date').eq(i).text()+", <br>Remarks: "+$(xml).find('fwd_remarks').eq(i).text();
		} else {
			last_action_remarks = $(xml).find('action_type_name').eq(i).text()+" on "+$(xml).find('fwd_date').eq(i).text()+", <br>Remarks: "+$(xml).find('fwd_remarks').eq(i).text();
		}
		$('#p_dataGrid')
		.append("<tr>"+
		"<td>"+$(xml).find('rownum').eq(i).text()+"</id>"+
		"<td>"+ 
			"Mobile No: "+$(xml).find('comm_mobile').eq(i).text()+"<br><br>"+
			"<a href='javascript:openPetitionStatusReport("+petition_id+");' title='Petition Process Report'>"+
			$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			"</a>"+
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"</td>"+
		"<td>"+source_name+"</td>"+
		"<td>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+ ", <br>"+$(xml).find('gri_address').eq(i).text()+"<br><b>"+$(xml).find('pet_type_name').eq(i).text()+"</b></td>"+
		"<td style='text-align: left;'>"+last_action_remarks+"</td>"+
		"<td>"+$(xml).find('pend_period').eq(i).text()+"</td>"+
		"</tr>");
	
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();

	drawPagination('p_pageFooter1', 'p_pageFooter2','p_pageSize', 'p_pageNoList', 'p_next', 'p_previous', 'p_noOfPageSpan', 'p_loadGrid', pageNo, pageSize, noOfPage);
	}
	
}

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_to_pet_date");
	
	newdate.setDate(newdate.getDate() - 7);
	var fromDate = new Date(newdate);
	setDateFormat(fromDate, "#p_from_pet_date");
}
function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		if(unicode<48||unicode>57)
		return false
	}
}

function charactersonly(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46 && unicode!=64  )
	{
	if (( ((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32))
			return true
	else
			return false
	}
}

function mob_chk()
{
 mob_no=document.getElementById("p_mobile").value.length;
	  if(mob_no < 10)
	 {
	 	alert("Mobile Number cannot be less than 10 characters");
		document.getElementById("p_mobile").value="";
		document.getElementById("p_mobile").focus();
		return false;
	 } 
}
function numbersonly_ph(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		if((unicode<48||unicode>57)&& unicode !=43) {
			alert("Only numbers 0 to 9 and + are allowed");
			return false
		}
	}
}

function validatedate(inputText,elementid){
 
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
  // Match the date format through regular expression 
  if (inputText != "") 
  {
		if(inputText.value.match(dateformat)) 
		{
			 document.form1.text1.focus();  
	  //Test which seperator is used '/' or '-'  
			  var opera1 = inputText.value.split('/');  
			  var opera2 = inputText.value.split('-');  
			  lopera1 = opera1.length;  
			  lopera2 = opera2.length;  
			  // Extract the string into month, date and year  
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
			  // Create list of days of a month [assume there is no leap year by default]  
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

 
}

function checkPetNo(e) 
{ 	
		//alert("Check");
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || 
		(unicode == 12 || (unicode>=33 && unicode <=40) || unicode == 45 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
}

</script>
<style type="text/css">
.lblTD{
	text-align:right !important;
	padding-right: 5px !important;	
}
.fldTD{
	text-align:left !important;
	padding-left: 5px !important;	
}
</style>
<?php
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='p_PetitionStatusReportsForm.php') order by ordering";
$result = $db->query($query);
while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
?>
<form name="petiton_status_report" id="petiton_status_report" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
  <div id="dontprint">
    <div class="form_heading">
       <div class="heading"><?php echo $label_name[49]; //Petition Re-open?> </div>
    </div>
  </div>
  <div class="contentMainDiv" style="width:98%;margin:auto;">
    <div class="contentDiv">
      <?PHP 
			
?>
      <table class="searchTbl" style="border-top: 1px solid #000000;">
        <tbody>
          <tr>
            <th colspan="8"><?php echo $label_name[0]; //Search Parameters?></th>
          </tr>
          <tr>
		  
			<td class="lblTD"><?php echo $label_name[20]; //Petition Period?></td>
            <td class="fldTD"><?php echo $label_name[21]; //From?>
            <input type="text" name="p_from_pet_date" id="p_from_pet_date" maxlength="12" style="width: 90px;" />
			&nbsp; <?php echo $label_name[22]; //To?>&nbsp;
			<input type="text" name="p_to_pet_date" id="p_to_pet_date"  maxlength="12" style="width: 90px;"/>
            </td>
			
			<td class="lblTD"><?php echo $label_name[39]; //Petitioner Name.?></td>
            <td  class="fldTD"><input type="text" name="p_petitioner_name" id="p_petitioner_name" /></td>
			
			<td class="lblTD" ><?php echo $label_name[40]; //Mobile Number?></td>
            <td  class="fldTD"><input type="text" name="p_mobile" id="p_mobile" maxlength="13" /></td>
		</tr>
		<tr>
            <td class="lblTD"><?php echo $label_name[36]; //Petition No.?></td>
            <td  class="fldTD"><input type="text" name="p_petition_no" id="p_petition_no" maxlength="25" /></td>
             <td class="lblTD" colSpan="4"><?php //echo $label_name[1]; //Source?></td>
            <!--td class="fldTD" colSpan="3"--><select name="p_source" id="p_source" style="display:none;">
                <option value="">-- <?php echo $label_name[2]; //Select Source?> --</option>
                <?PHP 
					
					$query="SELECT a.source_id, b.source_name, b.source_tname FROM usr_dept_off_level_sources a
					JOIN lkp_pet_source b ON b.source_id = a.source_id
					WHERE a.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and coalesce(b.enabling,true)
					and ((b.open_fr_date is null and b.open_to_date is null) or ((now()>=b.open_fr_date and now()<=b.open_to_date)))
					order by b.source_id" ;
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
					}
				?>
              </select><!--/td-->
			</tr>
			<tr>			
            <td class="lblTD" ><?php echo $label_name[44]; // Grievance ?></td>
            <td class="fldTD"><select name="p_griev" id="p_griev">
                <option value="">-- <?php echo $label_name[4]; //Select Grievance Type?> </option>
                <?PHP 
					$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
					griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
					dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";
			 
					$result = $db->query($gre_sql);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						echo "<option value='".$row['griev_type_id']."'>".$row['griev_type_name']."</option>";
					}
				?>
              </select></td>
            <td class="lblTD"><?php echo $label_name[45]; //Grievance Sub Type?></td>
            <td class="fldTD" colSpan="3"><select name="p_griev_sub_type" id="p_griev_sub_type">
                <option value="">-- <?php echo $label_name[6]; //Select Grievance Sub Type?> --</option>
              </select></td>
          </tr>
          
         
		 <td class="lblTD"><?php echo $label_name[48]; //Petition Type?></td>
            <td class="fldTD" id="c_dept"><select name="petition_type" id="petition_type">
                <option value="">--Select Petition Type--</option>
                <?PHP 
				if($userProfile->getOff_level_id()==46){
						$codn_pet=" where pet_type_id in (2,3)";
					}else{
						$codn_pet="";
					}
					$query="SELECT pet_type_id, pet_type_name, pet_type_tname, enabling, ordering
									FROM lkp_pet_type ".$codn_pet." order by pet_type_id";
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
         
		 <td class="lblTD"><?php echo $label_name[50]; //Action Type?></td>
            <td class="fldTD" id="c_dept"><select name="action_type" id="action_type">
                <option value="">--Select Action Type--</option>
                <?PHP 
					$query="SELECT action_type_id, action_type_code, action_type_name FROM lkp_action_type where 			action_type_code in ('A','R')";
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					//echo "<option value=''>--Select--</option>";
					foreach($rowarray as $row){
						
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['action_type_code']."'>".$row['action_type_name']."</option>";
						}else{
						echo "<option value='".$row['action_type_code']."'>".$row['action_type_tname']."</option>";	
						}
						
					}
					//echo "<option value='P'>Pending</option>";
				?>
              </select></td> 		 
         
				 <td class="lblTD"><?php echo $label_name[52]; //Order By?></td>
            <td class="fldTD" colspan="3" id="c_order_by"><select name="order_by" id="order_by">
                <option value="">--Select Order By--</option>
				<option value='P'><?php echo $label_name[36]; //Petition No.?></option>
				<option value='N'><?php echo $label_name[53]; //Petitioner Name and Address?></option>
				<option value='M'><?php echo $label_name[40]; //Mobile Number?></option>
                <?PHP 
				?>
              </select></td> 
			  
        </tbody>
    <tr style="display:none;">
	<td class="lblTD"><?php echo $label_name[54]; //Community?></td>
	<td class="fldTD">	
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
	<td class="lblTD"><?php echo $label_name[55]; //Category?></td>
	<td class="fldTD" colspan="3">	
	<select name="special_category" id="special_category">
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
			</select>
	 </td>
	 
	</tr>	
	<tr>
	<td class="lblTD" colspan="1"><?php echo $label_name[51]; //Keywords?></td>
	<td  class="fldTD" colspan="5"><input type="text" name="key_words" id="key_words" style="width: 900px;"/></td>
	</tr>
	<tr>	
	<input type="hidden" name="p_griev_dist_id" id="p_griev_dist_id" value="<?php echo $userProfile->getDistrict_id();?>" />
          <td colspan="8"><input type="button" name="p_search" id="p_search" value="<?php echo $label_name[32]; //Search?>" class="button"/>
            <input type="button" name="p_search" id="p_clear" value="<?php echo $label_name[33]; //Clear?>" class="button"/></td>
        </tr>
      </table>
      <table class="existRecTbl">
        <thead>
          <tr>
            <th>Petition Details</th>
           <!-- <th><?//php echo $label_name[23]; //Existing Details?></th>-->
            <th><?php echo $label_name[24]; //Page&nbsp;Size?>
              <select name="p_pageSize" id="p_pageSize" class="pageSize">
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
		<th  style="width:3%;"><?php echo $label_name[47]; //Sl No. ?></th>
		<th  style="width:15%;"><?php echo $label_name[40].', '.$label_name[25]; //Petition No. & Date?></th>
		<th  style="width:13%;"><?php echo $label_name[26]; //Petitioner's Communication Address?></th>
		<th  style="width:11%;"><?php echo $label_name[27]; //Source?><?php echo ' & '.$label_name[46]; //Source Remarks?></th>
		<th  style="width:20%;"><?php echo $label_name[28]; //Grievance?></th>
		<th  style="width:14%;"><?php echo $label_name[29]; //Grievance Type, Sub Type & Address?></th>
		<th  style="width:17%;"><?php echo $label_name[30]; //Action Type, Date & Remarks?></th>
		<th  style="width:5%;"><?php echo 'Pending Period';?></th>
	</tr>
        </thead>
        <tbody id="p_dataGrid">
        </tbody>
      </table>
	  <div id="loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
      <table class="paginationTbl">
        <tbody>
          <tr id="p_pageFooter1" style="display: none;">
            <td id="p_previous"></td>
            <td>Page
              <select id="p_pageNoList" name="p_pageNoList" class="pageNoList">
              </select>
              <span id="p_noOfPageSpan"></span></td>
            <td id="p_next"></td>
            <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            <input type="hidden" name="petition_id" id="petition_id" value="<?PHP echo $_REQUEST['petition_id']?>"/>
          </tr>
          <tr id="p_pageFooter2">
            <td colspan="3" class="emptyTR"></td>
          </tr>
        </tbody>
      </table>
      <div> </div>
    </div>
  </div>
  <input type="hidden" name="off_level_id" id="off_level_id"  value="<?php echo $userProfile->getOff_level_id();?>" />
  <input type="hidden" name="off_level_pattern_id" id="off_level_pattern_id" 
        value="<?php echo $userProfile->getOff_level_pattern_id();?>" />
  <input type="hidden" name="dept_coord" id="dept_coord" 
        value="<?php echo $userProfile->getDept_coordinating();?>" />
  <input type="hidden" name="desig_coord" id="desig_coord" 
        value="<?php echo $userProfile->getDesig_coordinating();?>" />
  <input type="hidden" name="off_coord" id="off_coord" 
        value="<?php echo $userProfile->getOff_coordinating();?>" />
</form>
<?php include("footer.php"); ?>
