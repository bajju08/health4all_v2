<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_panel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('reports_model');		
		$this->load->model('staff_model');	
		$this->data['op_forms']=$this->staff_model->get_forms("OP");
		$this->data['ip_forms']=$this->staff_model->get_forms("IP");	
	}
	function form_layout(){
		if($this->session->userdata('logged_in')){
		$this->load->helper('form');
		$this->data['title']="User Panel";
		$data['userdata']=$this->session->userdata('logged_in');
		$data['print_layouts']=$this->staff_model->get_print_layouts();
		$this->load->view('templates/header',$this->data);
		$this->load->view('pages/form_layout',$data);
		$this->load->view('templates/footer');	
		}
		else{
			show_404();
		}
	}
	function create_user(){
		if($this->session->userdata('logged_in')){
		$this->load->helper('form');
		$this->data['title']="Create User";
		$data['userdata']=$this->session->userdata('logged_in');
		$data['user_functions']=$this->staff_model->get_user_function();
		$data['staff']=$this->staff_model->get_staff();
		$this->load->view('templates/header',$this->data);
		$this->load->view('templates/leftnav',$this->data);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() === FALSE){
			$this->load->view('pages/create_user',$data);
		}
		else{
			if($this->staff_model->create_user()){
				$data['msg']="User created successfully";
				$this->load->view('pages/create_user',$data);
			}
			else{
				$data['msg']="Error creating user. Please retry.";
				$this->load->view('pages/create_user',$data);
			}
		}
		$this->load->view('templates/footer');	
		}
		else{
			show_404();
		}
	}
	function create_form(){
		if($this->session->userdata('logged_in')){
				if($this->staff_model->upload_form()){
					echo 1;
				}
				else echo 0;
		}
	}
	function settings(){
		if($this->session->userdata('logged_in')){
		$this->load->helper('form');
		$this->data['title']="User Panel";
		$data['userdata']=$this->session->userdata('logged_in');
		$this->load->view('templates/header',$this->data);
		$this->load->view('templates/leftnav',$this->data);
		$this->load->view('pages/settings',$data);
		$this->load->view('templates/footer');	
		}
		else{
			show_404();
		}
	}

}
