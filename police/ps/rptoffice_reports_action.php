<?PHP
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("UserProfile.php");
include("Pagination.php");
include("common_date_fun.php"); 
$userProfile = unserialize($_SESSION['USER_PROFILE']);
$mode=$_POST["mode"];

if($mode=='load_dept') {
	if (($userProfile->getDept_desig_id() == 12) || ($userProfile->getDept_desig_id() == 14)) {
		$userProfile = unserialize($_SESSION['PROXY_USER_PROFILE']);	
	} else {
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
	}	
	if ($userProfile->getOff_level_id() == 1 || $userProfile->getOff_level_id() == 3 || $userProfile->getOff_level_id() == 4) { 					
		$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
		FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name");
	} else { //if($userProfile->getDept_coordinating()&& $userProfile->getOff_coordinating()) 
		$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
		FROM usr_dept where dept_id>0 ORDER BY dept_name");					
	} 				

	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row) {
		echo "<dept_id>".$row['dept_id']."</dept_id>";
		if($_SESSION["lang"]=='E'){
			echo "<dept_name>".$row['dept_name']."</dept_name>";
		}else{
			echo "<dept_name>".strip_tags($row['dept_tname'])."</dept_name>";
		}
		echo "<off_level_pattern_id>".$row['off_level_pattern_id']."</off_level_pattern_id>";
	}

	if($userProfile->getOff_level_id()>2) {
		$res = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.district_id, b.district_name, 
		b.district_tname FROM mst_p_district b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, 
		a.off_level_id, a1.off_level_tblname from usr_dept_off_level a 
		inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_district' 
		and a.dept_id=".$userProfile->getDept_id().") b1 WHERE b.district_id=".$userProfile->getDistrict_id()."");

		$rowarray = $res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			echo "<dist_id>".$row['district_id']."</dist_id>";
			if($_SESSION["lang"]=='E'){
				echo "<dist_name>".$row['district_name']."</dist_name>";
			}else{
				echo "<dist_name>".$row['district_tname']."</dist_name>";	
			}
			echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
		}
	}
	echo "</response>";
}
if($mode=='load_griev_dept') {
	if (($userProfile->getDept_desig_id() == 12) || ($userProfile->getDept_desig_id() == 14)) {
		$userProfile = unserialize($_SESSION['PROXY_USER_PROFILE']);	
	} else {
		$userProfile = unserialize($_SESSION['USER_PROFILE']);	
	}	
	if ($userProfile->getOff_level_id() == 1 || $userProfile->getOff_level_id() == 3 || $userProfile->getOff_level_id() == 4) { 
		$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
		FROM usr_dept WHERE dept_id=".$userProfile->getDept_id()." ORDER BY dept_name");

	} else {
		$result = $db->query("SELECT dept_id, dept_name, dept_tname, off_level_pattern_id 
		FROM usr_dept where dept_id>0 and dept_id<12 ORDER BY dept_name");
	}
	
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	echo "<response>";
	foreach($rowarray as $row) {
		echo "<dept_id>".$row['dept_id']."</dept_id>";
		if($_SESSION["lang"]=='E'){
			echo "<dept_name>".$row['dept_name']."</dept_name>";
		}else{
			echo "<dept_name>".strip_tags($row['dept_tname'])."</dept_name>";
		}
		echo "<off_level_pattern_id>".$row['off_level_pattern_id']."</off_level_pattern_id>";
	}


	if($userProfile->getOff_level_id()>2) {
		$res = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.district_id, b.district_name, 
		b.district_tname FROM mst_p_district b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, 
		a.off_level_id, a1.off_level_tblname from usr_dept_off_level a 
		inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_district' 
		and a.dept_id=".$userProfile->getDept_id().") b1 WHERE b.district_id=".$userProfile->getDistrict_id()."");

		$rowarray = $res->fetchall(PDO::FETCH_ASSOC);
		foreach($rowarray as $row) {
			echo "<dist_id>".$row['district_id']."</dist_id>";
			if($_SESSION["lang"]=='E'){
				echo "<dist_name>".$row['district_name']."</dist_name>";
			}else{
				echo "<dist_name>".$row['district_tname']."</dist_name>";	
			}
			echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
		}
	}
	echo "</response>";
}
if($mode=='get_district')  { // For State Level and District Level 
	$dept=stripQuotes(killChars($_POST["dept_id"]));
		 if ($dept != "") {
			 $dept_Arr = explode("-", $dept);
			 $dept_id=$dept_Arr[0];
		 }

		if($userProfile->getOff_level_id()==1) {
			$res = $db->query("SELECT district_id, district_name, district_tname FROM mst_p_district where district_id>0 order by district_name");				
		} else {
			$res = $db->query("SELECT district_id, district_name, district_tname FROM mst_p_district where district_id =".$userProfile->getDistrict_id().""); 

		}

		$rowarray = $res->fetchall(PDO::FETCH_ASSOC);
		echo "<response>";
		foreach($rowarray as $row)
		{
			echo "<dist_id>".$row['district_id']."</dist_id>";
			if($_SESSION["lang"]=='E'){
			echo "<dist_name>".$row['district_name']."</dist_name>";
			}else{
			echo "<dist_name>".$row['district_tname']."</dist_name>";	
			}
		}
		   
		echo "</response>";
}
if($mode=='fill_rdo_taluk')
{
		$dept=substr(stripQuotes(killChars($_POST["dept_id"])), 0,1);
		 
		  if(!(is_null($userProfile->getRdo_id())))
		  {
							
				$result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.rdo_id, b.rdo_name, b.rdo_tname 
				FROM mst_p_rdo b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname 
				from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id 
				WHERE a1.off_level_tblname='mst_p_rdo' 
				and a.dept_id=".$dept.") b1 WHERE b.district_id=".$userProfile->getDistrict_id()." and 
				rdo_id=".$userProfile->getRdo_id()." ORDER BY b.rdo_name");
			 
		 } 
		  else if(stripQuotes(killChars($_POST["dist_id"]))!="") 
		  {
				$result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.rdo_id, b.rdo_name, b.rdo_tname 
				FROM mst_p_rdo b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, 
				a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id 
				WHERE a1.off_level_tblname='mst_p_rdo' and a.dept_id=".$dept.") b1 WHERE b.district_id=".stripQuotes(killChars($_POST["dist_id"]))."
				 order by rdo_name");
			
		  }
		  else 
		  {
			  $result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.rdo_id, b.rdo_name, b.rdo_tname 
				FROM mst_p_rdo b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, 
				a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id 
				WHERE a1.off_level_tblname='mst_p_rdo' and a.dept_id=".$dept.") b1 WHERE b.district_id=".$userProfile->getDistrict_id()."
				 order by rdo_name");
				
				 
			 
		  }
			  
		  $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		  echo "<response>";
		  foreach($rowarray as $row)
		  {
			  echo "<rdo_id>".$row['rdo_id']."</rdo_id>";
			  if($_SESSION["lang"]=='E'){
			  echo "<rdo_name>".$row['rdo_name']."</rdo_name>";
			  }else{
			  echo "<rdo_name>".$row['rdo_tname']."</rdo_name>";
			  }
			  echo "<off_level_dept_id_rdo>".$row['off_level_dept_id']."</off_level_dept_id_rdo>";
			   
		  }
			  
		  if(!(is_null($userProfile->getTaluk_id())))
		  {
			  $result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.taluk_id, b.taluk_name, b.taluk_tname 
			  FROM mst_p_taluk b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname 
			  from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE 
			  a1.off_level_tblname='mst_p_taluk' and a.dept_id=".$dept.") b1 WHERE 
			  b.district_id=".$userProfile->getDistrict_id()." and taluk_id=".$userProfile->getTaluk_id()." 
			  ORDER BY taluk_name ");
		  }
		  else if(stripQuotes(killChars($_POST["dist_id"]))!="") 
		  {
			  $result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.taluk_id, b.taluk_name, 
			  b.taluk_tname FROM mst_p_taluk b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, 
			  a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on 
			  a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_taluk' 
			  and a.dept_id=".$dept.") b1 WHERE b.district_id=".stripQuotes(killChars($_POST["dist_id"]))." order by taluk_name");
		  }
		   else
		   {
			  $result = $db->query("SELECT b1.off_level_dept_id, b1.dept_id, b.taluk_id, b.taluk_name, 
			  b.taluk_tname FROM mst_p_taluk b CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, 
			  a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on 
			  a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_taluk' 
			  and a.dept_id=".$dept.") b1 WHERE b.district_id=".$userProfile->getDistrict_id()." order by taluk_name");  
		   }
		  $rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		  foreach($rowarray as $row)
		  {
			  echo "<taluk_id>".$row['taluk_id']."</taluk_id>";
			  if($_SESSION["lang"]=='E'){
			  echo "<taluk_name>".$row['taluk_name']."</taluk_name>";
			  }else{
			  echo "<taluk_name>".$row['taluk_tname']."</taluk_name>";
			  }
			  echo "<off_level_dept_id_tlk>".$row['off_level_dept_id']."</off_level_dept_id_tlk>";
		  }
    
echo "</response>";
}
if($mode=='populaterdotaluk')
{
	$rdo_sql="SELECT rdo_id, rdo_name, rdo_tname FROM mst_p_rdo WHERE district_id=".stripQuotes(killChars($_POST["dist_id"]))." ORDER BY rdo_name";
	$result = $db->query($rdo_sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<rdo_id>".$row['rdo_id']."</rdo_id>";
		if($_SESSION["lang"]=='E'){
			echo "<rdo_name>".$row['rdo_name']."</rdo_name>";
		}else{
			echo "<rdo_name>".$row['rdo_tname']."</rdo_name>";
		}		
	}
	$taluk_sql="SELECT taluk_id, taluk_name, taluk_tname FROM mst_p_taluk 
				WHERE district_id=".stripQuotes(killChars($_POST["dist_id"]))." ORDER BY taluk_name";
	$result = $db->query($taluk_sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	foreach($rowarray as $row)
	{
		echo "<taluk_id>".$row['taluk_id']."</taluk_id>";
		if($_SESSION["lang"]=='E'){
			echo "<taluk_name>".$row['taluk_name']."</taluk_name>";
		}else{
			echo "<taluk_tname>".$row['taluk_tname']."</taluk_tname>";
		}		
	}
	echo "</response>";	
				
}
if($mode=='get_taluk')
{  
			if(stripQuotes(killChars($_POST["rdo_id"]))!=0)
			{
				$sql = "SELECT taluk_id, taluk_name, taluk_tname FROM mst_p_taluk  
				WHERE rdo_id=".stripQuotes(killChars($_POST["rdo_id"])).' ORDER BY taluk_name';
			}
			else if(stripQuotes(killChars($_POST["dist_id"]))!="")
			{
				$sql ="SELECT taluk_id, taluk_name, taluk_tname FROM mst_p_taluk 
				WHERE district_id=".stripQuotes(killChars($_POST["dist_id"]))." ORDER BY taluk_name";
			}
			else {
				$sql ="SELECT taluk_id, taluk_name, taluk_tname FROM mst_p_taluk 
				WHERE taluk_id=".$userProfile->getTaluk_id()." ORDER BY taluk_name";
				
			}
			
		      
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('TalukID', $row['taluk_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('TalukName', $row['taluk_name']);
				}else{
				echo $page->generateXMLTag('TalukName', $row['taluk_tname']);
				}
			}
			echo "</response>";
}
if($mode=='get_firka')
{
	 $dept=substr(stripQuotes(killChars($_POST["dept_id"])), 0,1);
			if(!(is_null($userProfile->getFirka_id())))
			{
				
				 $sql = "SELECT b1.off_level_dept_id, b1.dept_id, b.firka_id, b.firka_name, b.firka_tname FROM mst_p_firka b
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_firka' and a.dept_id=1) b1
WHERE b.firka_id=".$userProfile->getFirka_id()." ORDER BY b.firka_name";
			}
			else 
			{
			$sql = "SELECT b1.off_level_dept_id, b1.dept_id, b.firka_id, b.firka_name, b.firka_tname FROM mst_p_firka b
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_firka' and a.dept_id=".$dept.") b1
WHERE b.taluk_id=".stripQuotes(killChars($_POST["taluk_id"]))." ORDER BY b.firka_name";
			}
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('FirkaID', $row['firka_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('FirkaName', $row['firka_name']);
				}else{
				echo $page->generateXMLTag('FirkaName', $row['firka_tname']);	
				}
				echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
			}
			echo "</response>";
}
if($mode=='get_firka_for_taluk')
{
	$sql="SELECT firka_id, taluk_id,firka_name, firka_tname FROM mst_p_firka where taluk_id=".$_POST["taluk_id"]."";
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('FirkaID', $row['firka_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('FirkaName', $row['firka_name']);
				}else{
				echo $page->generateXMLTag('FirkaName', $row['firka_tname']);	
				}
			}
			echo "</response>";
}
if($mode=='fill_block')
{
	$dept=substr(stripQuotes(killChars($_POST["dept_id"])), 0,1);
	
			if(!(is_null($userProfile->getBlock_id())))
			{
				$sql ="SELECT b1.off_level_dept_id, b1.dept_id, b.block_id, b.block_name, b.block_tname FROM mst_p_lb_block b
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_block' and a.dept_id=".$dept.") b1
WHERE b.block_id=".$userProfile->getBlock_id()." ORDER BY block_name";
			}
			else if(stripQuotes(killChars($_POST["dist_id"]))!="")
			{
				
			 $sql = "SELECT b1.off_level_dept_id, b1.dept_id, b.block_id, b.block_name, b.block_tname FROM mst_p_lb_block b
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_block' and a.dept_id=".$dept.") b1
WHERE b.district_id=".stripQuotes(killChars($_POST["dist_id"]))." ORDER BY block_name";
			}
			else
			{
			 $sql = "SELECT b1.off_level_dept_id, b1.dept_id, b.block_id, b.block_name, b.block_tname FROM mst_p_lb_block b
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_block' and a.dept_id=".$dept.") b1
WHERE b.district_id=".$userProfile->getDistrict_id()." ORDER BY block_name";	
			}
			
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('BlockID', $row['block_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('BlockName', $row['block_name']);
				}else{
				echo $page->generateXMLTag('BlockName',$row['block_tname']);
				}
				echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
			}
			echo "</response>";
}
if($mode=='fill_urban')
{ 
			$dept=substr(stripQuotes(killChars($_POST["dept_id"])), 0,1);
			if(!(is_null($userProfile->getLb_urban_id())))
			{
				 
				$sql = "SELECT b2.dept_id,b2.off_level_dept_id,b.lb_urban_id, b.lb_urban_name || '(' || b1.lb_urban_type_name || ' - ' || c.lb_urban_grade_name || ')' lb_urban_name, b.lb_urban_tname || '(' || b1.lb_urban_type_tname || ' - ' || c.lb_urban_grade_tname || ')' lb_urban_tname FROM mst_p_lb_urban b
	left join lkp_p_lb_urban_type b1 on b1.lb_urban_type_id=b.lb_urban_type_id
	left join lkp_p_lb_urban_grade c on c.lb_urban_grade_id=b.lb_urban_grade_id 
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_urban' and a.dept_id=".$dept.") b2
WHERE b.lb_urban_id=".$userProfile->getLb_urban_id()." ORDER BY lb_urban_name";
			}
			else if(stripQuotes(killChars($_POST["dist_id"]))!="")
			{ 
				   $sql = "SELECT b2.dept_id,b2.off_level_dept_id,b.lb_urban_id, b.lb_urban_name || '(' || b1.lb_urban_type_name || ' - ' || c.lb_urban_grade_name || ')' lb_urban_name, b.lb_urban_tname || '(' || b1.lb_urban_type_tname || ' - ' || c.lb_urban_grade_tname || ')' lb_urban_tname FROM mst_p_lb_urban b
	left join lkp_p_lb_urban_type b1 on b1.lb_urban_type_id=b.lb_urban_type_id
	left join lkp_p_lb_urban_grade c on c.lb_urban_grade_id=b.lb_urban_grade_id 
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_urban' and a.dept_id=".$dept.") b2
WHERE b.district_id=".stripQuotes(killChars($_POST["dist_id"]))." ORDER BY lb_urban_name ";
			}
			else
			{
				 $sql = "SELECT b2.dept_id,b2.off_level_dept_id,b.lb_urban_id, b.lb_urban_name || '(' || b1.lb_urban_type_name || ' - ' || c.lb_urban_grade_name || ')' lb_urban_name, b.lb_urban_tname || '(' || b1.lb_urban_type_tname || ' - ' || c.lb_urban_grade_tname || ')' lb_urban_tname FROM mst_p_lb_urban b
	left join lkp_p_lb_urban_type b1 on b1.lb_urban_type_id=b.lb_urban_type_id
	left join lkp_p_lb_urban_grade c on c.lb_urban_grade_id=b.lb_urban_grade_id 
CROSS JOIN (SELECT a.off_level_dept_id, a.dept_id, a.off_level_id, a1.off_level_tblname from usr_dept_off_level a inner join usr_off_level a1 on a1.off_level_id=a.off_level_id WHERE a1.off_level_tblname='mst_p_lb_urban' and a.dept_id=".$dept.") b2
WHERE b.district_id=".$userProfile->getDistrict_id()." ORDER BY lb_urban_name ";	 
				
			}
			
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('UrbanID', $row['lb_urban_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('UrbanName', $row['lb_urban_name']);
				}else{
				echo $page->generateXMLTag('UrbanName', $row['lb_urban_tname']);	
				}
				echo "<off_level_dept_id>".$row['off_level_dept_id']."</off_level_dept_id>";
			}
			echo "</response>";
}

if($mode=='fill_user')
{
			 
			$dept=substr(stripQuotes(killChars($_POST["dept_id"])), 0,1);
		 
	 	 
	  $sql = "SELECT dept_user_id,dept_desig_name || "."', '"." || off_loc_name as desig,
		dept_desig_tname || "."', '"." || off_loc_tname as tdesig FROM vw_usr_dept_users_v_sup WHERE dept_id=".$dept." and off_level_dept_id=".stripQuotes(killChars($_POST["off_lvl_id"]))." and off_loc_id=".stripQuotes(killChars($_POST["off_loc_id"]))."";
	 	
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('DeptUser_ID', $row['dept_user_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('Off_desig_emp_name', $row['desig']);
				}else{
				echo $page->generateXMLTag('Off_desig_emp_name', $row['tdesig']);	
				}
			}
			echo "</response>";
}

if($mode=='fillOffice')
{
			 
		$dept=$_POST["dept"];
		$dist=$_POST["dist_id"];
		 
	 	 
	  $sql = "SELECT division_id, division_name, division_tname FROM mst_p_sp_division  where district_id=".$dist." and dept_id=".$dept."";
	 	
			$result = $db->query($sql);
			$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
			echo "<response>";
			foreach($rowarray as $row)
			{   
				echo $page->generateXMLTag('division_id', $row['division_id']);
				if($_SESSION["lang"]=='E'){
				echo $page->generateXMLTag('division_name', $row['division_name']);
				}else{
				echo $page->generateXMLTag('division_name', $row['division_tname']);	
				}
			}
			echo "</response>";
} 

if($mode=='get_off_level')  
{  
	
	$off_level=$_POST["off_level"];
	$dept=stripQuotes(killChars($_POST["dept_id"]));
	$dept_id=explode('-',$dept);
	
	$res = $db->query("SELECT  dept_id, off_level_id, off_level_dept_name, off_level_dept_tname
  FROM usr_dept_off_level where dept_id=".$dept_id[0]." and off_level_id>".$off_level." order by off_level_dept_id");
		
	$rowarray = $res->fetchall(PDO::FETCH_ASSOC);
	echo "<response>";
	foreach($rowarray as $row)
	{
		echo "<off_level_id>".$row['off_level_id']."</off_level_id>";
		if($_SESSION["lang"]=='E'){
		echo "<off_level_dept_name>".$row['off_level_dept_name']."</off_level_dept_name>";
		}else{
		echo "<off_level_dept_name>".$row['off_level_dept_name']."</off_level_dept_name>";	
		}		
	}
   
	echo "</response>";
}

?>