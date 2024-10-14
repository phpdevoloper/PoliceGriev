<?php 
ob_start();
session_start();

include("db.php");
include("common_fun.php");
//include("Pagination.php");
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
	.My_pet_3 {
				width: 100% !important;
			}
			.my_pet_3_1 {
				width: 100% !important;
			}
			.My_pet_2 {
				width: 100% !important;
			}
			.My_pet_2x {
				width: 100% !important;
			}
}
	
.My_pet_1 {
	width: 100%;
	
	text-align: center;

	padding: 20px;
	margin-top: 31px;
}
.My_pet_1x {
	width: 100%;

	text-align: center;

	margin-top: 31px;
	color: #000;
}
.My_pet_1_k {
	margin-left: 319px;
}
.My_pet_2 {
	width: 25%;
	float: left;
}
.My_pet_2x {
	width: 63%;
	font-size: 21px;
}
.My_pet_3 {
	width: 60%;
}
.my_pet_3_1 {
	width: 50%;
	height: 34px;
	padding: 6px 12px;
	font-size: 12px;
	line-height: 1.428571429;
	color: #555555;
	vertical-align: middle;

	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.my_sub_1 {
	width: 100%;

	text-align: center;

	padding: 20px;
	margin-top: 31px;
}
.lim {
	border: 3px solid #3c8dbc;
	border-radius: 4px;
	background-color: #ffffff;
	width: 75%;
	height: 100%;
}
/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
	.My_pet_1_k {
		margin-left: 0px;
	}
}

@media only screen and (max-width: 800px) {
	.My_pet_1_k {
		margin-left: 0px;
	}
}


/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
	
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
	
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
	
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
</style>
</head>
<?php include('online_header_submission.php') ?>
<?php include('online_menu.php') ?>
<br>
<br>
<br>
<br>
<form name="view_status" id="view_status" action="my_petition_status.php" method="post">
<div class='lim container'>
<div class="My_pet_1x">
	<div class="My_pet_1_k"><br><br>
		<div class="My_pet_2x" style="background: #2740e5;">
			<label style=" color: #ffffff;"><?php echo $lang['My_Petition_Status_LABEL_menu_hea']; ?><label>
		</div>
		
	</div><br>
</div>

<div class="My_pet_1">
	<div class="My_pet_1_k">
		<div class="My_pet_2"  style="text-align: right;">
			<label><?php echo $lang['petition_no']; ?><label>
		</div>
		<div class="My_pet_3">
			<input type="text"  id="petition_no" name="petition_no" onblur="killChars(this);" title="Enter Petition Number" placeholder="<?php echo $lang['petition_no']; ?>" maxlength="30" autocomplete="off" autofocus="" class="my_pet_3_1">
		</div>
		
	</div>
</div>
<div class="my_sub_1">
<button type="button" class="btn btn-primary" onClick="return checkValue();" style="margin-left :10px"><?php echo $lang['SUBMIT_BUTTON_TITLE']; ?></button>
<button type="button" class="btn btn-primary" onClick="return v_reset();"><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
</div>
</div>
<!--div class="modal-footer">
         <button type="button" class="reg_btn" id="sub_mit1" title="Click here to Submit" onClick="return checkValue();"><?php echo $lang['SUBMIT_BUTTON_TITLE']; ?></button>
		  <button type="button" class="reg_btn fn_cl" id="reset" title="Click here to Reset" onClick="return v_reset();" ><?php echo $lang['RESET_BUTTION_TITLE']; ?></button>
        </div-->



</form>
<div class='footBottom'>
<p class="footer" id="footertbl" style="margin-bottom: 0px;">
Computerized By NATIONAL INFORMATICS CENTRE<!--  IT Support by: National Informatics Centre-->, TNSU, Chennai
</p>
</div>
</body>
</html>
<?php
//pg_close($db);
$db = null;
?>

</body>
<?php
	
?>
<script>
/* function print_fun()
{
	   
	   document.getElementById("stype").value=($('input[name=status_type]:checked', '#view_status').val());
	   
       pet_no=$('#petition_no').val();
		 
		$('#petition_no').removeClass('error');
		document.getElementById("alrtmsg").innerHTML="";
		
		if($.trim($('#petition_no').val())=='')
		{
		$("#alrtmsg").html($('#petition_no').attr('data-error'));
		$('#petition_no').addClass('error');
		return false;
		}
		 
		else{
		 
		 $.ajax({
			type: "post",
			url: "check_petno_status.php",
			cache: false,
			data: {source_frm : 'check_petno',pet_no : pet_no},
			error:function(){ alert("some error occurred - pet_no check ") },
			success: function(html){
				  var str = html.trim();
				  
				if(str!=0){
					document.view_status.method="post";
					document.view_status.action = "print_status_page.php"
					document.view_status.submit();
					return true;
				  
				}
				else{ 
					 alert("Invalid Petition No.");
					 document.getElementById("petition_no").focus();
					 return false;
			 	}
				 
			  }
			});  
		    
        } 
     
} */
function checkValue() {	
if ($.trim($('#petition_no').val())=='')	{
	alert("Enter Petition Number.");		
		return false;
	} 
		 petition_no = $.trim(document.getElementById("petition_no").value);
		 //alert("petition_no:::"+petition_no);
		  $.ajax({
			type: "post",
			url: "online_check_petno_status.php",
			cache: false,
			data: {petition_no : petition_no},
			error:function(){ alert("some error occurred - pet_no check ") },
			success: function(html){
				
				  var str = html.trim();
				  //alert(str);
				if(str != 0){
					
				 document.view_status.method="post";
				 document.view_status.action = "online_print_status.php"
				 document.view_status.submit();
					return true;
				  
				}
				else{ 
					 alert("You are Not Authorized.");
					 document.getElementById("petition_no").value=""
					 document.getElementById("petition_no").focus();
					 return false;
			 	}
				 
			  }
			});  
		 /*   document.view_status.method="post";
		 document.view_status.action = "print_status_intermediate_page_1.php"
		 document.view_status.submit();  */
if ($.trim($('#petition_no').val())=='')	{
		$('#alertDiv font').html("<?php echo $lang['ENTER_PETITION_NUMBER']; ?>");
		$('#alertDiv').show();
		$('#petition_no').focus();
		
		return false;
	} /* else if($.trim($('#security_code').val())=='') {
		$('#alertDiv font').html("<?php echo $lang['ENTER_SECURITY_CODE']; ?>");
		$('#alertDiv').show();
		$('#security_code').focus();
		return false;
	}
	
    var tex_captch = document.getElementById("security_code").value;
	var xmlhttp = new XMLHttpRequest();
	
	xmlhttp.open("GET", "captcha_validation.php?q="+tex_captch, true);
	xmlhttp.onreadystatechange = function() {
		
    if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
		if(myObj[0]=='F') {
		document.getElementById("security_code").value="";
		document.getElementById("demo").innerHTML = myObj[0];
		 $('#alertDiv font').html("<?php echo $lang['ENTER_VALID_SECURITY_CODE']; ?>");
	     $('#alertDiv').show();
		 return false;
		} else {
			 document.view_status.method="post";
			 document.view_status.action = "print_status_intermediate_page.php"
			 document.view_status.submit();
			return false;
		}
    };
}; */

xmlhttp.send(null);
}
function v_reset() {
  document.getElementById("petition_no").value="";
  document.getElementById("petition_no").reset();
  document.getElementById("petition_no").focus();
  }
</script>
<script type="text/javascript" src="assets/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="dist/jquery.validate.js"></script>
