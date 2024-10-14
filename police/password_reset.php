<?php
ob_start();
session_start();
$pagetitle="Reset Password";
include("header_menu.php");
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
?>
<script nonce='1a2b' type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script nonce='1a2b' type="text/javascript" src="js/common_form_function.js"></script>
<script nonce='1a2b'>
$(document).ready(function(){
	
}); 
</script>

<!-- Date Picker css-->
<link rel="stylesheet" href="css/jquery.datepick.css" media="screen" type="text/css">
<script nonce='1a2b' type="text/javascript" src="js/jquery.datepick.js"></script>
<label style="display:none">
<img src="images/calendar.gif" id="calImg">
</label>
<script nonce='1a2b' type="text/javascript" charset="utf-8">
$(document).ready(function()
{
	document.getElementById("dontprint1").onclick = function(){
		return resetPassword();
	}
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
			 p_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
}
var array = [];

function p_createGrid(xml){
	$('#p2_dataGrid').empty();
	$i=0;
	if ($(xml).find('dept_user_id').length == 0) {
		alert("No records found for the given parameters");
	}
	$(xml).find('dept_user_id').each(function(i)
	{
		dept_user_id = $(xml).find('dept_user_id').eq(i).text();
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+"<input type='checkbox' name='chkbox' id='"+dept_user_id+"' value='"+dept_user_id+"'>"+"</td>"+
		"<td>"+$(xml).find('off_loc_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('off_level_dept_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('dept_desig_tname').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('user_name').eq(i).text()+"</td>"+
		"</tr>");
		document.getElementById("dontprint1").style.display='';		
	});
	$(xml).find('dept_name').each(function(i)
	{
		var label_n = "User Details of "+$(xml).find('dept_name').eq(i).text();
		document.getElementById("label_n").innerHTML = label_n;
	});
}
function resetPassword() {
	var array = [];
	userID=$('#userID').val();
		$("input:checkbox[name=chkbox]:checked").each(function(){
			array.push($(this).val());
		});
	var arrLen = array.length;
	if (arrLen == 0) {
		alert("Select atleat one user to reset password");
	} else {
		var confirm = window.confirm("Thia action will reset the password for the selected users. Do you want to continue?");
		if (confirm) {
			//alert(array);
			var param = "mode=password_reset"+"&userslist="+array+"&userID="+userID;
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
					 //p_createGrid(xml);
					count = $(xml).find('count').eq(0).text(); 
					if (count>0) {
						alert("The password reset to 'Password@1' for the selected users!!");
						$('input:checkbox').removeAttr('checked');
					}
				},  
				error: function(e){  
					//alert('Error: ' + e);  
				}
			});//ajax end
		} else {
			$('input:checkbox').removeAttr('checked');
			return false;
		}
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
<form method="post" name="petition_process_by_us" id="petition_process_by_us" style="background-color:#F4CBCB;">
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[20];//Petitions Processed By Us?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="searchTbl" style="border-top: 1px solid #000000;">
      <?php if ($userProfile->getOff_level_id()>=13){ ?>
	  <input type='hidden' name='dept_id' id='dept_id' value='1'>
	  <?php }else{?>
	<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tr id="gs_head">
      <td colspan='7'>
      <input type='button' name='above_dist' id='above_dist' onclick='document.getElementById("below_dist_row").style.display="none";p2_loadGrid("above");' value='Users upto District level' style='width:fit-content;float:left;text-align:center;left:20%;position:relative;'>
	  <input type='button' name='below_dist' id='below_dist' onclick='$("#p2_dataGrid").empty();load_ext_dist();document.getElementById("below_dist_row").style.display="";' value='District level Users' style='width:fit-content;float:right;text-align:left;right:20%;position:relative;'></td></tr>
	  <tr id="below_dist_row" style="display:none;"><td>
      <select name="dist_id" id="dist_id">
	  <option>--Select District--</option>
              </select>
     
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[2];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[3];//Clear?>" class="button"/>
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
      <th><?PHP echo $label_name[21];?></th>
     <th><?PHP echo $label_name[5];?></th>
      <th><?PHP echo $label_name[6];?></th>
      <th><?PHP echo $label_name[7];?></th>
      <th><?PHP echo $label_name[8];?></th>
      <th><?PHP echo $label_name[9];?></th>
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
      <input type="button" name="dontprint1" style="width:160px" id="dontprint1" value="<?PHP echo $label_name[22];//Page?>" style="display:none" class="button"/> 
      </td>     
      </tbody>
      </table>
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>