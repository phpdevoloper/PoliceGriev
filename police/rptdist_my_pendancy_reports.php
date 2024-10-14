<?php
ob_start();
session_start();
$pagetitle="MIS Reports";
include("db.php"); 
include("header_menu.php");
include("menu_home.php");
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
//include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
include("common_date_fun.php");
include("pm_common_js_css.php");
 
$hidsubstr=stripQuotes(killChars($_POST['hid']));
?>

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
function chk_form()
{
  
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
	if ($('input[name=dist_rpt]:checked', '#rpt_abstract').val() != 'my_pendency_status' &&
		$('input[name=dist_rpt]:checked', '#rpt_abstract').val() != 'my_pendency_tobeforwarded') {
	
	if($('input[name=off_type]:checked', '#rpt_abstract').length==0)
	 {
		alert("Select  All Offices / Particular Office");
		return false;
	 } else  { 
		 if($('input[name=dist_rpt]:checked', '#rpt_abstract').length==0)
		 {
			 alert("Select Any one Report");
			 return false;
		 } else  {
			if ($('input[name=off_type]:checked', '#rpt_abstract').val() == 'P') {
				/*if ($('#dist').val() == "") {
					alert("Select a district");
					return false
				} else*/ if ($('#dept').val() == "") {
					alert("Select a department");
					return false
				}
				var off_level = $('#off_level_id').val();
				if (off_level == 2) {
					var state = $('#state').val();
					if (state == '') {
						alert("Select one Superior office");
						return false;
					}
				} else if (off_level == 3) {
					var state = $('#state').val();
					var dist = $('#dist').val();
					if (state == '' && dist == '') {
						alert("Select one Superior office");
						return false;
					}
				} else if (off_level == 4) {
					var state = $('#state').val();
					var dist = $('#dist').val();
					var rdo = $('#rdo').val();
					if (state == '' && dist == '' && rdo == '') {
						alert("Select one Superior office");
						return false;
					}
				} else if (off_level == 5) {
					var state = $('#state').val();
					var dist = $('#dist').val();
					var rdo = $('#rdo').val();
					var taluk = $('#taluk').val();
					
					if (state == '' && dist == '' && rdo == '' && taluk == '') {
						alert("Select one Superior office");
						return false;
					}
				} else {
					var state = $('#state').val();
					var dist = $('#dist').val();
					
					if (state == '' && dist == '') {
						alert("Select one Superior office");
						return false;
					}
				}
					
				
			} 
			document.getElementById("office_type").value=$('input[name=off_type]:checked', '#rpt_abstract').val(); 
			document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
			document.rpt_abstract.target= "_blank";
			document.rpt_abstract.submit();
			return true;		 
		}
	 }
	 } else {
			document.rpt_abstract.action = "rptdist_"+$('input[name=dist_rpt]:checked', '#rpt_abstract').val()+".php";
			document.rpt_abstract.target= "_blank";
			document.rpt_abstract.submit();
			return true;
	 }
	 
	 
}

function clear_cnt()
{
	/*$('#from_date').val('');
	$('#to_date').val('');*/
	document.getElementById("from_date").value=' ';
	document.getElementById("to_date").value=' ';
	document.getElementById("office").innerHTML="<option>--Select Office--</option>";
	//document.getElementById("div_content1").style.display='none';
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

function loadOfficeLocation() {
//var vSkillText = vSkill.options[vSkill.selectedIndex].innerHTML;
;
	var dept = $('#grie_dept_id').val();
	//alert(dept);
	if (dept == '') {
		document.getElementById("gre_rev_tr").style.display='none';
		document.getElementById("gre_rural_tr").style.display='none';
		document.getElementById("gre_urban_tr").style.display='none';
		document.getElementById("gre_office_tr").style.display='none';
	} else {
		depts = dept.split('-');
		pattern = depts[1];
		alert(pattern);
		//document.getElementById("pat_id").value=pattern;
		//document.getElementById("dept_id").value= depts[0];
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
		//document.getElementById("src_tr").style.display="";
		document.getElementById("grie_tr").style.display="";
		document.getElementById("dept_tr").style.display="";
		document.getElementById("comm_cat_tr").style.display="none";
	} else {
		//document.getElementById("src_tr").style.display="none";
		document.getElementById("grie_tr").style.display="none";
		document.getElementById("dept_tr").style.display="none";
		document.getElementById("comm_cat_tr").style.display="none";
	}
	clearFormFields();
}
function clearFormFields() {
	//document.getElementById("gsrc").options.selectedIndex = "";
	document.getElementById("gsubsrc").disabled = false;
	document.getElementById("gsubsrc").options.length = 1;
	document.getElementById("gtype").options.selectedIndex = "";
	document.getElementById("gsubtype").options.length = 1;
	document.getElementById("pet_community").options.selectedIndex = "";
	document.getElementById("special_category").options.selectedIndex = "";	
	//if (document.getElementById("off_level_id").value == 2) {
		document.getElementById("grie_dept_id").options.selectedIndex = "";	
	//}
}

function changeLabel23() {
	return false;
	var offtype=$('input[name=off_type]:checked', '#rpt_abstract').val();
	if (offtype == "A") {
		document.getElementById("pattern_row").style.display='none';
		//document.getElementById("office_row").style.display='none';
	} else if (offtype == "P") {
		document.getElementById("pattern_row").style.display='';
		document.getElementById("office").innerHTML="<option>--Select Office--</option>";
		loadOfficeLevel();   
		//document.getElementById("office_row").style.display='';		
	}else{
	alert('Select Office');
	return false;
	}
		
}
function clearParticularOfficeSelection() {
	document.getElementById("dept").options.selectedIndex = "";
	document.getElementById("dist").options.selectedIndex = "";
	document.getElementById("rdo").options.selectedIndex = "";
	document.getElementById("taluk").options.selectedIndex = "";
	document.getElementById("firka").options.selectedIndex = "";
	document.getElementById("block").options.selectedIndex = "";
	document.getElementById("urban").options.selectedIndex = "";
	document.getElementById("office").options.selectedIndex = "";
}

function loadOfficeLevel() {
	var pattern_id = document.getElementById("pattern_id").value;
	//if (pattern_id != "") {
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'loadLevelForReports',pattern_id : pattern_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office_level").innerHTML=html;				
			}
		});
	//}
}

function loadOfficeLocationsForReport() {
	var pattern_id = document.getElementById("pattern_id").value;
	var office_level = document.getElementById("office_level").value;
	//if (office_level != '') {
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'loadLocationsForReports',office_level : office_level,pattern_id:pattern_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office").innerHTML=html;
			}
		});
	//}
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
       <?PHP echo $label_name[61]; //My Pendancy Report ?>
</div>
</div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv"> 
 
	<table class="formTbl" >
	<tbody>
    <tr id="alrtmsg" style="display:none;" align="center" ><td  colspan="4">&nbsp;</td></tr>	
	<tr>
	<td colspan="4" style="text-align:center">
	<b><?PHP echo $label_name[48];//Petition Period ?>:</b>&nbsp;&nbsp;&nbsp;
	<?PHP echo $label_name[1];//From Date?>
	<input type="text" name="from_date" id="from_date" value="<?php echo $frdate; ?>"  data_valid='yes' 
	class="select_style" data-error="Select From Date" onchange="return validatedate(from_date,'from_date');" maxlength="12"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2];//To Date?>
	<input type="text" name="to_date" id="to_date" value="<?php echo $todate; ?>"  data_valid='yes' class="select_style" 
	data-error="Select To Date" onchange="return validatedate(to_date,'to_date');" maxlength="12"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<span>
	 <input type="checkbox" name="additional" id="additional" onclick = "chkAdditional()" >
	<b><?PHP echo $label_name[52]; //Additional Parameters?><b>
	<!--img src="images/new.gif" /-->
	</span>
	</td>
	</tr>
   
	
	
	
	<!-- Source and Subsource Begins here-->   
  <!--tr id="src_tr" style="display:none;">
  <td><b><?PHP echo  $label_name[15];?></b></td>
  <td>	
  <select name="gsrc" id="gsrc">
 	<option value="">-- Select Source --</option>
    <?php 
	
		/*$sql = "SELECT DISTINCT(a.source_id), b.source_name, b.source_tname FROM usr_dept_desig_sources a  JOIN lkp_pet_source b ON b.source_id = a.source_id WHERE a.dept_desig_id = ".$userProfile->getDept_desig_id()." order by b.source_name";*/
		
		// SELECT distinct source_id, source_name, source_tname   FROM vw_usr_dept_desig_sources where off_level_id=2 and dept_id=1;
		/*if ($userProfile->getDept_id() != 12) { //25-10-2017 Included for IG Registration Office
			$sql="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";	
		} else {
			$sql="SELECT DISTINCT(a.source_id), b.source_name, b.source_tname
				  FROM usr_dept_desig_sources a
				  JOIN lkp_pet_source b ON b.source_id = a.source_id
				  WHERE a.dept_desig_id = ".$userProfile->getDept_desig_id()." order by b.source_name";
		}
		$sql="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";
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
		}*/
	?>
  </select>
  </td>
  <td><b><?PHP echo $label_name[16]; ?></b></td>
  <td>
  <select name="gsubsrc" id="gsubsrc">
 	 <option value="">-- Select Subsource --</option> 
  </select>
   </td>    
  </tr-->
   <!-- Source abd Subsource Ends here-->   
   
   
   <!-- Greivance type abd Greivance Subtype Begins here-->   
  <tr id="grie_tr" style="display:none;">
  <td><b><?PHP echo $label_name[17];?></b></td>
  <td>	
  <select name="gtype" id="gtype">
 	<option value="">-- Select Category --</option>
    <?php 
	
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
  <td><b><?PHP echo $label_name[18]; ?></b></td>
  <td>
  <select name="gsubtype" id="gsubtype">
 	 <option value="">-- Select Petition Sub Category --</option> 
  </select>
   </td>    
  </tr>
  
  
	<tr id="dept_tr" style="display:none;">
		<td style="display:none;"><b><?PHP echo $label_name[19];?></b></td>
		<td style="display:none;">	
			<select name="grie_dept_id" id="grie_dept_id">
			<option value="">-- Select Petition Department --</option>
			</select>
		</td>
		<td><b><?PHP echo $label_name[70]; // Petition Type?></b></td>
		<td colspan="3">	
			<select name="petition_type" id="petition_type">
			<option value="">-- Select Petition Type --</option>
			<?php
				$pet_type_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type";
				$result = $db->query($pet_type_sql);
				$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray as $row){
				//echo "<option value='$row[source_id]'>$row[source_name]</option>";
					if($_SESSION["lang"]=='E'){
						print "<option value='".$row['pet_type_id']."'>".$row['pet_type_name']."</option>";
					}else{
						print "<option value='".$row['pet_type_id']."'>".$row['pet_type_tname']."</option>";	
					}
				}
			?>
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
				$rs=$db->query($community_sql);
				$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarray as $row)
				{						
				$pet_community_id = $row["pet_community_id"];
				if($_SESSION["lang"]=='E'){
					print("<option value='".$pet_community_id."' >".$row["pet_community_name"]."</option>");	
				}else{
					print("<option value='".$pet_community_id."' >".$row["pet_community_tname"]."</option>");	
				}
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
  	<tr>
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo $label_name[64] // Petitions Owned By?></td>
    </tr>
	
	<tr>
	<td><input name="off_type" id="off_type" type="radio" value="A" /></td>
	<td> <?PHP echo $label_name[62]; //All Offices ?>  </td>
	
	<td><input name="off_type" id="off_type" type="radio" value="P" /></td>
	<td> <?PHP echo $label_name[63]; // Particular Office ?>  </td>
	</tr>
	
	<tr id="off_sel_head" style="display:none">
  <td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
  <label id="off_tiltle">Selection of the Office Owning the Petition</label>
  </td>
  </tr>
     
<tr id="pattern_row" style="display:none">
		<?php
			if ($userProfile->getDept_off_level_pattern_id() != "" || $userProfile->getDept_off_level_pattern_id()!=null) 
			{
				$off_pat_sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM usr_dept_off_level_pattern where 
				dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
			} else {
				$off_pat_sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM public.usr_dept_off_level_pattern order by 
				dept_off_level_pattern_id";
			}

			$off_pat_rs=$db->query($off_pat_sql);
			if(!$off_pat_rs)
			{
				print_r($db->errorInfo());
				exit;
			}
			$rowarray = $off_pat_rs->fetchall(PDO::FETCH_ASSOC);
			foreach($rowarray as $row)
			{
				$dept_off_level_pattern_id = $row["dept_off_level_pattern_id"];
				/*if($_SESSION["lang"]=='E'){
					print("<option value='".$dept_off_level_pattern_id."' >".$row["dept_off_level_pattern_name"]."</option>");	
				}else{
					print("<option value='".$dept_off_level_pattern_id."' >".$row["dept_off_level_pattern_name"]."</option>");
				}*/
			}
		?>
  <td><?PHP echo 'Office Level'; //  'State' ?></td>
		<td>
			<select name="office_level" id="office_level" onChange="loadOfficeLocationsForReport()">
			<option value="">-- Select Office Level--</option
			</select>
	<input type="hidden" name="pattern_id" id="pattern_id" value="<?php echo $dept_off_level_pattern_id;?>">
		</td>      
  <!--/tr-->
  
    
   <td><?PHP echo 'Office'?> </td>
   <td colspan="3">
  <select name="office" id="office">
  <option value="">-- Select Office Location--</option>
  </select>
  </td>
  <!--tr id="office_row" style='display:none;'></tr-->
  
	<tr>
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo $label_name[51];//Reports Based on Petition Period?></td>
    </tr>
	
	<tr>
	   	<?php if ($userProfile->getOff_level_id() == 1) { ?>
		<tr style="display:none;">
		<?php } else { ?>
		<tr style="display:none;">
		<?php } ?>
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo 'Reports Based on Petition Processing Period';//Reports Based on Petition Period?></td>
    </tr>
	
	<tr>
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="pending_with_loggedin_and_others" /></td>
	<td> <?PHP echo $label_name[67];//'Originally forwarded to me and Pending with myself or others'?>  </td>
	
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="pending_with_others" /></td>
	<td> <?PHP echo $label_name[69];//'Forwarded by me and pending with others';//Department wise Report?>  </td>
	
	</tr>
	
	<tr>
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="pending_with_loggedin" /></td>
	<td> <?PHP echo $label_name[68];//'Pending with myself at present';//Department wise Report?>  </td>
	
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="deferred_by_me" /></td>
	<td> <?PHP echo 'Deferred By Me';//'Forwarded by me and pending with others';//Department wise Report?>  </td>
	</tr>
	
	<tr style="display:none;">
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="action_taken_by_me_and_accepted" /></td>
	<td> <?PHP echo 'Action Taken by me and Accepted';//Action Taken by me and Accepted?>  </td>
	
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="action_taken_by_me_and_rejected" /></td>
	<td> <?PHP echo 'Action Taken by me and Rejected';//Action Taken by me and Rejected?>  </td>
	</tr>
	
	<tr>
    	<td colspan="4" style="text-align: center; font-weight:bold; padding-left: 0px; background-color: #F4CBCB;">
        <?PHP echo $label_name[105];//Reports Based on Petition Period?></td>
    </tr>
	<tr>
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="my_pendency_status" /></td>
	<td> <?PHP echo $label_name[107];//My Pendendy Summary as on Today?>  </td>
	<td><input name="dist_rpt" id="dist_rpt" type="radio" value="my_pendency_tobeforwarded" /></td>
	<td><font style="color:red;font-weight:bold;">
	<?PHP echo $label_name[108];//My Pendency To be Forwarded as on Today?>  </font></td>
	
	</tr>
	
	<tr>
	<td colspan="4" class="btn" align="center">
	
	<input type="button" name="save" id="save" value="<?PHP echo $label_name[10];//View?>" onClick="return chk_form();"  />
	&nbsp;<input type="reset" value="<?PHP echo $label_name[11];//Clear?>" onclick="clear_cnt()" /> 
	
	<input type="hidden" name="hid" id="hid" />
	<input type="hidden" name="rep_src" id="rep_src" value="simple"/>
	<input type="hidden" name="office_type" id="office_type"/>
	
    <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
	<input type="hidden" name="source_from" id="source_from" value="main"/>
    
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $userProfile->getOff_level_id();?>" />

	<input type="hidden" name="h_dist" id="h_dist" value="<?php echo $userProfile->getDistrict_id();?>" />
	
  <!--- AFter particular combo selection -->

  
	<input type="hidden" name="hid_dept" id="hid_dept" value="<?php echo stripQuotes(killChars($_POST["hid_dept"])); ?>" />
   
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
		
	//load_griev_department();
		
	$("input:radio[name=off_type]").click(function(){
	//alert("Test");
	var offtype=$('input[name=off_type]:checked', '#rpt_abstract').val();
	if (offtype == "A") {
		document.getElementById("pattern_row").style.display='none';
		//document.getElementById("office_row").style.display='none';
	} else if (offtype == "P") {
		document.getElementById("pattern_row").style.display='';
		document.getElementById("office").innerHTML="<option>--Select Office--</option>";
		loadOfficeLevel();   
		//document.getElementById("office_row").style.display='';		
	}
		//document.getElementById("office").innerHTML="<option>--Select Office--</option>";
	//loadOfficeLevel();
	});
});
		

</script>
