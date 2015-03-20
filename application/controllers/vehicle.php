<?php 
class Vehicle extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("settings_model");
		$this->load->model("customers_model");
		$this->load->model("vehicle_model");
		$this->load->model("trip_booking_model");
		$this->load->helper('my_helper');
		no_cache();
		
		}
	public function index($param1 ='',$param2='',$param3=''){ 

	
		if($this->session_check()==true || $this->owner_session_check()==true) {
		$tbl=array('vehicle-ownership'=>'vehicle_ownership_types','vehicle-types'=>'vehicle_types','ac-types'=>'vehicle_ac_types','fuel-types'=>'vehicle_fuel_types','seating-capacity'=>'vehicle_seating_capacity','beacon-light-options'=>'vehicle_beacon_light_options ','vehicle-makes'=>'vehicle_makes','driver-bata-percentages'=>'vehicle_driver_bata_percentages ','permit-types'=>'vehicle_permit_types','vehicle-models'=>'vehicle_models','driver_payment_percentages'=>'driver_payment_percentages','vehicle_payment_percentages'=>'vehicle_payment_percentages');
            if($param1=='getDescription') {
            $this->getDescription();
            }
			if($param1=='' || $param1 == 'home'){
				$this->Dashboard();
			}
			if($param1==''){ 
				$this->vehicle_validation();
				}
			if(isset($_REQUEST['service-submit'])){
				$this->service_manage();
				}
			if(isset($_REQUEST['insurance-submit'])){
				$this->insurance_validation();
				}
			if(isset($_REQUEST['loan-submit'])){
				$this->loan_validation();
				}
			if(isset($_REQUEST['owner-submit'])){
				$this->owner_validation();
				}
			if(isset($param1)&& $param1!='getDescription') {
				
				if(isset($_REQUEST['add'])){
					$this->add($tbl,$param1);
					}else if(isset($_REQUEST['edit'])){
					$this->edit($tbl,$param1);
					}else if(isset($_REQUEST['delete'])){
					$this->delete($tbl,$param1);
					}
				//if(isset($_REQUEST['submit-vehicle'])){
				//$this->vehicle_validation();
				//}
				//if(isset($_REQUEST['submit-insurance'])){
				//$this->insurance_validation();
				//}
		}
		
		
	
		}
		else{ 
			$this->notAuthorized();
			}
	}
	
	public function Dashboard(){
		$data['title']="Home | ".PRODUCT_NAME;    
       		$page='vehicle-owner-pages/dashboard';
		$this->load_templates($page,$data);
	}
	
	public function owner_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==VEHICLE_OWNER)) {
			return true;
		} else {
			return false;
		}
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
	
	public function add($tbl,$param1){
	
		if(isset($_REQUEST['select'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['add'])){ 
			$err=false;
		    	$data['name']=$this->input->post('select');
			$data['description']=$this->input->post('description');

			if($data['name']==''||$data['description']==''){
				$this->session->set_userdata(array('dbvalErr'=>'Fields Required..!'));
				$err=true;
			}
			
			$percentage_tabels=array('driver_payment_percentages','vehicle_payment_percentages');
			if((in_array($param1,$percentage_tabels) && !is_numeric($data['name'])) || (!in_array($param1,$percentage_tabels) && is_numeric($data['name']))) {
				$this->session->set_userdata(array('Err_num_name'=>'Invalid input on name field!'));
				$err=true;
			}


			if(is_numeric($data['description'])){
				$this->session->set_userdata(array('Err_num_desc'=>'Invalid input on description field!'));
				$err=true;
			}
			
			if($err==true){
				redirect(base_url().'organization/front-desk/settings');
			}
			else{
				$data['organisation_id']=$this->session->userdata('organisation_id');
				$data['user_id']=$this->session->userdata('id');
			
	        		//vehicle and driver percentage value to value column in table
        			if(in_array($param1,$percentage_tabels)){
        				$data['value'] = $data['name'];
        			}
	
				$result=$this->settings_model->addValues($tbl[$param1],$data);
				if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Added Succesfully..!'));
				    	$this->session->set_userdata(array('dbError'=>''));
				    	redirect(base_url().'organization/front-desk/settings');
				}
			
			}
		}
	}
	//-------------------------------------------------------------------------------

	public function edit($tbl,$param1){
		if(isset($_REQUEST['select_text'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['edit'])){ 
			$err=false;
		    	$data['name']=$this->input->post('select_text');
			$data['description']=$this->input->post('description');

			if($data['name']==''||$data['description']==''){
				$this->session->set_userdata(array('dbvalErr'=>'Fields Required..!'));
				$err=true;
			}

			$percentage_tabels=array('driver_payment_percentages','vehicle_payment_percentages');
			if((in_array($param1,$percentage_tabels) && !is_numeric($data['name'])) || (!in_array($param1,$percentage_tabels) && is_numeric($data['name']))) {
				$this->session->set_userdata(array('Err_num_name'=>'Invalid input on name field!'));
				$err=true;
			}

			if(is_numeric($data['description'])){
				$this->session->set_userdata(array('Err_num_desc'=>'Invalid input on description field!'));
				$err=true;
			}
			
			if($err==true){
				//redirect(base_url().'user/settings');
				redirect(base_url().'organization/front-desk/settings');
			}
			else{
				$id=$this->input->post('id_val');
	        		$this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean');
				$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
				//vehicle and driver percentage value to value column in table
        			if(in_array($param1,$percentage_tabels)){
        				$data['value'] = $data['name'];
        			}
      
				$result=$this->settings_model->updateValues($tbl[$param1],$data,$id);
				if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Updated Succesfully..!'));
			    		$this->session->set_userdata(array('dbError'=>''));
			    		//redirect(base_url().'user/settings');
			    		redirect(base_url().'organization/front-desk/settings');
				}
			
			}
		}
	
	}
	
	public function delete($tbl,$param1){
	if(isset($_REQUEST['delete'])){ 
	
	$id=$this->input->post('id_val');
	        $this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean');
			//$this->form_validation->set_rules('select','Values','trim|required|min_length[2]|xss_clean|alpha_numeric');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
		if($this->form_validation->run()==False){
         redirect(base_url().'organization/front-desk/settings');
		}
      else {
		$result=$this->settings_model->deleteValues($tbl[$param1],$id);
		if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Deleted Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/settings');
						}
			}
	}
	}
	
	
	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	} 

		public function getDescription(){
		$id=$_REQUEST['id'];
		$tbl=$_REQUEST['tbl'];
		$res=$this->settings_model->getValues($id,$tbl);
		echo $res[0]['id'].",".$res[0]['description'].",".$res[0]['name'];
		
		//return 
		}
		public function vehicle_validation(){
		if($this->session_check()==true) {
		if(isset($_REQUEST['vehicle-submit'])){
	
			$v_id=$this->input->post('hidden_id');//exit;
			$data['vehicle_ownership_types_id']=$this->input->post('ownership');
			$data['vehicle_percentage']=$this->input->post('vehicle_percentage');
			$data['driver_percentage']=$this->input->post('driver_percentage');
			$data['vehicle_type_id']=$this->input->post('vehicle_type');
			$data['vehicle_make_id']=$this->input->post('make');
			$data['vehicle_model_id']=$this->input->post('model');
			$data['vehicle_manufacturing_year']=$this->input->post('year');
			$data['vehicle_ac_type_id']=$this->input->post('ac');
			$data['vehicle_fuel_type_id']=$this->input->post('fuel');
			$data['vehicle_seating_capacity_id']=$this->input->post('seat');
			$driver_data['driver_id']=$this->input->post('driver');
			$hid_driver=$this->input->post('hid_driver');
			$driver_data['from_date']=$this->input->post('from_date');
			$h_fdate_driver=$this->input->post('h_fdate_driver'); 
			$device_data['device_id']=$this->input->post('device');
			$hid_device=$this->input->post('hid_device');
			$device_data['from_date_device']=$this->input->post('from_date_device');
			$h_fdate_device=$this->input->post('h_fdate_device');
			$data['registration_number']=$this->input->post('reg_number');
			$data['registration_date']=$this->input->post('reg_date');
			
			$data['engine_number']=$this->input->post('eng_num');
			$data['chases_number']=$this->input->post('chases_num');
			$data['vehicle_permit_type_id']=$this->input->post('permit');
			$data['vehicle_permit_renewal_date']=$this->input->post('permit_date');
			
			$data['vehicle_permit_renewal_amount']=str_replace(",","",$this->input->post('permit_amount'));
			$data['tax_renewal_amount']=str_replace(",","",$this->input->post('tax_amount'));
			$data['tax_renewal_date']=$this->input->post('tax_date');
			
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			$all_data=array('data'=>$data,'driver_data'=>$driver_data,'device_data'=>$device_data);
			
					
			$this->form_validation->set_rules('year','Year','trim|required|xss_clean');
			$this->form_validation->set_rules('reg_number','Registeration Number','trim|required|xss_clean|regex_match[/^[A-Z]{2}[ -][0-9]{1,2}(?: [A-Z])?(?: [A-Z]*)? [0-9]{4}$/]');
			$this->form_validation->set_rules('from_date','From Date ','trim|xss_clean');
			$this->form_validation->set_rules('from_date_device','From Date ','trim|xss_clean');
			$this->form_validation->set_rules('reg_date','Registration Date','trim|required|xss_clean');
			$this->form_validation->set_rules('eng_num','Engine Number','trim|xss_clean');
			$this->form_validation->set_rules('chases_num','Chases Number','trim|xss_clean');
			$this->form_validation->set_rules('permit_date','Permit Renewal Date','trim|xss_clean');
			$this->form_validation->set_rules('permit_amount','Permit Renewal  Amount','trim|xss_clean');
			$this->form_validation->set_rules('tax_amount','Tax Amount','trim|xss_clean');
			$this->form_validation->set_rules('tax_date','Tax Date','trim|xss_clean');
			//for insurance
			$err=True;


			if($data['vehicle_ownership_types_id'] ==-1){
			 $data['vehicle_ownership_types_id'] ='';
			 $err=False;
			 $this->mysession->set('ownership','Choose Ownership Type');
			}
			if($data['vehicle_ownership_types_id'] > 1){
				if($data['vehicle_percentage'] ==-1){
				 $data['vehicle_percentage'] ='';
				 $err=False;
				 $this->mysession->set('vehicle_percentage','Choose Vehicle Percentage');
				}
				if($data['driver_percentage'] ==-1){
				 $data['driver_percentage'] ='';
				 $err=False;
				 $this->mysession->set('driver_percentage','Choose Driver Percentage');
				}
			}

			if($data['vehicle_type_id'] ==-1){
			 $data['vehicle_type_id'] ='';
			 $err=False;
			 $this->mysession->set('vehicle_type','Choose Vehicle Type');
			}
			if($data['vehicle_make_id'] ==-1){
			 $data['vehicle_make_id'] ='';
			 $err=False;
			 $this->mysession->set('make','Choose Vehicle Make');
			}
			if($data['vehicle_ac_type_id'] ==-1){
			 $data['vehicle_ac_type_id'] ='';
			 $err=False;
			 $this->mysession->set('ac','Choose AC Type');
			}
			if($data['vehicle_fuel_type_id'] ==-1){
			 $data['vehicle_fuel_type_id'] ='';
			 $err=False;
			 $this->mysession->set('fuel','Choose Fuel Type');
			}
			if($data['vehicle_seating_capacity_id'] ==-1){
			 $data['vehicle_seating_capacity_id'] ='';
			 $err=False;
			 $this->mysession->set('seat','Choose Seat Capacity');
			}
			if($data['vehicle_model_id'] ==-1){
			 $data['vehicle_model_id'] ='-1';
			 $err=False;
			 $this->mysession->set('model','Choose Model Type');
			}
			/*if($driver_data['driver_id'] ==-1){
			 $driver_data['driver_id'] ='';
			 $err=False;
			 $this->mysession->set('Driver','Choose Any Driver');
			} */

	 if($this->form_validation->run()==False|| $err==False){
	
		
		
		$data['vehicle_percentages'] = $data['driver_percentages']= array();
		if($data['vehicle_ownership_types_id'] > 0 && $data['vehicle_ownership_types_id']!=OWNED_VEHICLE){
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

			
		
		$this->mysession->set('v_id',$v_id);
		$this->mysession->set('post_all',$data);
		$this->mysession->set('post_driver',$driver_data);
		$this->mysession->set('post_device',$device_data);
		
		
		if($v_id==gINVALID){
		$r_id='';
		}
		else{
		$r_id=$v_id;
		}
		redirect(base_url().'organization/front-desk/vehicle/'.$r_id);	// ?? driver data?? device_data??
	 }
	 
	  else{
	  		$date=explode("-",$driver_data['from_date']);
			$year=$date[0];
			$month=$date[1];
			$day=$date[2];

			$from_unix_time = mktime(0, 0, 0, $month, $day, $year);
			$day_before = strtotime("yesterday", $from_unix_time);
			$formatted_date = date('Y-m-d', $day_before);
			//for device data
			if($device_data['device_id'] > 0){
				$dev_date=explode("-",$device_data['from_date_device']);
				$dev_year=$dev_date[0];
				$dev_month=$dev_date[1];
				$dev_day=$dev_date[2];

				$dev_from_unix_time = mktime(0, 0, 0, $dev_month, $dev_day, $dev_year);
				$dev_day_before = strtotime("yesterday", $dev_from_unix_time);
				$dev_formatted_date = date('Y-m-d', $dev_day_before);
			}
			
	  if($v_id==gINVALID){ 
		$res=$this->vehicle_model->insertVehicle($data,$driver_data,$device_data);
		$v_id=$this->mysession->get('vehicle_id');
		if( $res==true ) {
			$this->vehicle_model->map_drivers($driver_data['driver_id'],$driver_data['from_date'],$formatted_date);
			if($device_data['device_id'] > 0)
				$this->vehicle_model->map_devices($device_data['device_id'],$device_data['from_date_device'],$dev_formatted_date);

			$this->mysession->set('dbSuccess',' Vehicle Details Added Succesfully..!');
			$this->mysession->set('dbError','');
			redirect(base_url().'organization/front-desk/vehicle/'.$v_id);
		}
		}
		else{
		
			$res=$this->vehicle_model->UpdateVehicledetails($data,$v_id); 
			if($res==true){
				$this->vehicle_model->map_drivers($driver_data['driver_id'],$driver_data['from_date'],$formatted_date);
				if($device_data['device_id'] > 0)
					$this->vehicle_model->map_devices($device_data['device_id'],$device_data['from_date_device'],$dev_formatted_date);
				$this->mysession->set('dbSuccess',' Vehicle Details Updated Succesfully..!');
	    			$this->mysession->set('dbError','');
	    			redirect(base_url().'organization/front-desk/vehicle/'.$v_id);
			}
		}
	 
		}
		}
		}
		else{
			$this->notAuthorized();
			}
		}
		public function insurance_validation(){
		if($this->session_check()==true) {
			if(isset($_REQUEST['insurance-submit'])){
			$ins_id=$this->input->post('hidden_ins_id');
			$vehicle_id=$this->mysession->get('vehicle_id');
			$data['insurance_number']=$this->input->post('insurance_number');
			$data['insurance_date']=$this->input->post('insurance_date');
			$h_ins=$this->input->post('h_ins');
			$data['insurance_renewal_date']=$this->input->post('insurance_renewal_date');
			$h_renew=$this->input->post('h_renew');
			$data['insurance_premium_amount']=str_replace(",","",$this->input->post('insurance_pre-amount'));
			$data['insurance_amount']=str_replace(",","",$this->input->post('insurance_amount'));
			$data['Insurance_agency']=$this->input->post('insurance_agency');
			$data['Insurance_agency_address']=$this->input->post('insurance_agency_address');
			$data['Insurance_agency_phone']=$this->input->post('insurance_agency_phn');
			$hphone=$this->input->post('hphone');
			$data['Insurance_agency_email']=$this->input->post('insurance_agency_mail');
			$hmail=$this->input->post('hmail');
			$data['Insurance_agency_web']=$this->input->post('insurance_agency_web');
			//$this->form_validation->set_rules('place_of_birth','Birth Place','trim|required|xss_clean|alpha');
					$this->form_validation->set_rules('insurance_number','Insurance Number','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_date','Insurance Date','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_renewal_date','Insurance Renewal Date ','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_amount','Insurance Amount','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_pre-amount','Insurance Pre Amount','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_agency','Insurance Agency','trim|xss_clean');
					 $this->form_validation->set_rules('insurance_agency_address','Address','trim|xss_clean');
					// if($hphone==$data['Insurance_agency_phone']){
					 $this->form_validation->set_rules('insurance_agency_phn','Agency ContactInfo ','trim|xss_clean|regex_match[/^[0-9]{10}$/]');
					 /*}
					 else{
					 $this->form_validation->set_rules('insurance_agency_phn','Agency ContactInfo ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[vehicles_insurance.Insurance_agency_phone]');
					 }*/
					 //if($hmail==$data['Insurance_agency_email']){
					 $this->form_validation->set_rules('insurance_agency_mail','Mail ID','trim|xss_clean|valid_email');
					/* }else{
					 $this->form_validation->set_rules('insurance_agency_mail','Mail ID','trim|required|xss_clean|valid_email|is_unique[vehicles_insurance.Insurance_agency_email]');
					 }*/
					 $this->form_validation->set_rules('insurance_agency_web','Web Address','trim|xss_clean');
					 
					 //for insurance
$err=True;
	
	/*if(preg_match('#[^0-9\.]#', $data['insurance_amount'])){
			$this->mysession->set('Err_insurance_amt','Invalid Characters on  Amount field!');
			$err=False;
			}
	if(preg_match('#[^0-9\.]#', $data['insurance_premium_amount'])){
			$this->mysession->set('Err_insurance_pre_amt','Invalid Characters on Pre Amount field!');
			$err=False;
			}*/
			
	if($this->mysession->get('vehicle_id')==null)
	{
	$this->mysession->set('Err_invalid_add','Invalid Attempt!  Please Add Vehicle Details !');
	$err=False;
	}
	 if($this->form_validation->run()==False|| $err==False){
	// echo "id".$this->mysession->get('vehicle_id');exit;
		$this->mysession->set('insurance_id',$ins_id);
		$this->mysession->set('ins_post_all',$data);
		if($this->mysession->get('Err_invalid_add')==null){
		$this->mysession->set('Err_tab','Missing Data in Insurance Tab');}
		else{
		
		}
		
		
		$id=$this->mysession->get('vehicle_id');
		if($id==''){
		$current_id='';
		}
		else{
		$current_id=$id;
		}
		redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/insurance',$data);	
	 }
	 
	  else{ 
		$id=$this->mysession->get('vehicle_id');
		if($id==''){
		$current_id='';
		}
		else{
		$current_id=$id;
		}
	  //database insertion for vehicle
	  if($ins_id==gINVALID ){ 
		
		$res=$this->vehicle_model->insertInsurance($data);
		//$ins_id=$this->mysession->get('vehicle_id');
		if( $res==true ) {
			$this->mysession->set('ins_Success',' Insurance Details Added Succesfully..!');
				    $this->mysession->set('ins_Error','');
				    redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/insurance');
		}
		}
		else{
	
		$res=$this->vehicle_model->UpdateInsurancedetails($data,$ins_id); 
		if($res==true){
		$id=$this->mysession->get('vehicle_id');
		$this->mysession->set('ins_Success','Insurance Details Updated Succesfully..!');
	    $this->mysession->set('ins_Error','');
	    redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/insurance');
		}
		}

	  }
		
		}
		}
		else{
			$this->notAuthorized();
			}
		}
		public function service_manage(){
		
	if(isset($_REQUEST['service-submit'])){
	
	$s_data['vehicle_id']=$this->mysession->get('vehicle_id');
	$s_data['service_date']=$this->input->post('service_date');
	$s_data['service_km']=$this->input->post('service_km');
	$s_data['particulars']=$this->input->post('particulars');
	$vid=$this->mysession->get('vehicle_id');
		$this->form_validation->set_rules('service_date','Date','trim|required|xss_clean');
		$this->form_validation->set_rules('service_km','Kilometer','trim|required|xss_clean');
		$this->form_validation->set_rules('particulars','Particular','trim|required|min_length[5]|xss_clean');
		if($this->form_validation->run()==False){
		 $this->mysession->set('service_post',$s_data);
		 redirect(base_url().'organization/front-desk/vehicle/'.$vid.'/service',$s_data);
		 }
		 else{
		 if($this->mysession->get('s_id')==gINVALID){
		 $result=$this->vehicle_model->insert_service($s_data);
		 if($result==true){
				$this->session->set_userdata(array('dbSuccess'=>'Details Added Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				
				redirect(base_url().'organization/front-desk/vehicle/'.$vid.'/service');
						}
						
		}else{
		 $s_id=$this->mysession->get('s_id');
		 
		$result=$this->vehicle_model->update_service($s_data,$s_id);
		 if($result==true){
				$this->session->set_userdata(array('dbSuccess'=>'Details Updated Succesfully..!'));
				$this->session->set_userdata(array('dbError'=>''));
				$this->mysession->delete('s_id');
				redirect(base_url().'organization/front-desk/vehicle/'.$vid.'/service');
						}
		}
		 }
	
	}
	}
	
	
	
	
	
		public function loan_validation(){if($this->session_check()==true) {
			if(isset($_REQUEST['loan-submit'])){
			$loan_id=$this->input->post('hidden_loan_id');
			$vehicle_id=$this->mysession->get('vehicle_id');
			$data['total_amount']=str_replace(",","",$this->input->post('total_amt'));
			$data['number_of_emi']=$this->input->post('emi_number');
			$data['emi_amount']=str_replace(",","",$this->input->post('emi_amt')); 
			$data['number_of_paid_emi']=$this->input->post('no_paid_emi');
			$data['emi_payment_date']=$this->input->post('emi_date');
			$h_emi=$this->input->post('h_emi');
			$data['loan_agency']=$this->input->post('loan_agency');
			$data['loan_agency_address']=$this->input->post('loan_agency_address');
			$data['loan_agency_web']=$this->input->post('loan_agency_web');
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			$data['loan_agency_phone']=$this->input->post('loan_agency_phn');
			$hphone=$this->input->post('hphone_ins');
			$data['loan_agency_email']=$this->input->post('loan_agency_mail');
			$hmail=$this->input->post('hmail_ins');
			
			//$this->form_validation->set_rules('place_of_birth','Birth Place','trim|required|xss_clean|alpha');
					$this->form_validation->set_rules('total_amt','Total Amount','trim|required|xss_clean');
					 $this->form_validation->set_rules('emi_number','EMI Number ','trim|required|xss_clean|numeric');
					 $this->form_validation->set_rules('emi_amt','EMI Amount ','trim|required|xss_clean');
					 $this->form_validation->set_rules('no_paid_emi','Number of Paid EMI','trim|required|xss_clean|numeric');
					 $this->form_validation->set_rules('emi_date','EMI Payment Date','trim|required|xss_clean');
					 $this->form_validation->set_rules('loan_agency','Loan Agency','trim|xss_clean');
					 $this->form_validation->set_rules('loan_agency_address','Address','trim|xss_clean');
					 $this->form_validation->set_rules('loan_agency_web','Web Address','trim|xss_clean');
					 //if($hphone==$data['loan_agency_phone']){
					 $this->form_validation->set_rules('loan_agency_phn','Agency ContactInfo ','trim|xss_clean|regex_match[/^[0-9]{10}$/]');
					/* }
					 else{
					 $this->form_validation->set_rules('loan_agency_phn','Agency ContactInfo ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[vehicle_loans.loan_agency_phone]');
					 }*/
					// if($hmail==$data['loan_agency_email']){
					 $this->form_validation->set_rules('loan_agency_mail','Mail ID','trim|xss_clean|valid_email');
					/* }else{
					 $this->form_validation->set_rules('loan_agency_mail','Mail ID','trim|required|xss_clean|valid_email|is_unique[vehicle_loans.loan_agency_email]');
					 }*/
					
					 
					 //for insurance
$err=True;
	
	/*if(preg_match('#[^0-9\.]#', $data['total_amount'])){
			$this->mysession->set('Err_loan_amt','Invalid Characters on Total Amount field!');
			$err=False;
			}
	if(preg_match('#[^0-9\.]#', $data['emi_amount'])){
			$this->mysession->set('Err_loan_emi_amt','Invalid Characters on EMI Amount field!');
			$err=False;
			}*/
	if($this->mysession->get('vehicle_id')==null)
	{
	$this->mysession->set('Err_invalid_add','Invalid Attempt!  Please Add Vehicle Details !');
	$err=False;
	}

	 if($this->form_validation->run()==False|| $err==False){
	 
		$this->mysession->set('loan_id',$loan_id);
		$this->mysession->set('loan_post_all',$data);
		$id=$this->mysession->get('vehicle_id');
		if($this->mysession->get('Err_invalid_add')==null){
		$this->mysession->set('Err_tab','Missing Data in Loan Tab');}
		else{
		
		}
		
		if($id==''){
		$current_id='';
		}
		else{
		$current_id=$id;
		}
		redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/loan',$data);	
	 }
	 
	  else{ 
	 $id=$this->mysession->get('vehicle_id');
		if($id==''){
		$current_id='';
		}
		else{
		$current_id=$id;
		}
	  //database insertion for vehicle
	  if($loan_id==gINVALID ){ 
		
		$res=$this->vehicle_model->insertLoan($data);
		//$ins_id=$this->mysession->get('vehicle_id');
		if( $res==true ) {
			$this->mysession->set('loan_Success',' Loan Details Added Succesfully..!');
				    $this->mysession->set('loan_Error','');
				    redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/loan');
		}
		}
		else{
	
		$res=$this->vehicle_model->UpdateLoandetails($data,$loan_id); 
		if($res==true){
		$this->mysession->set('loan_Success',' Loan Details Updated Succesfully..!');
	    $this->mysession->set('loan_Error','');
	    redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/loan');
		}
		}

	  }
		
		}
		}
		else{
			$this->notAuthorized();
			}
		}
		
	

	public function owner_validation(){	
		if(isset($_REQUEST['owner-submit'])){ 
			$owner_id=$this->input->post('hidden_owner_id');
			$vehicle_id=$this->mysession->get('vehicle_id');
			$data['name']=$this->input->post('owner_name');
			$data['address']=$this->input->post('address');
			$data['mobile']=$this->input->post('mobile');
			$data['email']=$this->input->post('mail');
			$data['dob']=$this->input->post('dob');
			$supplier_group_id=$this->input->post('supplier_group'); 
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			$hphone=$this->input->post('hphone_own');
			$hmail=$this->input->post('hmail_own');
			$username=$this->input->post('username');
			$password=$this->input->post('password');
			$hpass=$this->input->post('h_pass');
			$hidden_user=$this->input->post('h_user');
			//$this->form_validation->set_rules('place_of_birth','Birth Place','trim|required|xss_clean|alpha');
			
			 $this->form_validation->set_rules('address','Address','trim|xss_clean');
			 $this->form_validation->set_rules('dob','Date of Birth','trim|xss_clean');
			// if($hphone==$data['mobile']){
			if($supplier_group_id>0){
				$this->form_validation->set_rules('mobile','Agency ContactInfo ','trim|xss_clean|regex_match[/^[0-9]{10}$/]');
				$this->form_validation->set_rules('owner_name','Owner Name ','trim|xss_clean');
			}
			else{
				$this->form_validation->set_rules('mobile','Agency ContactInfo ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]');
				$this->form_validation->set_rules('owner_name','Owner Name ','trim|required|xss_clean');
			}/* }
			 else{
			 $this->form_validation->set_rules('mobile','Agency ContactInfo ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[vehicle_owners.mobile]');
			 }*/
			 //if($hmail==$data['email']){
			 $this->form_validation->set_rules('mail','Mail ID','trim|xss_clean|valid_email');
			 /*}else{
			 $this->form_validation->set_rules('mail','Mail ID','trim|required|xss_clean|valid_email|is_unique[vehicle_owners.email]');
			 }*/
			
			 $err=True;
			 //for insurance
			if($this->mysession->get('vehicle_id')==null)
			{
				$this->mysession->set('Err_invalid_add','Invalid Attempt!  Please Add Vehicle Details !');
				$err=False;
			}
	
		$this->mysession->delete('v_pwd_err');	
		if($this->input->post('username')!='') { 
	
			if($this->input->post('password')==''){
				$err=False;
				$this->mysession->set('v_pwd_err','Password Field Required');
			}
			else{
			
				if($owner_id==gINVALID ){
					$this->form_validation->set_rules('password','Password','trim|min_length[5]|matches[cpassword]|xss_clean');
					$this->form_validation->set_rules('cpassword','Confirmation','trim|min_length[5]|xss_clean');
				}else{
					$this->form_validation->set_rules('password','Password','trim|min_length[5]|xss_clean');
				}
			}
		
			if($hidden_user!=$this->input->post('username')){
				$this->form_validation->set_rules('username','Username','trim|min_length[4]|xss_clean|is_unique[users.username]');
			}
			if($hidden_user==$this->input->post('username')){
				$this->form_validation->set_rules('username','Username','trim|required|min_length[4]|xss_clean');
			}
		}

			if(($this->form_validation->run()==False )||($err==false )){
				$this->mysession->set('owner_id',$owner_id);
					$data['username']=$username;
					$data['password']=$password;
					$data['supplier_group']=$this->input->post('supplier_group'); 
				$this->mysession->set('owner_post_all',$data);
				$id=$this->mysession->get('vehicle_id');
				
				if($id==''){
				$current_id='';
				}
				else{
				$current_id=$id;
				}
				redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/owner',$data);	
			}else{ 
				$id=$this->mysession->get('vehicle_id'); 
				if($id==''){
					$current_id='';
				}else{
					$current_id=$id;
				}
				 //database insertion for vehicle 
				$login['username']=$username;
				if( $hpass==$password){
					$flag=1;
					$login['password']=$password;
				}else{
					$flag=0;
					$login['password']=$password;
				}
				 
				if($owner_id==gINVALID ){ 
					
		
					$id=$this->mysession->get('vehicle_id');
				if($data['name']!=''&& $data['mobile']!=''){
					$v_owner['vehicle_owner_id']=$this->vehicle_model->insertOwner($data,$login);
					if($v_owner['vehicle_owner_id']){
					//vehicle owner enter as supplier in fa 
						$this->load->model('account_model');
						$this->account_model->add_fa_supplier($v_owner['vehicle_owner_id'],"VW");
						}
				}
				if($supplier_group_id>0){
					$v_owner['supplier_group_id']=$supplier_group_id;
				}
				
				$res=$this->vehicle_model->mapDetails($v_owner);
					if($res) {
	
						$this->mysession->set('owner_Success',' Owner Details Added Succesfully..!');
						$this->mysession->set('owner_Error','');
		
						redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/owner');
					}
				}else{

					$res=$this->vehicle_model->UpdateOwnerdetails($data,$owner_id,$login,$flag); 
					$supplier_data=array('supplier_group_id'=>$supplier_group_id);
					$vehicle_update=$this->vehicle_model->UpdateVehicledetails($supplier_data,$current_id);
					
					if($res==true){
						//edit vehicle owner enter as supplier in fa
						$this->load->model('account_model');
						$this->account_model->edit_fa_supplier($owner_id,"VW");
						$id=$this->mysession->get('vehicle_id');
						$this->mysession->set('owner_Success',' Owner Details Updated Succesfully..!');
				    		$this->mysession->set('owner_Error','');
				    		redirect(base_url().'organization/front-desk/vehicle/'.$current_id.'/owner');
					}

				}

			}
		
		}else{
			$this->notAuthorized();
		}
	}
	//------------------------------------------------------------------
		
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
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
	public function load_templates($page='',$data=''){
	if($this->session_check()==true || $this->owner_session_check()==true) {
		$this->load->view('admin-templates/header',$data);
		$this->load->view('admin-templates/nav');
		$this->load->view($page,$data);
		$this->load->view('admin-templates/footer');
		}
	else{
			$this->notAuthorized();
		}
	}
	
	

}
