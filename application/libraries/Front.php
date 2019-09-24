<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://ellislab.com/codeigniter/user-guide/general/creating_libraries.html
// to call default library function array_map(array($this->CI->security, 'xss_clean'), $shipping_info);


class Front{
	
	private $CI;
	
	public function __construct(){
			
		$this->CI =& get_instance();
		
		
	}
	
	
	
	public function check_sess_timeout(){

		
		$session=$this->CI->session;
		$config=$this->CI->config;
		
		
		//print_r($session->user_session);
		//echo $session->user_session->last_activity_time;
		
		$last_activity_timeout=$config->item('last_activity_timeout');
		
		if( !empty($session->user_session->last_activity_time) && ( time() - $session->user_session->last_activity_time > $last_activity_timeout) )
		{
				$session->user_session=''; // $_SESSION['user_session']='';
				$session->unset_userdata('user_session'); // unset($_SESSION['user_session']); 
				$session->sess_destroy(); //session_destroy();
				$session->sess_regenerate(true); //session_regenerate_id(true);
		}
		
		
		

		if($session->user_session)
		{
			$session->user_session->last_activity_time=time();

		}

	}
	 
	  public function get_user_details($id){

		$userRec=$this->CI->user_model->get_user($id);

		return $userRec;
	 }
	 
	
	 
	 
	 
	  
	
}
