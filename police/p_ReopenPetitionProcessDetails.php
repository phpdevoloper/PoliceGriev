<?PHP 
error_reporting(0);
session_start();
include("header_menu.php");
include("db.php");
include("common_date_fun.php");
include("pm_common_js_css.php");

$userProfile = unserialize($_SESSION['USER_PROFILE']);

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
<title style="display:none;">Petition Re-open</title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
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

function reopenProcess() {
	
	var up_off_level_id=document.getElementById("up_off_level_id").value;
	if(up_off_level_id==46){
				saveReopen();
				return false;
		}
	
	document.getElementById("reopen_process").style.display='';
	var griev_sub_code=document.getElementById("griev_subcode").value;
	var off_level_pattern_id = document.getElementById("off_level_pattern_id").value;
	var griev_loc_id = document.getElementById("griev_loc_id").value;
	var dept_id = document.getElementById("dept_id").value;	
	var off_level_id=document.getElementById("off_level_id").value;
	var hid_off_loc_id=document.getElementById("hid_off_loc_id").value;
	var dept_off_level_pattern_id=document.getElementById("dept_off_level_pattern_id").value;;
	var petition_id=document.getElementById("petition_id").value;
	var off_level_dept_id = document.getElementById("off_level_dept_id").value;
	init_off=$('#userId').val();
	pet_process='F';
	//var dept_id=1;
	//alert("dept_off_level_pattern_id::"+dept_off_level_pattern_id);
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		//source_frm : 'loadSupervisoryOfficers'
		
		data:{source_frm :'enquiry_officer',init_off:init_off,pet_process:pet_process},
		success: function(html){
			document.getElementById("supervisory_officer").innerHTML=html;
			//alert(html.length);
			
			}
		});
}
/*data: {source_frm : 'loadSupervisoryOfficers',griev_sub_id : griev_sub_code, off_level_pattern_id : off_level_pattern_id,griev_loc_id : griev_loc_id,dept_id:dept_id,off_level_id:off_level_id,petition_office_loc_id:griev_loc_id,dept_off_level_pattern_id:dept_off_level_pattern_id,off_level_dept_id:off_level_dept_id},
		error:function(){ alert("some error occurred") }*/
/*if(html.length==156){
				document.getElementById("reopen_process").style.display='';
	var griev_sub_code=document.getElementById("griev_subcode").value;
	var off_level_pattern_id = document.getElementById("off_level_pattern_id").value;
	var griev_loc_id = document.getElementById("griev_loc_id").value;
	var dept_id = document.getElementById("dept_id").value;	
	var off_level_id=document.getElementById("off_level_id").value;
	var hid_off_loc_id=document.getElementById("hid_off_loc_id").value;
	var dept_off_level_pattern_id=document.getElementById("dept_off_level_pattern_id").value;;
	var petition_id=document.getElementById("petition_id").value;
	var off_level_dept_id = document.getElementById("off_level_dept_id").value;
	//var dept_id=1;
	//alert("dept_off_level_pattern_id::"+dept_off_level_pattern_id);
	$.ajax({
		type: "post",
		url: "pm_concerned_officers_action.php",
		cache: false,
		//source_frm : 'loadSupervisoryOfficers'
		data: {source_frm : 'enquiry_default',griev_sub_id : griev_sub_code, off_level_pattern_id : off_level_pattern_id,griev_loc_id : griev_loc_id,dept_id:dept_id,off_level_id:off_level_id,petition_office_loc_id:griev_loc_id,dept_off_level_pattern_id:dept_off_level_pattern_id,off_level_dept_id:off_level_dept_id},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("supervisory_officer").innerHTML=html;
		}
	});*/

function concernedOffId() {
	//document.getElementById("off_d_id").value=$("#concerned_officer").val();
}
function loadConcernedOfficer() {
	//alert("Hello");
	var griev_sub_code=document.getElementById("griev_subcode").value;
	var off_level_pattern_id = document.getElementById("off_level_pattern_id").value;
	var griev_loc_id = document.getElementById("griev_loc_id").value;
	var dept_id = document.getElementById("dept_id").value;	
	var off_level_id=document.getElementById("off_level_id").value;
	var hid_off_loc_id=document.getElementById("hid_off_loc_id").value;
	var dept_off_level_pattern_id=document.getElementById("dept_off_level_pattern_id").value;;
	var petition_id=document.getElementById("petition_id").value;
	var off_level_dept_id = document.getElementById("off_level_dept_id").value;
	var supervisory_officer = document.getElementById("supervisory_officer").value;
	//alert("dept_off_level_pattern_id::"+dept_off_level_pattern_id);
	disposing_officer=$('#userId').val();
	supervisory_officer=$('#supervisory_officer').val();
	$.ajax({
		type: "post",
		url: "pm_concerned_officers_action.php",
		cache: false,
		//source_frm : 'loadConcernedOfficers'
		data:{source_frm : 'load_Concerned_Officers',disposing_officer:disposing_officer,supervisory_officer:supervisory_officer
		}
		,
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("concerned_officer").innerHTML=html;
			//alert($(html).find('dept_user_id').eq(0).text());
			
		}
	});
}	
	//get_officer_list();	

/*data: {source_frm : 'loadConcernedOfficers',griev_sub_id : griev_sub_code, off_level_pattern_id : off_level_pattern_id,
		petition_office_loc_id : griev_loc_id,dept_id:dept_id,off_level_id:off_level_id,hid_off_loc_id:hid_off_loc_id,dept_off_level_pattern_id:dept_off_level_pattern_id,off_level_dept_id:off_level_dept_id,supervisory_officer:supervisory_officer}
		
		if($(html).find('dept_user_id').eq(0).text()==''){
				var griev_sub_code=document.getElementById("griev_subcode").value;
	var off_level_pattern_id = document.getElementById("off_level_pattern_id").value;
	var griev_loc_id = document.getElementById("griev_loc_id").value;
	var dept_id = document.getElementById("dept_id").value;	
	var off_level_id=document.getElementById("off_level_id").value;
	var hid_off_loc_id=document.getElementById("hid_off_loc_id").value;
	var dept_off_level_pattern_id=document.getElementById("dept_off_level_pattern_id").value;;
	var petition_id=document.getElementById("petition_id").value;
	var off_level_dept_id = document.getElementById("off_level_dept_id").value;
	var supervisory_officer = document.getElementById("supervisory_officer").value;
	//alert("dept_off_level_pattern_id::"+dept_off_level_pattern_id);
	$.ajax({
		type: "post",
		url: "pm_concerned_officers_action.php",
		cache: false,
		//source_frm : 'loadConcernedOfficers'
		
		data: {source_frm : 'load_Concerned_Officers',griev_sub_id : griev_sub_code, off_level_pattern_id : off_level_pattern_id,
		petition_office_loc_id : griev_loc_id,dept_id:dept_id,off_level_id:off_level_id,hid_off_loc_id:hid_off_loc_id,dept_off_level_pattern_id:dept_off_level_pattern_id,off_level_dept_id:off_level_dept_id,supervisory_officer:supervisory_officer},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("concerned_officer").innerHTML=html;
		}
	});*/
function saveReopen() {
	var supervisory_officer = $('#supervisory_officer').val();
	var concerned_officer = $('#concerned_officer').val();
	var conc_off = '';
	if (concerned_officer != '') {
		conc_off = concerned_officer;
	} 
	if (supervisory_officer != '') {
		sup_off = supervisory_officer;
	}
	petition_id = $('#petition_id').val();
	f_action_entby = $('#f_action_entby').val();
	up_off_level_id = $('#up_off_level_id').val();
	if(up_off_level_id!=46){
	if (conc_off == '') {
		alert('No officer is selected for this operation. Kindly check');
		return false;
	} else {
		var strconfirm = confirm("This action will reopen the petition. Are you sure to reopen?");
		if (strconfirm == true) {			
			$.ajax({
				type: "POST",
				dataType: "xml",
				url: "pm_pet_detail_entry_get_dept_action.php",  
				data: "mode=reopen_petition"+"&petition_id="+petition_id+"&conc_off="+conc_off+"&action_entby="+f_action_entby+"&sup_off="+sup_off,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					var count = $(xml).find('result').eq(0).text();
					if (count > 0) {
						alert("Petition reopened successfully and Kindly wait for the acknowledgement");
						document.petition_reopen.method="post";
						document.petition_reopen.action = "reopen_ackmnt_print_page.php"
						document.petition_reopen.submit();
					}
				}
			});
		} else {
			return false;
		}
	}
}else{
	var strconfirm = confirm("This action will reopen the petition. Are you sure to reopen?");
		if (strconfirm == true) {			
			$.ajax({
				type: "POST",
				dataType: "xml",
				url: "pm_pet_detail_entry_get_dept_action.php",  
				data: "mode=reopen_petition"+"&petition_id="+petition_id+"&conc_off="+conc_off+"&action_entby="+f_action_entby+"&sup_off="+sup_off,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					var count = $(xml).find('result').eq(0).text();
					if (count > 0) {
						alert("Petition reopened successfully and Kindly wait for the acknowledgement");
						document.petition_reopen.method="post";
						document.petition_reopen.action = "reopen_ackmnt_print_page.php"
						document.petition_reopen.submit();
					}
				}
			});
}
}
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
</script>

</head>
<body>
<?php
	$sql = "select pet_type_id from pet_master where petition_id=".$petition_id."";
	$rs = $db->query($sql);
	$rowarr = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarr as $r)
	{
		$pet_type_id = $r['pet_type_id'];
	}
	if ($pet_type_id == 6) {
	?>
	<div class="contentMainDiv" style="width:98%;background-color:#bc7676;margin-right:auto;margin-left:auto;">
	<div class="contentDiv" style="background-color:#F4CBCB;">
	<table class="viewTbl">
	<tbody>
	    <tr>    	
        <td class="heading" style="background-color: #BC7676;">This Petition is already reopened, Please refresh the previous page to delist this Petition!!!</td>
       </tr>
	   <tr>
	   <td class="btn">
	<input type="button" name="close" id="close" value="<?PHP echo 'Back'; //View. ?>"  onClick="closePopup();"/> 
	</td>
	</tr>
	</tbody>
	</table>
	</div>
	</div>
<?php	
	} else {
			$query = "SELECT a.petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name,gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_id,source_name, subsource_name,griev_type_name,griev_subtype_name,dept_name, grievance, canid, comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_pincode, comm_email, comm_phone, comm_mobile, comm_district_name,comm_taluk_name,
			comm_rev_village_name,comm_state_name, comm_country_name,COALESCE(zone_name,range_name, griev_district_name, griev_division_name,griev_subdivision_name, griev_circle_name,'Tamil Nadu') as loc, pet_type_name, COALESCE(zone_id,range_id,griev_district_id,griev_division_id, griev_subdivision_id, griev_circle_id,29) 
			as griev_loc_id,off_level_pattern_id,a.off_level_dept_id,a.dept_id,griev_subtype_id,f_action_entby,
			dept_off_level_pattern_id,c.off_level_id
			,case 
			WHEN zone_id is not null THEN 9
			WHEN range_id is not null THEN 11
			WHEN griev_district_id is not null THEN 13
			WHEN griev_division_id is not null THEN 42
			WHEN griev_circle_id is not null THEN 46
			ELSE 7
			END as off_level_id_loc
			FROM vw_pet_master  a
			inner join pet_action_first_last  b on b.petition_id=a.petition_id
			left join usr_dept_off_level c on c.off_level_dept_id=a.off_level_dept_id
			WHERE a.petition_id=".$petition_id."";
	
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);	
?>
<form method="post" name="petition_reopen" id="petition_reopen">
<div class="contentMainDiv" style="width:98%;background-color:#bc7676;margin-right:auto;margin-left:auto;">
<div class="contentDiv" style="background-color:#F4CBCB;">
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">
        	<?PHP echo $label_name[30];//Petition Re-open?>
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
		if ($row['off_level_dept_name'] != '') {
			$off_level_dept_name = $row['off_level_dept_name'];
		}else{
			$off_level_dept_name='';
		}
		$griev_loc_id=$row['griev_loc_id'];
		$loc=$row['loc'];
		$off_level_id=$row['off_level_id'];
		$off_level_dept_id=$row['off_level_dept_id'];
		$off_level_pattern_id=$row['off_level_pattern_id'];
		$dept_id=$row['dept_id'];
		$griev_subtype_id=$row['griev_subtype_id'];
		$petition_id=$row['petition_id'];
		$f_action_entby=$row['f_action_entby'];
		$dept_off_level_pattern_id=$row['dept_off_level_pattern_id'];
		$off_level_id_loc=$row['off_level_id_loc'];
		
		$source_id=$row['source_id'];
	?>
     <!-- Petition Details Building Block : Begins Here-->
	<input type="hidden" name="griev_loc_id" id="griev_loc_id" value="<?PHP echo $griev_loc_id; ?>"  />
	<input type="hidden" name="off_level_dept_id" id="off_level_dept_id" value="<?PHP echo $off_level_dept_id; ?>"  />
	<input type="hidden" name="off_level_pattern_id" id="off_level_pattern_id" value="<?PHP echo $off_level_pattern_id; ?>"  />
	<input type="hidden" name="dept_id" id="dept_id" value="<?PHP echo $dept_id; ?>"  />
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?PHP if($off_level_id!=''){echo $off_level_id;}else{	echo $off_level_id_loc; } ?>"/>
	<input type="hidden" name="hid_off_loc_id" id="hid_off_loc_id" value="<?PHP echo $userProfile->getOff_loc_id(); ?>"/>
    <input type="hidden" name="griev_subcode" id="griev_subcode" value="<?php echo $griev_subtype_id;?>" />
	<input type="hidden" name="petition_id" id="petition_id" value="<?php echo $petition_id;?>" />	
	<input type="hidden" name="f_action_entby" id="f_action_entby" value="<?php echo $f_action_entby;?>" />
	<input type="hidden" name="dept_off_level_pattern_id" id="dept_off_level_pattern_id" value="<?php echo $dept_off_level_pattern_id;?>" />
	<input type="hidden" name="source_id" id="source_id" value="<?php echo $source_id;?>" />		
	<input type="hidden" name="up_off_level_id" id="up_off_level_id" value="<?php echo $userProfile->getOff_level_id();;?>" />		
	<input type="hidden" name="userId" id="userId" value="<?PHP echo $_SESSION['USER_ID_PK']; ?>"/>
	 
	<tr>
		<td><?PHP echo $label_name[0];//Petition No and Date?></td> 
		<td><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']. ' ('.$row['pet_type_name'].')'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[7];//Mobile Number?> : <b><?php echo $row['comm_mobile'];?></b></td>
	</tr><tr>	
		<td><?PHP echo 'Source Name';//Source Name & Sub Source Name?></td>
		<td><?php echo $source_name_details;?></td>
	</tr>
    <!--tr>
		<td><?PHP echo $label_name[3];//Department?></td>
        <td ><?php echo $row['dept_name'];?></td>
	</tr-->

	<tr>
    	<td><?PHP echo $label_name[2];//Petition Main Type and Petition Sub Type?></td>
		<td><?php echo $row['griev_type_name'].' & '.$row['griev_subtype_name'];?></td>
	</tr>	
    	
	
    <tr>
    	<td><?PHP echo $label_name[4];//Petition Details?></td>
		<td><?php echo $row['grievance'];?></td>
	
	</tr>
      
   
    <tr>
    	<td colspan='2'><?php echo ' <br>';?></td>
	</tr>	
    
	<tr>
    	<td><?PHP echo $label_name[17];//Petitioner Name?></td>
		<td><?php echo $row['petitioner_name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$label_name[18].': '.$row['father_husband_name'];?></td>
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
	<!--tr>
	<td><?php echo  $label_name[37]//Petitioner Community and Category;?></td>
	<td><?PHP echo $community_category_name; ?></td>
	</tr-->
	<tr>
	<?php } ?>
	<tr>
		
		<?php 
		if($row['comm_pincode']!=''){
			$pin=$row['comm_pincode'];
		}else{
			$pin=$row['griev_pincode'];
		}
				
				$address = $row['comm_doorno'].', '.$row['comm_street'].','.$row['comm_area'].', Pincode - '.$pin.'.';
			
			
		?>
    	<td><?PHP echo $label_name[19];//Address?></td>
		<td><?php echo $address;?></td>
	</tr>
	
	<tr <?php echo ($row['passport_number'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[41];//Passport Number?></td>
        <td><?php echo $row['passport_number'];?></td>        
	</tr> 
        
    <!--tr <?php echo ($row['comm_mobile'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[7];//Mobile Number?></td>
        <td><?php echo $row['comm_mobile'];?></td>        
	</tr-->    
	<tr <?php echo ($row['comm_email'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[40];//Mobile Number?></td>
        <td><?php echo $row['comm_email'];?></td>        
	</tr>
    <tr>
    	<td class="sub_heading" style="text-align:left !important;">Document</td>
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
   	ORDER BY pet_action_id desc";

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$rowArr = $result->fetch(PDO::FETCH_NUM);
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
	<input type="button" name="reopen" id="reopen" value="<?PHP echo 'Re-open'; //View. ?>"  onClick="reopenProcess();"/> 	
	<input type="button" name="close" id="close" value="<?PHP echo 'Back'; //View. ?>"  onClick="closePopup();"/> 
	
	
	
	</td>
		</tr>
	<tr id="reopen_process" style="display:none;">
		<td align="center" colspan="7" class="btn" bgcolor="#DBA0A0">
		<b><?php echo "Enquiry Filing Officer: "?></b>
		<span id="sup_off">		
		<select name="supervisory_officer" id="supervisory_officer" class="select_style" onchange="loadConcernedOfficer();">
		<option value="">--Select Enquiry Filing Officer--</option>
		</select>
		</span>
		&nbsp;&nbsp;&nbsp;&nbsp;
		
		<span id="conc_off">
		<b><?php echo "Enquiry Officer: "?></b>
		<select name="concerned_officer" id="concerned_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style" style="width:275px">
		<option value="">--Select Enquiry Officer--</option>
		</select>
        </span>			
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" id="save_reopn" style="width:140px" value="Save Re-open" onclick="saveReopen()">
		</td>
		</tr>	
	</tbody>
</table>

</div>
</div>
</form> 
<?php
	}
	include("footer.php"); 
?>