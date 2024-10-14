<?PHP
session_start();
include("db.php");
// psql -U postgres -d ed_gdp_appscan -f E:\vpk-e-drive-backup\GDP\Backup\psppp.sql
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Office Designation Search</title>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{	
	$('#p1_pageNoList').change(function(){
		p1_loadGrid($('#p1_pageNoList').val(), $('#p1_pageSize').val());
	});
	
	$("#p1_submit").click(function(){
		submitToDesign();
	});
	
	$('#p1_pageSize').change(function(){
		p1_loadGrid(1, $('#p1_pageSize').val());
	});
	
	$("#p1_exit").click(function(){
		form_exit();
	});
	
	p1_loadGrid(1, $('#p1_pageSize').val());
});
function searchById() {
	if ($("#off_id").val() != '' || $("#desig_name").val() != '') {  //desig_name 
		var pageNo=1;
		var pageSize= $('#p1_pageSize').val();
		var desig = '';
		var param = "mode=p1_search"
				+"&petition_id="+ $("#petition_id").val()
				+"&griev_type_id="+ $("#griev_type_id").val()
				+"&griev_sub_type_id="+ $("#griev_sub_type_id").val()
				+"&off_pattern_id="+ $("#off_pattern_id").val()
				+"&off_loc_id="+ $("#off_loc_id").val()
				+"&dept_id="+ $("#dept_id").val()
				+"&off_id="+ $("#off_id").val()
				+"&action_entby="+ $("#action_entby").val()
				+"&act_type_code="+ $("#act_type_code").val()
				+"&desig_name="+ $("#desig_name").val()
				+"&desig_first="+ desig
				+"&page_size="+pageSize
				+"&page_no="+pageNo;
				
				
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "p1_OfficeDesignSearchAction.php",  
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				 p1_createGrid(xml);
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			}
		});//ajax end
	}
	
}
function p1_loadGrid(pageNo, pageSize){
	document.getElementById("t2_loadmessage").style.display='';
	var param = "mode=p1_search"
			+"&petition_id="+ $("#petition_id").val()
			+"&griev_type_id="+ $("#griev_type_id").val()
			+"&griev_sub_type_id="+ $("#griev_sub_type_id").val()
			+"&off_pattern_id="+ $("#off_pattern_id").val()
			+"&off_loc_id="+ $("#off_loc_id").val()
			+"&dept_id="+ $("#dept_id").val() 
			+"&action_entby="+ $("#action_entby").val()
			+"&act_type_code="+ $("#act_type_code").val()
			+"&desig_first="+ $('#alpha').val()
			+"&page_size="+pageSize
			+"&page_no="+pageNo;
			
			
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p1_OfficeDesignSearchAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 p1_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
	}

function p1_createGrid(xml){
	$('#p1_dataGrid').empty();
	document.getElementById("t2_loadmessage").style.display='none';
	if ($(xml).find('dept_user_id').length == 0) {
		alert("No officers found in this search");
	}
	$(xml).find('dept_user_id').each(function(i)
	{
		$('#p1_dataGrid')
		.append("<tr id='tr_"+$(xml).find('dept_user_id').eq(i).text()+"'>"+
		"<td><input type='radio' name='dept_user_id' value='"+$(xml).find('dept_user_id').eq(i).text()+"'/></td>"+
		"<td>"+$(xml).find('off_level_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('off_location').eq(i).text()+"</td>"+ 
		"<td>"+$(xml).find('dept_desig_name').eq(i).text()+"</td>"+		
		"</tr>");
		
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	
	drawPagination('p1_pageFooter1', 'p1_pageFooter2','p1_pageSize', 'p1_pageNoList', 'p1_next', 'p1_previous', 'p1_noOfPageSpan', 'p1_loadGrid', pageNo, pageSize, noOfPage);
}

function submitToDesign(){
	if($('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val()>0){
		var petition_id=$("#petition_id").val();
		var p_act_id=$("#pet_action_id").val();
		
		var open_form='<?PHP echo $_REQUEST['open_form']?>';
		var offLoc_designName = $('#tr_'+$('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val()).find("td").eq(1).html() +" / "+
			$('#tr_'+$('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val()).find("td").eq(3).html();
		if(open_form=="P1"){
			opener.p1_returnDesignationSearch( petition_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		} else if(open_form=="P2"){
			opener.p2_returnDesignationSearch( petition_id,p_act_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		} else if(open_form=="P5"){
			opener.p5_returnDesignationSearch( petition_id,p_act_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		} else if(open_form=="PE"){
			opener.pe_returnDesignationSearch( petition_id,p_act_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		} else if(open_form=="P6"){
			opener.p6_returnDesignationSearch( petition_id,p_act_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		} else if(open_form=="PT1"){
			opener.pt1_returnDesignationSearch( petition_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		}
		else{
			opener.p3_returnDesignationSearch( petition_id, $('input[name=dept_user_id]:checked', '#p1_off_loc_design_search').val(), offLoc_designName);
		}
		Minimize();
	}
	else{
		alert("Please select any one Designation");	
	}
}

function searchByAlpha(element) {
	var desig = element.id;
	var pageNo=1;
	var pageSize= $('#p1_pageSize').val();
	$('#alpha').val(desig);
	var param = "mode=p1_search"
				+"&petition_id="+ $("#petition_id").val()
				+"&griev_type_id="+ $("#griev_type_id").val()
				+"&griev_sub_type_id="+ $("#griev_sub_type_id").val()
				+"&off_pattern_id="+ $("#off_pattern_id").val()
				+"&off_loc_id="+ $("#off_loc_id").val()
				+"&dept_id="+ $("#dept_id").val()
				+"&off_id="+ $("#off_id").val()
				+"&act_type_code="+ $("#act_type_code").val()
				+"&desig_name="+ $("#desig_name").val()
				+"&desig_first="+ desig
				+"&page_size="+pageSize
				+"&page_no="+pageNo;
				
				
		$.ajax({
			type: "POST",
			dataType: "xml",
			url: "p1_OfficeDesignSearchAction.php",  
			data: param,  
			
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				 p1_createGrid(xml);
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			}
		});//ajax end 
	
}

</script>
</head>
<body>
<form method="post" id="p1_off_loc_design_search">
<div class="contentMainDiv">
<div class="contentDiv">
<input type="hidden" name="alpha" id="alpha"/>

<input type="hidden" name="petition_id" id="petition_id" value="<?PHP echo $_REQUEST['petition_id']?>"/>
<input type="hidden" name="pet_action_id" id="pet_action_id" value="<?PHP echo $_REQUEST['pet_action_id']?>"/>
<input type="hidden" name="act_type_code" id="act_type_code" value="<?PHP echo $_REQUEST['act_type_code']?>"/>

<input type="hidden" name="griev_type_id" id="griev_type_id" value="<?PHP echo $_REQUEST['griev_type_id']?>"/>
<input type="hidden" name="griev_sub_type_id" id="griev_sub_type_id" value="<?PHP echo $_REQUEST['griev_sub_type_id']?>"/>

<input type="hidden" name="off_loc_id" id="off_loc_id" value="<?PHP echo $_REQUEST['off_loc_id']?>"/>
<input type="hidden" name="dept_id" id="dept_id" value="<?PHP echo $_REQUEST['dept_id']?>"/>
<input type="hidden" name="action_entby" id="action_entby" value="<?PHP echo $_REQUEST['action_entby']?>"/>
<table class="existRecTbl" style="border-top-style: solid;">
	<thead>
    <tr>
    	<th style="background-color: #BC7676; color: #FFFFFF; font-size: 150%;" colspan="2">Office Designation Search</th>
    </tr>
        <tr>
    <td colspan="2" align="center">
    <label><b>Enter Officer Id</b></label>
    <input type="text"  name="off_id" id="off_id" value="" maxlength="5" style="width:70px;"/>
	&nbsp;&nbsp;&nbsp;
	<label><b>Enter Designation Name</b></label>
    <input type="text"  name="desig_name" id="desig_name" value="" maxlength="20" style="width:175px;"/>
    <input type="button" name="search_off" id="search_off" value="Search" onclick="searchById()"/>
    </td>
    </tr>
	<tr>
	<td style="text-align:center;background-color:#FFFFFF;" colspan="2">
	<label style="color:black;"><b>Alphabetical Search for Designation Name</b></label>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php
	$letters = range('A', 'Z');
 
	
	foreach ( $letters as $letter ) {
		$menu .= "<a id='".$letter."' href='javascript:searchByAlpha(".$letter.")'>".$letter."</a>"."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	
   echo $menu;
	?>
	</td>
	</tr>
	
	<tr>
		<th>Existing Details</th>
		<th>Page&nbsp;Size<select name="p1_pageSize" id="p1_pageSize" class="pageSize">
				<option value="50" selected="selected">50</option>
				<option value="30">30</option>
				<option value="15">15</option>
			</select>
		</th>
	</tr>
	</thead>
</table>
<table class="gridTbl">
	<thead>
		<tr>
			<th>Select</th>
			<th>Office Level</th>
			<th>Office Location</th>
			<th>Designation</th>            
            
		</tr>
	</thead>
	<tbody id="p1_dataGrid"></tbody>
</table>

<table class="paginationTbl">
<div id="t2_loadmessage" div align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
	<tbody>
		<tr id="p1_pageFooter1" style="display: none;">
			<td id="p1_previous"></td>
			<td>Page<select id="p1_pageNoList" name="p1_pageNoList" class="pageNoList"></select><span id="p1_noOfPageSpan"></span></td>
			<td id="p1_next"></td>
		</tr>
		<tr id="p1_pageFooter2"><td colspan="3" class="emptyTR"></td>
		</tr>
        <tr>
        	<td colspan="3" class="emptyTR">
            	<input type="button" class="button" value="Submit" id="p1_submit" name="p1_submit">
                <input type="button" class="button" value="Exit" id="p1_exit" name="p1_exit">
            </td>
		</tr>
	</tbody>
</table>
</div>
</div>
</form>
</body>
</html>
