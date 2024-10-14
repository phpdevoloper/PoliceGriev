<?php
ob_start();
session_start();
$pagetitle="List of Users";
include("header_menu.php");
include("header_menu_report.php");
include("menu_home.php");
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
		p2_loadGrid('below');
	});
		
	$("#p_clear").click(function(){
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
	$('#p2_dataGrid').empty();
	document.getElementById("loadmessage").style.display='';
	var param = "mode=p_search_officers"+p_searchParams();
	if(button=='below'){
		param+="&dist_id="+$('#dist_id').val();
	}
	
	if($('#off_level_id').val()<13){
		param+="&levl="+button+"&off_level_id="+$('#off_level_id').val();
	}
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionProcessedByUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response
			document.getElementById("loadmessage").style.display='none';			
			 p_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
}

function p_createGrid(xml){
	$('#p2_dataGrid').empty();
	$i=0;
	if ($(xml).find('dept_user_id').length == 0) {
		alert("No records found for the given parameters");
	}
	$(xml).find('dept_user_id').each(function(i)
	{
		
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+(++$i)+"</id>"+
		"<td>"+$(xml).find('off_loc_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('off_level_dept_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_tname').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('user_name').eq(i).text()+"</td>"+
		"</tr>");
		document.getElementById("dontprint1").style.display='';
		//$('#td_'+petition_id).append("<input type='radio' name='p2_id' onClick='javascript:petition_edit("+petition_id+")'/>");		
	});
	$(xml).find('dept_name').each(function(i)
	{
		var label_n = "User Details of "+$(xml).find('dept_name').eq(i).text();
		document.getElementById("label_n").innerHTML = label_n;
	});
}
function printAsPdf() {
	document.getElementById("header").style.display='none';
	document.getElementById("header_report").style.display='none'; 
	document.getElementById("myTopnav").style.display='none';
	document.getElementById("gs_head").style.display='none';
	//document.getElementById("usr_detail").style.display='none'; 
	//document.getElementById("footer").style.visibility='hidden';
	document.getElementById("dontprint1").style.visibility='hidden';
	window.print();
	document.getElementById("header_report").style.display='none'; 
	document.getElementById("header").style.display=''; 
	document.getElementById("myTopnav").style.display='block';
	//document.getElementById("usr_detail").style.display='block';
	//document.getElementById("footer").style.visibility='visible';
	document.getElementById("dontprint1").style.visibility='visible';
	document.getElementById("gs_head").style.display='';
	
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
<form method="post" name="petition_process_by_us" id="petition_process_by_us" style="background-color:#F4CBCB;">
<div id="dontprint"><div class="form_heading" ><div class="heading"><?PHP echo $label_name[0];//Petitions Processed By Us?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
	  <?php if ($userProfile->getOff_level_id()>=13){ ?>
	  <input type='hidden' name='dept_id' id='dept_id' value='1'>
	  <?php }else{?>
	<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tr id="gs_head">
      <td colspan='7'>
      <input type='button' name='above_dist' id='above_dist' onclick='document.getElementById("below_dist_row").style.display="none";p2_loadGrid("above");' value='Users upto District level' style='width:fit-content;float:left;text-align:center;left:20%;position:relative;'>
	  <input type='button' name='below_dist' id='below_dist' onclick='$("#p2_dataGrid").empty();load_ext_dist();document.getElementById("below_dist_row").style.display="";document.getElementById("dontprint1").style.display="none";' value='District level Users' style='width:fit-content;float:right;text-align:left;right:20%;position:relative;'></td></tr>
	  <tr id="below_dist_row" style="display:none;"><td>
      <select name="dist_id" id="dist_id">
	  <option>--Select District--</option>
              </select>
     
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[2];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[3];//Clear?>" class="button"/>
      </td>
      </tr>
      </table>
	  <?php } ?>
      <table class="existRecTbl">
      <thead>
      <tr>
      <th><label id="label_n"><?PHP echo $label_name[11] //User Details;?></label></th>

      </tr>
      </thead>
      </table>
      <table class="gridTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[4];?></th>
      <th><?PHP echo $label_name[5];?></th>
      <th><?PHP echo $label_name[6];?></th>
      <th><?PHP echo $label_name[7];?></th>
      <th><?PHP echo $label_name[8];?></th>
      <th><?PHP echo $label_name[9];?></th>
      </tr>
      </thead>
      <tbody id="p2_dataGrid"></tbody>
      </table>
      <div id="loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
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
      <input type="button" name="" id="dontprint1" value="Print" style="display:none" class="button" onClick="return printAsPdf()" /> 
      </td>     
      </tbody>
      </table>
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>