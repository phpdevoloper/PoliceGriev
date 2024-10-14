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

function linkPetition() {
	var petition_id = $('#petition_id').val();
	var link_documet_id = $('#link_documet_id').val();
	var link_documet_no = $('#link_documet_no').val();
		var strconfirm = confirm("This action will link the petition with the document given. Are you sure to link?");
	if (strconfirm == true) {
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "pm_pet_detail_entry_get_dept_action.php",  
			data: "mode=link_petition"+"&petition_id="+petition_id+"&link_documet_id="+link_documet_id+"&link_documet_no="+link_documet_no,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				var count = $(xml).find('result').eq(0).text();
				if (count > 0) {
					alert("Petition is linked");
					//window.location.reload();
					document.petition_reopen.submit();
				}
			}
		});
	}
}
function removeLink(pet_id, link_id) {
	var strconfirm = confirm("Are you sure to remove the link?");
	if (strconfirm == true) {
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "pm_pet_detail_entry_get_dept_action.php",  
			data: "mode=remove_link"+"&petition_id="+pet_id+"&link_id="+link_id,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				var count = $(xml).find('result').eq(0).text();
				if (count > 0) {
					alert("Link is removed successfully");
					//window.location.reload(true);
					document.petition_reopen.submit();
				}
			}
		});
	}
}
/* function checkForPetNo() {
	var link_documet_id = $('#link_documet_id').val();
	var link_documet_no = $('#link_documet_no').val();
	var petition_no = $('#petition_no').val();
	if (link_documet_id == '') {
		alert("Please select a document type to link");
		document.getElementById("link_documet_no").focus();
		document.getElementById("link_documet_no").value="";
		return false;
	} else if (link_documet_id == 3) {
			if (petition_no == link_documet_no) {
				alert("A petition can not be linked to itself. Please check!!!");
				document.getElementById("link_documet_no").focus();
				document.getElementById("link_documet_no").value="";
				return false;
			} else {
				$.ajax({
				type: "POST",
				dataType: "xml",
				url: "pm_pet_detail_entry_get_dept_action.php",  
				data: "mode=check_pet_no"+"&link_documet_id="+link_documet_id+"&link_documet_no="+link_documet_no,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					var exists = $(xml).find('exists').eq(0).text();
					var linked = $(xml).find('linked').eq(0).text();
					var master = $(xml).find('master').eq(0).text();
					if (exists == 0) {
						alert("Given petition no does not exists, Kindly check");
						document.getElementById("link_documet_no").focus();
						document.getElementById("link_documet_no").value="";
						return false;
					} else {
						if (linked == 1) {
							alert("Given petition no already linked with another petition, Kindly check");
							document.getElementById("link_documet_no").focus();
							document.getElementById("link_documet_no").value="";
							return false;
						} else {
							if (master == 1) {
								alert("Given petition no already having link with another petition, Kindly check");
								document.getElementById("link_documet_no").focus();
								document.getElementById("link_documet_no").value="";
								return false;
							}
						}
						
					}
				}
			});
		}
	}
	
	
} */
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

function chkLinkDocType() {
	var link_documet_id = $('#link_documet_id').val();
	var mobile_number = $('#mobile_number').val();
	var petition_id = $('#petition_id').val();
	if (link_documet_id == 3) {
		$('#link_documet_no').attr('readonly', true);
		openForm("get_petitions_to_link_form.php?open_form=P1&mobile_number="+mobile_number+"&petition_id="+petition_id, "p1_petition_search");	
	} else {
		$('#link_documet_no').attr('readonly', false); 
	}
}

function p1_returnPetionDetails(petition_id){
	$('#link_documet_no').val(petition_id);
}
</script>

</head>
<body>
<?php	
$query = "
	SELECT a.petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name, gender_name, 
	TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_id,source_name, subsource_name,griev_type_name, griev_subtype_name,dept_name, grievance, canid, comm_doorno, 
	comm_aptmt_block, comm_street, comm_area, griev_district_name, griev_taluk_name, griev_rev_village_name, comm_pincode, comm_email, 
	comm_phone, comm_mobile, comm_district_name,comm_taluk_name,comm_rev_village_name,comm_state_name, comm_country_name,
	griev_doorno, griev_aptmt_block, griev_street, 
	griev_area, griev_district_tname, griev_taluk_tname,griev_rev_village_tname, griev_block_name,
	griev_lb_village_name, griev_lb_urban_name,griev_division_name, griev_pincode,aadharid,pet_type_name, griev_district_id,  griev_taluk_id, griev_rev_village_id,  griev_block_id, griev_lb_village_id, 
       griev_lb_urban_id, griev_division_id, griev_subdivision_id, griev_circle_id,off_level_pattern_id,dept_id,griev_subtype_id,f_action_entby

	FROM vw_pet_master  a
	inner join pet_action_first_last  b on b.petition_id=a.petition_id
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
		$f_action_entby=$row['f_action_entby'];
		
		$source_id=$row['source_id'];
	?>

    <input type="hidden" name="griev_subcode" id="griev_subcode" value="<?php echo $griev_subtype_id;?>" />
	<input type="hidden" name="petition_id" id="petition_id" value="<?php echo $petition_id;?>" />	
	<input type="hidden" name="f_action_entby" id="f_action_entby" value="<?php echo $f_action_entby;?>" />
	<input type="hidden" name="source_id" id="source_id" value="<?php echo $source_id;?>" />		
	<input type="hidden" name="userId" id="userId" value="<?PHP echo $_SESSION['USER_ID_PK']; ?>"/>
	<input type="hidden" name="petition_no" id="petition_no" value="<?PHP echo $row['petition_no']; ?>"/>
	<input type="hidden" name="mobile_number" id="mobile_number" value="<?PHP echo $row['comm_mobile']; ?>"/>
	<tr>
		<td><?PHP echo $label_name[0];//Petition No and Date?></td> 
		<td><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']. ' ('.$row['pet_type_name'].')'; ?></td>
	</tr><tr>	
		<td><?PHP echo $label_name[1];//Source Name & Sub Source Name?></td>
		<td><?php echo $row['source_name']. $row['subsource_name'];?></td>
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
	if($row['griev_taluk_name']!="")
				$pet_off_address = $row['griev_rev_village_name'].$label_name[22].', '. $row['griev_taluk_name'].$label_name[21].', '.$row['griev_district_name'].$label_name[20];
			else if ($row['griev_block_name']!="")
				$pet_off_address = $row['griev_lb_village_name'].$label_name[25].', '. $row['griev_block_name'].$label_name[24].', '.$row['griev_district_name'].$label_name[20]; //Block Village Panchayat
			else if($row['griev_lb_urban_name']!="") 
				$pet_off_address = $row['griev_district_name'].$label_name[20].', '. $row['griev_lb_urban_name'].$label_name[25];   //Urban Local Body
			else
				$pet_off_address = $row['griev_district_name'].$label_name[20].', '. $row['griev_division_name'].$label_name[26];   //Office
	?>
    <tr>
    	<td><?PHP echo $label_name[5];//Petition Office Address?></td>
        <td><?php echo $pet_off_address;?></td>
	</tr>	
    
	<tr>
    	<td><?PHP echo $label_name[17].' & '.$label_name[18];//Petitioner Name,Father / Spouse Name and Address?></td>
		<td><?php echo $row['petitioner_name'].', '.$label_name[18].': '.$row['father_husband_name'];?></td>
	</tr>

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
	
   
        
    <tr <?php echo ($row['comm_mobile'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[7];//Mobile Number?></td>
        <td><?php echo $row['comm_mobile'];?></td>        
	</tr>    
	<tr <?php echo ($row['comm_email'] == "")? "style='display:none;'":"" ?>>    	
        <td><?PHP echo $label_name[40];//Mobile Number?></td>
        <td><?php echo $row['comm_email'];?></td>        
	</tr>
    <tr>
    	<td class="sub_heading" style="text-align:left !important;">Document</td>
        <td style="color:blue; text-decoration:underline">
        <?php
			$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row[petition_id]."')";
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
		window.location.href="http://14.139.183.34/police/ps/pm_petition_doc_download.php?doc_id="+url+"&source="+src;

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
	} ?>
<?php	
}else {
	?>
    <tr>
			<td colspan="7" style="font-size:18px; text-align:center;"><?PHP echo "No Records Found "; //No Records Found ?></td>
	</tr>
 <?php } ?>
	
	<tr><td colspan="7" class="emptyTR"></td></tr>
	</tbody>
</table>
<table  align="center" class="gridTbl">
<thead>	
	<tr>
		<th colspan="4" class="emptyTR">
			<?PHP echo 'Link Details';//Processing Details?>
		</th>
	</tr>
	<tr>
		<th><?PHP echo 'S.No';//Action Taken Date & Time?></th>
		<th><?PHP echo 'Linked With';//Action Type?></th>
		<th><?PHP echo 'Number';//File No. & File Date?></th>
		<th><?PHP echo 'Remove';//Action Remarks?></th>
        </tr>
	</thead>
	<?php
		$sql="select b.petition_id,b.pet_master_ext_link_id,a.pet_ext_link_name,b.pet_ext_link_no from lkp_pet_ext_link_type a
		inner join pet_master_ext_link b on b.pet_ext_link_id=a.pet_ext_link_id where b.petition_id=".$petition_id."";
		$result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		if(sizeof($rowarray)==0) { ?>
			<tr>
			<td colspan="7">No links found so far</td>
			</tr>
		<?php
		} else {
			$i=0;
			foreach($rowarray as $row)
			{
				?>
			<tr>
            <td><?php echo ++$i;?></td>
            <td><?php echo $row['pet_ext_link_name'];?></td>
			 <td><?php echo $row['pet_ext_link_no'];?></td>
			 <td><input type="button" id="remove_lnk_<?php echo $row['pet_ext_link_id']; ?>" style="width:90px" value="Remove Link" onclick="removeLink(<?php echo $petition_id; ?>,<?php echo $row['pet_master_ext_link_id']; ?>)"></td>
        </tr>
				
				<?php
			}
			
		}
	?>
</table>
<table  align="center">
	<tr id="link_process">
		<td colspan="7" class="btn" bgcolor="#DBA0A0">
		<b><?php echo "Select to Link: "?></b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php 
			$sql="SELECT pet_ext_link_id, pet_ext_link_name, pet_ext_link_tname FROM lkp_pet_ext_link_type order by pet_ext_link_id";
			$result = $db->query($sql);			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
		?>
		<span id="link_documet_label">		
		<select name="link_documet_id" id="link_documet_id" class="select_style" onChange="chkLinkDocType();">
		<option value="">--Select Link Document--</option>
		<?php 
			foreach($rowarray as $row)
			{
				$pet_ext_link_id=$row['pet_ext_link_id'];
				$pet_ext_link_name=$row['pet_ext_link_name'];
				print("<option value='".$pet_ext_link_id."'>".$pet_ext_link_name."</option>");

			}
		?>
		</select>
		</span>
		&nbsp;&nbsp;&nbsp;&nbsp;
		
		<span id="link_documet_drop_down">
        <input type="text" name="link_documet_no" id="link_documet_no"/>
        </span>			
		
		<input type="button" id="link_documet_btn" style="width:180px" value="Link Petition" onclick="linkPetition()">
		<input type="hidden" id="s_pet_no" name="s_pet_no" value="<?php echo $row[petition_no]; ?>" >
		</td>
		</tr>	
</table>

</div>
</div>
</form> 
<?php
	include("footer.php"); 
?>