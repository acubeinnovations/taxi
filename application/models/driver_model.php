<?php
class Driver_model extends CI_Model {

	public function addDriverdetails($data,$login=false){
		
		$org_id=$this->session->userdata('organisation_id');
			if($org_id){ 
				$login_id = $this->getLoginId($data,$login);
				$data['login_id'] = $login_id;
				$this->db->set('created', 'NOW()', FALSE);
				$this->db->insert('drivers',$data);
				$driver = $this->db->insert_id();
		if($driver > 0){ //echo "inserted";exit;
			return $driver;
		}else{
			$this->db->delete('users', array('id' => $login_id)); //echo "deleted";exit;
			return false;
		}
			
		
		}else{
			return false;
		}
	}

	//add new vehicle from trip booiking
	public function addDriverFromTripBooking($value = null){

		if($value !=null){
			$data['name'] = $value;
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('drivers',$data);
			return mysql_insert_id();
		}
		return -1;
	}
	
	
	
	public function getLoginId($data,$login)
	{
		$this->load->model('organization_model');
			if($login['username']!='' && $login['password'] != ''){
				$login_id = $this->organization_model->insertUser($data['name'],'',$data['present_address'],$login['username'],$login['password'],$data['email'],$data['mobile'],DRIVER);
			}else{
				$login_id = 0;
			}
		return $login_id;
	}

	public function getDriverDetails($data){ 
		
	$this->db->from('customers');
	$this->db->where($data);
	return $this->db->get()->result_array();
	
	}
	public function getCurrentStatuses($id){ 
	$qry='SELECT * FROM trips WHERE CONCAT(pick_up_date," ",pick_up_time) <= "'.date("Y-m-d H:i").'" AND CONCAT(drop_date," ",drop_time) >= "'.date("Y-m-d H:i").'" AND driver_id="'.$id.'" AND organisation_id = '.$this->session->userdata('organisation_id').' AND trip_status_id='.TRIP_STATUS_CONFIRMED;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}

	public function getDrivers(){ 
	$qry='SELECT D.name,D.id,D.mobile,VD.from_date,VD.to_date,VD.driver_id,VD.vehicle_id FROM drivers AS D LEFT JOIN vehicle_drivers AS VD ON  D.id =VD.driver_id AND D.organisation_id = '.$this->session->userdata('organisation_id').' WHERE VD.organisation_id = '.$this->session->userdata('organisation_id').' AND VD.to_date="9999-12-30"';
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$drivers[$results[$i]['vehicle_id']]['driver_name']=$results[$i]['name'];
		$drivers[$results[$i]['vehicle_id']]['mobile']=$results[$i]['mobile'];
		$drivers[$results[$i]['vehicle_id']]['from_date']=$results[$i]['from_date'];

		}
		return $drivers;
	}else{
		return false;
	}
	}

	function getDriversArray($condion=''){
	$this->db->from('drivers');
	$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
	if($condion!=''){
    $this->db->where($condion);
	}
	$qry=$this->db->order_by("name", "Asc"); 
    $results = $this->db->get()->result();
	

		for($i=0;$i<count($results);$i++){
		$values[$results[$i]->id]=$results[$i]->name;
		}
		if(!empty($values)){
		return $values;
		}
		else{
		return false;
		}

	}

	public function UpdateDriverdetails($data,$id,$login='',$flag=''){ 
		
		$qry=$this->db->where('id',$id );
		$qry=$this->db->get("drivers");
		
		if(count($qry)>0){
					
			$login_id=$qry->row()->login_id; 
		}else{
			$login_id=0;
		}
				
		if($login_id > 0){//user exists
			if($flag==0){
				$login['password'] = md5($login['password']);
			}
				$this->db->set('updated', 'NOW()', FALSE);
				$this->db->where('id',$login_id );
				$this->db->update("users",$login);
		}else{//new user
				$login_id = $this->getLoginId($data,$login);
		}		
	
	$arry=array('id'=>$id,'organisation_id'=>$data['organisation_id']);
	$this->db->set('login_id', $login_id);
	$this->db->set('updated', 'NOW()', FALSE);
	$qry=$this->db->where($arry);
	$qry=$this->db->update("drivers",$data);
	
	return true;
	}

	/* INTELLIGENCE PORTION
	public function GetDriverForTripBooking() 
	{
		$sql = "SELECT dr.id,dr.name FROM drivers dr WHERE dr.id NOT IN (SELECT driver_id FROM trips WHERE NOW() BETWEEN CONCAT(pick_up_date,' ',pick_up_time) AND CONCAT(drop_date,' ',drop_time))
GROUP BY dr.id";

		$query = $this->db->query($sql);
		$drivers=array();
		if($query->num_rows() > 0){
			$avl_drivers = $query->result_array();
			foreach($avl_drivers as $avl_driver){
				
				$sql_free = "SELECT CONCAT(trip.drop_date,' ',trip.drop_time) FROM trips trip WHERE CONCAT(trip.drop_date,' ',trip.drop_time) < NOW() AND trip.driver_id ='".$avl_driver['id']."' ORDER BY CONCAT(trip.drop_date,' ',trip.drop_time) DESC LIMIT 1";
				$query = $this->db->query($sql_free);
				if($query->num_rows() == 1){
					$drivers[$avl_driver['id']] = $avl_driver['name']."("."Free".")";
				}else{
					$sql_have_future_trip = "SELECT CONCAT(trip.pick_up_date,' ',trip.pick_up_time) AS TripTime
FROM trips trip WHERE  trip.driver_id ='".$avl_driver['id']."' AND CONCAT(trip.pick_up_date,' ',trip.pick_up_time) > NOW() ORDER BY CONCAT(trip.pick_up_date,' ',trip.pick_up_time) ASC LIMIT 1";
					$query = $this->db->query($sql_have_future_trip);
					if($query->num_rows() == 1){
						$row = $query->row();
						$drivers[$avl_driver['id']] = $avl_driver['name']."(".$row->TripTime.")";
					}else{
						$drivers[$avl_driver['id']] = $avl_driver['name']."("."Free".")";
					}
				}
			}
		}
		return $drivers;
	}*/



}?>
