<?php 
include("header.php");
include("db.php");
include("common_date_fun.php");
$_SESSION['lang'] = 'E';
$qry = "select label_name,label_tname from apps_labels where menu_item_id=82 order by ordering";

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
<title>Petition Processing Details</title>
<script type="text/javascript">
function print_page()
{
document.getElementById("header").style.visibility='hidden';
document.getElementById("btn_row").style.display='none';
print();
document.getElementById("header").style.visibility='visible';
document.getElementById("btn_row").style.display='';
}

function goback(){
	window.location = 'index.php';	
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>
<body>
<?php
	$pet_id=stripQuotes(killChars(trim($_GET['pet_id'])));
	
	$stype=stripQuotes(killChars(trim($_GET['stype'])));
		
 		$query = "	SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, 
		petitioner_name,  father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, 
		idtype_name, id_no, source_name,subsource_name,  griev_type_name, griev_subtype_name,dept_name, grievance, canid,
		
		comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name, comm_taluk_name, 
		comm_rev_village_name, comm_pincode, comm_email, comm_phone, comm_mobile,
		
		griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name, 
		griev_taluk_name, griev_rev_village_name,
		griev_block_name, griev_lb_village_name, griev_lb_urban_name, griev_pincode,aadharid
		
		FROM vw_pet_master 
		WHERE petition_id=".$pet_id;
		 
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		
?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">

<div class="contentMainDiv" style="width:98%;background-color:#bc7676;margin-right:auto;margin-left:auto;" align="center">
<div class="contentDiv" >
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">
		
        	<?PHP echo $label_name[29];//Petition Processing Details?>
        </td>
    </tr>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">
        	<?PHP echo $label_name[15];//Petition Details?>
        </td>
    </tr>
	<?PHP 
if(sizeof($rowarray)!=0)
{	
	foreach($rowarray as $row)
	{
	?>
     <!-- Petition Details Building Block : Begins Here-->
	
    
	<tr>
		<td><?PHP echo $label_name[0];//Petition No and Date?></td> 
		<td><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[7];//Mobile Number?> : <b><?php echo $row['comm_mobile'];?></b></td>
	</tr><tr>	
		<td><?PHP echo $label_name[1];//Source Name & Sub Source Name?></td>
		<td><?php echo $row['source_name']. $row['subsource_name'];?></td>
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
      
    
   
    <?PHP
	if($row['griev_taluk_name']!="")
				$pet_off_address = $row['griev_rev_village_name'].$label_name[22].', '. $row['griev_taluk_name'].$label_name[21].', '.$row['griev_district_name'].$label_name[20];
			else if ($row['griev_block_name']!="")
				$pet_off_address = $row['griev_lb_village_name'].$label_name[25].', '. $row['griev_block_name'].$label_name[24].', '.$row['griev_district_name'].$label_name[20]; //Block Village Panchayat
			else if($row['griev_lb_urban_name']!="") 
				$pet_off_address = $row['griev_district_name'].$label_name[20].', '. $row['griev_lb_urban_name'].$label_name[26];   //Urban Local Body
			else
				$pet_off_address = $row['griev_district_name'].$label_name[20].', '. $row['griev_division_name'].$label_name[27];   //Office
	?>
    <tr>
    	<td><?PHP echo $label_name[5];//Petition Office Address?></td>
        <td><?php echo $pet_off_address;?></td>
	</tr>	
    <tr><td colspan='2' height='100%'style='color:#FEEDED'><?php echo ' .';?></td></tr>
	<tr>
    	<td><?PHP echo $label_name[17].' & '.$label_name[18];//Petitioner Name,Father / Spouse Name and Address?></td>
		<td><?php echo $row['petitioner_name'].', '.$label_name[18].': '.$row['father_husband_name'];?></td>
	</tr>

	<tr>
		
		<?php 
			if ($row['comm_doorno'] != '' && $row['comm_street'] != '') {
				$address = $row['comm_rev_village_name'].$label_name[22].', '.$row['comm_taluk_name'].$label_name[21].', '.$row['comm_district_name'].$label_name[20];
				$address=' Pincode - '.$row['comm_pincode'].'.';
				$address = $row['comm_doorno'].', '.$row['comm_street'].','.$row['comm_area'].','.$address;
			} else {
				$address = $row['comm_rev_village_name'].$label_name[22].', '.$row['comm_taluk_name'].$label_name[21].', '.$row['comm_district_name'].$label_name[20];
				$address='';
			}
			
		?>
    	<td><?PHP echo $label_name[19];//Address?></td>
		<td><?php echo $address;?></td>
	</tr>
	
   
        
    <!--tr>
    	
        <td><?PHP echo $label_name[7];//Mobile Number?></td>
        <td><?php echo $row['comm_mobile'];?></td>
        
	</tr-->
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
			<span id="span_dwnd" onClick="download_document(<?php echo $key['doc_id']; ?>)"><?php echo $key['doc_name']; ?></span>

       
    <?php } ?><script>
					function download_document(url){
						//window.location.href="http://locahost/police/pm_petition_doc_download.php?doc_id="+url;
						window.location.href="http://14.139.183.34/police/ps/pm_petition_doc_download.php?doc_id="+url;
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
<?php
 	$pendsql="select action_type_code,pend_period from vw_petition_details  where petition_id='".$pet_id."'";		
		$presult = $db->query($pendsql);		
		$prowarray =$presult->fetchall(PDO::FETCH_ASSOC);
		foreach($prowarray as $row) {
			$act_type=$row[action_type_code];
			$pend_period=$row[pend_period];
		}
		
		if ($stype == 's') {
			$query=" select * from (
		SELECT petition_id,action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name,  off_level_dept_name, off_loc_name AS location,	
		to_whom, dept_desig_name1, off_loc_name1 AS location1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM vw_pet_actions a  	
		WHERE petition_id='".$pet_id."'  ) pet
		where rnk=1";
		} else {
			$query=" select * from (
		SELECT petition_id,action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name,  off_level_dept_name, off_loc_name AS location,	
		to_whom, dept_desig_name1, off_loc_name1 AS location1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM vw_pet_actions a  	
		WHERE petition_id='".$pet_id."'  ) pet";
		}

	$result = $db->query($query);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo $rowArr = $result->fetch(PDO::FETCH_NUM);
	?>
	<table class="gridTbl">
	<thead>
		
		<tr>
		<th colspan='6'  class="emptyTR">
		<label style="float:center">
		<?PHP echo $label_name[16]." "; ?>
		</label>
		<label style="float:right">
		<?PHP echo $label_name[28].":  "; //Pending Period:?> <?php echo $pend_period; ?>
		</label>
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
	foreach($rowarray as $row)
	{

		?>
        <tr>
            <td><?php echo $row['action_entdt'];?></td>
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

	</tbody>
</table>
	
</div>
</div>
</form>
