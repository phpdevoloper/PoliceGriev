<?PHP
session_start();
include("db.php");
include("UserProfile.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Office Designation Search</title>
<head>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-3.4.0.js"></script>
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
	
	//p1_loadGrid(1, $('#p1_pageSize').val());
});

function p1_loadGrid(pageNo, pageSize){
	document.getElementById("t2_loadmessage").style.display='';
    var param = "mode=p1_search"
			+"&off_level_id="+ $("#off_level_id").val()
			+"&off_level_dept_id="+ $("#off_level_dept_id").val()
			+"&dept_off_level_office_id="+ $("#dept_off_level_office_id").val()
			+"&dept_off_level_pattern_id="+ $("#dept_off_level_pattern_id").val()
			+"&page_size="+pageSize
			+"&page_no="+pageNo;
			
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "Get_all_officer_Form_Action.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			// we have the response 
			 //alert(xml);
			 p1_createGrid(xml);
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
	
	}
function searchById() {
		if ($("#district_id").val() == '') {
			alert("Plaese select a District");
			return false;
		} else {
			var pageNo=1;
			var pageSize= $('#p1_pageSize').val();
			var desig = '';
			var param = "mode=p1_search"
					+"&off_level_id="+ $("#off_level_id").val()
					+"&off_level_dept_id="+ $("#off_level_dept_id").val()
					+"&dept_off_level_office_id="+ $("#dept_off_level_office_id").val()
					+"&dept_off_level_pattern_id="+ $("#dept_off_level_pattern_id").val()
					+"&dept_id="+ $("#dept_id").val()
					+"&district_id="+ $("#district_id").val()
					+"&off_name="+ $("#off_name").val().toLowerCase()
					+"&page_size="+pageSize
					+"&page_no="+pageNo;	  
			$.ajax({
				type: "POST",
				dataType: "xml",
				url: "Get_all_officer_Form_Action.php",  
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
					alert('Enter valid Code');  
				}
			});//ajax end	
		}
		

		
}

function p1_createGrid(xml){
	document.getElementById("t2_loadmessage").style.display='none';
 	$('#p1_dataGrid').empty();
	if ($(xml).find('off_loc_id').length == 0) {
			alert("No offices found");
			//return false;
	}
	$(xml).find('off_loc_id').each(function(i)
	{
		$('#p1_dataGrid')
		.append("<tr id='tr_"+$(xml).find('off_loc_id').eq(i).text()+"'>"+
		"<td><input type='radio' name='off_loc_id' value='"+$(xml).find('off_loc_id').eq(i).text()+"'/></td>"+	
		"<td>"+$(xml).find('off_loc_name').eq(i).text()+"</td>"+
		"</tr>");
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	
	drawPagination('p1_pageFooter1', 'p1_pageFooter2','p1_pageSize', 'p1_pageNoList', 'p1_next', 'p1_previous', 'p1_noOfPageSpan', 'p1_loadGrid', pageNo, pageSize, noOfPage);
}

function submitToDesign(){
	if($('input[name=off_loc_id]:checked', '#p1_off_loc_design_search').val()>0){
		var off_level_id=$("#off_level_id").val();
		var open_form='<?PHP echo $_REQUEST['open_form']?>';
		var off_loc_name = $('#tr_'+$('input[name=off_loc_id]:checked', '#p1_off_loc_design_search').val()).find("td").eq(1).html() +" / "+
			$('#tr_'+$('input[name=off_loc_id]:checked', '#p1_off_loc_design_search').val()).find("td").eq(1).html();
		if(open_form=="P1"){
			opener.p1_returnDesignationSearchForConcerned( off_level_id, $('input[name=off_loc_id]:checked', '#p1_off_loc_design_search').val(), off_loc_name);
		}
		else{
			opener.p3_returnDesignationSearch( off_level_id, $('input[name=off_loc_id]:checked', '#p1_off_loc_design_search').val(), off_loc_name);
		}
		Minimize();
	}
	else{
		alert("Please select any one Location");	
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

function searchByAlpha(element) {
	var desig = element.id;
	//alert("desig::::"+desig);
	var pageNo=1;
	var pageSize= $('#p1_pageSize').val();
	$('#alpha').val(desig);

	var param = "mode=p1_search"
				+"&off_level_id="+ $("#off_level_id").val()
				+"&off_level_dept_id="+ $("#off_level_dept_id").val()
				+"&dept_off_level_office_id="+ $("#dept_off_level_office_id").val()
				+"&dept_off_level_pattern_id="+ $("#dept_off_level_pattern_id").val()
				+"&dept_id="+ $("#dept_id").val()
				+"&district_id="+ $("#district_id").val()
				+"&off_name="+ $("#off_name").val().toLowerCase()
				+"&loc_first="+ desig.toLowerCase()	
				+"&page_size="+pageSize
				+"&page_no="+pageNo;
		if ($("#district_id").val() != '') {
			$.ajax({
				type: "POST",
				dataType: "xml",
				url: "Get_all_officer_Form_Action.php",  
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
					alert('Enter valid Code');  
				}
			});//ajax end
		} else {
			alert("Please select a district");
			//return false;
		}
		
	
}
</script>
</head>
<body>
<form method="post" id="p1_off_loc_design_search">
<div class="contentMainDiv">
<div class="contentDiv">


<input type="hidden" name="off_level_id" id="off_level_id" value="<?PHP echo $_REQUEST['off_level_id']?>"/>
<input type="hidden" name="off_level_dept_id" id="off_level_dept_id" value="<?PHP echo $_REQUEST['off_level_dept_id']?>"/>
<input type="hidden" name="dept_off_level_office_id" id="dept_off_level_office_id" value="<?PHP echo $_REQUEST['dept_off_level_office_id']?>"/>
<input type="hidden" name="dept_off_level_pattern_id" id="dept_off_level_pattern_id" value="<?PHP echo $_REQUEST['dept_off_level_pattern_id']?>"/>
<input type="hidden" name="dept_id" id="dept_id" value="<?PHP echo $_REQUEST['dept_id']?>"/>

<input type="hidden" name="alpha" id="alpha"/>
<?
	$sql='select off_level_id from usr_dept_off_level where off_level_dept_id='.$_REQUEST['off_level_dept_id'].'';
	
	$rs=$db->query($sql);
	if(!$rs) {
		print_r($db->errorInfo());
		exit;
	}
	while($row = $rs->fetch(PDO::FETCH_BOTH)) {
		$off_level_id=$row["off_level_id"];
	}
	
	if ($off_level_id == 42) {
		$heading="DSP ";
	} else if ($off_level_id == 46) {
		$heading="Police Inspector ";;
	}
	$dept_off_level_pattern_id=$_REQUEST['dept_off_level_pattern_id'];
	$userProfile = unserialize($_SESSION['USER_PROFILE']); 
		//echo ">>>>>>>>>>>>>".$pattern;
		//exit;
	//echo $sql="select off_hier[13] as dist_id from vw_usr_dept_users_v_sup where dept_user_id=".$_SESSION['USER_ID_PK']."";
		//exit;
	
?> 
<table class="existRecTbl" style="border-top-style: solid;">
	<thead>
    <tr>
    	<th style="background-color: #BC7676; color: #FFFFFF; font-size: 150%;" colspan="2"><?php echo $heading; ?>Office Location Search</th>
    </tr>
    <tr>
    <td colspan="2" align="center">
	<span>
    <label><b>Select District</b></label>
	<select id="district_id" name="district_id">
	<?php if ($dept_off_level_pattern_id !=3 && $dept_off_level_pattern_id !=4 && $userProfile->getOff_level_id() != 13) { ?>
	<option value="">--Select--</option>
	<? } ?>
	<?php
		/*
		echo $sql="select off_hier[13] as dist_id from vw_usr_dept_users_v_sup where dept_user_id=".$_SESSION['USER_ID_PK']."";
		exit;
		$result=$db->query($sql);
		while($row = $result->fetch(PDO::FETCH_BOTH))
		{
			$dist_id=$row["dist_id"];			
		}
		*/
		if ($dept_off_level_pattern_id == 1 || $dept_off_level_pattern_id == 2) {
			if ($userProfile->getOff_level_id() == 7) { //State
				$sql="select district_id,district_name from mst_p_district where district_id>0 order by district_id";
			} else if ($userProfile->getOff_level_id() == 9) { //Zone
				$sql="select distinct a.district_id,a.district_name from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
				where c.zone_id=".$userProfile->getOff_loc_id();
			} else if ($userProfile->getOff_level_id() == 11) {
				$sql="select distinct a.district_id,a.district_name from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_range c on c.range_id=b.range_id  
				where c.range_id=".$userProfile->getOff_loc_id();
			} else if ($userProfile->getOff_level_id() == 13) {
				$sql="select district_id,district_name from mst_p_district where district_id=".$userProfile->getOff_loc_id()." order by district_id";
			}			
		} else if ($dept_off_level_pattern_id == 3) {
			$sql="select distinct a.district_id,a.district_name from mst_p_district a
			inner join mst_p_sp_division b on b.district_id=a.district_id
			inner join mst_p_sp_zone c on c.zone_id=b.zone_id
			where c.dept_off_level_pattern_id=3";	
		} else if ($dept_off_level_pattern_id == 4) {
			if ($userProfile->getOff_level_id() == 7) {
				$sql="select distinct a.district_id,a.district_name from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id
				where c.dept_off_level_pattern_id=4";
			} else if ($userProfile->getOff_level_id() == 9) {
				$sql="select a.district_id,a.district_name from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				inner join mst_p_sp_zone c on c.zone_id=b.zone_id  
				where c.zone_id=".$userProfile->getOff_loc_id();
			} else if ($userProfile->getOff_level_id() == 42) {
				$sql="select a.district_id,a.district_name from mst_p_district a
				inner join mst_p_sp_division b on b.district_id=a.district_id
				where b.division_id=".$userProfile->getOff_loc_id();
			}				
		} 
		
		$result=$db->query($sql);
		while($row = $result->fetch(PDO::FETCH_BOTH))
		{
			$district_id=$row["district_id"];
			$district_name=$row["district_name"];
			if ($dist_id==$district_id)
			print("<option value='".$district_id."' selected>".$district_name."</option>");
			else
			print("<option value='".$district_id."'>".$district_name."</option>");
		}
	?>
    </select>
	</span>
	&nbsp;&nbsp;&nbsp;
	<label><b>Enter Location Name</b></label>
    <input type="text"  name="off_name" id="off_name" value="" maxlength="20" style="width:175px;"/>
	
    <input type="button" name="search_off" id="search_off" value="Search" onclick="searchById()"/>
    </td>
    </tr>
	<tr>
	<td style="text-align:center;background-color:#FFFFFF;" colspan="2">
	<label style="color:black;"><b>Alphabetical Search for Location Name</b></label>&nbsp;&nbsp;&nbsp;&nbsp;
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
			<th>Office Location</th>
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
