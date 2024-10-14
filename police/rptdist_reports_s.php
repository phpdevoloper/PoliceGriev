<?php
ob_start();
session_start();
$pagetitle="MIS Reports";
include("db.php"); 
include("header_menu.php");
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
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
include("common_date_fun.php");
include("pm_common_js_css.php");
 
$hidsubstr=stripQuotes(killChars($_POST['hid']));
?>
<style>
a:hover {
    color: #772222 !important;
}
</style>
<script type="text/javascript">

function valchk()
{
document.getElementById("alrtmsg").innerHTML="";
 document.getElementById("alrtmsg").style.display='block'; 
var validateFlg = false;
    $(".divTable input[type='text'] , .divTable select, .divTable textarea").each(function( index ) {
	$(this).removeClass('error');
	if($(this).attr('data_valid')!='no')
	{
		if($.trim($(this).val())=='')
		{
			$(this).focus().addClass('error');
			$("#alrtmsg").html($(this).attr('data-error'));
			$(this).focus();
			validateFlg = false;
			return false;
		} 
        else
        {
            $(this).removeClass('error');
            validateFlg = true;
        }
	}
	
	});
	
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
	//keywords = replaceAll(keywords, '~!@#$%^&*()-_=+[{]}\|;:<.>/?\'', '');
	keywords = keywords.replace(/[,;:~!@#$%^&*-_=+|<.>?{}\\\/]/g, ' ');
	keywords = keywords.replace(/\s+/g, '**');
	if (keywords.endsWith("**")) {
	  keywords = keywords.substring(0, keywords.length - 2);
	}
	if (keywords.startsWith("**")) {
	  keywords = keywords.substring(2, keywords.length);
	}
	return keywords;
}

function chk_form()
{
	 var fromdt=document.getElementById("from_date").value;
 	 var todt=document.getElementById("to_date").value;
     var off_head = document.getElementById("off_head").value;
	 var disposing_officer = '';
	 if (off_head != '') {
		 disposing_officer = document.getElementById("disposing_officer").value
	 }
	 if(fromdt=="")
	{
		alert("Select Any From Date");
		document.getElementById("from_date").focus();
		return false; 
	}
	if(todt=="")
	{
		alert("Select Any To Date");
		document.getElementById("to_date").focus();
		return false; 
	}
	
	frDateArray =  fromdt.split("/");
	toDateArray =  todt.split("/");
	fromDate = new Date(frDateArray[2],frDateArray[1],frDateArray[0]);
	toDate = new Date(toDateArray[2],toDateArray[1],toDateArray[0]);
	
	if (fromDate > toDate) {
		alert("From date can not be greater than To date");
		return false;
	} 
	document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val().replace(/  +/g, ' '));
	//document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val());
	
	if (document.getElementById("delegated_petition").checked == true) {
		document.getElementById("delagated").value = 'true';			
	} else {
		document.getElementById("delagated").value = 'false';
	}
	var rep_type = $('input[name=dist_rpt]:checked', '#rpt_abstract').val();
	if (off_head != '' ) {
		var disp_officer = document.getElementById("disposing_officer").value;
		if (disp_officer == '' && rep_type != 'disposing_officerwise') {
			alert("Select Any one Initiating Officer"); 
			return false;
		}
	} 
	 if($('input[name=dist_rpt]:checked', '#rpt_abstract').length==0)
	 {
	 alert("Select Any one Report");
	 return false;
	 } else {
		document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
		document.rpt_abstract.target= "_blank";
		document.rpt_abstract.submit();
		return true; 
	 }
	/*  else
	 { */
		/*
		if (document.getElementById("include_sub").checked == true) {
			document.getElementById("include_sub_office").value = 'true';			
		} else {
			document.getElementById("include_sub_office").value = 'false';
		}
		*/
		/* if (document.getElementById("delegated_petition").checked == true) {
			document.getElementById("delagated").value = 'true';			
		} else {
			document.getElementById("delagated").value = 'false';
		} */
		/* if (off_head != '' ) {
			//alert($('input[name=dist_rpt]:checked', '#rpt_abstract').val()); //delegated_petition
				if (disposing_officer == '' 
				&& (($('input[name=dist_rpt]:checked', '#rpt_abstract').val() != 'disposing_officerwise') 
				&& ($('input[name=dist_rpt]:checked', '#rpt_abstract').val() != 'notyetforwardedonline')
				&& ($('input[name=dist_rpt]:checked', '#rpt_abstract').val() != 'counter_petition_not_forwraded')
				)) {	//notyetforwardedonline				
					alert('Select a Disposing Officer');
					return false;
				} else {
					var disp_officer = document.getElementById("disposing_officer");
//					document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val().replace(/  +/g, ' '));
					document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val());
					
					//alert(document.getElementById("disp_officer_instruction").value);
					var disp_officer_name = disp_officer.options[disp_officer.selectedIndex].text;
					document.getElementById("disp_officer_name").value = disp_officer_name;
					document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
					document.rpt_abstract.target= "_blank";
					document.rpt_abstract.submit();
					return true;
					
				}
		} else { */
			
//			document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val().replace(/\s+/g, ' '));
			/* var rep_val = $('input[name=dist_rpt]:checked', '#rpt_abstract').val(); //special_grievance_day
			//var villagewise_val = $('input[name=dist_rpt]:checked', '#rpt_abstract').val(); //special_grievance_day
			document.getElementById("disp_officer_instruction").value = replaceString($('#instructions').val());
			if (rep_val == 'special_grievance_day' || rep_val == 'revenue_villagewise' || rep_val == 'special_grievance_statistics' || rep_val == 'special_grievance_statistics_grievancetypewise' || rep_val == 'actual_delivery_status' || rep_val == 'actual_delivery_status_state') { //actual_delivery_status_state
				var gsrc = document.getElementById("gsrc").value;
				if (gsrc == '') {
					alert("Please select the concerned Greivance Source using Additional Parameters");
					return false;
				} else {
					document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
					document.rpt_abstract.target= "_blank";
					document.rpt_abstract.submit();
					return true;	
				}
			
			} else {
				document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
				document.rpt_abstract.target= "_blank";
				document.rpt_abstract.submit();
				return true;
			} */
			

		//}		
	 //}
	 
}

function clear_cnt()
{
	document.getElementById("from_date").value=' ';
	document.getElementById("to_date").value=' ';
	document.getElementById("div_content1").style.display='none';
}

function validatedate(inputText,elementid){
	var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
	if(inputText.value.match(dateformat)){  
		document.profile.inputText.focus(); 
		var opera1 = inputText.value.split('/');  
		var opera2 = inputText.value.split('-');  
		lopera1 = opera1.length;  
		lopera2 = opera2.length; 
		if (lopera1>1) {  
			var pdate = inputText.value.split('/');  
		} else if (lopera2>1) {  
			var pdate = inputText.value.split('-');  
		}  
		var mm  = parseInt(pdate[0]);  
		var dd = parseInt(pdate[1]);  
		var yy = parseInt(pdate[2]);  

		var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];  
		if (mm==1 || mm>2){  
			if (dd>ListofDays[mm-1])  
			{  
				alert('Invalid date format!');  
				return false;  
			}  
		}  
		if (mm==2) {  
			var lyear = false;  
			if ( (!(yy % 4) && yy % 100) || !(yy % 400)){  
				lyear = true;  
			}  
			if ((lyear==false) && (dd>=29)){  
				alert('Invalid date format!');  
				return false;  
			}  
			if ((lyear==true) && (dd>29)) {  
				alert('Invalid date format!');  
				return false;  
			}  
		}  
	} else {  
		alert("Invalid date format!");  
		document.getElementById(elementid).value=""; 
		document.getElementById(elementid).focus(); 
		return false;  
	}  
}
function chkAdditional() {
	if(document.getElementById("additional").checked == true)      
	{      
		document.getElementById("addtional_row1").style.display="";
		document.getElementById("addtional_row2").style.display="";
		/* document.getElementById("addtional_row3").style.display=""; */
		document.getElementById("addtional_row4").style.display="";
		document.getElementById("addtional_row5").style.display="";

	} else {
		document.getElementById("addtional_row1").style.display="none";
		document.getElementById("addtional_row2").style.display="none";
		/* document.getElementById("addtional_row3").style.display="none"; */
		document.getElementById("addtional_row4").style.display="none";
		document.getElementById("addtional_row5").style.display="none";
		document.getElementById("delegated_petition").checked=false;
	}
	clearFormFields();
}
function reportsOnDelegated() {
	var delg = document.getElementById("delegated_petition").checked;
	if (delg == true) {
		//document.getElementById("row_5").style.display="none";
		document.getElementById("row_6").style.display="none";
		document.getElementById("row_6_1").style.display="none";
		document.getElementById("row_7").style.display="none";
		document.getElementById("row_8").style.display="none";
		document.getElementById("rpt_processing_period_1").style.display="none";
		document.getElementById("ord_upload_2_1").style.display="none";
		document.getElementById("ord_upload_2_2").style.display="none";
		document.getElementById("rpts_without_params_1").style.display="none";
		document.getElementById("rpts_without_params_2").style.display="none";
		document.getElementById("disp_petition_2").colSpan="3";
		document.getElementById("disp_petition_2").colSpan="3";
		document.getElementById("include_sub").disabled=true;
	} else {
		//document.getElementById("row_5").style.display="";
		document.getElementById("row_6").style.display="";
		document.getElementById("row_6_1").style.display="";
		document.getElementById("row_7").style.display="";
		document.getElementById("row_8").style.display="";
		document.getElementById("rpt_processing_period_1").style.display="";
		document.getElementById("ord_upload_2_1").style.display="";
		document.getElementById("ord_upload_2_2").style.display="";
		document.getElementById("disp_petition_2").colSpan="1";
		document.getElementById("rpts_without_params_1").style.display="";
		document.getElementById("rpts_without_params_2").style.display="";
		document.getElementById("disp_petition_2").colSpan="1";
		document.getElementById("include_sub").disabled=false;
	}
}
function clearFormFields() {
	document.getElementById("gsrc").options.selectedIndex = "";
	document.getElementById("gsubsrc").disabled = false;
	document.getElementById("gsubsrc").options.length = 1;
	document.getElementById("gtype").options.selectedIndex = "";
	document.getElementById("gsubtype").options.length = 1;
	if (document.getElementById("off_level_id").value == 2) {
		document.getElementById("grie_dept_id").options.selectedIndex = "";	
	}
	document.getElementById("pet_community").options.selectedIndex = "";	
	document.getElementById("special_category").options.selectedIndex = "";
	document.getElementById("instructions").value = "";
}
 var more='';
function showMoreReports() {
	if (more=='') {
		document.getElementById("head_more_report").style.display='';
		document.getElementById("row_3").style.display='';
		document.getElementById("row_4").style.display='';
		//document.getElementById("row_5").style.display='';
		document.getElementById("more_less").innerHTML='Less';
		more=true;
	} else {
		document.getElementById("head_more_report").style.display='none';
		document.getElementById("row_3").style.display='none';
		document.getElementById("row_4").style.display='none';
		//document.getElementById("row_5").style.display='none';
		document.getElementById("more_less").innerHTML='More';
		more='';
	}
	
}
</script>
 

<?php 
 $flag = true;
if(!$flag){
	header('HTTP/1.0 401 Unauthorized');
	include("com/access_denied.php");
	die();
} 

$from_date=stripQuotes(killChars($_POST["from_date"]));
$to_date=stripQuotes(killChars($_POST["to_date"]));

?>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rptdist_reports.php') order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}	
}

?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<?php
	if($_SESSION['lang']=='E'){
		$heading = $label_name[36]." - ".$userProfile->getOff_level_name().' Petitions';	
	}else{
		$heading = $label_name[36]." - ". substr($userProfile->getOff_level_name(),0, strlen($userProfile->getOff_level_name())-6).' மனுக்கள் மட்டும்';
	}
?>	
<div class="form_heading">
<div class="heading">
       <?PHP echo $heading;//MIS Reports - Simple Interface?>
</div>
</div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv"> 
	<table class="formTbl" >
	<tbody>
	
	
	</tbody>
	</table>
 
	<table class="formTbl" >
	<tbody>
    <tr id="alrtmsg" style="display:none;" align="center" ><td  colspan="4">&nbsp;</td></tr>	
	
	<tr>
	<td colspan="4" style="text-align:center">
	<b><?PHP echo $label_name[48];//Petition Period?>&nbsp;:</b>&nbsp;
	<?PHP echo $label_name[1];//From Date?>
	<input type="text" name="from_date" id="from_date" value="<?php echo $frdate; ?>"  data_valid='yes' 
	class="select_style" style="width:120px;" data-error="Select From Date" onchange="return validatedate(from_date,'from_date');" maxlength="12"/>
	&nbsp;<?PHP echo $label_name[2];//To Date?>
	<input type="text" name="to_date" id="to_date" value="<?php echo $todate; ?>"  data_valid='yes' class="select_style" style="width:120px;"
	data-error="Select To Date" onchange="return validatedate(to_date,'to_date');" maxlength="12"/>
	&nbsp;
	<span>

	<b><?PHP echo $label_name[91];//Processing Period?>&nbsp;:</b>&nbsp;&nbsp;
	<?PHP echo $label_name[1];//From Date?>
	<input type="text" name="p_from_date" id="p_from_date" value="<?php echo $frdate; ?>"  data_valid='yes' 
	class="select_style" style="width:120px;" data-error="Select From Date" onchange="return validatedate(from_date,'from_date');" maxlength="12"/>
	&nbsp;<?PHP echo $label_name[2];//To Date?>
	<input type="text" name="p_to_date" id="p_to_date" value="<?php echo $todate; ?>"  data_valid='yes' class="select_style" style="width:120px;"
	data-error="Select To Date" onchange="return validatedate(to_date,'to_date');" maxlength="12"/>
	&nbsp;
	<span>

	<input type="checkbox" name="additional" id="additional" onclick = "chkAdditional()" >
	<b><?PHP echo $label_name[52]; //Additional Parameters?><b>
	<!--img src="images/new.gif" /-->
	</span>
	</td>
	</tr>
   
	<!-- Source and Subsource Begins here-->   
	<tr id="addtional_row1" style="display:none;">
	<td><b><?PHP echo  $label_name[15];?></b></td>
	<td>	
	<select name="gsrc" id="gsrc">
	<option value="">-- Select Source --</option>
    <?php 
	

		$sql="SELECT a.source_id, b.source_name, b.source_tname FROM usr_dept_off_level_sources a
		JOIN lkp_pet_source b ON b.source_id = a.source_id
		WHERE a.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and coalesce(b.enabling,true)
		and ((b.open_fr_date is null and b.open_to_date is null) or ((now()>=b.open_fr_date and now()<=b.open_to_date)))
		order by b.source_id" ;
		
		
		$rs = $db->query($sql);	
		while($row = $rs->fetch(PDO::FETCH_BOTH)) {
			$sourcename=$row["source_name"];
			$sourcetname = $row["source_tname"];
			if($_SESSION["lang"]=='E'){
			$source_name=$sourcename;
			}else{
			$source_name=$sourcetname;	
			}
			print("<option value='".$row["source_id"]."' >".$source_name."</option>");	
		}
	?>
  </select>
  </td>
  <td><b><?PHP echo $label_name[17];?></b></td>
  <td>	
  <select name="gtype" id="gtype">
 	<option value="">-- Select Category --</option>
    <?php 
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
   <!-- Source abd Subsource Ends here-->   
   
   
   <!-- Greivance type abd Greivance Subtype Begins here-->   
  <tr id="addtional_row2" style="display:none;">
  <td><b><?PHP echo $label_name[18]; ?></b></td>
  <td>
  <select name="gsubtype" id="gsubtype">
 	 <option value="">-- Select Petition Sub Category --</option> 
  </select>
   </td>

<td><b><?PHP echo $label_name[70]; // Petition Type?></b></td>
		<td>	
			<select name="petition_type" id="petition_type">
			<option value="">-- Select Petition Type --</option>
			<?php
				$pet_type_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type";
				$result = $db->query($pet_type_sql);
				$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray as $row){
				//echo "<option value='$row[source_id]'>$row[source_name]</option>";
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
  
  
	<tr id="addtional_row3" style="display:none;">
		
		<td><b><?PHP echo $label_name[70]; // Petition Type?></b></td>
		<td>	
			<select name="petition_type" id="petition_type">
			<option value="">-- Select Petition Type --</option>
			<?php
				$pet_type_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type";
				$result = $db->query($pet_type_sql);
				$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray as $row){
				//echo "<option value='$row[source_id]'>$row[source_name]</option>";
					if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_name']."</option>";
					}else{
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_tname']."</option>";	
					}
				}
			?>
			</select>
		</td>

<td><b><?PHP echo $label_name[109]; // Community?></b></td>
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
	</tr> 
  
	<!-- Introducing new search parameters Community and Special Category on  03/12/2018-->
	<tr id="addtional_row4" style="display:none;">
		<!--<td><b><?PHP echo $label_name[110]; // Special Category?></b></td>
		<td>	
			<select name="special_category" id="special_category">
			<option value="">-- Select Special Category --</option>
			<?php
				/* $petitioner_category_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category order by petitioner_category_id";
				$petitioner_category_rs=$db->query($petitioner_category_sql);
				while($petitioner_category_row = $petitioner_category_rs->fetch(PDO::FETCH_BOTH))
				{
					/*$petcommunityname=$gen_row["pet_community_name"];
					$gentname=$gen_row["pet_community_name"];*/
					/* if($_SESSION["lang"]=='E')
					{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_name"];
					}else{
						$petitioner_category_name=$petitioner_category_row["petitioner_category_tname"];
					}
					print("<option value='".$petitioner_category_row["petitioner_category_id"]."' >".$petitioner_category_name."</option>"); */

				//} */
			?>
			</select>
		</td>-->	
		
		<td><b>Delegated Petitions</b>
	</td>
	<td colspan="3">
	<span id="delg" name="delg" <?php //echo ($userProfile->getOff_level_id() == 7) ? "": "style='display:none;'"?>>
	<input type="checkbox" name="delegated_petition" id="delegated_petition">
	</td>
	
	</tr> 
	
	<tr id="addtional_row5" style="display:none;">
	<td><b>
	<?php echo $label_name[111];// Key words in the Instructions of Disposing Officer;?></b>
	</td>
	<td colspan="3">
	<input type="text" name="instructions" id="instructions" style="width: 800px;"/>
	&nbsp;&nbsp;<span class="star">*</span><b><?php echo $label_name[112];// Not applicable to some reports below.;?></b>
	</tr>

	
	<?php 
		$off_head_sql = "select dept_desig_id,off_head,dept_desig_role_id from usr_dept_desig where dept_desig_id=".$userProfile->getDept_desig_id()."";
		$result=$db->query($off_head_sql);				
		$rowArray = $result->fetch(PDO::FETCH_BOTH);
		$off_head = $rowArray["off_head"];
		$dept_desig_id = $rowArray["dept_desig_id"];
		$dept_desig_role_id = $rowArray["dept_desig_id"];
				
	?>
	<?php if ($off_head != '' || ($userProfile->getDesig_roleid() == 5 && $userProfile->getOff_level_id())) { ?>
	<tr id="off_head_row">
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px;">
		<?php echo $label_name[104]; //Select Disposing Officer?>:<span class="star">*</span>&nbsp;&nbsp;&nbsp;&nbsp;
		<select name="disposing_officer" id="disposing_officer"  class="select_style"> 
        <option value="">-- Select Initiating Officer --</option>
		<?php
			
			if ($userProfile->getOff_level_id() == 1) {
			$disp_off_sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, 
			dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, 
			off_loc_sname, dept_id, off_level_dept_id, off_loc_id from vw_usr_dept_users_v_sup c
			where dept_id = ".$userProfile->getDept_id()." and off_level_id=".$userProfile->getOff_level_id()."  
			and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal and dept_user_id!=".$_SESSION['USER_ID_PK'].
			"and case when not exists (select 1 from usr_dept_desig_disp_sources u1 
			where u1.dept_desig_id=c.dept_desig_id) then true
			when exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id and c.off_loc_id = any(u1.agri_districts)) then true
			else false
			end";
			
			$disp_off_sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, 
			dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, 
			off_loc_sname, dept_id, off_level_dept_id, off_loc_id from vw_usr_dept_users_v_sup c
			where dept_id = ".$userProfile->getDept_id()." and off_level_id=".$userProfile->getOff_level_id()."  
			and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal and case when not exists (select 1 from usr_dept_desig_disp_sources u1 
			where u1.dept_desig_id=c.dept_desig_id) then true
			when exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id and c.off_loc_id = any(u1.agri_districts)) then true
			else false
			end";
			
			$disp_off_sql="select dept_user_id, dept_desig_id, dept_desig_name, dept_desig_tname, 
			dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, 
			off_loc_sname, dept_id, off_level_dept_id, off_loc_id from vw_usr_dept_users_v_sup c
			where dept_id = ".$userProfile->getDept_id()." and off_level_id=".$userProfile->getOff_level_id()."  
			and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal and case when not exists (select 1 from usr_dept_desig_disp_sources u1 
			where u1.dept_desig_id=c.dept_desig_id) then true
			when exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id and c.off_loc_id = any(u1.agri_districts)) then true
			else false
			end and dept_desig_role_id in (2,3)";
			
			} else {			
			$disp_off_sql="select dept_user_id, dept_desig_id, s_dept_desig_id, dept_desig_name, dept_desig_tname, 
			dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, 
			off_loc_sname, dept_id, off_level_dept_id, off_loc_id from vw_usr_dept_users_v_sup c
			where off_level_id=".$userProfile->getOff_level_id()."  
			and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal and dept_user_id!=".$_SESSION['USER_ID_PK'].
			"and case when not exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id) then true
			when exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id and c.off_loc_id = any(u1.agri_districts)) then true
			else false
			end";
			
			$disp_off_sql="select dept_user_id, dept_desig_id,  dept_desig_name, dept_desig_tname, 
			dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, 
			off_loc_sname, dept_id, off_level_dept_id, off_loc_id from vw_usr_dept_users_v_sup c
			where off_level_id=".$userProfile->getOff_level_id()."  and off_level_dept_id=".$userProfile->getOff_level_dept_id()." 	and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal and case when not exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id) then true
			when exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=c.dept_desig_id and c.off_loc_id = any(u1.agri_districts)) then true
			else false
			end and dept_desig_role_id in (2,3)";

			}
			$result=$db->query($disp_off_sql);				
			while($row = $result->fetch(PDO::FETCH_BOTH)) 
			{
				$dept_user_id=$row['dept_user_id'];
				$dept_desig_name=$row['dept_desig_name'].' - '.$row['off_loc_name'];
				print("<option value='".$dept_user_id."' >".$dept_desig_name."</option>");
			}
		?>
		</select>
        </td>
    </tr>
	<?php } ?>
	
	
	<tr>
	<td colspan="4" style="text-align: center; font-weight:bold; font-size: 14px;padding-left: 0px; background-color: #F4CBCB;">
	<?PHP echo $label_name[51];//Reports Based on Petition Period?></td>
	</tr>
	
	<tr id="row_1">
	<td><input name="dist_rpt" id="officerswise_simple_pet_period" type="radio" value="officerswise_simple_pet_period" /></td>
	<td> <?PHP echo $label_name[13];//Officers Wise Report?> </td>	
	<td><input name="dist_rpt" id="grievancetypewise" type="radio" value="grievancetypewise" /></td>
	<td><?PHP echo $label_name[12];//Grievance Typewise Report?>
	<span style="float:right;font-weight:bold;">
	<a id="more_less" style="color:blue;font-weight:bold;font-size:15px;text-decoration:underline;" onClick="showMoreReports();">More</a></span>	
	</td>
	</tr>

	<tr id="head_more_report" style="display:none;">
	<td colspan="4" style="text-align: center; font-weight:bold; font-size: 14px;padding-left: 0px; background-color: #F4CBCB;">
	<?PHP echo 'Additional Reports based on Petition Period' ?>	
	</td>
	</tr>	
 	<tr id="row_3" style="display:none;">	
	<td><input name="dist_rpt" id="details_of_disposed_petitions_for_pet_period" type="radio" 
	value="details_of_disposed_petitions_for_pet_period" /></td>
	<td> <?PHP echo $label_name[86];//Disposed Petitions?> </td>
	<td><input name="dist_rpt" id="repeated_petitions" type="radio" value="repeated_petitions" /></td>
	<td><?PHP echo $label_name[75];//Repeated Petitions?>
	</tr>
	
	<tr id="row_4" name="part_off_reports3" style="display:none;">	
	<td><input name="dist_rpt" id="temp_reply" type="radio" value="temp_reply_not_forwraded" /></td>
	<td> <?PHP echo $label_name[73]; //'Yet to be forwarded';?> </td>

	<td><input name="dist_rpt" id="self_closed" type="radio" value="temp_reply_self_closed" /></td>
	<td> <?PHP echo $label_name[74]; //'Self Closed';?> </td>
	</tr>

		
	<tr id="rpt_process_petition_period">
	<td colspan="4" style="text-align: center; font-weight:bold; font-size: 14px;padding-left: 0px; background-color: #F4CBCB;">
	<?PHP echo $label_name[85]//Reports based on Petition Period and Processing Period ?></td>
	</tr>
	<tr>
	
	<td><input name="dist_rpt" id="pending_with_opening_balance" type="radio" value="pending_with_opening_balance" /></td>
	<td> <?PHP echo $label_name[90];//officers-wise pendency report with opening balance?>  </td>
	
	<td><input name="dist_rpt" id="disposed_petitions" type="radio" value="disposed_petitions" /></td>
	<td id="disp_petition_2"> <?PHP echo $label_name[86];//Disposed Petitions?> </td>
	</tr>
	

	<tr id="rpts_without_params_1" <?php echo (($userProfile->getPet_disposal())||($off_head != '')) ?  "":  "style='display:none;'" ;?>>
	<td colspan="4" style="text-align: center; font-weight:bold; font-size: 14px;padding-left: 0px; background-color: #F4CBCB;">
	<?PHP echo $label_name[105];//Reports without Parameters ?></td>
	</tr>
	<tr id="rpts_without_params_2">
	<?php //} ?>
	
	<td ><input name="dist_rpt" id="dist_rpt" type="radio" value="notyetforwardedonline" /></td>
	<td><font style="color:red;font-weight:bold;"> <?PHP echo $label_name[72];//Online Petitions - Yet to be Forwarded ?></font></td> 
	<td><input name="dist_rpt" id="counter_petition_not_forwraded" type="radio" value="counter_petition_not_forwraded" /></td>
	<td><font style="color:red;font-weight:bold;"> <?PHP echo "Counter Petitions - Yet to be forwarded ";//Pendency Status Related to Disposing Officer;?></font></td>
	</tr>	
	<tr>
	<td><input name="dist_rpt" id="disposing_officerwise" type="radio" value="disposing_officerwise" /></td>
	<td colSpan="3"> <?PHP echo $label_name[106];//Pendency Status Related to Disposing Officer;?>  </td></tr>

	<tr>
	<td colspan="4" class="btn" style="background-color: #FBE5E5;" align="center">
	<?php //if ($userProfile->getOff_level_id() > 7) { ?>
	<span style="float:left;display:none;">
	<a style="color:blue;font-weight: bold;font-size:15px;" href="rptdist_reports_sup.php" onclick=""><?PHP echo $label_name[60];//Superior Office Petitions?></a><!--img src="images/new.gif" /--></span>
	<?php //} ?>
	<input type="button" name="save" id="save" value="<?PHP echo $label_name[10];//View?>" onClick="return chk_form();"  />&nbsp;
	<input type="reset" value="<?PHP echo $label_name[11];//Clear?>" onclick="clear_cnt()" /> 
	<?php if ($userProfile->getPet_act_ret() && $_SESSION['LOGIN_LVL']==NON_BOTTOM){ ?>
	<span style="float:right;">
	<a style="color:blue;font-weight: bold;font-size:15px;" href="rptdist_reports.php" onclick=""><?PHP echo $label_name[58];//Subordinate Office Reports?></a><!--img src="images/new.gif" /--></span>
	<?php } ?>
	<input type="hidden" name="hid" id="hid" />
	<input type="hidden" name="rep_src" id="rep_src" value="simple"/>
	
    <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
	<input type="hidden" name="off_head" id="off_head" value="<?php echo $off_head; ?>"/>
	<input type="hidden" name="disp_officer_name" id="disp_officer_name"/>
	<input type="hidden" name="disp_officer_instruction" id="disp_officer_instruction"/>
	
	<input type="hidden" name="source_from" id="source_from" value="main"/>
    
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $userProfile->getOff_level_id();?>" />
 
	<input type="hidden" name="offlevel_rdodept_idhid" id="offlevel_rdodept_idhid" value="" />
	<input type="hidden" name="offlevel_tlkdept_idhid" id="offlevel_tlkdept_idhid" value="" /> 
	<input type="hidden" name="offlevel_firkadept_idhid" id="offlevel_firkadept_idhid" value="" />
	<input type="hidden" name="offlevel_blockdept_idhid" id="offlevel_blockdept_idhid" value="" />
	<input type="hidden" name="offlevel_urbandept_idhid" id="offlevel_urbandept_idhid" value="" />    
	<input type="hidden" name="offlevel_distdept_idhid" id="offlevel_distdept_idhid" value="" />
  
	<input type="hidden" name="p_offlevel_rdodept_idhid" id="p_offlevel_rdodept_idhid" value="" />
	<input type="hidden" name="p_offlevel_tlkdept_idhid" id="p_offlevel_tlkdept_idhid" value="" /> 
	<input type="hidden" name="p_offlevel_firkadept_idhid" id="p_offlevel_firkadept_idhid" value="" />
	<input type="hidden" name="p_offlevel_blockdept_idhid" id="p_offlevel_blockdept_idhid" value="" />
	<input type="hidden" name="p_offlevel_urbandept_idhid" id="p_offlevel_urbandept_idhid" value="" />    
	<input type="hidden" name="p_offlevel_distdept_idhid" id="p_offlevel_distdept_idhid" value="" />
	
	<input type="hidden" name="h_dist" id="h_dist" value="<?php echo $userProfile->getDistrict_id();?>" />
	
  <!--- AFter particular combo selection -->
	<input type="hidden" name="rdo_offlevel_deptidhid" id="rdo_offlevel_deptidhid" value="" />
	<input type="hidden" name="taluk_offlevel_deptidhid" id="taluk_offlevel_deptidhid" value="" />
	<input type="hidden" name="firka_offlevel_deptidhid" id="firka_offlevel_deptidhid" value="" />
	<input type="hidden" name="include_sub_office" id="include_sub_office" value="false" />
	<input type="hidden" name="delagated" id="delagated" value="false" />
  
	<input type="hidden" name="hid_dept" id="hid_dept" value="<?php echo stripQuotes(killChars($_POST["hid_dept"])); ?>" />
	<input type="hidden" name="hid_dist" id="hid_dist" value="<?php echo stripQuotes(killChars($_POST["hid_dist"])); ?>" />
	<input type="hidden" name="hid_taluk" id="hid_taluk" value="<?php echo stripQuotes(killChars($_POST["hid_taluk"])); ?>" />
	<input type="hidden" name="hid_rdo" id="hid_rdo" value="<?php echo stripQuotes(killChars($_POST["hid_rdo"])); ?>" />
	<input type="hidden" name="hid_firka" id="hid_firka" value="<?php echo stripQuotes(killChars($_POST["hid_firka"])); ?>" />
	<input type="hidden" name="hid_block" id="hid_block" value="<?php echo stripQuotes(killChars($_POST["hid_block"])); ?>" />
	<input type="hidden" name="hid_urban" id="hid_urban" value="<?php echo stripQuotes(killChars($_POST["hid_urban"])); ?>" />   
	<input type="hidden" name="hid_officer" id="hid_officer" value="<?php echo stripQuotes(killChars($_POST["hid_officer"])); ?>" />
    <input type="hidden" name="hid_radio" id="hid_radio" value="<?php echo stripQuotes(killChars($_POST["hid_radio"])); ?>" />
   	
	</td>
	</tr>
	</tbody>
	</table>
</div>
</div>
</form>
<?php include("footer.php"); ?>

<script type="text/javascript">
$(document).ready(function(){
	setDatePicker('from_date');
	setDatePicker('to_date'); 
	setDatePicker('p_from_date'); 
	setDatePicker('p_to_date'); 

	if($('#hid_radio').val() == 'deptwise'){
	 document.getElementById("dist_rpt").checked = 'checked';
	} else if($('#hid_radio').val() == 'grievancetypewise'){
		document.getElementById("grievancetypewise").checked = 'checked';
	} else if($('#hid_radio').val() == 'sourcewise'){
	 document.getElementById("src").checked = 'checked';
	} else if($('#hid_radio').val() == 'deowise'){
	 document.getElementById("deowise").checked = 'checked';
	} else if($('#hid_radio').val() == 'officers_sourcewise'){
	 document.getElementById("officers_sourcewise").checked = 'checked';
	} else if($('#hid_radio').val() == 'officerswise_simple'){
	 document.getElementById("officerswise_simple").checked = 'checked';
	} else if($('#hid_radio').val() == 't_officers_sourcewise'){
	 document.getElementById("t_officers_sourcewise").checked = 'checked';
	} else if($('#hid_radio').val() == 'r_officers_sourcewise'){
	 document.getElementById("r_officers_sourcewise").checked = 'checked';
	}  else if($('#hid_radio').val() == 'cla_officers_sourcewise'){
	 document.getElementById("cla_officers_sourcewise").checked = 'checked';
	} else if($('#hid_radio').val() == 'officerswise_simple_pet_period'){
	 document.getElementById("officerswise_simple_pet_period").checked = 'checked';
	}
		
	$("#gsrc").change(function(){
		var srcval = $("#gsrc").val();
		//alert(srcval);
		if (srcval == '') {
			document.getElementById("gsubsrc").length = 1;
			document.getElementById("gsubsrc").disabled = false;
		}
		if (srcval != '') {
			$.ajax({
				type:"post",
				url:"pm_petition_detail_entry_action.php",
				cache:false,
				data:{source_frm: 'get_sub_source', source_id: srcval},
				error:function(){alert ("Some error occured")},
				success: function(html){
					//alert(html);
					document.getElementById("gsubsrc").innerHTML=html;
					if (document.getElementById("gsubsrc").length == 1) {
						document.getElementById("gsubsrc").disabled = true;
					} else {
						document.getElementById("gsubsrc").disabled = false;
						
					}
				}
			});
		}
		
	});
	
	$("#gtype").change(function(){
		var gval = $("#gtype").val();
		
		if(gval!=""){
	 		$.ajax({
	  			type: "post",
	  			url: "pm_petition_detail_entry_action.php",
	  			cache: false,
	  			data: {source_frm : 'griev_subcategory',griev_main_code : gval},
	  			error:function(){ alert("some error occurred") },
	  			success: function(html){
	 				document.getElementById("gsubtype").innerHTML=html;
						 //get_officer_list();
				 }
	  
			});
		} else {
			$("#gsubtype").empty().append("<option value=''>-- Select Petition Sub Category --</option>");	
		}
	});

});

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#to_date");
	
	newdate.setDate(newdate.getDate() - 7);
	var fromDate = new Date(newdate);
	setDateFormat(fromDate, "#from_date");
}

</script>
