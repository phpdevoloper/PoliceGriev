<?php
ob_start();
session_start();
$pagetitle="Petition Processed By Us";
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
	setDatePicker('p_from_pet_date');
	setDatePicker('p_to_pet_date');
	setDatePicker('p_from_pet_act_date');
	setDatePicker('p_to_pet_act_date');	
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

function openPetitionStatusReport(petition_id){
	document.getElementById("petition_id").value=petition_id;
	document.petition_process_by_us.target = "Map";
	document.petition_process_by_us.method="post";  
	document.petition_process_by_us.action = "p_PetitionProcessDetails.php";
	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,maximizable=no,resizable=0");
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
	param+="&ptype="+$('#petition_type').val();
	param+="&gtype="+$('#gtype').val();
	param+="&pet_action="+$('#pet_action').val();
	param+="&form_tocken="+$('#formptoken').val(); 
	return param;
}

function p_clearSerachParams(){
	document.petition_process_by_us.action = "p_PetitionProcessedByUsForm.php";
	document.petition_process_by_us.target = "_self";
	document.petition_process_by_us.submit();
}
function generateReport() {
	document.petition_process_by_us.action = "p_PetitionProcessedByUsReport.php";
	document.petition_process_by_us.target= "_blank";
	document.petition_process_by_us.submit();
}
function p2_loadGrid(pageNo, pageSize){
	document.getElementById("loadmessage").style.display='';

	var param = "mode=p_search"
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

function p_createGrid(xml){
	
	$('#p2_dataGrid').empty();
	var actTypeCodeOption= "<option value=''>-- Select Action Type --</option>";
	if ($(xml).find('pet_action_id').length == 0) {
		alert("No records found for the given parameters");
	}
	$(xml).find('pet_action_id').each(function(i)
	{
		
		var pet_action_id = $(xml).find('pet_action_id').eq(i).text();
		var petition_id = $(xml).find('petition_id').eq(i).text();
		var action_entby = $(xml).find('action_entby').eq(i).text();
		$('#p2_dataGrid')
		.append("<tr>"+
		"<td>"+$(xml).find('rownum').eq(i).text()+"</id>"+
		"<td>"+
			"<input type='hidden' name='p2_pet_action_id' id='"+pet_action_id+"' value='"+pet_action_id+"'/>"+
			"Source: "+$(xml).find('source_name').eq(i).text()+
			"<br>Petition Type : <b>"+$(xml).find('pet_type_name').eq(i).text()+"<br><br>"+
			"<input type='hidden' name='p2_petition_id' id='p2_petition_id_"+pet_action_id+"' value='"+petition_id+"'/>"+	
			"<a href='javascript:openPetitionStatusReport("+petition_id+");' title='Petition Process Report'>"+
			$(xml).find('petition_no').eq(i).text()+"<br>Dt.&nbsp;"+ $(xml).find('petition_date').eq(i).text()+
			"</a>"+
		"</b></td>"+
		"<td>"+$(xml).find('pet_address').eq(i).text()+"<br>Mobile : <b>"+$(xml).find('mobile').eq(i).text()+"</b></td>"+
		//"<td>"+$(xml).find('source_name').eq(i).text()+'<br>'+$(xml).find('subsource_remarks').eq(i).text()+"</td>"+
		//"<td>"+$(xml).find('subsource_remarks').eq(i).text()+"</td>"+
		
		"<td>"+$(xml).find('griev_type_name').eq(i).text()+",<br> "+$(xml).find('griev_subtype_name').eq(i).text()+ 
		"<br><br><b>Petition: </b>"+$(xml).find('grievance').eq(i).text()+"</td>"+
		"<td style='text-align: left;'>"+$(xml).find('off_location_design').eq(i).text()+" : "+$(xml).find('action_type_name').eq(i).text()+" on "+$(xml).find('fwd_date').eq(i).text()+",<br>Remarks: "+$(xml).find('fwd_remarks').eq(i).text()+"</td>"+	
		
		"<td>"+$(xml).find('pend_period').eq(i).text()+"</td>"+
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
	setDateFormat(date, "#p_to_pet_date");
	
	newdate.setDate(newdate.getDate() - 7);
	var fromDate = new Date(newdate);
	setDateFormat(fromDate, "#p_from_pet_date");
}
function validatedate(inputText,elementid){
   
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
   
if(inputText.value.match(dateformat))  
{  
	  document.profile.inputText.focus();  
	  
	  var opera1 = inputText.value.split('/');  
	  var opera2 = inputText.value.split('-');  
	  lopera1 = opera1.length;  
	  lopera2 = opera2.length;  
	    
	  if (lopera1>1)  
	  {  
	  var pdate = inputText.value.split('/');  
	  }  
	  else if (lopera2>1)  
	  {  
	  var pdate = inputText.value.split('-');  
	  }  
	  var mm  = parseInt(pdate[0]);  
	  var dd = parseInt(pdate[1]);  
	  var yy = parseInt(pdate[2]);  
	    
	  var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];  
	  if (mm==1 || mm>2)  
	  {  
	  if (dd>ListofDays[mm-1])  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
	  if (mm==2)  
	  {  
	  var lyear = false;  
	  if ( (!(yy % 4) && yy % 100) || !(yy % 400))   
	  {  
	  lyear = true;  
	  }  
	  if ((lyear==false) && (dd>=29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  if ((lyear==true) && (dd>29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
}  
  else  
  {  
  	alert("Invalid date format!");  
    document.getElementById(elementid).value=""; 
    document.getElementById(elementid).focus(); 
  return false;  
  }  
}
</script>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
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
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[0];//Petitions Processed By Us?></div></div></div>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
<table class="searchTbl" style="border-top: 1px solid #000000;">
      <tbody>
	  <tr>
	  <th style="width:14%;"><?PHP echo $label_name[2];//Petition Period?></th>
	  <th style="width:14%;"><?PHP echo $label_name[5];//Processing Period?></th>
	  <th style="width:14%;"><?PHP echo $label_name[43];//Petition Type?></th>
	  <th style="width:14%;"><?PHP echo $label_name[8];//Source?></th>
	  <!--<th style="width:14%;"><?PHP //echo $label_name[29];//Department?></th>-->
	  <th style="width:14%;"><?PHP echo $label_name[27];//Petition Main Category?></th>
	  <!--<th style="width:14%;"><?PHP //echo $label_name[44];//Petitioner Community?></th>
	  <th style="width:14%;"><?PHP //echo $label_name[45];//Petitioner Special Category?></th>--> 
	  <th style="width:14%;"><?PHP echo 'Action Taken';//Processing Period?></th>
	 
	  </tr>
	  <tr>

	  </tr>
      <tr>
      <td class="from_to_dt">
        <?PHP echo $label_name[3];//From?>
          <input type="text" name="p_from_pet_date" id="p_from_pet_date" maxlength="12" style="width: 90px;"  
          onchange="return validatedate(p_from_pet_date,'p_from_pet_date'); "/>
          <?PHP echo $label_name[4];//To?>
          <input type="text" name="p_to_pet_date" id="p_to_pet_date" maxlength="12" 
          style="width: 90px;" onchange="return validatedate(p_to_pet_date,'p_to_pet_date'); "/>
      </td>
      <td class="from_to_dt">
      <?PHP echo $label_name[6];//From?>
          <input type="text" name="p_from_pet_act_date" id="p_from_pet_act_date" maxlength="12" style="width: 90px;" 
          onchange="return validatedate(p_from_pet_date,'p_from_pet_date'); "/>
          <?PHP echo $label_name[7];//To?>
          <input type="text" name="p_to_pet_act_date" id="p_to_pet_act_date" maxlength="12"
          style="width: 90px;" onchange="return validatedate(p_to_pet_act_date,'p_to_pet_act_date'); "/>
      </td>
	  <td>
		<select name="petition_type" id="petition_type" style="width:140px;">
            	<option value="">-- Select --</option>
                <?PHP 
					$query="SELECT pet_type_id, pet_type_name, pet_type_tname, enabling, ordering
									FROM lkp_pet_type order by pet_type_id";
					$result = $db->query($query);
					$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
					foreach($rowarray as $row){
						if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_name']."</option>";
						}else{
						echo "<option value='".$row['pet_type_id']."'>".$row['pet_type_tname']."</option>";	
						}
					}
				?>
            </select>
		</td>
      <td>
          <select name="p_source" id="p_source" style="width:140px;">
          <option value="">-- <?php if($_SESSION['lang']=='E'){ echo "Select Source"; }else{echo "தேர்ந்தெடு";} ?> --</option>
          <?PHP 
		  $query="SELECT source_id, source_name,source_tname FROM lkp_pet_source WHERE enabling ORDER BY source_name";			
						
          $result = $db->query($query);
          $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
          foreach($rowarray as $row){
          //echo "<option value='$row[source_id]'>$row[source_name]</option>";
					  if($_SESSION["lang"]=='E'){
						echo "<option value='".$row['source_id']."'>".$row['source_name']."</option>";
						}else{
						echo "<option value='".$row['source_id']."'>".$row['source_tname']."</option>";	
						}
          }
          ?>
          </select>
      </td>
      	  
	  
	  
	  <td>
          <select name="gtype" id="gtype" style="width:240px;">
          <option value="">-- Select Category --</option>
          <?PHP 

			$gre_sql = "SELECT DISTINCT(griev_type_id), griev_type_code, 
						griev_type_name, griev_type_tname FROM vw_usr_dept_griev_subtype WHERE 
						dept_id = ".$userProfile->getDept_id()." ORDER BY griev_type_name";	
			
			$gre_rs=$db->query($gre_sql);
			while($row = $gre_rs->fetch(PDO::FETCH_BOTH)) {
				$grename=$row["griev_type_name"];
				$gretname = $row["griev_type_tname"];
				if($_SESSION["lang"]=='E'){
				$gre_name=$grename;
				}else{
				$gre_name=$gretname;	
				}
				print("<option value='".$row["griev_type_id"]."' >".$gre_name."</option>");	
			}
          ?>
          </select>
      </td>
	  
	  <td>	
			<select name="pet_action" id="pet_action">
			<option value="">-- Select Action --</option>
			<?php
				$action_sql = "SELECT action_type_id, action_type_code, action_type_name,action_type_tname FROM lkp_action_type where action_type_code in ('A','R') order by action_type_id";
				$action_rs=$db->query($action_sql);
				while($action_row = $action_rs->fetch(PDO::FETCH_BOTH))
				{

					if($_SESSION["lang"]=='E')
					{
						$action_type_name=$action_row["action_type_name"];
					}else{
						$action_type_name=$action_row["action_type_tname"];
					}
					print("<option value='".$action_row["action_type_code"]."' >".$action_type_name."</option>");

				}
			?>
			</select>
		</td>	
	  <td colspan="3">
	  </td>
	  </tr>
	  <tr>
	  	  <td colspan="6">
          <input type="button" name="p_search" id="p_search" value="<?PHP echo $label_name[9];//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_report" onClick="generateReport();" value="<?PHP echo 'Report';//Search?>" class="button"/>
          <input type="button" name="p_search" id="p_clear" value="<?PHP echo $label_name[10];//Clear?>" class="button"/>
		</tr>  
      </td>
      </tbody>
      </table>
	  
      <table class="existRecTbl">
      <thead>
      <tr>
      <th><?PHP echo $label_name[24];//Petition Details?></th>
      <!--<th><?//PHP echo $label_name[11];//Existing Details?></th>-->
          <th><?PHP echo $label_name[12];//Page&nbsp;Size?>
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
      <table class="gridTbl">
      <thead>
      <tr>
      <th style="width:3%;"><?PHP echo $label_name[21];//Sl. No?></th>
      <th style="width:15%;"><?PHP echo $label_name[46];//Petition No. & Date?></th>
      <th style="width:13%;"><?PHP echo $label_name[47];//Petitioner's Communication Address?></th>
      <!--<th style="width:13%;"><?PHP //echo $label_name[15];//Source?> & <?PHP //echo $label_name[23]; //Source Remarks?></th>
      <th><?//PHP echo $label_name[23]; //Source Remarks?></th>-->
      <th style="width:14%;"><?PHP echo $label_name[48];//Grievance Type, Sub Type & Address?></th>
      <!--<th style="width:18%;"><?PHP echo $label_name[16];//Grievance?></th>-->
      <th style="width:17%;"><?PHP echo $label_name[18];//Action Type, Date & Remarks?></th>
      <th style="width:5%;"><?PHP echo $label_name[22];//Pending Period;?></th>
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
      <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
      <input type="hidden" name="petition_id" id="petition_id" />
      </tbody>
      </table>
      <div>  	
      
      </div>
      </div>
      </div>
</form>
      
<?php include("footer.php"); ?>