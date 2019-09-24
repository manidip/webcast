<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://ellislab.com/codeigniter/user-guide/general/creating_libraries.html
// to call default library function array_map(array($this->CI->security, 'xss_clean'), $shipping_info);

class Admin
{
	
	private $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();	
	}


	public function is_user_loggedin(){
		if(!empty($this->CI->session->admin_session->id))
		{
			return true;
		}
		else
		{
			return false;
		}
	 }
	 
	 public function get_loggedin_user(){
		 
		 
		$uid=$this->CI->session->admin_session->id;
	
		$userRec=$this->CI->admin_user_model->get_user($uid);
		
	
		return $userRec;

		
	 }



	public function check_sess_timeout(){

		
		$session=$this->CI->session;
		$config=$this->CI->config;
		

		//print_r($session->admin_session);
		//echo $session->admin_session->last_activity_time;
		
		$last_activity_timeout=$config->item('last_activity_timeout');
		
		if( !empty($session->admin_session->last_activity_time) && ( time() - $session->admin_session->last_activity_time > $last_activity_timeout) )
		{
				$session->admin_session=''; // $_SESSION['admin_session']='';
				$session->unset_userdata('admin_session'); // unset($_SESSION['admin_session']); 
				$session->sess_destroy(); //session_destroy();
				$session->sess_regenerate(true); //session_regenerate_id(true);
		}
		
		if($session->admin_session)
			$session->admin_session->last_activity_time=time();
		
		
		//////////enforce user to change password at his/her first login////////
		$uid=$session->admin_session->id; // logged in user
		
		if(!empty($uid)) // process this code only if the user is logged in
		{
			$userRec=$this->CI->admin_user_model->get_user($uid);
			//print_r($userRec);
			if($userRec->password_changed==0)
			{
				$controller=$this->CI->router->fetch_class();
				$method=$this->CI->router->fetch_method();
				
				if( ($controller=='home' && $method=='change_password') || ($controller=='home' && $method=='logout')) //means page is change password or is logout page
				{
					// do nothing
				}
				else
				{
					////////////redirect user to change password page on first login////////////
					redirect('/admin/home/change_password');
					exit;	
				}
			}
		}
		////////////////////////////

	}
	
}
