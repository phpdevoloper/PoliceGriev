<?php
$_SESSION['logged_in'] = true; //set you've logged in
$_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
$_SESSION['expire_time'] = 60; //expire time in seconds: three hours (you must change this)
include("db.php");
include("header_menu.php");

include("menu_home.php");
include("pm_common_js_css.php");
//include("UserProfile.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']);

$nonce = random_bytes(32);
$_SESSION['non']=base64_encode($nonce);
header("Content-Security-Policy: object-src 'self'; script-src 'self' 'nonce-".$_SESSION['non']."'", TRUE);
$non=$_SESSION['non'];
if($_SESSION['non']==''){$non=base64_encode($nonce);}
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='$non'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='$non'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='$non'> document.location = 'logout.php'; </script>";
	exit;
	}
}

$pagetitle="Department wise Report";
//echo $pagetitle;
?>
<style>
.pnd {
color: #FFF;
text-decoration-line: underline;
}
.pnd1 {
color: #FFF;
text-decoration-line: underline;
}

a.pnd:hover {
color: red;
}

a.pnd1:hover {
color: yellow;
}
</style>	  
<script type="text/javascript">
function detail_view(status,src_id,src_name)
{ 
	document.getElementById("status").value=status;
	document.getElementById("src_id").value=src_id;
	document.getElementById("src_name").value=src_name;
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_my_pendency_status.php";
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
//echo "=========================".$userProfile->getDivision_id()."*****";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=51 order by ordering";
$res = $db->query($qry);
while($rowArr = $res->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
//echo "====================";
?>
 
<?php 
//if(stripQuotes(killChars($_POST['hid']))=="") { 
?>
<div>
	<div align="center" style="margin-top:25px;">
	
		<?php if ($_SESSION['lang'] == 'E') { ?>
    	<span style="font-weight:bold;font-size:23px;color: rgb(0,0,225);">Welcome to Senior Police Officers Petition System of Tamil Nadu Police</span>
		<?php } else { ?>
		<span style="font-weight:bold;font-size:19px;color: rgb(0,0,225);"> தமிழக அரசின் மனுப் பரிசீலனை முகப்பு (ம.ப.மு.)  தங்களை அன்புடன் வரவேற்கிறது</span>
		<?php } ?>
    </div>
</div>

<div style="margin-top:40px;">
<table width="70%" align="center">
<tr height="40px">
<td bgcolor="#CD5C5C" style="font-size:25px;border-radius: 5px;" width="20%"><font color="#fff">
<center>MY&nbsp;&nbsp;&nbsp;PENDENCY<br>SUMMARY:</center></font></td>

<td bgcolor="#0000ff" style="font-size:25px;border-radius: 5px;"><font color="#fff">
<center>TOTAL PENDING</center><center><label id="tot_pend"></label></center></font></td>
<td bgcolor="#006400" style="font-size:25px;border-radius: 5px;"><font color="#fff">
<center> UPTO 30 DAYS</center><center><label id="pend_l30"></label></center></font></td>
<td bgcolor="#A52A2A" style="font-size:25px;border-radius: 5px;"><font color="#fff">
<center> 30 TO 60 DAYS</center><center><label id="pend_30_60"></label></center></font></td>
<td bgcolor="#D2691E" style="font-size:25px;border-radius: 5px;"><font color="#fff">
<center> ABOVE 60 DAYS</center><center><label id="pend_above_60"></label></center></font></td>
</tr>
</table>
</div>
<?php if (($userProfile->getPet_disposal() && $userProfile->getPet_act_ret())|| $userProfile->getPet_act_ret() || $userProfile->getDesig_roleid() == 5) {?>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#FFFFFF;">
<div class="contentMainDiv" style="width:80%;margin:auto;margin-top:30px;">
	<div class="contentDiv">	
		<table class="pndTble">
			<thead>

         	<tr>
                <tr>
                <th rowspan="3" ><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="3" ><?PHP echo $label_name[41]; //Source?></th>
                <th colspan="4" style="width: 70%;"><?PHP echo $label_name[5]; //Number Of Petitions?></th>


				</tr>
				<tr>
                <th rowspan="2"><?PHP echo $label_name[11]; //Closing Balance?></th>				
                <th rowspan="2"> <?PHP echo $label_name[12]; //Pending Less than 1 month?></th>
        	 	<th rowspan="2"> <?PHP echo $label_name[13]; //Pending for 2 months?></th>
        	 	<th rowspan="2"> <?PHP echo $label_name[14]; //Pending for more than 2 months?></th>

			</tr>
            </thead>
            <tbody>            
<?php 
	
	if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$sql="select a.dept_user_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$dept_user_id =  $row['dept_user_id'];
		}
		$cond = "(a1.l_to_whom = ".$dept_user_id." or (a1.l_action_entby=".$dept_user_id." and a1.l_action_type_code='T'))";
		$filter_cond = " and pet_type_id!=4 ";
		
	} else {
		$cond = "(a1.l_to_whom = ".$userProfile->getDept_user_id()." or (a1.l_action_entby=".$userProfile->getDept_user_id()." and a1.l_action_type_code='T'))";
		$filter_cond="";
	}
	
	$disposal_query = "";
	
	if ($userProfile->getPet_disposal()) {
		$disposal_query = "union
		select source_id,count(petition_id) as pending,
		sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
		sum(case when ((current_date - petition_date::date) > 30 
		and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
		sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt,'O' as flag from ( 
		select source_id,fn_off_loc_hierarchy(b.dept_id, b.dept_off_level_pattern_id,b.off_level_id,
		COALESCE(a.zone_id, a.range_id ,a.griev_district_id, a.griev_division_id, a.griev_subdivision_id, a.griev_circle_id,29)) 
		as off_loc_hier,a.petition_id,a.petition_date
		FROM vw_pet_master a
		left join usr_dept_off_level b on b.off_level_dept_id=a.off_level_dept_id 
		left join usr_dept_off_level c on c.off_level_dept_id=a.fwd_office_level_id 
		where source_id<0 and NOT EXISTS ( SELECT * FROM pet_action_first_last b WHERE b.petition_id = a.petition_id )  
		and a.fwd_office_level_id= ".$userProfile->getOff_level_dept_id()."  and c.off_level_id=".$userProfile->getOff_level_id()."  ORDER BY a.petition_id) bbb where off_loc_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." group by bbb.source_id";		
	}
	
	$i=1;  

	
	$sql="
	WITH p_off_pet as (
	WITH off_pet AS 
	(
	select a1.petition_id, a.petition_date, a.source_id, a1.l_action_type_code, a1.l_action_entby, a1.l_to_whom 
	from pet_action_first_last a1 
	inner join pet_master a on a.petition_id=a1.petition_id 
	where ".$cond.$filter_cond.") 

	select a.source_id,count(*) as pending,
	sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
	sum(case when ((current_date - petition_date::date) > 30 
	and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
	sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt,'B' as flag 
	from off_pet a
	group by a.source_id ".$disposal_query.")
	SELECT a.source_id, b.source_name, a.pending,a.cl_pend_leq30d_cnt,
	a.cl_pend_gt30leq60d_cnt,a.cl_pend_gt60d_cnt,a.flag from p_off_pet a
	INNER JOIN lkp_pet_source b on b.source_id=a.source_id;
	";

//echo $sql;
//exit;
 
	    $result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			if($_SESSION['lang']=='E'){
				$source_name=$row['source_name'];
			}else{
				$source_name=$row['source_name'];
			}
	
			 
			$source_id=$row['source_id'];
			$source_name=$row['source_name'];
			

			$closing_pending=$row['pending'];
			$cl_pend_leq30d_cnt=$row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt=$row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt=$row['cl_pend_gt60d_cnt'];
			$flag=$row['flag'];

			?>
			
			<?php if ($flag == 'O') { ?>
			<tr style="background-color:#E10F0FBF;font-weight: bold;color:#FFFFFF;">
			<td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $source_name. ' - To be Forwarded'; ?></td>
				<td> 
				<?php if ($closing_pending > 0) {?>
				<a class="pnd1" href="t0_ProcessPetitions.php"><?php echo $closing_pending;?> </a>
				<?php } else { ?>
				<?php echo $closing_pending;?> 
				<?php } ?>
				</td> 
				<td>
				<?php if ($cl_pend_leq30d_cnt > 0) {?>
				<a class="pnd1" href="t0_ProcessPetitions.php"><?php echo $cl_pend_leq30d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_leq30d_cnt;?> 
				<?php } ?>
				</td> 		
				<td> 
				<?php if ($cl_pend_gt30leq60d_cnt > 0) {?>
				<a class="pnd1" href="t0_ProcessPetitions.php"><?php echo $cl_pend_gt30leq60d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_gt30leq60d_cnt;?> 
				<?php } ?>
				</td>
                <td> 
				<?php if ($cl_pend_gt60d_cnt > 0) {?>
				<a class="pnd1" href="t0_ProcessPetitions.php"><?php echo $cl_pend_gt60d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_gt60d_cnt;?> 
				<?php } ?>
				
				</td>
			</tr>
			<?php } else { ?>
			<tr>
			<td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $source_name; ?></td>
				<td> 
				<?php if ($closing_pending > 0) {?>
				<a href="t0_ProcessPetitions.php"><?php echo $closing_pending;?> </a>
				<?php } else { ?>
				<?php echo $closing_pending;?> 
				<?php } ?>
				</td> 
				<td>
				<?php if ($cl_pend_leq30d_cnt > 0) {?>
				<a href="t0_ProcessPetitions.php"><?php echo $cl_pend_leq30d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_leq30d_cnt;?> 
				<?php } ?>
				</td> 		
				<td> 
				<?php if ($cl_pend_gt30leq60d_cnt > 0) {?>
				<a href="t0_ProcessPetitions.php"><?php echo $cl_pend_gt30leq60d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_gt30leq60d_cnt;?> 
				<?php } ?>
				</td>
                <td> 
				<?php if ($cl_pend_gt60d_cnt > 0) {?>
				<a href="t0_ProcessPetitions.php"><?php echo $cl_pend_gt60d_cnt;?> </a>
				<?php } else { ?>
				<?php echo $cl_pend_gt60d_cnt;?> 
				<?php } ?>
				
				</td>
			</tr>
			<?php } ?>
			 
			
					
			<?php  
			$i++;			 

			$tot_closing_pending=$tot_closing_pending+$closing_pending;
			$tot_cl_pend_more_cnt=$tot_cl_pend_more_cnt+$cl_pend_leq30d_cnt;
			$tot_cl_pend_2_cnt=$tot_cl_pend_2_cnt+$cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1_cnt=$tot_cl_pend_1_cnt+$cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2" style="border: 1px solid white;"><?PHP echo $label_name[16]; // Total?></td>
                <td style="border: 1px solid white;">
				<?php if ($tot_closing_pending > 0) {?>
				<a class="pnd" href="t0_ProcessPetitions.php"><?php echo $tot_closing_pending;?> </a>
				<?php } else { ?>
				<?php echo $tot_closing_pending;?> 
				<?php } ?>
				</td>                
                <td style="border: 1px solid white;">
				<?php if ($tot_cl_pend_more_cnt > 0) {?>
				<a class="pnd" href="t0_ProcessPetitions.php"><?php echo $tot_cl_pend_more_cnt;?> </a>
				<?php } else { ?>
				<?php echo $tot_cl_pend_more_cnt;?> 
				<?php } ?>
				</td>
           		<td style="border: 1px solid white;">
				<?php if ($tot_cl_pend_2_cnt > 0) {?>
				<a class="pnd" href="t0_ProcessPetitions.php"><?php echo $tot_cl_pend_2_cnt;?> </a>
				<?php } else { ?>
				<?php echo $tot_cl_pend_2_cnt;?> 
				<?php } ?>
				</td>
            	<td style="border: 1px solid white;">
				<?php if ($tot_cl_pend_1_cnt > 0) {?>
				<a class="pnd" href="t0_ProcessPetitions.php"><?php echo $tot_cl_pend_1_cnt;?> </a>
				<?php } else { ?>
				<?php echo $tot_cl_pend_1_cnt.'';?> 
				<?php } ?>
				</td>
				
			</tr>
	<script>
	var tot_pnd = <?php echo $tot_closing_pending;?>;
	var uptp30 = <?php echo $tot_cl_pend_more_cnt;?>;
	var cl_pnd2 = <?php echo $tot_cl_pend_2_cnt;?>;
	var gt60 = <?php echo $tot_cl_pend_1_cnt;?>;
	
	if (tot_pnd > 0)
	document.getElementById('tot_pend').innerHTML = '<a href="t0_ProcessPetitions.php" class="pnd">'+tot_pnd+'</a>';
	else
	document.getElementById('tot_pend').innerHTML = tot_pnd;

	if (tot_pnd > 0)
	document.getElementById('pend_l30').innerHTML = '<a href="t0_ProcessPetitions.php" class="pnd">'+uptp30+'</a>';
	else
	document.getElementById('pend_l30').innerHTML = uptp30;
	
	if (cl_pnd2 > 0)
	document.getElementById('pend_30_60').innerHTML = '<a href="t0_ProcessPetitions.php" class="pnd">'+cl_pnd2+'</a>';
	else
	document.getElementById('pend_30_60').innerHTML = cl_pnd2;

	if (gt60 > 0)
	document.getElementById('pend_above_60').innerHTML = '<a href="t0_ProcessPetitions.php" class="pnd">'+gt60+'</a>';
	else
	document.getElementById('pend_above_60').innerHTML = gt60;
	</script>
         </table>
         
        <?php } else {
			$tot_closing_pending = 0;
			$tot_cl_pend_more_cnt = 0;
			$tot_cl_pend_2_cnt = 0;
			$tot_cl_pend_1_cnt = 0;
		?>
		<tr> 
			<td colspan="6" style="font-size:30px; text-align:center">No Pending Petitions</td>
			<script>
			document.getElementById('tot_pend').innerHTML = '<?php echo $tot_closing_pending;?>';
			document.getElementById('pend_l30').innerHTML = '<?php echo $tot_cl_pend_more_cnt;?>';
			document.getElementById('pend_30_60').innerHTML = '<?php echo $tot_cl_pend_2_cnt;?>';
			document.getElementById('pend_above_60').innerHTML = '<?php echo $tot_cl_pend_1_cnt;?>';
			</script>
		</tr>
		<?php } ?>
        </tbody>
        </table>
 		 
	</div>
</div>
</form>
<br><br><br><br>
<?php 
}
if ($userProfile->getDesig_roleid() == 1) {
?>
<br><br><br><br>
<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#FFFFFF;">
<div class="contentMainDiv" style="width:80%;margin:auto;">
<div class="contentDiv">

<table class="pndTble">
<thead>
<tr><td colspan="6" style="background-color: #A52A2A;padding: 10px;text-align: center;font-weight: bold;color: #fff;font-size: 20px;">Pendency Status of the Initiating Officers</td></tr>
<tr>
<tr>
<th rowspan="3" style="width:50px;background-color: #D2691E;"><?PHP echo $label_name[3]; //S.No.?></th>
<th rowspan="3" style="background-color: #D2691E;" ><?PHP echo 'Initiating Officer'; //Source?></th>
<th colspan="4"  style="width: 70%;;background-color: #D2691E;"><?PHP echo $label_name[5]; //Number Of Petitions?></th>


</tr>
<tr>
<th rowspan="2" style="background-color: #D2691E;"><?PHP echo $label_name[11]; //Closing Balance?></th>				
<th rowspan="2" style="background-color: #D2691E;"> <?PHP echo $label_name[12]; //Pending Less than 1 month?></th>
<th rowspan="2" style="background-color: #D2691E;"> <?PHP echo $label_name[13]; //Pending for 2 months?></th>
<th rowspan="2" style="background-color: #D2691E;"> <?PHP echo $label_name[14]; //Pending for more than 2 months?></th>

</tr>
</thead>
<?php	
$sql="WITH off_pet AS ( 
select a.petition_id, a.action_type_code, a.off_loc_id as state_id, b.petition_date,
a.action_entby, a.l_action_type_code 
from fn_pet_action_first_last_off_level(1,7) a 
inner join pet_master b on b.petition_id=a.petition_id 
where a.l_action_type_code not in ('A','R') ) 

select * from ( 
select aa.state_id ,aa.state_name,aa.state_tname,aa.state_id as off_location_id,
aa.off_loc_name,aa.off_loc_tname,aa.dept_user_id,aa.dept_desig_id,
aa.dept_desig_name,aa.dept_desig_tname,aa.dept_id, COALESCE(pcb.pnd,0) as pnd, 
COALESCE(pcb.pnd_lt_eq_30,0) as pnd_lt_eq_30, 
COALESCE(pcb.pnd_31_to_60,0) as pnd_31_to_60, 
COALESCE(pcb.pnd_gt_60,0) as pnd_gt_60 from 
(select a.state_id,a.state_id as off_location_id,a.state_name,
a.state_name as off_loc_name, a.state_tname,a.state_tname as off_loc_tname,
c.dept_id,c.dept_user_id,c.dept_desig_id,c.dept_desig_name, 
c.dept_desig_tname from mst_p_state a 
inner join vw_usr_dept_users_v c on c.off_loc_id=a.state_id 
and c.dept_id=1 and c.dept_desig_role_id=2 and c.off_level_dept_id=1 and c.pet_disposal ) aa 
left join (select state_id,action_entby,count(*) as pnd, 
sum(case when (current_date - petition_date::date) <= 30 then 1 else 0 end) as pnd_lt_eq_30, 
sum(case when ((current_date - petition_date::date) > 30 and (current_date - petition_date::date)<=60 ) then 1 else 0 end) as pnd_31_to_60, 
sum(case when (current_date - petition_date::date) > 60 then 1 else 0 end) as pnd_gt_60 
from off_pet a 
group by state_id,action_entby) pcb on pcb.state_id=aa.state_id 
and pcb.action_entby=aa.dept_user_id) b_rpt 
order by b_rpt.pnd_gt_60 desc,b_rpt.pnd_31_to_60 desc,b_rpt.pnd_lt_eq_30 desc 
";
//$sql="select * from mvw_subordinate_pending_off_lvl_2 order by pending desc,griev_district_id";
//echo $sql;

$result = $db->query($sql);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
$row_cnt = $result->rowCount();
$i=0;
$t_d_pending=0;
$t_d_pending_30=0;
$t_d_pending_30_60=0;
$t_d_pending_60=0;
if($row_cnt!=0)
{
foreach($rowarray as $row)
{
$dept_desig_name=	$row['dept_desig_name'];
$d_pending=	$row['pnd'];
$d_pending_30=	$row['pnd_lt_eq_30'];
$d_pending_30_60=	$row['pnd_31_to_60'];
$d_pending_60=	$row['pnd_gt_60'];
?>
<tr>
<td style="text-align:center"><?php echo ++$i;?></td>
<td style="text-align:left"><?PHP echo $dept_desig_name; ?></td>
<td style="text-align:right"><?PHP echo $d_pending; ?></td>
<td style="text-align:right"><?PHP echo $d_pending_30; ?></td>
<td style="text-align:right"><?PHP echo $d_pending_30_60; ?></td>
<td style="text-align:right"><?PHP echo $d_pending_60; ?></td>
</td>
</tr> 
<?php 
$t_d_pending=	$t_d_pending+$d_pending;
$t_d_pending_30=$t_d_pending_30+$d_pending_30;	
$t_d_pending_30_60=$t_d_pending_30_60+$d_pending_30_60;	
$t_d_pending_60=$t_d_pending_60+$d_pending_60;	
}
?>
<tr class="totalTR">
<td colspan="2" style="border: 1px solid white;background-color: #D2691E;"><?PHP echo $label_name[16]; // Total?></td>
<td style="border: 1px solid white;background-color: #D2691E;">
<?php echo $t_d_pending;?> 

</td>                
<td style="border: 1px solid white;background-color: #D2691E;">
<?php echo $t_d_pending_30;?> 
</td>  
<td style="border: 1px solid white;background-color: #D2691E;">
<?php echo $t_d_pending_30_60;?> 
</td>  
<td style="border: 1px solid white;background-color: #D2691E;">
<?php echo $t_d_pending_60;?> 
</td>  
</tr>
<script>
document.getElementById('tot_pend').innerHTML = '<?php echo $t_d_pending;?>';
document.getElementById('pend_l30').innerHTML = '<?php echo $t_d_pending_30;?>';
document.getElementById('pend_30_60').innerHTML = '<?php echo $t_d_pending_30_60;?>';
document.getElementById('pend_above_60').innerHTML = '<?php echo $t_d_pending_60;?>';
</script>
<?php
}
else {
?>
<tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo 'No Records Found';?>...</td>   </tr>
<?php	
} 
?>
</table>
</div></div>
</form>
<?php
}
?>
<br><br><br><br>


<?php 
include("footer.php");
//} 
?>
