<?php 
ob_start();
$pagetitle="Acknowledgement";
include("db.php");
include("header_menu.php");
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
include("common_date_fun.php");
include("pm_common_js_css.php");

?>
 
<script type="text/javascript">

$(document).ready(function()
{
	setDatePicker('p_from_pet_date');
	setDatePicker('p_to_pet_date');
	addDate();
	$("#p_search").click(function(){
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$('#p2_pageNoList').change(function(){
		p2_loadGrid($('#p2_pageNoList').val(), $('#p2_pageSize').val());
	});
	
	$('#p2_pageSize').change(function(){
		p2_loadGrid(1, $('#p2_pageSize').val());
	});
	
	$("#p_clear").click(function(){
		p_clearSerachParams();
	});
	
});	
function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if((unicode >=47 && unicode<57) ||(unicode >65 && unicode<123) || (unicode==8))
		return true;
	else
		return false;	
}

function checkPetNo(e) 
{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || 
		(unicode == 12 || (unicode>=33 && unicode <=40) || unicode == 45 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
}
	
function print_fun()
{
        pet_no1=$('#petition_no1').val();
		pet_no2=$('#petition_no2').val();
		pet_no3=$('#petition_no3').val();
		
		
		if($.trim(pet_no1)=='' && $.trim(pet_no2)=='' && $.trim(pet_no3)=='')
		{
			alert("Please enter aleast one petition number !");
			return false;
		}
	   else{
	   
		 var param="mode=check_petno"+"&pet_no1="+pet_no1+"&pet_no2="+pet_no2+"&pet_no3="+pet_no3;
		 
	   		$.ajax({
				type: "POST",
				datatype: "xml",
				url:"check_petno.php",
				data:param,
				beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				 //alert(xml);			
				 check_petno(xml);  
			},  
			error: function(e){  
				//alert('Error: ' + e);  
		    } 
	});//ajax end
		 
			 
          }  
     
} 
function check_petno(xml){
	 
	var status = $(xml).find('status').eq(0).text();
 
	if(status=='wrong'){
		alert("Atleast one petition no is found wrong.");
		return false;
	}
	else if(status=='true'){ 
		document.ackmnt.method="post";
		document.ackmnt.action = "ackmnt_print_page.php"
		document.ackmnt.target= "_blank";
		document.ackmnt.submit();
		return true;
	}
	else {
		alert("Yor are not authorized to generate acknowledgement atleast of these petitions.");
		return false;
	}

}

function openPetitionStatusReport(petition_id){
	document.getElementById("petition_id").value=petition_id;
	document.petition_process_by_us.target = "Map";
	document.petition_process_by_us.method="post";  
	document.petition_process_by_us.action = "p_PetitionProcessDetails.php";
	
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.petition_process_by_us.submit();
	}  
}


function p_searchParams(){
	$('#p2_dataGrid').empty();
	var param="&p_from_pet_date="+$('#p_from_pet_date').val();
	param+="&p_to_pet_date="+$('#p_to_pet_date').val();
	param+="&p_from_pet_act_date="+$('#p_from_pet_act_date').val();
	param+="&p_to_pet_act_date="+$('#p_to_pet_act_date').val();
	param+="&p_source="+$('#p_source').val();
	param+="&form_tocken="+$('#formptoken').val(); 
	return param;
}

function p_clearSerachParams(){
	document.ackmnt.action = "pm_ackmnt.php";
	document.ackmnt.submit();
}

function p2_loadGrid(pageNo, pageSize){
	document.getElementById("loadmessage").style.display='';

	var param = "mode=p_ack_search"
		+"&page_size="+pageSize
		+"&page_no="+pageNo
		+p_searchParams();
	

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
var i = 1;
function handleClick(cb) {
	if (cb.checked) {
		if (i==1) {
			$('#petition_no1').val(cb.value);
			i=i+1;
		} else if (i == 2) {
			$('#petition_no2').val(cb.value);
			i=i+1;
		} else if (i == 3) {
			$('#petition_no3').val(cb.value);
			i=i+1;
		} else if (i > 3) {
			cb.checked = false; 
			alert("You can not select more than three petitions at a time");
			return false;
		}
	} else if (!(cb.checked)) {
		$('#petition_no1').val("");
		$('#petition_no2').val("");
		$('#petition_no3').val("");
		$('input[type=checkbox]').attr('checked',false);
		i = 1;
	}
}
function clearAll() {
	$('#petition_no1').val("");
	$('#petition_no2').val("");
	$('#petition_no3').val("");
	$('input[type=checkbox]').attr('checked',false);
	i = 1;
}
function p_createGrid(xml){
	
	$('#p2_dataGrid').empty();
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	if ($(xml).find('pet_action_id').length == 0) {
		alert("No records found for the given dates");
	}
	$(xml).find('pet_action_id').each(function(i)
	{
		
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		var pet_no=$(xml).find('petition_no').eq(i).text();
		var source_name=$(xml).find('source_name').eq(i).text();
		var subsource_remarks=$(xml).find('subsource_remarks').eq(i).text();
		
		if (subsource_remarks == "") {
			source_name = source_name;
		} else {
			source_name = source_name + ", "+subsource_remarks;
		}
		
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+"<input type='checkbox' name='chk_p2_pet_action_id' id='chk_petition_id_"+pet_action_id+"' value='"+pet_no+"' onclick='handleClick(this);'>"+"</id>"+
		"<td>"+
			"<input type='hidden' name='p2_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"<input type='hidden' name='p2_petition_id' id='p2_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+
			
		$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			
		"</td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"</td>"+
		"<td>"+source_name +"</td>"+ 
		"<td>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+", "+$(xml).find('griev_subtype_name').eq(i).text()+ "<br>Address: "                     +$(xml).find('gri_address').eq(i).text()+"</td>"+
		"<td>"+$(xml).find('action_type_name').eq(i).text()+" on "+$(xml).find('fwd_date').eq(i).text()+ "<br>Remarks: "                     +$(xml).find('fwd_remarks').eq(i).text()+"</td>"+
				"</tr>");
	
	});
	
	var pageNo = $(xml).find('pageNo').eq(0).text();
	var pageSize = $(xml).find('pageSize').eq(0).text();
	var noOfPage = $(xml).find('noOfPage').eq(0).text();
	drawPagination('p2_pageFooter1', 'p2_pageFooter2','p2_pageSize', 'p2_pageNoList', 'p2_next', 'p2_previous', 'p2_noOfPageSpan', 'p2_loadGrid', pageNo, pageSize, noOfPage);
}

function addDate(){
	var date = new Date();
	var newdate = new Date(date);
	setDateFormat(date, "#p_from_pet_date");
	setDateFormat(date, "#p_to_pet_date");
}

</script>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";

$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";

$result = $db->query($query);

while($rowArr = $result->fetch(PDO::FETCH_BOTH)){

	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
	
}
?>

<form name="ackmnt" id="ackmnt" action="pm_ackmnt.php" method="post" style="background-color:#F4CBCB;">

<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[0]; //Acknowledgement ?></div></div></div> 	
	<div class="contentMainDiv" style="width:98%;margin:auto;">
	 <div class="contentDiv"> 
           <div id="alrtmsg" style="color:#FF0000; font-size:18px" align="center"> 
			<?PHP 
			if($_REQUEST['msg']=='incorrect'){
			echo 'Enter Valid Petition Number !';
			echo "<br>"."<br>";
			}
			?>
			</div>
	   <div class="div0" id="main1" role="main">
		<table class="formTbl">
		<tbody>
		<tr>
		
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 1"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;
		<input type="text" name="petition_no1" id="petition_no1" value="" maxlength="25" onKeyPress="return checkPetNo(event);"/> 
        </td>
		</tr>
		<tr>
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 2"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;
		<input type="text" name="petition_no2" id="petition_no2" value="" maxlength="25" onKeyPress="return checkPetNo(event);"/> 
        </td>
		</tr>
		<tr>
		<td style="text-align:center;"><b><?PHP echo $label_name[1]." 3"; //Clear ?></b><span class="star">*</span>&nbsp;&nbsp;
		<input type="text" name="petition_no3" id="petition_no3" value="" maxlength="25" onKeyPress="return checkPetNo(event);"/> 
        </td>
		
		</tr>
		<tr>
            <td colspan="2" class="btn" >
            <input type="button" name="view" id="view" value="<?PHP echo $label_name[2]; //View. ?>"  onClick="return print_fun();"/>            <input type="button" name="view" id="clear" value="<?PHP echo $label_name[27]; //Clear ?>"  onClick="return clearAll();"/>

			
            <input type="hidden" name="ackmnt_hid" id="ackmnt_hid">
			<?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            </td>
        </tr>
        
		</tbody>
		</table>
		
		<table class="existRecTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[32];//Petition Details?></th>
      <!--<th><?//PHP echo $label_name[11];//Existing Details?></th>-->
          <th><?PHP echo 'Page Size';//Page&nbsp;Size?>
          <select name="p2_pageSize" id="p2_pageSize" class="pageSize">
          <!--<option value="5" selected="selected">5</option>-->
          <option value="15" selected="selected">15</option>
          <option value="30">30</option>
          <option value="50">50</option>
          </select>
      </th>
      </tr>
      </thead>
      </table>
	  
		<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tbody>
      <tr>
       <th><?PHP echo $label_name[29]; //Search Parameters ?></th>
      <td><?PHP echo $label_name[28]; //Processing Period ?></td>
      <td class="from_to_dt">
        <?PHP echo $label_name[30]; //From Date ?>&nbsp;&nbsp;
          <input type="text" name="p_from_pet_date" id="p_from_pet_date" maxlength="12" style="width: 90px;"  
          onchange="return validatedate(p_from_pet_date,'p_from_pet_date'); "/>
          &nbsp;&nbsp;<?PHP echo $label_name[31]; //To Date ?>&nbsp;&nbsp;
          <input type="text" name="p_to_pet_date" id="p_to_pet_date" maxlength="12" 
          style="width: 90px;" onchange="return validatedate(p_to_pet_date,'p_to_pet_date'); "/>
      </td>

	  <td><?PHP echo $label_name[7]; //Grievance Type ?></td>
      <td>
          <select name="p_source" id="p_source">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Source"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 

			$query= "-- petition form: sources combo
			SELECT DISTINCT(a.source_id), b.source_name, b.source_tname
			FROM usr_dept_desig_sources a
			JOIN lkp_pet_source b ON b.source_id = a.source_id
			WHERE a.dept_desig_id = ".$userProfile->getDept_desig_id()." order by b.source_name" ;	
			
          $result = $db->query($query);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          
		  foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
			  if($_SESSION["lang"]=='E'){
				echo "<option value='".$row[source_id]."'>".$row[source_name]."</option>";
			  }else{
				echo "<option value='".$row[source_id]."'>".$row[source_tname]."</option>";	
			  }
          }
		  
          ?>
          </select>
      </td>
      <td>
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[26]; //Search ?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[27]; //Clear ?>" class="button"/>
      </td>
      </tr>
      </tbody>
      </table>
	 
	<table class="gridTbl">
      <thead>
      <tr>
      <th style="width:3%;"><?PHP echo $label_name[33]; //Select ?></th>
      <th style="width:14%;"><?PHP echo $label_name[6]; //Petition No and Date ?></th>
      <th style="width:18%;"><?PHP echo $label_name[19]; //Address ?></th>
      <th style="width:8%;"><?PHP echo $label_name[7]; //Source ?> & <?PHP echo $label_name[34]; //Source Remarks ?></th>
      <!--<th><?//PHP echo $label_name[34]; //Source Remarks ?></th>-->
      <th style="width:22%;"><?PHP echo $label_name[32]; //Clear ?></th>
     <th style="width:20%;"><?PHP echo $label_name[10]; //Grievance Type ?>, <?PHP echo $label_name[11]; //Sub Type ?>, <?PHP echo $label_name[19]; //Address ?></th>  
      <th style="width:15%;"><?PHP echo $label_name[44]; //Last Action Type, Date & Remarks ?></th>
      </tr>
      </thead>
      <tbody id="p2_dataGrid"></tbody>
      </table>
      <div id="loadmessage" align="center" style="display:none"><img src="images/wait.gif" width="100" height="90" alt=""/></div>
      <table class="paginationTbl">
      <tbody>
      <tr id="p2_pageFooter1" style="display: none;">
      <td id="p2_previous"></td>
      <td><?PHP echo 'Page';//Page?><select id="p2_pageNoList" name="p2_pageNoList" class="pageNoList"></select>
      <span id="p2_noOfPageSpan"></span></td>
      <td id="p2_next"></td>
      </tr>
      <tr id="p2_pageFooter2"><td colspan="3" class="emptyTR"></td>
      </tr>
      <?php
      $ptoken = md5(session_id() . $_SESSION['salt']);
      $_SESSION['formptoken']=$ptoken;
      ?>
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
      <input type="hidden" name="petition_id" id="petition_id" />
      </tbody>
      </table>
	  
		</div>
</form>
<?php
include('footer.php');
?>
