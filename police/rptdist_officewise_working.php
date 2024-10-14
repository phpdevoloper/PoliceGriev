<?php
ob_start();
session_start();
include("db.php");
include("header_menu.php");
/*include("menu_home.php");*/
include("common_date_fun.php");
include("pm_common_js_css.php");

if(stripQuotes(killChars($_POST['hid_yes']))!="")
	$check=stripQuotes(killChars($_POST['hid_yes']));
else
	$check=$_SESSION["check"];

if($check=='yes')
{
$pagetitle="Officers wise Pendency Report - Based on Petition Period";
?>
  
<script type="text/javascript">
	
		
function detail_view(frm_date,to_date,dept,dept_name,status,src_id,sub_src_id,gtypeid,gsubtypeid,grie_dept_id,off_loc,dept_user_id)
{
	alert(off_loc);	
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("status").value=status;		
	document.getElementById("h_off_loc_id").value=off_loc;
	document.getElementById("src_id").value=src_id;
	document.getElementById("sub_src_id").value=sub_src_id;
	document.getElementById("gtypeid").value=gtypeid;
	document.getElementById("gsubtypeid").value=gsubtypeid;
	document.getElementById("grie_dept_id").value=grie_dept_id;			
	document.getElementById("dept_user_id").value=dept_user_id;	
alert(off_loc);		
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_officewise.php";
	document.rpt_abstract.target= "_blank";
	document.rpt_abstract.submit(); 
	return false;
}
function maintain_val(rep_src)
{
	//alert(rep_src);
	document.rpt_abstract.method="post";
	if (rep_src == 'simple')
		document.rpt_abstract.action="rptdist_reports_s.php";
	else 
		document.rpt_abstract.action="rptdist_reports.php";
	document.rpt_abstract.target= "_self";
	document.rpt_abstract.submit(); 

	return false;
}
</script>
<?php
if($check!="")
	$actual_link =basename($_SERVER['REQUEST_URI']); 
else
	$actual_link =basename(substr($_SERVER['REQUEST_URI'],0,-8));//"$_SERVER[REQUEST_URI]";

	$qry = "select label_name,label_tname from apps_labels where menu_item_id=71 order by ordering";
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
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
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

	$subordinate_level=stripQuotes(killChars($_POST["s_office"]));
	$sub_dept=stripQuotes(killChars($_POST["s_dept"]));
	
	$s_district=stripQuotes(killChars($_POST["s_district"]));
	$s_taluk=stripQuotes(killChars($_POST["s_taluk"]));
	$s_rdo=stripQuotes(killChars($_POST["s_rdo"]));
	
	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));
	$grev_taluk = stripQuotes(killChars($_POST["grev_taluk"]));
	$grev_rev_village = stripQuotes(killChars($_POST["grev_rev_village"]));
	$grev_block = stripQuotes(killChars($_POST["grev_block"]));
	$grev_p_village = stripQuotes(killChars($_POST["grev_p_village"]));
	$grev_urban_body = stripQuotes(killChars($_POST["grev_urban_body"]));
	$grev_office = stripQuotes(killChars($_POST["grev_office"]));
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));

	$off_level_id=$subordinate_level;

	if ($subordinate_level != "") {
		$of_sql="SELECT  dept_id, off_level_id, off_level_dept_name, off_level_dept_tname  
			FROM usr_dept_off_level where dept_id=".$userProfile->getDept_id()." and 
			off_level_id=".$subordinate_level." order by off_level_id";
		$rs=$db->query($of_sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$off_level_dept_name=$row[2];
		$off_level_id=$row[1];
		$off_level_name = "Office Level: &nbsp;&nbsp;".$off_level_dept_name;	
	}
	if ($s_district != "") {
		$dist_sql="select district_id,district_name from mst_p_district where  district_id=".$s_district."";
		
		$rs=$db->query($dist_sql);
		$row=$rs->fetch(PDO::FETCH_BOTH);
		$dist_name=$row[1];
		$off_level_name .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"." District: ".$dist_name;
	}
	$grie_dept_id = stripQuotes(killChars($_POST["grie_dept_id"]));
		
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
	$grev_dept_condition = "";
	$off_type = $_POST["offtype"];	
	
	
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
		$petition_type_condition = "";
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
			$petition_type_condition = " and (b.pet_type_id=".$petition_type.")";
		}
		
		if ($off_type != "B" && $grie_dept_id != "") {
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
		$pet_own_heading = "";
		if ($pet_own_dept_name != "") {
			$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$pet_own_dept_name;
		}
		
		if ($off_loc_name != "") {
			$pet_own_heading = $pet_own_heading." - Office Location: ".$off_loc_name;
		}		
					
?>
<div class="contentMainDiv">
	<div class="contentDiv" style="width:90%;margin:auto;">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="12" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
		<!--	<a href="" onclick="return maintain_val('<?php //echo $rep_src ?>');"><img src="images/bak.jpg" /></a> -->
			</th></tr>
            <tr> 
				<th colspan="12" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Office Leval and Office Location Name?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[63]; //Office wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="12" class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<tr> 
				<th colspan="12" class="search_desc"><?PHP echo $off_level_name; //Report type name?></th>
			</tr>
			
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="12" class="search_desc"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="12" class="search_desc"><b>Petition Period -  </b><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			<tr> 
				<th colspan="12" class="search_desc">
				<?PHP echo $label_name[6].' (A) = (B) + (D) + (F)'; ?>
				 &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
				<?PHP echo $label_name[37].' (F) = (H) + (I) + (J)';?>	
				</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="3"  style="width:3%;"><?PHP echo $label_name[3]; //S.No.?> <br></th>
				<?php
				if ($subordinate_level == '4') {
				?>	
				<th rowspan="3"  style="width:20%;"><?PHP echo $label_name[66]//'Office Name'; //Concerned Officer?> <br></th>
				<?php } else if ($subordinate_level == '3') { ?>
				<th rowspan="3"  style="width:20%;"><?PHP echo $label_name[65]//'Office Name'; //Concerned Officer?> <br></th>
				<?php } else { ?>
				<th rowspan="3"  style="width:20%;"><?PHP echo $label_name[68]//'Office Name'; //Concerned Officer?> <br></th>
			    <?php } ?>
				<th rowspan="3"><?PHP echo $label_name[6]; //Total Received?><br>(A)</th>
                <th colspan="4" style="width: 70%;"><?PHP echo $label_name[64];//'Disposed'; ?></th>
				<th style="width:10%;" rowspan="3"><?PHP echo $label_name[37]//'Pending';?><br>(F)</th>
				<th style="width:10%;" rowspan="3"><?PHP echo $label_name[37].' %';?><br>(G)</th>
				<th style="width:10%;" rowspan="3"><?PHP echo $label_name[11];//'Pending <=30 Days'?><br>(H)</th>
				<th style="width:10%;" rowspan="3"><?PHP echo $label_name[12];//'Pending >30 Days <=60 Days'?><br>(I)</th>
				<th style="width:10%;" rowspan="3"><?PHP echo $label_name[13];//'Pending >60 Days'?><br>(J)</th>
			</tr>
			
			<tr>
                <th style="width:10%;"><?PHP echo $label_name[8]; // Accepted?><br>(B)</th>
                <th style="width:10%;"><?PHP echo $label_name[8].' %'?><br>(C)</th>
				<th style="width:10%;"><?PHP echo $label_name[9] //'Rejected'?><br>(D)</th>
                <th style="width:10%;"><?PHP echo $label_name[9].' %' ?><br>(E)</th>				
			
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
	 
	$off_condition = "";
	$outer_condition = "";
	$inner_condition = "";
		
	if ($s_district != "") {
		$outer_condition = " where tk1.district_id=".$s_district." ";
		$inner_condition = " where tk.district_id=".$s_district." ";
		$inner_condition2 = " and a.griev_district_id=".$s_district." and ";
	} else {
		$inner_condition2 = " and ";
	}	
	if ($inner_condition != "") {
		$inner_condition1 = $inner_condition." and ";
	} else {
		$inner_condition1 = " and ";
	}
	
	$dist_cond=($s_district== "") ? "mst_p_district md" : "fn_single_district(".$s_district.") md";
	$dist_cond_1=($s_district== "") ? "mst_p_district " : "fn_single_district(".$s_district.") ";


	$dept=$userProfile->getDept_id(); // substr($sub_dept, 0,1);
	$tbl_pet_list = "fn_pet_action_first_last_off_level(".$dept.",".$off_level_id.")";
	
	$dist_cond="lkp_pet_source bb";
	$dist_params = '';
	if ($subordinate_level==1) {
		// code not reachable
	}
	else if ($subordinate_level==2) {	
		$dist_cond='(select a.district_id,a.district_id as off_location_id,a.district_name,a.district_name as off_loc_name,a.district_tname,a.district_tname as off_loc_tname,c.dept_user_id,c.dept_desig_id,c.dept_desig_name,c.dept_desig_tname from '.$dist_cond_1.' a
		inner join vw_usr_dept_users_v c on c.off_loc_id=a.district_id and c.off_level_id=2 and c.pet_disposal) aa';
		$dist_params = 'aa.district_id ,aa.district_name,aa.district_tname,';
		$off_level_params = 'aa.district_id as off_location_id,aa.off_loc_name,aa.off_loc_tname,aa.dept_user_id,aa.dept_desig_id,aa.dept_desig_name,aa.dept_desig_tname,';
		$off_level_pk_select = 'district_id,action_entby';
		$off_level_pk = 'district_id';
	}
	else if ($subordinate_level==3) {	
		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.rdo_id,b.rdo_name as off_loc_name,b.rdo_tname as off_loc_tname,c.dept_user_id,c.dept_desig_id, c.dept_desig_name,
		c.dept_desig_tname  from '.$dist_cond_1.' a inner join mst_p_rdo b on b.district_id=a.district_id
		inner join vw_usr_dept_users_v c on c.off_loc_id=b.rdo_id and c.off_level_id=3 and c.pet_disposal) aa';
		
		
		$dist_params = 'aa.district_id,aa.district_name,aa.district_tname,';
		$off_level_params = 'aa.rdo_id as off_location_id,aa.off_loc_name,aa.off_loc_tname,aa.off_loc_tname, aa.off_loc_tname,aa.dept_user_id,aa.dept_desig_id,aa.dept_desig_name,aa.dept_desig_tname,';
		$off_level_pk_select = 'rdo_id,action_entby';
		$off_level_pk = 'rdo_id';
	}
	else if ($subordinate_level==4) {	
		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.taluk_id,b.taluk_name as off_loc_name,b.taluk_tname as off_loc_tname 
		from mst_p_district a 
		inner join mst_p_taluk b on b.district_id=a.district_id) aa';		
		
		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.taluk_id,b.taluk_name  as off_loc_name,b.taluk_tname as off_loc_tname,c.dept_user_id,c.dept_desig_id, c.dept_desig_name,
		c.dept_desig_tname from '.$dist_cond_1.' a 
		inner join mst_p_taluk b on b.district_id=a.district_id
		inner join vw_usr_dept_users_v c on c.off_loc_id=b.taluk_id and c.off_level_id=4 and c.pet_disposal) aa';
		
		$dist_params = 'aa.district_id,aa.district_name,aa.district_tname,';
		$off_level_params = 'aa.taluk_id as off_location_id,aa.off_loc_name,
		aa.off_loc_tname, aa.off_loc_tname,aa.dept_user_id,aa.dept_desig_id,aa.dept_desig_name,aa.dept_desig_tname,';
		$off_level_pk_select = 'taluk_id,action_entby';
		$off_level_pk = 'taluk_id ';
	}

	$sql="WITH off_pet AS (
	select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date,a.action_entby 
	from ".$tbl_pet_list." a
	inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby 
	inner join pet_master b on b.petition_id=a.petition_id
	where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
	)

	select * 

	from ( select ".$dist_params.$off_level_params." COALESCE(rwp.tot,0) as tot,
	COALESCE(cpa.acp,0) as acp, 
	COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper, 
	COALESCE(cpr.rej,0) as rej, 
	COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper,
	COALESCE(pcb.pnd,0) as pnd, 
	COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper, 
	COALESCE(pcb.pnd_lt_eq_30,0) as pnd_lt_eq_30, COALESCE(pcb.pnd_31_to_60,0) as pnd_31_to_60, COALESCE(pcb.pnd_gt_60,0) as pnd_gt_60 

	from ".$dist_cond."

	left join -- received within the period 
  
	  (select ".$off_level_pk_select.",count(*) as tot from off_pet group by ".$off_level_pk_select.") rwp on rwp.".$off_level_pk."=aa.".$off_level_pk." and rwp.action_entby=aa.dept_user_id

	  left join -- closed petitions: status with 'A' 
	  (select ".$off_level_pk_select.",count(*) as acp from off_pet a 
	  inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code='A' group by ".$off_level_pk_select.") cpa on cpa.".$off_level_pk."=aa.".$off_level_pk."   and cpa.action_entby=aa.dept_user_id

	  left join -- closed petitions: status with 'R' 
	  (select ".$off_level_pk_select.",count(*) as rej from off_pet a 
	  inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code='R' group by ".$off_level_pk_select.") cpr on cpr.".$off_level_pk."=aa.".$off_level_pk."  and cpr.action_entby=aa.dept_user_id
	  
	  left join -- pending: cl. bal. 
	  (select ".$off_level_pk_select.",count(*) as pnd, 
	  sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as pnd_lt_eq_30, 
	  sum(case when ((current_date - petition_date::date) > 30 and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as pnd_31_to_60, 
	  sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as pnd_gt_60 
	  from off_pet a 
	  inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code not in ('A','R')
	  group by ".$off_level_pk_select.") pcb on pcb.".$off_level_pk."=aa.".$off_level_pk." and pcb.action_entby=aa.dept_user_id) b_rpt 
	  
	  --where tot+acp+rej+pnd > 0 
	  order by b_rpt.district_id, off_location_id,dept_desig_id";
//echo $sql;
if ($subordinate_level>4){
	$sql="select * from mst_p_district where district_id < 0";
}


		$result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$temp_dist_id='';
		$temp_loc_id = '';
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		$f_row = 0;
		$t_row = 0;
		
				
if($row_cnt!=0)
{
	$arrlength = count($rowarray);
	$x = 0;
	$gross_tot_tot = 0;
	$gross_tot_acp = 0;
	$gross_tot_rej = 0;
	$gross_tot_pnd = 0;
	$gross_tot_pnd_lt_eq_30 = 0;
	$gross_tot_pnd_31_to_60 = 0;
	$gross_tot_pnd_gt_60 = 0;				
	$gross_tot_acc_per = 0;
	$gross_tot_rej_per = 0;
	$gross_tot_pnd_per = 0;
	
	while($x < $arrlength) {
		
		$ary=$rowarray[$x];
		
		$dst_id=$ary[district_id];
		
		$district_name = $ary[district_name];
				
		$tot_tot = 0;
		$tot_acp = 0;
		$tot_rej = 0;
		$tot_pnd = 0;
		$tot_pnd_lt_eq_30 = 0;
		$tot_pnd_31_to_60 = 0;
		$tot_pnd_gt_60 = 0;
		
		$tot_acc_per = 0;
		$tot_rej_per = 0;
		$tot_pnd_per = 0;
				
		if ($subordinate_level == 3){
			$off_name = 'Rdo';
		} else if ($subordinate_level == 4){
			$off_name = 'Taluk';
		}

		?>
		<tr>
			<td class="h1" style="text-align:left" colspan="12"><?PHP echo $district_name." ".$label_name[69]; ?></td>
		</tr>
		<?php 
		
		while($ary[district_id]==$dst_id){			
			$office_loc_name = $ary[off_loc_name];
			$tlk_id=$ary[off_location_id];
	?>
			<?php if ($subordinate_level != 2) { ?>	
			<tr>					
			<td class="h1" style="text-align:left" colspan="12"><?PHP echo $office_loc_name." ".$off_name; ?></td>			
			</tr>
			<?php } ?>
			<?php 
			
			
			//echo $tlk_id;
			
			$i = 0;
			while($ary[off_location_id]==$tlk_id){
				
				$i++;
				/*$ary=$rowarray[$x];*/	
				$ary=$rowarray[$x];
				
				$off_location_id=$ary[off_location_id];
				$off_loc_name=$ary[off_loc_name];
				$tot=$ary[tot];
				$acp=$ary[acp];
				$acpper=$ary[acpper];
				$rej=$ary[rej]; 
				$rejper=$ary[rejper]; 
				
				$pnd=$ary[pnd]; 
				$pndper=$ary[pndper]; 			
				
				$pnd_lt_eq_30=$ary[pnd_lt_eq_30]; 
				$pnd_31_to_60=$ary[pnd_31_to_60]; 
				$pnd_gt_60=$ary[pnd_gt_60]; 
				$dist_id=$ary[district_id];
				
				$off_location_name=$ary[off_loc_name];
				$dept_desig_name=$ary[dept_desig_name];
				$district_name=$ary[district_name];			
				$dept_user_id=$ary[dept_user_id]; 
				
				
				?>
				<tr>
			
                <td><?php echo $i;?></td>
				<?php //if ($subordinate_level == '2') dept_desig_id { ?>
                <!--td class="desc"><?PHP //echo $off_loc_name; ?></td-->
				<?php //} else { ?>
				<td class="desc"><?PHP echo $dept_desig_name; ?></td>
				<?php //} ?>
                <?php if ($tot > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>', '<?php echo 'recd'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $tot; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $tot; ?></td>
				<?php } ?>
				
				<?php if ($acp > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo 'acp'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $acp; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $acp; ?></td>
				<?php } ?>
				
						 
                <td class="desc" style="text-align:center"><?PHP echo $acpper; ?></td>
				
				<?php if ($rej > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo 'rej'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $rej; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $rej; ?></td>
				<?php } ?>
									 
				<td class="desc" style="text-align:center"><?PHP echo $rejper; ?></td>
				
				<?php if ($pnd > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo 'pnd'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $pnd; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $pnd; ?></td>
				<?php } ?>
				
				 
				<td class="desc" style="text-align:center"><?PHP echo $pndper; ?></td>
				
				<?php if ($pnd_lt_eq_30 > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>', '<?php echo 'pnd_lt_eq_30'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $pnd_lt_eq_30; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $pnd_lt_eq_30; ?></td>
				<?php } ?>
				
				<?php if ($pnd_31_to_60 > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>', '<?php echo 'pnd_31_to_60'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')">
						<?PHP echo $pnd_31_to_60; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $pnd_31_to_60; ?></td>
				<?php } ?>
				
				<?php if ($pnd_gt_60 > 0) { ?>
				<td class="desc" style="text-align:center">
				<a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo 'pnd_gt_60'; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>',
						'<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_location_id; ?>','<?php echo $dept_user_id; ?>')"><?PHP echo $pnd_gt_60; ?></a></td>
				<?php } else { ?>
				<td class="desc" style="text-align:center"><?PHP echo $pnd_gt_60; ?></td>
				<?php } ?>
		 
				
                      	
		</tr>				
			<?php 
			
				$tot_tot = $tot_tot + $tot;
				$tot_acp = $tot_acp + $acp;
				$tot_rej = $tot_rej + $rej;
				$tot_pnd = $tot_pnd + $pnd;
				$tot_pnd_lt_eq_30 = $tot_pnd_lt_eq_30 + $pnd_lt_eq_30;
				$tot_pnd_31_to_60 = $tot_pnd_31_to_60 + $pnd_31_to_60;
				$tot_pnd_gt_60 = $tot_pnd_gt_60 + $pnd_gt_60;
				
				$tot_acc_per = round((($tot_acp / $tot_tot) * 100),2);
				$tot_rej_per = round((($tot_rej / $tot_tot) * 100),2);
				$tot_pnd_per = round((($tot_pnd / $tot_tot) * 100),2);
				
				$tlk_tot_tot = $tlk_tot_tot + $tot;
				$tlk_tot_acp = $tlk_tot_acp + $acp;
				$tlk_tot_rej = $tlk_tot_rej + $rej;
				$tlk_tot_pnd = $tlk_tot_pnd + $pnd;
				$tlk_tot_pnd_lt_eq_30 = $tlk_tot_pnd_lt_eq_30 + $pnd_lt_eq_30;
				$tlk_tot_pnd_31_to_60 = $tlk_tot_pnd_31_to_60 + $pnd_31_to_60;
				$tlk_tot_pnd_gt_60 = $tlk_tot_pnd_gt_60 + $pnd_gt_60;
				
				$tlk_tot_acc_per = round((($tlk_tot_acp / $tlk_tot_tot) * 100),2);
				$tlk_tot_rej_per = round((($tlk_tot_rej / $tlk_tot_tot) * 100),2);
				$tlk_tot_pnd_per = round((($tlk_tot_pnd / $tlk_tot_tot) * 100),2);
				
				$gross_tot_tot = $gross_tot_tot + $tot;
				$gross_tot_acp = $gross_tot_acp + $acp;
				$gross_tot_rej = $gross_tot_rej + $rej;
				$gross_tot_pnd = $gross_tot_pnd + $pnd;
				$gross_tot_pnd_lt_eq_30 = $gross_tot_pnd_lt_eq_30 + $pnd_lt_eq_30;
				$gross_tot_pnd_31_to_60 = $gross_tot_pnd_31_to_60 + $pnd_31_to_60;
				$gross_tot_pnd_gt_60 = $gross_tot_pnd_gt_60 + $pnd_gt_60;				
				$gross_tot_acc_per = round((($gross_tot_acp / $gross_tot_tot) * 100),2);
				$gross_tot_rej_per = round((($gross_tot_rej / $gross_tot_tot) * 100),2);
				$gross_tot_pnd_per = round((($gross_tot_pnd / $gross_tot_tot) * 100),2);
				
				$x++;
			} ?>
				
				<?php 
						if ($subordinate_level == 3) {
							$total_label = 	'RDO Total';
						} else if ($subordinate_level == 4) {
							$total_label = 	'Taluk Total';
						}
						if ($subordinate_level != 2) { 
					
				?>	
				<tr class="totalTR">
                <td colspan="2"><?PHP echo $total_label; ?></td>
                
                <td style="text-align:center"><?php echo $tlk_tot_tot;?></td>                      
                <td style="text-align:center"><?php echo $tlk_tot_acp;?></td>
           		<td style="text-align:center"><?php echo number_format($tlk_tot_acc_per,2)."<br>";?></td>
           		<td style="text-align:center"><?php echo $tlk_tot_rej;?></td>
            	<td style="text-align:center"><?php echo number_format($tlk_tot_rej_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $tlk_tot_pnd;?></td>
				<td style="text-align:center"><?php echo number_format($tlk_tot_pnd_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $tlk_tot_pnd_lt_eq_30;?></td>
                <td style="text-align:center"><?php echo $tlk_tot_pnd_31_to_60;?></td>
				<td style="text-align:center"><?php echo $tlk_tot_pnd_gt_60;?></td>
			</tr>
						<?php } ?>
		<?php 
			$tlk_tot_tot = 0;
			$tlk_tot_acp = 0;
			$tlk_tot_rej = 0;
			$tlk_tot_pnd = 0;
			$tlk_tot_pnd_lt_eq_30 = 0;
			$tlk_tot_pnd_31_to_60 = 0;
			$tlk_tot_pnd_gt_60 = 0;
			
			$tlk_tot_acc_per = 0;
			$tlk_tot_rej_per = 0;
			$tlk_tot_pnd_per = 0;
		} 
		//}
		?>
			<tr class="totalTR">
	             <td colspan="2"><?PHP echo 'District Total'; ?></td>                
                <td style="text-align:center"><?php echo $tot_tot;?></td>                      
                <td style="text-align:center"><?php echo $tot_acp;?></td>
           		<td style="text-align:center"><?php echo number_format($tot_acc_per,2)."<br>";?></td>
           		<td style="text-align:center"><?php echo $tot_rej;?></td>
            	<td style="text-align:center"><?php echo number_format($tot_rej_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $tot_pnd;?></td>
				<td style="text-align:center"><?php echo number_format($tot_pnd_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $tot_pnd_lt_eq_30;?></td>
                <td style="text-align:center"><?php echo $tot_pnd_31_to_60;?></td>
				<td style="text-align:center"><?php echo $tot_pnd_gt_60;?></td>
			</tr>
	<?php 

	} 
	?>
			<tr class="totalTR" style="background-color: #BA6060;">
                <td colspan="2"><?PHP echo 'Grand Total' ?></td>
                
                <td style="text-align:center"><?php echo $gross_tot_tot;?></td>                      
                <td style="text-align:center"><?php echo $gross_tot_acp;?></td>
           		<td style="text-align:center"><?php echo number_format($gross_tot_acc_per,2)."<br>";?></td>
           		<td style="text-align:center"><?php echo $gross_tot_rej;?></td>
            	<td style="text-align:center"><?php echo number_format($gross_tot_rej_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $gross_tot_pnd;?></td>
				<td style="text-align:center"><?php echo number_format($gross_tot_pnd_per,2)."<br>";?></td>
				<td style="text-align:center"><?php echo $gross_tot_pnd_lt_eq_30;?></td>
                <td style="text-align:center"><?php echo $gross_tot_pnd_31_to_60;?></td>
				<td style="text-align:center"><?php echo $gross_tot_pnd_gt_60;?></td>
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
			<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/>
			<input type="hidden" name="off_cond_para" id="off_cond_para" />
			<input type="hidden" name="fn_name" id="fn_name" />
			<input type="hidden" name="dept_condition" id="dept_condition" value="<?php echo $grev_dept_condition; ?>"/>
			<input type="hidden" name="pet_own_heading" id="pet_own_heading" value="<?php echo $pet_own_heading; ?>"/>
			
			<input type="hidden" name="h_s_dept_id" id="h_s_dept_id" value="<?php echo $userProfile->getDept_id(); ?>"/>
    		<input type="hidden" name="h_s_level" id="h_s_level"  value="<?php echo $subordinate_level; ?>"/>
			<input type="hidden" name="off_level_name" id="off_level_name"  value="<?php echo $off_level_name; ?>"/>
			
			<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" />
			<input type="hidden" name="dept_desig_id" id="dept_desig_id" />
			
            </td></tr>
			<thead>
			<tr style="background-color: #BC7676;text-align: center;"><td colspan="12">
			<a href="" onclick="self.close();"><img src="images/bak.jpg"  /></a>
		<!--	<a href="" onclick="return maintain_val('<?php //echo $rep_src ?>');"><img src="images/bak.jpg" /></a> -->
			</td></tr>
<?php }		
		else {?>
         <table class="rptTbl">
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
}


 ?>
<?php
if(stripQuotes(killChars($_POST['hid']))=='done')
{/*
	$from_date=$_POST["from_date"]; 
	$to_date=$_POST["to_date"]; 
	$dept_id=$_POST["dept"];
	$status=$_POST["status"];*/
		 
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

    //map = window.open("", "Map", "status=0,title=0,height=600,width=800,scrollbars=1");
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
   //header("Location: logout.php");
    //echo "Timed out. Please login again";
    //echo "<script>alert('Check User ID');<script>";
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


$s_dept_id = stripQuotes(killChars($_POST["h_s_dept_id"]));	  
$sub_level = stripQuotes(killChars($_POST["h_s_level"]));
$s_off_loc = stripQuotes(killChars($_POST["h_off_loc_id"]));
$off_level_name = stripQuotes(killChars($_POST["off_level_name"]));


//echo ">>>>>>>>>>>>>>>>>".$sub_level;
if ($sub_level == 3) {
	$sql = "SELECT off_level_dept_id, off_level_id, off_level_dept_name  
	FROM usr_dept_off_level where off_level_id = ".$sub_level." and dept_id=".$s_dept_id."";
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_level_dept_name=$row[2];
	$sql="SELECT rdo_id, rdo_name  FROM mst_p_rdo where rdo_id=".$s_off_loc;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[1];
} else if ($sub_level == 4) {
	$sql = "SELECT off_level_dept_id, off_level_id, off_level_dept_name  
	FROM usr_dept_off_level where off_level_id = ".$sub_level." and dept_id=".$s_dept_id."";
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_level_dept_name=$row[2];
	$sql="SELECT taluk_id, taluk_name  FROM mst_p_taluk where taluk_id=".$s_off_loc;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[1];
	
} else if ($sub_level == 2) {
	$sql = "SELECT off_level_dept_id, off_level_id, off_level_dept_name  
	FROM usr_dept_off_level where off_level_id = ".$sub_level." and dept_id=".$s_dept_id."";
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_level_dept_name=$row[2];
	$sql="SELECT district_id, district_name  FROM mst_p_district where district_id=".$s_off_loc;
	$rs=$db->query($sql);
	$row=$rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[1];
}

$dept_condition=$_POST["dept_condition"];

if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
			
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (b.dept_id=".$griedeptid.") ";
	}
	
	/*$grev_dept_condition = "";
	if(!empty($dept_condition)) {
		$grev_dept_condition = $dept_condition;
	}*/
	
	
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
			//echo ">>>>>>>>>>>>>>>>>>>>>>>".$dept;
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
		
   //echo $grev_condition;
$_SESSION["check"]="yes"; 

if($status=='recd')
	$cnt_type=" Received Petitions";
else if($status=='acp')
	$cnt_type=" Accepted Petitions";
else if($status=='rej')
	$cnt_type=" Rejected Petitions";
else if($status=='pnd')
	$cnt_type=" Pending Petitions";
else if($status=='pnd_lt_eq_30')
	$cnt_type=" Petitions pending for < 1 Month";
else if($status=='pnd_31_to_60')
	$cnt_type=" Petitions pending for > 1 month and < 2 Months";
else if($status=='pnd_gt_60')
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
				<th colspan="9" class="main_heading"><?PHP echo $label_name[63]." - ";?> <?php echo "Details of ".$cnt_type; ?></th>
                </tr>
                
				<tr> 
				<th colspan="9" class="main_heading"><?PHP echo $off_level_dept_name." - ".$off_loc_name;?></th>
                </tr>
				
                 <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="9" class="main_heading"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
                

				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="9" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
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
		 $cond1.="b.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="b.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="b.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="b.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";  	  
	}

	$tbl_pet_list = "fn_pet_action_first_last_p_office(".$s_dept_id.",".$sub_level.",".$s_off_loc.")";

//	$dist_cond=($s_district== "") ? "mst_p_district md" : "fn_single_district(".$s_district.") md";
//	$dist_cond_1=($s_district== "") ? "mst_p_district " : "fn_single_district(".$s_district.") ";
//	$dept=substr($sub_dept, 0,1);
//	$dist_cond="lkp_pet_source bb";
//	$dist_params = '';
	if ($sub_level==1) {
		// code not reachable
	}
	else if ($sub_level==2) {	
//		$dist_cond='(select a.district_id,a.district_name,a.district_name as off_loc_name,a.district_tname,a.district_tname as off_loc_tname from '.$dist_cond_1.' a) aa';
//		$dist_params = 'aa.district_id,aa.district_name,aa.district_tname,';
//		$off_level_params = 'aa.off_loc_name,aa.off_loc_tname,';
		$off_level_pk = 'district_id';
	}
	else if ($sub_level==3) {	
//		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.rdo_id,b.rdo_name as off_loc_name,b.rdo_tname as off_loc_tname from '.$dist_cond_1.' a inner join mst_p_rdo b on b.district_id=a.district_id) aa';
//		$dist_params = 'aa.district_id,aa.district_name,aa.district_tname,';
//		$off_level_params = 'aa.rdo_id,aa.off_loc_name,aa.off_loc_tname,';
		$off_level_pk = 'rdo_id';
	}
	else if ($sub_level==4) {	
//		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.taluk_id,b.taluk_name as off_loc_name,b.taluk_tname as off_loc_tname from mst_p_district a inner join mst_p_taluk b on b.district_id=a.district_id) aa';
//		$dist_cond='(select a.district_id,a.district_name,a.district_tname,b.taluk_id,b.taluk_name as off_loc_name,b.taluk_tname as off_loc_tname from '.$dist_cond_1.' a inner join mst_p_taluk b on b.district_id=a.district_id) aa';
//		$dist_params = 'aa.district_id,aa.district_name,aa.district_tname,';
//		$off_level_params = 'aa.taluk_id,aa.off_loc_name,aa.off_loc_tname,';
		$off_level_pk = 'taluk_id';
	}

    //echo "=====================================".$status;


	if($status=='recd'){					
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id 
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		";
		
		$sql=" -- Received
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";

 	}
	
	else if($status=='acp'){

		
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id 
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'A')";
		
		$sql=" -- Accepted
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	}

	else if($status=='rej'){	
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and d.l_action_type_code = 'R')";
		
		$sql=" -- Rejected
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	}

	else if($status=='pnd'){	
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and 
		d.l_action_type_code not in ('A','R'))";
		
		$sql=" -- Pending with Disposing Officer
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";	
	}

	else if($status=='pending'){
		$sub_sql="With off_pet as
		(
		select a.petition_id, b.petition_date, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id
		from fn_pet_action_first_last_received_from(".$h_dept_user_id.") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id." 
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition.")
		
		select a.petition_id from off_pet a
		where not exists (select * from pet_action_first_last d1 where d1.petition_id=a.petition_id and d1.l_action_type_code in ('A','R')) and not exists (select * from fn_pet_action_first_last_cb_with(".$h_dept_user_id.") d2 where d2.petition_id=a.petition_id)";
		
		$sql=" -- Pending with Disposing Officer
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	}
	else if ($status=='pnd_lt_eq_30') 
	{
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and 
		d.l_action_type_code not in ('A','R'))
		and (case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end)=1";
		
		$sql=" -- Pending with Disposing Officer
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	}
	
	else if ($status == 'pnd_31_to_60') {
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and 
		d.l_action_type_code not in ('A','R'))
		and (case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60)  then 1 else 0 end)=1";
		
		$sql=" -- Pending with Disposing Officer
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	} 

	else if ($status == 'pnd_gt_60') {
		$sub_sql="WITH off_pet AS (
		select a.petition_id, a.action_type_code, a1.off_loc_id as ".$off_level_pk.", b.petition_date
		from ".$tbl_pet_list." a
		inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby and a.action_entby=".$dept_user_id."
		inner join pet_master b on b.petition_id=a.petition_id
		where ".$cond3.$src_condition.$grev_condition.$grev_dept_condition.$petition_type_condition."
		)
		
		select a.petition_id from off_pet a
		where exists (select * from pet_action_first_last d where d.petition_id=a.petition_id and 
		d.l_action_type_code not in ('A','R'))
		and (case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end)=1";
		
		$sql=" -- Pending with Disposing Officer
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period ,v.pet_type_name
		from fn_pet_last_action_details(array(".$sub_sql.")) v";
	} 

	//echo $sql;
    //die();
	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			//echo "1111111";
			if ($row[subsource_name] != null || $row[subsource_name] != "") {
				//$source_details = ucfirst(strtolower($row[source_name])).' & '.ucfirst(strtolower($row[subsource_name]));
				$source_details = $row[source_name].' & '.$row[subsource_name];
			} else {
				$source_details = $row[source_name];
			}
			

			?>
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:13%;"> <a href=""  onclick="return petition_status('<?php echo $row[petition_id]; ?>')">
			<?PHP  echo $row[petition_no]."&nbsp;"."&amp;"."<br/>".$row[petition_date]; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row[pet_address] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:20%;white-space: normal;"> <?PHP echo $row[grievance] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row[griev_type_name].",".$row[griev_subtype_name]."&nbsp;"."<br>Address: ".$row[gri_address]; ?></td>
            
<td class="desc"> 
<?PHP 
if($row['action_type_name']!="") {  
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
            
<!--			<td class="desc"> <?PHP //if($row['action_type_name']!="") { echo $row[action_type_name].			",".$row[fwd_date]."&".$row[fwd_remarks].":"."&nbsp;".ucfirst(strtolower($row[off_location_design])); }?></td>-->
            <td class="desc"> <?PHP echo $row[pend_period]; //ucfirst(strtolower($row[pend_period])); ?></td>
			</tr>
<?php $i++; } ?> 
			<tr>
			<td colspan="9" class="buttonTD">
<!--			<input type="button" name="" id="dontprint1" value="<?PHP echo $label_name[29]; //Print?>" class="button" onClick="return printReportToPdf()"> -->
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