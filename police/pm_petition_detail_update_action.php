<?php
error_reporting(0);
ob_start();
session_start();
include("db.php");
include("Pagination.php");
//include("newSMS.php");
include('sms_airtel_code.php');

	$xml = new SimpleXMLElement($_POST['xml']);//print_r($xml);
	$pet_id=$xml->pet_id;
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

	$off_level=explode('-',$office_level);
	$off_level_id=$off_level[0];
	$off_level_dept_id=$off_level[1];
   // echo "============".$off_level_dept_id;

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
	$ptype_sql = "SELECT pet_type_id, pet_type_name, pet_type_tname FROM lkp_pet_type where pet_type_id=".$pet_type."";
	$ptype_rs=$db->query($ptype_sql);
	$ptype_row=$ptype_rs->fetch(PDO::FETCH_BOTH);
	$pet_type_name=$ptype_row['pet_type_name'];
	$pet_type_tname=$ptype_row['pet_type_tname'];
	
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
	//echo ">>>>>>>>>>>*".$off_level_id."*>>>>>>>>>>>".$office_loc_id."*>>>>>>>>>>>";
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
	
	$gre_sub_sql = "select griev_subtype_name,griev_subtype_tname from lkp_griev_subtype where griev_type_id='$griev_maincode' and griev_subtype_id='$griev_subcode'";
 	$gre_sub_rs=$db->query($gre_sub_sql);
	$gre_sub_row = $gre_sub_rs->fetch(PDO::FETCH_BOTH);
 	$griev_sub_name=$gre_sub_row[0];
	
	if ($lang=='E')
		$griev_sub_name=$gre_sub_row[0]; 
	else
		$griev_sub_name=$gre_sub_row[1];
	
	
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
	 	
	$gre_sql = "select griev_type_name,griev_type_tname from lkp_griev_type where griev_type_id='$griev_maincode' ";
    $gre_rs=$db->query($gre_sql);
	$gre_row = $gre_rs->fetch(PDO::FETCH_BOTH);
 	$griev_name=$gre_row[0];  
	
	$gen_sql = "SELECT gender_name,gender_tname FROM lkp_gender where gender_id='$gender_code'";
	$gen_rs=$db->query($gen_sql);
	$gen_row = $gen_rs->fetch(PDO::FETCH_BOTH);
	$gender_nm=strtoupper($gen_row[0]); 
	
	$sql = "SELECT idtype_name,idtype_tname FROM lkp_id_type where idtype_id=$idtype_code";
	$idtype_rs=$db->query($sql);
	$idtype_row = $idtype_rs->fetch(PDO::FETCH_BOTH);
	$idtype_nm= $idtype_row[0];
	
	
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
	
	$conc_off = "";
	if ($concerned_officer!="") {
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$concerned_officer."";
	} else if ($supervisory_officer!="") {
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$supervisory_officer."";
	}
	else { //supervisory_officer
		$conc_sql= "select dept_desig_name,off_loc_name ,dept_desig_tname,off_loc_tname from vw_usr_dept_users_v where dept_user_id=".$user_id."";		
	}
	$con_rs = $db->query($conc_sql);
	$conc_row = $con_rs->fetch(PDO::FETCH_BOTH);
	
	if ($lang=='E') 
		$conc_off = $conc_row [0]." - ".$conc_row [1];
	else
		$conc_off = $conc_row [2]." - ".$conc_row [3];	
	
	/**********************  END ******************************************/	
  
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
	}else if ($pet_process == 'C') {
		$pet_action_code='C';
	}else if ($pet_process == 'I') {
		$pet_action_code='I';
	}else if ($pet_process == 'S') {
		$pet_action_code='S';
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
	
/* 	if ($source == 26) {
		$src_query="select dept_user_id,dept_desig_id, dept_id,off_level_id from vw_usr_dept_users_v_sup where off_hier[2]=".$gre_dist." and dept_coordinating and desig_coordinating and off_coordinating and pet_disposal";
		$rs = $db->query($src_query);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		$disposing_officer=$row [0];
	} else if ($source == 38) {
		$src_query="select dept_user_id,dept_desig_id, dept_id,off_level_id from vw_usr_dept_users_v_sup 
		where off_hier[3]= (select rdo_id from mst_p_taluk where taluk_id=".$gre_taluk.") and dept_desig_id=s_dept_desig_id and pet_act_ret=true and pet_disposal=true and off_level_id=3";
		$rs = $db->query($src_query);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		$disposing_officer=$row [0];
	} */

 if(empty($filetypes))
 { 

 	$sql="UPDATE pet_master set 
	source_id=".($source==""?"null":"$source")." ,
	griev_type_id=".($griev_maincode==""?"null":"$griev_maincode")." ,
	griev_subtype_id =".($griev_subcode==""?"null":"$griev_subcode").",
	pet_type_id=".$pet_type.",
	grievance='".$grievance."',	
	state_id=".($gre_state==null?"null":"'$gre_state'")." ,
	zone_id=".($gre_zone==null?"null":"'$gre_zone'")." ,
	range_id=".($gre_range==null?"null":"'$gre_range'")." ,
	griev_district_id=".($gre_dist==''?"null":"'$gre_dist'")." ,	
	griev_division_id=".($gre_division==''?"null":"'$gre_division'")." ,
	griev_subdivision_id=".($gre_subdivision==''?"null":"'$gre_subdivision'")." ,
	griev_circle_id =".($gre_circle==''?"null":"'$gre_circle'"). ",		
	comm_doorno='".$comm_doorno."', 
	comm_street='".$commstreet."',
	comm_area='".$commarea."', 
	comm_district_id=".$comm_district_code.", 
	comm_taluk_id=".$comm_taluk_code.", 
	comm_rev_village_id=".$comm_village_code.", 
	comm_pincode=".$comm_pincode.", 
	comm_mobile=".$mobile_no.",
	petitioner_initial='".$pet_enginitial."',
	petitioner_name='".$pet_ename."',
	father_husband_name='".$father_ename."',
	aadharid='".$aadharid."',
	gender_id=".$gender_code.",
	idtype_id=".$idtype_code.",org_petition_no='$old_pet_no',
	id_no=".$id_no.",
	off_level_dept_id=".($off_level_dept_id==''?"null":"'$off_level_dept_id'").",
    mod_ip_address='".$ip."' where petition_id =".$pet_id."";
	//select * from vw_pet_master where petition_no='2018/9005/17/456624/1203';
//print_r($pet_process.'-'.$sql);exit;
	//echo $sql;exit;
	$result=$db->query($sql);
	if($pet_action_code!=''){
	$pet_act_id=$pet_action_code;	
	}else{
		$pet_act_id='F';
	}
	//echo "111111111111111".$supervisory_officer."%%%%%%%%%%%%%%%%%".$concerned_officer;	
	
	$sql="select pet_action_id, action_type_code from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1"; 
	
	$countsql="select f_action_entby,f_to_whom,l_action_entby,l_to_whom,f_action_type_code ,l_action_type_code from pet_action_first_last where petition_id=".$pet_id.""; 
	$result=$db->query($countsql);
	$row=$result->fetch(PDO::FETCH_BOTH);
	
	$f_action_entby=$row['f_action_entby'];
	$f_to_whom=$row['f_to_whom'];
	$l_action_entby=$row['l_action_entby'];
	$l_to_whom=$row['l_to_whom'];
	$f_action_type_code=$row['f_action_type_code'];
	$l_action_type_code=$row['l_action_type_code'];
	
	$today = $page->currentTimeStamp();
	$action_entby= $_SESSION['USER_ID_PK'];
	$instructions = ($instructions == '') ? 'Forwarded (Updated Petition)' : 'Forwarded (Updated Petition) '.$instructions;
	$ip=$_SERVER['REMOTE_ADDR'];
	/*
	$disposing_officer = $xml->disposing_officer;
$supervisory_officer = $xml->supervisory_officer;
$concerned_officer = $xml->concerned_officer;
select * from pet_action where petition_id=20;

select * from pet_action_first_last where petition_id=20;
	*/
	
	/* 	$first_action="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (".$pet_id.",'".$pet_act_id."',". $disposing_officer.",'".$today."',".$supervisory_officer.",'".$ip."','".$instructions."')";
	$result=$db->query($first_action); */
	
		//echo "Temporary Reply".$supervisory_officer."=========".$concerned_officer;
		//exit;
		if($pet_act_id!='C'){
	if ($supervisory_officer !='' && $concerned_officer != '') {
		//echo "11111";exit;
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,$supervisory_officer,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			if($pet_act_id=='D'){
				$codn=", d_action_type_code='".$pet_act_id."'";
			}else{
				$codn='';
			}
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=".$supervisory_officer.$codn." WHERE petition_id=".$pet_id."";
			$result=$db->query($upsql);
			$pet_act_id='F';
			$second_array = array($pet_id, $pet_act_id, $supervisory_officer,$concerned_officer,$ip,$instructions);
			$query->execute($second_array);
		}
	} else if (($supervisory_officer =='' && $concerned_officer == '') ||($supervisory_officer =='' && $concerned_officer == null)) {
		/* echo "Temporary Reply";
		exit; */
		$pet_act_id='T';
		$supervisory_officer = null;
		$instructions = 'Temporary reply (Updated Petition)';
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,null,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
		}
	} else {
		/* echo "********************************************";exit; */
		//echo $supervisory_officer.'>>>'.$concerned_officer ;
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,$supervisory_officer,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=".$supervisory_officer." WHERE petition_id=".$pet_id."";
			$result=$db->query($upsql);
		}
		
	}
 }else{
	 //Action Taken
	 /* echo "Temporary Reply";
		exit; */
		$pet_act_id='T';
		$supervisory_officer = null;
		$instructions = 'Temporary reply (Updated Petition)';
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,null,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
 }else{
 /* $query = $db->prepare('update action_remarks=? where pet_action_id=?');	
		$array = array($instructions, $petition_id);
	if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
 } */
 $sql_c="select count(*) as cnt from pet_action where petition_id=".$pet_id;
 $result_c=$db->query($sql_c);
 while($row_c = $result_c->fetch(PDO::FETCH_BOTH))
	{
		$count_act=$row_c["cnt"];
	}
	if($count_act==1){
$sql="update pet_action SET action_remarks='".$instructions."' where petition_id=".$pet_id;
 $result=$db->query($sql);
	}
	$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
 }
 }
	/*
	if ($action_type_code == 'F' || $action_type_code == 'Q'|| $action_type_code == 'T') {		
		 $upsql="update pet_action set to_whom=".$concerned_officer.", action_type_code='F',
		 action_remarks='".$instructions."',action_ip_address='".$ip."' where pet_action_id=".$pet_action_id."";
	} else {
		$upsql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (".$pet_id.",'".$pet_act_id."',". $user_id.",'".$today."',".$concerned_officer.",'".$ip."','".$instructions."')";		
	}
	
	$result=$db->query($upsql);
	*/
	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$pet_id."";
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0];
	
 }
 else {
if(in_array($filetypes,$file_type,true))
{

 	$sql="UPDATE pet_master set 
	source_id=".($source==""?"null":"$source")." ,
	griev_type_id=".($griev_maincode==""?"null":"$griev_maincode")." ,
	griev_subtype_id =".($griev_subcode==""?"null":"$griev_subcode").",
	pet_type_id=".$pet_type.",
	grievance='".$grievance."',	
	state_id=".($gre_state==null?"null":"'$gre_state'")." ,
	zone_id=".($gre_zone==null?"null":"'$gre_zone'")." ,
	range_id=".($gre_range==null?"null":"'$gre_range'")." ,
	griev_district_id=".($gre_dist==''?"null":"'$gre_dist'")." ,	
	griev_division_id=".($gre_division==''?"null":"'$gre_division'")." ,
	griev_subdivision_id=".($gre_subdivision==''?"null":"'$gre_subdivision'")." ,
	griev_circle_id =".($gre_circle==''?"null":"'$gre_circle'"). ",		
	comm_doorno='".$comm_doorno."', 
	comm_street='".$commstreet."',
	comm_area='".$commarea."', 
	comm_district_id=".$comm_district_code.", 
	comm_taluk_id=".$comm_taluk_code.", 
	comm_rev_village_id=".$comm_village_code.", 
	comm_pincode=".$comm_pincode.", 
	comm_mobile='".$mobile_no."',org_petition_no='$old_pet_no'
	petitioner_initial='".$pet_enginitial."',
	petitioner_name='".$pet_ename."',
	father_husband_name='".$father_ename."',
	aadharid='".$aadharid."',
	gender_id=".$gender_code.",
	idtype_id=".$idtype_code.",
	id_no=".$id_no.",
	off_level_dept_id=".($off_level_dept_id==''?"null":"'$off_level_dept_id'").",
    mod_ip_address='".$ip."' where petition_id =".$pet_id."";
	//select * from vw_pet_master where petition_no='2018/9005/17/456624/1203';
//print_r($pet_process.'-'.$sql);exit;
	$result=$db->query($sql);
	if($pet_action_code!=''){
	$pet_act_id=$pet_action_code;	
	}else{
		$pet_act_id='F';
	}
	//echo "111111111111111".$supervisory_officer."%%%%%%%%%%%%%%%%%".$concerned_officer;	
	
	$sql="select pet_action_id, action_type_code from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1"; 
	
	$countsql="select f_action_entby,f_to_whom,l_action_entby,l_to_whom,f_action_type_code ,l_action_type_code from pet_action_first_last where petition_id=".$pet_id.""; 
	$result=$db->query($countsql);
	$row=$result->fetch(PDO::FETCH_BOTH);
	
	$f_action_entby=$row['f_action_entby'];
	$f_to_whom=$row['f_to_whom'];
	$l_action_entby=$row['l_action_entby'];
	$l_to_whom=$row['l_to_whom'];
	$f_action_type_code=$row['f_action_type_code'];
	$l_action_type_code=$row['l_action_type_code'];
	
	$today = $page->currentTimeStamp();
	$action_entby= $_SESSION['USER_ID_PK'];
	$instructions = ($instructions == '') ? 'Forwarded (Updated Petition)' : 'Forwarded (Updated Petition) '.$instructions;
	$ip=$_SERVER['REMOTE_ADDR'];
	/*
	$disposing_officer = $xml->disposing_officer;
$supervisory_officer = $xml->supervisory_officer;
$concerned_officer = $xml->concerned_officer;
select * from pet_action where petition_id=20;

select * from pet_action_first_last where petition_id=20;
	*/
	
	/* 	$first_action="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (".$pet_id.",'".$pet_act_id."',". $disposing_officer.",'".$today."',".$supervisory_officer.",'".$ip."','".$instructions."')";
	$result=$db->query($first_action); */
	
		//echo "Temporary Reply".$supervisory_officer."=========".$concerned_officer;
		//exit;
		if($pet_act_id!='C'){
	if ($supervisory_officer !='' && $concerned_officer != '') {
		//echo "11111";exit;
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,$supervisory_officer,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			if($pet_act_id=='D'){
				$codn=", d_action_type_code='".$pet_act_id."'";
			}else{
				$codn='';
			}
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=".$supervisory_officer.$codn." WHERE petition_id=".$pet_id."";
			$result=$db->query($upsql);
			$pet_act_id='F';
			$second_array = array($pet_id, $pet_act_id, $supervisory_officer,$concerned_officer,$ip,$instructions);
			$query->execute($second_array);
		}
	} else if (($supervisory_officer =='' && $concerned_officer == '') ||($supervisory_officer =='' && $concerned_officer == null)) {
		/* echo "Temporary Reply";
		exit; */
		$pet_act_id='T';
		$supervisory_officer = null;
		$instructions = 'Temporary reply (Updated Petition)';
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,null,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
		}
	} else {
		/* echo "********************************************";exit; */
		//echo $supervisory_officer.'>>>'.$concerned_officer ;
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,$supervisory_officer,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=".$supervisory_officer." WHERE petition_id=".$pet_id."";
			$result=$db->query($upsql);
		}
		
	}
 }else{
	 //Action Taken
	 /* echo "Temporary Reply";
		exit; */
		$pet_act_id='T';
		$supervisory_officer = null;
		$instructions = 'Temporary reply (Updated Petition)';
		$query = $db->prepare('INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (?, ?, ?,current_timestamp, ?, ?, ?)');	
		$array = array($pet_id, $pet_act_id, $disposing_officer,null,$ip,$instructions);
		if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
 
	/*
	if ($action_type_code == 'F' || $action_type_code == 'Q'|| $action_type_code == 'T') {		
		 $upsql="update pet_action set to_whom=".$concerned_officer.", action_type_code='F',
		 action_remarks='".$instructions."',action_ip_address='".$ip."' where pet_action_id=".$pet_action_id."";
	} else {
		$upsql="INSERT INTO pet_action(petition_id, action_type_code,  action_entby, action_entdt, to_whom,action_ip_address,action_remarks) VALUES (".$pet_id.",'".$pet_act_id."',". $user_id.",'".$today."',".$concerned_officer.",'".$ip."','".$instructions."')";		
	}
	
	$result=$db->query($upsql);
	*/
	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$pet_id."";
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0];
	
 }else{
 /* $query = $db->prepare('update action_remarks=? where pet_action_id=?');	
		$array = array($instructions, $petition_id);
	if($query->execute($array)>0){
			$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
			$upsql="UPDATE pet_action_first_last SET f_pet_action_id=".$pet_action_id.", f_action_type_code='".$pet_act_id."', f_action_entby=".$disposing_officer.", f_action_entdt=current_timestamp, f_to_whom=null,l_pet_action_id=".$pet_action_id.", l_action_type_code='".$pet_act_id."', l_action_entby=".$disposing_officer.", l_action_entdt=current_timestamp, l_to_whom=null
			WHERE petition_id=".$pet_id."";
			//echo $upsql;exit;
			$result=$db->query($upsql);
 } */
 $sql_c="select count(*) as cnt from pet_action where petition_id=".$pet_id;
 $result_c=$db->query($sql_c);
 while($row_c = $result_c->fetch(PDO::FETCH_BOTH))
	{
		$count_act=$row_c["cnt"];
	}
	if($count_act==1){
$sql="update pet_action SET action_remarks='".$instructions."' where petition_id=".$pet_id;
 $result=$db->query($sql);
	}
	$max_pet_act_sql="select pet_action_id from pet_action where petition_id=".$pet_id." order by pet_action_id desc limit 1";
			$result=$db->query($max_pet_act_sql);
			$row=$result->fetch(PDO::FETCH_BOTH);		
			$pet_action_id=$row['pet_action_id'];
 }
 
	}
	
	
	$petno_sql = "SELECT petition_no FROM pet_master where petition_id=".$pet_id."";
	$petno_rs=$db->query($petno_sql);
	$petno_row = $petno_rs->fetch(PDO::FETCH_BOTH);
	$petition_no= $petno_row[0]; 
	
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

		$content = pg_escape_bytea($data);
		fclose($f);

	$sql ="INSERT INTO pet_master_doc (petition_id,doc_content,doc_name,doc_size,doc_type,doc_entby,ent_ip_address,doc_entdt)VALUES('".$pet_id."','".$content."','".$filenames."','".$filesizes."','".$filetypes."','".$user_id."','".$_SERVER['REMOTE_ADDR']."','".$current_date."')";
		$result=$db->query($sql);
		
	}
/* File Upload ends here*/ 
/* Sending SMS to petitioner*/
/*$phone_no = $commmobile;
call_user_func('sending_sms', $phone_no);*/
		
/* Sending SMS to petitioner ends here*/

/* Sending Email to petitioner */
/*$to = $commemail;
$subject = "TNEGA - Petition Acknowledgement";
$message = "Your Petition submitted successfully. And its forwarded to concerned officer for further processing.
Thank you.";
$headers = array(
        'From: No reply',
        'Content-Type: text/html'
        );
mail($to,$subject,$message,$headers);*/
/* Sending Email to petitioner ends here*/

}
else
{?>
 
<script> alert("Invalid File Type"); </script>	
<?php }

 }
 	
	if($pet_act_id=='')
	{?>
		<script type="text/javascript">
        alert ("Error in Inserting");
		window.location.href="petition_update.php";
        </script>
	<?php
	} 
	else 
	{
	?>
			<!--<script type="text/javascript">
			alert("Inserted Successfully");
			</script>-->
 
  <?php //include("pm_common_js_css.php"); ?>	
  <script type="text/javascript">
$(window).keydown(function(event) {
  if(event.ctrlKey && event.keyCode == 80) { 
    //console.log("Hey! Ctrl+P event captured!");
    printwindow1(); 
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
	 
function printwindow()
{
 
 	document.getElementById("div_head").style.display='none';
	//document.getElementById("menu").style.display='none';
	document.getElementById("myTopnav").style.display='none';
	//document.getElementById("usr_detail").style.display='none';
	document.getElementById("footertbl").style.visibility='hidden';
	document.getElementById("header").style.display='none';
	document.getElementById("dontprint1").style.visibility='hidden';
	//document.getElementById("dontprint2").style.visibility='hidden';
	//document.getElementById("dontprint3").style.visibility='hidden';
	//document.getElementById("buttons_row").style.display='none';
	
	window.print();
	//document.getElementById("buttons_row").style.display=''; 
	document.getElementById("div_head").style.display='block';
	document.getElementById("myTopnav").style.display='block';
	//document.getElementById("usr_detail").style.display='block';
	document.getElementById("footertbl").style.visibility='visible';
	document.getElementById("header").style.display='block';
	document.getElementById("dontprint1").style.visibility='visible';
	//document.getElementById("dontprint2").style.visibility='visible';
	//document.getElementById("dontprint3").style.visibility='visible';
}
</script>
<?php 
//include("menu_home.php");
$sql="select * from pet_master_ext_link where petition_id=".$pet_id."";
				$result = $db->query($sql);
				$exist_count=$result->rowCount();
				if($exist_count==0){
					$sql="INSERT INTO public.pet_master_ext_link(
	petition_id, pet_ext_link_id, district_id, circle_id, pet_ext_link_no, fir_csr_year, lnk_entby, lnk_entdt, ent_ip_address)
	VALUES (".$pet_id.",".$pet_ext_link.",".$pet_ext_dist.",".$ext_ps_id.",'".$ext_no."','".$ext_year."',".$userId.", current_timestamp, '".$ip."');";
					
				}else{
					$sql="update pet_master_ext_link set pet_ext_link_id=$pet_ext_link,pet_ext_link_no='".$ext_no."', lnk_modby=".$userId.", district_id=".$pet_ext_dist.",circle_id=".$ext_ps_id." ,fir_csr_year='".$ext_year."' where petition_id=".$pet_id."";
					
				}
				//echo $sql;
				$result = $db->query($sql);
?>		

<?php
$lang = $xml->lang;
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_petition_detail_insert.php') order by ordering";
//exit;
$result = $db->query($query);
while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($lang == 'E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}

/* 		if ($off_level_id == 1) {
			$acknow_label = $label_name[36];
		}  
		else if ($off_level_id == 2) {
			$acknow_label = $label_name[1]." - ". $griev_dist_name;		
		} else if ($off_level_id == 3) {
			$acknow_label = $label_name[35]." - ". $rdo_name;
		} else if ($off_level_id == 4){
			$acknow_label = $label_name[34]." - ". $griev_taluk_name;
		} */
						   
		$sql="select off_level_id,off_level_dept_name,off_level_dept_tname 
						from usr_dept_off_level where dept_id=1 and off_level_id=".$user_off_level_id."";
		$result = $db->query($sql);
		while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
			if($_SESSION["lang"]=='T'){
				$ack_title = 'மனுப் பரிசீலனை முகப்பு  (ம.ப.மு.) - '. $rowArr['off_level_dept_tname'].' - '.$label_name[0];
				$acknow_label = $rowArr['off_level_dept_tname']." - ".$user_off_loc_name;
			} else {
				$ack_title = 'Petition Processing Potral (PPP) - '.$rowArr['off_level_dept_name'].' - '.$label_name[0];
				$acknow_label = $rowArr['off_level_dept_name']." - ".$user_off_loc_name;
			}
		}
		
		
		$url='https://locahost/police/getPetitionStatusQR.php?pet_id='.$petition_id;
		include('phpqrcode/qrlib.php');
		$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
				//echo  $PNG_TEMP_DIR;
				//html PNG location prefix
		$PNG_WEB_DIR = 'temp/';
				
		if (!file_exists($PNG_TEMP_DIR)) mkdir($PNG_TEMP_DIR);
			
			
		$filename = $PNG_TEMP_DIR.'test.png';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 10;
				//$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.H.'|'.5).'.png';
		$filename = $PNG_TEMP_DIR.'test'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
				 //echo $filename;
        QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
		
				
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  />
<form name="pm_pet_process" id="pm_pet_process" action="pm_pet_processing.php" method="post">

<input type="hidden" name="pet_act_id" id="pet_act_id" value="<?PHP echo $pet_action_id;?>"/>
<!--<div class="form_heading">
	<div class="heading"><?PHP //echo $label_name[0]; //Acknowledgement ?></div>
</div>-->
	      
<div class="contentMainDiv">
	<div class="contentDiv">
	<table class="ack_viewTbl">
			<tbody>
             
        <tr>
			<td colspan="2" style="align:center;">
				<center><b><?PHP echo $source_name; //Petition No. & Date?></b></center>
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
<?php echo 'or send <b>'.$petition_no.' </b> as SMS to <b>155250</b> to know the status as reply SMS';?>

</label>
		</center>
        </td>									
        </tr>           
			
			<tr>
			<td><?PHP echo $label_name[3]; //Petition No. & Date?></td>
			<td colspan="2"><?php echo $petition_no.' & '.$label_name[37].' '.$date; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $label_name[19];//Mobile Number?> : <b><?php echo $mobile_no;?></b></td> 					
		</tr>
            
        <tr>
			<td><?PHP echo $label_name[28]; //Grievance Type ?></td>
			<td colspan="2"> <?php echo $griev_name;?> & <?php echo $griev_sub_name;?></td>
		</tr>          

		<tr>
			<td><?PHP echo $label_name[10].": "; //Grievance/ Request ?><b></td>
			<td colspan="2"> 
			<?php echo ($survey_no!="")? $grievance."; "."Survey No. ".$survey_no.";  Sub-division No. ".$sub_div_no."; " : $grievance;?></b></td>
		</tr>		   
		
		<tr>
			<td><?PHP echo  'Enquiry Filing Officer'; ?></td>
			<td colspan="2"><?PHP echo $conc_off; //e-Mail ?> </td>
		</tr>
			
			<tr><td colspan='2' height='100%'style='color:#FEEDED'><?php echo ' <br>';?></td></tr>
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
			

			<!--tr>
			<td><?php echo  $label_name[38]//Petitioner Community and Category;?></td>
			<td><?PHP echo $community_category_name; ?></td>
			</tr-->


			
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
			<tr id="buttons_row">
            	<td colspan="2" class="btn"> 
            		<input type="button" name="" id="dontprint1" value="<?PHP echo $label_name[22]; //Print ?>" class="button" onClick="return printwindow()">
            		<?PHP if($pet_action_code=='C'){ ?>
                    <input type="submit" name="" id="dontprint1" value="<?PHP echo $label_name[23]; //Petition Processing?>" class="button" style="width: 120px;">
                    <?PHP } ?>
            	</td>
			</tr>		
			
			</tbody>
			</table>
			 

</div>
</div>
			<?php
		}
	
  	}
//}
?>
<script>
function download_document(url){
	window.location.href="http://14.139.183.34/pm_petition_doc_download.php?doc_id="+url;
}
</script> 
 </form>
