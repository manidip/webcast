<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
			
			//$this->load->model('category_model');
			$this->load->model('log_model');	
			
			$this->load->library('my_form_validation');
			$this->load->library('pagination');
			$this->load->library('admin');
			
			//$this->load->library('validation'); //loaded in my_form_validation
			
			
			$this->admin->check_sess_timeout();
	}


	public function index()
	{

		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		//$controller=$this->router->fetch_class();
		//$method=$this->router->fetch_method();
		
		$errArr=array();
		
		$data['title'] = 'Dashboard';
		
		//print_r($this->session);
		//echo session_id();

		$msg=$this->input->get('msg');
		
		if($msg=='auth_error')
		{
			$error_message="You are not authorized for this action.";
			$data['error_message']=$error_message;
		}
		
		
		//$user_count=$this->admin_user_model->get_user_count();
		//$data['user_count']=$user_count;
		
		//echo $user_count; die;
		
		
		
		
		
		//$category_count=$this->category_model->get_category_count();
		//$data['category_count']=$category_count;
		
		//echo $category_count; die;
		
		/*
		$catArr=$this->category_model->get_categories();
		
		foreach($catArr as $catKey=>$catRec)
		{
			$catRec->total_nominations=$this->nomination_model->get_nominations_count($catRec->cat_id);
			$catRec->completed_nominations=$this->nomination_model->get_completed_nominations_count($catRec->cat_id);
			$catRec->canceled_nominations=$this->nomination_model->get_canceled_nominations_count($catRec->cat_id);
			$catRec->incomplete_nominations=$this->nomination_model->get_incomplete_nominations_count($catRec->cat_id);
			
			$catArr[$catKey]=$catRec;
			
		}
		
		$data['catArr']=$catArr;*/
		
		//print_r($catArr);
		
		//echo $category_count; die;
		
		//$error_message=implode(' ',$errArr);
	   // $data['error_message']=$error_message;
		
		
		//$this->load->view('home/index');
		$this->body = 'dashboard/index';
		$this->data = $data;
		$this->layout('inner');

	}
	

}
