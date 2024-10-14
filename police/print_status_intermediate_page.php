<?php 
ob_start();
session_start();

include("db.php");
include("common_fun.php");
include_once 'common_lang.php';

$nonce = random_bytes(32);
$_SESSION['non']=base64_encode($nonce);
//header("Content-Security-Policy: object-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline'", TRUE);

$non=($_SESSION['non']);
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='$non'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='$non'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}
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
    font-size: 22px;
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
</style>
</head>

<script nonce='<?php echo $non; ?>' type="text/javascript" src="assets/js/jquery-2.1.1.min.js"></script>

<script nonce='<?php echo $non; ?>' type="text/javascript">
$(document).ready(function(){
	var pet = document.getElementById("pet_no").value;
	var language = document.getElementById("language").value;
	//alert(language);
	document.getElementById("header").style.display='none';
	document.getElementById("footer").style.display='none';
	$.ajax({
		type: "POST",
		url: "action_status.php",
		data: "petition_no="+pet+"&language="+language,
		success: function(xml) {
		 document.getElementById("loadmessage").style.display='none'; 
		if($(xml).find('error').eq(0).text()=='F'){
		var pet_no1 = $(xml).find('p_no').eq(0).text();
		//document.getElementById("pet_no1").innerHTML=pet_no1;
		// document.getElementById("pet_no").value='';
		 document.getElementById("p3_dataGrid1").style.display='block';
		 document.getElementById("header").style.display='block';
		 document.getElementById("footer").style.display='none';
		 //document.getElementById("print").style.display='none';

		 //window.location.href = 'index.php';
		} else {
		var doc = $(xml).find('Document_id').eq(0).text();
		var action_doc = $(xml).find('action_doc_id').eq(0).text();
		if (doc != '') {
			show_doc = true;
			show_doc_show = "<tr>";
		} else {
			show_doc = false;
			show_doc_show = "<tr style='display:none;'>";
		}
		show_doc = true;
		show_doc_show = "<tr>";
		if (action_doc != '') {
			show_action_doc = true;
			show_action_doc_show = "<tr>";
		} else {
			show_action_doc = false;
			show_action_doc_show = "<tr style='display:none;'>";
		}
		show_action_doc = true;
		show_action_doc_show = "<tr>";
		var mobile_number = $(xml).find('Mobile_Number').eq(0).text();
		var e_mail = $(xml).find('Email_Label').eq(0).text();
		
		if (mobile_number != '') {
			var comm_label = "<?php echo $lang['MOBILE_NUMBER_LABEL']; ?>";
			var comm_detail = mobile_number;
		} else if (e_mail != '') {
			var comm_label = "<?php echo $lang['Email_Label']; ?>";
			var comm_detail = e_mail;
		}
		var  doc_tr = show_doc_show+
					  "<td >"+"<?php echo $lang['PETITION_DOCUMENT_LABEL']; ?>"+"</td>"+
					  "<td >"+"<span id='span_dwnd'  onClick='download_document("+$(xml).find('Document_id').eq(0).text()+")'>"+$(xml).find('Document_name').eq(0).text()+"</sapn></td>"+
						"</tr>";
		var  action_doc_tr = show_action_doc_show+
			 "<td style='background-color: #dddddd;'>"+"<?php echo $lang['ORDER_CERTIFICATE_LABEL']; ?>"+"</td>"+
			"<td style='background-color: #dddddd;'>"+"<span id='span_dwnd'  onClick='action_doc("+$(xml).find('action_doc_id').eq(0).text()+")'>"+$(xml).find('action_doc_name').eq(0).text()+"</span></td>"+
			"</tr>";
			var fir_csr_det = 
			 "<tr><td style='background-color: #dddddd;'>"+"<?php echo $lang['FIR_CSR_LABEL']; ?>"+"</td>";
			var fir_csr_det1='';
			if($(xml).find('pet_ext_link_name').eq(1).text()!=''){
			fir_csr_det1 +=
			"<br>2) <b>"+$(xml).find('pet_ext_link_name').eq(1).text()+" Detail</b> - "+$(xml).find('fir_circle_name').eq(1).text()+" Police Station,"+$(xml).find('fir_district_name').eq(1).text()+" District. "+$(xml).find('pet_ext_link_name').eq(1).text()+" No.: <b>"+$(xml).find('pet_ext_link_no').eq(1).text()+"/"+$(xml).find('fir_csr_year').eq(1).text()+"</b>.";
			}
			if($(xml).find('pet_ext_link_name').eq(0).text()!=''){
			fir_csr_det += 
			"<td style='background-color: #dddddd;'>1) <b>"+$(xml).find('pet_ext_link_name').eq(0).text()+" Detail</b> - "+$(xml).find('fir_circle_name').eq(0).text()+" Police Station,"+$(xml).find('fir_district_name').eq(0).text()+" District. "+$(xml).find('pet_ext_link_name').eq(0).text()+" No.: <b>"+$(xml).find('pet_ext_link_no').eq(0).text()+"/"+$(xml).find('fir_csr_year').eq(0).text()+"</b>."+fir_csr_det1+"</td>"+
			"</tr>";
			};var link_petition_status ='';
			if ($(xml).find('link_petition_status').eq(0).text()!='') {
				var link_petition_status = 
			 "<tr><td style='background-color: #dddddd;'>"+"<?php echo $lang['LINK_PET_STATUS']; ?>"+"</td>"+
			 "<td >"+$(xml).find('link_petition_status').eq(0).text()+"</td></tr>";
			}
			//alert("link_petition_status::"+link_petition_status);
			//alert(doc_tr);	
			//if($(xml).find('error').eq(0).text()=='F'){
			
			
		$('#p3_dataGrid')
 .append(
 "<div class='contentMainDiv' style='width:98%;margin-right:auto;margin-left:auto;' align='center'>"+
 "<div class='contentDiv' >"+"<table class='viewTbl'  style='margin-top: 20px;'>"+
"<tbody>"+
"<tr id='he_gov' style='display:none;'>"+
	"<td colspan='2' class='heading text-center' style='font-weight: 700;font-size: 20px;'>"+" <label>Government of Tamil Nadu - Petition Processing Portal - Online Status </label>"+
	"</td>"+
"</tr>"+
"<tr>"+
	"<td colspan='2' class='heading text-center' style='font-weight: 700;font-size: 20px;'>"+"<?php echo $lang['PETITION_STATUS_TITLE']; ?>"+"</td>"+
"</tr>"+
"<tr>"+
	"<td style='width: 24%;'>"+"<?php echo $lang['PETITION_NO_DATE_AND_TYPE_LABEL']; ?>"+"</td>"+
	"<td >"+$(xml).find('Petition_No_and_Date').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td >"+"<?php echo $lang['SOURCE_SUB_SOURCE_AND_REMARKS_LABEL']; ?>"+"</td>"+
	"<td >"+$(xml).find('Source_Name').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td >"+"<?php echo $lang['DEPARTMENT_LABEL']; ?>"+"</td>"+
	"<td >"+$(xml).find('Department').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td >"+"<?php echo $lang['PETITION_MAIN_AND_SUB_CATEGORY_LABEL']; ?>"+"</td>"+
	"<td >"+$(xml).find('Petition_Main_Type_and_Petition_Sub_Type').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td>"+"<?php echo $lang['PETITION_DETAILS_LABEL']; ?>"+"</td>"+
	"<td>"+$(xml).find('Petition_Details').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td>"+"<?php echo $lang['CONCERNED_OFFICER_ADDRESS_LABEL']; ?>"+"</td>"+
	"<td>"+$(xml).find('Petition_Office_Address').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td>"+"<?php echo $lang['PETITIONER_AND_FATHERHUSBAND_NAME_LABEL']; ?>"+"</td>"+
	"<td>"+$(xml).find('Petitioner_Name_Father_Spouse_Name_and_Address').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td>"+"<?php echo $lang['ADDRESS_LABEL']; ?>"+"</td>"+
	"<td>"+$(xml).find('Address').eq(0).text()+"</td>"+
"</tr>"+
"<tr>"+
	"<td>"+comm_label+"</td>"+
	"<td>"+comm_detail+"</td>"+
"</tr>"+doc_tr+action_doc_tr+fir_csr_det+link_petition_status+
"<tr>"+
	"<td style='color: #000000;font-weight: bold;background-color: #8d7e7e;'>"+"<?php echo $lang['STATUS']; ?>"+"</td>"+
	"<td style='color: #000000;font-weight: bold;background-color: #8d7e7e;'>"+$(xml).find('Status').eq(0).text()+"</td>"+
"</tr>"+ 
/* "<tr>"+
	"<td style='color: #000000;font-weight: bold;background-color: #8d7e7e;'>"+"<?php echo $lang['STATUS']; ?>"+"</td>"+
	"<td style='color: #000000;font-weight: bold;background-color: #8d7e7e;'>"+$(xml).find('Status').eq(0).text()+"</td>"+
"</tr>"+ */
"</tbody>"+"</table>"+"</div>"+"</div>"
);
document.getElementById("p3_dataGrid").style.display='';
   $('#p3_dataGrid')
   .append(
   "<div class='taple_scroll'>"+
   "<div class='contentMainDiv' style='width:98%;margin-right:auto;margin-left:auto;' align='center'>"+
   "<div class='contentDiv' >"+
   "<table class='viewTbl'  style='margin-top: 20px; '>"+
		"<thead >"+
		 "<tr>"+
			  "<th  class='heading text-left' style='font-size:15px;' colspan='2'>"+"<?php echo $lang['PROCESSING_DETAILS_LABEL']; ?>"+"</th>"+
			  "<th class='heading text-right' style='font-size:15px;' >"+"<?php echo $lang['PENDING_PERIOD_LABEL']; ?>"+$(xml).find('Pending_Period').text()+"</th>"+
		 "</tr>"+
		 "<tr>"+
			  "<th class='text-center' >"+"<?php echo $lang['DATE_AND_TIME_LABEL']; ?>"+"</th>"+
			  "<th class='text-center' >"+"<?php echo $lang['PROCESSING_OFFICIALS_LABEL']; ?>"+"</th>"+
			  "<th class='text-center'>"+"<?php echo $lang['REMARKS_STATUS']; ?>"+"</th>"+
		 "</tr>"+
		 "<tr>"+
"<td colspan='7' class='text-center' style='color:red;font-weight: bold;font-size: 15px;'>"+$(xml).find('no_action_taken').eq(0).text()+"</td>"+
		"</tr>"+
		"</thead>"+
"<tbody id='records'>"+
"</tbody>"+
"</table>"+
"</div>"+
"</div>"+
"</div>");
 $(xml).find('pet_action_id').each(function(i)	{
		if($(xml).find('action_type_code').eq(i).text()=='A' || $(xml).find('action_type_code').eq(i).text()=='R'){
				act_rem=$(xml).find('Action_Remarks_value').eq(i).text();
			}else{
				act_rem='';
			}
		$('#records')
			.append("<tr>"+"<td style='width: 17%;'>"+$(xml).find('Action_Taken_Date_Time').eq(i).text()+
			"</td>"+"<td style='width: 34%;' >"+$(xml).find('Processing_Officials_value').eq(i).text()+
			"</td>"+"</td>"+"<td style='width: 34%;' >"+act_rem+
			"</td>"+
			/* "<td>"+$(xml).find('Action_Remarks_value').eq(i).text()+"</td>"+ */
			
			"</tr>");

    }); 
		document.getElementById("header").style.display='';
	    document.getElementById("footer").style.display='';
	   // document.getElementById("he_no").style.display='';
		}
				} 
			}); 
	
});  

function print_page()
{
//alert("asdf");
document.getElementById("header").style.display='none';
document.getElementById("btn_row").style.display='none';
document.getElementById("he_gov").style.display='';
//document.getElementById("footertbl").style.visibility='hidden';
print();
document.getElementById("header").style.display='';
document.getElementById("btn_row").style.display='';
document.getElementById("he_gov").style.display='none';
//document.getElementById("footertbl").style.visibility='visible';
//self.close();
}

function goback(){
	window.location = 'index.php';	
}
function download_document(url){
source='P';
	window.location.href="http://14.139.183.34/police/pm_petition_doc_download.php?doc_id="+url+"&source="+source;
}
function action_doc(url){
source='A';
	window.location.href="http://14.139.183.34/police/pm_petition_doc_download.php?doc_id="+url+"&source="+source;
}
</script>

<script nonce='<?php echo $non; ?>' type="text/javascript" language="javascript">   
function disableBackButton()
{
window.history.forward()
}  
disableBackButton();  
window.onload=disableBackButton();  
window.onpageshow=function(evt) { if(evt.persisted) disableBackButton() }  
window.onunload=function() { void(0) }  

$(document).ready(function(){
document.getElementById("back").onclick = function(){
	goback();
}; 
document.getElementById("print").onclick = function(){
	return print_page();
}; 
document.getElementById("back1").onclick = function(){
	goback();
}; 
});
</script>

<body onload="noBack();" >
<?php
$petno=stripQuotes(killChars(trim($_POST['petition_no'])));

//unset($_SESSION['session_on']);

//$_SESSION['session_on']=2;
//$_SESSION = array();
//session_unset();
//session_destroy();


?>

 <input type="hidden" id="pet_no" value="<?php echo $petno; ?>">
  <input type="hidden" id="language" value="<?php echo $lang['LANGUAGE']; ?>">
 <div class="se-pre-con"></div>
 <?php include('online_header_status.php'); ?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" >
<div id="loadmessage" div align="center" style="margin-top: 15%;" ><img src="images/animation.gif" width="20%" height="90" alt=""/></div>
<div class="contentMainDiv" style="width:98%;margin-right:auto;margin-left:auto;" align="center">
<div class="contentDiv " >
<div id="p3_dataGrid" style="display: none;"></div>
<div id="p3_dataGrid1"  style="display: none;">

		<table class="gridTbl" style="width: 98%;">
		<tr >
			<td  class="heading text-center" style="font-weight: 700;font-size: 20px;"><?php echo $lang['PETITION_STATUS_TITLE']; ?></td>
		</tr>
		<tr >
			<td  class="text-center pad_t" style="color:red;"><b><?PHP echo $lang['No_Record_Found_Label']; //No_Record_Found_Label ?> </b></td>
		</tr>
		<tr>
			<td  class="text-center pad_t" >
			 <a class="btn btn-primary fa fa-sign-out"  name="back" id="back">&nbsp;<?PHP echo $lang['Back_Button_Label']; //View ?></a>
			</td>
		</tr>
		<tr>
				<td colspan="7" class="text-center" style="padding: 10px;" > <?php echo $lang['NIC_TN_DESC']; ?> </td>
		</tr>
			
		</table>
</div>

	<div class="taple_scroll">
	<table class="gridTbl" style="width: 98%;"  id="footer">
	<thead>
		<tr id='btn_row' >
			<td colspan="7" class="text-center pad_t" >
			 <a class="btn btn-primary fa fa-print"  name="print" id="print">&nbsp;<?PHP echo $lang['Print_Button_Label']; //View ?></a> 
			 <a class="btn btn-primary fa fa-sign-out"  name="back" id="back1">&nbsp;<?PHP echo $lang['Back_Button_Label']; //View ?></a>
			</td>
		</tr>
		
		<td colspan="7" class="text-center" style="padding: 10px;" >
			<?php echo $lang['NIC_TN_DESC']; ?>
		</td>
		</tr>

	</tbody>
</table>
</div>	
</div>
</div>
</form>
</body>


