<?php
session_start();
ob_start();
include("db.php");
include("Pagination.php");
include("common_fun.php"); 
include_once 'common_lang.php';

header("Content-Security-Policy: default-src 'self' 'unsafe-eval';object-src 'self'; script-src 'self' 'nonce-1a2b'", TRUE);
 $captcha=stripQuotes(killChars($_POST["security_code_offical"]));
 if($captcha=='') {
		header('Location: logout.php');
		exit;
	}
 $form_tocken=stripQuotes(killChars($_POST["encr"]));
	if($_SESSION['formptoken'] != $form_tocken) {
		header('Location: logout.php');
		exit;
	}
	$_SESSION['formptoken']="";
if (!isset($_SESSION['something_done'])) 
{
	do_something();
	$_SESSION['something_done'] = true;
}
function randomPrefix($length) 
{ 
	$random= ""; 
	srand((double)microtime()*1000000); 
	$data = "AbcDE123IJKLMN67QRSTUVWXYZ"; 
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; 
	$data .= "0FGH45OP89"; 
	for($i = 0; $i < $length; $i++) 
	{ 
		$random .= substr($data, (rand()%(strlen($data))), 1); 
	} 
	return $random; 
} 

function do_something()
{
	$_SESSION['itno']=rand(1,100);
	$_SESSION['salt']=randomPrefix(20);
	$_SESSION["attempts"]=0;
	unset($_SESSION["pagetoken"]);
	$_SESSION["pagetoken"]=randomPrefix(20);
}
 
$ip=$_SERVER['REMOTE_ADDR'];

if(isset($_POST["username"]) && isset($_POST['pwd']))
{
	$username = (strip_tags(trim($_POST["username"])));
	$password = (strip_tags(trim($_POST["pwd"])));
 
	
	//$sql = "SELECT user_name,dept_desig_id,user_pwd_encr FROM usr_dept_users WHERE user_name=?";
	$sql = "SELECT user_name,dept_desig_id,user_pwd_encr,COALESCE(enabling,true) as enabling FROM usr_dept_users WHERE user_name=?";
	$result = $db->prepare($sql);
	$result->execute(array($username));
	$count = $result->rowCount();
	$rowarray = $result->fetch(PDO::FETCH_ASSOC);
	if($result){
	$encpasswd=$_SESSION['salt'].$rowarray['user_pwd_encr'];
	 $lang = $lang['LANGUAGE'];
	 $opwd=md5($encpasswd);
	$sql_f="select coalesce(fail_count,0) as fail_count from usr_dept_users where user_name='".$username."';";
		$result_f = $db->query($sql_f);	
		$rowArr_f = $result_f->fetch(PDO::FETCH_BOTH);
		if($rowArr_f){
			$fail_count=$rowArr_f['fail_count'];
		}
		if($fail_count>=5){
			$sql_l="select CAST(login_date as date) as login_date from audit_trail_new where user_name='".$username."' and status='F' order by login_date desc limit 1";
			$result_l = $db->query($sql_l);	
			$rowArr_l = $result_l->fetch(PDO::FETCH_BOTH);
			if($rowArr_l){
				$login_date=date($rowArr_l['login_date']);
			}
			$date_now = date("Y-m-d");
			if($date_now <= $login_date && $fail_count>=5){
				$_SESSION['error_msg1'] = "Due to 5 failed logins in a day, your Account has been temporarily locked you can try to login by Tomorrow.";
				header("Location: index.php");
				 exit;
			}
		}
	//if($rowarray['user_name']==$username && $opwd==$password)
	if($rowarray['enabling'] && $rowarray['user_name']==$username && $opwd==$password)
	{
		//***************For Failed login upto 5 times on successful login reset counter
		$sql="update usr_dept_users set fail_count=0 where user_name='".$username."'";
		$db->query($sql);
		//**************/							  
		//For language Conversion
			   if($lang=='E'){
				$_SESSION['lang']=$lang;
			}else{
				$_SESSION['lang']=$lang;
			}  
		//End of Language Conversion

		include("UserProfile.php");
		$userProfile = new UserProfile();
		//echo "hreeeeerrrrr";
		 		
		
				
		  $sql="SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,sys_admin,
				   	pet_accept, pet_forward, pet_act_ret, pet_disposal,  
					desig_coordinating,	dept_id, dept_name, dept_tname, dept_pet_process, 
				   	off_level_pattern_id,  dept_coordinating,
				    off_level_dept_id, off_level_dept_name,off_level_dept_tname, 
				   	off_pet_process, off_coordinating, dept_desig_role_id,
					off_level_id, dept_off_level_office_id,				   
				   	off_loc_id, off_loc_name, off_loc_tname, sup_off_loc_id1, 
				   	sup_off_loc_id2, off_hier, 
				   	off_hier[7] AS state_id, off_hier[9] AS zone_id, off_hier[11] AS range_id, 
					off_hier[13] AS district_id, off_hier[42] AS division_id, off_hier[44] AS subdivision_id, off_hier[46] AS circle_id, off_hier[48] AS subcircle_id, off_hier[50] AS unit_id,griev_suptype_id,griev_suptype_name,griev_suptype_tname,
				   	user_name, off_desig_emp_name, off_desig_emp_tname,dept_off_level_pattern_id,fr_date, to_date, enabling
			  FROM vw_usr_dept_users_v_sup
			  WHERE user_name=?";
		
		$result = $db->prepare($sql);
		$result->execute(array($username));
		$rowArr = $result->fetch(PDO::FETCH_BOTH);
		$row_count = $result->rowCount();
		//print_r($rowArr['griev_suptype_id']);exit;
		$_SESSION['USER_ID_PK']=$rowArr['dept_user_id'];

		$petactret=$rowArr['pet_act_ret'];
		$petdisposal=$rowArr['pet_disposal'];
		$dept_user_id = $rowArr['dept_user_id'];
		$userProfile->setDept_user_id($rowArr['dept_user_id']);
		$userProfile->setUser_name($rowArr['user_name']);//login user id
		if($_SESSION['lang']=='E'){
		$userProfile->setOff_desig_emp_name($rowArr['off_desig_emp_name']);//Employee name
		}elseif($_SESSION['lang']=='T'){
		$userProfile->setOff_desig_emp_name($rowArr['off_desig_emp_tname']);//Employee name	
		}
		//DEPT. OFFICE LVL. DESIGN
		$userProfile->setDept_desig_id($rowArr['dept_desig_id']);
		//$userProfile->setDept_desig_id($rowArr['dept_off_level_pattern_id']);
		if($_SESSION['lang']=='E'){
		$userProfile->setDept_desig_name($rowArr['dept_desig_name']);
		}elseif($_SESSION['lang']=='T'){
		$userProfile->setDept_desig_name($rowArr['dept_desig_tname']);	
		}
		$userProfile->setSys_admin($rowArr['sys_admin']);		
		$userProfile->setPet_accept($rowArr['pet_accept']);
		$userProfile->setPet_forward($rowArr['pet_forward']);
		$userProfile->setPet_act_ret($rowArr['pet_act_ret']);
		$userProfile->setPet_disposal($rowArr['pet_disposal']);		
		$userProfile->setDesig_coordinating($rowArr['desig_coordinating']);
		$userProfile->setDesig_roleid($rowArr['dept_desig_role_id']);
		//SUP DESIGN
		//$userProfile->setS_Dept_desig_id($rowArr['s_dept_desig_id']);
				
		//DEPT. OFFICE LVL
		$userProfile->setOff_level_dept_id($rowArr['off_level_dept_id']);
		$userProfile->setOff_level_id($rowArr['off_level_id']);
		$_SESSION['OFF_LVL_ID']=$rowArr['off_level_id'];
		if($_SESSION['lang']=='E'){	
		$userProfile->setOff_level_name($rowArr['off_level_dept_name']);
		}elseif($_SESSION['lang']=='T'){
		$userProfile->setOff_level_name($rowArr['off_level_dept_tname']);	
		}
		$userProfile->setOff_pet_process($rowArr['off_pet_process']);		
		
		$userProfile->setOff_coordinating($rowArr['off_coordinating']);
		//DEPT.
		$userProfile->setDept_id($rowArr['dept_id']);
		$userProfile->setDept_name($rowArr['dept_name']);
		$userProfile->setDept_pet_process($rowArr['dept_pet_process']);
		
		$userProfile->setOff_level_pattern_id($rowArr['off_level_pattern_id']);
		$userProfile->setDept_off_level_office_id($rowArr['dept_off_level_office_id']);
		$userProfile->setDept_off_level_pattern_id($rowArr['dept_off_level_pattern_id']);
		//$userProfile->setOff_level_pattern_name($rowArr['off_level_pattern_name']);
		$userProfile->setDept_coordinating($rowArr['dept_coordinating']);
		//OFFICE LOC.
		//$userProfile->setOff_location($rowArr['off_location']);
		$userProfile->setOff_loc_id($rowArr['off_loc_id']);
		
		$userProfile->setgriev_suptype_id($rowArr['griev_suptype_id']);
		$userProfile->setgriev_suptype_name($rowArr['griev_suptype_name']);

		if($_SESSION['lang']=='E'){
		$userProfile->setOff_loc_name($rowArr['off_loc_name']);	
		}elseif($_SESSION['lang']=='T'){
		$userProfile->setOff_loc_name($rowArr['off_loc_tname']);		
		
		$userProfile->setgriev_suptype_name($rowArr['griev_suptype_tname']);
		}
		$userProfile->setSup_off_loc_id1($rowArr['sup_off_loc_id1']);
		$userProfile->setSup_off_loc_id2($rowArr['sup_off_loc_id2']);		
		$userProfile->setOff_hier($rowArr['off_hier']);
		
		//OFFICE LOCATION
		$userProfile->setState_id($rowArr['state_id']);
		$userProfile->setDistrict_id($rowArr['district_id']);
		$userProfile->setRdo_id($rowArr['rdo_id']);
		$userProfile->setRange_id($rowArr['range_id']);
		$userProfile->setZone_id($rowArr['zone_id']);
		//$userProfile->setFirka_id($rowArr['firka_id']);
		//$userProfile->setRev_village_id($rowArr['rev_village_id']);
		//$userProfile->setLb_urban_id($rowArr['lb_urban_id']);
		
		$userProfile->setDivision_id($rowArr['division_id']);
		$userProfile->setSubdivision_id($rowArr['subdivision_id']);
		$userProfile->setCircle_id($rowArr['circle_id']);
		$userProfile->setSubcircle_id($rowArr['subcircle_id']);
		//$userProfile->setgriev_suptype_id($rowArr['griev_suptype_id']);
		/*$userProfile->setUnit_id($rowArr['unit_id']);*/
		
		/*
		$userProfile->setState_name($rowArr['state_name']);
		$userProfile->setDistrict_name($rowArr['district_name']);
		$userProfile->setRdo_name($rowArr['rdo_name']);
		$userProfile->setTaluk_name($rowArr['taluk_name']);
		$userProfile->setFirka_name($rowArr['firka_name']);
		$userProfile->setBlock_name($rowArr['block_name']);
		$userProfile->setLb_urban_name($rowArr['lb_urban_name']);*/
		session_regenerate_id(true);
		
			
		$_SESSION['USER_PROFILE']=serialize($userProfile);

		$petactret="false";
		if ($userProfile->getPet_act_ret()){
			$petactret="true";
		};
		
		$petdisposal="false";
		if ($userProfile->getPet_disposal()){
			$petdisposal="true";
		};

		$sql="SELECT a.dept_desig_id, a.dept_user_id, off_level_id FROM vw_usr_dept_users_v_sup a WHERE dept_id=".$userProfile->getDept_id()." and off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and ((off_level_id > ".$userProfile->getOff_level_id()." and (sup_off_loc_id1 = ".$userProfile->getOff_loc_id()." OR sup_off_loc_id2 = ".$userProfile->getOff_loc_id().") and pet_act_ret=true) or case when exists (select true from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=a.dept_desig_id ) then true else off_level_id >= ".$userProfile->getOff_level_id()." end)";	
		
		$sql="SELECT a.dept_desig_id, a.dept_user_id, off_level_id FROM vw_usr_dept_users_v_sup a WHERE dept_id=".$userProfile->getDept_id()." and off_hier[".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and ((off_level_id > ".$userProfile->getOff_level_id()." and (sup_off_loc_id1 = ".$userProfile->getOff_loc_id()." OR sup_off_loc_id2 = ".$userProfile->getOff_loc_id().") and pet_act_ret=true) or case when exists (select true from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=a.dept_desig_id ) then true else off_level_id > ".$userProfile->getOff_level_id()." end)";	

		
		$result = $db->query($sql);
		$rowArr = $result->fetch(PDO::FETCH_NUM);		
//echo ">>>".$rowArr;exit;
		if($rowArr){
			$login_lvl=NON_BOTTOM;
		}
		else{
			$login_lvl=BOTTOM;
		}
		 
	$_SESSION['LOGIN_LVL']=$login_lvl;

	
	//For Audit trail
	$today = $page->currentTimeStamp();
	  
	
	 $query = 'INSERT INTO audit_trail_new(user_name, user_pwd, login_date, status, ent_ip_address) VALUES (?, ?, ?, ?, ?)';
	//echo 'INSERT INTO audit_trail(user_name, user_pwd, login_date, status) VALUES (?, ?, ?, ?)';
	//echo $username." ".$password." ".$today;
	 
	$result = $db->prepare($query);
	$result->execute(array($username, $password, $today, 'S', $ip)); 
	 
		ini_set('session.gc-maxlifetime', 1);//HERE TO SET SESSION TIME MAXIMUM OF 30 MINUTES
	//echo "===================".$userProfile->getDesig_coordinating();	

	session_regenerate_id(true); // Prevent's session fixation
    session_id(sha1(uniqid(microtime()))); // Sets a random ID for the session
    // Set the default values for the session
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Saves the user's IP
    $_SESSION['user_agent_id'] = $_SERVER['HTTP_USER_AGENT']; // Saves the user's navigator
	if ($userProfile->getDesig_coordinating() == true || $userProfile->getPet_act_ret() == true) {
		
		echo "column_series_with_lcl_data.php";
		header("location:welcome_pendency_page.php");
	} else {
		echo "welcome_to_e_district.php";
		header("location:welcome_to_e_district.php");
		
	}	
		
    //pg_close($db);
	}
	 else
	{
		//For Audit trail
		$today = $page->currentTimeStamp();
		
		//***************For Failed login upto 5 times
		if($fail_count>=5){
			$sql_l="select CAST(login_date as date) as login_date from audit_trail_new where user_name='".$username."' and status='F' order by login_date desc limit 1";
			$result_l = $db->query($sql_l);	
			$rowArr_l = $result_l->fetch(PDO::FETCH_BOTH);
			if($rowArr_l){
				$login_date=$rowArr_l['login_date'];
			}
			$date_now = date("Y-m-d");
			if($date_now <= $login_date && $fail_count>=5){
				$_SESSION['error_msg1'] = "Due to 5 failed logins in a day, your Account has been temporarily locked you can try to login by Tomorrow.";
				 header("Location: index.php");
				 //exit;
			}
		}
		$sql="update usr_dept_users set fail_count=".($fail_count+1)." where user_name='".$username."'";
		$result = $db->query($sql);
		//********************/
		$_SESSION['error_msg1'] = "Login Failed. Kindly check the username and password.";
		//$_SESSION['error_msg1'] = $lang['MODAL_USERNAME_PASSWORD_LOGIN_FAILED_DESC'];
		
		$query = 'INSERT INTO audit_trail_new(user_name, user_pwd, login_date, status, ent_ip_address) VALUES (?, ?, ?, ?, ?)';
		$result = $db->prepare($query);
		$result->execute(array($username, $password, $today, 'F', $ip)); 
		
		 header("Location: index.php");
	}
	}else{
	header("Location: logout.php");	
}
}else{
	$_SESSION['error_msg1'] = "Login Failed. Kindly check the username and password.";
	header("Location: logout.php");	
}

?>
