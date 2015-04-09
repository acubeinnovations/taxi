<?php 
class Trip_model extends CI_Model {

	function get_sql_for_trips($condition=false){
	
		$org_id = $this->session->userdata('organisation_id');

		$qry='SELECT TV.payment_type_id as payment_type_id,ORG.name as company_name ,VO.name as ownership,T.id,T.customer_id,T.customer_group_id,T.remarks,T.vehicle_model_id,T.vehicle_ac_type_id,T.driver_id,T.vehicle_id,T.guest_id,V.vehicle_ownership_types_id,T.tariff_id,T.trip_status_id,T.id as trip_id,T.booking_date,T.drop_date,T.drop_time,T.pick_up_date,T.pick_up_time,VM.name as model,V.registration_number,T.pick_up_city,T.pick_up_area,G.name as guest_name,G.mobile as guest_info,T.drop_city,T.drop_area,C.name as customer_name,C.mobile as customer_mobile,CG.name as customer_group,D.name as driver,D.mobile as driver_info, D.driver_status_id FROM trips T LEFT JOIN vehicle_models VM ON VM.id=T.vehicle_model_id LEFT JOIN vehicles V ON V.id=T.vehicle_id LEFT JOIN customers G ON G.id=T.guest_id LEFT JOIN customers C ON C.id=T.customer_id LEFT JOIN customer_groups CG ON CG.id=T.customer_group_id LEFT JOIN drivers D ON D.id=T.driver_id LEFT JOIN vehicle_ownership_types VO ON V.vehicle_ownership_types_id=VO.id  LEFT JOIN trip_vouchers TV ON TV.trip_id=T.id LEFT JOIN organisations ORG ON ORG.id = T.organisation_id where T.organisation_id='.$org_id;

		if($condition){
		
			$where = (isset($condition['where']))?$condition['where']:array();
			$like = (isset($condition['like']))?$condition['like']:array();

			//customer session check
			if($this->session->userdata('customer')){
				$qry .= ' AND T.customer_id='.$this->session->userdata('customer')->id;
			}else if($like['customer_name'] != null){
			
				$qry.=' And C.name Like "%'.$like['customer_name'].'%"';
			}

		
			//driver session check 
			if($this->session->userdata('driver')){ 
				$qry .= ' AND T.driver_id='.$this->session->userdata('driver')->id;
			}else if($where['driver_id'] > 0){
				$qry.=' AND T.driver_id ="'.$where['driver_id'].'"';
			}
			if($where['trip_id'] > 0){
				$qry.=' AND T.id ="'.$where['trip_id'].'"';
					
			}

			if($where['trip_pick_date'] != null && $where['trip_drop_date']!= null){
				$qry.=' AND (T.drop_date BETWEEN "'.$where['trip_pick_date'].'" AND "'.$where['trip_drop_date'].'")';
			}else if($where['trip_pick_date'] != null && $where['nt_continous'] == 'true'){
				$qry.=' AND (T.pick_up_date ="'.$where['trip_pick_date'].'")';
			
			}
			else if($where['trip_pick_date'] != null){
				$qry.=' AND  (T.pick_up_date="'.$where['trip_pick_date'].'" or "'.$where['trip_pick_date'].'" BETWEEN T.pick_up_date and T.drop_date)';
			}else if($where['trip_drop_date']!= null){
				$qry.=' AND (T.drop_date ="'.$where['trip_drop_date'].'")';	
			}

			if($where['vehicle_id'] > 0){
				$qry.=' AND T.vehicle_id ="'.$where['vehicle_id'].'"';
					
			}

		

			if($where['trip_status_id'] > 0){
				$qry.=' AND T.trip_status_id ="'.$where['trip_status_id'].'"';
			}

			if($where['customer_group_id'] > 0){
				$qry.=' AND T.customer_group_id ="'.$where['customer_group_id'].'"';
			}
		}

		$qry.=' order by CONCAT( T.pick_up_date, " ", T.pick_up_time ) ASC';

		return $qry;

	}

}
?>
