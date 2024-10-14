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
	document.rpt_abstract.action="rptdist_officerswise_simple_pet_period.php";
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
color: #C41E3A;
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

	if(stripQuotes(killChars($_POST["gsrc"]))!="")
		$src_id=stripQuotes(killChars($_POST["gsrc"]));
	else  
		$src_id=stripQuotes(killChars($_SESSION["gsrc"]));
	
	$proxy_profile = false;
	$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name(); 
	//echo "===================".$userProfile->getDesig_roleid(); exit;
	if ($userProfile->getDesig_roleid() == 5 && $userProfile->getOff_level_id() != 7) {
		$sql="SELECT dept_user_id from vw_usr_dept_users_v_sup where  off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and pet_disposal and off_level_id=".$userProfile->getOff_level_id()."";
		$rs=$db->query($sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$disposing_officer=$row['dept_user_id'];
	} else {
		$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	}
		
	//echo "################################".$disposing_officer;
	
	
	//echo "111111111111111111111111111";
	//echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name();
?>
 
<?php 
if(stripQuotes(killChars($_POST['hid']))=="") { ?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
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
	$instructions = stripQuotes(killChars($_POST["disp_officer_instruction"]));	
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>".$instructions;
	$disp_officer_name=stripQuotes(killChars($_POST["disp_officer_name"]));
	$delagated=stripQuotes(killChars($_POST["delagated"]));
	
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
	/* if ($grie_dept_id != "") {
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
	} */	
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
	$instruction_condition = '';
	if(!empty($instructions)) {
		$instructions_new=str_replace("**"," & ",$instructions);
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
	}
	
	
?>
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="11" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
			</th></tr>
			

	<tr><th colspan="11" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name(); //Department wise Report?></th></tr>

		
            <tr> 
				<th colspan="11" class="main_heading"><?PHP echo $label_name[0]; //Department wise Report?></th>
			</tr>
            
		<?php if ($disp_officer_title != '') { ?>
		<tr><th colspan="11" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
		<?php } ?>
		
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="11" class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="11" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="11" class="search_desc"><b>Petition Period -  </b><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="3"  style="width:3%;"><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="3"  style="width:17%;"><?PHP echo $label_name[40]; //Concerned Officer?></th>
                <th colspan="9" style="width: 70%;"><?PHP echo $label_name[4].':     ( E = A - (B + C + D)     and      F + G + H = E )';//Number Of Petitions?></th>


				</tr>
				<tr>
                <th rowspan="2" style="width:9%;"><?PHP echo $label_name[6]; //Received?><br>(A)</th>
				<th colspan="3" style="width:9%;"><?PHP echo 'Closed'; //Closed?></th>
				<?php if ($userProfile->getPet_disposal()) { ?>
                <th rowspan="2" style="width:9%;"><?PHP echo $label_name[62].' - '.$userProfile->getDept_desig_name(); //Closing Balance?><br>(D)</th>
				<?php } else { ?>
				<th rowspan="2" style="width:9%;"><?PHP echo $label_name[62]; //Closing Balance?><br>(D)</th>
				<?php } ?>
                <th rowspan="2" style="width:9%;"> <?PHP echo $label_name[61] //Pending for more than 2 months?><br>(E)</th>
        	 	<th rowspan="2" style="width:9%;"> <?PHP echo $label_name[11];; //Pending for 2 months?><br>(F)</th>
            	<th rowspan="2" style="width:9%;"> <?PHP echo $label_name[12];; //Pending for 1 month?><br>(G)</th>
            	<th rowspan="2" style="width:9%;"> <?PHP echo $label_name[13];; //Pending for less than 1 month?><br>(H)</th>
			</tr>
			<tr>
			    <th style="width:9%;"><?PHP echo $label_name[8]; //Closed?><br>(B)</th>
				<th style="width:9%;"><?PHP echo $label_name[9]; //Closed?><br>(C)</th>
				<th style="width:9%;"><?PHP echo 'Deferred'; //Closed?><br>(C1)</th>
			</tr>
            </thead>
            <tbody>            
			<?php 
			
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
			fr_date, to_date, enabling--,off_level_name
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
		//print_r($userProfile);
		//print_r($_SESSION['USER_PROFILE']);exit;
	}
			
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
		
		if ($userProfile->getOff_level_id()==7 && ($userProfile->getDept_off_level_pattern_id() == null || $userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {			
			$dist_cond="(select a1.state_id, a1.state_name from mst_p_state a1 where a1.state_id=29
			union
	        select a1.state_id, a1.state_name from mst_p_state a1 where a1.state_id=29)";
			$dist_params_1 = 'state_id,state_name,';
			$dist_params_2 = 'aa.state_id,aa.state_name,';					
		}
		else if ($userProfile->getOff_level_id()==9) {	
			$dist_cond=" fn_single_district(".$userProfile->getDistrict_id().")"; 
			$dist_cond='(select zone_id,zone_name from mst_p_sp_zone where zone_id='.$userProfile->getZone_id().')';
			$dist_params_1 = 'zone_id,zone_name,';
			$dist_params_2 = 'aa.zone_id,aa.zone_name,';
			$delegate_condtition=" ";
		}
		else if ($userProfile->getOff_level_id()==11 && ($userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {	
			$dist_cond=" fn_single_district(".$userProfile->getDistrict_id().")"; 
			$dist_cond='(select range_id,range_name from mst_p_sp_range where range_id='.$userProfile->getRange_id().')';
			$dist_params_1 = 'range_id,range_name,';
			$dist_params_2 = 'aa.range_id,aa.range_name,';
			$delegate_condtition=" ";
		}
		else if ($userProfile->getOff_level_id()==13) {	
			$dist_cond=" fn_single_district(".$userProfile->getDistrict_id().")"; 
			$dist_cond='(select district_id,district_name from mst_p_district where district_id='.$userProfile->getDistrict_id().')';
			$dist_params_1 = 'district_id,district_name,';
			$dist_params_2 = 'aa.district_id,aa.district_name,';
			$delegate_condtition=" ";
		}
		//else if ($userProfile->getOff_level_id()==42 && ($userProfile->getDept_off_level_pattern_id() == 3 || $userProfile->  getDept_off_level_pattern_id() == 4)) {	
		else if ($userProfile->getOff_level_id()==42 ) {	
			$dist_cond=" fn_single_district(".$userProfile->getDistrict_id().")"; 
			$dist_cond='(select division_id,division_name from mst_p_sp_division where division_id='.$userProfile->getDivision_id().')';
			$dist_params_1 = 'division_id,division_name,';
			$dist_params_2 = 'aa.division_id,aa.division_name,';
			$delegate_condtition=" ";
		}else if ($userProfile->getOff_level_id()==46 ) {	
			$dist_cond=" fn_single_district(".$userProfile->getDistrict_id().")"; 
			$dist_cond='(select circle_id,circle_name from mst_p_sp_circle where circle_id='.$userProfile->getCircle_id().')';
			$dist_params_1 = 'circle_id,circle_name,';
			$dist_params_2 = 'aa.circle_id,aa.circle_name,';
			$delegate_condtition=" ";
		}
		
//	inner join fn_usr_dept_users_vhr(".$userProfile->getOff_level_id().",".$userProfile->getOff_loc_id().$dept_off_level_cond.") cc on cc.dept_id = bb.dept_id 
	if ($delagated == "true") {	
		if ($disposing_officer == '') {
			$function_name="fn_pet_action_first_last_delegated_by (".$userProfile->getDept_user_id().")";
		} else {
			$function_name="fn_pet_action_first_last_delegated_by (".$disposing_officer.")";
		}		
		$delg_cond=" coalesce(a.d_to_whom,a.to_whom,a.action_entby)";
		$delg_user_id = ", d_to_whom";
		$inner_condition=", COALESCE(recd.d_to_whom,0) as d_to_whom";
		$rcvd_condition=", min(d_to_whom) as d_to_whom";
		$outer_condition=", pq.d_to_whom, pq6.dept_desig_name as del_desig, pq6.off_loc_name as del_loc ";
		$outer_join="inner join vw_usr_dept_users_v pq6 on pq6.dept_user_id=pq.d_to_whom ";
		
		
	} else {
		$function_name="fn_pet_action_first_last_received_from (".$userProfile->getDept_user_id().")";
		$delg_cond=" coalesce(a.to_whom,a.action_entby)";
		$delg_user_id = " ";
		$inner_condition=" ";
		$rcvd_condition="";
		$outer_condition="";
		$outer_join=" ";
	}
			
		
	$sql_1="With pending_pet as 

	(select * from (
	
	With off_pet as
	(
	select a.petition_id, b.petition_date, c.dept_id, c.off_level_dept_id, 
	c.off_loc_id, c.dept_desig_id, c.dept_user_id".$delg_user_id."
	from ".$function_name." a 
	-- fn_pet_action_first_last_received_from(), petitions forwarded first by the logged in user
	inner join pet_master b on b.petition_id=a.petition_id 
	inner join vw_usr_dept_users_v c on c.dept_user_id = coalesce(a.to_whom,a.action_entby)
		
	where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
	".$src_condition.$petition_type_condition.$grev_dept_condition.
	$grev_condition.$pet_community_condition.$special_category_condition.
	$instruction_condition." 
	)

	select ".$dist_params_1." dept_id, dept_name, off_level_dept_id, 
	off_level_dept_name, dept_desig_id, dept_desig_name, off_loc_id, 
	off_loc_name, dept_user_id, recd_cnt, acp_cnt, rjct_cnt, 
	dfr_cnt, no_act_cnt, cl_pend_cnt, cl_pend_leq30d_cnt, 
	cl_pend_gt30leq60d_cnt, cl_pend_gt60d_cnt".$delg_user_id."	
	
	from 

	( select ".$dist_params_2." bb.dept_id, bb.dept_name, cc.off_level_dept_id, 
	cc.off_level_dept_name, cc.dept_desig_id, cc.dept_desig_name, 
	cc.off_loc_id, cc.off_loc_name, cc.dept_user_id, 
	COALESCE(recd.recd_cnt,0) as recd_cnt, 
	COALESCE(acp.acp_cnt,0) as acp_cnt, 
	COALESCE(rjct.rjct_cnt,0) as rjct_cnt, 
	COALESCE(dfr.dfr_cnt,0) as dfr_cnt, 
	COALESCE(no_act.no_act_cnt,0) as no_act_cnt, 
	COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt, 
	COALESCE(clb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, 
	COALESCE(clb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, 
	COALESCE(clb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt
	".$inner_condition." 

	from ".$dist_cond." aa 
	cross join usr_dept bb 
	inner join vw_usr_dept_users_v cc on cc.dept_id = bb.dept_id 
	and cc.pet_act_ret = true  

	left join 

	-- received for action 

	( select  dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as recd_cnt".$rcvd_condition." from off_pet a
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) recd on  recd.dept_id=cc.dept_id and recd.off_level_dept_id=cc.off_level_dept_id and recd.off_loc_id=cc.off_loc_id 
	and recd.dept_desig_id=cc.dept_desig_id and recd.dept_user_id=cc.dept_user_id 

	left join 

	-- accepted 

	( select  dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as acp_cnt from off_pet a
	where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'A') 
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) acp on acp.dept_id=cc.dept_id and acp.off_level_dept_id=cc.off_level_dept_id and acp.off_loc_id=cc.off_loc_id 
	and acp.dept_desig_id=cc.dept_desig_id and acp.dept_user_id=cc.dept_user_id 

	left join 

	-- rejected 

	( select dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as rjct_cnt from off_pet a
	where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'R') 
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) rjct on rjct.dept_id=cc.dept_id and rjct.off_level_dept_id=cc.off_level_dept_id and rjct.off_loc_id=cc.off_loc_id 
	and rjct.dept_desig_id=cc.dept_desig_id and rjct.dept_user_id=cc.dept_user_id 

	left join 

	-- rejected 

	( select dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as dfr_cnt from off_pet a
	where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'T') 
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) dfr on  dfr.dept_id=cc.dept_id and dfr.off_level_dept_id=cc.off_level_dept_id and dfr.off_loc_id=cc.off_loc_id 
	and dfr.dept_desig_id=cc.dept_desig_id and dfr.dept_user_id=cc.dept_user_id 

	left join 

	-- pending with SDC 

	( select dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as no_act_cnt from off_pet a
	inner join fn_pet_action_first_last_cb_with(".$userProfile->getDept_user_id().") a1 on a1.petition_id=a.petition_id 
	-- fn_pet_action_first_last_cb_with(), petitions pending with the logged in user with the action code of ('C','E','N','I','S')
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) no_act on  no_act.dept_id=cc.dept_id and no_act.off_level_dept_id=cc.off_level_dept_id and no_act.off_loc_id=cc.off_loc_id and no_act.dept_desig_id=cc.dept_desig_id and no_act.dept_user_id=cc.dept_user_id 

	left join 

	-- pending with the concerned officer 

	( select dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as cl_pend_cnt, sum(case when (current_date - a.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, sum(case when ((current_date - a.petition_date::date) > 30 and (current_date - a.petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, sum(case when (current_date - a.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
	from off_pet a
	where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R','T')) and not exists (select * from fn_pet_action_first_last_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id) 
	group by dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) clb on clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id ) b_rpt 


	where recd_cnt+acp_cnt+rjct_cnt+dfr_cnt+no_act_cnt+cl_pend_cnt > 0 
	) pp)
	";

	if ($userProfile->getOff_level_id()==7) {
		$sql=$sql_1.'select pq.state_id,pq1.state_name, pq.dept_id, pq2.dept_name, pq.off_level_dept_id, pq3.off_level_dept_name, pq.dept_desig_id, pq4.dept_desig_name, pq5.off_loc_id, pq5.off_loc_name, pq.dept_user_id, pq.recd_cnt, pq.acp_cnt, pq.rjct_cnt, pq.no_act_cnt, pq.cl_pend_cnt, pq.cl_pend_leq30d_cnt, pq.cl_pend_gt30leq60d_cnt, pq.cl_pend_gt60d_cnt,pq.dfr_cnt'.$outer_condition.'
		from 
		(select state_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id, sum(recd_cnt) recd_cnt, sum(acp_cnt) acp_cnt, sum(rjct_cnt) rjct_cnt, sum(no_act_cnt) no_act_cnt, sum(cl_pend_cnt) cl_pend_cnt, sum(cl_pend_leq30d_cnt) cl_pend_leq30d_cnt, sum(cl_pend_gt30leq60d_cnt) cl_pend_gt30leq60d_cnt, sum(cl_pend_gt60d_cnt) cl_pend_gt60d_cnt,sum(dfr_cnt) as dfr_cnt'.$rcvd_condition.'
		from pending_pet 
		group by state_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id) pq
		inner join mst_p_state pq1 on pq1.state_id=pq.state_id
		inner join usr_dept pq2 on pq2.dept_id=pq.dept_id
		inner join usr_dept_off_level pq3 on pq3.off_level_dept_id=pq.off_level_dept_id
		inner join usr_dept_desig pq4 on pq4.dept_desig_id=pq.dept_desig_id
		inner join vw_usr_dept_users_v pq5 on pq5.dept_user_id=pq.dept_user_id
		'.$outer_join.'
		order by dept_id, off_level_dept_id, pq4.dept_desig_name, pq5.off_loc_name';
	} else if ($userProfile->getOff_level_id()==9) {
		$sql=$sql_1.'select pq.zone_id,pq1.zone_name, pq.dept_id, pq2.dept_name, pq.off_level_dept_id, pq3.off_level_dept_name, pq.dept_desig_id, pq4.dept_desig_name, pq5.off_loc_id, pq5.off_loc_name, pq.dept_user_id, pq.recd_cnt, pq.acp_cnt, pq.rjct_cnt, pq.no_act_cnt, pq.cl_pend_cnt, pq.cl_pend_leq30d_cnt, pq.cl_pend_gt30leq60d_cnt, pq.cl_pend_gt60d_cnt,pq.dfr_cnt'.$outer_condition.'
		from 
		(select zone_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id, sum(recd_cnt) recd_cnt, sum(acp_cnt) acp_cnt, sum(rjct_cnt) rjct_cnt, sum(no_act_cnt) no_act_cnt, sum(cl_pend_cnt) cl_pend_cnt, sum(cl_pend_leq30d_cnt) cl_pend_leq30d_cnt, sum(cl_pend_gt30leq60d_cnt) cl_pend_gt30leq60d_cnt, sum(cl_pend_gt60d_cnt) cl_pend_gt60d_cnt,sum(dfr_cnt) as dfr_cnt'.$rcvd_condition.'
		from pending_pet 
		group by zone_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id) pq
		inner join mst_p_sp_zone pq1 on pq1.zone_id=pq.zone_id
		inner join usr_dept pq2 on pq2.dept_id=pq.dept_id
		inner join usr_dept_off_level pq3 on pq3.off_level_dept_id=pq.off_level_dept_id
		inner join usr_dept_desig pq4 on pq4.dept_desig_id=pq.dept_desig_id
		inner join vw_usr_dept_users_v pq5 on pq5.dept_user_id=pq.dept_user_id
		'.$outer_join.'
		order by dept_id, off_level_dept_id, pq4.dept_desig_name, pq5.off_loc_name';
	} else if ($userProfile->getOff_level_id()==11) {
		$sql=$sql_1.'select pq.range_id,pq1.range_name, pq.dept_id, pq2.dept_name, pq.off_level_dept_id, pq3.off_level_dept_name, pq.dept_desig_id, pq4.dept_desig_name, pq5.off_loc_id, pq5.off_loc_name, pq.dept_user_id, pq.recd_cnt, pq.acp_cnt, pq.rjct_cnt, pq.no_act_cnt, pq.cl_pend_cnt, pq.cl_pend_leq30d_cnt, pq.cl_pend_gt30leq60d_cnt, pq.cl_pend_gt60d_cnt,pq.dfr_cnt'.$outer_condition.'
		from 
		(select range_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id, sum(recd_cnt) recd_cnt, sum(acp_cnt) acp_cnt, sum(rjct_cnt) rjct_cnt, sum(no_act_cnt) no_act_cnt, sum(cl_pend_cnt) cl_pend_cnt, sum(cl_pend_leq30d_cnt) cl_pend_leq30d_cnt, sum(cl_pend_gt30leq60d_cnt) cl_pend_gt30leq60d_cnt, sum(cl_pend_gt60d_cnt) cl_pend_gt60d_cnt,sum(dfr_cnt) as dfr_cnt'.$rcvd_condition.'
		from pending_pet 
		group by range_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id) pq
		inner join mst_p_sp_range pq1 on pq1.range_id=pq.range_id
		inner join usr_dept pq2 on pq2.dept_id=pq.dept_id
		inner join usr_dept_off_level pq3 on pq3.off_level_dept_id=pq.off_level_dept_id
		inner join usr_dept_desig pq4 on pq4.dept_desig_id=pq.dept_desig_id
		inner join vw_usr_dept_users_v pq5 on pq5.dept_user_id=pq.dept_user_id
		'.$outer_join.'
		order by dept_id, off_level_dept_id, pq4.dept_desig_name, pq5.off_loc_name';
	} else if ($userProfile->getOff_level_id()==13) {
		$sql=$sql_1.'select pq.district_id,pq1.district_name, pq.dept_id, pq2.dept_name, pq.off_level_dept_id, pq3.off_level_dept_name, pq.dept_desig_id, pq4.dept_desig_name, pq5.off_loc_id, pq5.off_loc_name, pq.dept_user_id, pq.recd_cnt, pq.acp_cnt, pq.rjct_cnt, pq.no_act_cnt, pq.cl_pend_cnt, pq.cl_pend_leq30d_cnt, pq.cl_pend_gt30leq60d_cnt, pq.cl_pend_gt60d_cnt,pq.dfr_cnt'.$outer_condition.'
		from 
		(select district_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id, sum(recd_cnt) recd_cnt, sum(acp_cnt) acp_cnt, sum(rjct_cnt) rjct_cnt, sum(no_act_cnt) no_act_cnt, sum(cl_pend_cnt) cl_pend_cnt, sum(cl_pend_leq30d_cnt) cl_pend_leq30d_cnt, sum(cl_pend_gt30leq60d_cnt) cl_pend_gt30leq60d_cnt, sum(cl_pend_gt60d_cnt) cl_pend_gt60d_cnt,sum(dfr_cnt) as dfr_cnt'.$rcvd_condition.'
		from pending_pet 
		group by district_id, dept_id, off_level_dept_id, dept_desig_id, off_loc_id, dept_user_id) pq
		inner join mst_p_district pq1 on pq1.district_id=pq.district_id
		inner join usr_dept pq2 on pq2.dept_id=pq.dept_id
		inner join usr_dept_off_level pq3 on pq3.off_level_dept_id=pq.off_level_dept_id
		inner join usr_dept_desig pq4 on pq4.dept_desig_id=pq.dept_desig_id
		inner join vw_usr_dept_users_v pq5 on pq5.dept_user_id=pq.dept_user_id
		'.$outer_join.'
		order by dept_id, off_level_dept_id, pq4.dept_desig_name, pq5.off_loc_name';
	}else {
		$sql=$sql_1.'select * from pending_pet 
		order by off_level_dept_id, dept_desig_name, off_loc_name';
	}
	/*else if ($userProfile->getOff_level_id()==2) {	
		$sql=$sql_1.'select * from pending_pet 
		order by dept_id, off_level_dept_id, dept_desig_name, off_loc_name';
	}
	else if ($userProfile->getOff_level_id()==3) {	
		$sql=$sql_1.'select * from pending_pet 
		order by off_level_dept_id, dept_desig_name, off_loc_name';
	}
	else if ($userProfile->getOff_level_id()>=4) {	
		$sql=$sql_1.'select * from pending_pet 
		order by off_level_dept_id, dept_desig_name, off_loc_name';
	}*/	
//	print_r($userProfile);exit;
	//echo $sql;	
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
			
			$recd_cnt = $row['recd_cnt'];
			$acp_cnt = $row['acp_cnt'];
			$rjct_cnt = $row['rjct_cnt'];
			$dfr_cnt = $row['dfr_cnt']; //3 Rejected
			$no_act_cnt =  $row['no_act_cnt'];
			$cl_pend_cnt = $row['cl_pend_cnt'];
			
			$cl_pend_leq30d_cnt = $row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt = $row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt = $row['cl_pend_gt60d_cnt'];		
			
		
	if ($delagated == "true") {
		$delegated_to = "Delegated To: ". $row['del_desig']." - ".$row['del_loc'];
		$forwarded_to = "Forwarded To: ".$row['dept_desig_name']." - ".$row['off_loc_name'];
		$dept_desig = $delegated_to."<br>".$forwarded_to;
    }	else {
		$dept_desig=$dept_desig;
	}	
						
			if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id;
	 
			?>
			
           <tr>
           		<td class="h1" style="text-align:left" colspan="11"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			} ?>

			<tr>
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_desig; ?></td>
                              
    <!-- 1 Received-->
	<?php if($recd_cnt!=0) {?>
	<td><a href="javascript:void(0)" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'recd'; ?>')"><?php echo $recd_cnt;?></a></td>  
	<?php } else {?>
	<td><?php echo $recd_cnt;?> </td> <?php } ?>

	<!-- 2 Accepted -->
	<?php if($acp_cnt!=0) {?>
	<td><a class="accepted" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',	'<?php echo 'acpt'; ?>')"><?php echo $acp_cnt;?></a></td>  
	<?php } else {?>
	<td><?php echo $acp_cnt;?> </td> <?php } ?>
                                
	<!-- 3 Rejected -->
	<?php if($rjct_cnt>0) {?>
	<td><a class="rejected" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
	'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',	'<?php echo 'rjct'; ?>')"><?php echo $rjct_cnt;?> </a></td>
	<?php } 
	else {?>
	<td><?php echo $rjct_cnt;?> </td> <?php } ?>
                
	<!-- 3 Deferred -->
	<?php if($dfr_cnt>0) {?>
	<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
	'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',	'<?php echo 'dfr'; ?>')"><?php echo $dfr_cnt;?> </a></td>
	<?php } 
	else {?>
	<td><?php echo $dfr_cnt;?> </td> <?php } ?>
				
	<!-- 4 Pending with Disposing Officer -->
	<?php if($no_act_cnt!=0) {?>
	<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',	'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pwdo'; ?>')"><?php echo $no_act_cnt;?> </a></td>
	<?php } else {?>
	<td><?php echo ($delagated == "true") ? "N/A"  : $no_act_cnt;?> </td> <?php } ?>
                 
	<!-- 5 Pending Petitions -->
	<?php if($cl_pend_cnt!=0) {?>
	<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',	'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pending'; ?>')"><?php echo $cl_pend_cnt;?> </a></td>
	<?php } else {?>
	<td><?php echo $cl_pend_cnt;?> </td> <?php } ?>
                
	<!-- 6 Pending for <30 Days -->
	<?php if($cl_pend_leq30d_cnt!=0) {?>
	<td><a class="pending" href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pl1m'; ?>')"><?php echo $cl_pend_leq30d_cnt;?> </a></td>
	<?php } else {?>
	<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>

	<!-- 7 Pending for >30 and <60 Days -->
	<?php if($cl_pend_gt30leq60d_cnt!=0) {?>
	<td><a class="pending" href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg1m'; ?>')"><?php echo $cl_pend_gt30leq60d_cnt;?> </a></td>
	<?php } else {?>
	<td><?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>				
                                               
               
	<!-- 8 Pending for >60 Days -->
	<?php if($cl_pend_gt60d_cnt!=0) {?>
	<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg2m'; ?>' )"><?php echo $cl_pend_gt60d_cnt;?> </a></td>
	<?php } else {?>
	<td><?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>
				
                      	
			</tr>
			<?php  
			$i++;		
			$tot_recd_cnt = $tot_recd_cnt + $recd_cnt;
			$tot_acp_cnt = $tot_acp_cnt + $acp_cnt;
			$tot_rjct_cnt = $tot_rjct_cnt + $rjct_cnt;
			$tot_dfr_cnt = $tot_dfr_cnt + $dfr_cnt;
			$tot_pwou_cnt = $tot_pwou_cnt + $no_act_cnt;
			$tot_cl_pend_cnt = $tot_cl_pend_cnt + $cl_pend_cnt;
			$tot_cl_pend_m2m_ct = $tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
			$tot_cl_pend_2m_cnt =  $tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1m_cnt = $tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Total' ?></td>
                
                <td><?php echo $tot_recd_cnt;?></td>                      
                <td><?php echo $tot_acp_cnt;?></td>
           		<td><?php echo $tot_rjct_cnt;?></td>
           		<td><?php echo $tot_dfr_cnt;?></td>
           		<td><?php echo  ($delagated == "true") ? "N/A"  : $tot_pwou_cnt;?></td>
            	<td><?php echo $tot_cl_pend_cnt;?></td>
				<td><?php echo $tot_cl_pend_m2m_ct;?></td>
				<td><?php echo $tot_cl_pend_2m_cnt;?></td>
				<td><?php echo $tot_cl_pend_1m_cnt;?></td>
                
			</tr>
			<!--$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();-->
			<tr><th colspan="11" style="text-align:right;font-size:15px;"><i><b>Report prepared by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y");?></th></tr>
			
			<tr>
            <td colspan="11" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
	<input type="hidden" name="function_name" id="function_name" value="<?php echo $function_name; ?>"/>
		<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 	
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
	<input type="hidden" name="h_dept_user_id" id="h_dept_user_id" value="<?php echo $userProfile->getDept_user_id(); ?>"/>
	<input type="hidden" name="h_off_level_dept_id" id="h_off_level_dept_id" value="<?php echo $userProfile->getOff_level_dept_id(); ?>"/>
	<input type="hidden" name="h_dept_id" id="h_dept_id" value="<?php echo $userProfile->getDept_id(); ?>"/>
	<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" value="<?php echo $userProfile->getOff_loc_id(); ?>"/>
	<input type="hidden" name="instructions" id="instructions" value="<?php echo $instructions; ?>"/>
	<input type="hidden" name="delagated" id="delagated" value="<?php echo $delagated; ?>"/>
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
	document.rpt_abstract.action="rptdist_officerswise_simple_pet_period.php";
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
/* if($_SESSION['USER_ID_PK'] != $session_user_id) {
	echo "<script> alert('Your session is expired !!');self.close();</script>";	
	exit;
} */

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
$instructions=stripQuotes(killChars($_POST["instructions"]));
$function_name=stripQuotes(killChars($_POST["function_name"]));
$delagated=stripQuotes(killChars($_POST["delagated"]));

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
	
	$pet_community_condition = '';
	if(!empty($pet_community)) {
		$pet_community_condition = " and (b.pet_community_id=".$pet_community.")";
	}
	$special_category_condition = '';
	if(!empty($special_category)) {
		$special_category_condition = " and (b.petitioner_category_id=".$special_category.")";
	}
	$instruction_condition = '';
	if(!empty($instructions)) {
		$instructions_new=str_replace("**"," & ",$instructions);
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
	}
	
if($status=='recd') {
	$cnt_type=" Received Petitions";	
} else if($status=='acpt') {
	$cnt_type=" Accepted Petitions";
} else if($status=='rjct') {
	$cnt_type=" Rejected Petitions";
} else if($status=='dfr') {
	$cnt_type=" Deferred Petitions";
}else if($status=='pwdo') {
	if ($userProfile->getPet_disposal())
		$cnt_type=" Petitions Pending with ".$userProfile->getOff_desig_emp_name().$userProfile->getDept_desig_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name());	
	else
		$cnt_type=" Petitions Pending with Initiating Officer";
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
                
              
	<tr> <th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th></tr>
            
				<tr> 
				<th colspan="9" class="main_heading"><?PHP echo $label_name[0]." - ";?> <?php echo "Details of ".$cnt_type; ?></th>
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
                <th colspan="9" class="search_desc">Petition Period - &nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
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
					<input type="checkbox" id="sort_on_pettype" name="sort_on_pettype" onclick="sortOnPetType();" checked/>
					<span class="tooltiptext">Click on the checkbox to cancel the sorting</span>
					</div>
				<?php } else { ?>
					<div class="tooltip">
					<input type="checkbox" id="sort_on_pettype" name="sort_on_pettype"  onclick="sortOnPetType();"/>
					<span class="tooltiptext">Click on the checkbox to sort on this field</span>
					</div>
				<?php } ?><?PHP echo $label_name[26]; //Petition type & address?>
				
				</th>
				<th>
				<?PHP echo $label_name[27]; //Action Type, Date & Remarks?>				
				</th>
				<th><?PHP echo $label_name[28]; //Pending Period?></th>
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
	
	if ($delagated == "true") {
		$join_condition = " c.dept_user_id = coalesce(a.to_whom,a.action_entby)  and coalesce(a.to_whom,a.action_entby)=";
	} else {
		$join_condition = " c.dept_user_id = coalesce(a.to_whom,a.action_entby)  and coalesce(a.to_whom,a.action_entby)=";
	}

	if($status=='recd'){	//Received				
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id."		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")	
		select a.petition_id from off_pet a";		
 	} else if($status=='acpt'){	//Accepted
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'A')";
	} else if($status=='rjct'){ //Rejected	
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'R')";
	} else if($status=='dfr'){	//Deferred
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a  
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'T')";		
	} else if($status=='pwdo'){ //Pending with Concerned Officer	
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id
		from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a inner join fn_pet_action_first_last_cb_with(".$h_dept_user_id.") a1 on a1.petition_id=a.petition_id";
	} else if($status=='pending'){ //Pending with Disposing Officer
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R','T')) and not exists (select * from fn_pet_action_first_last_cb_with(".$h_dept_user_id.") d2 where d2.petition_id=a.petition_id)";
	} else if ($status=='pl1m') {
		$sub_sql="With off_pet as
		( select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R','T')) and not exists (select * from fn_pet_action_first_last_cb_with(".$h_dept_user_id.") d2 where d2.petition_id=a.petition_id)
		and (case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end)=1";
	} else if ($status == 'pg1m') {
		$sub_sql="With off_pet as
		( select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 		
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R','T')) and not exists (select * from fn_pet_action_first_last_cb_with(".$h_dept_user_id.") d2 where d2.petition_id=a.petition_id)
		and (case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60)  then 1 else 0 end)=1";
	} else if ($status == 'pg2m') {
		$sub_sql="With off_pet as
		(select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id from ".$function_name." a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on ".$join_condition.$dept_user_id." 
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.$pet_community_condition.$special_category_condition.$instruction_condition.")		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R','T')) and not exists (select * from fn_pet_action_first_last_cb_with(".$h_dept_user_id.") d2 where d2.petition_id=a.petition_id)
		and (case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end)=1";
	} 
	
	$sql="select aa.*, bb.lnk_docs from
	(select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_id, griev_subtype_name, pet_address, gri_address, 
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
		$sql = $sql.' order by griev_type_name, griev_subtype_name, petition_no';
	} else {
		$sql = $sql.' order by petition_id';
	}
	//echo $status.$sql;
	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();
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
			<?php echo "<b>Mobile:</b> ".$row['comm_mobile']."<br><br>"; ?>
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  
			echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']."<br>"; 
			?></a>
			<?php
				/* if ($row[lnk_docs] != '') {
					echo "<br><b>Linked To:</b> ".$row[lnk_docs];
				} */
			?>
			</td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:18%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']." / ".$row['pet_type_name']."&nbsp;"."<br><br><b>Office:</b> ".$row['gri_address']; ?></td>
            
<td class="desc"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
            
           <td class="desc"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?>
			
			<tr><th colspan="9" style="text-align:right;font-size:15px;"><i><b>Report prepared by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y");?></th></tr>
			<tr>
			<td colspan="9" class="buttonTD">
			<input type="button" name="" id="dontprint1" value="<?PHP echo "Print";?>" class="button" onClick="return printReportToPdf()">
            <input type="hidden" name="petition_no" id="petition_no" />
			<input type="hidden" name="petition_id" id="petition_id" />
			<input type="hidden" name="sort_on_type" id="sort_on_type">
			
	<input type="hidden" name="function_name" id="function_name" value="<?php echo $function_name; ?>"/>
		<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 	
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
	
<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $userProfile->getOff_level_id();?>" />
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
