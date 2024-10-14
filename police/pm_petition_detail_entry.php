<?php
error_reporting(0);
ob_start();
session_start();
$prev_src = $_SESSION["prev_src"];
$prev_pet_type = $_SESSION["prev_pet_type"];
$prev_disposing_officer = $_SESSION["prev_disposing_officer"];
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	echo "<script> alert('Timed out. Please login again');</script>";
	echo '<script type="text/javascript">window.location="logout.php"</script>';
	exit;
}
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='1a2b'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='1a2b'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}
$pagetitle="Petition Details Entry";
include('header_menu.php');
include("menu_home.php");
include("db.php"); 
include("common_date_fun.php");
include("pm_common_js_css.php");  
?>
<style type="text/css">
/*#f40b8d#b0452b*/
.high_param {
  border-color: #95342e !important;
 // border-image: none;
  //border-radius: 6px 6px 6px 6px;
  border-style: solid;
//  border-width: 2px;
  box-shadow: 0 0 3px #95342e !important;
  background-color: #f2dfad;
}
.error {
   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #FF0000 !important;
    border-image: none;
    border-radius: 3px 3px 3px 3px;
    border-style: solid;
    border-width: 2px;
    box-shadow: 0 0 6px #F45B7A!important;
}
#alrtmsg
{
	font:Arial, Helvetica, sans-serif !important;
	font-size:16px;	
	font-weight:100 !important;
	background-color:#FF0000;
	color:#FFFF00;

}
#clicker
{
	font-size:20px;
	cursor:pointer;
}
#popup-wrapper
{
	width:500px;
	height:300px;
	background-color:#ccc;
	padding:10px;
}
#instructions {
	max-width: 1164px !important;
}

select {
  background-color: #fff;
}select:disabled {
  background-color: #d3d3d3;
}
.blue_star{
	color: #0000FF;
	padding-left: 2px;
}
</style>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/moment.min.js"></script>
<script type="text/javascript" nonce='1a2b'>
function preventBack(){
	window.history.forward();
}
setTimeout("preventBack()", 0);
window.onunload=function(){
	null
};
$(document).ready(function(){
	$('.divTable input,.divTable select,.divTable textarea').keyup(function() {
		if(($.trim($(this).val())!='')&&($.trim($(this).val())!=0))
		{
			$(this).removeClass('error');
			$('#alrtmsg').text('');
		}
	});	
	$('.divTable input,.divTable select,.divTable textarea').blur(function() {
		if($(this).val()=='')
		{
			if($(this).attr('data_valid')!='no')
			{
				$(this).addClass('error');
				$("#alrtmsg").html($(this).attr('data-error'));
			}
		}
		else
		{
			$(this).removeClass('error');
			$('#alrtmsg').text('');
		}
	});	
	$('#pet_forward').click(function(){
		//alert("1111111111111111111111111111");
		setDisable("#supervisory_officer", false);
		load_ef_for_officer();
		setDisable("#concerned_officer", false);
		setDisable("#off_d_id", false);
		$('#optgroup_DGP').show();
		//document.getElementById("all_link").style.display="";		
		//get_officer_list();			 
	});	
	
	$('#pet_delegate').click(function(){
		//alert("222222222222222222222");
		var disposing_officer = $('#disposing_officer').val();
		if (disposing_officer == '') {
			alert("Select Initiating Officer");
			return false;
		}else {
			
			if($('#office_pattern').css("display") === "none"){}else{
				//alert($('#office_loc_id').val());return false;
				if($('#office_loc_id').val()==''){
					alert("Select Petition Office");
					return false;
				}
			}
			
			$('#concerned_officer').val('');
			$('#supervisory_officer').val('');
			setDisable("#supervisory_officer", false);
			setDisable("#concerned_officer", false); 
			
			if ($('#office_loc_id').val() != '') {
				//alert("1");
				populateSupervisoryOfficers();
			} else {
				//alert("2");
				load_ef_officer();
			}
			
			
		}
	});
	
	$('#pet_action_taken').click(function(){
		//alert("33333333333333333333333");
		document.getElementById("supervisory_officer").options.length=1;
		document.getElementById("concerned_officer").options.length=1;		
 		setDisable("#supervisory_officer", true);
		setDisable("#concerned_officer", true); 
		load_ext_dist();
		//populateSupervisoryOfficers();
/* 		$("#concerned_officer").removeClass('error');
		$("#concerned_officer").val('');
		$("#off_d_id").val('');
		$('#optgroup_DGP').show(); */

		//setDisable("#off_d_id", true);
		//document.getElementById("all_link").style.display="none";
	});
	
/* 	$('#pet_delegate').click(function(){
		
		setDisable("#concerned_officer", false);
		setDisable("#off_d_id", false);
		//document.getElementById("all_link").style.display="";		//pet_delegate
		//get_officer_list();
		//alert("Delegate");
	}); */								 
	$('#clear').click(function(){document.petiton_detail_entry.reset();
		if($('#office_pattern').css("display") === "none"){
			$('#office_level').val('');
		$('#office_loc_id').val('');
		$('#office_level').empty();
		$('#office_loc_id').empty();
		$('#griev_subcode').val('');
		$('#dept_off_level_pattern_id').val('');
		$('#old_pet_no').val('');
		loadOfficeLevel();
		document.getElementById('office_pattern').style.display='none';
		document.getElementById('linked_pet').innerHTML='';
		document.getElementById('office_pattern_icon').value='+';
		load_ef_officer();
		}else{
				$('#office_level').val('');
		$('#office_loc_id').val('');
		$('#office_level').empty();
		$('#office_loc_id').empty();
		$('#griev_subcode').val('');
		$('#dept_off_level_pattern_id').val('');
		$('#old_pet_no').val('');
		loadOfficeLevel();
		document.getElementById('office_pattern').style.display='none';
		document.getElementById('linked_pet').innerHTML='';
		document.getElementById('office_pattern_icon').value='+';
		load_ef_officer();
			}
	});
	
	
		
	$('#save').click(function(){	 
		if($('#off_level_id').val()==2){			 
			if($("#gre_taluk").val()==""){
				$(this).addClass('error');
				$("#alrtmsg").html($(this).attr('data-error'));
			}
			else if($("#gre_urban_body").val()==""){
				$(this).addClass('error');
				$("#alrtmsg").html($(this).attr('data-error'));
			}		
		}
	});	 
	  //On Page load hide Source_remarks_grid
	$('.source_remarks_grid').hide();   
	$('#sub_source').on('change', function() {
		$('.source_remarks_grid').show();
	});

	$('#source').on('change', function() {
		$('.source_remarks_grid').hide();
	});	  
	$('.survey_no').hide(); 
	$('#griev_maincode').on('change', function() {
		if($('#griev_maincode').val()==1)
			$('.survey_no').show();
		else
			$('.survey_no').hide();
	});
	/* if ($('#source').val() != '') {
		fixDisposingOfficer();
	} */
	var disposing_officer = $('#disposing_officer').val();
	if(disposing_officer!=''){
		load_ef_officer();
		}
}); 
 
$(window).bind("load", function() {
   var source = $('#source').val();
   if($('#login_level').val()== 'BOTTOM'){
	selectActionTakenDefault();
   }
});


function selectActionTakenDefault() {
	$("#pet_action_taken").attr('checked', 'checked');
	$("#supervisory_officer").removeClass('error');
	$("#supervisory_officer").val('');
	setDisable("#supervisory_officer", true);
	$("#concerned_officer").removeClass('error');
	$("#concerned_officer").val('');
	setDisable("#concerned_officer", true);
	setDisable("#off_d_id", true);
	//document.getElementById("all_link").style.display="none";
	if($("#off_level_id").val()==46){
		showhid();
		loadOfficeLocations();
		setDisable("#office_pattern_icon", true);
		setDisable("#dept_off_level_pattern_id", true);
		$("#dept_off_level_pattern_id").css('background-color','#fff');
		setDisable("#office_level", true);
		$("#office_level").css('background-color','#fff');;
		setDisable("#office_loc_id", true);
		$("#office_loc_id").css('background-color','#fff');
		load_ext_dist();
		}
}
	
function setLabelForSelected() {
	var selected = document.getElementById("sub_source");
	var lang = document.getElementById("lang").value;
	var namelabel = document.getElementById("namelabel").value;
	document.getElementById("source_remarks").value = '';
	if (lang == 'E') {
		selectedText = "Name of " + selected.options[selected.selectedIndex].text;
	} else {
		selectedText = selected.options[selected.selectedIndex].text+' ' + namelabel;
	}			
	document.getElementById("sourcename").innerHTML  = selectedText;
	document.getElementById("source_remarks").focus();
}
function resetDepartment() {
	var html = '<select><option value="">--Select--</select>';
	document.getElementById("dept").innerHTML=html;
}
function controlLocDetails(){
	if($('#off_level_id').val()==2){
		setDisable("#gre_taluk", true);
		setDisable("#gre_rev_village", true);
		$("#gre_taluk").val('');
		$("#gre_rev_village").val('');
	}
	else{
		setDisable("#gre_taluk", false);
		setDisable("#gre_rev_village", false);
	}
}
function charactersonly(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46 && unicode!=64  )
	{
	if (( ((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32))
			return true
	else
			return false
	}
}

function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{

		if(unicode<48||unicode>57)
		return false
	}
}
function characters_numsonly(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46)
	{
	if ((((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && 
	unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode==45 || unicode>=47 && unicode<=57))
			return true
	else
			return false
	}
}

function chk_email(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46)
	{
	if ((unicode >63 && unicode<123 && unicode!=96 && unicode!=95 && unicode!=94 && 
	unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode==45 || unicode==95|| unicode>=47 && unicode<=57))
			return true
	else
			return false
	}
}

function characters_numsonly_grievance(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46)
	{
	if ( (((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || unicode==32 || unicode>=44 && unicode<=59)
			return true
	else
			return false
	}
}	

function characters_numsonly_instructions(e) {
	var unicode=e.charCode? e.charCode : e.keyCode;
	if (unicode!=8 && unicode!=9 && unicode!=46)
	{
	if ( (((unicode >64 && unicode<122) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=94 && unicode!=92 ) || unicode==32 ||  unicode==38 || unicode==123 || unicode==125 || unicode==40 || unicode==41 || 
	(unicode>=44 && unicode<=59))
			return true
	else
			return false
	}
}	
function numbersonly_ph(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		if((unicode<48||unicode>57)&& unicode !=43) {
			alert("Only numbers 0 to 9 and + are allowed");
			return false
		}
	}
}
function mob_chk()
{
	mob_no=document.getElementById("mobile_number").value.length;
	if(mob_no < 10)
	{
		alert("Mobile Number cannot be less than 10 characters");
		document.getElementById("mobile_number").value="";
		document.getElementById("mobile_number").focus();
		return false;
	} 
}
////////////// For Avoid Special Characters ////////////
function avoid_Special(elementid)
{
	var iChars = "!@#$%^&*()+=-[]\\\';,{}|\":<>?";
	var val= document.getElementById(elementid).value;
	for (var i = 0; i < val.length; i++) {
		if (iChars.indexOf(val.charAt(i)) != -1) {
			alert ("Special characters are not allowed.\n Please remove them and try again.");
			document.getElementById(elementid).value="";
			document.getElementById(elementid).focus();
			return false;
		}
	}
}
function avoid_Special_doorno(elementid)
{
	var iChars = "!@#$%^&*()+=[]\\\';,{}|\":<>?";
	var val= document.getElementById(elementid).value;
	for (var i = 0; i < val.length; i++) {
		if (iChars.indexOf(val.charAt(i)) != -1) {
			alert ("Special characters are not allowed.\n Please remove them and try again.");
			document.getElementById(elementid).value="";
			document.getElementById(elementid).focus();
			return false;
		}
	}
}
function avoid_special_grievance(elementid)
{
	var iChars = "!@#$%^&*()+=[]\\\';,{}|\":<>?";
	var val= document.getElementById(elementid).value;
	for (var i = 0; i < val.length; i++) {
		if (iChars.indexOf(val.charAt(i)) != -1) {
			alert ("Special characters are not allowed.\n Please remove them and try again.");
			document.getElementById(elementid).value="";
			document.getElementById(elementid).focus();
			return false;
		}
	}
}
////////////////////////////////////////
function textCounter(field,cntfield,maxlimit)
{
 	   if(field.value.length > maxlimit)
           field.value = field.value.substring(0,maxlimit);
       else
           cntfield.value = maxlimit - field.value.length; 
}
//To Get the sub source details
function get_sub_source_details()
{
	source_id = $('#source').val();	
	if (source_id==1) {
		document.getElementById("elec_row").style.display = '';		
	} else {
		document.getElementById("elec_row").style.display = 'none';	
	}
   	if (source_id != '' && source_id==1) {
		$.ajax({
			type:"post",
			url:"pm_petition_detail_entry_action.php",
			cache:false,
			data:{source_frm: 'get_sub_source', source_id: source_id},
			error:function(){alert ("get_sub_source: Some error occured")},
			success: function(html){
				//alert(html);
				document.getElementById("sub_source").innerHTML=html;
				if (document.getElementById("sub_source").length == 1) {
					document.getElementById("sub_source").disabled = true;
					document.getElementById("sourcecomments").value = 'Y';	
				} else {
					document.getElementById("sub_source").disabled = false;
					document.getElementById("sourcecomments").value = 'N';	
				}
			}
		});	
	} 	
}
function fixDisposingOfficer() {
	document.getElementById("disposing_officer").options.length=1;
	source_id = $('#source').val();
	if (source_id != '') {
		$.ajax({
			type:"post",
			url:"pm_petition_detail_entry_action.php",
			cache:false,
			data:{source_frm: 'get_disposing_officer', source_id: source_id},
			error:function(){alert ("get_disposing_officer: Some error occured")},
			success: function(html){
				document.getElementById("disposing_officer").innerHTML=html;

			}
		});	
	} else {
		var html = '<select><option value="">--Select--</select>';
		document.getElementById("disposing_officer").innerHTML=html;
	}
} 

/* function getDisposingOfficerDetails() {
	disposing_officer = $('#disposing_officer').val();
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_disposing_officer_details"+"&disposing_officer="+disposing_officer,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			$(xml).find('off_level_id').each(function(i) // for loop
			{
				document.getElementById("disposing_officer_off_loc_id").value = $(xml).find('off_loc_id').eq(i).text();  
				document.getElementById("disposing_officer_off_level_id").value = $(xml).find('off_level_id').eq(i).text(); 
					 
			});
			loadTalukForSplCamp();		 
					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
	
} */

function get_griev_detais() {
	griev_code=$('#griev_code').val(); 
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'griev_maindetails',griev_code : griev_code},
		error:function(){ alert("Enter valid grievance code")},
		success: function(html){
			document.getElementById("griev_maincode").innerHTML=html;
			if($('#griev_maincode').val()==1)
			$('.survey_no').show();
			else
			$('.survey_no').hide();
			get_griev_sub_category();
		}
	} ); 
}
function get_griev_sub_category() {
	griev_code=$('#griev_code').val(); 
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'griev_subdetails',griev_code : griev_code},
		error:function(){ alert("Enter valid grievance code")},
		success: function(html){
			document.getElementById("griev_subcode").innerHTML=html;
		}
	} );	
}

function get_sub_category() {
	griev_main_code=$('#griev_maincode').val();
	if(griev_main_code != ""){ 
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'griev_subcategory',griev_main_code : griev_main_code},
			error:function(){ alert("griev_subcategory: some error occurred") },
			success: function(html){
				document.getElementById("div_sub_category").innerHTML=html;
			}
		} ); 
	} else {
		var html = '<select><option value="">--Select--</select>';
		document.getElementById("div_sub_category").innerHTML=html;
	}
}
function get_griev_code(){
	griev_sub_code=$('#griev_subcode').val();
	if (griev_sub_code != '') {
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'get_griev_code',griev_sub_code : griev_sub_code},
			error:function(){ alert("get_griev_code: some error occurred") },
			success: function(html){
				document.getElementById("div_griev_code").innerHTML=html;
				checkForRemarks();
			}
		} ); 
	}
}

function populateConcernedOfficers() {
	if($('#office_pattern').css("display") === "none"){
	document.getElementById("concerned_officer").options.length=1;
	var disposing_officer = $('#disposing_officer').val();
	var supervisory_officer = $('#supervisory_officer').val();
	if($('#office_pattern').css("display") === "none"){
		var office_level = '';
		var office_loc_id = '';
	}else{
		var office_level_id = $('#office_level').val();
		var office_loc_id = $('#office_loc_id').val();
		//alert(office_loc_id+','+office_level);
		var arr = office_level_id.split("-");
		var office_level=arr[0];
	}
	
/* 	alert("supervisory_officer:::::"+supervisory_officer);
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2]; */
	//alert("off_level_id::::::"+off_level_id);
	//if (supervisory_officer != '') {
		$.ajax({
			type: "post",
			url: "pm_concerned_officers_action.php",
			cache: false,
			data: {source_frm : 'load_Concerned_Officers',disposing_officer:disposing_officer,supervisory_officer:supervisory_officer,office_level:office_level,office_loc_id:office_loc_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("concerned_officer").innerHTML=html;
			}
	});	
	}else{
		document.getElementById("concerned_officer").options.length=1;
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var pattern_id = $('#dept_off_level_pattern_id').val();
	var office_level = $('#office_level').val(); 
	var office_loc_id = $('#office_loc_id').val();
	var dept_id = $('#dept_id').val();
	var pet_off_id = $('#pet_off_id').val();
	var disposing_officer = $('#disposing_officer').val();
	var supervisory_officer = $('#supervisory_officer').val();
	
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	//alert("off_level_id::::::"+off_level_id);
	//if (supervisory_officer != '') {
		$.ajax({
			type: "post",
			url: "pm_concerned_officers_action.php",
			cache: false,
			data: {source_frm : 'loadConcernedOfficers',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id,petition_office_loc_id:office_loc_id,pet_off_id:pet_off_id,disposing_officer:disposing_officer,supervisory_officer:supervisory_officer},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("concerned_officer").innerHTML=html;
			}
		});	
	}
	//}
}
/*
function plolulateSupervisory_Officers() {
	document.getElementById("concerned_officer").options.length=1;
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var pattern_id = $('#dept_off_level_pattern_id').val();
	var office_level = $('#office_level').val(); 
	var office_loc_id = $('#office_loc_id').val();
	var dept_id = $('#dept_id').val();
	var pet_off_id = $('#pet_off_id').val();
	var disposing_officer = $('#disposing_officer').val();
	var supervisory_officer = $('#supervisory_officer').val();
	
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	//alert("off_level_id::::::"+off_level_id);
	if (supervisory_officer != '') {
		$.ajax({
			type: "post",
			url: "pm_concerned_officers_action.php",
			cache: false,
			data: {source_frm : 'loadConcernedOfficers',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id,petition_office_loc_id:office_loc_id,pet_off_id:pet_off_id,disposing_officer:disposing_officer,supervisory_officer:supervisory_officer},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("supervisory_officer").innerHTML=html;
			}
		});	
	}
}
*/
/*
function resetOtherOptions() {
	document.getElementById("office_level").options.length=1;
	document.getElementById("all_link").style.display="none";
	document.getElementById("all_off").style.display="none";
	document.getElementById("con_off").style.display="";	
	document.getElementById("office_loc_id").options.length=1;
	document.getElementById("concerned_officer").options.length=1;
}
*/
function loadOfficeLevel() {
	document.getElementById("concerned_officer").options.length=1;
	document.getElementById("supervisory_officer").options.length=1;
	document.getElementById("office_level").options.length=1;
	var html='<option value="">--Select--</option>';
	document.getElementById("office_loc_id").innerHTML=html;
	document.getElementById("pet_all_link").style.display="none";
	document.getElementById("pet_all_off").style.display="none";
	document.getElementById("pet_off").style.display="";
	document.getElementById("pet_off_name").value="";
	var pattern_id = $('#dept_off_level_pattern_id').val();
	//if (pattern_id != "") {
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'loadOfficeLevel',pattern_id : pattern_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office_level").innerHTML=html;
				//document.getElementById("conc_office_level").innerHTML=html;
			}
		});
	//}
	
}
function loadOfficeLocations() {
	document.getElementById("concerned_officer").options.length=1;
	document.getElementById("supervisory_officer").options.length=1;
	var optionLength = document.getElementById("office_loc_id").options.length;
	if (optionLength > 1) {
		document.getElementById("office_loc_id").options.length = 1;
	} else if (optionLength <= 1){
		var html='<option value="">--Select--</option>';
		document.getElementById("office_loc_id").innerHTML=html;
		populateSupervisoryOfficers();
	}
	//document.getElementById("concerned_officer").options.length=1;
	document.getElementById("pet_all_link").style.display="none";
	document.getElementById("pet_all_off").style.display="none";
	document.getElementById("pet_off").style.display="";
	document.getElementById("pet_off_name").value="";	
	var dept_id = $('#dept_id').val();
	var office_level = $('#office_level').val();
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();//1182
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	//alert("off_level_office_id:::::"+off_level_office_id);
	if (off_level_id > 13) {
	//	document.getElementById("pet_all_link").style.display="";
	//	document.getElementById("pet_all_off").style.display="";
	//	document.getElementById("pet_off").style.display="none";
	} else {
	//	document.getElementById("pet_all_link").style.display="none";
	//	document.getElementById("pet_all_off").style.display="none";
	//	document.getElementById("pet_off").style.display="";
		
	}
	if (office_level != '') {
		$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'loadLocations',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("office_loc_id").innerHTML=html;
				if (document.getElementById("office_loc_id").value != ''){
					populateSupervisoryOfficers();
				}
			}
		});
	}
		
	
}

function loadConcernedOfficeLocations() {
	document.getElementById("conc_office_loc_id").options.length=1;
	document.getElementById("concerned_officer").options.length=1;
	document.getElementById("conc_all_link").style.display="none";
	document.getElementById("conc_all_off").style.display="none";
	document.getElementById("con_off").style.display="";
	document.getElementById("conc_off_name").value="";	
	
	var dept_id = $('#dept_id').val();
	var office_loc_id = $('#office_loc_id').val();
	
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var conc_office_level = $('#conc_office_level').val();
	var arr = conc_office_level.split("-");
	var conc_off_level_id=arr[0];
	var conc_off_level_dept_id=arr[1];
	var conc_dept_off_level_office_id=arr[2];
	
	var office_level = $('#office_level').val();
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	
	var pet_off_id = $('#pet_off_id').val();
	//alert("pet_off_id:::::"+pet_off_id)
	/*
	if (off_level_id > 13) {
		document.getElementById("conc_all_link").style.display="";
		document.getElementById("conc_all_off").style.display="";
		document.getElementById("con_off").style.display="none";
	} else {
		document.getElementById("conc_all_link").style.display="none";
		document.getElementById("conc_all_off").style.display="none";
		document.getElementById("con_off").style.display="";
		
	}
	*/
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'loadConcernedLocations',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id,conc_off_level_id:conc_off_level_id,conc_off_level_dept_id:conc_off_level_dept_id,conc_dept_off_level_office_id:conc_dept_off_level_office_id,petition_office_loc_id:office_loc_id,pet_off_id:pet_off_id},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("conc_office_loc_id").innerHTML=html;			
		}
	});	
	
}

function loadEnq() {
	
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'enquiry_default'},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("supervisory_officer").innerHTML=html;			
		}
	});	
	
}

function load_ext_dist() {
	ef_off=$('#supervisory_officer').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_ext_dist',ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("ext_dist").innerHTML=html;			
		}
	});	
	
}

function load_ext_ps() {
	district=$('#ext_dist').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_police_station',district:district},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("ext_pol_stat").innerHTML=html;			
		}
	});	
	
}

function get_all_offices() {
	var office_level = $('#office_level').val();
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var dept_id = $('#dept_id').val();
	//Calling popup
	openForm("Get_all_officer_Form.php?open_form=P1&off_level_id="+off_level_id+"&off_level_dept_id="+off_level_dept_id+"&dept_off_level_office_id="+dept_off_level_office_id+"&dept_off_level_pattern_id="+dept_off_level_pattern_id+"&dept_id="+dept_id, "office_design_search");
}
function p1_returnDesignationSearchForEntry(off_level, off_loc_id, off_loc_name){
	$('#pet_off_id').val(off_loc_id);
	$('#off_d_id').val(off_loc_id);
	$('#pet_off_name').val(off_loc_name);
	populateSupervisoryOfficers();
}


function get_all_conc_offices() {
	var office_level = $('#conc_office_level').val();
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var dept_id = $('#dept_id').val();
	//Calling popup
	openForm("Get_all_conc_officer_Form.php?open_form=P1&off_level_id="+off_level_id+"&off_level_dept_id="+off_level_dept_id+"&dept_off_level_office_id="+dept_off_level_office_id+"&dept_off_level_pattern_id="+dept_off_level_pattern_id+"&dept_id="+dept_id, "office_design_search");
}


function p1_returnDesignationSearchForConcerned(off_level, off_loc_id, off_loc_name){
	//alert(off_loc_name)
	$('#conc_off_id').val(off_loc_id);
	//$('#off_d_id').val(off_loc_id);
	$('#conc_off_name').val(off_loc_name);
	//getOfficersForLocation(off_loc_id);
	//getOfficersForProcessing()
}

function getOfficersForLocation(off_loc_id) {
	//alert("off_loc_id::::"+off_loc_id)	
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	//var office_id = $('#office_id').val();
	var office_level = $('#conc_office_level').val(); 
	var office_loc_id = off_loc_id;
	$.ajax({
		type: "post",
		url: "pm_concerned_officers_action.php",
		cache: false,
		data: {source_frm : 'loadConcernedOfficers',office_level : office_level,office_loc_id : office_loc_id},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("concerned_officer").innerHTML=html;
		}
	});	
}

function populateSupervisoryOfficers() {
	document.getElementById("concerned_officer").options.length=1;
	var dept_off_level_pattern_id = $('#dept_off_level_pattern_id').val();
	var pattern_id = $('#dept_off_level_pattern_id').val();
	var office_level = $('#office_level').val(); 
	var office_loc_id = $('#office_loc_id').val();
	var dept_id = $('#dept_id').val();
	var pet_off_id = $('#pet_off_id').val();
	var disposing_officer = $('#disposing_officer').val();
	//alert("pet_off_id:::"+pet_off_id);
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	var pet_process = document.querySelector('input[name = "pet_process"]:checked').value;
	//alert("pet_process::::"+pet_process);
	//alert("off_level_id::::::"+off_level_id);
	if (office_loc_id != '') {
		$.ajax({
			type: "post",
			url: "pm_concerned_officers_action.php",
			cache: false,
			data: {source_frm : 'loadSupervisoryOfficers',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id,petition_office_loc_id:office_loc_id,pet_off_id:pet_off_id,disposing_officer:disposing_officer,pet_process:pet_process},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("supervisory_officer").innerHTML=html;
				if (document.getElementById("supervisory_officer").options.length == 1) {
					document.getElementById("supervisory_officer_present").value="N";
					populateConcernedOfficers();
				} else if (document.getElementById("supervisory_officer").options.length > 1) {
					document.getElementById("supervisory_officer_present").value="Y";
				}
			}
		});	
	}
}

function loadConcernedOfficeLevel() {
	var pattern_id = $('#dept_off_level_pattern_id').val();
	var office_level = $('#office_level').val(); 
	
	var arr = office_level.split("-");
	var off_level_id=arr[0];
	var off_level_dept_id=arr[1];
	var dept_off_level_office_id=arr[2];
	
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'concerned_office_level',off_level_id : off_level_id,pattern_id : pattern_id},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("conc_office_level").innerHTML=html;
		}
	});
	
}
function valchk() {
	document.getElementById("alrtmsg").innerHTML="";
	document.getElementById("alrtmsg").style.display='block';
	var validateFlg = false;
	$(".divTable input[type='text'] , .divTable select, .divTable textarea").each(function( index ) {
		$(this).removeClass('error');
		if($(this).attr('data_valid')!='no') {
			if($.trim($(this).val())=='') {
				$(this).focus().addClass('error');
				$("#alrtmsg").html($(this).attr('data-error'));
				$(this).focus();
				validateFlg = false;
				return false;			
			} else {		 
				$(this).removeClass('error');
				validateFlg = true;
			}
		}
	});
	if(validateFlg==true) { 
		griev_val=document.getElementById("grievance").value.length;
		if(griev_val < 3) {
			alert("Grievance cannot be less than 3 characters");
			document.getElementById("grievance").focus();		 
			validateFlg = false;
			return false;
		}
	}
	
	if(validateFlg==true) {
		/*if(getRadioValue("pet_process")=='F' && $("#concerned_officer").val()==""){
			alert("Please select the Concenrned Officer");
			validateFlg = false;
		} else {
			$("#concerned_officer").removeClass('error');
			validateFlg = true;
		}*/
	}
	/*
	if(validateFlg==true) { 
		var current_date = new Date();
		todayYear = current_date.getFullYear();
		todayMonth = current_date.getMonth();//In javascript getMonth() function begins with 0 so need to add plus one to 		compare with our date format.
		todayMonth += 1;
		todayDay = current_date.getDate();
		var cur_date=   todayYear.toString() +  todayMonth.toString()  +  todayDay.toString();
		var dob=$('#dob').val();
		arr_date = dob.split('/');
		date_birth=arr_date[2] + arr_date[1] + arr_date[0];
		cal_date = cur_date - date_birth;
		var birthday = '';
		if (dob != '') {
			birthday = moment(dob);
		}
		if ((birthday!='') && (!birthday.isValid())) {
			alert("Invalid Date");    
		}
	}
	*/
	if(validateFlg==true) {
		var comments = document.getElementById("sourcecomments").value;
		if (comments == 'N') {
			document.getElementById("source_remarks").value	 = "";
		}
	}
	var pet_process = document.querySelector('input[name = "pet_process"]:checked').value;
	document.getElementById("pet_process").value= pet_process;
	//alert(document.getElementById("pet_process").value);
	if(validateFlg==true) {
		var sup_length = document.getElementById("supervisory_officer").options.length;
		var sup = document.getElementById("supervisory_officer").value;
		var conc_length = document.getElementById("concerned_officer").options.length;
		var conc = document.getElementById("concerned_officer").value;
		var supervisory_present=document.getElementById("supervisory_officer_present").value;
		if (sup == '' && conc == '') {
			if (pet_process == 'F') {
				msg = "Enquiry Filing and Enquiry Officers are not selected, hence, the petition will have to be attended by the Initiating Officer in the 'To Process / Temporary Reply' Inbox. Do you want to continue?";
				reply = confirm(msg);
				if (reply == false) {
					return false;
				}
			} else if (pet_process == 'D') {
				//msg = "Only Initiating Officer is selected, hence the petition cannot be delegated";
				alert("Only Initiating Officer is selected, hence the petition cannot be delegated");
				return false;
			}
						
		} else if (sup == '' && supervisory_present == 'N') {
			msg = "If Enquiry Filing Officer is not selected, Initiating Officer will be the Enquiry Filing Officer also. Do you want to continue?";
			reply = confirm(msg);
			if (reply == false) {
				return false;
			}
		} else if (sup == '' && conc != '') {
			if (pet_process == 'F') {
				msg = "If Enquiry Filing Officer is not selected, Initiating Officer will be the Enquiry Filing Officer also. Do you want to continue?";
				reply = confirm(msg);
				if (reply == false) {
					return false;
				}
			} else if (pet_process == 'D') {
				msg = "Enquiry Filing Officer is not selected. Do you want to continue?";
				reply = confirm(msg);
				if (reply == false) {
					return false;
				}
			}			
		}
		if (pet_process == 'D') {
			if($("#supervisory_officer").val()==186){
				alert("Delegate Petition to ADGP not possible.");
				$("#supervisory_officer").val('');
				$('#optgroup_DGP').hide();
				return false;
			}
		}
		
	$pet_ext_link=$("#pet_ext_link").val();
	$ext_year=$("#ext_year").val();
	$ext_no=$("#ext_no").val();
	if($pet_ext_link!=''){
		if($ext_year==''){
			alert('Enter FIR/CSR Year');
			return false;
		}
		if($ext_no==''){
			alert('Enter FIR/CSR number');
			return false;
		}
		$ext_dist=$("#ext_dist").val();
		if($ext_dist==''){
			alert('Select FIR/CSR district');
			return false;
		}
		$ext_pol_stat=$("#ext_pol_stat").val();
		if($ext_pol_stat==''){
			alert('Select FIR/CSR Police Station');
			return false;
		}
	}
		document.getElementById("save").disabled= true;;
		document.getElementById("hid").value='done';
		document.petiton_detail_entry.method="post";
		document.petiton_detail_entry.target= "_blank";
		document.petiton_detail_entry.action = "pm_petition_detail_entry.php";
		document.petiton_detail_entry.submit();
		$('#disposing_officer').val('');
		setTimeout(function(){window.open('pm_petition_detail_entry.php', '_self',false);}, 500);
		return true;	
	}
}
function filesizevalidation() {
	var selectedFiles = document.getElementById("files");
	var totalfilesize = 0;
	if(selectedFiles)	{
		if (selectedFiles.files.length == 0) {
			alert("Select one file to upload.");
		} else {
			if (selectedFiles.files.length > 1) {
				alert("Only one PDF or JPEG file is allowed to upload, Combine them into a single file");
				document.getElementById('files').value=null;
				document.getElementById('files').focus();	
				return false;
			} else {
				var totalfilesize = selectedFiles.files[0].size;
			}
	
			if(totalfilesize >= 1572864)//Bytes value for 1.5mb = 1572864
			{
				alert('File size exceeds the limit. It should be not greater than 1.5mb');
				document.getElementById('files').value=null;
				document.getElementById('files').focus();	
				return false;
			}
			else{
				return true;	
			}

		}
	}
}
function validateFileExtension(fld,fn) {
	if(fld!="")
	{
		if(fld == 'pdf' || fld == 'jpeg' || fld =='jpg' ) {
			return true;
		}else{
			alert("Invalid file type of "+fn +".");
			document.getElementById('files').value=null;
			document.getElementById('files').focus();	
			return false;

		}
	}
}
//File upload validation 
function filetypevalidation(){
	var selectedFiles = document.getElementById("files");
	if(selectedFiles){
		if (selectedFiles.files.length == 0) {
			alert("Select one file to upload.");
			return false;
		} else {
			for(var i =0; i<selectedFiles.files.length; i++) {
				var filename = selectedFiles.files[i].name;
				var fileSplit = filename.split('.');
				var fileExt = '';
				if (fileSplit.length > 2) {
					alert ('Filename not correct');
					document.getElementById('files').value=null;
					document.getElementById('files').focus();	
					return false;
				} else {
					var ext=filename.substring(filename.lastIndexOf('.')+1);
					validateFileExtension(ext,filename);
				}				
			}	
		}
	}
}
function checkForRemarks() {
	var griev_subcode = document.getElementById("griev_subcode").value;
	if (griev_subcode == 210) {
		document.getElementById("griev_subtype_remarks_row").style.display = "";
	} else {
		document.getElementById("griev_subtype_remarks_row").style.display = "none";
		document.getElementById("griev_subtype_remarks").value = "";
	}
}

function get_taluk() {	
	dist=document.getElementById("comm_dist").value;	
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'taluk',distid : dist},
		error:function(){ alert("taluk: some error occurred") },
		success: function(html){
			document.getElementById("div_comm_taluk").innerHTML=html;			
		}		
	}); 	 
	get_village();
}
function get_village() { 
	taluk=$('#comm_taluk').val();
	dist=$('#comm_dist').val();
	if(taluk==0){
		document.getElementById("comm_rev_village").options.length = 1;
		return false;
	} 
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'village',talukid : taluk,distid : dist},
		error:function(){ alert("village: some error occurred") },
		success: function(html){
			document.getElementById("div_comm_village").innerHTML=html;
		}
	}); 
}

function chkForExistingPetitions() {
	var mobile_number = document.getElementById("mobile_number").value;
	if (mobile_number != '') {
		$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_pet_for_mobile"+"&mobile_number="+mobile_number,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			count = $(xml).find('count').eq(0).text();
			if (count > 0) {
				//alert("Already petition exists");
				opener=window.open("Get_all_related_petitions_Form.php?open_form=P1&mobile_number="+mobile_number, "p1_petition_search","_blank","fullscreen=yes");
			}else{
				alert('No Petition found for this number');
			}
					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
	}
}
//p1_returnPetionorPersonalDetails
function p1_returnPetionorPersonalDetails(mob_no) {
	//alert ("Hello");
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_petitioner_details"+"&mobile_number="+mob_no,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
			
		},
		success: function(xml){			//alert();
		$('#pet_eng_initial').val('');
			$('#old_pet_no').val('');
				document.getElementById('linked_pet').innerHTML="";
			$('#pet_ename').val('');
			$('#father_ename').val('');
			$('#gender').val('');
			$('#comm_doorno').val('');
			$('#comm_street').val('');
			$('#comm_area').val('');
			$('#comm_pincode').val('');
			$('#idtype_id').val('');
			$('#idtype_no').val('');
			$('#email').val('');
			$('#grievance').val('');
			$('#pet_type').val('');
			$('#griev_maincode').val('');
			$('#griev_subcode').val('');
			$('#pet_eng_initial').val( $(xml).find('petitioner_initial').eq(0).text());		
			$('#pet_ename').val( $(xml).find('petitioner_name').eq(0).text());		
			$('#father_ename').val( $(xml).find('father_husband_name').eq(0).text());		
			$('#gender').val( $(xml).find('gender_id').eq(0).text());		
			$('#comm_doorno').val( $(xml).find('comm_doorno').eq(0).text());		
			$('#comm_street').val( $(xml).find('comm_street').eq(0).text());		
			$('#comm_area').val( $(xml).find('comm_area').eq(0).text());		
			$('#comm_pincode').val( $(xml).find('comm_pincode').eq(0).text());		

			$('#idtype_id').val( $(xml).find('idtype_id').eq(0).text());
			$('#idtype_no').val( $(xml).find('id_no').eq(0).text());
			$('#email').val( $(xml).find('comm_email').eq(0).text());
			$('#pet_community').val( $(xml).find('petitioner_category_id').eq(0).text());
			$('#petitioner_category').val( $(xml).find('pet_community_id').eq(0).text());
			if($("#off_level_id").val()!=46){
			$('#disposing_officer').val('');
			}
			/* $('#grievance').val( $(xml).find('grievance').eq(0).text());
			$('#pet_type').val($(xml).find('pet_type_id').eq(0).text());
			$('#griev_maincode').val($(xml).find('griev_type_id').eq(0).text());*/
			
			//alert($(xml).find('pet_no').eq(0).text());
			//alert($(xml).find('comm_dist').eq(0).text());
			if($(xml).find('comm_dist').eq(0).text()!=''){
			$('#comm_dist').val( $(xml).find('comm_dist').eq(0).text());
			
			comm_dist = $(xml).find('comm_dist').eq(0).text();
			comm_taluk_id = $(xml).find('comm_taluk_id').eq(0).text();
			populateTaluk(comm_dist,comm_taluk_id);			
			comm_rev_village_id = $(xml).find('comm_rev_village_id').eq(0).text();
			populateRevVillage(comm_dist,comm_taluk_id,comm_rev_village_id);
			}
		load_ef_officer();
/* 			polulateSubcategory($(xml).find('griev_type_id').eq(0).text(),$(xml).find('griev_subtype_id').eq(0).text());
 */					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
}
function p1_returnPetionDetails(petition_id,testsource = "test"){
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_petition_details"+"&petition_id="+petition_id,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
			
		},
		success: function(xml){		
			$('#pet_eng_initial').val('');
			$('#pet_ename').val('');
			$('#father_ename').val('');
			$('#gender').val('');
			$('#comm_doorno').val('');
			$('#comm_street').val('');
			$('#comm_area').val('');
			$('#comm_pincode').val('');
			$('#idtype_id').val('');
			$('#idtype_no').val('');
			$('#email').val('');
			$('#grievance').val('');
			$('#griev_maincode').val('');
			$('#griev_subcode').val('');
			$('#pet_eng_initial').val( $(xml).find('petitioner_initial').eq(0).text());		
			$('#pet_ename').val( $(xml).find('petitioner_name').eq(0).text());		
			$('#father_ename').val( $(xml).find('father_husband_name').eq(0).text());		
			$('#gender').val( $(xml).find('gender_id').eq(0).text());		
			$('#comm_doorno').val( $(xml).find('comm_doorno').eq(0).text());		
			$('#comm_street').val( $(xml).find('comm_street').eq(0).text());	$('#griev_maincode').val($(xml).find('griev_type_id').eq(0).text());	
			$('#comm_area').val( $(xml).find('comm_area').eq(0).text());		
			$('#comm_pincode').val( $(xml).find('comm_pincode').eq(0).text());		
			if($(xml).find('comm_dist').eq(0).text()!=''){
			$('#comm_dist').val( $(xml).find('comm_dist').eq(0).text());
			comm_dist = $(xml).find('comm_dist').eq(0).text();
			comm_taluk_id = $(xml).find('comm_taluk_id').eq(0).text();
			populateTaluk(comm_dist,comm_taluk_id);			
			comm_rev_village_id = $(xml).find('comm_rev_village_id').eq(0).text();
			populateRevVillage(comm_dist,comm_taluk_id,comm_rev_village_id);
			
			}polulateSubcategory($(xml).find('griev_type_id').eq(0).text(),$(xml).find('griev_subtype_id').eq(0).text());
			$('#idtype_id').val( $(xml).find('idtype_id').eq(0).text());
			$('#idtype_no').val( $(xml).find('id_no').eq(0).text());
			$('#email').val( $(xml).find('comm_email').eq(0).text());
			if($("#off_level_id").val()!=46){
			$('#disposing_officer').val('');
			}
			//$('#pet_community').val( $(xml).find('petitioner_category_id').eq(0).text());
			//$('#petitioner_category').val( $(xml).find('pet_community_id').eq(0).text());
			$('#grievance').val( $(xml).find('grievance').eq(0).text());
			$('#pet_type').val($(xml).find('pet_type_id').eq(0).text());
			
			if(testsource=='petitioner'){ 
				$('#old_pet_no').val('True');
		document.getElementById('linked_pet').innerHTML="Linking Petition no.: "+$(xml).find('pet_no').eq(0).text();
			}else{
				$('#old_pet_no').val('');
				document.getElementById('linked_pet').innerHTML="";
			}
		if($('#old_pet_no').val()=='True'){	$('#old_pet_no').val($(xml).find('pet_no').eq(0).text());
		}														 
			//$('#old_pet_no').val($(xml).find('pet_no').eq(0).text());
			//alert($(xml).find('pet_no').eq(0).text());
			if($("#off_level_id").val()!=46){
			if($('#office_pattern').css("display") === "none"){}else{
				$('#office_level').val('');
		$('#office_loc_id').val('');
		$('#office_level').empty();
		$('#office_loc_id').empty();
		$('#dept_off_level_pattern_id').val('');
		loadOfficeLevel();
		document.getElementById('office_pattern').style.display='none';
		document.getElementById('office_pattern_icon').value='+';
		load_ef_officer();
			}
			}
			if($("#off_level_id").val()==46){
		//showhid();
		loadOfficeLocations();
		setDisable("#office_pattern_icon", true);
		setDisable("#dept_off_level_pattern_id", true);
		$("#dept_off_level_pattern_id").css('background-color','#fff');
		setDisable("#office_level", true);
		$("#office_level").css('background-color','#fff');;
		setDisable("#office_loc_id", true);
		$("#office_loc_id").css('background-color','#fff');
		load_ext_dist();
		}
					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
}
function polulateSubcategory(griev_type_id,griev_subtype_id) {
	$.ajax({
			type: "post",
			url: "pm_petition_detail_entry_action.php",
			cache: false,
			data: {source_frm : 'griev_sub_category',griev_main_code : griev_type_id,griev_subtype_id:griev_subtype_id},
			error:function(){ alert("griev_subcategory: some error occurred") },
			success: function(html){
				document.getElementById("div_sub_category").innerHTML=html;
			}
		} ); 
}
function populateTaluk(comm_dist,comm_taluk_id) {
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'taluk_for_pet_id',distid : comm_dist,comm_taluk_id:comm_taluk_id},
		error:function(){ alert("taluk: some error occurred") },
		success: function(html){
			document.getElementById("div_comm_taluk").innerHTML=html;			
		}	
	});
}
function populateRevVillage(comm_dist,comm_taluk_id,comm_rev_village_id){
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'vill_for_pet_id',talukid : comm_taluk_id,distid : comm_dist,comm_rev_village_id:comm_rev_village_id},
		error:function(){ alert("village: some error occurred") },
		success: function(html){
			document.getElementById("div_comm_village").innerHTML=html;
		}
	}); 
}

function showhid(){
	if($('#disposing_officer').val()!=''){
	if($('#office_pattern').css("display") === "none"){
		//alert();
		document.getElementById('office_pattern').style.display='';
		document.getElementById('office_pattern_icon').value='-';
	}else{
		//alert("==================================");
		var opt_length = document.getElementById("dept_off_level_pattern_id").options.length;
		//alert("opt_length:::"+opt_length)
		$('#office_level').val('');
		$('#office_loc_id').val('');
		$('#office_level').empty();
		$('#office_loc_id').empty();
		if (opt_length > 1) {
			$('#dept_off_level_pattern_id').val('');
		}
		
		loadOfficeLevel();
		document.getElementById('office_pattern').style.display='none';
		document.getElementById('office_pattern_icon').value='+';
		load_ef_officer();
		
	}
	}else{
		alert('Select Intitiating Officer before selecting Petition Office');
	}
	//if($('#office_pattern').style.display())
}

function load_ef_officer(){
	var pet_process = document.querySelector('input[name = "pet_process"]:checked').value;
	/* if($('#office_pattern').css("display") === "none"){
		
	}else{ 
		var opt_length = document.getElementById("dept_off_level_pattern_id").options.length;
		//alert("opt_length:::"+opt_length)
		//$('#office_level').val('');
		//$('#office_loc_id').val('');
		//$('#office_level').empty();
		//$('#office_loc_id').empty();
		$('#concerned_officer').empty();
		$("#supervisory_officer").append('<option value="" selected>--Select Enquiry Filing Officer--');
		$("#concerned_officer").append('<option value="" selected>--Select Enquiry Officer--');
		if (opt_length > 1) {
			$('#dept_off_level_pattern_id').val('');
		}
		
		loadOfficeLevel();*/
		//document.getElementById('office_pattern').style.display='none';
		//document.getElementById('office_pattern_icon').value='+';
		//load_ef_officer();
	//}
	var opt_length = document.getElementById("supervisory_officer").options.length;
	//alert("opt_length"+opt_length);
	if(opt_length==1){
		init_off=$("#disposing_officer").val();
		//alert(init_off);
		if(init_off!=''){
	 $.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'enquiry_officer',init_off:init_off,pet_process:pet_process},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("supervisory_officer").innerHTML=html;			
		}
	});	 
	}
	}
}

function load_ef_for_officer(){
	var pet_process = document.querySelector('input[name = "pet_process"]:checked').value;
	offlev=$('#office_level').val();
	offloc=$('#office_loc_id').val();
	if(offlev!='' && offloc!=''){
		populateSupervisoryOfficers();
		return false;
	}
	var opt_length = document.getElementById("supervisory_officer").options.length;
	//alert("opt_length"+opt_length);
	if(opt_length==1){
		init_off=$("#disposing_officer").val();
		//alert(init_off);
		if(init_off!=''){
	 $.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'enquiry_officer',init_off:init_off,pet_process:pet_process},
		error:function(){ alert("Enter Office Level") },
		success: function(html){
			document.getElementById("supervisory_officer").innerHTML=html;			
		}
	});	 
	}
	}
}


function escapeRegExp(string){

    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

}

function replaceAll(str, term, replacement) {
  var new_str = str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
  
  if (new_str.endsWith("**")) {
	  new_str = new_str.substring(0, new_str.length - 2);
  }
  return new_str;

}

function replaceString(keywords) {
	return replaceAll(keywords, ' ', '**');
}

function highlight_param(search){
	$('*').removeClass('error');
	if(search=='applicant'){
	$('#pet_ename').addClass('high_param');
	$('#father_ename').addClass('high_param');
	//$('#comm_street').addClass('high_param');
	$('#comm_area').addClass('high_param');
	$('#comm_pincode').addClass('high_param');
	//$('#gender').addClass('high_param');
	}else if(search=='subject'){
	//$('#comm_street').addClass('high_param');
	$('#comm_area').addClass('high_param');
	$('#comm_pincode').addClass('high_param');
	$('#griev_maincode').addClass('high_param');
	$('#griev_subcode').addClass('high_param');
	$('#grievance').addClass('high_param');
	}else if(search=='appl_sub'){
	$('#pet_ename').addClass('high_param');
	$('#father_ename').addClass('high_param');
	//$('#gender').addClass('high_param');
	//$('#comm_street').addClass('high_param');
	$('#comm_area').addClass('high_param');
	$('#comm_pincode').addClass('high_param');
	$('#griev_maincode').addClass('high_param');
	$('#griev_subcode').addClass('high_param');
	$('#grievance').addClass('high_param');
	}
}
function remove_highlight(search){
	$('*').removeClass('error');
	if(search=='applicant'){
	$('#pet_ename').removeClass('high_param');
	$('#father_ename').removeClass('high_param');
	$('#comm_area').removeClass('high_param');
	$('#comm_pincode').removeClass('high_param');
	//$('#comm_street').removeClass('high_param');
	//$('#gender').removeClass('high_param');
	}else if(search=='subject'){
	//$('#comm_street').removeClass('high_param');
	$('#comm_area').removeClass('high_param');
	$('#comm_pincode').removeClass('high_param');
	$('#griev_maincode').removeClass('high_param');
	$('#griev_subcode').removeClass('high_param');
	$('#grievance').removeClass('high_param');
	}else if(search=='appl_sub'){
	$('#pet_ename').removeClass('high_param');
	$('#father_ename').removeClass('high_param');
	//$('#gender').removeClass('high_param');
	//$('#comm_street').removeClass('high_param');
	$('#comm_area').removeClass('high_param');
	$('#comm_pincode').removeClass('high_param');
	$('#griev_maincode').removeClass('high_param');
	$('#griev_subcode').removeClass('high_param');
	$('#grievance').removeClass('high_param');
	}
}

function load_pet_search(){
	param_count=0;	
	var name = document.getElementById("pet_ename").value;
	if(name!=''){param_count+=1;}
	var father = document.getElementById("father_ename").value;
	if(father!=''){param_count+=1;}
	var area = document.getElementById("comm_area").value;
	if(area!=''){param_count+=1;}
	var pin = document.getElementById("comm_pincode").value;
	if(pin!=''){param_count+=1;}
	/*var street = document.getElementById("comm_street").value;
	if(street!=''){param_count+=1;}
	var gender = document.getElementById("gender").value;
	if(gender!=''){param_count+=1;}*/
	if(param_count>=2){
opener=window.open	("Get_pm_petitioner_search.php?open_form=P1&name="+name+"&father="+father+"&area="+area+"&pin="+pin,"p1_petition_search","_blank","fullscreen=yes");
	}else{
		alert("Please Enter Two or more parameters that are highlighted.");
	}
}



function removeFromString(str){
  array=
[
'i','my','me','mine','we','our','us','ours','you','your','yours','he','his','him','she','her','hers','it','its','they','their','them','theirs','myself','ourselves','yourself','yourselves','himself','herself','itself','themselves','another','this','these','that','those','such','all','first','last','be','being','been','am','is','are','was','were','shall','will','have','has','had','having','should','would','can','could','may','might','must','ought','there','here','a','an','the','every','each','very','many','much','more','than','most','even','quite','little','less','least','bit','just','only','almost','also','any','at','in','into','from','to','for','by','of','off','on','onto','with','down','up','up to','about','above','over','across','around','through','along','below','under','beside','besides','like','out','since','during','and','but','or','nor','yet','so','after','although','though','as','because','before','if','lest','till','unless','until','whereas','whether','while','who','whose','whom','which','what','how','when','where','why','both','either','neither','rather','nevertheless','therefore','hence','thus','besides','moreover','furthermore','otherwise','consequently','some','few','else','no','not','yes','respected','sir','madam','sir/madam','god','office','application','minister','mr','mrs','good','small','big','related','apply','take','make','action','dated','please','item','humbly','then','already','now','then','purpose','amount',
'நான்','எனக்கு','எனது','நாங்கள்','நாம்','அது','அதை','இது','இதை','யார்','யாருடைய','யாரை','யாருடையது','அங்கே','இங்கே','எங்கே','அப்படி','இப்படி','எப்படி','ஏன்','ஏனென்றால்','என','ஒரு','ஓர்','சில','பல','அதிக','நிறைய','குறைய','அநேக','இன்னும்','மீண்டும்','கூட','விட','கொஞ்சம்','சிறிது','கிட்டத்தட்ட','மேலும்','மேலே','மீது','கீழ்','கீழே','வழியாக','உள்ளே','வெளியே','ஆனால்','அல்லது','எனவே','பிறகு','முன்பு','இருந்தாலும்','ஏறக்குறைய','ஏற்கனவே','அதுவரை','இல்லையெனில்','இல்லையென்றால்','அதே','அதேபோல்','அதேவேளையில்','அதனால்','இருந்தபோதிலும்','இவ்வாறு','இவ்வாறான','இவ்வாறாக','விளைவாக','ஐயா','ஐயோ','கடவுளே','அலுவலகம்','அலுவலர்','அமைச்சர்','தொடர்ந்து','ஆம்', 'இல்லை','திரு','திருமதி','மற்றும்','குறித்து','மட்டும்','போன்று',
'want', 'need', 'bad', 'near', 'mention', 'attend', 'inform', 'furnish', 'detail', 'reg', 'req', 'request', 'complain', 'document', 'report', 'letter', 'order', 'arrange', 'problem', 'issue', 'necessary', 'condition', 'petition', 'other' 
, 'என்', 'எங்க', 'நம்ம', 'அவ', 'அவைக', 'வேண்', 'கோரி' , 'கோாி', 'கோரு' , 'வழங்க' , 'கொடு', 'எடுக்க' , 'கூறி' , 'கூறு', 'நடவடிக்கை', 'கேட்', 'உத்திர' , 'உதவு', 'தந்து', 'பெற' , 'செய்', 'சம்பந்த', 'புகார்' , 'புகாா்', 'உள்ள', 'தொடர்பாக', 'தொடா்பாக', 'மனு', 'விண்ண', 'நல்ல', 'கெட்ட', 'சின்ன', 'சிறிய', 'பெரிய', 'அத', 'இத', 'அகற்'];
strq=str.toLowerCase().split(' ');
let difference = strq.filter(x => !array.includes(x));
str=difference.join(' ');
return str;
}

function load_sub_loc_search(){
	param_count=0;
	var area = document.getElementById("comm_area").value;
	if(area!=''){param_count+=1;}
	var pin = document.getElementById("comm_pincode").value;
	if(pin!=''){param_count+=1;}
	//var street = document.getElementById("comm_street").value;
	//if(street!=''){param_count+=1;}
	var main = document.getElementById("griev_maincode").value;
	if(main!=''){param_count+=1;}
	try{var sub = document.getElementById("griev_subcode").value;}catch(e){}
	if(sub!=''){param_count+=1;}
	var grievance =removeFromString($('#grievance').val());
	grievance =replaceString(grievance.replace(/  +/g, ' '));
	if(grievance!=''){param_count+=1;}
	if(param_count>=3){
		opener=window.open("Get_pm_sub_loc_search.php?open_form=P1&area="+area+"&pin="+pin+"&main="+main+"&sub="+sub+"&grievance="+grievance,"p1_petition_search","_blank","fullscreen=yes");
	}else{
		alert("Please Enter Three or more parameters that are highlighted.");
	}
}

function load_pet_loc_search(){
	param_count=0;
	var name = document.getElementById("pet_ename").value;
	if(name!=''){param_count+=1;}
	var father = document.getElementById("father_ename").value;
	if(father!=''){param_count+=1;}
	//var gender = document.getElementById("gender").value;
	//if(gender!=''){param_count+=1;}
	var area = document.getElementById("comm_area").value;
	if(area!=''){param_count+=1;}
	var pin = document.getElementById("comm_pincode").value;
	if(pin!=''){param_count+=1;}
	//var street = document.getElementById("comm_street").value;
	//if(street!=''){param_count+=1;}
	var main = document.getElementById("griev_maincode").value;
	if(main!=''){param_count+=1;}
	try{var sub = document.getElementById("griev_subcode").value;}catch(e){}
	if(sub!=''){param_count+=1;}
	var grievance =removeFromString($('#grievance').val());
	grievance1 =replaceString(grievance.replace(/  +/g, ' '));
	if(grievance1!=''){param_count+=1;}
	if(param_count>=3){
	opener=window.open("Get_pm_sub_pet_search.php?open_form=P1&name="+name+"&area="+area+"&father="+father+"&pin="+pin+"&main="+main+"&sub="+sub+"&grievance="+grievance1,"p1_petition_search","_blank","fullscreen=yes");
	}else{
		alert("Please Enter Three or more parameters that are highlighted.");
	}
}   
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 

<div id="div_head">
<?php 
?>

<?PHP
 if($userProfile->getOff_level_id()==7 || $userProfile->getOff_level_id()==9 || $userProfile->getOff_level_id()==11 || $userProfile->getOff_level_id()==13 || $userProfile->getOff_level_id()==42 || $userProfile->getOff_level_id()==46){
	$flag=true;//Primary user roles
}
if(!$flag){
	header('HTTP/1.0 401 Unauthorized');
	include("com/access_denied.php");
	die();
}
?>
</div>
<?php
	$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
	
	$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
	$result = $db->query($query);
	
	while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
		if($_SESSION['lang']=='E'){
			$label_name[] = $rowArr['label_name'];	
		} else {
			$label_name[] = $rowArr['label_tname'];
		}
	}
	
	if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		if ($userProfile->getOff_level_id()==7) {
		$codn_cc=' and dept_user_id=(select dept_user_id from usr_dept_users where dept_desig_id=(select sup_dept_desig_id from usr_dept_desig where dept_desig_id=(select dept_desig_id from usr_dept_users where dept_user_id='.$_SESSION["USER_ID_PK"].')))';
		}
		
		$sql="select a.dept_user_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_cc." ";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
			$dept_user_id =  $_SESSION['USER_ID_PK'];
		} 
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
?>
<?php 
if($_POST['hid']=="") { ?>
<div id="div_content" class="divTable">
	<div class="form_heading"><div class="heading"><?PHP echo $label_name[0]; //Petition Details Entry ?><?php echo $dis_name;?>
	
	</div></div>
	<div class="contentMainDiv">
	<div class="contentDiv" align="center" style="background-color:#bc7676">
		<form name="petiton_detail_entry" id="petiton_detail_entry" enctype="multipart/form-data" method="post" action="">
        	<input type="hidden" name="off_level_id" id="off_level_id" value="<?PHP echo $userProfile->getOff_level_id(); ?>"/>
        	<input type="hidden" name="sup_id" id="sup_id" value="<?PHP echo $userProfile->getS_Dept_desig_id(); ?>"/>
			<input type="hidden" name="userId" id="userId" value="<?PHP echo $_SESSION['USER_ID_PK']; ?>"/>
   			<table class="formTbl" style="width:90%;" border="1" cellspacing="0" cellpadding="0">
			<tbody>
			<!--
			<tr><td colspan="6">
			<span class="star"  style="float:left;"> * <?PHP //echo $label_name[51]; //Mandatory Data?></span>
			<span class="bluestar"  style="float:left;"> &nbsp;&nbsp;* <?PHP echo $label_name[68] //Mandatory if required for the petition?></span>
			<span class="star"  style="float:right;"> * <?PHP //echo $label_name[63]; //Indicates Mandatory?></span>
			</td></tr>
			-->
			<tr><td colspan="6" class="heading"><?PHP echo $label_name[3]; //ApplicantDetails ?></td></tr>
	<tr>
	<td style="width:15%"><span class="star"><b><?PHP echo $label_name[22]; //Mobile Number ?></b> *</span> </td>
	<td style="width:15%"><input type="text" name="mobile_number" id="mobile_number" value="" maxlength="13" class="select_style" data_valid='yes' onchange="mob_chk();" onKeyPress="return numbersonly_ph(event);" data-error="please enter mobile number" style="width:120px"/>
	<input type="button" name="search" id="search" value="<?PHP echo 'Search'; //Save?>" onClick="return chkForExistingPetitions();"/>
	
	</td>	
	
	<td style="width:15%"><?PHP echo $label_name[24].' & Number'; //ID Proof Type& Number ?><span class="star"></span></td> 
	<td style="width:15%">
	<select name="idtype_id" id="idtype_id" data_valid='no'  data-error="Please select ID Type" class="select_style"
	style="width:130px;margin-right:5px;"> 
	<option value="">--Select--</option>
	<?php
		$sql="SELECT idtype_id, idtype_name, idtype_tname FROM lkp_id_type order by idtype_id";
		$rs=$db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_BOTH))
		{
			$idtype_id=$row["idtype_id"];
			$idtype_name=$row["idtype_name"];
			$idtype_tname = $row["idtype_tname"];
			if($_SESSION["lang"]=='E')
			{
				$idtype_name = $idtype_name;
			}else{
				$idtype_name = $idtype_tname;	
			}
			print("<option value='".$idtype_id."'>".$idtype_name."</option>");
		}
	?>
	&nbsp;&nbsp;&nbsp;<input type="text" name="idtype_no" id="idtype_no" value=""  data_valid='no' onBlur="" class="select_style" style="width:130px" data-error="Please select ID Number"/>
	<input type="hidden" name="canid" id="canid" value=""  data_valid='no' onBlur="test();" class="select_style" onKeyPress="return numbersonly(event);" style="width:135px"/></td>
	 
	<td style="width:15%"><?PHP echo $label_name[26]; //Email ?></td>
	<td><input type="text" name="email" id="email" value="" class="select_style" onKeyPress="return chk_email(event);" data_valid='no' maxlength="30" data-error="Please enter email-Id" style="width:250px"/></td></tr>
			 
	<tr><td style="width:15%"><?PHP echo $label_name[18]; //Initial &amp; Name ?><span class="star">*</span></td>
	<td style="width:15%">
	<input type="text" name="pet_eng_initial" id="pet_eng_initial" value="" size="3" maxlength="15" onchange="avoid_Special('pet_eng_initial');" style="width:30px;" onKeyPress="return charactersonly(event);" data_valid='no'  data-error="Please enter initial" />&nbsp;
	<input type="text" name="pet_ename" id="pet_ename" value="" onchange="avoid_Special('pet_ename');" onKeyPress="return charactersonly(event);" data_valid='yes' style="width:225px !important;" maxlength="150" data-error="Please enter Name"/>
	</td>  
	<td style="width:15%"><?PHP echo $label_name[19]; //Father / Spouse Name ?><span class="blue_star">*</span></td>
	<td style="width:15%"><input type="text" name="father_ename" id="father_ename" value="" class="select_style" onchange="avoid_Special('father_ename');" onKeyPress="return charactersonly(event);" data_valid='no' maxlength="150" data-error="Please enter father/spouse name" style="width:225px" /></td>

	<?php
	$gen_sql = "select gender_id,gender_name,gender_tname from lkp_gender order by gender_id";
	$gen_rs=$db->query($gen_sql);
	if(!$gen_rs)
	{
	print_r($db->errorInfo());
	exit;
	}		
	?>
	<td style="width:15%"><?PHP echo $label_name[20]; //Gender ?><span class="blue_star">*</span></td> 
	<td><span id="div_comm_gender"> 
	<select name="gender" id="gender" data_valid='no' data-error="Please select gender" class="select_style" style="width:135px;">
	<option value="">--Select--</option>
	<?php  
		while($gen_row = $gen_rs->fetch(PDO::FETCH_BOTH))
		{
			$genname=$gen_row["gender_name"];
			$gentname=$gen_row["gender_tname"];
			if($_SESSION["lang"]=='E')
			{
				$gen_name=$genname;
			}else{
				$gen_name=$gentname;
			}
			print("<option value='".$gen_row["gender_id"]."' >".$gen_name."</option>");
		}
	?>
	</select>
	</span>
	<input type="hidden" id="gender_id_hid" name="gender_id_hid" value="" data_valid='no' /></td></tr>
			
	<tr style='display:none;'><td style="width:15%"><?PHP echo $label_name[66]; //Petitioner Community ?> </td>
	<td style="width:15%">
	<select name="pet_community" id="pet_community" data_valid='no' class="select_style">
	<option value="">--Select--</option>	
	<?php
	
		$community_sql = "SELECT pet_community_id, pet_community_name, pet_community_tname FROM lkp_pet_community order by pet_community_id";
		$community_rs=$db->query($community_sql);
		while($community_row = $community_rs->fetch(PDO::FETCH_BOTH))
		{

			if($_SESSION["lang"]=='E')
			{
				$pet_community_name=$community_row["pet_community_name"];
			}else{
				$pet_community_name=$community_row["pet_community_tname"];
			}
			print("<option value='".$community_row["pet_community_id"]."' >".$pet_community_name."</option>");

		}		
	?>
	</select>
	</td>
	<td style="width:15%"><?PHP echo $label_name[67]; //Petitioner Special Category ?></td>
	<td style="width:15%" colspan="3">
	<select name="petitioner_category" id="petitioner_category" data_valid='no' class="select_style">
	<option value="">--Select--</option>	
	<?php
		$petitioner_category_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category order by petitioner_category_id";
		$petitioner_category_rs=$db->query($petitioner_category_sql);
		while($petitioner_category_row = $petitioner_category_rs->fetch(PDO::FETCH_BOTH))
		{

			if($_SESSION["lang"]=='E')
			{
				$petitioner_category_name=$petitioner_category_row["petitioner_category_name"];
			}else{
				$petitioner_category_name=$petitioner_category_row["petitioner_category_tname"];
			}
			print("<option value='".$petitioner_category_row["petitioner_category_id"]."' >".$petitioner_category_name."</option>");

		}
		
	?>
	</select>
	</td></tr>
	
	<tr><td style="width:15%"><span style="float:left;"><b><?PHP echo $label_name[4]; //Communication Address ?></b></span><?PHP echo $label_name[27].' &'.$label_name[29]; //Door Number ?><span class="star">*</span></td>
	<td style="width:15%"><input type="text" name="comm_doorno" id="comm_doorno" value="" class="select_style" maxlength="15" onchange="avoid_Special_doorno('comm_doorno');" onKeyPress="return characters_numsonly(event);" data_valid='yes' data-error="Please enter door number" 
	style="width:30px"/>
	<input type="text" name="comm_street" id="comm_street" value="" class="select_style" onchange="avoid_Special('comm_street');" onKeyPress="return characters_numsonly(event);" maxlength="150" data_valid='yes' data-error="Please enter street name"
	style="width:225px"/>
	</td>	
	
	<td style="text-align:right;width:15%"><?PHP echo $label_name[30]; //Area / Ward / Location ?>
	<span class="star">*</span> 
	</td>
	<td style="text-align:left;"><input type="text" name="comm_area" id="comm_area" value="" onchange="avoid_Special('comm_area');" class="select_style" maxlength="150" onKeyPress="return characters_numsonly(event);" data_valid='yes'  data-error="Please enter Place/ Hamlet/ Ward"
	style="width:250px"/></td>
	<td style="text-align:right;width:15%"><?PHP echo $label_name[34]; //Pincode ?>
	<span class="star">*</span> 
	</td><td colspan="5">
	<input type="text" name="comm_pincode" id="comm_pincode" value="" maxlength="6" class="select_style" onKeyPress="return numbersonly(event);" onchange="pin_chk();" data_valid='yes' data-error="Please enter Pincode"/></td>
	</tr>
	<?php
		$dist_sql = 'select district_id,district_name,district_tname from mst_p_district order by district_name collate "C"';
		$dist_rs=$db->query($dist_sql);
		if(!$dist_rs)
		{
			print_r($db->errorInfo());
			exit;
		}		
	?>
	<tr style='display:none;'><td style="text-align:right;width:15%"><?PHP echo $label_name[31]; //District ?><span class="star">*</span></td>
	<td style="text-align:left;">
	<span id="div_comm_district">
	<select name="comm_dist" id="comm_dist" data_valid='no' data-error="Please select district"
	onChange="get_taluk();"  class="select_style">            
	<option value="0">--Select--</option>
	<?php  
		$comm_dist = $userProfile->getDistrict_id();
		while($dist_row = $dist_rs->fetch(PDO::FETCH_BOTH))
		{
		$distname=$dist_row["district_name"];
		$disttname=$dist_row["district_tname"];
		if($_SESSION["lang"]=='E'){
		$comm_dist_name=$distname;
		}else{
		$comm_dist_name=$disttname;
		}
		/*if ($comm_dist == $dist_row["district_id"])
		print("<option value='".$dist_row["district_id"]."' selected>".$comm_dist_name."</option>");
		else */
		print("<option value='".$dist_row["district_id"]."'>".$comm_dist_name."</option>");
		
		}
	?>
	</select>
	</span>
	<input type="hidden" id="comm_dist_id_hid" name="comm_dist_id_hid" value="null" data_valid='no'/> 
	</td>
	<td style="text-align:right; width:15%"><?PHP echo $label_name[32]; //Taluk ?> <span class="star">*</span></td>
	<td style="text-align:left;width:15%">
	<span id="div_comm_taluk">
	<select name="comm_taluk" id="comm_taluk" onChange="get_village();" data_valid='no' data-error="Please select taluk" class="select_style">
	<option value="">--Select--</option>
	</select>
	</span>
	<input type="hidden" id="comm_taluk_id_hid" name="comm_taluk_id_hid" value="null" data_valid='no' />
	</td>
	<td style="text-align:right;width:15%"><?PHP echo $label_name[33]; //Revenue Village ?> <span class="star">*</span></td>
	<td style="text-align:left;">
	<span id="div_comm_village" style='display:none;'>
	<select name="comm_rev_village" id="comm_rev_village" data_valid='no' data-error="Please select revenue village" class="select_style">
	<option value="">--Select--</option>
	</select>
	</span>
	<input type="hidden" id="comm_village_id_hid" name="comm_village_id_hid" value="null" data_valid='no' />
	</td></tr>
			
			
			<tr><td colspan="6" class="heading"><?PHP echo $label_name[1]; //Grievance Details?></td></tr>			
			<tr>
			<td style="width:15%"> <?PHP echo $label_name[10]; //Source?> <span class="star">*</span></td>
			<td style="width:15%">
			<select name="source" id="source" 
			onchange="get_sub_source_details();fixDisposingOfficer();" data_valid='yes' data-error="Please select Source" class="select_style">
			<!--<option value="">--Select--</option> -->
			<?php  
			
				$src_sql = "SELECT a.source_id, b.source_name, b.source_tname FROM usr_dept_off_level_sources a
				JOIN lkp_pet_source b ON b.source_id = a.source_id
				WHERE a.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and coalesce(b.enabling,true)
				and ((b.open_fr_date is null and b.open_to_date is null) or ((now()>=b.open_fr_date and now()<=b.open_to_date)))
				order by b.source_id" ;
			
			
			$src_rs=$db->query($src_sql);
			if(!$src_rs)
			{
			print_r($db->errorInfo());
			exit;
			}	
			while($src_row = $src_rs->fetch(PDO::FETCH_BOTH))
			{
			$sourcename=$src_row["source_name"];
			$sourcetname = $src_row["source_tname"];
			if($_SESSION["lang"]=='E'){
			$source_name=$sourcename;
			}else{
			$source_name=$sourcetname;	
			}
				if ($prev_src==$src_row["source_id"])
				print("<option value='".$src_row["source_id"]."' selected>".$source_name."</option>");
				else
				print("<option value='".$src_row["source_id"]."' >".$source_name."</option>");	
			}
			?>
			</select></td>
			<?php
/* 				if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
					$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
				} else {
					$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
				}
				$sql="select dept_user_id, dept_desig_id, dept_desig_name, dept_desig_tname, dept_desig_sname, off_level_dept_name, off_level_dept_tname, off_loc_name, off_loc_tname, off_loc_sname, dept_id, off_level_dept_id, off_loc_id 
				from vw_usr_dept_users_v_sup where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true and dept_desig_role_id=2".$condition.""; */
				
			if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
				$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
			} else {
				$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
			}	$codn_dgp='';
			if($_SESSION['USER_ID_PK']!=1 && $userProfile->getgriev_suptype_id()!=1){
				 $codn_dgp="and dept_user_id=".$_SESSION['USER_ID_PK'];
				 $codn_dgp=" and griev_suptype_id=".$userProfile->getgriev_suptype_id();
			}
			if($userProfile->getOff_level_dept_id()==1 && $userProfile->getPet_disposal()){
			//	$codn_dgp.=" and dept_user_id=".$_SESSION['USER_ID_PK'];
			}
			$disp_codn='';
			if($userProfile->getPet_disposal() || $userProfile->getDesig_roleid() == 5){
				$disp_codn=" and a.dept_user_id=".$dept_user_id;
				}
			$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
			from vw_usr_dept_users_v_sup a
			--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
			where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
			and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
			and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition.$codn_dgp.$disp_codn." and enabling";
	
				$rs=$db->query($sql);
				if(!$rs)
				{
				print_r($db->errorInfo());
				exit;
				}
				
			?>
			<td style="width:15%">
			<b><?PHP echo 'Initiating Officer '; //Disposing Officer ?></b><span class="star">*</span>
			</td>
			<td style="width:15%">
			<span id="dept_list">
			<select name="disposing_officer" id="disposing_officer" data_valid='yes' data-error="Please select Disposing Officer" class="select_style" onchange="load_ef_officer();">
			<?php 
			if($userProfile->getOff_level_id()!=46){
				?>
			<option value="">--Select Initiating Officer--</option>
			<?php
			}
				while ($row= $rs->fetch(PDO::FETCH_BOTH)) {
					$con_off_ename=$row["dept_desig_name"].', '.$row["off_level_dept_name"].', '.$row["off_loc_name"];
					$con_off_tname=$row["dept_desig_tname"].', '.$row["off_level_dept_tname"].', '.$row["off_loc_tname"];
					if($_SESSION["lang"]=='E'){
						$con_officer_ename=$con_off_ename;
					}else{
						$con_officer_ename=$con_off_tname;	
					}
					if ($prev_disposing_officer == $row["dept_user_id"])
						print("<option value='".$row["dept_user_id"]."' selected>".$con_officer_ename."</option>");
					else
						print("<option value='".$row["dept_user_id"]."'>".$con_officer_ename."</option>");
					
				}
			?>
			</select>	
			</span>	
			</td>
			
			<td style="width:15%">
			<?PHP echo $label_name[62]; //Petition Type ?><span class="blue_star">*</span>
			</td>
			<td style="width:15%">
			<span id="pet_type_id">
			<select name="pet_type" id="pet_type" data_valid='no' data-error="Please select Petition type" class="select_style">
			<option value="">--Select Petition Type--</option>
			<?php
				$sql="SELECT pet_type_id, pet_type_name, pet_type_tname from lkp_pet_type order by pet_type_id";
				$result = $db->query($sql);
																	
				while($qua_row = $result->fetch(PDO::FETCH_BOTH))
				{
					$pet_type_name=$qua_row["pet_type_name"];
					$pet_type_tname=$qua_row["pet_type_tname"];
					
					if($_SESSION["lang"]=='E'){
					$pet_type_name=$pet_type_name;
					}else{
					$pet_type_name=$pet_type_tname;	
					}						
					if ($prev_pet_type==$qua_row["pet_type_id"])
						print("<option value='".$qua_row["pet_type_id"]."' selected>".$pet_type_name."</option>");
					else
						print("<option value='".$qua_row["pet_type_id"]."' >".$pet_type_name."</option>");				
					
				}
			?>
		       
              </select>	
			  </span>	
			</td>
			
			</tr>
			<tr id="elec_row" style="display:none;">
			<td style="width:15%"><?PHP echo $label_name[11]; //Sub Source?>  </td>
            <td style="width:15%">
      <select name="sub_source" id="sub_source" data_valid="no" data-error="Please select Sub Source" 
	  class="select_style">
            <option value="">--Select--</option>
			<?php
			
					if ($prev_src != "") {	
						$sub_source_sql = "SELECT a.subsource_id, a.subsource_name, a.subsource_tname
						FROM lkp_pet_subsource a WHERE a.source_id=".$prev_src." ORDER BY a.subsource_name";
							$sub_source_rs=$db->query($sub_source_sql);
							while($sub_source_row = $sub_source_rs->fetch(PDO::FETCH_BOTH))
							{
								$subsourcename=$sub_source_row["subsource_name"];
								$subsourcetname = $sub_source_row["subsource_tname"];
								if($_SESSION["lang"]=='E'){
									$sub_source_name=$subsourcename;
								}else{
									$sub_source_name=$subsourcetname;	
								}
								print("<option value='".$sub_source_row["subsource_id"]."' >".$sub_source_name."</option>");
								
							}
					}
			?>
            </select>
            </td>
			<td style="width:15%"><?PHP echo $label_name[12]; //Sub Source?></td>
			<td colspan="3">
			<input type="text" id="source_remarks" name="source_remarks" class="select_style" data_valid='no' value="" style="width:450px;"/>  </td>			 			  
			</tr>			
            
			
            <tr>
            		
			<?php
				$gre_sql = "SELECT griev_type_id, griev_type_code, griev_type_name, griev_type_tname
				FROM lkp_griev_type  where
				(case when ".$userProfile->getgriev_suptype_id()."=1 then griev_suptype_id in (2,3,1)
				 when ".$userProfile->getgriev_suptype_id()."=2 then griev_suptype_id in (2,1)
				 when ".$userProfile->getgriev_suptype_id()."=3 then griev_suptype_id in (3,1)
				 end
				) order by griev_suptype_id,CASE WHEN lower(griev_type_name) like 'others%' THEN 'ZZOthers' ELSE griev_type_name END";	
			$gre_rs=$db->query($gre_sql);
			if(!$gre_rs)
			{
			print_r($db->errorInfo());
			exit;
			}		
			?>
			
           <td style="width:15%"><?PHP echo $label_name[14]; // Grievance Main Category ?><strong><span class="star">*</span></strong> </td>
			<td style="width:15%">
			<select name="griev_maincode" id="griev_maincode" data_valid='yes' onChange="get_sub_category();"  data-error="Please select Main category" class="select_style" >
			<option value="">--Select--</option>
			<?php  
			while($gre_row = $gre_rs->fetch(PDO::FETCH_BOTH))
			{
				$grename=$gre_row["griev_type_name"];
				$gretname = $gre_row["griev_type_tname"];
				if($_SESSION["lang"]=='E')
				{
				$gre_name = $grename;
				}else{
				$gre_name = $gretname;	
				}
				
				print("<option value='".$gre_row["griev_type_id"]."' >".$gre_name."</option>");
			}?>             
			 </select>	              	 
             </td>
			 
           <td style="width:15%"><?PHP echo $label_name[15]; //Grievance Sub Category ?> <span class="star">*</span></td>
			<td>
			<span id="div_sub_category">			
			  <select name="griev_subcode" id="griev_subcode" data_valid='yes' onchange="get_griev_code();checkForRemarks();"  data-error="Please select Subcategory" class="select_style">
                <option value="">--Select--</option>
              </select>	
			  </span>		
			  </td>
			  <td>Petition Office</td>
			  <td align='left'><input id='office_pattern_icon' type='button' value='+' style='color: #000;background-color: #8D4747;width:20px;border-color:#fff;color:#fff;font-weight:bold;font-size:16px;' onclick='showhid();'></td>
			</tr>
			<tr id="griev_subtype_remarks_row" style="display:none;">
			<td colSpan="4">Grievance Subtype Remarks</td>
			<td colSpan="2"><input type="text" name="griev_subtype_remarks" id="griev_subtype_remarks" data_valid='no' style="width:550px"/></td>
			</tr>
		 	
			<tr id="office_pattern" style='display:none;'><!--<tr id="office_pattern">-->
            <td style="width:15%"><?PHP echo '<b>Petition Office:</b> Pattern'; // Grievance Code?>
			<strong><span class="star">*</span></strong> 
			</td>
			<td style="width:15%">
			<?php 
				if ($userProfile->getDept_off_level_pattern_id() != "" || $userProfile->getDept_off_level_pattern_id()!=null) 
				{
					$off_pat_sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM usr_dept_off_level_pattern where 
					dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id()."";
				} else {
					$off_pat_sql= "SELECT dept_off_level_pattern_id, dept_off_level_pattern_name, dept_off_level_pattern_tname FROM public.usr_dept_off_level_pattern order by 
					dept_off_level_pattern_id";
				}
	
				$off_pat_rs=$db->query($off_pat_sql);
				if(!$off_pat_rs)
				{
					print_r($db->errorInfo());
					exit;
				}
			?>
			<select name="dept_off_level_pattern_id" id="dept_off_level_pattern_id" data_valid='no' data-error="Please select Office Pattern" class="select_style" onchange="loadOfficeLevel();">

			<?php 
			if($off_pat_rs->rowCount() > 1) {
			?>
			<option value="">DGP Office</option>  
			<?php
			}
				while($off_pat_row = $off_pat_rs->fetch(PDO::FETCH_BOTH))
				{
					$dept_off_level_pattern_id=$off_pat_row["dept_off_level_pattern_id"];
					$dept_off_level_pattern_name=$off_pat_row["dept_off_level_pattern_name"];
					$dept_off_level_pattern_tname = $off_pat_row["dept_off_level_pattern_tname"];
					if($_SESSION["lang"]=='E')
					{
					$dept_off_level_pattern_name = $dept_off_level_pattern_name;
					}else{
					$dept_off_level_pattern_name = $dept_off_level_pattern_tname;	
					}
					
					if ($dept_off_level_pattern_id == $userProfile->getDept_off_level_pattern_id())
						print("<option value='".$off_pat_row["dept_off_level_pattern_id"]."' selected>".$dept_off_level_pattern_name."</option>");
					else
						print("<option value='".$off_pat_row["dept_off_level_pattern_id"]."'>".$dept_off_level_pattern_name."</option>");
				}				
			?>
			</td>           
            <td style="width:15%"><?PHP echo 'Office Level'; // Grievance Code?>
			<strong><span class="star">*</span></strong> 
			</td>
			<td style="width:15%">
			<select name="office_level" id="office_level" data_valid='no' data-error="Please select Office Pattern" class="select_style" onChange="loadOfficeLocations();">
			<?php if ($userProfile->getOff_level_id() != 46)  {
			?>
			<option value="">--Select--</option>
			<?php 
			}
				$pattern_id=$userProfile->getDept_off_level_pattern_id();
				$off_level_office_id=$userProfile->getDept_off_level_office_id();
				$condition='';
				if ($off_level_office_id != '') {
					$condition=' and dept_off_level_office_id='.$off_level_office_id.'';
				}
				if ($pattern_id != "" || $pattern_id != null) 
				{
					//echo "1111";
					$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.$condition.") order by off_level_dept_id";
					
				/* 	$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id > ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=".$pattern_id.$condition.") order by off_level_dept_id"; */
				} else {
					//echo "2222";
					$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id = ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id is null) order by off_level_dept_id";
				}
				/*$sql="select off_level_dept_id, off_level_id, dept_off_level_pattern_id, dept_off_level_office_id, off_level_dept_name, off_level_dept_tname from usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and (off_level_id >= ".$userProfile->getOff_level_id()." and dept_off_level_pattern_id=1".$condition.") order by off_level_dept_id";*/
				
				$rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$off_level_id=$row["off_level_id"];
					$off_level_dept_id=$row["off_level_dept_id"];
					$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"] == '') ? 0:$row["dept_off_level_office_id"];
					$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
					$off_level_dept_name=$row["off_level_dept_name"];
					$off_level_dept_tname = $row["off_level_dept_tname"];
					if($_SESSION["lang"]=='E')
					{
						$off_level_dept_name = $off_level_dept_name;
					}else{
						$off_level_dept_name = $off_level_dept_tname;	
					}
					print("<option value='".$off_level."'>".$off_level_dept_name."</option>");
				}	
			?>	
			</select>
			</td>
			<td style="width:15%"><?PHP echo 'Office Location'; // Grievance Main Category ?><strong><span class="star">*</span></strong> </td>
			<td style="width:15%">
			<span id="pet_off">
			<select name="office_loc_id" id="office_loc_id" data_valid='no'  data-error="Please select Office" class="select_style" onChange="populateSupervisoryOfficers();"> 
			<option value="">--Select--</option>
			 </select></span>
			<span id="pet_all_off" style="display:none;">
            <input type="text" name="pet_off_name" id="pet_off_name" disabled="disabled" data_valid='no'/>
            <input type="hidden" name="pet_off_id" id="pet_off_id" data_valid='no' />
            <!--  onclick="retrun get_all_officer_list();" --></span>			 
			<a id="pet_all_link" href="javascript:get_all_offices();" style="display:none;"><?PHP echo "Get Offices"; ?> </a>	
            </td>
			</tr>
			<!-- Concerned Officer Begins here-->
			<tr id="concerned_office">
			<td style="width:15%"><b><?PHP echo $label_name[46]; //Petition Process ?></b><span class="star">*</span></td>
            <td>
            	<?PHP //echo $userProfile->getPet_forward();echo ">>>>>>".$_SESSION['LOGIN_LVL'].">>>>>";
				if($userProfile->getPet_forward() && $_SESSION['LOGIN_LVL'] == NON_BOTTOM){?>
			<input type="radio" name="pet_process" id="pet_forward" value="F" checked="checked"/> <?PHP echo $label_name[47]; //Forward ?>
                <?PHP 
				} if($_SESSION['LOGIN_LVL'] == NON_BOTTOM){
				if ($userProfile->getOff_level_id() == 7 && $userProfile->getDesig_roleid()==1)	{
					?>
				<input type="radio" name="pet_process" id="pet_delegate" value="D" checked /> <?PHP echo 'Delegate'; //Delegate ?>
				<?php
				}else{?>
					<input type="radio" name="pet_process" id="pet_delegate" value="D" /> <?PHP echo 'Delegate'; //Delegate 
				}}														   
				if($userProfile->getPet_act_ret() || $userProfile->getPet_disposal()){
				?>
					<input type="radio" name="pet_process" id="pet_action_taken" value="C" /> <?PHP echo $label_name[48]; //Action Taken?>
				<?PHP
				} //26443020
				?>                
            </td>
			<?php 
				$up_off_level_id=$userProfile->getOff_level_id();
				$up_dept_off_level_pattern_id= $userProfile->getDept_off_level_pattern_id();
				$up_dept_off_level_office_id=$userProfile->getDept_off_level_office_id();
				$up_dept_id=$userProfile->getDept_id();
				$up_off_level_pattern_id=$userProfile->getOff_level_pattern_id();
				$up_off_level_dept_id=$userProfile->getOff_level_dept_id();
				
				/* echo $sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal";
				$rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$disposing_officer_id = $row["dept_user_id"];
				}
				if ($disposing_officer_id != null || $disposing_officer_id != '') {
					$disposal_officer_cond = " and dept_user_id !=".$disposing_officer_id."";
				}
				 */
				if ($up_dept_off_level_pattern_id == ''){
					$up_dept_off_level_pattern_id='null';
				}	
				if ($up_dept_off_level_pattern_id == 'null'){
					$condition = " ";	 
				} else {					
					$condition = " and (dept_off_level_pattern_id is null or dept_off_level_pattern_id=".$up_dept_off_level_pattern_id.")";	
				}
				
				$sql="select dept_user_id from vw_usr_dept_users_v_sup where off_level_id=".$up_off_level_id." and off_loc_id=".$userProfile->getOff_loc_id()." and pet_disposal".$condition."";
				$rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					$disposing_officer_id = $row["dept_user_id"];
				}
				if ($disposing_officer_id != null || $disposing_officer_id != '') {
					$disposal_officer_cond = " and dept_user_id !=".$disposing_officer_id."";
				}
				//echo 
				$sql="select dept_user_id, dept_desig_name, off_loc_id, off_loc_name, off_level_id,off_level_dept_id,off_level_dept_name, dept_off_level_pattern_name
				from vw_usr_dept_users_v_sup
				where dept_id=".$up_dept_id.$condition." 
				and dept_desig_role_id in (2,3) and off_level_id>=".$up_off_level_id." 
				and COALESCE(enabling,true) and off_hier[".$up_off_level_id."]=".$userProfile->getOff_loc_id()."
				and dept_user_id!=".$userProfile->getDept_user_id().$disposal_officer_cond." 
				and pet_forward and
				(case when ".$userProfile->getgriev_suptype_id()."=1 then griev_suptype_id in (2,3,1)
				 when ".$userProfile->getgriev_suptype_id()."=2 then griev_suptype_id in (2,1)
				 when ".$userProfile->getgriev_suptype_id()."=3 then griev_suptype_id in (3,1) end ) 
				 order by off_level_id,off_level_dept_id,dept_desig_id,off_loc_name";
				//exit;
			?>
			<td style="text-align:right;font-weight:bold;">
            <?PHP echo 'Enquiry Filing Officer'; //Concerned Officer ?><span class="star">*</span></td>
		    <td>
            <span id="conc_all_off">
			<select name="supervisory_officer" id="supervisory_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style" style="width:275px" onChange="populateConcernedOfficers();load_ext_dist();">
			<option value="">--Select Enquiry Filing Officer--</option>
			<?php 
				/* $rs=$db->query($sql);
				if(!$rs)
				{
					print_r($db->errorInfo());
					exit;
				}
				$prev_off_level_dept_id = '';
				while($row = $rs->fetch(PDO::FETCH_BOTH))
				{
					
					if ($prev_off_level_dept_id <> $row["off_level_dept_id"]) {
						$off_level_dept_name = $row["off_level_dept_name"];
						$dept_off_level_pattern_name = $row["dept_off_level_pattern_name"];
						$dept_label = $off_level_dept_name.' - '.$dept_off_level_pattern_name;
						print("<optgroup label='".$dept_label."' id='optgroup_".substr($off_level_dept_name,0,3)."'>" );
					}
					$dept_user_id=$row["dept_user_id"];
					$dept_desig_name=$row["dept_desig_name"];
					//$off_level_office_id=($row["dept_off_level_office_id"]==null || $row["dept_off_level_office_id"] == '') ? 0:$row["dept_off_level_office_id"];
					//$off_level = $off_level_id.'-'.$off_level_dept_id.'-'.$off_level_office_id;
					$off_loc_name=$row["off_loc_name"];
					//$off_level_dept_tname = $row["off_level_dept_tname"];*/
/* 					if($_SESSION["lang"]=='E')
					{
						$off_level_dept_name = $off_level_dept_name;
					}else{
						$off_level_dept_name = $off_level_dept_tname;	
					} */
				/*	print("<option value='".$dept_user_id."'>".$dept_desig_name." - ".$off_loc_name."</option>");
					$prev_off_level_dept_id=$row["off_level_dept_id"];
				} */
			?>
			</select>
            </span>			 
            </td>
				
            <td style="width:15%" style="text-align:right;font-weight:bold;">
			<b><?PHP echo 'Enquiry Officer'; //Concerned Officer ?></b>
			<span class="blue_star">*</span>
			</td>
		    <td>
            <span id="conc_all_off">
			<select name="concerned_officer" id="concerned_officer" data_valid='no' data-error="Please Select Concerned Officer" class="select_style" style="width:275px">
			<option value="">--Select Enquiry Officer--</option>
			</select>
            </span>			 
            </td>
			</tr>
			
			
            <!-- File Upload Process and its source --------------------------------->
            <tr>
			<td style="width:15%"><?PHP echo $label_name[16]; //Grievance ?><span class="star">*</span></td>
			<td colspan="3">
			  <textarea name="grievance" id="grievance" rows="3" cols="95" data_valid='yes' 
			  data-error="Please enter petition detail" 
			  onkeydown="textCounter(document.petiton_detail_entry.grievance,document.petiton_detail_entry.remLen2,3000); "  
			  onkeyup="textCounter(document.petiton_detail_entry.grievance,document.petiton_detail_entry.remLen2,3000);" 
			   
			  onKeyPress="return characters_numsonly_grievance(event);" autocomplete="on"></textarea>
			  <input type="hidden" name="remLen2" id="remLen2">	
			  </td>
			  
			  <td colspan="2"><label style="float:left";><?php echo "Upload Petition copy / Supporting documents (Optional);Max File size allowed is 1.5 MB";?></label><br/>
			 
			   <input type="file" name="files[]" id="files" multiple="multiple" 
			   onchange="filesizevalidation();filetypevalidation();" 
			   accept="application/pdf, image/jpeg" 
			   data_valid='no' 
			   data-error="Please select a document to upload." 
			   style="float:left";/>
			   
			   <br/>
			   
			<label style="color:red;float:left;"> Only PDF or JPEG should be uploaded; If you have many files, combine them into a single file. </label>		  
			  </td>
			</tr>
			
			
			<tr>
			<td style="width:15%"><?PHP echo "Instructions of Initiating Officer"; //Instructions of Disposing Officer ?></td>
			<td colspan="3">
			<textarea name="instructions" id="instructions"  data_valid='no' 
			onkeydown="textCounter(document.petiton_detail_entry.instructions,document.petiton_detail_entry.remLen2,3000);"  
			onkeyup="textCounter(document.petiton_detail_entry.instructions,document.petiton_detail_entry.remLen2,3000);" 
			onKeyPress="return characters_numsonly_instructions(event);" autocomplete="on" style="height: 30px;width:98%;"></textarea>
			<input type="hidden" name="remLen2" id="remLen2">	
			</td>
			<td colspan='2' style="text-align:center;"><b style="text-align:center;" id="linked_pet"></b></td>		  
			</tr>
			<tr>  
			<td colspan='6' style='text-align: left;  background-color: #D7A2A2;color: #FFFFFF;font-size: 16px;font-weight: bold;'>FIR/CSR Details
			</td>
			</tr>
			<tr><td><?PHP echo "FIR/CSR";  ?> </td><td>
			<select name="pet_ext_link" id="pet_ext_link" data_valid='no'  data-error="Please select FIR/CSR" class="select_style">
			<option value="">--Select--</option>
			<?php
				$gre_sql = "SELECT pet_ext_link_id,pet_ext_link_name,pet_ext_link_tname FROM public.lkp_pet_ext_link_type ORDER BY pet_ext_link_id ASC ";	
			$gre_rs=$db->query($gre_sql);
			if(!$gre_rs)
			{
			print_r($db->errorInfo());
			exit;
			}		
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH))
				{
					$pet_ext_link_id=$row["pet_ext_link_id"];
					$pet_ext_link_name=$row["pet_ext_link_name"];
					$pet_ext_link_tname=$row["pet_ext_link_tname"];
					if($_SESSION["lang"]=='T')
					{
						$off_level_dept_name = $off_level_dept_tname;
					}else{
						$off_level_dept_name = $off_level_dept_name;	
					}
					print("<option value='".$pet_ext_link_id."'>".$pet_ext_link_name."</option>");
				}	
			?>
			 </select></span></td>
			<td><?php echo "District";?></td>
			<td>
			<select name='ext_dist' id="ext_dist" data_valid='no'  data-error="Please select District" class="select_style" onchange="load_ext_ps();">
			<option value="" selected>--Select--</option>	
				
			</select></td>
			<td><?PHP echo "Police Station";  ?> </td>
			<td><select name='ext_pol_stat' id="ext_pol_stat" data_valid='no' data-error="Please select Police Station" class="select_style">
			<option value="" selected>--Select--</option></select>
			</td>
			<!--td></td-->
			</tr>
			<tr>
			
			 <td><?PHP echo "FIR/CSR Year";  ?> </td>
			 <td>
			 <input type="text" name="ext_year" id="ext_year" class="select_style" maxlength="4" onkeypress="return numbersonly(event);" data_valid="no" data-error="Please enter FIR/CSR Year" Placeholder="YYYY">
			 </td>
			 <td><?PHP echo "FIR/CSR Number";  ?></td>
			 <td colspan='3'>
			 <input type="text" name="ext_no" id="ext_no" class="select_style"  onkeypress="return numbersonly(event);" data_valid="no" data-error="Please enter FIR/CSR Number" maxlength='150'>
			 </td>
			</tr>
			            
			<tr>  
			<td colspan='6' style='text-align: left;  background-color: #D7A2A2;color: #FFFFFF;font-size: 16px;font-weight: bold;'>To Search Petition
			</td>
			</tr>
	<tr id='test' style='border-color:#BC7676;border: 1px solid #BC7676;//background-color:#ffe8b6'><td colspan="2" style="text-align: center;"><input type="button" name="pb_search" id="pb_search" value="<?PHP echo "Applicant Details - Based Search"; //Save?>" onClick='load_pet_search()' onmouseover='highlight_param("applicant");' onmouseleave='remove_highlight("applicant");' style="width:fit-content;" title="Enter Two or more parameters that are highlighted."/></td><td colspan="2" style="text-align: center;"><input type="button" name="sl_search" id="sl_search" value="<?PHP echo "Subject & Location - Based Search"; //Save?>" onClick='load_sub_loc_search()' style="width:fit-content;" onmouseover='highlight_param("subject");' onmouseleave='remove_highlight("subject");' title="Enter Three or more parameters that are highlighted."/></td><td colspan="2" style="text-align: center;"><input type="button" name="pb_search" id="pb_search" value="<?PHP echo "Applicant Details & Subject - Based Search"; //Save?>" onClick='load_pet_loc_search()' style="width:fit-content;" onmouseover='highlight_param("appl_sub");' onmouseleave='remove_highlight("appl_sub");' title="Enter Three or more parameters that are highlighted."/></td></tr>
	
    <tr id="alrtmsg" style="white-space: nowrap;"><td colspan="6" style="display:none;">&nbsp;</td> </tr>
	<tr><td colspan="6" style="font-weight:bold;color:red;">After clicking 'Save' button once, please wait for the Acknowledgement page, and do not click it again or do not Refresh the page</td> </tr> 
	
	<tr id='test1'><td colspan="6" class="btn">
							 
            <input type="button" name="save" id="save" value="<?PHP echo $label_name[8]; //Save?>" onClick="return valchk();"/> &nbsp;
            <input type="button" name="clear" id="clear" value="<?PHP echo $label_name[9]; //Clear ?>"  />
            </td>
			</tr>	
			
	
	
	<?php
	$ptoken = md5(session_id() . $_SESSION['salt']);
	$_SESSION['formptoken']=$ptoken;
	 
	?>
	
	<input type="hidden" name="namelabel" id="namelabel" value="<?PHP echo $label_name[54]; //Name Label?>" />
	<input type="hidden" name="sourcecomments" id="sourcecomments"/><input type="hidden" name="hid" id="hid" />
	<input type="hidden" name="pet_process" id="pet_process" />
	<input type="hidden" name="login_level" id="login_level" value="<?php echo $_SESSION['LOGIN_LVL']; ?>" />			   
	<input type="hidden" name="dept_id" id="dept_id" value="1"/>
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['USER_ID_PK'];?>" />	
																																						 
																										  
	<input type="hidden" name="supervisory_officer_present" id="supervisory_officer_present"/>			
	<input type="hidden" name="dob" id="dob" value=""/>
	<input type="hidden" name="old_pet_no" id="old_pet_no" value=""/>
	<input type="hidden" name="phone_no" id="phone_no" value=""/>
	<input type="hidden" name="gre_doorno" id="gre_doorno" value="" />
	<input type="hidden" name="gre_flat_no" id="gre_flat_no" value="" />
	<input type="hidden" name="gre_street" id="gre_street" value="" />
	<input type="hidden" name="gre_area" id="gre_area" value="" />
	<input type="hidden" name="gre_pincode" id="gre_pincode" value="" />
	<input type="hidden" name="disposing_officer_off_level_id" id="disposing_officer_off_level_id"/>
	<input type="hidden" name="disposing_officer_off_loc_id" id="disposing_officer_off_loc_id"/>
	<input type="hidden" name="lang" id="lang" value="<?php echo $_SESSION["lang"];?>"/>
	<input type="hidden" name="user_dept_id" id="user_dept_id" value="1"/>
	<input type="hidden" name="club_done" id="club_done" value=""/>
	<input type="hidden" name="user_off_level_id" id="user_off_level_id"  
	value="<?php echo $userProfile->getOff_level_id(); ?>"/>
 
	  
												 
												 
  <div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">
  </div>
   
	<input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" /> 
	 </td>
	</tr></tbody></table></form>     
</div></div></div>
<?php  }  ?>
 
<?php
// File upload Validation
if ($_POST['hid']=='done') {
	$valid_formats = array("pdf","jpg","jpeg");
	$max_file_size = 1572864; //in Bytes which is 1.5 mb
	
	$count = 0;
    // Loop $_FILES to execute all files
	foreach ($_FILES['files']['name'] as $f => $name) {
		$doc_size[] = $_FILES["files"]["size"][$f];
		$doc_type[] = $_FILES["files"]["type"][$f];
		if ($_FILES['files']['error'][$f] == 4) {
			continue; // Skip file if any error found
		}	       
		if ($_FILES['files']['error'][$f] == 0) {
			
				$selected_files = $selected_files + $_FILES['files']['size'][$f];	           
			if ($selected_files > $max_file_size) {
				$message .= "$name is too large!.";
				?><script>
					alert("<?php echo  $message; ?>");
					window.location.href="pm_petition_detail_entry.php";
				</script>
				<?php
				continue; // Skip large files
			}
			elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
				$message .= "$name is not a valid format";
				?><script>
					alert("<?php echo $message; ?>");
					window.location.href="pm_petition_detail_entry.php";
				</script>
				<?php
				continue; // Skip invalid file formats
			}
		}
	}
	

if($_POST['hid']=='done' && $message == '') { 
$off_level_pattern_id=stripQuotes(killChars($_POST['off_level_pattern_id']));
$hid_pattern_id=stripQuotes(killChars($_POST['hid_pattern_id']));
$user_off_level_id=stripQuotes(killChars($_POST['user_off_level_id']));

$source=stripQuotes(killChars($_POST['source']));
$griev_code=stripQuotes(killChars($_POST['griev_code']));
$arry=explode('-',$_POST['griev_maincode']);
$griev_maincode=$arry[0];
$chkbox = $_POST['condchk']; 
$griev_subcode=stripQuotes(killChars($_POST['griev_subcode']));
$survey_no=stripQuotes(killChars($_POST['survey_no']));
$sub_div_no=stripQuotes(killChars($_POST['sub_div_no']));
$grievance=stripQuotes(killChars($_POST['grievance'])); 
if($_POST['concerned_officer']!="")
	$concerned_officer=stripQuotes(killChars($_POST['concerned_officer']));
else 
	$concerned_officer=stripQuotes(killChars($_POST['off_id']));	 
$supervisory_officer=stripQuotes(killChars($_POST['supervisory_officer']));	 
$canid=stripQuotes(killChars($_POST['canid']));
$pet_eng_initial=stripQuotes(killChars($_POST['pet_eng_initial']));
$pet_ename=stripQuotes(killChars($_POST['pet_ename']));
$father_ename=stripQuotes(killChars($_POST['father_ename']));
$gender=stripQuotes(killChars($_POST['gender']));
$dob = stripQuotes(killChars($_POST['dob']));
$mobile_number=stripQuotes(killChars($_POST['mobile_number']));
$phone_no=stripQuotes(killChars($_POST['phone_no']));
$id_type=stripQuotes(killChars($_POST['idtype_id']));  
$id_no=stripQuotes(killChars($_POST['idtype_no']));
$email=stripQuotes(killChars($_POST['email']));
$pet_type=stripQuotes(killChars($_POST['pet_type']));
$comm_doorno=stripQuotes(killChars($_POST['comm_doorno']));
$comm_flat_no=stripQuotes(killChars($_POST['comm_flat_no']));
$comm_street=stripQuotes(killChars($_POST['comm_street']));
$comm_area=stripQuotes(killChars($_POST['comm_area']));
$comm_dist=stripQuotes(killChars($_POST['comm_dist']));
$comm_taluk=stripQuotes(killChars($_POST['comm_taluk']));
$comm_rev_village=stripQuotes(killChars($_POST['comm_rev_village']));
$comm_pincode=stripQuotes(killChars($_POST['comm_pincode']));
/////  For get codes afer give canid   //////////
$comm_district_code_hid=stripQuotes(killChars($_POST['comm_dist_id_hid']));
$comm_taluk_code_hid=stripQuotes(killChars($_POST['comm_taluk_id_hid']));
$comm_village_code_hid=stripQuotes(killChars($_POST['comm_village_id_hid']));
$gender_code_hid=stripQuotes(killChars($_POST['gender_id_hid']));
$idtype_code_hid=stripQuotes(killChars($_POST['idtype_id_hid']));  
$user_off_loc_name=stripQuotes(killChars($_POST['user_off_loc_name'])); 
$pet_process=stripQuotes(killChars($_POST['pet_process']));	
$pattern_id=stripQuotes(killChars($_POST['dept_off_level_pattern_id']));	
$office_level=stripQuotes(killChars($_POST['office_level']));	

$office_loc_id=stripQuotes(killChars($_POST['office_loc_id']));	
$pet_off_id=stripQuotes(killChars($_POST['pet_off_id']));
$old_pet_no=stripQuotes(killChars($_POST['old_pet_no']));
$ext_ps_id=stripQuotes(killChars($_POST['ext_pol_stat']));
$gre_dist=null;
$gre_taluk=null;
$gre_rev_village=null;
$gre_block=null;
$gre_tp_village=null;
$gre_urban_body=null;
$gre_division=null;
$gre_subdivision=null;
$gre_circle=null;
$gre_pincode=null;
$user_id=stripQuotes(killChars($_POST['user_id']));
$formptoken=stripQuotes(killChars($_POST['formptoken']));
$off_level_id=stripQuotes(killChars($_POST['off_level_id']));
$sub_source = stripQuotes(killChars($_POST['sub_source']));
$source_remarks = stripQuotes(killChars($_POST['source_remarks']));
$lang = stripQuotes(killChars($_POST['lang']));
$dept = stripQuotes(killChars($_POST['dept']));
//$aadharid = stripQuotes(killChars($_POST['aadharid']));
$isdeo = stripQuotes(killChars($_POST['h_isdeo']));
$pet_ext_link=stripQuotes(killChars($_POST['pet_ext_link']));
$pet_ext_dist=stripQuotes(killChars($_POST['ext_dist']));
$ip=stripQuotes(killChars($_POST['ip']));
$ext_year=stripQuotes(killChars($_POST['ext_year']));
$ext_no=stripQuotes(killChars($_POST['ext_no']));
$userId=stripQuotes(killChars($_POST['userId']));
$disposing_officer=stripQuotes(killChars($_POST['disposing_officer']));

$user_dept_id=stripQuotes(killChars($_POST['user_dept_id']));
$user_off_level_id=stripQuotes(killChars($_POST['user_off_level_id']));
 
$instructions=stripQuotes(killChars($_POST['instructions']));  //instructions

$pet_community=stripQuotes(killChars($_POST['pet_community']));
$petitioner_category=stripQuotes(killChars($_POST['petitioner_category']));
$griev_subtype_remarks=stripQuotes(killChars($_POST['griev_subtype_remarks']));
$supervisory_officer_present=stripQuotes(killChars($_POST['supervisory_officer_present']));

$ip=$_SERVER['REMOTE_ADDR'];
//File name
$document_name = array();
$i=0;
foreach($_FILES['files']['name'] as $filename){
//exit;
  $filename = preg_replace("/[^a-zA-Z0-9.]/", "", $filename);
  $document_name[$i] = $filename;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
 $document_names .= $document_name[$j].',';
}
//Temp Name
$document_tmp_name = array();
$i=0;
foreach($_FILES['files']['tmp_name'] as $tmp_name){
  $document_tmp_name[$i] = $tmp_name;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
  $document_tmp_names .= $document_tmp_name[$j].',';
}
//File Size
$document_size = array();
$i=0;
foreach($_FILES['files']['size'] as $filesize){
  $document_size[$i] = $filesize;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
  $document_sizes .= $document_size[$j].',';
}
//File Type
$document_type = array();
$i=0;
foreach($_FILES['files']['type'] as $filetype){
  $document_type[$i] = $filetype;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
 $document_types .=$document_type[$j].',';
}
//File Count
$i=0;
foreach($_FILES['files']['name'] as $filename){
	$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $filename);
	if($filename!=''){
		$document_count[$i] = count($_FILES);
	}
  $i=$i+1;

}
$j=0;
for($j=0;$j<$i;$j++){
 $document_counts = $document_count[$j]+$j;
} 
$data['xml']='
<Data>
<source>'.$source.'</source>
<sub_source>'.$sub_source.'</sub_source>
<source_remarks>'.$source_remarks.'</source_remarks>
<griev_code>'.$griev_code.'</griev_code>
<griev_maincode>'.$griev_maincode.'</griev_maincode>
<griev_subcode>'.$griev_subcode.'</griev_subcode>
<survey_no>'.$survey_no.'</survey_no>
<sub_div_no>'.$sub_div_no.'</sub_div_no>
<grievance>'.$grievance.'</grievance>
<disposing_officer>'.$disposing_officer.'</disposing_officer>
<supervisory_officer>'.$supervisory_officer.'</supervisory_officer>
<concerned_officer>'.$concerned_officer.'</concerned_officer>
<canid>'.$canid.'</canid>
<pet_eng_initial>'.$pet_eng_initial.'</pet_eng_initial>
<pet_ename>'.$pet_ename.'</pet_ename>
<father_ename>'.$father_ename.'</father_ename>
<gender>'.$gender.'</gender>
<dob>'.$dob.'</dob>
<mobile_number>'.$mobile_number.'</mobile_number>
<phone_no>'.$phone_no.'</phone_no>
<id_type>'.$id_type.'</id_type>
<id_no>'.$id_no.'</id_no>
<idtype_code_hid>'.$idtype_code_hid.'</idtype_code_hid>
<email>'.$email.'</email>
<user_id>'.$user_id.'</user_id>
<comm_doorno>'.$comm_doorno.'</comm_doorno>
<comm_flat_no>'.$comm_flat_no.'</comm_flat_no>
<comm_street>'.$comm_street.'</comm_street>
<comm_area>'.$comm_area.'</comm_area>
<comm_dist>'.$comm_dist.'</comm_dist>
<comm_taluk>'.$comm_taluk.'</comm_taluk>
<comm_rev_village>'.$comm_rev_village.'</comm_rev_village>
<comm_pincode>'.$comm_pincode.'</comm_pincode>
<comm_dist_code_hid>'.$comm_district_code_hid.'</comm_dist_code_hid>
<comm_taluk_code_hid>'.$comm_taluk_code_hid.'</comm_taluk_code_hid>
<comm_village_code_hid>'.$comm_village_code_hid.'</comm_village_code_hid>
<gender_code_hid>'.$gender_code_hid.'</gender_code_hid>
<pet_type>'.$pet_type.'</pet_type>
<gre_doorno>'.$gre_doorno.'</gre_doorno>
<gre_flat_no>'.$gre_flat_no.'</gre_flat_no>
<gre_street>'.$gre_street.'</gre_street>
<gre_area>'.$gre_area.'</gre_area>
<gre_dist>'.$gre_dist.'</gre_dist>
<gre_taluk>'.$gre_taluk.'</gre_taluk>
<gre_rev_village>'.$gre_rev_village.'</gre_rev_village>

<cra_gre_taluk>'.$cra_gre_taluk.'</cra_gre_taluk>
<cra_gre_rev_village>'.$cra_gre_rev_village.'</cra_gre_rev_village>

<gre_block>'.$gre_block.'</gre_block>
<gre_tp_village>'.$gre_tp_village.'</gre_tp_village>
<gre_urban_body>'.$gre_urban_body.'</gre_urban_body>
<gre_division>'.$gre_division.'</gre_division>
<gre_subdivision>'.$gre_subdivision.'</gre_subdivision>
<gre_circle>'.$gre_circle.'</gre_circle>
<gre_pincode>'.$gre_pincode.'</gre_pincode>
<user_id>'.$_SESSION['USER_ID_PK'].'</user_id>
<form_tocken>'.$formptoken.'</form_tocken>
<off_level_id>'.$off_level_id.'</off_level_id>
<document_names>'.$document_names.'</document_names>
<document_tmp_names>'.$document_tmp_names.'</document_tmp_names>
<document_sizes>'.$document_sizes.'</document_sizes>
<document_types>'.$document_types.'</document_types>
<document_counts>'.$document_counts.'</document_counts>
<lang>'.$lang.'</lang>
<dept>'.$dept.'</dept>
<isdeo>'.$isdeo.'</isdeo> 
<user_dept_id>'.$user_dept_id.'</user_dept_id>
<user_off_level_id>'.$user_off_level_id.'</user_off_level_id>
<user_off_loc_name>'.$user_off_loc_name.'</user_off_loc_name>
<off_level_pattern_id>'.$off_level_pattern_id.'</off_level_pattern_id>
<instructions>'.$instructions.'</instructions>
<pet_community>'.$pet_community.'</pet_community>
<petitioner_category>'.$petitioner_category.'</petitioner_category>
<griev_subtype_remarks>'.$griev_subtype_remarks.'</griev_subtype_remarks>
<ip_address>'.$ip.'</ip_address>
<pet_process>'.$pet_process.'</pet_process>										   

<pattern_id>'.$pattern_id.'</pattern_id>										   
<office_level>'.$office_level.'</office_level>										   
<office_loc_id>'.$office_loc_id.'</office_loc_id>										   
<pet_off_id>'.$pet_off_id.'</pet_off_id>										   
<supervisory_officer_present>'.$supervisory_officer_present.'</supervisory_officer_present>										   
<old_pet_no>'.$old_pet_no.'</old_pet_no>	
<pet_ext_link>'.$pet_ext_link.'</pet_ext_link>						   
<pet_ext_dist>'.$pet_ext_dist.'</pet_ext_dist>						   
<ext_year>'.$ext_year.'</ext_year>						   
<ext_ps_id>'.$ext_ps_id.'</ext_ps_id>						   
<userId>'.$userId.'</userId>				   
<ext_no>'.$ext_no.'</ext_no>							   
</Data>';

	

$_SESSION["prev_src"] = $source;
$_SESSION["prev_pet_type"] = $pet_type;
$_SESSION["prev_disposing_officer"] = $disposing_officer;

$ipaddress = $_SERVER['SERVER_ADDR'];
$ippart = explode('/',$_SERVER['REQUEST_URI']);
if ($ippart[1] == 'pm_petition_detail_entry.php'){
        $url = 'https://locahost/police/pm_petition_detail_insert.php';
}
else {
        $url = 'https://locahost/police/psppp/pm_petition_detail_insert.php';
}

$url = 'http://localhost/police/pm_petition_detail_insert.php';
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// added on 23/7/2020
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
// added on 23/7/2020
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec ($ch);
print_r($result);
 }
}
?>
<?php include("footer.php"); ?>
