<?php
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("Pagination.php");
//include("newSMS.php");
include('sms_airtel_code.php');
include_once 'common_lang.php';

/* $xml = new SimpleXMLElement($_POST['xml']);
$source = $xml->source;
$dept = $xml->dept;
$pet_type = $xml->pet_type;
$griev_maincode = $xml->griev_maincode;
$griev_subcode = $xml->griev_subcode;
$grievance = $xml->grievance;
$sub_level = $xml->sub_level;
$idtype = $xml->idtype;
$idno = $xml->idno;
$mobile_number = $xml->mobile_number;
$email_id = $xml->email;
$pet_enginitial = $xml->pet_eng_initial;
$pet_ename = $xml->pet_ename;
$father_ename = $xml->father_ename;
$gender = $xml->gender;
$community = $xml->community;
$petitioner_category = $xml->petitioner_category;
$comm_doorno = $xml->comm_doorno;
$commstreet = $xml->comm_street;
$commarea = $xml->comm_area;
$comm_dist = $xml->comm_dist;
$comm_taluk = $xml->comm_taluk;
$comm_rev_village = $xml->comm_rev_village;
$comm_state_id = 33;
$comm_country_id = 99;
$gre_dist = $xml->gre_dist;
$gre_range = $xml->gre_range;
$gre_zone = $xml->gre_zone;
$gre_division = $xml->gre_division;
$gre_subdivision = $xml->gre_subdivision;
$gre_circle = $xml->gre_circle;
$off_level = $xml->off_level;
$fwd_sub_level = $xml->fwd_sub_level;
$language = $lang['LANGUAGE'];

$comm_street = ($commstreet == "") ? null:$commstreet;
$commarea = ($commarea == "") ? null:$commarea;	
$mobile_number = ($mobile_number == "") ? null:$mobile_number; */	
$xml = new SimpleXMLElement($_POST['xml']);
$source = $xml->source;
$pet_type = 3;
$dept = $xml->dept;
$fwd_office_level = $xml->fwd_office_level;
$office_level1 = $xml->office_level;//echo $office_level1;
$office_level = $xml->office_level;//echo $office_level;
$office_level_id = $xml->office_level_id;//echo $office_level_id;
$griev_maincode = $xml->griev_maincode;
$griev_subcode = $xml->griev_subcode;
$survey_no = $xml->survey_no;
$sub_div_no = $xml->sub_div_no;
$grievance = $xml->grievance;
$gre_dist = $xml->gre_dist;
$gretaluk = $xml->gre_taluk;
$gre_revvillage = $xml->gre_rev_village;
$greblock = $xml->gre_block;
$gre_tpvillage = $xml->gre_tp_village;
$gre_urbanbody = $xml->gre_urban_body;
$gre_division = $xml->gre_division;
$gre_subdivision = $xml->gre_subdivision;
$gre_circle = $xml->gre_circle;
$aadharid = $xml->aadharid;
$pet_enginitial = $xml->pet_eng_initial;
$pet_ename = $xml->pet_ename;
$father_ename = $xml->father_ename;
$gender = $xml->gender;
$community = $xml->community;
$petitioner_category = $xml->petitioner_category;
$mobile_number = $xml->mobile_number;
$email = $xml->email;
$comm_doorno = $xml->comm_doorno;
$commstreet = $xml->comm_street;
$commarea = $xml->comm_area;
$comm_dist = $xml->comm_dist;
$comm_taluk = $xml->comm_taluk;
$comm_rev_village = $xml->comm_rev_village;
$zone_id = $xml->zone_id;
$range_id = $xml->range_id;
$state_id = $xml->state_id;
$off_level_dept_id = $xml->off_level_dept_id;
$pincode = $xml->pincode;
$idtype_id = $xml->idtype;
$id_no = $xml->idno;
$offlocation = $xml->offlocation;
$offname = $xml->offname;
$pattern = $xml->pattern;
$old_pet_no = $xml->old_pet_no;
$comm_state_id = 33;
$comm_country_id = 99;
$language = $lang['LANGUAGE'];
	$sub_level = $xml->sub_level;
$comm_street = ($commstreet == "") ? null:$commstreet;
$commarea = ($commarea == "") ? null:$commarea;	
$mobile_number = ($mobile_number == "") ? null:$mobile_number;
$id_no = ($id_no == "") ? null:$id_no;
$idtype_id = ($idtype_id == "") ? null:$idtype_id;

$date= Date("d/m/Y");

/**********************  FOR GET GRIEVANCE DISTRICT, TALUK, VILLAGE,GRIEVANCE  NAMES ******************************************/  

if($gre_dist!="" || $gre_dist!=0) {
	$dist_sql = "SELECT district_name,district_tname FROM mst_p_district where district_id='$gre_dist'";
	$dist_rs=$db->query($dist_sql);
	$dist_row = $dist_rs->fetch(PDO::FETCH_BOTH);
	if ($language=='E')
	{
		$griev_dist_name=$dist_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_dist_name=$dist_row[1];
	}
	else
	{
		$griev_dist_name=$dist_row[0]; 
	}
}
	//echo "gretalukgretalukgretalukgretaluk::::".$gretaluk;
if($grezone!="" || $grezone!=0)
{
	$zone_sql = "SELECT zone_name,zone_tname FROM mst_p_sp_zone where zone_id='$grezone'";
	$zone_rs=$db->query($zone_sql);
	$zone_row = $zone_rs->fetch(PDO::FETCH_BOTH);
	$griev_zone_name=$zone_row[0];
		
	if ($language=='E')
	{
		$griev_zone_name=$zone_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_zone_name=$zone_row[1];
	}
	else
	{
		$griev_zone_name=$zone_row[0]; 
	}	
}
if($gre_range!="" || $gre_range!=0)
{
	$range_sql = "SELECT range_name,range_tname FROM mst_p_sp_range where range_id='$gre_revvillage'";
	$range_rs=$db->query($range_sql);
	$range_row = $village_rs->fetch(PDO::FETCH_BOTH);
	$range_name=$range_row[0];
	if ($language=='E')
	{
		$range_name=$range_row[0]; 
	}
	else if ($language=='T')
	{
		$range_name=$range_row[1];	
	}
	else
	{
		$range_name=$range_row[0]; 
	}
}

if($gredivision!="" || $gredivision!=0)
	{
	$division_sql = "SELECT division_name,division_tname FROM mst_p_sp_division where division_id='$gredivision'";
	$division_rs=$db->query($division_sql);
	$division_row = $division_rs->fetch(PDO::FETCH_BOTH);
	$griev_division_name=$division_row[0];
	if ($language=='E')
	{
		$griev_division_name=$division_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_division_name=$division_row[1];	
	}
	else
	{
		$griev_division_name=$division_row[0]; 
	}		
}

if($gre_subdivision!="" || $gre_subdivision!=0)
{
	$subdivision_sql = "SELECT subdivision_name,subdivision_tname FROM mst_p_sp_subdivision where subdivision_id=$gre_tpvillage";
	$subdivision_rs=$db->query($subdivision_sql);
	$subdivision_row = $subdivision_rs->fetch(PDO::FETCH_BOTH);
	$griev_subdivision_name=$subdivision_row[0];
	if ($language=='E')
	{
		$griev_subdivision_name=$subdivision_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_subdivision_name=$subdivision_row[1];	
	}
	else
	{
		$griev_subdivision_name=$subdivision_row[0]; 
	}
}
	
if($gre_circle!="" || $gre_urbanbgre_circleody!=0)
{
	$circle_sql = "SELECT circle_name,circle_tname FROM mst_p_circle where circle_id='$gre_circle'";
	$circle_rs=$db->query($circle_sql);
	$circle_row = $circle_rs->fetch(PDO::FETCH_BOTH);
	$circle_name=$circle_row[0];
	if ($language=='E')
	{
		$circle_name=$circle_row[0]; 
	}
	else if ($language=='T')
	{
		$circle_name=$circle_row[1];	
	}
	else
	{
		$circle_name=$circle_row[0]; 
	}	
}


	$gre_sql = "select griev_type_name,griev_type_tname from lkp_griev_type where griev_type_id='$griev_maincode' ";
    $gre_rs=$db->query($gre_sql);
	$gre_row = $gre_rs->fetch(PDO::FETCH_BOTH);
 	$griev_name=$gre_row[0];  
	if ($language=='E')
	{
		$griev_name=$gre_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_name=$gre_row[1];
	}
	else
	{
		$griev_name=$gre_row[0]; 
	}
	 
	$gre_sub_sql = "select griev_subtype_name,griev_subtype_tname from lkp_griev_subtype where griev_type_id='$griev_maincode' and griev_subtype_id='$griev_subcode'";
 	$gre_sub_rs=$db->query($gre_sub_sql);
	$gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH);
 	$griev_sub_name=$gre_sub_row[0];
	
	if ($language=='E')
	{
		$griev_sub_name=$gre_sub_row[0]; 
	}
	else if ($language=='T')
	{
		$griev_sub_name=$gre_sub_row[1];
	}
	else
	{
		$griev_sub_name=$gre_sub_row[0]; 
	}

	if($griev_taluk_name!="")
		$taluk_block_urban=$griev_taluk_name;
	else if($griev_block_name!="")
		$taluk_block_urban=$griev_block_name;
	else
		$taluk_block_urban=$griev_urban_name;
		
	
	/* if($griev_village_name!="")
		$villae_lbvillage=$griev_village_name;
	else
		$villae_lbvillage=$griev_lb_village_name;
	
	$dept_sql = "select dept_name,dept_tname from usr_dept where dept_id='$dept'";
	//echo $dept_sql;
	$dept_rs = $db->query($dept_sql);
	$dept_row = $dept_rs->fetch(PDO::FETCH_BOTH); 
	$dept_name = $dept_row[0];
	if ($language=='E')
	{
		$dept_name=$dept_row[0]; 
	}
	else if ($language=='T')
	{
		$dept_name=$dept_row[1];
	}
	else
	{
		$dept_name=$dept_row[0]; 
	} */

/**********************  END ******************************************/

/**********************  FOR GET COMMUNICATION ADDRESS DISTRICT, TALUK, VILLAGE,GRIEVANCE  NAMES ******************************************/

	/* $dist_sql = "SELECT district_name,district_tname FROM mst_p_district where district_id='$comm_dist'";
	$dist_rs=$db->query($dist_sql);
	$dist_row = $dist_rs->fetch(PDO::FETCH_BOTH);
	$comm_dist_name=ucfirst(strtolower($dist_row[0]));  
	 $taluk_sql = "SELECT taluk_name,taluk_tname FROM mst_p_taluk where taluk_id='$comm_taluk'";
	$taluk_rs=$db->query($taluk_sql);
	$taluk_row = $taluk_rs->fetch(PDO::FETCH_BOTH);
	$comm_taluk_name=ucfirst(strtolower($taluk_row[0]));  
	
	$village_sql = "SELECT rev_village_name,rev_village_tname FROM mst_p_rev_village 
	where rev_village_id='$comm_rev_village'";
	$village_rs=$db->query($village_sql);
	$village_row = $village_rs->fetch(PDO::FETCH_BOTH);
	$comm_village_name=ucfirst(strtolower($village_row[0]));   */
	$source = -1;$dept=1;
	$src_sql = "SELECT source_name,source_tname FROM lkp_pet_source where source_id='$source'";
	$src_rs=$db->query($src_sql);
	$src_row = $src_rs->fetch(PDO::FETCH_BOTH);
	$source_name=strtoupper($src_row[0]);
//echo "1111111111111111111111111111111111111>>>>>>>>>>".$mobile_number.'<br>';
	if($fwd_office_level!=''){
	$f_office_sql = "select fwd_office_level_name,fwd_office_level_tname from lkp_fwd_office_level where  fwd_office_level_id=".$fwd_office_level."";
	$f_office_rs=$db->query($f_office_sql);
	$f_office_row=$f_office_rs->fetch(PDO::FETCH_BOTH);
	}
	
	$gen_sql = "SELECT gender_name,gender_tname FROM lkp_gender where gender_id='$gender'";
	$gen_rs=$db->query($gen_sql);
	$gen_row = $gen_rs->fetch(PDO::FETCH_BOTH);
	$gender_nm=strtoupper($gen_row[0]); 
	if ($language=='E')
	{
		$gender_nm=$gen_row[0]; 
	}
	else if ($language=='T')
	{
		$gender_nm=$gen_row[1];
	}
	else
	{
		$gender_nm=$gen_row[0]; 
	}
			 
	
	/* if ($language=='E') 
	{
		$comm_dist_name=$dist_row[0];
		$comm_taluk_name=$taluk_row[0];	
		$comm_village_name=$village_row[0]; 
		$source_name=strtoupper($src_row[0]);
		$f_office_name=$f_office_row[0];
		//$subsource_name=strtoupper($subsrc_row[0]);
	}
	else if ($language=='T') 
	{
		$comm_dist_name=$dist_row[1];
		$comm_taluk_name=$taluk_row[1];
		$comm_village_name=$village_row[1]; 
		$source_name=$src_row[1];
		$f_office_name=$f_office_row[1];
	}else 
	{
		$comm_dist_name=$dist_row[0];
		$comm_taluk_name=$taluk_row[0];	
		$comm_village_name=$village_row[0]; 
		$source_name=strtoupper($src_row[0]);
		$f_office_name=$f_office_row[0];
	}	 */
	
	
	if($fwd_office_level!=''){
	$f_office_sql = "select fwd_office_level_id,fwd_office_level_name,fwd_office_level_tname from lkp_fwd_office_level where  fwd_office_level_id=".$fwd_office_level."";
	$f_office_rs=$db->query($f_office_sql);
	$f_office_row=$f_office_rs->fetch(PDO::FETCH_BOTH);
	}
	$pet_type_name=$ptype_row['pet_type_name'];
	
	if ($language=='E') 
	{
		if ($pet_type == '1') 
		{
			$pet_type_name = ' - Application (New Petition)';
		} 
		else 
		{
			$pet_type_name = ' - Grievance (Repeated Petition)';
		}
	} else if ($language=='T') 
	{
		if ($pet_type == '1') 
		{
			$pet_type_name = ' - மனு ( புதிய மனு)';
		} 
		else 
		{
			$pet_type_name = ' - குறை (மீண்டும் சமர்பிக்கப் படும் மனு)';
		}
	}
	else
		{
		if ($pet_type == '1') 
		{
			$pet_type_name = ' - Application (New Petition)';
		} 
		else 
		{
			$pet_type_name = ' - Grievance (Repeated Petition)';
		}
	} 
	
	
	$community_category_name='';
	if ($community != '') {
		$sql="SELECT pet_community_name, pet_community_tname FROM lkp_pet_community where pet_community_id=".$community."";
		$rs = $db->query($sql);
		$row = $rs->fetch(PDO::FETCH_BOTH); //$lang['Community_Label']
		
		if ($language=='E')
		{
			$pet_community_name = $lang['Community_Label'].' : '.$row [0];
		}
		else if ($language=='T')
		{
			$pet_community_name = $lang['Community_Label'].' : '.$row [1];
		}
		else
		{
			$pet_community_name = $lang['Community_Label'].' : '.$row [0];
		}
	
		$community_category_name .= $pet_community_name;
	}
		 
	if ($petitioner_category != '') {
		$sql="SELECT  petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category where petitioner_category_id=".$petitioner_category."";
		$rs = $db->query($sql);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		
		if ($language=='E')
		{
			$petitioner_category_name = $lang['Category_Label'].' : '.$row [0];
		}
		else if ($language=='T')
		{
			$petitioner_category_name = $lang['Category_Label'].' : '.$row [1];
		}
		else
		{
			$petitioner_category_name = $lang['Category_Label'].' : '.$row [0];
		}
		
		if ($community_category_name != '') {
			$community_category_name .= ' / '. $petitioner_category_name;
		} else {
			$community_category_name .=  $petitioner_category_name;
		}
		
	}
	
	/**************** FOR PETITION NUMBER GENERATION *******************/
 	$petiton_no='';
if($_SERVER['REQUEST_METHOD']=='POST')
	{


	$ip=$_SERVER['REMOTE_ADDR'];
   
    $cur_date=explode('/',$date);
	$day=$cur_date[0];
	$mnth=$cur_date[1];
	$yr=$cur_date[2];
	$cur_dt=$yr.'-'.$mnth.'-'.$day;
 
 // For validate the date format 
	$dob1=explode('/',$dob);
	$day=$dob1[0];
	$mnth=$dob1[1];
	$yr=$dob1[2];
    $dateofbirth=$yr.'-'.$mnth.'-'.$day;
	

	
$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/'; 
$dept = ($dept == "") ? 'null':$dept;
$gre_dist = ($gre_dist == "") ? 'null':$gre_dist;
$gre_taluk = ($gretaluk == "" || $gretaluk == '0') ? 'null':$gretaluk;
$griev_rev_village = ($gre_revvillage == "") ? 'null':$gre_revvillage;
$gre_block = ($greblock == "" || $greblock == '0') ? 'null':$greblock;
$gre_tp_village = ($gre_tpvillage == "") ? 'null':$gre_tpvillage;
$gre_urban_body = ($gre_urbanbody == "" || $gre_urbanbody == '0' ) ? 'null':$gre_urbanbody;
$gre_division = ($gre_division == "") ? 'null':$gre_division;
$griev_maincode = ($griev_maincode=="") ? 'null' :$griev_maincode;
$community = ($community=="") ? 'null' :$community;
$petitioner_category = ($petitioner_category=="") ? 'null' :$petitioner_category;


$survey_no = ($survey_no == "") ? null:$survey_no;
$sub_div_no = ($sub_div_no == "") ? null:$sub_div_no;

$gre_subdivision = ($gre_subdivision == "") ? 'null':$gre_subdivision;
$gre_circle = ($gre_circle == "") ? 'null':$gre_circle;
$fwd_office_level = ($fwd_office_level == "") ? 'null':$fwd_office_level;
$document_counts = $xml->document_counts;
$passport_number = null; 
$pincode = ($pincode=="") ? '' :$pincode;
$email = ($email=="") ? '' :$email;

$t=0;
for($t=0;$t<$document_counts;$t++)
{
$document_types = $xml->document_types;
$filetype = explode(',',$document_types);

$filetypes = $filetype[$t];
}
$file_type=array("application/pdf","image/jpeg","application/download");

$document_names = $xml->document_names;
$off_level = $xml->fwd_office_level;
/* $off_sql = "select off_level_id,off_level_dept_id FROM usr_dept_off_level where off_level_dept_id=".$office_level1.";";
//echo $office_level1.' >>>>>>>'.$off_sql;echo "<br>";
			$off_rs=$db->query($off_sql);
			if(!$off_rs)
			{
			print_r($db->errorInfo());
			exit;
			}	
			while($off_row = $off_rs->fetch(PDO::FETCH_BOTH)){
				$offname=$off_row['off_level_id'];
				$off_level_dept_id=$off_row['off_level_dept_id'];
			} */
			//echo $offlocation;
if ($offname==7){
	//echo 'state_id';
	$state_id =29;
	$sql = "SELECT state_name FROM mst_p_state where state_id=".$state_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==9){
	//echo 'zone_id';try
	$zone_id =$offlocation;
	$sql = "SELECT zone_name FROM mst_p_sp_zone where zone_id=".$zone_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==11){
	//echo 'range_id';
	$range_id =$offlocation;
	$sql = "SELECT range_name FROM mst_p_sp_range where range_id=".$range_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==13){
	//echo 'district_id';
	$district_id =$offlocation;
	if($pattern==4){
	$district_id =$off_level;
	}//echo 'district_id'.$district_id;exit;
	//$zone_id =$offlocation;
	$sql = "SELECT district_name FROM mst_p_district where district_id=".$district_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==42){
	//echo 'division_id';
	$division_id =$offlocation;
	$sql = "SELECT division_name FROM mst_p_sp_division where division_id=".$division_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==44){
	//echo 'subdivision_id';
	$subdivision_id = $offlocation;
	$sql = "SELECT subdivision_name FROM mst_p_sp_subdivision where subdivision_id=".$subdivision_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}else if ($offname==46){
	//echo 'circle_id';
	$circle_id =$offlocation;
	$sql = "SELECT circle_name FROM mst_p_sp_circle where circle_id=".$circle_id;
	$rs=$db->query($sql);
	$row = $rs->fetch(PDO::FETCH_BOTH);
	$off_loc_name=$row[0];
}
//echo $sub_level;exit;
 if($sub_level == 1||$sub_level == 14){
	$fwd_office_level_id=7;
}else if($sub_level == 4||$sub_level == 15||$sub_level == 20){
	$fwd_office_level_id=9;
}else if($sub_level == 5||$sub_level == 16){
	$fwd_office_level_id=11;
}else if($sub_level == 6||$sub_level == 17||$sub_level == 21){
	$fwd_office_level_id=13;
}  
$state_id = ($state_id=="") ? 'null' :$state_id;
$zone_id = ($zone_id=="") ? 'null' :$zone_id;
$range_id = ($range_id=="") ? 'null' :$range_id;
$district_id = ($district_id=="") ? 'null' :$district_id;
$division_id = ($division_id=="") ? 'null' :$division_id;
$subdivision_id = ($subdivision_id=="") ? 'null' :$subdivision_id;
$circle_id = ($circle_id=="") ? 'null' :$circle_id;
$idtype_id = ($idtype_id=="") ? 'null' :$idtype_id;
$off_level_in=$office_level_id;
//echo $off_level_in.">>>>>>".$office_level_id.">>>>>>".$office_level;
if($pattern==3 &&$office_level_id==14){
	$off_level_in=$office_level_id;
}else if($pattern==3 &&$office_level_id==15){
	$off_level_in=$office_level_id;
}else if($pattern==3 &&$office_level_id==16){
	$off_level_in=$office_level_id;
}else if($pattern==4 &&$office_level_id==20){
	$off_level_in=$office_level_id;
}else if($pattern==4 &&$office_level_id==21){
	$off_level_in=$office_level_id;
}else if(($pattern==1 && $office_level_id==42 )||($pattern==1 && $office_level_id==46)||($pattern!=1)){
	$off_level_in=$office_level;
}
//echo ">>>>>>".$off_level_in."<<<<<<<<>-!";exit;
/*
off_level_dept_id - off_leevl_id
if 7 
steta=33
else if 9
zone_id
11
rage
13 district
42 division
44 subdivision_id
46 circle
*/
$comm_dist=($comm_dist == "") ? 'null':$comm_dist;
$comm_taluk=($comm_taluk == "") ? 'null':$comm_taluk;	
$comm_rev_village=($comm_rev_village == "") ? 'null':$comm_rev_village; 
$fwd_office_level_id=$sub_level;

$sql = "SELECT fn_online_pet_master_insert('".$petiton_no."','".$pet_enginitial."','".$pet_ename."','".$father_ename."',
".$gender.",".$griev_maincode.",".$griev_subcode.",'".$grievance."','".$comm_doorno."','".$comm_doorno."','".$commstreet."',
'".$commarea."',".$comm_dist.",".$comm_taluk.",".$comm_rev_village.",'".$email."','".$mobile_number."',".$district_id.",
".$gre_taluk.",".$griev_rev_village.",".$gre_block.",".$gre_tp_village.",".$gre_urban_body.",".$source.",".$division_id.",".$dept.",".$pet_type.",'".$survey_no."','".$sub_div_no."',".$subdivision_id.",".$circle_id.",".$fwd_office_level_id.",".$comm_country_id.",".$comm_state_id.",'".$passport_number."',".$community.",".$petitioner_category.",".$zone_id.",".$range_id.",".$state_id.",".$off_level_in.",'".$pincode."',".$idtype_id.",'".$id_no."')";  //state,zone,rane
	
	
	//echo "333333333333333333333";	
//echo $sql;exit;
	 $result=$db->query($sql);
	 //exit;
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$pet_act_id='';		
	 
	foreach($rowarray as $row){
		$pet_act_id=$row[0];
	}
	
	if(in_array($filetypes,$file_type,true))
	{
		
	$document_names = $xml->document_names;
	$document_tmp_names = $xml->document_tmp_names;
	$document_sizes = $xml->document_sizes;
	$document_types = $xml->document_types;
	$document_counts = $xml->document_counts;
	$current_date = date('Y-m-d h:i:s');  
	$t=0;
	for($t=0;$t<$document_counts;$t++)
	{
		$filename = explode(',',$document_names);
		$filenames = $filename[$t];
		
		$filetmp_name = explode(',',$document_tmp_names);
		$filetmp_names = $filetmp_name[$t];
		
		$filesize = explode(',',$document_sizes);
		$filesizes = $filesize[$t];
		
		$filetype = explode(',',$document_types);
		$filetypes = $filetype[$t];
		
		$f = fopen($filetmp_names,'r');
		$data = fread($f, filesize($filetmp_names));

		$content = pg_escape_bytea($data);
		fclose($f);

		$sql ="INSERT INTO pet_master_doc (petition_id,doc_content,doc_name,doc_size,doc_type,ent_ip_address,doc_entdt)VALUES('".$pet_act_id."','".$content."','".$filenames."','".$filesizes."','".$filetypes."','".$_SERVER['REMOTE_ADDR']."','".$current_date."')";
			$result=$db->query($sql);
		}
	}
 }


 if ($pet_act_id != -1) {

	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$pet_act_id;
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0];
	
	//-------------old petition no insert--------------->>>>>below
	 $petno_sql1 = "SELECT petition_no,org_petition_no FROM pet_master where petition_id=".$pet_act_id;
	$petno_rs1=$db->query($petno_sql1);
	$petno_row1 = $petno_rs1->fetch(PDO::FETCH_BOTH);
	if($petno_row1[1]!=''){
	$petition_no1= $petno_row1[1];
	}else{
	$petition_no1= $petno_row1[0];
	}
	if($old_pet_no==''){
		$sql ="UPDATE pet_master set org_petition_no='".$petition_no1."' where petition_id=".$pet_act_id.""; 
		$result=$db->query($sql);
	} else{
		$sql ="UPDATE pet_master set org_petition_no='".$old_pet_no."' where petition_id=".$pet_act_id.""; 
		$result=$db->query($sql);
	}
  //-------------old petition no insert--------------->>>>>>above
	if ($mobile_number!="") {
	$mobile_number = str_replace("'", "", $mobile_number);
	if($language == 'E')
	{
	  //$strContent = "Your Petition No  ".$petition_no." is received. URL to check the status: http://locahost/police";
	  $strContent = '';//"Your Petition No ".$petition_no." is received. URL to check the status: https://locahost/police - Tamil Nadu e-Governance Agency."
	  $ucode='0';
	  $ct_id = '1007534064096719952';
	}
	else if($language == 'T')
	{
	  //$strContent = "தங்களுடைய மனு எண்  ".$petition_no." பெறப்பட்டது. தங்கள் மனுவின் நிலையை "." http://locahost/police"." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
	  $strContent = '';//"தங்களுடைய மனு எண்".$petition_no." பெறப்பட்டது. தங்கள் மனுவின் நிலையை https://locahost/police என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம் - தமிழ்நாடு மின்ஆளுமை முகமை."
	  $ucode='2';
	  $ct_id = '1007806599718876169';
	}
	else 
	{
	  //$strContent = "Your Petition No  ".$petition_no." is received. URL to check the status: http://locahost/police";
	  $strContent = '';//"Your Petition No ".$petition_no." is received. URL to check the status: https://locahost/police - Tamil Nadu e-Governance Agency."
	  $ucode='0';
	  $ct_id = '1007534064096719952';
	}
	
	//echo $strContent;
	//$strStatus = SMS($mobile_number,$strContent,$ucode,$ct_id);
	$strContent = '';
	}
	//}
	
?>
<script type="text/javascript">
$(window).keydown(function(event) {
  if(event.ctrlKey && event.keyCode == 80) { 
    //console.log("Hey! Ctrl+P event captured!");
    printwindow1('1'); 
  }  
}); 

function preventBack(){window.history.forward();}

    setTimeout("preventBack()", 0);

    window.onunload=function(){null};
	 
function printwindow1(val)
{
	document.getElementById("myTopnav").style.display='none';
	document.getElementById("header").style.display='none';
	document.getElementById("btn_row").style.display='none';
	window.print();
	document.getElementById("myTopnav").style.display='';
	document.getElementById("header").style.display='block';
	document.getElementById("btn_row").style.display='';
}
LogoutButton.addEventListener('click', logout, false);	

/* function closepage() {
	document.location = 'logout.php';
} */	

</script>
<?php 
//include("menu_home.php");
/*					width: 500,
					height:309
*/
?>		

<?php


$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";

	$petition_id = $pet_act_id;	
	//$url='http://localhost/police/getPetitionStatusQR.php?pet_id='.$petition_id;
	$url='http://localhost/gdp_police/getPetitionStatusQR.php?pet_id='.$petition_id;
	//echo $url;
	include('phpqrcode/qrlib.php');
	
	$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
	$PNG_WEB_DIR = 'temp/';
	
	if (!file_exists($PNG_TEMP_DIR)) mkdir($PNG_TEMP_DIR);
	
	$filename = $PNG_TEMP_DIR.'test.png';
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 10;
	$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
	QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
	
	$sql = "select petition_no from pet_master where petition_id=".$petition_id."";
	$result=$db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$petition_no='';		
	foreach($rowarray as $row){
		$petition_no=$row[0];
	}			
	
	$sql = "SELECT off_level_dept_name,off_level_dept_tname from usr_dept_off_level where off_level_dept_id=".$fwd_office_level_id."";
	$result=$db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$off_level_dept_name='';		
	foreach($rowarray as $row){
		if ($language=='E'){
			$off_level_dept_name=$row[0];
		}else if ($language=='T'){
			$off_level_dept_name=$row[1];
		}else{
			$off_level_dept_name=$row[0];
		}
	}
	$sql = "SELECT off_level_dept_name,off_level_dept_tname from usr_dept_off_level where off_level_dept_id=".$off_level_in."";
	$result=$db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$fwd_off_level_dept_name='';		
	foreach($rowarray as $row){
		if ($language=='E'){
			$fwd_off_level_dept_name=$row[0];
		}else if ($language=='T'){
			$fwd_off_level_dept_name=$row[1];
		}else{
			$fwd_off_level_dept_name=$row[0];
		}
	}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  />
<form name="pm_pet_process" id="pm_pet_process" action="pm_pet_processing.php" method="post">
<input type="hidden" name="pet_act_id" id="pet_act_id" value="<?PHP echo $pet_act_id;?>"/>
<!--<div class="form_heading">
	<div class="heading"><?PHP //echo $label_name[0]; //Acknowledgement ?></div>
</div>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
function downloadPDF() {
	html2canvas(document.getElementById('ack_viewTbl'), {
		onrendered: function (canvas) {
			var data = canvas.toDataURL();
			var docDefinition = {
				content: [{
					image: data,
					width: 550,
					height:150
				}]
			};
			pdfMake.createPdf(docDefinition).download("PPP_Petition_Ack.pdf");
		}
	});
}
</script>
<style media="print">
 @page {
  size: auto;
  margin: 0;
       }

</style>
<style>
  tr {
  font-size: 16px;
}
td, th {
	padding: 0px !important;
	font-size: 16px;
	color: #000000;
	padding-left: 10px !important;
	padding-bottom: 0px !important;
	//font-weight: bold;
}
th {
	font-size: 16px;
	line-height: 8px;
}
.viewTbl{
	margin-bottom: 10px;
}
th {
	background: #DDDDDD;
	color: #000000;
}
.pad_t {
	padding: 9px !important;
}
#prnt_img{
	margin-top: 9px ;
}
.sms_me{
	text-align:center;
	color:red;
}
</style>
	      
<div class="contentMainDiv" style="width:98%;margin-right:auto;margin-left:auto;" align="center">
	<div class="contentDiv">
	<table class="ack_viewTbl" id="ack_viewTbl" border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
			<tbody>
    <tr>
			<td colspan="2">
				<center><b><?PHP echo $source_name; //Source Name?></b></center>
			</td>
	</tr>          
             
<tr>
    <td colspan="2" class="heading" >
    <img height="70" width="70" src="theme/images/emblem-dark.png" id="prnt_img" align="left"/> 
<?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" id="prnt_img" align="right" height="70" width="70" style="margin-right:30px" />'; ?><br>
     <center>
		<label style="font-weight:bold;font-size:17px;margin-top: 3px;"><?php echo $lang['HEADER_LABEL']; ?></label> 
		<br><label style="font-weight:bold;font-size:17px;"><?php echo $lang['ACKNOWLEDGEMENT_LABEL']; ?></label><br> 
<label style="font-size:15px;font-weight: normal;"><?php echo $lang['ACKNOWLEDGEMENT_CHECK_STATUS_LABEL']; ?>
<?php //echo '<b>'.$petition_no.' </b>' .$lang['ACKNOWLEDGEMENT_CHECK_STATUS1_LABEL'];?>

</label>
		</center>    
                </td>									

             </tr>              
			 <tr><?php
				
				//QRcode::png($url); 
			 ?></tr>
             <tr>
             <!--td colspan="3" class="heading" style="background-color:#BC7676">
			  <center><?PHP //echo $label_name[0]; //Acknowledgement ?></center> </td>
              </td-->
			 </tr>
			
		<tr>
			<td style="width: 31%;"><?php echo $lang['PETITION_NO_DATE_AND_TYPE_LABEL']; ?></td>
			<td ><?php echo "<b>".$petition_no.'</b> & '.'Date:'.' '.$date ?>&nbsp;&nbsp;&nbsp;&nbsp;</td> 					
		</tr>
            
        <tr>
			<td><?php //echo $lang['DEPARTMENT_LABEL'].' , '; ?><?php echo $lang['PETITION_MAIN_AND_SUB_CATEGORY_LABEL']; ?></td>
			<td colspan="2"> <?php echo $griev_name;?> <?php echo ' & '.$griev_sub_name;?></td>
		</tr>          

		<tr>
			<td ><?php echo $lang['PETITION_DETAILS_LABEL']; ?></td>
			<td colspan="2">
			<?php echo $grievance;?></b></td>
		</tr>		   
		
		
		<?php
		if($griev_taluk_name!="")
			$taluk_block_urban=$griev_taluk_name;
		else if($griev_block_name!="")
			$taluk_block_urban=$griev_block_name;
		else
			$taluk_block_urban=$griev_urban_name;
		
		if($griev_village_name!="")
			$villae_lbvillage=$griev_village_name;
		else
			$villae_lbvillage=$griev_lb_village_name;
		
		if($gre_flatno!="")
			$gre_flatno=",".$gre_flatno;
		if($grearea!="")
			$grearea=",".$grearea;
		?>
		<tr>
		<td>
		<?php echo $lang['CONCERNED_OFFICER_ADDRESS_LABEL']; ?>
		</td>
		<td colspan="2">  <?php echo $fwd_off_level_dept_name.' '.$off_loc_name; ?></td>
		</tr><tr>
		<td>
		<?php echo 'Submission level'; ?>
		</td>
		<td colspan="2">  <?php echo $off_level_dept_name; ?></td>
		</tr>
		<tr>
			<td ><?php echo $lang['PETITIONER_AND_FATHERHUSBAND_NAME_LABEL']; ?></td><td colspan="2">  <?php echo $pet_enginitial."&nbsp;".$pet_ename." &  ";?> <?php echo $father_ename?></td>
		</tr>
			
		
		<?php if ((strlen(trim($community_category_name))) > 0) { ?>
		<tr>
		<td><?php echo  $lang['Community_Category_Label']//Petitioner Community and Category;?></td>
		<td><?PHP echo $community_category_name; ?></td>
		</tr>
		<tr>
		<?php } ?>
		
			
			<tr>
			<!--td><?PHP //echo $label_name[15]; //DOB ?> : <?php //echo $dob;?></td-->
			
			
		<?php
		if($comm_doorno!="")
			$comm_doorno=$comm_doorno;
		if($comm_block_no!="")
			$comm_block_no=", ".$comm_block_no;
		if($commstreet!="")
			$commstreet=", ".$commstreet;
			//$commstreet=", ".ucfirst(strtolower($commstreet));
		if($commarea!="")
			$commarea=", ".$commarea;
		?>
			
			<td ><?php echo $lang['ADDRESS_LABEL']; ?></td>
			<td colspan="2">
			<?php
			if ($language=='E')
			{
				echo $comm_doorno.$commstreet.$commarea.", Pincode - ".$pincode.".";
			}
			else if ($language=='T')
			{
				echo $comm_doorno.$commstreet.$commarea.", அ.கு.எண் - ".$pincode.".";
			}
			else
			{
				echo $comm_doorno.$commstreet.$commarea.", Pincode - ".$pincode.".";
			}
			?>
		<?php /* echo $comm_doorno.$comm_block_no.$commstreet.$commarea.", ".$comm_village_name.$label_name[27].", 
			".$comm_taluk_name.$label_name[26].", ".$comm_dist_name.$label_name[25]." ".$comm_pincode."."; */?>	
			</td>
			</tr>
                 <tr>
			<td ><?php if ($email==''){echo $lang['MOBILE_NUMBER_LABEL'];}else{echo $lang['MOBILE_NUMBER_LABEL']; echo "& Email";} ?></td><td colspan="2">  <?php if ($email==''){echo "<b>".$mobile_number."</b>"; }else{echo "<b>".$mobile_number."</b>"; echo " & ".$email;}?></td>
		</tr>
		  <tr>
			<td colspan="7" style="color: red;" class="text-center"><?php echo $lang['REMARKS']; ?></td>
		</tr>
           
		</tbody>
			</table>
			 	<div class="taple_scroll">
	<table class="gridTbl" style="width: 98%;"  >
	<thead>
		<tr id='btn_row' >
			<td colspan="7" class="text-center pad_t" >
			 <a class="btn btn-primary fa fa-print"  name="" id="dontprint1" onClick="return printwindow1('1')">&nbsp;<?php echo $lang['Print_Button_Label']; ?></a> 
			 <a class="btn btn-primary fa fa-sign-out"  name="LogoutButton" id="LogoutButton" onclick="downloadPDF();">&nbsp;<?php echo $lang['Download_Button']; ?></a> 
			</td>
		</tr>
		
		<td colspan="7" class="text-center" style="padding: 10px;font-size: 13px;" >
			<?php echo $lang['NIC_TN_DESC']; ?> 
		</td>
		</tr>

	</tbody>
</table>
</div>

			<div id="footer" role="contentinfo">
			<?php //include("footer.php"); ?>
			</div>
	 
</div>
</div>

</div>
</div>
			<?php
		//}
	
  	} else {
		
//}
?>
<div class="contentMainDiv">
	<div class="contentDiv">
	<table class="ack_viewTbl" border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
			
		<tr>
			<td colspan="2" class="heading">		
			<font color='#FFFFFF'><?PHP echo " "; ?></font>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">		
			&nbsp;
			</td>
		</tr>	
		<tr>
			<td colspan="2">		
			&nbsp;
			</td>
		</tr>		
		<tr>
			<td class="heading" colspan="2" style="background-color: #ff0000;">
			<font color='#FFFFFF'>
			<center><b><?php echo $lang['Alert_Already_Saved_Label']; ?></b></center></font>
			</td>
		</tr>
		<tr>
			<td colspan="2">		
			&nbsp;
			</td>
		</tr>	
		<tr>
			<td colspan="2">		
			&nbsp;
			</td>
		</tr>	
	<tr>
			<td colspan="2" class="heading">		
			<font color='#FFFFFF'><?PHP echo " "; ?></font>
			</td>
		</tr>
		
	</tbody>
	</table>
	</div>
</div>
 <?php } ?>
 </form>
