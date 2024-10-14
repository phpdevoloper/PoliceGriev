<?php
ob_start();
session_start();
$pagetitle="Officers Source wise Pendency Report";
include("db.php");
include("header_report.php");
include("header_menu_report.php");
include("common_date_fun.php");
include("pm_common_js_css.php");
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
$pagetitle="Officers source wise Report";

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
	document.rpt_abstract.action="rptdist_officers_sourcewise.php";
	document.rpt_abstract.target= "_blank";
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
	$petition_type = stripQuotes(killChars($_POST["petition_type"]));
	$disp_officer_name=stripQuotes(killChars($_POST["disp_officer_name"]));

			
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
	
	$disp_officer_title = '';
	if ($disp_officer_name != '') {
		$disp_officer_title = 'Disposing Officer :&nbsp;&nbsp;'.$disp_officer_name;
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
		
	
	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
	
		
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " (b.dept_id=".$griedeptid.") ";
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

?>
<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">	
		<table class="rptTbl">
			<thead>
          	<tr id="bak_btn"><th colspan="17" >
			<a href="" onclick="self.close();"><img src="images/bak.jpg" /></a>
			</th></tr>
            <tr> 
				<th colspan="17" class="main_heading"><?PHP echo $userProfile->getOff_level_name()." - ". $userProfile->getOff_loc_name() //Department wise Report?></th>
			</tr>
            <tr> 
				<th colspan="17" class="main_heading"><?PHP echo $label_name[56]; //Officers wise Report?></th>
			</tr>
            
		<?php if ($disp_officer_title != '') { ?>
		<tr><th colspan="17" class="sub_heading"><?php echo $disp_officer_title;?></th></tr>
		<?php } ?>
		
            <?php if ($reporttypename != "") {?>
            <tr> 
				<th colspan="17 class="search_desc"><?PHP echo $reporttypename; //Report type name?></th>
			</tr>
            <?php } ?>
            
			
			<?php if ($petition_processing_loc != "") {?>
				<tr> 
				<th colspan="17" class="main_heading"><?PHP echo $petition_processing_loc; //Report type name?></th>
			</tr>
			<?php } ?>
			
			<tr> 
				<th colspan="17" class="search_desc"><?PHP echo $label_name[57]; //As On?> &nbsp;&nbsp;&nbsp; <?php echo $from_date; ?> - <?php echo $to_date; ?>	</th>
			</tr>
			

				<tr>
                <tr> 
					<th rowspan="2" width="3%"><?PHP echo $label_name[3]; //S.No.?></th>
					<th rowspan="2" width="31%"><?PHP echo $label_name[40]; //S.No.?></th>
					<th colspan="15" style="width: 70%;color:#F5F5F5;font-size:16px; font-weight: bold;"><?PHP echo $label_name[58]; //No. of Petitions?></th>
				</tr>
				<tr>
					 <th width="6%"><?PHP echo $label_name[59]; //Total?> (A)</th>	
					 <th width="6%"><?PHP echo $label_name[60]; //Total?> (B)</th>	
					 <th width="6%"><?PHP echo $label_name[42]; //Monday?><br> (C)</th>
					 <th width="6%"><?PHP echo $label_name[80]; //Collector Special Divisional Level GDP?> (D)</th>	
					 <th width="6%"><?PHP echo $label_name[53]; //Collectorate Petition?> (E)</th>
					 <th width="6%"><?PHP echo $label_name[49]; //Collectorate Chamber?> (F)</th>
					 <th width="6%"><?PHP echo $label_name[50]; //MCP - Collector?> (G)</th>
					 <th width="6%"><?PHP echo $label_name[51]; //MCP - DRO?> (H)</th>
					 <th width="6%"><?PHP echo $label_name[52]; //MCP - RDO?> (I)</th>
					 <th width="6%"><?PHP echo $label_name[44]; //Jamabandhi?> (J)</th>
					 <th width="6%"><?PHP echo $label_name[48]; //Amma Thittam?> (K)</th>
					 <th width="6%"><?PHP echo $label_name[54]; //Agri. GDP?> (L)</th>				 
					 <th width="6%"><?PHP echo $label_name[79]; //Differently Abled People GDP?> (M)</th>				 
					 <th width="6%"><?PHP echo $label_name[43]; //ER?> (N)</th>
					 <th width="6%"><?PHP echo $label_name[70]; //Others?> (O)</th>
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
			
 	
	$dept_cond='';

	$proxy_profile = false;
	$disposing_officer = stripQuotes(killChars($_POST["disposing_officer"]));
	if ($disposing_officer != "") {
	
	$sql = "SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
			pet_accept, pet_forward, pet_act_ret, pet_disposal,  
			desig_coordinating, s_dept_desig_id,

			dept_id, dept_name, dept_tname, dept_pet_process, 
			off_level_pattern_id, off_level_pattern_name, off_level_pattern_tname, dept_coordinating,

			off_level_dept_id, off_level_dept_name,off_level_dept_tname, 
			off_pet_process, off_coordinating,
			off_level_id, off_level_name, off_level_tname, 

			off_loc_id, off_loc_name, off_loc_tname, sup_off_loc_id1, 
			sup_off_loc_id2, off_hier, 
			off_hier[1] AS state_id, off_hier[2] AS district_id, off_hier[3] AS rdo_id, 
			off_hier[4] AS taluk_id, off_hier[5] AS firka_id, off_hier[6] AS block_id, off_hier[7] AS lb_urban_id, 		                    
			off_hier[8] AS rev_village_id, off_hier[10] AS division_id, off_hier[11] AS subdivision_id, 
			off_hier[12] AS circle_id, off_hier[13] AS subcircle_id, off_hier[14] AS unit_id,

			user_name, off_desig_emp_name, off_desig_emp_tname,
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
		$userProfile->setDept_coordinating($rowArr['dept_coordinating']);
		$userProfile->setOff_loc_id($rowArr['off_loc_id']);
		$userProfile->setOff_hier($rowArr['off_hier']);	
		$userProfile->setState_id($rowArr['state_id']);
		$userProfile->setDistrict_id($rowArr['district_id']);
		$proxy_profile = true;
	} else {
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
	}	
			
			
	
	if(!$userProfile->getDept_coordinating()&& !$userProfile->getOff_coordinating() && !$userProfile->getDesig_coordinating())
	{		
		$dept_cond.=" and b1.dept_id=".$userProfile->getDept_id()." ";
	}  
	
			 
	$usr_cond="";
	if($user_id!="")
		$usr_cond=$user_id;
	else
	{
		$usr_cond=" SELECT dept_user_id FROM vw_usr_dept_users_v_sup WHERE dept_id=".$dept." and 
		off_level_dept_id=".$off_level_dept_id." and off_loc_id=".$off_loc_id."";
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
		$grev_dept_condition = " and (c.dept_id=".$griedeptid.") ";
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


	$sql="With off_pet as
			(
			select a.petition_id, b.petition_date, b.source_id, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id
			from fn_pet_action_first_last_received_from(".$userProfile->getDept_user_id().") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom 
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
			".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition." 
			)

		select t1.district_id, t1.district_name, t2.dept_id, t2.dept_name, t3.off_level_dept_id, t3.off_level_dept_name, t3.dept_desig_id, 
		t3.dept_desig_name,t3.off_loc_id, t3.off_loc_name, t3.dept_user_id, COALESCE(rcd.recd_cnt,0) recd_cnt, ct2.tot as tot, 
		ct2.s1 as s1,ct2.s10 as s10, ct2.s4 as s4,ct2.s3 as s3, ct2.s13 as s13, ct2.s14 as s14, ct2.s5 as s5, ct2.s8 as s8, ct2.s11 as s11, ct2.s2 as s2,ct2.s19 as s19,ct2.s26 as s26,ct2.others as others

		from 

		(select dept_user_id, 
		sum(case when source_id=1 then cl_pend_cnt else 0 end) as s1, -- Monday 
		sum(case when source_id=10 then cl_pend_cnt else 0 end) as s10, -- Collectorate Petition 
		sum(case when source_id=4 then cl_pend_cnt else 0 end) as s4, -- Collectorate Chamber 
		sum(case when source_id=3 then cl_pend_cnt else 0 end) as s3, -- MCP - Collector 
		sum(case when source_id=13 then cl_pend_cnt else 0 end) as s13, -- MCP - DRO 
		sum(case when source_id=14 then cl_pend_cnt else 0 end) as s14, -- MCP - RDO 
		sum(case when source_id=5 then cl_pend_cnt else 0 end) as s5, -- Jamabandhi 
		sum(case when source_id=8 then cl_pend_cnt else 0 end) as s8, -- Amma Thittam 
		sum(case when source_id=11 then cl_pend_cnt else 0 end) as s11, -- Agri. GDP 
		sum(case when source_id=2 then cl_pend_cnt else 0 end) as s2, -- ER 
		sum(case when source_id=19 then cl_pend_cnt else 0 end) as s19, -- Differently abled GDP 
		sum(case when source_id=26 then cl_pend_cnt else 0 end) as s26, -- Collector Special Divisional Level GDP 
		sum(case when source_id not in (1,10,4,3,13,14,5,8,11,2,19,26) then cl_pend_cnt else 0 end) as others, -- Others 
		sum(cl_pend_cnt) as tot 
		
		from (select dept_user_id, source_id, cl_pend_cnt 

		from 

		( select cc.dept_user_id, clb.source_id, COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt 

		from fn_usr_dept_users_vhr(2,".$userProfile->getOff_loc_id().",null,'{2,3,4,6,7,10,11}') cc -- 17 district from user profile 

		left join 

		( select griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, source_id, count(*) as cl_pend_cnt 
		from off_pet a
		where not exists (select * from pet_action d1 where d1.petition_id=a.petition_id and action_type_code in ('A','R')) and not exists (select * from fn_pet_action_first_last_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
		group by griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, source_id ) clb on clb.griev_district_id=".$userProfile->getDistrict_id()." and clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id 
		where cc.pet_act_ret = true and cc.dept_desig_id <> ".$userProfile->getDept_desig_id()." -- griev_district_id=17 user profile 
		) b_rpt 

		) ct1 
		group by dept_user_id) ct2

		cross join fn_single_district(".$userProfile->getDistrict_id().") t1 -- 17 district from user profile 
		inner join vw_usr_dept_users_v t3 on t3.dept_user_id = ct2.dept_user_id 
		inner join usr_dept t2 on t3.dept_id=t2.dept_id 

		left join

		( select griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as recd_cnt 
		from off_pet a 
		group by griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id) rcd on rcd.griev_district_id=".$userProfile->getDistrict_id()." and rcd.dept_id=t2.dept_id and rcd.off_level_dept_id=t3.off_level_dept_id and rcd.off_loc_id=t3.off_loc_id and rcd.dept_desig_id=t3.dept_desig_id and rcd.dept_user_id=t3.dept_user_id

		where (rcd.recd_cnt + ct2.tot) > 0
		order by district_id, t2.dept_id, off_level_dept_id, dept_desig_id, off_loc_name";

$sql="With off_pet as
			(
			select a.petition_id, b.petition_date, b.source_id, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id
			from fn_pet_action_first_last_received_from(".$userProfile->getDept_user_id().") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom 
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date
			".$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition." 
			)

		select t1.district_id, t1.district_name, t2.dept_id, t2.dept_name, t3.off_level_dept_id, t3.off_level_dept_name, t3.dept_desig_id, 
		t3.dept_desig_name,t3.off_loc_id, t3.off_loc_name, t3.dept_user_id, COALESCE(rcd.recd_cnt,0) recd_cnt, ct2.tot as tot, 
		ct2.s1 as s1,ct2.s10 as s10, ct2.s4 as s4,ct2.s3 as s3, ct2.s13 as s13, ct2.s14 as s14, ct2.s5 as s5, ct2.s8 as s8, ct2.s11 as s11, ct2.s2 as s2,ct2.s19 as s19,ct2.s26 as s26,ct2.others as others

		from 

		(select dept_user_id, 
		sum(case when source_id=1 then cl_pend_cnt else 0 end) as s1, -- Monday 
		sum(case when source_id=10 then cl_pend_cnt else 0 end) as s10, -- Collectorate Petition 
		sum(case when source_id=4 then cl_pend_cnt else 0 end) as s4, -- Collectorate Chamber 
		sum(case when source_id=3 then cl_pend_cnt else 0 end) as s3, -- MCP - Collector 
		sum(case when source_id=13 then cl_pend_cnt else 0 end) as s13, -- MCP - DRO 
		sum(case when source_id=14 then cl_pend_cnt else 0 end) as s14, -- MCP - RDO 
		sum(case when source_id=5 then cl_pend_cnt else 0 end) as s5, -- Jamabandhi 
		sum(case when source_id=8 then cl_pend_cnt else 0 end) as s8, -- Amma Thittam 
		sum(case when source_id=11 then cl_pend_cnt else 0 end) as s11, -- Agri. GDP 
		sum(case when source_id=2 then cl_pend_cnt else 0 end) as s2, -- ER 
		sum(case when source_id=19 then cl_pend_cnt else 0 end) as s19, -- Differently abled GDP 
		sum(case when source_id=26 then cl_pend_cnt else 0 end) as s26, -- Collector Special Divisional Level GDP 
		sum(case when source_id not in (1,10,4,3,13,14,5,8,11,2,19,26) then cl_pend_cnt else 0 end) as others, -- Others 
		sum(cl_pend_cnt) as tot 
		
		from (select dept_user_id, source_id, cl_pend_cnt 

		from 

		( select cc.dept_user_id, clb.source_id, COALESCE(clb.cl_pend_cnt,0) as cl_pend_cnt 

		--from fn_usr_dept_users_vhr(2,".$userProfile->getOff_loc_id().",null,'{2,3,4,6,7,10,11}') cc -- 17 district from user profile 

		from (select district_id,district_name from mst_p_district where district_id=".$userProfile->getDistrict_id().") aa 
		cross join usr_dept bb 
		inner join vw_usr_dept_users_v cc on cc.dept_id = bb.dept_id 
		and cc.pet_act_ret = true  
	
		left join 

		( select griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, source_id, count(*) as cl_pend_cnt 
		from off_pet a
		where not exists (select * from pet_action d1 where d1.petition_id=a.petition_id and action_type_code in ('A','R')) and not exists (select * from fn_pet_action_first_last_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)
		group by griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, source_id ) clb on clb.griev_district_id=".$userProfile->getDistrict_id()." and clb.dept_id=cc.dept_id and clb.off_level_dept_id=cc.off_level_dept_id and clb.off_loc_id=cc.off_loc_id and clb.dept_desig_id=cc.dept_desig_id and clb.dept_user_id=cc.dept_user_id 
		where cc.pet_act_ret = true 
		--and cc.dept_desig_id <> ".$userProfile->getDept_desig_id()." 
		-- griev_district_id=17 user profile 
		) b_rpt 

		) ct1 
		group by dept_user_id) ct2

		cross join fn_single_district(".$userProfile->getDistrict_id().") t1 -- 17 district from user profile 
		inner join vw_usr_dept_users_v t3 on t3.dept_user_id = ct2.dept_user_id 
		inner join usr_dept t2 on t3.dept_id=t2.dept_id 

		left join

		( select griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id, count(*) as recd_cnt 
		from off_pet a 
		group by griev_district_id, dept_id, off_level_dept_id, off_loc_id, dept_desig_id, dept_user_id) rcd on rcd.griev_district_id=".$userProfile->getDistrict_id()." and rcd.dept_id=t2.dept_id and rcd.off_level_dept_id=t3.off_level_dept_id and rcd.off_loc_id=t3.off_loc_id and rcd.dept_desig_id=t3.dept_desig_id and rcd.dept_user_id=t3.dept_user_id

		where (rcd.recd_cnt + ct2.tot) > 0
		order by district_id, t2.dept_id, off_level_dept_id, dept_desig_id, off_loc_name";

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
	
				
			$tot_rcvd = $row['recd_cnt'];
			$tot_pending = $row['tot'];
			$monday_petition = $row['s1'];
			$collectorate_petition = $row['s10'];
			$coll_chamber = $row['s4'];
			$mcp_collector = $row['s3'];
			$mcp_dro =  $row['s13'];
			$mcp_rdo =  $row['s14'];
			$jamabandhy =  $row['s5'];
			$amma_thittam =  $row['s8'];
			$agri_gdp = $row['s11'];			
			$diff_gdp = $row['s19'];			
			$coll_gdp = $row['s26'];			
			$elec_rep = $row['s2'];
			$others = $row['others'];
			
			if($temp_dept_id!=$dept_id) 
			{
				$temp_dept_id=$dept_id;
	 
			?>
			
           <tr>
           		<td class="h1" style="text-align:left" colspan="17"><?PHP echo $label_name[33].": ".$dept_name; ?></td>
           </tr>

           <?php 
			
				$j++;
			 	$i=1;
			} ?>

			<tr>
                <td><?php echo $i;?></td>
                
				
                <td class="desc"><?PHP echo $dept_desig; ?></td> 
				
				<td><?PHP echo $tot_rcvd; ?></td>

				<?php if($tot_pending!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "0"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $tot_pending;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $tot_pending;?> </td> <?php } ?>  
				
				<!-- 1 Monday Petition-->  
                 <?php if($monday_petition!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "1"; ?>',
						'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>',
						'<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>',
						'<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>',
						'<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>',
						'<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $monday_petition;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $monday_petition;?> </td> <?php } ?>
				
				<!-- 14 Collector Special GDP -->
                  <?php if($coll_gdp!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "26"; ?>' ,'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $coll_gdp;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $coll_gdp;?> </td> <?php } ?>
				
                
                 <!-- 2 Collectorate Petition-->
                 <?php if($collectorate_petition!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "10"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $collectorate_petition;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $collectorate_petition;?> </td> <?php } ?>

                 <!-- 3 Collector Chamber / Camp - Direct Petition -->
                 <?php if($coll_chamber!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
						'<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "4"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $coll_chamber;?> </a></td>  
			  	 <?php } else {?>
				<td><?php echo $coll_chamber;?> </td> <?php } ?>
                                
               <!-- 4 MCP - Collector -->
                <?php if($mcp_collector>0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "3"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $mcp_collector;?> </a></td>
			  	 <?php } 
				 else {?>
				<td><?php echo $mcp_collector;?> </td> <?php } ?>
                
                <!-- 5 MCP - DRO -->
                 <?php if($mcp_dro!=0) {?>
			<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
            '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "13"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $mcp_dro;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $mcp_dro;?> </td> <?php } ?>
                 
                 <!-- 6 MCP - RDO -->
                 <?php if($mcp_rdo!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "14"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $mcp_rdo;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $mcp_rdo;?> </td> <?php } ?>
                
                <!-- 7 Jamabanthy -->
                 <?php if($jamabandhy!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "5"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $jamabandhy;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $jamabandhy;?> </td> <?php } ?>

                <!-- 8 AMMA Thittam -->
                 <?php if($amma_thittam!=0) {?>
						<td><a href=""  onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "8"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $amma_thittam;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $amma_thittam;?> </td> <?php } ?>
				
                                                 
               
                <!-- 12 Agriculture GDP -->
                  <?php if($agri_gdp!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "11"; ?>' ,'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $agri_gdp;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $agri_gdp;?> </td> <?php } ?>
				
				<!-- 13 Diff GDP -->
                  <?php if($diff_gdp!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "19"; ?>' ,'<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $diff_gdp;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $diff_gdp;?> </td> <?php } ?>
				
						
				
                <!-- 15 Elected Representative  -->
                 <?php if($elec_rep!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "2"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $elec_rep;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $elec_rep;?> </td> <?php } ?>
                
				 <!-- Others  -->
                 <?php if($others!=0) {?>
						<td><a href="" onclick="return detail_view('<?php echo $from_date; ?>', '<?php echo $to_date ; ?>',
                        '<?php echo $dept_id; ?>','<?php echo $dept_name; ?>','<?php echo $dept_user_id; ?>','<?php echo "others"; ?>','<?php echo $src_id; ?>','<?php echo $sub_src_id; ?>','<?php echo $gtypeid; ?>','<?php echo $gsubtypeid; ?>','<?php echo $grie_dept_id; ?>','<?php echo $off_cond_para; ?>','<?php echo $p_off_cond_para; ?>','<?php echo $pet_own_heading; ?>','<?php echo $petition_processing_loc; ?>')"><?php echo $others;?> </a></td>
			  	 <?php } else {?>
				<td><?php echo $others;?> </td> <?php } ?>
				
                <!-- 14 Total -->
                               
                 
			</tr>
			<?php  
			$i++;

			$tot_rcvd_cnt = $tot_rcvd_cnt + $tot_rcvd;
			$tot_tot_pending = $tot_tot_pending + $tot_pending;
			$tot_monday_petition = $tot_monday_petition + $monday_petition;
			$tot_collectorate_petition = $tot_collectorate_petition + $collectorate_petition;
			$tot_coll_chamber = $tot_coll_chamber + $coll_chamber;
			$tot_mcp_collector = $tot_mcp_collector + $mcp_collector;
			$tot_mcp_dro = $tot_mcp_dro + $mcp_dro;
			$tot_mcp_rdo = $tot_mcp_rdo + $mcp_rdo;
			$tot_jamabandhy = $tot_jamabandhy + $jamabandhy;
			$tot_amma_thittam = $tot_amma_thittam + $amma_thittam;
			$tot_agri_gdp = $tot_agri_gdp + $agri_gdp;
			$tot_diff_gdp = $tot_diff_gdp + $diff_gdp;
			$tot_coll_gdp = $tot_coll_gdp + $coll_gdp;
			$tot_elec_rep =  $tot_elec_rep + $elec_rep;
			$tot_others =  $tot_others + $others;
			
			}
			?>
			<tr class="totalTR">
                <td colspan="2"><?PHP echo 'Total' ?></td>
                <td><?php echo $tot_rcvd_cnt;?></td>
				<td><?php echo $tot_tot_pending;?></td>
                <td><?php echo $tot_monday_petition;?></td>
				<td><?php echo $tot_coll_gdp;?></td>
                <td><?php echo $tot_collectorate_petition;?></td>                      
                <td><?php echo $tot_coll_chamber;?></td>
           		<td><?php echo $tot_mcp_collector;?></td>
           		<td><?php echo $tot_mcp_dro;?></td>
            	<td><?php echo $tot_mcp_rdo;?></td>
            	<td><?php echo $tot_jamabandhy;?></td>
            	<td><?php echo $tot_amma_thittam;?></td>
            	<td><?php echo $tot_agri_gdp;?></td>
            	<td><?php echo $tot_diff_gdp;?></td>
            	<td><?php echo $tot_elec_rep;?></td>
            	<td><?php echo $tot_others;?></td>

			</tr>
			<tr>
            <td colspan="17" class="buttonTD"> 
            
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
	<input type="hidden" name="disp_officer_title" id="disp_officer_title" value='<?php echo $disp_officer_title ?>'/>

			<input type="hidden" name="petition_type" id="petition_type" value="<?php echo $petition_type; ?>"/> 
		<input type="hidden" name="reporttypename" id="reporttypename" value="<?php echo $reporttypename; ?>"/> 
		<input type="hidden" name="session_user_id" id="session_user_id" value="<?php echo $_SESSION['USER_ID_PK']; ?>"/> 	


	<input type="hidden" name="h_dept_user_id" id="h_dept_user_id" value="<?php echo $userProfile->getDept_user_id(); ?>"/>
	<input type="hidden" name="h_off_level_dept_id" id="h_off_level_dept_id" value="<?php echo $userProfile->getOff_level_dept_id(); ?>"/>
	<input type="hidden" name="h_dept_id" id="h_dept_id" value="<?php echo $userProfile->getDept_id(); ?>"/>
	<input type="hidden" name="h_off_loc_id" id="h_off_loc_id" value="<?php echo $userProfile->getOff_loc_id(); ?>"/>			
			
 <input type="hidden" name="h_Dept_coordinating" id="h_Dept_coordinating" value="<?php echo $userProfile->getDept_coordinating(); ?>"/>	
 <input type="hidden" name="h_Off_coordinating" id="h_Off_coordinating" value="<?php echo $userProfile->getOff_coordinating(); ?>"/>	
 <input type="hidden" name="h_Desig_coordinating" id="h_Desig_coordinating" value="<?php echo $userProfile->getDesig_coordinating(); ?>"/>
 
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
$session_user_id=stripQuotes(killChars($_POST["session_user_id"]));
if($_SESSION['USER_ID_PK'] != $session_user_id) {
   echo "<script> alert('Your session is expired !!');self.close();</script>";	
   exit;
}
$rep_src=stripQuotes(killChars($_POST["rep_src"])); 
$from_date=stripQuotes(killChars($_POST["frdate"])); 
$_SESSION["from_date"]=$from_date;
$to_date=stripQuotes(killChars($_POST["todate"]));
$_SESSION["to_date"]=$to_date; 
$dept_id=stripQuotes(killChars($_POST["dept"]));
$dept_name=stripQuotes(killChars($_POST["dept_name"]));

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
$petition_type=stripQuotes(killChars($_POST["petition_type"]));
$reporttypename=stripQuotes(killChars($_POST["reporttypename"]));


$h_dept_user_id=stripQuotes(killChars($_POST["h_dept_user_id"]));
$h_off_level_dept_id=stripQuotes(killChars($_POST["h_off_level_dept_id"]));
$h_dept_id=stripQuotes(killChars($_POST["h_dept_id"]));
$h_off_loc_id=stripQuotes(killChars($_POST["h_off_loc_id"]));

$h_Dept_coordinating=stripQuotes(killChars($_POST["h_Dept_coordinating"]));
$h_Off_coordinating=stripQuotes(killChars($_POST["h_Off_coordinating"]));
$h_Desig_coordinating=stripQuotes(killChars($_POST["h_Desig_coordinating"]));

$source_id=stripQuotes(killChars($_POST["status"]));
$disp_officer_title=stripQuotes(killChars($_POST["disp_officer_title"]));

	if ($grie_dept_id != "") {
		$griedept_id = explode("-", $grie_dept_id);
		$griedeptid = $griedept_id[0];
		$griedeptpattern = $griedept_id[1];
	}
			
	$grev_dept_condition = "";
	if(!empty($grie_dept_id)) {
		$grev_dept_condition = " and (c.dept_id=".$griedeptid.") ";
	}
	
	$grev_condition = "";
	if(!empty($gtypeid)) {
		
		$grev_condition = " and (b.griev_type_id=".$gtypeid.")";	
	}
	if (!empty($gtypeid)&& !empty($gsubtypeid)) {
		
		$grev_condition = " and (b.griev_type_id=".$gtypeid." and b.griev_subtype_id=".$gsubtypeid.")";	
	}
	
	$src_condition = "";
	if(!empty($src_id)) {
		
		$src_condition = " and (b.source_id=".$src_id.")";	
	}
	if (!empty($src_id)&& !empty($sub_src_id)) {
		
		$src_condition = " and (b.source_id=".$src_id." and b.subsource_id=".$sub_src_id.")";		
	}
	
	//Grev type and Grev Subtype Condition		
	$petition_type_condition = "";
	if(!empty($petition_type)) {
		
		$petition_type_condition = " and (b.pet_type_id=".$petition_type.")";	
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
				<th colspan="8" class="main_heading"><?PHP echo $label_name[56];?> 
                </tr>
				
				<?php if($disp_officer_title!="") { ?>
                <tr>
                <th colspan="8" class="search_desc"><?php echo $disp_officer_title;?></th>
                </tr>
                <?php } ?>
                <?php if($reporttypename!="") { ?>
                <tr>
                <th colspan="8" class="search_desc"><?php echo $reporttypename;?></th>
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
                <th colspan="8" class="search_desc">&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[57]; //From Date?>  
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $from_date; ?> - <?php echo $to_date; ?></th>
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
         $cond3.="a.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
	}

	$condition_src='';
	if ($source_id != '0') {
			$condition_src=" and a.source_id=".$source_id."";
	}
	if ($source_id == 'others') {
			$condition_src=" and a.source_id not in (1,10,4,3,13,14,5,8,11,2) ";
	}

	$sql=" select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, 
	v.subsource_remarks,v.grievance, v.griev_type_id,v.griev_type_name,v.griev_subtype_name, v.pet_address, 
	v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period , v.pet_type_name
	from fn_petition_details (array(select a.petition_id from fn_pet_action_first_last_pending_cb_from(".$_SESSION['USER_ID_PK'].") b 
	inner join pet_master a on a.petition_id=b.petition_id 
	inner join fn_pet_action_first_last_office() a1 on a1.petition_id = a.petition_id and a1.dept_id =".$userProfile->getDept_id()."
	and a1.off_level_dept_id =".$userProfile->getOff_level_dept_id()."  and a1.off_loc_id = ".$userProfile->getOff_loc_id()."  
	inner join vw_usr_dept_users_v c on c.dept_user_id = b.to_whom where ".$cond3." and b.to_whom=".$dept_user_id."".$condition_src.")) v"  ;

  	if(!empty($from_date) && !empty($to_date) ){
         $cond3="b.petition_date::date between '".$frm_dt."'::date and '".$to_dt."'::date"; 
	}

	$condition_src='';
	if ($source_id != '0') {
			$condition_src=" and b.source_id=".$source_id."";
	}
	if ($source_id == 'others') {
			$condition_src=" and b.source_id not in (1,10,4,3,13,14,5,8,11,2,19,26) ";
	}
	$sub_sql="With off_pet as
			(
			select a.petition_id, b.petition_date, b.source_id, b.griev_district_id, c.dept_id, c.off_level_dept_id, c.off_loc_id, c.dept_desig_id, c.dept_user_id
			from fn_pet_action_first_last_received_from(".$h_dept_user_id.") a 
			inner join pet_master b on b.petition_id=a.petition_id 
			-- inner join fn_pet_action_first_last_office() b1 on b1.petition_id = b.petition_id and b1.dept_id = ".$h_dept_id." and b1.off_level_dept_id = ".$h_off_level_dept_id." and b1.off_loc_id = ".$h_off_loc_id." 
			inner join vw_usr_dept_users_v c on c.dept_user_id = a.to_whom 
			where ".$cond3." and a.to_whom=".$dept_user_id.$condition_src.$src_condition.$petition_type_condition.$grev_dept_condition.$grev_condition." 
			)
			select a.petition_id from off_pet a
			where not exists (select * from pet_action d1 where d1.petition_id=a.petition_id and action_type_code in ('A','R')) and not exists (select * from fn_pet_action_first_last_cb_with(".$userProfile->getDept_user_id().") d2 where d2.petition_id=a.petition_id)";

	$sql=" select v.petition_no, v.petition_id, v.petition_date, v.source_name,v.subsource_name, 
	v.subsource_remarks,v.grievance, v.griev_type_id,v.griev_type_name,v.griev_subtype_name, v.pet_address, 
	v.gri_address, v.griev_district_id, v.fwd_remarks, v.action_type_name, v.fwd_date, v.off_location_design, v.pend_period,v.pet_type_name 
	from fn_petition_details(array(".$sub_sql.")) v order by v.petition_id asc"  ;

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
			if (!empty($row['subsource_remarks'])) {
				$source_details .= ' & '.$row['subsource_remarks'];
			}
			?>
			<tr>
			<td style="width:3%;"><?php echo $i;?></td>
			<td class="desc" style="width:14%;"> <a href="" onclick="return petition_status('<?php echo $row['petition_id']; ?>')">
			<?PHP  echo $row['petition_no']."<br>Dt.&nbsp;".$row['petition_date']; ?></a></td>
			<td class="desc" style="width:15%;"> <?PHP echo $row['pet_address'] //ucfirst(strtolower($row[pet_address])); ?></td>
			<td class="desc" style="width:10%;"> <?PHP echo $source_details; ?></td>
			<!--td class="desc"><?php //echo $row[subsource_remarks];?></td-->
			<!--td class="desc"><?php //echo ucfirst(strtolower($row[subsource_remarks]));?></td-->
			<td class="desc wrapword" style="width:19%;white-space: normal;"> <?PHP echo $row[grievance] //ucfirst(strtolower($row[grievance])); ?></td> 
			<td class="desc" style="width:12%;"> <?PHP echo $row['griev_type_name'].",".$row['griev_subtype_name']."&nbsp;"."<br>Address: ".$row['gri_address']."<br>".$row['pet_type_name']; ?></td>
            
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
