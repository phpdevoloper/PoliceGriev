<?php 
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("common_fun.php");
include("Pagination.php");

//$petno=stripQuotes(killChars(trim($_POST['petition_no'])));
$petno=stripQuotes(killChars(trim($_POST['petition_no'])));
$language=stripQuotes(killChars(trim($_POST['language'])));
$stype=stripQuotes(killChars(trim($_POST['stype'])));
	
$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date,pet_type_name, 
pet_type_tname,TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name,source_tname,subsource_name,
subsource_tname,griev_type_name,griev_type_tname,griev_subtype_name,griev_subtype_tname,
dept_name,dept_tname, grievance, 

canid,petitioner_name, father_husband_name, gender_name,comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name,comm_district_tname, comm_taluk_name,comm_taluk_tname,comm_rev_village_name, comm_rev_village_tname,comm_pincode, comm_email, comm_phone, comm_mobile,comm_country_name,comm_country_tname,comm_state_name,comm_state_tname,

griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name,griev_district_tname, 
griev_taluk_name, griev_taluk_tname, griev_rev_village_name,griev_rev_village_tname,griev_block_name, griev_block_tname,griev_lb_village_name, griev_lb_village_tname, griev_lb_urban_name, griev_lb_urban_tname, griev_pincode,aadharid,griev_division_name,griev_division_tname,griev_subdivision_name,griev_circle_name

FROM vw_pet_master WHERE petition_id='".$petno."'";  
	
// echo $query;
//exit; 	
$result = $db->query($query);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

echo "<response><A>".$query."</A>";
if(sizeof($rowarray)==0) {		 
	$_SESSION['error_msg'] = "No Record found ! Enter valid Petition Number !!";	         
	echo $page->generateXMLTag('error','F');
} else {
echo $page->generateXMLTag('result', 'T');			

foreach($rowarray as $row) 
{
	$petno1=$row['petition_no'];
//petition address	
if ($language=='E') {
	if($row['griev_taluk_name']!="") {
		$pet_off_address = $row['griev_rev_village_name'].', '. $row['griev_taluk_name'].' Taluk'.', '.$row['griev_district_name'].' District'; //Re_pa Taluk Revenue Village
	} else if ($row['griev_block_name']!="") {
		$pet_off_address = $row['griev_lb_village_name'].', '. $row['griev_block_name'].', '.$row['griev_district_name'].' District';//Ru_pa Block Village Panchayat
	} else if($row['griev_lb_urban_name']!="") {
		$pet_off_address = $row['griev_district_name'].' District'.', '. $row['griev_lb_urban_name'];   //Ur_pa Urban Local Body 
	} else {
		$pet_off_address = '';
	//griev_circle_name,griev_subdivision_name,griev_division_name
	if ($row['griev_circle_name']!="") {
		$pet_off_address .= $row['griev_circle_name'];
	}
	if ($row['griev_subdivision_name']!="") {
		$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_subdivision_name'] : $row['griev_subdivision_name'];
	}
	if ($row['griev_division_name']!="") {
		$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_division_name'] : $row['griev_division_name'];
	}
	$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_district_name'].' District' : $row['griev_district_name'].' District';
	}
} else if ($language=='T') {
	if($row['griev_taluk_name']!="") {
		$pet_off_address = $row['griev_rev_village_tname'].', '. $row['griev_taluk_tname'].' தாலுக்கா'.', '.$row['griev_district_tname'].' மாவட்டம்'; //Re_pa Taluk Revenue Village
	} else if ($row['griev_block_name']!="") {
		$pet_off_address = $row['griev_lb_village_tname'].', '. $row['griev_block_tname'].', '.$row['griev_district_tname'].' மாவட்டம்';  //Ru_pa Block Village Panchayat
	} else if($row['griev_lb_urban_name']!="") {
		$pet_off_address = $row['griev_district_tname'].' மாவட்டம்'.', '. $row['griev_lb_urban_tname'];   //Ur_pa Urban Local Body 
	} else {
		$pet_off_address = $row['griev_district_tname'].' மாவட்டம்'.', '. $row['griev_division_tname'];   //Sp_pa Office  
	}
} else {
	if($row['griev_taluk_name']!="") {
		$pet_off_address = $row['griev_rev_village_name'].', '. $row['griev_taluk_name'].' Taluk'.', '.$row['griev_district_name'].' District';//Re_pa Taluk Revenue Village
	} else if ($row['griev_block_name']!="") {
		$pet_off_address = $row['griev_lb_village_name'].', '. $row['griev_block_name'].', '.$row['griev_district_name'].' District'; //Ru_pa Block Village Panchayat
	} else if($row['griev_lb_urban_name']!="") {
		$pet_off_address = $row['griev_district_name'].' District'.', '. $row['griev_lb_urban_name'];   //Ur_pa Urban Local Body 
	} else {
	$pet_off_address = '';
	if ($row['griev_circle_name']!="") {
		$pet_off_address .= $row['griev_circle_name'];
	}
	if ($row['griev_subdivision_name']!="") {
		$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_subdivision_name'] : $row['griev_subdivision_name'];
	}
	if ($row['griev_division_name']!="") {
		$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_division_name'] : $row['griev_division_name'];
	}
	$pet_off_address .= ($pet_off_address != '') ? ', '.$row['griev_district_name'].' District' : $row['griev_district_name'].' District';  
	}
}	
// petitioner communication address
if ($language=='E')
{
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
}
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
}
}
else if ($language=='T')
{
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
}
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
}
}
else
{
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
}
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
}
}
//மனு எண் & தேதி - மனு வகை
if ($language=='E')
{
echo $page->generateXMLTag('Petition_No_and_Date', $row['petition_no'].' & Date:'.$row['petition_date'].' - '.$row['pet_type_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petition_No_and_Date', $row['petition_no'].' & தேதி :'.$row['petition_date'].' - '.$row['pet_type_tname']);
}
else
{
echo $page->generateXMLTag('Petition_No_and_Date', $row['petition_no'].' & Date:'.$row['petition_date'].' - '.$row['pet_type_name']);
}	
//மனு பெற்ற வழி, மனு பெற்ற துணை வழி & கருத்துக்கள்	
if ($language=='E')
{
echo $page->generateXMLTag('Source_Name', $row['source_name'].$row['subsource_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Source_Name', $row['source_tname'].$row['subsource_tname']);
}
else
{
echo $page->generateXMLTag('Source_Name', $row['source_name'].$row['subsource_name']);
}	
//துறை
if ($language=='E')
{
echo $page->generateXMLTag('Department', $row['dept_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Department', $row['dept_tname']);
}
else
{
echo $page->generateXMLTag('Department', $row['dept_name']);
}			
//மனு முதன்மை & துணை பிரிவு
if ($language=='E')
{
echo $page->generateXMLTag('Petition_Main_Type_and_Petition_Sub_Type', $row['griev_type_name'].' & '.$row['griev_subtype_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petition_Main_Type_and_Petition_Sub_Type', $row['griev_type_tname'].' & '.$row['griev_subtype_tname']);
}
else
{
echo $page->generateXMLTag('Petition_Main_Type_and_Petition_Sub_Type', $row['griev_type_name'].' & '.$row['griev_subtype_name']);
}			
//மனு விவரம்	
echo $page->generateXMLTag('Petition_Details', $row['grievance']);
//தொடர்புடைய அலுவலக முகவரி
if ($language=='E')
{
echo $page->generateXMLTag('Petition_Office_Address', $pet_off_address);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petition_Office_Address', $pet_off_address);
}
else
{
echo $page->generateXMLTag('Petition_Office_Address', $pet_off_address);
}			
//மனுதாரரின் பெயர் & தந்தை / கணவர் பெயர்
if ($language=='E')
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address', $row['petitioner_name'].' & Father / Husband Name :'.$row['father_husband_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address', $row['petitioner_name'].' &  தந்தை / கணவர் பெயர் :'.$row['father_husband_name']);
}
else
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address', $row['petitioner_name'].' & Father / Husband Name :'.$row['father_husband_name']);
}			
//Address
echo $page->generateXMLTag('Address', $address);
//Mobile Number
echo $page->generateXMLTag('Mobile_Number', $row['comm_mobile']);//Mobile Number
//Email
echo $page->generateXMLTag('Email_Label', $row['comm_email']);

$query_doc = "select doc_id,doc_name from pet_master_doc where petition_id in('".$row['petition_id']."')";
$fetch_doc = $db->query($query_doc);
$doc_count= $fetch_doc->rowCount();
$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
if ($doc_count > 0) 
{ 
foreach($doc_row as $key){
echo $page->generateXMLTag('Document_id', $key['doc_id']);
echo $page->generateXMLTag('Document_name', $key['doc_name']);
}
}

$query_doc = "select action_doc_id,action_doc_name from pet_action_doc where petition_id in('".$row['petition_id']."')";
$fetch_doc = $db->query($query_doc);
$action_doc_count= $fetch_doc->rowCount();
$doc_row = $fetch_doc->fetchall(PDO::FETCH_BOTH);
if ($action_doc_count > 0) 
{
foreach($doc_row as $key)
{
echo $page->generateXMLTag('action_doc_id', $key['action_doc_id']);
echo $page->generateXMLTag('action_doc_name', $key['action_doc_name']);
}
}
}
}
			
		$pendsql="select action_type_code,action_type_name,pend_period from vw_petition_details  where petition_id='".$petno."'";		
		$presult = $db->query($pendsql);		
		$prowarray =$presult->fetchall(PDO::FETCH_ASSOC);
		foreach($prowarray as $row) {
			$act_type=$row['action_type_code'];
			$act_type_name=$row['action_type_name'];
			$pend_period=$row['pend_period'];
		}
		
			
		if ($language=='E')
		{
			if ($pend_period== '---')
			{
				$status=$act_type_name;
			}
			else
			{
				$status='PENDING';
			}
		}
		else if ($language=='T')
		{
			if ($pend_period== '---')
			{
			//---------
				if ($act_type=='A')
				{
				$status='கோரிக்கை ஏற்றுக்கொள்ளப்பட்டது';
				}
				else if ($act_type=='R')
				{
				$status='கோரிக்கை நிராகரிக்கப்பட்டது';
				}
			//---------
			}
			else
			{
				$status='கோரிக்கை நிலுவையில் உள்ளது';
			}
		}
		else
		{
			if ($pend_period== '---')
			{
				$status=$act_type_name;
			}
			else
			{
				$status='PENDING';
			}
		}
				
		echo $page->generateXMLTag('Status', $status);
		
		if ($stype == 's') {
			$query=" select * from (
		SELECT petition_id,pet_action_id,action_type_name, action_type_tname, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name, dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_loc_name AS location,off_loc_tname AS tlocation,	
		to_whom, dept_desig_name1, dept_desig_tname1,  off_level_dept_name1,off_level_dept_tname1,off_loc_name1 AS location1,off_loc_tname1 AS tlocation1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM fn_pet_actions_pet_no('".$petno1."')) pet
		where rnk=1";
		} else {
			$query=" select * from (
		SELECT petition_id,pet_action_id,action_type_name, action_type_tname, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name, dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_loc_name AS location,off_loc_tname AS tlocation,		
		to_whom, dept_desig_name1, dept_desig_tname1, off_level_dept_name1, off_level_dept_tname1,off_loc_name1 AS location1,off_loc_tname1 AS tlocation1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM fn_pet_actions_pet_no('".$petno1."')) pet";
		}	
		echo $page->generateXMLTag('Pending_Period', $pend_period);		
		$result = $db->query($query);
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	    $rowArr = $result->fetch(PDO::FETCH_NUM);
				
		if (sizeof($rowarray)==0)
		{
		if ($language=='E')
		{
			echo $page->generateXMLTag('no_action_taken', 'No action taken so far.');
		}
		else if ($language=='T')
		{
			echo $page->generateXMLTag('no_action_taken', 'இதுவரை எந்த நடவடிக்கையும் எடுக்கப்படவில்லை.');
		}
		else
		{
		echo $page->generateXMLTag('no_action_taken', 'No action taken so far.');
		}		
		}
		else	
		{
	    foreach($rowarray as $row) 
		
		{		
		if ($language=='E')
		{
			 $ac_re=	!empty($row['dept_desig_name1'])?$row['dept_desig_name1']. ', ' .$row['off_level_dept_name1'].', ' .$row['location1'] : "";
		}
		else if ($language=='T')
		{
			$ac_re=	!empty($row['dept_desig_tname1'])?$row['dept_desig_tname1']. ', ' .$row['off_level_dept_tname1'].', ' .$row['tlocation1'] : "";
		}
		else
		{
			$ac_re=	!empty($row['dept_desig_name1'])?$row['dept_desig_name1']. ', ' .$row['off_level_dept_name1'].', ' .$row['location1'] : "";
		}		
		
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);	   
		
		if ($language=='E')
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row['action_type_name']);
		}
		else if ($language=='T')
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row['action_type_tname']);
		}
		else
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row['action_type_name']);
		}		
		 
	    if ($language=='E')
		{
			  echo $page->generateXMLTag('Processing_Officials_value', "By &nbsp:&nbsp".$row['dept_desig_name'].', ' .$row['off_level_dept_name'].', ' .$row['location']."<br>"."To &nbsp:&nbsp".$ac_re);
		}
		else if ($language=='T')
		{
			echo $page->generateXMLTag('Processing_Officials_value', "அனுப்புநர்&nbsp:&nbsp".$row['dept_desig_tname'].', ' .$row['off_level_dept_tname'].', ' .$row['tlocation']."<br>"."பெறுநர்&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:&nbsp".$ac_re);
		}
		else
		{
			  echo $page->generateXMLTag('Processing_Officials_value', "By &nbsp:&nbsp".$row['dept_desig_name'].', ' .$row['off_level_dept_name'].', ' .$row['location']."<br>"."To &nbsp:&nbsp".$ac_re);
		}		

		echo $page->generateXMLTag('Action_Remarks_value', $row['action_remarks']);		
	    }	
		}
					
		
    echo "</response>";	
?>