<?php
session_start();
ob_start();
include("db.php");
include("Pagination.php");

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
   
$_SESSION['security_code']=substr($_SESSION['key'],0,5);
$num = pg_escape_string(strip_tags(trim($_POST['security_code']))); 
if( $_SESSION['security_code'] == $num && !empty($_SESSION['security_code'] ) )
{//security code valid starts
 
	unset($_SESSION['security_code']);
if(isset($_POST["username"]) && isset($_POST['pwd']))
{
	$username = pg_escape_string(strip_tags(trim($_POST["username"])));
	$password = pg_escape_string(strip_tags(trim($_POST["pwd"])));
 
	
	$sql = "SELECT user_name,dept_desig_id,user_pwd_encr FROM usr_dept_users WHERE user_name=?";
	$result = $db->prepare($sql);
	$result->execute(array($username));
	$count = $result->rowCount();

	$rowarray = $result->fetch(PDO::FETCH_ASSOC);
	 
	$encpasswd=$_SESSION['salt'].$rowarray['user_pwd_encr'];
	$lang = pg_escape_string(strip_tags(trim($_POST['lang'])));
	 
	$opwd=md5($encpasswd);
  	  
 
	if($rowarray['user_name']==$username && $opwd==$password)
	{

		//For language Conversion
			if($lang=='T'){
				$_SESSION['lang']=$lang;
			}else{
				$_SESSION['lang']=$lang;
			}
		//End of Language Conversion

		include("UserProfile.php");
		$userProfile = new UserProfile();
 		
		
				
		  $sql="SELECT dept_user_id, dept_desig_id, dept_desig_name,dept_desig_tname,
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
			  WHERE user_name=?";
		 	 
		$result = $db->prepare($sql);
		$result->execute(array($username));
		$rowArr = $result->fetch(PDO::FETCH_BOTH);
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
		//SUP DESIGN
		$userProfile->setS_Dept_desig_id($rowArr['s_dept_desig_id']);
				
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
		$userProfile->setOff_level_pattern_name($rowArr['off_level_pattern_name']);
		$userProfile->setDept_coordinating($rowArr['dept_coordinating']);
		//OFFICE LOC.
		//$userProfile->setOff_location($rowArr['off_location']);
		$userProfile->setOff_loc_id($rowArr['off_loc_id']);
		if($_SESSION['lang']=='E'){
		$userProfile->setOff_loc_name($rowArr['off_loc_name']);	
		}elseif($_SESSION['lang']=='T'){
		$userProfile->setOff_loc_name($rowArr['off_loc_tname']);		
		}
		$userProfile->setSup_off_loc_id1($rowArr['sup_off_loc_id1']);
		$userProfile->setSup_off_loc_id2($rowArr['sup_off_loc_id2']);		
		$userProfile->setOff_hier($rowArr['off_hier']);
		
		//OFFICE LOCATION
		$userProfile->setState_id($rowArr['state_id']);
		$userProfile->setDistrict_id($rowArr['district_id']);
		$userProfile->setRdo_id($rowArr['rdo_id']);
		$userProfile->setTaluk_id($rowArr['taluk_id']);
		$userProfile->setBlock_id($rowArr['block_id']);
		$userProfile->setFirka_id($rowArr['firka_id']);
		$userProfile->setRev_village_id($rowArr['rev_village_id']);
		$userProfile->setLb_urban_id($rowArr['lb_urban_id']);
		
		$userProfile->setDivision_id($rowArr['division_id']);
		$userProfile->setSubdivision_id($rowArr['subdivision_id']);
		$userProfile->setCircle_id($rowArr['circle_id']);
		$userProfile->setSubcircle_id($rowArr['subcircle_id']);
		/*$userProfile->setUnit_id($rowArr['unit_id']);*/
		
		/*
		$userProfile->setState_name($rowArr['state_name']);
		$userProfile->setDistrict_name($rowArr['district_name']);
		$userProfile->setRdo_name($rowArr['rdo_name']);
		$userProfile->setTaluk_name($rowArr['taluk_name']);
		$userProfile->setFirka_name($rowArr['firka_name']);
		$userProfile->setBlock_name($rowArr['block_name']);
		$userProfile->setLb_urban_name($rowArr['lb_urban_name']);*/
		session_regenerate_id();
		
			
		$_SESSION['USER_PROFILE']=serialize($userProfile);

		$petactret="false";
		if ($userProfile->getPet_act_ret()){
			$petactret="true";
		};
		
		$petdisposal="false";
		if ($userProfile->getPet_disposal()){
			$petdisposal="true";
		};

		if (($rowArr['dept_desig_id'] == 12) || ($rowArr['dept_desig_id'] == 14)) {
			$proxyProfile = new UserProfile();
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
					WHERE dept_user_id=(select dept_user_id from usr_dept_users where district_id = 
				    (select district_id from usr_dept_users where  dept_user_id=?) and dept_desig_id=17)";

			$result = $db->prepare($sql);
		    $result->execute(array($dept_user_id));
			$proxy_petactret=$rowArr['pet_act_ret'];
			$proxy_petdisposal=$rowArr['pet_disposal'];			
			$rowArr = $result->fetch(PDO::FETCH_BOTH);
			//$_SESSION['USER_ID_PK']=$rowArr['dept_user_id'];
			$dept_user_id = $rowArr['dept_user_id'];
			$proxyProfile->setDept_user_id($rowArr['dept_user_id']);
			$proxyProfile->setUser_name($rowArr['user_name']);//login user id
			if($_SESSION['lang']=='E'){
			$proxyProfile->setOff_desig_emp_name($rowArr['off_desig_emp_name']);//Employee name
			}elseif($_SESSION['lang']=='T'){
			$proxyProfile->setOff_desig_emp_name($rowArr['off_desig_emp_tname']);//Employee name	
			}
			//DEPT. OFFICE LVL. DESIGN
			$proxyProfile->setDept_desig_id($rowArr['dept_desig_id']);
			if($_SESSION['lang']=='E'){
			$proxyProfile->setDept_desig_name($rowArr['dept_desig_name']);
			}elseif($_SESSION['lang']=='T'){
			$proxyProfile->setDept_desig_name($rowArr['dept_desig_tname']);	
			}
			$proxyProfile->setSys_admin($rowArr['sys_admin']);		
			$proxyProfile->setPet_accept($rowArr['pet_accept']);
			$proxyProfile->setPet_forward($rowArr['pet_forward']);
			$proxyProfile->setPet_act_ret($rowArr['pet_act_ret']);
			$proxyProfile->setPet_disposal($rowArr['pet_disposal']);		
			$proxyProfile->setDesig_coordinating($rowArr['desig_coordinating']);
			//SUP DESIGN
			$proxyProfile->setS_Dept_desig_id($rowArr['s_dept_desig_id']);
					
			//DEPT. OFFICE LVL
			$proxyProfile->setOff_level_dept_id($rowArr['off_level_dept_id']);
			$proxyProfile->setOff_level_id($rowArr['off_level_id']);
			if($_SESSION['lang']=='E'){	
			$proxyProfile->setOff_level_name($rowArr['off_level_dept_name']);
			}elseif($_SESSION['lang']=='T'){
			$proxyProfile->setOff_level_name($rowArr['off_level_dept_tname']);	
			}
			$proxyProfile->setOff_pet_process($rowArr['off_pet_process']);		
			
			$proxyProfile->setOff_coordinating($rowArr['off_coordinating']);
			//DEPT.
			$proxyProfile->setDept_id($rowArr['dept_id']);
			$proxyProfile->setDept_name($rowArr['dept_name']);
			$proxyProfile->setDept_pet_process($rowArr['dept_pet_process']);
			
			$proxyProfile->setOff_level_pattern_id($rowArr['off_level_pattern_id']);
			$proxyProfile->setOff_level_pattern_name($rowArr['off_level_pattern_name']);
			$proxyProfile->setDept_coordinating($rowArr['dept_coordinating']);
			//OFFICE LOC.
			//$userProfile->setOff_location($rowArr['off_location']);
			$proxyProfile->setOff_loc_id($rowArr['off_loc_id']);
			if($_SESSION['lang']=='E'){
			$proxyProfile->setOff_loc_name($rowArr['off_loc_name']);	
			}elseif($_SESSION['lang']=='T'){
			$proxyProfile->setOff_loc_name($rowArr['off_loc_tname']);		
			}
			$proxyProfile->setSup_off_loc_id1($rowArr['sup_off_loc_id1']);
			$proxyProfile->setSup_off_loc_id2($rowArr['sup_off_loc_id2']);		
			$proxyProfile->setOff_hier($rowArr['off_hier']);
			
			//OFFICE LOCATION
			$proxyProfile->setState_id($rowArr['state_id']);
			$proxyProfile->setDistrict_id($rowArr['district_id']);
			$proxyProfile->setRdo_id($rowArr['rdo_id']);
			$proxyProfile->setTaluk_id($rowArr['taluk_id']);
			$proxyProfile->setBlock_id($rowArr['block_id']);
			$proxyProfile->setFirka_id($rowArr['firka_id']);
			$proxyProfile->setRev_village_id($rowArr['rev_village_id']);
			$proxyProfile->setLb_urban_id($rowArr['lb_urban_id']);
			
			$proxyProfile->setDivision_id($rowArr['division_id']);
			$proxyProfile->setSubdivision_id($rowArr['subdivision_id']);
			$proxyProfile->setCircle_id($rowArr['circle_id']);
			$proxyProfile->setSubcircle_id($rowArr['subcircle_id']);
			
			$_SESSION['PROXY_USER_PROFILE']=$proxyProfile;			
					
		}
		//chk for BOTTOM or NON-BOTTOM level user
		
	
$sql="SELECT a.dept_desig_id, a.dept_user_id, off_level_id,off_level_name FROM vw_usr_dept_users_v_sup a WHERE dept_id=".$userProfile->getDept_id()." and off_hier[ ".$userProfile->getOff_level_id()."]=".$userProfile->getOff_loc_id()." and ((off_level_id > ".$userProfile->getOff_level_id()." and (sup_off_loc_id1 = ".$userProfile->getOff_loc_id()." OR sup_off_loc_id2 = ".$userProfile->getOff_loc_id().") and pet_act_ret=true) or exists (select 1 from usr_dept_desig_disp_sources u1 where u1.dept_desig_id=a.dept_desig_id ))";	

		$result = $db->query($sql);
		$rowArr = $result->fetch(PDO::FETCH_NUM);		
	
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
	 
	$result = $db->prepare($query);
	$result->execute(array($username, $password, $today, 'S', $ip)); 

	 
		ini_set('session.gc-maxlifetime', 1);//HERE TO SET SESSION TIME MAXIMUM OF 30 MINUTES

	if ($userProfile->getDesig_coordinating() == true) {
		echo "column_series_with_lcl_data.php";
		header("location:column_series_with_lcl_data.php");
	} else {
		echo "welcome_to_e_district.php";
		header("location:welcome_to_e_district.php");
	}	
		
    pg_close($db);
	}
	 else
	{
		//For Audit trail
		$today = $page->currentTimeStamp();
		$_SESSION['error_msg'] = "Login Failed. Kindly check the username and password.";
		
		$query = 'INSERT INTO audit_trail_new(user_name, user_pwd, login_date, status, ent_ip_address) VALUES (?, ?, ?, ?, ?)';
		$result = $db->prepare($query);
		$result->execute(array($username, $password, $today, 'F', $ip)); 
		
		 pg_close($db);
		 header("Location: index_login.php");
	}
	
}else{
	header("Location: logout.php");	
}
}else{
$_SESSION['error_msg'] = "Incorrect Security Code.";
pg_close($db);
header("Location: index_login.php");
}

?>