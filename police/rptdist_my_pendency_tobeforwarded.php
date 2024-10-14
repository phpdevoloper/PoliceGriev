<?php
ob_start();
session_start();
include("db.php");
include("header_menu.php");
include("header_menu_report.php");
//include("menu_home.php");
include("common_date_fun.php");
include("pm_common_js_css.php");
/*if(stripQuotes(killChars($_POST['hid']))=="") {
	include("menu_home.php");
}*/

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
if(stripQuotes(killChars($_POST['hid_yes']))!="")
	$check=stripQuotes(killChars($_POST['hid_yes']));
else
	$check=$_SESSION["check"];

if($check=='yes')
{
$pagetitle="Department wise Report";
?>
  
<script type="text/javascript">
function detail_view(status,src_id,src_name)
{ 
	document.getElementById("status").value=status;
	document.getElementById("src_id").value=src_id;
	document.getElementById("src_name").value=src_name;
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_my_pendency_tobeforwarded.php";
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

	$qry = "select label_name,label_tname from apps_labels where menu_item_id=51 order by ordering";
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
		
		$office_type=stripQuotes(killChars($_POST["office_type"]));	
		
		
/*$_SESSION["gsrc"]  = $src_id; 
$_SESSION["gsubsrc"]  = $sub_src_id; 
$_SESSION["gtype"]  = $gtypeid; 
$_SESSION["gsubtype"]  = $gsubtypeid; */	

		 
?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="6" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
		<!--	<a href="" onclick="return maintain_val('<?php //echo $rep_src ?>');"><img src="images/bak.jpg" /></a> -->
			</th></tr>
            <tr> 
				<th colspan="6" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="6" class="main_heading"><?PHP echo $label_name[42]; //Pending with myself at present '; //Department wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="6" class="main_heading"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="6" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>

			
			
			<tr>
                <tr>
                <th rowspan="2" ><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="2" ><?PHP echo $label_name[41]; //Source?></th>
                <th colspan="4" style="width: 70%;"><?PHP echo $label_name[5]; //Number Of Petitions?></th>


				</tr>
				<tr>
                <th rowspan="1"><?PHP echo $label_name[11]; //Closing Balance?></th>				
                <th rowspan="1"> <?PHP echo $label_name[12]; //Pending Less than 1 month?></th>
        	 	<th rowspan="1"> <?PHP echo $label_name[13]; //Pending for 2 months?></th>
        	 	<th rowspan="1"> <?PHP echo $label_name[14]; //Pending for more than 2 months?></th>

			</tr>
            </thead>
            <tbody>            
			<?php
$fwd_offr_cond = "";
	if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2){
		$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,20)=20) ";
	}

	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==2){
		$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=30 and ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==10){
		$fwd_offr_cond=" AND a.griev_division_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=30 and ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
		$fwd_offr_cond=" and (coalesce(a.fwd_office_level_id,10) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=10 and ".$userProfile->getOff_level_id()."=any(off_level_id)) ) and dept_id=".$userProfile->getDept_id()."";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !		$userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
		$fwd_offr_cond=" and ((coalesce(a.fwd_office_level_id,10) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=10 and ".$userProfile->getOff_level_id()."=any(off_level_id)) ) and dept_id=".$userProfile->getDept_id().") ";
	}
	else{
		$fwd_offr_cond=" and false ";
	}
		
			
			$i=1;  
	

		$sql = "SELECT a.source_id,b.source_name || ' - To be forwarded' as source_name,count(*) as pending,
		sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
		sum(case when ((current_date - petition_date::date) > 30 
		and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
		sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt
		FROM vw_pet_master a
		left join lkp_pet_source b on b.source_id=a.source_id
		WHERE a.source_id < 0 and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond." 
		group by a.source_id,b.source_name";

//echo $sql;
 
	    $result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			if($_SESSION['lang']=='E'){
				$source_name=$row[source_name];
			}else{
				$source_name=$row[source_name];
			}
	
			 
			$source_id=$row[source_id];
			$source_name=$row[source_name];
			

			$closing_pending=$row[pending];
			$cl_pend_leq30d_cnt=$row[cl_pend_leq30d_cnt];
			$cl_pend_gt30leq60d_cnt=$row[cl_pend_gt30leq60d_cnt];
			$cl_pend_gt60d_cnt=$row[cl_pend_gt60d_cnt];

			?>
			<tr>   
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $source_name; ?></td>
 		
						
						
		<?php if($closing_pending!=0) {?>
				<td><a href="" onclick="return detail_view('<?php echo "pcb"; ?>','<?php echo $source_id; ?>','<?php echo $source_name; ?>')"><?php echo $closing_pending;?></a></td>
		<?php } else {?>
				<td> <?php echo $closing_pending;?> </td> 
		<?php } ?>
            
				

		<?php if($cl_pend_leq30d_cnt!=0) {?>
				<td><a href="" onclick="return detail_view('<?php echo "pm2"; ?>','<?php echo $source_id; ?>','<?php echo $source_name; ?>')"><?php echo $cl_pend_leq30d_cnt;?></a></td>
		<?php } 
			else {?>
				<td><?php echo $cl_pend_leq30d_cnt;?> </td> 
		<?php } ?>
                        
		<?php if($cl_pend_gt30leq60d_cnt!=0) { ?>
				<td><a href="" onclick="return detail_view('<?php echo "p2m"; ?>','<?php echo $source_id; ?>','<?php echo $source_name; ?>')"><?php echo $cl_pend_gt30leq60d_cnt;?></a></td>
		<?php } 
			 else {?>
				 <td> <?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>
            
		<?php if($cl_pend_gt60d_cnt!=0) {?>
				<td><a href="" onclick="return detail_view('<?php echo "pm1"; ?>','<?php echo $source_id; ?>','<?php echo $source_name; ?>')"> <?php echo $cl_pend_gt60d_cnt;?></a></td>
		<?php } 
			else {?>
				<td> <?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>
			</tr>

			<?php  
			$i++;			 

			$tot_closing_pending=$tot_closing_pending+$closing_pending;
			$tot_cl_pend_more_cnt=$tot_cl_pend_more_cnt+$cl_pend_leq30d_cnt;
			$tot_cl_pend_2_cnt=$tot_cl_pend_2_cnt+$cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1_cnt=$tot_cl_pend_1_cnt+$cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo $label_name[16]; // Total?></td>
                <td><?php echo $tot_closing_pending;?></td>                
                <td><?php echo $tot_cl_pend_more_cnt;?></td>
           		<td><?php echo $tot_cl_pend_2_cnt;?></td>
            	<td><?php echo $tot_cl_pend_1_cnt;?></td>
            	
			</tr>
			<tr>
            <td colspan="6" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
            <input type="hidden" name="hid" id="hid" />
            <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
			<input type="hidden" name="src_id" id="src_id" />
			<input type="hidden" name="src_name" id="src_name" />
			
            <input type="hidden" name="frdate" id="frdate"  />
   		    <input type="hidden" name="todate" id="todate" />
    		<input type="hidden" name="dept" id="dept" />
            <input type="hidden" name="dept_name" id="dept_name" />
			<input type="hidden" name="off_loc_name" id="off_loc_name" />
     		<input type="hidden" name="status" id="status" /> 
			<input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/> 
            <input type="hidden" name="pet_own_dept_name" id="pet_own_dept_name" value='<?php echo $pet_own_dept_name ?>'/> 
            
    		<input type="hidden" name="sub_src_id" id="sub_src_id" />
            <input type="hidden" name="gtypeid" id="gtypeid" />
            <input type="hidden" name="gsubtypeid" id="gsubtypeid" />
            <input type="hidden" name="grie_dept_id" id="grie_dept_id" />
            <input type="hidden" name="off_cond_para" id="off_cond_para" />
			<input type="hidden" name="dept_condition" id="dept_condition" value="<?php echo $grev_dept_condition; ?>"/>       		
			<input type="hidden" name="office_type" id="office_type" value="<?php echo $office_type; ?>"/>
			<input type="hidden" name="off_level_dept_id" id="off_level_dept_id" value="<?php echo $off_level_dept_id; ?>"/>
			<input type="hidden" name="off_loc_id" id="off_loc_id" value="<?php echo $off_loc_id; ?>"/>
			<input type="hidden" name="p_dept_id" id="p_dept_id" value="<?php echo $dept; ?>"/>
			<input type="hidden" name="off_dept_id" id="off_dept_id" />
			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/> 
		<input type="hidden" name="reporttypename" id="reporttypename" value="<?php echo $reporttypename; ?>"/> 
		<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 

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
$src_name = stripQuotes(killChars($_POST["src_name"]));	  
$sub_src_id = stripQuotes(killChars($_POST["sub_src_id"]));	
$gtypeid = stripQuotes(killChars($_POST["gtypeid"]));	  
$gsubtypeid = stripQuotes(killChars($_POST["gsubtypeid"]));
$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
$off_cond_para=stripQuotes(killChars($_POST["off_cond_para"]));
$dept_condition=$_POST["dept_condition"];
$off_dept_id=$_POST["off_dept_id"];
$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$reporttypename=stripQuotes(killChars($_POST["reporttypename"]));
		
$_SESSION["check"]="yes"; 

//if($status=='pob')
//	$cnt_type=" ".$label_name[6];//" Opening Balance (Pending)";
//else 
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
//else if($status=='p1m')
//	$cnt_type=" Petitions pending for < 1 month";
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
				<th colspan="8" class="main_heading"><?PHP echo $label_name[42];?>  - <?php echo " ".$cnt_type; ?></th>
                </tr>
                <!--tr>
                <th colspan="8" class="main_heading"><?php //echo (($_SESSION["lang"]=='E') ? "Processing Dept.: ":"செயலாக்க  துறை :  ").$dept_name;?> <?PHP //echo $label_name[31];?>  </th>
                </tr-->
                

                <tr>
                <th colspan="8" class="main_heading"><?php echo $label_name[41].' : '.$src_name;?></th>
                </tr>

                
				<?php if (($pet_own_heading != "") && ($rep_src == "")) {?>
					<tr> 
					<th colspan="8" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
     
				<tr>
				<th><?PHP echo $label_name[21]; //S.No.?></th>
				<th><?PHP echo $label_name[22]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[23]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[24]; //Source & Sub Source?> & <?PHP echo $label_name[25]; //Source Remarks?></th>
				<th><?PHP echo $label_name[26]; //Grievance?></th>
				<th><?PHP echo $label_name[27]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[29]; //Pending Period?></th>
				</tr>
			</thead>
		<tbody>
<?php 

$fwd_offr_cond = "";
	if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2){
		$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,20)=20) ";
	}

	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==2){
		$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=30 and ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==10){
		$fwd_offr_cond=" AND a.griev_division_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=30 and ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
		$fwd_offr_cond=" and (coalesce(a.fwd_office_level_id,10) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=10 and ".$userProfile->getOff_level_id()."=any(off_level_id)) ) and dept_id=".$userProfile->getDept_id()."";
	}
	else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !		$userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
		$fwd_offr_cond=" and ((coalesce(a.fwd_office_level_id,10) in (select fwd_office_level_id from lkp_fwd_office_level where fwd_office_level_id=10 and ".$userProfile->getOff_level_id()."=any(off_level_id)) ) and dept_id=".$userProfile->getDept_id().") ";
	}
	else{
		$fwd_offr_cond=" and false ";
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
 
  	if(!empty($from_date) && !empty($to_date) ){
		 $cond1.="a.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="b.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="a.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="b.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="a.petition_date::date <= '".$to_dt."'::date";
         $cond6.="b.action_entdt::date <= '".$to_dt."'::date";  	  
	}

	$aspect_cond7="";
	if(!empty($src_id)) {
		$aspect_cond7 = " and (a.source_id=".$src_id.") ";
	}

//	echo 	$grev_dept_condition." - ".$src_condition." - ".$grev_condition." - ".$off_condition;

	$particular_office_cond = "";
	$all_office_cond = "";
	
	$office_type=stripQuotes(killChars($_POST["office_type"]));
	$p_dept_id=stripQuotes(killChars($_POST["p_dept_id"]));
	$off_level_dept_id=stripQuotes(killChars($_POST["off_level_dept_id"]));
	$off_loc_id=stripQuotes(killChars($_POST["off_loc_id"]));
	if ($office_type == "P") {
		$particular_office_cond = "inner join vw_usr_dept_users_v a2 on a2.dept_user_id=a.pet_entby 
								   and a2.dept_id= ".$p_dept_id." 
								   and a2.off_level_id=".$off_level_dept_id."
								   and a2.off_loc_id=".$off_loc_id."";
	} //else {
		$all_office_cond = " and a.source_id= ".$src_id." ";
	//}
		
/*
		SELECT a.source_id,b.source_name || ' - To be forwarded' as source_name,count(*) as pending,
		sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
		sum(case when ((current_date - petition_date::date) > 30 
		and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
		sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt
		FROM vw_pet_master a
		left join lkp_pet_source b on b.source_id=a.source_id
		WHERE a.source_id < 0 and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond." 
		group by a.source_id,b.source_name*/
		
	if($status=='pcb'){
		$sub_sql="SELECT a.petition_id
		FROM vw_pet_master a
		WHERE a.source_id=".$src_id." and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond."";

		
	 }
	else if ($status=='pm2') 
	{
		$sub_sql="SELECT a.petition_id
		FROM vw_pet_master a
		WHERE a.source_id=".$src_id." and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond." and (case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end)=1";

	}
	else if ($status == 'p2m') {
		
		$sub_sql="SELECT a.petition_id
		FROM vw_pet_master a
		WHERE a.source_id=".$src_id." and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond." and (case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60)  then 1 else 0 end)=1";		
		
	} 
	else if ($status == 'pm1') {
		
		$sub_sql="SELECT a.petition_id
		FROM vw_pet_master a
		WHERE a.source_id=".$src_id." and NOT EXISTS (
		SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id
		)  ".$fwd_offr_cond." and (case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end)=1";
			
	} 
	
	$sql="SELECT petition_id, petition_no, to_char(pet_entdt, 'dd/mm/yyyy hh12:mi:ss PM')::character varying AS petition_date, petitioner_initial, petitioner_name, father_husband_name, 
		source_name, source_tname, subsource_name,griev_type_name, griev_subtype_name, grievance, 
		survey_no, sub_div_no, comm_doorno, comm_aptmt_block, comm_street, comm_area,  comm_district_name, 
		comm_taluk_name,comm_rev_village_name, comm_pincode, griev_district_name,griev_taluk_name,  
		griev_rev_village_name, griev_block_name, griev_lb_village_name, griev_lb_urban_name,  
		griev_lb_urban_type_name,griev_division_name, dept_name, pet_type_name,
		age(now()::date::timestamp with time zone, petition_date::timestamp with time zone)::character varying as pending_period FROM vw_pet_master
		where petition_id in (".$sub_sql.") order by petition_id";
		
//echo $sql;
		//$sql .= " order by petition_id";
	    $result = $db->query($sql);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		 
		foreach($rowarray as $row)
		{
			if ($row[subsource_name] != null || $row[subsource_name] != "") {
				$source_details = $row[source_name].' & '.$row[subsource_name];
			} else {
				$source_details = $row[source_name];
			}
			$pet_address = $row[petitioner_name].', '.$row[father_husband_name].', '.$row[comm_rev_village_name].', '.$row[comm_taluk_name].', '.$row[comm_district_name];
			
			if ($row[griev_taluk_name]!= '' && $row[griev_rev_village_name]!='') {
				$griev_address = $row[griev_rev_village_name].','.$row[griev_taluk_name].','.$row[griev_district_name];
			} else if ($row[griev_block_name]!= '' && $row[griev_lb_village_name]!='') {
				$griev_address = $row[griev_lb_village_name].','.$row[griev_block_name].','.$row[griev_district_name];
			} else if ($row[griev_lb_urban_name]!= '') {
				$griev_address = $row[griev_lb_urban_name].','.$row[griev_district_name];
			} else if ($row[griev_division_name]!= '') {
				$griev_address = $row[griev_division_name].','.$row[griev_district_name];
			}
	
			?>
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:14%;"> <a href=""  onclick="return petition_status('<?php echo $row[petition_id]; ?>')">
			<?PHP  echo $row[petition_no]."<br>Dt.&nbsp;".$row[petition_date]; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $pet_address //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo ($row[subsource_remarks] != '')? ' & '.$row[subsource_remarks]:'';?></td>
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:19%;white-space: normal;"> <?PHP echo $row[grievance] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row[griev_type_name].",".$row[griev_subtype_name]."&nbsp;"."<br>Address: ".$griev_address."<br>".$row[pet_type_name]; ?></td>
            
           
<!--			<td class="desc"> <?PHP //if($row['action_type_name']!="") { echo $row[action_type_name].			",".$row[fwd_date]."&".$row[fwd_remarks].":"."&nbsp;".ucfirst(strtolower($row[off_location_design])); }?></td>-->
            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row[pending_period])); ?></td>
			</tr>
<?php $i++; } ?> 
			<tr>
			<td colspan="8" class="buttonTD">
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
