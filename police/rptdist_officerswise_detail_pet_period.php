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
function detail_view(frm_date,to_date,dept,dept_name,dept_user_id,dept_designation,status,src_id,sub_src_id,gtypeid,gsubtypeid,grie_dept_id,off_cond_para,fn_name,off_loc_id,off_level_id)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("dept_user_id").value=dept_user_id;
	document.getElementById("status").value=status;
	document.getElementById("dept_designation").value=dept_designation;		
	document.getElementById("src_id").value=src_id;
	document.getElementById("sub_src_id").value=sub_src_id;
	document.getElementById("gtypeid").value=gtypeid;
	document.getElementById("gsubtypeid").value=gsubtypeid;
	document.getElementById("grie_dept_id").value=grie_dept_id;
	document.getElementById("off_cond_para").value=off_cond_para;
	document.getElementById("fn_name").value=fn_name;
	document.getElementById("dept_id").value=dept;		
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_officerswise_detail_pet_period.php";
	document.rpt_abstract.target= "_blank";		
	document.rpt_abstract.submit(); 
	return false;
}
</script>
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
if (($userProfile->getDept_desig_id() == 12) || ($userProfile->getDept_desig_id() == 14)) {
		$userProfile = unserialize($_SESSION['PROXY_USER_PROFILE']);	
} else {
	//$userProfile = unserialize($_SESSION['USER_PROFILE']);	
}
$off_loc_id = stripQuotes(killChars($_POST["office"]));
$p_office_level = stripQuotes(killChars($_POST["p_office_level"]));
$p_dept_off_level_pattern_id = stripQuotes(killChars($_POST["p_dept_off_level_pattern_id"]));

if ($p_dept_off_level_pattern_id == 1) {
	if ($p_office_level == 9) {
		$off_loc = "SELECT zone_name, zone_tname FROM mst_p_sp_zone where zone_id=".$off_loc_id."";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Office Name: ".$off_loc_rw[0];		
	} else if ($p_office_level == 11) {
		$off_loc = "SELECT range_name, range_tname FROM mst_p_sp_range where range_id=".$off_loc_id."";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Office Name: ".$off_loc_rw[0];
	} else if ($p_office_level == 13) {
		$off_loc = "SELECT district_name, district_tname FROM mst_p_district where district_id=".$off_loc_id."";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Office Name: ".$off_loc_rw[0];
	} else if ($p_office_level == 42) {
		$off_loc = "SELECT division_name, division_tname FROM mst_p_sp_division where division_id=".$off_loc_id."";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Office Name: ".$off_loc_rw[0];
	}
}
/*  if(stripQuotes(killChars($_POST["office"]!="")) || $_SESSION["office"]!="") 
 
 {
	  
	 if(stripQuotes(killChars($_POST["office"]))!="")
		 $off_loc_id.="".stripQuotes(killChars($_POST["office"]))."";
	 else
		 $off_loc_id.="".$_SESSION["hid_office"]."";
		  
	 $p_off_level_id=10;
	  $off_level_id=10;
	 $p_off_loc_id=$off_loc_id;
	 $off_loc = "SELECT division_name, division_tname FROM mst_p_sp_division where division_id='$off_loc_id'";
	 $off_loc_rs=$db->query($off_loc);
	 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
	 $off_loc_name= "division: ".$off_loc_rw[0];				 
	 $off_loc_cond = "10,".$off_loc_id.",null,'{10,11}'";
 }  */
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
			
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$pet_community = stripQuotes(killChars($_POST["pet_community"]));	
	$special_category = stripQuotes(killChars($_POST["special_category"]));

	$p_dept_off_level_pattern_id = stripQuotes(killChars($_POST["p_dept_off_level_pattern_id"]));
	$pattern_id = stripQuotes(killChars($_POST["p_dept_off_level_pattern_id"]));
	$p_office_level = stripQuotes(killChars($_POST["p_office_level"]));
	$p_office = stripQuotes(killChars($_POST["office"]));
	$dept=1;
	$p_subordinatelevel = explode("-", $p_office_level);
	$p_off_level_id = $p_subordinatelevel[0];
	$off_level_id = $p_subordinatelevel[0];
	$p_off_level_dept_id=$p_subordinatelevel[1];
	$p_subordinate_level = $p_off_level_id;
	$subordinate_level = $p_subordinate_level;
	


	$grev_dept_condition = "";
	$off_type = $_POST["offtype"];		

	//For Addtional Parameters
	//Source and Sub-Source	
  	$src_condition = "";
	if(!empty($src_id)) {
		$src_condition = " and (c.source_id=".$src_id.")";
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		$src_condition = " and (c.source_id=".$src_id." and c.subsource_id=".$sub_src_id.")";
	}
	
	//For Addtional Parameters
	//Grev type and Grev Subtype Condition		
	$grev_condition = "";
	if(!empty($gtypeid)) {
		$grev_condition = " and (c.griev_type_id=".$gtypeid.")";
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		$grev_condition = " and (c.griev_type_id=".$gtypeid." and c.griev_subtype_id=".$gsubtypeid.")";	
	}
	
	$reporttypename = "";
		
	if ($src_id != "") {
			$sql="select source_id,source_name from lkp_pet_source where source_id=".$src_id;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$sourcename=$row[1];
			$reporttypename = "Source: ".$sourcename;	
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
		$petition_type_condition = "";
		if(!empty($petition_type)) {
			$petition_type_condition = " and (c.pet_type_id=".$petition_type.")";
		}
		$pet_community_condition = '';
		if(!empty($pet_community)) {
			$pet_community_condition = " and (c.pet_community_id=".$pet_community.")";
		}
		$special_category_condition = '';
		if(!empty($special_category)) {
			$special_category_condition = " and (c.petitioner_category_id=".$special_category.")";
		}
		
		$off_loc_cond = ""; 
		
		$pet_own_heading = "";
		if ($pet_own_dept_name != "") {
			$pet_own_heading = "";
		}
		
		if ($off_loc_name != "") {
			$pet_own_heading = $off_loc_name;
		}

?>
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="10" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
			</th></tr>
            <tr> 
				<th colspan="10" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="10" class="main_heading"><?PHP echo $label_name[0]; //Department wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="10" class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="10" class="search_desc"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="10" class="search_desc"><b>Petition Period -  </b><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="2"  style="width:3%;"><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="2"  style="width:20%;"><?PHP echo $label_name[40]; //Concerned Officer?></th>
                <th colspan="8" style="width: 70%;"><?PHP echo $label_name[4].':     ( E = A - (B + C + D)     and      F + G + H = E )';//Number Of Petitions?></th>


				</tr>
				<tr>
                <th style="width:10%;"><?PHP echo $label_name[6]; //Received?><br>(A)</th>
                <th style="width:10%;"><?PHP echo $label_name[8]; //Closed?><br>(B)</th>
				<th style="width:10%;"><?PHP echo $label_name[9]; //Closed?><br>(C)</th>
                <th style="width:10%;"><?PHP echo $label_name[67]; //Closing Balance?><br>(D)</th>
                <th style="width:10%;"> <?PHP echo $label_name[61]; //Pending for more than 2 months?><br>(E)</th>
        	 	<th style="width:10%;"> <?PHP echo $label_name[11];; //Pending for 2 months?><br>(F)</th>
            	<th style="width:10%;"> <?PHP echo $label_name[12];; //Pending for 1 month?><br>(G)</th>
            	<th style="width:10%;"> <?PHP echo $label_name[13];; //Pending for less than 1 month?><br>(H)</th>
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
			
 	

		$source_from = $_POST["source_from"];
		
		$dist_cond=$userProfile->getOff_level_id()==1 ? "mst_p_district" : "mst_p_district";
		
		//$dept_off_level_cond=$userProfile->getDesig_coordinating() ? ",null,'{2,3,4,5,6,7,10,11}'" : ",'{".$userProfile->getDept_id()."}','{2,3,4,5,6,7,10,11}'";
		
		$off_condition = "";
		if ($off_type == "S"){

		} else if ($off_type == "P"){
			$off_condition = " and b1.off_level_dept_id = ".$p_off_level_dept_id." and b1.off_loc_id = ".$office;
			$off_cond_para = "P-".$dept."-".$off_level_dept_id."-".$off_loc_id;
			$fn_name = "fn_pet_action_received_cb_from_any() ";
			
			if ($p_off_level_id == 7) {
				$dist_cond= "(select * from mst_p_state where state_id=33)";
				$off_loc_cond= "7,33,null,null";
				$dist_params_1 = 'state_id,state_name,';
				$dist_params_2 = 'aa.state_id,aa.state_name,';
			} else if ($p_off_level_id == 9) {
				$dist_cond= "(select * from mst_p_sp_zone where zone_id=".$p_office.")";
				$off_loc_cond= "9,".$p_office.",null,null";
				$dist_params_1 = 'zone_id,zone_name,';
				$dist_params_2 = 'aa.zone_id,aa.zone_name,';
			} else if ($p_off_level_id == 11) {
				$dist_cond= "(select * from mst_p_sp_range where range_id=".$p_office.")";
				$off_loc_cond= "11,".$p_office.",null,null";
				$dist_params_1 = 'range_id,range_name,';
				$dist_params_2 = 'aa.range_id,aa.range_name,';
			} else if ($p_off_level_id == 13) {
				$dist_cond= "(select * from mst_p_district where district_id=".$p_office.")";
				$off_loc_cond= "13,".$p_office.",null,null";
				$dist_params_1 = 'district_id,district_name,';
				$dist_params_2 = 'aa.district_id,aa.district_name,';
			}
		} 
		
		if ($off_level_dept_id != "") {
			$result = $db->query("select off_level_id from usr_dept_off_level where off_level_dept_id=".$off_level_dept_id);
			$off_level = $result->fetch(PDO::FETCH_BOTH);
			$off_level_id= $off_level[0];
		}

		$our_off_condition = "";
		if ($source_from == "sup"){
			$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
			$our_off_condition = "  and b.dept_id = ".$userProfile->getDept_id()." and b.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and b.off_loc_id = ".$userProfile->getOff_loc_id();
		}
				
		$coord_cond = "";
		if ($source_from == "sub"){
			$coord_cond = $userProfile->getDesig_coordinating() ? 'cc.dept_user_id <> '.$userProfile->getDept_user_id() : '(cc.dept_user_id = '.$userProfile->getDept_user_id().' or cc.off_level_id >= '.$userProfile->getOff_level_id().')';
			if ($off_type == 'P') {
			//	$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
				//$dist_cond="lkp_griev_type bb";
				//$dist_params = '';
			} else if ($off_type == 'S') {
				//echo "SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS";
				$dept=$userProfile->getDept_id(); // substr($sub_dept, 0,1);
				$t1=($s_district== '') ? "" : " inner join fn_single_district(".$s_district.") c on c.district_id=b.griev_district_id ";
				$tbl_pet_list = "fn_pet_action_first_last_off_level(".$dept.",".$s_office.")";
				$tbl_pet_list = "fn_pet_action_first_last_off_level_wo_dept(".$off_level_id.")";
				$dist_cond="lkp_griev_type ";
				$dist_params = '';	
				$user_dept_condition= ($userProfile->getDept_coordinating() == true) ? "":" inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a1.dept_id=".$userProfile->getDept_id();	
			}
			if ($subordinate_level == 7) {
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
				if($pattern_id!=''){
					$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",3)";
				}
			} else if ($subordinate_level == 9) {
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
			} else if ($subordinate_level == 11) {
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
			} else if ($subordinate_level == 13) {
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";				
			}
			/* if ($userProfile->getOff_level_id() != 1) {
				$petition_location_condition = " and b.griev_district_id=".$userProfile->getDistrict_id();
			} 
			if ($s_office == 2) {
				$online_petition_condition = ($src_id == 5) ? " and source_id=5 and fwd_office_level_id in (20,40) " : " and fwd_office_level_id in (20,40) ";
			} else if ($s_office == 3) {
				$online_petition_condition = ($src_id == 5) ? " and source_id=5 and b.dept_id=1 and fwd_office_level_id = 40 " : " and b.dept_id=1 and fwd_office_level_id in (40) ";
			} else if ($s_office == 4) {
				$online_petition_condition = ($src_id == 5) ? " and source_id=5 and b.dept_id=1 and fwd_office_level_id = 40 " : " and b.dept_id=1 and fwd_office_level_id in (40) ";
			}
			//}
			if ($userProfile->getOff_level_id()==1) {}
			else if ($userProfile->getOff_level_id()==2) {
			if($userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getDesig_coordinating()){
				$condition_for_pag=" and b.source_id!=5 ";
			}
			else if ($userProfile->getDept_desig_id()==16) {
				$condition_for_pag=" and b.source_id=5 and b.dept_id=1 ";				
			}
			}
			else if ($userProfile->getOff_level_id()==3) {
				$condition_for_pag=" and b.dept_id=1 ";				
			}
			else if ($userProfile->getOff_level_id()==4) {
				$condition_for_pag=" and b.dept_id=1 ";							
			} */
   
		} else if ($source_from == "sup"){
			$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
			$our_off_condition = " inner join vw_usr_dept_users_v b1 on b1.dept_user_id=a.to_whom and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getOff_loc_id();
			$dist_cond="lkp_griev_type ";
			$dist_params = '';
		} 		
		 else if ($source_from == "sup") {
			$coord_cond = '(cc.dept_user_id = '.$userProfile->getDept_user_id().' or cc.off_level_id >= '.$userProfile->getOff_level_id().')';
		}
					
		
		
		$sql="With off_pet as
		(
		select a.petition_id, c.petition_date, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id,a.off_level_id as off_level_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date >= '".$frm_dt."'::date and c.petition_date <= '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition."
		)

		select ".$dist_params_1." 
		dept_id, dept_name, 
		off_level_dept_id, off_level_dept_name, dept_desig_id, dept_desig_name, off_loc_id, off_loc_name, dept_user_id, recd_cnt, acp_cnt, rjct_cnt, no_act_cnt, cl_pend_cnt, cl_pend_leq30d_cnt,cl_pend_gt30leq60d_cnt,cl_pend_gt60d_cnt ,off_level_id

		from 

		( select ".$dist_params_2." 
		bb.dept_id, bb.dept_name, 
		cc.off_level_dept_id, cc.off_level_dept_name, cc.dept_desig_id, cc.dept_desig_name, cc.off_loc_id, cc.off_loc_name, cc.dept_user_id, COALESCE(recd.recd_cnt,0) as recd_cnt, COALESCE(acp.acp_cnt,0) as acp_cnt, COALESCE(rjct.rjct_cnt,0) as rjct_cnt, COALESCE(no_act.no_act_cnt,0) as no_act_cnt, COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt, COALESCE(clb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, COALESCE(clb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, COALESCE(clb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt ,off_level_id

		from 
		".$dist_cond." aa 
		cross join usr_dept bb 
		inner join 
		fn_usr_dept_users_vhr(".$off_loc_cond.") cc on cc.dept_id = bb.dept_id 
		and cc.pet_act_ret = true and ".$coord_cond." 

		left join 

		-- received for action 

		( select off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as recd_cnt from off_pet a
		group by off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) recd on  recd.off_level_dept_id=cc.off_level_dept_id and recd.off_loc_id=cc.off_loc_id and recd.dept_desig_id=cc.dept_desig_id and recd.dept_user_id=cc.dept_user_id 

		left join 

		-- accepted 

		( select off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as acp_cnt from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'A') 
		group by off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) acp on  acp.off_level_dept_id=cc.off_level_dept_id and acp.off_loc_id=cc.off_loc_id and acp.dept_desig_id=cc.dept_desig_id and acp.dept_user_id=cc.dept_user_id 

		left join 

		-- rejected 

		( select off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as rjct_cnt from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'R') 
		group by off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) rjct on  rjct.off_level_dept_id=cc.off_level_dept_id and rjct.off_loc_id=cc.off_loc_id and rjct.dept_desig_id=cc.dept_desig_id and rjct.dept_user_id=cc.dept_user_id 

		left join 

		-- pending with Others 

		( select off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as no_act_cnt from off_pet a 
		where exists (select * from pet_action_first_last a1 where a1.petition_id=a.petition_id and a1.l_to_whom<>a.dept_user_id and a1.l_action_type_code not in ('A','R')) 
		group by off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) no_act on  no_act.off_level_dept_id=cc.off_level_dept_id and no_act.off_loc_id=cc.off_loc_id and no_act.dept_desig_id=cc.dept_desig_id and no_act.dept_user_id=cc.dept_user_id 

		left join 

		-- pending with the concerned officer 

		( select off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as cl_pend_cnt, sum(case when (current_date - a.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, sum(case when ((current_date - a.petition_date::date) > 30 and (current_date - a.petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, sum(case when (current_date - a.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
		from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and exists (select * from pet_action_first_last d2 where d2.petition_id=a.petition_id and d2.l_to_whom=a.dept_user_id) 
		group by off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id ) clb on  clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id ) b_rpt 
		where recd_cnt+acp_cnt+rjct_cnt+no_act_cnt+cl_pend_cnt > 0 		
		order by b_rpt.off_level_dept_id, b_rpt.dept_desig_id, b_rpt.off_loc_name";
		
		//echo $sql;
	    $result = $db->query($sql);
		$row_cnt = $result->rowCount();
		//echo '--'.$row_cnt;
		$temp_dept_id='';
		$j=1;
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		
if($row_cnt!=0)
	{
		$arrlength = count($rowarray);
		$x = 0;	
		while($x < $arrlength) {
		
			$ary=$rowarray[$x];
			
			$dept_id=$ary[dept_id];
			$dept_name=$ary[dept_name];
			
			$tot_recd_cnt = 0;
			$tot_acp_cnt = 0;
			$tot_rjct_cnt = 0;
			$tot_pwou_cnt = 0;
			$tot_cl_pend_cnt = 0;
			$tot_cl_pend_m2m_ct = 0;
			$tot_cl_pend_2m_cnt =  0;
			$tot_cl_pend_1m_cnt = 0;
						
			/* if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id; */
	 
			?>
			
           <tr style="display:none;">
           		<td class="h1" style="text-align:left" colspan="10"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			//} 
			
			while ($ary['dept_id']==$dept_id) {
				$source_id=$ary['source_id'];
				
				$source_name=$ary['source_name'];
				$dept_name=$ary['dept_name']; 
				$dept_desig=$ary['dept_desig_name']." - ".$ary['off_loc_name'];
				$dept_user_id = $ary['dept_user_id'];
				$op_bal = $ary['dept_name'];
				$off_level_id=$ary['off_level_id'];
				$off_loc_id=$ary['off_loc_id'];			
				$recd_cnt = $ary['recd_cnt']; //1 Received 
				$acp_cnt = $ary['acp_cnt'];   //2 Accepted
				$rjct_cnt = $ary['rjct_cnt']; //3 Rejected
				$no_act_cnt =  $ary['no_act_cnt']; //4 Pending with Disposing Officer
				$cl_pend_cnt = $ary['cl_pend_cnt'];	//5	 Pending Petitions	
				$cl_pend_leq30d_cnt = $ary['cl_pend_leq30d_cnt']; //6 Pending for <30 Days
				$cl_pend_gt30leq60d_cnt = $ary['cl_pend_gt30leq60d_cnt']; //7 Pending for >30 and <60 days
				$cl_pend_gt60d_cnt = $ary['cl_pend_gt60d_cnt']; //8 Pending for >60 Days
			?>
			
			<tr>
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_desig; ?></td>
                              
                 <!-- 1 Received-->
                 <?php if($recd_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'recd'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $recd_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $recd_cnt;?> </td> <?php } ?>

		<!-- 2 Accepted -->
		<?php if($acp_cnt!=0) {?>
		<td><span style="color:#00BF00;"><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>','<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>', '<?php echo 'acpt'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>', '<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $acp_cnt;?> </a></span></td>  
		<?php } else {?>
		<td><?php echo $acp_cnt;?> </td> <?php } ?>
                                
		<!-- 3 Rejected -->
		<?php if($rjct_cnt>0) {?>
		<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'rjct'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $rjct_cnt;?> </a></td>
		<?php } 
		else {?>
		<td><?php echo $rjct_cnt;?> </td> <?php } ?>
                
		<!-- 4 Pending with Disposing Officer -->
		<?php if($no_act_cnt!=0) {?>
		<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pwdo'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $no_act_cnt;?> </a></td>
		<?php } else {?>
		<td><?php echo $no_act_cnt;?> </td> <?php } ?>
                 
		<!-- 5 Pending Petitions -->
		<?php if($cl_pend_cnt!=0) {?>
		<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pending'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $cl_pend_cnt;?> </a></td>
		<?php } else {?>
		<td><?php echo $cl_pend_cnt;?> </td> <?php } ?>
                
		<!-- 6 Pending for <30 Days -->
		<?php if($cl_pend_leq30d_cnt!=0) {?>
		<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pl1m'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $cl_pend_leq30d_cnt;?> </a></td>
		<?php } else {?>
		<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>

		<!-- 7 Pending for >30 and <60 Days -->
		<?php if($cl_pend_gt30leq60d_cnt!=0) {?>
		<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg1m'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>')"><?php echo $cl_pend_gt30leq60d_cnt;?> </a></td>
		<?php } else {?>
		<td><?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>
				
                                                 
               
		<!-- 8 Pending for >60 Days -->
		<?php if($cl_pend_gt60d_cnt!=0) {?>
		<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
		'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>','<?php echo 'pg2m'; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>',	'<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $fn_name; ?>','<?php echo $off_loc_id; ?>','<?php echo $off_level_id; ?>' )"><?php echo $cl_pend_gt60d_cnt;?> </a></td>
		<?php } else {?>
		<td><?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>
				
                      	
			</tr>
			<?php  
		$i++;		

		$ary=$rowarray[++$x];
		$tot_recd_cnt = $tot_recd_cnt + $recd_cnt;
		$tot_acp_cnt = $tot_acp_cnt + $acp_cnt;
		$tot_rjct_cnt = $tot_rjct_cnt + $rjct_cnt;
		$tot_pwou_cnt = $tot_pwou_cnt + $no_act_cnt;
		$tot_cl_pend_cnt = $tot_cl_pend_cnt + $cl_pend_cnt;
		$tot_cl_pend_m2m_ct = $tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
		$tot_cl_pend_2m_cnt =  $tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
		$tot_cl_pend_1m_cnt = $tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
		
		$grand_tot_recd_cnt = $grand_tot_recd_cnt + $recd_cnt;
		$grand_tot_acp_cnt = $grand_tot_acp_cnt + $acp_cnt;
		$grand_tot_rjct_cnt = $grand_tot_rjct_cnt + $rjct_cnt;
		$grand_tot_pwou_cnt = $grand_tot_pwou_cnt + $no_act_cnt;
		$grand_tot_cl_pend_cnt = $grand_tot_cl_pend_cnt + $cl_pend_cnt;
		$grand_tot_cl_pend_m2m_ct = $grand_tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
		$grand_tot_cl_pend_2m_cnt =  $grand_tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
		$grand_tot_cl_pend_1m_cnt = $grand_tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
			
			}
		
			?>
			
 			<!--<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Department Total' ?></td>
                
                <td><?php //echo $tot_recd_cnt;?></td>                      
                <td><?php //echo $tot_acp_cnt;?></td>
           		<td><?php //echo $tot_rjct_cnt;?></td>
           		<td><?php //echo $tot_pwou_cnt;?></td>
            	<td><?php //echo $tot_cl_pend_cnt;?></td>
				<td><?php //echo $tot_cl_pend_m2m_ct;?></td>
				<td><?php //echo $tot_cl_pend_2m_cnt;?></td>
				<td><?php//echo $tot_cl_pend_1m_cnt;?></td>
                
			</tr> -->
			
			
		<?php } ?>
		
                <td colspan="2"><?PHP echo 'Grand Total' ?></td>
                
                <td><?php echo $grand_tot_recd_cnt;?></td>                      
                <td><?php echo $grand_tot_acp_cnt;?></td>
           		<td><?php echo $grand_tot_rjct_cnt;?></td>
           		<td><?php echo $grand_tot_pwou_cnt;?></td>
            	<td><?php echo $grand_tot_cl_pend_cnt;?></td>
				<td><?php echo $grand_tot_cl_pend_m2m_ct;?></td>
				<td><?php echo $grand_tot_cl_pend_2m_cnt;?></td>
				<td><?php echo $grand_tot_cl_pend_1m_cnt;?></td>
                
			</tr>
			<?php 
			$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();
			?>
			<tr><th colspan="10" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
		<tr class="totalTR">
			<tr>
            <td colspan="10" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
			<input type="hidden" name="pattern_id" id="pattern_id" value='<?php echo $pattern_id; ?>'/>
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
			
			<input type="hidden" name="src_id" id="src_id" />
    		<input type="hidden" name="sub_src_id" id="sub_src_id" />
            <input type="hidden" name="gtypeid" id="gtypeid" />
            <input type="hidden" name="gsubtypeid" id="gsubtypeid" />
            <input type="hidden" name="grie_dept_id" id="grie_dept_id" />
			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/>
			<input type="hidden" name="pet_community" id="pet_community" value="<?php echo $pet_community; ?>"/> 
			<input type="hidden" name="special_category" id="special_category" value="<?php echo $special_category; ?>"/> 
			<input type="hidden" name="off_cond_para" id="off_cond_para" />
			<input type="hidden" name="fn_name" id="fn_name" />
			<input type="hidden" name="dept_condition" id="dept_condition" value="<?php echo $grev_dept_condition; ?>"/>
			<input type="hidden" name="pet_own_heading" id="pet_own_heading" value="<?php echo $pet_own_heading; ?>"/>
			<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 	

			<input type="hidden" name="dept_p_office" id="dept_p_office" value='<?php echo $dept_p_office ?>'/>	
			<input type="hidden" name="dept_id" id="dept_id" value='<?php echo $dept_id; ?>'/>
			<input type="hidden" name="p_off_level_id" id="p_off_level_id" value='<?php echo $p_off_level_id; ?>'/>
			<input type="hidden" name="p_off_loc_id" id="p_off_loc_id" value='<?php echo $p_office; ?>'/>
			<input type="hidden" name="off_loc_id" id="off_loc_id" value='<?php echo $off_loc_id; ?>'/>
			<input type="hidden" name="off_level_id" id="off_level_id" value='<?php echo $off_level_id; ?>'/>
			<input type="hidden" name="subordinate_level" id="subordinate_level" value='<?php echo $subordinate_level; ?>'/>
			
			<input type="hidden" name="source_frm" id="source_frm" value="<?php echo $source_from; ?>"/>
            </td></tr>
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
</script>
 
<?php
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

$src_id = stripQuotes(killChars($_POST["src_id"]));	  
$sub_src_id = stripQuotes(killChars($_POST["sub_src_id"]));	
$gtypeid = stripQuotes(killChars($_POST["gtypeid"]));	  
$gsubtypeid = stripQuotes(killChars($_POST["gsubtypeid"]));
$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$pet_community=stripQuotes(killChars($_POST["pet_community"]));
$special_category=stripQuotes(killChars($_POST["special_category"]));
$dept=stripQuotes(killChars($_POST["dept_id"]));

$off_cond_para=stripQuotes(killChars($_POST["off_cond_para"]));
$fn_name=stripQuotes(killChars($_POST["fn_name"]));
$pet_own_heading=stripQuotes(killChars($_POST["pet_own_heading"]));

$dept_condition=$_POST["dept_condition"];

$dept_id=stripQuotes(killChars($_POST["dept_id"]));	
$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));	
$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));	
$pattern_id=stripQuotes(killChars($_POST["pattern_id"]));	
$subordinate_level=stripQuotes(killChars($_POST["subordinate_level"]));	
$dept_p_office=stripQuotes(killChars($_POST["dept_p_office"]));	
$off_condition = "";

	if(!empty($off_cond_para)){
		$off_cond_paras = explode("-", $off_cond_para);
		if ($off_cond_paras[0] == "O"){
			$off_condition = " and b1.dept_id = ".$off_cond_paras[1]." and b1.off_level_dept_id = ".$off_cond_paras[2]." and b1.off_loc_id = ".$off_cond_paras[3];
		}
		else if ($off_cond_paras[0] == "S"){
			$off_condition = " and b1.dept_id = ".$off_cond_paras[1]." and b1.off_level_dept_id > ".$off_cond_paras[2];
		}
		else if ($off_cond_paras[0] == "B"){
			$off_condition = " and b1.dept_id = ".$off_cond_paras[1]." and b1.off_level_dept_id >= ".$off_cond_paras[2];
		}
		else if ($off_cond_paras[0] == "P"){
			$off_condition = " and b1.dept_id = ".$off_cond_paras[1]." and b1.off_level_dept_id = ".$off_cond_paras[2]." and b1.off_loc_id = ".$off_cond_paras[3];
		}		
	}
	

if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}

	$grev_dept_condition = "";
	if(!empty($dept_condition)) {
		$grev_dept_condition = $dept_condition;
	}
	
	
	$src_condition = "";
	if(!empty($src_id)) {
		
		$src_condition = " and (c.source_id=".$src_id.")";	
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		
		$src_condition = " and (c.source_id=".$src_id." and c.subsource_id=".$sub_src_id.")";		
	}
	
	//Grev type and Grev Subtype Condition		
	
	$grev_condition = "";
	if(!empty($gtypeid)) {
		
		$grev_condition = " and (c.griev_type_id=".$gtypeid.")";	
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		
		$grev_condition = " and (c.griev_type_id=".$gtypeid." and c.griev_subtype_id=".$gsubtypeid.")";	
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
		if ($off_cond_paras[0] != 'B' && $grie_dept_id != "") {
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

		$petition_type_condition = "";
		if(!empty($petition_type)) {
			$petition_type_condition = " and (c.pet_type_id=".$petition_type.")";
		}
	
		$pet_community_condition = '';
		if(!empty($pet_community)) {
			$pet_community_condition = " and (c.pet_community_id=".$pet_community.")";
		}
		$special_category_condition = '';
		if(!empty($special_category)) {
			$special_category_condition = " and (c.petitioner_category_id=".$special_category.")";
		}
   //echo $grev_condition;
$_SESSION["check"]="yes"; 

if($status=='recd')
	$cnt_type=" Received Petitions";
else if($status=='acpt')
	$cnt_type=" Accepted Petitions";
else if($status=='rjct')
	$cnt_type=" Rejected Petitions";
else if($status=='pwdo')
	$cnt_type=" Pending with Disposing Officer";
else if($status=='pending')
	$cnt_type=" Petitions Pending";
else if($status=='pl1m')
	$cnt_type=" Petitions pending for < 1 Month";
else if($status=='pg1m')
	$cnt_type=" Petitions pending for > 1 month and < 2 Months";
else if($status=='pg2m')
	$cnt_type=" Petitions pending for > 2 months";
?>

<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">	
		<table class="rptTbl">
			<thead>
				<tr id="bak_btn"><th colspan="9" > 
				<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
				<!--<a href="" onclick="return maintain_val('det')"><img src="images/bak.jpg" /></a>-->
				</th></tr>
                
              
                <tr> 
				<th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
				</tr>
            
				<tr> 
				<th colspan="9" class="main_heading"><?PHP echo $label_name[0]." - ";?> <?php echo "Details of ".$cnt_type; ?></th>
                </tr>
                               
                 <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="9" class="search_desc"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
                
				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="9" class="search_desc"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
				<tr> 
					<th colspan="9" class="search_desc"><?PHP echo  $label_name[40].' : '.$dept_designation; //Report type name?></th>
				</tr>
				<tr>				
                <th colspan="9" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo$label_name[20]; //S.No.?></th>
				<th><?PHP echo $label_name[21]; //S.No.?></th>
				<th><?PHP echo $label_name[22]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[23]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[24]; //Source & Sub Source?></th>
				<!--th><?PHP //echo $label_name[25]; //Source Remarks?></th-->
				<th><?PHP echo $label_name[26]; //Grievance?></th>
				<th><?PHP echo $label_name[27]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[28]; //Action Type, Date & Remarks?></th>
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
 
  	if(!empty($from_date) && !empty($to_date) ){
		 $cond1.="a.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="b.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="a.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="a.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";  	  
	}
$tbl_pet_list = "";
//	echo 	$grev_dept_condition." - ".$src_condition." - ".$grev_condition." - ".$off_condition;
	$source_frm=$_POST["source_frm"];
	//echo "DDDDDDDDDDDDD :::".$dept_id.">>>>>>>>>>>>>>>>>>>>>>".$off_level_id."#################".$off_loc_id;
	$our_off_condition = "";
	if ($source_frm == "sup"){
		$our_off_condition = "  and b.dept_id = ".$userProfile->getDept_id()." and b.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and b.off_loc_id = ".$userProfile->getOff_loc_id();
		//$off_level_id = $userProfile->getOff_level_id();
		//$off_loc_id = $userProfile->getOff_loc_id();
	}
	$dept_p_office = 1;	
	if ($off_type == 'P') {
			$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
		$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
			//$dist_cond="lkp_griev_type bb";
			//$dist_params = '';
		} else if ($off_type == 'S') {

			//$dept=$userProfile->getDept_id(); // substr($sub_dept, 0,1);
			//$t1=($s_district== '') ? "" : " inner join fn_single_district(".$s_district.") g on g.district_id=b.griev_district_id ";
			$tbl_pet_list = "fn_pet_action_first_last_off_level(".$dept.",".$s_office.")";
			$tbl_pet_list = "fn_pet_action_first_last_off_level_wo_dept(".$off_level_id.")";
			//$dist_cond="lkp_griev_type bb";
			//$dist_params = '';
			//$user_dept_condition= ($userProfile->getDept_coordinating() == true) ? "":" inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a1.dept_id=".$userProfile->getDept_id();
			
		}
		if ($userProfile->getOff_level_id() != 1) {
			if($userProfile->getDistrict_id()!=''){
			$petition_location_condition = " and b.griev_district_id=".$userProfile->getDistrict_id();
			}
		} 
		if ($s_office == 2) {
			$online_petition_condition = ($src_id == 5) ? " and source_id=5 and fwd_office_level_id in (20,40) " : " and fwd_office_level_id in (20,40) ";
		} else if ($s_office == 3) {
			$online_petition_condition = ($src_id == 5) ? " and source_id=5 and b.dept_id=1 and fwd_office_level_id = 40 " : " and b.dept_id=1 and fwd_office_level_id = 40 ";
		} else if ($s_office == 4) {
			$online_petition_condition = ($src_id == 5) ? " and source_id=5 and b.dept_id=1 and fwd_office_level_id = 40 " : " and b.dept_id=1 and fwd_office_level_id = 40 ";
		}
	if ($source_frm == "sub") {
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
				if($pattern_id==''){
					$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",3)";
				}
			}
	else if ($source_frm == "sup"){

		$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
		$our_off_condition = " inner join vw_usr_dept_users_v b1 on b1.dept_user_id=a.to_whom and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getOff_loc_id();
	}
	if($status=='recd') { //Received
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id ".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")		
		select a.petition_id from off_pet a";
 	} else if($status=='acpt') { //Accepted
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'A')";
	} else if($status=='rjct') {	 //Rejected
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition."  and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'R')";
	} else if($status=='pwdo') { //Pending with Others	
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a 
		where exists (select * from pet_action_first_last a1 where a1.petition_id=a.petition_id and a1.l_to_whom<>a.dept_user_id and a1.l_action_type_code not in ('A','R'))";
	} else if($status=='pending') { //Pending Concerned Officer
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and exists (select * from pet_action_first_last d2 where d2.petition_id=a.petition_id and d2.l_to_whom=a.dept_user_id)";
	 } else if ($status=='pl1m') { //Pending upto one month
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and exists (select * from pet_action_first_last d2 where d2.petition_id=a.petition_id and d2.l_to_whom=a.dept_user_id) 
		and (case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end)=1";
	} else if ($status == 'pg1m') { //Pending one month to 60 days
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and exists (select * from pet_action_first_last d2 where d2.petition_id=a.petition_id and d2.l_to_whom=a.dept_user_id) 
		and (case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60)  then 1 else 0 end)=1";
	}  else if ($status == 'pg2m') { //Pending above 60 days
		$sub_sql="With off_pet as
		(
		select a.petition_id, c.petition_date, c.griev_district_id, b.dept_id, b.off_level_dept_id, b.off_loc_id, b.dept_desig_id, b.to_whom as dept_user_id
		from $tbl_pet_list a
		inner join fn_pet_action_first_last_f_whom() b on b.petition_id=a.petition_id".$our_off_condition." and b.to_whom=".$dept_user_id."
		inner join pet_master c on c.petition_id=a.petition_id
		where c.petition_date between '".$frm_dt."'::date 
		and '".$to_dt."'::date".$grev_dept_condition.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.")
		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and exists (select * from pet_action_first_last d2 where d2.petition_id=a.petition_id and d2.l_to_whom=a.dept_user_id) 
		and (case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end)=1";
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
	
	$sql .= " order by petition_id";
	//echo "--".$status."<br>".$sql;
    //die();
	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			//echo "1111111";
			if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
				//$source_details = ucfirst(strtolower($row[source_name])).' & '.ucfirst(strtolower($row[subsource_name]));
				$source_details = $row['source_name'].' & '.$row['subsource_name'];
			} else {
				//$source_details = ucfirst(strtolower($row[source_name]));
				$source_details = $row['source_name'];
			}
			
			?>
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:15%;"> 
			<?php echo "<b>Mobile:</b> ".$row['comm_mobile']."<br><br>"; ?>
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']; ?></a>
			<?php
				if ($row[lnk_docs] != '') {
					echo "<br><br><b>Linked To:</b> ".$row['lnk_docs'];
				}
			?>
			</td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:18%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;/".$row['pet_type_name']."<br><br><b>Petition Office:</b> ".$row['gri_address']; ?></td>
            
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
			</td>
			</tr>
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
