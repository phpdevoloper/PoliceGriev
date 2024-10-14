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

			
	$pfrompetdate=stripQuotes(killChars($_POST["p_from_pet_date"]));
	$ptopetdate=stripQuotes(killChars($_POST["p_to_pet_date"]));
	$pfrompetactdate=stripQuotes(killChars($_REQUEST["p_from_pet_act_date"]));
	$ptopetactdate=stripQuotes(killChars($_REQUEST["p_to_pet_act_date"]));
	$p_petition_no=stripQuotes(killChars($_POST["p_petition_no"]));
	$p_source=stripQuotes(killChars($_POST["p_source"]));
	$gtype=stripQuotes(killChars($_POST["gtype"]));
	$ptype=stripQuotes(killChars($_POST["ptype"]));
	$dept=stripQuotes(killChars($_POST["dept"]));
	$pet_community=stripQuotes(killChars($_POST["pet_community"]));
	$pet_action=stripQuotes(killChars($_POST["pet_action"]));
	$special_category=stripQuotes(killChars($_POST["special_category"]));
	
	$dt1=explode('/',$pfrompetdate);
	$day=$dt1[0];
	$mnth=$dt1[1];
	$yr=$dt1[2];
	$p_frompetdate=$yr.'-'.$mnth.'-'.$day;

	if (!preg_match($date_regex, $p_frompetdate)) { //$date_regex declared in common_date_fun.php
		$p_from_pet_date = '';
	} else {
		$p_from_pet_date = "$p_frompetdate";     
	}

	$dt2=explode('/',$ptopetdate);
	$day=$dt2[0];
	$mnth=$dt2[1];
	$yr=$dt2[2];
	$p_topetdate=$yr.'-'.$mnth.'-'.$day;

	if (!preg_match($date_regex, $p_topetdate)) { //$date_regex declared in common_date_fun.php
		$p_to_pet_date = '';
	} else {
		$p_to_pet_date = "$p_topetdate";     
	}	
	$dt3=explode('/',$pfrompetactdate);
	$day=$dt3[0];
	$mnth=$dt3[1];
	$yr=$dt3[2];
	$p_frompetactdate=$yr.'-'.$mnth.'-'.$day;

	if (!preg_match($date_regex, $p_frompetactdate)) { //$date_regex declared in common_date_fun.php
		$p_from_pet_act_date = '';
	} else {
		$p_from_pet_act_date = "$p_frompetactdate";     
	}

	$dt4=explode('/',$ptopetactdate);
	$day=$dt4[0];
	$mnth=$dt4[1];
	$yr=$dt4[2];
	$p_topetactdate=$yr.'-'.$mnth.'-'.$day;

	if (!preg_match($date_regex, $p_topetactdate)) { //$date_regex declared in common_date_fun.php
		$p_to_pet_act_date = '';
	} else {
		$p_to_pet_act_date = "$p_topetactdate";     
	}
	
	$codn=" where b.action_entby=".$_SESSION['USER_ID_PK'];
	$codn_cnt=" where a.action_entby=".$_SESSION['USER_ID_PK'];
	
	if(!empty($p_from_pet_date)){
		$codn.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
		$codn_cnt.=  " AND a.petition_date::date >= '".$p_from_pet_date."'::date";
	}
	if(!empty($p_to_pet_date)){
		$codn.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
		$codn_cnt.= " AND a.petition_date::date <= '".$p_to_pet_date."'::date";
	}
	if(!empty($p_from_pet_act_date)){
		$codn.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date >= '".$p_from_pet_act_date."'::date";
	}
	if(!empty($p_to_pet_act_date)){
		$codn.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
		$codn_cnt.= " AND b.action_entdt::date <= '".$p_to_pet_act_date."'::date";
	}
	if(!empty($p_source)){
		$codn.= " AND a.source_id=".$p_source;
		$codn_cnt.= " AND a.source_id=".$p_source;
	}
	
	if(!empty($dept)){
		$codn.= " AND a.dept_id=".$dept;
		$codn_cnt.= " AND a.dept_id=".$dept;
	}
	
	if(!empty($gtype)){
		$codn.= " AND a.griev_type_id=".$gtype;
		$codn_cnt.= " AND a.griev_type_id=".$gtype;
	}
	if(!empty($ptype)){
		$codn.= " AND a.pet_type_id=".$ptype;
		$codn_cnt.= " AND a.pet_type_id=".$ptype;
	}
	
	if(!empty($pet_community)){
		$codn.= " AND a.pet_community_id=".$pet_community;
		$codn_cnt.= " AND a.pet_community_id=".$pet_community;
	}
	
	if(!empty($special_category)){
		$codn.= " AND a.petitioner_category_id=".$special_category;
		$codn_cnt.= " AND a.petitioner_category_id=".$special_category;
	}
	if(!empty($pet_action)){
		$pet_action_cond= " where action_type_code='".$pet_action."'";
		$codn_cnt.= " AND action_type_code='".$pet_action."'";
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
    	 <tr><th colspan="9" class="main_heading"><?PHP echo 'Report for Petition Processed By Us';//Application Details?>  </th> </tr>
         <tr> 
			<th colspan="9" class="search_desc"><?PHP echo $label_name[34];//From Date?> : <?php echo $pfrompetdate; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[35];//To Date?> : <?php echo $ptopetdate;?>
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
  	
	$query="select petition_no, pet_action_id,petition_id, petition_date, source_name,subsource_name, subsource_remarks, 
	grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, 
	fwd_remarks, griev_subtype_id,action_type_name,fwd_date, off_location_design, pend_period,action_entby,action_entdt,
	action_type_code,to_whom,pet_type_name
	from fn_petition_details(array(select a.petition_id from pet_master a 
	inner join  pet_action b on b.petition_id=a.petition_id".$codn."))".$pet_action_cond."";

	//echo $query;
	
	$result = $db->query($query);
	$row_cnt = $result->rowCount();
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";

$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='rpt_anx1_details.php') order by ordering";

$res = $db->query($qry);

while($rowArr = $res->fetch(PDO::FETCH_BOTH))
{
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
$i = 1;	
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