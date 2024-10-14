<?php
ob_start();
session_start();
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
$pagetitle="Grievance Type wise Report";
//$db = null;
//include("dbr.php");
?>
<script type="text/javascript">
function detail_view(frm_date,to_date,griev_type_id,griev_type_name,status,src_id,sub_src_id,gtypeid,gsubtypeid,grie_dept_id,off_cond_para,pet_own_heading)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("griev_type_id").value=griev_type_id;
	document.getElementById("griev_type_name").value=griev_type_name;
	document.getElementById("pet_own_heading").value=pet_own_heading;
	document.getElementById("status").value=status;
	document.getElementById("src_id").value=src_id;
	document.getElementById("sub_src_id").value=sub_src_id;
	document.getElementById("gtypeid").value=gtypeid;
	document.getElementById("gsubtypeid").value=gsubtypeid;
	document.getElementById("grie_dept_id").value=grie_dept_id;
	document.getElementById("off_cond_para").value=off_cond_para;		
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_grievancetypewise.php";
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
//echo "check b";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rptdist_deptsourcewise.php') order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
//echo "check a";

?>
 
<?php 
if(stripQuotes(killChars($_POST['hid']))=="") { ?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<?php
	$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name(); 
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

	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));

	$grev_taluk = stripQuotes(killChars($_POST["grev_taluk"]));
	$grev_rev_village = stripQuotes(killChars($_POST["grev_rev_village"]));
	$grev_block = stripQuotes(killChars($_POST["grev_block"]));
	$grev_p_village = stripQuotes(killChars($_POST["grev_p_village"]));
	$grev_urban_body = stripQuotes(killChars($_POST["grev_urban_body"]));
	$grev_office = stripQuotes(killChars($_POST["grev_office"]));
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$pet_community = stripQuotes(killChars($_POST["pet_community"]));	
	$special_category = stripQuotes(killChars($_POST["special_category"]));
	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"])); //s_office
	
	$s_dept = stripQuotes(killChars($_POST["s_dept"]));
	$s_office = stripQuotes(killChars($_POST["s_office"])); //s_office
	$s_district = stripQuotes(killChars($_POST["s_district"])); //s_office
	$subordinate_level=stripQuotes(killChars($_POST["s_office"]));
	$disp_officer_name=stripQuotes(killChars($_POST["disp_officer_name"]));
	$instructions=stripQuotes(killChars($_POST["disp_officer_instruction"]));
	$delagated=stripQuotes(killChars($_POST["delagated"]));
	$include_sub_office=stripQuotes(killChars($_POST["include_sub_office"]));
	
	$pattern_id=stripQuotes(killChars($_POST["dept_off_level_pattern_id"]));
	$off_type=stripQuotes(killChars($_POST["off_type"]));
	//echo $off_type;
	if ($off_type == 'S') {
	$subordinate_level=stripQuotes(killChars($_POST["office_level"]));
	}
	if ($off_type == 'P') {
	$subordinate_level=stripQuotes(killChars($_POST["p_office_level"]));
	}
	//echo $subordinate_level;
	$office=stripQuotes(killChars($_POST["office"]));
	$off_level_sltd=stripQuotes(killChars($_POST["office_level_sltd"]));
	$off_loc_id=stripQuotes(killChars($_POST["office"]));
	$subordinatelevel = explode("-", $subordinate_level);
	$dept_desig_id = $subordinatelevel[1];
	$off_level_id = $subordinatelevel[0];
	$subordinate_level = $off_level_id;

	
	if ($subordinate_level != "") {
		/* $of_sql="SELECT  dept_id, off_level_id, off_level_dept_name, off_level_dept_tname  
			FROM usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and 
			off_level_id=".$subordinate_level." order by off_level_id";
		$rs=$db->query($of_sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$off_level_dept_name=$row[2];
		$off_level_id=$row[1]; */
		$off_level_sltd1 = "Office Level: &nbsp;&nbsp;".$off_level_sltd;	
	}
	if ($s_district != "") {
		$dist_sql="select district_id,district_name from mst_p_district where  district_id=".$s_district."";
		
		$rs=$db->query($dist_sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$dist_name=$row[1];
		$off_level_name .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"." District: ".$dist_name;
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
	
	$pet_own_dept_name = "";
	$dept=stripQuotes(killChars($_POST["dept"]));
		  
	$dept_Arr = explode('-',$dept);
	$dept=$dept_Arr[0];
	
	 if($dept=="")
		$dept=1;
	if ($dept != "") {
		 $dept_sql = "SELECT dept_id,dept_name,dept_tname FROM usr_dept where dept_id='$dept'";
		 $dept_rs=$db->query($dept_sql);
		 $dept_row = $dept_rs->fetch(PDO::FETCH_BOTH);
		 $pet_own_dept_name= $dept_row[1]; 
	 
	}
		
	/* if(stripQuotes(killChars($_POST["office"]!="")) || $_SESSION["office"]!="") 
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
		 }
		else */ if(stripQuotes(killChars($_POST["firka"]!="")) || $_SESSION["hid_firka"]!="") 
	 {
		  
		 if(stripQuotes(killChars($_POST["firka"]))!="")
			 $off_loc_id.="".stripQuotes(killChars($_POST["firka"]))."";
		 else
			 $off_loc_id.="".$_SESSION["hid_firka"]."";
			  
		 $off_level_dept_id=stripQuotes(killChars($_POST["offlevel_firkadept_idhid"]));
		 $off_level_id=5;

		 $off_loc = "SELECT firka_name, firka_tname FROM mst_p_firka where firka_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "Firka: ".$off_loc_rw[0];				 				 
	 }
	else if( stripQuotes(killChars($_POST["taluk"]!="")) || $_SESSION["hid_taluk"]!="")
	 { 
		 if(stripQuotes(killChars($_POST["taluk"]))!="")
			 $off_loc_id.="".stripQuotes(killChars($_POST["taluk"]))."";
		 else
			 $off_loc_id.="".$_SESSION["hid_taluk"]."";
		  
		 $off_level_dept_id=4;
		 $off_level_id=4;

		 $off_loc = "SELECT taluk_name, taluk_tname FROM mst_p_taluk where taluk_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "Taluk: ".$off_loc_rw[0];				 

	 }
	 else if(stripQuotes(killChars($_POST["block"]!="")) || $_SESSION["hid_block"]!="")
	 {
		 if(stripQuotes(killChars($_POST["block"]!="")))
			 $off_loc_id.="".stripQuotes(killChars($_POST["block"]))."";
		 else
			 $off_loc_id.="".$_SESSION["hid_block"]."";
			
		  
		 $off_level_dept_id=stripQuotes(killChars($_POST["offlevel_blockdept_idhid"]));
		 $off_level_id=6; 
		 $off_loc = "SELECT block_name, block_tname FROM mst_p_lb_block where block_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "Block: ".$off_loc_rw[0];
	 }
	 else if(stripQuotes(killChars($_POST["urban"]!="")) || $_SESSION["hid_urban"]!="")
	 {
		 if(stripQuotes(killChars($_POST["urban"]))!="")
			 $off_loc_id.="".stripQuotes(killChars($_POST["urban"]))."";
		 else
			 $off_loc_id.="".$_SESSION["hid_urban"]."";
		 
		 $off_level_dept_id=stripQuotes(killChars($_POST["offlevel_urbandept_idhid"]));
		 $off_level_id=7;
		 $off_loc = "SELECT lb_urban_name, lb_urban_tname FROM mst_p_lb_urban where lb_urban_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "Urban Body: ".$off_loc_rw[0];
	 }
	  else if(stripQuotes(killChars($_POST["rdo"]!="")) || $_SESSION["hid_rdo"]!="")
	 {
		 if(stripQuotes(killChars($_POST["rdo"]))!=0 || stripQuotes(killChars($_POST["rdo"]!="")))
				$off_loc_id.="".stripQuotes(killChars($_POST["rdo"]))."";
		 else
				$off_loc_id.="".$_SESSION["hid_rdo"]."";
		 
		 $off_level_dept_id=3;
		 $off_level_id=3;
		 $off_loc = "SELECT rdo_name, rdo_tname FROM mst_p_rdo where rdo_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "RDO: ".$off_loc_rw[0];
	 }
	  else if( stripQuotes(killChars($_POST["dist"]!=""))  || $_SESSION["hid_dist"]!="") 
	 {
		 
		 if(stripQuotes(killChars($_POST["dist"]!=""))){
			  $off_loc_id.="".stripQuotes(killChars($_POST["dist"]))."";
			  $dist_id.="".stripQuotes(killChars($_POST["dist"]))."";
		 }
		 else{
			   $off_loc_id.="".$_SESSION["hid_dist"]."";
			   $dist_id.="".$_SESSION["hid_dist"]."";
		 }
		 
		  
		$off_level_dept_id=2;
		$off_level_id=2; 
		 $off_loc = "SELECT district_name, district_tname FROM mst_p_district where district_id='$off_loc_id'";
		 $off_loc_rs=$db->query($off_loc);
		 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		 $off_loc_name= "District: ".$off_loc_rw[0];
		
	 } 
	 else if( stripQuotes(killChars($_POST["state"]!=""))) 
	 {
		 
		 if(stripQuotes(killChars($_POST["state"]!=""))){
			  $off_loc_id.="".stripQuotes(killChars($_POST["state"]))."";
		 }		  
		 $off_level_dept_id=1;
		 $off_level_id=1;
	
	 } 
	 
			 				
	$pet_own_heading = "";
	if ($pet_own_dept_name != "") {
		$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$pet_own_dept_name;
	}
	
	if ($off_loc_name != "") {
		$pet_own_heading = $pet_own_heading." Office Location: ".$off_loc_name;
	}
		 
?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table id="rptTbl1" class="rptTbl" border="1">
		<thead>
		
	<tr id="bak_btn"><th colspan="9">
	<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
	</th></tr>
	
	<tr><th colspan="9" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th></tr>
	<tr><th colspan="9" class="main_heading"><?PHP echo $label_name[35]; //Department wise Report?></th></tr>
	
	<?php if ($disp_officer_title != '') { ?>
	<tr><th colspan="9" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
	<?php } ?>

	<?php if ($reporttypename != "") {?>
	<tr> 
		<th colspan="9" class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
	</tr>
	<?php } ?>
	
	<?php if ($off_level_sltd1 != "") {?>
	<tr> 
		<th colspan="9" class="search_desc"><?PHP echo $off_level_sltd1; //Report type name?></th>
	</tr>
	<?php } ?>
				
	<?php if ($pet_own_heading != "") {?>
	<tr> 
	<th colspan="9" class="search_desc"><?PHP echo $pet_own_heading; //Report type name?></th>
	</tr>
	<?php } ?>
	
	<tr> 
		<th colspan="9" class="search_desc"><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?>	</th>
	</tr>
	<tr>
		<th rowspan="3" ><?PHP echo $label_name[3]; //S.No.?></th>
		<th rowspan="3" ><?PHP echo 'Petition Main Category'; //Department?></th>
		<th colspan="7" style="width: 70%;"><?PHP echo $label_name[5]; //Number Of Petitions?></th>
	</tr>
	<tr>
		<th rowspan="2"><?PHP echo $label_name[7]; //Received?></th>
		<th colspan="2"><?PHP echo $label_name[8]; //Closed?></th>
		<th rowspan="2"><?PHP echo $label_name[11]; //Closing Balance?></th>
		<th rowspan="2"> <?PHP echo $label_name[12]; //Pending for more than 2 months?></th>
		<th rowspan="2"> <?PHP echo $label_name[13]; //Pending for 2 months?></th>
		<th rowspan="2"> <?PHP echo $label_name[14]; //Pending for 1 month?></th>
	</tr>
	<tr>
	  <th><?PHP echo $label_name[9]; //Accepted?></th>
	  <th><?PHP echo $label_name[10]; //Rejected?></th>
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
			
 	if(!empty($from_date) && !empty($to_date) )
	 {
	 	 $cond1.="a.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="b.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="b.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="a.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";
	 }
	$proxy_profile = false;
	$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	if ($userProfile->getDesig_roleid() == 5 && $userProfile->getOff_level_id() != 7) {
		$sql="SELECT dept_user_id from vw_usr_dept_users_v_sup where  off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and pet_disposal and off_level_id=".$userProfile->getOff_level_id()."";
		$rs=$db->query($sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$disposing_officer=$row['dept_user_id'];
	} else {
		$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	}
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
	//echo "##############################".$userProfile->getOff_level_dept_id();		 
	//Grievance Department Newly Included
	if(stripQuotes(killChars($_POST["grie_dept_id"]))!="")
		$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
	else  
		$grie_dept_id=stripQuotes(killChars($_SESSION["grie_dept_id"]));
	
	
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
		
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid.") ";
	}

	if(!empty($grie_dept_id) && !empty($grev_taluk)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_taluk_id=".$grev_taluk.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_taluk) && !empty($grev_rev_village)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_taluk_id=".$grev_taluk." and a.griev_rev_village_id=".$grev_rev_village.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_block)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_block_id=".$grev_block.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_block) && !empty($grev_p_village)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_block_id=".$grev_block." and b.griev_lb_village_id=".$grev_p_village.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_urban_body)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_lb_urban_id=".$grev_urban_body.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_office)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_division_id=".$grev_office.") ";
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
	if(!empty($grie_dept_id) && !empty($grev_urban_body)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_lb_urban_id=".$grev_urban_body.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_office)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid." and b.griev_division_id=".$grev_office.") ";
	}
	
	$pet_community_condition = '';
	if(!empty($pet_community)) {
		$pet_community_condition = " and (b.pet_community_id=".$pet_community.")";
	}
	$special_category_condition = '';
	if(!empty($special_category)) {
		$special_category_condition = " and (b.petitioner_category_id=".$special_category.")";
	}	
	//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".$userProfile->getOff_level_dept_id();
	$instruction_condition = '';
	if(!empty($instructions)) {
		$instructions_new=str_replace("**"," & ",$instructions);
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ lower(a.action_remarks)::tsvector ";
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
	}
	
	//$off_type = $_POST["offtype"]; // O or S or B or P
	$source_from = $_POST["source_from"];
	$tbl_pet_list = "";
	$our_off_condition = "";
	$off_condition = "";
	$dist_cond='';
	$dist_params = '';
	$t1 = '';
	$user_dept_condition="";
	$condition_for_pag="";
	if ($source_from == "main"){
		if ($delagated == "true") {	
			$tbl_pet_list="fn_pet_action_first_last_delegated_by (".$userProfile->getDept_user_id().")";			
		} else {
			$tbl_pet_list="fn_pet_action_first_last_received_from (".$userProfile->getDept_user_id().")";
		}

		if ($userProfile->getOff_level_id()==7 && ($userProfile->getDept_off_level_pattern_id() == null || $userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {
			//echo "111111111111111111111111111111111111111111111111";
			$dist_cond='(select * from mst_p_state where state_id='.$userProfile->getState_id().') aa cross join lkp_griev_type bb';
			$dist_params = 'aa.state_id,aa.state_name,';
			//$dept_filter_condition = " and b.dept_id=1 and fwd_office_level_id in (10) ";
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().")";		
		}
		else if ($userProfile->getOff_level_id()==9) {	
			$dist_cond=" (select zone_id,zone_name from mst_p_sp_zone where zone_id=".$userProfile->getZone_id().") aa cross join lkp_griev_type bb"; 
			$dist_params = 'aa.zone_id,aa.zone_name,';
			$dept_filter_condition = " and b.dept_id=1";
			$petition_location_condition = " and b.zone_id=".$userProfile->getZone_id();
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().") ";
		}
		else if ($userProfile->getOff_level_id()==11 && ($userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2 || $userProfile->getDept_off_level_pattern_id() == 3)) {	
			$dist_cond=" (select range_id,range_name from mst_p_sp_range where range_id=".$userProfile->getRange_id().") aa cross join lkp_griev_type bb"; 
			$dist_params = 'aa.range_id,aa.range_name,';
			$dept_filter_condition = " and b.dept_id=1";
			$petition_location_condition = " and b.range_id=".$userProfile->getRange_id();
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().") ";
   
		}
		else if ($userProfile->getOff_level_id()==13
		// && ($userProfile->getDept_off_level_pattern_id() == 1 || $userProfile->  getDept_off_level_pattern_id() == 2)
			) {	
			$dist_cond=" (select district_id,district_name from mst_p_district where district_id=".$userProfile->getDistrict_id().") aa cross join lkp_griev_type bb"; 
			$dist_params = 'aa.district_id,aa.district_name,';
			$dept_filter_condition = " and b.dept_id=1";
			$petition_location_condition = " and b.griev_district_id=".$userProfile->getDistrict_id();
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().") ";
		
																		 
	  
			
																																																												  
		}
		else if ($userProfile->getOff_level_id()==42 //&& ($userProfile->getDept_off_level_pattern_id() == 3 || $userProfile->  getDept_off_level_pattern_id() == 4)
			) {	
			$dist_cond=" (select mst_p_district.district_id,district_name,division_id,division_name from mst_p_sp_division inner join mst_p_district on mst_p_district.district_id=mst_p_sp_division.district_id where division_id=".$userProfile->getDivision_id().") aa cross join lkp_griev_type bb"; 									 
	 
															
 
   
			 
																																				 
			$dist_params = 'aa.district_id,aa.district_name,';
			$dept_filter_condition = " and b.dept_id=1";
			$petition_location_condition = " and b.griev_district_id=".$userProfile->getDistrict_id();
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().") ";
		}
		else if ($userProfile->getOff_level_id()==46 //&& ($userProfile->getDept_off_level_pattern_id() == 3 || $userProfile->  getDept_off_level_pattern_id() == 4)
			) {	
			$dist_cond=" lkp_griev_type bb"; 									 
			$dist_params = '';
			$dept_filter_condition = " and b.dept_id=1";
			$petition_location_condition = " and b.griev_district_id=".$userProfile->getDistrict_id();
			$online_petition_condition=" and dept_id=1 and fwd_office_level_id in (".$userProfile->getOff_level_dept_id().") ";
		}
	} else if ($source_from == "sub"){
			if ($off_type == 'P') {
				$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
				$dist_cond="lkp_griev_type bb";
				$dist_params = '';
				$off_level_id = $subordinatelevel[0];
	$subordinate_level = $off_level_id;
			} else if ($off_type == 'S') {
				//echo "SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS";
				$dept=$userProfile->getDept_id(); // substr($sub_dept, 0,1);
				$t1=($s_district== '') ? "" : " inner join fn_single_district(".$s_district.") c on c.district_id=b.griev_district_id ";
				$tbl_pet_list = "fn_pet_action_first_last_off_level(".$dept.",".$s_office.")";
				$tbl_pet_list = "fn_pet_action_first_last_off_level_wo_dept(".$off_level_id.")";
				$dist_cond="lkp_griev_type bb";
				$dist_params = '';	
				$user_dept_condition= ($userProfile->getDept_coordinating() == true) ? "":" inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a1.dept_id=".$userProfile->getDept_id();	
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
			$dist_cond="lkp_griev_type bb";
			$dist_params = '';
		} 		
		
 $off_level_id = $subordinatelevel[0];
 
	$subordinate_level = $off_level_id;
		if($source_from=='sub'){
			$sub_sup_par=',a.off_level_dept_id';
		}else if($source_from=='sup'){
			$sub_sup_par=',b.off_level_dept_id';
		}
		if($source_from=='sub'){
			if($off_type=='S'){
				$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
			$sub_s_param=',a.off_loc_id';
			
			if($dept_desig_id!=''){
				$off_level_codn="where off_level_dept_id=".$dept_desig_id;
				$off_level_codn1=" and off_level_dept_id=".$dept_desig_id;
			}else{
				$off_level_codn="";
				$off_level_codn1="";
			}
			}else{
				$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
			}
			if($dept_desig_id!=''){
				$off_level_codn="where off_level_dept_id=".$dept_desig_id;
				$off_level_codn1=" and off_level_dept_id=".$dept_desig_id;
			}else{
				$off_level_codn="";
				$off_level_codn1="";
			}
			/* if($office!='' && $off_level_codn!=""){
				$off_loc_codn=" and off_loc_id=".$office;
			}else{

			} */
							$off_loc_codn="";
		}else{
			
			$sub_s_param='';
			$off_loc_codn="";
		}
   				
 
	   
															   
		
																	 
															
																																													 
  
	
		$sql="	
		WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition."		
		 
																				  
							 
																																																																																						 
																													 
		)

		select * 

		from ( select ".$dist_params." bb.griev_type_id, bb.griev_type_name , bb.griev_type_tname, COALESCE(rwp.recd_cnt,0) as recd_cnt, COALESCE(cpa.cl_pet_a_cnt,0) as cl_pet_a_cnt, COALESCE(cpr.cl_pet_r_cnt,0) as cl_pet_r_cnt, COALESCE(pcb.cl_pend_cnt,0) as cl_pend_cnt, COALESCE(pcb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, COALESCE(pcb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, COALESCE(pcb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt 

		from ".$dist_cond."

		left join -- received within the period 

		(select griev_type_id,count(*) as recd_cnt from off_pet ".$off_level_codn.$off_loc_codn." group by griev_type_id) rwp on rwp.griev_type_id=bb.griev_type_id 

		left join -- closed petitions: status with 'A' 
		(select griev_type_id,count(*) as cl_pet_a_cnt from off_pet a 
		inner join pet_action_first_last b on b.petition_id=a.petition_id and coalesce(b.l_action_type_code,'')='A' ".$off_level_codn.$off_loc_codn." group by griev_type_id) cpa on cpa.griev_type_id=bb.griev_type_id 

		left join -- closed petitions: status with 'R' 
		(select griev_type_id,count(*) as cl_pet_r_cnt from off_pet a 
		inner join pet_action_first_last b on b.petition_id=a.petition_id and coalesce(b.l_action_type_code,'')='R' ".$off_level_codn.$off_loc_codn." group by griev_type_id) cpr on cpr.griev_type_id=bb.griev_type_id 

		left join -- pending: cl. bal. 
		(select griev_type_id,count(*) as cl_pend_cnt, 
		sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
		sum(case when ((current_date - petition_date::date) > 30 and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
		sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
		from off_pet a 
		left join pet_action_first_last b on b.petition_id=a.petition_id 
		".$off_level_codn.$off_loc_codn." and coalesce(b.l_action_type_code,'') not in ('A','R') 
		group by griev_type_id) pcb on pcb.griev_type_id=bb.griev_type_id ) b_rpt 

		where recd_cnt+cl_pet_a_cnt+cl_pet_r_cnt+cl_pend_cnt > 0 
		order by b_rpt.cl_pend_cnt desc";
//echo $sql;
	   
	    $result = $db->query($sql);
		
		$row_cnt = $result->rowCount();
		//echo "--".$row_cnt;
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			if($_SESSION['lang']=='E'){
				$griev_type_name=$row['griev_type_name'];
			}else{
				$griev_type_name=$row['griev_type_tname'];
			}
	
			 
			$griev_type_id=$row['griev_type_id'];
			$received=$row['recd_cnt'];
			$accepted=$row['cl_pet_a_cnt'];
			$rejected=$row['cl_pet_r_cnt'];
			$closing_pending=$row['cl_pend_cnt'];
			
			$cl_pend_leq30d_cnt=$row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt=$row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt=$row['cl_pend_gt60d_cnt'];
			?>
			<tr>   
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $griev_type_name; ?></td>
             <!-- $off_loc_name -->  
		
	<?php if($received!=0) {?>
			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "rwp"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $received;?></a></td>  
	<?php } 
		else {?>
			<td><?php echo $received;?> </td> <?php } ?>

	<?php if($accepted!=0) {?>
			<td><a class="accepted" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "cpa"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $accepted;?></a></td> 
	<?php } 
		else {?>
			<td> <?php echo $accepted;?> </td> <?php } ?>

	<?php if($rejected!=0) {?>
			<td><a class="rejected" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "cpr"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $rejected;?></a></td> 
	<?php } 
		else {?>
			<td> <?php echo $rejected;?> </td> <?php } ?>

	<?php if($closing_pending!=0) {?>
			<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "pcb"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $closing_pending;?></a></td>
	<?php } 
		else {?>
			<td> <?php echo $closing_pending;?> </td> <?php } ?>

	<?php if($cl_pend_leq30d_cnt!=0) {?>
			<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "pm2"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $cl_pend_leq30d_cnt;?></a></td>
	<?php } 
		else {?>
			<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>
			
	<?php if($cl_pend_gt30leq60d_cnt!=0) { ?>
			<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "p2m"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $cl_pend_gt30leq60d_cnt;?></a></td>
	<?php } 
		 else {?>
			 <td> <?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>

	<?php if($cl_pend_gt60d_cnt!=0) {?>
			<td><a class="pending" href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $griev_type_id; ?>','<?php echo $griev_type_name; ?>','<?php echo "pm1"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"> <?php echo $cl_pend_gt60d_cnt;?></a></td>
	<?php } 
		else {?>
			<td> <?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>

</tr>

			<?php  
			$i++;			 
			$tot_received=$tot_received+$received;			 
			$tot_accepted=$tot_accepted+$accepted;	
			$tot_rejected=$tot_rejected+$rejected;	
			$tot_closing_pending=$tot_closing_pending+$closing_pending;
			
			$tot_cl_pend_more_cnt=$tot_cl_pend_more_cnt+$cl_pend_leq30d_cnt;
			$tot_cl_pend_2_cnt=$tot_cl_pend_2_cnt+$cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1_cnt=$tot_cl_pend_1_cnt+$cl_pend_gt60d_cnt;
			//$tot_cl_pend_less_cnt=$tot_cl_pend_less_cnt+$cl_pend_less_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo $label_name[16]; // Total?></td>
                <td><?php echo $tot_received;?></td>
                <td><?php echo $tot_accepted;?></td>
                <td><?php echo $tot_rejected;?></td>
                <td><?php echo $tot_closing_pending;?></td>
                
                <td><?php echo $tot_cl_pend_more_cnt;?></td>
           		<td><?php echo $tot_cl_pend_2_cnt;?></td>
            	<td><?php echo $tot_cl_pend_1_cnt;?></td>
			</tr>
			<tr><th colspan="9" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
			<tr>
            <td colspan="9" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
            <input type="hidden" name="hid" id="hid" />
            <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
            <input type="hidden" name="frdate" id="frdate"  />
   		    <input type="hidden" name="todate" id="todate" />
    		<input type="hidden" name="griev_type_id" id="griev_type_id" />
            <input type="hidden" name="griev_type_name" id="griev_type_name" />
			<input type="hidden" name="pet_own_heading" id="pet_own_heading" />
     		<input type="hidden" name="status" id="status" /> 

            <input type="hidden" name="src_id" id="src_id" />
    		<input type="hidden" name="sub_src_id" id="sub_src_id" />
            <input type="hidden" name="gtypeid" id="gtypeid" />
            <input type="hidden" name="gsubtypeid" id="gsubtypeid" />
            <input type="hidden" name="grie_dept_id" id="grie_dept_id" />
            <input type="hidden" name="off_cond_para" id="off_cond_para" />
			<input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/> 
       		<input type="hidden" name="dept_condition" id="dept_condition" value="<?php echo $grev_dept_condition; ?>"/>
       		<input type="hidden" name="source_frm" id="source_frm" value="<?php echo $source_from; ?>"/>
			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/> 
	<input type="hidden" name="pet_community" id="pet_community" value="<?php echo $pet_community; ?>"/> 
	<input type="hidden" name="special_category" id="special_category" value="<?php echo $special_category; ?>"/> 
	<input type="hidden" name="h_dept_user_id" id="h_dept_user_id" value="<?php echo $userProfile->getDept_user_id(); ?>"/>
	<input type="hidden" name="h_off_level_dept_id" id="h_off_level_dept_id" value="<?php echo $userProfile->getOff_level_dept_id(); ?>"/>
	<input type="hidden" name="h_dept_id" id="h_dept_id" value="<?php echo $userProfile->getDept_id(); ?>"/>
	<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" value="<?php echo $userProfile->getOff_loc_id(); ?>"/>			
	<input type="hidden" name="disp_officer_title" id="disp_officer_title" value='<?php echo $disp_officer_title ?>'/>
			
 <input type="hidden" name="h_Dept_coordinating" id="h_Dept_coordinating" value="<?php echo $userProfile->getDept_coordinating(); ?>"/>	
 <input type="hidden" name="h_Off_coordinating" id="h_Off_coordinating" value="<?php echo $userProfile->getOff_coordinating(); ?>"/>	
 <input type="hidden" name="h_Desig_coordinating" id="h_Desig_coordinating" value="<?php echo $userProfile->getDesig_coordinating(); ?>"/>	

	<input type="hidden" name="dept" id="dept" value="<?php echo $dept; ?>"/> 
	<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $off_level_id; ?>"/> 
	<input type="hidden" name="off_loc_id" id="off_loc_id" value="<?php echo $off_loc_id; ?>"/> 
	<input type="hidden" name="off_type" id="off_type" value="<?php echo $off_type; ?>"/> 

	<input type="hidden" name="s_office" id="s_office" value="<?php echo $s_office; ?>"/> 
	<input type="hidden" name="s_district" id="s_district" value="<?php echo $s_district; ?>"/> 
	<input type="hidden" name="off_level_name" id="off_level_name" value="<?php echo $off_level_name; ?>"/> 
	<input type="hidden" name="reporttypename" id="reporttypename" value="<?php echo $reporttypename; ?>"/> 
	<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 
	<input type="hidden" name="instructions" id="instructions" value="<?php echo $instructions; ?>"/>
	<input type="hidden" name="delagated" id="delagated" value="<?php echo $delagated; ?>"/>
	<input type="hidden" name="include_sub_office" id="include_sub_office" value="<?php echo $include_sub_office; ?>"/>
	<input type="hidden" name="pattern_id" id="pattern_id" value="<?php echo $pattern_id; ?>"/>
	<input type="hidden" name="subordinate_level" id="subordinate_level" value="<?php echo $subordinate_level; ?>"/>
	<input type="hidden" name="dept_desig_id" id="dept_desig_id" value="<?php echo $dept_desig_id; ?>"/>
	<input type="hidden" name="proxy_profile" id="proxy_profile" value="<?php echo $proxy_profile; ?>"/>
	<input type="hidden" name="proxy_user_id" id="proxy_user_id" value="<?php echo $userProfile->getDept_user_id(); ?>"/>
            </td></tr>
	        </tbody>
        </table>		
		<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo $label_name[37]; //No Records Found?>...</td></tr>
         </table>
         
        <?php } ?>

 		 
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
//$db = null;
//include("dbr.php");
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

$h_dept_user_id=stripQuotes(killChars($_POST["h_dept_user_id"]));



$rep_src=stripQuotes(killChars($_POST["rep_src"])); 

$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$griev_type_id=stripQuotes(killChars($_POST["griev_type_id"]));
$griev_type_name=stripQuotes(killChars($_POST["griev_type_name"]));
$off_loc_name=stripQuotes(killChars($_POST["off_loc_name"])); //off_loc_name
$status=stripQuotes(killChars($_POST["status"]));
$pet_own_dept_name=stripQuotes(killChars($_POST["pet_own_dept_name"])); 

$src_id = stripQuotes(killChars($_POST["src_id"]));	  
$sub_src_id = stripQuotes(killChars($_POST["sub_src_id"]));	
$gtypeid = stripQuotes(killChars($_POST["gtypeid"]));	  
$gsubtypeid = stripQuotes(killChars($_POST["gsubtypeid"]));
$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
$off_cond_para=stripQuotes(killChars($_POST["off_cond_para"]));
$dept_condition=$_POST["dept_condition"];
$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$pet_community=stripQuotes(killChars($_POST["pet_community"]));
$special_category=stripQuotes(killChars($_POST["special_category"]));
$h_off_level_dept_id=stripQuotes(killChars($_POST["h_off_level_dept_id"]));
$h_dept_id=stripQuotes(killChars($_POST["h_dept_id"]));
$h_off_loc_id=stripQuotes(killChars($_POST["h_off_loc_id"]));

$h_Dept_coordinating=stripQuotes(killChars($_POST["h_Dept_coordinating"]));
$h_Off_coordinating=stripQuotes(killChars($_POST["h_Off_coordinating"]));
$h_Desig_coordinating=stripQuotes(killChars($_POST["h_Desig_coordinating"]));

$dept=stripQuotes(killChars($_POST["dept"]));
$off_level_id=stripQuotes(killChars($_POST["off_level_id"]));
$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
$off_type=stripQuotes(killChars($_POST["off_type"]));
$s_district=stripQuotes(killChars($_POST["s_district"]));
$s_office=stripQuotes(killChars($_POST["s_office"]));
$reporttypename=stripQuotes(killChars($_POST["reporttypename"]));
$off_level_name=stripQuotes(killChars($_POST["off_level_name"]));
$disp_officer_title=stripQuotes(killChars($_POST["disp_officer_title"]));
$instructions=stripQuotes(killChars($_POST["instructions"]));
$delagated=stripQuotes(killChars($_POST["delagated"]));
$include_sub_office=stripQuotes(killChars($_POST["include_sub_office"]));
$subordinate_level=stripQuotes(killChars($_POST["subordinate_level"]));
$pattern_id=stripQuotes(killChars($_POST["pattern_id"]));
$dept_desig_id=stripQuotes(killChars($_POST["dept_desig_id"]));
$proxy_profile=stripQuotes(killChars($_POST["proxy_profile"]));
$proxy_user_id=stripQuotes(killChars($_POST["proxy_user_id"]));
$report_preparing_officer = $userProfile->getDept_desig_name()." - ". $userProfile->getOff_loc_name();	
	$pet_own_heading = "";
	if ($pet_own_dept_name != "") {
		$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$pet_own_dept_name;
	}
		
	if ($off_loc_name != "") {
		$pet_own_heading = $pet_own_heading." Office Location: ".$off_loc_name;
	}
	$grev_dept_condition = "";
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
		$grev_dept_condition = " and (b.dept_id=".$griedeptid.")";
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
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ lower(a.action_remarks)::tsvector ";
		$instruction_condition = " and lower('".$instructions_new."')::tsquery @@ translate(lower(a.action_remarks),'`~!@#$%^&*()-_=+[{]}\|;:,<.>/?''','')::tsvector ";
	}
	$source_frm=$_POST["source_frm"];
	$tbl_pet_list = "";
	$our_off_condition = "";
	$off_condition = "";
	$dist_cond='';
	$dist_params = '';
	$t1 = '';
	$user_dept_condition = '';	
	
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
		 $cond1.="b.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="b.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="b.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="b.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";  	  
	}
	
	$aspect_cond7="";
	$condition_for_pag = "";
	if(!empty($griev_type_id)) {
		$aspect_cond7 = " and (b.griev_type_id=".$griev_type_id.") ";
	}
	//echo "======================";
	if ($source_from == "main"){
		//echo ">>>>>>>>>>>>>>>>>>>>>>>>>";
		if ($delagated == "true") {
			if ($proxy_profile == 1) {
				$tbl_pet_list="fn_pet_action_first_last_delegated_by (".$proxy_user_id.")";
			} else {
				$tbl_pet_list="fn_pet_action_first_last_delegated_by (".$userProfile->getDept_user_id().")";
			}	
						
		} else {
			if ($proxy_profile == 1) {
				echo $tbl_pet_list="fn_pet_action_first_last_received_from (".$proxy_user_id.")";
			} else {
				$tbl_pet_list="fn_pet_action_first_last_received_from (".$userProfile->getDept_user_id().")";
			}
			
		}
	}
	else if ($source_frm == "sub"){

		$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
		$dist_cond="lkp_griev_type bb";
		$dist_params = '';
		if ($off_type == 'P') {
			$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
		$tbl_pet_list = "fn_pet_action_first_last_off_level_with_pattern(".$subordinate_level.",".$pattern_id.")";
			$dist_cond="lkp_griev_type bb";
			$dist_params = '';
		} else if ($off_type == 'S') {

			$dept=$userProfile->getDept_id(); // substr($sub_dept, 0,1);
			$t1=($s_district== '') ? "" : " inner join fn_single_district(".$s_district.") g on g.district_id=b.griev_district_id ";
			$tbl_pet_list = "fn_pet_action_first_last_off_level(".$dept.",".$s_office.")";
			$tbl_pet_list = "fn_pet_action_first_last_off_level_wo_dept(".$off_level_id.")";
			$dist_cond="lkp_griev_type bb";
			$dist_params = '';
			$user_dept_condition= ($userProfile->getDept_coordinating() == true) ? "":" inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a1.dept_id=".$userProfile->getDept_id();
			
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
			}
		/*if($userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2 && $userProfile->getDept_desig_id()==16){
			$condition_for_pag=" and b.source_id=5 and b.dept_id=1 ";
		}*/
		
	}if ($subordinate_level == 7) {
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
	else if ($source_frm == "sup"){

		//$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
		$our_off_condition = " inner join vw_usr_dept_users_v b1 on b1.dept_user_id=a.to_whom and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getOff_loc_id();
		$dist_cond="lkp_griev_type bb";
		$dist_params = '';
	} 
	
	$_SESSION["check"]="yes"; 

if($status=='rwp')
	$cnt_type=" ".$label_name[7];//" Received Petitions";
else if($status=='cpa')
	$cnt_type=" ".$label_name[9];//" Accepted Petitions";
else if($status=='cpr')
	$cnt_type=" ".$label_name[10];//" Rejected Petitions";
else if($status=='pcb')
	$cnt_type=" ".$label_name[11];//" Closing Balance (Pending)";
else if($status=='pm2')
	$cnt_type=" ".$label_name[12];
else if($status=='p2m')
	$cnt_type=" ".$label_name[13];
else if($status=='pm1')
	$cnt_type=" ".$label_name[14];

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
				<th colspan="8" class="main_heading"><?PHP echo $label_name[35]." - ";?> <?php echo " ".$cnt_type; ?></th>
                </tr>
                                 <?php if($disp_officer_title!="") { ?>
                <tr>
                <th colspan="8" class="search_desc"><?php echo $disp_officer_title;?></th>
                </tr>
				
				 <tr>
                <th colspan="8" class="search_desc"><?php echo $griev_type_name;?></th>
                </tr>
				
                <?php } ?>
                 <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="8" class="search_desc"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
                
				<?php if($off_level_name!="") { ?>
                <tr>
                <th colspan="8" class="search_desc"><?php echo $off_level_name;?></th>
                </tr>
                <?php } ?>
				
				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="8" class="search_desc"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
                 <tr>
                <th colspan="8" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo $label_name[22]; //S.No.?></th>
				<th><?PHP echo $label_name[23]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[24]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[25]; //Source & Sub Source?> & <?PHP echo $label_name[26]; //Source Remarks?></th>
				<th><?PHP echo $label_name[27]; //Grievance?></th>
				<th><?PHP echo $label_name[28]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[29]; //Action Type, Date & Remarks?></th>
                <th><?PHP echo $label_name[30]; //Pending Period?></th>
				</tr>
			</thead>
		<tbody>
<?php 
$i=1;

	if (!empty($griev_type_id)) {
		
		$grev_condition2 = " and (griev_type_id=".$griev_type_id.")";	
	}

	if($off_type=='S'){
	if($dept_desig_id!=''){
				$off_level_codn="where off_level_dept_id=".$dept_desig_id;
				$off_level_codn1=" and a.off_level_dept_id=".$dept_desig_id;
			}else{
				$off_level_codn="";
				$off_level_codn1="";
			}
			}else if($off_type=='P'){
				$tbl_pet_list = "fn_pet_action_first_last_p_office(".$dept.",".$off_level_id.", ".$off_loc_id.")";
			
			if($dept_desig_id!=''){
				$off_level_codn="where off_level_dept_id=".$dept_desig_id;
				$off_level_codn1=" and a.off_level_dept_id=".$dept_desig_id;
			}else{
				$off_level_codn="";
				$off_level_codn1="";
			}
			}else{
				$tbl_pet_list="fn_pet_action_first_last_received_from (".$userProfile->getDept_user_id().")";
				//$tbl_pet_list="fn_pet_action_first_last_received_from (5)";
				if ($proxy_profile == 1) {
					$tbl_pet_list="fn_pet_action_first_last_received_from (".$proxy_user_id.")";
				} else {
					$tbl_pet_list="fn_pet_action_first_last_received_from (".$userProfile->getDept_user_id().")";
				}
			}
			
			if($source_from=='sub'){
			$sub_sup_par=',a.off_level_dept_id';
		}else if($source_from=='sup'){
			$sub_sup_par=',b.off_level_dept_id';
		}
	if($status=='rwp'){			
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag."		
		union
		select b.petition_id, f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition.$sub_office_pet_condn.$condition_for_pag."
		)
  
		select petition_id from off_pet ";

		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."		
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
																																					   
		)
  
		select petition_id from off_pet ";
									 
		}
	else if($status=='cpa'){
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag." and coalesce(c.l_action_type_code,'')='A'
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition." 
		and coalesce(c.l_action_type_code,'')='A' ".$sub_office_pet_condn.$condition_for_pag."
		and coalesce(c.l_action_type_code,'')='A'
		)
		
		select petition_id from off_pet";


		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		and coalesce(c.l_action_type_code,'')='A'
																																					   
		)
  
		select petition_id from off_pet";
												 
		}
	else if($status=='cpr'){	
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag."
		and coalesce(c.l_action_type_code,'')='R'
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition." and coalesce(c.l_action_type_code,'')='R' ".$sub_office_pet_condn.$condition_for_pag."
		and coalesce(c.l_action_type_code,'')='R'
		)
		
		select petition_id from off_pet";

		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		and coalesce(c.l_action_type_code,'')='R'
																																					   
		)
  
		select petition_id from off_pet";
														
												 
		}
	else if($status=='pcb'){
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id 
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag." 
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null".$online_petition_condition.$petition_location_condition." and coalesce(c.l_action_type_code,'') not in ('A','R') ".$sub_office_pet_condn.$condition_for_pag."
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		)
		
		select petition_id from off_pet";

		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		and coalesce(c.l_action_type_code,'') not in ('A','R')
																																					   
		)
  
		select petition_id from off_pet";
							  
																																																																									
												 
	 }
	else if ($status=='pm2') 
	{
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id 
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag." and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition." 
		and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		".$sub_office_pet_condn.$condition_for_pag." and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R')
		)
		
		select petition_id from off_pet";

		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		 and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R')
																																					   
		)
  
		select petition_id from off_pet";
							  							 
	}
	else if ($status == 'p2m') {
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id 
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag." and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R')
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition." and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R')
		".$sub_office_pet_condn.$condition_for_pag." and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R')
		)
		
		select petition_id from off_pet";
	
		
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		 and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1 and coalesce(c.l_action_type_code,'') not in ('A','R') $grev_condition
																																					   
		)
  
		select petition_id from off_pet";							
												 
	} 
	else if ($status == 'pm1') {
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id ".$user_dept_condition.$t1."
		inner join pet_action_first_last c on c.petition_id=a.petition_id 
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$condition_for_pag." and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		union
		select b.petition_id, c.f_action_type_code, b.griev_type_id, b.petition_date
		from pet_master b ".$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition.
		$petition_type_condition.$pet_community_condition.$special_category_condition." 
		and b.pet_entby is null ".$online_petition_condition.$petition_location_condition." 
		and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		".$sub_office_pet_condn.$condition_for_pag."
		and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
		)
  
		select petition_id from off_pet";

		
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, b.griev_type_id, b.petition_date$sub_sup_par $sub_s_param
		from ".$tbl_pet_list." a "
		.$our_off_condition."
		inner join pet_master b on b.petition_id=a.petition_id".$user_dept_condition.$t1."
		left join pet_action_first_last c on c.petition_id=b.petition_id
		where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition.$off_level_codn1.$grev_condition2."			
		--union
		--select b.petition_id, '' as action_type_code, b.griev_type_id, b.petition_date
		--from pet_master b ".$t1."
		--where ".$cond3.$src_condition.$grev_condition.$petition_type_condition.$pet_community_condition.$special_category_condition.$instruction_condition." and b.pet_entby is null ".$online_petition_condition.$petition_location_condition."
		and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1
		and coalesce(c.l_action_type_code,'') not in ('A','R')
																																					   
		)
  
		select petition_id from off_pet";																													
												 
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
			<td class="desc" style="width:14%;"> 
			<?php echo "<B>Mobile:</B> ".$row['comm_mobile']."<br><br>"; ?>
			<a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']."<br>"; ?></a>
			<?php
				if ($row['lnk_docs'] != '') {
					echo "<br><B>Linked To:</B> ".$row['lnk_docs'];
				}
			?>
			
			</td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:19%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br><B>Office:</B> ".$row['gri_address']."<br>".$row['pet_type_name']; ?></td>
            
<td class="desc" style="width:24%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
} else {
	echo "No actions taken so far";
}
?>
</td>
            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
			<tr><th colspan="8" style="text-align:right;font-size:15px;"><i><b>Report generated by:</b></i> <?PHP echo  $report_preparing_officer.' on '. date("d-m-Y h:i A");?></th></tr>
			<tr>
			<td colspan="8" class="buttonTD">
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

