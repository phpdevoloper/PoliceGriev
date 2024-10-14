<?php 
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("common_date_fun.php"); 

include_once 'common_lang.php';

if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   ob_start();	
   echo "<script> alert('Timed out. Please login again');</script>";
   echo "<script type='text/javascript'> document.location = 'logout.php'; </script>";
   exit;
} 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="apple-touch-icon" href="assets/images/favicon/apple-touch-icon.png">
<link rel="icon" href="assets/images/favicon/favicon.png">
<title><?php echo $lang['PAGE_TITLE']; ?></title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css"> 
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- font Awesome -->
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- font Awesome -->
<link href="assets/css/base.css" rel="stylesheet" media="all">
<link href="assets/css/form.css" rel="stylesheet" media="all">
<link href="assets/css/form_responsive.css" rel="stylesheet" media="all">
<link href="assets/css/base-responsive.css" rel="stylesheet" media="all">
<link href="assets/css/grid.css" rel="stylesheet" media="all">
<link href="assets/css/font.css" rel="stylesheet" media="all">
<link href="assets/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="assets/css/flexslider.css" rel="stylesheet" media="all">
<link href="assets/css/megamenu.css" rel="stylesheet" media="all" />
<link href="assets/css/print.css" rel="stylesheet" media="print" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>	
<link href="theme/css/site.css" rel="stylesheet" media="all">
<link href="theme/css/site-responsive.css" rel="stylesheet" media="all">
<link href="theme/css/ma5gallery.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="bootstrap/mycss/dpk.css">
<style>
body {
    font-family: "Open Sans", sans-serif !important;
}
.error {
-moz-border-bottom-colors: none;
-moz-border-left-colors: none;
-moz-border-right-colors: none;
-moz-border-top-colors: none;
border-color: #FF0000 !important;
border-image: none;
border-radius: 3px 3px 3px 3px;
border-style: solid;
border-width: 2px;
box-shadow: 0 0 6px #F45B7A!important;
}
.accordion:after {
    content: '\002B';
    color: #777;
    font-weight: bold;
    float: right;
    margin-left: 5px;
}

.active:after {
    content: "\2212";
}
.bluestar{
	color: #0000FF;
	padding-left: 2px;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0px;
  width: 100%;
  background-color: #95342e;
  color: white;
  text-align: center;
margin-bottom: 11%;
}
@media screen and (max-width: 600px) {
	.idname{
		width:43%;
	}
	.idno{
		width:73%;
	}
  }
   select,	option{
	 //  text-align-last: center;
   }
    .shadowbox {
  left: 35%;
  width: 40em;
  border: 1px solid #95342e;
  box-shadow: 4px 2px 1px #444;
  padding: 8px 6px;
  background-color: #f2dfad;
  position:relative;
  animation: blinker 1s step-start infinite;
  top: -20px;
}
.center {
border: 5px solid #95342e;
text-align: center;
}
.error{
border-color:red;	
}
body{
	height:max-content;
}
@keyframes blinker {
  50% {
    opacity: 50;
	border-color:#2740e5;
  }
.scroll {
    min-height:100%;
    overflow:scroll;
height: calc(100vh - 100%);
overflow-y: scroll;	
}
@media(max-width:767px)
{
	.taple_scroll {
		overflow-x: scroll;
	}
	
}
@media (min-width: 1200px)
{
  .flex-control-nav {
    left: 202px; 
    right: auto;
}
   .banner-wrapper .flex-pauseplay {
    left: 870px; 
    right: auto;
}
}
</style>
<script>
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    animationLoop: false,
    itemWidth: 210,
    itemMargin: 5
  });
});

</script>
</head>
<body class="scroll">
<?php// if($_SESSION['lang']=='en') { echo 'Welcome User';}else{ echo 'நல்வரவு பயனர்'; }?>
<?php 
include('online_header_submission.php'); 
include("online_menu.php");
?> 	
	<!--div class="form_logout_wid" id="logout"> <!-- form logout div Start-->
		<!--div class="form_logout_pad">
			<div class="form_user">
				<label class="lab_user_lef"> <?php echo $lang['Welcome_User_Label']; ?> : 
	 <?php echo $_SESSION['USER_ID_PK'];?></label>
			</div>
			<div class="form_logout" >
				<button type="button" class="btn btn-primary btn-sm bt_logout_rig" onclick="closepage_down();">
				  <span class="glyphicon glyphicon-log-out" ></span> <?php echo $lang['Logout_Button']; ?>
				</button>
			</div>	
		</div>
	</div-->    <!-- form logout div  End -->

<div style="clear:both;"></div>
<?php if($_POST['hid']=="") { ?>
<div id="div_content" class="divTable">
<form name="petiton_detail_entry" id="petiton_detail_entry" enctype="multipart/form-data" method="post" action="" autocomplete="off">
<?php

$query = "UPDATE usr_online SET  otp=null WHERE user_mobile=?";
$result = $db->prepare($query);
$result->execute(array($_SESSION['USER_ID_PK']));
?>
	
	<div class="pe_he_wid " id="flip" >    <!-- Petition Related Details div Start-->
			<div class="form_he_rig res_siz">
				<h1><?php echo $lang['Applicant_Details_Label']; ?></h1>
			</div>
			<div class="form_down" id="form_down1">
				<!--<i class="fa fa-angle-down down_arrow" ></i>-->	
			</div>
			<div class="form_down" id="form_down_up" style="display:none;">
				<!--<i class="fa fa-angle-up down_arrow" ></i>-->	
			</div>
	</div>   <!-- Petition Related Details div End -->
	
	<div style="clear:both;"></div>
<?php

$src_sql = "-- petition form: sources combo
	SELECT source_id, source_name, source_tname FROM lkp_pet_source  WHERE source_id = -1" ;
$src_rs=$db->query($src_sql);
if(!$src_rs)
{
	print_r($db->errorInfo());
	exit;
}	
?>	

<div class="pe_re_de_with" id="panel">
	<div class="pet_all_with">
		<div class="pe_re_pad">
			<div class="with_se">
				<label class="lab_with" style="display:none;"><span class="star">* </span>Source</label>
				<select class="se_box_with" name="source" id="source" style="background-color:#d3d3d3;display:none;" selected disabled / ><option value='-1'>Online Petition</option>
			  
				</select>
			</div>
			<div class="with_se">
			<label class="lab_with">
			</div>
			<div class="with_se">
			<label class="lab_with" style="display:none;"><span class="star">* </span>Department</label>
			<select class="se_box_with" name="dept" id="dept" style="background-color:#d3d3d3;display:none;" selected disabled / >
				<option value='1'/>Police Department</option>
				</select>
			</div>
		</div>
	</div> <!-- textbox label div Start div End 1-->
<div style="clear:both;"></div>

<!-- Personal Details Line 1 -->
<div class="pet_all_with" style="margin-bottom:1px;"> <!-- textbox label div Start 1-->
	<div class="pe_re_pad">
		<div class="with_se">
			<label class="lab_with"><span class="star">* </span><?php echo $lang['Mobile_Number_Label']; ?></label>
			<input type="text" name="mobile_number" id="mobile_number" data_valid='yes'  class="dis_able " style="width:35%;" value="<?php echo $_SESSION['USER_ID_PK'] ;?>"  disabled/>
			<input type="button" name="search" id="search" class="btn btn-primary" style="padding: revert;" value="<?PHP echo 'Search'; //Save?>" onClick="return chkForExistingPetitions();"/>
		</div>
		<div class="with_se">
			<label class="lab_with"><?php echo $lang['Email_Label']; ?></label>
			<input type="email" name="email" id="email" class="se_box_with" data_valid='no' maxlength="30" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" required>
		</div>
		<?php
			$id_sql = "select * FROM lkp_id_type order by case when idtype_id=6 then 1 else 0 end,idtype_name;";
			$id_rs=$db->query($id_sql);
			if(!$id_rs)
			{
				print_r($db->errorInfo());
				exit;
			}		
			?>
			<div class="with_se">
				<label class="lab_with">Id Type & No.</label>
				<select class="se_box_with" name="idtype" id="idtype"  data_valid='no' data-error="Please select Id Type" style="width:22%;" onChange="javascript:$('#idno').removeAttr('disabled');">
				<option value="" selected disabled>--Select--</option> 
				<?php  
				while($id_row = $id_rs->fetch(PDO::FETCH_BOTH))
				{
					$idname=$id_row["idtype_name"];
					$idtname=$id_row["idtype_tname"];
					if($_SESSION["lang"]=='E')
					{
						$id_name=$idname;
					}else if($_SESSION["lang"]=='T')
					{
						$id_name=$idtname;
					}
					else
					{
						$id_name=$idname;
					}
					print("<option value='".$id_row["idtype_id"]."' >".$id_name."</option>");

				}
			?>
			</select>
			<input type="text" name="idno" id="idno" maxlength="30" onkeypress="return characters_numsonly(event);" class="se_box_with" data_valid='no' data-error="Please enter Id no." style="width:39%;" disabled/>
		</div>
	</div>
</div>
<!-- Personal Details Line 2 -->
<div class="pe_re_pad">
	<div class="with_se">
		<label class="lab_with"><span class="star">* </span><?php echo $lang['Name_Label']; ?></label>
		<input type="text"  class="se_box_with1" name="pet_eng_initial" id="pet_eng_initial" value="" size="3" maxlength="15" onchange="avoid_Special('pet_eng_initial');" onkeypress="return charactersonly(event);" data_valid="yes" data-error="Please enter initial">
		<input type="text" name="pet_ename" id="pet_ename" class="se_box_with2" onkeypress="return charactersonly(event);" maxlength="50" data_valid='yes' data-error="Please enter Petitioner Name" >
	</div>
	<div class="with_se">
		<label class="lab_with"><span class="star">* </span><?php echo $lang['Father_Husband_Label']; ?></label>
		<input type="text" name="father_ename" id="father_ename" maxlength="50" onkeypress="return charactersonly(event);" class="se_box_with" data_valid='yes' maxlength="150" data-error="Please enter father/spouse name">
	</div>
		<?php
			$gen_sql = "select gender_id,gender_name,gender_tname from lkp_gender order by gender_id";
			$gen_rs=$db->query($gen_sql);
			if(!$gen_rs)
			{
				print_r($db->errorInfo());
				exit;
			}		
		?>
	<div class="with_se">
		<label class="lab_with"><span class="star">* </span><?php echo $lang['Gender_Label']; ?></label>
		<select class="se_box_with" name="gender" id="gender"  data_valid='yes' data-error="Please select gender">
		<option value="" selected disabled>--Select--</option> 
		<?php  
			while($gen_row = $gen_rs->fetch(PDO::FETCH_BOTH))
			{
				$genname=$gen_row["gender_name"];
				$gentname=$gen_row["gender_tname"];
				if($_SESSION["lang"]=='E')
				{
					$gen_name=$genname;
				}else if($_SESSION["lang"]=='T')
				{
					$gen_name=$gentname;
				}
				else
				{
					$gen_name=$genname;
				}
				print("<option value='".$gen_row["gender_id"]."' >".$gen_name."</option>");

			}
		?>
		</select>
	</div>
</div>
<!-- Personal Details Line 3  hidden-->
<div class="pet_all_with" style="display:none;"> <!-- textbox label div Start 1-->
	<div class="pe_re_pad">
		<div class="with_se">
			<label class="lab_with"><?php echo $lang['Community_Label']; ?></label>
			<select class="se_box_with" name="community" id="community"  data_valid='no'>
			<option value="" selected disabled>--Select--</option> 
			<?php  
				$comm_sql = "SELECT pet_community_id, pet_community_name, pet_community_tname FROM lkp_pet_community order by pet_community_id";
				$comm_rs=$db->query($comm_sql);
				while($comm_row = $comm_rs->fetch(PDO::FETCH_BOTH))
					{
					$pet_community_name=$comm_row["pet_community_name"];
					$pet_community_tname=$comm_row["pet_community_tname"];
					if($_SESSION["lang"]=='E')
					{
						$petcommunityname=$pet_community_name;
					}else if($_SESSION["lang"]=='T')
					{
						$petcommunityname=$pet_community_tname;
					}
					else
					{
						$petcommunityname=$pet_community_name;
					}
					print("<option value='".$comm_row["pet_community_id"]."' >".$petcommunityname."</option>");
				}
			?>
			</select>
		</div>
		<div class="with_se">
			<label class="lab_with"><?php echo $lang['Category_Label']; ?></label>
			<select class="se_box_with" name="petitioner_category" id="petitioner_category"  data_valid='no'>
			<option value="" selected disabled>--Select--</option> 
			<?php  
				$cat_sql = "SELECT petitioner_category_id, petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category order by petitioner_category_id";
				$cat_rs=$db->query($cat_sql);
				while($cat_row = $cat_rs->fetch(PDO::FETCH_BOTH))
				{
					$petitioner_category_name=$cat_row["petitioner_category_name"];
					$petitioner_category_tname=$cat_row["petitioner_category_tname"];
					if($_SESSION["lang"]=='E')
					{
						$petitionercategoryname=$petitioner_category_name;
					}else if($_SESSION["lang"]=='T')
					{
						$petitionercategoryname=$petitioner_category_tname;
					}
					else
					{
						$petitionercategoryname=$petitioner_category_name;
					}
					print("<option value='".$cat_row["petitioner_category_id"]."' >".$petitionercategoryname."</option>");

				}
			?>
			</select>
		</div>
	</div>
</div> <!-- textbox label div Start div End 1-->
<!-- Personal Details Line 4  hidden-->
<!-- New columns for Community and special categoty -->
<div class="pet_all_with" style="display:none"> <!-- textbox label div Start 1-->
	<div class="pe_re_pad">
		
		<div class="with_se">
		<label class="lab_with"><?php echo $lang['Aadhar_Number_Label']; ?></label>
			<input type="text" name="aadharid" id="aadharid" class="se_box_with" onKeyPress="return numbersonly(event);" onblur="checkAadhar();" maxlength="12"  data_valid='no'>
		</div>
		
	</div>
</div> <!-- textbox label div Start div End 1-->
<div class="pet_all_with"> <!-- textbox label div Start 1-->
	<div class="pe_re_pad">
		<div class="with_se">
		<label class="lab_with" style="padding-right:0px"><?php echo $lang['Address_Label']; ?>&nbsp;&nbsp;<span class="star">* </span>Door No. & Street&nbsp;&nbsp; </label>
			<input type="text" name="comm_doorno" id="comm_doorno" maxlength="15" class="se_box_with" data_valid='yes' data-error="Please enter door number" style="width:10%">
			<input type="text" name="comm_street" id="comm_street" class="se_box_with2" maxlength="50" data_valid='yes' data-error="Please enter street Name" >
		</div>
		<div class="with_se">
		<label class="lab_with"><span class="star">* </span><?php echo $lang['Area_Ward_Place_Label']; ?></label>
		<input type="text" name="comm_area" id="comm_area" class="se_box_with" maxlength="50" data_valid='yes'  data-error="Please enter Place/ Hamlet/ Ward">
		</div>
		<div class="with_se">
		<label class="lab_with"><span class="star">* </span>Pincode</label>
		<input type="text" name="pincode" id="pincode" class="se_box_with" maxlength="6"  min="6"  data_valid='yes' onKeyPress="return numbersonly(event);"  data-error="Please enter Valid Pincode" required pattern="[1-9][0-9]{5}"/>
		</div>
	</div>
</div> <!-- textbox label div Start div End 1-->

<?php
	$dist_sql = "SELECT * FROM mst_p_district order by case when district_id<0 then 1 else 0 end,district_name";
	$dist_rs=$db->query($dist_sql);
	if(!$dist_rs)
	{
	print_r($db->errorInfo());
	exit;
	}		
?>
<div class="pet_all_with" style="display:none;"><!-- Applicant Details & Communication Address Start 1-->
	<div class="pe_re_pad" >
		<div class="with_se" >
		<label class="lab_with"><span class="star">* </span><?php echo $lang['District_Label']; ?></label>
			<select class="se_box_with" name="comm_dist" id="comm_dist" onChange="get_comm_taluk();" data_valid='no' data-error="Please select district">
				<option value="" selected disabled>--Select--</option>
					<?php  
							//$comm_dist = $userProfile->getDistrict_id();
							while($dist_row = $dist_rs->fetch(PDO::FETCH_BOTH))
							{
							$distname=$dist_row["district_name"];
							$disttname=$dist_row["district_tname"];
							if($_SESSION["lang"]=='E')
							{
							$comm_dist_name=$distname;
							}else if($_SESSION["lang"]=='T')
							{
							$comm_dist_name=$disttname;
							}else 
							{
							$comm_dist_name=$distname;
							}
							
							print("<option value='".$dist_row["district_id"]."'>".$comm_dist_name."</option>");
							
							}
					?>
			</select>	
			
		</div>
		<div class="with_se">
			<label class="lab_with"><span class="star">* </span><?php echo $lang['Taluk_Label']; ?></label>
			<select class="se_box_with" name="comm_taluk" id="comm_taluk"  onChange="get_comm_village();" data_valid='no' data-error="Please select taluk">
			<option value="" selected disabled>Select</option>	  
			</select>
		</div>
		<div class="with_se">
		<label class="lab_with"><span class="star">* </span><?php echo $lang['Revenue_Village_Label']; ?></label>
		<select class="se_box_with" name="comm_rev_village" id="comm_rev_village" data_valid='no' data-error="Please select revenue village">
			<option value="" selected disabled>Select</option>	  
		</select>
		</div>
	</div>
</div> <!-- textbox label div Start div End 1-->
	
<div class="app_he_wid" id="flip1">    <!-- Applicant Details & Communication Address div Start -->
			<div class="form_he_rig res_siz">
				<h1><?php echo $lang['Petition_Details_Label']; ?></h1>
			</div>
			<div class="form_down" id="form_down2">
				<!--<i class="fa fa-angle-down down_arrow" ></i>-->	
			</div>
			<div class="form_down" id="form_down_up1" style="display:none;">
				<!--<i class="fa fa-angle-up down_arrow" ></i>-->	
			</div>
	</div>   <!-- PApplicant Details & Communication Address div End -->	
	<div class="pe_re_de_with" id="panel1">

	
	<div class="pet_all_with"> <!-- textbox label div Start 2-->
		<div class="pe_re_pad">
			<div class="with_se" style="display:none;">
				<label class="lab_with"><span class="star">* </span>Petition Type</label>
				<select class="se_box_with" name="pet_type" id="pet_type" data_valid='no' data-error="Please Forward Office Level">
				<option value="3" disabled>--Select--</option> 
				<?php  
				/* $cat_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type order by pet_type_id";
				$cat_rs=$db->query($cat_sql);
				while($cat_row = $cat_rs->fetch(PDO::FETCH_BOTH))
				{
					$pet_type_name=$cat_row["pet_type_name"];
					$pet_type_tname=$cat_row["pet_type_tname"];
					if($_SESSION["lang"]=='E')
					{
						$pet_typename=$pet_type_name;
					}else if($_SESSION["lang"]=='T')
					{
					$pet_typename=$pet_type_tname;
					}
					else
					{
					$pet_typename=$pet_type_name;
					}
					print("<option value='".$cat_row["pet_type_id"]."' >".$pet_typename."</option>");
				
				} */print("<option value='3' >Regular</option>");
			    ?>
				</select>
			</div>
			<div class="with_se">
				<label class="lab_with"><span class="star">* </span>Main Category</label>
				<select class="se_box_with" name="griev_maincode" id="griev_maincode" onChange="if($('#griev_maincode').val()!=''){get_sub_category();}" data_valid='yes' data-error="Please select Main category" >
				<option value="" selected>--Select--</option>  
				</select>
			</div>
			<div class="with_se">
				<label class="lab_with"><span class="star">* </span>Sub Category</label>
				<select class="se_box_with" name="griev_subcode" id="griev_subcode" data_valid='yes' data-error="Please select Subcategory">
					<option value="" selected>--Select--</option> 
				</select>
			</div>
			<div class="with_se">
				<label class="lab_with"><span class="star">* </span>Petition Office</label>
				<select class="se_box_with" name="fwd_office_level" id="fwd_office_level" data_valid='yes' data-error="Please Select Petition Office" onchange="if($('#fwd_office_level').val()!=''){lo();loadSL();loadoff()};">
				<option value="" selected>--Select--</option>  
				</select>
			</div>
		</div>
		
		<div class="pet_all_with"> <!-- textbox label div Start 2-->
		<div class="pe_re_pad">
			
			<div class="with_se" id='off_level_row' style='display:none;'>
				<label class="lab_with"><span class="bluestar">* </span>District</label>
				<select class="se_box_with" name="off_level" id="off_level"  data_valid='no' data-error="Please select District" onchange="if($('#off_level').val()!=''){loadOfficeLocations();}">
				<option value="" selected>--Select--</option>
				</select>
				
			</div>
			<div class="with_se">
				<label class="lab_with" id='loc_off'><span class="star">* </span>Office Location</label>
				<select class="se_box_with" name="offlocation" id="offlocation" data_valid='yes' data-error="Please select office location" onchange="if($('#offlocation').val()!=''){chk();}">
					<option id='offloc1' selected >--Select--</option> 
				</select>
				<lim id ='all_off' style="display:none;"><input type="button" class="se_box_with" name="off_name" id="off_name1" disabled="disabled"  width='30px' onClick="javascript:get_all_officer_list();" style="background-color: #e9e9ed;text-decoration: underline;color:blue;" title="Click here to change"/></lim>
				<input type="hidden" name="off_d_id" id="off_d_id" />
				
				<button id="sub_div" style="display:none;"><a href="javascript:get_all_officer_list();" title="Click here to Select" style="color:blue;text-decoration: underline;">Select Location</a></button>
				
				
				
				<button id="divlink" style="display:none;"><a href="javascript:get_all_officer_list();" title="Click here to Select" style="color:blue;text-decoration: underline;">Select Location</a></button>
				<button id="cir_link" style="display:none;"><a href="javascript:get_all_officer_list();" title="Click here to Select" style="color:blue;text-decoration: underline;">Select Location</a></button>
			
			<!--lim id="alcirc2"><a id="all_linkp0s" href="javascript:get_all_officer_list();">&nbsp;<input type="text" name="off_name" id="off_name" disabled="disabled" data_valid='no' width='90px'/>&nbsp;Circle</a></lim--> 
           
			</div>
			</div>
	
		</div>
	</div> <!-- textarea div Start div End 3-->
	

<div style="clear:both;"></div>
	<div class="pet_all_with"> <!-- textbox label div Start 2-->
			<div class="pe_re_pad">
				<div class="with_textarea">
					<label class="tex_lab_with"><span class="star">* </span>Petition Detail</label>
					<div class="textarea_box_with">
					<textarea  class="textarea_des" name="grievance" id="grievance" data_valid='yes' data-error="Please enter petition detail" 
					onkeydown="textCounter(document.petiton_detail_entry.grievance,document.petiton_detail_entry.remLen2,3000); "  
					onkeyup="textCounter(document.petiton_detail_entry.grievance,document.petiton_detail_entry.remLen2,3000);" 
					onKeyPress="return characters_numsonly_grievance(event);" ></textarea>
					</div>
				</div>
			</div>
			<div class="with_se res_pad">
			<label class="lab_with"><?php echo $lang['Upload_Label']; ?></label>
			<div class="file_with">
			<input type="file" name="files[]" id="files" multiple="multiple" onchange="filesizevalidation();filetypevalidation();"  accept="application/pdf, image/jpeg" data_valid='no' data-error="Please select a document to upload."><span class="star"><?php echo $lang['Upload_File_Message_Label']; ?></span>
			</div>
		</div>
	
	<div class="pe_re_pad">
	<div class="with_se">
				<label class="lab_with"><span class="star">* </span>Petition Submission Level</label>
				<select class="se_box_with" name="sub_level" id="sub_level"  data_valid='yes' data-error="Please select Submission Level" >
				<option value="" selected>--Select--</option>  
				</select>
			</div><p id='related' style='display:none;'></p>
		</div>
	</div> <!-- textarea div Start div End 3--> 

	
	 <!-- textbox label div Start div End 1-->
<div class='shadowbox center' id="confirm_old" style="display:none;">

	<div style="text-align:center;"><span><h3 style="color:red;font-weight:bolder;">Confirmation <sup>*</sup></h3></span><hr style="border-top: 1px solid red;">
	<span><p style="color:red;font-weight:bolder;">Is the Petition selected by you related to this Petition being entered?</p><div><div><input type='button' name="old_pet_btn" id="old_pet_btn1" class='btn btn-primary' value="Yes" onclick="add_old();" style="background-color:red;">&nbsp;<input type='button' id="old_pet_btn" name="old_pet_btn2" class='btn btn-primary' style="background-color:red;" value="No" onclick="remove_old();"></div></div></span>
	</div>

	</div>
	
	
<div style="clear:both;"></div>


</div>

<div style="clear:both;"></div>
<div class="pe_re_pad"></div>
	<div class="pe_he_wid1" >    <!-- Petition Related Details div Start-->
			<div class="form_sub_detail">
				<h1 style="color:red;"><?php echo $lang['Alert_Message_Label']; ?></h1>
			</div>
	</div>   <!-- Petition Related Details div End -->
	</div>

<div style="clear:both;"></div>
<div class="save_with" >
	<div class="save_des" style="position:relative;left:20%;">
		<input type="button" class="btn btn-primary bt_pad" name="save" id="save" value="<?PHP echo $lang['Save_Button']; ?>" onClick="return valchk();" />
	</div >
	<div class="clear_des" style="position:relative;left:25%;">
		<input type="button" class="btn btn-primary bt_pad" name="" id="" value="<?php echo $lang['Reset_Button']; ?>" onClick="return v_reset();" />
	</div >
	<!--div class="logout_des">
		<input type="button" class="btn btn-primary bt_pad" name="" id=""  onclick="closepage_up();" value="<?php echo $lang['Logout_Button']; ?>" />
	</div -->
</div >
<div style="clear:both;"></div>

    <div class="modal fade" id="alrtmsg1"  role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm modal_width modal_mr_top">
      <div class="modal-content bor-mod">
        <div class="modal-header">
          <button type="button" class="close int_close" data-dismiss="modal">&times;</button>
          <!--h4 class="modal-title text-center note_col1 mod_size"><span class="star">* </span>Indicates Mandatory Data</h4-->
        </div>
        <div class="modal-body note_col1">
          <span class="star" style="float: left;font-size: 20px;"> *</span><div id="alrtmsg" class="mod_size"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close_bott" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div> 
  <!-- Hidden Start-->
<input type="hidden" name="pattern" id="pattern" />	
<input type="hidden" name="off_level_id" id="off_level_id" />	
<input type="hidden" name="off_level_dept_id" id="off_level_dept_id" />	
<input type="hidden" name="offname" id="offname" />
<input type="hidden" name="off_level_office_id" id="off_level_office_id" />	
<input type="hidden" name="pat_id" id="pat_id" />	
<input type="hidden" name="locid" id="locid" />	
<input type="hidden" name="hid" id="hid" />
<input type="hidden" name="hidSL" id="hidSL" />
<input type="hidden" name="pre" id="pre" />
<input type="hidden" name="hid_pet_old" id="hid_pet_old" />

<input type="hidden" name="remLen2" id="remLen2" />
<input type="hidden" name="lang" id="lang" value="<?php echo $_SESSION["lang"];?>"/>
<!-- Hidden End-->
</form>

<!--input type="hidden" name="pat_id" id="pat_id" />	
<input type="hidden" name="locid" id="locid" />	
<input type="hidden" name="hid" id="hid" />
<input type="hidden" name="hidSL" id="hidSL" />
<input type="hidden" name="pre" id="pre" />
<input type="hidden" name="off_level_id" id="off_level_id" />
<input type="hidden" name="off_level_dept_id" id="off_level_dept_id" />
<input type="hidden" name="remLen2" id="remLen2" /-->
<input type="hidden" name="lang" id="lang" value="<?php echo $_SESSION["lang"];?>"/>
<br>
<br>
</div><div class='footBottom'>
<p class="footer" id="footertbl" style="margin-bottom: 0px;">
Computerized By NATIONAL INFORMATICS CENTRE<!--  IT Support by: National Informatics Centre-->, TNSU, Chennai
</p>
</div>
</body>
<div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">

</div><?php } ?>
	
<?php if ($_POST['hid'] == 'done') {
	
	$valid_formats = array("pdf","jpg","jpeg");
	$max_file_size = 1572864; //in Bytes which is 1.5 mb
	
	//$path = "doc_uploads/"; // Upload directory
				$count = 0;
    // Loop $_FILES to execute all files
	foreach ($_FILES['files']['name'] as $f => $name) {
		$doc_size[] = $_FILES["files"]["size"][$f];
		$doc_type[] = $_FILES["files"]["type"][$f];
		if ($_FILES['files']['error'][$f] == 4) {
			continue; // Skip file if any error found
		}	       
		if ($_FILES['files']['error'][$f] == 0) {
			
				$selected_files = $selected_files + $_FILES['files']['size'][$f];	           
			if ($selected_files > $max_file_size) {
				//echo $selectedfiles;
				$message .= "$name is too large!.";
				?><script>
					alert("<?php echo  $message; ?>");
					window.location.href="online_petition_detail_entry.php";
				</script>
				<?php
				continue; // Skip large files
			}
			elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
				$message .= "$name is not a valid format";
				?><script>
					alert("<?php echo $message; ?>");
					window.location.href="online_petition_detail_entry.php";
				</script>
				<?php
				continue; // Skip invalid file formats
			}
		}
		//die;
	}	
if($_POST['hid']=='done' && $message == '')
{ 
//echo "qqqqqq";
$source=stripQuotes(killChars($_POST['source']));
$griev_maincode=stripQuotes(killChars($_POST['griev_maincode']));
$griev_subcode=stripQuotes(killChars($_POST['griev_subcode']));
$survey_no=stripQuotes(killChars($_POST['survey_no']));
$sub_div_no=stripQuotes(killChars($_POST['sub_div_no']));
$grievance=stripQuotes(killChars($_POST['grievance']));
$pet_eng_initial=stripQuotes(killChars($_POST['pet_eng_initial']));
$pet_ename=stripQuotes(killChars($_POST['pet_ename']));
$father_ename=stripQuotes(killChars($_POST['father_ename']));
$gender=stripQuotes(killChars($_POST['gender']));
$mobile_number=stripQuotes(killChars($_POST['mobile_number']));
$email=stripQuotes(killChars($_POST['email']));
$pet_type=stripQuotes(killChars($_POST['pet_type']));
$comm_doorno=stripQuotes(killChars($_POST['comm_doorno']));
$comm_flat_no=stripQuotes(killChars($_POST['comm_flat_no']));
$comm_street=stripQuotes(killChars($_POST['comm_street']));
$comm_area=stripQuotes(killChars($_POST['comm_area']));
$comm_dist=stripQuotes(killChars($_POST['comm_dist']));
$comm_taluk=stripQuotes(killChars($_POST['comm_taluk']));
$comm_rev_village=stripQuotes(killChars($_POST['comm_rev_village']));
$pincode=stripQuotes(killChars($_POST['pincode']));
$offname=stripQuotes(killChars($_POST['offname']));
$gre_dist=stripQuotes(killChars($_POST['district']));
$gre_taluk=stripQuotes(killChars($_POST['gre_taluk']));
$gre_rev_village=stripQuotes(killChars($_POST['gre_rev_village']));
$gre_block=stripQuotes(killChars($_POST['gre_block']));
$gre_tp_village=stripQuotes(killChars($_POST['gre_tp_village']));
$gre_urban_body=stripQuotes(killChars($_POST['gre_urban_body']));
$gre_division=stripQuotes(killChars($_POST['gre_division']));
$gre_subdivision=stripQuotes(killChars($_POST['gre_subdivision']));
$gre_circle=stripQuotes(killChars($_POST['gre_circle']));
$off_d_id=stripQuotes(killChars($_POST['off_d_id']));
$idtype=stripQuotes(killChars($_POST['idtype']));
$idno=stripQuotes(killChars($_POST['idno']));
$sub_level=stripQuotes(killChars($_POST['sub_level']));
$pattern=stripQuotes(killChars($_POST['pattern']));
/* $zone_id=Quotes(killChars($_POST['zone_id']));
$range_id=Quotes(killChars($_POST['range_id']));
$state_id=Quotes(killChars($_POST['state_id'])); */


$formptoken=stripQuotes(killChars($_POST['formptoken']));
$language = stripQuotes(killChars($_POST['lang']));
$fwd_office_level=stripQuotes(killChars($_POST['off_level']));
$office_level_id=stripQuotes(killChars($_POST['off_level_id']));
$office_level=stripQuotes(killChars($_POST['off_level_dept_id']));
$dept = stripQuotes(killChars($_POST['dept']));
$aadharid = stripQuotes(killChars($_POST['aadharid']));
$community = stripQuotes(killChars($_POST['community']));
$petitioner_category = stripQuotes(killChars($_POST['petitioner_category']));
$offlocation = stripQuotes(killChars($_POST['locid']));
$old_pet_no = stripQuotes(killChars($_POST['hid_pet_old']));





$document_name = array();
$i=0;
foreach($_FILES['files']['name'] as $filename){

//exit;
  $filename = preg_replace("/[^a-zA-Z0-9.]/", "", $filename);
  $document_name[$i] = $filename;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
 $document_names .= $document_name[$j].',';
}
//Temp Name
$document_tmp_name = array();
$i=0;
foreach($_FILES['files']['tmp_name'] as $tmp_name){
  $document_tmp_name[$i] = $tmp_name;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
  $document_tmp_names .= $document_tmp_name[$j].',';
}
//File Size
$document_size = array();
$i=0;
foreach($_FILES['files']['size'] as $filesize){
  $document_size[$i] = $filesize;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
  $document_sizes .= $document_size[$j].',';
}
//File Type
$document_type = array();
$i=0;
foreach($_FILES['files']['type'] as $filetype){
  $document_type[$i] = $filetype;
  $i=$i+1;
}
$j=0;
for($j=0;$j<$i;$j++){
 $document_types .=$document_type[$j].',';
}
//File Count
$i=0;
foreach($_FILES['files']['name'] as $filename){
	$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $filename);
  $document_count[$i] = count($filename);
  $i=$i+1;

}
$j=0;
for($j=0;$j<$i;$j++){
 $document_counts = $document_count[$j]+$j;
}
$data['xml']='
<Data>
<source>'.$source.'</source>
<pet_type>'.$pet_type.'</pet_type>
<dept>'.$dept.'</dept>
<griev_maincode>'.$griev_maincode.'</griev_maincode>
<griev_subcode>'.$griev_subcode.'</griev_subcode>
<survey_no>'.$survey_no.'</survey_no>
<sub_div_no>'.$sub_div_no.'</sub_div_no>
<grievance>'.$grievance.'</grievance>
<gre_dist>'.$gre_dist.'</gre_dist>
<gre_taluk>'.$gre_taluk.'</gre_taluk>
<gre_rev_village>'.$gre_rev_village.'</gre_rev_village>
<gre_block>'.$gre_block.'</gre_block>
<gre_tp_village>'.$gre_tp_village.'</gre_tp_village>
<gre_urban_body>'.$gre_urban_body.'</gre_urban_body>
<gre_division>'.$gre_division.'</gre_division>
<gre_subdivision>'.$gre_subdivision.'</gre_subdivision>
<gre_circle>'.$gre_circle.'</gre_circle>
<fwd_office_level>'.$fwd_office_level.'</fwd_office_level>
<office_level>'.$office_level.'</office_level>
<aadharid>'.$aadharid.'</aadharid>
<community>'.$community.'</community>
<petitioner_category>'.$petitioner_category.'</petitioner_category>
<pet_eng_initial>'.$pet_eng_initial.'</pet_eng_initial>
<pet_ename>'.$pet_ename.'</pet_ename>
<father_ename>'.$father_ename.'</father_ename>
<gender>'.$gender.'</gender>
<mobile_number>'.$mobile_number.'</mobile_number>
<email>'.$email.'</email>
<comm_doorno>'.$comm_doorno.'</comm_doorno>
<comm_street>'.$comm_street.'</comm_street>
<comm_area>'.$comm_area.'</comm_area>
<comm_dist>'.$comm_dist.'</comm_dist>
<comm_taluk>'.$comm_taluk.'</comm_taluk>
<comm_rev_village>'.$comm_rev_village.'</comm_rev_village>
<zone_id>'.$zone_id.'</zone_id>
<range_id>'.$range_id.'</range_id>
<state_id>'.$state_id.'</state_id>
<idtype>'.$idtype.'</idtype>
<idno>'.$idno.'</idno>
<off_level_dept_id>'.$off_level_dept_id.'</off_level_dept_id>
<offlocation>'.$off_d_id.'</offlocation>
<office_level_id>'.$office_level_id.'</office_level_id>
<pincode>'.$pincode.'</pincode>
<offname>'.$offname.'</offname>

<document_names>'.$document_names.'</document_names>
<document_tmp_names>'.$document_tmp_names.'</document_tmp_names>
<document_sizes>'.$document_sizes.'</document_sizes>
<document_types>'.$document_types.'</document_types>
<document_counts>'.$document_counts.'</document_counts>
<sub_level>'.$sub_level.'</sub_level>
<pattern>'.$pattern.'</pattern>
<old_pet_no>'.$old_pet_no.'</old_pet_no>



</Data>';
/* $data['xml']='
<source>'.$source.'</source>
<dept>'.$dept.'</dept>
<pet_type>'.$pet_type.'</pet_type>
<griev_maincode>'.$griev_maincode.'</griev_maincode>
<griev_subcode>'.$griev_subcode.'</griev_subcode>
<grievance>'.$grievance.'</grievance>
<sub_level>'.$sub_level.'</sub_level>
<idtype>'.$idtype.'</idtype>
<idno>'.$idno.'</idno>
<mobile_number>'.$mobile_number.'</mobile_number>
<email>'.$email.'</email>
<pet_eng_initial>'.$pet_eng_initial.'</pet_eng_initial>
<pet_ename>'.$pet_ename.'</pet_ename>
<father_ename>'.$father_ename.'</father_ename>
<gender>'.$gender.'</gender>
<community>'.$community.'</community>
<petitioner_category>'.$petitioner_category.'</petitioner_category>
<comm_doorno>'.$comm_doorno.'</comm_doorno>
<comm_street>'.$comm_street.'</comm_street>
<comm_area>'.$comm_area.'</comm_area>
<comm_dist>'.$comm_dist.'</comm_dist>
<comm_taluk>'.$comm_taluk.'</comm_taluk>
<comm_rev_village>'.$comm_rev_village.'</comm_rev_village>
<gre_dist>'.$gre_dist.'</gre_dist>
<gre_range>'.$gre_range.'</gre_range>
<gre_zone>'.$gre_zone.'</gre_zone>
<gre_division>'.$gre_division.'</gre_division>
<gre_subdivision>'.$gre_subdivision.'</gre_subdivision>
<gre_circle>'.$gre_circle.'</gre_circle>
<off_level>'.$off_level.'</off_level>
<fwd_office_level>'.$fwd_office_level.'</fwd_office_level>

<document_names>'.$document_names.'</document_names>
<document_tmp_names>'.$document_tmp_names.'</document_tmp_names>
<document_sizes>'.$document_sizes.'</document_sizes>
<document_types>'.$document_types.'</document_types>
<document_counts>'.$document_counts.'</document_counts>


</Data>'; */


$_SESSION['language']=$language;

$ipaddress = $_SERVER['SERVER_ADDR'];
$ippart = explode('/',$_SERVER['REQUEST_URI']);
if ($ippart[1] == 'online_petition_detail_entry.php'){
      //  $url = 'http://localhost/police/online_petition_detail_insert.php';
	  $url = 'http://localhost/police/online_petition_detail_insert.php';
}
else {
        //$url = 'http://localhost/police/psppp/online_petition_detail_insert.php';
		$url = 'http://localhost/police/online_petition_detail_insert.php';
}
$url = 'http://localhost/police/online_petition_detail_insert.php';
//print($data['xml']);exit;
//echo $url;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// added on 23/7/2020
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
// added on 23/7/2020

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result = curl_exec ($ch);
print_r($result);
//echo ">>>>>>>>>";
	}
}

?>
</footer><!--/.footer-wrapper-->

<script type="text/javascript" src="assets/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>
<!--script type="text/javascript" src="dist/jquery.validate.js"></script-->
<!--<script type="text/javascript" src="assets/js/jquery.flexslider.js"></script>-->
<script src="bootstrap/js/modalbootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//document.getElementById("sub_div").style.display = "none";
	/* document.getElementById("alcirc").style.display = "none";
	document.getElementById("alcirc1").style.display = "none";
	document.getElementById("alcirc2").style.display = "none"; */
	//document.getElementById("cir_link").style.display = "none";
	//document.getElementById("divlink").style.display = "none";
	//document.getElementById("all_off").style.display = "none";
/*	$('cir_link').hide(); */
if ($('#hid').val()==''){
	loadGrievanceType();
	getpetoff();
	}
});

function loadGrievanceType() {  //  Main Category // Included for Registration Department  
	dept_id=$('#dept').val();
	fwd_office_level=$('#fwd_office_level').val();
	//alert(dept_id);
	if (dept_id!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=get_grievance"+"&dept_id="+dept_id+"&fwd_office_level="+fwd_office_level,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("griev_maincode").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}
function loadzone() {  //  Main Category // Included for Registration Department  
	pattern=$('#pattern').val();
	fwd_office_level=$('#fwd_office_level').val();
	//alert(dept_id);
	if (pattern!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=loadzone"+"&pattern="+pattern,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("offlocation").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}
function loaddistsp() {  //  Main Category // Included for Registration Department  
	pattern=$('#pattern').val();
	fwd_office_level=$('#fwd_office_level').val();
	//alert(dept_id);
	if (pattern!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=loaddistsp"+"&pattern="+pattern,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("offlocation").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}
function getpetoff() {  //  Main Category // Included for Registration Department  
	//alert(fwd_office_id);
	if (dept_id!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=po",  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("fwd_office_level").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}

function office(){
	var param = "source_frm=office"
					+"&off_level_id="+ $("#off_level_id").val()
					+"&off_level_dept_id="+ $("#off_level_dept_id").val()
					+"&pre="+ $("#pre").val()
					+"&district="+ $("#off_level").val();
					//alert(param);
					$.ajax({
				type: "POST",
				dataType: "xml",
				url: "online_petition_details_action.php",  
				data: param,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					// we have the response 
					// p1_createGrid(xml);
				
			 
				document.getElementById('offlocation').innerHTML='<option value="" selected disabled>--Select--</option>';
				
$(xml).find('off_loc_id').each(function(i)
	{
		var off_loc_id = $(xml).find('off_loc_id').eq(i).text();
		var off_loc_name = $(xml).find('off_loc_name').eq(i).text();
		$('#offlocation').append("<option value="+off_loc_id+"> "+off_loc_name+"</option>");
	
	})
},  
				error: function(e){  
					//alert('Enter valid Code');  
				}
					})
		}


function loadpetoff() {  //  Main Category // Included for Registration Department  
	fwd_office_id=$('#fwd_office_level').val();
	//$("#hidSL").val()=fwd_office_id;
	//console.log(fwd_office_id);
	//alert(fwd_office_id);
	if (dept_id!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=get_po"+"&fwd_office_id="+fwd_office_id+"&fwd_office_level="+fwd_office_level,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("off_level").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}
function loadSL() {  //  Main Category // Included for Registration Department  alert();
	fwd_office=$('#fwd_office_level').val();
	//off_level=$('#off_level').val();
	const myArray = fwd_office.split("-");
	off_level = myArray[2];
	fwd_office_id = myArray[0];
	if (dept_id!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=Submission_Level"+"&off_level="+off_level+"&fwd_office_id="+fwd_office_id,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				document.getElementById("sub_level").innerHTML=html;
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}

function chkForExistingPetitions() {
	var mobile_number = document.getElementById("mobile_number").value;
	if (mobile_number != '') {
		$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_pet_for_mobile"+"&mobile_number="+mobile_number,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			count = $(xml).find('count').eq(0).text();
			if (count > 0) {
				//alert("Already petition exists");
				window.open("online_old_pet_details.php?open_form=P1&mobile_number="+mobile_number,"","fullscreen=1");
			}else{
				alert('No Petition found for this number');
			}
					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
	}
}

function loadoff_level(){
	var param = "source_frm=p1_search"
					+"&off_level_id="+ $("#off_level_id").val()
					+"&off_level_dept_id="+ $("#off_level_dept_id").val()
					+"&pre="+ $("#pre").val();
					//alert(param);
					$.ajax({
				type: "POST",
				dataType: "xml",
				url: "online_petition_details_action.php",  
				data: param,  
				
				beforeSend: function(){
					//alert( "AJAX - beforeSend()" );
				},
				complete: function(){
					//alert( "AJAX - complete()" );
				},
				success: function(xml){
					// we have the response 
					// p1_createGrid(xml);
	if ($(xml).find('off_loc_id').length == 0) {
		//alert("No records found for the given parameters");
	}
	//alert($(xml).find('off_loc_id').length);
	try{document.getElementById('off_level').innerHTML='<option value="" selected disabled>--Select--</option>';}catch(e){}
	$(xml).find('off_loc_id').each(function(i)
	{
		var off_loc_id = $(xml).find('off_loc_id').eq(i).text();
		var off_loc_name = $(xml).find('off_loc_name').eq(i).text();
		$('#off_level').append("<option value="+off_loc_id+"> "+off_loc_name+"</option>");
	
	})
	},  
				error: function(e){  
					//alert('Enter valid Code');  
				}
			});
}

function loadoff() {  //  Main Category // Included for Registration Department  alert();
//fwd_office_id=$('#hidSL').val();
	fwd_office=$('#fwd_office_level').val();
	//off_level=$('#off_level').val();
	const myArray = fwd_office.split("-");
	off_level = myArray[2];
	fwd_office_id = myArray[0];
	$("#off_level_id").val(off_level);
	$("#off_level_dept_id").val(fwd_office_id);
	get_all_officer_list();
	if (dept_id!="") {
		$.ajax({
			type: "POST",
			url: "online_petition_details_action.php",  
			data: "source_frm=Load_Office"+"&off_level="+off_level+"&fwd_office_id="+fwd_office_id,  
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(html){
				//(html);
				//alert(html.length);alert(fwd_office_id);
			if (html.length==18) {
				 if(fwd_office_id==1){
					document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";
			 loaddistsp();setTimeout(
			 function(){document.getElementById('off_level_row').style.display='none';},100) 
				 }
				 if(fwd_office_id==7){
					 document.getElementById('off_level_row').style.display='';
				/* $('#offlocation').hide();
				document.getElementById("sub_div").style.display = "none";
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("divlink").style.display = "block";
				document.getElementById("all_off").style.display = "none";
				document.getElementById("loc_off").style.display = ""; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>"; 
				loadoff_level();
				}
			}
			else if (html.length==30) { //alert(fwd_office_id);
			if(myArray[1]==9){	
			 if(fwd_office_id==1){
				 		document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";
			 loadzone();setTimeout(
			 function(){document.getElementById('off_level_row').style.display='none';},100)
				}
			}if(fwd_office_id=8){
				 document.getElementById('off_level_row').style.display='';
				/* $('#offlocation').hide();
				document.getElementById("loc_off").style.display = "";
				document.getElementById("all_off").style.display = "none";
				document.getElementById("cir_link").style.display = "";
				document.getElementById("sub_div").style.display = "none";
				document.getElementById("divlink").style.display = "none"; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";loadoff_level();
				}
			}else if (html.length==40) { 
			 if(fwd_office_id==17){//division
			 document.getElementById('off_level_row').style.display='';
				/* $('#offlocation').hide();
				document.getElementById("loc_off").style.display = "";
				document.getElementById("all_off").style.display = "none";
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("sub_div").style.display = "none";
				document.getElementById("divlink").style.display = "block"; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";loadoff_level();
				}if(fwd_office_id==22) {
					document.getElementById('off_level_row').style.display='';
				/* $('#offlocation').hide();
				document.getElementById("loc_off").style.display = "";
				document.getElementById("all_off").style.display = "none";
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("sub_div").style.display = "none";
				document.getElementById("divlink").style.display = "block"; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";loadoff_level();
				}if(fwd_office_id==18){//sub_division
				document.getElementById('off_level_row').style.display='';
				/* $('#offlocation').hide();
				document.getElementById("loc_off").style.display = "";
				document.getElementById("all_off").style.display = "none";
				document.getElementById("sub_div").style.display = "block";
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("divlink").style.display = "none"; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";loadoff_level();
				}
			}else if (html.length==34) { 
			//$('#offlocation').hide();
			document.getElementById('off_level_row').style.display='none';
			/* document.getElementById("all_off").style.display = "none";
				document.getElementById("loc_off").style.display = "";	
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("divlink").style.display = "none";
				document.getElementById("sub_div").style.display = "none"; */
				document.getElementById("offlocation").innerHTML= "<option selected />--Select--</option>";
			}else{
				document.getElementById('off_level_row').style.display='none';
				/* $('#offlocation').show();
				document.getElementById("all_off").style.display = "none";
				document.getElementById("loc_off").style.display = "block";
				document.getElementById("cir_link").style.display = "none";
				document.getElementById("divlink").style.display = "none";
				document.getElementById("sub_div").style.display = "none"; */
				document.getElementById("offlocation").innerHTML=html;loadoff_level();
				 } 
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 			
		});
		
	}
}

function avoid_Special(elementid)
{
	var iChars = "!@#$%^&*()+=-[]\\\';,{}|\":<>?";
	var val= document.getElementById(elementid).value;
	for (var i = 0; i < val.length; i++) {
		if (iChars.indexOf(val.charAt(i)) != -1) {
			alert ("Special characters are not allowed.\n Please remove them and try again.");
			document.getElementById(elementid).value="";
			document.getElementById(elementid).focus();
			return false;
		}
	}
}

function get_comm_taluk()
{
	dist=document.getElementById("comm_dist").value;
	
	$.ajax({
		type: "post",
		url: "online_petition_details_action.php",
		cache: false,
		data: {source_frm : 'taluk',district : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("comm_taluk").innerHTML=html;
			
		}
		
	}); 
	 
}

function get_sub_category() {
	var gtype = document.getElementById("griev_maincode").value;
	var dept_id=$('#dept').val();
	var fwd_office_level=$('#fwd_office_level').val();
	
	if(gtype!=""){
 
	 $.ajax({
	  type: "post",
	  url: "online_petition_details_action.php",
	  cache: false,
	  data: {source_frm : 'griev_subcategory',griev_main_code : gtype,dept_id:dept_id,fwd_office_level:fwd_office_level},
	  error:function(){ alert("some error occurred") },
	  success: function(html){
			document.getElementById("griev_subcode").innerHTML=html;
			}
	  } ); 
    } else {
		var html = '<select><option value="">--Select--</select>';
		document.getElementById("griev_subcode").innerHTML=html;
	}
}
function valchk() {
	vald();
	//var selected = document.getElementById("offlocation");
	//alert(selected.options[selected.selectedIndex].text);
	
	if(pinchk()==false){
		return;
	}
	if(document.getElementById('confirm_old').style.display!='none'){
			document.getElementById('confirm_old').focus;
			$('#confirm_old').addClass('error');
			$('#confirm_old').focus;
			$('#alrtmsg1').modal('show');
			$('#alrtmsg1').modal({ backdrop: 'static', keyboard: false })
			$("#alrtmsg").html("Please respond to Confirmation box?");
			//alert("Please respond to Confirmation box?");
			setTimeout(function(){$('#confirm_old').removeClass('error')},3000);
			validateFlg = false;
			return false;
		}else{
			validateFlg = true;
		}
	document.getElementById("alrtmsg").innerHTML="";
	document.getElementById("alrtmsg").style.display='block';
	var validateFlg = false;
	
	
	$(".divTable input[type='text'] , .divTable select, .divTable textarea").each(function( index ) {
		$(this).removeClass('error');
		if($(this).attr('data_valid')!='no')
		{
			if($.trim($(this).val())=='')
			{
					

				$(this).focus().addClass('error');
				$('#alrtmsg1').modal('show');
				$('#alrtmsg1').modal({ backdrop: 'static', keyboard: false })
				$("#alrtmsg").html($(this).attr('data-error'));
				$(this).focus();
				validateFlg = false;
				return false;			
			}
			else
			{		 
				$(this).removeClass('error');
				validateFlg = true;
			}
			
		}
	});
		//alert(document.getElementById('offlocation').value);
	
	if(validateFlg==true)
	{ 
	if($('#offlocation :selected').text()=='--Select--'){
			//alert($('#offlocation').val());
			$('#offlocation').focus().addClass('error');
				$('#alrtmsg1').modal('show');
				$('#alrtmsg1').modal({ backdrop: 'static', keyboard: false })
			$("#alrtmsg").html($('#offlocation').attr('data-error'));
			$('#offlocation').focus();
			validateFlg = false;
			return false;
		}
		griev_val=document.getElementById("grievance").value.length;
		if(griev_val < 3)
		{
			alert("Grievance cannot be less than 3 characters");
			document.getElementById("grievance").focus();		 
			validateFlg = false;
			return false;
		}
		
		
	}
	if(validateFlg==true) {
			var district = $('#district').val();
			if (fwd_office_level == '') {
				if (district == '') {
					alert('You have not selected any Office. Select atleast District to continue.');
					validateFlg = false;
				}
			}
			
		}
		
		
		if(validateFlg==true) {
			//alert();
			document.getElementById("hid").value='done';
			document.getElementById("mobile_number").disabled=false;
			document.getElementById("save").disabled=true;
			document.getElementById("divBackground").style.display='';
			document.petiton_detail_entry.method="post";		
			document.petiton_detail_entry.action = "online_petition_detail_entry.php";
			document.petiton_detail_entry.submit();
			return true;
		}
	//}
}

	/*
	if(validateFlg==true)
	{
		tlkval=$('#gre_taluk').val();
		rev_villval=$('#gre_rev_village').val();
		blkval=$('#gre_block').val();
		pan_villval=$('#gre_tp_village').val();
		urbanval=$('#gre_urban_body').val(); 
		hid_pattern_id = document.getElementById("pat_id").value;	
		if(hid_pattern_id==1){
			if($('#off_level_id').val()==2)
			{
				if(tlkval=='')
				{ 
					alert('You have not selected taluk. Select taluk to continue.');
					validateFlg = false;
				} else if(rev_villval=='')
				{
					alert('You have not selected Revenue Village. Select Revenue Village to continue.');
					validateFlg = false;
				}

			}
			else if($('#off_level_id').val()==4)
			{
				if(rev_villval=='')
				{
					alert('You have not selected Revenue Village. Select Revenue Village to continue.');
					validateFlg = false;
				}
			}
		}
		else if(hid_pattern_id==2)
		{
			if($('#off_level_id').val()==2)
			{
				if(blkval=='')
				{
					alert('You have not selected block. Select block to continue.');
					validateFlg = false;
				} else if(pan_villval=='')
				{
					alert('You have not selected Panchayat Village. Select Panchayat Village to continue.');
					validateFlg = false;
				}
			}
			else if($('#off_level_id').val()==6)
			{
				if(pan_villval=='')
				{
					alert('You have not selected Panchayat Village. Select Panchayat Village to continue.');
					validateFlg = false;
				}
			}
		}
		else if(hid_pattern_id==3)
		{
			if(urbanval=='')
			{
				alert('You have not selected urban. Select urban to continue.');
				validateFlg = false;
			}
		}
		else if(hid_pattern_id==4) 
		{
			office=$('#gre_division').val();
			sub_div=$('#gre_subdivision').val();
			circle=$('#gre_circle').val();
			if(office=='' && sub_div=='' && circle=='')
			{
				alert('You have not selected Office. Select Office to continue.');
				validateFlg = false;
			}
		}
		*/
	
function get_comm_village() {
	taluk=$('#comm_taluk').val();
	dist=$('#comm_dist').val();

	if(taluk==0){
		//$("#comm_rev_village").val('');
		document.getElementById("comm_rev_village").options.length = 1;
		return false;
	} 
	$.ajax({
		type: "post",
		url: "online_petition_details_action.php",
		cache: false,
		data: {source_frm : 'gre_village',talukid : taluk,distid : dist},
		error:function(){ alert("some error occurred") },
		success: function(html){
			document.getElementById("comm_rev_village").innerHTML=html;
		}
	}); 
}
function textCounter(field,cntfield,maxlimit)
{
 	   if(field.value.length > maxlimit)
           field.value = field.value.substring(0,maxlimit);
       else
           cntfield.value = maxlimit - field.value.length; 
} 
function characters_numsonly_grievance(e) 
{ 	
	var unicode=e.charCode? e.charCode : e.keyCode;
	//alert(unicode);
	if (unicode!=8 && unicode!=9 && unicode!=46)
	{
//if ( (unicode >64 && unicode<123 && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode>=45 || unicode>=47 && unicode<=57))
	if ( (((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || unicode==32 || unicode>=44 && unicode<=59)
			return true
	else
			return false
	}
}


function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if (unicode!=8 && unicode !=9)
	{
		if(unicode<48||unicode>57)
		return false
	}
}
function characters_numsonly(e) 
	{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		//alert(unicode);
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((((unicode >64 && unicode<123) || (unicode >=2304 && unicode<=3583)) && unicode!=96 && unicode!=95 && unicode!=94 && 
		unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode==45 || unicode>=47 && unicode<=57))
				return true
		else
				return false
		}
	}

function p1_returnDesignationSearch(petition_id, userID, offLoc_designName){
	document.getElementById("all_off").style.display = "block";
	document.getElementById("divlink").style.display = "none";
	document.getElementById("sub_div").style.display = "none";
	document.getElementById("cir_link").style.display = "none";
	$("#off_name1").removeAttr('disabled');
	$('#off_id').val(userID);
	$('#locid').val(userID);
	$('#off_d_id').val(userID);
	$('#off_name1').val(offLoc_designName);
	$('#offloc1').val(userID); 
	/* document.getElementById("offloc1").value=userID;
	document.getElementById("offloc1").style.display = "";
	//document.getElementById("offloc1").type = "text";
	//document.getElementById("offloc1").type = "text";
	//document.getElementById("off_d_id").type = "text";
	//document.getElementById("locid").type = "text";
	document.getElementById("off_d_id").value=userID;
	document.getElementById("off_d_id").style.display = "";
	document.getElementById("locid").value=userID;
	document.getElementById("locid").style.display = ""; */
}

function lo(){
	var fwd=$('#fwd_office_level').val();
	fwd=fwd.split('-');
	//alert(fwd[1]+fwd[2]+fwd[3]+fwd[0]);
	pattern= fwd[0];
	off_level_id= fwd[1];
	off_level_dept_id= fwd[2];
	off_level_office_id= fwd[3];
	$('#pattern').val(pattern);
	$('#off_level_id').val(off_level_id);
	$('#offname').val(off_level_id);
	$('#off_level_dept_id').val(off_level_dept_id);
	$('#off_level_office_id').val(off_level_office_id);
}

function loadOfficeLocations(){
	var fwd=$('#fwd_office_level').val();
	fwd=fwd.split('-');
	pattern= fwd[0];
	off_level_id= fwd[1];
	off_level_dept_id= fwd[2];
	off_level_office_id= fwd[3];
	$('#pattern').val(pattern);
	$('#off_level_id').val(off_level_id);
	$('#off_level_dept_id').val(off_level_dept_id);
	$('#off_level_office_id').val(off_level_office_id);
	dept_off_level_pattern_id=pattern;
	dept_off_level_office_id=off_level_office_id;
	dept_id=1;
	district=$('#off_level').val();
	
		$.ajax({
			type: "post",
			url: "online_petition_details_action.php",
			cache: false,
			data: {source_frm : 'loadLocations',off_level_id : off_level_id,dept_off_level_pattern_id:dept_off_level_pattern_id,dept_off_level_office_id:dept_off_level_office_id,dept_id:dept_id,off_level_dept_id:off_level_dept_id,district:district},
			error:function(){ alert("Enter Office Level") },
			success: function(html){
				document.getElementById("offlocation").innerHTML=html;//alert(html.length);
				if(html.length==45){
					document.getElementById("loc_off").style.display='none';
					document.getElementById("offlocation").style.display='none';
				}else{
					document.getElementById("loc_off").style.display='';
					document.getElementById("offlocation").style.display='';
					}
				if(document.getElementById("offlocation").value!=''){
					document.getElementById("off_d_id").value=document.getElementById("offlocation").value;
				}
				
			}
		});
	
	//alert(pattern);
}

 function charactersonly(e) 
	{ 	
	 
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46 )
		{
		if ((unicode >64 && unicode<123 && unicode!=34 && unicode!=41 && unicode!=96 && unicode!=95 && 
		unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32))
				return true
		else
				return false
		}
	}
	function avoid_special_grievance(elementid)
{
	//alert("12345");
	var iChars = "!@#$%^&*()+=[]\\\';,{}|\":<>?";
	var val= document.getElementById(elementid).value;
	for (var i = 0; i < val.length; i++) {
		if (iChars.indexOf(val.charAt(i)) != -1) {
			alert ("Special characters are not allowed.\n Please remove them and try again.");
			document.getElementById(elementid).value="";
			document.getElementById(elementid).focus();
			return false;
		}
	}
}

function v_reset() {
  $("#offlocation").removeAttr('display');
  document.getElementById("off_name1").style.display = "none";
  document.getElementById("off_level_row").style.display = "none";
  document.getElementById("offlocation").style.display = "block";
  document.getElementById('hid_pet_old').value="";
  document.getElementById('confirm_old').style.display="none";
  document.getElementById("petiton_detail_entry").reset();
  	$('#related').innerHTML='';
  }

function add_old(){
	document.getElementById('confirm_old').style.display="none";
	old=$('#hid_pet_old').val();
	document.getElementById('related').innerHTML='<b>  Related Petition no.: '+old+"</b>";
	document.getElementById('related').style.display="";
}

function remove_old(){
	document.getElementById('confirm_old').style.display="none";
	document.getElementById('hid_pet_old').value='';
	$('#related').innerHTML='';
	document.getElementById('related').style.display="none";
}

function get_all_officer_list() {
	//var sub_div_exists = 'N';
	//var get_circle_exists = $('#get_circle_exists').val();
	hid_pattern_id=$('#off_level_dept_id').val();
	
	griev_main_code=$('#griev_maincode').val();	
	//griev_sub_code=$('#griev_subcode').val();	
	department_id = $('#dept').val();
	off_level = $('#off_level_id').val();
	//district_id = $('#district').val();
	source = $('#source').val();//alert(source);
	//alert(hid_pattern_id);
	if (hid_pattern_id == 1) {
		var table1='';var name='';var pre='';
		if($('#off_level_id').val()==7) {
			pre='division';
			}else if($('#off_level_id').val()==8) {
			pre='circle';
			}	 
				
		} else if (hid_pattern_id == 3) {
		    
			if($('#off_level_id').val()==17) {
			pre='division';
			}else if($('#off_level_id').val()==18) {
			pre='subdivision';
			}else if($('#off_level_id').val()==19) {
			pre='circle';
			}	
		} 	
	 else if (hid_pattern_id == 4) {
		

		if($('#off_level_id').val()==21) {
			pre='division';
			}else if($('#off_level_id').val()==22) {
			pre='subdivision';
			}else if($('#off_level_id').val()==23) {
			pre='circle';
			}	
	}
	$('#pre').val(pre);

	/* if($('off_name1').clicked == true)
{
   alert("button was clicked");
}
	
	openForm("Online_officer_Form.php?open_form=P1&off_level_id="+off_level+"&off_level_dept_id="+hid_pattern_id
		+"&dept_id="+department_id+"&pre="+pre, "office_location_search");								 */										
	
}
function vald(){
if(($('#offlocation').val()==null)||(($('#off_d_id').val()!=null))){
	document.getElementById("locid").value=$('#off_d_id').val();
	$("#offlocation").attr( 'data_valid', 'no' );
}else{
	document.getElementById("locid").value=$('#offlocation').val();
	$('#offlocation').attr( 'data_valid', 'yes' );
}}
function chk(){
	if($('#offlocation').val()!=''){
		document.getElementById("off_d_id").value=($('#offlocation').val());
	}
}
function pinchk(){
	/* const mail = document.getElementById("email");
	var email=$('#email').val();
	var mailformat = /^w+([.-]?w+)*@w+([.-]?w+)*(.w{2,3})+$/;
if (email!=''){
if(!mail.value.match(mailformat))
{
 alert("You have entered an invalid email address!")
    document.getElementById("mail").focus();
	validateFlg = false;
	return false;
	$('#email').val()=email;
    
  }else{
   validateFlg = true;
		$('#email').val()=email;
	}   */
	var email=$('#email').val();
	if (email!=''){
	var x=$('#email').val();
var atposition=x.indexOf("@");  
var dotposition=x.lastIndexOf(".");  
if (atposition<1 || dotposition<atposition+2 || dotposition+2>=x.length){  
  alert("Please enter a valid e-mail address"); 
validateFlg = false;  
return false;  
	}else{
		validateFlg = true;
	}
	}
	const pincode = document.getElementById("pincode");
	var pin=$('#pincode').val();
	let valid = true;
	
	//alert($('#email').val());
	if ((/(^[6][0-9]{5}$)/).test(pin)) { 
	validateFlg = true;
	}else{
		alert("Please enter Valid Pincode");
		document.getElementById("pincode").focus();
		validateFlg = false;
		return false;
}
}

function p1_returnPetionDetails(petition_id){
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_petition_details"+"&petition_id="+petition_id,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
			
		},
		success: function(xml){		
			$('#pet_eng_initial').val('');
			$('#pet_ename').val('');
			$('#father_ename').val('');
			$('#gender').val('');
			$('#comm_doorno').val('');
			$('#comm_street').val('');
			$('#comm_area').val('');
			$('#comm_pincode').val('');
			$('#idtype_id').val('');
			$('#idtype_no').val('');
			$('#email').val('');
			$('#grievance').val('');
			$('#griev_maincode').val('');
			$('#griev_subcode').val('');
			$('#griev_maincode').val($(xml).find('griev_type_id').eq(0).text());
			get_sub_category();
			
			$('#pet_eng_initial').val( $(xml).find('petitioner_initial').eq(0).text());		
			$('#pet_ename').val( $(xml).find('petitioner_name').eq(0).text());		
			$('#father_ename').val( $(xml).find('father_husband_name').eq(0).text());		
			$('#gender').val( $(xml).find('gender_id').eq(0).text());		
			$('#comm_doorno').val( $(xml).find('comm_doorno').eq(0).text());		
			$('#comm_street').val( $(xml).find('comm_street').eq(0).text());		
			$('#comm_area').val( $(xml).find('comm_area').eq(0).text());		
			$('#pincode').val( $(xml).find('comm_pincode').eq(0).text());		
			if($(xml).find('comm_dist').eq(0).text()!=''){
			$('#comm_dist').val( $(xml).find('comm_dist').eq(0).text());
			comm_dist = $(xml).find('comm_dist').eq(0).text();
			comm_taluk_id = $(xml).find('comm_taluk_id').eq(0).text();
			//populateTaluk(comm_dist,comm_taluk_id);		
			comm_rev_village_id = $(xml).find('comm_rev_village_id').eq(0).text();
//populateRevVillage(comm_dist,comm_taluk_id,comm_rev_village_id);
			}
			//polulateSubcategory($(xml).find('griev_type_id').eq(0).text(),$(xml).find('griev_subtype_id').eq(0).text());
			$('#idtype').val( $(xml).find('idtype_id').eq(0).text());
			$('#idno').val( $(xml).find('id_no').eq(0).text());
			$('#email').val( $(xml).find('comm_email').eq(0).text());
			//$('#pet_community').val( $(xml).find('petitioner_category_id').eq(0).text());
			//$('#petitioner_category').val( $(xml).find('pet_community_id').eq(0).text());
			$('#grievance').val( $(xml).find('grievance').eq(0).text());
			$('#pet_type').val($(xml).find('pet_type_id').eq(0).text());
			setTimeout(function(){$('#griev_subcode').val($(xml).find('griev_subtype_id').eq(0).text());},500);
			
			//get_sub_category();
			
			$('#hid_pet_old').val($(xml).find('pet_no').eq(0).text());
			//alert($(xml).find('pet_no').eq(0).text());

			
					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
}

function p1_returnPetionorPersonalDetails(mob_no) {
	//alert ("Hello");
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "pm_pet_detail_entry_get_dept_action.php",  
		data: "mode=get_petitioner_details"+"&mobile_number="+mob_no,  
				
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
			
		},
		success: function(xml){			//alert();
			v_reset();
			$('#pet_eng_initial').val( $(xml).find('petitioner_initial').eq(0).text());		
			$('#pet_ename').val( $(xml).find('petitioner_name').eq(0).text());		
			$('#father_ename').val( $(xml).find('father_husband_name').eq(0).text());		
			$('#gender').val( $(xml).find('gender_id').eq(0).text());		
			$('#comm_doorno').val( $(xml).find('comm_doorno').eq(0).text());		
			$('#comm_street').val( $(xml).find('comm_street').eq(0).text());		
			$('#comm_area').val( $(xml).find('comm_area').eq(0).text());		
			$('#pincode').val( $(xml).find('comm_pincode').eq(0).text());		

			$('#idtype').val( $(xml).find('idtype_id').eq(0).text());
			$('#idno').val( $(xml).find('id_no').eq(0).text());
			$('#email').val( $(xml).find('comm_email').eq(0).text());
			$('#pet_community').val( $(xml).find('petitioner_category_id').eq(0).text());
			$('#petitioner_category').val( $(xml).find('pet_community_id').eq(0).text());
		//	$('#hid_old_pet').val($(xml).find('pet_no').eq(0).text()); 
			/* $('#grievance').val( $(xml).find('grievance').eq(0).text());
			$('#pet_type').val($(xml).find('pet_type_id').eq(0).text());
			$('#griev_maincode').val($(xml).find('griev_type_id').eq(0).text());*/
			
			//alert($(xml).find('pet_no').eq(0).text());
			//alert($(xml).find('comm_dist').eq(0).text());
			if($(xml).find('comm_dist').eq(0).text()!=''){
			$('#comm_dist').val( $(xml).find('comm_dist').eq(0).text());
			
			comm_dist = $(xml).find('comm_dist').eq(0).text();
			comm_taluk_id = $(xml).find('comm_taluk_id').eq(0).text();
			}
/* 			polulateSubcategory($(xml).find('griev_type_id').eq(0).text(),$(xml).find('griev_subtype_id').eq(0).text());
 */					
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		} 
	});//ajax end
}
</script>
