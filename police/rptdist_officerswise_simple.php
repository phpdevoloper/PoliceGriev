<?php
ob_start();
session_start();
$pagetitle="Officers wise Report - Simple";
include("db.php");
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
$pagetitle="Officers wise Report";

?>
  
<script type="text/javascript">
function detail_view(frm_date,to_date,dept,dept_name,dept_user_id,status,src_id,sub_src_id,gtypeid,gsubtypeid,grie_dept_id,off_cond_para,p_off_cond_para,pet_own_heading,petition_processing_loc)
{ 
	document.getElementById("frdate").value=frm_date;
	document.getElementById("todate").value=to_date;
	document.getElementById("dept").value=dept;
	document.getElementById("dept_name").value=dept_name;
	document.getElementById("status").value=status;
	document.getElementById("dept_user_id").value=dept_user_id;
	document.getElementById("src_id").value=src_id;
	document.getElementById("sub_src_id").value=sub_src_id;
	document.getElementById("gtypeid").value=gtypeid;
	document.getElementById("gsubtypeid").value=gsubtypeid;
	document.getElementById("grie_dept_id").value=grie_dept_id;
	document.getElementById("off_cond_para").value=off_cond_para;
	document.getElementById("p_off_cond_para").value=p_off_cond_para;
	document.getElementById("petition_processing_loc").value=petition_processing_loc;
	document.getElementById("pet_own_heading").value=pet_own_heading;
	document.getElementById("hid").value='done';
	document.rpt_abstract.method="post";
	document.rpt_abstract.action="rptdist_officerswise_simple.php";
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
	//Conditions part for processing petition

	$p_dept=stripQuotes(killChars($_POST["p_dept"]));
	$p_dist=stripQuotes(killChars($_POST["p_dist"]));

	$p_rdo=stripQuotes(killChars($_POST["p_rdo"]));
	$p_taluk=stripQuotes(killChars($_POST["p_taluk"]));
	$p_firka=stripQuotes(killChars($_POST["p_firka"]));

	$p_block=stripQuotes(killChars($_POST["p_block"]));
	$p_urban=stripQuotes(killChars($_POST["p_urban"]));
	$p_office=stripQuotes(killChars($_POST["p_office"]));

	$p_off_loc_cond = "";

	if ($p_dept != "") {
		$p_dept=substr($p_dept, 0,1);
		$p_off_loc_cond = $p_off_loc_cond." and c.dept_id=".$p_dept."";
		$dept_sql = "SELECT dept_id,dept_name,dept_tname FROM usr_dept where dept_id='$p_dept'";
		$dept_rs=$db->query($dept_sql);
		$dept_row = $dept_rs->fetch(PDO::FETCH_BOTH);
		$p_dept_name= $dept_row[1];
	}

	if ($p_firka != "") {
		$p_off_loc_id="".$p_firka."";
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_firkadept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT firka_name, firka_tname FROM mst_p_firka where firka_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "Firka: ".$off_loc_rw[0];
	} else if ($p_taluk != "") {
		$p_off_loc_id="".$p_taluk.""; 
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_tlkdept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT taluk_name, taluk_tname FROM mst_p_taluk where taluk_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "Taluk: ".$off_loc_rw[0];	
	} else if ($p_block != "") {
		$p_off_loc_id="".$p_block.""; 
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_blockdept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT block_name, block_tname FROM mst_p_lb_block where block_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "Block: ".$off_loc_rw[0];
	} else if ($p_urban != "") {
		$p_off_loc_id="".$p_urban.""; 
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_urbandept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT lb_urban_name, lb_urban_tname FROM mst_p_lb_urban where lb_urban_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "Urban Body: ".$off_loc_rw[0];
	} else if ($p_rdo != "") {
		$p_off_loc_id="".$p_rdo.""; 
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_rdodept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT rdo_name, rdo_tname FROM mst_p_rdo where rdo_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "RDO: ".$off_loc_rw[0];
	} else if ($p_dist != "") {
		$p_off_loc_id="".$p_dist.""; 
		$p_off_level_dept_id=stripQuotes(killChars($_POST["p_offlevel_distdept_idhid"]));			
		$p_off_loc_cond = 	$p_off_loc_cond." and c.off_level_dept_id=".$p_off_level_dept_id." and c.off_loc_id=".$p_off_loc_id."";
		$off_loc = "SELECT district_name, district_tname FROM mst_p_district where district_id='$p_off_loc_id'";
		$off_loc_rs=$db->query($off_loc);
		$off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
		$p_off_loc_name= "District: ".$off_loc_rw[0];
	} 
	$p_off_cond_para = $p_dept."-".$p_off_level_dept_id."-".$p_off_loc_id;
	$petition_processing_loc="";
	if ($p_dept_name != "") {
			$petition_processing_loc = $petition_processing_loc."Petition Processed By  Department: ".$p_dept_name." - ";
	}
	if ($p_off_loc_name != "") {
			$petition_processing_loc = $petition_processing_loc." Office Location: ".$p_off_loc_name;
	}

	$dept=stripQuotes(killChars($_POST["dept"]));
		  
		 $dept=substr($dept, 0,1);
		 if($dept=="")
			$dept=$_SESSION["dept_id"];
		if ($dept != "") {
		 $dept_sql = "SELECT dept_id,dept_name,dept_tname FROM usr_dept where dept_id='$dept'";
		 $dept_rs=$db->query($dept_sql);
		 $dept_row = $dept_rs->fetch(PDO::FETCH_BOTH);
		 $dept_name= $dept_row[1];  
		}
		$off_loc_cond = ""; 
		if(stripQuotes(killChars($_POST["firka"]!="")) || $_SESSION["hid_firka"]!="") 
			 {
				  
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
			 }
			else if( stripQuotes(killChars($_POST["taluk"]!="")) || $_SESSION["hid_taluk"]!="")
			 { 
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

			 }
			 else if(stripQuotes(killChars($_POST["block"]!="")) || $_SESSION["hid_block"]!="")
			 {
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
			 }
			 else if(stripQuotes(killChars($_POST["urban"]!="")) || $_SESSION["hid_urban"]!="")
			 {
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
			 }
			  else if(stripQuotes(killChars($_POST["rdo"]!="")) || $_SESSION["hid_rdo"]!="")
			 {
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
				 
				  
		   		 $off_level_dept_id=stripQuotes(killChars($_POST["offlevel_distdept_idhid"]));
				 
				 $off_loc = "SELECT district_name, district_tname FROM mst_p_district where district_id='$off_loc_id'";
				 $off_loc_rs=$db->query($off_loc);
				 $off_loc_rw = $off_loc_rs->fetch(PDO::FETCH_BOTH);
				 $off_loc_name= "District: ".$off_loc_rw[0];
				 $off_loc_cond = "2,".$off_loc_id.",null,'{2,3,4,5,6,7,10,11}'";
				
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
		if ($dept_name != "") {
			$pet_own_heading = $pet_own_heading."Petition Owned By Department: ".$dept_name;
		}
		
		if ($off_loc_name != "") {
			$pet_own_heading = $pet_own_heading." - Office Location: ".$off_loc_name;
		}
		
?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="15" ><a href="" onclick="self.close();"><img src="images/bak.jpg" /></a></th></tr>
            <tr> 
				<th colspan="15" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="15" class="main_heading"><?PHP echo $label_name[0]; //Officers wise Report?></th>
			</tr>
            
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="15 class="main_heading"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			<?php if ($pet_own_heading != "") {?>
				<tr> 
				<th colspan="15" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
			</tr>
			<?php } ?> 
			
			<?php if ($petition_processing_loc != "") {?>
				<tr> 
				<th colspan="15" class="main_heading"><?PHP echo $petition_processing_loc; //Report type name?></th>
			</tr>
			<?php } ?>
			
			<tr> 
				<th colspan="15" class="search_desc"><?PHP echo $label_name[18]; //From Date?> : <?php echo $from_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19]; //To Date?> : <?php echo $to_date; ?>	</th>
			</tr>
			
			
			
              <tr>
                <tr> 
                <th rowspan="3"><?PHP echo $label_name[3]; //S.No.?></th>
                <th rowspan="3"><?PHP echo $label_name[40]; //S.No.?></th>
				<th colspan="13" style="width: 70%;font-size:16px; font-weight: bold;">No. of Petitions:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Closing Balance&nbsp;&nbsp;(I) = (A + B) - (C + D + G + H)</th>
				</tr>
				<tr>
				 <th><?PHP echo $label_name[5]; //S.No.?><br> (A)</th>
				 <th><?PHP echo $label_name[6]; //S.No.?> (B)</th>
				 <th><?PHP echo $label_name[8]; //S.No.?> (C)</th>
				 <th><?PHP echo $label_name[9]; //S.No.?> (D)</th>
				 <th><?PHP echo $label_name[34]; //S.No.?> (E)</th>
				 <th><?PHP echo $label_name[35]; //S.No.?> (F)</th>
				 <th><?PHP echo $label_name[36]; //S.No.?> (G)</th>
				 <th><?PHP echo $label_name[38]; //S.No.?> (H)</th>
				 <th style="font-size:16px; font-weight: bold;"><?PHP echo $label_name[10]; //S.No.?> (I)</th>				 
				 <th><?PHP echo $label_name[11]; //S.No.?> (J)</th>
				 <th><?PHP echo $label_name[12]; //S.No.?> (K)</th>
				 <th><?PHP echo $label_name[13]; //S.No.?> (L)</th>
				 <!-- <th><?PHP // echo $label_name[14]; //S.No.?> (M)</th>-->
				</tr>

<!--
                <tr>
                <th colspan="1" style="width: 70%;">Closing Balance (L) = (A) + (B) - (C) + (D) - (E) + (H) + (K)</th>
                <th colspan="15" style="width: 70%;">Replied Up (E) = (F) + (G) + (H) + (I) + (J)</th>
				</tr>
-->
			
			<!--<tr>
			  <th><?PHP //echo $label_name[9]; //Accepted?></th>
			  <th><?PHP //echo $label_name[10]; //Rejected?></th>
			  </tr>-->
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
		$dept_cond.=" and b1.dept_id=".$userProfile->getDept_id()." ";
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


$sql="
select district_id, district_name, dept_id, dept_name, off_level_dept_id, off_level_dept_name, dept_desig_id, dept_desig_name, off_loc_id, off_loc_name, dept_user_id, pob_cnt, recd_cnt, acp_cnt, rjct_cnt, rb4_acp_cnt, rb4_rjct_cnt, no_act_cnt, oth_cnt, cl_pend_cnt, 
cl_pend_leq30d_cnt,cl_pend_gt30leq60d_cnt,cl_pend_gt60d_cnt
/*
cl_pend_m2m_cnt, 
cl_pend_2m_cnt, 
cl_pend_1m_cnt, 
cl_pend_l1m_cnt 
*/
from 

( select aa.district_id,aa.district_name, bb.dept_id, bb.dept_name, cc.off_level_dept_id, cc.off_level_dept_name, cc.dept_desig_id, cc.dept_desig_name, cc.off_loc_id, cc.off_loc_name, cc.dept_user_id, COALESCE (pob.pob_cnt,0) as pob_cnt, COALESCE(recd.recd_cnt,0) as recd_cnt, COALESCE(acp.acp_cnt,0) as acp_cnt, COALESCE(rjct.rjct_cnt,0) as rjct_cnt, COALESCE(rb4_acp.rb4_acp_cnt,0) as rb4_acp_cnt, COALESCE(rb4_rjct.rb4_rjct_cnt,0) as rb4_rjct_cnt, COALESCE(no_act.no_act_cnt,0) as no_act_cnt, COALESCE(oth.oth_cnt,0) as oth_cnt, COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt, 
COALESCE(clb.cl_pend_leq30d_cnt,0) as cl_pend_leq30d_cnt, 
COALESCE(clb.cl_pend_gt30leq60d_cnt,0) as cl_pend_gt30leq60d_cnt,
COALESCE(clb.cl_pend_gt60d_cnt,0) as cl_pend_gt60d_cnt
/*
COALESCE(clb.cl_pend_m2m_cnt,0) as cl_pend_m2m_cnt, 
COALESCE(clb.cl_pend_2m_cnt,0) as cl_pend_2m_cnt, 
COALESCE(clb.cl_pend_1m_cnt,0) as cl_pend_1m_cnt, 
COALESCE(clb.cl_pend_l1m_cnt,0) as cl_pend_l1m_cnt 
*/
from fn_single_district(".$userProfile->getDistrict_id().") aa cross join usr_dept bb 
inner join fn_usr_dept_users_vhr(2,".$userProfile->getDistrict_id().",null,'{2,3,4,6,7,10,11}') cc on cc.dept_id = bb.dept_id 
and cc.pet_act_ret = true and dept_desig_id <> 17 

left join -- pending: op. bal. 

( select a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as pob_cnt 
from fn_pet_action_pending_ob_dt_from(('".$frm_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
inner join pet_master a on a.petition_id=b.petition_id
inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom  
group by a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) pob 
on pob.griev_district_id=aa.district_id and pob.dept_id=cc.dept_id and pob.off_level_dept_id=cc.off_level_dept_id and pob.off_loc_id=cc.off_loc_id and pob.dept_desig_id=cc.dept_desig_id and pob.dept_user_id=cc.dept_user_id 

left join -- received for action   

( select a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as recd_cnt 
from fn_pet_action_received_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") b
inner join pet_master a on a.petition_id=b.petition_id
inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id  =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom 
where not exists (select * from fn_pet_action_pending_ob_dt_from(('".$frm_dt."'::date),  ".$_SESSION['USER_ID_PK'].") d where d.petition_id=b.petition_id) 
group by a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) recd 
on recd.griev_district_id=aa.district_id and recd.dept_id=cc.dept_id and recd.off_level_dept_id=cc.off_level_dept_id and recd.off_loc_id=cc.off_loc_id and recd.dept_desig_id=cc.dept_desig_id and recd.dept_user_id=cc.dept_user_id 

left join -- accepted 

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as acp_cnt 
from fn_pet_action_received_ob_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
inner join fn_pet_action_accepted_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) acp 
on acp.griev_district_id=aa.district_id and acp.dept_id=cc.dept_id and acp.off_level_dept_id=cc.off_level_dept_id and acp.off_loc_id=cc.off_loc_id and acp.dept_desig_id=cc.dept_desig_id and acp.dept_user_id=cc.dept_user_id 

left join -- rejected 

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as rjct_cnt 
from fn_pet_action_received_ob_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
inner join fn_pet_action_rejected_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id 
and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) rjct 
on rjct.griev_district_id=aa.district_id and rjct.dept_id=cc.dept_id and rjct.off_level_dept_id=cc.off_level_dept_id and rjct.off_loc_id=cc.off_loc_id and rjct.dept_desig_id=cc.dept_desig_id and rjct.dept_user_id=cc.dept_user_id 

left join -- replied before and accepted 

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as rb4_acp_cnt 
from fn_pet_action_pending_cb_dt_with(('".$frm_dt."'::date)-1,  ".$_SESSION['USER_ID_PK'].") a
inner join fn_pet_action_accepted_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) rb4_acp 
on rb4_acp.griev_district_id=aa.district_id and rb4_acp.dept_id=cc.dept_id and rb4_acp.off_level_dept_id=cc.off_level_dept_id and rb4_acp.off_loc_id=cc.off_loc_id and rb4_acp.dept_desig_id=cc.dept_desig_id and rb4_acp.dept_user_id=cc.dept_user_id 

left join -- replied before and rejected 

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as rb4_rjct_cnt 
from fn_pet_action_pending_cb_dt_with(('".$frm_dt."'::date)-1,  ".$_SESSION['USER_ID_PK'].") a
inner join fn_pet_action_rejected_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) rb4_rjct 
on rb4_rjct.griev_district_id=aa.district_id and rb4_rjct.dept_id=cc.dept_id and rb4_rjct.off_level_dept_id=cc.off_level_dept_id and rb4_rjct.off_loc_id=cc.off_loc_id and rb4_rjct.dept_desig_id=cc.dept_desig_id and rb4_rjct.dept_user_id=cc.dept_user_id 

left join -- replied till date and pending with SDC 

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as no_act_cnt 
from fn_pet_action_pending_cb_dt_with('".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) no_act 
on no_act.griev_district_id=aa.district_id and no_act.dept_id=cc.dept_id and no_act.off_level_dept_id=cc.off_level_dept_id and no_act.off_loc_id=cc.off_loc_id and no_act.dept_desig_id=cc.dept_desig_id and no_act.dept_user_id=cc.dept_user_id 

left join -- replied till date and pending with others or SDC through others

( select b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as oth_cnt 
from fn_pet_action_pending_cb_dt_with_others(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
inner join pet_master b on b.petition_id=a.petition_id
inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom
group by b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) oth 
on oth.griev_district_id=aa.district_id and oth.dept_id=cc.dept_id and oth.off_level_dept_id=cc.off_level_dept_id and oth.off_loc_id=cc.off_loc_id and oth.dept_desig_id=cc.dept_desig_id and oth.dept_user_id=cc.dept_user_id 

left join -- pending: cl. bal. 

( select a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id, count(*) as cl_pend_cnt, 
sum(case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end) as cl_pend_leq30d_cnt, 
sum(case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60 ) then 1 else 0 end) as cl_pend_gt30leq60d_cnt, 
sum(case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end) as cl_pend_gt60d_cnt 
/*
sum(case when date_part('month',age(current_date, a.petition_date::date)) > 2 then 1 else 0 end) as cl_pend_m2m_cnt, 
sum(case when date_part('month',age(current_date, a.petition_date::date)) = 2 then 1 else 0 end) as cl_pend_2m_cnt, 
sum(case when date_part('month',age(current_date, a.petition_date::date)) = 1 then 1 else 0 end) as cl_pend_1m_cnt, 
sum(case when date_part('month',age(current_date, a.petition_date::date)) < 1 then 1 else 0 end) as cl_pend_l1m_cnt
*/ 
from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
inner join pet_master a on a.petition_id=b.petition_id
inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom  
group by a.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id ) clb on clb.griev_district_id=aa.district_id and clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id ) b_rpt 

where pob_cnt+recd_cnt+acp_cnt+rjct_cnt+rb4_acp_cnt+rb4_rjct_cnt+no_act_cnt+cl_pend_cnt > 0 order by b_rpt.district_id, b_rpt.dept_id, b_rpt.off_level_dept_id, b_rpt.dept_desig_id, b_rpt.off_loc_name 

";
//echo $sql;

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
			
			
			$pob_cnt = $row['pob_cnt'];
			$recd_cnt = $row['recd_cnt'];
			$acp_cnt = $row['acp_cnt'];
			$rjct_cnt = $row['rjct_cnt'];
			$rb4_acp_cnt =  $row['rb4_acp_cnt'];
			$rb4_rjct_cnt =  $row['rb4_rjct_cnt'];
			$no_act_cnt =  $row['no_act_cnt'];
			$oth_cnt =  $row['oth_cnt'];
			$cl_pend_cnt = $row['cl_pend_cnt'];
			
			$cl_pend_leq30d_cnt = $row['cl_pend_leq30d_cnt'];
			$cl_pend_gt30leq60d_cnt = $row['cl_pend_gt30leq60d_cnt'];
			$cl_pend_gt60d_cnt = $row['cl_pend_gt60d_cnt'];
			//$cl_pend_l1m_cnt = $row[cl_pend_l1m_cnt];
						
			if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id;
	 
			?>
			
           <tr>
           		<td class="h1" style="text-align:left" colspan="15"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			} ?>

			<tr>
                <td><?php echo $i;?></td>
                <td class="desc"><?PHP echo $dept_desig; ?></td>
                 
                 <!-- 1 Opening Pending-->  
                 <?php if($pob_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "pob"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $pob_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $pob_cnt;?> </td> <?php } ?>
                
                 <!-- 2 Received-->
                 <?php if($recd_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "recd"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $recd_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $recd_cnt;?> </td> <?php } ?>

                 <!-- 3 acp_cnt-->
                 <?php if($acp_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "acpt"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $acp_cnt;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $acp_cnt;?> </td> <?php } ?>
                                
               <!-- 4 rjct_cnt -->
                <?php if($rjct_cnt>0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "rjct"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $rjct_cnt;?> </a></td>
			  	 <?php } 
				 else {?>
				<td><?php echo $rjct_cnt;?> </td> <?php } ?>
                
                <!-- 5 rb4_acp_cnt -->
                 <?php if($rb4_acp_cnt!=0) {?>
			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
            '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "rb4acp"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $rb4_acp_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $rb4_acp_cnt;?> </td> <?php } ?>
                 
                 <!-- 6 rb4_rjct_cnt -->
                 <?php if($rb4_rjct_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "rb4_rjct"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $rb4_rjct_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $rb4_rjct_cnt;?> </td> <?php } ?>
                
                <!-- 7 no_act_cnt -->
                 <?php if($no_act_cnt!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "pfd"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $no_act_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $no_act_cnt;?> </td> <?php } ?>

                <!-- 8 oth_cnt -->
                 <?php if($oth_cnt!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "oth"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $oth_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $oth_cnt;?> </td> <?php } ?>
				
                                                 
               
                <!-- 12 Closing Pending -->
                  <?php if($cl_pend_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "pending"; ?>' ,'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $cl_pend_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_cnt;?> </td> <?php } ?>
				
                <!-- 13 Pending for more than two months -->
                 <?php if($cl_pend_leq30d_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "pm2"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $cl_pend_leq30d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_leq30d_cnt;?> </td> <?php } ?>
                
                <!-- 14 Pending for 2 months -->
                <?php if($cl_pend_gt30leq60d_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "p2m"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $cl_pend_gt30leq60d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_gt30leq60d_cnt;?> </td> <?php } ?> 
                 
                 <!-- 15 Pending for more than One month-->
                 <?php if($cl_pend_gt60d_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "pm1"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>' ,'<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $cl_pend_gt60d_cnt;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $cl_pend_gt60d_cnt;?> </td> <?php } ?> 
                
                <!-- 16 Pending for one month -->
             <!--    <?php //if($cl_pend_gt60d_cnt!=0) {?>
						<td><a href="" onclick="return detail_view('<?php //echo $from_date; ?>', '<?php //echo $to_date ; ?>',
                        '<?php //echo $dept_id; ?>','<?php //echo $dept_name; ?>','<?php //echo $dept_user_id; ?>','<?php //echo "p1m"; ?>','<?php //echo $src_id; ?>','<?php //echo $sub_src_id; ?>','<?php //echo $gtypeid; ?>','<?php //echo $gsubtypeid; ?>','<?php //echo $grie_dept_id; ?>','<?php //echo $off_cond_para; ?>','<?php //echo $p_off_cond_para; ?>','<?php //echo $pet_own_heading; ?>','<?php //echo $petition_processing_loc; ?>')"><?php //echo $cl_pend_gt60d_cnt;?> </a></td>
			  	 <?php //} else {?>
				<td><?php //echo $cl_pend_gt60d_cnt;?> </td> <?php //} ?>   -->         	
			</tr>
			<?php  
			$i++;		
			
			$tot_pob_cnt = $tot_pob_cnt + $pob_cnt;
			$tot_recd_cnt = $tot_recd_cnt + $recd_cnt;
			$tot_acp_cnt = $tot_acp_cnt + $acp_cnt;
			$tot_rjct_cnt = $tot_rjct_cnt + $rjct_cnt;
			$tot_recd_fa_cnt = $tot_recd_fa_cnt + $rb4_acp_cnt;
			$tot_pwlu_cnt = $tot_pwlu_cnt + $rb4_rjct_cnt;
			$tot_pwou_cnt = $tot_pwou_cnt + $no_act_cnt;
			$tot_clb_xtra_cnt = $tot_clb_xtra_cnt + $oth_cnt;
			$tot_cl_pend_cnt = $tot_cl_pend_cnt + $cl_pend_cnt;
			$tot_cl_pend_m2m_ct = $tot_cl_pend_m2m_ct + $cl_pend_leq30d_cnt;
			$tot_cl_pend_2m_cnt =  $tot_cl_pend_2m_cnt + $cl_pend_gt30leq60d_cnt;
			$tot_cl_pend_1m_cnt = $tot_cl_pend_1m_cnt + $cl_pend_gt60d_cnt;
			//$tot_cl_pend_l1m_cnt = $tot_cl_pend_l1m_cnt + $cl_pend_l1m_cnt;
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Total' ?></td>
                
                <td><?php echo $tot_pob_cnt;?></td>
                <td><?php echo $tot_recd_cnt;?></td>
                      
                <td><?php echo $tot_acp_cnt;?></td>
           		<td><?php echo $tot_rjct_cnt;?></td>
           		<td><?php echo $tot_recd_fa_cnt;?></td>
            	<td><?php echo $tot_pwlu_cnt;?></td>
            	<td><?php echo $tot_pwou_cnt;?></td>
            	<td><?php echo $tot_clb_xtra_cnt;?></td>
            	<td><?php echo $tot_cl_pend_cnt;?></td>
                <td><?php echo $tot_cl_pend_m2m_ct;?></td>
            	<td><?php echo $tot_cl_pend_2m_cnt;?></td>
            	<td><?php echo $tot_cl_pend_1m_cnt;?></td>
                <!--<td><?php //echo $tot_cl_pend_l1m_cnt;?></td>-->
			</tr>
			<tr>
            <td colspan="15" class="buttonTD"> 
            
            <input type="button" name="" id="dontprint1" value="Print" class="button" onClick="return printReportToPdf()" /> 
            
            <input type="hidden" name="hid" id="hid" />
            <input type="hidden" name="hid_yes" id="hid_yes" value="yes"/>
            <input type="hidden" name="frdate" id="frdate"  />
   		    <input type="hidden" name="todate" id="todate" />
    		<input type="hidden" name="dept" id="dept" />
            <input type="hidden" name="dept_name" id="dept_name" />
            <input type="hidden" name="dept_user_id" id="dept_user_id" />
            <input type="hidden" name="pet_own_heading" id="pet_own_heading" />
            <input type="hidden" name="src_id" id="src_id" />
    		<input type="hidden" name="sub_src_id" id="sub_src_id" />
            <input type="hidden" name="gtypeid" id="gtypeid" />
            <input type="hidden" name="gsubtypeid" id="gsubtypeid" />
            <input type="hidden" name="grie_dept_id" id="grie_dept_id" />
            <input type="hidden" name="off_cond_para" id="off_cond_para" />
            <input type="hidden" name="p_off_cond_para" id="p_off_cond_para" />  
			<input type="hidden" name="rep_src" id="rep_src" value='<?php echo $rep_src ?>'/>
			<input type="hidden" name="petition_processing_loc" id="petition_processing_loc" />
     		<input type="hidden" name="status" id="status" /> 
       		 
            </td></tr>
		<?php }  else {?>
         <table class="rptTbl" height="80" >
         <tr><td style="font-size:20px; text-align:center" colspan="2"><?PHP echo $label_name[30]; //No Records Found?>...</td>   </tr>
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
$rep_src=stripQuotes(killChars($_POST["rep_src"])); 
$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$dept_id=stripQuotes(killChars($_POST["dept"]));
$dept_name=stripQuotes(killChars($_POST["dept_name"]));
$status=stripQuotes(killChars($_POST["status"]));
$dept_user_id=stripQuotes(killChars($_POST["dept_user_id"]));
$pet_own_heading=stripQuotes(killChars($_POST["pet_own_heading"]));
$petition_processing_loc=stripQuotes(killChars($_POST["petition_processing_loc"]));
//petition_processing_loc
$src_id = stripQuotes(killChars($_POST["src_id"]));	  
$sub_src_id = stripQuotes(killChars($_POST["sub_src_id"]));	
$gtypeid = stripQuotes(killChars($_POST["gtypeid"]));	  
$gsubtypeid = stripQuotes(killChars($_POST["gsubtypeid"]));
$grie_dept_id=stripQuotes(killChars($_POST["grie_dept_id"]));
$off_cond_para=stripQuotes(killChars($_POST["off_cond_para"]));
$p_off_cond_para=stripQuotes(killChars($_POST["p_off_cond_para"]));

	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
			
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (v.dept_id=".$griedeptid.") ";
	}
	
	$src_condition = "";
	if(!empty($src_id)) {
		
		$src_condition = " and (v.source_id=".$src_id.")";	
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		
		$src_condition = " and (v.source_id=".$src_id." and v.subsource_id=".$sub_src_id.")";		
	}
	
	//Grev type and Grev Subtype Condition		
	
	$grev_condition = "";
	if(!empty($gtypeid)) {
		
		$grev_condition = " and (v.griev_type_id=".$gtypeid.")";	
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		
		$grev_condition = " and (v.griev_type_id=".$gtypeid." and v.griev_subtype_id=".$gsubtypeid.")";	
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
	}
	
	$p_off_condition = "";
	if(!empty($p_off_cond_para)){
		$p_off_cond_paras = explode("-", $p_off_cond_para);
		$p_off_condition = " and c.dept_id = ".$p_off_cond_paras[0]." and c.off_level_dept_id = ".$p_off_cond_paras[1]." and c.off_loc_id = ".$p_off_cond_paras[2]." ";
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
		//echo ">>>>>>>>>>>>>>>>>>>>>>>".$grie_dept_id;
		if ($grie_dept_id != "") {
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
//jrcoop_prmbl
   //echo $grev_condition;
$_SESSION["check"]="yes"; 
if($status=='pob')
	$cnt_type=" ".$label_name[41];//"Already Received Petitions";
else if($status=='recd')
	$cnt_type=" ".$label_name[6];//"Received - Petitions for Action";
else if($status=='acpt')
	$cnt_type=" ".$label_name[8];//" Petitions Accepted";
else if($status=='rjct')
	$cnt_type=" ".$label_name[9];//" Rejected Petitions";	
else if($status=='rb4acp')
	$cnt_type=" ".$label_name[34];//"Received before and Accepted - Petitions";
else if($status=='rb4_rjct')
	$cnt_type=" ".$label_name[35];//"Received before and Rejected - Petitions";
else if($status=='pfd')
	$cnt_type=" ".$label_name[36];//"Pending for Disposal - Petitions";
else if($status=='oth')
	$cnt_type=" ".$label_name[38];//"Received through Others - Petitions";
else if($status=='pending')
	$cnt_type=" ".$label_name[10];//" Pending Petitions";	
else if($status=='pm2')
	$cnt_type=" ".$label_name[11];//"Pending for 2 months - Petitions";
else if($status=='p2m')
	$cnt_type=" ".$label_name[12];//"Pending for < 2 months - Petitions"; 
else if($status=='pm1')
	$cnt_type=" ".$label_name[13];//"Pending for 1 month - Petitions";
//else if($status=='p1m')
	//$cnt_type="Pending for < 1 month - Petitions"; 

?>

<form name="rpt_abstract" id="rpt_abstract" enctype="multipart/form-data" method="post" action="" style="background-color:#F4CBCB;">
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
				<tr id="bak_btn"><th colspan="8" > 
<!--				<a href="rptdist_officerswise.php"><img src="images/bak.jpg" /></a> -->
				<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
				</th></tr>
                                
                <tr> 
				<th colspan="8" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
				</tr>
            
				<tr> 
				<th colspan="8" class="main_heading"><?PHP echo $label_name[0]." - ";?> <?php echo " ".$cnt_type; ?></th>
                </tr>
                <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="8" class="main_heading"><?php echo $reporttypename;?></th>
                </tr>
                <?php } ?>
                
				<?php if ($pet_own_heading != "") {?>  
					<tr> 
					<th colspan="8" class="main_heading"><?PHP echo $pet_own_heading; //Report type name?></th>
					</tr>
				<?php } ?>
				
				<?php if ($petition_processing_loc != "") {?>  
					<tr> 
					<th colspan="8" class="main_heading"><?PHP echo $petition_processing_loc; //Report type name?></th>
					</tr>
				<?php } ?>
				
                 <tr>
                <th colspan="8" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[1]; //From Date?>  
				<?php echo $from_date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[2]; //To Date?> : <?php echo $to_date; ?></th>
                </tr>
				<tr>
				<th><?PHP echo $label_name[20]; //S.No.?></th>
				<th><?PHP echo $label_name[21]; //Petition No. & Date?></th>
				<th><?PHP echo $label_name[22]; //Petitioner's communication address?></th>
				<th><?PHP echo $label_name[23]; //Source , Sub Source & Source Remarks?></th>				
				<th><?PHP echo $label_name[25]; //Grievance?></th>
				<th><?PHP echo $label_name[26]; //Grievance type & Address?></th>
				<th><?PHP echo $label_name[27]; //Action Type, Date & Remarks?></th>
                <th><?PHP echo $label_name[28]; //Pending Period?></th>
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
		 $cond1.="v.petition_date::date < '".$frm_dt."'::date";
		 $cond2.="v.action_entdt::date < '".$frm_dt."'::date";
         $cond3.="v.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
         $cond4.="v.action_entdt::date between '".$frm_dt."'::date and '".$to_dt."'::date";
         $cond5.="v.petition_date::date <= '".$to_dt."'::date";
         $cond6.="v.action_entdt::date <= '".$to_dt."'::date";  	  
	}

	if($status=='pob'){
		$sql=" -- pending: op. bal. 
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_ob_dt_from(('".$frm_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
inner join pet_master a on a.petition_id=b.petition_id
inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom  and c.dept_user_id=".$dept_user_id." )";
 	}
	
	else if($status=='recd'){	
		$sql="-- received within the period
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id
		from fn_pet_action_received_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id  =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id."
		where not exists (select * from fn_pet_action_pending_ob_dt_from(('".$frm_dt."'::date),  ".$_SESSION['USER_ID_PK'].") d where d.petition_id=b.petition_id))";
	}

	else if($status=='acpt'){	
		$sql="-- forwarded down
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id
		from fn_pet_action_received_ob_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
		inner join fn_pet_action_accepted_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id = ".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom  where c.dept_user_id =".$dept_user_id.")";
	}

	else if($status=='rjct'){	
		$sql="-- received for review
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		( select a.petition_id
		from fn_pet_action_received_ob_bw_dt_from(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
		inner join fn_pet_action_rejected_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id 
		and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom and c.dept_user_id =".$dept_user_id.")";
	}

	else if($status=='rb4acp'){
		$sql="-- returned up
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_with(('".$frm_dt."'::date)-1,  ".$_SESSION['USER_ID_PK'].") a
		inner join fn_pet_action_accepted_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and 
		b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby and c.dept_user_id =".$dept_user_id.")";
	 }

 	else if($status=='rb4_rjct'){
		$sql="-- accepted
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_with(('".$frm_dt."'::date)-1,  ".$_SESSION['USER_ID_PK'].") a
		inner join fn_pet_action_rejected_bw_dt_by(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") d on d.petition_id=a.petition_id
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby and c.dept_user_id =".$dept_user_id.")";
	}
	
	else if($status=='pfd'){
		$sql="-- rejected
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select  a.petition_id from fn_pet_action_pending_cb_dt_with('".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id
		and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.action_entby and c.dept_user_id =".$dept_user_id.")";
	 }
	 
 	else if($status=='oth') {	
		$sql="-- received for further action
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_with_others(('".$frm_dt."'::date), '".$to_dt."'::date,  ".$_SESSION['USER_ID_PK'].") a
		inner join pet_master b on b.petition_id=a.petition_id
		inner join fn_pet_action_first_office() b1 on b1.petition_id = b.petition_id and b1.dept_id =".$userProfile->getDept_id()." and b1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."
		and b1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom and c.dept_user_id =".$dept_user_id.")";
	}

 	
	else if($status=='pending')
	{
		$sql="-- Closing Balance
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id.")";
	}

	else if ($status=='pm2') 
	{
		$sql="-- pending for more than 2 months
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id." 
		and (case when (current_date -  a.petition_date::date) <= 30 then 1 else 0 end)=1 )";	
	}
	
	else if ($status == 'p2m') {
		$sql="-- pending for 2 months
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id." 
		and (case when ((current_date -  a.petition_date::date) > 30 and (current_date -  a.petition_date::date)<=60)  then 1 else 0 end)=1 )";	
	} 

	else if ($status == 'pm1') {
		$sql="-- pending for 1 month
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id." and (case when (current_date -  a.petition_date::date) > 60 then 1 else 0 end)=1 )";	
	} 

	/*
	else if ($status == 'p1m') {
		$sql="-- pending for less than 1 month
		select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, v.subsource_remarks, v.grievance, v.griev_type_id,v.griev_type_name, v.griev_subtype_name, v.pet_address, v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period 
		from vw_petition_details v 
		where v.petition_id in 
		
		(select a.petition_id from fn_pet_action_pending_cb_dt_from(('".$to_dt."'::date),  ".$_SESSION['USER_ID_PK'].") b
		inner join pet_master a on a.petition_id=b.petition_id
		inner join fn_pet_action_first_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()." and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()." 
		and a1.off_loc_id =  ".$userProfile->getOff_loc_id()." 
		inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom and c.dept_user_id =".$dept_user_id." and (case when date_part('month',age(current_date, a.petition_date::date)) < 1 then 1 else 0 end)=1 )";	
	}
	*/

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
			<td class="desc" style="width:13%;"> <a href="" onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."&nbsp;"."&amp;"."<br/>".$row['petition_date']; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?><?php echo '& '.$row['subsource_remarks'];?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:20%;white-space: normal;"> <?PHP echo $row['grievance'] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$row['gri_address']; ?></td>
            
<td class="desc" style="width:24%;"> 
<?PHP 
if($row['action_type_name']!="") {
	echo "PETITION STATUS: ".$row['action_type_name']. " on ".$row['fwd_date'].".<br>REMARKS: ".$row['fwd_remarks']."<br>PETITION IS WITH: ".($row['off_location_design'] != "" ? $row['off_location_design'] : "---"); 
}?>
</td>
            
<!--			<td class="desc"> <?PHP //if($row['action_type_name']!="") { echo $row[action_type_name].			",".$row[fwd_date]."&".$row[fwd_remarks].":"."&nbsp;".ucfirst(strtolower($row[off_location_design])); }?></td>-->
            <td class="desc" style="width:3%;"> <?PHP echo ucfirst(strtolower($row['pend_period'])); ?></td>
			</tr>
<?php $i++; } ?> 
			<tr>
			<td colspan="8" class="buttonTD">
			<input type="button" name="" id="dontprint1" value="<?PHP echo $label_name[29]; //Print?>" class="button" onClick="return printReportToPdf()">
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
