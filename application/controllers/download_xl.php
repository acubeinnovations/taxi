<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download_xl extends CI_Controller {

	public function __construct()
{
    parent::__construct();
    $this->load->helper('my_helper');
    $this->load->model('print_model');
    $this->load->model('driver_model');
    $this->load->model('vehicle_model');
    $this->load->model('customers_model');
    $this->load->model('user_model');
    $this->load->model('trip_booking_model');
    no_cache();

}
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
	} else {
		return false;
	}

	}    
	public function index(){
		$param1=$this->uri->segment(4);
        if($this->session_check()==true) {
		
			if($param1=='driver'){

				$this->driverXL();

			}else if($param1=='vehicle'){
			
				$this->vehicleXL();

			}else if($param1=='trips'){
				
				$this->tripsXL();

			}else if($param1=='customers'){
				
				$this->customersXL();

			}else if($param1=='tariffs'){
				
				$this->tariffsXL();

			}
			else{

				$this->notFound();
			}
		}else{
			
			$this->notAuthorized();
		}
	
    }
	
    public function driverXL(){
		//echo $this->input->get('name');
		//echo $this->input->get('age');
	$name= $this->input->get('name');
	$city= $this->input->get('city');
	$status= $this->input->get('status');  //echo $name.",".$city.",".$status;exit;
	$qry='SELECT D.id,D.name
FROM drivers D where D.organisation_id = '.$this->session->userdata('organisation_id');
		/*if(isset($name)&& $name!=null && isset($city)&& $city!=null){
		$qry.=' AND name LIKE "%'.$name.'%" AND district LIKE "%'.$city.'%" ';
		}*/
		if(isset($_REQUEST['status']) && $_REQUEST['status']!=-1 ){
	$date_now=date('Y-m-d H:i:s');
	$qry=' SELECT D.id
			FROM drivers AS D  LEFT JOIN trips as T ON D.id= T.driver_id where D.organisation_id = '.$this->session->userdata('organisation_id').'
			AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
			AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
			AND T.organisation_id = '.$this->session->userdata('organisation_id').'
			AND T.driver_id = D.id
			AND T.trip_status_id = '.TRIP_STATUS_CONFIRMED;
	}
	if(isset($_REQUEST['status']) && $_REQUEST['status']==0 ){
	$date_now=date('Y-m-d H:i:s');
	$qry='SELECT D.id FROM drivers D WHERE  D.organisation_id = "2" AND D.id NOT IN ('.$qry.')';
	}
		if(isset($name) ){
		$qry.=' AND name LIKE "%'.$name.'%" ';
		}
		if(isset($city)){
		$qry.=' AND district LIKE "%'.$city.'%" ';
		}
	
	$data['values']=$this->print_model->all_details($qry); 
	if(empty($data['values'])){
	$data['msg']='No Results Found';
	}
	$driver_trips='';
	$driver_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		$id=$data['values'][$i]['id'];
		$availability=$this->driver_model->getCurrentStatuses($id);
		if($availability==false){
		$driver_statuses[$id]='Available';
		$driver_trips[$id]=gINVALID;
		}else{
		$driver_statuses[$id]='OnTrip';
		$driver_trips[$id]=$availability[0]['id'];
		}
	}
	$data['driver_statuses']=$driver_statuses;
	$data['driver_trips']=$driver_trips;
	/*if(empty($data['values'])){
				$data['result']="No Results Found !";
				}*/
	
	for ($i=0;$i<count($data['values']);$i++){
	$driverid=$data['values'][$i]['id'];
	$driver_details[$driverid]=$this->user_model->getVehicleDetails($driverid);
	
	$drivers=$this->vehicle_model->getDriversInfo();
	if($drivers!=false){
	$data['drivers']=$drivers;
	}else{
	$data['drivers']='';
	}
	}
	if(!empty($driver_details)){
		$data['v_details']=$driver_details;
	}
	
	
	
	$data['v_models']=$this->user_model->getArray('vehicle_models');
	$data['v_makes']=$this->user_model->getArray('vehicle_makes');
	$vehicles=$this->vehicle_model->getVehicles();
	if($vehicles!=false){
	$data['vehicles']=$vehicles;
	}else{
	$data['vehicles']='';
	}
	
	$data['title']='List Driver| '.PRODUCT_NAME;
	$page='user-pages/print_listDrivers';
	$this->load_templates($page,$data);	

	}


	public function vehicleXL(){
		
	$qry='SELECT V.id
FROM vehicles V where V.organisation_id = '.$this->session->userdata('organisation_id');
	if(isset($_REQUEST['status'])&& $_REQUEST['status']!=-1 ){
	$date_now=date('Y-m-d H:i:s');
	$qry=' SELECT V.id FROM vehicles AS V LEFT JOIN trips as T ON T.vehicle_id=V.id where V.organisation_id = '.$this->session->userdata('organisation_id').' 
	AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
	AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
	AND T.organisation_id = '.$this->session->userdata('organisation_id').'
	AND T.vehicle_id = V.id 
	AND T.trip_status_id ='.TRIP_STATUS_CONFIRMED;
	}
	if(isset($_REQUEST['status'])&& $_REQUEST['status']==0 ){
	$date_now=date('Y-m-d H:i:s');
	$qry='SELECT V.id FROM vehicles V WHERE V.organisation_id = '.$this->session->userdata('organisation_id').' AND V.id NOT IN ('.$qry.')';
	}
	
	if(isset($_REQUEST['reg_num'])){
	$qry.=' AND V.registration_number LIKE "%'.$_REQUEST['reg_num'].'%" ';
	}
	if(isset($_REQUEST['vehicle_owner']) &&$_REQUEST['vehicle_owner'] >0){
	$qry.=' AND V.vehicle_owner_id ='.$_REQUEST['vehicle_owner'];
	}
	if(isset($_REQUEST['vehicle_ownership']) && $_REQUEST['vehicle_ownership']>0){
	$qry.=' AND V.vehicle_ownership_types_id ='.$_REQUEST['vehicle_ownership'];;
	
	}
	if(isset($_REQUEST['supplier']) && $_REQUEST['supplier']>0){
	$qry.=' AND V.supplier_group_id ='.$_REQUEST['supplier'];
	
	}
	if(isset($_REQUEST['vehicle_model']) && $_REQUEST['vehicle_model']>0){
	$qry.=' AND V.vehicle_model_id ='.$_REQUEST['vehicle_model'];
	
	}
	
	$data['values']=$this->print_model->all_details($qry); 
	if(empty($data['values'])){
	$data['msg']='No Results Found';
	}
	$vehicle_trips='';
	$vehicle_statuses='';
	for($i=0;$i<count($data['values']);$i++){
		$id=$data['values'][$i]['id'];
		$availability=$this->vehicle_model->getCurrentStatuses($id);
		if($availability==false){
		$vehicle_statuses[$id]='Available';
		$vehicle_trips[$id]=gINVALID;
		}else{
		$vehicle_statuses[$id]='OnTrip';
		$vehicle_trips[$id]=$availability[0]['id'];
		}
	}//print_r($vehicle_statuses);print_r($vehicle_trips);exit;
	$data['vehicle_statuses']=$vehicle_statuses;
	$data['vehicle_trips']=$vehicle_trips;
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	for ($i=0;$i<count($data['values']);$i++){
	//$id=$data['values'][$i]['vehicle_owner_id'];
	//$details[$id]=$this->user_model->getOwnerDetails($id);
	$owners=$this->vehicle_model->getOwners();
	if($owners!=false){
	$data['owners']=$owners;
	}else{
	$data['owners']='';
	}
	$vehicles=$this->vehicle_model->getListVehicles();
	if($vehicles!=false){
	$data['vehicles']=$vehicles;
	}else{
	$data['vehicles']='';
	}
	}
	if(!empty($details)){
	$data['owner_details']=$details;
	}

	$tbl_arry=array('vehicle_models','vehicle_types','vehicle_owners','vehicle_makes','vehicle_ownership_types');
	$count=count($tbl_arry);
	for ($i=0;$i<$count;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$drivers=$this->driver_model->getDrivers();
	if($drivers!=false){
	$data['drivers']=$drivers;
	}else{
	$data['drivers']='';
	}
	$data['title']='List Vehicles | '.PRODUCT_NAME;
	$page='user-pages/print_listVehicles';
	
	$this->load_templates($page,$data);	
	}
	public function tripsXL(){
		//echo $this->input->get('name');
		//echo $this->input->get('age');
		
			
			if((isset($_REQUEST['pickupdate']) || isset($_REQUEST['dropdate']) || isset($_REQUEST['vehicles'])|| isset($_REQUEST['drivers'])|| isset($_REQUEST['trip_status']) || isset($_REQUEST['cgroups']) || isset($_REQUEST['c_name']) || isset($_REQUEST['trip_id']) || isset($_REQUEST['suppliers']))){
				$qry='SELECT VO.name as ownership,T.customer_id,T.customer_group_id,T.vehicle_model_id,T.driver_id,T.vehicle_id,T.guest_id,V.vehicle_ownership_types_id,T.tariff_id,T.trip_status_id,T.id as trip_id,T.booking_date,T.drop_date,T.drop_time,T.pick_up_date,T.pick_up_time,VM.name as model,V.registration_number,T.pick_up_city,T.pick_up_area,G.name as guest_name,G.mobile as guest_info,T.drop_city,T.drop_area,C.name as customer_name,C.mobile as customer_mobile,CG.name as customer_group,D.name as driver,D.mobile as driver_info,T.remarks FROM trips T LEFT JOIN vehicle_models VM ON VM.id=T.vehicle_model_id LEFT JOIN vehicles V ON V.id=T.vehicle_id LEFT JOIN customers G ON G.id=T.guest_id LEFT JOIN customers C ON C.id=T.customer_id LEFT JOIN customer_groups CG ON CG.id=T.customer_group_id LEFT JOIN drivers D ON D.id=T.driver_id LEFT JOIN vehicle_ownership_types VO ON V.vehicle_ownership_types_id=VO.id where T.organisation_id='.$this->session->userdata('organisation_id');
				
				if(isset($_REQUEST['pickupdate']) && isset($_REQUEST['dropdate'])){
					
					//new $qry.=' AND T.pick_up_date BETWEEN "'.$_REQUEST['pickupdate'].'" AND "'.$_REQUEST['dropdate'].'" AND T.drop_date BETWEEN "'.$_REQUEST['pickupdate'].'" AND "'.$_REQUEST['dropdate'].'"';		
					$qry.=' AND (T.drop_date BETWEEN "'.$_REQUEST['pickupdate'].'" AND "'.$_REQUEST['dropdate'].'")';
				}else if(isset($_REQUEST['pickupdate'])){
				
				//$qry.=' AND T.pick_up_date ="'.$_REQUEST['pickupdate'].'"';
				//new $qry.=' AND (T.pick_up_date="'.$_REQUEST['pickupdate'].'" OR T.drop_date="'.$_REQUEST['pickupdate'].'")';
				$qry.='  AND  (T.pick_up_date="'.$_REQUEST['pickupdate'].'" or "'.$_REQUEST['pickupdate'].'" BETWEEN T.pick_up_date and T.drop_date)';
				}else if(isset($_REQUEST['dropdate'])){
				
				$qry.=' AND T.drop_date ="'.$_REQUEST['dropdate'].'"';

				}
				if(isset($_REQUEST['vehicles']) && $_REQUEST['vehicles']!=gINVALID){
					
					$qry.=' AND T.vehicle_id ="'.$_REQUEST['vehicles'].'"';
				
				}
				if(isset($_REQUEST['suppliers']) && $_REQUEST['suppliers']!=gINVALID){
					
					$qry.=' AND V.supplier_group_id ="'.$_REQUEST['suppliers'].'"';
					
				}
				if(isset($_REQUEST['drivers']) && $_REQUEST['drivers']!=gINVALID){
					
					$qry.=' AND T.driver_id ="'.$_REQUEST['drivers'].'"';
					
				}
				if(isset($_REQUEST['trip_status']) && $_REQUEST['trip_status']!=gINVALID){
					
					$qry.=' AND T.trip_status_id ="'.$_REQUEST['trip_status'].'"';
				
					
				}
				if(isset($_REQUEST['cgroups']) && $_REQUEST['cgroups']!=gINVALID){
					
					$qry.=' AND T.customer_group_id ="'.$_REQUEST['cgroups'].'"';
				
					
				}
				if(isset($_REQUEST['c_name']) && $_REQUEST['c_name']!=gINVALID){
					
					$qry.=' And C.name Like "%'.$_REQUEST['c_name'].'%"';
					
				}
		
					$qry.=' order by CONCAT(T.pick_up_date," ",T.pick_up_time) ASC';
			
			
			$data['trips']=$this->print_model->all_details($qry);
			if(empty($data['trips']) || $data['trips']==false){
				$data['msg']="No Results Found !";
			}
			$data['status_class']=array(TRIP_STATUS_PENDING=>'label-warning',TRIP_STATUS_CONFIRMED=>'label-success',TRIP_STATUS_CANCELLED=>'label-danger',TRIP_STATUS_CUSTOMER_CANCELLED=>'label-danger',TRIP_STATUS_ON_TRIP=>'label-primary',TRIP_STATUS_TRIP_COMPLETED=>'label-success',TRIP_STATUS_TRIP_PAYED=>'label-info',TRIP_STATUS_TRIP_BILLED=>'label-success');
			$data['trip_statuses']=$this->user_model->getArray('trip_statuses'); 
			
			$data['title']="Trips | ".PRODUCT_NAME;  
			$page='user-pages/print_listTrips';
		    $this->load_templates($page,$data);
	}

	}
	
	public function load_templates($page='',$data=''){
	if($this->session_check()==true) {
   	$this->load->view($page,$data);
   } 
	else{
			$this->notAuthorized();
		}

    }  
    
	   public function customersXL(){
	
		
				$qry='select * from customers where organisation_id='.$this->session->userdata('organisation_id');
				
				 if(isset($_REQUEST['cust_name'])&& $_REQUEST['cust_name']!=null){
				
				$qry.=' AND name Like "%'.$_REQUEST['cust_name'].'%"';
				
				}else if(isset($_REQUEST['cust_mobile'])&& $_REQUEST['cust_mobile']!=null){
				
				$qry.=' AND mobile Like "%'.$_REQUEST['cust_mobile'].'%"';

				}
				if(isset($_REQUEST['cust_type']) &&$_REQUEST['cust_type']!=gINVALID){
					
					$qry.=' AND customer_type_id ="'.$_REQUEST['cust_type'].'"';
				
				}
				if(isset($_REQUEST['cust_group']) && $_REQUEST['cust_group']!=gINVALID){
					
					$qry.=' AND customer_group_id ="'.$_REQUEST['cust_group'].'"';
					
				}
			
			
			$data['customers']=$this->print_model->all_details($qry);
			//print_r($data['customers']);exit;
			for($i=0;$i<count($data['customers']);$i++){
					$id=$data['customers'][$i]['id'];
					$availability=$this->customers_model->getCurrentStatuses($id);
					if($availability==false){
					$customer_statuses[$id]='NoBookings';
					$customer_trips[$id]=gINVALID;
					}else{
					$customer_statuses[$id]='OnTrip';
					$customer_trips[$id]=$availability[0]['id'];
					}
				}
				$data['customer_statuses']=$customer_statuses;
				$data['customer_trips']=$customer_trips;	
			if(empty($data['customers']) || $data['customers']==false){
				$data['result']="No Results Found !";
			}
			$tbl_arry=array('customer_types','customer_groups');
	
			for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
			}
			$data['title']="Customers | ".PRODUCT_NAME;  
			$page='user-pages/print_Customers';
		    $this->load_templates($page,$data);
	

	}
    
	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}
	public function notFound(){
		if($this->session_check()==true) {
		 $this->output->set_status_header('404'); 
		 $data['title']="Not Found";
      	 $page='not_found';
         $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
	}
	}
	
	public function tariffsXL(){
			
			$data['vehicle_models']=$this->print_model->getModels();
	
			
		
				$qry='SELECT TM.id,TM.title,TM. vehicle_ac_type_id,TM.minimum_kilometers,T.vehicle_model_id,T.rate FROM tariff_masters As TM LEFT JOIN tariffs As T ON TM.id=T.tariff_master_id where TM.organisation_id='.$this->session->userdata('organisation_id');
				
				 if(isset($_REQUEST['title'])&& $_REQUEST['title']!=null){
				
				$qry.=' AND TM.title Like "%'.$_REQUEST['title'].'%"';
				
				}
				if(isset($_REQUEST['trip_model']) &&$_REQUEST['trip_model']!=gINVALID){
					
					$qry.=' AND TM.trip_model_id ="'.$_REQUEST['trip_model'].'"';
				
				}
				if(isset($_REQUEST['ac_type']) && $_REQUEST['ac_type']!=gINVALID){
					
					$qry.=' AND TM.vehicle_ac_type_id ="'.$_REQUEST['ac_type'].'"';
					
				}
			
			
			$data['res']=$this->print_model->all_details($qry);
				$count=count($data['res']);
				$tm= $data['res'];
				//echo '<pre>';print_r($tm);echo '</pre>';exit;
				for($i=0;$i<$count;$i++){
				$values[$tm[$i]['id']][$tm[$i]['vehicle_ac_type_id']][$tm[$i]['vehicle_model_id']]['rate']=$tm[$i]['rate'];
				$values[$tm[$i]['id']][$tm[$i]['vehicle_ac_type_id']][$tm[$i]['vehicle_model_id']]['minimum_kilometers']=$tm[$i]['minimum_kilometers'];
				$values[$tm[$i]['id']]['title']=$tm[$i]['title'];
				$values[$tm[$i]['id']]['model']=$tm[$i]['vehicle_model_id'];
				}
				$data['tariffs']=$values;
		//echo '<pre>';	print_r($data['tariffs']);echo '</pre>';exit;
			if(empty($data['tariffs']) || $data['tariffs']==false){
				$data['result']="No Results Found !";
			}
		
			$data['title']="Tarrifs | ".PRODUCT_NAME;  
			$page='user-pages/print_tariffmaster';
		    $this->load_templates($page,$data);
	

	}

}
