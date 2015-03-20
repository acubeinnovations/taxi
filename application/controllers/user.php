<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
    parent::__construct();
    $this->load->helper('my_helper');
	$this->load->model('user_model');
	$this->load->model('trip_model');
	$this->load->model('driver_model');
	$this->load->model('customers_model');
	$this->load->model('trip_booking_model');
	$this->load->model('customers_model');
    $this->load->model('tarrif_model');
	$this->load->model('device_model');
	 $this->load->model('vehicle_model');
	no_cache();

	}
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	} 
	
	public function customer_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==CUSTOMER)) {
			return true;
		} else {
			return false;
		}
	}
	public function driver_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==DRIVER)) {
			return true;
		} else {
			return false;
		}
	} 
	
	public function v_owner_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==VEHICLE_OWNER)) {
			return true;
		} else {
			return false;
		}
	}
	public function index(){
		$param1=$this->uri->segment(3);
		$param2=$this->uri->segment(4);
		$param3=$this->uri->segment(5);
		$param4=$this->uri->segment(6);

		//unset search session condition before navigation
		/*if($param1!='trips'){
		$this->mysession->delete('condition');
		}*/
		//print_r($this->mysession->get('condition'));

        if($this->session_check()==true) {
		if($param1==''){
			$data['title']="Home | ".PRODUCT_NAME;    
       			 $page='user-pages/user_home';
			$this->load_templates($page,$data);
		}elseif($param1=='profile'){
		$this->profile();
		}elseif($param1=='changepassword'){
		$this->changePassword();
		}
		elseif($param1=='settings'){
		$this->settings();
		}elseif($param1=='trip-booking'){

		$this->ShowBookTrip($param2);
		}elseif($param1=='trips'){

		$this->Trips($param2);
		}elseif($param1=='customer'){

		$this->Customer($param2);

		}/*elseif($param1=='service'){
		$this->ShowService();
		}*/
		elseif($param1=='customers'){

		$this->Customers($param2);

		}elseif($param1=='device'){

		$this->Device($param2);

		}elseif($param1=='setup_dashboard'){

		$this->setup_dashboard();

		}elseif($param1=='getNotifications'){
			$this->getNotifications();
		}elseif($param1=='tripvouchers'){
			$this->tripVouchers($param2);
		}

		elseif($param1=='tarrif-masters'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif_masters($param1,$param2);
		}elseif($param1=='tarrif'&& ($param2== ''|| is_numeric($param2))){
		$this->tarrif($param1,$param2);

		}
		elseif($param1=='driver'){

		$this->ShowDriverView($param2);
		}elseif($param1=='list-driver'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverList($param1,$param2);
		}elseif($param1=='driver-profile'&&($param2== ''|| is_numeric($param2))){
		$this->ShowDriverProfile($param1,$param2);
		}
		//elseif($param1=='vehicle' && ($param2!= ''|| is_numeric($param2)||$param2== '') &&($param3== ''|| is_numeric($param3))){
		elseif($param1=='vehicle'){
		$this->ShowVehicleView($param2,$param3,$param4);
		}
		
		
		elseif($param1=='list-vehicle'&&($param2== ''|| is_numeric($param2)) && ($param3== ''|| is_numeric($param3))){
		$this->ShowVehicleList($param1,$param2,$param3);
		}else{
			$this->notFound();
		}
		}
		elseif($this->customer_session_check()==true){
			if($param1=='trip-booking'){
				$this->ShowBookTrip($param2);
			}elseif($param1=='customer') {
				$this->Customer($param2);
			}elseif($param1=='trips'){
				$this->Trips($param2);
			}else{
				$this->notAuthorized();
			}
		}elseif($this->driver_session_check()==true){
			if($param1=='driver-profile'&&($param2== ''|| is_numeric($param2))){
				$this->ShowDriverProfile($param1,$param2);
			}elseif($param1=='trips'){
				$this->Trips($param2);
			}elseif($param1=='tripvouchers'){
			$this->tripVouchers($param2);
			}
		}
		else{
			$this->notAuthorized();
		}
	
    }
	/*public function ShowService(){
	if($this->session_check()==true) {
	$data['title']="Service | ".PRODUCT_NAME;  
	$page='user-pages/addVehicles';
	$this->load_templates($page,$data);
	}else{
	$this->notAuthorized();
	}
	}*/
	public function settings() {
	if($this->session_check()==true) {
	$tbl_arry=array('vehicle_ownership_types','vehicle_types','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_beacon_light_options','vehicle_makes','vehicle_payment_percentages','driver_payment_percentages','vehicle_permit_types','languages','language_proficiency','driver_type','payment_type','customer_types','customer_groups','customer_registration_types','marital_statuses','bank_account_types','id_proof_types','trip_models','trip_statuses','booking_sources','trip_expense_type','vehicle_models','supplier_groups');
	
	for ($i=0;$i<count($tbl_arry);$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}//echo '<pre>';print_r($data);exit;
	$data['title']="Settings | ".PRODUCT_NAME;  
	$page='user-pages/settings';
	$this->load_templates($page,$data);
	}
	else{
			$this->notAuthorized();
		}
	}
	public function tarrif_masters($param1,$param2) {
	if($this->session_check()==true) {
	$tbl_arry=array('trip_models','vehicle_makes','vehicle_ac_types','vehicle_types');
	$this->load->model('user_model');
		for ($i=0;$i<4;$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	//print_r($result);exit;
	//echo $result['id'];exit;
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
	    $org_id=$this->session->userdata('organisation_id'); 
		$where_arry['organisation_id']=$org_id;
	if(isset($_REQUEST['search'])){
		$title = $this->input->post('search_title');
		$trip_model_id = $this->input->post('search_trip_model');
		$vehicle_ac_type_id = $this->input->post('search_ac_type');
	 if(($title=='')&& ($trip_model_id == -1) && ($vehicle_ac_type_id ==-1)){
	 $this->session->set_userdata('Required','Search with value !');
	 redirect(base_url().'organization/front-desk/tarrif-masters');
		}
		else {
		//show search results
		if($param2=='1' ){
				$param2='0';
			}
	if((isset($_REQUEST['search_title'])|| isset($_REQUEST['search_trip_model'])||isset($_REQUEST['search_ac_type']))&& isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	}
	
	if($_REQUEST['search_title']!=null){
	
	$like_arry=array('title'=> $_REQUEST['search_title']); 
	}
	if($_REQUEST['search_trip_model']>0){
	$where_arry['trip_model_id']=$_REQUEST['search_trip_model'];
	}
	if($_REQUEST['search_ac_type']>0){
	$where_arry['vehicle_ac_type_id']=$_REQUEST['search_ac_type'];
	}
	$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
	
	}
	}
	}
	    
		$tbl="tariff_masters";
		//to avoid session problem while session value sets from another page--starts--
		if(!is_null($this->mysession->get('condition'))){//print_r($where_arry);exit;
		$condition=$this->mysession->get('condition');
		if(isset($condition['where']['trip_model_id']) || isset($condition['where']['vehicle_ac_type_id'])||isset($condition['like']['title'])){ 
		if($condition['where']['trip_model_id']!=null){
		$where_arry['trip_model_id']=$condition['where']['trip_model_id'];
		}
		if($condition['where']['vehicle_ac_type_id']!=null){
		$where_arry['vehicle_ac_type_id']=$condition['where']['vehicle_ac_type_id'];
		}
		if($condition['like']['title']!=null){
		$like_arry['title']=$condition['like']['title'];
		}
		$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
		
		}else{ 
		$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
		}
		}
		//--ends--
		$baseurl=base_url().'organization/front-desk/tarrif-masters/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$data['title']="Tariff Masters | ".PRODUCT_NAME;  
	$page='user-pages/tarrif_master';
	$this->load_templates($page,$data);
	
	
	}
	else{
			$this->notAuthorized();
		}
	
	}
	public function tarrif($param1,$param2){
	if($this->session_check()==true) {
	$tbl_arry=array('vehicle_models','vehicle_ac_types');
	for ($i=0;$i<count($tbl_arry);$i++){
	$result=$this->user_model->getArray($tbl_arry[$i]);
	if($result!=false){
	$data[$tbl_arry[$i]]=$result;
	
	}
	else{
	$data[$tbl_arry[$i]]='';
	}
	}
	$data['customers']=$this->customers_model->getArray();
	$result=$this->user_model->getTarrif_masters();
	if($result!=false){
	$data['masters']=$result;
	}else
	{
	$data['masters']='';
	}	//start
		$condition='';
	    $per_page=10;
	    $org_id=$this->session->userdata('organisation_id');
		$where_arry['organisation_id']=$org_id;
	if(isset($_REQUEST['search'])){
		$fdate = $this->input->post('search_from_date');
		$tdate = $this->input->post('search_to_date');
		//valid date check
		/*if(!$this->date_check($fdate)){
	$this->mysession->set('Err_from_date','Invalid From Date for Tariff Search!');
	}
		if(!$this->date_check($tdate)){
	$this->mysession->set('Err_to_date','Invalid To Date for Tariff Search!');
	}*/
		if($fdate!=''&& $tdate==''){
		$tdate=date('Y-m-d');
		}
	 if(($fdate=='')&& ($tdate =='')){
	 $this->session->set_userdata('Date','Search with value');
	 redirect(base_url().'organization/front-desk/tarrif');
		}
		else {
		//show search results
		
	if((isset($_REQUEST['search_from_date'])|| isset($_REQUEST['search_to_date']))&& isset($_REQUEST['search'])){
	if($param2==''){
	$param2='0';
	} 
	if(($_REQUEST['search_from_date']>= $tdate)){
	$this->session->set_userdata('Date_err','Not a valid search');
	}
	if($_REQUEST['search_from_date']!=null){
	
	$where_arry['from_date >=']=$_REQUEST['search_from_date'];
	}
	if($_REQUEST['search_to_date']!=null){
	$where_arry['to_date <=']= $_REQUEST['search_to_date'];
	}
	/*else{
	$where_arry['to_date <=']= $tdate;
	}*/
	
	$this->mysession->set('condition',array("where"=>$where_arry));
	
	//print_r($where_arry);
	}
	}
	}	$where_arry['to_date >= ']=date("Y-m-d");
		//$where_arry['from_date >= ']=date("Y-m-d");
	    	//to avoid session problem while session value sets from another page--starts--
		if(!is_null($this->mysession->get('condition'))){ //print_r($where_arry);exit;
		$condition=$this->mysession->get('condition');
		if(isset($condition['where']['organisation_id'])){ 
		if($condition['where']['organisation_id']!=null){
		$where_arry['organisation_id']=$condition['where']['organisation_id'];
		}
		$this->mysession->set('condition',array("where"=>$where_arry));
		}else{ 
		$this->mysession->set('condition',array("where"=>$where_arry));
		}
		}
		//--ends--
		$tbl="tariffs";
		if(is_null($this->mysession->get('condition'))){ 
		$this->mysession->set('condition',array("where"=>$where_arry));
		}
		$baseurl=base_url().'organization/front-desk/tarrif/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];//echo '<pre>';//print_r($data['values']);exit;
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	//end
	//$data['allDetails']=$this->user_model->getAll_tarrifDetails();
	$data['title']="Tariff| ".PRODUCT_NAME; 
	$page='user-pages/tarrif';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}
	}

	public function Device($param2){
		if($this->session_check()==true) {
	
		$condition='';
	    $per_page=10;
	    $like_arry='';
		$data['s_imei']='';
		$data['s_sim_no']='';
	$where_arry['organisation_id']=$this->session->userdata('organisation_id');
		
	if((isset($_REQUEST['s_imei']) || isset($_REQUEST['s_sim_no'])) && isset($_REQUEST['search'])){
	if($param2==''){
	$param2=0;
	}
	
	if($_REQUEST['s_imei']!=null){
	$data['s_imei']=$_REQUEST['s_imei'];
	$like_arry['imei']=$_REQUEST['s_imei'];
	}
	if($_REQUEST['s_sim_no']!=null){
	$data['s_sim_no']=$_REQUEST['s_sim_no'];
	$like_arry['sim_no'] = $_REQUEST['s_sim_no'];
	}
	
	$this->mysession->set('condition',array("like"=>$like_arry));
	}
	/*if($this->mysession->get('condition')){
		$this->mysession->set('condition',array("like"=>$like_arry));
	}*/
	
	    
		$tbl="devices";
		if(is_null($this->mysession->get('condition'))){ 
		$this->mysession->set('condition',array("where"=>$where_arry));
		}
		$baseurl=base_url().'organization/front-desk/device/';
		$uriseg ='4';
		
		
		$p_res=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
		if($param2==''){
		$this->mysession->delete('condition');
		}
		
	$data['values']=$p_res['values'];
	if(empty($data['values'])){
	$data['result']="No Results Found !";
	}
	$data['page_links']=$p_res['page_links'];
	$devices=$this->device_model->getReg_Num();
	if($devices!=false){
	$data['devices']=$devices;
	}else{
	$data['devices']='';
	}
	$data['title']="Device | ".PRODUCT_NAME; 
	$page='user-pages/device';
	$this->load_templates($page,$data);
	
	}
	else{
			$this->notAuthorized();
		}



	}



	//trip booking from front-desk and customer
	public function ShowBookTrip($trip_id =''){
		if($this->session_check()==true || $this->customer_session_check()==true) {
		
			//-------------------new function call to build Trip data---------------------------------------------------------------------------------
			$data=$this->build_Trip_Data($trip_id);
			
			//set form arrays
			$tbl_arry=array('booking_sources','available_drivers','trip_models','drivers','vehicle_types','vehicle_models','vehicle_makes','vehicle_ac_types','vehicle_fuel_types','vehicle_seating_capacity','vehicle_beacon_light_options','languages','payment_type','customer_types','customer_groups');
			for ($i=0;$i<count($tbl_arry);$i++){
				$result=$this->user_model->getArray($tbl_arry[$i]);
				if($result!=false){
					$data[$tbl_arry[$i]]=$result;
				}
				else{
				$data[$tbl_arry[$i]]='';
				}
			}
			
			//$data['driver_availability']=$this->driver_model->GetDriverForTripBooking();
			$data['driver_availability']=$this->driver_model->getDriversArray();
			$trim=True;
			$data['available_vehicles']=$this->trip_booking_model->getVehiclesArray($condion='',$trim);
				

			//get notification and customer array
			list($data['notification'],$data['customers_array']) = $this->getNotifications();
			
			//set flag for new trip by 'customer' or 'organization'
			if($this->customer_session_check() == true){
				$data['booking_by'] = 'customer';
			}else{
				$data['booking_by'] = 'organization ';
			}
			

			//redirect(base_url().'organization/front-desk/trips');
			
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			$data['title']="Trip Booking | ".PRODUCT_NAME;  
			$page='user-pages/trip-booking';
			$this->load_templates($page,$data);
		
		}
		else{
			$this->notAuthorized();
		}
	}
	
	//--------------------------------------- new function to generate array of trip details---------------------------------------
	public function build_Trip_Data($trip_id){
	
	$return_data=array();
	
	$return_data['trip_id']			=	'';
	$return_data['guest_id']			=	gINVALID;
	$return_data['booking_source']		=	'';
	$return_data['source']				=	'';
	$return_data['customer']			=	'';
	$return_data['new_customer']		=	true;
	$return_data['email']				=	'';
	$return_data['mobile']				=	'';
	$return_data['advanced']			=	'';
	$return_data['advanced_vehicle']		=	'';
	$return_data['guest']				=	'';
	$return_data['email']				=	'';
	$return_data['customer_group']		=	'';
	$return_data['guestname']		=	'';
	$return_data['guestemail']			=	'';
	$return_data['guestmobile']		=	'';
	$return_data['remarks']			=	'';

	$return_data['trip_model']			=	'';		
	$return_data['no_of_passengers']	=	'';
	$return_data['pickupcity']			=	'';
	$return_data['pickupcitylat']			=	'';
	$return_data['pickupcitylng']			=	'';
	//$pickuparea			=	'';
	$return_data['pickuplandmark']		=	'';
	$return_data['viacity']			=	'';
	$return_data['viacitylat']			=	'';
	$return_data['viacitylng']			=	'';
	//$viaarea			=	'';
	$return_data['vialandmark']		=	'';
	$return_data['dropdownlocation']	=	'';
	$return_data['dropdownlocationlat']	=	'';
	$return_data['dropdownlocationlng']	=	'';
	//$dropdownarea		=	'';
	$return_data['dropdownlandmark']	=	'';
	$return_data['pickupdatepicker']	=	'';
	$return_data['dropdatepicker']		=	'';
	$return_data['pickuptimepicker']	=	'';
	$return_data['droptimepicker']	=	'';

	//$vehicle_type 				=	'';
	$return_data['vehicle_ac_type']			=	'';
	//$vehicle_make_id			=	'';
	$return_data['vehicle_model_id']			=	'';
	$return_data['beacon_light']				=	'';
	$return_data['beacon_light_radio']	   	    =	'';
	$return_data['pluck_card']				=	'';
	$return_data['uniform'] 					=	'';
	$return_data['seating_capacity'] 			=	'';
	$return_data['available_driver'] 			=	'';
	$return_data['language']				=	'';
	$return_data['tariff'] 					=	'';
	$return_data['available_vehicle']			=	'';

	$return_data['recurrent_yes']				=	'';
	$return_data['recurrent_continues']		=	'';
	$return_data['recurrent_alternatives'] 	=	'';
	$return_data['recurrent']				=	'';

	$return_data['reccurent_continues_pickupdatepicker'] 	=	'';
	$return_data['reccurent_continues_pickuptimepicker'] 	=	'';
	$return_data['reccurent_continues_dropdatepicker'] 	=	'';
	$return_data['reccurent_continues_droptimepicker'] 	=	'';


	$return_data['reccurent_alternatives_pickupdatepicker']	=	'';
	$return_data['reccurent_alternatives_pickuptimepicker']	=	'';
	$return_data['reccurent_alternatives_dropdatepicker']		=	'';
	$return_data['reccurent_alternatives_droptimepicker']		=	'';

	$return_data['alternative_pickupdatepicker']	= '';
	$return_data['alternative_pickuptimepicker']	= '';
	$return_data['alternative_droptimepicker']	= '';
	$return_data['alternative_dropdatepicker']	    = '';

	$return_data['customer_type']					= -1;
	$return_data['available_vehicles']='';
	$return_data['tariffs']='';
	
	
	
	//-----------------------------------------
	if($this->mysession->get('post')!=NULL){ //echo "<pre>";print_r($this->mysession->get('post'));echo "</pre>";exit;
		$data=$this->mysession->get('post');
	     if(isset($data['trip_id'])){			
		$return_data['trip_id']			=$data['trip_id'];
		}
		$return_data['recurrent_yes']		=$data['recurrent_yes'];
		$return_data['recurrent_continues']	=$data['recurrent_continues'];
		$return_data['recurrent_alternatives']	=$data['recurrent_alternatives'];
		$return_data['advanced']		=$data['advanced'];
	     
		$return_data['advanced_vehicle']	=$data['advanced_vehicle'];
		
		$return_data['customer_group']		=$data['customer_group'];
	     if(isset($data['guest_id'])){
		$return_data['guest_id']		=$data['guest_id'];
		}
		$return_data['guest']			=$data['guest'];
		$return_data['guestname']		=$data['guestname'];
		$return_data['guestemail']		=$data['guestemail'];
		$return_data['guestmobile']		=$data['guestmobile'];
		$return_data['customer']		=$data['customer'];
		$return_data['new_customer']		=$data['new_customer'];
		$return_data['email']			=$data['email'];
		$return_data['mobile']			=$data['mobile'];
	   
		$return_data['booking_source']		=$data['booking_source'];
				
		$return_data['source']			=$data['source'];
	
		$return_data['trip_model']		=$data['trip_model'];
		
		$return_data['no_of_passengers']	=$data['no_of_passengers'];
		$return_data['pickupcity']		=$data['pickupcity'];
		$return_data['pickupcitylat']		=$data['pickupcitylat'];
		$return_data['pickupcitylng']		=$data['pickupcitylng'];
		//$return_data['pickuparea']		=$data->pick_up_area;
		$return_data['pickuplandmark']		=$data['pickuplandmark'];
		$return_data['viacity']			=$data['viacity'];
		$return_data['viacitylat']		=$data['viacitylat'];
		$return_data['viacitylng']		=$data['viacitylng'];
		//$return_data['viaarea']		=$data['viaarea'];
		$return_data['vialandmark']		=$data['vialandmark'];
		$return_data['dropdownlocation']	=$data['dropdownlocation'];
		$return_data['dropdownlocationlat']	=$data['dropdownlocationlat'];
		$return_data['dropdownlocationlng']	=$data['dropdownlocationlng'];
		//$return_data['dropdownarea']		=$data['dropdownarea'];
		$return_data['dropdownlandmark']	=$data['dropdownlandmark'];
		$return_data['pickupdatepicker']	=$data['pickupdatepicker'];
		$return_data['dropdatepicker']		=$data['dropdatepicker'];
		$return_data['pickuptimepicker']	=$data['pickuptimepicker'];
		$return_data['droptimepicker']		=$data['droptimepicker'];
	     
		$return_data['vehicle_ac_type']		=$data['vehicle_ac_type'];
		
		//$return_data['vehicle_make']		=$data->vehicle_make_id;
	   
		$return_data['vehicle_model_id']		=$data['vehicle_model'];
		
		$return_data['remarks']			=$data['remarks'];
		$return_data['recurrent_yes']		=$data['recurrent_yes'];
		$return_data['beacon_light']		=$data['beacon_light'];
		$return_data['beacon_light_radio']	=$data['beacon_light_radio'];
		//$return_data['beacon_light_id'] = '';
		$return_data['pluck_card']		=$data['pluck_card'];
		$return_data['uniform']			=$data['uniform'];
	    
		$return_data['seating_capacity']	=$data['seating_capacity'];
		
		$return_data['language']		=$data['language'];
		
		$return_data['tariff']			=$data['tariff'];
		$return_data['tariffs']			='';
		$return_data['available_vehicles']	='';
		$return_data['available_driver']	=$data['driver_id'];
		$return_data['available_vehicle']	=$data['vehicle_id'];
		
	     if($data['recurrent_yes']==TRUE){
		if($data['recurrent_continues']==TRUE){
			$return_data['reccurent_continues_pickupdatepicker'] 	=	$data['reccurent_continues_pickupdatepicker'];
			$return_data['reccurent_continues_pickuptimepicker'] 	=	$data['reccurent_continues_pickuptimepicker'];
			$return_data['reccurent_continues_dropdatepicker'] 	=	$data['reccurent_continues_dropdatepicker'];
			$return_data['reccurent_continues_droptimepicker'] 	=	$data['reccurent_continues_droptimepicker'];
			$return_data['recurrent']								=	$data['recurrent'];
		}else if($data['recurrent_alternatives']==TRUE){
			$return_data['reccurent_alternatives_pickupdatepicker']	=	$data['reccurent_alternatives_pickupdatepicker'];
			$return_data['reccurent_alternatives_pickuptimepicker']	=	$data['reccurent_alternatives_pickuptimepicker'];
			$return_data['reccurent_alternatives_dropdatepicker']		=	$data['reccurent_alternatives_dropdatepicker'];
			$return_data['reccurent_alternatives_droptimepicker']		=	$data['reccurent_alternatives_droptimepicker'];
			$return_data['recurrent']								=	$data['recurrent'];
		}
	    }
		$return_data['customer_type']		=$data['customer_type'];
	
	$this->mysession->delete('post');
	}
	elseif($trip_id>0){
		
		$conditon = array('id'=>$trip_id,'organisation_id'=>$this->session->userdata('organisation_id'));
		$data=$this->trip_booking_model->getDetails($conditon); 
		$data=$data[0];
		if($data->trip_status_id==TRIP_STATUS_PENDING || ($data->trip_status_id==TRIP_STATUS_CONFIRMED && $this->customer_session_check() != true )){
		
					$return_data['trip_id']=$data->id;
					$return_data['recurrent_continues']='';
					$return_data['recurrent_alternatives']='';
				if(isset($data->customer_group_id) && $data->customer_group_id > 0){
					$return_data['advanced']=TRUE;
					$return_data['customer_group']=$data->customer_group_id;
				}else{
					$return_data['advanced']='';
					$return_data['customer_group']='';
				}
				
				if(isset($data->guest_id) && $data->guest_id > 0){
					$dbdata=array('id'=>$data->guest_id);
					$guest 	=	$this->customers_model->getCustomerDetails($dbdata);
					$guest 	=$guest[0];
					$return_data['guest_id']	= $data->guest_id;
					$return_data['guest']	=	TRUE;
					$return_data['guestname']=	$guest['name'];
					$return_data['guestemail']=$guest['email'];
					$return_data['guestmobile']=$guest['mobile'];
				}else{
					$return_data['guest']='';
					$return_data['guestname']='';
					$return_data['guestemail']='';
					$return_data['guestmobile']='';
				}
				
				$dbdata=array('id'=>$data->customer_id);	
				$customer 	=	$this->customers_model->getCustomerDetails($dbdata);
				if(count($customer)>0){
					$customer=$customer[0];
					$return_data['customer']				=	$customer['name'];
					$return_data['new_customer']			=	'false';
					$return_data['email']					=	$customer['email'];
					$return_data['mobile']				=	$customer['mobile'];
					
					$this->session->set_userdata('customer_id',$data->customer_id);
					$this->session->set_userdata('customer_name',$customer['name']);
					$this->session->set_userdata('customer_email',$customer['email']);
					$this->session->set_userdata('customer_mobile',$customer['mobile']);
				}else{
	
					$return_data['customer']				=	'';
					$return_data['new_customer']			=	'true';
					$return_data['email']					=	'';
					$return_data['mobile']				=	'';

				}
				$return_data['booking_source']			=	$data->booking_source_id;	
				$return_data['source']				=	$data->source;
				$return_data['trip_model']				=	$data->trip_model_id;
				$return_data['no_of_passengers']			=	$data->no_of_passengers;
				$return_data['pickupcity']				=	$data->pick_up_city;
				$return_data['pickupcitylat']				=	$data->pick_up_lat;
				$return_data['pickupcitylng']				=	$data->pick_up_lng;
				$return_data['pickuparea']				=	$data->pick_up_area;
				$return_data['pickuplandmark']			=	$data->pick_up_landmark;
				$return_data['viacity']				=	$data->via_city;
				$return_data['viacitylat']				=	$data->via_lat;
				$return_data['viacitylng']				=	$data->via_lng;
				$return_data['viaarea']				=	$data->via_area;
				$return_data['vialandmark']				=	$data->via_landmark;
				$return_data['dropdownlocation']			=	$data->drop_city;
				$return_data['dropdownlocationlat']			=	$data->drop_lat;
				$return_data['dropdownlocationlng']			=	$data->drop_lng;
				$return_data['dropdownarea']				=	$data->drop_area;
				$return_data['dropdownlandmark']			=	$data->drop_landmark;
				$return_data['pickupdatepicker']			=	$data->pick_up_date;
				$return_data['dropdatepicker']			=	$data->drop_date;
				$return_data['pickuptimepicker']			=	$data->pick_up_time;
				$return_data['droptimepicker']			=	$data->drop_time;
				$pickupdatetime					= $data->pick_up_date.' '.$data->pick_up_time;
				$dropdatetime					= $data->drop_date.' '.$data->drop_time;
				$return_data['vehicle_type']				=	$data->vehicle_type_id;
				$return_data['vehicle_ac_type']			=	$data->vehicle_ac_type_id;
				$return_data['vehicle_make']				=	$data->vehicle_make_id;
				$return_data['vehicle_model_id']				=	$data->vehicle_model_id;
				$return_data['remarks']				=	$data->remarks;
				$return_data['recurrent_yes']				= 	'';
				//$data1['seating_capacity']		=	$result->vehicle_seating_capacity_id;
				//$data1['language']				=	$result->driver_language_id;
				$return_data['tariff']				=	$data->tariff_id;
				$return_data['available_vehicle']		=	$data->vehicle_id;
				$return_data['available_vehicles']='';
				$return_data['tariffs']='';
				if($return_data['available_vehicle']>0){
					$driver_id = $this->trip_booking_model->getDriver($return_data['available_vehicle']);
				}else{
					$driver_id = gINVALID;
				}
	
				$return_data['available_driver']		=	$data->driver_id;
	
				if($driver_id==$return_data['available_driver']){
					$return_data['advanced_vehicle']='';
				}else{
					$return_data['advanced_vehicle']=TRUE;
				}
				
				if(isset($data->vehicle_beacon_light_option_id) && $data->vehicle_beacon_light_option_id > 0){
					$return_data['beacon_light']=TRUE;
					$return_data['advanced_vehicle']=TRUE;
					if($data->vehicle_beacon_light_option_id==BEACON_LIGHT_RED){

						$return_data['beacon_light_radio']='red';
					
					}else{
	
						$return_data['beacon_light_radio']='blue';
			
					}
				}else{
		
					$return_data['beacon_light']='';
					$return_data['beacon_light_radio']='';
					$return_data['beacon_light_id'] = '';

				}
				
				if(isset($data->pluckcard) && $data->pluckcard==true){
					$return_data['pluck_card']=TRUE;
					$return_data['advanced_vehicle']=TRUE;
				}else{
					$return_data['pluck_card']='';
		
				}
	
				if(isset($data->uniform) && $data->uniform==true){
					$return_data['uniform']=TRUE;
					$return_data['advanced_vehicle']=TRUE;
				}else{
					$return_data['uniform']='';
		
				}
	
				if(isset($data->vehicle_seating_capacity_id) && $data->vehicle_seating_capacity_id > 0){
					$return_data['advanced_vehicle']=TRUE;
					$return_data['seating_capacity']=$data->vehicle_seating_capacity_id;
				}else{
		
					$return_data['seating_capacity']='';
				}
	
				if(isset($data->driver_language_id) && $data->driver_language_id > 0){
					$return_data['advanced_vehicle']=TRUE;
					$return_data['language']=$data->driver_language_id;
				}else{
		
					$return_data['language']='';
				}
				$this->session->set_userdata('driver_id',$data->driver_id);
				$return_data['customer_type']			=	$data->customer_type_id;
				
	
	
	
			}
	
		}else{
			
			if($this->session->userdata('customer')){
				$customer=$this->session->userdata('customer');
				$return_data['customer']	= $this->session->userdata('customer')->name;
				$return_data['new_customer']		= 'false';
				$return_data['email']			= $this->session->userdata('customer')->email;
				$return_data['mobile']		= $this->session->userdata('customer')->mobile;
				
				$this->session->set_userdata('customer_id',$this->session->userdata('customer')->id);
				$this->session->set_userdata('customer_name',$this->session->userdata('customer')->name);
				$this->session->set_userdata('customer_email',$this->session->userdata('customer')->email);
				$this->session->set_userdata('customer_mobile',$this->session->userdata('customer')->mobile);
			}else{
				$this->session->set_userdata('customer_id','');
				$this->session->set_userdata('customer_name','');
				$this->session->set_userdata('customer_email','');
				$this->session->set_userdata('customer_mobile','');
			}

		
		
		}
		
		return $return_data;
	}
	
	
	//------------------------------------------------------------------------------------------------------------------------------
	
	
	

	public function getAvailableVehicle($available){
	
	
	return $this->trip_booking_model->selectAvailableVehicles($available);

	}
	public function tariffSelecter($data){
	
	return $this->tarrif_model->selectAvailableTariff($data);

	

	}

	//-----------------Show trip list-----------------------
	public function Trips($param2){
		if($this->session_check()==true|| $this->customer_session_check()==true || $this->driver_session_check()==true) 
		{
		
			//pagination first page link setup
			if($param2=='1'){ $param2='0'; }
			if($param2==''){$param2='0'; }
			
					
	
			$tbl	= "trips";$like_arry = $where_arry = array();
			$baseurl = base_url().'organization/front-desk/trips/';
			$per_page = 30;
			$data['slno_per_page'] = 10;
			$uriseg ='4';
			$data['urlseg'] = 4;

			$tdate = date('Y-m-d');
			$data['trip_pick_date'] = $where_arry['trip_pick_date'] 	= '';
			$data['trip_drop_date'] = $where_arry['trip_drop_date'] 	= '';
			$data['vehicles'] 	= $where_arry['vehicle_id'] 		= '';
			$data['cgroups'] 	= $where_arry['customer_group_id'] 	= '';
			$data['drivers']	= $where_arry['driver_id'] 		= '';
			$data['customer']	= $like_arry['customer_name'] 		= '';
			$data['trip_status_id']	= $where_arry['trip_status_id'] 	= '';
			
			//submit search and set condition in session
			
			if(isset($_REQUEST['trip_search'])){
				
				$where_arry['trip_pick_date']=$_REQUEST['trip_pick_date'];
				$where_arry['trip_drop_date']=$_REQUEST['trip_drop_date'];
				$where_arry['vehicle_id']=$_REQUEST['vehicles'];
				$where_arry['driver_id']=$_REQUEST['drivers'];
				$where_arry['trip_status_id']=$_REQUEST['trip_status_id'];
				$where_arry['customer_group_id']=$_REQUEST['cgroups'];
				$like_arry['customer_name']=$_REQUEST['customer'];
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry,"trips"=>true));				
			}

			//get session condition
			$condition = false;
			if($this->mysession->get('condition')!=''){

				$condition=$this->mysession->get('condition');//print_r($condition);exit;
				if(!isset($condition['trips'])){
					$this->mysession->delete('condition');
					//default conditions
					
					$condition['where']['trip_pick_date']='';
					$condition['where']['trip_drop_date']='';
					$condition['where']['vehicle_id']='';
					$condition['where']['driver_id']='';
					$condition['where']['trip_status_id']='';
					$condition['where']['customer_group_id']='';
					$condition['like']['customer_name']='';
					
				}else{
					$data['trip_pick_date']=$condition['where']['trip_pick_date'];
					$data['trip_drop_date']=$condition['where']['trip_drop_date'];
					$data['vehicle_id']=$condition['where']['vehicle_id'];
					$data['driver_id']=$condition['where']['driver_id'];
					$data['trip_status_id']=$condition['where']['trip_status_id'];
					$data['customer_group_id']=$condition['where']['customer_group_id'];
					$data['customer_name']=$condition['like']['customer_name'];
				}
				
			}

			//get sql for trips and make paginated data
			$tripsQRY = $this->trip_model->get_sql_for_trips($condition);
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$tripsQRY);
			if($param2=='')$this->mysession->delete('condition');
			
			
			$tbl_arry=array('trip_statuses','customer_groups','payment_type','driver_payment_percentages','vehicle_payment_percentages');
			for ($i=0;$i<count($tbl_arry);$i++){
				$result=$this->user_model->getArray($tbl_arry[$i]);
				if($result!=false){
				$data[$tbl_arry[$i]]=$result;
				}
				else{
				$data[$tbl_arry[$i]]='';
				}
			}
			
			
			$this->load->model('account_model');
			$data['taxes']=$this->account_model->getTaxArray();
			//print_r($data['taxes']);exit;

			$data['vehicles']=$this->trip_booking_model->getVehiclesArray();
			$data['drivers']=$this->driver_model->getDriversArray();  
			
			
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];
			if(empty($data['trips'])){
				$data['result']="No Results Found !";
					}
			//echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			//$data['trips']=$this->trip_booking_model->getDetails($conditon='');echo '<pre>';print_r($data['trips']);echo '</pre>';exit;
			$data['status_class']=array(TRIP_STATUS_PENDING=>'label-warning',TRIP_STATUS_CONFIRMED=>'label-success',TRIP_STATUS_CANCELLED=>'label-danger',TRIP_STATUS_CUSTOMER_CANCELLED=>'label-danger',TRIP_STATUS_ON_TRIP=>'label-primary',TRIP_STATUS_TRIP_COMPLETED=>'label-success',TRIP_STATUS_TRIP_PAYED=>'label-info',TRIP_STATUS_TRIP_BILLED=>'label-success');
			$data['trip_statuses']=$this->user_model->getArray('trip_statuses'); 
			$data['customers']=$this->customers_model->getArray();
			$data['title']="Trips | ".PRODUCT_NAME;  
			$page='user-pages/trips';
			
			//input hide class if needed
			$data['input_class'] = $this->trip_filter_inputs();
			$data['trip_action_allowed'] = $this->trip_action_allowed();
			
		    	$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}	
	
	//show or hide input based on session check, for trip list page inputs
	function trip_filter_inputs(){
		$inputs= array('trip_pick_date'=>'','trip_drop_date'=>'','vehicles' => '',
						'drivers' => '','trip_status_id' => '','cgroups' => '','customer' => '','hide_edit'=> '');
		if($this->session->userdata('type')==CUSTOMER){
			$inputs['vehicles']=$inputs['drivers']=$inputs['cgroups']=$inputs['customer']=$inputs['hide_edit']=' hide-me';
		}else if($this->session->userdata('type')==DRIVER){
			$inputs['vehicles']=$inputs['drivers']=$inputs['customer']=' hide-me';
		}
		return $inputs;
				
	}

	//actions for trip list table filter by user type in session
	function trip_action_allowed(){
				
		if($this->session->userdata('type')==CUSTOMER){
			$actions = array('edit');
		}elseif($this->session->userdata('type')==DRIVER){
			$actions = array('new_voucher');
		}else{
			$actions = array('edit','complete','new_voucher','edit_voucher','proposal','send_sms');
		}
		return $actions;
	}

	public function Customer($param2=''){
		if($this->session_check()==true  || ($param2==$this->session->userdata['customer']->id && $this->customer_session_check()==true)) {
	
		if($param2!=''){
				//$condition=array('id'=>$param2);
				//$result=$this->customers_model->getCustomerDetails($condition);
				$result=$this->customers_model->getCustomerUser($param2);
				$pagedata['id']=$result[0]['id'];
				$pagedata['name']=$result[0]['name'];
				$pagedata['email']=$result[0]['email'];
				$pagedata['dob']=$result[0]['dob'];
				$pagedata['mobile']=$result[0]['mobile'];
				$pagedata['address']=$result[0]['address'];
				$pagedata['customer_group_id']=$result[0]['customer_group_id'];
				$pagedata['customer_type_id']=$result[0]['customer_type_id'];
				$pagedata['username']=$result[0]['username'];
				$pagedata['password']=$result[0]['password'];
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
			$data['title']="Customer | ".PRODUCT_NAME;
			if(isset($pagedata)){ 
				$data['values']=$pagedata;
			}else{
				$data['values']=false;
			}
			$active_tab = 'c_tab';//default profile tab
			if($param2!=''){
			$tdate=date('Y-m-d');
			$date=explode("-",$tdate);
			$fdate=$date[0].'-'.$date[1].'-01';
			$todate=$date[0].'-'.$date[1].'-31';
			if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['cdate_search'])){ 
			if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
			$fdate=$date[0].'-'.$date[1].'-01';
			$todate=$date[0].'-'.$date[1].'-31';
			} else{
			$fdate=$_REQUEST['from_pick_date'];
			$todate=$_REQUEST['to_pick_date']; }
			//$data['trip_tab']='active';
			$active_tab = 't_tab';//trip tab
			}
			$data['trips']=$this->trip_booking_model->getCustomerVouchers($param2,$fdate,$todate);
			}
			$data['tabs'] = $this->set_up_customer_tabs($active_tab,$param2);
			$data['tariffs'] = $this->customers_model->getCustomerTariffs($param2);//print_r($data['tariffs']);exit;
			if($this->session->userdata('type') == CUSTOMER){
				$data['edit_profile'] = false;
			}else{
				$data['edit_profile'] = true;
			}
			$data['c_id']=$param2;
			$page='user-pages/customer';
		    $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}

	}	

	public function load_templates($page='',$data=''){
	if($this->session_check()==true|| $this->customer_session_check()==true || $this->driver_session_check()==true) {
		$this->load->view('admin-templates/header',$data);
		$this->load->view('admin-templates/nav');
		$this->load->view($page,$data);
		$this->load->view('admin-templates/footer');
		}
	else{
			$this->notAuthorized();
		}
	}

	public function	Customers($param2){
		if($this->session_check()==true) {
			if($this->mysession->get('condition')!=null){
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['trip_pick_date'])){
					$this->mysession->delete('condition');
				}
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
			
			$tbl="customers";
			$baseurl=base_url().'organization/front-desk/customers/';
			$per_page=10;
			$uriseg ='4';
			
			$where_arry['organisation_id']=$this->session->userdata('organisation_id');
			$like_arry['organisation_id']=$this->session->userdata('organisation_id');

			if((isset($_REQUEST['customer'])|| isset($_REQUEST['mobile']) || isset($_REQUEST['customer_type_id']))&& isset($_REQUEST['customer_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['customer']!=null){
					$data['customer']=$_REQUEST['customer'];
					$like_arry['name']=$_REQUEST['customer'];
				}
				if($_REQUEST['mobile']!=null){
					$data['mobile']=$_REQUEST['mobile'];
					$like_arry['mobile']=$_REQUEST['mobile'];
				}
				if($_REQUEST['customer_type_id']!=null && $_REQUEST['customer_type_id']!=gINVALID){
				$data['customer_type_id']=$_REQUEST['customer_type_id'];
				$where_arry['customer_type_id']=$_REQUEST['customer_type_id'];
				}
				if($_REQUEST['customer_group_id']!=null && $_REQUEST['customer_group_id']!=gINVALID){
				$data['customer_group_id']=$_REQUEST['customer_group_id'];
				$where_arry['customer_group_id']=$_REQUEST['customer_group_id'];
				}
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry,"customers"=>true));
			}
			if(is_null($this->mysession->get('condition'))){
			$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
					
			$paginations=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['customers']=$paginations['values'];	
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
				}//print_r($customer_statuses);print_r($customer_trips);exit;
				if(isset($customer_statuses) && count($customer_statuses)>0){
				$data['customer_statuses']=$customer_statuses;
				}	
				if(isset($customer_trips) && count($customer_trips)>0){
				$data['customer_trips']=$customer_trips;
				}		
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}
			$data['title']="Customers | ".PRODUCT_NAME;  
			$page='user-pages/customers';
		    $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
}
	
public function profile() {
	   if($this->session_check()==true) {
		
		$dbdata = '';
              if(isset($_REQUEST['user-profile-update'])){ 
			$dbdata['first_name'] = $this->input->post('firstname');
			$dbdata['last_name']  = $this->input->post('lastname');
		    $dbdata['email'] 	   = $this->input->post('email');
			$hmail 	   = $this->input->post('hmail');
			$dbdata['phone'] 	   = $this->input->post('phone');
			$hphone 	   = $this->input->post('hphone');
		    $dbdata['address']   = $this->input->post('address');
			$dbdata['username']   = $this->input->post('husername');
			$fadata['firstname'] = $this->input->post('firstname');
			$fadata['lastname']  = $this->input->post('lastname');
		    $fadata['email'] 	   = $this->input->post('email');
			$fadata['phone'] 	   = $this->input->post('phone');
			$fadata['fa_account']   = $this->input->post('fa_account');
			//$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[20]|xss_clean');
			$this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|xss_clean');
			if($dbdata['email'] == $hmail){
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email');
		}else{
			$this->form_validation->set_rules('email','Mail','trim|required|valid_email|is_unique[users.email]');
		}
			if($dbdata['phone'] == $hphone){
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
		}else{
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean||is_unique[users.phone]');
		}
			
			$this->form_validation->set_rules('address','Address','trim|required|min_length[10]|xss_clean');
			//$dbdata['username']  = $this->input->post('username');
		   	
			
			if($this->form_validation->run() != False) {
				$val    		   = $this->user_model->updateProfile($dbdata);
				if($val==true){
				//fa user edit
					$this->load->model('account_model');
					$this->account_model->edit_user($fadata);
                   
				redirect(base_url().'organization/front-desk');
				}
			}else{
				$this->show_profile($dbdata);
			}
		}else{
			
			$this->show_profile($dbdata);

		}
	   }	
		else{
			$this->notAuthorized();
		}
	}
	public function show_profile($data) {
		  if($this->session_check()==true) {
			if($data == ''){
			$data['values']=$this->user_model->getProfile();
			}else{
			$data['postvalues']=$data;
			}
			$data['title']="Profile | ".PRODUCT_NAME;  
			$page='user-pages/profile';
		    $this->load_templates($page,$data);
		    }
			else{
				$this->notAuthorized();
			}
	}
	public function changePassword() {
	if($this->session_check()==true) {
	   $this->load->model('user_model');
	   $data['old_password'] = 	'';
		$data['password']	  = 	'';
		$data['cpassword'] 	  = 	'';
       if(isset($_REQUEST['user-password-update'])){ 
			$this->form_validation->set_rules('old_password','Current Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');
			$data['old_password'] = trim($this->input->post('old_password'));
			$data['password'] = trim($this->input->post('password'));
			$data['cpassword'] = trim($this->input->post('cpassword'));
			if($this->form_validation->run() != False) {
				$dbdata['password']  	= md5($this->input->post('password'));
				$dbdata['old_password'] = md5(trim($this->input->post('old_password')));
				$val    			    = $this->user_model->changePassword($dbdata);
				if($val == true) {				
					redirect(base_url().'organization/front-desk');
				}else{
					$this->show_change_password($data);
				}
			} else {
				
					$this->show_change_password($data);
			}
		} else {
			
					$this->show_change_password($data);
		}
		           }
		else{
			$this->notAuthorized();
		}
	}
	
	/*customer page tab setting ,
	1.first parameter is tab identifier you want set active tab, default profile
		tabs are c_tab=>profile,t_tab=>trip , p_tab=>payments and a_tab=>accounts 
	2.second parameter is the customer id */
	function set_up_customer_tabs($tab_active='c_tab',$customer_id=''){
			
		$tabs['c_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Profile',
						'content_class'=>'tab-pane');

		if($customer_id!='' && $customer_id > 0){

			$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Trip',
						'content_class'=>'tab-pane');
			if(!$this->session->userdata('customer')){
				$tabs['p_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Payments',
						'content_class'=>'tab-pane');
					
			}
			$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Accounts',
						'content_class'=>'tab-pane');
			$tabs['tr_tab'] = array('class'=>'','tab_id'=>'tab_5','text'=>'Tariffs',
						'content_class'=>'tab-pane');
		}

		if (array_key_exists($tab_active, $tabs)) {
			$tabs[$tab_active]['class'] = 'active';
			$tabs[$tab_active]['content_class'] = 'tab-pane active';
		}else{
			$tabs['c_tab']['class'] = 'active';
			$tabs['c_tab']['content_class'] = 'tab-pane active';
		}


		return $tabs;
	}
	public function show_change_password($data) {
		if($this->session_check()==true) {
				$data['title']="Change Password | ".PRODUCT_NAME;  
				$page='user-pages/change_password';
				 $this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	public function ShowDriverView($param2) {
		if($this->session_check()==true) {
			$data['select']=$this->select_Box_Values();

			//trip details
			if($param2!=''){
				$data['trips']=$this->trip_booking_model->getDriverVouchers($param2);
			}
			
			
			$data['title']="Driver Details | ".PRODUCT_NAME;  
			$page='user-pages/addDrivers';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	
	public function ShowDriverList($param1,$param2) { 
		if($this->session_check()==true) { 
			/*if($this->mysession->get('condition')!=null){
			$condition=$this->mysession->get('condition');
			if(isset($condition['like']['name']) || isset($condition['like']['district']) ){
			}
			else{
			$this->mysession->delete('condition');
			}
			}*/
			$data['driver_name']='';
			$data['driver_city']='';
			$data['status_id']='';

			$condition='';
			$per_page=10;
			$like_arry=''; 
			$org_id=$this->session->userdata('organisation_id');
			$where_arry['organisation_id']=$org_id;
			$qry='SELECT D.id,D.name
		FROM drivers D where D.organisation_id = '.$this->session->userdata('organisation_id');
					if($param2=='1' ){
						$param2='0';
						}
			if($param2==''){
				$this->mysession->delete('condition');

			}
			//for search
			if(isset($_REQUEST['search'])){
				if($param2==''){
					$param2='0';
				}
				if($_REQUEST['status']!=null && $_REQUEST['status']!=-1 ){
					$data['status_id']=$_REQUEST['status'];
					$date_now=date('Y-m-d H:i:s');
					$where_arry['dstatus']=$_REQUEST['status'];
					$qry=' SELECT D.id
						FROM drivers AS D  LEFT JOIN trips as T ON D.id= T.driver_id where D.organisation_id = '.$this->session->userdata('organisation_id').'
						AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
						AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
						AND T.organisation_id = '.$this->session->userdata('organisation_id').'
						AND T.driver_id = D.id
						AND T.trip_status_id = '.TRIP_STATUS_CONFIRMED;
				}
				if($_REQUEST['status']!=null && $_REQUEST['status']==0 ){
					$data['status_id']=$_REQUEST['status'];
					$date_now=date('Y-m-d H:i:s');
					$where_arry['dstatus']=$_REQUEST['status'];
					$qry='SELECT D.id FROM drivers D WHERE  D.organisation_id = "'.$this->session->userdata('organisation_id').'" AND D.id NOT IN ('.$qry.')';
				}
				if($_REQUEST['driver_name']!=null){
					$data['driver_name']=$_REQUEST['driver_name'];
					$qry.=' AND D.name LIKE "%'.$_REQUEST['driver_name'].'%" ';
					$like_arry['name']=$_REQUEST['driver_name'];
				}
	
				if($_REQUEST['driver_city']!=null){
					$data['driver_city']=$_REQUEST['driver_city'];
					$like_arry['district']=$_REQUEST['driver_city'];
					$qry.=' AND D.district LIKE "%'.$_REQUEST['driver_city'].'%" ';
				}

				if(isset($where_arry) || isset($like_arry)){
					$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				}

				//$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));

			
			} 
			else if($this->mysession->get('condition')!=''){ 
					$condition=$this->mysession->get('condition');
					if(isset($condition['where']['dstatus']) || isset($condition['like']['name']) || isset($condition['like']['district']) ){
			
					if(isset($condition['where']['dstatus']) && $condition['where']['dstatus']!=-1 ){
					$data['status_id']=$condition['where']['dstatus'];
					$date_now=date('Y-m-d H:i:s');
					//$where_arry['status']=$_REQUEST['status'];
				$qry=' SELECT D.id
				FROM drivers AS D  LEFT JOIN trips as T ON D.id= T.driver_id where D.organisation_id = '.$this->session->userdata('organisation_id').'
				AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
				AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
				AND T.organisation_id = '.$this->session->userdata('organisation_id').'
				AND T.driver_id = D.id
				AND T.trip_status_id = '.TRIP_STATUS_CONFIRMED;
			
					}	
					if(isset($condition['where']['dstatus'])&& $condition['where']['dstatus']==0){
					$data['status_id']=$condition['where']['dstatus'];
					$date_now=date('Y-m-d H:i:s');
					//$where_arry['status_id']=$_REQUEST['status'];
					$qry='SELECT D.id FROM drivers D WHERE  D.organisation_id = "2" AND D.id NOT IN ('.$qry.')';
			
					}
					if(isset($condition['like']['name'])){
			
					$data['driver_name']=$condition['like']['name'];
					$qry.=' AND D.name LIKE "%'.$condition['like']['name'].'%" ';
					}
					if(isset($condition['like']['district'])){
					$data['driver_city']=$condition['like']['district'];
					$qry.=' AND D.district LIKE "%'.$condition['like']['district'].'%" ';
					}
			
			
				}
			}
			
			/*if(is_null($this->mysession->get('condition'))){
			$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
			}*/
			//$tbl="drivers";
			$baseurl=base_url().'organization/front-desk/list-driver/';
			$uriseg ='4';
				//echo $qry;//exit;
			   $p_res=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);

			$data['values']=$p_res['values'];
	
			//print_r($data['values']);exit;
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
			if(empty($data['values'])){
						$data['result']="No Results Found !";
						}
	
			for ($i=0;$i<count($data['values']);$i++){
			$driverid=$data['values'][$i]['id'];
			$driver_details[$driverid]=$this->user_model->getVehicleDetails($driverid);
	
			$drivers=$this->vehicle_model->getDriversInfo();
			if($drivers!=false){
			$data['drivers']=$drivers;// print_r($data['drivers']);exit;
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

			$data['trip_info']=$this->user_model->getTotTripInfo();

			$data['page_links']=$p_res['page_links']; 
			$data['title']='List Driver| '.PRODUCT_NAME;
			$page='user-pages/driverList';
			$this->load_templates($page,$data);	
	
	
		}
		else{
			$this->notAuthorized();
		}
	}
		
	public function ShowDriverProfile($param1,$param2){ 
		if($this->session_check()==true || $this->driver_session_check()==true) { 
			$data['mode']=$param2;
			$DriverSalary = 0;
			if($param2!=null&& $param2!=gINVALID){
				$org_id=$this->session->userdata('organisation_id');
				$arry=array('id'=>$param2,'organisation_id'=>$org_id);
				//$data['result']=$this->user_model->getDriverDetails($arry);
				$data['result']=$this->user_model->getDriverUser($param2); 
				//echo "<pre>";print_r($data['result']);echo "</pre>";exit;
				$DriverSalary = @$data['result']['salary'];
			}   

			$tdate=date('Y-m-d');
			$date=explode("-",$tdate);
			$fdate=$date[0].'-'.$date[1].'-01';
			$todate=$date[0].'-'.$date[1].'-31';
			//trip details
			$active_tab = 'd_tab';//default profile tab
			if($param2!=''){
				
				//echo $fdate.",".$todate;exit;
				if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['date_search'])){
					if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
						$fdate=$date[0].'-'.$date[1].'-01';
						$todate=$date[0].'-'.$date[1].'-31';
					} else{
						$fdate=$_REQUEST['from_pick_date'];
						$todate=$_REQUEST['to_pick_date']; 	
					}
					$data['trip_tab']='active';
					$active_tab = 't_tab';//trip tab
				} 
				$trips = $this->trip_booking_model->getDriverVouchers($param2,$fdate,$todate);
				
				//echo "<pre>";print_r($trips);echo "</pre>";exit;
				list($data['TripTableData'], $data['TotalTable']) = $this->DriverTripsTable($trips,$DriverSalary);

				
				//echo "<pre>";print_r($data['TotalTable']['tdata']);echo "</pre>";exit;
				
			
				//$this->mysession->set('trips',$data['trips']);
			} 
			$data['tabs'] = $this->set_up_driver_tabs($active_tab,$param2);
			if($this->session->userdata('type') == DRIVER){
				$data['edit_profile'] = false;
			}else{
				$data['edit_profile'] = true;
			}
			$data['fdate'] = $fdate;
			$data['todate'] = $todate;
			$data['driver_tab']='active';
			
		//-----------get trip expense type array----------------------
			
			
			
		//------------------------------------------------------------
			
			$data['title']='Driver Profile| '.PRODUCT_NAME;
			$page='user-pages/addDrivers';
			$data['d_id']=$param2;
			$data['select']=$this->select_Box_Values();
			$this->load_templates($page,$data);
		
		}else{
			$this->notAuthorized();
		}
	}
	
	


	//driver tab table generation
	function DriverTripsTable($trips = array(),$Salary=0)
	{
		$expenses=$this->trip_booking_model->getTripExpenses(); 

		$tripsTable = $totalTable = array();

		//trips table column header
		$tripsTable['theader'] = array("Trip ID","Date","Days","Total Km","Total Hrs","Over Time","Trip Amount","Trip %","Halt","Bata");
			
		//total table column header
		$totalTable['theader'] = array(
				'<th style="width:70%;">Particulars</th>',
				'<th style="width:10%;">Tariff</th>',
				'<th style="width:10%;">Credit</th>',
				'<th style="width:10%;">Outstanding</th>');
		//total table row header
		$Particulars[0]= array("label"=>"Total Trips %","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[1]= array("label"=>"Salary","tariff"=>0,"credit"=>0,"outstanding"=>$Salary);
		$Particulars[2]= array("label"=>"Total Halt","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[3]= array("label"=>"Total Bata","tariff"=>0,"credit"=>0,"outstanding"=>0);

		$Total = array('trf'=>0,'cr'=>0,'ots'=>$Salary);
		
		if($trips){
			$tdata = array();$i=0;
			$TotalExpense = array();
			$TotalHalt = $TotalBata = $TotalTripsPercentage =  0;
			foreach($trips as $trip){
				//echo "<pre>";print_r($trip);echo "</pre>";exit;
				$trip_km=$trip['end_km_reading']-$trip['start_km_reading'];
				$trip_hrs = 0;
				$no_of_days=$trip['no_of_days'];

				$date1 = date_create($trip['pick_up_date'].' '.$trip['pick_up_time']);
				$date2 = date_create($trip['drop_date'].' '.$trip['drop_time']);

				$diff= date_diff($date1, $date2);
				$trip_hrs = $diff->h;
	
				if($trip_hrs > 10) 
					$over_time = $trip_hrs % 10;
				else 
					$over_time = 0;

			
				$tdata[$i] = array($trip['trip_id'],$trip['pick_up_date'],$trip['no_of_days'],$trip_km,$trip_hrs,$over_time,number_format($trip['driver_trip_amount'],2),number_format($trip['driver_payment_amount'],2),number_format($trip['night_halt_charges'],2),number_format($trip['driver_bata'],2)
					);

				$expenseValue = unserialize($trip['trip_expense']);
			
				foreach($expenses as $expense){
					$expAmt = (isset($expenseValue[$expense->value]) && $expenseValue[$expense->value] != null)?$expenseValue[$expense->value]:0;
					array_push($tdata[$i],number_format($expAmt,2));

					//total expense
					if(isset($TotalExpense['ots'][$expense->value]))
						$TotalExpense['ots'][$expense->value]	+= $expAmt;
					else
						$TotalExpense['ots'][$expense->value]	= $expAmt;
				}
				$Particulars[0]['outstanding'] += $trip['driver_payment_amount'];
				$Particulars[2]['outstanding'] += $trip['night_halt_charges'];
				$Particulars[3]['outstanding'] += $trip['driver_bata'];
				$Total['ots'] += $trip['driver_payment_amount']+$trip['night_halt_charges']+$trip['driver_bata'];
				
				$i++;//next td values
			}//trips loop ends 

			$tripsTable['tdata'] = $tdata;

			foreach($expenses as $expense){
				//trip table headers for expense
				array_push($tripsTable['theader'] ,$expense->description);

				//build total Trip Expense Fields
				$TotalExAmt =(array_key_exists($expense->value,$TotalExpense['ots']))?
					 $TotalExpense['ots'][$expense->value]:0;
				$Total['ots'] += $TotalExAmt;
				
				$Particulars[]= array("label"=>"Total ".$expense->description,"tariff"=>0,"credit"=>0,"outstanding"=>$TotalExAmt);
		
			}
			
			//total table footer
			$totalTable['tfooter']= array("label"=>"Total","tariff"=>number_format(0,2),"credit"=>number_format(0,2),"outstanding"=>number_format($Total['ots'],2));
			$totalTable['tdata'] = $Particulars;
			
			//echo "<pre>";print_r($totalTable);echo "</pre>";exit;
			
		}else{
			$tripsTable = false;
			$totalTable = false;
			
		}
		return array($tripsTable,$totalTable);
	}
	//------------------------------------------------------------------------------------------


	/*driver page tab setting ,
	1.first parameter is tab identifier you want set active tab, default profile
		tabs are d_tab=>profile,t_tab=>trip , p_tab=>payments and a_tab=>accounts 
	2.second parameter is the customer id */
	function set_up_driver_tabs($tab_active='d_tab',$driver_id=''){
			
		$tabs['d_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Profile',
						'content_class'=>'tab-pane');

		if($driver_id!='' && $driver_id > 0){

			$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Trip',
						'content_class'=>'tab-pane');
			if(!$this->session->userdata('driver')){
				$tabs['p_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Payments',
						'content_class'=>'tab-pane');
					
			}
			$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Accounts',
						'content_class'=>'tab-pane');
		}

		if (array_key_exists($tab_active, $tabs)) {
			$tabs[$tab_active]['class'] = 'active';
			$tabs[$tab_active]['content_class'] = 'tab-pane active';
		}else{
			$tabs['d_tab']['class'] = 'active';
			$tabs['d_tab']['content_class'] = 'tab-pane active';
		}


		return $tabs;
	}
	function set_up_vehicle_tabs($tab_active='v_tab',$vehicle_id=''){
			
		$tabs['v_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Vehicle',
						'content_class'=>'tab-pane');

		if($vehicle_id!='' && $vehicle_id > 0){

			$tabs['i_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Insurance',
						'content_class'=>'tab-pane');
			
			$tabs['l_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Loan',
						'content_class'=>'tab-pane');
					
			$tabs['o_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Owner',
						'content_class'=>'tab-pane');
			$tabs['s_tab'] = array('class'=>'','tab_id'=>'tab_5','text'=>'Service',
						'content_class'=>'tab-pane');
			$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_6','text'=>'Trip',
						'content_class'=>'tab-pane');	
			$tabs['p_tab'] = array('class'=>'','tab_id'=>'tab_7','text'=>'Payments',
						'content_class'=>'tab-pane');
			$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_8','text'=>'Accounts',
						'content_class'=>'tab-pane');
		}

		if (array_key_exists($tab_active, $tabs)) {
			$tabs[$tab_active]['class'] = 'active';
			$tabs[$tab_active]['content_class'] = 'tab-pane active';
		}else{
			$tabs['v_tab']['class'] = 'active';
			$tabs['v_tab']['content_class'] = 'tab-pane active';
		}


		return $tabs;
	}
	
	public function tripVouchers($param2){
			if($this->session_check()==true || $this->driver_session_check()==true) { 
		
			//$data['trips']=$this->trip_booking_model->getTripVouchers();
			//print_r($data['trips']);exit;
			$baseurl=base_url().'organization/front-desk/tripvouchers/';
			$per_page=10;
			$uriseg ='4';
			$data['from_date']='';
			$data['to_date']='';
			$data['trip_id']='';
			$qry='SELECT TV.total_trip_amount,TV.start_km_reading,TV.end_km_reading,TV.end_km_reading,TV.releasing_place,TV.parking_fees,TV.toll_fees,TV.state_tax,TV.night_halt_charges,TV.fuel_extra_charges, T.id,T.pick_up_city,T.drop_city,T.pick_up_date,T.pick_up_time,T.drop_date,T.drop_time,T.tariff_id FROM trip_vouchers AS TV LEFT JOIN trips AS T ON  TV.trip_id =T.id AND TV.organisation_id = '.$this->session->userdata('organisation_id').' WHERE T.organisation_id = '.$this->session->userdata('organisation_id').' ';
			
			//driver session check 
			
			if($this->session->userdata('driver')){ 
				$qry .= ' AND T.driver_id='.$this->session->userdata('driver')->id;
			}
			
			if($param2=='1' ){
				$param2='0';
			}
			if((isset($_REQUEST['trip_id'])|| isset($_REQUEST['from_date']) || isset($_REQUEST['to_date']))&& isset($_REQUEST['voucher_search'])){	
				
				if($param2==''){
				$param2='0';
				}
				if($_REQUEST['trip_id']!=null){
					$data['trip_id']=$_REQUEST['trip_id'];
					$qry.=' AND T.id ='.$_REQUEST['trip_id'];
					$where_arry['trip_id']=$_REQUEST['trip_id'];
				}
				
				if($_REQUEST['from_date']!=null && $_REQUEST['to_date']!=null){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date >="'.$_REQUEST['from_date'].'" AND T.drop_date <="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];
				}else if($_REQUEST['from_date']!=null && $_REQUEST['to_date']==null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.pick_up_date ="'.$_REQUEST['from_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}else if($_REQUEST['from_date']==null && $_REQUEST['to_date']!=null ){
				$data['from_date']=$_REQUEST['from_date'];
				$data['to_date']=$_REQUEST['to_date'];
				$qry.=' AND T.drop_date ="'.$_REQUEST['to_date'].'"';
				$where_arry['from_date']=$_REQUEST['from_date'];
				$where_arry['to_date']=$_REQUEST['to_date'];

				}
				if(isset($where_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry));
				}
			}else if($this->mysession->get('condition')!=''){
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['from_date']) || isset($condition['where']['to_date']) ){
				if(isset($condition['where']['trip_id'])){
				$data['trip_id']=$condition['where']['trip_id'];
				$qry.=' AND T.id ='.$condition['where']['trip_id'];
				}
				if($condition['where']['from_date']!=null && $condition['where']['to_date']!=null){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.pick_up_date >="'.$condition['where']['from_date'].'" AND T.drop_date <="'.$condition['where']['to_date'].'"';
				
				}else if($condition['where']['from_date']!=null && $condition['where']['to_date']==null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.='AND T.pick_up_date ="'.$condition['where']['from_date'].'"';
				
				}else if($condition['where']['from_date']==null && $condition['where']['to_date']!=null ){
				$data['from_date']=$condition['where']['from_date'];
				$data['to_date']=$condition['where']['to_date'];
				$qry.=' AND T.drop_date ="'.$condition['where']['to_date'].'"';
				

				}
			}
			}
			
						
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			if($param2==''){
				$this->mysession->delete('condition');
			}
			$data['page_links']=$paginations['page_links'];
			$data['trips']=$paginations['values'];			
			if(empty($data['customers'])){
				$data['result']="No Results Found !";
				}




			$data['title']='Trip Vouchers | '.PRODUCT_NAME;
			$page='user-pages/trip_vouchers';
			$this->load_templates($page,$data);
		
			}else{
				$this->notAuthorized();
			}
	}
	public function select_Box_Values(){
		$tbl_arry=array('marital_statuses','bank_account_types','id_proof_types');
		$this->load->model('user_model');
		for ($i=0;$i<count($tbl_arry);$i++){
		$result=$this->user_model->getArray($tbl_arry[$i]);
		if($result!=false){
		$data[$tbl_arry[$i]]=$result;
		}
		else{
		$data[$tbl_arry[$i]]='';
		}
		}
		return $data;
	}
	
	public function ShowVehicleView($param1,$param2,$param3)
	{
	
		if($this->session_check()==true) {
			$active_tab = 'v_tab';
			if($param1==''){
				$this->mysession->delete('vehicle_id');
			} 
			
			$org_id=$this->session->userdata('organisation_id');
			$data['select']=$this->select_Vehicle_Values();
			
			if($param1!='' && is_numeric($param1)){
				
				$tbl 	= 'vehicles';
				$vid 	= $param1;
				if($vid != null){
					$this->mysession->set('vehicle_id',$vid);
				}
		
				$data['s_list']=$this->vehicle_model->get_listService($vid);

				switch ($param2){
					case 'insurance':$active_tab = 'i_tab';break;
					case 'loan':$active_tab = 'l_tab';break;
					case 'owner':$active_tab = 'o_tab';break;
					case 'trip':$active_tab = 't_tab';break;
					case 'service':$active_tab = 's_tab';
							if(is_numeric($param3)){
							$data['s_edit']=$this->vehicle_model->get_Service($param3);
							}
							break;
				}

				//-----trip tab
				$tdate=date('Y-m-d');
				$date=explode("-",$tdate);
				$fdate=$date[0].'-'.$date[1].'-01';
				$todate=$date[0].'-'.$date[1].'-31';

				if((isset($_REQUEST['from_pick_date'])|| isset($_REQUEST['to_pick_date']))&& isset($_REQUEST['vdate_search'])){
				if($_REQUEST['from_pick_date']==null && $_REQUEST['to_pick_date']==null){
					$fdate=$date[0].'-'.$date[1].'-01';
					$todate=$date[0].'-'.$date[1].'-31';
				}else{
					$fdate=$_REQUEST['from_pick_date'];
					$todate=$_REQUEST['to_pick_date'];}
					$data['trip_tab']='active';
				}
				$data['ve_id']=$vid; 
				//$data['trips']=$this->trip_booking_model->getVehicleVouchers($vid,$fdate,$todate); 
				$trips=$this->trip_booking_model->getVehicleVouchers($vid,$fdate,$todate); 
				//array values for Vehicle Trip tab
				list($data['TripTableData'], $data['TotalTable']) = $this->VehicleTripsTable($trips);
				
				//----------------------
				$data['record_values']=$this->user_model->getRecordsById($tbl,$vid); 
				$data['driver']=$data['record_values']['driver'];
				$data['vehicle']=$data['record_values']['vehicle'];//print_r($data['vehicle']);exit;
				$data['device']=$data['record_values']['device'];
				$insurance_id=$data['vehicle']['vehicles_insurance_id'];
				$loan_id=$data['vehicle']['vehicle_loan_id'];
				$owner_id=$data['vehicle']['vehicle_owner_id'];
				$data['supplier_group']=$data['vehicle']['supplier_group_id'];
				if($insurance_id!=gINVALID && $insurance_id!=0){
					$data['get_insurance']=$this->user_model->getInsurance($insurance_id);

				}
				if($loan_id!=gINVALID && $loan_id!=0){
					$data['get_loan']=$this->user_model->getLoan($loan_id);
			
				}
				if($owner_id!=gINVALID && $owner_id!=0){
					$data['get_owner']=$this->user_model->getOwner($owner_id); 
					
				}


				$data['vehicle_percentages'] = $data['driver_percentages'] = array();
			
				if($data['vehicle']['vehicle_ownership_types_id'] > 0 && $data['vehicle']['vehicle_ownership_types_id']!=OWNED_VEHICLE){
					$percentages	= $this->trip_booking_model->getPercentages();
					if(isset($percentages['vehicle'])){
						foreach($percentages['vehicle'] as $val){
						$data['vehicle_percentages'][$val['id']] = $val['value'];
						}
					}
					if(isset($percentages['driver'])){
						foreach($percentages['driver'] as $val){
						$data['driver_percentages'][$val['id']] = $val['value'];
						}
					}
				}
				//echo "<pre>";print_r($data['select']['drivers']);echo "</pre>";exit;
			
				//if($this->mysession->get("error")=='true'){
			
				$driver_id=@$data['driver']['driver_id'];
				$result=$this->user_model->getDriverNameById($driver_id);
				$data['select']['drivers'][$driver_id]=$result['name'];
				//for device
				if(isset($data['device']['device_id'])){
					$device_id=$data['device']['device_id'];
					$result=$this->user_model->getDeviceImeiById($device_id);
					$data['select']['devices'][$device_id]=$result['imei'];
				}

			
			}
			
			$data['tabs'] = $this->set_up_vehicle_tabs($active_tab,$param1);
			$data['title']="Vehicle Details | ".PRODUCT_NAME;  
			$page='user-pages/addVehicles';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	
	//vehicle tab table generation
	function VehicleTripsTable($trips = array())
	{
		$expenses=$this->trip_booking_model->getTripExpenses(); 

		$tripsTable = $totalTable = array();

		//trips table column header
		$tripsTable['theader'] = array("Trip Id","Date","Days","Total Km","Trip Amount","Trip %");
			
		//total table column header
		$totalTable['theader'] = array(
				'<th style="width:70%;">Particulars</th>',
				'<th style="width:10%;">Tariff</th>',
				'<th style="width:10%;">Credit</th>',
				'<th style="width:10%;">Outstanding</th>');
		//total table row header
		$Particulars[0]= array("label"=>"Total Trip Amount","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[1]= array("label"=>"Less Cash Trip/Advance Amount","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[2]= array("label"=>"Total Trip Percentage","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[3]= array("label"=>"Less Cash Trip Percentage","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[4]= array("label"=>"Balance Due","tariff"=>0,"credit"=>0,"outstanding"=>0);
		$Particulars[5]= array("label"=>"TDS 1 %","tariff"=>0,"credit"=>0,"outstanding"=>0);
		
		$Total = array('trf'=>0,'cr'=>0,'ots'=>0);
		
		if($trips){ 
			$tdata = array();$i=0;
			$TotalExpense = array();
			$TotalHalt = $TotalBata = $TotalTripAmount = $full_tot_km= $tot_tax= $tot_vehicle_payment_amount=$tot_vehicle_trip_amount=0;
			foreach($trips as $trip){
				//echo "<pre>";print_r($trip);echo "</pre>";exit;
				$trip_km=$trip['end_km_reading']-$trip['start_km_reading'];
				$full_tot_km=$full_tot_km+$trip_km;
				$tot_tax=$tot_tax+$trip['state_tax'];
				$tot_vehicle_payment_amount=$tot_vehicle_payment_amount+$trip['vehicle_payment_amount'];
				$tot_vehicle_trip_amount=$tot_vehicle_trip_amount+$trip['vehicle_trip_amount'];
				
				$date1 = date_create($trip['pick_up_date'].' '.$trip['pick_up_time']);
				$date2 = date_create($trip['drop_date'].' '.$trip['drop_time']);

				$diff= date_diff($date1, $date2);
				$no_of_days=$diff->d;
				if($no_of_days==0){
					$no_of_days='1 Day';
							
				}else{
					$no_of_days.=' Days';
							
				}

				$tdata[$i] = array($trip['id'],$trip['pick_up_date'],$no_of_days,$trip_km,number_format($trip['vehicle_trip_amount'],2),number_format($trip['vehicle_payment_amount'],2)
					);

				$expenseValue = unserialize($trip['trip_expense']);
			
				foreach($expenses as $expense){
					$expAmt = (isset($expenseValue[$expense->value]) && $expenseValue[$expense->value] != null)?$expenseValue[$expense->value]:0;
					array_push($tdata[$i],number_format($expAmt,2));

					//total expense
					if(isset($TotalExpense['ots'][$expense->value]))
						$TotalExpense['ots'][$expense->value]	+= $expAmt;
					else
						$TotalExpense['ots'][$expense->value]	= $expAmt;
				}
				$Particulars[0]['outstanding'] += $trip['vehicle_trip_amount'];
				$Particulars[2]['outstanding'] += $trip['vehicle_payment_amount'];
				
				$Total['ots'] += $trip['vehicle_trip_amount']+$trip['vehicle_payment_amount'];
				
				$i++;//next td values
			}//trips loop ends 
			
			
			$tripsTable['tdata'] = $tdata;

			foreach($expenses as $expense){
				//trip table headers for expense
				array_push($tripsTable['theader'] ,$expense->description);

				//build total Trip Expense Fields
				$TotalExAmt =(array_key_exists($expense->value,$TotalExpense['ots']))?
					 $TotalExpense['ots'][$expense->value]:0;
				$Total['ots'] += $TotalExAmt;
				
				$Particulars[]= array("label"=>"Less ".$expense->description,"tariff"=>0,"credit"=>0,"outstanding"=>$TotalExAmt);
		
			}
			
			//total table footer
			$totalTable['tfooter']= array("label"=>"Total","tariff"=>number_format(0,2),"credit"=>number_format(0,2),"outstanding"=>number_format($Total['ots'],2));
			$totalTable['tdata'] = $Particulars;
			
			//echo "<pre>";print_r($totalTable);echo "</pre>";exit;
			
		}else{
			$tripsTable = false;
			$totalTable = false;
			
		}
		return array($tripsTable,$totalTable);
	}
	
	public function select_Vehicle_Values(){
		$tbl_arry=array(
			'vehicle_models','drivers','devices',
			'vehicle_ownership_types','vehicle_types','vehicle_makes',
			'vehicle_ac_types','vehicle_fuel_types',
			'vehicle_seating_capacity','vehicle_permit_types',
			'vehicle_payment_percentages','driver_payment_percentages','supplier_groups'
			);
		$this->load->model('user_model');
		for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
				$data[$tbl_arry[$i]]=$result;
			}else{
				$data[$tbl_arry[$i]]='';
			}
		}
		return $data;
	}
	
	public function ShowVehicleList($param1,$param2) {
	if($this->session_check()==true) {
/*if($this->mysession->get('condition')!=null){
						$condition=$this->mysession->get('condition');
						if(isset($condition['like']['registration_number'])|| isset($condition['where']['vehicle_owner_id']) || isset($condition['where']['vehicle_ownership_types_id'])  || isset($condition['where']['vehicle_model_id']) || isset($condition['where']['status'])){
						}
						else{
						$this->mysession->delete('condition');
						}
						}*/
	$data['reg_num']='';
	$data['owner']='';
	$data['v_model']='';
	$data['ownership']='';
	$data['status_id']='';
	$condition='';
	$per_page=10;
	$like_arry=''; 
	$org_id=$this->session->userdata('organisation_id');
	$where_arry['organisation_id']=$org_id;
	$qry='SELECT V.id
FROM vehicles V where V.organisation_id = '.$this->session->userdata('organisation_id');
	if($param2=='1'){
	$param2='0';
	}
	if($param2==''){
	$this->mysession->delete('condition');

	}
	//for search
	   if( isset($_REQUEST['search'])){ 
	if($param2==''){
	$param2='0';
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']!=-1 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['status']=$_REQUEST['status'];
	$qry=' SELECT V.id FROM vehicles AS V LEFT JOIN trips as T ON T.vehicle_id=V.id where V.organisation_id = '.$this->session->userdata('organisation_id').' 
	AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
	AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
	AND T.organisation_id = '.$this->session->userdata('organisation_id').'
	AND T.vehicle_id = V.id 
	AND T.trip_status_id ='.TRIP_STATUS_CONFIRMED;
	}
	if($_REQUEST['status']!=null && $_REQUEST['status']==0 ){
	$data['status_id']=$_REQUEST['status'];
	$date_now=date('Y-m-d H:i:s');
	$where_arry['status']=$_REQUEST['status'];
	$qry='SELECT V.id FROM vehicles V WHERE V.organisation_id = '.$this->session->userdata('organisation_id').' AND V.id NOT IN ('.$qry.')';
	}
	if($_REQUEST['reg_num']!=null){
	$data['reg_num']=$_REQUEST['reg_num'];
	$qry.=' AND V.registration_number LIKE "%'.$_REQUEST['reg_num'].'%" ';
	$like_arry['registration_number']=$_REQUEST['reg_num'];
	}
	if($_REQUEST['owner']>0){
	$data['owner']=$_REQUEST['owner'];
	$qry.=' AND V.vehicle_owner_id ='.$_REQUEST['owner'];
	$where_arry['vehicle_owner_id']=$_REQUEST['owner'];
	}
	if($_REQUEST['ownership']>0){
	$data['ownership']=$_REQUEST['ownership'];
	$qry.=' AND V.vehicle_ownership_types_id ='.$_REQUEST['ownership'];
	$where_arry['vehicle_ownership_types_id']=$_REQUEST['ownership'];
	}
	/*if($_REQUEST['v_type']>0){
	$where_arry['vehicle_type_id']=$_REQUEST['v_type'];
	}*/
	if($_REQUEST['v_model']>0){
	$data['v_model']=$_REQUEST['v_model'];
	$qry.=' AND V.vehicle_model_id ='.$_REQUEST['v_model'];
	$where_arry['vehicle_model_id']=$_REQUEST['v_model'];
	}

	if(isset($where_arry) || isset($like_arry)){
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				}
	}
	else if($this->mysession->get('condition')!=''){ 
				$condition=$this->mysession->get('condition');
				if(isset($condition['where']['status']) || isset($condition['like']['registration_number']) || isset($condition['where']['vehicle_owner_id'])|| isset($condition['where']['vehicle_ownership_types_id'])|| isset($condition['where']['vehicle_model_id']) ){
			
				if(isset($condition['where']['status'])&& $condition['where']['status']!=-1 ){
				$data['status_id']=$condition['where']['status'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status']=$_REQUEST['status'];
			$qry=' SELECT V.id FROM vehicles AS V LEFT JOIN trips as T ON T.vehicle_id=V.id where V.organisation_id = '.$this->session->userdata('organisation_id').'
	AND CONCAT( T.pick_up_date, " ", T.pick_up_time ) <= "'.$date_now.'"
	AND CONCAT( T.drop_date, " ", T.drop_time ) >= "'.$date_now.'"
	AND T.organisation_id = '.$this->session->userdata('organisation_id').'
	AND T.vehicle_id = V.id 
	AND T.trip_status_id ='.TRIP_STATUS_CONFIRMED;
				
				}
				if(isset($condition['where']['status'])&& $condition['where']['status']==0 ){
				$data['status_id']=$condition['where']['status'];
				$date_now=date('Y-m-d H:i:s');
				//$where_arry['status_id']=$_REQUEST['status'];
				$qry='SELECT V.id FROM vehicles V WHERE V.organisation_id = '.$this->session->userdata('organisation_id').' AND V.id NOT IN ('.$qry.')';
				
				}

					if(isset($condition['like']['registration_number'])){
				
				$data['reg_num']=$condition['like']['registration_number']; 
				$qry.=' AND V.registration_number LIKE "%'.$condition['like']['registration_number'].'%" ';
				}
				if(isset($condition['where']['vehicle_owner_id']) && $condition['where']['vehicle_owner_id']!=-1 ){
				$data['owner']=$condition['where']['vehicle_owner_id'];
				$qry.=' AND V.vehicle_owner_id ='.$condition['where']['vehicle_owner_id'];
				}
				if(isset($condition['where']['vehicle_ownership_types_id']) && $condition['where']['vehicle_ownership_types_id']!=-1 ){
				$data['ownership']=$condition['where']['vehicle_ownership_types_id'];
				$qry.=' AND V.vehicle_ownership_types_id ='.$condition['where']['vehicle_ownership_types_id'];
				}
				if(isset($condition['where']['vehicle_model_id'])&& $condition['where']['vehicle_model_id']!=-1 ){
				$data['v_model']=$condition['where']['vehicle_model_id'];
				$qry.=' AND V.vehicle_model_id ='.$condition['where']['vehicle_model_id'];
				}
			}
			}
			
	
	$baseurl=base_url().'organization/front-desk/list-vehicle/';
	$uriseg ='4';

	 $p_res=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);

	$data['values']=$p_res['values'];  
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
	$data['page_links']=$p_res['page_links'];
	$tbl_arry=array('vehicle_models','vehicle_types','vehicle_owners','vehicle_makes','vehicle_ownership_types','supplier_groups');
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
	$page='user-pages/vehicleList';
	
	$this->load_templates($page,$data);	
	}
	else{

	$this->notAuthorized();
	
	}
	}
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}
	}
	public function setup_dashboard(){
	if(isset($_REQUEST['setup_dashboard']) ){
	$data=$this->trip_booking_model->getTodaysTripsDriversDetails();
	//$data['organisation_name']=$this->session->userdata('organisation_name');print_r($data);exit;
	if($data!=false){
	$json_data['organisation']=$this->session->userdata('organisation_name');
	$json_data['graph']=$data;
	echo json_encode($json_data);
	}else{
		echo 'false';
	}
	}
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
	//get notification with organisaion id and customer if exists in session
	public function getNotifications(){
	
	$conditon =array('trip_status_id'=>TRIP_STATUS_PENDING,'CONCAT(pick_up_date," ",pick_up_time) >='=>date('Y-m-d H:i'),'organisation_id'=>$this->session->userdata('organisation_id'));
	//check customer session if yes show only logged in customer notification
		if($this->session->userdata('customer'))
		{
			$conditon['customer_id']= $this->session->userdata('customer')->id;
		}
		$orderby = ' CONCAT(pick_up_date," ",pick_up_time) ASC';
		$notification=$this->trip_booking_model->getDetails($conditon,$orderby);
		$customers_array=$this->customers_model->getArray();
	if(isset($_REQUEST['notify']) ){
	$json_data=array('notifications'=>$notification,'customers'=>$customers_array);
	if(count($notification)>0 && count($customers_array) >0 ){
		echo json_encode($json_data);
	}else{
		echo 'false';
	}
	}else{
			return array($notification,$customers_array);
		}

	}
	 
}
