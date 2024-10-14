<?PHP
session_start();
include("db.php");
include("UserProfile.php");

$mobile_number = $_REQUEST['mobile_number'];
if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	echo "<script> alert('Timed out. Please login again');</script>";
	echo '<script type="text/javascript">self.close();"</script>';
	exit;
}
function randomHex1($color) {
	//$color_arr=array('#dac7af','#bcbee2','#7fffd4','#ff7f50','#f5f5f5','#05f058','#fff68f','#3399ff','#fab5dd','#ff6666');
	//$color_arr=array('#ffcccc','#ffe5cc','#ffffcc','#e5ffcc','#ccffff','#cce5ff','#ccffff','#e5ccff','#ffccff','#fcdfff');
		$color_arr=array('#ffe5cc','#ecc5f0','#ffffcc','#e3e4fa','#e5ffcc','#ccffff','#dac7af','#e5e4e2','#ffcccc','#cce5ff');
	$col_pos=($color)%count($color_arr);
	return $color_arr[$col_pos];
}
function randomHex() {
	 $str = '#';
    for($i = 0 ; $i < 3 ; $i++) {
        $str .= dechex( rand(170 , 255) );
    }
    return $str; 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Office Designation Search</title>
<style>
.stop-scrolling {
  height: 100%;
  overflow: hidden;
}html, body {
    max-width: 100%;
    overflow-x: hidden;
}
</style>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="theme/css/jquery-ui.css">
  <script src="theme/js/jquery-ui.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function()
{	
	$("#p1_submit").click(function(){
		submitToDesign();
	});
	$("#p1_exit").click(function(){
		if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		confirm1=alertbox("Do you want to exit?",void_return);}else{
		confirm1=alertbox("No Petition Selected. Do you want to exit?",void_return);
		}
	});
	$("#submit_copy_all").click(function(){
		submit_copy_all();
	});
	$("#submit_copy_per").click(function(){
		submit_copy_per();
	});
	$("#p1_club").click(function(){
		var array = [];
		//var array_col = [];
        breakOut = false;
		
		$("input:checkbox[name=club_pet_id]:checked").each(function(){
			arrval=$(this).val();
			array.push(arrval);
			if(document.getElementById('pet_id_'+arrval).innerText!==document.getElementById('old_pet_id_'+arrval).innerText){
			if($('input:checkbox[name=club_pet_id]:checked').length>1){
				alert("One or more petition(s) is/are already clubbed.");
        breakOut = true;
        return false;
			} 
			}
		}); 	
				
		if(breakOut) {
			breakOut = false;
			return false;
		} 
		//const uniquecol = Array.from(new Set(array_col));
		//alert(array+'&'+array.length);
		//alert(uniquecol+'&'+uniquecol.length);
		if (array.length > 1){
			$arr=array;
			//.sort(function(a, b){return a - b});
			//alert($arr);
			var confirm = window.confirm("Do you want to club the selected petitions?");
		if (confirm == true) {
			/* if (uniquecol.length > 1){
			club_pet($arr);
			}else{
				alert("Petition(s) already clubbed.");
			} */
			club_pet($arr);
		}
		}else if (array.length == 1){
			var confirm = window.confirm("Do you want to unclub the selected petition from the group?");
		if (confirm == true) {
			$arr=array;
			//.sort(function(a, b){return a - b});
			club_pet($arr,'unclub');
		}
		}else{
		alert("Select Petition(s) using checkbox.");
	}
	});
});

function club_pet(arr,func=''){
	$.ajax({
		type: "post",
		url: "pm_petition_detail_entry_action.php",
		cache: false,
		data: {source_frm : 'club_pet',arr:arr,func:func},
		error:function(){ alert("Club Petition") },
		success: function(html){
	if(html.trim()!='First Petition'){
	if(html.trim()>1){
		pet1=html.trim();
		petition=pet1+" Petitions";
	}else if(html.trim()==0 || html.trim()==1){
		pet1=html.trim();
		petition='';
	}else{
		petition='';
	}
	if(petition!=''){
	message=petition+" Clubbed Succesfully.";
	}else if(html.trim()=='alert'){
	message='One or more petition(s) is/are already clubbed.';
	}else{
	message=pet1+' Petition Unclubbed Successfully.';
	}
	alert(message);
	//opener.document.getElementById('club_done').value='Petitioner';
    location.reload(true);
	}else{
		message="First petition in the group of linked petition cannot be unclubbed.";
		alert(message);
	}
		
		}
});
}
function alertbox(msg,fn_to_execute) {
	$('body').addClass('stop-scrolling');
    $("#dialog-confirm").html(msg);
    // Default value is dialog is closed: don't save.
    var Confirmed = false;
    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        //title: "Confirmation box",
        height: 'auto',
        width: 400,
        buttons: {
            "Yes": function () {
                // Confirm saving:
                Confirmed=true;
                $(this).dialog('close');
            },
                // Do not confirm saving:
                "No": function () {
                Confirmed=false;
                $(this).dialog('close');
            }
        },
        // Sace if "Yes" was pressed, otherwise, alert that it was not saved.
        close: function() {
			fn_to_execute(Confirmed);
	$('body').removeClass('stop-scrolling');
        }
    });
}

function old_pet(Confirmed){
	if (Confirmed == true) {
			opener.document.getElementById('old_pet_no').value='petitioner';
			opener.p1_returnPetionDetails(pet,'petitioner');
			Minimize();
		}else{
		opener.document.getElementById('old_pet_no').value='';
			opener.p1_returnPetionDetails(pet);
			Minimize();
		}
}

function void_return(data){
	if (data == true) {
		self.close();
	}
}
function submit_copy_all(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
	pet=$('input[name=petition_id]:checked', '#p1_petition_search').val();
	var confirm = alertbox("Is the Petition selected by you related to the new Petition to be entered?",old_pet);
	}else{
		alert("Select one Petition with radio button.");
	}
}

function submit_copy_per(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		opener.p1_returnPetionorPersonalDetails($('#mobile_number').val());
		Minimize();
	}else{
		alert("Select one Petition with radio button.");
	}
}

function submitToDesign(){
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		var confirm = window.confirm("Is the Petition selected by you related to the new Petition to be entered?");
		if (confirm == true) {
			opener.document.getElementById('old_pet_no').value='True';
			opener.p1_returnPetionDetails($('input[name=petition_id]:checked', '#p1_petition_search').val());//alert(opener.document.getElementById('old_pet_no').value);
		}else{
			var mobile_number = document.getElementById("mobile_number").value;opener.p1_returnPetionorPersonalDetails(mobile_number);
		}
			$('#old_pet_no').val('');
		
		//alert("petition_id::"+petition_id)
		Minimize();
	}
	else{
		var mobile_number = document.getElementById("mobile_number").value;
		//alert(mobile_number);
		var confirm = window.confirm("No petition is selected do you want to copy only the personal details?");
		if (confirm == true) {
			opener.p1_returnPetionorPersonalDetails(mobile_number);
			$('#old_pet_no').val('');Minimize();
		}
		
		
	}
}

function submitToExit() {
	var mobile_number = document.getElementById("mobile_number").value;
	//alert(mobile_number);
	if($('input[name=petition_id]:checked', '#p1_petition_search').val()>0){
		var confirm = window.confirm("Do you want to exit without any action?");
		if (confirm == true) {
			$('#old_pet_no').val('');
			Minimize();
		}
	} else {
		var confirm = window.confirm("No petition is selected do you want to copy only the personal details?");
		if (confirm == true) {
			opener.p1_returnPetionorPersonalDetails(mobile_number);
			$('#old_pet_no').val('');
		}
		Minimize();
	}
}
function openPetitionStatusReport1(petition_id){
	//alert();
	openForm("p_PetitionProcessDetails.php?petition_id="+petition_id, "pp_status","_blank","fullscreen=yes");
}
</script>
</head>
<body>
<form method="post" id="p1_petition_search">
<div class="contentMainDiv">
<div class="contentDiv">


<input type="hidden" name="mobile_number" id="mobile_number" value="<?PHP echo $_REQUEST['mobile_number']?>"/>
	<div id="dialog-confirm" style=" overflow-y: hidden;height:fit-content;"></div>
<table class="existRecTbl" style="border-top-style: solid;">
	<thead>
    <tr>
    	<th style="background-color: #BC7676; color: #FFFFFF; font-size: 150%;" colspan="2">Petitions already submitted by Mobile: <?php echo $_GET['mobile_number']; ?></th>
    </tr>
	</thead>
</table>
<table class="gridTbl">
	<thead>
		<tr>
			<th width="5%">Select to Edit</th>
			<th width="10%">Petition Number</th>
			<th>Petition Date</th>
			<th width="10%">Linking Petition Number</th>
			<th width="12%">Petitioner Name, Father Name and Address</th>
			<th width="8%">Grievance Type and Subtype</th>
			<th width="20%">Petition Detail</th>
			<th width="15%">Source</th>
			<th width="10%">Status</th>
			<th width="5%">Select to Club</th>
		</tr>
	</thead>
	<tbody id="p1_dataGrid">
	<?php 
		 $sql="SELECT a.petition_id, petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date, petitioner_initial, petitioner_name, father_husband_name, source_name, griev_type_name, griev_subtype_name, grievance, comm_doorno, comm_aptmt_block, comm_street, comm_area,comm_district_id, comm_district_name, comm_taluk_id, comm_taluk_name, comm_rev_village_id, comm_rev_village_name, comm_pincode, comm_mobile, COALESCE(org_petition_no,petition_no) as org_petition_no,  dept_name, pet_type_name,off_level_dept_name,(split_part(COALESCE(org_petition_no,petition_no), '/',3)) as org_pet_align,c.action_type_name,l_action_type_code FROM vw_pet_master a inner join pet_action_first_last b on a.petition_id=b.petition_id inner join lkp_action_type c on c.action_type_code=b.l_action_type_code where comm_mobile='".$mobile_number."' order by org_pet_align,petition_id";
		
		$result = $db->query($sql);
		//$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$temp_old_pet='';
		$temp_color='';
		$count_r=$result->rowCount();
		if($count_r>0){
		while($row = $result->fetch(PDO::FETCH_BOTH)) {
			$petition_id=$row["petition_id"];
			$petition_no=$row["petition_no"];
			$petition_date=$row["petition_date"];
			$petitioner_initial=$row["petitioner_initial"];
			$petitioner_name=$row["petitioner_name"];
			$father_husband_name=$row["father_husband_name"];
			$p_name=$petitioner_name.', '.$father_husband_name;
			$source_name=$row["source_name"];
			$griev_type_name=$row["griev_type_name"];
			$griev_subtype_name=$row["griev_subtype_name"];
			$grievance=$row["grievance"];
			$comm_doorno=$row["comm_doorno"];
			$comm_street=$row["comm_street"];
			$comm_area=$row["comm_area"];
			$comm_doorno=$row["comm_doorno"];
			$comm_district_id=$row["comm_district_id"];
			$comm_district_name=$row["comm_district_name"];
			$comm_taluk_id=$row["comm_taluk_id"];
			$comm_taluk_name=$row["comm_taluk_name"];
			$comm_rev_village_id=$row["comm_rev_village_id"];
			$comm_rev_village_name=$row["comm_rev_village_name"];
			$comm_pincode=$row["comm_pincode"];
			if($row["l_action_type_code"]=='A'){
			$action_type_name="<lim style='color:#118e11;font-weight:bolder;'>".$row["action_type_name"]."</lim>";
			}else if($row["l_action_type_code"]=='R'){
			$action_type_name="<lim style='color:#bd0505;font-weight:bolder;'>".$row["action_type_name"]."</lim>";
			}else{
			$action_type_name="Under Process";
			}
			$org_petition_no=$row["org_petition_no"];
			if($temp_old_pet!=$org_petition_no){
				$temp_old_pet=$org_petition_no;
				$color1=randomHex1($color);
				$color++;
				//$color1=randomHex();
			}
			$pet_type_name=$row["pet_type_name"];
			
			//.$comm_rev_village_name.', '.$comm_taluk_name.', '.$comm_district_name
			$comm_address = $comm_doorno.', '.$comm_street.', '.$comm_area.', '.'<br>Pincode- '.$comm_pincode.'.';
			
			if($temp_color==$color1){
			?>
				<tr style="background-color:<?php echo $color1;?>;border-top:hidden;">
			<?php
			}else{							  
			?>
			<tr style="background-color:<?php echo $color1;?>;">
				<?php
				$temp_color=$color1;
			}
			?>
		<td style="text-align:center;"><input type='radio' name='petition_id' value="<?php echo $petition_id; ?>"/></td>	
    	<td><a href="javascript:openPetitionStatusReport1(<?php echo $petition_id?>);" title='Petition Process Report'><p id='pet_id_<?PHP echo $petition_id; //Pincode?>'><?PHP echo $petition_no; //Pincode?></p></a></td>
    	<td><?PHP echo $petition_date; //Pincode?></td>
    	<td><p id='old_pet_id_<?PHP echo $petition_id; //Pincode?>'><?PHP echo $org_petition_no; //Pincode?></p></td>
    	<td><?PHP echo $p_name.', <br>'.$comm_address; //Pincode?></td>
    	<td><?PHP echo $griev_type_name.', '.$griev_subtype_name; //Pincode?></td>
    	<td><?PHP echo $grievance; //Pincode?></td>
    	<td><?PHP echo $source_name; //Pincode?></td>
    	<td><?PHP echo $action_type_name; //Pincode?></td>
		<td><input type='checkbox' name='club_pet_id' value="<?php echo $petition_id; ?>"/></td>
 	</tr>
		<?php	
		}			
	?>
	
	</tbody>
	</table>

<table class="paginationTbl">
<tbody>
<tr>
<td colspan="3" class="emptyTR">
<input type="button" class="button" value="Club / Unclub" id="p1_club" name="p1_club" style="float:left;left:10%;position: absolute;width:fit-content;" title="Select Two or more petition to club / Select One petition to unclub ">
<input type="button" class="button" value="To Copy Applicant Details" id="submit_copy_per" name="submit_copy_per" style="width:fit-content;" title="Select a Petition">&nbsp;
<input type="button" class="button" value="To Copy Applicant & Petition Details" id="submit_copy_all" name="submit_copy_all" style="width:fit-content;" title="Select a Petition">&nbsp;
<!--input type="button" class="button" value="Submit" id="p1_submit" name="p1_submit"-->
<input type="button" class="button" value="Exit" id="p1_exit" name="p1_exit">
 <input type="hidden" name="petition_id1" id="petition_id1" />
 <?php
}else{
	?>
	<td colspan="9" style="font-size:48px; text-align:center;"><?PHP echo "No Records Found "; //No Records Found ?></td>
	<tr><td colspan='9' class="btn">
	<input type="button" value="Exit" id="exit" name="exit" onclick='opener.focus();self.close();'></td></tr>
<?php
		}
?>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</form>
</body>
</html>
