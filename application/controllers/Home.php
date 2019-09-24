<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	
	
	public function __construct()
	{
			parent::__construct();
			
			$this->load->library('front');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 
	 */
	 
	 
	 public function index($ln='')
	{

		
		if($ln=='hi')
			$data['title'] ='मुख्य पृष्ठ';
		else
			$data['title'] = 'Home';


		if($ln=='hi')
			$this->body = 'home/hi/index';
		else
			$this->body = 'home/index';
			
		$data['ln']=$ln;	
		
		
		$this->data = $data;
		$this->layout();
	}
	 
	
}
