<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://ellislab.com/codeigniter/user-guide/general/creating_libraries.html
// to call default library function array_map(array($this->CI->security, 'xss_clean'), $shipping_info);


class Front{
	
	private $CI;
	
	public function __construct(){
			
		$this->CI =& get_instance();
	}

	public function get_lang(){

	    $lang = $this->CI->input->get('lang');

	    if(empty($lang) || !in_array($lang,array('hi','en'))) $lang = 'en';

	    return $lang;
    }
	
}
