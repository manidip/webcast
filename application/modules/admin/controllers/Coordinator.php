<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coordinator extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
			$this->load->model('category_model');
			$this->load->model('coordinator_model');

			
			$this->load->model('stateind_model');
			
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
			$success_message="Coordinator has been added successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='updated')
		{
			$success_message="Coordinator has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='deleted')
		{
			$success_message="Coordinator has been deleted successfully.";
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
		
		
		if($this->input->get('search') || $this->input->get('adv_search'))
		{
			$data['data_posted']=1;
		}
		
	

		
		$this->form_validation->set_data($this->input->get()); // to validate GET array
		
		$this->form_validation->set_rules('search_kw', 'Search Keyword', 'trim|strip_tags|xss_clean|max_length[100]|is_valid_text');
	
	
		
		$this->form_validation->set_rules('ipp', 'ipp', 'trim|strip_tags|xss_clean|max_length[3]|is_natural_no_zero'); // is_natural_no_zero or is_integer (includes zero)
		
		
		$this->form_validation->set_rules('search', 'Search', 'trim|strip_tags|xss_clean|max_length[1]|is_natural_no_zero'); // is_natural_no_zero or is_integer (includes zero)
		
		
		
		
		
		$formValidated=$this->form_validation->run();
			
		if($formValidated===FALSE)
		{
			$validationErrors=validation_errors();
			if(!empty($validationErrors))
			{
				$errArr[]=validation_errors(); // returns validation errors
			}
			
			$search_kw='';
			$search='';

			$ipp=50;
		}
		else
		{
			$search_kw=$this->input->get('search_kw');
			
			
			$ipp=$this->input->get('ipp');
			
			$search=$this->input->get('search');
			
			
		}


		$sort_option=$this->input->get('sort_option');
	
		
		if(!empty($sort_option))
		{
			$sortOptArr=explode('|',$sort_option);	
			
			$sort_field=$sortOptArr[0];
			$sort_order=$sortOptArr[1];
			
			
			if(!in_array($sort_field, array('coordinator.name', 'coordinator.created_at')))
			{
				$errArr[]='Invalid Sort Field.';	
				$sort_field='coordinator.created_at';
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
			
			$sort_field='coordinator.created_at';
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
			'search_kw'=>$search_kw
		);
		

		//echo "<pre>";
		//print_r($optArr);
		
		
		$allCorArr=$this->coordinator_model->get_coordinators(0, 0, $sort_field, $sort_order, $optArr);
		
		$total_rows=count($allCorArr);
	
		$pagination_config['total_rows']=$total_rows;
		$pagination_config['base_url'] = base_url() . "admin/coordinator/index"; 
		
		if(!empty($ipp))
			$per_page=$ipp;
		else{
			$per_page=$pagination_config['per_page'];
			
			$per_page=50;
			
			$ipp=$per_page;
		}
			

			
		$pagination_config['per_page']=$per_page; // override config parameter
		
		
		
        $this->pagination->initialize($pagination_config);
		
       
		
		$start=($page-1)*$per_page;
		$limit=$per_page;
		
		
			
		
		$coordinators = $this->coordinator_model->get_coordinators($start, $limit, $sort_field, $sort_order, $optArr);
		
		$data['coordinators'] = $coordinators;
			
			
		
		$data["paging_links"] = $this->pagination->create_links();

		$data["page"] =$page;
		$data["per_page"] =$per_page;
		$data["total_rows"] =$total_rows;
		$data["search_kw"] =$search_kw;
		$data["sort_option"] =$sort_option;
		$data["ipp"] =$ipp;
		

		$data["search"]=$search;
		
		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
		
	    $data['error_message']=$error_message;
		
		
		
		$admin_users=$this->admin_user_model->get_users(0,0,'admin_user.fname','asc');
		$data['admin_users'] = $admin_users;
		
		
		
		
		$data['userRec'] = $userRec;
		
		//$this->load->view('coordinator/index');
		$this->body = 'coordinator/index';
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
				$errArr[]='Invalid Coordinator ID.';
			}
			else
			{
				$data['id']=$id;
				$corRec=$this->coordinator_model->get_coordinator($id);
				$data['corRec']=$corRec;
			}				
		}
		else
		{
			$data['title'] = 'Add';	
			$data['action_btn_title'] = 'Add';
		}
		
		
	
		$this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|xss_clean|required|max_length[100]|is_valid_name');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|strtolower|is_valid_email');
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
		$this->form_validation->set_rules('active', 'Status', 'trim|required|is_zero_one');  // same for status
		

	
		
		if(($this->input->post('add_submit_btn'))) // if the form is posted
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
			
	
			if(!empty($id)) //edit
				$cor_count=$this->coordinator_model->get_coordinator_count(array('email'=>$this->input->post('email')), $id);
			else
				$cor_count=$this->coordinator_model->get_coordinator_count(array('email'=>$this->input->post('email')));
	
				
			if($cor_count>0)
			{
				$errArr[]='An coordinator with same Email ID already exists.';
			}
		
			
			
			
			
			if (empty($errArr) && $formValidated === TRUE) // validation passed
			{
				
				$dataArr=array();
		
				$dataArr['name']=$this->input->post('name');
				$dataArr['email']=$this->input->post('email');
				$dataArr['mobile']=$this->input->post('mobile');
				$dataArr['designation']=$this->input->post('designation');
				$dataArr['organization']=$this->input->post('organization');
				$dataArr['address']=$this->input->post('address');
				$dataArr['city']=$this->input->post('city');
				$dataArr['state']=$this->input->post('state');
				$dataArr['pin_code']=$this->input->post('pin_code');
				$dataArr['std_code']=$this->input->post('std_code');
				$dataArr['phone']=$this->input->post('phone');
				$dataArr['intercom']=$this->input->post('intercom');
				$dataArr['active']=$this->input->post('active');

				$curr_date=date('Y-m-d H:i:s');
				
				if(!empty($id)) // edit
				{
					
					$dataArr['updated_at']=$curr_date;
					
					$this->coordinator_model->edit_coordinator($dataArr, $id);
					
					
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Edit Coordinator';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					
					redirect('/admin/coordinator/index?msg=updated');
				}
				else // add
				{
					
					$dataArr['created_at']=$curr_date;
					$dataArr['updated_at']=$curr_date;
					$dataArr['author']=$uid;
					

					$id=$this->coordinator_model->add_coordinator($dataArr);
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Add Coordinator';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					redirect('/admin/coordinator/index?msg=added');
	
				}
				
	
			}
			
		}

		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
			
	
		$stateArr=$this->stateind_model->get_states();
		$data['stateArr'] = $stateArr;
		
		$data['error_message']=$error_message;
		
		$data['userRec'] = $userRec;
		
		//$this->load->view('coordinator/add');
		$this->body = 'coordinator/add';
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
				$errArr[]='Invalid Coordinator ID.';
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
		/*else
		{
			$errArr[]='Please enter Email.';	
		}*/
			
		
		if(empty($errArr))
		{
			
			if(!empty($email))
			{
				
				if(!empty($id)) //edit
					$cor_count=$this->coordinator_model->get_coordinator_count(array('email'=>$email), $id);
				else
					$cor_count=$this->coordinator_model->get_coordinator_count(array('email'=>$email));
						
					
				if($cor_count>0)
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
				echo "true";
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
		
		//echo $this->session->csrf_salt;
		//die;
		
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
				$errArr[]="Invalid Coordinator ID.";
			}
			
			
			if(empty($errArr))
			{
				$this->coordinator_model->delete_coordinator($id); 
				
				////////////////add to audit trail////////////////
				$dataArr=array();
				$dataArr['user_id']=$userRec->id;
				$dataArr['user_email']=$userRec->email;
				$dataArr['activity']='Delete Coordinator';
				$dataArr['item_id']=$id;
				$dataArr['activity_time']=date('Y-m-d H:i:s');
				$dataArr['activity_result']='success';
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
