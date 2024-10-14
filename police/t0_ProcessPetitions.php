<?PHP 
session_start();
$pagetitle="Petition Process";
include("db.php");
include("header_menu.php");
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
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz for get userprofile data
include("common_form_function.js");

?>

<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/common_form_function.js"></script>

<!-- Date Picker css-->
<link rel="stylesheet" href="css/jquery.datepick.css" media="screen" type="text/css">
<link rel="stylesheet" href="css/petpopup.css" media="screen" type="text/css">
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<link rel="stylesheet" href="css/petpopup.css" type="text/css">
<label style="display:none">
<img src="images/calendar.gif" id="calImg">
</label>
<!-- Date Picker css-->

<script type="text/javascript">

	
  window.onload = function () {
        document.onkeydown = function (event) {
			switch (event.keyCode) { 
			case 116 : //F5 button
				event.returnValue = false;
				event.keyCode = 0;
				return false; 
			case 82 : //R button
				if (event.ctrlKey) { 
					event.returnValue = false; 
					event.keyCode = 0;  
					return false; 
				} 
			}
        };
    }
	
$(document).ready(function() {
	
	$(document).bind("contextmenu",function(e){
	  return false;
	}); 
	   
	$(".tab_content").hide();
	$(".tab_content:first").show(); 
	$("ul.tabs li").click(function() {
		
		var activeTab = $(this).attr("rel"); 
		if(activeTab=="tab2")
		{
			p2_loadGrid(1, $('#p2_pageSize').val());
		}
		else if(activeTab=="tab3")
		{
			p3_loadGrid(1, $('#p3_pageSize').val());
		}
		else if(activeTab=="tab4")
		{
			
			p5_loadGrid(1, $('#p5_pageSize').val());
		}
		else if(activeTab=="tab5")
		{
			p4_loadGrid(1, $('#p4_pageSize').val());
		}
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();
		$("#"+activeTab).fadeIn(); 
	});
	
	//onload page
	if($("#tabName").val()=='t1')
	{
		p1_loadGrid(1, $('#p1_pageSize').val());
	}
	else if($("#tabName").val()=='t2')
	{
		p2_loadGrid(1, $('#p2_pageSize').val());
	}
	else if($("#tabName").val()=='t3')
	{
		p3_loadGrid(1, $('#p3_pageSize').val());
	}
	else if($("#tabName").val()=='t4')
	{
		p4_loadGrid(1, $('#p4_pageSize').val());
	}
	else if($("#tabName").val()=='t5')
	{
		p5_loadGrid(1, $('#p5_pageSize').val());
	}
});

function setPetitionCount(countId, count){
	$("#"+countId).text(count+" ");
}
// for date validation
function validatedate(inputText,elementid){
   
     var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;  
   
if(inputText.value.match(dateformat))  
{  
	  document.profile.inputText.focus();  
	  
	  var opera1 = inputText.value.split('/');  
	  var opera2 = inputText.value.split('-');  
	  lopera1 = opera1.length;  
	  lopera2 = opera2.length;  
	    
	  if (lopera1>1)  
	  {  
	  var pdate = inputText.value.split('/');  
	  }  
	  else if (lopera2>1)  
	  {  
	  var pdate = inputText.value.split('-');  
	  }  
	  var mm  = parseInt(pdate[0]);  
	  var dd = parseInt(pdate[1]);  
	  var yy = parseInt(pdate[2]);  
	    
	  var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];  
	  if (mm==1 || mm>2)  
	  {  
	  if (dd>ListofDays[mm-1])  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
	  if (mm==2)  
	  {  
	  var lyear = false;  
	  if ( (!(yy % 4) && yy % 100) || !(yy % 400))   
	  {  
	  lyear = true;  
	  }  
	  if ((lyear==false) && (dd>=29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  if ((lyear==true) && (dd>29))  
	  {  
	  alert('Invalid date format!');  
	  return false;  
	  }  
	  }  
}  
  else  
  {  
  	alert("Invalid date format!");  
    document.getElementById(elementid).value=""; 
    document.getElementById(elementid).focus(); 
  return false;  
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
	if (unicode!=8 && unicode !=9 && unicode !=47)
	{
		if(unicode<48||unicode>57)
		return false
	}
}
function characters_numsonly(e) 
	{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >=65 && unicode<123 && unicode!=96 && unicode!=95 && unicode!=94 && 
		unicode!=93 && unicode!=92 && unicode!=91 ) || (unicode==32 || unicode>=47 && unicode<=57))
				return true
		else
				return false
		}
	}
function checkPetNo(e) 
{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || 
		(unicode == 12 || (unicode>=33 && unicode <=40) || unicode == 45 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
}
	
</script>
<?php
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$qry = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
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
$user_id=$_SESSION['USER_ID_PK'];
//Fetch user level
$login_lvl=$_SESSION['LOGIN_LVL'];
?>
<input type="hidden" name="login_lvl" id="login_lvl" value="<?PHP echo $login_lvl;?>"/>
<div class="form_heading" style="background-color:#bc7676;">
    <div class="heading">
    	<?PHP echo $label_name[0];//Petition Processing?>
  </div>
</div>
<?PHP 
$flag = false;
$show_apge = false;
$show_tap_1_2=false;//To forward and Forwarded to us
$show_tap_3_4=false;//To review and Temporary Reply
$show_tap_5=false;//To dispose	
		
//check role for logged in user's view tabs
 //echo "===============================".$userProfile->getDesig_roleid();
if($userProfile->getPet_act_ret() || $userProfile->getDesig_roleid() == 5){
	//echo "============================";
	$show_apge = true;
	
	if ($userProfile->getDesig_roleid() == 5) {
		
		if ($userProfile->getDept_off_level_pattern_id() != '' || $userProfile->getDept_off_level_pattern_id() != null) {
			$condition = " and dept_off_level_pattern_id=".$userProfile->getDept_off_level_pattern_id().""; 
		} else {
			$condition = " and off_level_dept_id=".$userProfile->getOff_level_dept_id().""; 
		}	
		$sql="select a.dept_user_id, a.dept_desig_id, a.dept_desig_name, a.dept_desig_tname, a.dept_desig_sname, a.off_level_dept_name, a.off_level_dept_tname, a.off_loc_name, a.off_loc_tname, a.off_loc_sname, a.dept_id, a.off_level_dept_id, a.off_loc_id 
		from vw_usr_dept_users_v_sup a
		--inner join usr_dept_sources_disp_offr b on b.dept_desig_id=a.dept_desig_id
		where off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." 
		and dept_id=".$userProfile->getDept_id(). " and off_loc_id=".$userProfile->getOff_loc_id()." 
		and off_level_id = ".$userProfile->getOff_level_id()." and pet_act_ret=true and pet_disposal=true ".$condition."";

		$rs=$db->query($sql);
		$rowarray = $rs->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			$sup_dept_user_id =  $row['dept_user_id'];
		}
		/* $inSql="SELECT row_number() over () as rownumber,* FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) a".$codn." ORDER BY pet_action_id"; */
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
		if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
			$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
			$aog_dept_user_id =  $_SESSION['USER_ID_PK'];
		} else {
			$dept_user_id = $sup_dept_user_id;
			$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
		}
	} else {
		$dept_user_id =  $_SESSION['USER_ID_PK'];
	}
	
	if(($userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() && $userProfile->getPet_disposal() && ($userProfile->getOff_level_id() == 7 || $userProfile->getOff_level_id() == 9|| $userProfile->getOff_level_id() == 11|| $userProfile->getOff_level_id() == 13)) || $userProfile->getDesig_roleid() == 5)
	{
		$show_tap_1=true;//show tab1
	}
	if(($userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() && $userProfile->getPet_act_ret()) || $userProfile->getDesig_roleid() == 5)
	{
		$show_tap_2=true;//show tab2
	}
	if(($userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() && $userProfile->getPet_act_ret() && $_SESSION['LOGIN_LVL']==NON_BOTTOM && $userProfile->getPet_forward())|| $userProfile->getDesig_roleid() == 5)
	{
		$show_tap_3=true;//show tab3
	}
	if(($userProfile->getDept_pet_process() && $userProfile->getOff_pet_process() && $userProfile->getPet_disposal()) || $userProfile->getDesig_roleid() == 5)
	{
		$show_tap_4=true;//show tab5 
	}

	if(($userProfile->getDept_pet_process() && $userProfile->getOff_pet_process()  && $userProfile->getPet_act_ret()) || $userProfile->getDesig_roleid() == 5)
	{
		$show_tap_5=true;//show tab4
	}
	
	
}
//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>";
if(!$show_apge){
	header('HTTP/1.0 401 Unauthorized');
	include("com/access_denied.php");
	die();
}
$tabName='';
?>
<div class="star" style="width:98%;">  
    	<marquee behavior='alternate'>Logged-in User cannot assign the Petition to the Self after taking any action.</marquee>
  </div>
<div id="container" style="width:98%;background-color:#bc7676;">
	
    <ul class="tabs">
        <?php 
            if($show_tap_1)
			{
				
		$fwd_offr_cond='';
		
		if($userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2 && $userProfile->getDept_desig_id()==16){
			$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,20)=20) and a.source_id=5 ";
		}
		else if($userProfile->getDesig_coordinating() 
		&& $userProfile->getOff_coordinating() 
		&& $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==2){
			$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,20)=20) and a.source_id!=5 ";
		}
		else if($userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==4 && $userProfile->getDept_desig_id()==56){
			$fwd_offr_cond=" AND a.griev_taluk_id=".$userProfile->getOff_loc_id()." and (coalesce(a.fwd_office_level_id,40)=40) and a.source_id=5 ";
		}
//		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !$userProfile->getDept_coordinating() && ($userProfile->getOff_level_id()==2 || $userProfile->getOff_level_id()==10)){
		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==2){
			$fwd_offr_cond=" AND a.griev_district_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30) in (select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") and a.source_id!=5 ";
		}
		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==10){
			$fwd_offr_cond=" AND a.griev_division_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,30)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") and a.source_id!=5 ";
		}
/*		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getOff_level_id()==10){
			$fwd_offr_cond=" AND a.griev_division_id=".$userProfile->getOff_loc_id()." and  ((coalesce(a.fwd_office_level_id,20)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and (dept_id=".$userProfile->getDept_id()." and  dept_id in (SELECT c.dept_id from usr_dept_desig_disp_sources a inner join lkp_pet_source a1 on a1.source_id=a.source_id inner join usr_dept_desig b on b.dept_desig_id=a.dept_desig_id inner join usr_dept_off_level c on c.off_level_dept_id=b.off_level_dept_id where case when (".$userProfile->getDistrict_id()."=any(a.agri_districts)) then true else false end) ) ) ";
		}*/
		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && $userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
			$fwd_offr_cond=" and coalesce(a.fwd_office_level_id,10)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id)) and dept_id=".$userProfile->getDept_id()."";
		}
		else if($userProfile->getDesig_coordinating() && $userProfile->getOff_coordinating() && !$userProfile->getDept_coordinating() && $userProfile->getOff_level_id()==1){
			$fwd_offr_cond=" and ((coalesce(a.fwd_office_level_id,10)=(select fwd_office_level_id from lkp_fwd_office_level where ".$userProfile->getOff_level_id()."=any(off_level_id))) and dept_id=".$userProfile->getDept_id().") ";
		}
		else{
			$fwd_offr_cond=" and false ";
		}
				

				
		$sql_count = "SELECT count(*)
		FROM pet_master a
		WHERE".$agri_condition." (source_id < 0 or source_id = 5) and NOT EXISTS (
			SELECT * FROM pet_action b WHERE b.petition_id = a.petition_id 
		) ".$fwd_offr_cond;
			
				$count = $db->query($sql_count)->fetch(PDO::FETCH_NUM);
				if ($userProfile->getDept_desig_id()==16 || $userProfile->getDept_desig_id()==56) {
					$lbl_name = 'Online Jamabandhi';
				} else {
					$lbl_name = $label_name[1];
				}
				?>
                <li class="<?PHP echo $tabName==''?'active':''?>" rel="tab1"><?PHP echo $lbl_name;//To Forward?> (<span id="p1_count"> <?PHP echo $count[0];?> </span><?PHP echo " ".$label_name[31];//Petitions ?>)</li>
				<?php	
				$tabName=$tabName==''?"t1":$tabName;	
            }
        ?>
        
        <?php 
			
			if($show_tap_2)
            {
				//echo "======================";
				if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
					//$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
					$dept_user_id =  $aog_dept_user_id;
				} else if ($userProfile->getDesig_roleid() == 5) { 
					$dept_user_id = $sup_dept_user_id;
					//$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
				} else {
					$dept_user_id = $_SESSION['USER_ID_PK'];
				}
		
				if ($userProfile->getDesig_roleid() == 5) {
					$sql_count = "SELECT count(petition_id) FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D']) where pet_type_id != 4";
				} else {
					$sql_count = "SELECT count(petition_id) FROM fn_Petition_Action_Taken(".$dept_user_id.",array['F','Q','D'])";
				}
			
				$count=  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
				
				?>
                <li class="<?PHP echo $tabName==''?'active':''?>" rel="tab2"><?PHP echo $label_name[2];//Forwarded to Us?> (<span id="p2_count"> <?PHP echo $count[0];?> </span> <?PHP echo " ".$label_name[31];//Petitions ?>)</li>
				<?php	
				$tabName=$tabName==''?"t2":$tabName;
            }
        ?>
        
        <?php 
			if($show_tap_3)
            {
				if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
					//$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
					$dept_user_id =  $aog_dept_user_id;
				} else if ($userProfile->getDesig_roleid() == 5) { 
					$dept_user_id = $sup_dept_user_id;
					//$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
				} else {
					$dept_user_id = $_SESSION['USER_ID_PK'];
				}
				
				if ($userProfile->getDesig_roleid() == 5) {
					$sql_count = "SELECT count(*) FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a where pet_type_id!=4";
				} else {
					$sql_count = "SELECT count(*) FROM fn_Petition_Action_Taken(".$_SESSION['USER_ID_PK'].",array['C','E','N','I','S']) a";
				}

	$codnExOwnOffPet = "";
	//if logged in user is boss of the office then Exclude own office pettion 
	if($userProfile->getPet_disposal()){
		$codnExOwnOffPet = " WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE";
	} else if ($userProfile->getDesig_roleid() == 5) {
		$codnExOwnOffPet = " and fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = FALSE and pet_type_id!=4";
	}
	//echo $sql_count.$codnExOwnOffPet;
	
				$count =  $db->query($sql_count.$codnExOwnOffPet)->fetch(PDO::FETCH_NUM);
				?>
               	<li class="<?PHP echo $tabName==''?'active':''?>" rel="tab3"><?PHP echo $label_name[3];//To Review ?>(<span id="p3_count"> <?PHP echo $count[0];?> </span> <?PHP echo " ".$label_name[31];//Petitions ?>)</li>
				<?php
				$tabName=$tabName==''?"t3":$tabName;
            }
			
        ?>
		
		 <?php 
			if($show_tap_4)
            {
				if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
					//$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
					$dept_user_id =  $aog_dept_user_id;
				} else if ($userProfile->getDesig_roleid() == 5) { 
					$dept_user_id = $sup_dept_user_id;
					//$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
				} else {
					$dept_user_id = $_SESSION['USER_ID_PK'];
				}
				
				if ($userProfile->getDesig_roleid() == 5) {
					$dept_user_id = $sup_dept_user_id;
				$sql_count = "SELECT count(*)
				FROM fn_Petition_Action_Taken(".$dept_user_id.",array['C','E','N','I','S']) a 
				WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = TRUE ".$pet_entby_cond." 
				and pet_type_id!=4";
				} else {
				$sql_count = "SELECT count(*)
				FROM fn_Petition_Action_Taken(".$_SESSION['USER_ID_PK'].",array['C','E','N','I','S']) a 
				WHERE fn_pet_origin_from_myself(a.petition_id,".$dept_user_id.") = TRUE";	
				}
			
				$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
				?>
               	<li class="<?PHP echo $tabName==''?'active':''?>" rel="tab4"><?PHP echo $label_name[4];//To Dispose ?>(<span id="p5_count"> <?PHP echo $count[0];?> </span> <?PHP echo " ".$label_name[31];//Petitions ?>)</li>
				<?php
				$tabName=$tabName==''?"t4":$tabName;
            }
			
        ?>  
        
        <?php 
			if($show_tap_5)
            {
				if ($userProfile->getDept_desig_id() == 76 || $userProfile->getDept_desig_id() == 77 ||$userProfile->getDept_desig_id() == 78 ||$userProfile->getDept_desig_id() == 79 ||$userProfile->getDept_desig_id() == 80) {
					//$pet_entby_cond = " and a.pet_entby=".$_SESSION['USER_ID_PK']."";
					$dept_user_id =  $aog_dept_user_id;
				} else if ($userProfile->getDesig_roleid() == 5) { 
					$dept_user_id = $sup_dept_user_id;
					//$pet_entby_cond = " and a.pet_entby in (".$_SESSION['USER_ID_PK'].",".$dept_user_id.")";
				} else {
					$dept_user_id = $_SESSION['USER_ID_PK'];
				}
				
				if ($userProfile->getDesig_roleid() == 5) {
					$dept_user_id = $sup_dept_user_id;					
				  $sql_count = "SELECT count(*) FROM pet_action a inner join pet_master b on b.petition_id=a.petition_id WHERE a.action_type_code='T' AND a.action_entby=".$dept_user_id." and b.pet_type_id!=4 ";
				} else {
					$sql_count = "SELECT count(*) FROM pet_action a WHERE a.action_type_code='T' AND a.action_entby=".$dept_user_id."";
				}
				$count =  $db->query($sql_count)->fetch(PDO::FETCH_NUM);
				
				if ($userProfile->getDept_desig_id()==56) {
					$t_lbl_name = 'Office Jamabandhi / Temporary Reply';
				} else {
					$t_lbl_name = $label_name[5];
				}
				
				?>
               	<li class="<?PHP echo $tabName==''?'active':''?>" rel="tab5"><?PHP echo $t_lbl_name;//Temporary Reply ?>(<span id="p4_count"> <?PHP echo $count[0];?> </span> <?PHP echo " ".$label_name[31];//Petitions ?>)</li>
				<?php
				$tabName=$tabName==''?"t5":$tabName;
            }
        ?>       
        
    </ul>
    <!-- used for which tab to be loaded at the start up form load-->
	<input type="hidden" name="tabName" id="tabName" value="<?PHP echo $tabName;?>"/>
    <div class="tab_container"> 
		
        <!-- #tab1 -->
		<?php 
            if($show_tap_1)
			{
		?>
                <div id="tab1" class="tab_content"> 
				<?php
                include("t1_ProcessToForwardForm.php");
				?>
                </div><!--End #tab1 -->
		<?php		
            }
        ?>
        
        <?php 
            if($show_tap_2)
			{
				
				?>
                <div id="tab2" class="tab_content"> 
				<?php
                include("t2_ProcessForwardedToUsForm.php");
				?>
                </div><!-- #tab2 -->
		<?php		
            }
        ?>
        
        <?php 
            if($show_tap_3)
			{
				?>
                <div id="tab3" class="tab_content"> 
				<?php
                include("t3_ProcessReviewForm.php");
				?>
                </div><!-- #tab3 -->
		<?php		
            }
        ?>
		
		
        <?php 
			if($show_tap_4)
            {
				?>
                <div id="tab4" class="tab_content"> 
				<?php
                include("t4_ProcessDisposalForm.php");
				?>
                </div><!-- #tab5 -->
		<?php		
            }
        ?>
        
                
        <?php 
			if($show_tap_5)
            {
				?>
                <div id="tab5" class="tab_content"> 
				<?php
                include("t5_ProcessTempReplyForm.php");
				?>
                </div><!-- #tab4 -->
		<?php		
            }
        ?>
     <div>  	
       
     </div>  
    </div> <!-- .tab_container --> 
	
</div> <!-- #container -->
<input type="hidden" name="desig_role" id="desig_role" value="<?php echo $userProfile->getDesig_roleid();?>" />
<input type="hidden" name="dept_user_id" id="dept_user_id" value="<?php echo $userProfile->getDept_user_id();?>" />
<input type="hidden" name="off_level_id" id="off_level_id" value="<?php echo $userProfile->getOff_level_id();?>" />
 <?php include("footer.php"); ?>