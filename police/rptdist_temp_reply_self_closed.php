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
</script>

<?php
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
$pet_community = stripQuotes(killChars($_POST["pet_community"]));	
$special_category = stripQuotes(killChars($_POST["special_category"]));

$grie_dept_id=stripQuotes(killChars($_POST["dept"]));
$rep_src=stripQuotes(killChars($_POST["rep_src"]));
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

$conditions = "";
if(!empty($from_date)){
	$conditions.=" b.petition_date::date  >= '".$frm_dt."'::date";
}
if(!empty($to_date)){
	$conditions.=" AND b.petition_date::date  <= '".$to_dt."'::date";
}

if(!empty($src_id)){
	$conditions.= " AND b.source_id=".$src_id;
}

if(!empty($sub_src_id)){
	$conditions.= " AND b.source_id=".$sub_src_id;
}

if(!empty($gtypeid)){
	$conditions.= " AND b.griev_type_id=".$gtypeid;
}

if(!empty($gsubtypeid)){
	$conditions.= " AND b.griev_subtype_id=".$gsubtypeid;
}

if(!empty($dept)){
	$conditions.= "  AND b.dept_id=".$griedeptid;
}

if(!empty($petition_type)) {
	
	$conditions.= " and (b.pet_type_id=".$petition_type.")";	
}

if(!empty($pet_community)) {
	$conditions .= " and (b.pet_community_id=".$pet_community.")";
}
if(!empty($special_category)) {
	$conditions .= " and (b.petitioner_category_id=".$special_category.")";
}

	if(stripQuotes(killChars($_POST["office"]!="")) || $_SESSION["office"]!="") 
		 {
			  
			 if(stripQuotes(killChars($_POST["office"]))!="")
				 $off_loc_id.="".stripQuotes(killChars($_POST["office"]))."";
			 else
				 $off_loc_id.="".$_SESSION["hid_office"]."";
				  
			 //$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_firkadept_idhid"]));
			 $off_level_id=10;
			 
		 }
		else if(stripQuotes(killChars($_POST["firka"]!="")) || $_SESSION["hid_firka"]!="") {
				  
	 if(stripQuotes(killChars($_POST["firka"]))!="")
		 $off_loc_id ="".stripQuotes(killChars($_POST["firka"]))."";
	 else
		 $off_loc_id ="".$_SESSION["hid_firka"]."";
		  
     $off_level_id=5;
} else if( stripQuotes(killChars($_POST["taluk"]!="")) || $_SESSION["hid_taluk"]!="")  { 
	 if(stripQuotes(killChars($_POST["taluk"]))!="")
		 $off_loc_id.="".stripQuotes(killChars($_POST["taluk"]))."";
	 else
		 $off_loc_id.="".$_SESSION["hid_taluk"]."";
	  
	 $off_level_id=4;
} else if(stripQuotes(killChars($_POST["block"]!="")) || $_SESSION["hid_block"]!="") {
	 if(stripQuotes(killChars($_POST["block"]!="")))
		 $off_loc_id.="".stripQuotes(killChars($_POST["block"]))."";
	 else
		 $off_loc_id.="".$_SESSION["hid_block"]."";
					
	 $off_level_id=6;		 	  
} else if(stripQuotes(killChars($_POST["urban"]!="")) || $_SESSION["hid_urban"]!="") {
	 if(stripQuotes(killChars($_POST["urban"]))!="")
		 $off_loc_id.="".stripQuotes(killChars($_POST["urban"]))."";
	 else
		 $off_loc_id.="".$_SESSION["hid_urban"]."";
				 
	$off_level_id=7;			 
} else if(stripQuotes(killChars($_POST["rdo"]!="")) || $_SESSION["hid_rdo"]!="") {
	 if(stripQuotes(killChars($_POST["rdo"]))!=0 || stripQuotes(killChars($_POST["rdo"]!="")))
			$off_loc_id.="".stripQuotes(killChars($_POST["rdo"]))."";
	 else
			$off_loc_id.="".$_SESSION["hid_rdo"]."";

	 $off_level_id=3;
}  else if( stripQuotes(killChars($_POST["dist"]!=""))  || $_SESSION["hid_dist"]!="") {
				 
	 if(stripQuotes(killChars($_POST["dist"]!=""))){
		  $off_loc_id.="".stripQuotes(killChars($_POST["dist"]))."";
		  $dist_id.="".stripQuotes(killChars($_POST["dist"]))."";
	 }
	 else{
		   $off_loc_id.="".$_SESSION["hid_dist"]."";
		   $dist_id.="".$_SESSION["hid_dist"]."";
	 }
	 $off_level_id=2;
}  else if( stripQuotes(killChars($_POST["state"]!=""))) {
				 
	 if(stripQuotes(killChars($_POST["state"]!=""))){
		  $off_loc_id.="".stripQuotes(killChars($_POST["state"]))."";
	 }		  
	 
	 $off_level_id=1;
	 
}
if ($rep_src == 'simple') {
		$griedeptid = $userProfile->getDept_id();
		$off_level_id = $userProfile->getOff_level_id();
		$off_loc_id = $userProfile->getOff_loc_id();
}			 
$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	if ($disposing_officer != "") {	
	
	$sql = "SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
			pet_accept, pet_forward, pet_act_ret, pet_disposal,  
			desig_coordinating, dept_id, dept_name, dept_tname, dept_pet_process, 
			off_level_pattern_id,  dept_coordinating,

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


	$insql="SELECT a.petition_id
	FROM fn_pet_action_first_last_p_office(".$griedeptid.",".$off_level_id.",".$off_loc_id.") a 
	INNER JOIN pet_master b ON b.petition_id=a.petition_id
	WHERE ".$conditions." and a.action_type_code='T' and a.l_action_type_code in ('A','R')";
	
	$sql="select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period , v.pet_type_name
	from fn_pet_last_action_details(array(".$insql.")) v order by v.petition_id";

	$result = $db->query($sql);	
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$petcount = sizeof($rowarray);

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
	
    <tr><th colspan="14" class="main_heading"> Self Closed: &nbsp;&nbsp;&nbsp;(No of Petitions : <?php echo $petcount;?> ) </th></tr>
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
$i = 1;

foreach($rowarray as $row)
{
	if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
		$source_details = $row['source_name'].' & '.$row['subsource_name'];
	} else {
		$source_details = $row['source_name'];
	}
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
	$petitiondate=explode('/',$petition_date);
	$day=$petitiondate[2];
	$mnth=$petitiondate[1];
	$yr=$petitiondate[0];
	
	$pet_dt=$day.'-'.$mnth.'-'.$yr;
	
				
?>
	<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:14%;">
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">	
			<?PHP  echo $row['petition_no'].""."<br>Dt.&nbsp;".$petition_date; ?></a></td>
			<td class="desc" style="width:20%;"> <?PHP echo $pet_address //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:15%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<td class="desc wrapword" style="width:20%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
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
