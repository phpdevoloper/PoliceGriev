<?php 
//error_reporting(0);
//ob_start();
session_start();
include("header_menu.php");
include("menu_home.php");
include("db.php");
include("common_date_fun.php");
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	echo "Timed out. Please login again";
	header("Location: logout.php");
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Petition Status</title>
</head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript">
function print_page()
{
document.getElementById("prnt_img").style.visibility='hidden';
document.getElementById("bak_btn").style.visibility='hidden';
document.getElementById("header").style.visibility='hidden';
document.getElementById("usr_detail").style.visibility='hidden';
document.getElementById("menu").style.display='none';
//document.getElementById("footertbl").style.visibility='hidden';
print();
document.getElementById("prnt_img").style.visibility='visible';
document.getElementById("bak_btn").style.visibility='visible';
document.getElementById("header").style.visibility='visible';
document.getElementById("usr_detail").style.visibility='visible';
document.getElementById("menu").style.display='';
//document.getElementById("footertbl").style.visibility='visible';
//self.close();
}
</script>

<?php
// the message
/*$msg = "First line of text\n=================================Second line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("prabakaranpandit@gmail.com","My subject",$msg);*/
?> 

<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='ackmnt.php') order by ordering";

$query = "select label_name,label_tname from apps_labels where menu_item_id=82 order by ordering";

$result = $db->query($query);

while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	//print_r($rowArr);
	//echo $_SESSION['lang'];
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
	
	//label(0)=(version="english") : $rowArr['label_name'] ? $rowArr['label_tname'];
} 
?>
 

<form name="petition_status" id="petition_status" action="" enctype="multipart/form-data" method="post">
 
   <h3 align="right"> 
   		 <a href="pm_view_petition_status.php"> <img id="bak_btn" width="25px" height="25px" src="images/bak_prnt.jpg" /></a>
						
        <img src="images/print.jpg" id="prnt_img" onClick="return print_page()"/>	  		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   </h3>
   
   <div align="center"><h4><?php echo $_SESSION['offtypedesc']; ?></h4></div>
    
<?php
	//$substr_hid=stripQuotes(killChars($_POST['ackmnt_hid'])); 
	$petno=stripQuotes(killChars(trim($_POST['petition_no'])));
	
	$stype=stripQuotes(killChars(trim($_POST['stype'])));
		
 		$query = "
		SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, 
		petitioner_name,  father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, 
		idtype_name, id_no, source_name,subsource_name,  griev_type_name, griev_subtype_name,dept_name, grievance, canid,
		
		comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name, comm_taluk_name, 
		comm_rev_village_name, comm_pincode, comm_email, comm_phone, comm_mobile,
		
		griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name, 
		griev_taluk_name, griev_rev_village_name,
		griev_block_name, griev_lb_village_name, griev_lb_urban_name, griev_pincode,aadharid
		
		FROM vw_pet_master 
		WHERE petition_no='$petno' ";  
		 
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
?>
	 <?PHP 
	 	foreach($rowarray as $row)
		{?>
		 
<div id="prn_ack" style="font-size:12px"> 
        
		
        <table border="1" cellspacing="0" cellpadding="1" class="viewTbl" width="95%" align="center"> 
       <!--  <tr id="bak_btn">
         <td colspan="3" align="center">
         <a href="view_petition_status.php"><img  width="25px" height="25px" src="../<?php //echo PROJECT_NAME; ?>/images/bak.jpg" /></a>
         </td>
         </tr>-->
        <tr>
        <td colspan="2">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
			<td colspan="2" class="heading" style="background-color:#BC7676">
			<img height="50" width="50" src="images/TamilNadu_Logo.jpg" id="prnt_img" align="left"/> 
			 <center>
				<label style="font-weight:bold;font-size:16px;"><b><?PHP echo $label_name[27];  ?></b></label> 
				<br><label style="font-weight:bold;font-size:12px;"><?PHP  echo $label_name[4]; //Acknowledgement Grievance Details ?>  - 
				<?PHP  echo $row['griev_district_name']; //Acknowledgement Grievance Details ?>
				</label></center>
			
			</td>
            </tr>
            </table>
        
        </tr> 
        
       <!-- <tr>
        <td align="center" colspan="3">
        <b> <?php 
        /* echo "CSC".(($_POST["csc_address"]!="") ? ", ".$_POST["csc_address"].", " : '').(($_POST["csc_vill_nm"]!="") ? ucfirst(strtolower($_POST["csc_vill_nm"])).", " : '').(($_POST["csc_tlk_nm"]!="") ? $_POST["csc_tlk_nm"].'(Tk)'.", " : '').(($_POST["csc_dist_nm"]!="") ? $_POST["csc_dist_nm"].'(Dt)'.'.' : '');*/
		  ?> </b>
        </td>
        </tr> -->
        
		<tr>
        <td style="width:30%">
        <b><?PHP echo $label_name[0]." "; //Petition No. & Date ?> </b>
		</td>
		<td style="width:70%">&nbsp;&nbsp;	
        <?php echo $row['petition_no'].' & Dt. '.$row['petition_date']; ?>  
        </td>
		</tr>
		
		<tr>
        <td>
        <b><?PHP echo $label_name[1]; //Source Name ?> </b>
		</td><td>&nbsp;&nbsp;
        <?php echo $row['source_name'].','.$row['subsource_name'];?> 	 
        </td>
        </tr>
				
		<tr>
		<td>
        <b><?PHP echo $label_name[3]." "; //Department ?></b> 
		</td><td>&nbsp;&nbsp;	
        <?php echo  $row['dept_name'];?> 
        </td>
		</tr>
        
		<tr>
        <td>
        <b><?PHP echo $label_name[2]." "; //Grievance Type ?></b>
		</td>
		<td>&nbsp;&nbsp;	
        <?php echo $row['griev_type_name'].', '.$row['griev_subtype_name'];?> 
        </td>        
        </tr>
       
		
        <tr>
        <td><b>
        <?PHP echo $label_name[4]." "; //Grievance/ Request ?></b>
		</td><td>&nbsp;&nbsp;
        <?php echo $row['grievance'];?> 
        </td>
        </tr>    
        
        <?php
        //if($row[taluk_name_1]!="") comm_taluk_name
        /*if($row[griev_taluk_name]!="") 
        $taluk_block_urban=$row[griev_taluk_name];
        //else if($row[block_name]!="")
        else if($row[griev_block_name]!="")
        $taluk_block_urban=$row[griev_block_name];
        else
        $taluk_block_urban=$row[griev_lb_urban_name];
        
        if($row[griev_rev_village_name]!="")
        $village_lbvillage=$row[griev_rev_village_name];
        else
        $village_lbvillage=$row[griev_lb_village_name];
        
        if($row[griev_aptmt_block]!="") 
        $row[griev_aptmt_block]=",".$row[griev_aptmt_block];
        if($row[griev_area]!="")
        $row[griev_area]=",".$row[griev_area];
        if($row[griev_street]!="")
        $row[griev_street]=",".$row[griev_street];*/
        ?>
		<!--tr>
        <td>&nbsp;&nbsp;
        <b><?PHP //echo $label_name[5]." "; //Grievance/ Request ?></b></td><td>  
        <?php //echo $row[griev_doorno].$row[griev_aptmt_block].$row[griev_street].
		//$row[griev_area].",".$village_lbvillage.",".
		//$taluk_block_urban.",".$row[griev_district_name]."-".
		//$row[griev_pincode].".";?> 
        </td>
        </tr-->
        
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
				<td><b><?PHP echo $label_name[5];//Petition Office Address?></b></td>
				<td>&nbsp;&nbsp;<?php echo $pet_off_address;?></td>
			</tr>
		
        <tr>
        <td>
        <b><?PHP echo $label_name[17].'& '.$label_name[18]; //Petitioner Name ?></b> 
		</td>
		<td>&nbsp;&nbsp;
        <?php echo $row['petitioner_name'].', '.$label_name[18].': '.$row['father_husband_name'];?>  
        </td>
        </tr>
               
        <tr>
        <?php
        if($row['comm_doorno']!="")
        $row['comm_doorno']=$row['comm_doorno'];
        if($row['comm_aptmt_block']!="")
        $row['comm_aptmt_block']=",".$row['comm_aptmt_block'];
        if($row['comm_street']!="")
        $row['comm_street']=",".$row['comm_street'];
        if($row['comm_area']!="")
        $row['comm_area']=",".$row['comm_area'];
        ?>
        
        <td>
        <b><?PHP echo $label_name[19]." "; //Address ?></b></td><td>&nbsp;&nbsp; 
        <?php echo $row['comm_doorno'].$row['comm_aptmt_block'].$row['comm_street'].$row['comm_area'].",".
        $row['comm_rev_village_name'].$label_name[22].",".$row['comm_taluk_name'].$label_name[21].",".
        $row['comm_district_name'].$label_name[20]."-".$row['comm_pincode'].".";?> 
        </td>
        </tr>
        
        <tr>
        <td>
        <b><?PHP echo $label_name[7]." "; //Mobile Number ?> </b></td><td>&nbsp;&nbsp;
        <?php echo $row['comm_mobile'];?> 
        </td>
        </tr>  
		
		<!--tr>
        <td>
        <b><?PHP //echo $label_name[22].": "; //"Document" ?> </b>
        
        <?php
        /*$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row[petition_id]."')";
        $fetch_doc = $db->query($query_doc);
        $doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH); 
        //$num_rows=$fetch_doc->rowCount();
        
        foreach($doc_row as $key){
        echo $key['doc_name']."; "; */
        ?>
      
        <?php  //} ?> 
        </td></tr-->
	   <?php
			$query_doc = "select action_doc_id,action_doc_name from pet_action_doc where petition_id  in('".$row[petition_id]."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	   ?>
       <tr>
	   <td><b><?php echo "Action Taken Document:";?></b></td><td>&nbsp;&nbsp;
	    <?php
		foreach($doc_row as $key){
		?>
	   <span id="span_dwnd" style="color:blue; text-decoration:underline" onClick="download_document(<?php echo $key['action_doc_id']; ?>)">
	   <?php echo $key['action_doc_name']; ?>
	   </span>
		<?php } ?>
	   </td>
	   <script>
					function download_document(url){
						//alert(url);
						//alert("http://10.163.30.9/ed_gdp_tnega/fileupload1.php?doc_id="+url);
						window.location.href="http://14.139.183.34/pm_petition_doc_download.php?doc_id="+url;
						//window.location.href="http://10.163.30.9/ed_gdp_rpts_new/pm_petition_action_doc_download.php?doc_id="+url;
					}
				</script>
	   </tr>
       <?PHP
        }?>
        </table>
        
       
		<?php
		$pendsql="select action_type_code,pend_period from vw_petition_details  where petition_no='".$petno."'";		
		$presult = $db->query($pendsql);		
		$prowarray =$presult->fetchall(PDO::FETCH_ASSOC);
		foreach($prowarray as $row) {
			$act_type=$row['action_type_code'];
			$pend_period=$row['pend_period'];
		}
		
		if ($stype == 's') {
			$query=" select * from (
		SELECT petition_id,action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name,  off_level_dept_name, off_loc_name AS location,	
		to_whom, dept_desig_name1, off_loc_name1 AS location1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
		FROM vw_pet_actions a  	
		WHERE petition_no='".$petno."'  ) pet
		where rnk=1";
		} else {
			$query=" select * from (
		SELECT petition_id,action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name,  off_level_dept_name, off_loc_name AS location,	
		to_whom, dept_desig_name1, off_loc_name1 AS location1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
		FROM vw_pet_actions a  	
		WHERE petition_no='".$petno."'  ) pet";
		}
		//echo $query;
		
		
		
		$result = $db->query($query);
		$rowcnt = $result->rowCount();
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		if($rowcnt!=0)
		{
		?>
        
		<table border="1" cellpadding="0" cellspacing="0"  align="center" class="gridTbl" style="width:95%;">
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
		<th><?PHP echo $label_name[8]." "; //Action Taken Date & Time?></th>
		<th><?PHP echo $label_name[9]." "; //Action Type?></th>
		<th><?PHP echo $label_name[10]." "; //File No. & File Date?></th>
		<th><?PHP echo $label_name[11]." "; //Action Remarks?></th>
		<th><?PHP echo $label_name[12]." "; //Action Taken?></th>
		<th><?PHP echo $label_name[13]." "; //Addressed To?> </th>
		</tr>
		</thead>
		
		<tbody>
		
        
		<?php foreach($rowarray as $row)
		{
		?>
		<tr>
		<td><?php echo $row['action_entdt'];?></td>     
		<td><?php echo $row['action_type_name'];?></td> 
		<td><?php echo !empty($row['file_no'])? '<b>'.$row['file_no'].'</b>'."<br>".$row['file_date'] : "";?></td>     
		<td><?php echo $row['action_remarks'];?></td>
		<td><?php echo $row['dept_desig_name'].',' .$row['off_level_dept_name'].',' .$row['location'];?></td>
		<td><?php echo (($row['dept_desig_name1'])? $row['dept_desig_name1'].', ' : " ") .$row['location1'];?></td>
		</tr>
		
		<?php
		}
		}
?>
</div> 
 </form>
 
<?php

	//include("footer.php"); 
?>
