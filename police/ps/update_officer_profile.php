<?php
ob_start();
session_start();
$pagetitle="Update Officers Profile";
include("header_menu.php");
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
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data

?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>

<!-- Date Picker css-->
<link rel="stylesheet" href="css/jquery.datepick.css" media="screen" type="text/css">
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<label style="display:none">
<img src="images/calendar.gif" id="calImg">
</label>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{
	if($('#off_level_id').val()>=13){
	
		p2_loadGrid();
	}
	$("#p_search").click(function(){
		
		if($('#dist_id').val()==''){
			alert("Select District.");
			return false;
		}
	
		document.getElementById("alpha_list").style.display="";
		p2_loadGrid('below');
	});
		
	$("#p_clear").click(function(){
		document.getElementById("alpha_list").style.display="none";
		p_clearSerachParams();
	});
	
});


function p_searchParams(){
	var param="&dept_id=1";
	return param;
}

function p_clearSerachParams(){
	$('#dist_id').val('');
	$('#p2_dataGrid').empty();
}

function p2_loadGrid(button=''){
	
	/* if ($('#dept_id').val() == '') {
		alert('Please select a Department');
		return false;
	} else { */
		var param = "mode=p_search_officers"+p_searchParams();
	if(button=='below'){
		param+="&dist_id="+$('#dist_id').val();
	}
	if($('#off_level_id').val()<13){
		param+="&levl="+button+"&off_level_id="+$('#off_level_id').val();
	}

	if(button!=''){
		$('#buttonpress').val(button);
	}

		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "update_officer_profile_action.php",  
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				 p_createGrid(xml);
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			}
		});//ajax end
	//}
	
}

function chk_email(e) 
	{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		//alert(unicode);
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >63 && unicode<123 && unicode!=96 && unicode!=95 && unicode!=94 && 
		unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode==45 || unicode==95|| unicode>=47 && unicode<=57))
				return true
		else
				return false
		}
	}
var mobilearray = [];
var emailarray = [];

function chk_forsamemail(dept_user_id) {
	hemail = document.getElementById('hemail_'+dept_user_id).value;	 
	email = document.getElementById('email_'+dept_user_id).value;
	if (email != '') {
		var atpos = email.indexOf("@");
		var dotpos = email.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
			alert("Not a valid e-mail address");
			document.getElementById('email_'+dept_user_id).value = '';
			 window.setTimeout(function ()
			{
				document.getElementById('email_'+dept_user_id).focus();
			}, 0);
			
			return false;
		} else {
			if (hemail != email) {
				emailarray.push(dept_user_id +"*"+ email);
			}
		}
	} else {
			emailarray.push(dept_user_id +"*"+ '');
	}
 	
	
}

function chk_forsamemobile(dept_user_id) {
	hmobile = document.getElementById('hmobile_'+dept_user_id).value;	
	mobile = document.getElementById('mobile_'+dept_user_id).value;	
	if (hmobile != mobile) {
			mobilearray.push(dept_user_id +"*"+ mobile);
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
	if (unicode!=8 && unicode !=9 && unicode !=46)
	{
		if((unicode<48||unicode>57)&& unicode !=43) {
			alert("Only numbers 0 to 9 and + are allowed");
			return false
		}
	}
}


function p_createGrid(xml){
	$('#p2_dataGrid').empty();
	$i=0;
	if ($(xml).find('dept_user_id').length == 0) {
		alert("No records found for the given parameters");
		$("#dontprint1").attr('disabled','disabled');
	}
	$(xml).find('dept_user_id').each(function(i)
	{
		mobile = $(xml).find('mobile').eq(i).text();
		email = $(xml).find('email').eq(i).text();
		
		$i=$i+1;
		dept_user_id = $(xml).find('dept_user_id').eq(i).text();
		$('#p2_dataGrid')
		.append("<tr style='background:#FCEFEF;'>"+
		"<input type='hidden' name='hmobile_"+dept_user_id+"' id='hmobile_"+dept_user_id+"' value="+mobile+">"+
		"<input type='hidden' name='hemail_"+dept_user_id+"' id='hemail_"+dept_user_id+"' value="+email+">"+
		"<input type='hidden' name='usrid_"+dept_user_id+"' id='usrid_"+dept_user_id+"' value="+dept_user_id+">"+
		"<td style='text-align:center;'>"+$i+"</td>"+
		"<td>"+$(xml).find('off_level_dept_name').eq(i).text()+' - '+$(xml).find('off_loc_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_tname').eq(i).text()+"</td>"+
		"<td>"+"<input type='text' style='width:120px;font-weight:bold;' onKeyPress='chkval("+dept_user_id+");return numbersonly_ph(event);chk_forsamemobile("+dept_user_id+");' maxlength='13' name='mobile_"+dept_user_id+"' onblur='chk_forsamemobile("+dept_user_id+")' id='mobile_"+dept_user_id+"' value="+mobile+" >"+"</td>"+
		"<td>"+"<input type='text' style='width:250px;font-weight:bold;' onKeyPress='chkval("+dept_user_id+");return chk_email(event);chk_forsamemail("+dept_user_id+");;' onblur='chk_forsamemail("+dept_user_id+");' name='email_"+dept_user_id+"' maxlength='30' id='email_"+dept_user_id+"'  value="+email+">"+"</td>"+
		"</tr>");
		document.getElementById("dontprint1").style.display='';
$("#dontprint1").removeAttr('disabled');
				
	});
	$(xml).find('dept_name').each(function(i)
	{
		var label_n = "User Details of "+$(xml).find('dept_name').eq(i).text();
		document.getElementById("label_n").innerHTML = label_n;
	});
}

function chkval(dept_user_id){ 
$("#dontprint1").removeAttr('disabled');
}

function saveDetails() {
	//alert(mobilearray);
		if(mobilearray=='' && emailarray==''){
		alert('No Changes made.');
		return false;
		}else{
	var confirm = window.confirm("Do you want to save the details?");
	if (confirm) {
		//alert(emailarray);
		var param = "mode=update_profile"+"&mobilearray="+mobilearray+"&emailarray="+emailarray;
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "update_officer_profile_action.php",  
			data: param,  
					
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
				mobilearray=[];emailarray=[];
			},
			success: function(xml){
				// we have the response 
			   //p_createGrid(xml);
				count = $(xml).find('count').eq(0).text(); 
				if (count>0) {
					alert("The profile details are updated successfully!!!!");
				}else{
					
				$("#dontprint1").attr("disabled","disabled");
				}
				$('#p2_dataGrid').empty();
				if($('#off_level_id').val()>=13){
	
		p2_loadGrid();
	}
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			}
		});//ajax end
		}else{
			return false;
		}
	}
}

function searchByAlpha(element) {
	var desig = element.id;
	
	if ($('#dept_id').val() == '') {
		alert('Please select a Department');
		//return false;
	} else {
	button=$('#buttonpress').val();
		var param = "mode=p_search_officers"+p_searchParams()+"&desig_first="+ desig+"&levl="+button;
		
	if(button=='below'){
		param+="&dist_id="+$('#dist_id').val();
	}
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "update_officer_profile_action.php",  
			data: param,  
				
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				$("#dontprint1").attr("disabled","disabled");
				// we have the response 
				 p_createGrid(xml);
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			}
		});//ajax end
	}
		
	
	
}

function load_ext_dist() {
	ef_off=$('#userID').val();
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'load_ext_dist',ef_off:ef_off},
		error:function(){ alert("") },
		success: function(html){
			document.getElementById("dist_id").innerHTML=html;			
		}
	});	
	
}
</script>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=75 order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}	
}

?>
<form method="post" name="petition_process_by_us" id="petition_process_by_us" style="background-color:#F4CBCB">
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[25];//Petitions Processed By Us?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="searchTbl" style="border-top: 1px solid #000000;">
          <?php if ($userProfile->getOff_level_id()>=13){ ?>
	  <input type='hidden' name='dept_id' id='dept_id' value='1'>
	  <?php }else{?>
	<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tr id="gs_head">
      <td colspan='7'>
      <input type='button' name='above_dist' id='above_dist' onclick='document.getElementById("below_dist_row").style.display="none";document.getElementById("alpha_list").style.display="";$("#dontprint1").attr("disabled","disabled");p2_loadGrid("above");' value='Users upto District level' style='width:fit-content;float:left;text-align:center;left:20%;position:relative;'>
	  <input type='button' name='below_dist' id='below_dist' onclick='$("#dontprint1").attr("disabled","disabled");$("#p2_dataGrid").empty();load_ext_dist();document.getElementById("below_dist_row").style.display="";document.getElementById("alpha_list").style.display="none";' value='District level Users' style='width:fit-content;float:right;text-align:left;right:20%;position:relative;'></td></tr>
	  <tr id="below_dist_row" style="display:none;"><td>
      <select name="dist_id" id="dist_id">
	  <option>--Select District--</option>
              </select> 
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[2];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[3];//Clear?>" class="button"/>
      </td>
      </tr>
	  <tr id='alpha_list' style='display:none;'>
	<td style="text-align:center;background-color:#FFFFFF;" colspan="2">
	<?php
	echo "<b style='position:absolute;float:left;left:18%;'>Designation's starting with :</b>";
	$letters = range('A', 'Z');
 
	
	foreach ( $letters as $letter ) {
		$menu .= "<a id='".$letter."' href='javascript:searchByAlpha(".$letter.")'>".$letter."</a>"."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}	
   echo $menu;
	?>
	</td>
	</tr>
      </tbody>
      </table>
	  
	  <?php } ?>
      <table class="existRecTbl">
      <thead>
      <tr>
      <th><label id="label_n"><?PHP echo $label_name[11];?></label></th>
      </tr>
      </thead>
      </table>
      <table class="gridTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[4];?></th>
     <th><?PHP echo $label_name[6]." - ".$label_name[5];?></th>
      <th><?PHP echo $label_name[7];?></th>
      <th><?PHP echo $label_name[8];?></th>
      <th><?PHP echo $label_name[23];?></th>
      <th><?PHP echo $label_name[24];?></th>
      </tr>
      </thead>
      <tbody id="p2_dataGrid"></tbody>
      </table>
      
      <table class="paginationTbl">
      <tbody>
      <tr id="p2_pageFooter1" style="display: none;">
      <td id="p2_previous"></td>
      <td><?PHP echo $label_name[19];//Page?><select id="p2_pageNoList" name="p2_pageNoList" class="pageNoList"></select>
      <span id="p2_noOfPageSpan"></span></td>
      <td id="p2_next"></td>
      </tr>
      <tr id="p2_pageFooter2"><td colspan="3" class="emptyTR"></td>
      </tr>
      <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
      <td colspan="8" class="buttonTD">
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" /> 
      <input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo($userProfile->getOff_level_id());?>" /> 
      <input type="hidden" name="userID" id="userID" value="<?php echo($_SESSION['USER_ID_PK']);?>" /> 
      <input type="hidden" name="buttonpress" id="buttonpress" /> 
      <input type="button" name="" style="width:160px" id="dontprint1" value="<?PHP echo $label_name[26];//Page?>" style="display:none" class="button" onClick="return saveDetails()" disabled /> 
      </td>     
      </tbody>
      </table>
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>