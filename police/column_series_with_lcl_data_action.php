<?php
ob_start();
session_start();
include("db.php");
$mode=$_POST["mode"];
/*-------------------------------  For State Level --------------------------------- */

if ($mode=="get_details_auto"){
	$sql="select * from dash_district_sum";
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		
		$data=array($row['district_id'],$row['district_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);
}

if($mode=="get_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$office_level=$_POST["office_level"];
	$dept_id=$_POST["dept_id"];
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	if ($office_level != "") {
		$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",".$office_level.") pa 
					   inner join pet_master a on pa.petition_id=a.petition_id";
	    $dept_level_condition = "";				   
	} else {
		$table_list = " pet_master a";
		$dept_level_condition = " and b.dept_id =".$dept_id."";
	}
	
	
	if ($office_level == 2) {
		$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select md.district_id as district_id,md.district_name as district_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper, 
		COALESCE(rej,0) as rej , 
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd,
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper,
		substr(md.district_name,1,5) ||  
		upper(substr(translate(initcap(substr(md.district_name,6,length(md.district_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dist_sname

		from mst_p_district md 
		left join 

		(select tk.district_id, tk.district_name,sum(tk_tot.tot) tot 
		from mst_p_district tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.district_id 
		group by tk.district_id) tot on tot.district_id=md.district_id 

		left join 
		(select tk.district_id, sum(tk_tot.acp) acp 
		from mst_p_district tk 
		left join (select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.district_id 
		group by tk.district_id) acp on acp.district_id=md.district_id 

		left join 
		(select tk.district_id, sum(tk_tot.rej) rej from mst_p_district tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.district_id group by tk.district_id) rej on rej.district_id=md.district_id

		left join 
		(select tk.district_id, sum(tk_tot.pnd) pnd from mst_p_district tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a 
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.district_id group by tk.district_id) pnd on pnd.district_id=md.district_id 
		where md.district_id > 0
		order by district_id";
		
	} else if ($office_level == 3) {
		$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select asd.district_id,asd.district_name,sum(tot) as tot,sum(acp) as acp,sum(rej) as rej,sum(pnd) as pnd, 
		substr(asd.district_name,1,5) ||  upper(substr(translate(initcap(substr(asd.district_name,6,length(asd.district_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dist_sname	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.rdo_id as off_location_id, tk1.rdo_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_rdo tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.rdo_id, tk.rdo_name,sum(tk_tot.tot) tot from mst_p_rdo tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) tot on tot.rdo_id=tk1.rdo_id 

		left join 
		(select tk.rdo_id, sum(tk_tot.acp) acp from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) acp on acp.rdo_id=tk1.rdo_id 

		left join 
		(select tk.rdo_id, sum(tk_tot.rej) rej from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) rej on rej.rdo_id=tk1.rdo_id 

		left join
		(select tk.rdo_id, sum(tk_tot.pnd) pnd
		from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a 
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) pnd on pnd.rdo_id=tk1.rdo_id ) asd
		where asd.district_id > 0
		group by asd.district_id,asd.district_name  order by asd.district_id";
		
		
	} else if ($office_level == 4) {
		$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select asd.district_id,asd.district_name,sum(tot) as tot,sum(acp) as acp,sum(rej) as rej,sum(pnd) as pnd, 
		substr(asd.district_name,1,5) ||  upper(substr(translate(initcap(substr(asd.district_name,6,length(asd.district_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dist_sname	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.taluk_id as off_location_id, tk1.taluk_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_taluk tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.taluk_id, tk.taluk_name,sum(tk_tot.tot) tot from mst_p_taluk tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) tot on tot.taluk_id=tk1.taluk_id 


		left join 
		(select tk.taluk_id, sum(tk_tot.acp) acp from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) acp on acp.taluk_id=tk1.taluk_id 

		left join 
		(select tk.taluk_id, sum(tk_tot.rej) rej from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) rej on rej.taluk_id=tk1.taluk_id 

		left join
		(select tk.taluk_id, sum(tk_tot.pnd) pnd
		from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a 
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) pnd on pnd.taluk_id=tk1.taluk_id ) asd
		where asd.district_id > 0
		group by asd.district_id,asd.district_name 
		
		order by asd.district_id";
	} else  if ($office_level == 10) {
		$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select asd.district_id,asd.district_name,sum(tot) as tot,sum(acp) as acp,sum(rej) as rej,sum(pnd) as pnd, 
		substr(asd.district_name,1,5) ||  upper(substr(translate(initcap(substr(asd.district_name,6,length(asd.district_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dist_sname	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.division_id as off_location_id, tk1.division_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_sp_division tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.division_id, tk.division_name,sum(tk_tot.tot) tot 
		from mst_p_sp_division tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) tot on tot.division_id=tk1.division_id 


		left join 
		(select tk.division_id, sum(tk_tot.acp) acp from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) acp on acp.division_id=tk1.division_id 

		left join 
		(select tk.division_id, sum(tk_tot.rej) rej from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) rej on rej.division_id=tk1.division_id 

		left join
		(select tk.division_id, sum(tk_tot.pnd) pnd
		from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a 
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) pnd on pnd.division_id=tk1.division_id ) asd
		where asd.district_id > 0
		group by asd.district_id,asd.district_name 

		order by asd.district_id";
	} else  if ($office_level == 11) {
		$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select asd.district_id,asd.district_name,sum(tot) as tot,sum(acp) as acp,sum(rej) as rej,sum(pnd) as pnd, 
		substr(asd.district_name,1,5) ||  upper(substr(translate(initcap(substr(asd.district_name,6,length(asd.district_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dist_sname	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.subdivision_id as off_location_id, tk1.subdivision_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_sp_subdivision tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.subdivision_id, tk.subdivision_name,sum(tk_tot.tot) tot 
		from mst_p_sp_subdivision tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) tot on tot.subdivision_id=tk1.subdivision_id 


		left join 
		(select tk.subdivision_id, sum(tk_tot.acp) acp from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) acp on acp.subdivision_id=tk1.subdivision_id 

		left join 
		(select tk.subdivision_id, sum(tk_tot.rej) rej from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a 
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) rej on rej.subdivision_id=tk1.subdivision_id 

		left join
		(select tk.subdivision_id, sum(tk_tot.pnd) pnd
		from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a 
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) pnd on pnd.subdivision_id=tk1.subdivision_id ) asd
		where asd.district_id > 0
		group by asd.district_id,asd.district_name 

		order by asd.district_id";
	}else {
		$sql="WITH off_pet AS 
			( 
			select a.petition_id, a.action_type_code, a1.off_loc_id as state_id, b.petition_date,a.action_entby from fn_pet_action_first_last_off_level(".$dept_id.",".$office_level.") a 
			inner join vw_usr_dept_users_v a1 on a1.dept_user_id=a.action_entby 
			inner join pet_master b on b.petition_id=a.petition_id 
			where b.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date) 

			select * from ( 
			select aa.state_id ,aa.state_name,aa.state_tname,aa.state_id as off_location_id,
			aa.off_loc_name,aa.off_loc_tname,aa.dept_user_id as district_id,aa.dept_desig_id,aa.dept_desig_name as district_name,
			aa.dept_desig_tname,aa.dept_id, 
			COALESCE(rcvd.received,0) as tot, 
			COALESCE(acpt.accepted,0) as acp,
			COALESCE((round(((accepted)*100.0/received),2)),0.00) as acpper, 
			COALESCE(rjct.rejected,0) as rej, 
			COALESCE((round(((rejected)*100.0/received),2)),0.00) as rejper, 
			COALESCE(pcb.pnd,0) as pnd,
			COALESCE((round(((pnd)*100.0/received),2)),0.00) as pndper,
			case when (length(aa.dept_desig_name) > 5) then
			substr(aa.dept_desig_name,1,5) ||  upper(substr(translate(initcap(substr(aa.dept_desig_name,6,length(aa.dept_desig_name) - 5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) 
			else aa.dept_desig_name end as dist_sname	
			from 

			(select a.state_id,a.state_id as off_location_id,a.state_name,a.state_name as off_loc_name, a.state_tname,a.state_tname as off_loc_tname,c.dept_id,c.dept_user_id,c.dept_desig_id,c.dept_desig_name, c.dept_desig_tname 
			from mst_p_state a 
			inner join vw_usr_dept_users_v c on c.off_loc_id=a.state_id and c.dept_id=".$dept_id." and c.off_level_id=".$office_level." and c.pet_disposal ) aa 

			left join
			(
			select state_id,action_entby,count(*) as received
			from off_pet a
			inner join pet_action_first_last b on b.petition_id=a.petition_id
			group by state_id,action_entby
			) rcvd on rcvd.state_id=aa.state_id and rcvd.action_entby=aa.dept_user_id

			left join
			(
			select state_id,action_entby,count(*) as accepted
			from off_pet a
			inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code='A'
			group by state_id,action_entby
			) acpt on acpt.state_id=aa.state_id and acpt.action_entby=aa.dept_user_id

			left join
			(
			select state_id,action_entby,count(*) as rejected
			from off_pet a
			inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code='R'
			group by state_id,action_entby
			) rjct on rjct.state_id=aa.state_id and rjct.action_entby=aa.dept_user_id

			left join -- pending: cl. bal. 
			(select state_id,action_entby,count(*) as pnd
			from off_pet a 
			inner join pet_action_first_last b on b.petition_id=a.petition_id and b.l_action_type_code not in ('A','R') 
			group by state_id,action_entby) pcb on pcb.state_id=aa.state_id and pcb.action_entby=aa.dept_user_id) b_rpt";
		
	} 
    $result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		
		$data=array($row['district_id'],$row['district_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}

if($mode=="get_dro_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$district_id=$_POST["district_id"];
	$dept_id=$_POST["dept_id"];
	$off_level_id=$_POST["off_level_id"];
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",10) pa 
					   inner join pet_master a on pa.petition_id=a.petition_id";
					   
	$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select district_id,off_location_id,off_loc_name,district_id,tot,acp,rej,pnd,pndper,
		substr(off_loc_name,1,5) ||  upper(substr(translate(initcap(substr(off_loc_name,6,length(off_loc_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dro_sname,district_name 	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.division_id as off_location_id, tk1.division_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_sp_division tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id and tk1.dept_id=".$dept_id."
		left join 
		(select tk.division_id, tk.division_name,sum(tk_tot.tot) tot from mst_p_sp_division tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) tot on tot.division_id=tk1.division_id 

		left join 
		(select tk.division_id, sum(tk_tot.acp) acp from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) acp on acp.division_id=tk1.division_id 

		left join 
		(select tk.division_id, sum(tk_tot.rej) rej from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) rej on rej.division_id=tk1.division_id 

		left join
		(select tk.division_id, sum(tk_tot.pnd) pnd from mst_p_sp_division tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.division_id 
		group by tk.division_id) pnd on pnd.division_id=tk1.division_id ) asd where district_id=".$district_id." 
		order by district_id,off_location_id";
	
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		//pndper
		$data=array($row['off_location_id'],$row['off_loc_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['dro_sname'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}

if($mode=="get_sro_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$district_id=$_POST["district_id"];
	$dept_id=$_POST["dept_id"];
	$off_level_id=$_POST["off_level_id"];
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",11) pa 
					   inner join pet_master a on pa.petition_id=a.petition_id";
					   
	$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select district_id,off_location_id,off_loc_name,district_id,tot,acp,rej,pnd,pndper,
		substr(off_loc_name,1,5) ||  upper(substr(translate(initcap(substr(off_loc_name,6,length(off_loc_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) dro_sname,district_name 	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.subdivision_id as off_location_id, tk1.subdivision_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_sp_subdivision tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id and tk1.dept_id=".$dept_id."
		left join 
		(select tk.subdivision_id, tk.subdivision_name,sum(tk_tot.tot) tot from mst_p_sp_subdivision tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) tot on tot.subdivision_id=tk1.subdivision_id 

		left join 
		(select tk.subdivision_id, sum(tk_tot.acp) acp from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) acp on acp.subdivision_id=tk1.subdivision_id 

		left join 
		(select tk.subdivision_id, sum(tk_tot.rej) rej from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) rej on rej.subdivision_id=tk1.subdivision_id 

		left join
		(select tk.subdivision_id, sum(tk_tot.pnd) pnd from mst_p_sp_subdivision tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.subdivision_id 
		group by tk.subdivision_id) pnd on pnd.subdivision_id=tk1.subdivision_id ) asd where district_id=".$district_id." 
		order by district_id,off_location_id";
	
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		//pndper
		$data=array($row['off_location_id'],$row['off_loc_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['dro_sname'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}

if($mode=="get_rdo_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$district_id=$_POST["district_id"];
	$dept_id=$_POST["dept_id"];
	
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",3) pa 
					   inner join pet_master a on pa.petition_id=a.petition_id";
					   
	$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select district_id,off_location_id,off_loc_name,district_id,tot,acp,rej,pnd,pndper,
		substr(off_loc_name,1,5) ||  upper(substr(translate(initcap(substr(off_loc_name,6,length(off_loc_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) rdo_sname,district_name 	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.rdo_id as off_location_id, tk1.rdo_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_rdo tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.rdo_id, tk.rdo_name,sum(tk_tot.tot) tot from mst_p_rdo tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) tot on tot.rdo_id=tk1.rdo_id 

		left join 
		(select tk.rdo_id, sum(tk_tot.acp) acp from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) acp on acp.rdo_id=tk1.rdo_id 

		left join 
		(select tk.rdo_id, sum(tk_tot.rej) rej from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) rej on rej.rdo_id=tk1.rdo_id 

		left join
		(select tk.rdo_id, sum(tk_tot.pnd) pnd
		from mst_p_rdo tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.rdo_id 
		group by tk.rdo_id) pnd on pnd.rdo_id=tk1.rdo_id ) asd where district_id=".$district_id."
		order by district_id,off_location_id";
	
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		//pndper
		$data=array($row['off_location_id'],$row['off_loc_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['rdo_sname'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}

if($mode=="get_tahsil_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$dist_id=$_POST["dist_id"];
	$dept_id=$_POST["dept_id"];
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",4) pa 
					   inner join pet_master a on pa.petition_id=a.petition_id";
					   
	$sql="with off_pet as
		(
		select b.off_loc_id, a.petition_id 
		from ".$table_list." 
		inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby 
		where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
		)

		select district_id,off_location_id,off_loc_name,district_id,tot,acp,rej,pnd,pndper,
		substr(off_loc_name,1,5) ||  upper(substr(translate(initcap(substr(off_loc_name,6,length(off_loc_name)-5)),'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) taluk_sname,district_name 	
		from
		(select tk1.district_id as district_id,md.district_name as district_name, 
		tk1.taluk_id as off_location_id, tk1.taluk_name as off_loc_name, 
		COALESCE(tot,0) as tot, 
		COALESCE(acp,0) as acp , 
		COALESCE((round(((acp)*100.0/tot),2)),0.00) as acpper,
		COALESCE(rej,0) as rej ,
		COALESCE((round(((rej)*100.0/tot),2)),0.00) as rejper, 
		COALESCE(pnd,0) as pnd, 
		COALESCE((round(((pnd)*100.0/tot),2)),0.00) as pndper

		from mst_p_taluk tk1 
		inner join mst_p_district md on tk1.district_id=md.district_id 
		left join 
		(select tk.taluk_id, tk.taluk_name,sum(tk_tot.tot) tot from mst_p_taluk tk 
		left join (select a.off_loc_id, count(a.petition_id) as tot 
		from off_pet a  
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) tot on tot.taluk_id=tk1.taluk_id 


		left join 
		(select tk.taluk_id, sum(tk_tot.acp) acp from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as acp 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) acp on acp.taluk_id=tk1.taluk_id 

		left join 
		(select tk.taluk_id, sum(tk_tot.rej) rej from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as rej 
		from off_pet a
		where exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) rej on rej.taluk_id=tk1.taluk_id 

		left join
		(select tk.taluk_id, sum(tk_tot.pnd) pnd
		from mst_p_taluk tk 
		left join 
		(select a.off_loc_id, count(a.petition_id) as pnd
		from off_pet a
		where not exists ( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') ) 
		group by a.off_loc_id) tk_tot on tk_tot.off_loc_id=tk.taluk_id 
		group by tk.taluk_id) pnd on pnd.taluk_id=tk1.taluk_id ) asd where district_id=".$dist_id."
		order by district_id,off_location_id";
	
	$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		
		$data=array($row['off_location_id'],$row['off_loc_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd'],$row['taluk_sname'],$row['district_name'],$row['pndper']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}


/*-------------------------------  For District Level --------------------------------- */
if($mode=="get_taluk_details")
{ 	
		$dist_id = $_POST["dist_id"];


	    $sql="select sum(cnt) as total from
			(select aa2.district_id,aa3.district_name,hsd_det_taluk_id,taluk_name,count(*) as cnt from hsd.hsd_details aa1
			inner join mst_p_taluk aa2 on aa2.taluk_id=aa1.hsd_det_taluk_id
			inner join mst_p_district aa3 on aa3.district_id=aa2.district_id
			where aa3.district_id=".$dist_id."
			group by hsd_det_taluk_id,aa2.taluk_name,aa2.district_id,aa3.district_name
			order by aa2.district_id) ee"; 
		 
		$result = $db->query($sql);
	 
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	 	$tlk_arr2d = array();
			  
		 foreach($rowarray as $row)
		{ 
				$tlk_data=array($row['total']);
 				array_push($tlk_arr2d,$tlk_data);
		 
	    } 
	   $sql="select aa.taluk_id,aa.district_id, aa.taluk_name, aa.target, bb.achieved, 
			case COALESCE(aa.target,0)
			when 0 then null
			else round((bb.achieved*100.0/aa.target),2) 
			end as ach_per, 
			(aa.target-bb.achieved) as balance, 
			case COALESCE(aa.target,0)
			when 0 then null
			else round(((aa.target-bb.achieved)*100.0/aa.target),2) 
			end as bal_per,
substr(aa.taluk_name,1,5) ||  upper(substr(translate(initcap(substr(aa.taluk_name,6,length(aa.taluk_name)-5)),
					'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) taluk_sname 			

			from

			(select a.taluk_id, a.district_id,a.taluk_name, COALESCE(b.hsd_taluk_target_target_value, 0) as target
			from mst_p_taluk a 
			left join hsd.hsd_taluk_target b on a.taluk_id=b.hsd_taluk_target_taluk_id and b.hsd_taluk_target_fin_year_id=".$_SESSION['phase']." )aa		
			
			inner join

			(select a.taluk_id, a.district_id,count(b.hsd_ben_id) achieved
			from mst_p_taluk a 
			left join hsd.hsd_details b on a.taluk_id=b.hsd_det_taluk_id and b.hsd_det_fin_year_id=".$_SESSION['phase']." 
			group by a.taluk_id,a.district_id) bb on bb.taluk_id=aa.taluk_id where aa.district_id=".$dist_id."";
		
		
		$result = $db->query($sql);
	 
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		 foreach($rowarray as $row)
		{ 
				$tlk_data=array($row['taluk_id'],$row['taluk_name'],$row['target'],$row['achieved'],$row['balance'],$row['ach_per'],$row['bal_per'],$row['taluk_sname']);
				
				array_push($tlk_arr2d,$tlk_data);
		 
	    } 
		 echo json_encode($tlk_arr2d);
	 
}
/*-------------------------------  For Taluk Level --------------------------------- */
if($mode=="get_villge_details")
{ 	
		$tlk_id = $_POST["tlk_id"];


	    $sql="select max(recd_pet) totallength from
		(select xx.district_id, xx.taluk_id, xx.rev_village_id, sum(COALESCE(xx.pet_recd::numeric,0)) recd_pet
		from camp_indl_details xx
		where xx.taluk_id=".$tlk_id."  
		group by xx.district_id, xx.taluk_id, xx.rev_village_id) bb1"; 
		 
		$result = $db->query($sql);
	 
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	 	$villge_arr2d = array();
			  
		 foreach($rowarray as $row)
		{ 
				$villge_data=array($row['totallength']);
 				array_push($villge_arr2d,$villge_data);
		 
	    } 
	     $sql="select aa1.*, substr(aa2.rev_village_name,1,5) ||  upper(substr(translate(initcap(substr(aa2
.rev_village_name,6,length(aa2.rev_village_name)-5)),
					'`~aeiou!@#$%^&*()-_=+[{]}\|;:,<.>/?'' ',''),1,5)) villge_name  
from
(select xx.district_id, xx.taluk_id, xx.rev_village_id, sum(COALESCE(xx.pet_recd::numeric
,0)) recd_pet, sum(COALESCE(xx.pet_accept::numeric,0)) accept_pet, sum(COALESCE(xx.pet_rejd::numeric,0)) rejd_pet, sum(COALESCE(xx.pet_oth_dept::numeric
,0)) oth_dept_pet
from camp_indl_details xx
where xx.taluk_id=".$tlk_id." and xx.phase_id=".$_SESSION['phase']."
group by xx.district_id,xx.taluk_id, xx.rev_village_id order by xx.rev_village_id) aa1
inner join
mst_p_rev_village aa2 on aa2.rev_village_id=aa1.rev_village_id";
  	  
		$result = $db->query($sql);
	 
		$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
		 foreach($rowarray as $row)
		{ 
				$villge_data=array($row['recd_pet'],$row['accept_pet'],$row['rejd_pet'],$row['oth_dept_pet'],
				ucfirst(strtolower($row['villge_name'])),$row['rev_village_id']);
				array_push($villge_arr2d,$villge_data);
		 
	    } 
		 echo json_encode($villge_arr2d);
	 
}

if($mode=="find_interval")
{ 	
	$interval = $_POST["interval"];
	$interval_length = $_POST["interval_length"];	
	if ($interval_length > 1)
		$interval_length = (-1) * ($interval_length - 1);
	else	
		$interval_length = (-1) * $interval_length;
	$sql="select round(".$interval.",".$interval_length.") as interval"; 	 
	$result = $db->query($sql); 
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$villge_arr2d = array();		  
	 foreach($rowarray as $row)
	{ 
		$villge_data=array($row['interval']);
		array_push($villge_arr2d,$villge_data);
	 
	} 
	echo json_encode($villge_arr2d);      
}

if($mode=="get_state_details_auto") {	
	
	
	$sql="select * from dash_state_sum";
    $result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		
		$data=array($row['state_id'],$row['state_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}

if($mode=="get_state_details") { 	
	$from_date=$_POST["from_date"];
	$to_date=$_POST["to_date"];
	$office_level=$_POST["office_level"];
	$dept_id=$_POST["dept_id"];
	
	if ($office_level != "") {
		$table_list = "fn_pet_action_first_last_off_level(".$dept_id.",".$office_level.") pa 
					   inner join pet_master a on pa.petition_id=a.petition_id
					   inner join vw_usr_dept_users_v b on b.dept_user_id = pa.action_entby ";
	    $dept_level_condition = "";				   

	} else {
		$table_list = " pet_master a
						inner join vw_usr_dept_users_v b on b.dept_user_id = a.pet_entby ";
		$dept_level_condition = " and b.dept_id =".$dept_id."";
	}
	
	$fromdate=explode('/',$from_date);
	$day=$fromdate[0];
	$mnth=$fromdate[1];
	$yr=$fromdate[2];
	$frm_dt=$yr.'-'.$mnth.'-'.$day;
	
	$todate=explode('/',$to_date);
	$day=$todate[0];
	$mnth=$todate[1];
	$yr=$todate[2];
	$to_dt=$yr.'-'.$mnth.'-'.$day;	
	
	
	$sql="
	with off_pet as
	(
	select a.petition_id, a.petition_date 
	from ".$table_list." 
	where a.petition_date between '".$frm_dt."'::date and '".$to_dt."'::date 
	".$dept_level_condition."
	)
	select abc.state_id,abc.state_name,abc.state_name,abc.tot,abc.acp,abc.acpper,abc.rej,
	abc.rejper,abc.pnd,abc.pndper from (
	select md.state_id as state_id,md.state_name as state_name, 
	COALESCE(tot,0) as tot, 
	COALESCE(acp,0) as acp , 
	COALESCE((round(((acp)*100.0/case when tot=0 then 1 else tot end),2)),0.00) as acpper, 
	COALESCE(rej,0) as rej , 
	COALESCE((round(((rej)*100.0/case when tot=0 then 1 else tot end),2)),0.00) as rejper, 
	COALESCE(pnd,0) as pnd,
	COALESCE((round(((pnd)*100.0/case when tot=0 then 1 else tot end),2)),0.00) as pndper	

	from mst_p_state md 
	left join 

	(select count(a.petition_id) as tot 
	from off_pet a) tot on true
	
	left join 
	(select count(a.petition_id) as acp 
	from off_pet a
	where exists 
	( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='A' )) acp on true

	left join 
	(select count(a.petition_id) as rej 
	from off_pet a
	where exists 
	( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code='R' )) rej on true

	left join 
	(select count(a.petition_id) as pnd 
	from off_pet a
	where not exists 
	( select * from pet_action_first_last c where c.petition_id=a.petition_id and c.l_action_type_code in ('A','R') )) pnd on true ) abc where abc.state_id=29";
    $result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	$arr2d = array();
	$data = array();
	foreach($rowarray as $row)
	{ 		
		$data=array($row['state_id'],$row['state_name'],$row['tot'],$row['acp'],$row['rej'],$row['pnd']); 
		array_push($arr2d,$data);

	}
	echo json_encode($arr2d);

}
