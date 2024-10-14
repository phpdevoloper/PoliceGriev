<?php
ob_start();
session_start();
$pagetitle="List of Grievance Sub Types";
include("header_menu.php");
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
	$("#p_search").click(function(){
		p2_loadGrid();
	});
		
	$("#p_clear").click(function(){
		p_clearSerachParams();
	});
	
});


function p_searchParams(){
	var param="&gtype="+$('#gtype').val();
	return param;
}

function p_clearSerachParams(){
	$('#gtype').val('');
	$('#p2_dataGrid').empty();
}

function p2_loadGrid(){
	$('#p2_dataGrid').empty();
	document.getElementById("loadmessage").style.display='';
	var param = "mode=p_search_gsubtype"+p_searchParams();
	

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
	if ($(xml).find('griev_subtype_code').length == 0) {
		alert("No records found for the given parameters");
	}
	$(xml).find('griev_subtype_code').each(function(i)
	{
		
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+(++$i)+"</id>"+
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_type_tname').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_subtype_name').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_subtype_tname').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_subtype_code').eq(i).text()+"</td>"+
		"</tr>");
		document.getElementById("dontprint1").style.display='';
		//$('#td_'+petition_id).append("<input type='radio' name='p2_id' onClick='javascript:petition_edit("+petition_id+")'/>");		
	});
	
}
function printAsPdf() {
	document.getElementById("header").style.visibility='visible'; 
	document.getElementById("menu").style.display='none';
	document.getElementById("gs_head").style.display='none';
	document.getElementById("usr_detail").style.display='none'; 
	//document.getElementById("footer").style.visibility='hidden';
	document.getElementById("dontprint1").style.visibility='hidden';
	window.print();
	document.getElementById("header").style.visibility='visible';  
	document.getElementById("menu").style.display='block';
	document.getElementById("usr_detail").style.display='block';
	//document.getElementById("footer").style.visibility='visible';
	document.getElementById("dontprint1").style.visibility='visible';
	document.getElementById("gs_head").style.display='';
	
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
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[12];//Petitions Processed By Us?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tbody>
      <tr id="gs_head">
      <td><b><?PHP echo $label_name[18];//Search Parameters?>:</b>&nbsp;&nbsp;
      
      <select name="gtype" id="gtype">
       <option value="">--Select Petition Main Category--</option>
                <?php 

		if ($userProfile->getOff_level_id() == 1) {
			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
			griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
			dept_id = ".$userProfile->getDept_id()." and ".$userProfile->getOff_level_id()."=any(off_level_id)
			ORDER BY griev_type_name";
		} else {
			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
			griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
			dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";
		}
				$res = $db->query($gre_sql);
				$row_arr = $res->fetchall(PDO::FETCH_ASSOC);
				foreach($row_arr as $row) {
					
					echo "<option value='".$row[griev_type_id]."'>".$row['griev_type_name']."</option>";	
				}
			?>
              </select>
     
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[2];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[3];//Clear?>" class="button"/>
      </td>
      </tr>
      </tbody>
      </table>
      <table class="existRecTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[19];?></th>

      </tr>
      </thead>
      </table>
      <table class="gridTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[4];//Sl. No?></th> 
      <th><?PHP echo $label_name[13];?></th>
      <th><?PHP echo $label_name[14];?></th>
      <th><?PHP echo $label_name[15];?></th>
      <th><?PHP echo $label_name[16];?></th>
      <th><?PHP echo $label_name[17];?></th>
     
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
      <td colspan="6" class="buttonTD">
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" /> 
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