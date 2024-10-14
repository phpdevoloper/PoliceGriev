<?php 
ob_start();
session_start();
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	header("Location: logout.php");
	exit;
}
include("db.php");
include("common_date_fun.php");
include("UserProfile.php");
include('phpqrcode/qrlib.php');
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$designId = $userProfile->getDept_desig_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script type="text/javascript">
function print_page()
{
	document.getElementById("prnt_img").style.visibility='hidden';
	document.getElementById("bak_btn").style.visibility='hidden'; 
	print();
	document.getElementById("prnt_img").style.visibility='visible';
	document.getElementById("bak_btn").style.visibility='visible';
}
</script>
<?php
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_petition_detail_insert.php') order by ordering";
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
			$ack_title = 'Tamil Nadu Police - Police Station Complaint Redressal System  - '.$label_name[0];
			$acknow_label = $rowArr['off_level_dept_name']." - ".$userProfile->getOff_loc_name();
		}
	}
?>
 
<form name="ackmnt_prnt" id="ackmnt_prnt" action="ackmnt.php" enctype="multipart/form-data" method="post" 
class="form-horizontal">
 
   <h3 align="right"> 
   		 <a href="" onclick="self.close();"> <img id="bak_btn" width="25px" height="25px" src="images/bak_prnt.jpg" /></a>
         
        <img src="images/print.jpg" id="prnt_img" onClick="return print_page()"/>	  		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   </h3>
   
   <div align="center"><h4><?php echo $_SESSION['offtypedesc']; ?></h4></div>
    
<?php
	$petno[]=stripQuotes(killChars(trim($_POST['petition_no1'])));
	$petno[]=stripQuotes(killChars(trim($_POST['petition_no2'])));
	$petno[]=stripQuotes(killChars(trim($_POST['petition_no3'])));	
	
	if ($petno1!="") {
		$i = 1;
	}
	if ($petno1!= "" && $petno2!="") {
		$i = 2;
	}
	if ($petno1!= "" && $petno2!="" && $petno3!="") {
		$i = 3;
	}

	for ($x=0;$x<sizeof($petno);$x++) {
		$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy') as petition_date, 
		petitioner_name,  father_husband_name, gender_name, TO_CHAR(dob,'dd/mm/yyyy') AS dob, 
		source_name,source_tname,subsource_name,subsource_tname, griev_type_name, griev_subtype_name,
		dept_name, grievance, canid,comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name, comm_taluk_name, comm_rev_village_name, comm_country_name,comm_state_name,comm_pincode, comm_email, comm_mobile,
		griev_district_name, aadharid, dept_desig_name, dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname,pet_type_name, pet_type_id as pet_type,
		pet_community_name,pet_community_tname, petitioner_category_name, petitioner_category_tname 	
		FROM vw_pet_ack WHERE petition_no='".$petno[$x]."'";  		 
		//echo $query;
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
			
			if ($row['subsource_name'] != '') {
				if($_SESSION['lang']=='E') {
					$src_name .= ' - '.$row['subsource_name'];
				} else {
					$src_name .= ' - '.$row['subsource_tname'];
				}	
			}

			$pet_type = $row['pet_type'];
			if ($pet_type == '1') {
				$pet_type_name = '(Top Priority)';
			} else if ($pet_type == '2') {
				$pet_type_name = '(Immediate)';
			} else if ($pet_type == '3') {
				$pet_type_name = '(Regular)';
			} else if ($pet_type == '4') {
				$pet_type_name = '(Confidential)';
			}	
	
			$petition_id = $row['petition_id'];
			
			$url='http://locahost/police/getPetitionStatusQR.php?pet_id='.$petition_id;
			
				
			$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

			$PNG_WEB_DIR = 'temp/';
				
				if (!file_exists($PNG_TEMP_DIR))
				mkdir($PNG_TEMP_DIR);
			
			
				$filename = $PNG_TEMP_DIR.'test.png';
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 10;
				$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
                QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			
			?>
		<div id="prn_ack" style="font-size:15px;width:1200px;margin:0 auto;"> 
 
        
        <table border="1" cellspacing="0" cellpadding="1" width="80%">  
		
		<tr>
			<td colspan="3">
				<center><b><?PHP echo $src_name; //Petition No. & Date?></b></center>
			</td>
		</tr> 
		
        <tr>
        <td colspan="3">
            <img height="70" width="70" src="images/1.png" id="prnt_img" align="left"/> <!--/td-->
			<?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" align="right" height="70" width="70" style="margin-right:30px" />'; ?>
			<center>
			<label>
			<b><?PHP echo $ack_title; ?>    
			</b></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <label style="align:center"><b><?PHP echo $acknow_label; //District Collectorate ?></label><br>
	<label style="align:center">
	<?php echo ' To check the status of your petition: http://localhost/police; or scan the QR Code with a QR Reader in a smartphone; '?><br>
	</b></label> </center>
        </td>
        </tr> 
        
       
        <tr> <!-- Ist Row-->
        <td>
        <b><?PHP echo $label_name[3]; //Petition No. & Date ?> </b>		 
        </td>
        <td>&nbsp;&nbsp;
		<?php echo $row['petition_no'].' '.$pet_type_name,' & Dt. '.$row['petition_date']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19];//Mobile Number?> : <b><?php echo $row['comm_mobile'];?></b>
		</td>
		</tr>
		
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
        
		<!-- V Row-->
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
        <tr>
        <td>
        <b><?PHP echo 'Enquiry Filing Officer'; //Concerned Officer ?></b> 
		</td><td>&nbsp;&nbsp;	
        <?php echo $dept_desig_name.", ".$off_level_dept_name.", ".$off_loc_name;?> 
        </td>
        </tr>
						
        <tr>
        <td>
        <b><?PHP echo $label_name[30]; //Petitioner Name ?></b> 
		</td><td>&nbsp;&nbsp;
        <?php echo  $row['petitioner_name']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$label_name[13]."</b>: ".$row['father_husband_name'] //ucfirst(strtolower($row[petitioner_name]));?>  
        </td>
        </tr>
		<?php
			$community_category_name = "";
			if($row['pet_community_name']!="")
				$community_category_name = $label_name[39].': '.$row['pet_community_name'];
			else 
				$community_category_name = $label_name[39].': ---';
			
			if($row['petitioner_category_name']!="") {
				if ($community_category_name != "")
					$community_category_name.=", ".$label_name[40].': '.$row['petitioner_category_name'];
				else 
					$community_category_name.=$label_name[40  ].': '.$row['petitioner_category_name'];
			}
		?>
		
		<!--tr>
		<td><?php echo  $label_name[38]//Petitioner Community and Category;?></td>
		<td>&nbsp;&nbsp;<?PHP echo $community_category_name; ?></td>
		</tr-->	
		
		<tr>
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
        <b><?PHP echo $label_name[18]; //Address ?></b> &nbsp;&nbsp;
        </td>
		<td>&nbsp;&nbsp;
        <?php 
		$comm_rev_village_name = $row['comm_rev_village_name'].$label_name[27];
		$comm_taluk_name = $row['comm_taluk_name'].$label_name[26];
		$comm_district_name = $row['comm_district_name'].$label_name[25];
		$comm_country_name = $row['comm_country_name'];
		$comm_state_name = $row['comm_state_name'];
		
		//.$comm_rev_village_name.", ".$comm_taluk_name.", ".$comm_district_name." - ".$comm_state_name.", ".$comm_country_name.", ".
		echo $comm_address.", Pincode - ".$row['comm_pincode'].'.';//', '.$label_name[19].": ".$row[comm_mobile] ?> 
        
        </td>
        </tr>
        <tr style="font-size:12px;">
		<td colspan="2"><b><?PHP echo $label_name[32].$label_name[31]; //Petition Main Category & Petition Sub Category ?></b> </td>
		</tr>
		
        </table>
        
        <?PHP
        }
        
        ?>
        
		<br><br><br>
	   <?php } ?>
</div> 
 </form>
 
