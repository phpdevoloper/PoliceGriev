<?php
ob_start();
session_start();
include("dbr.php");
include("header_menu.php");
include("menu_home.php");
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
function detail_view(frm_date,to_date,dept,dept_name,dept_user_id,dept_designation,status)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("dept_user_id").value=dept_user_id;
	document.getElementById("status").value=status;
	document.getElementById("dept_designation").value=dept_designation;
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_officerswise_simple_pet_period.php";
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
	
	$reporttypename = "";
			 
?>
<div class="contentMainDiv">
	<div class="contentDiv" style="width:90%;margin:auto;">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="10" ><a href="" onclick="self.close();"><img src="images/bak.jpg" /></a></tr>
            <tr> 
				<th colspan="10" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="10" class="main_heading"><?PHP echo $label_name[0]; //Department wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="10" class="main_heading"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="10" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?>
			<tr> 
				<th colspan="10" class="search_desc"><b>Petition Period -  </b><?PHP echo $label_name[1]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			<tr>
                <tr>
                <th rowspan="3"  style="width:3%;"><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="3"  style="width:20%;"><?PHP echo $label_name[40]; //Concerned Officer?></th>
                <th colspan="8" style="width: 70%;"><?PHP echo $label_name[4].':     ( E = A - (B + C + D)     and      F + G + H = E )';//Number Of Petitions?></th>


				</tr>
				<tr>
                <th style="width:10%;"><?PHP echo $label_name[6]; //Received?><br>(A)</th>
                <th style="width:10%;"><?PHP echo $label_name[8]; //Closed?><br>(B)</th>
				<th style="width:10%;"><?PHP echo $label_name[9]; //Closed?><br>(C)</th>
				<?php if ($userProfile->getPet_disposal()) { ?>
                <th style="width:10%;"><?PHP echo $label_name[62].' - '.$userProfile->getDept_desig_name(); //Closing Balance?><br>(D)</th>
				<?php } else { ?>
				<th style="width:10%;"><?PHP echo $label_name[62]; //Closing Balance?><br>(D)</th>
				<?php } ?>
                <th style="width:10%;"> <?PHP echo $label_name[61] //Pending for more than 2 months?><br>(E)</th>
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
		
		if ($userProfile->getOff_level_id() == 2)
					$off_loc_cond = "2,".$userProfile->getOff_loc_id().",null,'{2,3,4,6,7,10,11}'"; 	 
				else if ($userProfile->getOff_level_id() == 3)
					$off_loc_cond = "3,".$userProfile->getOff_loc_id().",null,'{3,4,5}'";
				else if ($userProfile->getOff_level_id() == 4)
					$off_loc_cond = "4,".$userProfile->getOff_loc_id().",null,'{4,5}'";
				
				
 	

		$sql="select district_id, district_name, dept_id, dept_name, off_level_dept_id, off_level_dept_name, 
			dept_desig_id, dept_desig_name, off_loc_id, off_loc_name, dept_user_id, 
			recd_cnt, acp_cnt, rjct_cnt, no_act_cnt, cl_pend_cnt, cl_pend_leq30d_cnt,cl_pend_gt30leq60d_cnt,cl_pend_gt60d_cnt 
			-- ,recd_cnt-acp_cnt-rjct_cnt-no_act_cnt-cl_pend_cnt chk

			from 

			( select aa.district_id,aa.district_name, bb.dept_id, bb.dept_name, cc.off_level_dept_id, cc.off_level_dept_name, 
			cc.dept_desig_id, cc.dept_desig_name, cc.off_loc_id, cc.off_loc_name, cc.dept_user_id, 
			COALESCE(recd.recd_cnt,0) as recd_cnt, 
			COALESCE(acp.acp_cnt,0) as acp_cnt, 
			COALESCE(rjct.rjct_cnt,0) as rjct_cnt, 
			COALESCE(no_act.no_act_cnt,0) as no_act_cnt, 
			COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt, 
			COALESCE(clb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, 
			COALESCE(clb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt, 
			COALESCE(clb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt 

		    from fn_single_district(".$userProfile->getDistrict_id().") aa 
			cross join usr_dept bb 
			inner join fn_usr_dept_users_vhr(2,".$userProfile->getDistrict_id().",null,'{2,3,4,6,7,10,11}') cc on cc.dept_id = bb.dept_id 
			and cc.pet_act_ret = true and dept_desig_id <> ".$userProfile->getDistrict_id()." 

			left join -- received for action 

			( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as recd_cnt 
			  from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		      inner join pet_master b on b.petition_id=a.petition_id 
			  inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
			  and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
			  inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
			group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, 
			c.dept_user_id ) recd on recd.griev_district_id=aa.district_id 
			and recd.dept_id=cc.dept_id and recd.off_level_dept_id=cc.off_level_dept_id and recd.off_loc_id=cc.off_loc_id 
			and recd.dept_desig_id=cc.dept_desig_id and recd.dept_user_id=cc.dept_user_id 

			left join -- accepted 

			( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as acp_cnt 
			from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id 
			and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  
			and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and exists 
			(select * from pet_action d where d.petition_id=a.petition_id and action_type_code = 'A')
			group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, 
			c.dept_desig_id, c.dept_user_id ) acp on acp.griev_district_id=aa.district_id
			 and acp.dept_id=cc.dept_id and acp.off_level_dept_id=cc.off_level_dept_id 
			 and acp.off_loc_id=cc.off_loc_id and acp.dept_desig_id=cc.dept_desig_id 
			and acp.dept_user_id=cc.dept_user_id 

			left join -- rejected 

			( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as rjct_cnt 
			from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
			and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and exists 
			(select * from pet_action d where d.petition_id=a.petition_id and action_type_code = 'R')
			group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, 
			c.dept_desig_id, c.dept_user_id ) rjct on rjct.griev_district_id=aa.district_id 
			and rjct.dept_id=cc.dept_id and rjct.off_level_dept_id=cc.off_level_dept_id 
			and rjct.off_loc_id=cc.off_loc_id and rjct.dept_desig_id=cc.dept_desig_id 
			and rjct.dept_user_id=cc.dept_user_id 

			left join -- pending with SDC 

			( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as no_act_cnt 
			from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
			inner join fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") a1 on a1.petition_id=a.petition_id 
			inner join pet_master b on b.petition_id=a1.petition_id 
			inner join fn_pet_action_first_office() b1 on b1.petition_id = a1.petition_id
			and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  
			and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom 
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
			group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, 
			c.dept_desig_id, c.dept_user_id ) no_act on no_act.griev_district_id=aa.district_id
			and no_act.dept_id=cc.dept_id and no_act.off_level_dept_id=cc.off_level_dept_id 
			and no_act.off_loc_id=cc.off_loc_id and no_act.dept_desig_id=cc.dept_desig_id and no_act.dept_user_id=cc.dept_user_id 

			left join -- pending with the concerned officer 

			( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, 
			c.dept_user_id, count(*) as cl_pend_cnt, 
			sum(case when (current_date - b.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
			sum(case when ((current_date - b.petition_date::date) > 30 and (current_date - b.petition_date::date)<=60 ) then 1 else 0 end) 
			as cl_pend_gt30leq60d_cnt, sum(case when (current_date - b.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
			from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id 
			and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  
			and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and not exists 
			(select * from pet_action d1 where d1.petition_id=a.petition_id 
			and action_type_code in ('A','R')) 
			and not exists (select * from fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
			group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, 
			c.dept_desig_id, c.dept_user_id ) clb on clb.griev_district_id=aa.district_id 
			and clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id 
			and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id 
			and clb.dept_user_id=cc.dept_user_id ) b_rpt 

			where recd_cnt+acp_cnt+rjct_cnt+no_act_cnt+cl_pend_cnt > 0 
			order by b_rpt.district_id, b_rpt.dept_id, b_rpt.off_level_dept_id, b_rpt.dept_desig_id, b_rpt.off_loc_name ";

	    $result = $db->query($sql);
		$row_cnt = $result->rowCount();
		$temp_dept_id='';
		$j=1;
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		$SlNo=1;
		
if($row_cnt!=0)
	{
		 
		foreach($rowarray as $row)
		{
			
			$dept_id=$row['dept_id'];
			$source_id=$row['source_id'];
			$dept_name=$row['dept_name'];
			$source_name=$row['source_name'];
			$dept_name=$row['dept_name']; 
			$dept_desig=$row['dept_desig_name']." - ".$row['off_loc_name'];
			$dept_user_id = $row['dept_user_id'];
			$op_bal = $row['dept_name'];
			
			$recd_cnt = $row['recd_cnt'];
			$acp_cnt = $row['acp_cnt'];
			$rjct_cnt = $row['rjct_cnt'];
			$dfr_cnt = $row['dfr_cnt']; //3 Rejected
			$no_act_cnt =  $row['no_act_cnt'];
			$cl_pend_cnt = $row['cl_pend_cnt'];
			
			$cl_pend_leq30d_cnt = $row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt = $row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt = $row['cl_pend_gt60d_cnt'];	
			
						
			if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id;
	 
			?>
			
           <tr>
           		<td class="h1" style="text-align:left" colspan="10"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			} ?>

			<tr>
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_desig; ?></td>
                              
                 <!-- 1 Received-->
                 <?php if($recd_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'recd'; ?>')"><?php echo $recd_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $recd_cnt;?> </td> <?php } ?>

                 <!-- 2 Accepted -->
                 <?php if($acp_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'acpt'; ?>')"><?php echo $acp_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $acp_cnt;?> </td> <?php } ?>
                                
               <!-- 3 Rejected -->
                <?php if($rjct_cnt>0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'rjct'; ?>')"><?php echo $rjct_cnt;?> </a></td>
			  	 <?php } 
				 else {?>
				<td><?php echo $rjct_cnt;?> </td> <?php } ?>

                 <!-- 5 Pending Petitions -->
                 <?php if($cl_pend_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'pending'; ?>')"><?php echo $cl_pend_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_cnt;?> </td> <?php } ?>
                
                <!-- 6 Pending for <30 Days -->
                 <?php if($cl_pend_leq30d_cnt!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'pl1m'; ?>')"><?php echo $cl_pend_leq30d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>

                <!-- 7 Pending for >30 and <60 Days -->
                 <?php if($cl_pend_gt30leq60d_cnt!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'pg1m'; ?>')"><?php echo $cl_pend_gt30leq60d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?>
				
                                                 
               
                <!-- 8 Pending for >60 Days -->
                  <?php if($cl_pend_gt60d_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo $dept_desig; ?>',
						'<?php echo 'pg2m'; ?>' )"><?php echo $cl_pend_gt60d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?>
				
                      	
			</tr>
			<?php  
			$i++;		
			
			$tot_recd_cnt = $tot_recd_cnt + $recd_cnt;
			$tot_acp_cnt = $tot_acp_cnt + $acp_cnt;
			$tot_rjct_cnt = $tot_rjct_cnt + $rjct_cnt;
			$tot_pwou_cnt = $tot_pwou_cnt + $no_act_cnt;
			$tot_cl_pend_cnt = $tot_cl_pend_cnt + $cl_pend_cnt;
			$tot_cl_pend_m2m_ct = $tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
			$tot_cl_pend_2m_cnt =  $tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1m_cnt = $tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Total' ?></td>
                
                <td><?php echo $tot_recd_cnt;?></td>                      
                <td><?php echo $tot_acp_cnt;?></td>
           		<td><?php echo $tot_rjct_cnt;?></td>
           		<td><?php echo $tot_pwou_cnt;?></td>
            	<td><?php echo $tot_cl_pend_cnt;?></td>
				<td><?php echo $tot_cl_pend_m2m_ct;?></td>
				<td><?php echo $tot_cl_pend_2m_cnt;?></td>
				<td><?php echo $tot_cl_pend_1m_cnt;?></td>
                
			</tr>
			<tr>
            <td colspan="10" class="buttonTD"> 
            
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

$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$dept_user_id=stripQuotes(killChars($_POST["dept_user_id"]));	
$status=stripQuotes(killChars($_POST["status"]));	
$dept_designation=stripQuotes(killChars($_POST["dept_designation"]));	

$reporttypename = "";
$_SESSION["check"]="yes"; 

if($status=='recd') {
	$cnt_type=" Received Petitions";	
} else if($status=='acpt') {
	$cnt_type=" Accepted Petitions";
} else if($status=='rjct') {
	$cnt_type=" Rejected Petitions";
} else if($status=='pwdo') {
	if ($userProfile->getPet_disposal())
		$cnt_type=" Petitions Pending with ".$userProfile->getOff_desig_emp_name().$userProfile->getDept_desig_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name());	
	else
		$cnt_type=" Petitions Pending with Disposing Officer";
} else if($status=='pending') {
	$cnt_type=" Petitions Pending with ".$dept_designation;
} else if($status=='pl1m') {
	$cnt_type=" Petitions pending for < 1 Month";
} else if($status=='pg1m') {
	$cnt_type=" Petitions pending for > 1 month and < 2 Months";
} else if($status=='pg2m') {
	$cnt_type=" Petitions pending for < 2 months";
}
?>

<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<div class="contentMainDiv">
	<div class="contentDiv" style="width:98%;margin:auto;">	
		<table class="rptTbl">
			<thead>
				<tr id="bak_btn"><th colspan="9" > 
				<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
				</th></tr>
                
              
                <tr> 
				<th colspan="14" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
				</tr>
            
				<tr> 
				<th colspan="9" class="main_heading"><?PHP echo $label_name[0]." - ";?> <?php echo "Details of ".$cnt_type; ?></th>
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
					<th colspan="9" class="main_heading"><?PHP echo  $label_name[40].' : '.$dept_designation; //Report type name?></th>
				</tr>
				<tr>				
                <th colspan="9" class="search_desc">Petition Period - &nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]." : "; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo$label_name[20]; //S.No.?></th>
				<th><?PHP echo $label_name[21]; //S.No.?></th>
				<th><?PHP echo $label_name[22]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[23]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[24]; //Source & Sub Source?></th>
				<th><?PHP echo $label_name[26]; //Grievance?></th>
				<th><?PHP echo $label_name[27]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[28]; //Action Type, Date & Remarks?></th>
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


	if($status=='recd'){
		$sql=" -- Received
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (
		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()."
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date)";		
 	}
	
	else if($status=='acpt'){	
		$sql=" -- Received
		
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (
		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()."
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."  
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and exists 
		(select * from pet_action d where d.petition_id=a.petition_id and action_type_code = 'A'))";		
	}

	else if($status=='rjct'){	
		$sql="-- Rejected
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (
		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()."
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."  
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and exists 
		(select * from pet_action d where d.petition_id=a.petition_id and action_type_code = 'R'))";
	}

	else if($status=='pwdo'){	
		$sql="-- Pending with Disposing Officer
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
				from vw_petition_details v 
				where v.petition_id in (

		select a.petition_id 
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") a1 on a1.petition_id=a.petition_id 
		inner join pet_master b on b.petition_id=a1.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = a1.petition_id 
		and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and b1.off_loc_id = ".$userProfile->getDistrict_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date)";
	}

	else if($status=='pending'){
		$sql="-- Pending Petitions
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (

		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date and not exists 
		(select * from pet_action d1 where d1.petition_id=a.petition_id 
		and action_type_code in ('A','R')) 
		and not exists (select * from fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id))";
	 }

	else if ($status=='pl1m') 
	{
		$sql="-- Pending Petitions
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (

		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date  
		and not exists 
		(select * from pet_action d1 where d1.petition_id=a.petition_id 
		and action_type_code in ('A','R')) 
		and not exists (select * from fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
		and (case when (current_date -  b.petition_date::date) <= 30 then 1 else 0 end)=1 )
		";
	}
	
	else if ($status == 'pg1m') {
		$sql="-- Pending Petitions
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (

		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		and not exists 
		(select * from pet_action d1 where d1.petition_id=a.petition_id 
		and action_type_code in ('A','R')) 
		and not exists (select * from fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
		and (case when ((current_date -  b.petition_date::date) > 30 and (current_date -  b.petition_date::date)<=60)  then 1 else 0 end)=1 )
		";
	} 

	else if ($status == 'pg2m') {
		$sql="-- Pending Petitions
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, 
		v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, 
		v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in (

		select b.petition_id
		from fn_pet_action_received_cb_from(".$userProfile->getDept_user_id().") a 
		inner join pet_master b on b.petition_id=a.petition_id 
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." 
		and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id = ".$userProfile->getDistrict_id()."
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  and a.to_whom=".$dept_user_id."
		where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
		and not exists 
		(select * from pet_action d1 where d1.petition_id=a.petition_id 
		and action_type_code in ('A','R')) 
		and not exists (select * from fn_pet_action_pending_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
		and (case when (current_date -  b.petition_date::date) > 60 then 1 else 0 end)=1 )
		";
	} 
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
			<td class="desc" style="width:13%;"> <a href=""  onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."&nbsp;"."&amp;"."<br/>".$row['petition_date']; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:20%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$row['gri_address']; ?></td>
            
<td class="desc"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
            
            <td class="desc"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
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
