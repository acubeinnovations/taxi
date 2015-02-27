<?php 
class Tarrif extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("tarrif_model");
		$this->load->helper('my_helper');
		no_cache();

		}
		public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
	} else {
		return false;
	}
	}
	public function tarrif_master_manage(){
	if($this->session_check()==true) {
	if(isset($_REQUEST['add'])){
	 $data['title'] = $this->input->post('title');
	 /*$data['trip_model_id'] = $this->input->post('select_trip_model');
	 $data['vehicle_make_id'] = $this->input->post('select_vehicle_makes');
	 $data['vehicle_type_id'] = $this->input->post('search_vehicle_type');
	 $data['vehicle_ac_type_id'] = $this->input->post('select_ac_type');*/
	 $data['minimum_kilometers'] = $this->input->post('min_kilo');
	 $data['minimum_hours'] = $this->input->post('min_hours');
	 $data['organisation_id']=$this->session->userdata('organisation_id');
	 $data['user_id']=$this->session->userdata('id');
	 
	$err=True;
	$this->form_validation->set_rules('title','Title','trim|required|min_length[2]|xss_clean');
	/* if($data['trip_model_id'] ==-1){
	 $data['trip_model_id'] ='';
	 $err=False;
	 $this->session->set_userdata('select_trip_model','Choose Any Trip Model');
	 }
	 if($data['vehicle_make_id']==-1){
	  $data['vehicle_make_id'] = '';
	 $err=False;
	 $this->session->set_userdata('select_vehicle_makes','Choose Any Vehicle Makes');
	 } 
	 if($data['vehicle_ac_type_id']==-1){
	 $data['vehicle_ac_type_id'] ='';
	 $err=False;
	 $this->session->set_userdata('select_ac_type','Choose Any AC Type');
	 }
	 if($data['vehicle_type_id']==-1){
	 $data['vehicle_type_id'] ='';
	 $err=False;
	 $this->session->set_userdata('select_vehicle_type','Choose Any Vehicle Type');
	 }*/
	 $this->form_validation->set_rules('min_kilo','Minimum Kilometers','trim|required|xss_clean|numeric');
	 $this->form_validation->set_rules('min_hours','Minimum Hours','trim|required|xss_clean|numeric');
	
		if($this->form_validation->run()==False || $err==False){
		 $this->session->set_userdata('post',$data);
		redirect(base_url().'organization/front-desk/tarrif-masters',$data);
		}
		else {
		$res=$this->tarrif_model->addValues($data);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif-masters');
		}
		}
	}
	if(isset($_REQUEST['edit'])){
	 $id= $this->input->post('manage_id');
	 $data['title'] = $this->input->post('manage_title');
	/* $data['trip_model_id'] = $this->input->post('manage_select_trip_model');
	 $data['vehicle_type_id'] = $this->input->post('manage_select_vehicle_type');
	 $data['vehicle_make_id'] = $this->input->post('manage_select_vehicle_makes');
	 $data['vehicle_ac_type_id'] = $this->input->post('manage_select_ac_type');*/
	 $data['minimum_kilometers'] = $this->input->post('manage_min_kilo');
	 $data['minimum_hours'] = $this->input->post('manage_min_hours');
	
		$err=False;
			/*if($data['trip_model_id'] ==-1){
			 $data['trip_model_id'] ='';
			 $err=true;
			 $this->session->set_userdata('m_trip_model','Choose Trip Model');
			 }
			 if($data['vehicle_type_id'] ==-1){
			 $data['vehicle_type_id'] ='';
			 $err=true;
			 $this->session->set_userdata('m_vehicle_type','Choose Vehicle Type');
			 }
			  if($data['vehicle_make_id'] ==-1){
			 $data['vehicle_make_id'] ='';
			 $err=true;
			 $this->session->set_userdata('m_vehicle_make','Choose Vehicle Make');
			 }
			 if($data['vehicle_ac_type_id'] ==-1){
			 $data['vehicle_ac_type_id'] ='';
			 $err=true;
			 $this->session->set_userdata('m_vehicle_ac','Choose Vehicle AC Type');
			 }*/
		if($data['title']==''||$data['minimum_kilometers']==''||$data['minimum_hours']==''){
			
			$this->session->set_userdata(array('dbvalErr'=>'Fields Required..!'));
			$err=true;
			}
		
		if(preg_match('#[^0-9\.]#', $data['minimum_kilometers'])){
			$this->session->set_userdata(array('Err_m_kilo'=>'Invalid Characters on Kilometers field!'));
			$err=true;
			}
		if(preg_match('#[^0-9\.]#', $data['minimum_hours'])){
			$this->session->set_userdata(array('Err_m_hrs'=>'Invalid Characters on Hours field!'));
			$err=true;
			}
			if($err==true){
			redirect(base_url().'organization/front-desk/tarrif-masters');
			}
			else{
			
		$res=$this->tarrif_model->editValues($data,$id);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Updated Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif-masters');
		}
		}
	}
	if(isset($_REQUEST['delete'])){
	 $id= $this->input->post('manage_id');
	 $res=$this->tarrif_model->deleteValues($id);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Deleted Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif-masters');
		}
	}

	}
	else{
			$this->notAuthorized();
			}
	}
	
	public function tarrif_manage(){
	if($this->session_check()==true) {
	if(isset($_REQUEST['tarrif-add'])){
	$data['tariff_master_id']=$this->input->post('select_tariff');
	$data['vehicle_model_id']=$this->input->post('vehicle_model');
	$data['vehicle_ac_type_id']=$this->input->post('vehicle_ac_type');
	if($this->input->post('customers')!=''){
			 $data['customer_id']=$this->input->post('customers');
		}else{
			 $data['customer_id']=gINVALID;
		}
	$data['from_date']=$this->input->post('fromdatepicker');
	$data['rate']=str_replace(",","",$this->input->post('rate'));
	$data['additional_kilometer_rate']=str_replace(",","",$this->input->post('additional_kilometer_rate'));
	$data['additional_hour_rate']=str_replace(",","",$this->input->post('additional_hour_rate'));
	$data['driver_bata']=str_replace(",","",$this->input->post('driver_bata'));
	$data['night_halt']= str_replace(",","",$this->input->post('night_halt'));
	$data['organisation_id']=$this->session->userdata('organisation_id'); //print_r($data);exit;
	 $data['user_id']=$this->session->userdata('id');
	 $this->form_validation->set_rules('select_tariff','Tariff Master','trim|required|xss_clean|numeric');
	 $this->form_validation->set_rules('vehicle_model','Vehicle model','trim|required|xss_clean|numeric');
	 $this->form_validation->set_rules('vehicle_ac_type','Vehicle Ac type','trim|required|xss_clean|numeric');
	 $this->form_validation->set_rules('fromdatepicker','Date ','trim|xss_clean');
	 $this->form_validation->set_rules('rate','Rate','trim|required|xss_clean');
	 $this->form_validation->set_rules('additional_kilometer_rate','Kilometer Rate','trim|required|xss_clean');
	 $this->form_validation->set_rules('additional_hour_rate','Hour Rate','trim|required|xss_clean');
	 $this->form_validation->set_rules('driver_bata','Driver Bata','trim|required|xss_clean');
	 $this->form_validation->set_rules('night_halt','Night Halt','trim|required|xss_clean');
	 $err=True;
	if(!$this->date_check($data['from_date'])){
	$err=False;
	$this->session->set_userdata('Err_dt','Invalid Date for Tariff Add!');
	}
	 if($data['tariff_master_id'] ==-1){
	 $data['tariff_master_id'] ='';
	 $err=False;
	 $this->session->set_userdata('select_tariff','Choose Tariff Master');
	 }
	 if($data['vehicle_model_id'] ==-1){
	 $data['vehicle_model_id'] ='';
	 $err=False;
	 $this->session->set_userdata('vehicle_model','Choose Vehicle Model');
	 }
	if($data['vehicle_ac_type_id'] ==-1){
	 $data['vehicle_ac_type_id'] ='';
	 $err=False;
	 $this->session->set_userdata('vehicle_ac_type','Choose Ac Type');
	 }
	 
	 if(($this->form_validation->run()==False) || ($err==False)){ 
		$this->session->set_userdata('post',$data);
		redirect(base_url().'organization/front-desk/tarrif',$data);	
	 }
	 else{
	 $res=$this->tarrif_model->addTariff($data);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Added Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif');
		}
		else{
		$this->session->set_userdata('post',$data);
		$this->session->set_userdata(array('Err_date'=>'Invalid Date!'));
		redirect(base_url().'organization/front-desk/tarrif');
		}
	 }
	}
	if(isset($_REQUEST['edit'])){
	 $id= $this->input->post('manage_id');
	 $data['tariff_master_id'] = $this->input->post('manage_tariff');
	 $data['vehicle_model_id']=$this->input->post('vehicle_model');
	 $data['vehicle_ac_type_id']=$this->input->post('vehicle_ac_type');
		if($this->input->post('customers')!=''){
			 $data['customer_id']=$this->input->post('customers');
		}else{
			 $data['customer_id']=gINVALID;
		}
	 $data['from_date'] = $this->input->post('manage_datepicker');
	 $h_dtpicker=$this->input->post('h_dtpicker');
	 $data['rate'] =  str_replace(",","",$this->input->post('manage_rate'));
	 $data['additional_kilometer_rate'] = str_replace(",","",$this->input->post('manage_additional_kilometer_rate'));
	 $data['additional_hour_rate'] = str_replace(",","",$this->input->post('manage_additional_hour_rate'));
	 $data['driver_bata'] = str_replace(",","",$this->input->post('manage_driver_bata'));
	 $data['night_halt'] = str_replace(",","",$this->input->post('manage_night_halt'));
	
	
		$err=False;
		if($h_dtpicker!=$data['from_date'] ){
		if(!$this->date_check($data['from_date'])){
		$err=true;
		$this->session->set_userdata('Err_m_dt','Invalid Date for Tariff !');
		}
		}
		if($data['tariff_master_id']==-1){
			$this->session->set_userdata(array('Err_m_tarrif'=>'Tariff Master  Required..!'));
			$err=true;
			}
		if($data['vehicle_model_id']==-1){
			$this->session->set_userdata(array('Err_m_vid'=>'Vehicle Model Required..!'));
			$err=true;
			}
		if($data['vehicle_ac_type_id']==-1){
			$this->session->set_userdata(array('Err_ac_vid'=>'Ac type Required..!'));
			$err=true;
			}
		if($data['from_date']==''){
			$this->session->set_userdata(array('Err_m_date'=>'From Date Required..!'));
			$err=true;
			}
		if($data['rate']==''){
			$this->session->set_userdata(array('Err_m_rate'=>'Rate Required..!'));
			$err=true;
			}
		if($data['additional_kilometer_rate']==''){
			$this->session->set_userdata(array('Err_m_krate'=>'Additional Kilometer Rate Required..!'));
			$err=true;
			}
		if($data['additional_hour_rate']==''){
			$this->session->set_userdata(array('Err_m_hrate'=>'Additional Hour Rate Required..!'));
			$err=true;
			}
		if($data['driver_bata']==''){
			$this->session->set_userdata(array('Err_m_bata'=>'Driver Bata Required..!'));
			$err=true;
			}
		if($data['night_halt']==''){
			$this->session->set_userdata(array('Err_m_halt'=>'Night Halt Required..!'));
			$err=true;
			}
		/*if(preg_match('#[^0-9\.]#', $data['rate'])){
			$this->session->set_userdata(array('Err_rate'=>'Invalid Characters on Rate field!'));
			$err=true;
			}*/
		/*if(preg_match('#[^0-9\.]#', $data['additional_kilometer_rate'])){
			$this->session->set_userdata(array('Err_add_kilo'=>'Invalid Characters on Kilometers field!'));
			$err=true;
			}*/
		/*if(preg_match('#[^0-9\.]#', $data['additional_hour_rate'])){
			$this->session->set_userdata(array('Err_add_hrs'=>'Invalid Characters on Hours field!'));
			$err=true;
			}*/
		/*if(preg_match('#[^0-9\.]#', $data['driver_bata'])){
			$this->session->set_userdata(array('Err_bata'=>'Invalid Characters on Driver Bata field!'));
			$err=true;
			}	
		if(preg_match('#[^0-9\.]#', $data['night_halt'])){
			$this->session->set_userdata(array('Err_halt'=>'Invalid Characters on Night Halt field!'));
			$err=true;
			}*/
			if($err==true){
			redirect(base_url().'organization/front-desk/tarrif');
			}
			else{ 
			
		$res=$this->tarrif_model->edit_tarrifValues($data,$id);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Updated Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif');
		}
		}
	}
			if(isset($_REQUEST['delete'])){
	 $id= $this->input->post('manage_id');
	 $res=$this->tarrif_model->delete_tarrifValues($id);
		if($res==true){
		$this->session->set_userdata(array('dbSuccess'=>' Deleted Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/tarrif');
		}
	}
	}
	
	else{
			$this->notAuthorized();
			}
	}

	public function tariffSelecter(){
	if(isset($_REQUEST['vehicle_ac_type']) && isset($_REQUEST['vehicle_model'])){

	
	$data['vehicle_ac_type']=$_REQUEST['vehicle_ac_type'];
	$data['vehicle_model']=$_REQUEST['vehicle_model'];
	$data['organisation_id']=$this->session->userdata('organisation_id');

	$res['data']=$this->tarrif_model->selectAvailableTariff($data);
	if(count($res['data'])>0){
	echo json_encode($res);
	}else{
	echo 'false';
	}

	}	
	}

	public function customertariff(){
	if(isset($_REQUEST['vehicle_ac_type_id']) && isset($_REQUEST['vehicle_model_id']) && isset($_REQUEST['tariff_master_id'])){

		$data['vehicle_ac_type_id']=$_REQUEST['vehicle_ac_type_id'];
		$data['vehicle_model_id']=$_REQUEST['vehicle_model_id'];
		$data['tariff_master_id']=$_REQUEST['tariff_master_id'];
		if($_REQUEST['from']=='trip-booking'){

			$data['customer_id']=$this->session->userdata('customer_id');

		}else if($_REQUEST['from']=='trip-voucher'){

			$data['customer_id']=$_REQUEST['customer_id'];

		}
		$data['organisation_id']=$this->session->userdata('organisation_id');
		if($this->session->userdata('customer_id')!='' || $_REQUEST['customer_id']!=''){
		$res['data']=$this->tarrif_model->getCustomerTariff($data);
		if(count($res['data'])>0){
		echo json_encode($res);
		}else{
			echo 'false';
		}
		}else{
			echo 'false';
		}

		}else{
			echo 'false';
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
	
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){ 
	return true;
	}
	}
}
