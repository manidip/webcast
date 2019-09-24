<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
			$this->load->model('category_model');
			$this->load->model('event_model');
			
			$this->load->model('log_model');	
			
			$this->load->library('my_form_validation');
			$this->load->library('pagination');
			$this->load->library('admin');
			
			//$this->load->library('validation'); //loaded in my_form_validation
			
			
			$this->admin->check_sess_timeout();
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
		
		$data['title'] = 'Add';
		
		
		$id=$this->input->get('id');
		
		//echo $id; die;
		
		if(!empty($id)) // edit
		{

			$data['action_title'] = 'Edit Category';
			$data['action_btn_title'] = 'Edit';
			
			if(is_array($id)) // auditor passes this field as an array that throws an error
				$id=0;
			
			if(!$this->validation->isInteger($id))
			{
				$errArr[]='Invalid Category ID.';
			}
			else
			{
				$data['id']=$id;
				$catRec=$this->category_model->get_category($id);
				$data['catRec']=$catRec;
			}				
		}
		else
		{
			$data['action_title'] = 'Add Category';	
			$data['action_btn_title'] = 'Add';
		}
		
		
		$this->form_validation->set_rules('title', 'Title (en)', 'trim|strip_tags|xss_clean|required|max_length[255]|is_valid_text');
		$this->form_validation->set_rules('title_hi', 'Title (hi)', 'trim|strip_tags|xss_clean|required|max_length[255]|is_valid_text');
		
		$this->form_validation->set_rules('desc', 'Description (en)', 'trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
		$this->form_validation->set_rules('desc_hi', 'Description (hi)', 'trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
		
		$this->form_validation->set_rules('parent', 'Parent', 'trim|required|is_integer'); // is_natural_no_zero or is_integer (includes zero)
		
		
		if($this->input->post('display_order'))
		{
			$this->form_validation->set_rules('display_order', 'Display Order', 'trim|is_integer');
		}

		$this->form_validation->set_rules('active', 'Status', 'trim|required|is_zero_one');
		

		if(($this->input->post('add_submit_btn'))) // if the form is posted
		{
			
			
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
			
			/////////check if Title already exists////////////
			
			if(!empty($id)) //edit
				$cat_count=$this->category_model->category_title_count($this->input->post('title'), $id, $this->input->post('parent'));
			else
				$cat_count=$this->category_model->category_title_count($this->input->post('title'), 0, $this->input->post('parent'));
				
			if($cat_count>0)
			{
				$errArr[]='A catgeory with same Title (en) already exists.';
			}
			
			
			if(!empty($id)) //edit
				$cat_count=$this->category_model->category_title_count_hi($this->input->post('title_hi'), $id, $this->input->post('parent'));
			else
				$cat_count=$this->category_model->category_title_count_hi($this->input->post('title_hi'), 0, $this->input->post('parent'));
				
			if($cat_count>0)
			{
				$errArr[]='This Catgeory with same Title (hi) already exists.';
			}
				
				
			/////check icon home file//////////////////////////
			$thumb_image_name=$_FILES['thumb_image']['name'];
			
			if(empty($id)) // add form
			{
				if(empty($thumb_image_name)) // file is required
				{
					$errArr[]="Thumb field is required.";
				}
			}
				
				
			$ferror=$_FILES['thumb_image']['error']; // upload error
					
			if(!empty($ferror) && $ferror!=4) // 4 means no file uploaded
			{
				$errArr[]="An upload error has occured. Please check the Thumb Image file type/size you are uploading.";
			}
			
			
			if(!empty($thumb_image_name))
			{
				$thumb_image_size=$_FILES['thumb_image']['size'];
				if(is_numeric($thumb_image_size) && $thumb_image_size>0)
				{
					$allowed_size_in_mb=5; //MB
					
					$allowed_size_in_kb= $allowed_size_in_mb*1024;
					
					$allowed_size_in_bytes= round($allowed_size_in_kb*1024, 2);
	
					$uploaded_size_in_mb=round(($thumb_image_size/(1024))/1024, 2);
					
					if($thumb_image_size > $allowed_size_in_bytes)
					{
						$errArr[]="Please upload an Thumb Image file no larger than $allowed_size_in_mb MB. You uploaded $uploaded_size_in_mb MB file.";
					}
				
				}
				else
				{
					$errArr[]="Please upload valid sized Thumb Image.";
				}
			}
			
			
			if(empty($errArr))
			{
			
				$retArr=$this->validation->check_jpgpng_file('Thumb Image',$_FILES['thumb_image'],0, array('jpg','jpeg','png'));
				
				if($retArr['valid']==0)
				{
					$errArr=array_merge($errArr, $retArr['error']);
				}
			
			}
			
			
			
			/////check large image//////////////////////////
			
			$large_image_name=$_FILES['large_image']['name'];
			
			if(empty($id)) // add form
			{
				if(empty($large_image_name)) // file is required
				{
					$errArr[]="Large Image field is required.";
	
				}
			}
				
			//echo $_FILES['large_image']['error']; die;
				
			$ferror=$_FILES['large_image']['error']; // upload error
					
			if(!empty($ferror) && $ferror!=4) // 4 means no file uploaded
			{
				$errArr[]="An upload error has occured. Please check the Large Image file type/size you are uploading.";
				
			}
			
			
			if(!empty($large_image_name))
			{
				
				//echo "<pre>";
				
				//print_r($_FILES);
				
				
				
				$large_image_size=$_FILES['large_image']['size'];
				
				//echo $large_image_size; 
				//die;
				
				if( is_numeric($large_image_size) && $large_image_size>0)
				{
				
					$allowed_size_in_mb=5; //MB
					
					$allowed_size_in_kb= $allowed_size_in_mb*1024;
					
					$allowed_size_in_bytes= round($allowed_size_in_kb*1024, 2);
	
					$uploaded_size_in_mb=round(($large_image_size/(1024))/1024, 2);
					
					if($large_image_size > $allowed_size_in_bytes)
					{
						$errArr[]="Please upload an Large Image file no larger than $allowed_size_in_mb MB. You uploaded $uploaded_size_in_mb MB file.";
					}
				
				}
				else
				{
					$errArr[]="Please upload valid sized Large Image.";
				}
			}
			
			
			if(empty($errArr))
			{
			
				$retArr=$this->validation->check_jpgpng_file('Large Image',$_FILES['large_image'],0, array('jpg','jpeg','png'));
				
				if($retArr['valid']==0)
				{
					$errArr=array_merge($errArr, $retArr['error']);
				}
			
			}
	
	
	
			$uploads_dir=FCPATH.'uploads/';
				
			
			
			if (empty($errArr) && $formValidated === TRUE) // validation passed
			{
				
				
				$title=$this->input->post('title');
				$title_hi=$this->input->post('title_hi');
				$desc=$this->input->post('desc');
				$desc_hi=$this->input->post('desc_hi');
				
				
				$parent=$this->input->post('parent');
				
				$active=$this->input->post('active');
				$author=$userRec->id;
				$display_order=$this->input->post('display_order');	
				
				
				$thumb_image_name=$_FILES['thumb_image']['name'];
				if(!empty($thumb_image_name))
				{
					$randStr=$this->validation->generateRandomAlphaNumericString(10);
					$encRandStr=substr(md5(microtime().$randStr), 1, 16); //$encRandStr=md5(microtime().$randStr);
					$thumb_image_dyn_name=$encRandStr.'_'.$this->validation->sanitize_file_name($thumb_image_name);
					$thumb_image_fname=$uploads_dir .'category/thumb/'.$thumb_image_dyn_name;
					move_uploaded_file($_FILES['thumb_image']['tmp_name'],$thumb_image_fname);
				}
				
				
				$large_image_name=$_FILES['large_image']['name'];
				if(!empty($large_image_name))
				{
					$randStr=$this->validation->generateRandomAlphaNumericString(10);
					$encRandStr=substr(md5(microtime().$randStr), 1, 16); //$encRandStr=md5(microtime().$randStr);
					$large_image_dyn_name=$encRandStr.'_'.$this->validation->sanitize_file_name($large_image_name);
					$large_image_fname=$uploads_dir .'category/'.$large_image_dyn_name;
					move_uploaded_file($_FILES['large_image']['tmp_name'],$large_image_fname);
				}
				
				
				
				
				$dataArr=array();
				$curr_date=date('Y-m-d H:i:s');
					
				$dataArr['title']=$title;
				$dataArr['title_hi']=$title_hi;
				$dataArr['desc']=$desc;
				$dataArr['desc_hi']=$desc_hi;

				$dataArr['parent']=$parent;
			
				$dataArr['active']=$active;
				$dataArr['author']=$author;
				$dataArr['display_order']=$display_order;
			
				if(!empty($id)) // edit
				{
					
					
					$dataArr['updated_at']=$curr_date;
					
					
					
					if(!empty($thumb_image_name))
					{
						@unlink($uploads_dir .'category/thumb/'.$catRec->thumb_image); // unlink old file if any, in case of edit
						$dataArr['thumb_image']=$thumb_image_dyn_name;
					}
					
					if(!empty($large_image_name))
					{
						@unlink($uploads_dir .'category/'.$catRec->large_image); // unlink old file if any, in case of edit
						$dataArr['large_image']=$large_image_dyn_name;
					}

					
					
					
					$this->category_model->edit_category($dataArr, $id);
					
					
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Edit Category';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					
					redirect('/admin/category/index?msg=updated');
				}
				else // add
				{
					
					$dataArr['created_at']=$curr_date;
					$dataArr['updated_at']=$curr_date;
					
					
					if(!empty($thumb_image_name))
					{
						$dataArr['thumb_image']=$thumb_image_dyn_name;
					}
					
					if(!empty($large_image_name))
					{
						$dataArr['large_image']=$large_image_dyn_name;
					}

					$id=$this->category_model->add_category($dataArr);
					
					
					
					////////////////add to audit trail////////////////
					$dataArr=array();
					$dataArr['user_id']=$userRec->id;
					$dataArr['user_email']=$userRec->email;
					$dataArr['activity']='Add Category';
					$dataArr['item_id']=$id;
					$dataArr['activity_time']=date('Y-m-d H:i:s');
					$dataArr['activity_result']='success';
					$dataArr['ip_address']=$this->validation->get_client_ip(); //$_SERVER['REMOTE_ADDR'];
					$this->log_model->add_log($dataArr);
					////////////////////////////////
					
					redirect('/admin/category/index?msg=added');
	
				}
	
	
			}
			
		}
		
		

		$msg=$this->input->get('msg');
		
		if($msg=='added')
		{	
			$success_message="Category has been added successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='updated')
		{
			$success_message="Category has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='deleted')
		{
			$success_message="Category has been deleted successfully.";
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
		else if($msg=='delete_error')
		{
			$error_message="Action could not be completed.";
			$data['error_message']=$error_message;
		}
		
		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
		
	    $data['error_message']=$error_message;
		
		$data['userRec'] = $userRec;
		
		$data['topCatArr'] = $this->category_model->get_top_categories(0, 0, 'title', 'asc', array());
		
		//$this->load->view('category/add');
		$this->body = 'category/add';
		$this->data = $data;
		$this->layout('inner');
	}
	
	
	public function deletecatimage()
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
		//print_r($_SESSION);
		
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
		
		$csrf_npi_token=$this->input->get('csrf_wc_token');
		 
		if($csrf_npi_token!=$hash)
		{
			$errArr[]="Security tokens do not match.";
		}
		else
		{

			$type=$this->input->get('type');

			if(!empty($type))
			{
				if(!in_array($type, array('thumb_image', 'large_image')))
				{
					$errArr[]="Invalid Image type provided.";	
				}
			}
			else
			{
				$errArr[]="Image type is required.";
			}
		
			$id=$this->input->get('id');
			
			if(!empty($id))
			{
				if(!$this->validation->isInteger($id))
				{
					$errArr[]="Invalid Category ID provided.";	
				}
			}
			else
			{
				$errArr[]="Category ID is required.";
			}
			

			if(empty($errArr))
			{
				
				$catRec=$this->category_model->get_category($id);
				
				if($type=='thumb_image')
				{
					@unlink(FCPATH.'uploads/category/thumb/'.$catRec->thumb_image);
					$this->category_model->edit_category(array('thumb_image'=>''), $id);
				}
				else if($type=='large_image')
				{
					@unlink(FCPATH.'uploads/category/'.$catRec->large_image);
					$this->category_model->edit_category(array('large_image'=>''), $id);
				}
				
				
				
				////////////////add to audit trail////////////////
				$dataArr=array();
				$dataArr['user_id']=$userRec->id;
				$dataArr['user_email']=$userRec->email;
				$dataArr['activity']='Delete Category '.$type;
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
			
		if(!empty($errArr))
			$errStr=implode(' ',$errArr);
				
		echo $errStr; 
		exit;
		
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
		
		
		$id=$this->input->get('id');

		
		
		$msg=$this->input->get('msg');
		
		if($msg=='added')
		{	
			$success_message="Category has been added successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='updated')
		{
			$success_message="Category has been updated successfully.";
			$data['success_message']=$success_message;
		}
		else if($msg=='deleted')
		{
			$success_message="Category has been deleted successfully.";
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
		
		
		$search_kw=$this->input->get('search_kw');
		
		$search_kw=$this->validation->xssSafe(trim($search_kw));
		
		
		$parent=$this->input->get('parent');
		

		if($parent=='')
		{
			// no op
		}
		else if($parent=='0')
		{
			// no op
		}
		else 
		{
			//echo "aa"; die;
			
			if(!empty($parent))
			{
				if(!$this->validation->isInteger($parent))
				{
					$errArr[]='Invalid Parent Category.';
					$parent='';
				}
			}
		}
		

		$sort_option=$this->input->get('sort_option');
	
		
		if(!empty($sort_option))
		{
			$sortOptArr=explode('|',$sort_option);	
			
			$sort_field=$sortOptArr[0];
			$sort_order=$sortOptArr[1];
			
			
			if(!in_array($sort_field, array('category.title', 'category.created_at')))
			{
				$errArr[]='Invalid Sort Field.';	
				$sort_field='category.title';
				$sort_option='';
			}
			
			if(!in_array($sort_order, array('asc', 'desc')))
			{
				$errArr[]='Invalid Sort Order.';
				$sort_order='asc';
				$sort_option='';
			}
			
		}
		else
		{
			
			$sort_field='category.title';
			$sort_order='asc';
			$sort_option=$sort_field.'|'.$sort_order;
			
		}
		
		
		$ipp=$this->input->get('ipp');
		
		if(!empty($ipp))
		{
			if(!$this->validation->isInteger($ipp))
			{
				$errArr[]='Invalid Records Per Page.';
				$ipp=0;
			}
		}
		else
		{
			$ipp=50;
		}
		
		
		
		$pagination_config = $this->config->item('pagination_config'); // pagination config array define in \application\config\config.php	
		
		 //$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; //use if if $config['page_query_string']=FALSE;
		
		$page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
		
		//echo $page;die;
		
		if(!$this->validation->isInteger($page))
		{
			//show_error('Invalid argument provided');
			$errArr[]='Invalid Page Number';
			$page=1;
			
		}
		
		
		if($parent=='')
		{
			$optArr=array(
				'search_kw'=>$search_kw
			);
		}
		else
		{
			$optArr=array(
				'search_kw'=>$search_kw,
				'parent'=>$parent
			);
		}
		
		$allCatArr=$this->category_model->get_categories(0, 0, $sort_field, $sort_order, $optArr);
		
		
		$total_rows=count($allCatArr);
	
		$pagination_config['total_rows']=$total_rows;
		$pagination_config['base_url'] = base_url() . "admin/category/index"; 
		
		if(!empty($ipp))
			$per_page=$ipp;
		else
			$per_page=$pagination_config['per_page'];
			
		$pagination_config['per_page']=$per_page; // override config parameter
		
		
		
        $this->pagination->initialize($pagination_config);
		
       
		
		$start=($page-1)*$per_page;
		$limit=$per_page;
		

		$categories = $this->category_model->get_categories($start, $limit, $sort_field, $sort_order, $optArr);
		
		
			
		
		
		$data['categories'] = $categories;
			
			
		
		$data["paging_links"] = $this->pagination->create_links();

		$data["page"] =$page;
		$data["per_page"] =$per_page;
		$data["total_rows"] =$total_rows;
		$data["search_kw"] =$search_kw;
		$data["parent"] =$parent;
		$data["sort_option"] =$sort_option;
		$data["ipp"] =$ipp;
		
		
		if(!empty($errArr))
			$error_message=implode(' ',$errArr);
			
		
	    $data['error_message']=$error_message;
		
		$data['userRec'] = $userRec;
		
		
		 $data['topCatArr'] = $this->category_model->get_top_categories(0, 0, 'title', 'asc', array());
		 
		 
		
		//$this->load->view('category/index');
		$this->body = 'category/index';
		$this->data = $data;
		$this->layout('inner');
		
	
		
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
				$errArr[]="Invalid Category ID.";
			}
			
			
			if(empty($errArr))
			{
				
				
				
				$catRec=$this->category_model->get_category($id);
				
			
				
				$eventArr=$this->event_model->get_events(0,0,'event.created_at','desc',array('cat_id'=>$id)); 
				
				$event_count=count($eventArr);
				
				if($event_count>0)
				{
					$errArr[]="Event(s) exist under this Category so it can't be deleted.";
					$errStr=implode(' ',$errArr);
					echo $errStr; 
					exit;
				}
				else
				{
					
					
					
						//////first delete images////////
	
					
						@unlink(FCPATH.'uploads/category/thumb/'.$catRec->thumb_image);
						//$this->category_model->edit_category(array('thumb_image'=>''), $id);
					
					
						@unlink(FCPATH.'uploads/category/'.$catRec->large_image);
						//$this->category_model->edit_category(array('large_image'=>''), $id);

						$this->category_model->delete_category($id); 
						//////////add to audit trail///////////////
						
						

						
						
						////////////////add to audit trail////////////////
						$dataArr=array();
						$dataArr['user_id']=$userRec->id;
						$dataArr['user_email']=$userRec->email;
						$dataArr['activity']='Delete Category';
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
			
			
		}
		
		
		$errStr=implode(' ',$errArr);
		echo $errStr; 
		exit;
	}
	
	
	

}
