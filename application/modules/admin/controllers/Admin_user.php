<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
		
			$this->load->model('stateind_model');
			
			
			
			//$this->load->model('category_model');
			$this->load->model('log_model');	
			
		
			
			$this->load->library('my_form_validation');
			$this->load->library('pagination');
			$this->load->library('admin');
			
			$this->load->library('email');
			
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
		
		$errArr=array();
		
		$uid=$this->session->admin_session->id; // logged in user
		$userRec=$this->admin_user_model->get_user($uid);
		
		if($userRec->role!='admin') 
		{
			//show_error('You are not authorized for this action');
			redirect('/admin/dashboard/index?msg=auth_error');
			exit;
		}
		
		$data['title'] = 'List';
		
		
		
		
		$msg=$this->input->get('msg');
		
		if($msg=='added')
		{	
			$success_message="User has been added successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='updated')
		{
			$success_message="User has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='deleted')
		{
			$success_message="User has been deleted successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='error')
		{
			$error_message="An error has occured.";
			$data['error_message']=$error_message;
		}
		else if($msg=='delete_error')
		{
			$error_message="Action could not be completed.";
			$data['error_message']=$error_message;
		}
		
	
	

		
		$this->form_validation->set_data($this->input->get()); // to validate GET array
		
		$this->form_validation->set_rules('search_kw', 'Search Keyword', 'trim|strip_tags|xss_clean|max_length[100]|is_valid_text');
		
		$this->form_validation->set_rules('ipp', 'ipp', 'trim|strip_tags|xss_clean|max_length[3]|is_natural_no_zero'); // is_natural_no_zero or is_integer (includes zero)
		
		$this->form_validation->set_rules('active', 'Status', 'trim|is_zero_one'); 
		
		
		
		$formValidated=$this->form_validation->run();
			
		if($formValidated===FALSE)
		{
			$validationErrors=validation_errors();
			if(!empty($validationErrors))
			{
				$errArr[]=validation_errors(); // returns validation errors
			}
			
			$search_kw='';
			$active='';
			$ipp=10;
			
		}
		else
		{
			$search_kw=$this->input->get('search_kw');
			$active=$this->input->get('active');
			$ipp=$this->input->get('ipp');
		}


		$sort_option=$this->input->get('sort_option');
	
		
		if(!empty($sort_option))
		{
			$sortOptArr=explode('|',$sort_option);	
			
			$sort_field=$sortOptArr[0];
			$sort_order=$sortOptArr[1];
			
			
			if(!in_array($sort_field, array('admin_user.created', 'admin_user.fname')))
			{
				$errArr[]='Invalid Sort Field.';	
				$sort_field='admin_user.created';
				$sort_option='';
			}
			
			if(!in_array($sort_order, array('asc', 'desc')))
			{
				$errArr[]='Invalid Sort Order.';
				$sort_order='desc';
				$sort_option='';
			}
			
		}
		else
		{
			
			$sort_field='admin_user.created';
			$sort_order='desc';
			$sort_option=$sort_field.'|'.$sort_order;
			
		}

		
		$pagination_config = $this->config->item('pagination_config'); // pagination config array define in \application\config\config.php	
		
		 //$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; //use if if $config['page_query_string']=FALSE;
		
		$page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
		
	
		
		if(!$this->validation->isInteger($page))
		{
			//show_error('Invalid argument provided');
			$errArr[]='Invalid Page Number';
			$page=1;
			
		}
		
		
		$optArr=array(
			'search_kw'=>$search_kw,
			'active'=>$active
		);
		
		//echo "<pre>";
		//print_r($optArr);
		
		
		$allUsrArr=$this->admin_user_model->get_users(0, 0, $sort_field, $sort_order, $optArr);
		
		$total_rows=count($allUsrArr);
	
		$pagination_config['total_rows']=$total_rows;
		$pagination_config['base_url'] = base_url() . "admin/admin_user/index"; 
		
		if(!empty($ipp))
			$per_page=$ipp;
		else{
			$per_page=$pagination_config['per_page'];
			
			$per_page=10;
			
			$ipp=$per_page;
		}
			

			
		$pagination_config['per_page']=$per_page; // override config parameter
		
		
		
        $this->pagination->initialize($pagination_config);
		
       
		
		$start=($page-1)*$per_page;
		$limit=$per_page;
		
		
		$data["admin_users"] = $this->admin_user_model->get_users($start, $limit, $sort_field, $sort_order, $optArr);	
		
		$data["paging_links"] = $this->pagination->create_links();

		$data["page"] =$page;
		$data["per_page"] =$per_page;
		$data["total_rows"] =$total_rows;
		$data["search_kw"] =$search_kw;
		$data["sort_option"] =$sort_option;
		$data["ipp"] =$ipp;
		
		$data["active"]=$active;
		
		
		$data["added_by"]=$added_by;
		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
		
	    $data['error_message']=$error_message;
		
		
		$data['userRec'] = $userRec;
		
		//$this->load->view('admin_user/index');
		$this->body = 'admin_user/index';
		$this->data = $data;
		$this->layout('inner');
		
	}
	
	
	public function add()
	{


		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		$errArr=array();
		
		$uid=$this->session->admin_session->id; // logged in user
		$userRec=$this->admin_user_model->get_user($uid);
		
		if($userRec->role!='admin') 
		{
			//show_error('You are not authorized for this action');
			redirect('/admin/dashboard/index?msg=auth_error');
			exit;
		}
		
		
		$id=$this->input->get('id');
		
		//echo $id; die;
		
		if(!empty($id)) // edit
		{

			$data['title'] = 'Edit';
			$data['action_btn_title'] = 'Edit';
			
			if(is_array($id)) // auditor passes this field as an array that throws an error
				$id=0;
			
			if(!$this->validation->isInteger($id))
			{
				$errArr[]='Invalid User ID.';
			}
			else
			{
				$data['id']=$id;
				$usrRec=$this->admin_user_model->get_user($id);
				$data['usrRec']=$usrRec;
			}				
		}
		else
		{
			$data['title'] = 'Add';	
			$data['action_btn_title'] = 'Add';
		}
		
		
	
		$this->form_validation->set_rules('fname', 'First Name', 'trim|strip_tags|xss_clean|required|max_length[100]|is_valid_name');
		
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|strip_tags|xss_clean|required|max_length[100]|is_valid_name');
		
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|strtolower|is_valid_email');
		
		
		if(!empty($id)) // edit
		{
			if($this->input->post('password')) // if password is updated
			{
				$this->form_validation->set_rules('password', 'Password', 'trim|required|is_valid_sha256');
				$this->form_validation->set_rules('cpassword', 'Retype Password', 'trim|required|is_valid_sha256');
			}			
		}
		else
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|is_valid_sha256');
			$this->form_validation->set_rules('cpassword', 'Retype Password', 'trim|required|is_valid_sha256');
		}
		
		
		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|is_mobile_ten_digits');
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required|max_length[100]|strip_tags|xss_clean|is_valid_designation');
		$this->form_validation->set_rules('organization', 'Organization', 'trim|max_length[255]|strip_tags|xss_clean|is_valid_address');
		$this->form_validation->set_rules('address_line', 'Address Line', 'trim|max_length[100]|strip_tags|xss_clean|is_valid_address');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|strip_tags|xss_clean|is_valid_text');
		$this->form_validation->set_rules('state', 'State', 'trim|is_integer'); // is_natural_no_zero or is_integer (includes zero)
		$this->form_validation->set_rules('pin_code', 'Pin Code', 'trim|is_valid_pincode'); 
		$this->form_validation->set_rules('std_code', 'STD Code', 'trim|is_valid_stdcode');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|is_valid_phone');
		$this->form_validation->set_rules('intercom', 'Intercom', 'trim|is_valid_intercom');
		
		
		
		
		if(!empty($id)) // edit
		{
			if($usrRec->role!='admin') ///Role is NOT required if you are editing Admin user
			{
				$this->form_validation->set_rules('role', 'Role', 'trim|required|is_valid_role');
				$this->form_validation->set_rules('active', 'Status', 'trim|required|is_zero_one');  // same for status
			}
		}
		else // role is required in case of Add
		{
			$this->form_validation->set_rules('role', 'Role', 'trim|required|is_valid_role');
			$this->form_validation->set_rules('active', 'Status', 'trim|required|is_zero_one');  // same for status
		}
		

	
		
		if($this->input->post('add_submit_btn')) // if the form is posted
		{
			//echo "in"; die;
			
			
			$data['data_posted'] = TRUE;
		
			$formValidated=$this->form_validation->run();
			
			if($formValidated===FALSE)
			{
				$validationErrors=validation_errors();
				if(!empty($validationErrors))
				{
					$errArr[]=validation_errors(); // returns validation errors
				}
			}
			
			
			/////////check if Email already exists////////////
			
			$decodedEmail=$this->validation->decodeEmail(trim($this->input->post('email')));
			

			if(!empty($id)) //edit
				$email_count=$this->admin_user_model->user_email_count($decodedEmail, $id);
			else
				$email_count=$this->admin_user_model->user_email_count($decodedEmail);
	
				
			if($user_count>0)
			{
				$errArr[]='An user with same Email ID already exists.';
			}
			
			
			
			//////////////check if password does not matches with confirm password field/////////////
			
			if(!empty($id)) // edit
			{
				if($this->input->post('password')) // if password is updated
				{
					if(hash('sha256',$this->session->rand_str.$this->input->post('password'))!=$this->input->post('cpassword'))
					{	
						$errArr[]="Password and Retype Password fields don't match.";
					}
				}
			}
			else // password is required in case of Add
			{
				
				if(hash('sha256',$this->session->rand_str.$this->input->post('password'))!=$this->input->post('cpassword'))
				{	
					$errArr[]="Password and Retype Password fields don't match.";
				}
			}
			
			
			////////////check last 3 passwords/////////////
			
			if(!empty($id)) // edit
			{


					### this is not required for Admin as per Auditor ###
					$passRecArr=$this->admin_user_model->get_prev_passwords($id,3);
					//print_r($passRecArr);
					
					
					if(empty($passRecArr)) // first attemp to change password (password history does not exist)
					{
						if($this->input->post('password')==$usrRec->password)  // new password and current passwords are same
						{
							$errArr[]="Old Password and New Password can't be the same.";
						}
					}
					
					
					
					$passArr=array();
						
					foreach ( $passRecArr as $passRec) 
					{
						$passArr[]=$passRec->user_password;
					}
					
					//echo "<pre>"; 
					//print_r($passArr); die; 
				
					if(in_array($this->input->post('password'),$passArr)) // if password is same as any of the last 3 passwords changed
					{
						$errArr[]="Password can't be same as any of the last 3 changed passwords.";
					}
					
			}
			
			
			
			
			if (empty($errArr) && $formValidated === TRUE) // validation passed
			{
				
				$dataArr=array();
		
				$dataArr['fname']=$this->input->post('fname');
				$dataArr['lname']=$this->input->post('lname');
				
				$email=$this->input->post('email');
				
				$dataArr['email']=$this->validation->decodeEmail($email); //  // [at] => @, [dot] => .
				
				
				$mobile=$this->input->post('mobile');
				if(is_array($mobile)) // auditor passes this field (as empty to bypass validation above) as an array [] that throws an error
					$mobile='';
				$dataArr['mobile']=$mobile;
				
				
				$designation=$this->input->post('designation');
				if(is_array($designation))
					$designation='';
				$dataArr['designation']=$designation;
				
				
				$organization=$this->input->post('organization');
				if(is_array($organization))
					$organization='';
				$dataArr['organization']=$organization;
				
				$address=$this->input->post('address');
				if(is_array($address))
					$address='';
				$dataArr['address']=$address;
				
				$city=$this->input->post('city');
				if(is_array($city))
					$city='';
				$dataArr['city']=$city;
				
				$state=$this->input->post('state');
				if(is_array($state))
					$state='';
				$dataArr['state']=$state;
				
				$pin_code=$this->input->post('pin_code');
				if(is_array($pin_code))
					$pin_code='';
				$dataArr['pin_code']=$pin_code;
				
				
				$std_code=$this->input->post('std_code');
				if(is_array($std_code))
					$std_code='';
				$dataArr['std_code']=$std_code;
				
				$phone=$this->input->post('phone');
				if(is_array($phone))
					$phone='';
				$dataArr['phone']=$phone;
				
				
				$intercom=$this->input->post('intercom');
				if(is_array($intercom))
					$intercom='';
				$dataArr['intercom']=$intercom;
			
				
				
				$role=$this->input->post('role');
				$active=$this->input->post('active');
				

				if(!empty($id)) // edit
				{
					if($usrRec->role!='admin') ///Role is NOT required if you are editing Admin user
					{
						$dataArr['role']=$role;
						$dataArr['active']=$active;
					}
				}
				else // role is required in case of Add
				{
					$dataArr['role']=$role;
					$dataArr['active']=$active;
				}
				

				$curr_date=date('Y-m-d H:i:s');
				
				
				$password=$this->input->post('password');
				
				
				if(!empty($id)) // edit
				{
					
					if(!empty($password))
					{
						$dataArr['password']=$password;
					}
					
					$dataArr['updated']=$curr_date;
					
					$this->admin_user_model->edit_user($dataArr, $id);
					
					
					////////////add new password to history table////////////
					
					if(!empty($password))
					{
						$dataArr=array();
						$dataArr['password_changed']=0; // to enforce user to Change password on first login
						$this->admin_user_model->edit_user($dataArr, $id);
						
						$dataArr=array();
						$dataArr['user_id']=$id;
						$dataArr['user_password']=$password;
						$dataArr['password_date']=$curr_date;			
						$this->admin_user_model->add_pass_history($dataArr);
					}
					
					
					
					///////////////////////////////////////////////////
					
					
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Edit Admin User';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					
					redirect('/admin/admin_user/index?msg=updated');
				}
				else // add
				{
					
					
					$dataArr['password']=$password;
					
					$dataArr['created']=$curr_date;
					$dataArr['updated']=$curr_date;
					
					$dataArr['author']=$uid;
					

					$id=$this->admin_user_model->add_user($dataArr);
					
					
					
					////////////add password to history table////////////
					$dataArr=array();
					$dataArr['user_id']=$id;
					$dataArr['user_password']=$password;
					$dataArr['password_date']=$curr_date;			
					$this->admin_user_model->add_pass_history($dataArr);
					//////////////////////
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Add Admin User';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					redirect('/admin/admin_user/index?msg=added');
	
				}
				
	
			}
			
		}

		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
			
	
		$stateArr=$this->stateind_model->get_states();
		$data['stateArr'] = $stateArr;
		
		$data['error_message']=$error_message;
		
		
		
		$randStr=$this->validation->generateRandomAlphaNumericString(8);
		$this->session->rand_str=md5($randStr);
		//echo $this->session->rand_str;
		
		$data['userRec'] = $userRec;
		
		//$this->load->view('admin_user/add');
		$this->body = 'admin_user/add';
		$this->data = $data;
		$this->layout('inner');
			
	}
	
	
	public function check_user_email()
	{
		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		$errArr=array();
		$uid=$this->session->admin_session->id; // logged in user
		$userRec=$this->admin_user_model->get_user($uid);
		
		
		if($userRec->role!='admin') 
		{
			//show_error('You are not authorized for this action');
			redirect('/admin/dashboard/index?msg=auth_error');
			exit;
		}
		
		$id=$this->input->get('id');
		
		
		if(!empty($id)) // edit
		{
			if(!$this->validation->isInteger($id))
			{
				$errArr[]='Invalid user ID.';
			}
		}
		
		
		$email=trim($this->input->get('email'));
				
		if(!empty($email))
		{
			$email=$this->validation->decodeEmail($email);
			
			//echo $email;
			
			if(!$this->validation->isEmail($email))
			{
				$errArr[]="Invalid Email.";
				$email='';
			}
		}
		else
		{
			$errArr[]='Please enter Email.';	
		}
			
		
		if(empty($errArr))
		{
			
			if(!empty($id)) //edit
				$user_count=$this->admin_user_model->user_email_count($email, $id);
			else
				$user_count=$this->admin_user_model->user_email_count($email);
					
				
			if($user_count>0)
			{
				echo "false";
				//echo 'This Email already exists.';
			}
			else
			{
				echo "true";
				//echo "This Email does not exist.";
				
			}
		}
		else
		{
			$error_message=implode(' ',$errArr);
			echo $error_message;
			
		}

	}
	
	
	
	public function delete()
	{
		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		$uid=$this->session->admin_session->id; // logged in user
		
		$userRec=$this->admin_user_model->get_user($uid);
		
		
		if($userRec->role!='admin') 
		{
			//show_error('You are not authorized for this action');
			redirect('/admin/dashboard/index?msg=auth_error');
			exit;
		}
		
		$errArr=array();
		
		
		$use_csrf_salt=config_item('use_csrf_salt');
		
		
		if($use_csrf_salt)
		{
			$csrf_salt=$this->session->csrf_salt;
			$hash=md5($this->security->get_csrf_hash().$csrf_salt);
		}
		else
		{
			
			$hash=$this->security->get_csrf_hash();
		}
		
		$csrf_wc_token=$this->input->get('csrf_wc_token');
		
		 
		if($csrf_wc_token!=$hash)
		{
			$errArr[]="Security tokens do not match.";
		}
		else
		{

			$id=$this->input->get('id');
			
			
			if(!$this->validation->isInteger($id))
			{
				$errArr[]="Invalid User ID.";
			}
			else {
				
				$usrRec=$this->admin_user_model->get_user($id);	
			}
			
			
			if(empty($errArr))
			{

					$this->admin_user_model->delete_user($id); 
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Delete Admin User';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='successful';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					
					# update csrf salt in session
					$csrf_salt=md5(uniqid(mt_rand()));
					$this->session->csrf_salt=$csrf_salt;
					
					
					echo "deleted"; 
					exit;
			}
		}
		
		$errStr=implode(' ',$errArr);
		echo $errStr; 
		exit;
	}
	
	
	

	
	
}
