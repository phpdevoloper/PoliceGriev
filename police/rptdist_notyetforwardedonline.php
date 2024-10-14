<?php
ob_start();
session_start();
include("db.php");
include("header_report.php");
include("header_menu_report.php");
include("common_date_fun.php");

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
	$codn.=" AND a.petition_date::date  >= '".$frm_dt."'::date";
}
if(!empty($to_date)){
	$codn.=" AND a.petition_date::date  <= '".$to_dt."'::date";
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

if(!empty($src_id)){
	$codn.= " AND a.source_id=".$src_id;
}

if(!empty($sub_src_id)){
	$codn.= " AND a.source_id=".$sub_src_id;
}

if(!empty($gtypeid)){
	$codn.= " AND a.griev_type_id=".$gtypeid;
}

if(!empty($gsubtypeid)){
	$codn.= " AND a.griev_subtype_id=".$gsubtypeid;
}

if(!empty($grie_dept_id)){
	$codn.= "  AND a.dept_id=".$grie_dept_id;
}

if(!empty($petition_type)) {
	
	$codn.= " and (a.pet_type_id=".$petition_type.")";	
}

$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	if ($disposing_officer != "") {
	
	$sql = "SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
			pet_accept, pet_forward, pet_act_ret, pet_disposal,  
			desig_coordinating, dept_id, dept_name, dept_tname, dept_pet_process, 
			off_level_pattern_id, dept_coordinating,

			off_level_dept_id, off_level_dept_name,off_level_dept_tname, 
			off_pet_process, off_coordinating,
			off_level_id, off_loc_id, off_loc_name, off_loc_tname, sup_off_loc_id1, 
			sup_off_loc_id2, off_hier, 
			off_hier[1] AS state_id, off_hier[2] AS district_id, off_hier[3] AS rdo_id, 
			off_hier[4] AS taluk_id, off_hier[5] AS firka_id, off_hier[6] AS block_id, off_hier[7] AS lb_urban_id, 		                    
			off_hier[8] AS rev_village_id, off_hier[10] AS division_id, off_hier[11] AS subdivision_id, 
			off_hier[12] AS circle_id, off_hier[13] AS subcircle_id, off_hier[14] AS unit_id,

			user_name, off_desig_emp_name, off_desig_emp_tname,
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
		$userProfile->setOff_level_id($rowArr['off_level_id']);
		$userProfile->setOff_pet_process($rowArr['off_pet_process']);		
		$userProfile->setOff_coordinating($rowArr['off_coordinating']);
		$userProfile->setDept_id($rowArr['dept_id']);
		$userProfile->setDept_name($rowArr['dept_name']);
		$userProfile->setDept_pet_process($rowArr['dept_pet_process']);
		$userProfile->setOff_level_pattern_id($rowArr['off_level_pattern_id']);
		$userProfile->setDept_coordinating($rowArr['dept_coordinating']);
		$userProfile->setOff_loc_id($rowArr['off_loc_id']);
		$userProfile->setOff_hier($rowArr['off_hier']);	
		$userProfile->setState_id($rowArr['state_id']);
		$userProfile->setDistrict_id($rowArr['district_id']); 		
		$userProfile->setOff_level_name($rowArr['off_level_dept_name']);
		$userProfile->setOff_loc_name($rowArr['off_loc_name']); 

		$proxy_profile = true;
	} else {
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
	}

$agri_sql = "SELECT a.dept_desig_id, case when (".$userProfile->getOff_loc_id()."=any(a.agri_districts)) then true else false end as agri from usr_dept_desig_disp_sources a where a.source_id=11";
	
$result = $db->query($agri_sql);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
foreach($rowarray as $row)
{
	$agri_desig = $row[dept_desig_id];
	$agri_dist = $row[agri];			
}
$agri_condition = "";
	$fwd_offr_cond='';
		
		if($userProfile->getDesig_coordinating() 
		&& $userProfile->getOff_coordinating() 
		&& $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2){
			$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,20)=20) ";
		} else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==2){
			$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
		} else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==10){
			$fwd_offr_cond=" AND a.griev_division_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
		} else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
			$fwd_offr_cond=" and coalesce(a.fwd_office_level_id,10)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id)) and dept_id=".$userProfile->getDept_id()."";
		} else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !$userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==7){
			$fwd_offr_cond=" and ((coalesce(a.fwd_office_level_id,10)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
		} else{
			$fwd_offr_cond=" and false ";
		}

		$sql_count = "SELECT count(*) FROM pet_master a WHERE source_id < 0 and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id ) 
		and fwd_office_level_id=".$userProfile->getOff_level_dept_id()."";
		
$result = $db->query($sql_count);
$row=$result->fetch(PDO::FETCH_BOTH);
$petcount=$row[0];
?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">
	<table class="rptTbl">
	<thead>
    <tr id="bak_btn"><th colspan="14" ><a href="" onclick="self.close();"><img src="images/bak.jpg" /></a></th></tr>
    <tr><th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name();?></th></tr>
	
			<?php if ($disp_officer_title != '') { ?>
		<tr><th colspan="14" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
		<?php } ?>
		
    <tr><th colspan="14" class="main_heading">Online Petitions - Yet to be Forwarded &nbsp;&nbsp;&nbsp;(No of Petitions : <?php echo $petcount;?> ) </th></tr>
	<?php if($reporttypename!="") { ?>
	<tr>
	<th colspan="14" class="sub_heading"><?php echo $reporttypename;?></th>
	</tr>
	<?php } ?>	
	<tr><th colspan="14" class="search_desc">From Date: <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;To Date: <?php echo $to_date; ?></th></tr>
	<tr>
		<th><?PHP echo $label_name[22]; //S.No.?></th>
		<th><?PHP echo $label_name[27]; //Petition No. & Date; ?></th>
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


if ($petcount != 0) {

	$sub_sql ="SELECT petition_id FROM pet_master a WHERE source_id < 0 and NOT EXISTS (
	SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id) and  
	fwd_office_level_id=".$userProfile->getOff_level_dept_id()."";
			
$sql="SELECT petition_id, petition_no, to_char(pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date, petitioner_initial, petitioner_name, father_husband_name, 
source_name, source_tname, subsource_name,griev_type_name, griev_subtype_name, grievance, 
survey_no, sub_div_no, comm_doorno, comm_aptmt_block, comm_street, comm_area,  comm_district_name, 
comm_taluk_name,comm_rev_village_name, coalesce(comm_pincode,griev_pincode)as comm_pincode, griev_district_name,griev_taluk_name,  
griev_rev_village_name, griev_block_name, griev_lb_village_name, griev_lb_urban_name,  
griev_lb_urban_type_name,griev_division_name, dept_name, pet_type_name,
age(now()::date::timestamp with time zone, petition_date::timestamp with time zone)::character varying as pending_period FROM vw_pet_master
where petition_id in (".$sub_sql.") order by petition_id";
//echo $sql;
$result = $db->query($sql);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
$i = 1;

foreach($rowarray as $row)
{
	if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
		$source_details = $row['source_name'].' & '.$row['subsource_name'];
	} else {
		$source_details = $row['source_name'];
	}
	$pet_address = $row['petitioner_name'].',<br>'.$row['comm_doorno'].', '.$row['comm_street'].', '.$row['comm_area'].',<br> Pincode - '.$row['comm_pincode'].'.';

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
			<td class="desc" style="width:16%;">
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">	
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$petition_date; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $pet_address //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<td class="desc wrapword" style="width:32%;white-space: normal;">
				<?php echo $grievance; ?>			
			</td>
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$griev_address."<br>".$row['pet_type_name']; ?></td>
            
<td class="desc" style="width:13%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
} else {
	echo "No actions taken so far";
}?>
</td>

            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
            	
			
<?php 
			$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();
			?>
			<tr><th colspan="14" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
			<tr>
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
