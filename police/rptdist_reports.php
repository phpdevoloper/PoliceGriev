<?php
ob_start();
session_start();
$pagetitle="MIS Reports";
include("db.php"); 
include("header_menu.php");
include("menu_home.php");
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
function loadOfficeLocation() {

	var dept = $('#grie_dept_id').val();
	if (dept == '') {
		document.getElementById("gre_rev_tr").style.display='none';
		document.getElementById("gre_rural_tr").style.display='none';
		document.getElementById("gre_urban_tr").style.display='none';
		document.getElementById("gre_office_tr").style.display='none';
	} else {
		depts = dept.split('-');
		pattern = depts[1];
		if (pattern == '1') {
			document.getElementById("gre_rev_tr").style.display='';
			document.getElementById("gre_rural_tr").style.display='none';
			document.getElementById("gre_urban_tr").style.display='none';
			document.getElementById("gre_office_tr").style.display='none';
		} else if (pattern == '2') {
			document.getElementById("gre_rev_tr").style.display='none';
			document.getElementById("gre_rural_tr").style.display='';
			document.getElementById("gre_urban_tr").style.display='none';
			document.getElementById("gre_office_tr").style.display='none';
		} else if (pattern == '3') {
			document.getElementById("gre_rev_tr").style.display='none';
			document.getElementById("gre_rural_tr").style.display='none';
			document.getElementById("gre_urban_tr").style.display='';
			document.getElementById("gre_office_tr").style.display='none';
		} else if (pattern == '4') {
			loadOffice(depts[0]);
			document.getElementById("gre_rev_tr").style.display='none';
			document.getElementById("gre_rural_tr").style.display='none';
			document.getElementById("gre_urban_tr").style.display='none';
			document.getElementById("gre_office_tr").style.display='';
		}
	}
	
	
}


function valchk() {
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
function chk_form() {
    var fromdt=document.getElementById("from_date").value;
	var todt=document.getElementById("to_date").value;
    
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
/* 	if($('input[name=off_type]:checked', '#rpt_abstract').length==0)
	{
		alert("Select Purticular Office / Subordtinate Office");
		return false;
	}
	 
	var rep_val = $('input[name=dist_rpt]:checked', '#rpt_abstract').val(); */
/* 	if (rep_val == 'subordinate_special_grievance_day') {
		var gsrc = document.getElementById("gsrc").value;
		if (gsrc == '') {
			alert("Please select the concerned Special Greivance Source using Additional Parameters");
			return false;
		}
	} */
	 if($('input[name=off_type]:checked', '#rpt_abstract').length==0)
	 {
		 alert("Select Petitions Owned By.");
		 return false;
	 }else{
		 //alert(1);
		if($('input[name=off_type]:checked', '#rpt_abstract').val()!='S'){ 
		dept_off_level_pattern_id=$('#p_dept_off_level_pattern_id').val();
			if(dept_off_level_pattern_id==''){
				alert("Select Office Pattern.");
				return false;
			}office_level=$('#p_office_level').val();
			if(office_level==''){
				alert("Select Office level.");
				return false;
			}
		office=$('#office').val();//alert(office);
			if(office==''){//alert(123);
				alert("Select upto Office.");
				return false;
			}
		}//alert(1234);
		if ($('input[name=off_type]:checked', '#rpt_abstract').val()=='S'){
			
			dept_off_level_pattern_id=$('#dept_off_level_pattern_id').val();
			if(dept_off_level_pattern_id==''){
				alert("Select Office Pattern.");
				return false;
			}office_level=$('#office_level').val();
			if(office_level==''){
				alert("Select Office level.");
				return false;
			}
		}
	 }
	 if($('input[name=dist_rpt]:checked', '#rpt_abstract').length==0)
	 {
		 alert("Select Any one Report");
		 return false;
	 } 
	 else
	 {

	    var off_type= $('input[name=off_type]:checked', '#rpt_abstract').val();
		if (off_type=="P") {
			var pattern_id=$("#p_dept_off_level_pattern_id").val();
			var office_level=$("#p_office_level").val();
			var office_level_sltd=$("#office option:selected").text()+', '+$("#p_office_level option:selected").text();
			var office=$("#office").val();
			//alert(pattern_id+ ">>>>>>>>>>>>>>"+office_level+">>>>>>>>>>>>>>>>>>>>"+office);
			if (pattern_id=="") {
				alert("Select the particular office owning the petition");
				return false;
			} else if (office_level=="") {
				alert("Select the particular office owning the petition");
				return false;
			}
			
			
		}  else if (off_type=="S") {
			var pattern_id=$("#dept_off_level_pattern_id").val();
			var office_level_sltd=$("#office_level option:selected").text();
			var office_level=$("#office_level").val();
			if (office_level == '') {
					alert('Select a Subordinate Office Level');
					return false;
				
			}	
		}
		
	    document.getElementById("office_level_sltd").value=office_level_sltd;
	    document.getElementById("offtype").value=$('input[name=off_type]:checked', '#rpt_abstract').val();
	 	document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
		document.rpt_abstract.target= "_blank";
		document.rpt_abstract.submit();
		return true;
		 
	 }
	 
}

//rptdist_subordinate_special_grievance_day
function clear_cnt()
{
	document.getElementById("from_date").value=' ';
	document.getElementById("to_date").value=' ';
	document.getElementById("div_content1").style.display='none';
}
function validatedate(inputText,elementid){   
    var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
    if(inputText.value.match(dateformat)) {  
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
		if (mm==1 || mm>2) {  
			if (dd>ListofDays[mm-1]){  
				alert('Invalid date format!');  
			return false;  
			}  
		}  
		if (mm==2) {  
			var lyear = false;  
			if ( (!(yy % 4) && yy % 100) || !(yy % 400)) {  
				lyear = true;  
			}  
			if ((lyear==false) && (dd>=29))	{  
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

function loadOffice(dept_id) {
	var dist=$('#h_dist').val();
	dept = dept_id;
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'populate_office',dept : dept, dist : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("grev_office").innerHTML=html;
		}
		});	
}

function chkAdditional() {
	if(document.getElementById("additional").checked == true)      
	{      
		document.getElementById("src_tr").style.display="";
		document.getElementById("grie_tr").style.display="";
		document.getElementById("comm_cat_tr").style.display="none";
	} else {
		document.getElementById("src_tr").style.display="none";
		document.getElementById("grie_tr").style.display="none";
		document.getElementById("comm_cat_tr").style.display="none";
	}
	clearFormFields();
}
function clearFormFields() {
	document.getElementById("gsrc").options.selectedIndex = "";
	document.getElementById("gtype").options.selectedIndex = "";
	document.getElementById("gsubtype").options.length = 1;
	document.getElementById("pet_community").options.selectedIndex = "";	
	document.getElementById("special_category").options.selectedIndex = "";	
}
function loadOfficeLevels() {	
	var dept_off_level_pattern_id = document.getElementById("dept_off_level_pattern_id").value;
	if (dept_off_level_pattern_id != '') {
		$.ajax({
			type: "post",
			url: "rptdist_reports_action.php",
			cache: false,
			data: {source_frm : 'loadOfficeLevel',pattern_id : dept_off_level_pattern_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office_level").innerHTML=html;
				//document.getElementById("conc_office_level").innerHTML=html;
			}
		});
		
	} else {
		document.getElementById("office_level").options.length = 1;
	}
}

function p_loadOfficeLevels() {	
	var p_dept_off_level_pattern_id = document.getElementById("p_dept_off_level_pattern_id").value;
	if (p_dept_off_level_pattern_id != '') {
		$.ajax({
			type: "post",
			url: "rptdist_reports_action.php",
			cache: false,
			data: {source_frm : 'p_loadOfficeLevel',pattern_id : p_dept_off_level_pattern_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("p_office_level").innerHTML=html;
				document.getElementById("office").innerHTML='<option value="">-- Select Office --</option>';
			}
		});
		
	} else {
		document.getElementById("p_office_level").options.length = 1;
	}
}

function loadParticularOffice() {
	var pattern_id = document.getElementById("p_dept_off_level_pattern_id").value;
	var p_office_level = document.getElementById("p_office_level").value;
	//alert("p_office_level::"+p_office_level);
	if (p_office_level != '') {
		$.ajax({
			type: "post",
			url: "rptdist_reports_action.php",
			cache: false,
			data: {source_frm : 'p_loadOffice',pattern_id : pattern_id,p_office_level:p_office_level},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office").innerHTML=html;
			}
		});
		
	} else {
		document.getElementById("office").options.length = 1;
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
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
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
	
<div class="form_heading">
<div class="heading">
       <?PHP echo $label_name[36].' - '.$label_name[58];//MIS Reports?>
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
	<td colspan="4" style="text-align:center;"><b><?PHP echo $label_name[48];//Petition Period?>&nbsp;:</b>&nbsp;&nbsp;&nbsp;&nbsp;
	<?PHP echo $label_name[1];//From Date?>&nbsp;&nbsp;
	<input type="text" name="from_date" id="from_date" value="<?php echo $frdate; ?>"  data_valid='yes' 
	class="select_style" data-error="Select From Date" onchange="return validatedate(from_date,'from_date');" maxlength="12"/>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?PHP echo $label_name[2];//To Date?>&nbsp;&nbsp;
	
	<input type="text" name="to_date" id="to_date" value="<?php echo $todate; ?>"  data_valid='yes' 
	class="select_style" data-error="Select To Date" onchange="return validatedate(to_date,'to_date');" maxlength="12"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<span>
	 <input type="checkbox" name="additional" id="additional" onclick = "chkAdditional()" >
	<b><?PHP echo $label_name[52]; //Additional Parameters?><b>
	<!--img src="images/new.gif" /-->
	
	</td>
	</tr>
	
	<tr id="src_tr"  style="display:none;">
  <td><?PHP echo  $label_name[15];?></td>
  <td>	
  <select name="gsrc" id="gsrc">
 	<option value="">-- Select Source --</option>
    <?php 
	
		$sql = "SELECT a.source_id, b.source_name, b.source_tname FROM usr_dept_off_level_sources a
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
  <td><?PHP echo $label_name[70]; // Petition Type?></td>
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
   <!-- Source and Subsource Ends here-->    
   
   
   <!-- Greivance type abd Greivance Subtype Begins here-->   
  <tr id="grie_tr"  style="display:none;">
  <td><?PHP echo $label_name[17];?></td>
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
  <td><?PHP echo $label_name[18]; ?></td>
  <td>
  <select name="gsubtype" id="gsubtype">
 	 <option value="">-- Select Petition Sub Category --</option> 
  </select>
   </td>    
  </tr>
  
	 
   <!-- Introducing new search parameters Community and Special Category on  03/12/2018-->
	<tr id="comm_cat_tr" style="display:none;">
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
		<td><b><?PHP echo $label_name[110]; // Special Category?></b></td>
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


    <!-- Newly Added for Office Selection
    Date: 02/09/2016
    -->
    <tr>
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo $label_name[64] // Petitions Owned By?></td>
    </tr>

    <tr>
		<td>
			<input name="off_type" id="P" type="radio" value="P" />
		</td>
		<td> 
			<?PHP echo $label_name[56]; // 'Particular Office'?> 
		</td>
		
		<td>
			<input name="off_type" id="S" type="radio" value="S" />
		</td>
		<td> 
			<?PHP echo $label_name[57]; // 'Subordinate Offices'?> 
		</td>
	</tr>
    <input type="hidden" name="offtype" id="offtype" value=""/>
	<!-- Office Selection Ends-->
   
  <!-- Office Selection Owning Petition - Begins here  

--> 
  <tr id="off_sel_head" style="display:none">
  <td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
  <label id="off_tiltle">Selection of the Office Owning the Petition</label>
  </td>
  </tr>
     
  <tr id="dept_row" style="display:none">
  <td><?PHP echo 'Office Pattern'; //  'District' ?></td>
  <td>
  <select name="p_dept_off_level_pattern_id" id="p_dept_off_level_pattern_id" onchange="p_loadOfficeLevels();">
  <option value="">--Select--</option> 
	<?php 
		if ($userProfile->getDept_off_level_pattern_id() != "" || $userProfile->getDept_off_level_pattern_id()!=null) 
		{
			$sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM usr_dept_off_level_pattern where 
			dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
		} else {
			$sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM public.usr_dept_off_level_pattern order by 
			dept_off_level_pattern_id";
		}
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH))
		{
			$dept_off_level_pattern_id=$row["dept_off_level_pattern_id"];
			$dept_off_level_pattern_name=$row["dept_off_level_pattern_name"];
			$dept_off_level_pattern_tname=$row["dept_off_level_pattern_tname"];
			if($_SESSION["lang"]=='E'){
				
				$dept_off_level_pattern_name=$dept_off_level_pattern_name;
			}else{
				$dept_off_level_pattern_name=$dept_off_level_pattern_tname;	
			}
			print("<option value='".$dept_off_level_pattern_id."' >".$dept_off_level_pattern_name."</option>");
		}		
	?>	 
  </select>
   </td>    
  <!--tr id="s_off_row" style="display:none"-->
  <td><?PHP  echo 'Office Level'; //  'Department'?></td>
  <td>	
  <select name="p_office_level" id="p_office_level" onchange="loadParticularOffice();">
 	<option value="">-- Select Office Level--</option>
  </select>
  </td>    
  </tr>
  
<tr id="office_tr" style='display:none;'> 
<td><?PHP echo 'Office'?> </td>
<td colspan="3">
<select name="office" id="office">
<option value="">-- Select Office --</option>
</select>
</td>
</tr>
  
  <!-- Office Selection Owning Petition - Ends here  --> 
   <tr id="sub_off_sel_head" style="display:none">
  <td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
  <label id="off_tiltle">Selection of the Office for Subordinate Office</label>
  </td>
  </tr>
  
  <tr id="s_dept_row" style="display:none">
  <td><?PHP echo 'Office Pattern'; //  'District' ?></td>
  <td>
  <select name="dept_off_level_pattern_id" id="dept_off_level_pattern_id" onchange="loadOfficeLevels();">
  <option value="">--Select--</option> 
	<?php 
		if ($userProfile->getDept_off_level_pattern_id() != "" || $userProfile->getDept_off_level_pattern_id()!=null) 
		{
			$sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM usr_dept_off_level_pattern where 
			dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
		} else {
			$sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM public.usr_dept_off_level_pattern order by 
			dept_off_level_pattern_id";
		}
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH))
		{
			$dept_off_level_pattern_id=$row["dept_off_level_pattern_id"];
			$dept_off_level_pattern_name=$row["dept_off_level_pattern_name"];
			$dept_off_level_pattern_tname=$row["dept_off_level_pattern_tname"];
			if($_SESSION["lang"]=='E'){
				
				$dept_off_level_pattern_name=$dept_off_level_pattern_name;
			}else{
				$dept_off_level_pattern_name=$dept_off_level_pattern_tname;	
			}
			print("<option value='".$dept_off_level_pattern_id."' >".$dept_off_level_pattern_name."</option>");
		}		
	?>	 
  </select>
   </td>    
   
  <!--tr id="s_off_row" style="display:none"-->
  <td><?PHP  echo 'Office Level'; //  'Department'?></td>
  <td>	
  <select name="office_level" id="office_level">
 	<option value="">-- Select Office Level--</option>
  </select>
  </td>
     
  </tr>
  
      <tr id="list_of_reports">
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo $label_name[3];//List of Reports?></td>
    </tr>
	
	<!-- Source abd Subsource Begins here-->   
  
  	<tr id="p_sub_off_reports1" name="p_sub_off_reports1">	
	<td><input name="dist_rpt" id="officerswise_detail_pet_period" type="radio" value="officerswise_detail_pet_period" /></td>
	<td> <?PHP echo 'Officers wise Pendency Report';//Sourcewise Report?> </td>	
	<td><input name="dist_rpt" id="griev_type" type="radio" value="grievancetypewise" /></td>
	<td> <?PHP echo $label_name[12];//Grievance Typewise Report?> </td>
	</tr>
	
	<tr id="sub_off_reports1" name="sub_off_reports1" style="display:none;">	
	<td><input name="dist_rpt" id="officewise" type="radio" value="officewise" /></td>
	<td> <?PHP echo 'Office wise Pendency Report';//Sourcewise Report?> </td>	
	<td><input name="dist_rpt" id="griev_type" type="radio" value="grievancetypewise" /></td>
	<td> <?PHP echo $label_name[12];//Grievance Typewise Report?> </td>
	</tr>
	
	<tr id="dispose_level_1" style="display:none;">
	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
	<?PHP echo $label_name[105] // Reports without Parameters?></td>
	</tr> 
 
	<tr id="dispose_level_2" style="display:none;">		
	<td><input name="dist_rpt" id="subordinate_disposal" type="radio" value="subordinate_disposal" /></td>
	<td colspan="3"> <?PHP echo  'All Petitions Pendency Status of All Disposing Officers';//இறுதித் தீர்வளிக்கும் அலுவலர் சார்ந்த நிலுவை நிலை?> </td>
	</tr>
  
 <!-- Greivance type and Greivance Subtype Ends here-->   
 
  <tr id="p_off_sel_head" style="display:none">
  <td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
  <label id="off_tiltle">Selection of the Office Processing the Petition</label>
  </td>
  </tr>
     
   <tr id="p_dept_row" style="display:none">
  <td><?PHP echo 'Department'?></td>
  <td>	
  <select name="p_dept" id="p_dept">
 	<option value="">-- Select Department --</option>
  </select>
  </td>
  <td><?PHP echo 'District' ?></td>
  <td>
  <select name="p_dist" id="p_dist">
 	 <option value="">-- Select District --</option> 
  </select>
   </td>    
  </tr>
  
  	<tr id="button_row" name="button_row">
	<td colspan="4" class="btn"  style="background-color: #FBE5E5;" align="center">
	<?php if($_SESSION['lang']=='E'){ ?>
	<span  style="position: absolute;margin-left:-200px">
	<?php } else { ?>
	<span  style="position: absolute;margin-left:-300px">
	<?php } ?>
	<input type="button" name="save" id="save" value="<?PHP echo $label_name[10];//View?>" onClick="return chk_form();"  />
	&nbsp;<input type="reset" value="<?PHP echo $label_name[11];//Clear?>" onclick="clear_cnt()" /> 
	</span>
	<?php if ($userProfile->getOff_level_id() >= 1) { ?>
	<span style="float:left;">
	<a style="color:blue;font-weight: bold;font-size:15px;" href="rptdist_reports_s.php" onclick=""><?php echo $label_name[36]." - ".$userProfile->getOff_level_name().' - '.$label_name[66];?></a></span>
	<?php } ?>
	
	<input type="hidden" name="hid" id="hid" />
    <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>   
	<input type="hidden" name="source_from" id="source_from" value="sub"/>	
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $userProfile->getOff_level_id();?>" />
	<input type="hidden" name="off_level_pattern_id" id="off_level_pattern_id" value="<?php echo $userProfile->getOff_level_pattern_id();?>" />
	<input type="hidden" name="usr_dept_id" id="usr_dept_id" value="<?php echo $userProfile->getDept_id();?>" />	
    <input type="hidden" name="subordinate_type" id="subordinate_type" value="" />
	<input type="hidden" name="hid_radio" id="hid_radio" value="<?php echo stripQuotes(killChars($_POST["hid_radio"])); ?>" />
   	
	<input type="hidden" name="office_level_sltd" id="office_level_sltd"/>
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
	addDate();
	
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
	
	
$("input:radio[name=off_type]").click(function(){
	changeLabel();
});

$("input:radio[name=dist_rpt]").click(function(){
	//loadProcessingOffice();
});

});
function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	var newdate1 = new Date(date);
	//setDateFormat(date, "#to_date");
	
	newdate.setDate(newdate.getDate() - 8);
	var fromDate = new Date(newdate);
	setDateFormat(fromDate, "#from_date");
	
	newdate1.setDate(newdate1.getDate() - 1);
	var toDate = new Date(newdate1);
	setDateFormat(toDate, "#to_date");
}
/* function loadProcessingOffice(){
	var rpttype=$('input[name=dist_rpt]:checked', '#rpt_abstract').val();
	off_level_id = document.getElementById("off_level_id").value;
	if (rpttype=='officerswise') {
		document.getElementById("p_off_sel_head").style.display='';
		document.getElementById("p_dept_row").style.display='';
		document.getElementById("office_tr").style.display='';
		document.getElementById("p_rdo_tr").style.display='';
		if (off_level_id == 4 || off_level_id == 5) {
			document.getElementById("p_rev_tr").style.display=''
		}
		load_processing_department();
	} else {
		document.getElementById("p_off_sel_head").style.display='none';
		document.getElementById("p_dept_row").style.display='none';
		document.getElementById("office_tr").style.display='none';
		document.getElementById("p_rdo_tr").style.display='none';
		document.getElementById("p_rev_tr").style.display='none'
		document.getElementById("p_block_tr").style.display='none';
		document.getElementById("p_urban_tr").style.display='none'
		document.getElementById("p_office_tr").style.display='none';
	}
	
} */
function changeLabel() {
	var offtype=$('input[name=off_type]:checked', '#rpt_abstract').val();
	$('input[name=dist_rpt]').attr('checked',false);
	if (offtype == "P") {
		//pattern_id=document.getElementById("off_level_pattern_id").value;
		//off_level_id = document.getElementById("off_level_id").value;    //
		document.getElementById("sub_off_reports1").style.display='none';
		document.getElementById("p_sub_off_reports1").style.display='';		
		document.getElementById("off_sel_head").style.display='';
		document.getElementById("dept_row").style.display='';	
		document.getElementById("office_tr").style.display='';
		document.getElementById("sub_off_sel_head").style.display='none';
		document.getElementById("s_dept_row").style.display='none';	
		document.getElementById("dispose_level_1").style.display='none';	
		document.getElementById("dispose_level_2").style.display='none';	
		document.getElementById("button_row").style.display='';		
		//disp_check($('#dept').val());
	} else {
		//off_level_id = document.getElementById("off_level_id").value; 
		document.getElementById("sub_off_reports1").style.display='';
		document.getElementById("p_sub_off_reports1").style.display='none';  		
		document.getElementById("off_sel_head").style.display='none';
		document.getElementById("dept_row").style.display='none';	
		document.getElementById("office_tr").style.display='none';
		document.getElementById("sub_off_sel_head").style.display='';
		document.getElementById("s_dept_row").style.display='';	
		document.getElementById("dispose_level_1").style.display='';	
		document.getElementById("dispose_level_2").style.display='';
		document.getElementById("button_row").style.display='';
	
	}
		
}
</script>
