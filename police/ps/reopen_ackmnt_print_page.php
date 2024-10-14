<?php 
//error_reporting(0);
ob_start();
session_start();
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   header("Location: logout.php");    
	exit;
}

include('header_menu.php');
include("db.php");
include("common_date_fun.php");
//include("UserProfile.php");
include('phpqrcode/qrlib.php');
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$designId = $userProfile->getDept_desig_id();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
.contentMainDiv .contentDiv{
	width: 90%;
	margin: 0 auto;
}
table.ack_viewTbl {
    font-size: 12px !important;
}
</style>
<script type="text/javascript">
function printwindow()
{
	document.getElementById("header").style.display='none';
	document.getElementById("btn_row").style.display='none';
	window.print();
	document.getElementById("header").style.display='block';
	document.getElementById("btn_row").style.display='';
}
function closeMe() {
	window.close();
	if (window.opener != null && !window.opener.closed) {
		window.opener.location.reload();
	}
}
</script>

<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_petition_detail_insert.php') order by ordering";

$query = "select label_name,label_tname from apps_labels where menu_item_id=64 order by ordering";

$result = $db->query($query);

while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
	
}
 if($_SESSION['lang']=='E')
	{
		if ($userProfile->getOff_level_id() == 7) {
			$ack_title = 'Petition Processing Portal (PPP) - DGP Office Petitions - Acknowledgement';				
		}  else if ($userProfile->getOff_level_id() == 9) {
			$ack_title = 'Petition Processing Portal (PPP) - IGP Office Petitions - Acknowledgement';				
		} else if ($userProfile->getOff_level_id() == 11) {
			$ack_title = 'Petition Processing Portal (PPP) - DIG Office Petitions - Acknowledgement';
		} else if ($userProfile->getOff_level_id() >= 13) {
			$ack_title = 'Petition Processing Potral (PPP) - TOAMS - SP Office Petitions - Acknowledgement';
		}
	} 
	else
	{
		if ($userProfile->getOff_level_id() == 7) {
			$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - ஆணையாளர் (நில நிர்வாகம்) அலுவலக மனுக்கள் - ஒப்புகைச்சீட்டு';
		}  else if ($userProfile->getOff_level_id() == 9) {
			$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - மின்-மாவட்ட குறை தீர்க்கும் நாள் மனுக்கள் - ஒப்புகைச்சீட்டு';
		} else if ($userProfile->getOff_level_id() == 11) {
			$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - சார் ஆட்சியா் / வருவாய் கோட்ட அலுவலர் அலுவலக மனுக்கள் - ஒப்புகைச்சீட்டு';
		} else if ($userProfile->getOff_level_id() >= 13) {
			$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - வருவாய் வட்டாட்சியர் அலுவலக மனுக்கள் - ஒப்புகைச்சீட்டு';
		}
	}
			
	if ($userProfile->getOff_level_id() == 1) {
		if($_SESSION['lang']=='E')
			$off_loc_name = 'Chennai';
		else
			$off_loc_name = 'சென்னை';
	} else {
		$off_loc_name = $userProfile->getOff_loc_name();
	}
			
			
	if ($off_level_id == 1) {
		$acknow_label = $label_name[36];
	}  
	else if ($off_level_id == 2) {
		$acknow_label = $label_name[1]." - ". $griev_dist_name;		
	} else if ($off_level_id == 3) {
		$acknow_label = $label_name[35]." - ". $rdo_name;
	} else if ($off_level_id == 4){
		$acknow_label = $label_name[34]." - ". $griev_taluk_name;
	}
				   
	$sql="select off_level_dept_id,off_level_dept_name,off_level_dept_tname 
	from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." 
	and off_level_dept_id=".$userProfile->getOff_level_dept_id()."";
	
	$result = $db->query($sql);
	while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
		if($_SESSION["lang"]=='T'){
			$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - '. $rowArr['off_level_dept_tname'].' - '.$label_name[0];
			$acknow_label = $rowArr['off_level_dept_tname']." - ".$userProfile->getOff_loc_name();
		} else {
			$ack_title = 'Tamil Nadu Police Senior Police Officers Petition System(SPOPS) - '.$rowArr['off_level_dept_name'].' - '.$label_name[0];
			$acknow_label = $rowArr['off_level_dept_name']." - ".$userProfile->getOff_loc_name();
		}
	}
	?>
 
<form name="ackmnt_prnt" id="ackmnt_prnt" action="ackmnt.php" enctype="multipart/form-data" method="post" 
class="form-horizontal">
<input type="hidden" name="pet_act_id" id="pet_act_id" value="<?PHP echo $pet_act_id;?>"/>

<style media="print">
 @page {
  size: auto;
  margin: 0;
       }
@media print {
   body { font-size: 8 pt }
 }
 @media screen {
   body { font-size: 8 px }
 }
 @media screen, print {
   body { line-height: 1.0 }
 }	   
</style>
 

    
<?php
	$date= Date("d/m/Y");
	$cur_date=explode('/',$date);
	$day=$cur_date[0];
	$mnth=$cur_date[1];
	$yr=$cur_date[2];
	$cur_dt=$day.'-'.$mnth.'-'.$yr;
	
	$petition_id = stripQuotes(killChars(trim($_POST['petition_id'])));

	$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, 
	petitioner_name,  father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob,source_name,
	source_tname,subsource_name,  griev_type_name, griev_subtype_name,dept_name, grievance, canid,
	comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name, comm_taluk_name, 
	comm_rev_village_name, comm_state_name,comm_country_name,comm_pincode, comm_email, comm_mobile,
	griev_district_name, aadharid,dept_desig_name, dept_desig_tname, off_level_dept_name, 
	off_level_dept_tname, off_loc_name, off_loc_tname,pet_type_name,pet_type_id as pet_type
	FROM vw_pet_ack WHERE petition_id='".$petition_id."'";  
		 
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		
				
?>

	 <?PHP 
	 	foreach($rowarray as $row)
		{
			if($_SESSION['lang']=='E') {
				$src_name = $row['source_name'];
			} else {
				$src_name = $row['source_tname'];
			}
			$petition_no = $row['petition_no'];

			$pet_type = $row['pet_type'];
			if ($pet_type == '1') {
				$pet_type_name = '(New Petition)';
			} else {
				$pet_type_name = '(Repeated Petition)';
			}	
	
			$petition_id = $row['petition_id'];
			
			$url='http://locahost/police/getPetitionStatusQR.php?pet_id='.$petition_id;
				
				$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
				//echo  $PNG_TEMP_DIR;
				//html PNG location prefix
				$PNG_WEB_DIR = 'temp/';
				
				if (!file_exists($PNG_TEMP_DIR))
				mkdir($PNG_TEMP_DIR);
			
			
				$filename = $PNG_TEMP_DIR.'test.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 10;
				//$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.H.'|'.5).'.png';
				$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
				 //echo $filename;
                QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			
			?>
		<!--div id="prn_ack" style="font-size:12px"--> 
 <div class="contentMainDiv" style="margin-top:50px;">
	<div class="contentDiv">
        
        <table class="ack_viewTbl"  border="1" cellspacing="0" cellpadding="1" width="100%">  
		
		<tr>
			<td colspan="3">
				<center><b><?PHP echo $src_name; //Petition No. & Date?></b></center>
			</td>
		</tr> 
		
        <tr>
        <td class="heading" style="background-color:#BC7676" colspan="3">
            <!--table border="0" cellspacing="0" cellpadding="0" width="100%"-->
            <!--tr-->
            <!--td width="40%" style="align:left"-->
            <img height="70" width="70" src="images/1.png" id="prnt_img" align="left"/> <!--/td-->
			<?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" align="right" height="70" width="70" style="margin-right:30px" />'; ?>
            <!--td width="70%" style="align:center"-->
			<center>
			<label>
			<b><?PHP echo $ack_title; ?>    
			</b></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <label style="align:center"><b><?PHP echo $acknow_label; //District Collectorate ?></label><br>
	<label style="align:center">
	<?php echo ' To check the status of your petition: http://localhost/police; or scan the QR Code with a QR Reader in a smartphone; '?><br>
			</b></label> </center>
			<!--/td-->
            <!--/--tr>
            <!--/table-->
        </td>
        </tr> 
        
       
        <tr> <!-- Ist Row-->
        <td>
        <b><?PHP echo $label_name[3]; //Petition No. & Date ?> </b>		 
        </td>
        <td>&nbsp;&nbsp;
		<?php echo $row['petition_no'].' '.$pet_type_name,' & Dt. '.$row['petition_date']; ?>&nbsp;&nbsp;
		<?php echo "(Re-opened on: ".$cur_dt.")"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19];//Mobile Number?> : <b><?php echo $row['comm_mobile'];?> </b>  
		</td>
		</tr>
		
		
		
		<!--III Row-->
		<tr>		
		<td>
        <b><?PHP echo $label_name[28]; //Petition Main Category & Petition Sub Category ?></b> </td>
		<td>&nbsp;&nbsp;
        <?php echo $row['griev_type_name'].' & '.$row['griev_subtype_name'];?> 
        </td>        
		</tr>
		
		<!-- IV Row-->
         <tr>
        <td>
        <b><?PHP echo $label_name[10]; //Grievance/ Request ?></b> </td><td>&nbsp;&nbsp;
		<?php echo $row['grievance'];?> 
        </td>
        </tr>
        <?php
			$dept_desig_name = $row['dept_desig_name'];
			$off_level_dept_name = $row['off_level_dept_name'];
			$off_loc_name = $row['off_loc_name'];
			/*$petition_id = $row[petition_id];
			if ($dept_desig_name == '') {
				$sql="SELECT * FROM
				(SELECT a.petition_id, a.to_whom, b.dept_desig_name, b.dept_desig_tname, b.off_level_dept_name, b.off_level_dept_tname, b.off_loc_name, b.off_loc_tname,
				cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
				FROM pet_action a
				INNER JOIN vw_usr_dept_users_v b on b.dept_user_id=a.action_entby and a.action_type_code='T'
				) AA
				WHERE rnk=1 and petition_id=".$petition_id;
				
				$rs = $db->query($sql);
				$rowarr = $rs->fetchall(PDO::FETCH_ASSOC);
				foreach($rowarr as $r)
				{
					$dept_desig_name = $r[dept_desig_name];
					$off_level_dept_name = $r[off_level_dept_name];
					$off_loc_name = $r[off_loc_name];
				}
				
			}*/
			
		?>
		<!-- V Row-->
       <tr>
        <td>
        <b><?PHP echo 'Enquiry Filing Officer'; //Concerned Officer ?></b> 
		</td><td>&nbsp;&nbsp;	
        <?php echo $dept_desig_name.", ".$off_level_dept_name.", ".$off_loc_name;?> 
        </td>
        </tr>
		
		<tr><td colspan='2' height='100%'style='color:#FEEDED'><?php echo ' <br>';?></td></tr>
		
        <tr>
        <td>
        <b><?PHP echo $label_name[30]; //Petitioner Name ?></b> 
		</td><td>&nbsp;&nbsp;
        <?php echo  $row['petitioner_name']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$label_name[13]."</b>: ".$row['father_husband_name'] //ucfirst(strtolower($row[petitioner_name]));?>  
        </td>
        </tr>
		       
        <tr>
         
        <?php
		$comm_address = '';
        if($row['comm_doorno']!="")
			$comm_address.=$row['comm_doorno'];
        if($row['comm_aptmt_block']!="")
        	$comm_address.=", ".$row['comm_aptmt_block'];
        if($row['comm_street']!="")
        	$comm_address.=", ".$row['comm_street'];
        if($row['comm_area']!="")
        	 $comm_address.=", ".$row['comm_area'];
        ?>
        <td>
        <b><?PHP echo $label_name[18]; //Address ?>: </b> &nbsp;&nbsp;
		</td>
		<td>&nbsp;&nbsp;
        <?php //echo ucfirst($comm_doorno).$comm_aptmt_block.$comm_street.$comm_area.", ".
        //ucfirst(strtolower($row[comm_rev_village_name])).", ".ucfirst(strtolower($row[comm_taluk_name])).", ".
        //ucfirst(strtolower($row[comm_district_name]))." - ".$row[comm_pincode].".";?> 
        
        <?php 
		$comm_rev_village_name = $row['comm_rev_village_name'].$label_name[27];
		$comm_taluk_name = $row['comm_taluk_name'].$label_name[26];
		$comm_district_name = $row['comm_district_name'].$label_name[25];
		$comm_state_name = $row['comm_state_name'];
		$comm_country_name = $row['comm_country_name'];
		
		//echo $comm_address.", ".$comm_rev_village_name.", ".$comm_taluk_name.", ".$comm_district_name.", ".$comm_state_name.", ".$comm_country_name." - ".$row[comm_pincode].', '.$label_name[19].": ".$row[comm_mobile] ;
		
		echo $comm_address.", Pincode - ".$row['comm_pincode'].'.';
		?> 
        
        </td>
        </tr>
        <tr style="font-size:12px;">
		<td colspan="2"><b><?PHP echo $label_name[32].$label_name[31]; //Petition Main Category & Petition Sub Category ?></b> </td>
		</tr>
		
        </table>

        
        <?PHP
        }
        
		$query=" select * from (
		SELECT petition_id,action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name,  off_level_dept_name, off_loc_name AS location,	
		to_whom, dept_desig_name1,  off_level_dept_name1,off_loc_name1 AS location1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
		FROM fn_pet_actions_pet_no('".$petition_no."')) pet where rnk=1";
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
        ?>
        <table class="ack_viewTbl" border="1" cellspacing="0" cellpadding="1" width="100%">
		<tr>
		<th colspan='6'  class="emptyTR">
		<label style="float:center">
		<?PHP echo 'Action Details'; ?>
		</label>
		
		</th>
	    </tr>
	
		<tr class="heading" style="background-color:#BC7676" >
			<th><?PHP echo 'Action Taken Date & Time' ?></th>
			<th><?PHP echo 'Action Type';//Action Type?></th>
			<th><?PHP echo 'File No. & File Date';//File No. & File Date?></th>
			<th><?PHP echo 'Action Remarks';//Action Remarks?></th>
            <th><?PHP echo 'Action Taken By';//Action Taken By?></th>
            <th><?PHP echo 'Addressed To';//Addressed To?></th>
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
	?>
	
	<tr>
            	<td colspan="6" align="center" class="btn" id="btn_row"> 
     <input type="button" name="" id="dontprint1" style="width:150px;" value="<?PHP echo $label_name[22]; //Print ?>" class="button" onClick="return printwindow()">					
				
	<input type="button" name="dontprint3" id="dontprint3" style="width:150px;" value="Back" class="button" onClick="closeMe();">
					
            	</td>
			</tr>
			
	</table>	
	<!--	<br><br><br> -->
	  
</div> 
</div>
 </form>
 
