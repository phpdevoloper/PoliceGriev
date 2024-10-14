<?php
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("Pagination.php");
//include("newSMS.php");
include('sms_airtel_code.php');

	$xml = new SimpleXMLElement($_POST['xml']);
	$formptoken = $xml->form_tocken;
	$source = $xml->source;
	$sub_source = $xml->sub_source;
	$source_remarks = $xml->source_remarks;
	$griev_code = $xml->griev_code; 
	$griev_maincode = $xml->griev_maincode;
	$griev_subcode = $xml->griev_subcode;
	$dept = $xml->dept;
	$survey_no = $xml->survey_no;
	$sub_div_no = $xml->sub_div_no;
	$grievance = $xml->grievance;	
	$lang = $xml->lang;
	$can_id = $xml->canid;
	//$aadhar_id = $xml->aadharid;
	$pet_enginitial = $xml->pet_eng_initial;
	$pet_ename = $xml->pet_ename;
	$father_ename = $xml->father_ename;
	$pet_type = $xml->pet_type;
	$gender = $xml->gender;
	$dob = $xml->dob;
	$mobile_number = $xml->mobile_number;
	$phoneno = $xml->phone_no;
	$idtype = $xml->id_type;  
	$idno = $xml->id_no;
	$email_id = $xml->email;
	$user_id = $xml->user_id;
	$comm_doorno = $xml->comm_doorno;
	$comm_flatno = $xml->comm_flat_no;
	$commstreet = $xml->comm_street;
	$commarea = $xml->comm_area;
	$comm_dist = $xml->comm_dist;
	$comm_taluk = $xml->comm_taluk;
	$comm_rev_village = $xml->comm_rev_village;
	$comm_pincode = $xml->comm_pincode;
	//////////// For afer give cain id ////////////
	$comm_dist_code_hid = $xml->comm_dist_code_hid;
	$comm_taluk_code_hid = $xml->comm_taluk_code_hid;
	$comm_village_code_hid = $xml->comm_village_code_hid;
	$gender_code_hid = $xml->gender_code_hid;
	$idtype_code_hid = $xml->idtype_code_hid;  
  	$user_id = $xml->user_id;
	$off_level_id = $xml->off_level_id;
   	$disposing_officer = $xml->disposing_officer;
   	$supervisory_officer = $xml->supervisory_officer;
	$concerned_officer = $xml->concerned_officer;
	$isdeo = $xml->isdeo;
	$user_dept_id = $xml->user_dept_id;
	$user_off_level_id = $xml->user_off_level_id;
	$off_level_pattern_id = $xml->off_level_pattern_id;
	$user_off_loc_name = $xml->user_off_loc_name;
	$user_off_loc_name = $xml->user_off_loc_name;
	$instructions = $xml->instructions;
	$pet_community = $xml->pet_community;
	$petitioner_category = $xml->petitioner_category;
	$griev_subtype_remarks = $xml->griev_subtype_remarks;
	$pet_process = $xml->pet_process;				  
	$supervisory_present = $xml->supervisory_officer_present;				  
	$old_pet_no = $xml->old_pet_no;				  
	
	$pattern_id = $xml->pattern_id;				  
	$office_level = $xml->office_level;				  
	$office_loc_id = $xml->office_loc_id;				  
	$pet_off_id = $xml->pet_off_id;				  
	$ext_no = $xml->ext_no;
	$ext_year = $xml->ext_year;
	$ext_ps_id = $xml->ext_ps_id;
	$pet_ext_link = $xml->pet_ext_link;	
	$pet_ext_dist = $xml->pet_ext_dist;	
	$userId = $xml->userId;						
				 
 
	/*if($pet_ext_link!=''){
	$fi_cs=$ext_ps_id."/".str_pad($ext_no, 8, '0', STR_PAD_LEFT)."/".$ext_year;
	}	*/		 
		

	$off_level=explode('-',$office_level);
	$off_level_id=$off_level[0];
	$off_level_dept_id=$off_level[1];
    //echo "============".$off_level_dept_id;

	if($comm_dist!="")
	 	$comm_district_code=$comm_dist;
    else
	 	$comm_district_code=$comm_dist_code_hid;
	 	$comm_district_code='null';
	 
	
	if($comm_taluk!=""){
	 	$comm_taluk_code=$comm_taluk;
	}
    else{
		 /* $talukid_sql = "SELECT taluk_id,taluk_name,taluk_tname FROM mst_p_taluk where taluk_code='$comm_taluk_code_hid' and district_code='$comm_dist_code_hid'";
		 $talukid_rs=$db->query($talukid_sql);
		 $talukid_row = $talukid_rs->fetch(PDO::FETCH_BOTH);
		 $comm_taluk_code= $talukid_row[0]; */  
		 $comm_taluk_code= 'null';  
	 }
	
	 
	if($comm_rev_village!=""){
		$comm_village_code=$comm_rev_village;
	}
	else{
		/* $villageid_sql = "SELECT rev_village_id,rev_village_name,rev_village_tname FROM mst_p_rev_village where rev_village_code='$comm_village_code_hid' and taluk_code='$comm_taluk_code_hid' and district_code='$comm_dist_code_hid'";
		$villageid_rs=$db->query($villageid_sql);
		$villageid_row = $villageid_rs->fetch(PDO::FETCH_BOTH);
		$comm_village_code= $villageid_row[0]; */
		$comm_village_code= 'null';
	}
	//echo "#####################".$pet_type;
	if ($pet_type != '') {
		$ptype_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type where pet_type_id=".$pet_type."";
		$ptype_rs=$db->query($ptype_sql);
		$ptype_row=$ptype_rs->fetch(PDO::FETCH_BOTH);
		$pet_type_name=$ptype_row['pet_type_name'];
		$pet_type_tname=$ptype_row['pet_type_tname'];
	}
	//echo ">>>>>>>>>>>>>>>>>>>>>";
	if ($pet_type_name != '') {
		if ($lang=='E') {
			if (strpos($pet_type_name, '(') > 0) {
				$pet_type_name = substr($pet_type_name,  strpos($pet_type_name, '('), strpos($pet_type_name, ')'));
			} else {
				$pet_type_name = '('.$pet_type_name.')';
			}
		} else {
			if (strpos($pet_type_tname, '(') > 0) {
				$pet_type_name = substr($pet_type_tname,  strpos($pet_type_tname, '('), strpos($pet_type_tname, ')'));
			} else {
				$pet_type_name = '('.$pet_type_tname.')';
			}
		}
	}
			

	
	if($gender!="")
		$gender_code=$gender;
	else
		$gender_code=$gender_code_hid;
	
	if($idtype!="")
		$idtypecode=$idtype;
	else
		$idtypecode=$idtype_code_hid; 
		

	///////////////////////////////////////////////
	$gre_doorno = $xml->gre_doorno;
	$gre_flatno = $xml->gre_flat_no;
	$grestreet = $xml->gre_street;
	$grearea = $xml->gre_area;
	$gre_dist = $xml->gre_dist;
	$gretaluk = $xml->gre_taluk;
	$gre_revvillage = $xml->gre_rev_village;
	$greblock = $xml->gre_block;
	$gre_tpvillage = $xml->gre_tp_village;
	$gre_urbanbody = $xml->gre_urban_body;
	
	$gre_division = $xml->gre_division;
	$gre_subdivision = $xml->gre_subdivision;
	$gre_circle = $xml->gre_circle;
	
	$cra_gre_taluk = $xml->cra_gre_taluk;
	$cra_gre_rev_village = $xml->cra_gre_rev_village;
	
	$gre_pincode = $xml->gre_pincode;
	
	$office_loc_id=($office_loc_id != '')?$office_loc_id:$pet_off_id;
	
	if ($off_level_id == 7) {
		$gre_state = $office_loc_id;
	} else if ($off_level_id == 9) {
		$gre_zone = $office_loc_id;
	} else if ($off_level_id == 11) {
		$gre_range = $office_loc_id;
	} else if ($off_level_id == 13) {
		$gre_dist = $office_loc_id;
	} else if ($off_level_id == 42) {
		$gre_division = $office_loc_id;
	} else if ($off_level_id == 44) {
		$gre_subdivision = $office_loc_id;
	} else if ($off_level_id == 46) {
		$gre_circle = $office_loc_id;
	}
	
    if($pet_enginitial=="")
		$pet_eng_initial='NULL';
	else
		$pet_eng_initial="'$pet_enginitial'";
	  
	if($can_id=="")
		$canid='NULL';
	else
		$canid="'$can_id'";
		
	if($phoneno=="")
		$phone_no='NULL';
	else
		$phone_no="'$phoneno'";
	
	if($idtypecode=="")
		$idtype_code='NULL';
	else
		$idtype_code="$idtypecode";
	
	if($idno=="")
		$id_no='NULL';
	else
		$id_no="'$idno'";
	
	if($email_id=="")
		$email='NULL';
	else
		$email="'$email_id'";
		
	if($comm_flatno=="")
		$comm_flat_no='NULL';
	else
		$comm_flat_no="'$comm_flatno'";
	
	if($commstreet=="")
		$comm_street='NULL';
	else
		$comm_street="'$commstreet'";
	
	if($commarea=="")
		$comm_area='NULL';
	else
		$comm_area="'$commarea'";
	
	if($gre_flatno=="")
		$gre_flat_no='NULL';
	else
		$gre_flat_no="'$gre_flatno'";
	 
	if($grearea=="")
		$gre_area='NULL';
	else
		$gre_area="'$grearea'";
	

	//$gre_dist = 'NULL';
	if($gretaluk=="" || $gretaluk==0)
		$gre_taluk='NULL';
	else
		$gre_taluk="$gretaluk";
	
	if($gre_revvillage=="")
		$griev_rev_village='NULL';
	else
		$griev_rev_village="$gre_revvillage";

	if ($user_off_level_id==1 && $off_level_pattern_id==1) {
		if($cra_gre_taluk=="" || $cra_gre_taluk==0)
			$gre_taluk='NULL';
		else
			$gre_taluk="$cra_gre_taluk";
		
		if($cra_gre_rev_village=="" || $cra_gre_rev_village==0)
			$griev_rev_village='NULL';
		else
			$griev_rev_village="$cra_gre_rev_village";

	}
	
	if($greblock=="" || $greblock==0)
		$gre_block='NULL';
	else
		$gre_block="'$greblock'";
	
	if($gre_tpvillage=="")
		$gre_tp_village='NULL';
	else
		$gre_tp_village="$gre_tpvillage";
	
	if($gre_urbanbody=="")
		$gre_urban_body='NULL';
	else
		$gre_urban_body="$gre_urbanbody";
		
	if($mobile_number=="")
		$mobile_no='NULL';
	else
		$mobile_no="'$mobile_number'";
	 

	$date= Date("d/m/Y");
	
/**********************  FOR GET GRIEVANCE DISTRICT, TALUK, VILLAGE,GRIEVANCE  NAMES ******************************************/  
	
	$gre_sub_sql = "select griev_subtype_name,griev_subtype_tname,b.griev_type_name,b.griev_type_tname from lkp_griev_subtype a inner join lkp_griev_type b on b.griev_type_id = a.griev_type_id where a.griev_type_id='$griev_maincode' and griev_subtype_id='$griev_subcode'";
 	$gre_sub_rs=$db->query($gre_sub_sql);
	$gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH);
 	$griev_sub_name=$gre_sub_row[0];
	
	if ($lang=='E'){
		$griev_sub_name=$gre_sub_row[0]; 
		$griev_main_name=$gre_sub_row[2]; 
	}else{
		$griev_sub_name=$gre_sub_row[1];
		$griev_main_name=$gre_sub_row[3];
	}
	
	$dept_sql = "select dept_name,dept_tname from usr_dept where dept_id=1";
	$dept_rs = $db->query($dept_sql);
	$dept_row = $dept_rs->fetch(PDO::FETCH_BOTH); 
	if ($lang=='E')
		$dept_name = $dept_row[0];
	else
		$dept_name = $dept_row[1];
	
 
/**********************  END ******************************************/

/**********************  FOR GET COMMUNICATION ADDRESS DISTRICT, TALUK, VILLAGE,GRIEVANCE  NAMES ******************************************/
if($comm_district_code!='null'){
	$dist_sql = "SELECT district_name,district_tname FROM mst_p_district where district_id='$comm_district_code'";
	$dist_rs=$db->query($dist_sql);
	$dist_row = $dist_rs->fetch(PDO::FETCH_BOTH);
	$comm_dist_name=ucfirst(strtolower($dist_row[0]));  
}
if($comm_taluk_code!='null'){
	$taluk_sql = "SELECT taluk_name,taluk_tname FROM mst_p_taluk where taluk_id='$comm_taluk_code'";
	$taluk_rs=$db->query($taluk_sql);
	$taluk_row = $taluk_rs->fetch(PDO::FETCH_BOTH);
	$comm_taluk_name=ucfirst(strtolower($taluk_row[0]));  
}
if($comm_village_code!='null'){
	$village_sql = "SELECT rev_village_name,rev_village_tname FROM mst_p_rev_village 
	where rev_village_id='$comm_village_code'";
	$village_rs=$db->query($village_sql);
	$village_row = $village_rs->fetch(PDO::FETCH_BOTH);
	$comm_village_name=ucfirst(strtolower($village_row[0])); 
}	
if($comm_district_code!='null'){	
	$country_sql="SELECT b.state_name,c.country_name FROM mst_p_district a
	left join mst_p_state b on b.state_id=a.state_id 
	left join mst_p_country c on c.country_id=b.country_id
	where a.district_id=".$comm_district_code."";
	$country_rs=$db->query($country_sql);
	$country_row = $country_rs->fetch(PDO::FETCH_BOTH);
	$comm_state_name=ucfirst(strtolower($country_row[0]));
	$comm_country_name=ucfirst(strtolower($country_row[1]));
}	  
	$src_sql = "SELECT source_name,source_tname FROM lkp_pet_source where source_id='$source'";
	$src_rs=$db->query($src_sql);
	$src_row = $src_rs->fetch(PDO::FETCH_BOTH);

	if ($lang=='E')
		$source_name= $src_row[0];
	else
		$source_name = $src_row[1];	
		
	
	
	if($sub_source!=""){
	$subsrc_sql = "SELECT subsource_name,subsource_tname FROM lkp_pet_subsource where subsource_id='$sub_source'";
	$subsrc_rs=$db->query($subsrc_sql);
	$subsrc_row = $subsrc_rs->fetch(PDO::FETCH_BOTH);
	$subsource_name=strtoupper($subsrc_row[0]);
	}
	if($gender_code!=""){ 	
	$gen_sql = "SELECT gender_name,gender_tname FROM lkp_gender where gender_id='$gender_code'";
	$gen_rs=$db->query($gen_sql);
	$gen_row = $gen_rs->fetch(PDO::FETCH_BOTH);
	$gender_nm=strtoupper($gen_row[0]); 
	}
	if($idtype_code!=""){
	$sql = "SELECT idtype_name,idtype_tname FROM lkp_id_type where idtype_id=$idtype_code";
	$idtype_rs=$db->query($sql);
	$idtype_row = $idtype_rs->fetch(PDO::FETCH_BOTH);
	$idtype_nm= $idtype_row[0];
	}
	
	if ($lang=='E') 
	{
		$comm_dist_name=$dist_row[0];
		$comm_taluk_name=$taluk_row[0];	
		$comm_village_name=$village_row[0]; 
		$source_name=strtoupper($src_row[0]);
		$subsource_name=strtoupper($subsrc_row[0]);
	}
	else
	{
		$comm_dist_name=$dist_row[1];
		$comm_taluk_name=$taluk_row[1];
		$comm_village_name=$village_row[1]; 
		$source_name=$src_row[1];
		$subsource_name=$subsrc_row[1];
	}	
	
	$sup_off = "";
	$conc_off = "";
	if ($supervisory_officer!="") {
		$sup_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$supervisory_officer."";
		$sup_rs = $db->query($sup_sql);
	$sup_row = $sup_rs->fetch(PDO::FETCH_BOTH);
	
	if ($lang=='E') 
		$sup_off = $sup_row [0]." - ".$sup_row [1];
	else
		$sup_off = $sup_row [2]." - ".$sup_row [3];	
	
	}
	/*else if ($concerned_officer!="") {
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$concerned_officer."";
	} else{ //supervisory_officer
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$user_id."";		
	}*/
	
	/**********************  END ******************************************/	
	if ($concerned_officer!="") {
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$concerned_officer."";
	
	$con_rs = $db->query($conc_sql);
	$conc_row = $con_rs->fetch(PDO::FETCH_BOTH);
	
	if ($lang=='E') 
		$conc_off = $conc_row [0]." - ".$conc_row [1];
	else
		$conc_off = $conc_row [2]." - ".$conc_row [3];	
  }
	if ($subsource_name != '')   {
		$source_detail = $source_name." - ".$subsource_name;
	} else {
		$source_detail = $source_name;
	}

		$query = "select label_name,label_tname from apps_labels where menu_item_id=64 order by ordering";
		$result = $db->query($query);
		while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
			if($lang == 'E'){
				$label_name[] = $rowArr['label_name'];	
			}else{
				$label_name[] = $rowArr['label_tname'];
			}
		}
		
	$community_category_name='';
	if ($pet_community != '') {
		$sql="SELECT pet_community_name, pet_community_tname FROM lkp_pet_community where pet_community_id=".$pet_community."";
		$rs = $db->query($sql);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		if ($lang=='E') {
			$pet_community_name = $label_name[39].' : '.$row [0];
		} else {
			$pet_community_name = $label_name[39].' : '.$row [0];	
		}
		
	} else {
		$pet_community_name = $label_name[39].' : ---';
	}
	$community_category_name .= $pet_community_name;	 
	if ($petitioner_category != '') {
		$sql="SELECT  petitioner_category_name, petitioner_category_tname FROM lkp_petitioner_category where petitioner_category_id=".$petitioner_category."";
		$rs = $db->query($sql);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		if ($lang=='E') {
			$petitioner_category_name = $label_name[40].' : '.$row [0];
		} else {
			$petitioner_category_name = $label_name[40].' : '.$row [1];
		}

		
	}
	if ($community_category_name != '') {
		$community_category_name .= ' / '. $petitioner_category_name;
	} else {
		$community_category_name .=  $petitioner_category_name;
	}

 /**************** FOR PETITION NUMBER GENERATION *******************/
 	$petiton_no=null;
	
/**********************   END  ******************************************/
	  
$gredoorno=$gre_doorno;
$greflatno=$gre_flat_no; 
$gre_street=$grestreet;
if($_SERVER['REQUEST_METHOD']=='POST')
	{
 	$pet_action_code='';
	if($concerned_officer==""){
		$petition_rem= "Temporary Reply";
		$concerned_officer='null';
		$pet_action_code='T';
	}
	else{
		$petition_rem= "forward";
		$pet_action_code='F';
	}
	
	if ($pet_process == 'F') {
		$pet_action_code='F';
	} else if ($pet_process == 'D') {
		$pet_action_code='D';
	} else {
		$pet_action_code='T';
	}					   
	$ip=$_SERVER['REMOTE_ADDR'];
	//$ip=$ip_address;
    
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
//Here regex didn't work because you had unescaped / delimiter. The regex that would validate date in format YYYY-MM-DD

//$dateofbirth="%'+and+'b%'"; //for test wrongly

if (!preg_match($date_regex, $dateofbirth)) {
   // echo '<br>Your hire date entry does not match the YYYY-MM-DD required format.<br>';
   		$date_of_birth = 'NULL';
} else {
    //echo '<br>Your date is set correctly<br>'; 
	 	$date_of_birth = "'$dateofbirth'";     
}
 
	 
	
/* Checking file upload MIME type before Insert for secrity reason */
	$document_counts = $xml->document_counts;
	 
 
	$t=0;
	for($t=0;$t<$document_counts;$t++)
	{
	$document_types = $xml->document_types;
	$filetype = explode(',',$document_types);
	
	$filetypes = $filetype[$t];
	}
	$file_type=array("application/pdf","image/jpeg","application/download");
	//print_r($filetypes);
	
	$document_names = $xml->document_names;

 if(empty($filetypes))
 { 	
		$disposing_officer = ($disposing_officer=="") ?"null":$disposing_officer;
		//$off_level_dept_id = null;   
		$sql = "SELECT fn_pet_master_action_insert(null, $pet_eng_initial, '$pet_ename', ".($father_ename==""?"null":"'$father_ename'").", ".($gender_code==""?"null":"'$gender_code'").", $date_of_birth, $idtype_code, $id_no, $source, ".($griev_maincode==""?"null":"$griev_maincode").", ".($griev_subcode==""?"null":"$griev_subcode").", '$grievance', $canid, '$comm_doorno', $comm_flat_no, $comm_street, $comm_area, $comm_district_code, $comm_taluk_code, $comm_village_code, '$comm_pincode', $email, $phone_no, $mobile_no, '$gredoorno', $greflatno,  ".($gre_street==""?"null":"'$gre_street'").", $gre_area, ".($gre_dist==""?"null":"'$gre_dist'").", $gre_taluk, $griev_rev_village, $gre_block, $gre_tp_village, $gre_urban_body, '$gre_pincode', null, null, ".$user_id.", '".$ip."', null, null, null,".($sub_source==""?"null":"$sub_source").",".($source_remarks==""?"null":"'$source_remarks'").",NULL,NULL,1,".($gre_division==""?"null":"'$gre_division'").",".($gre_subdivision==""?"null":"'$gre_subdivision'").",".($gre_circle==""?"null":"'$gre_circle'").",".($concerned_officer==""?"null":"$concerned_officer").",'$aadhar_id',".($pet_type==""?"null":"$pet_type").",$disposing_officer,".($instructions==""?"null":"'$instructions'")." ,".($pet_community==""?"null":"'$pet_community'")." ,".($petitioner_category==""?"null":"'$petitioner_category'").",".($griev_subtype_remarks==""?"null":"'$griev_subtype_remarks'").",'$pet_process',".($gre_zone==""?"null":"'$gre_zone'").",".($gre_range==""?"null":"'$gre_range'").",".($off_level_dept_id==""?"null":"'$off_level_dept_id'").",".($supervisory_officer==""?"null":"$supervisory_officer").",".($gre_state==""?"null":"'$gre_state'").",'$supervisory_present')";

	$result=$db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$petition_id=-1;		
	foreach($rowarray as $row){
		$petition_id=$row[0];	 
	}							   
// != -1 is to be handled	
	if ($petition_id != -1) {

	$sql ="UPDATE pet_master set org_petition_no='".$old_pet_no."' where petition_id=".$petition_id.""; 
		$result=$db->query($sql);
	
	
	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$petition_id;
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0];
	
	if ($pet_action_code == 'T') {
		$pet_act_sql = "select pet_action_id from pet_action where  petition_id=".$petition_id;
		$pet_act_rs=$db->query($pet_act_sql);
		$pet_act_row = $pet_act_rs->fetch(PDO::FETCH_BOTH);
		$pet_act_id= $pet_act_row[0];
	}
	$petno_sql1 = "SELECT petition_no,org_petition_no FROM pet_master where petition_id=".$petition_id;
	$petno_rs1=$db->query($petno_sql1);
	$petno_row1 = $petno_rs1->fetch(PDO::FETCH_BOTH);
	if($petno_row1[1]!=''){
	$petition_no1= $petno_row1[1];
	}else{
	$petition_no1= $petno_row1[0];
	}
	if($old_pet_no==''){
		$sql ="UPDATE pet_master set org_petition_no='".$petition_no1."' where petition_id=".$petition_id.""; 
		$result=$db->query($sql);
	}
	
	if($pet_ext_link!=''){	
	//$current_date = date('Y-m-d h:i:s'); 
	//$sql12="INSERT INTO pet_master_ext_link(petition_id, pet_ext_link_id, pet_ext_link_no, lnk_entby, lnk_entdt, ent_ip_address) VALUES (".$petition_id.",".$pet_ext_link.",'". $fi_cs."', ".$userId.", '". $current_date."', '".$ip."')";	
	$lnk_sql="INSERT INTO public.pet_master_ext_link(
	petition_id, pet_ext_link_id, district_id, circle_id, pet_ext_link_no, fir_csr_year, lnk_entby, lnk_entdt, ent_ip_address)
	VALUES (".$petition_id.",".$pet_ext_link.",".$pet_ext_dist.",".$ext_ps_id.",'".$ext_no."','".$ext_year."',".$userId.", current_timestamp, '".$ip."');";	
	$p_rs=$db->query($lnk_sql);
	}	
	$mobile_no = str_replace("'", "", $mobile_no);
	if ($mobile_no!="" && ((int) substr($mobile_no, 0, 1) >=6 && (int) substr($mobile_no, 0, 1) <=9)) {
	if($lang == 'E')
	{
	  //$strContent = "Your Petition No  ".$petition_no." is received. URL to check the status: http://locahost/police";
	  $strContent = "Your Petition No ".$petition_no." is received. URL to check the status: https://locahost/police - Tamil Nadu e-Governance Agency.";
	  $ucode='0';
	  $ct_id = '1007534064096719952';
	}
	else if($lang == 'T')
	{
	  //$strContent = "தங்களுடைய மனு எண்  ".$petition_no." பெறப்பட்டது. தங்கள் மனுவின் நிலையை "." http://locahost/police"." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
	  $strContent = "தங்களுடைய மனு எண்".$petition_no." பெறப்பட்டது. தங்கள் மனுவின் நிலையை https://locahost/police என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம் - தமிழ்நாடு மின்ஆளுமை முகமை.";
	  $ucode='2';
	  $ct_id = '1007806599718876169';
	}
	else 
	{
	  //$strContent = "Your Petition No  ".$petition_no." is received. URL to check the status: http://locahost/police";
	  $strContent = "Your Petition No ".$petition_no." is received. URL to check the status: https://locahost/police - Tamil Nadu e-Governance Agency.";
	  $ucode='0';
	  $ct_id = '1007534064096719952';
	}
	
	//$strStatus = SMS($mobile_no,$strContent,$ucode,$ct_id);
	$strContent = '';
	}
	}
	
	
}
 else {
if(in_array($filetypes,$file_type,true))
{
	$disposing_officer = ($disposing_officer=="" ) ?"null":$disposing_officer;
	$instructions = ($instructions=="") ? null:$instructions;
	//$off_level_dept_id = null;  

	/* $sql = "SELECT fn_pet_master_action_insert(null, $pet_eng_initial, '$pet_ename', '$father_ename', $gender_code, $date_of_birth, $idtype_code, $id_no, $source, ".($griev_maincode==""?"null":"$griev_maincode").", ".($griev_subcode==""?"null":"$griev_subcode").", '$grievance', $canid, '$comm_doorno', $comm_flat_no, $comm_street, $comm_area, $comm_district_code, $comm_taluk_code, $comm_village_code, '$comm_pincode', $email, $phone_no, $mobile_no, '$gredoorno', $greflatno,  ".($gre_street==""?"null":"'$gre_street'").", $gre_area, $gre_dist, $gre_taluk, $griev_rev_village, $gre_block, $gre_tp_village, $gre_urban_body, '$gre_pincode', null, null, ".$user_id.", '".$ip."', null, null, null,".($sub_source==""?"null":"$sub_source").",".($source_remarks==""?"null":"'$source_remarks'").",".($survey_no==""?"null":"'$survey_no'").",".($sub_div_no==""?"null":"'$sub_div_no'").",1,".($gre_division==""?"null":"'$gre_division'").",".($gre_subdivision==""?"null":"'$gre_subdivision'").",".($gre_circle==""?"null":"'$gre_circle'").",$concerned_officer,'$aadhar_id',$pet_type,$disposing_officer,".($instructions==""?"null":"'$instructions'")." ,".($pet_community==""?"null":"'$pet_community'")." ,".($petitioner_category==""?"null":"'$petitioner_category'").",".($griev_subtype_remarks==""?"null":"'$griev_subtype_remarks'").",'$pet_process',".($gre_zone==""?"null":"'$gre_zone'").",".($gre_range==""?"null":"'$gre_range'").",$off_level_dept_id,".($gre_state==""?"null":"'$gre_state'").")"; */
	
	$sql = "SELECT fn_pet_master_action_insert(null, $pet_eng_initial, '$pet_ename', ".($father_ename==""?"null":"'$father_ename'").", ".($gender_code==""?"null":"'$gender_code'").", $date_of_birth, $idtype_code, $id_no, $source, ".($griev_maincode==""?"null":"$griev_maincode").", ".($griev_subcode==""?"null":"$griev_subcode").", '$grievance', $canid, '$comm_doorno', $comm_flat_no, $comm_street, $comm_area, $comm_district_code, $comm_taluk_code, $comm_village_code, '$comm_pincode', $email, $phone_no, $mobile_no, '$gredoorno', $greflatno,  ".($gre_street==""?"null":"'$gre_street'").", $gre_area, ".($gre_dist==""?"null":"'$gre_dist'").", $gre_taluk, $griev_rev_village, $gre_block, $gre_tp_village, $gre_urban_body, '$gre_pincode', null, null, ".$user_id.", '".$ip."', null, null, null,".($sub_source==""?"null":"$sub_source").",".($source_remarks==""?"null":"'$source_remarks'").",NULL,NULL,1,".($gre_division==""?"null":"'$gre_division'").",".($gre_subdivision==""?"null":"'$gre_subdivision'").",".($gre_circle==""?"null":"'$gre_circle'").",".($concerned_officer==""?"null":"$concerned_officer").",'$aadhar_id',".($pet_type==""?"null":"'$pet_type'").",$disposing_officer,".($instructions==""?"null":"'$instructions'")." ,".($pet_community==""?"null":"'$pet_community'")." ,".($petitioner_category==""?"null":"'$petitioner_category'").",".($griev_subtype_remarks==""?"null":"'$griev_subtype_remarks'").",'$pet_process',".($gre_zone==""?"null":"'$gre_zone'").",".($gre_range==""?"null":"'$gre_range'").",".($off_level_dept_id==""?"null":"'$off_level_dept_id'").",".($supervisory_officer==""?"null":"$supervisory_officer").",".($gre_state==""?"null":"'$gre_state'").",'$supervisory_present')";
	
   
	$result=$db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_BOTH);
	$petition_id=-1;		
	foreach($rowarray as $row){
		$petition_id=$row[0];	 
	}
// = -1 is to be handled
	if ($petition_id != -1) {
	
	$sql ="UPDATE pet_master set org_petition_no='".$old_pet_no."' where petition_id=".$petition_id.""; 
	$result=$db->query($sql);
	
	
	/* if($pet_ext_link!=''){	 
	$sql12="INSERT INTO public.pet_master_ext_link(
	petition_id, pet_ext_link_id, district_id, circle_id, pet_ext_link_no, fir_csr_year, lnk_entby, lnk_entdt, ent_ip_address)
	VALUES (".$petition_id.",".$pet_ext_link.",".$pet_ext_dist.",".$ext_ps_id.",'".$ext_no."','".$ext_year."',".$userId.", current_timestamp, '".$ip."');";	
	$p_rs=$db->query($sql12);
	}	 */
	
	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$petition_id;
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0]; 
	
	if ($pet_action_code == 'T') {
		$pet_act_sql = "select pet_action_id from pet_action where  petition_id=".$petition_id;
		$pet_act_rs=$db->query($pet_act_sql);
		$pet_act_row = $pet_act_rs->fetch(PDO::FETCH_BOTH);
		$pet_act_id= $pet_act_row[0];
	}
	$petno_sql1 = "SELECT petition_no,org_petition_no FROM pet_master where petition_id=".$petition_id;
	$petno_rs1=$db->query($petno_sql1);
	$petno_row1 = $petno_rs1->fetch(PDO::FETCH_BOTH);
	if($petno_row1[1]!=''){
	$petition_no1= $petno_row1[1];
	}else{
	$petition_no1= $petno_row1[0];
	}
	if($old_pet_no==''){
		$sql ="UPDATE pet_master set org_petition_no='".$petition_no1."' where petition_id=".$petition_id.""; 
		$result=$db->query($sql);
	}
	
	/* File Upload*/ 
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
		//	$data = file_get_contents($tmp_name);
		$content = base64_encode($data);
		//$content = pg_escape_bytea($data);
		fclose($f);
		$fp = fopen($filetmp_names, 'r');

// move to the 0th byte
		fseek($fp, 0);
		$data = fread($fp, 5);   // read 5 bytes from byte 0
		if(strcmp($data,"%PDF-")==0){
		  
		}else{?>
		<script>
		alert("The PDF File is  Corrupted."); 
		</script>	
<?php }
		fclose($fp);

		
if(strtolower($filetypes)=='application/pdf'){
	if(strcmp($data,"%PDF-")==0)
	{
	$sql ="INSERT INTO pet_master_doc (petition_id,doc_content,doc_name,doc_size,doc_type,doc_entby,ent_ip_address,doc_entdt)VALUES('".$petition_id."','".$content."','".$filenames."','".$filesizes."','".$filetypes."','".$user_id."','".$_SERVER['REMOTE_ADDR']."','".$current_date."')";
		$result=$db->query($sql);
	}
else
{?>
 
<script> alert("Invalid File Type"); </script>	
<?php }
	}else{
	$sql ="INSERT INTO pet_master_doc (petition_id,doc_content,doc_name,doc_size,doc_type,doc_entby,ent_ip_address,doc_entdt)VALUES('".$petition_id."','".$content."','".$filenames."','".$filesizes."','".$filetypes."','".$user_id."','".$_SERVER['REMOTE_ADDR']."','".$current_date."')";
		$result=$db->query($sql);
	}
	}
	
	$mobile_no = str_replace("'", "", $mobile_no);
	if ($mobile_no!="" && ((int) substr($mobile_no, 0, 1) >=6 && (int) substr($mobile_no, 0, 1) <=9)) {
		if($lang == 'E')
		{
		  $strContent = "Your Petition No  ".$petition_no." is received. URL to check the status: http://locahost/police";
		}
		else
		{
		  $strContent = "தங்களுடைய மனு எண்  ".$petition_no." பெறப்பட்டது. தங்கள் மனுவின் நிலையை "." http://locahost/police"." என்ற இணையதள முகவரியில் தெரிந்து கொள்ளலாம்.";
		}
		//$strStatus = SMS($mobile_no,$strContent);
		$strContent = '';
	}
 }
	}

}
 	
	if($petition_id==-1)
	{?>
		<script type="text/javascript">
			alert ("Duplicate Petition / Saving Error !!!");
			self.close();
        </script>
	<?php
	} 
	else 
	{
	?>
 
  <?php //include("pm_common_js_css.php"); ?>	
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
 	document.getElementById("div_head").style.display='none';
	document.getElementById("footertbl").style.display='none';
	document.getElementById("header").style.display='none';
	document.getElementById("btn_row").style.display='none';
	document.getElementById("myTopnav").style.display='none';
	if (val == '2') {
		document.getElementById("second").style.display='';
	}
	window.print();
	if (val == '2') {
		document.getElementById("second").style.display='none';
	}	
	document.getElementById("myTopnav").style.display='';
	document.getElementById("div_head").style.display='block';
	document.getElementById("footertbl").style.display='';
	document.getElementById("header").style.display='block';
	document.getElementById("btn_row").style.display='';

}
</script>
<?php
		$lang = $xml->lang;
		$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
                
		$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
		$result = $db->query($query);
		while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
			if($lang == 'E'){
				$label_name[] = $rowArr['label_name'];	
			}else{
				$label_name[] = $rowArr['label_tname'];
			}

		}
		if ($off_level_id == 1) {
			$acknow_label = $label_name[36];
		}  
		else if ($off_level_id == 2) {
			$acknow_label = $label_name[1]." - ". $griev_dist_name;		
		} else if ($off_level_id == 3) {
			$acknow_label = $label_name[35]." - ". $rdo_name;
		} else if ($off_level_id == 4){
			$acknow_label = $label_name[34]." - ". $griev_taluk_name;
		}
						   
		/* $sql="select off_level_id,off_level_dept_name,off_level_dept_tname 
						from usr_dept_off_level where dept_id=1 and off_level_id=".$user_off_level_id."";
		$result = $db->query($sql);
		while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
			if($_SESSION["lang"]=='T'){

				$ack_title = 'Petition Processing Portal (PPP) - '.$rowArr[off_level_dept_name].' - '.$label_name[0];
				$acknow_label = $rowArr[off_level_dept_name]." - ".$user_off_loc_name;
			}
		} */
        
		$ack_title = 'Tamil Nadu Police - Police Station Complaint Redressal System';
		$acknow_label = 'Acknowledgement';
				
		$url='https://locahost/police/ps/getPetitionStatusQR.php?pet_id='.$petition_id;

		include('phpqrcode/qrlib.php');
		$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

	    $PNG_WEB_DIR = 'temp/';
		
		if (!file_exists($PNG_TEMP_DIR))
		mkdir($PNG_TEMP_DIR);
	
	
		$filename = $PNG_TEMP_DIR.'test.png';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 10;
		$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  				 

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  />
<form name="pm_pet_process" id="pm_pet_process" action="pm_pet_processing.php" method="post">
<input type="hidden" name="pet_act_id" id="pet_act_id" value="<?PHP echo $pet_act_id;?>"/>

<style media="print">
 @page {
  size: auto;
  margin: 0;
       }
</style>

	      
<div class="contentMainDiv" style="margin-top:50px;">
	<div class="contentDiv">
	<table class="ack_viewTbl" border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
             
        <tr>
			<td colspan="2">
				<center><b><?PHP echo $source_detail; //Petition No. & Date?></b></center>
			</td>
		</tr>     
             <tr>
                <td colspan="2" class="heading" style="background-color:#BC7676">
                <img height="70" width="70" src="images/emblem-dark.png" id="prnt_img" align="left"/> 
				<?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" align="right" height="70" width="70" style="margin-right:30px" />'; ?>
                <center>
		<label style="font-weight:bold;font-size:16px;"><b><?PHP echo $ack_title;  ?></b></label> 
        <br><label style="font-weight:bold;font-size:12px;"><?PHP  echo $acknow_label; ?></label><br>
		<!--br><?PHP //echo $label_name[0]; //Acknowledgement ?><br--> 
<label style="font-size:13px;">
<?php echo ' To check the status of your petition: http://localhost/police; or scan the QR Code with a QR Reader in a smartphone; '?><br>


</label>
		</center>    
                </td>									

             </tr>              
			 <tr><?php ?></tr>
             <tr> </tr>
			
		<tr>
			<td><?PHP echo $label_name[3]; //Petition No. & Date?></td>
			<td colspan="2"><?php echo $petition_no.' & '.$label_name[37].' '.$date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19];//Mobile Number?> : <b><?php echo $mobile_number;?></b></td></td> 					
		</tr>
            
        <tr>
			<td><?PHP echo $label_name[28]; //Grievance Type ?></td>
			<td colspan="2"> <?php echo $griev_main_name;?> <?php echo ' & '.$griev_sub_name;?></td>
		</tr>          

		<tr>
			<td><?PHP echo $label_name[10].""; //Grievance/ Request ?><b></td><td colspan="2">
			<?php echo ($survey_no!="")? $grievance."; "."Survey No. ".$survey_no.";  Sub-division No. ".$sub_div_no."; " : $grievance;?></b></td>
		</tr>		   
		
		<tr>
			<td><?PHP echo  'Enquiry Filing Officer'; ?></td>
			<td colspan="2"><?PHP echo $sup_off; //e-Mail ?> </td>
		</tr>
		<?php
	if ($concerned_officer!="") {
		?>
		<tr>
			<td><?PHP echo  'Enquiry Officer'; ?></td>
			<td colspan="2"><?PHP echo $conc_off; //e-Mail ?> </td>
		</tr>
			<?php
	}?>
			
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
		 <tr><td colspan='2' height='100%'style='color:#FEEDED'><?php echo ' <br>';?></td></tr>
	<tr>
			<tr>
			<td ><?PHP echo $label_name[30]; //Applicant Name ?></td><td colspan="2">  <?php echo $pet_ename."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; //ucfirst(strtolower($pet_ename)); ?>
			<?PHP echo $label_name[13]." : "; //Father / Spouse Name ?><?php echo $father_ename //ucfirst(strtolower($father_ename)); ?></td>
			</tr>
			
			
			<!--tr>
			<td><?php echo  $label_name[38]//Petitioner Community and Category;?></td>
			<td><?PHP echo $community_category_name; ?></td>
			</tr-->

			
			
		<tr>
	
			
			<?php
		if($comm_doorno!="")
			$comm_doorno=$comm_doorno;
		if($comm_block_no!="")
			$comm_block_no=", ".$comm_block_no;
		if($commstreet!="")
			$commstreet=", ".$commstreet;
		if($commarea!="")
			$commarea=", ".$commarea;
		
		?>
			
			<td><?PHP echo $label_name[18].""; //Address
			//.$comm_village_name.$label_name[27].","$comm_taluk_name.$label_name[26].", ".$comm_dist_name.$label_name[25].", ".$comm_state_name.", ".$comm_country_name. ?></td>
			<td><?php echo $comm_doorno.$comm_block_no.$commstreet.$commarea.","." Pincode- ".$comm_pincode."."; ?>
			</td>
			</tr>
			<tr>
			<td colspan="3" style="text-align:center;"><b>This is a computer generated report. No sign is required.</b></td>			
			</tr>     
			<tr>
			<td colspan="3"><b><?PHP echo  $label_name[32]." ".$label_name[31] ; ?></b></td>			
			</tr>
			<tr>
            	<td colspan="3" class="btn" id="btn_row"> 
            		<input type="button" name="" id="dontprint1" style="width:150px;" value="<?PHP echo $label_name[22]." 1 Copy"; //Print ?>" class="button" onClick="return printwindow1('1')">
					
					<input type="button" name="" id="dontprint2" style="width:150px;" value="<?PHP echo $label_name[22]." 2 Copies"; //Print ?>" class="button" onClick="return printwindow1('2')">
					
            		<?PHP if($pet_action_code=='T'){ ?>
                    <input type="submit" name="" id="dontprint3" style="width:150px;" value="<?PHP echo $label_name[23]; //Petition Processing?>" class="button" style="width: 120px;">
                    <?PHP } ?>
					
					<input type="button" name="dontprint3" id="dontprint3" style="width:150px;" value="Back" class="button" onClick="self.close();">
					
            	</td>
			</tr>
			
			</tbody>
			</table>
			 

			<div id="footer" role="contentinfo">
			<?php //include("footer.php"); ?>
			</div>
	 
</div>
</div>



<div class="contentMainDiv" id="second" style="margin-top:500px;display:none;">
	<div class="contentDiv">
	
	
	

	<table class="ack_viewTbl" >
			<tbody>
             
			<tr>
			<td colspan="2" style="align:center;">
				<center><b><?PHP echo $source_detail; //Petition No. & Date?></b></center>
			</td>
		   </tr> 
		
			
             <tr>
                <td colspan="2" class="heading" style="background-color:#BC7676">
                <img height="70" width="70" src="images/emblem-dark.png" id="prnt_img" align="left"/> 
				<?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" align="right" height="70" width="70" style="margin-right:30px" />'; ?>
                <center>
		<label style="font-weight:bold;font-size:16px;"><b><?PHP echo $ack_title;  ?></b></label> 
        <br><label style="font-weight:bold;font-size:12px;"><?PHP  echo $acknow_label; ?></label><br>
		<!--br><?PHP //echo $label_name[0]; //Acknowledgement ?><br--> 
<label style="font-size:13px;">
<?php echo ' To check the status of your petition: http://locahost/police; or scan the QR Code with a QR Reader in a smartphone; '?><br>
<?php echo 'or send <b>'.$petition_no.'</b> as SMS to <b>155250</b> to know the status as reply SMS';?>
</label>
		</center>    
                </td>									
             </tr>           
			 
             <tr>
             
			<tr>
			<td><?PHP echo $label_name[3]; //Petition No. & Date?></td>
			<td colspan="2"><?php echo $petition_no.' & '.$label_name[37].' '.$date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[7];//Mobile Number?> : <b><?php echo $row['comm_mobile'];?></b></td> 					
		</tr>
            
        <tr>
			<td><?PHP echo $label_name[28]; //Grievance Type ?></td>
			<td colspan="2"> <?php echo $griev_name;?>& <?php echo $griev_sub_name;?></td>
		</tr>          

		<tr>
			<td colspan="3"><?PHP echo $label_name[10].": "; //Grievance/ Request ?><b>
			<?php echo ($survey_no!="")? $grievance."; "."Survey No. ".$survey_no.";  Sub-division No. ".$sub_div_no."; " : $grievance;?></b></td>
		</tr>		   
		
		<tr>
			<td><?PHP echo  'Enquiry Filing Officer'; ?></td>
			<td colspan="2"><?PHP echo $sup_off; //e-Mail ?> </td>
		</tr>
		<?php
	if ($concerned_officer!="") {
		?>
		<tr>
			<td><?PHP echo  'Enquiry Officer'; ?></td>
			<td colspan="2"><?PHP echo $conc_off; //e-Mail ?> </td>
		</tr>
			<?php
	}?>
			
			
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
			<td ><?PHP echo $label_name[30]; //Applicant Name ?></td><td colspan="2">  <?php echo $pet_ename //ucfirst(strtolower($pet_ename)); ?>
			<?PHP echo $label_name[13].":"; //Father / Spouse Name ?> : <?php echo $father_ename //ucfirst(strtolower($father_ename)); ?></td>
			</tr>
			

			<tr>
			<td><?php echo  $label_name[38]//Petitioner Community and Category;?></td>
			<td><?PHP echo $community_category_name; ?></td>
			</tr>


			
			<tr>
			<!--td><?PHP //echo $label_name[15]; //DOB ?> : <?php //echo $dob;?></td-->
			
			
			<?php
		if($comm_doorno!="")
			$comm_doorno=$comm_doorno;
		if($comm_block_no!="")
			$comm_block_no=", ".$comm_block_no;
		if($commstreet!="")
			$commstreet=", ".$commstreet;
		if($commarea!="")
			$commarea=", ".$commarea;
		?>
			
			<td><?PHP echo $label_name[18].": "; //Address ?></td>
			<td><?php echo $comm_doorno.$comm_block_no.$commstreet.$commarea.", Pincode - ".$comm_pincode."."; ?>&nbsp;
			</td>
			</tr>
			<tr>
			<td colspan="3" style="text-align:center;"><b>This is a computer generated report. No sign is required.</b></td>			
			</tr>
                 
			<tr>
			<td colspan="3"><b><?PHP echo  $label_name[32]." ".$label_name[31] ; ?></b></td>			
			</tr>
        			
			</tbody>
			</table>
			 

			<div id="footer" role="contentinfo">
			<?php //include("footer.php"); ?>
			</div>
	 
</div>
</div>
			<?php
		}
	
  	}
//}
?>
 
 </form>
 <script type = "text/javascript">
document.getElementById("menu").style.display='none';
	document.getElementById("usr_detail").style.display='none';	
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
	
document.onmousedown=disableclick;
status="This action is not allowed here";
function disableclick(e)
{
  if(e.button==2)
   {
	   
     alert(status);
     return false;  
   }
}

</script>
