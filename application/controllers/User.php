<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->database();
        $this->load->model('Usermodel');
		/*cash control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
         

    }
         
		//Method for view login page 
		public function index(){
	    if($this->session->userdata('logged_in')==TRUE){
        redirect('Admin/dashboard');    
        }
        $data=array();
		$this->load->view('theme/login');	
	   }

	   //Method for varify login information 
	   public function varifyUser(){
        if($this->session->userdata('logged_in')==TRUE){
        redirect('Admin/dashboard');    
        }
        $email=$this->input->post('email',true);
        $password=$this->input->post('password',true);
        if($this->Usermodel->login($email,md5($password))){
        echo "true";
        }else{
        echo "False";
        }

	   }
	   
	   //Method for logout
	   public function logout(){
	   	$this->Usermodel->logout();
	   	redirect('User');
	   }


}