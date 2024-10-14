<?php
ob_start();
session_start();
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   echo "Timed out. Please login again";
   header("Location: logout.php");
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
$pagetitle="View Petition Status";
include("db.php");
include("header_menu.php");
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
 
include("common_date_fun.php");
include("pm_common_js_css.php");
?>
<style type="text/css">
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
	color:#FF0000;

}
</style>

<script type="text/javascript">

$(document).ready(function(){
	setDatePicker('dob');
 });
 
function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9 && unicode !=47)
	{
		if(unicode<48||unicode>57)
		return false
	}
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
<form name="view_status" id="view_status" action="pm_view_petition_status.php" method="post">
		<div id="dontprint">
		<div class="form_heading">
		<!--<div class="heading"><?//PHP echo $label_name[0]; //View Petition Status ?></div></div></div> -->
        <div class="heading"><?PHP echo $label_name[24]; //Petition No ?></div></div></div> 	
		<div class="contentMainDiv">
		<div class="contentDiv">
        <div id="alrtmsg" style="color:#FF0000" align="center">
          
		</div>
       
		<table class="formTbl">
		<tbody>
		
		<tr>
		<td colspan="2" style="text-align:center"><input name="status_type" id="status_type" type="radio" value="s" checked=checked/>
		<?PHP echo $label_name[25]; //Petition No ?>
        <input name="status_type" id="status_type" type="radio" value="d" />
		<?PHP echo $label_name[26]; //Petition No ?> </td>
		</tr>
		
		<tr>
		<td width="30%" align="right"><?PHP echo $label_name[1]; //Petition No ?> <span class="star">*</span></td>
		<td> 
		<input type="text" name="petition_no" id="petition_no" value=""  data-error="Enter Petition No." 
        maxlength="25" onKeyPress="return checkPetNo(event);" /> </td>
		</tr>
		<tr><td colspan="2"  class="btn">
		<input type="button" name="view" id="view" value="<?PHP echo $label_name[2]; //View ?>" onClick="return print_fun();" />
		<input type="hidden" name="view_hid" id="view_hid">
         <?php
          $ptoken = md5(session_id() . $_SESSION['salt']);
		  $_SESSION['formptoken']=$ptoken;
        ?>
        <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
		<input type="hidden" name="stype" id="stype" value="" />
        </td></tr>
        </tbody>
		</table>
		 
 </div>
 </div>
 <?php //include("footer.php"); ?>
<?php //} ?>
 
 

<?php
 if(isset($_POST["view"]))
 {
	 
	$petition_no=stripQuotes(killChars($_POST["petition_no"]));

	 
	$codn=" petition_no='".$petition_no."'";
	

	$status=false;
 	
		 $query = "
		SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_name, father_husband_name, gender_name, 
		TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name, griev_type_name, griev_subtype_name, grievance, canid, comm_doorno, 
		comm_aptmt_block, comm_street, comm_area, griev_district_name, griev_taluk_name, griev_rev_village_name, comm_pincode, comm_email, 
		comm_phone, comm_mobile, griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_tname, griev_taluk_tname, 
		griev_rev_village_tname, griev_block_name, griev_lb_village_name, griev_lb_urban_name, griev_pincode FROM vw_pet_master WHERE ".$codn;  
			
		
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
if(sizeof($rowarray)!=0)
{
	 
		?>
		<div class="form_heading">
		<div class="heading">
		<?PHP echo $label_name[3]; //Petition Details ?>
		</div>
		</div>
        <div class="contentMainDiv">
		<div class="contentDiv">
		<table class="viewTbl">
		<tbody>

		<tr>
		<td colspan="6" class="sub_heading"><?PHP echo $label_name[4]; //Grievance Details ?> </td>
		</tr>
		
		<?PHP foreach($rowarray as $row)
		{
		$status=true;
		
		?>
		<tr>
		<td><?PHP echo $label_name[5]; //Petition No. & Date ?></td>
		<td><b><?php echo $row['petition_no'].' & Dt. '.$row['petition_date']; ?></b></td>
		<td><?PHP echo $label_name[6]; //Source Name ?></td>
		<td><?php echo $row['source_name'];?></td>	
		<td><?PHP echo $label_name[7]; //CAN ID ?></td>
		<td><b><?php echo  $row['canid'];?></b></td>
		</tr>
		<tr>
		<td><?PHP echo $label_name[8]; //Grievance/ Request ?></td>
		<td colspan="6"><b><?php echo $row['grievance'];?></b></td>
		</tr>
		<tr>
		<td><?PHP echo $label_name[9]; //Grievance Type ?></td>
		<td><?php echo $row['griev_type_name'];?></td>
		<td><?PHP echo $label_name[10]; //Grievance Sub Type ?></td>
		<td colspan="3"><?php echo $row['griev_subtype_name'];?></td>		
		</tr>
		<?php
		if($row['taluk_name_1']!="")
		$taluk_block_urban=$row['taluk_name_1'];
		else if($row['block_name']!="")
		$taluk_block_urban=$row['block_name'];
		else
		$taluk_block_urban=$row['lb_urban_name'];
		
		if($row['rev_village_name_1']!="")
		$village_lbvillage=$row['rev_village_name_1'];
		else
		$village_lbvillage=$row['lb_village_name'];
		
		
		if($row['griev_aptmt_block']!="")
			$row['griev_aptmt_block']=",".$row['griev_aptmt_block'];
		if($row['griev_area']!="")
			$row['griev_area']=",".$row['griev_area'];
		if($row['griev_street']!="")
			$row['griev_street']=",".$row['griev_street'];
		?>
		<tr>
		<td><?PHP echo $label_name[11]; //Grievance Address ?></td>
	<td colspan="6"><?php echo $row['griev_doorno'].$row['griev_aptmt_block'].$row['griev_street'].$row['griev_area'].",".$village_lbvillage.",".$taluk_block_urban.",".$row['district_name_1'].",".$row['griev_pincode'].".";?>
		</td>
		</tr>
		<tr>
		<td colspan="6" class="sub_heading"><?PHP echo $label_name[12]; //Petitioner Details ?></td>
		</tr>
		
		<tr>
		<td><?PHP echo $label_name[13]; //Petitioner Name ?></td>
		<td><?php echo $row['petitioner_name'];?></td>
		<td><?PHP echo $label_name[14]; //Father / Spouse Name ?></td>
		<td><?php echo $row['father_husband_name'];?></td>
		<td><?PHP echo $label_name[15]; //Gender ?></td>
		<td><?php echo $row['gender_name'];?></td>		
		</tr>
		
		<tr>
		<td><?PHP echo $label_name[16]; //DOB ?></td>
		<td><?php echo $row['dob'];?></td>
		<td><?PHP echo $label_name[17]; //ID Proof Type ?></td>
		<td><?php echo $row['idtype_name'];?></td>
		<td><?PHP echo $label_name[18]; //ID Proof Number ?></td>
		<td><?php echo $row['id_no'];?></td>
		</tr>
		<?php
		if($row['comm_doorno']!="")
			$row['comm_doorno']=$row['comm_doorno'];
		if($row['comm_aptmt_block']!="")
			$row['comm_aptmt_block']=",".$row['comm_aptmt_block'];
		if($row['comm_street']!="")
			$row['comm_street']=",".$row['comm_street'];
		if($row['comm_area']!="")
			$row['comm_area']=",".$row['comm_area'];
		?>
		<tr>
		<td><?PHP echo $label_name[19]; //Address ?></td>
		<td colspan="6"><?php echo $row['comm_doorno'].",".$row['comm_street'].$row['comm_area'].",".$row['rev_village_name'].",".$row['taluk_name'].",".$row['district_name']."-".$row['comm_pincode'].".";?></td>
		 
		</tr>
		  
		<tr>
		<td><?PHP echo $label_name[20]; //Mobile Number ?></td>
		<td><?php echo $row['comm_mobile'];?></td>
		<td><?PHP echo $label_name[21]; //e-Mail ?></td>
		<td colspan="3"><?php echo $row['comm_email'];?></td>
		</tr>
        <tr>
    	<td><?PHP echo $label_name[22]; //Document ?></td>
        <td colspan="5">
        <?php
			$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row[petition_id]."')";
			$fetch_doc = $db->query($query_doc);
			$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
	
			
	?>
    <?php
		foreach($doc_row as $key){
		echo $key['doc_name'];
	?>
    	<img src="images/download.png" onclick="download_document(<?php echo $key['doc_id']; ?>)"/>
        
    <?php } ?><script>
					function download_document(url){
						//alert("http://10.163.30.9/ed_gdp_tnega/fileupload1.php?doc_id="+url);
						window.location.href="http://14.139.183.34/police/pm_petition_doc_download.php?doc_id="+url;
					}
				</script>
        </td>
    </tr> 
	     <tr><td style="text-align:center" colspan="6"> <input type="button" name="" id="dontprint1" value="<?PHP echo $label_name[23]; //Print?>" class="button" onClick="return printwindow()"></td></tr>
		<?PHP
		}

		?>
		</tbody>
		</table>
		<table class="gridTbl">
		<thead>
		<tr>
		<th colspan="6" class="emptyTR">
		Processing Details
		</th>
		</tr>
		<tr>
		<th>Action Taken Date & Time</th>
		<th>Action Type</th>
		<th>File No. & File Date</th>
		<th>Action Remarks</th>
		<th>Action Taken</th>
		<th>Addressed To </th>
		</tr>
		</thead>
		
		<tbody>
		<tr>
		<?php
		/*$query=" SELECT action_type_name, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name, off_level_name, dept_name, 
		CASE WHEN district_name IS NOT NULL THEN district_name
		WHEN rdo_name IS NOT NULL THEN rdo_name
		WHEN taluk_name IS NOT NULL THEN taluk_name
		WHEN block_name IS NOT NULL THEN block_name
		WHEN firka_name IS NOT NULL THEN firka_name
		WHEN lb_urban_name IS NOT NULL THEN lb_urban_name
		END AS location, 
		
		to_whom, dept_desig_name1, off_level_name1, dept_name1,
		CASE WHEN district_name1 IS NOT NULL THEN district_name1
		WHEN rdo_name1 IS NOT NULL THEN rdo_name1
		WHEN taluk_name1 IS NOT NULL THEN taluk_name1
		WHEN block_name1 IS NOT NULL THEN block_name1
		WHEN firka_name1 IS NOT NULL THEN firka_name1
		WHEN lb_urban_name1 IS NOT NULL THEN lb_urban_name1
		END AS location1
		FROM vw_pet_action
		WHERE ".$codn."
		ORDER BY action_entdt";*/
		  echo $query=" 

SELECT action_type_name, file_no, to_char(file_date, 'DD/MM/YYYY') as file_date, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
	action_entby, dept_desig_name, off_level_dept_name, dept_name, off_loc_name AS location,	
	to_whom, dept_desig_name1, dept_name1,	off_loc_name1 AS location1,
	cast (rank() OVER (PARTITION BY petition_id ORDER BY pet_action_id DESC)as integer) rnk
FROM vw_pet_actions a  	
WHERE petition_no='$petition_no' and ".$codn."";
 
		
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		
		foreach($rowarray as $row)
		{
		?>
		<td><?php echo $row['action_entdt'];?></td>     
		<td><?php echo $row['action_type_name'];?></td> 
		<td><?php echo !empty($row['file_no'])? '<b>'.$row['file_no'].'</b>'."<br>".$row['file_date'] : "";?></td>     
		<td><?php echo $row['action_remarks'];?></td>
		<td><?php echo $row['dept_desig_name'].',' .$row['off_level_dept_name'].',' .$row['location'];?></td>
		<td><?php echo $row['dept_desig_name1'].', ' .$row['location1'];?></td>
		</tr>
		
		<?php
		}
?>
        <tr>
		<td colspan="6" class="emptyTR"></td>
		</tr>
	 
        
        
		
<?php }
 else{?>
	 <div id="alrtmsg" style="color:#FF0000" align="center">
		 Enter Valid Petition Number </div>
		
<?php }
  
}?>
</div>
        </div>
</tbody>
		</table>
</form>
         <?php include("footer.php"); ?>