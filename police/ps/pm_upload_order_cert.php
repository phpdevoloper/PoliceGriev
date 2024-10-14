<?php 
ob_start();
$pagetitle="Upload Order/Certificate";

 if(isset($_GET['user_id']))
 {
     $_SESSION['USER_ID_PK'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $_GET['user_id']);
	 $_SESSION['lang']=  preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $_GET['language']);;
 }
include("db.php");
include("header_menu.php");
include("menu_home.php");
include("common_date_fun.php");
include("pm_common_js_css.php"); 

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
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/moment.min.js"></script>
 <script type="text/javascript">

$(document).ready(function()
{
	setDatePicker('p_from_pet_date');
	setDatePicker('p_to_pet_date');
	setDatePicker('p_from_pet_act_date');
	setDatePicker('p_to_pet_act_date');
	addDate();
	
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

function checkPetNo(e) 
{ 	
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
	
function uploadOrderCopies(upact)
{

	var user_id = document.getElementById("user_id").value;
	pet_no1=$('#petition_no1').val();
	pet_no2=$('#petition_no2').val();
	pet_no3=$('#petition_no3').val();	
	
	if($.trim(pet_no1)=='' && $.trim(pet_no2)=='' && $.trim(pet_no3)=='')
	{
		alert("Please enter aleast one petition number !");
		return false;
	} else {
		if (i > order_copy_counts) {
			alert('Please select files for all the selected Petition numbers');
			return false;
		}
		if (upact == 'save') {
			document.getElementById("uploadaction").value='save';
		} else {
			document.getElementById("uploadaction").value='update';
		}
		document.getElementById("hid").value='done';
		document.ackmnt.action = "pm_upload_order_cert.php";
		document.ackmnt.submit();
		return true;
	}		
    
} 
function p_searchParams(){
	$('#p2_dataGrid').empty();
	var param="&p_from_pet_date="+$('#p_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p_to_pet_date').val();
	param+="&p_from_pet_act_date="+$('#p_from_pet_act_date').val();
	param+="&p_to_pet_act_date="+$('#p_to_pet_act_date').val();
	param+="&p_source="+$('#p_source').val();
	param+="&gtype="+$('#gtype').val();
	param+="&dept="+$('#dept').val();
	param+="&pet_community="+$('#pet_community').val();
	param+="&special_category="+$('#special_category').val();
	param+="&p_uploaded="+$('#uploaded').val();
	param+="&form_tocken="+$('#formptoken').val(); 
	return param;
}

function p_clearSerachParams(){
	document.ackmnt.action = "pm_upload_order_cert.php";
	document.ackmnt.submit();	
}

function p2_loadGrid(pageNo, pageSize){
	$("#uploaded").val($('input[name=upload]:checked', '#ackmnt').val());
	document.getElementById("loadmessage").style.display='';

	var param = "mode=p_search_upload"
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
var i = 0;
function handleClick(cb) {
	i=i+1;
	if (cb.checked) {
		document.getElementById('update').disabled=false;
		document.getElementById('delete').disabled=false;
		if (i==1) {
			$('#petition_no1').val(cb.value);
			document.getElementById('files1').disabled=false;
		document.getElementById('upload_tab').style.display='';
		} else if (i == 2) {
			$('#petition_no2').val(cb.value);
			document.getElementById('files2').disabled=false;
		document.getElementById('upload_tab').style.display='';
			//i=i+1;
		} else if (i == 3) {
			$('#petition_no3').val(cb.value);
			document.getElementById('files3').disabled=false;
		document.getElementById('upload_tab').style.display='';
			//i=i+1;
		} else if (i > 3) {
			cb.checked = false; 
		document.getElementById('upload_tab').style.display='';
			alert("You can not select more than Three petitions at a time");
			return false;
		}
	} else if (!(cb.checked)) {
		document.getElementById('upload_tab').style.display='none';
		clearAll();
	}
}
function clearAll() {
	$('#petition_no1').val("");
	$('#petition_no2').val("");
	$('#petition_no3').val("");
	$('input[type=checkbox]').attr('checked',false);
	i = 0;
	document.getElementById('update').disabled=true;
	document.getElementById('delete').disabled=true;
	document.getElementById('files1').disabled=true;
	document.getElementById('files2').disabled=true;
	document.getElementById('files3').disabled=true;
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
		source_name = $(xml).find('source_name').eq(i).text();
		subsource_remarks = $(xml).find('subsource_remarks').eq(i).text();
		
		if (subsource_remarks == "") {
			source_name = source_name;
		} else {			
			source_name = source_name +" & "+subsource_remarks;
		}
		
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+"<input type='checkbox' name='chk_p2_pet_action_id' id='chk_petition_id_"+pet_action_id+"' value='"+pet_no+"' onclick='handleClick(this);'>"+"</id>"+
		"<td>"+
			"<input type='hidden' name='p2_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p2_petition_id' id='p2_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			
		$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"</td>"+
		"<td>"+source_name+"</td>"+		
		"<td>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+ "<br>Address: "                     +$(xml).find('gri_address').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('action_type_name').eq(i).text()+" On "+$(xml).find('action_entdt').eq(i).text()+ "<brRemarks: "                     +$(xml).find('fwd_remarks').eq(i).text()+"</td>"+
		"</tr>");
	
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	drawPagination('p2_pageFooter1', 'p2_pageFooter2','p2_pageSize', 'p2_pageNoList', 'p2_next', 'p2_previous', 'p2_noOfPageSpan', 'p2_loadGrid', pageNo, pageSize, noOfPage);
}

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_from_pet_date");
	setDateFormat(date, "#p_to_pet_date");
}

var totalfilesize = 0;
order_copy_counts = 0;
function filesizevalidation(fileid,pet_id)
{
	var selectedFiles = document.getElementById(fileid);
	var pet = document.getElementById(pet_id).value;
	
	if (pet == "") {
		alert("Select a petition number before uploading a document");
		document.getElementById(fileid).value=null;
		document.getElementById(pet_id).focus();	
		return false;
	} else {
		if(selectedFiles){
			
			if (selectedFiles.files.length == 0) {
				alert("Select one file to upload.");
			} else {
				var totalfilesize = totalfilesize + selectedFiles.files[0].size;
				
				if(totalfilesize >= 1572864)//Bytes value for 1.5mb = 1572864
				{
					alert('File size exceeds the limit. It should be not greater than 1.5mb');
					document.getElementById(fileid).value=null;
					document.getElementById(fileid).focus();	
					return false;
				}
				else{
					order_copy_counts = order_copy_counts + 1;
					return true;	
				}
						
			}
		}
	}
	
}

function filetypevalidation(fileid,pet_id){
	var selectedFiles = document.getElementById(fileid);
	var pet = document.getElementById(pet_id).value;
	if (pet != "") {
		if(selectedFiles){
			if (selectedFiles.files.length == 0) {
				alert("Select one file to upload.");
				return false;
			} else {
				for(var i =0; i<selectedFiles.files.length; i++)
				{
					var filename = selectedFiles.files[i].name;
					
					 
					var fileSplit = filename.split('.');
					//alert (fileSplit.length); 
					var fileExt = '';
					if (fileSplit.length > 2) 
					{
					 alert ('Filename not correct');
					 document.getElementById(fileid).value=null;
					 document.getElementById(fileid).focus();	
					 return false;
					
					} 
					else 
					{
					
					var ext=filename.substring(filename.lastIndexOf('.')+1);
					validateFileExtension(ext,filename,fileid);
					}				
				}	
			}
		}
	}
	
	
	//return;
}
function validateFileExtension(fld,fn,fileid) {
	if(fld!="")
	{
	if(fld == 'pdf' || fld == 'jpeg' || fld =='jpg' ) {
		return true;
	}else{
		alert("Invalid file type of "+fn +".");
		document.getElementById(fileid).value=null;
		document.getElementById(fileid).focus();
		return false;

	}
	}
}

function toggleButtons(myRadio) {
	if (myRadio.value=='yes') {
		document.getElementById('view').style.display='none';
		document.getElementById('update').style.display='';
		document.getElementById('delete').style.display='';
	} else {
		document.getElementById('view').style.display='';
		document.getElementById('update').style.display='none';
		document.getElementById('delete').style.display='none';
	}

}
function deleteSeleted() {
	var p1 = $("#petition_no1").val();
	var p2 = $("#petition_no2").val();
	var p3 = $("#petition_no3").val();
	
	if (p1 != '' || p2 != '' || p3 != '') {
		var confirm = window.confirm("This action will remove uploaded documents! Do you want to continue?");
		if (confirm) {
			var param = "mode=p_delete_uploaded"+"&p1="+p1+"&p2="+p2+"&p3="+p3;	
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
					var count = $(xml).find('count').eq(i).text();
					if (count == 1) {
						alert("Failed to delete the documents");					
					} else {
						alert("Selected Documents are deleted");
					}
					i = 0;
					$("#petition_no1").val("");
					$("#petition_no2").val("");
					$("#petition_no3").val("");
					p2_loadGrid(1, $('#p2_pageSize').val());
				},  
				error: function(e){  
					//alert('Error: ' + e);  
				}
			});	
		} else {
			return false;
		}
	} else {
		alert("Select atleast one petition to delete the document");
		return false;
	}
}

function alert_pet2(){
	pet_no=$('#petition_no2').val();
	if(pet_no==''){
		alert('Please Search and select the Petition');
		return false;
	}
}
function alert_pet1(){
	pet_no=$('#petition_no1').val();
	if(pet_no==''){
		alert('Please Search and select the Petition');
		return false;
	}
}
function alert_pet3(){
	pet_no=$('#petition_no3').val();
	if(pet_no==''){
		alert('Please Search and select the Petition');
		return false;
	}
}

</script>
<?php
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_ackmnt.php') order by ordering";
$result = $db->query($query);
while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
$userid = $_SESSION['USER_ID_PK'];	
$qry = "update usr_dept_users set otp=null where dept_user_id=".$userid;
$result = $db->query($qry);
?>
<?php if($_POST['hid']=="") { ?>
<form name="ackmnt" id="ackmnt"  enctype="multipart/form-data" action="" method="post" style="background-color:#F4CBCB;">

<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[36];//Upload Order/Certificate ?></div></div></div> 	
	<div class="contentMainDiv" style="width:98%;margin:auto;">
	 <div class="contentDiv"> 
           <div id="alrtmsg" style="color:#FF0000; font-size:18px" align="center"> 
			<?PHP 
			if($_REQUEST['msg']=='incorrect'){
			echo 'Enter Valid Petition Number !';
			echo "<br>"."<br>";
			}
			?>
			</div>
	   <div class="div0" id="main1" role="main">
		<table class="formTbl" id="upload_tab" style="display:none;">
		<tbody>
  
		<tr><td><span class="star"  style="float:right;"> * <?PHP echo 'Max upload size for each Petition No is 1.5 MB. Only PDF or JPEG should be uploaded.'; //Indicates Mandatory?></span>
			</td></tr>
			
		<tr>
		
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 1 :"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;&nbsp;
		<input type="text" name="petition_no1" id="petition_no1" value="" maxlength="25" onKeyPress="return checkPetNo(event);" onclick="alert_pet1();" readOnly />
      <input type="file" name="files1" id="files1" multiple="multiple" 
	  onchange="filesizevalidation(this.id,'petition_no1');filetypevalidation(this.id,'petition_no1');" accept="image/jpeg"  disabled />		
       	</td>
		</tr>
		<tr>
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 2 :"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;&nbsp;
		<input type="text" name="petition_no2" id="petition_no2" value="" maxlength="25" onKeyPress="return checkPetNo(event);" onclick="alert_pet2();" readOnly /> 
		<input type="file" name="files2" id="files2" multiple="multiple" onchange="filesizevalidation(this.id,'petition_no2');filetypevalidation(this.id,'petition_no2');" accept="image/jpeg" data_valid='no' data-error="Please select a document to upload." disabled />
        </td>
		</tr>
		<tr>
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 3 :"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;&nbsp;
		<input type="text" name="petition_no3" id="petition_no3" value="" maxlength="25" onKeyPress="return checkPetNo(event);" onclick="alert_pet3();" readOnly />
	
		<input type="file" name="files3" id="files3" multiple="multiple" onchange="filesizevalidation(this.id,'petition_no3');filetypevalidation(this.id,'petition_no3');" accept="image/jpeg" data_valid='no' data-error="Please select a document to upload." disabled />
        </td>		
		</tr>
	 
		<tr>
            <td colspan="2" class="btn" >
            <input type="button" name="view" id="view" value="<?PHP echo $label_name[35];; //Upload. ?>"  onClick="return uploadOrderCopies('save');"/> 
            <input type="button" name="view" id="update" value="<?PHP echo 'Update'; //Upload. ?>"  style="display:none;" onClick="return uploadOrderCopies('update');" disabled />           
            <input type="button" name="view" id="delete" value="<?PHP echo 'Delete'; //Upload. ?>"  style="display:none;" onClick="return deleteSeleted();" disabled />           
			<input type="button" name="view" id="clear" value="<?PHP echo $label_name[27]; ?>"  onClick="return clearAll();"/>

			
            <input type="hidden" name="ackmnt_hid" id="ackmnt_hid">
			<input type="hidden" name="actiontaken" id="actiontaken">
			<input type="hidden" name="otp1" id="otp1">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['USER_ID_PK'];?>">
			
			<?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            </td>
        </tr>
        
		</tbody>
		</table>
		
					</div>
		<table class="existRecTbl">
      <thead>
      <tr colspan='5'>
      <th colspan='4' align='center' style='text-align:center;'><?PHP echo $label_name[32];//Petition Details?></th>
      <!--<th><?//PHP echo $label_name[11];//Existing Details?></th>-->
          <th><?PHP echo 'Page Size';//Page&nbsp;Size?>
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
	  
	<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tbody>
	  
	  <tr>
	  <th style="width:17%;"><?PHP echo $label_name[28];//Petition Period?></th>
	  <th style="width:17%;"><?PHP echo $label_name[38];//Processing Period?></th>
	  <th style="width:17%;"><?PHP echo $label_name[39];//Source?></th>
	  <th style="width:17%;"><?PHP echo $label_name[40];//Department?></th>
	  <th style="width:17%;"><?PHP echo $label_name[41];//Petition Main Category?></th>
	  </tr>
	  
      <tr>
      <td class="from_to_dt">
        <?PHP echo $label_name[42];//From?>
          <input type="text" name="p_from_pet_date" id="p_from_pet_date" maxlength="12" style="width: 90px;"  
          onchange="return validatedate(p_from_pet_date,'p_from_pet_date'); "/>
          <?PHP echo $label_name[43];//To?>
          <input type="text" name="p_to_pet_date" id="p_to_pet_date" maxlength="12" 
          style="width: 90px;" onchange="return validatedate(p_to_pet_date,'p_to_pet_date'); "/>
      </td>
      <td class="from_to_dt">
      <?PHP echo $label_name[42];//From?>
          <input type="text" name="p_from_pet_act_date" id="p_from_pet_act_date" maxlength="12" style="width: 90px;" 
          onchange="return validatedate(p_from_pet_act_date,'p_from_pet_act_date'); "/>
          <?PHP echo $label_name[43];//To?>
          <input type="text" name="p_to_pet_act_date" id="p_to_pet_act_date" maxlength="12"
          style="width: 90px;" onchange="return validatedate(p_to_pet_act_date,'p_to_pet_act_date'); "/>
      </td>
      <td>
          <select name="p_source" id="p_source" style="width:240px;">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Source"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
          $query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";
		  
          $result = $db->query($query);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
						echo "<option value='$row[source_id]'>$row[source_name]</option>";
						}else{
						echo "<option value='$row[source_id]'>$row[source_tname]</option>";	
						}
          }
          ?>
          </select>
      </td>
      	  
	  <td>
          <select name="dept" id="dept" style="width:240px;">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Department"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
          $dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
						FROM usr_dept where dept_id>0 ORDER BY dept_name";
		  if ($userProfile->getOff_level_id() == 1) { 					
					$dept_sql ="SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 } else if($userProfile->getDept_coordinating()&& $userProfile->getOff_coordinating()) {
					//01/11/2017  Registration Dept Officials to be moved from Miscellaneous Dept to IGR Dept(ID = 12) and 
					//the condition 'and dept_id<12' is to be deleted later
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept where dept_id>0 and dept_id<12 ORDER BY dept_name";					
			 } else  {
					$dept_sql = "SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
					FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name";
			 }				
				$res = $db->query($dept_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					if($_SESSION["lang"]=='E'){
						$dept_name=$row['dept_name'];
					}else{
						$dept_name=$row['dept_tname'];	
					}
						
					
					echo "<option value='".$row['dept_id']."'>$dept_name</option>";	
				}
          ?>
          </select>
      </td>
	  
	  <td>
          <select name="gtype" id="gtype" style="width:240px;">
		  <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Category"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
						griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
						dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";	
			
			$gre_rs=$db->query($gre_sql);
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH)) {
				$grename=$row["griev_type_name"];
				$gretname = $row["griev_type_tname"];
				if($_SESSION["lang"]=='E'){
				$gre_name=$grename;
				}else{
				$gre_name=$gretname;	
				}
				print("<option value='".$row["griev_type_id"]."' >".$gre_name."</option>");	
			}
          ?>
          </select>
      </td>
	  
      </tr>
	  
	  <tr style="display:none;">
	  <th style="width:14%;"><?PHP echo $label_name[45];//Petition Period?></th>
	  <th style="width:14%;"><?PHP echo $label_name[46];//Processing Period?></th>
	  <th colspan="4"><?PHP //echo $label_name[43];//Petition Type?></th>
	  </tr>
	  <tr style="display:none;">
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
	 <td>	
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
	  <td colspan="4">
	  </td>
	  </tr>
	  
	  <tr>
      <td colspan="6">
         <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[26]; //Search ?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[27]; //Clear ?>" class="button"/>
      </td>
      </tr>
	  <tr>
	  <td colspan="6">
	  <b><?PHP echo $label_name[37];;//Order/Certificate?> :</b>
	  <input name="upload" id="uploadyn" type="radio" value="no" checked="checked" onclick="toggleButtons(this);"/> <?PHP echo 'Yet to be uploaded';//Yet to be uploaded?>
	  &nbsp;&nbsp;&nbsp;&nbsp;
	  <input name="upload" id="uploadyn" type="radio" value="yes" onclick="toggleButtons(this);"/> <?PHP echo 'Already uploaded';//Already uploaded?>
	  
	  </td>
	  </tr>
      </tbody>
      </table>
	 
	<table class="gridTbl">
      <thead>
      <tr>
      <th style="width:3%;"><?PHP echo $label_name[33]; //Select ?></th>
      <th style="width:15%;"><?PHP echo $label_name[6]; //Petition No and Date ?></th>
      <th style="width:13%;"><?PHP echo $label_name[19]; //Address ?></th>
      <th style="width:13%;"><?PHP echo $label_name[7].' & '.$label_name[34]; //Source ?></th>     
     <th style="width:18%;"><?PHP echo $label_name[32]; //Grievance ?></th>
	 <th style="width:14%;"><?PHP echo $label_name[10]; //Grievance Type ?>, <?PHP echo $label_name[11]; //Sub Type ?>, <?PHP echo $label_name[19]; //Address ?></th>
	 <th style="width:22%;"><?PHP echo $label_name[44]; //Last Action Type, Date & Remarks ?></th>	 
      
      
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
      <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
      <input type="hidden" name="petition_id" id="petition_id" />
      <input type="hidden" name="uploadaction" id="uploadaction" />
	  <input type="hidden" name="uploaded" id="uploaded" />
	  			<input type="hidden" name="hid" id="hid" />

      </tbody>
      </table>
	  
		</div>
</form>
<?php } ?>
<?php
$upaction=$_POST['uploadaction'];
if ($_POST['hid']=='done') {
$valid_formats = array("pdf","jpg","jpeg");
$max_file_size = 1572864; //in Bytes which is 1.5 mb
	
$petition_no1=stripQuotes(killChars($_POST['petition_no1']));
$file_name1 = $_FILES['files1']['name'];
$file_size1 = $_FILES['files1']['size'];
$file_tmp1 = $_FILES['files1']['tmp_name'];
$file_type1 = $_FILES['files1']['type'];

$petition_no2=stripQuotes(killChars($_POST['petition_no2']));
$file_name2 = $_FILES['files2']['name'];
$file_size2 = $_FILES['files2']['size'];
$file_tmp2 = $_FILES['files2']['tmp_name'];
$file_type2 = $_FILES['files2']['type'];

$petition_no3=stripQuotes(killChars($_POST['petition_no3']));
$file_name3 = $_FILES['files3']['name'];
$file_size3 = $_FILES['files3']['size'];
$file_tmp3 = $_FILES['files3']['tmp_name'];
$file_type3 = $_FILES['files3']['type'];

if ($file_name1!= "" && $file_name2!="" && $file_name3!="") {
	
	$data['xml']='
	<Data>
	<pet_no1>'.$petition_no1.'</pet_no1>
	<document_name1>'.$file_name1.'</document_name1>
	<document_tmp_name1>'.$file_tmp1.'</document_tmp_name1>
	<document_size1>'.$file_size1.'</document_size1>
	<document_type1>'.$file_type1.'</document_type1>	
	<pet_no2>'.$petition_no2.'</pet_no2>
	<document_name2>'.$file_name2.'</document_name2>
	<document_tmp_name2>'.$file_tmp2.'</document_tmp_name2>
	<document_size2>'.$file_size2.'</document_size2>
	<document_type2>'.$file_type2.'</document_type2>	
	<pet_no3>'.$petition_no3.'</pet_no3>
	<document_name3>'.$file_name3.'</document_name3>
	<document_tmp_name3>'.$file_tmp3.'</document_tmp_name3>
	<document_size3>'.$file_size3.'</document_size3>
	<document_type3>'.$file_type3.'</document_type3>	
	<user_id>'.$_SESSION['USER_ID_PK'].'</user_id>
	<upaction>'.$upaction.'</upaction>
	
	</Data>
	';
} else if ($file_name1!= "" && $file_name2!="") {
	
	$data['xml']='
		<Data>
		<pet_no1>'.$petition_no1.'</pet_no1>
	<document_name1>'.$file_name1.'</document_name1>
	<document_tmp_name1>'.$file_tmp1.'</document_tmp_name1>
	<document_size1>'.$file_size1.'</document_size1>
	<document_type1>'.$file_type1.'</document_type1>	
	<pet_no2>'.$petition_no2.'</pet_no2>
	<document_name2>'.$file_name2.'</document_name2>
	<document_tmp_name2>'.$file_tmp2.'</document_tmp_name2>
	<document_size2>'.$file_size2.'</document_size2>
	<document_type2>'.$file_type2.'</document_type2>		
		<user_id>'.$_SESSION['USER_ID_PK'].'</user_id>
		<upaction>'.$upaction.'</upaction>
		</Data>
		';
} else if ($file_name1!= "") {
	
	$data['xml']='
		<Data>
		<pet_no1>'.$petition_no1.'</pet_no1>
	<document_name1>'.$file_name1.'</document_name1>
	<document_tmp_name1>'.$file_tmp1.'</document_tmp_name1>
	<document_size1>'.$file_size1.'</document_size1>
	<document_type1>'.$file_type1.'</document_type1>
		<user_id>'.$_SESSION['USER_ID_PK'].'</user_id>
		<upaction>'.$upaction.'</upaction>
		</Data>
		';
}
$ipaddress = $_SERVER['SERVER_ADDR'];
$ippart = explode('/',$_SERVER['REQUEST_URI']);

if ($ippart[1] == 'pm_upload_order_cert.php'){
	$url = 'http://localhost/pm_upload_order_cert_action.php';
}
else {
	$url = 'http://localhost/'.$ippart[1].'/pm_upload_order_cert_action.php';
}

if ($ippart[1] == 'pm_upload_order_cert.php'){
	$url = 'https://locahost/police/ps/pm_upload_order_cert_action.php';
}
else {
	$url = 'https://locahost/police/ps/psppp/pm_upload_order_cert_action.php';
}
$url = 'http://localhost/police/ps/pm_upload_order_cert_action.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec ($ch);
print_r($result);

 } ?>
<?php
include('footer.php');
?>
