<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('account_model');
		no_cache();
	}

	public function index($param1 ='')
	{

	}


	public function getTotalTax()
	{
		$tax_group = $_REQUEST['id'];
		$amount = $_REQUEST['amt'];
		
		$res = $this->account_model->get_tax_group_rates($tax_group);
		$tax = 0;
		foreach($res as $row){

			$taxable_amount = $amount*40/100;
			$tax += ($taxable_amount*$row['rate']/100);
		}
		echo round($tax,2);
		
		
	}
	
	//cnc organization create company in fa
	public function add_accounts($org_id = -1)
	{
		if($this->admin_session_check()==true) {
			$data['url'] = "facnc/admin/create_coy.php?NewCompany=".$org_id."&cnc_token=".$this->session->userdata('session_id');
			$data['title']="Create company account | ".PRODUCT_NAME;	
			$page='fa-modules/module';
			$this->load_admin_templates($page,$data);
			
		}else{
				$this->notAuthorized();
		}
	}
	
	//admin
	public function admin($action='None'){
		
		if($this->admin_session_check()==true) {
		$data['title'] = $action." | ".PRODUCT_NAME;
		$data['url'] = "facnc/sync_cnc.php?".$action."=Yes&cnc_token=".$this->session->userdata('session_id');
			$page='fa-modules/module';
			$this->load_admin_templates($page,$data);
		}
	  	else{
			$this->notAuthorized();
		}
	}

	
	//organisation admin pages from fa
	public function organization($action='None'){
		
		if($this->org_admin_session_check()==true) {
		$data['title'] = $action." | ".PRODUCT_NAME;
		$data['url'] = "facnc/sync_cnc.php?".$action."=Yes&cnc_token=".$this->session->userdata('session_id');
			$page='fa-modules/module';
			$this->load_admin_templates($page,$data);
		}
	  	else{
			$this->notAuthorized();
		}
	}

	//organisation user pages from fa
	public function front_desk($action='None',$value='',$tab = false){
	
		
		if($this->org_user_session_check()==true|| $this->customer_session_check()==true || $this->driver_session_check()==true) {
			$data['title'] =$action." | ".PRODUCT_NAME;
			if($value)
				$data['url'] = "facnc/sync_cnc.php?".$action."=".$value."&cnc_token=".$this->session->userdata('session_id');
			else
				$data['url'] = "facnc/sync_cnc.php?".$action."=Yes&cnc_token=".$this->session->userdata('session_id');

			$page='fa-modules/module';
			
			if($tab)
				$this->load->view($page,$data);
			else
				$this->load_admin_templates($page,$data);
		}
	  	else{
			$this->notAuthorized();
		}
	}


	//cnc organization user create user account in fa
	public function add_user($user_id = -1)
	{
		if($this->org_admin_session_check()==true) {
			$data['url'] = "facnc/admin/users.php?cnc_token=".$this->session->userdata('session_id');
			if($user_id > 0)
				$data['url'] .= "&NewUser=".$user_id;
			$data['title']="Create User account | ".PRODUCT_NAME;	
			$page='fa-modules/module';
			$this->load_admin_templates($page,$data);
			
		}else{
			$this->notAuthorized();
		}
	}

	//save driver trip to account
	public function driver_trip_save()
	{
		if($this->org_user_session_check() == true) {
			
			echo "ok";exit;
			$this->account_model->add_gl_trans($_REQUEST['driver_id'],$_REQUEST['amount']);
			

		}else{
			$this->notAuthorized();
		}
	}


	//admin templates
	public function load_admin_templates($page='',$data=''){
	
		if($this->admin_session_check()==true || $this->org_admin_session_check()==true || $this->org_user_session_check()==true || $this->driver_session_check()==true) {
		    	$this->load->view('admin-templates/header',$data);
			$this->load->view('admin-templates/nav');
			$this->load->view($page,$data);
			$this->load->view('admin-templates/footer');
		}else{
			$this->notAuthorized();
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

	//check system administrator logged in 
	public function admin_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')== SYSTEM_ADMINISTRATOR) ) {
			return true;
		} else {
			return false;
		}
	}
	
	//check organization administrator logged in 
	public function org_admin_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')== ORGANISATION_ADMINISTRATOR) ) {
			return true;
		} else {
			return false;
		}
	}

	public function org_user_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) 	{
			return true;
		} else {
			return false;
		}
	} 
	
	public function customer_session_check() {
		if(($this->session->userdata('isLoggedIn')==true )&&($this->session->userdata('type')== CUSTOMER)) 		{
			return true;
		} else {
			return false;
		}
	} 

	public function driver_session_check() {
		if(($this->session->userdata('isLoggedIn')==true )&&($this->session->userdata('type')== DRIVER)) 		{
			return true;
		} else {
			return false;
		}
	}



}
?>
