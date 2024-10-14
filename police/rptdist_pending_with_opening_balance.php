<?php
ob_start();
session_start();
include("db.php");
include("header_report.php");
include("header_menu_report.php");
include("common_date_fun.php");
include("pm_common_js_css.php");

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
	echo "<script> alert('Timed out. Please login again');</script>";	
	header("Location: logout.php");
	exit;
}

if(stripQuotes(killChars($_POST['hid_yes']))!="")
	$check=stripQuotes(killChars($_POST['hid_yes']));
else
	$check=$_SESSION["check"];

if($check=='yes')
{
$pagetitle="Officers wise Pendency Report - Based on Petition Period";
?>
  
<script type="text/javascript">
function detail_view(frm_date,to_date,dept,dept_name,dept_user_id,dept_designation,status)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("dept_user_id").value=dept_user_id;
	document.getElementById("status").value=status;
	document.getElementById("dept_designation").value=dept_designation;
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";				
	document.rpt_abstract.action="rptdist_pending_with_opening_balance.php";
	document.rpt_abstract.target= "_blank";
	document.rpt_abstract.submit(); 
	return false;
}
</script>
<style>
.accepted {
color: #00BF00;
text-decoration-line: underline;
}
.rejected {
color: #FF0000;
text-decoration-line: underline;
}
.pending {
color: #CF0000;
text-decoration-line: underline;
}
</style>
<?php

$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rptdist_officerswise.php') order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
	$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name(); 
	if(stripQuotes(killChars($_POST["gsrc"]))!="")
		$src_id=stripQuotes(killChars($_POST["gsrc"]));
	else  
		$src_id=stripQuotes(killChars($_SESSION["gsrc"]));
	
	$proxy_profile = false;
	
	$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	
	if ($disposing_officer != "") {	
	
	$sql = "SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
			pet_accept, pet_forward, pet_act_ret, pet_disposal,  
			desig_coordinating, dept_id, dept_name, dept_tname, dept_pet_process, 
			off_level_pattern_id,  dept_coordinating,dept_off_level_pattern_id,

			off_level_dept_id, off_pet_process, off_coordinating,off_level_id, 

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
	
?>
 
<?php 
if(stripQuotes(killChars($_POST['hid']))=="") { ?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<?php
	$rep_src=stripQuotes(killChars($_POST["rep_src"]));
	
	if(stripQuotes(killChars($_POST["p_from_date"]))!="")
	$from_date=stripQuotes(killChars($_POST["p_from_date"]));
	else  
	$from_date=stripQuotes(killChars($_SESSION["p_from_date"]));
	
	if(stripQuotes(killChars($_POST["p_to_date"]))!="")
	$to_date=stripQuotes(killChars($_POST["p_to_date"]));
	else  
	$to_date=stripQuotes(killChars($_SESSION["p_to_date"]));
	
	$reporttypename = "";
		
		
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

	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$pet_community = stripQuotes(killChars($_POST["pet_community"]));	
	$special_category = stripQuotes(killChars($_POST["special_category"]));	
	$disp_officer_name=stripQuotes(killChars($_POST["disp_officer_name"]));
	
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
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
	if ($pet_community != "") {
		$pet_community_sql = "SELECT pet_community_id, pet_community_name, pet_community_tname FROM lkp_pet_community where pet_community_id=".$pet_community;
		$pet_community_rs=$db->query($pet_community_sql);
		$pet_community_row = $pet_community_rs->fetch(PDO::FETCH_BOTH);
		$pet_community_name= $pet_community_row[1]; 
		if ($reporttypename == "") {
			$reporttypename = "Petitioner Community: ".$pet_community_name;
		} else {
			$reporttypename = $reporttypename.", Petitioner Community: ".$pet_community_name;
		}
	}

	if ($special_category != "") {
		$pet_community_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category where petitioner_category_id=".$special_category;
		$pet_community_rs=$db->query($pet_community_sql);
		$pet_community_row = $pet_community_rs->fetch(PDO::FETCH_BOTH);
		$petitioner_category_name= $pet_community_row[1]; 
		if ($reporttypename == "") {
			$reporttypename = "Petitioner Special Category: ".$petitioner_category_name;
		} else {
			$reporttypename = $reporttypename.", Petitioner Special Category: ".$petitioner_category_name;
		}
	}
	$disp_officer_title = '';
	if ($disp_officer_name != '') {
		$disp_officer_title = 'Disposing Officer :&nbsp;&nbsp;'.$disp_officer_name;
	}

		
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (c.dept_id=".$griedeptid.") ";
	}
	
	$src_condition = "";
	if(!empty($src_id)) {
		$src_condition = " and (b.source_id=".$src_id.")";
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		$src_condition = " and (b.source_id=".$src_id." and b.subsource_id=".$sub_src_id.")";
	}
	
	//Grev type and Grev Subtype Condition		
	$grev_condition = "";
	if(!empty($gtypeid)) {
		$grev_condition = " and (b.griev_type_id=".$gtypeid.")";
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		$grev_condition = " and (b.griev_type_id=".$gtypeid." and b.griev_subtype_id=".$gsubtypeid.")";	
	}
	
	$petition_type_condition = "";
	
	if(!empty($petition_type)) {
		$petition_type_condition = " and (b.pet_type_id=".$petition_type.")";
	}
	
	$condition="";

	if (!empty($src_id)) {
		$condition.=" where b.source_id=".$src_id;
	}
	if (!empty($sub_src_id)) {
		$condition.=" and b.subsource_id=".$sub_src_id;
	}

	if(!empty($gtypeid)) {
		$condition.= ($condition== "")? " where b.griev_type_id=".$gtypeid : " and b.griev_type_id=".$gtypeid;
	}
	if (!empty($gsubtypeid)) {
		$condition.=" and b.griev_subtype_id=".$gsubtypeid;
	}
	if(!empty($grie_dept_id)) {
		$condition.= ($condition== "")? " where b.dept_id=".$griedeptid : " and b.dept_id=".$griedeptid;
	}
	if(!empty($petition_type)) {
		$condition.= ($condition== "")? " where b.pet_type_id=".$petition_type : " and b.pet_type_id=".$petition_type;
	}
	$pet_community_condition = '';
	if(!empty($pet_community)) {
		$condition.= ($condition== "")? " where b.pet_community_id=".$pet_community : " and b.pet_community_id=".$pet_community;
	}
	$special_category_condition = '';
	if(!empty($special_category)) {
		$condition.= ($condition== "")? " where b.petitioner_category_id=".$special_category : " and b.petitioner_category_id=".$special_category;
	}	
?>
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="12" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
			</th></tr>
			

            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
			

	
            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $label_name[72]; //Officers wise Pendency Report with Opening and Closing Balance?></th>
			</tr>

		<?php if ($disp_officer_title != '') { ?>
		<tr><th colspan="12" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
		<?php } ?>
		
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="12" class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="12" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="12" class="search_desc"><b><?PHP echo $label_name[73];?>  </b>&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="2"  style="width:3%;"><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="2"  style="width:20%;"><?PHP echo $label_name[40]; //Concerned Officer?></th>
                <th colspan="10" style="width: 70%;"><?PHP echo $label_name[4];//Number Of Petitions?></th>


				</tr>
				<tr>
                <th style="width:7%;"><?PHP echo $label_name[5]; //Opening Balance?><br>(A)</th>
                <th style="width:7%;"><?PHP echo $label_name[6]; //Received?><br>(B)</th>
				<th style="width:7%;"><?PHP echo $label_name[8]; //Accepted?><br>(C)</th>
				<th style="width:7%;"><?PHP echo $label_name[9]; //Rejected?><br>(D)</th>
				<th style="width:8%;"><?PHP echo $label_name[62].' - '.$userProfile->getDept_desig_name(); //Pending with Disposing Officer?><br>(E)</th>
                <th style="width:7%;"> <?PHP echo $label_name[67] //Pending with others?><br>(F)</th>
        	 	<th style="width:8%;"> <?PHP echo $label_name[61];; //Pending with Concerned Officer?><br>(G)</th>
				
				<th style="width:8%;"> <?PHP echo $label_name[14];; //Pending > 30 and <= 60 Days?><br>(H)</th>
				<th style="width:8%;"> <?PHP echo $label_name[12];; //Pending > 60 Days?><br>(I)</th>
				<th style="width:8%;"> <?PHP echo $label_name[13];; //Pending with Others?><br>(J)</th>
         		</tr>
			
            </thead>
            <tbody>            
			<?php 
			
		$i=1;
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
					
		$dist_cond='';
		$dist_params_1 = '';
		$dist_params_2 = '';
		
		//echo "11111";
		
		if ($userProfile->getOff_level_id()==7 && ($userProfile->getDept_off_level_pattern_id() == null || $userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {			
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.state_id, aa.state_name, aa.district_id, aa.district_name, ';
			$dist_params_2 = "select a1.state_id, a1.state_name, a2.district_id, a2.district_name from mst_p_state a1 inner join mst_p_district a2 on a2.state_id=a1.state_id where a1.state_id=".$userProfile->getState_id()."
			union
			select a1.state_id, a1.state_name, null as district_id, 'State' as district_name from mst_p_state a1 where a1.state_id=".$userProfile->getState_id()."";
			$join_condition=" aa.district_id=a.off_hier[2] ";
		}
		else if ($userProfile->getOff_level_id()==9) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.zone_id, aa.zone_name, ';
			$dist_params_2 = "select a1.state_id, a1.state_name, a2.zone_id, a2.zone_name from mst_p_state a1 inner join mst_p_sp_zone a2 on a2.state_id=a1.state_id where a2.zone_id=".$userProfile->getZone_id()."";
			$join_condition=" aa.zone_id=a.off_hier[9] ";
		}
		else if ($userProfile->getOff_level_id()==11 && ($userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.range_id, aa.range_name, ';
			$dist_params_2 = "select a1.state_id, a1.state_name, a2.range_id, a2.range_name from mst_p_state a1 inner join mst_p_sp_range a2 on a2.state_id=a1.state_id where a2.range_id=".$userProfile->getRange_id()."";
			$join_condition=" aa.range_id=a.off_hier[11] ";
		}
		else if ($userProfile->getOff_level_id()==13
		// && ($userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2)
			) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.district_id, aa.district_name, ';
			$dist_params_2 = "select a1.state_id, a1.state_name, a2.district_id, a2.district_name from mst_p_state a1 inner join mst_p_district a2 on a2.state_id=a1.state_id where a2.district_id=".$userProfile->getDistrict_id()."";
			$join_condition=" aa.district_id=a.off_hier[13] ";
		}
		else if ($userProfile->getOff_level_id()==42) //&& ($userProfile->getDept_off_level_pattern_id() == 3 || $userProfile->  getDept_off_level_pattern_id() == 4)) 
		{	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.division_id, aa.division_name, ';
			$dist_params_2 = "select a1.district_id, a1.district_name, a2.division_id, a2.division_name from mst_p_district a1 inner join mst_p_sp_division a2 on a2.district_id=a1.district_id where a2.division_id=".$userProfile->getDivision_id()."";
			$join_condition=" aa.division_id=a.off_hier[42] ";
		}
		else if ($userProfile->getOff_level_id()==46) //&& ($userProfile->getDept_off_level_pattern_id() == 3 || $userProfile->  getDept_off_level_pattern_id() == 4)) 
		{	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.circle_id, aa.circle_name, ';
			$dist_params_2 = "select a1.district_id, a1.district_name, a3.circle_id, a3.circle_name from mst_p_district a1 inner join mst_p_sp_division a2 on a2.district_id=a1.district_id inner join mst_p_sp_circle a3 on a2.division_id=a3.division_id where a3.circle_id=".$userProfile->getCircle_id()."";
			$join_condition=" aa.circle_id=a.off_hier[46] ";
		}
		/*
		else if ($userProfile->getOff_level_id()==3) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.rdo_id, aa.rdo_name, ';
			$dist_params_2 = "select a1.rdo_id, a1.rdo_name, a2.district_id from mst_p_rdo a1 inner join mst_p_district a2 on a2.district_id=a1.district_id where a1.rdo_id=".$userProfile->getRdo_id()."";
		}
		else if ($userProfile->getOff_level_id()==4) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.taluk_id,aa.taluk_name,';
			$dist_params_2 = "select a1.taluk_id, a1.taluk_name, a2.district_id from mst_p_taluk a1 inner join mst_p_district a2 on a2.district_id=a1.district_id where a1.taluk_id=".$userProfile->getTaluk_id()."";
		}	
		else if ($userProfile->getOff_level_id()==6) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';
			$dist_params_1 = 'aa.block_id,aa.block_name,';			
			$dist_params_2 = "SELECT a1.block_id, a1.block_name,a2.district_id FROM mst_p_lb_block a1
			inner join mst_p_district a2 on a2.district_id=a1.district_id where a1.block_id=".$userProfile->getBlock_id()."";	
		}
		else if ($userProfile->getOff_level_id()==10) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';	
			$dist_params_1 = 'aa.division_id,aa.division_name,';
			$dist_params_2 = "SELECT a1.division_id, a1.district_id, a1.division_name  FROM mst_p_sp_division a1
			inner join mst_p_district a2 on a2.district_id=a1.district_id where a1.division_id=".$userProfile->getDivision_id()."";			
		}
		else if ($userProfile->getOff_level_id()==11) {	
			$dist_cond='(SELECT c.dept_id, c.off_level_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, c.off_hier, c.off_loc_name, c.dept_desig_name, c.dept_desig_tname from vw_usr_dept_users_vh c)';	
			$dist_params_1 = 'aa.subdivision_id,aa.subdivision_name,';
			$dist_params_2 = "SELECT a1.subdivision_id, a1.district_id, a1.subdivision_name  FROM mst_p_sp_subdivision a1
			inner join mst_p_district a2 on a2.district_id=a1.district_id where a1.subdivision_id=".$userProfile->getDivision_id()."";
		}		
		*/
		$sql="With pending_pet as 
		(select * from 
		( 
		select off_level_dept_id, dept_desig_id, dept_desig_name, off_loc_id, off_loc_name, dept_user_id, off_hier,  ob_cnt, recd_cnt, acp_cnt, rjct_cnt, no_act_cnt, oth_pend_cnt, cl_pend_cnt, cl_pend_leq30d_cnt, cl_pend_gt30leq60d_cnt, cl_pend_gt60d_cnt 
		from 

		( select cc.off_level_dept_id, cc.dept_desig_id, cc.dept_desig_name, cc.off_loc_id, cc.off_loc_name, cc.dept_user_id, off_hier, 
		COALESCE(ob.ob_cnt,0) as ob_cnt, 
		COALESCE(recd.recd_cnt,0) as recd_cnt, 
		COALESCE(acp.acp_cnt,0) as acp_cnt, 
		COALESCE(rjct.rjct_cnt,0) as rjct_cnt, 
		COALESCE(no_act.no_act_cnt,0) as no_act_cnt, 
		COALESCE(oth.oth_pend_cnt,0) as oth_pend_cnt, 
		COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt, 
		COALESCE(clb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, 
		COALESCE(clb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, 
		COALESCE(clb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt 
		from 

		(".$dist_cond.") cc 
		
		left join -- opening balance 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as ob_cnt 
		from fn_pet_action_first_last_pndg_notAR_cb_dt_from('".$frm_dt."'::date,".$userProfile->getDept_user_id().") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom ".$condition." 
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) ob on ob.dept_id=cc.dept_id and ob.off_level_dept_id=cc.off_level_dept_id and ob.off_loc_id=cc.off_loc_id and ob.dept_desig_id=cc.dept_desig_id and ob.dept_user_id=cc.dept_user_id 

		left join -- received for action 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as recd_cnt 
		from fn_pet_action_first_last_received_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom ".$condition." 
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) recd on recd.dept_id=cc.dept_id and recd.off_level_dept_id=cc.off_level_dept_id and recd.off_loc_id=cc.off_loc_id and recd.dept_desig_id=cc.dept_desig_id and recd.dept_user_id=cc.dept_user_id 

		left join -- accepted 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as acp_cnt 
		from fn_pet_action_first_last_accepted_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  ".$condition."
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) acp on acp.dept_id=cc.dept_id and acp.off_level_dept_id=cc.off_level_dept_id and acp.off_loc_id=cc.off_loc_id and acp.dept_desig_id=cc.dept_desig_id and acp.dept_user_id=cc.dept_user_id 

		left join -- rejected 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as rjct_cnt 
		from fn_pet_action_first_last_rejected_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom ".$condition." 
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) rjct on rjct.dept_id=cc.dept_id and rjct.off_level_dept_id=cc.off_level_dept_id and rjct.off_loc_id=cc.off_loc_id and rjct.dept_desig_id=cc.dept_desig_id and rjct.dept_user_id=cc.dept_user_id 

		left join -- pending with SDC 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as no_act_cnt 
		from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().")
		where to_whom=".$userProfile->getDept_user_id()." and action_type_code not in ('A','R')) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) ".$condition."
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) no_act on no_act.dept_id=cc.dept_id and no_act.off_level_dept_id=cc.off_level_dept_id and no_act.off_loc_id=cc.off_loc_id and no_act.dept_desig_id=cc.dept_desig_id and no_act.dept_user_id=cc.dept_user_id 

		left join -- pending with others 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as oth_pend_cnt
		from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().") a1
		where a1.to_whom<>".$userProfile->getDept_user_id()." and not exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) ".$condition."
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) oth on oth.dept_id=cc.dept_id and oth.off_level_dept_id=cc.off_level_dept_id and oth.off_loc_id=cc.off_loc_id and oth.dept_desig_id=cc.dept_desig_id and oth.dept_user_id=cc.dept_user_id

		left join -- pending with the concerned officer 
		( select c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as cl_pend_cnt, sum(case when (current_date - b.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, sum(case when ((current_date - b.petition_date::date) > 30 and (current_date - b.petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, sum(case when (current_date - b.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
		from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,".$userProfile->getDept_user_id().") a1
		where a1.to_whom<>".$userProfile->getDept_user_id()." and exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) ".$condition."
		group by c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) clb on clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id ) b_rpt 
		where ob_cnt+recd_cnt+acp_cnt+rjct_cnt+no_act_cnt+oth_pend_cnt+cl_pend_cnt > 0 
		) pp) 

		select ".$dist_params_1." b.dept_id, b1.dept_name, a.off_level_dept_id, b.off_level_dept_name, a.dept_desig_id, a.dept_desig_name, a.off_loc_id, a.off_loc_name, a.dept_user_id, a.off_hier, a.ob_cnt, a.recd_cnt, a.acp_cnt, a.rjct_cnt, a.no_act_cnt, a.oth_pend_cnt, a.cl_pend_cnt, a.cl_pend_leq30d_cnt, a.cl_pend_gt30leq60d_cnt, a.cl_pend_gt60d_cnt
		
		from pending_pet a

		inner join usr_dept_off_level b on b.off_level_dept_id=a.off_level_dept_id
		inner join usr_dept b1 on b1.dept_id=b.dept_id
		left join

		(".$dist_params_2.") aa
		on ".$join_condition."
		order by off_level_dept_id, dept_desig_name, off_loc_name";			
	    //echo  $sql;
//".$dist_params_1." dept_id, dept_name, off_level_dept_id, off_level_dept_name, dept_desig_id, dept_desig_name, off_loc_id, off_loc_name, dept_user_id,
		$result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$temp_dept_id='';
		$j=1;
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			$dept_id=$row['dept_id'];
			$source_id=$row['source_id'];
			$dept_name=$row['dept_name'];
			$source_name=$row['source_name'];
			$dept_name=$row['dept_name']; 
			$dept_desig=$row['dept_desig_name']." - ".$row['off_loc_name'];
			$dept_user_id = $row['dept_user_id'];
			$op_bal = $row['dept_name'];
			
			$ob_cnt = $row['ob_cnt']; //1 Opening Balance
			$recd_cnt = $row['recd_cnt']; //2 Received 
			$acp_cnt = $row['acp_cnt'];   //3 Accepted
			$rjct_cnt = $row['rjct_cnt']; //4 Rejected
			$no_act_cnt =  $row['no_act_cnt']; //5 Pending with Disposing Officer				
			$oth_pend_cnt = $row['oth_pend_cnt'];	//6	 Pending with Others	
			$cl_pend_cnt = $row['cl_pend_cnt'];	//7	 Closed Pending Pending 
			$cl_pend_leq30d_cnt = $row['cl_pend_leq30d_cnt'];	//7	 Closed Pending Pending < 30 days
			$cl_pend_gt30leq60d_cnt = $row['cl_pend_gt30leq60d_cnt'];	//7	 Closed Pending Pending >30 and <60 days
			$cl_pend_gt60d_cnt = $row['cl_pend_gt60d_cnt'];	//7	 Closed Pending Pending > 60 days
			
			if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id;
	 
			?>
			
           <tr>
           		<td class="h1" style="text-align:left" colspan="12"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			} ?>

			<tr>
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_desig; ?></td>
                              
		 <!-- 1 Opening Balance  opbal-->
		 <?php if($ob_cnt!=0) {?>
				<td><a href="javascript:void(0)" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'opbal'; ?>')"><?php echo $ob_cnt;?> </a></td>  
		 <?php } else {?>
		<td><?php echo $ob_cnt;?> </td> <?php } ?>

		 <!-- 2 Received recd -->
		 <?php if($recd_cnt!=0) {?>
				<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
				'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'recd'; ?>')"><?php echo $recd_cnt;?> </a></td>  
		 <?php } else {?>
		<td><?php echo $recd_cnt;?> </td> <?php } ?>
                                
	   <!-- 3 Accepted acpt -->
		<?php if($acp_cnt>0) {?>
				<td><a class="accepted" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'acpt'; ?>')"><?php echo $acp_cnt;?> </a></td>
		 <?php } 
		 else {?>
		<td><?php echo $acp_cnt;?> </td> <?php } ?>
                
		<!-- 4 Rejected rjct-->
		 <?php if($rjct_cnt!=0) {?>
		<td><a class="rejected" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'rjct'; ?>')"><?php echo $rjct_cnt;?> </a></td>
			 <?php } else {?>
		<td><?php echo $rjct_cnt;?> </td> <?php } ?>
                 
		 <!-- 5 Pending with Disposing Officer  pwdo -->
		 <?php if($no_act_cnt!=0) {?>
				<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pwdo'; ?>')"><?php echo $no_act_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $no_act_cnt;?> </td> <?php } ?>
                

		<!-- 6 Pending with Others -->
		 <?php if($oth_pend_cnt!=0) {?>
				<td><a class="pending" href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pwo'; ?>')"><?php echo $oth_pend_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $oth_pend_cnt;?> </td> <?php } ?>
				
        
		<!-- 7 Pending with Conecerned officer -->
		 <?php if($cl_pend_cnt!=0) {?>
				<td><a class="pending" href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pending'; ?>')"><?php echo $cl_pend_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $cl_pend_cnt;?> </td> <?php } ?>
		
               
		<!-- 8 Pending for less than 1 month -->
		  <?php if($cl_pend_leq30d_cnt!=0) {?>
				<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pl1m'; ?>' )"><?php echo $cl_pend_leq30d_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>
		
		<!-- 9 Pending > 30 and <= 60 Days -->
		  <?php if($cl_pend_gt30leq60d_cnt!=0) {?>
				<td><a class="pending"  href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg1m'; ?>' )"><?php echo $cl_pend_gt30leq60d_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>
		
        <!-- 10 Closed Pending Pending > 60 days -->
		  <?php if($cl_pend_gt60d_cnt!=0) {?>
				<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg2m'; ?>' )"><?php echo $cl_pend_gt60d_cnt;?> </a></td>
		 <?php } else {?>
		<td><?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>
		
			</tr>
			<?php  
			$i++;		
			$tot_ob_cnt = $tot_ob_cnt + $ob_cnt;
			$tot_recd_cnt = $tot_recd_cnt + $recd_cnt;
			$tot_acp_cnt = $tot_acp_cnt + $acp_cnt;
			$tot_rjct_cnt = $tot_rjct_cnt + $rjct_cnt;
			$tot_pwou_cnt = $tot_pwou_cnt + $no_act_cnt;
			$tot_oth_pend_cnt = $tot_oth_pend_cnt + $oth_pend_cnt;
			$tot_cl_pend_cnt = $tot_cl_pend_cnt + $cl_pend_cnt;
			$tot_cl_pend_m2m_ct = $tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
			$tot_cl_pend_2m_cnt =  $tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1m_cnt = $tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Total' ?></td>
                <td><?php echo $tot_ob_cnt;?></td> 
                <td><?php echo $tot_recd_cnt;?></td>                      
                <td><?php echo $tot_acp_cnt;?></td>
           		<td><?php echo $tot_rjct_cnt;?></td>
           		<td><?php echo $tot_pwou_cnt;?></td>
				<td><?php echo $tot_oth_pend_cnt;?></td>
            	<td><?php echo $tot_cl_pend_cnt;?></td>
				<td><?php echo $tot_cl_pend_m2m_ct;?></td>
				<td><?php echo $tot_cl_pend_2m_cnt;?></td>
				<td><?php echo $tot_cl_pend_1m_cnt;?></td>
                
			</tr>
			<tr><th colspan="12" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
			<tr>
            <td colspan="12" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
            <input type="hidden" name="hid" id="hid" />
            <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
            <input type="hidden" name="frdate" id="frdate"  />
   		    <input type="hidden" name="todate" id="todate" />
    		<input type="hidden" name="dept" id="dept" />
            <input type="hidden" name="dept_name" id="dept_name" />
			<input type="hidden" name="dept_user_id" id="dept_user_id" />
            <input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/>
			<input type="hidden" name="status" id="status" /> 
       		<input type="hidden" name="dept_designation" id="dept_designation" />
	
	<input type="hidden" name="disp_officer_title" id="disp_officer_title" value='<?php echo $disp_officer_title ?>'/>
			<input type="hidden" name="h_srcid" id="h_srcid" value='<?php echo $src_id ?>'/>
			<input type="hidden" name="h_subsrcid" id="h_subsrcid" value='<?php echo $sub_src_id ?>'/>
            <input type="hidden" name="h_gtype" id="h_gtype" value='<?php echo $gtypeid ?>'/>
			<input type="hidden" name="h_gsubtype" id="h_gsubtype" value='<?php echo $gsubtypeid ?>'/> 
       		<input type="hidden" name="h_dept" id="h_dept" value='<?php echo $grie_dept_id ?>'/>
			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/> 
	
	<input type="hidden" name="pet_community" id="pet_community" value="<?php echo $pet_community; ?>"/> 
	<input type="hidden" name="special_category" id="special_category" value="<?php echo $special_category; ?>"/> 
	<input type="hidden" name="reporttypename" id="reporttypename" value="<?php echo $reporttypename; ?>"/> 
	
		<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 	
	<input type="hidden" name="h_dept_user_id" id="h_dept_user_id" value="<?php echo $userProfile->getDept_user_id(); ?>"/>
	<input type="hidden" name="h_off_level_dept_id" id="h_off_level_dept_id" value="<?php echo $userProfile->getOff_level_dept_id(); ?>"/>
	<input type="hidden" name="h_dept_id" id="h_dept_id" value="<?php echo $userProfile->getDept_id(); ?>"/>
	<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" value="<?php echo $userProfile->getOff_loc_id(); ?>"/>
            </td></tr>
			 <tr id="bak_btn1"><td colspan="12" style="text-align: center;background-color: #BC7676;"><a href="" onclick="self.close();">
            <img src="images/bak.jpg" style="height: 25px;width: 45px;"/></a></td></tr>
		
		<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo $label_name[30]; //No Records Found?>...</td></tr>
		 

			
         </table>
         
        <?php } ?>
        </tbody>
        </table>
 		 
	</div>
</div>
					<?php  		if(stripQuotes(killChars($_POST["dist_rpt"]))!="")
								 $_SESSION["dist_rpt"]  = stripQuotes(killChars($_POST["dist_rpt"])); 
					 ?>
					<input type="hidden" name="hid_radio" id="hid_radio" 
                    value="<?php echo (stripQuotes(killChars($_POST["dist_rpt"])))? 
					stripQuotes(killChars($_POST["dist_rpt"])) : $_SESSION["dist_rpt"]; ?>" />
                    <input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date; ?>" />
                    <input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date; ?>" />
</form>
<?php 
include("footer.php");
} ?>
 
<?php
if(stripQuotes(killChars($_POST['hid']))=='done')
{	 
ob_start();
session_start();
include("db.php"); 
?>
  
<?php
include("pm_common_js_css.php");
?>
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

function sortOnPetType() {
	if (document.getElementById("sort_on_pettype").checked == true) {
		document.getElementById("sort_on_type").value = 'Y';
		document.getElementById("hid").value = 'done';
	} else {
		document.getElementById("sort_on_type").value = '';
		document.getElementById("hid").value = 'done';
	}
	document.rpt_abstract.action="rptdist_pending_with_opening_balance.php";
	document.rpt_abstract.submit(); 

}
</script>

<style>
.tooltip {
    position: relative;
    display: inline-block;   
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 150px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 1s;
}

.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>
 
<?php

$h_dept_user_id=stripQuotes(killChars($_POST["h_dept_user_id"]));

$session_user_id=stripQuotes(killChars($_POST["session_user_id"]));
if($_SESSION['USER_ID_PK'] != $session_user_id) {
	echo "<script> alert('Your session is expired !!');self.close();</script>";	
	exit;
}

$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$dept_user_id=stripQuotes(killChars($_POST["dept_user_id"]));	
$status=stripQuotes(killChars($_POST["status"]));	
$dept_designation=stripQuotes(killChars($_POST["dept_designation"]));	
$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$pet_community=stripQuotes(killChars($_POST["pet_community"]));
$special_category=stripQuotes(killChars($_POST["special_category"]));

$h_off_level_dept_id=stripQuotes(killChars($_POST["h_off_level_dept_id"]));
$h_dept_id=stripQuotes(killChars($_POST["h_dept_id"]));
$h_off_loc_id=stripQuotes(killChars($_POST["h_off_loc_id"]));
$reporttypename=stripQuotes(killChars($_POST["reporttypename"]));
$disp_officer_title=stripQuotes(killChars($_POST["disp_officer_title"]));

$_SESSION["check"]="yes"; 

if(stripQuotes(killChars($_POST["h_srcid"]))!="")
	$src_id=stripQuotes(killChars($_POST["h_srcid"]));
	
if(stripQuotes(killChars($_POST["h_subsrcid"]))!="")
	$sub_src_id=stripQuotes(killChars($_POST["h_subsrcid"]));


if(stripQuotes(killChars($_POST["h_gtype"]))!="")
	$gtypeid=stripQuotes(killChars($_POST["h_gtype"]));


if(stripQuotes(killChars($_POST["h_gsubtype"]))!="")
	$gsubtypeid=stripQuotes(killChars($_POST["h_gsubtype"]));

if(stripQuotes(killChars($_POST["h_dept"]))!="")
	$grie_dept_id = stripQuotes(killChars($_POST["h_dept"]));

	$sort_on_type = $_POST["sort_on_type"];
	//
	$grev_dept_condition = "";
	
		if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
	$condition="";

	if (!empty($src_id)) {
		$condition.=" where b.source_id=".$src_id;
	}
	if (!empty($sub_src_id)) {
		$condition.=" and b.subsource_id=".$sub_src_id;
	}

	if(!empty($gtypeid)) {
		$condition.= ($condition== "")? " where b.griev_type_id=".$gtypeid : " and b.griev_type_id=".$gtypeid;
	}
	if (!empty($gsubtypeid)) {
		$condition.=" and b.griev_subtype_id=".$gsubtypeid;
	}
	if(!empty($grie_dept_id)) {
		$condition.= ($condition== "")? " where b.dept_id=".$griedeptid : " and b.dept_id=".$griedeptid;
	}
	if(!empty($petition_type)) {
		$condition.= ($condition== "")? " where b.pet_type_id=".$petition_type : " and b.pet_type_id=".$petition_type;
	}
	$pet_community_condition = '';
	if(!empty($pet_community)) {
		$condition.= ($condition== "")? " where b.pet_community_id=".$pet_community : " and b.pet_community_id=".$pet_community;
	}
	$special_category_condition = '';
	if(!empty($special_category)) {
		$condition.= ($condition== "")? " where b.petitioner_category_id=".$special_category : " and b.petitioner_category_id=".$special_category;
	}
	
if($status=='opbal') {
	$cnt_type=" Opening Balance";	
} else if($status=='recd') {
	$cnt_type=" Received Petitions";	
} else if($status=='acpt') {
	$cnt_type=" Accepted Petitions";
} else if($status=='rjct') {
	$cnt_type=" Rejected Petitions";
} else if($status=='pwdo') {
	if ($userProfile->getPet_disposal())
		$cnt_type=" Petitions Pending with ".$userProfile->getOff_desig_emp_name().$userProfile->getDept_desig_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name());	
	else
		$cnt_type=" Petitions Pending with Disposing Officer";
} else if($status=='pwo') {
	$cnt_type=" Petitions Pending with Others";
} else if($status=='pending') {
	$cnt_type=" Petitions Pending with ".$dept_designation;
} else if($status=='pl1m') {
	$cnt_type=" Petitions pending for < 1 Month";
} else if($status=='pg1m') {
	$cnt_type=" Petitions pending for > 1 month and < 2 Months";
} else if($status=='pg2m') {
	$cnt_type=" Petitions pending for < 2 months";
}
?>

<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">
		<div id="response"></div>
		<table class="rptTbl">
			<thead>
				<tr id="bak_btn"><th colspan="9" > 
				<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
				</th></tr>
                
              
                <tr> 
				<th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
				</tr>
            
				<tr> 
				<th colspan="9" class="main_heading"><?PHP echo $label_name[72]." - ";?> <?php echo "Details of ".$cnt_type; ?></th>
                </tr>
                               
                 <?php if($disp_officer_title!="") { ?>
                <tr>
                <th colspan="9" class="search_desc"><?php echo $disp_officer_title;?></th>
                </tr>
                <?php } ?>
                
				<?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="9" class="search_desc"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
				
				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="9" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
				<tr> 
					<th colspan="9" class="main_heading"><?PHP echo  $label_name[40].' : '.$dept_designation; //Report type name?></th>
				</tr>
			<tr> 
				<th colspan="9" class="search_desc"><b><?PHP echo $label_name[73];?>  </b>&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
				<tr>
				

				<th><?PHP echo$label_name[20]; //S.No.?></th>
				<th><?PHP echo $label_name[21]; //Petition No. & Date. ?></th>
				<th><?PHP echo $label_name[22]; //Petitioners communication address ?></th>
				<th><?PHP echo $label_name[23]; //Source, Sub Source & Source Remarks ?></th>
				<th><?PHP echo $label_name[25]; //Petition ?></th>
				<!--th><?PHP //echo $label_name[25]; //Source Remarks?></th-->
				<th width="14%">
				<?php if ($sort_on_type == 'Y') { ?>
					<div class="tooltip">
				<!--	<input type="checkbox" id="sort_on_pettype" name="sort_on_pettype" onclick="sortOnPetType();" checked/>
					<span class="tooltiptext">Click on the checkbox to cancel the sorting</span> -->
					</div>
				<?php } else { ?>
					<div class="tooltip">
					<!--<input type="checkbox" id="sort_on_pettype" name="sort_on_pettype"  onclick="sortOnPetType();"/>
					<span class="tooltiptext">Click on the checkbox to sort on this field</span> -->
					</div>
				<?php } ?><?PHP echo $label_name[26]; //Petition type & address?>
				
				</th>
				<th>
				<?PHP echo $label_name[27]; //Action Type, Date & Remarks?>				
				</th>
				<th><?PHP echo $label_name[28]; //Pending Period?></th>
                <!--th><?PHP //echo $label_name[29]; //Pending Period?></th-->
				</tr>
				
			</thead>
		<tbody>
<?php 
$i=1;

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

	if ($status=='opbal') { // Opening Balance		
		$sub_sql="select a.petition_id
		from fn_pet_action_first_last_pndg_notAR_cb_dt_from('".$frm_dt."'::date,".$h_dept_user_id.") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id =a.to_whom and c.dept_user_id =".$dept_user_id.$condition;
	} else if($status=='recd') {	 //Received				
		$sub_sql="select a.petition_id
		from fn_pet_action_first_last_received_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id =a.to_whom and c.dept_user_id =".$dept_user_id.$condition;
 	} else if($status=='acpt') {	 //Accepted
		$sub_sql="select a.petition_id
		from fn_pet_action_first_last_accepted_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom and c.dept_user_id =".$dept_user_id.$condition;
	} else if($status=='rjct') {	 //Rejected
		$sub_sql="select a.petition_id
		from fn_pet_action_first_last_rejected_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom and c.dept_user_id =".$dept_user_id.$condition;
	} else if($status=='pwdo') {	 //Pending with Disposing Officer
		$sub_sql="select a.petition_id	from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.")
		where to_whom=".$h_dept_user_id." and action_type_code not in ('A','R')) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} else if($status=='pwo') {	 //Pending with Other Officer
		$sub_sql="select a.petition_id	from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a1
		where a1.to_whom<>".$h_dept_user_id." and not exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} else if($status=='pending') { //Pending with Concerned Officer
		$sub_sql="select a.petition_id from 
		(select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a1
		where a1.to_whom<>".$h_dept_user_id." and exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} else if ($status=='pl1m') { //Pending upto 1 month
		$sub_sql="select a.petition_id from (
		select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a1 
		where a1.to_whom<>".$h_dept_user_id." 
		and exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a 
		inner join pet_master b on b.petition_id=a.petition_id and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} else if ($status == 'pg1m') { //Pending 1 month to 60 days
		$sub_sql="select a.petition_id from (
		select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a1 
		where a1.to_whom<>".$h_dept_user_id." 
		and exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a 
		inner join pet_master b on b.petition_id=a.petition_id and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} else if ($status == 'pg2m') { //Pending above 60 days
		$sub_sql="select a.petition_id from (
		select * from fn_pet_action_first_last_pndg_bw_dt_from('".$frm_dt."'::date,'".$to_dt."'::date,
		".$h_dept_user_id.") a1 
		where a1.to_whom<>".$h_dept_user_id." 
		and exists (select 1 from pet_action_first_last a2 where a2.petition_id=a1.petition_id and a2.f_to_whom=a1.to_whom) ) a 
		inner join pet_master b on b.petition_id=a.petition_id and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1
		inner join vw_usr_dept_users_v c on c.dept_user_id = (select f_to_whom from pet_action_first_last a2 where a2.petition_id=a.petition_id) and c.dept_user_id=".$dept_user_id.$condition;
	} 
	
	$sql="select aa.*, bb.lnk_docs from
	(select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, 
	griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, 
	pend_period ,pet_type_name,comm_mobile from fn_pet_last_action_details(array(".$sub_sql."))) aa
	left join
	(select a.petition_id, string_agg(pet_ext_link_name || ': '||pet_ext_link_no,', ' order by b.petition_id,b.pet_master_ext_link_id) as lnk_docs 
	from pet_master a
	LEFT JOIN pet_master_ext_link b on b.petition_id=a.petition_id
	LEFT JOIN lkp_pet_ext_link_type c on c.pet_ext_link_id=b.pet_ext_link_id
	where a.petition_id in ((".$sub_sql."))
	group by a.petition_id) bb on bb.petition_id=aa.petition_id";
	
	if ($sort_on_type == 'Y') {
		
		$sql = $sql.' order by griev_type_id, griev_subtype_id, petition_no';
	} else {
		$sql = $sql.' order by petition_id';
	}
	//echo $status.$sql;
	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
				$source_details = $row['source_name'].' & '.$row['subsource_name'];
			} else {
				$source_details = $row['source_name'];
			}
			
			?>
			
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:15%;"> 
			<?php echo "Mobile: ".$row['comm_mobile']."<br><br>"; ?>
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']; ?></a>
			<?php
				if ($row['lnk_docs'] != '') {
					echo "<br><br>Linked To: ".$row['lnk_docs'];
				}
			?>
			</td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:18%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$row['gri_address']."<br>".$row['pet_type_name']; ?></td>
            
<td class="desc"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>

            <td class="desc"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
			<?php 
			$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();
			?>
			<tr><th colspan="9" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
			<tr>
			<td colspan="9" class="buttonTD">

			<input type="button" name="" id="dontprint1" value="<?PHP echo "Print";?>" class="button" onClick="return printReportToPdf()">
            <input type="hidden" name="petition_no" id="petition_no" />
			<input type="hidden" name="petition_id" id="petition_id" />
			<input type="hidden" name="sort_on_type" id="sort_on_type">
			
			<input type="hidden" name="hid" id="hid" value="done"/>
            <input type="hidden" name="frdate" id="frdate"  value="<?php echo $from_date; ?>"/>
   		    <input type="hidden" name="todate" id="todate" value="<?php echo $to_date; ?>"/>
    		<input type="hidden" name="dept" id="dept" />
            <input type="hidden" name="dept_name" id="dept_name" />
			<input type="hidden" name="dept_user_id" id="dept_user_id" value='<?php echo $dept_user_id ?>'/> 
            <input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/>
			<input type="hidden" name="status" id="status" value="<?php echo $status; ?>"/> 
       		<input type="hidden" name="dept_designation" id="dept_designation" value="<?php echo $dept_designation; ?>"/>
	
			<input type="hidden" name="h_srcid" id="h_srcid" value='<?php echo $src_id ?>'/>
			<input type="hidden" name="h_subsrcid" id="h_subsrcid" value='<?php echo $sub_src_id ?>'/>
            <input type="hidden" name="h_gtype" id="h_gtype" value='<?php echo $gtypeid ?>'/>
			<input type="hidden" name="h_gsubtype" id="h_gsubtype" value='<?php echo $gsubtypeid ?>'/> 
       		<input type="hidden" name="h_dept" id="h_dept" value='<?php echo $grie_dept_id ?>'/>
			<input type="hidden" name="petition_type" id="petition_type" value='<?php echo $petition_type ?>'/>
			
	<input type="hidden" name="h_dept_user_id" id="h_dept_user_id" value="<?php echo $h_dept_user_id; ?>"/>
	<input type="hidden" name="h_off_level_dept_id" id="h_off_level_dept_id" value="<?php echo $h_off_level_dept_id; ?>"/>
	<input type="hidden" name="h_dept_id" id="h_dept_id" value="<?php echo $h_dept_id; ?>"/>
	<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" value="<?php echo $h_off_loc_id; ?>"/>
	
			</td>
			</tr>
			
			<tr id="bak_btn1"><td colspan="8" style="text-align: center;background-color: #BC7676;"><a href="" onclick="self.close();">
            <img src="images/bak.jpg" style="height: 25px;width: 45px;"/></a></td></tr>
		
			</tbody>
	</table>
 
</div>
</div>
</form>
<?php
include("footer.php");
  }
}
else{
 	pg_close($db);
	header('Location: logout.php');
	exit; 
}
?>
