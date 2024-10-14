<?PHP 
error_reporting(0);
session_start();
include("common_date_fun.php");
$form_tocken=stripQuotes(killChars($_POST['formtocken']));
if($_SESSION['formptoken'] != $form_tocken)
{
   header('Location: logout.php');
   exit;
} else {
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   header("Location: logout.php");
   exit;
}
$petition_no=stripQuotes(killChars($_POST['petition_no']));

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Petition Processing Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>
</head>
<body>
<?php
include("db.php");

	   $query = "
	SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, 
	idtype_name, id_no, source_name,subsource_name,  griev_type_name, griev_subtype_name, grievance, canid,
	
	comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name, comm_taluk_name, comm_rev_village_name, comm_pincode, comm_email, comm_phone, comm_mobile,
	
	griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name, griev_taluk_name, griev_rev_village_name,
	griev_block_name, griev_lb_village_name, griev_lb_urban_name, griev_pincode,aadharid
	
	FROM vw_pet_master 
	WHERE petition_no='$petition_no'";
	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

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
<div class="contentMainDiv">
<div class="contentDiv">
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="6" class="heading" style="background-color: #BC7676;">
        	<?PHP echo $label_name[0]; //Petition Processing Details?>
        </td>
    </tr>
	<tr>
    	<td colspan="6" class="heading">
        	<?PHP echo $label_name[1]; //Petition Details?>
        </td>
    </tr>
   
	<?PHP 
if(sizeof($rowarray)!=0)
{
	
	foreach($rowarray as $row)
	{
	?>
	<tr>
    	<td colspan="6" class="sub_heading"><?PHP echo $label_name[2]; //Grievance Details?></td>
    </tr>
    
	<tr>
		<td><?PHP echo $label_name[3]; //Petition No. & Date?></td>
		<td><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']; ?></td>
		<td><?PHP echo $label_name[4]; //Source Name?></td>
		<td><?php echo $row['source_name'];?></td>	
        <td>Sub Source Name </td>
		<td><?php echo  $row['subsource_name'];?></td>
	</tr>
    
    <tr>
    	<td>Grievance Type</td>
		<td><?php echo $row['griev_type_name'];?></td>
        <td>Grievance Sub Type</td>
		<td><?php echo $row['griev_subtype_name'];?></td>	
        <td>Department</td>
        <td ><?php echo $row['dept_name'];?></td>
	</tr>
    
	<tr>
    	<td>Grievance/ Request</td>
		<td colspan="5"><?php echo $row['grievance'];?></td>
	
	</tr>
	
    <?PHP
	if($row['griev_taluk_name']!=""){
	?>
    <tr>
    	<td><?PHP echo $label_name[27]; //District?></td>
        <td><?php echo $row['griev_district_name'];?></td>
        <td><?PHP echo $label_name[26]; //Taluk?></td>
        <td><?php echo $row['griev_taluk_name'];?></td>
    	<td><?PHP echo $label_name[25]; //Revenue Village?></td>
        <td><?php echo $row['griev_rev_village_name'];?></td>        
	</tr>
    <?PHP 
	}
	else if($row['griev_block_name']!=""){
	?>
    <tr>
    	<td><?PHP echo $label_name[27]; //District?></td>
        <td><?php echo $row['griev_district_name'];?></td>
       	<td><?PHP echo $label_name[38]; //Block Name?></td>
        <td><?php echo $row['griev_block_name'];?></td>
        <td><?PHP echo $label_name[39]; //Local Body Village?></td>
        <td><?php echo $row['griev_lb_village_name'];?></td>
	</tr>
    <?PHP 
	}
	else{
	?>
    <tr>
    	<td><?PHP echo $label_name[27]; //District?></td>
        <td><?php echo $row['griev_district_name'];?></td>
        <td><?PHP echo $label_name[13]; //Urban Local Body?></td>
        <td colspan="3"><?php echo $row['griev_lb_urban_name'];?></td>
	</tr>
    <?PHP
	}?>
    <tr>
    	
        
	</tr>
    
   
    
    <tr>
    	<td colspan="6" class="sub_heading"><?PHP echo $label_name[15]; //Petitioner Details?></td>
    </tr>
    
	<tr>
    	<td>CAN ID</td>
		<td><?php echo $row['canid'];?></td>
		<td>Aadhar ID </td>
		<td><?php echo $row['aadharid'];?></td>
        <td><?PHP echo "Other Id Type & No."; //ID Proof Type?></td>
		<td><?php echo $row['idtype_name']."  ".$row['id_no'];?></td>
		
	</tr>
	
    <tr>
    	<td><?PHP echo $label_name[16]; //Petitioner Name?></td>
		<td><?php echo $row['petitioner_name'];?></td>
		<td><?PHP echo $label_name[17]; //Father / Spouse Name ?></td>
		<td><?php echo $row['father_husband_name'];?></td>
        <td><?PHP echo $label_name[18]; //Gender?></td>
		<td><?php echo $row['gender_name'];?></td>		
	</tr>
    
    
    <!-- UPTO THIS Completed-->
    <tr>
    	<td><?PHP echo $label_name[22]; //Door No. / Flat No. ?></td>
        <td><?php echo $row['comm_doorno'];?></td>
        <td><?PHP echo $label_name[23]; //Street?></td>
        <td><?php echo $row['comm_street'];?></td>
        <td><?PHP echo $label_name[24]; //Area / Ward?></td>
        <td><?php echo $row['comm_area'];?></td>
	</tr>
    
    <tr>
	 <td>District</td>
        <td><?php echo $row['comm_district_name'];?></td>     	
        <td>Taluk</td>
        <td><?php echo $row['comm_taluk_name'];?></td>
       <td>Revenue Village</td>
        <td><?php echo $row['comm_rev_village_name'];?></td>
	</tr>
    
    <tr>
    	<td><?PHP echo $label_name[28]; //Pincode?></td>
        <td><?php echo $row['comm_pincode'];?></td>
        <td><?PHP echo $label_name[29]; //Mobile Number?></td>
        <td><?php echo $row['comm_mobile'];?></td>
        <td><?PHP echo $label_name[30]; //e-Mail?></td>
        <td><?php echo $row['comm_email'];?></td>
	</tr>
    <tr>
    	<td class="sub_heading" style="text-align:left !important;"><?PHP echo $label_name[31]; //Document?></td>
        <td colspan="5">
        <?php
			$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row['petition_id']."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	
			
	?>
    <?php
		foreach($doc_row as $key){
		?>
			<span id="span_dwnd" onClick="download_document(<?php echo $key['doc_id']; ?>)"><?php echo $key['doc_name'].'&nbsp;&nbsp;&nbsp;'; ?></span>

<!--    	<img src="images/download.png" onclick="download_document(<?php //echo $key['doc_id']; ?>)"/>-->
        
    <?php } ?><script>
					function download_document(url){
						//alert("http://10.163.30.9/ed_gdp_tnega/fileupload1.php?doc_id="+url);
						window.location.href="http://14.139.183.34/police/ps/pm_petition_doc_download.php?doc_id="+url;
					}
				</script>
        </td>
    </tr>   	    
    <?PHP
	}
	?>
</tbody>
</table>

<table class="gridTbl">
	<thead>
    	<tr>
            <th colspan="7" class="emptyTR">
                <?PHP echo $label_name[32]; //Processing Details?>
            </th>
        </tr>
		<tr>
			<th><?PHP echo $label_name[33]; //Action Taken Date & Time?></th>
			<th><?PHP echo $label_name[34]; //Action Type?></th>
			<th>File No. & File Date</th>
			<th><?PHP echo $label_name[35]; //Action Remarks?></th>
            <th><?PHP echo $label_name[36]; //Action Taken By?></th>
            <th><?PHP echo $label_name[37]; //Addressed To?></th>
        </tr>
	</thead>
	
	<tbody>
		
<?php
    $query=" SELECT action_type_name, file_no, to_char(file_date, 'DD/MM/YYYY') as file_date, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt_fmt, 
	action_entby, dept_desig_name, off_level_dept_name, dept_name, off_loc_name AS location,	
	to_whom, dept_desig_name1,off_level_dept_name1, dept_name1,	off_loc_name1 AS location1
	FROM vw_pet_actions
	WHERE petition_no='$petition_no'
   	ORDER BY action_entdt desc";

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarray as $row)
	{

		?>
        <tr>
            <td><?php echo $row['action_entdt_fmt'];?></td>
            <td><?php echo $row['action_type_name'];?></td>
			<td><?php echo !empty($row['file_no'])? '<b>'.$row['file_no'].'</b>'."<br>".$row['file_date'] : "";?></td> 
            <td><?php echo $row['action_remarks'];?></td>
			<td><?php echo $row['dept_desig_name'].', ' .$row['off_level_dept_name'].', ' .$row[location];?></td>
			<td><?php echo !empty($row['dept_desig_name1'])?$row['dept_desig_name1']. ', ' .$row['off_level_dept_name1'].', ' .$row['location1'] : "";?></td>			
        </tr>
		<?php
	}
	if(sizeof($rowarray)==0)
	{
		?>
		<tr>
			<td colspan="7"><?PHP echo $label_name[40]; //Yet not taken to processing the petition?></td>
		</tr>
	<?PHP
	}
}
else {
	?>
    <tr>
			<td colspan="7" style="font-size:18px; text-align:center;"><?PHP echo "No Records Found "; //No Records Found ?></td>
	</tr>
 <?php } ?>
    	<tr>
			<td colspan="7" class="emptyTR"></td>
		</tr>
	</tbody>
</table>

</div>
</div>
 
<?php
}
include("footer.php");
?>