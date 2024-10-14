<?php
error_reporting(0);
$pagetitle="Downloads";
include("header_menu.php");
include("menu_home.php");
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
table.rptTbl td {
    border-collapse: collapse;
    border: none;
	line-height: 0px;
}
.contentMainDiv .contentDiv {
   
    border: none;
}
a:hover {
    color: red;
}
</style>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
$result = $db->query($query);
while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
?>
<script type="text/javascript" src="js/jquery.md5.min.js"></script>
<script LANGUAGE="Javascript" SRC="md5.js"></script>
<div id="div_content" class="divTable" style="background-color:#F4CBCB;">

	<div class="form_heading"><div class="heading"></div></div>
</br>	</br> </br>
	<div class="contentMainDiv" style="width:55%;margin:auto;">
	<div class="contentDiv">
	
	<table class="rptTbl" style="border:0px;">
	<tr style="text-align:center;border:hidden;background-color: #8D4747;">
	<?php if($_SESSION['lang']=='E'){ ?>
		<th style="font-size:20px;border-right-style:hidden;font-weight:bold;color:#ffffff;" colspan="3">
	<?php echo $label_name[0]; //Downloads?></th>
	<?php }else{ ?>
		<th style="font-size:16px;border-right-style:hidden;font-weight:bold;color:#ffffff;" colspan="3">
	<?php echo $label_name[0]; //Downloads?></th>
	<?php }	?>
	</tr>
    <tr>
	<th colspan="3">  </th>
	</tr>
	<tr style="text-align:center;border:hidden;background-color: #bc6f6f;">
	<?php if($_SESSION['lang']=='E'){ ?>
		<th style="font-size:16px;border-right-style:hidden;font-weight:bold;color:#ffffff;" colspan="3">
	e-District GDP (Grievance Day Petitions) / TOPMS - Features, Advantages and Benefits</th>
	<?php }else{ ?>
		<th style="font-size:16px;border-right-style:hidden;font-weight:bold;color:#ffffff;" colspan="3">
	e-District GDP (Grievance Day Petitions) / TOPMS - Features, Advantages and Benefits</th>
	<?php }	?>
	</tr>
<?php
   // Array containing sample image file names
   
    $images = array("eDistrict_GDP_TOPMS_Complete.pdf");
	
	$download_files = array("eDistrict_GDP_TOPMS_Complete.pdf",
							"eDistrict_GDP_TOPMS_1_Introduction.pdf",
							"eDistrict_GDP_TOPMS_2_Home_Page_URL.pdf",
							"eDistrict_GDP_TOPMS_3_Dept_Official_Login.pdf",
							"eDistrict_GDP_TOPMS_4_Petiton_Processing.pdf",
							"eDistrict_GDP_TOPMS_5_Reports.pdf",
							"eDistrict_GDP_TOPMS_6_System_Admin.pdf",
							"eDistrict_GDP_TOPMS_7_Help_Centre.pdf",
							"eDistrict_GDP_TOPMS_8_Online_Petition_Submission.pdf",
							"eDistrict_GDP_TOPMS_9_Petition_Submission_at_CSC.pdf",
							"eDistrict_GDP_TOPMS_10_Online_Petiton_Status.pdf",
							"eDistrict_GDP_TOPMS_11_State_Level_Reports.pdf"
							);
	$download_files_name = array("Full Document",
							"Introduction",
							"Home Page URL",
							"Department Officials Login",
							"Petiton Processing",
							"Reports",
							"System Administration",
							"Help Centre",
							"Online Petition Submission",
							"Petition Submission at CSC",
							"Online Petiton Status",
							"State Level Reports"
							);						
	
	$i = 1;
	$j = 0;
    ?>

	<?php
		echo '<tr>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p>
		<a href="download.php?file='.urlencode($download_files[0]).'">'.$download_files_name[0].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p><a href="download.php?file='.urlencode($download_files[1]).'">'.$download_files_name[1].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p><a href="download.php?file='.urlencode($download_files[2]).'">'.$download_files_name[2].'</a></p></td>';
		echo '</tr>';

		echo '<tr><td style="text-align:left;font-weight:bold;font-size:15px;"><p><a href="download.php?file=' . urlencode($download_files[3]) . '" class="do_ho">'.$download_files_name[3].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p><a href="download.php?file=' . urlencode($download_files[4]) . '" class="do_ho">'.$download_files_name[4].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p><a href="download.php?file=' . urlencode($download_files[5]) . '" class="do_ho">'.$download_files_name[5].'</a></p></td></tr>';
		
		echo '<tr><td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[6]) . '" class="do_ho">'.$download_files_name[6].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[7]) . '" class="do_ho">'.$download_files_name[7].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[8]) . '" class="do_ho">'.$download_files_name[8].'</a></p></td></tr>';
		
		echo '<tr><td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[9]) . '" class="do_ho">'.$download_files_name[9].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[10]) . '" class="do_ho">'.$download_files_name[10].'</a></p></td>';
		echo '<td style="text-align:left;font-weight:bold;font-size:15px;"><p ><a href="download.php?file=' . urlencode($download_files[11]) . '" class="do_ho">'.$download_files_name[11].'</a></p></td></tr>';
		
		

	?>
	</table>
	</div>
	</div>	
	</br>	</br> </br>
<?php
include('footer.php');
?>