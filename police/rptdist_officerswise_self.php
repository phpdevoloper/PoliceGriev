<?php
ob_start();
session_start();
include("db.php");
include("header_menu.php");
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
function detail_view(frm_date,to_date,dept,dept_name,status,src_id,sub_src_id,gtypeid,gsubtypeid,grie_dept_id,off_cond_para,off_loc_name)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("off_loc_name").value=off_loc_name;
	document.getElementById("status").value=status;
	document.getElementById("src_id").value=src_id;
	document.getElementById("sub_src_id").value=sub_src_id;
	document.getElementById("gtypeid").value=gtypeid;
	document.getElementById("gsubtypeid").value=gsubtypeid;
	document.getElementById("grie_dept_id").value=grie_dept_id;
	document.getElementById("off_cond_para").value=off_cond_para;		
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_officerswise_self.php";
	document.rpt_abstract.target= "_blank";
	document.rpt_abstract.submit(); 
	return false;
}
</script>
<?php
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rptdist_deptwise.php') order by ordering";
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
	
	$pet_own_dept_name = "";
	$dept=stripQuotes(killChars($_POST["dept"]));

	$dept=substr($dept, 0,1);
	if($dept=="")
		$dept=$_SESSION["dept_id"];
	if ($dept != "") {
		$dept_sql = "SELECT dept_id,dept_name,dept_tname FROM usr_dept where dept_id='$dept'";
		$dept_rs=$db->query($dept_sql);
		$dept_row = $dept_rs->fetch(PDO::FETCH_BOTH);
		$pet_own_dept_name= $dept_row[1]; 
	}
	$off_loc_cond = ""; 
	if(stripQuotes(killChars($_POST["firka"]!="")) || $_SESSION["hid_firka"]!="") {
		if(stripQuotes(killChars($_POST["firka"]))!="")
			$off_loc_id.="".stripQuotes(killChars($_POST["firka"]))."";
		else
			$off_loc_id.="".$_SESSION["hid_firka"]."";

		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_firkadept_idhid"]));
		$off_loc = "SELECT firka_name, firka_tname FROM mst_p_firka where firka_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Firka: ".$off_loc_rw[0];				 
		$off_loc_cond = "5,".$off_loc_id.",null,'{5}'";
	} else if( stripQuotes(killChars($_POST["taluk"]!="")) || $_SESSION["hid_taluk"]!="") { 
		if(stripQuotes(killChars($_POST["taluk"]))!="")
			$off_loc_id.="".stripQuotes(killChars($_POST["taluk"]))."";
		else
			$off_loc_id.="".$_SESSION["hid_taluk"]."";

		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_tlkdept_idhid"]));
		$off_loc = "SELECT taluk_name, taluk_tname FROM mst_p_taluk where taluk_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Taluk: ".$off_loc_rw[0];
		$off_loc_cond = "4,".$off_loc_id.",null,'{4,5}'";

	} else if(stripQuotes(killChars($_POST["block"]!="")) || $_SESSION["hid_block"]!="") {
		if(stripQuotes(killChars($_POST["block"]!="")))
			$off_loc_id.="".stripQuotes(killChars($_POST["block"]))."";
		else
			$off_loc_id.="".$_SESSION["hid_block"]."";


		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_blockdept_idhid"]));
		$off_loc = "SELECT block_name, block_tname FROM mst_p_lb_block where block_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Block: ".$off_loc_rw[0];
		$off_loc_cond = "6,".$off_loc_id.",null,'{6}'";
	} else if(stripQuotes(killChars($_POST["urban"]!="")) || $_SESSION["hid_urban"]!="") {
		if(stripQuotes(killChars($_POST["urban"]))!="")
			$off_loc_id.="".stripQuotes(killChars($_POST["urban"]))."";
		else
			$off_loc_id.="".$_SESSION["hid_urban"]."";

		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_urbandept_idhid"]));
		$off_loc = "SELECT lb_urban_name, lb_urban_tname FROM mst_p_lb_urban where lb_urban_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "Urban Body: ".$off_loc_rw[0];
		$off_loc_cond = "7,".$off_loc_id.",null,'{7}'";
	} else if(stripQuotes(killChars($_POST["rdo"]!="")) || $_SESSION["hid_rdo"]!="") {
		if(stripQuotes(killChars($_POST["rdo"]))!=0 || stripQuotes(killChars($_POST["rdo"]!="")))
			$off_loc_id.="".stripQuotes(killChars($_POST["rdo"]))."";
		else
			$off_loc_id.="".$_SESSION["hid_rdo"]."";

		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_rdodept_idhid"]));
		$off_loc = "SELECT rdo_name, rdo_tname FROM mst_p_rdo where rdo_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "RDO: ".$off_loc_rw[0];
		$off_loc_cond = "3,".$off_loc_id.",null,'{3,4,5}'";
	} else if( stripQuotes(killChars($_POST["dist"]!=""))  || $_SESSION["hid_dist"]!="") {
		if(stripQuotes(killChars($_POST["dist"]!=""))){
			$off_loc_id.="".stripQuotes(killChars($_POST["dist"]))."";
			$dist_id.="".stripQuotes(killChars($_POST["dist"]))."";
		}
		else{
			$off_loc_id.="".$_SESSION["hid_dist"]."";
			$dist_id.="".$_SESSION["hid_dist"]."";
		}
		$off_level_dept_id=stripQuotes(killChars($_POST["offlevel_distdept_idhid"]));
		$off_loc = "SELECT district_name, district_tname FROM mst_p_district where district_id='$off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$off_loc_name= "District: ".$off_loc_rw[0];
		$off_loc_cond = "2,".$off_loc_id.",null,'{2}'";
	} 
	if ($off_loc_cond == "") {
		if ($userProfile->getOff_level_id() == 2)
			$off_loc_cond = "2,".$userProfile->getOff_loc_id().",null,'{2,3,4,6,7,10,11}'"; 	 
		else if ($userProfile->getOff_level_id() == 3)
			$off_loc_cond = "3,".$userProfile->getOff_loc_id().",null,'{3,4,5}'";
		else if ($userProfile->getOff_level_id() == 4)
			$off_loc_cond = "4,".$userProfile->getOff_loc_id().",null,'{4,5}'";
	}

	$pet_own_heading = "";
	if ($pet_own_dept_name != "") {
		$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$pet_own_dept_name;
	}

	if ($off_loc_name != "") {
		$pet_own_heading = $pet_own_heading." - Office Location: ".$off_loc_name;
	}
 
?>
<div class="contentMainDiv" style="width:80%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="12" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
			</th></tr>
            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $label_name[33]; //Department wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="12" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="12" class="search_desc"><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="3" ><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="3" ><?PHP echo $label_name[4]; //Department?></th>
                <th colspan="10" style="width: 70%;"><?PHP echo $label_name[5]; //Number Of Petitions?></th>


				</tr>
				<tr>
                <!--th rowspan="2"><?PHP //echo $label_name[6]; //Opening Balance?></th-->
                <th rowspan="2"><?PHP echo $label_name[7]; //Received?></th>
                <th colspan="2"><?PHP echo $label_name[8]; //Closed?></th>
				<th rowspan="2"><?PHP echo $label_name[32]; //Pending with Others?></th>
                <th rowspan="2"><?PHP echo $label_name[11]; //Closing Balance?></th>				
                <th rowspan="2"> <?PHP echo $label_name[12]; //Pending for more than 2 months?></th>
        	 	<th rowspan="2"> <?PHP echo $label_name[13]; //Pending for 2 months?></th>
            	<th rowspan="2"> <?PHP echo $label_name[14]; //Pending for 1 month?></th>
            	<!--th rowspan="2"> <?PHP //echo $label_name[15]; //Pending for less than 1 month?></th-->
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
         $cond3.="a.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="a.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";
	 }
	
	$dept_cond='';
	$userProfile = unserialize($_SESSION['USER_PROFILE']);
	//if($_SESSION['ADMIN_ROLE']==DEPT_DIST_ADMIN){
	
	if(!$userProfile->getDept_coordinating()&& !$userProfile->getOff_coordinating() && !$userProfile->getDesig_coordinating())
	{		
		$dept_cond.=" and a.dept_id=".$userProfile->getDept_id()." ";
	}  
		
			 
	$usr_cond="";
	if($user_id!="")
		$usr_cond=$user_id;
	else
	{
		$usr_cond=" SELECT dept_user_id FROM vw_usr_dept_users_v_sup WHERE dept_id=".$dept." and off_level_dept_id=".$off_level_dept_id." and off_loc_id=".$off_loc_id."";
	}
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
		$grev_dept_condition = " and (a.dept_id=".$griedeptid.") ";
	}

	if(!empty($grie_dept_id) && !empty($grev_taluk)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_taluk_id=".$grev_taluk.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_taluk) && !empty($grev_rev_village)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_taluk_id=".$grev_taluk." and a.griev_rev_village_id=".$grev_rev_village.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_block)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_block_id=".$grev_block.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_block) && !empty($grev_p_village)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_block_id=".$grev_block." and a.griev_lb_village_id=".$grev_p_village.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_urban_body)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_lb_urban_id=".$grev_urban_body.") ";
	}
	
	if(!empty($grie_dept_id) && !empty($grev_office)) {
		$grev_dept_condition = " and (a.dept_id=".$griedeptid." and a.griev_division_id=".$grev_office.") ";
	}
	
	//$_SESSION["grie_dept_id"] = $grie_dept_id;	
	//Source And Subsource Condition  
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

	$off_type = $_POST["offtype"]; // O or S or B or P
	if ($off_type == "O"){
		$off_condition = " and a1.dept_id = ".$userProfile->getDept_id()." and a1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and a1.off_loc_id = ".$userProfile->getOff_loc_id();
		$off_cond_para = "O-".$userProfile->getDept_id()."-".$userProfile->getOff_level_dept_id()."-".$userProfile->getOff_loc_id();
	}
	else if ($off_type == "S"){
		$off_condition = " and a1.dept_id = ".$userProfile->getDept_id()." and a1.off_level_dept_id > ".$userProfile->getOff_level_dept_id();
		$off_cond_para = "S-".$userProfile->getDept_id()."-".$userProfile->getOff_level_dept_id()."-".$userProfile->getOff_loc_id();
	}
	else if ($off_type == "B"){
		$off_condition = " and a1.dept_id = ".$userProfile->getDept_id()." and a1.off_level_dept_id >= ".$userProfile->getOff_level_dept_id();
		$off_cond_para = "B-".$userProfile->getDept_id()."-".$userProfile->getOff_level_dept_id()."-".$userProfile->getOff_loc_id();
	}
	else if ($off_type == "P"){
		$off_condition = " and a1.dept_id = ".$dept." and a1.off_level_dept_id = ".$off_level_dept_id." and a1.off_loc_id = ".$off_loc_id;
		$off_cond_para = "P-".$dept."-".$off_level_dept_id."-".$off_loc_id;
	}
	else {
		
		if ($userProfile->getDept_coordinating() and $userProfile->getOff_coordinating() and $userProfile->getDesig_coordinating()){
			$off_condition = " and a1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and a1.off_loc_id = ".$userProfile->getOff_loc_id();
			$off_cond_para = "G"."-".$userProfile->getOff_level_dept_id();
		}
		else {
		$off_condition = " and a1.dept_id = ".$userProfile->getDept_id()." and a1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and a1.off_loc_id = ".$userProfile->getOff_loc_id()." and a1.dept_user_id = ".$userProfile->getDept_user_id();
		$off_cond_para = "G"."-".$userProfile->getOff_level_dept_id();
		}
	}
	
	
		$off_condition = substr($off_condition,4,strlen($off_condition)-4);
	
	$dist_cond=$userProfile->getOff_level_id()==1 ? "mst_p_district" : "fn_single_district(".$userProfile->getDistrict_id().")"; // fn_single_district

		$sql="WITH off_pet AS (
		select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a.to_whom = ".$userProfile->getDept_user_id()." ".$cond3.$src_condition.$grev_condition.$grev_dept_condition."
		)";

		$sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)

		select * 

		from ( select aa.dept_desig_id, aa.dept_desig_name, bb.dept_id, bb.dept_name , bb.dept_tname, COALESCE(rwp.recd_cnt,0) as recd_cnt, COALESCE(cpa.cl_pet_a_cnt,0) as cl_pet_a_cnt, COALESCE(cpr.cl_pet_r_cnt,0) as cl_pet_r_cnt, COALESCE(pcbo.cl_pend_o_cnt,0) as cl_pend_o_cnt, COALESCE(pcb.cl_pend_cnt,0) as cl_pend_cnt, COALESCE(pcb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, COALESCE(pcb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, COALESCE(pcb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt 

		from (select dept_desig_id, dept_desig_name, dept_desig_tname from vw_usr_dept_users_v where dept_user_id = ".$userProfile->getDept_user_id().") aa 
		cross join usr_dept bb 

		left join -- received within the period 
		(select a1.dept_id,count(*) as recd_cnt
		from off_pet a1
		group by a1.dept_id) rwp on rwp.dept_id=bb.dept_id 

		left join -- closed petitions: status with 'A' 
		(select a1.dept_id,count(*) as cl_pet_a_cnt
		from off_pet a1
		where a1.l_action_type_code='A'
		group by a1.dept_id) cpa on cpa.dept_id=bb.dept_id 

		left join -- closed petitions: status with 'R' 
		(select a1.dept_id,count(*) as cl_pet_r_cnt
		from off_pet a1
		where a1.l_action_type_code='R'
		group by a1.dept_id) cpr on cpr.dept_id=bb.dept_id 

		left join -- pending with others 
		(select a1.dept_id,count(*) as cl_pend_o_cnt
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom <> ".$userProfile->getDept_user_id()."
		group by a1.dept_id) pcbo on pcbo.dept_id=bb.dept_id 

		left join -- pending: cl. bal. 
		(select a1.dept_id,count(*) as cl_pend_cnt, sum(case when (current_date - a1.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, sum(case when ((current_date - a1.petition_date::date) > 30 and (current_date - a1.petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, sum(case when (current_date - a1.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom = ".$userProfile->getDept_user_id()."
		group by a1.dept_id) pcb on pcb.dept_id=bb.dept_id ) b_rpt 

		where recd_cnt+cl_pet_a_cnt+cl_pet_r_cnt+cl_pend_o_cnt+cl_pend_cnt > 0 
		order by b_rpt.cl_pend_cnt desc";
 
	    $result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			if($_SESSION['lang']=='E'){
				$dept_name=$row['dept_name'];
			}else{
				$dept_name=$row['dept_tname'];
			}
	
			 
			$dept_id=$row['dept_id'];
			
//			$opening_pending=$row[op_pend_cnt]; 
			$received=$row['recd_cnt'];
			$accepted=$row['cl_pet_a_cnt'];
			$rejected=$row['cl_pet_r_cnt'];
			$closing_pending=$row['cl_pend_cnt'];
			$cl_pend_o_cnt=$row['cl_pend_o_cnt'];
			$cl_pend_leq30d_cnt=$row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt=$row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt=$row['cl_pend_gt60d_cnt'];
			//$cl_pend_less_cnt=$row[cl_pend_less_cnt];
			?>
			<tr>   
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_name; ?></td>
             <!-- $off_loc_name -->  

	
				<?php if($received!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "rwp"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $received;?></a></td>  
				<?php } 
					else {?>
						<td><?php echo $received;?> </td> <?php } ?>
			
				<?php if($accepted!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "cpa"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $accepted;?></a></td> 
				<?php } 
					else {?>
						<td> <?php echo $accepted;?> </td> <?php } ?>
			
				<?php if($rejected!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "cpr"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $rejected;?></a></td> 
				<?php } 
					else {?>
						<td> <?php echo $rejected;?> </td> <?php } ?>
				
				<?php if($cl_pend_o_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "pcbo"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $cl_pend_o_cnt;?></a></td>
				<?php } 
					else {?>
						<td> <?php echo $cl_pend_o_cnt;?> </td> <?php } ?>
						
				<?php if($closing_pending!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "pcb"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $closing_pending;?></a></td>
				<?php } 
					else {?>
						<td> <?php echo $closing_pending;?> </td> <?php } ?>
            
				

            	<?php if($cl_pend_leq30d_cnt!=0) {?>
            			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "pm2"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $cl_pend_leq30d_cnt;?></a></td>
	   			<?php } 
	   				else {?>
                    	<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>
                        
                <?php if($cl_pend_gt30leq60d_cnt!=0) { ?>
              			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "p2m"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"><?php echo $cl_pend_gt30leq60d_cnt;?></a></td>
	  			<?php } 
		 			 else {?>
           	 			 <td> <?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>
            
            	<?php if($cl_pend_gt60d_cnt!=0) {?>
              			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>', '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo "pm1"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $off_loc_name; ?>'  )"> <?php echo $cl_pend_gt60d_cnt;?></a></td>
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
			$tot_cl_pend_o_cnt=$tot_cl_pend_o_cnt+$cl_pend_o_cnt;
			$tot_cl_pend_more_cnt=$tot_cl_pend_more_cnt+$cl_pend_leq30d_cnt;
			$tot_cl_pend_2_cnt=$tot_cl_pend_2_cnt+$cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1_cnt=$tot_cl_pend_1_cnt+$cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo $label_name[16]; // Total?></td>
                <td><?php echo $tot_received;?></td>
                <td><?php echo $tot_accepted;?></td>
                <td><?php echo $tot_rejected;?></td>
				<td><?php echo $tot_cl_pend_o_cnt;?></td>
                <td><?php echo $tot_closing_pending;?></td>                
                <td><?php echo $tot_cl_pend_more_cnt;?></td>
           		<td><?php echo $tot_cl_pend_2_cnt;?></td>
            	<td><?php echo $tot_cl_pend_1_cnt;?></td>
			</tr>
			<tr>
            <td colspan="12" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
            <input type="hidden" name="hid" id="hid" />
            <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
            <input type="hidden" name="frdate" id="frdate"  />
   		    <input type="hidden" name="todate" id="todate" />
    		<input type="hidden" name="dept" id="dept" />
            <input type="hidden" name="dept_name" id="dept_name" />
			<input type="hidden" name="off_loc_name" id="off_loc_name" />
     		<input type="hidden" name="status" id="status" /> 
			<input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/> 
            <input type="hidden" name="pet_own_dept_name" id="pet_own_dept_name" value='<?php echo $pet_own_dept_name ?>'/>
			<input type="hidden" name="reporttypename" id="reporttypename" value='<?php echo $reporttypename ?>'/> 
            <input type="hidden" name="src_id" id="src_id" />
    		<input type="hidden" name="sub_src_id" id="sub_src_id" />
            <input type="hidden" name="gtypeid" id="gtypeid" />
            <input type="hidden" name="gsubtypeid" id="gsubtypeid" />
            <input type="hidden" name="grie_dept_id" id="grie_dept_id" />
            <input type="hidden" name="off_cond_para" id="off_cond_para" />
			<input type="hidden" name="dept_condition" id="dept_condition" value="<?php echo $grev_dept_condition; ?>"/>
       		 
            </td></tr>
		<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo $label_name[31]; //No Records Found?>...</td></tr>
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
$rep_src=stripQuotes(killChars($_POST["rep_src"])); 

$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$dept_id=stripQuotes(killChars($_POST["dept"]));
$dept_name=stripQuotes(killChars($_POST["dept_name"]));  
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
$reporttypename=$_POST["reporttypename"];
	
	$pet_own_heading = "";
	if ($pet_own_dept_name != "") {
		$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$pet_own_dept_name;
	}
		
	if ($off_loc_name != "") {
		$pet_own_heading = $pet_own_heading." - Office Location: ".$off_loc_name;
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
    //echo $grev_dept_condition;
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

	$off_condition = "";
	if(!empty($off_cond_para)){
		$off_cond_paras = explode("-", $off_cond_para);
		if ($off_cond_paras[0] == "O"){
			$off_condition = " and a1.dept_id = ".$off_cond_paras[1]." and a1.off_level_dept_id = ".$off_cond_paras[2]." and a1.off_loc_id = ".$off_cond_paras[3];
		}
		else if ($off_cond_paras[0] == "S"){
			$off_condition = " and a1.dept_id = ".$off_cond_paras[1]." and a1.off_level_dept_id > ".$off_cond_paras[2];
		}
		else if ($off_cond_paras[0] == "B"){
			$off_condition = " and a1.dept_id = ".$off_cond_paras[1]." and a1.off_level_dept_id >= ".$off_cond_paras[2];
		}
		else if ($off_cond_paras[0] == "P"){
			$off_condition = " and a1.dept_id = ".$off_cond_paras[1]." and a1.off_level_dept_id = ".$off_cond_paras[2]." and a1.off_loc_id = ".$off_cond_paras[3];
		}
		else {
			if ($userProfile->getDept_coordinating() and $userProfile->getOff_coordinating() and $userProfile->getDesig_coordinating()){
//				$off_condition = " and a1.off_level_id in (2,3,4,5,6,7,10,11)";				
			$off_condition = " and a1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and a1.off_loc_id = ".$userProfile->getOff_loc_id();
			}
			else {
			$off_condition = " and a1.dept_id = ".$userProfile->getDept_id()." and a1.off_level_dept_id = ".$userProfile->getOff_level_dept_id()." and a1.off_loc_id = ".$userProfile->getOff_loc_id();
			}
		}		
	}

	$off_condition = substr($off_condition,4,strlen($off_condition)-4);

		   //echo $grev_condition;
$_SESSION["check"]="yes"; 

if($status=='rwp')
	$cnt_type=" ".$label_name[7];//" Received Petitions";
else if($status=='cpa')
	$cnt_type=" ".$label_name[9];//" Accepted Petitions";
else if($status=='cpr')
	$cnt_type=" ".$label_name[10];//" Rejected Petitions";
else if($status=='pcb')
	$cnt_type=" ".$label_name[11];//" Closing Balance (Pending)";
else if($status=='pcbo')
	$cnt_type=" ".$label_name[32];//" Pending with others";
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
				<th colspan="8" class="main_heading"><?PHP echo $label_name[33]." - ";?> <?php echo " ".$cnt_type; ?></th>
                </tr>
                
                 <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="8" class="main_heading"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
                
				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="8" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
                 <tr>
                <th colspan="8" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo $label_name[21]; //S.No.?></th>
				<th><?PHP echo $label_name[22]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[23]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[24]; //Source & Sub Source?> & <?PHP echo $label_name[25]; //Source Remarks?></th>
				<th><?PHP echo $label_name[26]; //Grievance?></th>
				<th><?PHP echo $label_name[27]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[28]; //Action Type, Date & Remarks?></th>
                <th><?PHP echo $label_name[29]; //Pending Period?></th>
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

	$aspect_cond7="";
	if(!empty($dept_id)) {
		$aspect_cond7 = " and (a.dept_id=".$dept_id.") ";
	}

	if($status=='rwp'){
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
		}
	else if($status=='cpa'){
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code='A'";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
		}
	else if($status=='cpr'){	
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code='R'";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
		}
	else if($status=='pcbo'){
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom <> ".$userProfile->getDept_user_id()."";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
	 }
	else if($status=='pcb'){
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom = ".$userProfile->getDept_user_id()."";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
	 }
	else if ($status=='pm2') 
	{
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom = ".$userProfile->getDept_user_id()." and (case when (current_date -  a1.petition_date::date) <= 30 then 1 else 0 end)=1";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
	}
	else if ($status == 'p2m') {
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom = ".$userProfile->getDept_user_id()." and (case when ((current_date -  a1.petition_date::date) > 30 and (current_date -  a1.petition_date::date)<=60)  then 1 else 0 end)=1";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
	} 
	else if ($status == 'pm1') {
		$sub_sql="WITH off_pet AS (
		select op.petition_id, op.action_type_code, op.petition_date, op.source_id, op.subsource_id, op.griev_type_id, op.griev_subtype_id, op.dept_id, oq.l_action_type_code, oq.l_action_entby, oq.l_to_whom from
		(select a1.petition_id, a1.action_type_code, a.petition_date, a.source_id, a.subsource_id, a.griev_type_id, a.griev_subtype_id, a.dept_id, 
		cast (rank() OVER (PARTITION BY a.petition_id ORDER BY a1.action_entdt DESC) as integer) rnk
		from pet_action a1
		inner join pet_master a on a.petition_id=a1.petition_id
		where a1.to_whom = ".$userProfile->getDept_user_id()." and ".$cond3.$aspect_cond7.$src_condition.$grev_condition.$grev_dept_condition."
		) op 
		inner join pet_action_first_last oq on oq.petition_id=op.petition_id
		where op.rnk=1)
		select a1.petition_id
		from off_pet a1
		where a1.l_action_type_code not in ('A','R') and a1.l_to_whom = ".$userProfile->getDept_user_id()." and (case when (current_date -  a1.petition_date::date) > 60 then 1 else 0 end)=1";

		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period 
		from fn_petition_details(array(".$sub_sql."))";
	} 

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
			<td class="desc" style="width:13%;"> <a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."&nbsp;"."&amp;"."<br/>".$row['petition_date']; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo ($row['subsource_remarks'] != '')? ' & '.$row['subsource_remarks']:'';?></td>
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:20%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
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
