<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
			$this->load->model('stateind_model');
			$this->load->model('log_model');
			$this->load->library('my_form_validation');
			
			//$this->load->library('validation'); //loaded in my_form_validation
			$this->load->library('email');
			
			$this->load->helper('captcha');
			
			$this->load->library('admin');
			$this->admin->check_sess_timeout();
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
	 
	 
	 /*
	creating admin panel in codeigniter
	https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/
	http://developers.ph/codeigniter/hmvc-pattern-codeigniter-php-framework/
	http://www.tutorials.kode-blog.com/codeigniter-admin-panel
	http://webduos.com/create-an-admin-panel-with-codeigniter/#.VqhtFFKrE20
	https://philsturgeon.uk/codeigniter/2009/07/08/Create-an-Admin-panel-with-CodeIgniter/
	http://www.darwinbiler.com/ready-to-use-codeigniter-modular-extensions-hmvc/
	*/

	public function index()
	{
		
		
		if(!empty($this->session->admin_session->id))
		{
			redirect('/admin/dashboard/index');
			exit;
		}
		
		
		$errArr=array();
		
		$data['title'] = 'SIGN IN';
		
		$logout=$this->input->get('logout');
		
		if(!empty($logout))
		{
			$success_message="You have successfully logged out.";
			$data['success_message']=$success_message;
		}
		
		
		$success=$this->input->get('success'); // from reset password page
		
		if(!empty($success))
		{
			
			$success_message='You have successfully reset the password.';
			$data['success_message']=$success_message;
		}
		
		

		$this->form_validation->set_rules('email', 'Email', 'trim|required|strtolower|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|is_valid_sha256');
		$this->form_validation->set_rules('user_captcha', 'Verification Code', 'trim|strip_tags|xss_clean|required|is_alpha_numeric|is_valid_captcha');
		

		if(($this->input->post('submit_btn'))) // if the form is posted
		{
			
			if ($this->form_validation->run() === TRUE) // validation passed
			{
			
				
				// validation passed	
				$userRec = $this->admin_user_model->get_active_user_by_email($this->input->post('email'));
				
				if(!empty($userRec))
				{
				  	$dbPass=$userRec->password;

					//$encPass=md5($this->session->rand_str.$dbPass);
					$encPass=hash('sha256', $this->session->rand_str.$dbPass); 
					
										
					if($this->input->post('password') != $encPass)
					{
						$errArr[]="Invalid credentials provided.";
						
						////////////////add to audit trail////////////////
						$dataArr=array();
						$dataArr['user_id']=$userRec->id;
						$dataArr['user_email']=$userRec->email;
						$dataArr['activity']='login';
						$dataArr['activity_time']=date('Y-m-d H:i:s');
						$dataArr['activity_result']='failed';
						$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
						$this->log_model->add_log($dataArr);
						////////////////////////////////	
					}
					else // passwords are matched
					{
						
						
						
						
						
						////////////////add to audit trail////////////////
						$dataArr=array();
						$dataArr['user_id']=$userRec->id;
						$dataArr['user_email']=$userRec->email;
						$dataArr['activity']='login';
						$dataArr['activity_time']=date('Y-m-d H:i:s');
						$dataArr['activity_result']='successful';
						$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
						$this->log_model->add_log($dataArr);
						////////////////////////////////			
						
						
						$this->session->admin_session=$userRec; 
						$this->session->admin_session->last_activity_time=time();
						$this->session->sess_regenerate(true); //session_regenerate_id(true);
						
						redirect('/admin/dashboard/index');
						exit;
					}
				}
				else
				{
					

					$errArr[]="Invalid credentials provided.";
				}
			}
		
		}
		
		$error_message=implode(' ',$errArr);
		
	    $data['error_message']=$error_message;
		
		$randStr=$this->validation->generateRandomAlphaNumericString(8);
		$this->session->rand_str=md5($randStr);
		//echo $this->session->rand_str;
		
		$data['captcha'] = create_captcha($this->config->item('captcha_config'));	
		$this->session->captchaWord=$data['captcha']['word'];

		//$this->load->view('home/index');
		$this->body = 'home/index';
		$this->data = $data;
		$this->layout('login');

	}
	
	
	public function logout()
	{
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		
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
			//$errArr[]="Security tokens do not match.";
			
			//show_error('You are not authorized for this action');
			redirect('/admin/dashboard/index?msg=auth_error');
			exit;
			
		}
		else
		{

			$userRec=$this->admin_user_model->get_user($this->session->admin_session->id);
	
			//echo "<pre>";
			//print_r($_SESSION);
			
			
			$this->session->admin_session=''; // $_SESSION['admin_session']='';
			//print_r($_SESSION);
			
			$this->session->unset_userdata('admin_session'); // unset($_SESSION['admin_session']); 
			//print_r($_SESSION);
			
			$this->session->sess_destroy(); //session_destroy();
			
			$this->session->sess_regenerate(true); //session_regenerate_id(true);
			
			/*
			if(ini_get("session.use_cookies")) 
			{
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"],$params["httponly"]);
			}
			*/
				
			////////////////add to audit trail////////////////
			$dataArr=array();
			$dataArr['user_id']=$userRec->id;
			$dataArr['user_email']=$userRec->email;
			$dataArr['activity']='logout';
			$dataArr['activity_time']=date('Y-m-d H:i:s');
			$dataArr['activity_result']='successful';
			$dataArr['ip_address']=$this->validation->get_client_ip();  //$_SERVER['REMOTE_ADDR'];
			$this->log_model->add_log($dataArr);
			////////////////////////////////
			
			redirect('/admin/');
			exit;
			
		}
	}
	
	
	public function forgot_password()
	{
		if(!empty($this->session->admin_session->id))
		{
			redirect('/admin/dashboard/index');
			exit;
		}
		
		$controller=$this->router->fetch_class();
		$method=$this->router->fetch_method();
		
		$data['title'] = 'Forgot Password';
		
		$success=$this->input->get('success');
		
		if(!empty($success))
		{
			
			$success_message="A link has been sent to your email. Please click on that link to reset your password.";
			
			
			/////for testing without mail/////////
			/*
			$fp_key=$this->session->fp_key;			
			//print_r($_SESSION);
			if(!empty($fp_key))
			{	
				$success_message.=' Link: <a href="'.base_url().'admin/home/reset_password?fp_key='.$fp_key.'">Reset Password</a>';
			}
			*/
			///////////////////
			
			$data['success_message']=$success_message;
		}

	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|strtolower|valid_email');
		
		$this->form_validation->set_rules('user_captcha', 'Verification Code', 'trim|strip_tags|xss_clean|required|is_alpha_numeric|is_valid_captcha');
		
		if(($this->input->post('submit_btn'))) // if the form is posted
		{
			if ($this->form_validation->run() === TRUE) // validation passed
			{
				$errArr=array();
				
				$email=$this->input->post('email');
				$userRec=$this->admin_user_model->get_user_by_email($email);
				
				if(!empty($userRec))
				{
					// print_r($userRec);

					while(1)
					{
						$fp_key=$this->validation->generateUniqueMD5Key();
						
						$fp_key=hash('sha256', $fp_key);
						
						
						if($this->admin_user_model->fp_key_exists($fp_key)) // although we are generating unique key, still we make sure that key does not already exists
						{
							continue;
						}
						else
						{
							break;
						}
					}
					
					$dataArr=array();

					$dataArr['fp_key']=$fp_key;
					
					$created = date('Y-m-d H:i:s');
					$expires = date('Y-m-d H:i:s', strtotime($created) + 24*60*60); // 24 hrs
					
					//echo $created;
					//echo "<br/>";
					//echo $expires;
		
					$dataArr['key_created']=$created;
					$dataArr['key_expires']=$expires;
					$dataArr['key_used']=0;
					
					
					if($this->admin_user_model->fp_uid_exists($userRec->id)) //update
					{
						$inserted_id=$this->admin_user_model->update_fp_key($dataArr, $userRec->id);
					}
					else //insert
					{
						$dataArr['user_id']=$userRec->id;
						
						$inserted_id=$this->admin_user_model->add_fp_key($dataArr);
					}
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='forgot password';
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='successful';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////	
										
										
					
					// send mail
					//$this->session->fp_key=$fp_key; // for testing without mail
					///////////send link to user///////////////////
					
					
					$this->email->from('webcast@gov.in', 'Government Webcast Administrator');
					$this->email->to($email); 
					$this->email->subject('Reset Password Link for Government Webcast Management System');
					
					$message='Dear Sir/Madam,
					<br/><br/>
					Please click on the link given below to reset your password:
					<br/><br/>
					<a href="'.base_url().'admin/home/reset_password?fp_key='.$fp_key.'">Reset Password</a>
					<br/><br/>
					Regards<br/>
					Government Webcast Administrator<br/>';
					
					$this->email->message($message);
					
					$this->email->set_mailtype("html");
					
					$this->email->send();
					
					
					/////////////////////////
					
					//echo $fp_key; die;
						
					
					
					redirect('/admin/home/forgot_password?success=1');
					exit;
	
				}
				
				else
				{
					$errArr[]="If the provided email id is associated with the application, we've sent your password reset link to the email address you've entered.";
				}
				
				
				$errStr=implode(' ',$errArr);
				
				$data['error_message']=$errStr;
				
				
			}
		
		}
		
		
		
		$randStr=$this->validation->generateRandomAlphaNumericString(6);
		$this->session->rand_str=md5($randStr);
		//echo $this->session->rand_str;

		$data['captcha'] = create_captcha($this->config->item('captcha_config'));	
		$this->session->captchaWord=$data['captcha']['word'];
		
		//$this->load->view('home/forgot_password', $data);

		$this->body = 'home/forgot_password';
		$this->data = $data;
		$this->layout('login');

	}
	
	public function refresh_captcha()
	{	
		$captcha = create_captcha($this->config->item('captcha_config'));
		$this->session->captchaWord=$captcha['word'];
		//print_r($captcha);
		echo $captcha['image'];	
		
		exit;
		
	}
	
	
	
	
	
	public function reset_password()
	{
		
		
		if(!empty($this->session->admin_session->id))
		{
			redirect('/admin/dashboard/index');
			exit;
		}
		
		
		$controller=$this->router->fetch_class();
		$method=$this->router->fetch_method();
		
		$data['title'] = 'Reset Password';
		
		
		/*$success=$this->input->get('success');
		
		if(!empty($success))
		{
			
			$success_message='You have successfully reset the password.';
			$data['success_message']=$success_message;
		}*/
		
		$errArr=array();
		
		$linkErrArr=array();
		
		
		$fp_key=$this->validation->stripHtmlTags($this->input->get('fp_key'));
		
		
		if(!$this->validation->isValidSha256($fp_key))
		{
			$fp_key='';
		}
		
		
		
		//echo $fp_key; die;
		
		
		if($this->validation->isValidSha256($fp_key))
		{
		
			$fp_details=$this->admin_user_model->get_fp_details($fp_key);
			
			//print_r($fp_details);
			//die;
			
			
			if(!empty($fp_details))
			{
				
				if(!$fp_details->key_used)
				{
				
					$expires=strtotime($fp_details->key_expires);
					
					$now=time();
					
					if($now > $expires)
					{
						$linkErrArr[]="Link has expired.";
					}
					else
					{
						//echo "key is valid";
						
						$this->form_validation->set_rules('password', 'Password', 'trim|required|is_valid_sha256');
						$this->form_validation->set_rules('cpassword', 'Password Confirmation', 'trim|required|is_valid_sha256');
						
						$this->form_validation->set_rules('user_captcha', 'Verification Code', 'trim|strip_tags|xss_clean|required|is_alpha_numeric|is_valid_captcha');
						
						if(($this->input->post('submit_btn'))) // if the form is posted
						{
				
							if ($this->form_validation->run() === TRUE) // validation passed
							{
								// validation passed
								
								
								//if(md5($this->session->rand_str.$this->input->post('password'))!=$this->input->post('cpassword'))
								if(hash('sha256', $this->session->rand_str.$this->input->post('password'))!=$this->input->post('cpassword'))
								{	
									$errArr[]="New Password and Confirm Password fields don't match.";
								}
								else
								{
									
									
									
									$uid=$fp_details->user_id;
									
									$userRec=$this->admin_user_model->get_user($uid);
									
									$passRecArr=$this->admin_user_model->get_prev_passwords($uid,3);
									
									if(empty($passRecArr)) // first attemp to change password (password history does not exist)
									{
										if($this->input->post('password')==$userRec->password)  // new password and current passwords are same
										{
											$errArr[]="Old Password and New Password can't be the same.";
										}
									}
									
									
									
									//print_r($passRecArr);
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
									
									if(!empty($errArr))
									{
										
										////////////////add to audit trail////////////////
										$dataArr=array();
										$dataArr['user_id']=$userRec->id;
										$dataArr['user_email']=$userRec->email;
										$dataArr['activity']='password reset';
										$dataArr['activity_time']=date('Y-m-d H:i:s');
										$dataArr['activity_result']='failed';
										$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
										$this->log_model->add_log($dataArr);
										////////////////////////////////	
									
									}
									else
									{
											$dataArr=array();
											$dataArr['password']=$this->input->post('password');
											$this->admin_user_model->edit_user($dataArr, $uid);
		
											$dataArr=array();
											$dataArr['user_id']=$uid;
											$dataArr['user_password']=$this->input->post('password');
											$dataArr['password_date']=date('Y-m-d H:i:s');
											
											$this->admin_user_model->add_pass_history($dataArr);
										
											/////// update key status to used /////////
											
											$this->admin_user_model->update_fp_key(array('key_used' => 1), $uid);
			
											////////////////add to audit trail////////////////
											
											$userRec=$this->admin_user_model->get_user($uid);
		
											$dataArr=array();
											$dataArr['user_id']=$userRec->id;
											$dataArr['user_email']=$userRec->email;
											$dataArr['activity']='password reset';
											$dataArr['activity_time']=date('Y-m-d H:i:s');
											$dataArr['activity_result']='successful';
											$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
											$this->log_model->add_log($dataArr);
											////////////////////////////////	
											
											//redirect('/admin/home/reset_password?fp_key='.$fp_key.'&success=1');
											
											redirect('/admin?success=1'); // as suggested by auditor
											exit;

											
									}
									
								}
								
							}
						
						}
					}
				}
				else
				{
					$linkErrArr[]="Link has already been used.";
				}
			}
			else
			{
				$linkErrArr[]="Invalid link.";
			}
		}
		else
		{
			$linkErrArr[]="Invalid link.";
		}
		
		$data['fp_key']=$fp_key;
		
		
		$errStr=implode(' ',$errArr);
				
		$data['error_message']=$errStr;
		
		$linkErrStr=implode(' ',$linkErrArr);
		
		$data['link_error']=$linkErrStr;
		
		$randStr=$this->validation->generateRandomAlphaNumericString(6);
		$this->session->rand_str=md5($randStr);
		//echo $this->session->rand_str;

		$data['captcha'] = create_captcha($this->config->item('captcha_config'));	
		$this->session->captchaWord=$data['captcha']['word'];
		
		//$this->load->view('home/reset_password', $data);
		
		$this->body = 'home/reset_password';
		$this->data = $data;
		$this->layout('login');

	}
	
	
	public function change_password()
	{
		
		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		
		$data['title'] = 'Change Password';
		
		$uid=$this->session->admin_session->id; // logged in user
		
		$userRec=$this->admin_user_model->get_user($uid);
		
		$msg=$this->input->get('msg');
		

		if($msg=='updated')
		{
			$success_message="Password has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='error')
		{
			$error_message="An error has occured.";
			$data['error_message']=$error_message;
		}

		
		$this->form_validation->set_rules('opassword', 'Current Password', 'trim|required|is_valid_sha256');
		$this->form_validation->set_rules('npassword', 'New Password', 'trim|required|is_valid_sha256');
		$this->form_validation->set_rules('cpassword', 'Retype Password', 'trim|required|is_valid_sha256');
		
		if(($this->input->post('submit'))) // if the form is posted
		{
			

			if ($this->form_validation->run() === TRUE) // validation passed
			{
				// get filtered data
				$opassword=$this->input->post('opassword');
				$npassword=$this->input->post('npassword');
				$cpassword=$this->input->post('cpassword');
				
				// validation passed
				
				$errArr=array();
				
				//if(md5($this->session->rand_str.$npassword)!=$cpassword)
				if(hash('sha256', $this->session->rand_str.$npassword)!=$cpassword)
				{
						
					$errArr[]="New Password and Retype Password fields don't match.";
				}
				else
				{
					
					//print_r($userArr);
					
					//$matchPassword=md5($this->session->rand_str.$userRec->password);
					
					$matchPassword=hash('sha256', $this->session->rand_str.$userRec->password);
					
					if($opassword==$matchPassword)
					{
						
						
						
						$passRecArr=$this->admin_user_model->get_prev_passwords($uid,3);
						
						if(empty($passRecArr)) // first attempt to change password (password history does not exist)
						{
							if($npassword==$userRec->password)  // new password and current passwords are same
							{
								$errArr[]="Old Password and New Password can't be the same.";
							}
						}
						
						//print_r($passRecArr);
						$passArr=array();
							
						foreach ( $passRecArr as $passRec) 
						{
							$passArr[]=$passRec->user_password;
						}
						
						//echo "<pre>"; 
						//print_r($passArr); 
			
						if(in_array($npassword,$passArr)) // if password is same as any of the last 3 passwords changed
						{
							$errArr[]="Password can't be same as any of the last 3 changed passwords.";
						}
						
						
						
						if(empty($errArr))
						{
								$dataArr=array();
								$dataArr['password']=$npassword;
								$dataArr['password_changed']=1;
							
								$this->admin_user_model->edit_user($dataArr, $uid);

								$dataArr=array();
								$dataArr['user_id']=$uid;
								$dataArr['user_password']=$npassword;
								$dataArr['password_date']=date('Y-m-d H:i:s');
								
								$this->admin_user_model->add_pass_history($dataArr);
								
								
								////////////////add to audit trail////////////////
								$dataArr=array();
								$dataArr['user_id']=$userRec->id;
								$dataArr['user_email']=$userRec->email;
								$dataArr['activity']='Password Change';
								$dataArr['item_id']=0;
								$dataArr['activity_time']=date('Y-m-d H:i:s');
								$dataArr['activity_result']='successful';
								$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
								$this->log_model->add_log($dataArr);
								////////////////////////////////
								redirect('/admin/home/view_profile?success=change_password');
								exit;	
						}
					}
					else
					{
						$errArr[]='Incorrect password provided.';
					}
				}
				$error_message=implode(' ',$errArr);
				$data['error_message']=$error_message;
			}
		
		}
		
		$randStr=$this->validation->generateRandomAlphaNumericString(8);
		$this->session->rand_str=md5($randStr);
		//echo $this->session->rand_str;
		
		//$this->load->view('home/change_password', $data);
		
		$this->body = 'home/change_password';
		$this->data = $data;
		$this->layout('inner');

	}
	
	
	public function view_profile()
	{

		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}

		$errArr=array();
		$uid=$this->session->admin_session->id; // logged in user
		$userRec=$this->admin_user_model->get_user($uid);
		
		
		
		$success=$this->input->get('success');
		

		if($success=='change_password')
		{
			$success_message="Password has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($success=='edit_profile')
		{
			$success_message="Profile has been updated successfully.";
			$data['success_message']=$success_message;
		}
		
		
		
		
				
		$msg=$this->input->get('msg');
		
		
		if($msg=='deleted')
		{
			$success_message="Category has been deleted successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='delete_error')
		{
			$error_message="Action could not be completed.";
			$data['error_message']=$error_message;
		}
		
		$id=$uid; // logged in user's id
		
		$data['title'] = 'View Profile';
		
		if(!empty($id)) // edit
		{
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
			$errArr[]='Invalid User ID.';	
		}
		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
	    $data['error_message']=$error_message;

		$data['usrRec'] = $usrRec;
		
		//$this->load->view('home/view_profile');
		$this->body = 'home/view_profile';
		$this->data = $data;
		$this->layout('inner');

	}
	
	public function edit_profile()
	{
		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/home/index');
			exit;
		}
		
		$errArr=array();
		$uid=$this->session->admin_session->id; // logged in user
		$userRec=$this->admin_user_model->get_user($uid);

		
		$msg=$this->input->get('msg');
		
		if($msg=='updated')
		{
			$success_message="Profile has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='image_deleted')
		{
			$success_message="Image has been deleted successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='error')
		{
			$error_message="An error has occured.";
			$data['error_message']=$error_message;
		}
		
		

		$id=$uid;
		

		$data['title'] = 'Edit Profile';
		
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
	


		
		$this->form_validation->set_rules('fname', 'First Name', 'trim|strip_tags|xss_clean|required|max_length[50]|is_valid_text');
		$this->form_validation->set_rules('lname', 'Last Name', 'trim|strip_tags|xss_clean|required|max_length[50]|is_valid_text');

		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|is_mobile_ten_digits');
		$this->form_validation->set_rules('std_code', 'STD Code', 'trim|is_valid_stdcode');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|is_valid_phone');
		$this->form_validation->set_rules('intercom', 'Intercom', 'trim|is_valid_intercom');

		$this->form_validation->set_rules('designation', 'Designation', 'trim|required|max_length[50]|strip_tags|xss_clean|is_valid_text');
		$this->form_validation->set_rules('organization', 'Organization', 'trim|required|max_length[50]|strip_tags|xss_clean|is_valid_text');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[250]|strip_tags|xss_clean|is_valid_text');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[50]|strip_tags|xss_clean|is_valid_text');
		$this->form_validation->set_rules('state', 'State', 'trim|is_integer'); // is_natural_no_zero or is_integer (includes zero)
		$this->form_validation->set_rules('pin_code', 'Pin Code', 'trim|is_valid_pincode'); 
		
		
		
	
		if(($this->input->post('submit'))) // if the form is posted
		{
			
			
			////////////run form validations///////////////
			$formValidated=$this->form_validation->run();
			
			if($formValidated===FALSE)
			{
				$validationErrors=validation_errors();
				if(!empty($validationErrors))
				{
					$errArr[]=validation_errors(); // returns validation errors, append to error array
				}
			}
			

			if (empty($errArr) && $formValidated === TRUE) // validation passed
			{

				///////////////////get filtered data///////////////
				$fname=$this->input->post('fname');
				$lname=$this->input->post('lname');
				
				$mobile=$this->input->post('mobile');
				$std_code=$this->input->post('std_code');
				$phone=$this->input->post('phone');
				$intercom=$this->input->post('intercom');
				$designation=$this->input->post('designation');
				$organization=$this->input->post('organization');
				$address=$this->input->post('address');
				$city=$this->input->post('city');
				$state=$this->input->post('state');
				$pin_code=$this->input->post('pin_code');
				

				$dataArr=array();
				
				$curr_date=date('Y-m-d H:i:s');
					
				$dataArr['fname']=$fname;
				$dataArr['lname']=$lname;
				
				
				$dataArr['mobile']=$mobile;
				$dataArr['std_code']=$std_code;
				$dataArr['phone']=$phone;
				$dataArr['intercom']=$intercom;
			
				$dataArr['designation']=$designation;
				$dataArr['organization']=$organization;
				$dataArr['address']=$address;
				$dataArr['city']=$city;
				$dataArr['state']=$state;
				$dataArr['pin_code']=$pin_code;
				
				if(!empty($id)) // edit
				{
					
					$dataArr['updated']=$curr_date;
					$this->admin_user_model->edit_user($dataArr, $id);

					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Profile Update';
					$dataArr['item_id']='';
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='successful';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					
					redirect('/admin/home/view_profile?success=edit_profile');
				}
				
			}
		
		}

		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
		
	    $data['error_message']=$error_message;

		$stateArr=$this->stateind_model->get_states();
		$data['stateArr']=$stateArr;

		$data['userRec'] = $userRec;
		
		//$this->load->view('home/edit_profile');
		$this->body = 'home/edit_profile';
		$this->data = $data;
		$this->layout('inner');

	}
	
}
