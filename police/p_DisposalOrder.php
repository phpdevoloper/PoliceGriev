<?php
ob_start();
session_start();

include("db.php");
include("header_menu.php");
include("menu_home.php");
include("common_date_fun.php");
include("pm_common_js_css.php");
	$userId = $userProfile->getDept_user_id();
	
	$sql="SELECT dept_user_id, user_name, dept_desig_name,  dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_level_name, off_level_tname, district_id,  district_name, district_tname,rdo_id,  rdo_name, rdo_tname, taluk_id,  taluk_name, taluk_tname,  block_id,  block_name, block_tname,  firka_id, firka_name, firka_tname,  lb_urban_id, lb_urban_name, lb_urban_tname,  rev_village_id, rev_village_name, rev_village_tname, lb_village_id,  lb_village_name, lb_village_tname,division_id, division_name, division_tname, subdivision_id, subdivision_name, subdivision_tname, circle_id, circle_name, circle_tname                       
	FROM vw_usr_dept_users where dept_user_id=".$userId;
	
	$rs = $db->query($sql);
	$rowarr = $rs->fetchall(PDO::FETCH_ASSOC);
	foreach($rowarr as $off_row) {
		$dept_desig_name = $off_row['dept_desig_tname'];
		$off_level_name = $off_row['off_level_dept_tname'];
		if ($off_row['district_id'] != '') {
			$off_loc_name = $off_row['district_tname'];
		} else if ($off_row['rdo_id'] != '') {
			$off_loc_name = $off_row['rdo_tname'];
		} else if ($off_row['taluk_id'] != '') {
			$off_loc_name = $off_row['taluk_tname'];
		} else if ($off_row['block_id'] != '') {
			$off_loc_name = $off_row['block_tname'];
		} else if ($off_row['firka_id'] != '') {
			$off_loc_name = $off_row['firka_tname'];
		} else if ($off_row['lb_urban_id'] != '') {
			$off_loc_name = $off_row['lb_urban_tname'];
		} else if ($off_row['rev_village_id'] != '') {
			$off_loc_name = $off_row['rev_village_tname'];
		} else if ($off_row['lb_village_id'] != '') {
			 $off_loc_name = $off_row['lb_village_tname'];
		} else if ($off_row['division_id'] != '') {
			$off_loc_name = $off_row['division_tname'];
		} else if ($off_row[''subdivision_id''] != '') {
			$off_loc_name = $off_row[''subdivision_tname''];
		} else if ($off_row[''circle_id''] != '') {
			$off_loc_name = $off_row[''circle_tname''];
		}
	}
	
	$pet_details=stripQuotes(killChars($_POST["pet_details"]));

	$query = "select petition_no,petition_date,action_remarks,petitioner_initial,petitioner_name,
	father_husband_name,comm_doorno,comm_street,comm_pincode, source_tname, 
	comm_area,comm_district_name,comm_taluk_name,comm_rev_village_name,griev_district_name,
	action_type_tname from vw_pet_actions 	where  action_type_code in ('A','R') 
	and petition_id in (".$pet_details.") ";

	$result = $db->query($query);
	$row_cnt = $result->rowCount();
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

if($row_cnt!=0)
{

	foreach($rowarray as $row)
	{
		$initial = $row['petitioner_initial'];
		$pet_name = $row['petitioner_name'];
		$father_husband_name = $row['father_husband_name'];
		$door_no = $row['comm_doorno'];
		$street = $row['comm_street'];
		$hamlet = $row['comm_area'];
		$desc_taluk = $row['comm_taluk_name'];
		$desc_vige = $row['comm_rev_village_name'];
		$pincode = $row['comm_pincode'];
		$receipt_date = $row['petition_date'];
		$desc_dist=$row['comm_district_name'];
		$action_type_tname=$row['action_type_tname'];
		$source_name=$row['source_tname'];
		
		$dt=explode('-',$receipt_date);
		
		
		$day=$dt[2];
		$mnth=$dt[1];
		$yr=$dt[0];
		$receiptdate=$day.'-'.$mnth.'-'.$yr;

		$pet_no = $row['petition_no'];
		$griev_district_name = $row['griev_district_name'];
		
		$remarks = $row['action_remarks'];
		$date = date("d/m/Y");
			
?>

	<table border=0 align="center" bordercolor=darkblue cellspacing=1 cellpadding=5 width="70%">
	<tr><th colspan=2 class="reply"><font size=2><b><? echo "பதில் கடிதம்"; ?></b></font></th></tr> 

	<tr>
	<td width="60%" class="address_label"><?php echo "விடுநர்   ";?></td>
	<td width="40%" class="address_label"><?php echo "பெறுநர்";?></td>
	</tr>
	
	<tr> 
	 
	<td  class="address_label add_v">
	<?php echo $dept_desig_name; ?>
	<br><?php echo $off_level_name; ?>, <br> <?php echo $off_loc_name;?>.</td> 
	  
	  

	<td class="address_label" width="40%"><?php echo $initial; ?>
	<? if($initial!="") 
	{ ?>. <? } ?> <?php echo $pet_name;?>,
	<? if($father_husband_name!="")
	{ ?><br>(த/க பெயர் <?php echo $father_husband_name; ?>), <? } ?>
	<br><?php echo $door_no;?>,&nbsp;<?php echo $street;?>,
	<? if($hamlet!="") { ?><br><?php echo $hamlet;?>, <? } ?>
	<? if (($desc_taluk!="") or ($desc_vige)) { ?><br>
	<?php echo $desc_vige;?>,&nbsp;<?php echo $desc_taluk.' தாலுக்கா';?>,  <? } ?>

	<br><?php echo $desc_dist.' மாவட்டம்';?>-<?php echo $pincode;?>.</td>
	</tr>
	
	 <tr> 
	 <td width=70 class="content_label">அன்புடையீர்,</td> 
	 <td width=300 class="label"></td> 
	 </tr>	
	 
	<tr>  
	<td colspan="2" class="subject_label">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;பொருள்:- &nbsp;&nbsp;&nbsp;&nbsp;தங்களது <? echo $receiptdate; ?>&nbsp;&nbsp;தேதியிட்ட  மனு எண் <? echo $pet_no; ?> தொடர்பாக.
	</td>
	</tr>
	
	<tr> 
	<td colspan="2" class="desc_label">
	<p align='justify'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; தங்களால் &nbsp;&nbsp;<?php echo $source_name; ?> &nbsp;&nbsp;  மூலமாக  அளிக்கப்பட்ட <? echo $receiptdate; ?>&nbsp;  தேதியிட்ட மனு &nbsp;எண் &nbsp;&nbsp;&nbsp; <b><? echo $pet_no; ?></b>,&nbsp;பரிசீலிக்கப்பட்டு, &nbsp&nbsp<? echo $action_type_tname; ?>.&nbspஅதன் &nbsp;மீது&nbsp;&nbsp; எடுக்கப்பட்ட &nbsp;&nbsp;  நடவடிக்கைக்கான &nbsp; &nbsp;&nbsp; பதில் &nbsp;&nbsp;&nbsp;கீழே &nbsp;&nbsp;கொடுக்கப்பட்டுள்ளது.</p>
	</td> 
	</tr>
	
	<tr>	
	<td colspan="2" class="reply_label"><p align='justify'>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $remarks; ?> </p> 
	</td> 
	</tr>
	
	<tr> 
	<td colspan="2" class="thanks_label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;நன்றி,</td>
	</tr>
	 
	
	<tr> 
	<td  class="sign_label" align="left" width="30%">இடம்: &nbsp;<?php echo $off_loc_name;?>
	<br>தேதி :&nbsp;<? echo $date; ?>
	</td>
	<td  class="sign_label" align="right" class="label">அலுவலரின் கையொப்பம்
	<br><?php echo $dept_desig_name; ?>
	</td>
	</tr>
	
 
	</table>

<?php		
		
	}?>
	<div  align="center" id="button_row">
		  
 <span><input name="btn2" type="button" id="btn2" value="<?php echo 'Print';?>"  onclick="printToFile();"/></span>
  
	</div>
<?php
	
}	 

?>
<?php
	//include("footer.php");

?>
<style>
@media print
{
table {page-break-after:always}

table:last-of-type {
    page-break-after: auto
}

}


</style>
<style media="print">
 @page {
  size: auto;
  margin: 0;
 }
</style>
<style>
.reply {
	height: 75px;
}
.address_label {
	font-size: 13px;
	font-weight : bold;
}
.content_label {
	font-size: 12px;
	font-weight : bold;
	height: 45px;
}
.subject_label {
	font-size: 12px;
	font-weight : bold;
	height: 40px;
}
.desc_label {
	font-size: 12px;
	height: 40px;
}
.reply_label {
	font-size: 12px; 
	
}
.thanks_label {
	font-size: 11px;
	font-weight : bold;	
	
}
.sign_label {
	font-size: 11px;
	height: 40px;
	padding-top: 45px;
}
.add_v {
	padding-bottom: 54px;
	width:60%;
}
</style>
<script type="text/javascript">
function printToFile() {
	document.getElementById("header").style.visibility='hidden'; 
	document.getElementById("menu").style.display='none';
	document.getElementById("usr_detail").style.display='none';
	//document.getElementById("footertbl").style.visibility='hidden';
	document.getElementById("button_row").style.visibility='hidden';
	
	window.print();
	document.getElementById("header").style.visibility='visible';  
	document.getElementById("menu").style.display='block';
	document.getElementById("usr_detail").style.display='block';
	//document.getElementById("footertbl").style.visibility='visible';
	document.getElementById("button_row").style.visibility='visible';
	
	
}
</script>