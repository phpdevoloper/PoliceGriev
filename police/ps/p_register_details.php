<?PHP
ob_start(); 
session_start();
include("db.php");
include("header_menu.php");
include("header_menu_report.php");
include("common_date_fun.php");
include("pm_common_js_css.php");

$hid_yes="yes";

if($hid_yes=="yes")
{
 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Petition Processing Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>
<script type="text/javascript">
function printwindow()
{
document.getElementById("header").style.display='none'; 
document.getElementById("dontprint1").style.visibility='hidden';
document.getElementById("bak_btn").style.visibility='hidden'; 
document.getElementById("bak_btn1").style.visibility='hidden';
document.getElementById("header_report").style.display='block'; 
window.print();
document.getElementById("header").style.display=''; 
document.getElementById("dontprint1").style.visibility='visible';
document.getElementById("bak_btn").style.visibility='visible'; 
document.getElementById("bak_btn1").style.visibility='visible';
document.getElementById("header_report").style.display='none'; 	
}
function retainValues() {
	document.p_registerdetail.action="p_RegistersForm.php";
	document.p_registerdetail.submit();
}
</script>
<?php
$userProfile = unserialize($_SESSION['USER_PROFILE']); 
  $actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
        $qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rpt_anx1_details.php') order by ordering";

$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}

			
			$p_source=stripQuotes(killChars($_POST["p_source"]));
			$gtype=stripQuotes(killChars($_POST["gtype"]));
			$gstype=stripQuotes(killChars($_POST["gstype"]));
			$pat=stripQuotes(killChars($_POST["pat_id"]));
			$dept=stripQuotes(killChars($_POST["dept_id"]));
			$off_detail=stripQuotes(killChars($_POST["off_detail"]));
			$p_taluk=stripQuotes(killChars($_POST["p_taluk"]));
			$p_rev_village=stripQuotes(killChars($_POST["p_rev_village"]));
			
			$taluk=stripQuotes(killChars($_POST["taluk"]));
			$rev_village=stripQuotes(killChars($_POST["rev_village"]));
			$block=stripQuotes(killChars($_POST["block"]));
			$p_village=stripQuotes(killChars($_POST["p_village"]));
			$urban=stripQuotes(killChars($_POST["urban_body"]));
			$office=stripQuotes(killChars($_POST["office"]));
			$actiontype=stripQuotes(killChars($_POST["actiontype"]));
			$petition_type=stripQuotes(killChars($_POST["petition_type"]));
			$pet_community=stripQuotes(killChars($_POST["pet_community"]));
			$special_category=stripQuotes(killChars($_POST["special_category"]));
			
			$tlk = ($taluk == '')? "null":$taluk;
			$rv = ($rev_village == '')? "null":$rev_village;
			$blk = ($block == "")? "null":$block;
			$pv = ($p_village == '')? "null":$p_village;
			$ur = ($urban == '')? "null":$urban;
			$of = ($office == '')? "null":$office;
			
			$p_taluk = ($p_taluk == '')? "null":$p_taluk;
			$p_rev_village = ($p_rev_village == '')? "null":$p_rev_village;
			
			if ($dept != '') {
				$dep = "and dept_id=".$dept;
			} else  {
				$dep = "and dept_id=".$userProfile->getDept_id();
			}
				
			if(stripQuotes(killChars($_POST["p_from_pet_date"]))!="") 
				$p_from_pet_date=stripQuotes(killChars($_POST["p_from_pet_date"]));
				
		 	if(stripQuotes(killChars($_POST["p_to_pet_date"]))!="") 
				$p_to_pet_date=stripQuotes(killChars($_POST["p_to_pet_date"]));
			
			$fromdate=explode('/',$p_from_pet_date);
			$day=$fromdate[0];
			$mnth=$fromdate[1];
			$yr=$fromdate[2];
			$frm_dt=$yr.'-'.$mnth.'-'.$day;
			
			$todate=explode('/',$p_to_pet_date);
			$day=$todate[0];
			$mnth=$todate[1];
			$yr=$todate[2];
			$to_dt=$yr.'-'.$mnth.'-'.$day;
			
			$reporttypename = "";
			$pet_comm = "";
			
		if ($p_source != "") {
			$sql="select source_id,source_name from lkp_pet_source where source_id=".$p_source;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$sourcename=$row[1];
			$reporttypename = "Source: ".$sourcename;	
		}
			
		if ($gtype != "") {
			$sql="select griev_type_id,griev_type_name from lkp_griev_type where griev_type_id=".$gtype;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$gtypename=$row[1];
			if ($reporttypename == "") {
				$reporttypename = "Petition Main Category: ".$gtypename;
			} else {
				$reporttypename = $reporttypename.", Petition Main Category: ".$gtypename;
			}
		}
		
		if ($gstype != "") {
			$sql="select griev_subtype_id,griev_subtype_name from lkp_griev_subtype where griev_subtype_id=".$gstype;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$grievsubtypename=$row[1];
			if ($reporttypename == "") {
				$reporttypename = "Petition Sub-Category: ".$grievsubtypename;
			} else {
				$reporttypename = $reporttypename.", Petition Sub-Category: ".$grievsubtypename;
			}
		}
		
		if ($p_taluk != "null") {
			$sql="select taluk_id,taluk_name,taluk_tname from mst_p_taluk where taluk_id =".$p_taluk;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$talukname=$row[1];
			if ($pet_comm == "") {
				$pet_comm = "Petitioner Taluk: ".$talukname;
			} else {
				$pet_comm = $pet_comm.", Petitioner Taluk: ".$talukname;
			}
		}
		
		if ($p_rev_village != "null") {
			$sql="select rev_village_id,rev_village_name,rev_village_tname from mst_p_rev_village where  rev_village_id  =".$p_rev_village;
			$rs=$db->query($sql);
			$row=$rs->fetch(PDO::FETCH_BOTH);
			$rev_village_name=$row[1];
			if ($pet_comm == "") {
				$pet_comm = "Petitioner Revenue Village: ".$rev_village_name;
			} else {
				$pet_comm = $pet_comm.", Petitioner Revenue Village: ".$rev_village_name;
			}
		}
		
		$actiontype_cond='';
		if ($actiontype != '') {
			if ($actiontype == 'P') {
				$actiontype_cond = " where action_type_code not in ('A','R')";
			} else if ($actiontype == 'A'){
				$actiontype_cond = " where action_type_code='A'";
			} else if ($actiontype == 'R'){
				$actiontype_cond = " where action_type_code='R'";
			}
		}
		
?>
<form method="POST" name="p_registerdetail" id="p_registerdetail" style="background-color:#F4CBCB;">
<body>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
<table  class="rptTbl">
<thead>
        <tr>
        <th colspan="9" id="bak_btn">
        <a href="" onClick="self.close();">
        <img src="images/bak.jpg" /></a></th> 
        </tr>
    	 <tr><th colspan="9" class="main_heading"><?PHP echo $label_name[38];//Application Details?>  </th> </tr>
         <tr> 
			<th colspan="9" class="search_desc"><?PHP echo $label_name[34];//From Date?> : <?php echo $p_from_pet_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[35];//To Date?> : <?php echo $p_to_pet_date;?>
			<?php echo ($sub_category)? "; ".$label_name[36]. " : ".$cat_name : " "; // Cateogry?>	</th>
			</tr>
			
		<?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="9" class="main_heading"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>	
    
		<?php if ($pet_comm != "") {?>
            <tr> 
				<th colspan="9" class="main_heading"><?PHP echo $pet_comm; //Petitioner Communication?></th>
			</tr>
            <?php } ?>	
			
		<?php if ($off_detail != "") {?>
            <tr> 
				<th colspan="9" class="main_heading"><?PHP echo $off_detail; //Report type name?></th>
			</tr>
            <?php } ?>
	<tr>
    	<th><?PHP echo $label_name[39];?></th>
		<th><?PHP echo $label_name[3];//Petition No. ?>  </th>
		<th><?PHP echo $label_name[40];//Petition Date ?></th>
		<th><?PHP echo $label_name[41];//Signature of the Revenue Inspector ?> & <?PHP echo $label_name[42];//Date of Return from the Senior Draughtsman ?></th>
       <!--<th><?//PHP echo $label_name[42];//Date of Return from the Senior Draughtsman ?></th> -->
        <th><?PHP echo $label_name[43];//Accepted / Rejected ?></th>
        <th><?PHP echo $label_name[44];//Date of Action, File No. & Remarks ?></th>
		<th><?PHP echo $label_name[47];//Action Type, Date & Remarks ?></th>
		<th><?PHP echo $label_name[46];//Signature of the Tahsildar ?> <?php echo date('d-m-Y');?></th> 
		<th><?PHP echo $label_name[45];//Signature of the Tahsildar ?></th>    
			
	</tr>
  </thead>
  <tbody>
  <tr>
    	<td><b>1</b></td>
		<td class="columno" style="text-align:center"><b>2</b></td>
		<td class="columno" style="text-align:center"><b>3</b></td>
		<td class="columno" style="text-align:center"><b>4</b></td>
		<td class="columno" style="text-align:center"><b>5</b></td>	
        <td class="columno" style="text-align:center"><b>6</b></td>
		<td class="columno" style="text-align:center"><b>7</b></td>
		<td class="columno" style="text-align:center"><b>8</b></td> 
        <td class="columno" style="text-align:center"><b>9</b></td> 
		<!--<td class="columno" style="text-align:center"><b>10</b></td>-->
	</tr>
<?php
  	
	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.petition_date::date >= '".$frm_dt."'::date";
		//$codn_cnt.=  "  where a.petition_date::date >= '".$frm_dt."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$to_dt."'::date";
		//$codn_cnt.= " AND a.petition_date::date <= '".$to_dt."'::date";
	}
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		//$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
	if(!empty($gtype)){
		$codn.= " AND a.griev_type_id=".$gtype;
		//$codn_cnt.= " AND a.griev_type_id=".$gtype;
	}
	
	if(!empty($gstype)){
		$codn.= " AND a.griev_subtype_id=".$gstype;
		//$codn_cnt.= " AND a.griev_subtype_id=".$gstype;
	}
	
	if ($dept != '') {
		$codn .= " and a.dept_id =".$dept." ";
	}
	
	if ($rv!="null") {
		$codn .= " and a.griev_rev_village_id =".$rv."";
	} else if ($tlk!="null") {
		$codn .= " and a.griev_taluk_id =".$tlk."";
	} else if ($pv!="null") {
		$codn .= " and a.griev_lb_village_id =".$pv."";
	} else if ($blk!="null") {
		$codn .= " and a.griev_block_id =".$blk."";
	} else if ($ur!="null") {
		$codn .= " and a.griev_lb_urban_id =".$ur."";
	} else if ($of!="null") {
		$codn .= " and a.griev_division_id =".$of."";
	}
	
	
	if ($p_rev_village!="null") {
		$codn .= " and a.comm_rev_village_id =".$p_rev_village."";
	} else if ($p_taluk!="null") {
		$codn .= " and a.comm_taluk_id =".$p_taluk."";
	} 
	
	if ($petition_type!="") {
		$codn .= " and a.pet_type_id =".$petition_type."";
	} 
	
	if ($pet_community!="") {
		$codn .= " and a.pet_community_id =".$pet_community."";
	}
	
	if ($special_category!="") {
		$codn .= " and a.petitioner_category_id =".$special_category."";
	}
	
	//$petition_type=stripQuotes(killChars($_POST["petition_type"]));
	$codn.= ")";
	
	$i=1;
			 
		  
/*
    $query = "select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	 			grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	 			fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
				action_type_code,to_whom,cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk				 
				from vw_petition_details  
				where petition_id in (
				select a.petition_id from pet_master a 
				inner join  pet_action b on b.petition_id=a.petition_id
				inner join usr_dept b1 on b1.dept_id=a.dept_id
				inner join vw_usr_dept_users_v c on c.dept_user_id=a.pet_entby
				where c.dept_id=".$userProfile->getDept_id()." and c.off_level_dept_id=".$userProfile->getOff_level_dept_id()." 
				and c.off_loc_id=".$userProfile->getOff_loc_id().$codn."";
*/

	$query = "select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	 			grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	 			fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
				action_type_code,to_whom,pet_type_id,pet_type_name				 
				from fn_petition_details  
				(
				array(select a.petition_id from pet_master a 
				inner join  pet_action_first_last b on b.petition_id=a.petition_id
				inner join vw_usr_dept_users_v c on c.dept_user_id=b.f_action_entby
				where c.dept_id=".$userProfile->getDept_id()." and c.off_level_dept_id=".$userProfile->getOff_level_dept_id()." 
				and c.off_loc_id=".$userProfile->getOff_loc_id().$codn.")".$actiontype_cond." order by petition_id";

	//echo $query;
	
	$result = $db->query($query);
	$row_cnt = $result->rowCount();
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";

$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_PetitionProcessDetails.php') order by ordering";

$res = $db->query($qry);

while($rowArr = $res->fetch(PDO::FETCH_BOTH))
{
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}	
?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
<div class="contentDiv">
	<?PHP 
	if($row_cnt!=0)
	{
		foreach($rowarray as $row)
		{
			if ($row['action_type_code'] == 'A' || $row['action_type_code'] == 'R')
				$pending_periood = $row['action_type_name'];
			else 
				$pending_periood = $row['pend_period'];
	?>
<!--	<tr>
    	<td colspan="6" class="sub_heading"><?PHP// echo $label_name[1]; //Petition Details?></td>
    </tr>-->
    
    
	<tr>
    	<td style="width:3%;"><?PHP echo $i; ?></td>
		<td class="desc" style="width:15%;"><b><?php echo $row['petition_no'].'<br>Dt. '.$row['petition_date']; ?></b></td>
		<td class="desc" style="width:15%;"><?php echo $row['pet_address'];?></td>
        <td class="desc" style="width:10%;"><?php echo $row['source_name'].$row['edist_appln_no'];?> <?php echo '<br> '.$row['subsource_remarks'];?></td>
        <!--<td class="desc"><?php //echo $row[subsource_remarks];?></td>-->
        <td class="desc" style="width:18%;"><?php echo $row['grievance'];?></td>
	 	<td class="desc" style="width:10%;"><?php echo $row['griev_type_name']." & ".$row['griev_subtype_name']." & ".$row['gri_address'];?>
		<br><b><?php echo $row['pet_type_name'];?></b>
		</td>
		<td class="desc" style="width:15%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
<td class="desc" style="width:8%;"><?php echo $pending_periood;?></td>
        <td class="desc" style="width:6%;">&nbsp; </td>
   
    </tr>
    	    
    <?PHP $i++;
		}
	  }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2">No Records Found...</td>   </tr>
         </table>
         
        <?php } ?>	
	 
</tbody>
</table>
<input type="hidden" id="fromdate" name="fromdate" value="<?php echo $p_from_pet_date;?>"/> 
<input type="hidden" id="todate" name="todate" value="<?php echo $p_to_pet_date;?>"/> 
<input type="hidden" id="h_gtype" name="h_gtype" value="<?php echo $gtype;?>"/> 
<input type="hidden" id="h_gstype" name="h_gstype" value="<?php echo $gstype;?>"/> 
<input type="hidden" id="h_source" name="h_source" value="<?php echo $p_source;?>"/> 
<input type="hidden" id="h_taluk" name="h_taluk" value="<?php echo $p_taluk;?>"/> 
<input type="hidden" id="h_rvill" name="h_rvill" value="<?php echo $p_rev_village;?>"/> 
<table class="gridTbl" align="center">
		<tr>
        	<td colspan="9" class="btn"><input type="button" name="" id="dontprint1" value="<?PHP echo $label_name[20]; //Print ?>" class="button" onClick="return printReportToPdf()">
            </td>
        </tr>
		<tr id="bak_btn1"><td colspan="8" style="text-align: center;background-color: #BC7676;"><a href="" onclick="self.close();">
         <img src="images/bak.jpg" style="height: 25px;width: 45px;"/></a></td></tr>
</table>

</div>
</div>
</body
</form> 
<?php
	include("footer.php");
} // end of chk yes
else{
 	pg_close($db);
	header('Location: logout.php');
	exit; 
} 
?>