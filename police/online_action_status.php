<?php 
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("common_fun.php");
include("Pagination.php");

$petno=stripQuotes(killChars(trim($_POST['petition_no'])));
$language=stripQuotes(killChars(trim($_POST['language'])));
$stype=stripQuotes(killChars(trim($_POST['stype'])));
	
$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date,pet_type_name, 
pet_type_tname,TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name,source_tname,subsource_name,
subsource_tname,griev_type_name,griev_type_tname,griev_subtype_name,griev_subtype_tname,
dept_name,dept_tname, grievance, 

canid,petitioner_initial,petitioner_name, father_husband_name, gender_name,comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name,comm_district_tname, comm_taluk_name,comm_taluk_tname,comm_rev_village_name, comm_rev_village_tname,comm_pincode, comm_email, comm_phone, comm_mobile,comm_country_name,comm_country_tname,comm_state_name,comm_state_tname,

griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name,griev_district_tname, 
griev_taluk_name, griev_taluk_tname, griev_rev_village_name,griev_rev_village_tname,griev_block_name, griev_block_tname,griev_lb_village_name, griev_lb_village_tname, griev_lb_urban_name, griev_lb_urban_tname, griev_pincode,aadharid,griev_division_name,griev_division_tname,griev_subdivision_name,griev_circle_name,off_level_dept_name

FROM vw_pet_master WHERE petition_no='".$petno."'";  

$query = "SELECT petition_id,petition_no, TO_CHAR(petition_date,'dd/mm/yyyy')as petition_date,pet_type_name, 
pet_type_tname,TO_CHAR(dob,'dd/mm/yyyy') AS dob, idtype_name, id_no, source_name,source_tname,subsource_name,
subsource_tname,griev_type_name,griev_type_tname,griev_subtype_name,griev_subtype_tname,
dept_name,dept_tname, grievance, 

canid,petitioner_initial,petitioner_name, father_husband_name, gender_name,comm_doorno, comm_aptmt_block, comm_street, comm_area, comm_district_name,comm_district_tname, comm_taluk_name,comm_taluk_tname,comm_rev_village_name, comm_rev_village_tname,coalesce(comm_pincode,griev_pincode) as comm_pincode, comm_email, comm_phone, comm_mobile,comm_country_name,comm_country_tname,comm_state_name,comm_state_tname,

griev_doorno, griev_aptmt_block, griev_street, griev_area, griev_district_name,griev_district_tname, 
griev_taluk_name, griev_taluk_tname, griev_rev_village_name,griev_rev_village_tname,griev_block_name, 
griev_block_tname,griev_lb_village_name, griev_lb_village_tname, griev_lb_urban_name, griev_lb_urban_tname,
griev_pincode,aadharid,griev_division_name,griev_division_name,griev_subdivision_name,griev_circle_name,
off_level_dept_name,fwd_off_level_dept_name
,coalesce(griev_district_name,zone_name,range_name,griev_division_name,griev_circle_name,comm_state_name) as location_name,coalesce(griev_district_tname,zone_tname,range_tname,griev_division_tname,griev_circle_tname,comm_state_tname) as location_tname

FROM vw_pet_master WHERE petition_no='".$petno."'";  
		 
$result = $db->query($query);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

echo "<response>";
if(sizeof($rowarray)==0) {		 
	$_SESSION['error_msg'] = "No Record found ! Enter valid Petition Number !!";	         
	echo $page->generateXMLTag('error','F');
} else {
echo $page->generateXMLTag('result', 'T');			

foreach($rowarray as $row) 
{
//petition address	
if ($language=='E') {
		$pet_off_address = $row['off_level_dept_name']." ".$row['location_name'];
		$pet_with = $row['fwd_off_level_dept_name'];
} else if ($language=='T') {
		$pet_off_address = $row['off_level_dept_tname']." ".$row['location_tname'];
		$pet_with = $row['fwd_off_level_dept_tname'];
} else {
	$pet_off_address = $row['off_level_dept_name']." ".$row['location_name'];
	$pet_with = $row'[fwd_off_level_dept_name'];
}	// petitioner communication address
if ($language=='E')
{
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_area'].', Pincode - '.$row['comm_pincode'].'.';
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
}
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_name'].', '.$row['comm_taluk_name'].' Taluk'.', '.$row['comm_district_name'].' District'.', '.$row['comm_state_name'].','.$row['comm_country_name'];
$address = $row['comm_area'].', Pincode - '.$row['comm_pincode'].'.';
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
$address = $row['comm_area'].', அ.கு.எண் - '.$row['comm_pincode'].'.';
$address = $row['comm_doorno'].', '.$row['comm_street'].','.$address;
} 
else 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
}
if ($row['comm_doorno'] != '' && $row['comm_street'] != '') 
{
$address = $row['comm_rev_village_tname'].', '.$row['comm_taluk_tname'].' தாலுக்கா'.', '.$row['comm_district_tname'].' மாவட்டம்'.', '.$row['comm_state_tname'].','.$row['comm_country_tname'];
$address = $row['comm_area'].', Pincode - '.$row['comm_pincode'].'.';
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
$address = $row['comm_area'].', Pincode - '.$row['comm_pincode'].'.';
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
echo $page->generateXMLTag('Petition_No', $row['petition_no']);
echo $page->generateXMLTag('Date', ' & Date:'.$row['petition_date']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petition_No', $row['petition_no']);
echo $page->generateXMLTag('Date', ' & தேதி:'.$row['petition_date']);
}
else
{
echo $page->generateXMLTag('Petition_No', $row['petition_no']);
echo $page->generateXMLTag('Date', ' & Date:'.$row['petition_date']);
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
echo $page->generateXMLTag('Petition_With', $pet_with);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petition_Office_Address', $pet_off_address);
echo $page->generateXMLTag('Petition_With', $pet_with);
}
else
{
echo $page->generateXMLTag('Petition_Office_Address', $pet_off_address);
echo $page->generateXMLTag('Petition_With', $pet_with);
}			
//மனுதாரரின் பெயர் & தந்தை / கணவர் பெயர்
if ($language=='E')
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address',$row['petitioner_initial']."&nbsp;". $row['petitioner_name'].' & Father / Husband Name :'.$row['father_husband_name']);
}
else if ($language=='T')
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address', $row['petitioner_initial']."&nbsp;".$row['petitioner_name'].' &  தந்தை / கணவர் பெயர் :'.$row['father_husband_name']);
}
else
{
echo $page->generateXMLTag('Petitioner_Name_Father_Spouse_Name_and_Address',$row['petitioner_initial']."&nbsp;". $row['petitioner_name'].' & Father / Husband Name :'.$row['father_husband_name']);
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

$link_pet_status="select petition_no, org_petition_no, to_char(l_action_entdt, 'dd-mm-yyyy hh12:mi:ss PM')::character varying AS action_date, l_action_type_code, action_type_name, action_type_tname, l_action_type_code,dept_desig_name,dept_desig_tname,location_name,location_tname, l_to_whom,to_dept_desig_name,to_dept_desig_tname,to_location_name,to_location_tname
		from fn_clubbed_petition_status(".$row['petition_id'].")";
		$status_rs=$db->query($link_pet_status);
		$status_rowarray = $status_rs->fetchall(PDO::FETCH_ASSOC);
		$k=1;
		foreach($status_rowarray as $status_row){
			$petition_no=$status_row['petition_no'];
			$action_type_name=$status_row['action_type_name'];
			$action_type_tname=$status_row['action_type_tname'];
			$action_date=$status_row['action_date'];
			if($_SESSION['lang']=='T'){
				$action_ent ="";
				$to_action_ent="";
				$action_ent = "<b>".$status_row['dept_desig_tname'].", ".$status_row['location_tname']."</b>";	
				if($status_row['l_to_whom']!=''){
				$action_ent.=" and sent to ";
				$to_action_ent = $status_row['to_dept_desig_tname'].", ".$status_row['to_location_tname'];
				}				
			}else{
				$action_ent ="";
				$to_action_ent="";
				$action_ent = "<b>".$status_row['dept_desig_name'].", ".$status_row['location_name']."</b>";
				if($status_row['l_to_whom']!=''){
				$action_ent.=" and sent to ";
				$to_action_ent = $status_row['to_dept_desig_name'].", ".$status_row['to_location_name'];
				}				
			}
			if($status_row['l_action_type_code']=='A'||$status_row['l_action_type_code']=='R'){
				if($status_row[l_action_type_code]=='A'){
					$color='#118e11;font-weight:bolder;';
				}if($status_row[l_action_type_code]=='R'){
					$color='#bd0505;font-weight:bold;';
				}
				$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: <lim style='color:".$color."'>".$status_row['action_type_name']."</lim> on ".$status_row['action_date']." by ".$action_ent." &emsp;<br>   ";
			}else{
				//$link_petition_status.= $k++.") ".$status_row[petition_no]." Status:  Under Process &emsp;<br>";
				if($status_row['action_type_name']!=''){
				$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: ".$status_row['action_type_name']." on ".$status_row['action_date']." by ".$action_ent." <b>".$to_action_ent."</b>&emsp;<br>";
				}else{
					$link_petition_status.= $k++.") ".$status_row['petition_no']." Status: No Action Taken So far.&emsp;<br>";
				}
			}
		}
		if (sizeof($status_rowarray) > 0) {
			echo $page->generateXMLTag('link_petition_status', $link_petition_status);
		}
}

}
			
		$pendsql="select action_type_code,action_type_name,pend_period from vw_petition_details  where petition_no='".$petno."'";		
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
		SELECT petition_id,pet_action_id,action_type_code,action_type_name, action_type_tname, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name, dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_loc_name AS location,off_loc_tname AS tlocation,	
		to_whom, dept_desig_name1, dept_desig_tname1,  off_level_dept_name1,off_level_dept_tname1,off_loc_name1 AS location1,off_loc_tname1 AS tlocation1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM fn_pet_actions_pet_no('".$petno."')) pet
		where rnk=1";
		} else {
			$query=" select * from (
		SELECT petition_id,pet_action_id,action_type_code,action_type_name, action_type_tname, action_remarks, to_char(action_entdt, 'DD/MM/YYYY HH24:MI:SS') as action_entdt, 
		action_entby, dept_desig_name, dept_desig_tname, off_level_dept_name, off_level_dept_tname, off_loc_name AS location,off_loc_tname AS tlocation,		
		to_whom, dept_desig_name1, dept_desig_tname1, off_level_dept_name1, off_level_dept_tname1,off_loc_name1 AS location1,off_loc_tname1 AS tlocation1,
		cast (rank() OVER (PARTITION BY petition_id ORDER BY action_entdt DESC)as integer) rnk
		FROM fn_pet_actions_pet_no('".$petno."')) pet";
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
			$ac_re=	!empty($row[''dept_desig_tname1''])?$row[''dept_desig_tname1'']. ', ' .$row[''off_level_dept_tname1''].', ' .$row[''tlocation1''] : "";
		}
		else
		{
			$ac_re=	!empty($row['dept_desig_name1'])?$row['dept_desig_name1']. ', ' .$row['off_level_dept_name1'].', ' .$row['location1'] : "";
		}		
		
		echo $page->generateXMLTag('pet_action_id', $row['pet_action_id']);	   
		
		if ($language=='E')
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row[action_type_name]);
			echo $page->generateXMLTag('Action_Remarks_value', $row['action_remarks']);		
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);	
		}
		else if ($language=='T')
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row['action_type_tname']);
			echo $page->generateXMLTag('Action_Remarks_value', $row['action_remarks']);		
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);	
		}
		else
		{
			  echo $page->generateXMLTag('Action_Taken_Date_Time', $row['action_entdt']."<br>".$row['action_type_name']);
			echo $page->generateXMLTag('Action_Remarks_value', $row['action_remarks']);		
		echo $page->generateXMLTag('action_type_code', $row['action_type_code']);	
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

		//echo $page->generateXMLTag('Action_Remarks_value', $row[action_remarks]);		
	    }	
		}
					
		
    echo "</response>";	
?>