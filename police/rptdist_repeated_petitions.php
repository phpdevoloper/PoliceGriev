<?php
ob_start();
session_start();
include("db.php");
include("header_report.php");
include("header_menu_report.php");
include("common_date_fun.php");


if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	echo "<script> alert('Timed out. Please login again');self.close();</script>";	
	exit;
}
include("pm_common_js_css.php");
?>
<link rel="stylesheet" href="css/petpopup.css" type="text/css">
<script type="text/javascript">
function openPetitionStatusReport(petition_id){
	openForm("p_PetitionProcessDetails.php?petition_id="+petition_id, "pp_status");
}

function petition_status(pet_no)
{ 
 	document.getElementById("petition_id").value=pet_no;
	document.rpt_abstract.target = "Map";
    document.rpt_abstract.method="post";  
    document.rpt_abstract.action = "p_PetitionProcessDetails.php";
   	map = window.open("", "Map", "status=0,title=0,fullscreen=yes,scrollbars=1,resizable=0");
	if(map){
		document.rpt_abstract.submit();
 	}  
	return false; 
} 
function showMessage(pet_id) {
	document.getElementById('popup_'+pet_id).style.display='';
	var popup = document.getElementById('popup_'+pet_id);
    popup.classList.toggle("show");
}

function hidePopup(pet_id) {
	document.getElementById('popup_'+pet_id).style.display='none';
}
</script>

<?php
if(stripQuotes(killChars($_POST['hid_yes']))!="")
	$check=stripQuotes(killChars($_POST['hid_yes']));
else
	$check=$_SESSION["check"];

$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rptdist_reports.php') order by ordering";

$res = $db->query($qry);

while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB">
<?php
$rep_src=stripQuotes(killChars($_POST["rep_src"]));
if(stripQuotes(killChars($_POST["from_date"]))!="")
	$from_date=stripQuotes(killChars($_POST["from_date"]));
else  
	$from_date=stripQuotes(killChars($_SESSION["from_date"]));
		
if(stripQuotes(killChars($_POST["to_date"]))!="")
	$to_date=stripQuotes(killChars($_POST["to_date"]));
else  
	$to_date=stripQuotes(killChars($_SESSION["to_date"]));

if(stripQuotes(killChars($_POST["gsrc"]))!="")
	$src_id=stripQuotes(killChars($_POST["gsrc"]));
else  
	$src_id=stripQuotes(killChars($_SESSION["gsrc"]));

if(stripQuotes(killChars($_POST["gsubsrc"]))!="")
	$sub_src_id=stripQuotes(killChars($_POST["gsubsrc"]));
else  
	$sub_src_id=stripQuotes(killChars($_SESSION["gsubsrc"]));

if(stripQuotes(killChars($_POST["gtype"]))!="")
	$gtypeid=stripQuotes(killChars($_POST["gtype"]));
else  
	$gtypeid=stripQuotes(killChars($_SESSION["gtype"]));

if(stripQuotes(killChars($_POST["gsubtype"]))!="")
	$gsubtypeid=stripQuotes(killChars($_POST["gsubtype"]));
else  
	$gsubtypeid=stripQuotes(killChars($_SESSION["gsubtype"]));

$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
$disp_officer_name=stripQuotes(killChars($_POST["disp_officer_name"]));


if ($grie_dept_id != "") {
	$griedept_id = explode("-", $grie_dept_id);
	$griedeptid = $griedept_id[0];
	$griedeptpattern = $griedept_id[1];
}

	
$grev_dept_condition = "";
if(!empty($grie_dept_id)) {
	$grev_dept_condition = " and (a.dept_id=".$griedeptid.") ";
}
	
$fromdate=explode('/',$from_date);
$day=$fromdate[0];
$mnth=$fromdate[1];
$yr=$fromdate[2];
$frm_dt=$yr.'-'.$mnth.'-'.$day;

$todate=explode('/',$to_date);
$day=$todate[0];
$mnth=$todate[1];
$yr=$todate[2];
$to_dt=$yr.'-'.$mnth.'-'.$day;

if(!empty($from_date)){
	$codn.=" AND petition_date::date  >= '".$frm_dt."'::date";
}
if(!empty($to_date)){
	$codn.=" AND petition_date::date  <= '".$to_dt."'::date";
}

if(!empty($from_date) && !empty($to_date) )
 {
	 $cond1.="a.petition_date::date < '".$frm_dt."'::date";
	 $cond2.="a.action_entdt::date < '".$frm_dt."'::date";
	 $cond3.=" and a.petition_date::date >='".$frm_dt."'::date and a.petition_date::date <='".$to_dt."'::date"; 
	 $cond4.="  and a.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
	 $cond5.="a.petition_date::date <= '".$to_dt."'::date";
	 $cond6.="a.action_entdt::date <= '".$to_dt."'::date";
 }
	 
$reporttypename = "";
if ($src_id != "") {
	$sql="select source_id,source_name from lkp_pet_source where source_id=".$src_id;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$sourcename=$row[1];
	$reporttypename = "Source: ".$sourcename;	
}

if ($sub_src_id != "") {
	$sql="select subsource_id,subsource_name from lkp_pet_subsource where subsource_id =".$sub_src_id;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$subsourcename=$row[1];
	if ($reporttypename == "") {
		$reporttypename = "Sub-Source: ".$subsourcename;
	} else {
		$reporttypename = $reporttypename.", Sub-Source: ".$subsourcename;
	}	
}
		
if ($gtypeid != "") {
	$sql="select griev_type_id,griev_type_name from lkp_griev_type where griev_type_id=".$gtypeid;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$gtypename=$row[1];
	if ($reporttypename == "") {
		$reporttypename = "Grievance Type: ".$gtypename;
	} else {
		$reporttypename = $reporttypename.", Grievance Type: ".$gtypename;
	}
}
		
if ($gsubtypeid != "") {
	$sql="select griev_subtype_id,griev_subtype_name from lkp_griev_subtype where griev_subtype_id=".$gsubtypeid;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$grievsubtypename=$row[1];
	if ($reporttypename == "") {
		$reporttypename = "Grievance Sub-Type: ".$grievsubtypename;
	} else {
		$reporttypename = $reporttypename.", Grievance Sub-Type: ".$grievsubtypename;
	}
}
		
if ($grie_dept_id != "") {
	$griedept_id = explode("-", $grie_dept_id);
	$griedeptid = $griedept_id[0];
	$dept_sql = "SELECT dept_id,dept_name,dept_tname FROM usr_dept where dept_id='$griedeptid'";
	$dept_rs=$db->query($dept_sql);
	$dept_row = $dept_rs->fetch(PDO::FETCH_BOTH);
	
	$dept_name= $dept_row[1]; 
	
	if ($reporttypename == "") {
		$reporttypename = "Grievance Dept.: ".$dept_name;
	} else {
		$reporttypename = $reporttypename.", Grievance Dept.: ".$dept_name;
	}
}	
if ($petition_type != "") {
	$pet_type_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type where pet_type_id=".$petition_type;
	$pet_type_rs=$db->query($pet_type_sql);
	$pet_type_row = $pet_type_rs->fetch(PDO::FETCH_BOTH);
	$pet_type_name= $pet_type_row[1]; 
	if ($reporttypename == "") {
		$reporttypename = "Petition Type: ".$pet_type_name;
	} else {
		$reporttypename = $reporttypename.", Petition Type: ".$pet_type_name;
	}
}

	$disp_officer_title = '';
	if ($disp_officer_name != '') {
		$disp_officer_title = 'Disposing Officer :&nbsp;&nbsp;'.$disp_officer_name;
	}

$src_condition = "";
	if(!empty($src_id)) {
		$src_condition = " and (a.source_id=".$src_id.")";
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		$src_condition = " and (a.source_id=".$src_id." and a.subsource_id=".$sub_src_id.")";
	}
	
	//Grev type and Grev Subtype Condition		
	$grev_condition = "";
	if(!empty($gtypeid)) {
		$grev_condition = " and (a.griev_type_id=".$gtypeid.")";
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		$grev_condition = " and (a.griev_type_id=".$gtypeid." and a.griev_subtype_id=".$gsubtypeid.")";	
	}
	$petition_type_condition = "";
	
	if(!empty($petition_type)) {
		$petition_type_condition = " and (a.pet_type_id=".$petition_type.")";
	}

$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));

if ($disposing_officer != "") {	
	
	$sql = "SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
			pet_accept, pet_forward, pet_act_ret, pet_disposal,  
			desig_coordinating, dept_id, dept_name, dept_tname, dept_pet_process, 
			off_level_pattern_id,  dept_coordinating,dept_off_level_pattern_id,

			off_level_dept_id,off_level_dept_name, off_pet_process, off_coordinating,off_level_id, 

			off_loc_id, off_loc_name, off_loc_tname, sup_off_loc_id1, 
			sup_off_loc_id2, off_hier, 
			off_hier[7] AS state_id, off_hier[9] AS zone_id, off_hier[11] AS range_id, 
			off_hier[13] AS district_id, off_hier[42] AS division_id, off_hier[44] AS subdivision_id, 
			off_hier[46] AS circle_id, user_name, off_desig_emp_name, off_desig_emp_tname,
			fr_date, to_date, enabling
			FROM vw_usr_dept_users_v_sup
			WHERE dept_user_id=".$disposing_officer;
			
		$userProfile = new UserProfile();
		$rs=$db->query($sql);
		$rowArr = $rs->fetch(PDO::FETCH_BOTH);
	
		$userProfile->setDept_user_id($rowArr['dept_user_id']);
		//DEPT. OFFICE LVL. DESIGN
		$userProfile->setDept_desig_id($rowArr['dept_desig_id']);
		$userProfile->setSys_admin($rowArr['sys_admin']);		
		$userProfile->setPet_accept($rowArr['pet_accept']);
		$userProfile->setPet_forward($rowArr['pet_forward']);
		$userProfile->setPet_act_ret($rowArr['pet_act_ret']);
		$userProfile->setPet_disposal($rowArr['pet_disposal']);		
		$userProfile->setDesig_coordinating($rowArr['desig_coordinating']);
		$userProfile->setS_Dept_desig_id($rowArr['s_dept_desig_id']);
		$userProfile->setOff_level_dept_id($rowArr['off_level_dept_id']);
		$userProfile->setOff_level_dept_name($rowArr['off_level_dept_name']);
		$userProfile->setOff_level_id($rowArr['off_level_id']);
		$userProfile->setOff_pet_process($rowArr['off_pet_process']);		
		$userProfile->setOff_coordinating($rowArr['off_coordinating']);
		$userProfile->setDept_id($rowArr['dept_id']);
		$userProfile->setDept_name($rowArr['dept_name']);
		$userProfile->setDept_pet_process($rowArr['dept_pet_process']);
		$userProfile->setOff_level_pattern_id($rowArr['off_level_pattern_id']);
		$userProfile->setDept_off_level_pattern_id($rowArr['dept_off_level_pattern_id']);
		$userProfile->setDept_coordinating($rowArr['dept_coordinating']);
		$userProfile->setOff_loc_id($rowArr['off_loc_id']);
		$userProfile->setOff_hier($rowArr['off_hier']);	
		
		$userProfile->setState_id($rowArr['state_id']);
		$userProfile->setZone_id($rowArr['zone_id']);
		$userProfile->setRange_id($rowArr['range_id']);
		$userProfile->setDistrict_id($rowArr['district_id']);
		$userProfile->setDivision_id($rowArr['division_id']);
		$userProfile->setSubDivision_id($rowArr['subdivision_id']);
		$userProfile->setCircle_id($rowArr['circle_id']);
		
		
		$userProfile->setOff_level_name($rowArr['off_level_name']);
		$userProfile->setOff_loc_name($rowArr['off_loc_name']);
		$proxy_profile = true;
	} else {
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
	}
if ($userProfile->getOff_level_id() == 7) {
	$griev_loc_condition=" state_id=".$userProfile->getState_id()."";
} else if ($userProfile->getOff_level_id() == 9) {
	$griev_loc_condition=" zone_id=".$userProfile->getZone_id()."";
} else if ($userProfile->getOff_level_id() == 11) {
	$griev_loc_condition=" range_id=".$userProfile->getRange_id()."";
} else if ($userProfile->getOff_level_id() == 13) {
	$griev_loc_condition=" griev_district_id=".$userProfile->getDistrict_id()."";
}

if ($userProfile->getOff_level_id() == 1) {
$sub_sql = "select a.petition_id from pet_master a where griev_district_id=-9999";
} else {
$sub_sql = "select petition_id from
(select a.petition_id, petition_date,

cast (lead(petition_date,1) OVER (PARTITION BY comm_district_id, comm_taluk_id, comm_rev_village_id, lower(trim(petitioner_name)), griev_type_id, griev_subtype_id, lower(trim(griev_key_words)) ORDER BY pet_entdt) as date) same_pet_date

from pet_master a 
inner join vw_usr_dept_users_v c on c.dept_user_id=a.pet_entby
where ".$griev_loc_condition.$userProfile->getOff_loc_id().$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$cond3."
) aa
where petition_date <> same_pet_date
union
select petition_id from
(select a.petition_id, petition_date,

cast (lead(petition_date,-1) OVER (PARTITION BY comm_district_id, comm_taluk_id, comm_rev_village_id, lower(trim(petitioner_name)), griev_type_id, griev_subtype_id, lower(trim(griev_key_words)) ORDER BY pet_entdt) as date) same_pet_date

from pet_master a 
inner join vw_usr_dept_users_v c on c.dept_user_id=a.pet_entby
where ".$griev_loc_condition.$userProfile->getOff_loc_id().$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$cond3.") aa
where petition_date <> same_pet_date";
}


//echo $sub_sql;

$sql="  
select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period,pet_type_name ,comm_mobile
		from fn_petition_details(array(".$sub_sql.")) order by 
		pet_address,comm_mobile,petition_id,gri_address, griev_type_id, griev_subtype_id
		"; 
	//echo $sql;	
$result = $db->query($sql);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
$row_cnt = $result->rowCount();

?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">
	<table class="rptTbl">
	<thead>
    <tr id="bak_btn"><th colspan="14" ><a href="" onclick="self.close();"><img src="images/bak.jpg" /></a></th></tr>
    <tr><th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_dept_name()." - ". $userProfile->getOff_loc_name();?></th></tr>
	
		<?php if ($disp_officer_title != '') { ?>
		<tr><th colspan="14" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
		<?php } ?>
		
    <tr><th colspan="14" class="main_heading"><?PHP echo $label_name[75];//Reapted Petitions?> &nbsp;&nbsp;&nbsp;(<?PHP echo $label_name[49];//Reapted Petitions?> : <?php echo $row_cnt;?> ) </th></tr>
	<?php if($reporttypename!="") { ?>
	<tr>
	<th colspan="14" class="sub_heading"><?php echo $reporttypename;?></th>
	</tr>
	<?php } ?>	
	<tr><th colspan="14" class="search_desc">From Date: <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;To Date: <?php echo $to_date; ?></th></tr>
	<tr>
		<th><?PHP echo $label_name[22]; //S.No.?></th>
		<th><?PHP echo $label_name[79].', '.$label_name[27]; //Petition No. & Date; ?></th>
		<th><?PHP echo $label_name[28]; //Petitioner's communication address?></th>
		<th><?PHP echo $label_name[29].' & '.$label_name[30];  //Source & Sub Source?></th>
		<th><?PHP echo $label_name[31]; // Grievance?></th>
		<th><?PHP echo $label_name[32];  //Grievance type & Address?></th>
		<th><?PHP echo $label_name[33];  //Action Type, Date & Remarks?></th>
		<th><?PHP echo $label_name[34];  //Pending Period?></th>
	</tr>
    </thead>
	<tbody>	
<?php 


if ($row_cnt != 0) {
$i=1;
foreach($rowarray as $row)
{
	if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
		$source_details = $row['source_name'].' & '.$row['subsource_name'];
	} else {
		$source_details = $row['source_name'];
	}
	//petitioner_initial, petitioner_name, father_husband_name
	$pet_address = $row['pet_address'];

	if ($row['griev_taluk_name']!= '' && $row['griev_rev_village_name']!='') {
		$griev_address = $row['griev_rev_village_name'].','.$row['griev_taluk_name'].','.$row['griev_district_name'];
	} else if ($row['griev_block_name']!= '' && $row['griev_lb_village_name']!='') {
		$griev_address = $row['griev_lb_village_name'].','.$row['griev_block_name'].','.$row['griev_district_name'];
	} else if ($row['griev_lb_urban_name']!= '') {
		$griev_address = $row['griev_lb_urban_name'].','.$row['griev_district_name'];
	} else if ($row['griev_division_name']!= '') {
		$griev_address = $row['griev_division_name'].','.$row['griev_district_name'];
	}
	
	$petition_date = $row['petition_date'];
	//echo $petition_date;
	$petitiondate=explode('/',$petition_date);
	//print_r($petitiondate);
	$day=$petitiondate[0];
	$mnth=$petitiondate[1];
	$yr=$petitiondate[2];
	
	$pet_dt=$day.'-'.$mnth.'-'.$yr;
	
	$grievance = $row['grievance'];
	if ($grievance != '')  {
		if (strlen($grievance) > 120) {
				$remark = substr($grievance,0,120);
				$linkforpopup = ' more..';
			} else {
				$remark = $grievance;
				$linkforpopup = '';
		}
	} else {
		$linkforpopup = '';	
	}
	$petition_id = $row['petition_id'];
	$msgid='popup_'.$row['petition_id'];
		
				
?>
	<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:14%;">
			<?php echo "Mobile No: ".$row['comm_mobile']."<br><br>"?>
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">	
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$pet_dt; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $pet_address //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:11%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:17%;white-space: normal;"> <?PHP echo $remark //ucfirst(strtolower($row[grievance])); ?>
			<a onclick="showMessage('<?php echo $petition_id; ?>')" style='cursor: pointer;' ><?php echo $linkforpopup; ?></a>
			<div class='popup'>
			
			<span   id=<?php echo $msgid; ?>  class='popuptext' name='popup' 
			 
			onclick='hidePopup(<?php echo $petition_id; ?>)'> 
			
			<?php echo $grievance; ?> </span></div>
			</td> 
			
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$griev_address."<br>".$row['pet_type_name']; ?></td>
            
<td class="desc" style="width:19%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
} else {
	echo "No actions taken so far";
}?>
</td>
            
<!--			<td class="desc"> <?PHP //if($row['action_type_name']!="") { echo $row[action_type_name].			",".$row[fwd_date]."&".$row[fwd_remarks].":"."&nbsp;".ucfirst(strtolower($row[off_location_design])); }?></td>-->
            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
            	
			
			<?php  
			
			
			
			?>
			
			<tr>
            <td colspan="14" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
			<input type="hidden" name="hid" id="hid" />
			<input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
			<input type="hidden" name="frdate" id="frdate"  />
			<input type="hidden" name="todate" id="todate" />
			<input type="hidden" name="dept" id="dept" />
			<input type="hidden" name="dept_name" id="dept_name" />
			<input type="hidden" name="dept_user_id" id="dept_user_id" />
			<input type="hidden" name="src_id" id="src_id" />
			<input type="hidden" name="sub_src_id" id="sub_src_id" />
			<input type="hidden" name="gtypeid" id="gtypeid" />
			<input type="hidden" name="gsubtypeid" id="gsubtypeid" />
			<input type="hidden" name="status" id="status" /> 
			<input type="hidden" name="petition_no" id="petition_no" />
			<input type="hidden" name="petition_id" id="petition_id" /> 
            </td></tr>
		<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo 'No Records Found';?>...</td>   </tr>
         </table>
         
        <?php } ?>
        </tbody>
        </table>
	</div>
</div>	
</form>
<?php 
include("footer.php");
?>
