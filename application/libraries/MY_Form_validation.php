<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://ellislab.com/codeigniter/user-guide/general/creating_libraries.html
// to call default library function array_map(array($this->CI->security, 'xss_clean'), $shipping_info);


class MY_Form_validation extends CI_Form_validation
{
		
	 // http://tutsnare.com/custom-form-validation-in-codeigniter/
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->CI->load->library('validation');
		
	}
	
	
	public function is_valid_captcha($str){
		
		$word = $this->CI->session->captchaWord;
		
		//echo $str.'---'.$word;
		//die;
	
		//if(strcmp(strtoupper($str),strtoupper($word)) == 0){// case insensitive
			
		
		
		if(strcmp($str,$word) == 0){ // case sensitive
		  return true;
		}
		else{
		  $this->CI->form_validation->set_message('is_valid_captcha', 'Please enter correct verification code (case sensitive).');
		  return false;
		}
	 }
  
  
	
	 /**
     * MY_Form_validation::alpha_extra().
     * Alpha-numeric with periods, underscores, spaces and dashes.
     */
    public function is_alpha_extra($str) {
		
	   $this->CI->form_validation->set_message('is_alpha_extra', 'The %s may only contain alpha-numeric characters, spaces, periods, underscores & dashes.');
	   
	   if($this->CI->validation->isAlphaExtra($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	
	public function is_alpha_numeric($str) {
		
	   $this->CI->form_validation->set_message('is_alpha_numeric', 'The %s may only contain alpha-numeric characters.');
	   
	   if($this->CI->validation->isAlphaNumeric($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	
	
	public function is_valid_salutation($str) {
		
        $this->CI->form_validation->set_message('is_valid_salutation', 'Please enter a valid %s.');
		
		
	 
	   if($this->CI->validation->is_listed_value($str, array('Mr','Ms','Dr','Prof','Shri','Smt')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	public function is_valid_award_rank($str) {
		
        $this->CI->form_validation->set_message('is_valid_award_rank', 'Please enter a valid %s.');
		
		
	 
	   if($this->CI->validation->is_listed_value($str, array('Platinum','Gold','Silver')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	public function is_valid_winner_role($str) {
		
        $this->CI->form_validation->set_message('is_valid_winner_role', 'Please enter a valid %s.');
		
		
	 
	   if($this->CI->validation->is_listed_value($str, array('Team Leader','Team Member')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	public function is_valid_government($str) {
		
        $this->CI->form_validation->set_message('is_valid_government', 'Please enter a valid %s.');
		
		
	 
	   if($this->CI->validation->is_listed_value($str, array('Union','State','None')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	public function is_valid_state_code($str) {
		
	   $this->CI->form_validation->set_message('is_valid_state_code', 'Please enter a valid %s.');
	   
	   if($this->CI->validation->isValidStateCode($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	
	
	
	
	public function is_valid_name($str) {
		
        $this->CI->form_validation->set_message('is_valid_name', 'The %s may only contain alphabetic, spaces & periods.');
		
		
	   if($this->CI->validation->isValidName($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_valid_email($str) {
		
		$str=$this->CI->validation->decodeEmail($str); // [at] => @, [dot] => .
		
        $this->CI->form_validation->set_message('is_valid_email', 'Please enter a valid %s.');
		
		
	   if($this->CI->validation->isEmail($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	public function have_valid_email_chars($str) {
		
		
		
        $this->CI->form_validation->set_message('have_valid_email_chars', 'Invalid charcters found in %s.');
		
		
	   if($this->CI->validation->haveValidEmailChars($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	public function is_valid_mobile($str) {
		
        $this->CI->form_validation->set_message('is_valid_mobile', 'Please enter a valid %s.');
		
		
	   if($this->CI->validation->isValidMobile($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_mobile_ten_digits($str) {
		
        $this->CI->form_validation->set_message('is_mobile_ten_digits', 'Please enter ten digits %s.');
		
		
	   if($this->CI->validation->isMobileTenDigits($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	
	public function is_valid_pincode($str) {
		
        $this->CI->form_validation->set_message('is_valid_pincode', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidPinCode($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	
	public function is_valid_stdcode($str) {
		
        $this->CI->form_validation->set_message('is_valid_stdcode', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidStdCode($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
		
	public function is_valid_phone($str) {
		
        $this->CI->form_validation->set_message('is_valid_phone', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidPhone3($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	public function is_valid_intercom($str) {
		
        $this->CI->form_validation->set_message('is_valid_intercom', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidIntercom($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_valid_text($str) {
		
        $this->CI->form_validation->set_message('is_valid_text', 'The %s contains invalid characters.');
		
		
	   if($this->CI->validation->isValidText($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_valid_address($str) {
		
        $this->CI->form_validation->set_message('is_valid_address', 'The %s contains invalid characters.');
		
		
	   if($this->CI->validation->isValidAddress($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	public function is_valid_designation($str) {
		
        $this->CI->form_validation->set_message('is_valid_designation', 'The %s contains invalid characters.');
		
		
	   if($this->CI->validation->isValidDesignation($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	

	
	
	public function is_valid_fax($str) {
		
        $this->CI->form_validation->set_message('is_valid_fax', 'Please enter a valid %s.');
		
		
	   if($this->CI->validation->isValidPhone3($str)) // fax is same as phone number
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_valid_md5($str) {
		
        $this->CI->form_validation->set_message('is_valid_md5', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidMd5($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_valid_sha256($str) {
		
        $this->CI->form_validation->set_message('is_valid_sha256', 'Please enter valid %s.');
		
		
	   if($this->CI->validation->isValidSha256($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	
	public function is_url($str) {
		
        $this->CI->form_validation->set_message('is_url', 'Please enter valid %s.');

	   if($this->CI->validation->isURL($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	
	public function is_integer($str) {
		
        $this->CI->form_validation->set_message('is_integer', 'Please enter valid %s.');

	   if($this->CI->validation->isInteger($str))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
	  
    }
	
	public function is_yes_no($str) {
		
	   $this->CI->form_validation->set_message('is_yes_no', 'Please enter valid %s.');
	   
	   if($this->CI->validation->is_listed_value($str, array('yes','no')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	

	public function is_zero_one($str) {
		
	   $this->CI->form_validation->set_message('is_zero_one', 'Please enter/select valid %s.');
	   
	   if($this->CI->validation->is_listed_value($str, array('0','1')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	
	public function is_valid_role($str) {
		
	   $this->CI->form_validation->set_message('is_valid_role', 'Please enter valid %s.');
	   
	   if($this->CI->validation->is_listed_value($str, array('creator', 'publisher')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }  
     
    }
	
	
	public function is_valid_download_type($str) {
		
        $this->CI->form_validation->set_message('is_valid_download_type', 'Please enter a valid %s.');
		
		
	 
	   if($this->CI->validation->is_listed_value($str, array('xls','json')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	public function is_valid_entry_value($str) {
		
        $this->CI->form_validation->set_message('is_valid_entry_value', 'Please enter a valid %s.');
		
	   if($this->CI->validation->is_listed_value($str, array('yes','no')))
	   {
		   return TRUE;
	   }
	   else
	   {
		   return FALSE;
	   }   
	  
    }
	
	
	
	
	
	
	/*
	Note :- function body must have a message $this->form_validation->set_message(‘rule’, ‘Error Message’); and return a Boolean value TRUE/FALSE.
	*/
    // add more function to apply custom rules.
	
}
