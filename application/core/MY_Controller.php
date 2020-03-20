<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//http://tutsnare.com/creating-a-layout-in-codeigniter/
//http://www.stepblogging.com/how-to-create-layouts-in-codeigniter-3-0/

class MY_Controller extends CI_Controller 
{ 
	//set the class variable.
	public $template  = array();
	public $data      = array();
	
	//Load layout    
   public function layout($layout='default') {
		// making temlate and send data to view.
		//$this->template['header']   = $this->load->view('layout/header', $this->data, true);
		// $this->template['left']   = $this->load->view('layout/left', $this->data, true);
		//$this->template['body'] = $this->load->view($this->body, $this->data, true);
		// $this->template['footer'] = $this->load->view('layout/footer', $this->data, true);
		//$this->load->view('layout/index', $this->template);
		
		$this->template['body'] = $this->load->view($this->body, $this->data, true);
		$this->load->view('layouts/'.$layout, $this->template);
   }
}