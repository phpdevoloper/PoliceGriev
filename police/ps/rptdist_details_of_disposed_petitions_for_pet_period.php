<?php
ob_start();
session_start();
include("db.php");
include("header_report.php");
include("header_menu_report.php");
include("common_date_fun.php");
include("pm_common_js_css.php");

if(stripQuotes(killChars($_POST['hid_yes']))!="")
	$check=stripQuotes(killChars($_POST['hid_yes']));
else
	$check=$_SESSION["check"];

if($check=='yes')
{
$pagetitle="Department wise Report";
?>
  
<script type="text/javascript">
function detail_view(frm_date,to_date,desig_id,src,uploaded_officer)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("pet_entby").value=desig_id;
	document.getElementById("src_id").value=src;
	document.getElementById("uploaded_by").value=uploaded_officer;	
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_details_of_disposed_petitions_for_pet_period.php";
	document.rpt_abstract.target= "_blank";
	document.rpt_abstract.submit(); 
	return false;
}
</script>
<?php
if($check!="")
	$actual_link =basename($_SERVER['REQUEST_URI']); 
else
	$actual_link =basename(substr($_SERVER['REQUEST_URI'],0,-8));//"$_SERVER[REQUEST_URI]";

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
	
	$pet_type_cond = "";
	$reporttypename = "";	

	$src_id=stripQuotes(killChars($_POST["gsrc"]));
	$sub_src_id=stripQuotes(killChars($_POST["gsubsrc"]));
	$gtypeid=stripQuotes(killChars($_POST["gtype"]));
	$gsubtypeid=stripQuotes(killChars($_POST["gsubtype"]));
	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$pet_community = stripQuotes(killChars($_POST["pet_community"]));	
	$special_category = stripQuotes(killChars($_POST["special_category"]));
	$instructions=stripQuotes(killChars($_POST["disp_officer_instruction"]));
	$delagated=stripQuotes(killChars($_POST["delagated"]));
	
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
		
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid.") ";
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
	
	/* if ($pet_community != "") {
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
	} */
	
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
	/* if(!empty($pet_community)) {
		$pet_community_condition = " and (b.pet_community_id=".$pet_community.")";
	} */
	$special_category_condition = '';
	/* if(!empty($special_category)) {
		$special_category_condition = " and (b.petitioner_category_id=".$special_category.")";
	} */
	$instruction_condition = '';
	if(!empty($instructions)) {
		$instructions_new=str_replace("**"," & ",$instructions);
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ lower(a.f_action_remarks)::tsvector ";
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.f_action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
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
	
	 
?>
<div class="contentMainDiv" style="width:70%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="14" ><a href="" onclick="self.close();">
            <img src="images/bak.jpg" /></a></th></tr>
            <tr> 
				<th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="14" class="main_heading"><?php echo $label_name[86]; ?>&nbsp;&nbsp;&nbsp;<?php echo $reporttypename; ?> </th>
			</tr>
			<tr> 
				<th colspan="14" class="search_desc"><?php echo $label_name[48]; ?>&nbsp;&nbsp;&nbsp;<?php echo $label_name[1]; ?> : <?php echo $from_date; ?> 
				&nbsp;&nbsp;&nbsp;<?php echo $label_name[2]; ?> : <?php echo $to_date; ?> </th>
			</tr>
			
			
			<tr style="width:75%">
            <th colspan="2">
            <?php echo $label_name[22]; ?>
            </th>
            <th colspan="8">
            <?php echo $label_name[82]; ?>
            </th>
            <th colspan="4">
            <?php echo $label_name[87]; ?>
            </th>
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
			

	if ($pet_source != "") {
		$cond=" and source_id=".$pet_source; 
	}
	
	
	if ($delagated == "true") {
		$sql="select bb.dept_desig_name, bb.dept_user_id,bb.off_loc_name, aa.disposed_total from
		(select d_to_whom, count(*) disposed_total 
		from pet_action_first_last a
		inner join pet_master b on b.petition_id=a.petition_id
		where petition_date::date >= '".$frm_dt."'::date and  petition_date::date <= '".$to_dt."'::date	
		".$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition."  
		and l_action_type_code in ('A','R') and d_action_entby=".$userProfile->getDept_user_id()." and d_action_type_code='D'
		group by d_to_whom) aa
		inner join vw_usr_dept_users_v_sup bb on bb.dept_user_id=aa.d_to_whom
		order by bb.dept_desig_id, bb.dept_user_id";
	} else {
		$sql="select bb.dept_desig_name, bb.dept_user_id,bb.off_loc_name, aa.disposed_total from
		(select l_action_entby, count(*) disposed_total 
		from pet_action_first_last a
		inner join pet_master b on b.petition_id=a.petition_id
		where petition_date::date >= '".$frm_dt."'::date and  petition_date::date <= '".$to_dt."'::date	
		".$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition."  
		and l_action_type_code in ('A','R') and l_action_entby=".$userProfile->getDept_user_id()."
		group by l_action_entby) aa
		inner join vw_usr_dept_users_v_sup bb on bb.dept_user_id=aa.l_action_entby
		where bb.off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."
		order by bb.dept_desig_id, bb.dept_user_id";
	}
	
	
	//echo $sql;
	$result = $db->query($sql);
	$row_cnt = $result->rowCount();
	$temp_src_id='';
	$j=1;
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$SlNo=1;
	
	if($row_cnt!=0) {
		 
		foreach($rowarray as $row)
		{			
			$dept_desig_name=$row['dept_desig_name'].' - '.$row['off_loc_name'];
			$disposed_total=$row['disposed_total'];
			
			$source_name=$row['source_name'];
			$source_id=$row['source_id'];
			$dept_desig_id = $row['dept_user_id'];
			
			if($temp_src_id!=$source_id) 
			{
				$temp_src_id=$source_id;
	 
			?>
				<tr>
					<td class="h1" style="text-align:left" colspan="14"><?PHP echo $source_name; ?></td>
				</tr>			
			<?php 			
				$j++;
			 	$i=1;
			} 
?>
 		  
				<tr>
				<td colspan="2"  style="text-align:center"><?php echo $i;?></td>
				<td colspan="8" style="text-align:left;width: 65%;"><?PHP echo $dept_desig_name; ?></td>
				
				<td colspan="4" style="text-align:center">
				
				<?php if($disposed_total!=0){?>
					<a href="" onclick="return detail_view('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $dept_desig_id; ?>','<?php echo $pet_source; ?>','<?PHP echo $dept_desig_name; ?>')"> <?php echo $disposed_total;?> </a> 
				<?php } else{?>
				<?php echo $disposed_total;?> </td> 
				<?php } ?>
				
				</td>
				</tr>               

			<?php  
			$i++;			 
			$tot_cnt = $tot_cnt + $disposed_total;
			
			}
			?>
			<tr class="totalTR">
                <td colspan="10"><?php echo $label_name[25]; ?></td>
                
                <td colspan="4" style="text-align:center"><?php echo $tot_cnt;?></td>
               
			</tr>
			
			<tr>
			<td colspan="12" class="buttonTD">             
			<input type="button" name="" id="dontprint1" value="<?php echo $label_name[26]; ?>" class="button" onClick="return printReportToPdf()" />             
			<input type="hidden" name="hid" id="hid" />
			<input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
			<input type="hidden" name="frdate" id="frdate"  />
			<input type="hidden" name="todate" id="todate" />
			<input type="hidden" name="src_id" id="src_id" />
			<input type="hidden" name="pet_entby" id="pet_entby" />
			<input type="hidden" name="uploaded_by" id="uploaded_by" />
			<input type="hidden" name="reporttypename" id="reporttypename" value="<?php echo $reporttypename; ?>"/>
			<input type="hidden" name="pet_type_id" id="pet_type_id" value="<?php echo $petition_type; ?>"/>  

			<input type="hidden" name="gsrc" id="gsrc" value="<?php echo $src_id; ?>"/>
			<input type="hidden" name="gsubsrc" id="gsubsrc" value="<?php echo $sub_src_id; ?>"/>
			<input type="hidden" name="gtype" id="gtype" value="<?php echo $gtypeid; ?>"/>
			<input type="hidden" name="gsubtype" id="gsubtype" value="<?php echo $gsubtypeid; ?>"/>
			<input type="hidden" name="grie_dept_id" id="grie_dept_id" value="<?php echo $grie_dept_id; ?>"/>			
			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/>
			<input type="hidden" name="pet_community" id="pet_community" value="<?php echo $pet_community; ?>"/> 
			<input type="hidden" name="special_category" id="special_category" value="<?php echo $special_category; ?>"/> 
		<input type="hidden" name="instructions" id="instructions" value="<?php echo $instructions; ?>"/>
		<input type="hidden" name="delagated" id="delagated" value="<?php echo $delagated; ?>"/>
			</td></tr>
			 
			<tr id="bak_btn1"><td colspan="12" style="text-align: center;background-color: #BC7676;"><a href="" onclick="self.close();">
			<img src="images/bak.jpg" style="height: 25px;width: 45px;"/></a></td></tr>
			
<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo $label_name[38]; //No Records Found?>...</td></tr>
         </table>
         
<?php } ?>
        </tbody>
        </table>
 		 
	</div>
</div>
<?php  		
	if(stripQuotes(killChars($_POST["dist_rpt"]))!="")
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
} 
?> 
<?php
if(stripQuotes(killChars($_POST['hid']))=='done')
{
ob_start();
session_start();
include("db.php"); 
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
$rep_src=stripQuotes(killChars($_POST["rep_src"])); 
$from_date=stripQuotes(killChars($_POST["frdate"])); 
$to_date=stripQuotes(killChars($_POST["todate"])); 
$_SESSION["from_date"]=$from_date;
$pet_type_id=stripQuotes(killChars($_POST["pet_type_id"]));
$reporttypename=stripQuotes(killChars($_POST["reporttypename"]));
$uploaded_by=stripQuotes(killChars($_POST["uploaded_by"]));

$pet_type_cond="";
if ($pet_type_id != "") {
	$pet_type_cond = " and a.pet_type_id=".$pet_type_id;
}
$pet_entby=stripQuotes(killChars($_POST["pet_entby"]));

$src_id=stripQuotes(killChars($_POST["gsrc"]));
	$sub_src_id=stripQuotes(killChars($_POST["gsubsrc"]));
	$gtypeid=stripQuotes(killChars($_POST["gtype"]));
	$gsubtypeid=stripQuotes(killChars($_POST["gsubtype"]));
	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$pet_community=stripQuotes(killChars($_POST["pet_community"]));
	$special_category=stripQuotes(killChars($_POST["special_category"]));
	$instructions=stripQuotes(killChars($_POST["instructions"]));
	$delagated=stripQuotes(killChars($_POST["delagated"]));
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
		
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid.") ";
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
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ lower(a.f_action_remarks)::tsvector ";
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.f_action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
	}
$_SESSION["check"]="yes"; 

$qry="select dept_desig_name,dept_desig_tname from usr_dept_desig a    
		inner join usr_dept_users b on b.dept_desig_id=a.dept_desig_id
		where b.dept_user_id=".$pet_entby."";
		
		$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$dept_desig_name = $rowArr['dept_desig_name'];	
	}else{
		$dept_desig_name = $rowArr['dept_desig_tname'];
	}
}

?>

<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
				<tr id="bak_btn"><th colspan="8" > 
				<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
				</th></tr>
                
				<tr> 
				<th colspan="8" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			    </tr>
                
			 <tr> 
				<th colspan="8" class="main_heading"><?php echo $label_name[86].$reporttypename; ?> </th>
			 </tr>
			
			 <tr> 
				<th colspan="8" class="main_heading"><?php echo $label_name[82]; ?>: <?php echo $uploaded_by; ?> </th>
			 </tr>
                <th colspan="8" class="search_desc"><?PHP echo $label_name[48]; //Disposed Period?> &nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?> &nbsp;&nbsp;<?PHP echo $label_name[2]." : "; //To Date?>  
				<?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo $label_name[22]; //S.No.?></th>
				<th><?PHP echo $label_name[27]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[28]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[29]; //Source & Sub Source?> & <?PHP echo $label_name[30]; //Source Remarks?> </th>
				<!--<th><?PHP //echo $label_name[30]; //Source Remarks?></th>-->
				<th><?PHP echo $label_name[31]; //Grievance?></th>
				<th><?PHP echo $label_name[32]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[33]; //Action Type, Date & Remarks?></th>
                <th><?PHP echo $label_name[34]; //Pending Period?></th>
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
 
	
	if(!empty($src_id)){
		 $cond.=" and b.source_id= '".$src_id."' ";
		   
	}

	if ($delagated == "true") {
		$insql="select a.petition_id
		from pet_action_first_last a
		inner join pet_master b on b.petition_id=a.petition_id
		where petition_date::date >= '".$frm_dt."'::date and  petition_date::date <= '".$to_dt."'::date
		and l_action_type_code in ('A','R')	and d_action_entby=".$userProfile->getDept_user_id()." and d_action_type_code='D'		
		".$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." 
		and l_action_entby=".$pet_entby;
	} else {	
		$insql="select a.petition_id
		from pet_action_first_last a
		inner join pet_master b on b.petition_id=a.petition_id
		where petition_date::date >= '".$frm_dt."'::date and  petition_date::date <= '".$to_dt."'::date
		and l_action_type_code in ('A','R')			
		".$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." 
		and l_action_entby=".$pet_entby;
	}
	
	$sql=" -- pending: op. bal. 
	select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period,pet_type_name 
	from fn_petition_details(array(".$insql.")) order by petition_id"; 

	$result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			if ($row[subsource_name] != null || $row[subsource_name] != "") {
				$source_details = ucfirst(strtolower($row[source_name])).' & '.ucfirst(strtolower($row[subsource_name]));
			} else {
				$source_details = ucfirst(strtolower($row[source_name]));
			}
			
			?>
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:14%;"> <a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<!--<td class="desc"><?php //echo $row[subsource_remarks];?></td>-->
			<td class="desc wrapword" style="width:19%;"> <?PHP echo $row['grievance'] ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$row['gri_address']; ?></td>
            
<td class="desc" style="width:24%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
            
            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
			<tr>
			<td colspan="8" class="buttonTD">

			<input type="button" name="" id="dontprint1" value="<?PHP echo "Print";?>" class="button" onClick="return printReportToPdf()">
            <input type="hidden" name="petition_no" id="petition_no" />
			<input type="hidden" name="petition_id" id="petition_id" />
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
