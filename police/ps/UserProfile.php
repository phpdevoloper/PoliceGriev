<?php
class UserProfile{
	
	private $user_name;//login user id
	private $off_desig_emp_name;//Actual user name
	private $dept_user_id;
	
	//DESIGN.
	private $dept_desig_id;
	private $dept_desig_name;//Desigantion name	
	private $sys_admin;//admin role
	private $pet_accept;//Petition receiving role
	private $pet_forward;//petition forward role
	private $pet_act_ret;//petition taking action and returning
	private $pet_disposal;//petition disposal (Processed petition Accept/ Reject)
	private $desig_coordinating;
	
	private $dept_desig_role_id;
	
	//SUP. DESIGNATION
	private $s_dept_desig_id;
	
	//OFFICE LEVEL
	private $off_level_dept_id;//office level department id
	private $off_level_dept_name;//office level department id
	private $off_level_name;//OFFICE LEVEL DEPT. name
	private $off_level_id;//office level id, its may be state, district, RDO, taluk, firka, block, village Panchayat, ULB etc,.
	private $off_pet_process;	
	private $off_coordinating;
	
	//DEPT.
	private $dept_id;//department id
	private $dept_name;//Department name
	private $off_level_pattern_id;//Revenue, Rural or Urban
	private $off_level_pattern_name;
	private $dept_pet_process;	
	private $dept_coordinating;
	
	//LOC. DETAILS
	private $off_location;
	private $off_loc_id;
	private $off_loc_name;
	private $sup_off_loc_id1;
	private $sup_off_loc_id2;
	private $off_hier;
	
	private $state_id;
	private $state_name;
	private $district_id;
	private $district_name;
	private $rdo_id;
	private $rdo_name;
	private $taluk_id;
	private $taluk_name;
	private $block_id;
	private $block_name;
	private $firka_id;
	private $firka_name;
	private $rev_village_id;
	private $rev_village_name;	
	private $lb_urban_id;
	private $lb_urban_name;	
	
	private $division_id;
	private $subdivision_id;
	private $circle_id;
	private $subcircle_id;
	private $unit_id;
	
	private $zone_id;
	private $zone_name;
	private $range_id;
	private $range_name;
	
	//New Fields
	private $dept_off_level_pattern_id;
	private $dept_off_level_pattern_name;
	private $dept_off_level_office_id;
	private $dept_off_level_office_name;
	
	private $griev_suptype_id;
	private $griev_suptype_name;
	
	
	public function setUser_name($user_name) {
		$this->user_name = $user_name;
		return $this;
	}

	public function getUser_name() {
		return $this->user_name;
	}
	//START DESIGN.
	public function setDept_desig_id($dept_desig_id) {
		$this->dept_desig_id = $dept_desig_id;
		return $this;
	}

	public function getDept_desig_id() {
		return $this->dept_desig_id;
	}
	
	
	public function setDept_user_id($dept_user_id) {
		$this->dept_user_id = $dept_user_id;
		return $this;
	}

	public function getDept_user_id() {
		return $this->dept_user_id;
	}
	
	
	public function setDept_desig_name($dept_desig_name) {
		$this->dept_desig_name = $dept_desig_name;
		return $this;
	}
	
	public function getDept_desig_name() {
		return $this->dept_desig_name;
	}
	
	public function getSys_admin() {
		return $this->sys_admin;
	}
	
	public function setSys_admin($sys_admin) {
		$this->sys_admin = $sys_admin;
		return $this;
	}
	
	public function setPet_accept($pet_accept) {
		$this->pet_accept = $pet_accept;
		return $this;
	}

	public function getPet_accept() {
		return $this->pet_accept;
	}
	
	public function setPet_forward($pet_forward) {
		$this->pet_forward = $pet_forward;
		return $this;
	}

	public function getPet_forward() {
		return $this->pet_forward;
	}
	
	public function setPet_act_ret($pet_act_ret) {
		$this->pet_act_ret = $pet_act_ret;
		return $this;
	}

	public function getPet_act_ret() {
		return $this->pet_act_ret;
	}
	
	public function setPet_disposal($pet_disposal) {
		$this->pet_disposal = $pet_disposal;
		return $this;
	}

	public function getPet_disposal() {
		return $this->pet_disposal;
	}
 	
	public function setDesig_coordinating($desig_coordinating) {
		$this->desig_coordinating = ($desig_coordinating == '')? 0:1;
		return $this;
	}

	public function getDesig_coordinating() {
		return $this->desig_coordinating;
	}
	
	public function setDesig_roleid($dept_desig_role_id) {
		$this->dept_desig_role_id = $dept_desig_role_id;
		return $this;
	}

	public function getDesig_roleid() {
		return $this->dept_desig_role_id;
	}
	
	//END DESIG.

	//SUP DESIG.
	public function setS_Dept_desig_id($s_dept_desig_id) {
		$this->s_dept_desig_id = $s_dept_desig_id;
		return $this;
	}

	public function getS_Dept_desig_id() {
		return $this->s_dept_desig_id;
	}	
	//END SUP DESIG.
	
	//START DEPT. OFFICE LEVEL
	public function setOff_level_dept_id($off_level_dept_id) {
		$this->off_level_dept_id = $off_level_dept_id;
		return $this;
	}

	public function getOff_level_dept_id() {
		return $this->off_level_dept_id;
	}
	
	public function setOff_level_dept_name($off_level_dept_name) {
		$this->off_level_dept_name = $off_level_dept_name;
		return $this;
	}

	public function getOff_level_dept_name() {
		return $this->off_level_dept_name;
	}
	
	public function setOff_level_name($off_level_name) {
		$this->off_level_name = $off_level_name;
		return $this;
	}

	public function getOff_level_name() {
		return $this->off_level_name;
	}
	
	public function setOff_level_id($off_level_id) {
		//echo $off_level_id;
		$this->off_level_id = $off_level_id;
		return $this;
	}

	public function getOff_level_id() {
		return $this->off_level_id;
	}
	
	public function setOff_pet_process($off_pet_process) {
		$this->off_pet_process = $off_pet_process;
		return $this;
	}

	public function getOff_pet_process() {
		return $this->off_pet_process;
	}
	
	public function setOff_coordinating($off_coordinating) {
		$this->off_coordinating = $off_coordinating;
		return $this;
	}

	public function getOff_coordinating() {
		return $this->off_coordinating;
	}
	//END DEPT. OFFICE LEVEL
	//START DEPT. 
	public function setDept_id($dept_id) {
		$this->dept_id = $dept_id;
		return $this;
	}

	public function getDept_id() {
		return $this->dept_id;
	}
	
	public function setDept_name($dept_name) {
		$this->dept_name = $dept_name;
		return $this;
	}

	public function getDept_name() {
		return $this->dept_name;
	}
	
	public function setOff_level_pattern_id($off_level_pattern_id) {
		$this->off_level_pattern_id = $off_level_pattern_id;
		return $this;
	}

	public function getOff_level_pattern_id() {
		return $this->off_level_pattern_id;
	}
	
	public function setOff_level_pattern_name($off_level_pattern_name) {
		$this->off_level_pattern_name = $off_level_pattern_name;
		return $this;
	}

	public function getOff_level_pattern_name() {
		return $this->off_level_pattern_name;
	}
	
	//dept_off_level_pattern_id
	public function setDept_off_level_pattern_id($dept_off_level_pattern_id) {
		$this->dept_off_level_pattern_id = $dept_off_level_pattern_id;
		return $this;
	}

	public function getDept_off_level_pattern_id() {
		return $this->dept_off_level_pattern_id;
	}

	
	public function setDept_off_level_office_id($dept_off_level_office_id) {
		$this->dept_off_level_office_id = $dept_off_level_office_id;
		return $this;
	}

	public function getDept_off_level_office_id() {
		return $this->dept_off_level_office_id;
	}
	
	public function setDept_off_level_office_name($dept_off_level_office_name) {
		$this->dept_off_level_office_name = $dept_off_level_office_name;
		return $this;
	}

	public function getDept_off_level_office_name() {
		return $this->dept_off_level_office_name;
	}
	
	/*
	private $dept_off_level_office_id;
	private $dept_off_level_office_name;
	*/
	public function setDept_pet_process($dept_pet_process) {
		$this->dept_pet_process = $dept_pet_process;
		return $this;
	}

	public function getDept_pet_process() {
		return $this->dept_pet_process;
	}
	
	public function setDept_coordinating($dept_coordinating) {
		$this->dept_coordinating = $dept_coordinating;
		return $this;
	}

	public function getDept_coordinating() {
		return $this->dept_coordinating;
	}
	//END DEPT.
	//START OFFICE LOC.
	public function setOff_location($off_location) {
		$this->off_location = $off_location;
		return $this;
	}

	public function getOff_location() {
		return $this->off_location;
	}
	
	public function setOff_loc_id($off_loc_id) {
		$this->off_loc_id = $off_loc_id;
		return $this;
	}

	public function getOff_loc_id() {
		return $this->off_loc_id;
	}
	
	public function setOff_loc_name($off_loc_name) {
		$this->off_loc_name = $off_loc_name;
		return $this;
	}

	public function getOff_loc_name() {
		return $this->off_loc_name;
	}
	
	public function setSup_off_loc_id1($sup_off_loc_id1) {
		$this->sup_off_loc_id1 = $sup_off_loc_id1;
		return $this;
	}

	public function getSup_off_loc_id1() {
		return $this->sup_off_loc_id1;
	}
	
	public function setSup_off_loc_id2($sup_off_loc_id2) {
		$this->sup_off_loc_id2 = $sup_off_loc_id2;
		return $this;
	}

	public function getSup_off_loc_id2() {
		return $this->sup_off_loc_id2;
	}
	
	public function setOff_hier($off_hier) {
		$this->off_hier = substr($off_hier,1,-1);
		return $this;
	}

	public function getOff_hier() {
		return $this->off_hier;
	}
	
	public function setState_id($state_id) {
		$this->state_id = $state_id;
		return $this;
	}

	public function getState_id() {
		return $this->state_id;
	}
	
	public function setState_name($state_name) {
		$this->state_name = $state_name;
		return $this;
	}

	public function getState_name() {
		return $this->state_name;
	}
	
	public function setDistrict_id($district_id) {
		$this->district_id = $district_id;
		return $this;
	}

	public function getDistrict_id() {
		return $this->district_id;
	}
	
	public function setDistrict_name($district_name) {
		$this->district_name = $district_name;
		return $this;
	}

	public function getDistrict_name() {
		return $this->district_name;
	}
	
	public function setRdo_id($rdo_id) {
		$this->rdo_id = $rdo_id;
		return $this;
	}

	public function getRdo_id() {
		return $this->rdo_id;
	}
	
	public function setRdo_name($rdo_name) {
		$this->rdo_name = $rdo_name;
		return $this;
	}

	public function getRdo_name() {
		return $this->rdo_name;
	}
	
	public function setTaluk_id($taluk_id) {
		$this->taluk_id = $taluk_id;
		return $this;
	}

	public function getTaluk_id() {
		return $this->taluk_id;
	}
	
	public function setTaluk_name($taluk_name) {
		$this->taluk_name = $taluk_name;
		return $this;
	}

	public function getTaluk_name() {
		return $this->taluk_name;
	}
	
	public function setBlock_id($block_id) {
		$this->block_id = $block_id;
		return $this;
	}

	public function getBlock_id() {
		return $this->block_id;
	}
	
	public function setBlock_name($block_name) {
		$this->block_name = $block_name;
		return $this;
	}

	public function getBlock_name() {
		return $this->block_name;
	}
	
	public function setFirka_id($firka_id) {
		$this->firka_id = $firka_id;
		return $this;
	}

	public function getFirka_id() {
		return $this->firka_id;
	}
	
	public function setFirka_name($firka_name) {
		$this->firka_name = $firka_name;
		return $this;
	}

	public function getFirka_name() {
		return $this->firka_name;
	}
	
	public function setRev_village_id($rev_village_id){
		$this->rev_village_id = $rev_village_id;
		return $this;
	}
	
	public function getRev_village_id(){
		return $this->rev_village_id;
	}
	
	public function setRev_village_name($Rev_village_name) {
		$this->Rev_village_name = $Rev_village_name;
		return $this;
	}

	public function getRev_village_name() {
		return $this->Rev_village_name;
	}
	 
	public function setLb_urban_id($lb_urban_id) {
		$this->lb_urban_id = $lb_urban_id;
		return $this;
	}

	public function getLb_urban_id() {
		return $this->lb_urban_id;
	}
	
	public function setLb_urban_name($lb_urban_name) {
		$this->lb_urban_name = $lb_urban_name;
		return $this;
	}

	public function getLb_urban_name() {
		return $this->lb_urban_name;
	}
	/***************** Division *********************/
	public function setDivision_id($division_id) {
		$this->division_id = $division_id;
		return $this;
	}

	public function getDivision_id() {
		return $this->division_id;
	}
	
	public function setDivision_name($division_name) {
		$this->division_name = $division_name;
		return $this;
	}

	public function getDivision_name() {
		return $this->division_name;
	}
	/*******************  ****************************/
	
	/***************** Sub Division *********************/
	public function setSubDivision_id($subdivision_id) {
		$this->subdivision_id = $subdivision_id;
		return $this;
	}

	public function getSubDivision_id() {
		return $this->subdivision_id;
	}
	
	public function setSubdivision_name($subdivision_name) {
		$this->subdivision_name = $subdivision_name;
		return $this;
	}

	public function getSubdivision_name() {
		return $this->Subdivision_name;
	}
	/*******************  ****************************/
	
	/***************** Circle *********************/
	public function setCircle_id($circle_id) {
		$this->circle_id = $circle_id;
		return $this;
	}

	public function getCircle_id() {
		return $this->circle_id;
	}
	
	public function setCircle_name($circle_name) {
		$this->circle_name = $circle_name;
		return $this;
	}

	public function getCircle_name() {
		return $this->circle_name;
	}
	/*******************  ****************************/
	
	/***************** Sub Circle *********************/
	public function setSubcircle_id($subcircle_id) {
		$this->subcircle_id = $subcircle_id;
		return $this;
	}

	public function getSubcircle_id() {
		return $this->subcircle_id;
	}
	
	public function setSubcircle_name($subcircle_name) {
		$this->subcircle_name = $subcircle_name;
		return $this;
	}

	public function getSubcircle_name() {
		return $this->subcircle_name;
	}
	/*******************  ****************************/
	
	/***************** Unit *********************/
	public function setUnit_id($unit_id) {
		$this->unit_id = $unit_id;
		return $this;
	}

	public function getUnit_id() {
		return $this->unit_id;
	}
	
	public function setUnit_name($unit_name) {
		$this->unit_name = $unit_name;
		return $this;
	}

	public function getUnit_name() {
		return $this->unit_name;
	}
	/*******************  ****************************/
	
	/***************** Zone *********************/
	public function setZone_id($zone_id) {
		$this->zone_id = $zone_id;
		return $this;
	}

	public function getZone_id() {
		return $this->zone_id;
	}
	
	public function setZone_name($zone_name) {
		$this->zone_name = $zone_name;
		return $this;
	}

	public function getZone_name() {
		return $this->zone_name;
	}
	/*******************  ****************************/
	
	/***************** Range *********************/
	public function setRange_id($range_id) {
		$this->range_id = $range_id;
		return $this;
	}

	public function getRange_id() {
		return $this->range_id;
	}
	
	public function setRange_name($range_name) {
		$this->range_name = $range_name;
		return $this;
	}

	public function getRange_name() {
		return $this->range_name;
	}
	/*******************  ****************************/
	public function setOff_desig_emp_name($off_desig_emp_name) {
		$this->off_desig_emp_name = $off_desig_emp_name;
		return $this;
	}

	public function getOff_desig_emp_name() {
		return $this->off_desig_emp_name;
	}
		
	public function setgriev_suptype_id($griev_suptype_id) {
		//echo '1';exit;
		$this->griev_suptype_id = $griev_suptype_id;
		return $this;
	}

	public function getgriev_suptype_id() {
		return $this->griev_suptype_id;
	}
	
	public function setgriev_suptype_name($griev_suptype_name) {
		
		//echo '1';exit;
		$this->griev_suptype_name = $griev_suptype_name;
		return $this;
	}

	public function getgriev_suptype_name() {
		return $this->griev_suptype_name;
	}
}

function castObject(&$object)
{
	if (!is_object ($object) && gettype ($object) == 'object'){
		return ($object = unserialize (serialize ($object)));
	}
	return $object;
}
?>