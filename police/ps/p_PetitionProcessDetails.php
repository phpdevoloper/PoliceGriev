<?PHP 
error_reporting(0);
session_start();
include("header_menu.php");
include("db.php");
include("common_date_fun.php");

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
	else if(stripQuotes(killChars($_GET['petition_id']!="")))
		$received_petition_id = stripQuotes(killChars($_GET['petition_id']));
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
<title style="display:none;">Petition Processing Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>

<script type="text/javascript">

window.onunload = function(){
   window.opener.focus(); 
}
function closePopup() {
	window.opener.focus(); 
	window.close();
}

</script>

</head>
<body>
<?php

	$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name, subsource_name,
	--fwd_office_level_name
	off_level_dept_name,griev_type_name, griev_subtype_name,dept_name, grievance, canid, comm_doorno, 
	comm_aptmt_block, comm_street, comm_area, griev_district_name, griev_taluk_name, griev_rev_village_name, comm_pincode, comm_email, comm_phone, comm_mobile, comm_district_name,comm_taluk_name,comm_rev_village_name, griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_tname, griev_taluk_tname,griev_rev_village_tname, griev_block_name, griev_lb_village_name, griev_lb_urban_name,griev_division_name,griev_circle_name,
	griev_subdivision_name, griev_pincode,aadharid,pet_type_name,comm_state_name,comm_country_name,
	pet_community_name,pet_community_tname, petitioner_category_name, petitioner_category_tname,passport_number,off_level_dept_name
	FROM vw_pet_master WHERE petition_id=".$petition_id."";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);	
?>
<div class="contentMainDiv" style="width:98%;background-color:#bc7676;margin-right:auto;margin-left:auto;">
<div class="contentDiv" style="background-color:#F4CBCB;">
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">
        	<?PHP echo $label_name[14];//Petition Processing Details?>
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
	?>
     <!-- Petition Details Building Block : Begins Here-->
	
    
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
    	<td class="sub_heading" style="text-align:left !important;">Petition Document</td>
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

<!--    	<img src="images/download.png" onclick="download_document(<?php //echo $key['doc_id']; ?>)"/>-->
        
    <?php } ?><script>
	function download_document(url,src){
		//window.location.href="http://locahost/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
		window.location.href="http://14.139.183.34/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
	}
				</script>
        </td>
    </tr>  
    
	<tr>
    	<td class="sub_heading" style="text-align:left !important;">Action Document</td>
        <td style="color:blue; text-decoration:underline">
        <?php
			$query_doc = "select action_doc_id,action_doc_name from pet_action_doc where petition_id in('".$row['petition_id']."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	
			
	?>
    <?php
		foreach($doc_row as $key){
		?>
			<span id="span_dwnd" onClick="download_document(<?php echo $key['action_doc_id']; ?>,'A')"><?php echo $key['action_doc_name']; ?></span>

        
    <?php } ?><script>
	function download_document(url,src){
		//window.location.href="http://locahost/police/pm_petition_doc_download.php?doc_id="+url+"&source="+src;
		window.location.href="http://14.139.183.34/police/ps/pm_petition_doc_download.php?doc_id="+url+"&source="+src;

	}
				</script>
        </td>
    </tr>  
	
     <!-- Petitioner Details Building Block : Ends Here--> 
		<?php
		$link_sql="select a.pet_ext_link_id,a.pet_ext_link_name,b.pet_ext_link_no,c.circle_name,fir_csr_year from lkp_pet_ext_link_type a
		inner join pet_master_ext_link b on b.pet_ext_link_id=a.pet_ext_link_id
		inner join mst_p_sp_circle c on c.circle_id=b.circle_id
		where b.petition_id=".$row['petition_id']."";
		$link_doc = $db->query($link_sql);
		$link_doc_array = $link_doc->fetchall(PDO::FETCH_ASSOC);
		$j=1;
		$link_doc_list="";
		foreach($link_doc_array as $link_document){
			$link_doc_list.= $j++.") ".$link_document['pet_ext_link_name']." Details: Police Station: ".$link_document['circle_name'].", No. : ".$link_document['pet_ext_link_no']."/".$link_document['fir_csr_year'].". &emsp;<br>   ";
		}
		if (sizeof($link_doc_array) > 0) {
	?>	
		<tr>
    	<td class="sub_heading" style="text-align:left !important;">FIR / CSR Details</td>
        <td><?php echo substr(trim($link_doc_list), 0,-1); ?></td></tr>	
    <?PHP
		}
		
		/*
		select petition_no, org_petition_no, to_char(l_action_entdt, 'dd-mm-yyyy hh12:mi:ss PM')::character varying AS action_date, l_action_type_code, action_type_name, action_type_tname 
		from fn_clubbed_petition_status(15);
		*/
		$link_pet_status="select petition_no, org_petition_no, to_char(l_action_entdt, 'dd-mm-yyyy hh12:mi:ss PM')::character varying AS action_date, l_action_type_code, action_type_name, action_type_tname, l_action_type_code,dept_desig_name,dept_desig_tname,location_name,location_tname, l_to_whom,to_dept_desig_name,to_dept_desig_tname,to_location_name,to_location_tname
		from fn_clubbed_petition_status(".$row['petition_id'].")";
		$status_rs=$db->query($link_pet_status);
		$status_rowarray = $status_rs->fetchall(PDO::FETCH_ASSOC);
		$k=1;
		foreach($status_rowarray as $status_row){
			$petition_no=$status_row['petition_no'];
			$action_type_name=$status_row['action_type_name'];
			$action_type_tname=$status_row['action_type_tname'];
			$action_date=$status_row['action_date'];
			if($_SESSION['lang']=='T'){
				$action_ent ="";
				$to_action_ent="";
				$action_ent = "<b>".$status_row['dept_desig_tname'].", ".$status_row['location_tname']."</b>";	
				if($status_row['l_to_whom']!=''){
				$action_ent.=" and sent to ";
				$to_action_ent = $status_row['to_dept_desig_tname'].", ".$status_row['to_location_tname'];
				}				
			}else{
				$action_ent ="";
				$to_action_ent="";
				$action_ent = "<b>".$status_row['dept_desig_name'].", ".$status_row['location_name']."</b>";
				if($status_row['l_to_whom']!=''){
				$action_ent.=" and sent to ";
				$to_action_ent = $status_row['to_dept_desig_name'].", ".$status_row['to_location_name'];
				}				
			}
			if($status_row['l_action_type_code']=='A'||$status_row['l_action_type_code']=='R'){
				if($status_row['l_action_type_code']=='A'){
					$color='#118e11;font-weight:bolder;';
				}if($status_row['l_action_type_code']=='R'){
					$color='#bd0505;font-weight:bold;';
				}
				$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: <lim style='color:".$color."'>".$status_row['action_type_name']."</lim> on ".$status_row['action_date']." by ".$action_ent." &emsp;<br>   ";
			}else{
				//$link_petition_status.= $k++.") ".$status_row[petition_no]." Status:  Under Process &emsp;<br>";
				if($status_row['action_type_name']!=''){
				$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: ".$status_row['action_type_name']." on ".$status_row['action_date']." by ".$action_ent." <b>".$to_action_ent."</b>&emsp;<br>";
				}else{
					$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: No Action Taken So far.&emsp;<br>";
				}
			}
		}
		if (sizeof($status_rowarray) > 0) {
			?>
			<tr>
    	<td class="sub_heading" style="text-align:left !important;">Linked Petition(s) Status</td>
        <td><?php echo $link_petition_status; ?></td></tr>	
			<?php
		}
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
 	$query="SELECT action_type_name, file_no, to_char(file_date, 'DD/MM/YYYY') as file_date, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt_fmt, 
	action_entby, dept_desig_name, off_level_dept_name, off_loc_name AS location,	
	to_whom, dept_desig_name1,off_level_dept_name1, off_loc_name1 AS location1
	FROM vw_pet_actions WHERE petition_id=".$petition_id." ORDER BY pet_action_id desc";

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo $rowArr = $result->fetch(PDO::FETCH_NUM);
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
			<input type="button" name="close" id="close" value="<?PHP echo 'Back'; //View. ?>"  onClick="closePopup();"/> 
			</td>
		</tr>
		
	</tbody>
</table>

</div>
</div>
 
<?php
	include("footer.php"); 
?>