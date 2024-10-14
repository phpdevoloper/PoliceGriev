<?php
ob_start();
session_start();
include("dbr.php");
include("header_menu.php");
include("common_date_fun.php");
include("pm_common_js_css.php");
if(stripQuotes(killChars($_POST['hid']))=="") {
	include("menu_home.php");
}

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
	document.rpt_abstract.action="rptdist_details_of_order_disposed_petitions.php";
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

	
	
	if ($pet_source != "") {
		$pet_type_sql = "SELECT source_id, source_name, source_tname FROM lkp_pet_source where source_id =".$pet_source;
		$pet_type_rs=$db->query($pet_type_sql);
		$pet_type_row = $pet_type_rs->fetch(PDO::FETCH_BOTH);
		$source_name= $pet_type_row[1]; 
		
		if ($reporttypename == "") {
			$reporttypename = " - ".$source_name;
		} else {
			$reporttypename = $reporttypename." - ".$source_name;
		}		
	}		 
?>
<div class="contentMainDiv" style="width:55%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="14" ><a href="" onclick="self.close();">
            <img src="images/bak.jpg" /></a></th></tr>
            <tr> 
				<th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="14" class="main_heading"><?php echo $label_name[83].$reporttypename; ?> </th>
			</tr>
			<tr> 
				<th colspan="14" class="search_desc"><?php echo $label_name[84]; ?> &nbsp;&nbsp;&nbsp;<?php echo $label_name[1]; ?> : <?php echo $from_date; ?> 
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
            <?php echo $label_name[83]; ?>
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
	
	
	$sql="select bb.dept_desig_name, bb.dept_user_id,bb.off_loc_name, aa.total_uploads from
	(select l_action_entby, count(*) total_uploads 
	from pet_action_first_last a
	inner join pet_master b on b.petition_id=a.petition_id
	where l_action_entdt::date >= '".$frm_dt."'::date and  l_action_entdt::date <= '".$to_dt."'::date ".$cond." 
	and l_action_type_code in ('A','R')
	group by l_action_entby) aa
	inner join vw_usr_dept_users_v_sup bb on bb.dept_user_id=aa.l_action_entby
	where bb.off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()."
	order by bb.dept_desig_id, bb.dept_user_id";
	
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
			$total_uploads=$row['total_uploads'];
			
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
				
				<?php if($total_uploads!=0){?>
					<a href="" onclick="return detail_view('<?php echo $from_date; ?>','<?php echo $to_date; ?>','<?php echo $dept_desig_id; ?>','<?php echo $pet_source; ?>','<?PHP echo $dept_desig_name; ?>')"> <?php echo $total_uploads;?> </a> 
				<?php } else{?>
				<?php echo $total_uploads;?> </td> 
				<?php } ?>
				
				</td>
				</tr>               

			<?php  
			$i++;			 
			$tot_cnt = $tot_cnt + $total_uploads;
			
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
$src_id=stripQuotes(killChars($_POST["src_id"]));

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
				<th colspan="8" class="main_heading"><?php echo $label_name[81].$reporttypename; ?> </th>
			 </tr>
			
			 <tr> 
				<th colspan="8" class="main_heading"><?php echo $label_name[82]; ?>: <?php echo $uploaded_by; ?> </th>
			 </tr>
                <th colspan="8" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[20]." : "; //From Date?>  
				<?php echo $from_date; ?></th>
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

 	$insql="select a.petition_id
			from pet_action_doc a
			inner join pet_master b on b.petition_id=a.petition_id
			where action_doc_entdt::date >= '".$frm_dt."'::date and  action_doc_entdt::date <= '".$to_dt."'::date ".$cond." 
			and action_doc_entby=".$pet_entby;
	
		$sql=" -- pending: op. bal. 
		select petition_no, petition_id, petition_date, source_name,subsource_name, subsource_remarks, grievance, griev_type_id,griev_type_name, griev_subtype_name, pet_address, gri_address, griev_district_id, fwd_remarks, action_type_name, fwd_date, off_location_design, pend_period,pet_type_name 
		from fn_petition_details(array(".$insql.")) order by petition_id"; 

	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			if ($row['subsource_name'] != null || $row['subsource_name'] != "") {
				$source_details = ucfirst(strtolower($row['source_name'])).' & '.ucfirst(strtolower($row['subsource_name']));
			} else {
				$source_details = ucfirst(strtolower($row['source_name']));
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
