<?php 
class Customers_model extends CI_Model {
	//newly added- to be organisation based---
	var $organisation_id;
	public function __construct()
		{ 
		parent::__construct();
		$this->organisation_id=$this->session->userdata('organisation_id');
		

		}
	//------
	
	// to get customer details corresponding to the values provided 
	public function getCustomerDetails($data){ 
	//newly added- to be organisation based---
	$data['organisation_id']=$this->organisation_id;
	//----
	$this->db->from('customers');
	
	if($data!=''){
		$this->db->where($data);
	}
	return $this->db->get()->result_array();
	
	}
	public function getCustomerTariffs($customer_id){ 
	
	$qry='SELECT T.id as tariff_id,VM.name as vehicle_model,VACT.name as vehicle_ac_type,T.rate,TM.title,T.from_date,T.additional_kilometer_rate,T.	additional_hour_rate,T.driver_bata,T.night_halt FROM tariffs as T LEFT JOIN vehicle_models as VM on VM.id=T.vehicle_model_id LEFT JOIN vehicle_ac_types as VACT on VACT.id=T.vehicle_ac_type_id LEFT JOIN tariff_masters as TM on TM.id=T.tariff_master_id where T.customer_id="'.$customer_id.'" AND T.organisation_id = '.$this->session->userdata('organisation_id');
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(!empty($results)){
		return $results;
	}else{
		return false;
	}
	
	}
	public function getCustomerUser($cust_id){ 
		$this->db->select('customers.*,users.username,users.password');
		$this->db->from('customers');
		$this->db->join('users', 'customers.login_id = users.id','left');
		$this->db->where('customers.id',$cust_id);
		
		return $this->db->get()->result_array();
	
	}

	//to add customer details with unique mobile number
	/*public function addCustomer($data){ 
		$data['organisation_id']=$this->session->userdata('organisation_id');
		$data['user_id']=$this->session->userdata('id');
		
 		if($data['mobile']!=''){
		$condition['mobile']=$data['mobile'];
		//newly added- to be organisation based---
		//$condition['organisation_id']=$this->session->userdata('organisation_id');
		//----
		$res=$this->getCustomerDetails($condition); 
		if(count($res)==0){
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('customers',$data);
			$insert_id=$this->db->insert_id();

			if($insert_id > 0){
				return $insert_id;
			}else{
				return false;
			}
		}else{
			return $res[0]['id'];
		}
	
	}else{
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('customers',$data);
			$insert_id=$this->db->insert_id();

			if($insert_id > 0){
				return $insert_id;
			}else{
				return false;
			}

	}
	}*/
	
	//get customers array by term matching with name
	public function getArrayByName($name=''){
		
		if($name == '')
			return false;

		$this->db->where('organisation_id',$this->organisation_id);
		$this->db->like('name', $name, 'after'); 
		$this->db->order_by('name');
		$qry = $this->db->get('customers');
		if($qry->num_rows() > 0){
			return $qry->result_array();
		}else{
			return false;
		}
	}
	
	public function addGuest($data){  
		$data['organisation_id']=$this->session->userdata('organisation_id');
		$data['user_id']=$this->session->userdata('id');
	
		if($data['name']!=''){
			$newGuest = true;
			if($data['mobile'] != null){
				$condition['mobile']=$data['mobile'];
				$res=$this->getCustomerDetails($condition);
				if(count($res) >0){
					$newGuest = false;
					return $res[0]['id'];
				}
					
			}
			
			if($newGuest){
				$this->db->set('created', 'NOW()', FALSE);
				$this->db->insert('customers',$data);
				$insert_id=$this->db->insert_id();

				if($insert_id > 0){

					return $insert_id;
				}
			}

			return false;
	
		}
	
	}
	
	public function getLoginId($data,$login)
	{
		$this->load->model('organization_model');
		if($login['username']!='' && $login['password'] != ''){
			$login_id = $this->organization_model->insertUser($data['name'],
							'',$data['address'],$login['username'],
							$login['password'],$data['email'],
							$data['mobile'],CUSTOMER);
		}else{
			$login_id = 0;
		}
		return $login_id;
	}
	
	public function addCustomer($data,$login=false){
			
		$org_id=$this->session->userdata('organisation_id');
		if($org_id){
			$login_id = $this->getLoginId($data,$login);
			//insert customer	
			$data['organisation_id'] = $org_id;
			$data['user_id']=$this->session->userdata('id');
			$data['login_id'] = $login_id;
					
			//mobile validation for customer
			$insert_customer = true;
			if($data['mobile']!=''){
				$res=$this->getCustomerDetails(array('mobile'=>$data['mobile'],'organisation_id'=>$org_id));
				if(count($res)==0){
					$insert_customer = true;
				}else{
					$insert_customer = false;
				}

			}else{
				$insert_customer = true;
			}

			//validation true insert data and return insert customer id
			if($insert_customer){
				$this->db->set('created', 'NOW()', FALSE);
				$this->db->insert('customers',$data);
				$customer_id=$this->db->insert_id();

				if($customer_id > 0)
					return $customer_id;
				
			}

			// customer not inserted , delete user
			$this->db->delete('users', array('id' => $login_id));
			return false;
			
		}else{//organisation id not in session and customer login details not found
			return false;
		}
	}
	
	public function getCurrentStatuses($id){ 
	$qry='SELECT * FROM trips WHERE CONCAT(pick_up_date," ",pick_up_time) <= "'.date("Y-m-d H:i").'" AND CONCAT(drop_date," ",drop_time) >= "'.date("Y-m-d H:i").'" AND customer_id="'.$id.'" AND organisation_id = '.$this->session->userdata('organisation_id').' AND trip_status_id='.TRIP_STATUS_CONFIRMED;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}
	
	function  updateCustomers($data,$id,$login='',$flag='') {

		$qry=$this->db->where('id',$id );
		$qry=$this->db->get("customers");
		if(count($qry)>0){ 
			$login_id=$qry->row()->login_id; 
		}else{
			$login_id = 0;
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
		
		$this->db->where('id',$id );
		$this->db->set('login_id', $login_id);
		$this->db->set('updated', 'NOW()', FALSE);
		$this->db->update("customers",$data);
		return true;
	}
	
	public function getArray(){
		//newly added- to be organisation based----
		
		$qry=$this->db->where('organisation_id',$this->organisation_id);
		//---
		$qry=$this->db->get('customers');
		$count=$qry->num_rows();
		$l= $qry->result_array();
		
			for($i=0;$i<$count;$i++){
			$values[$l[$i]['id']]=$l[$i]['name'];
			}
			if(!empty($values)){
			return $values;
			}
			else{
			return false;
			}
	}
	public function getAllIds(){

	$qry=$this->db->select('id');
	$this->db->from('customers');
	//newly added- to be organisation based----
	$this->db->where('organisation_id',$this->organisation_id);
	//---
	$qry=$this->db->get();
	$count=$qry->num_rows();
	$result= $qry->result_array();
	for($i=0;$i<$count;$i++){
			$values[$result[$i]['id']]=$result[$i]['id'];
			}
	
	return $values;

	
	
	}
	public function getCustomersById($c_id){
	$qry='select id,name,mobile,email from customers where customer_group_id='.$c_id.' and organisation_id='.$this->session->userdata('organisation_id');
	$result=$this->db->query($qry);
	$result=$result->result_array();
	return $result;
	
	}
}
?>
