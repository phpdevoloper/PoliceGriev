<?php 
ob_start();
session_start();

include("db.php");
include("common_fun.php");
include_once 'common_lang.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['PAGE_TITLE']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0	 maximum-scale=6.0 user-scalable=no">
<meta name="theme-color" content="#317EFB"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="apple-touch-icon" href="assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="assets/images/favicon/favicon.png">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css"> 
<!-- font Awesome -->
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- font Awesome -->
<link href="assets/css/stylev1.css" rel="stylesheet" media="all">
<link href="assets/css/stylev1.css" rel="stylesheet" media="all">
<link href="assets/css/style.css" rel="stylesheet" media="all">
<link href="assets/css/base.css" rel="stylesheet" media="all">
<link href="assets/css/base-responsive.css" rel="stylesheet" media="all">
<link href="assets/css/grid.css" rel="stylesheet" media="all">
<link href="assets/css/font.css" rel="stylesheet" media="all">
<link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="assets/css/flexslider.css" rel="stylesheet" media="all">
<link href="assets/css/megamenu.css" rel="stylesheet" media="all" />
<link href="assets/css/print.css" rel="stylesheet" media="print" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>	
<link href="theme/css/site.css" rel="stylesheet" media="all">
<link href="theme/css/site-responsive.css" rel="stylesheet" media="all">
<link href="theme/css/ma5gallery.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="bootstrap/mycss/dpk.css">
<style>
a {
    color: #000;
}
#span_dwnd {
	cursor:pointer;
	font-weight:bold;
	color: red;
	text-decoration:underline;
	float: left;
}
#span_dwnd:hover {
color: #0000FF;
}

.co-left {
	color: #fff;
    line-height: 22px;
	cursor: pointer;
}
.call_now {
    font-size: 20px;
	letter-spacing: 2px;
	position: relative;
	font-family: times new roman;
	font-weight: bold;
}
.call_now1 {
    font-size: 20px;
    letter-spacing: 2px;
    position: relative;
    font-family: Tahoma;
}
.call_now2 {
    font-size: 25px;
	letter-spacing: 2px;
	position: relative;
	font-family: times new roman;
	font-weight: bold;
	top: 8px;
}
.call_now3 {
    font-size: 11px;
	letter-spacing: 2px;
	position: relative;
	font-family: times new roman;
	font-weight: bold;
	top: 8px;
}
.call_now::before {
    content: none;
    position: absolute;
    top: -50px;
    left: -12px;
}
.log_wid {
	width: 59%;
	float: left;
}
.bt_se {
	margin-top: -61px;
    margin-left: 117px;
}
.common-left ul li.ministry a:hover {
    color: #CA0C5C !important;
}
.fo_si {
     font-size: 19px;
}

.relo {
	width: 97%;
	cursor: pointer;
}
.catc {
	width: 100%;
    margin-left: 52px;
}
.cec {
width: 89%;
}
.re_img {
	margin-left: -64px;
    margin-top: -4px;
}
@media(min-width:1440px) and (max-width:2254px)
{
	.flex-control-nav {
    left: 190px;
    right: auto;
    }
   .banner-wrapper .flex-pauseplay {
    left: 854px;
    right: auto;
}
   .logo
   {
    margin-left: -13px;
}
	
}
@media (min-width: 1200px)
{
  .flex-control-nav {
    left: 202px; 
    right: auto;
}
   .banner-wrapper .flex-pauseplay {
    left: 870px; 
    right: auto;
}
}
input:focus {
	color:red;
}
#submit_otp:hover {
    background-color: #F37A0B;
}
tr {
	font-size: 14px;
}
td {
	padding: 0px;
	font-size: 14px;
	color: #000000;
	padding-left: 10px;
	padding-bottom: 0px;
}
th {
	font-size: 15px;
	line-height: 8px;
}
.viewTbl{
	margin-bottom: 10px;
}



th {
	background: #DDDDDD;
	color: #000000;
}
table, th, td {
    font-family: "Open Sans", sans-serif;
		border: 3px solid #3c8dbc;
}
.emptyTRV
{
	 width:100%;
	 text-align: right;
	 line-height: 9px;
}
body {
     background: #D3D3D3;
    font-family: "Open Sans", sans-serif !important;
}
@page {
size:auto;margin:0mm;
}
.pad_t {
	padding: 9px;
}
@media(max-width:767px)
{
	.taple_scroll {
		overflow-x: scroll;
	}
	
}
.My_pet_1 {
	width: 100%;
	border: 3px solid #3c8dbc;
	text-align: center;
	background: #f8f8f8;
	padding: 20px;
	margin-top: 31px;
}
.My_pet_2 {
	width: 25%;
	float: left;
}
.My_pet_3 {
	width: 50%;
}
.my_pet_3_1 {
	width: 50%;
	height: 34px;
	padding: 6px 12px;
	font-size: 12px;
	line-height: 1.428571429;
	color: #555555;
	vertical-align: middle;
	background-color: #ffffff;
	background-image: none;
	border: 1px solid #716F6F;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.my_sub_1 {
	width: 100%;
	border: 3px solid #3c8dbc;
	text-align: center;
	background: #f8f8f8;
	padding: 20px;
	margin-top: 31px;
}
a:hover, a:active {
  color: red;
}
a:link {
  color: blue;
}
td {
    padding: 0px;
        padding-bottom: 0px;
        padding-left: 0px;
    font-size: 14px;
    color: #000000;
    padding-left: 10px;
    padding-bottom: 0px;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: #95342e;
  color: white;
  text-align: center;
}
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table, thead, tbody, th, td, tr { 
		display: block; 
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}
	
	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
}
.heading{
	text-align:left;
}

	td:nth-of-type(1):before { content: "S.no."; }
	td:nth-of-type(2):before { content: "Petition No. &	date"; }
	td:nth-of-type(3):before { content: "Address"; }
	td:nth-of-type(4):before { content: "<?php echo $lang['pet_depart_category']; ?>"; }
	td:nth-of-type(5):before { content: "Concerned Office"; }
	td:nth-of-type(6):before { content: "<?php echo $lang['Pending_Period'];?>"; }}
</style>
</head>
<?php include('online_header_submission.php') ?>
<?php include('online_menu.php') ?>
<form name="view_status" id="view_status" action="online_my_petition_status.php" method="post">
<div class="My_pet_1">
<table name="tblMenuAllergens" width="" cellpadding="1">
<tbody>
<tr><br>
	<th style="text-align: center;color: #ffffff;font-weight: bold;font-size: 20px;background: #2740e5;"><?php echo $lang['Pending_list']; ?></td>
</tr>
</tbody>
</table>
</div>

<table  border="3">
	
<tr style="background-color=#ffffff">
<th style="text-align:center;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['S.No']; ?></th>
<th style="text-align:left;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['pet_no_date_type']; ?></th>
<th style="text-align:left;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['petition_comm_address']; ?></th>
<th style="text-align:left;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['pet_depart_category']; ?></th>
<th style="text-align:left;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['concerned_off']; ?></th>
<th style="text-align:center;background-color:#95342e;
//#6e8397;color:#ffffff;"><?php echo $lang['Pending_Period']; ?></th>
</tr>
<?php
$usr_mobile=stripQuotes(killChars(trim($_SESSION['USER_ID_PK'])));
	//$qua_sql = "(select * from vw_pet_master a  left join vw_petition_details b on a.petition_id=b.petition_id  where a.comm_mobile='".$usr_mobile."' and a.source_id=-1) n left join vw_pet_action c on n.petition_id=c.petition_id ";
	
/* 	select * from vw_pet_master a  join vw_petition_details b on a.petition_id=b.petition_id join vw_pet_ack_with_pet_office c on b.petition_id=c.petition_id join usr_dept_desig d on c.dept_desig_name=d.dept_desig_name
where a.comm_mobile='".$usr_mobile."' and a.source_id=-1; */
	
	$qua_sql="select * from vw_pet_master a  join vw_petition_details b on a.petition_id=b.petition_id join vw_pet_ack_with_pet_office c on b.petition_id=c.petition_id left join usr_dept_desig d on c.dept_desig_name=d.dept_desig_name
where a.comm_mobile='".$usr_mobile."' and a.source_id=-1;";

$insql="select petition_id from pet_master where comm_mobile='".$usr_mobile."'";
//-- and source_id=-1
$qua_sql="select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period,pet_type_name from fn_petition_details(array(".$insql.")) order by petition_id"; 

 $qua_sql="select petition_id,petition_no,TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date,petitioner_initial,petitioner_name,'Door no. : '::text || comm_doorno::text || ', ' || comm_street::text || ', ' || comm_area::text || ',<br>Pincode - ' || coalesce(comm_pincode,griev_pincode)::text || '.'::text as pet_address,
griev_type_name,griev_type_tname,griev_subtype_name,griev_subtype_tname,coalesce(griev_district_name,zone_name,range_name,griev_division_name,griev_circle_name,comm_state_name) as location_name,
coalesce(griev_district_tname,zone_tname,range_tname,griev_division_tname,griev_circle_tname,comm_state_tname) as location_tname,off_level_dept_name,off_level_dept_tname,fwd_off_level_dept_name,fwd_off_level_dept_tname,pet_type_name,pet_type_tname,pend_period ,source_id
 from fn_online_petition_details(array(".$insql.")) order by petition_id";  

//echo $qua_sql;
//exit;

	/* select *
from vw_pet_master a  left join vw_petition_details b on a.petition_id=b.petition_id where a.comm_mobile='".$usr_mobile."' and a.source_id=-1  */
	


	$qua_rs=$db->query($qua_sql);
$i=1;
$rows = $qua_rs->fetchAll();    
    foreach($rows as $row){
	
	if($_SESSION['lang']=='E'){
//		$label_name= $row['dept_name'];	
		$label_name1= $row['griev_type_name'];	
//		$label_name2= $row['dept_desig_name1'];	
		//$label_name3 = " and SDC(GDP)";	
	 
	}else if($_SESSION['lang']=='T'){
		//echo "hhh";
		//exit;
//		$label_name = $row['dept_tname'];
		$label_name1 = $row['griev_type_tname'];
		$label_name2 = $row['dept_desig_tname1'];
		//$label_name3 = "  மற்றும் சிறப்பு துணை ஆட்சியர் (கு.நா.ம. - GDP)";
	}else{
//		$label_name= $row['dept_name'];	
		$label_name1= $row['griev_type_name'];	
		$label_name2= $row['dept_desig_name1'];
		//$label_name3 = " and SDC(GDP)";			
	}
	
	if($row['pend_period']=='---'){
		$pend_period_1= $row['action_type_name'];	
		
		//$sdc=' and SDC(GDP)';
	}
	 /* else if($row['pend_period']=='00:00:00'){
		$pend_period_1=='00:00:00';
		$sdc=' and SDC(GDP)';	
		
	}  */ 
	else{
		
		//$sdc=' and SDC(GDP)';off_level_dept_name,off_level_dept_tname,fwd_off_level_dept_name,fwd_off_level_dept_tname
		if($_SESSION['lang']=='E'){
		if($row['off_level_dept_name']!=''){
			$label_name3 = $row['off_level_dept_name'];	
			$location_name = $row['location_name'];	
			$fwd_off_level_dept_name = $row['fwd_off_level_dept_tname'];	
		}
		}
		else if($_SESSION['lang']=='T'){
		if($row['off_level_dept_tname']!=''){
			$label_name3 = $row['off_level_dept_name'];	
			$location_name = $row['location_tname'];	
			$fwd_off_level_dept_name = $row['fwd_off_level_dept_name'];	
		
		}
		}else{
		//	$label_name3 =" and ".$row['dept_desig_name'];
			$label_name3 = $row['off_level_dept_name'];	
			$location_name = $row['location_name'];	
			$fwd_off_level_dept_name = $row['fwd_off_level_dept_name'];	
	} 
			
		$pend_period_1=$row['pend_period'];
		
		
	}
	$petition_no=$row['petition_no'];
	$petition_id=$row['petition_id'];
	$source_id=$row['source_id'];
	$griev_type_name=$row['griev_type_name'];
	
	$pet_address=$row['pet_address'];
	$pend_period=$row['pend_period'];
	//if not working comment below conditions
	 if($pend_period=='00:00:00'){
		$pend_period= '0 days';	
	}else if($row['pend_period']=='---'){
		$pend_period= $row['action_type_name'];	
	} 
	if($source_id==-1){
		$off_location_design="Submitted to : <b> {$label_name3}, {$location_name}. </b><br> Submission level: <b>{$row['fwd_off_level_dept_name']}</b>.";
	}else if($source_id==1){
			$off_location_design="Submitted to : <b>Elected Representative / Other VIP</b></br> Submission level: <b>Elected Representative / Other VIP Office.</b>";
		}else if($source_id==2){
			$off_location_design="Submitted to : <b>DGP, Tamil Nadu.</b></br> Submission level: <b>DGP Office.</b>";
		}else if($source_id==3){
			$off_location_design="Submitted to : <b>Commissioner, Tamil Nadu.</b></br> Submission level: <b>Commissioner Office.</b>";
		}else if($source_id==4){
			$off_location_design="Submitted to : <b>SP.</b></br> Submission level: <b>SP Office.</b>";
		}else if($source_id==5){
			$off_location_design="Submitted to : <b>IGP.</b></br> Submission level: <b>IGP Office.</b>";
		}else if($source_id==6){
			$off_location_design="Submitted to : <b>DIG .</b></br> Submission level: <b>DIG Office.</b>";
		}else if($source_id==7){
			$off_location_design="Submitted to : <b>JCOP.</b></br> Submission level: <b>JCOP Office.</b>";
		}else if($source_id==8){
			$off_location_design="Submitted to : <b>ADGP CB-CID.</b></br> Submission level: <b>ADGP CB-CID Office.</b>";
		}else if($source_id==9){
			$off_location_design="Submitted to : <b>IGP CB-CID.</b></br> Submission level: <b>IGP CB-CID Office.</b>";
		}else if($source_id==10){
			$off_location_design="Submitted to : <b>DIG CB-CID.</b></br> Submission level: <b>DIG CB-CID Office.</b>";
		}else if($source_id==11){
			$off_location_design="Submitted to : <b>SP CB-CID.</b></br> Submission level: <b>SP CB-CID Office.</b>";
		}else if($source_id==12){
			$off_location_design="Submitted to : <b>Additional Commissioner.</b></br> Submission level: <b>Additional Commissioner Office.</b>";
		}else if($source_id==13){
			$off_location_design="Submitted to : <b>Joint Commissioner.</b></br> Submission level: <b>Joint Commissioner Office.</b>";
		}else if($source_id==14){
			$off_location_design="Submitted to : <b>Deputy Commissioner.</b></br> Submission level: <b>Deputy Commissioner Office.</b>";
		}
	if($off_location_design==''){
		$off_location_design='---';
	}else{
		$off_location_design=$off_location_design;
	}
    echo '<tr>
		<td style="text-align:center;width:5%;">'.$i++.'</td>
			<td style="font-size: 16px;width:20%;"><a href="javascript:openPetitionStatusReport1('.$petition_id.')">';
			print_r($row['petition_no'].' <br>& '.$row['petition_date']);
			echo '</a></td>
			<td style=" text-align:left;width:20%">';print_r($pet_address);

			echo '</td>
	<td style=" text-align:left;width:20%">';print_r($griev_type_name.'</td><td style=" text-align:left;width:20%">'.$off_location_design);echo '</td>
	<td style=" text-align:center;width:20%">';print_r($pend_period);echo '</td>
		</tr>';
		
		
	
    }
?>


</tbody>
</table>
<!--div class="modal-footer">
         <button type="button" class="reg_btn" id="sub_mit1" title="Click here to Submit" onClick="return checkValue();"><?php echo $lang['SUBMIT_BUTTON_TITLE']; ?></button>
		  <button type="button" class="reg_btn fn_cl" id="reset" title="Click here to Reset" onClick="return v_reset();" ><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
        </div-->
<input type="hidden" name="petition_id1" id="petition_id1" />


</form>
<div class='footBottom'>
<p class="footer" id="footertbl" style="margin-bottom: 0px;">
Computerized By NATIONAL INFORMATICS CENTRE<!--  IT Support by: National Informatics Centre-->, TNSU, Chennai
</p>
</div>
</body>
<?php
//pg_close($db);
$db = null;
?>
<script>
 function openPetitionStatusReport1(petition_id){
	 //
	document.getElementById("petition_id1").value=petition_id;
	document.view_status.target = "Map";
	document.view_status.method="post";  
	document.view_status.action = "online_print_status_popup.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	
	if(map){	
		document.view_status.submit();
	}  
}

</script>
<script type="text/javascript" src="assets/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="dist/jquery.validate.js"></script>

