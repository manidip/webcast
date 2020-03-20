<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends MX_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->model('admin_user_model');
        $this->load->model('category_model');
        $this->load->model('event_model');

        $this->load->model('log_model');
		$this->load->model('sg_department_model');
        $this->load->model('stateind_model');
        $this->load->model('ug_organization_model');
        $this->load->model('coordinator_model');

        $this->load->library('my_form_validation');
        $this->load->library('pagination');
        $this->load->library('admin');

        $this->admin->check_sess_timeout();
	}

	public function index()
	{
        $uid = $this->session->admin_session->id;
        $options = array();

		if(empty($uid)){
			redirect('/admin/home/index');
			exit;
		}

        $user = $this->admin_user_model->get_user($uid);

        $msg = $this->input->get('msg');

        if($msg=='added'){
            $data['success_message'] = "Event has been added successfully.";
        }else if($msg=='updated'){
            $data['success_message'] = "Event has been updated successfully.";
        }else if($msg=='deleted'){
            $data['success_message'] = "Event has been deleted successfully.";
        } else if($msg=='error'){
            $data['error_message'] = "An error has occurred.";
        }else if($msg=='delete_error'){
            $data['error_message'] = "Action could not be completed.";
        }else if($msg == 'security_mismatch'){
            $data['error_message'] = "Security tokens do not match.";
        }else if($msg=='invalid_event'){
            $data['error_message'] = "Invalid Event ID.";
        }else if($msg=='event_not_exists'){
            $data['error_message'] = "Event does not exist so it can't be deleted.";
        }else if($msg=='event_has_active_sessions'){
            $data['error_message'] = "Event has active session, so it can't be deleted.";
        }else if($msg == 'auth_error'){
            $data['error_message'] = "You are not authorized to perform this action.";
        }else if($msg == 'invalid_cord'){
            $data['error_message'] = "Invalid Coordinator.";
        }

		$data['title'] = 'List';
        $data['categories']['parents'] = $category_parents = $this->category_model->get_top_categories();

        foreach ($category_parents as $category_parent ){
            $data['categories']['childs'][$category_parent->id] = $this->category_model->get_categories(0, 0, 'created_at', 'desc', array('parent' => $category_parent->id));
        }

        $search_kw = ($this->input->get('search_kw')) ? $this->input->get('search_kw') : '';
        $coordinator = $data['current_coordinator'] = ($this->input->get('coordinator') && $this->validation->isInteger($this->input->get('coordinator'))) ? $this->input->get('coordinator') : '';
        $status = $data['status'] = ($this->input->get('status') && $this->validation->isValidText($this->input->get('status'))) ? $this->input->get('status') : 'all';
        $data['ipp'] = $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 50;
        $sort_option = ($this->input->get('sort_option')) ? $this->input->get('sort_option') : 'event.created_at|desc';


        if(!empty($search_kw)){
            $search_kw = $this->validation->xssSafe(trim($search_kw));
            $options['search_kw'] = $data['search_kw'] = $search_kw;
        }

        if(!empty($sort_option))
        {
            list($sort_field, $sort_order) = explode('|',$sort_option);

            if(!in_array($sort_field, array('event.title_en', 'event.created_at','event.start_date')))
                $sort_field = 'event.created_at';

            if(!in_array($sort_order, array('asc', 'desc')))
                $sort_order = 'desc';

            $data['sort_option'] = $sort_field.'|'.$sort_order;

        }

        if(!empty($coordinator)){
            $cord = $this->coordinator_model->get_coordinator($coordinator);
            if(empty($cord)){
                redirect('/admin/events/index?msg=invalid_cord');
                exit;
            }
            $options['coordinator'] = (int)$coordinator;
        }

        if(!empty($status)){
            $options['status'] = $status;
        }


        $all_events = $this->event_model->get_events(0,0,$sort_field, $sort_order,$options);
        $data['event_data']['total_items'] = count($all_events);

        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/events/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;



        $data['event_data']['events'] = $this->event_model->get_events($start, $limit, $sort_field, $sort_order, $options);

        $pagination_config['total_rows'] = count($all_events);
        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();

        $data['event_data']['events'] =  array_map(function ($event){

            if($event->ministry)
                $event->ministry = $this->ug_organization_model->get_organization($event->ministry);

            if($event->department)
                $event->department = $this->ug_organization_model->get_organization($event->department);

            if($event->organization)
                $event->organization = $this->ug_organization_model->get_organization($event->organization);

            $event->coordinators = $this->event_model->get_event_coordinators($event->id,array('name','id'));

            if($event->state)
                $event->state = $this->stateind_model->get_state($event->state);

                return $event;
            },$data['event_data']['events']);


        $data['coordinators'] = $this->coordinator_model->get_coordinators();
        $data['user'] = $user;

		$this->body = 'events/index';
		$this->data = $data;
		$this->layout('inner');
		
	}

    public function add()
    {
        $uid = $this->session->admin_session->id;
        $event_data = $data = $audit_log = $errors = array();
        $data['editing'] = $data['form_submitting'] = false;

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $user = $this->admin_user_model->get_user($uid);

        $this->form_validation->set_message('is_valid_web_thumb_image', 'The Thumbnail Image field is invalid.');
        $this->form_validation->set_message('is_valid_web_large_image', 'The Large Image field is invalid.');

        $this->form_validation->set_rules('title_en', 'Title (en)', 'required|trim|strip_tags|xss_clean|max_length[100]|is_valid_text');
        $this->form_validation->set_rules('title_hi', 'Title (hi)', 'trim|strip_tags|xss_clean|max_length[100]|is_valid_text');
        $this->form_validation->set_rules('title_reg', 'Title (Regional)', 'trim|strip_tags|xss_clean|max_length[100]|is_valid_text');
        $this->form_validation->set_rules('desc_en', 'Description (en)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('desc_hi', 'Description (hi)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('keywords_en', 'Keywords', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('keywords_hi', 'Keywords (hi)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim|strip_tags|xss_clean|valid_date');
        $this->form_validation->set_rules('end_date', 'End Date', 'required|trim|strip_tags|xss_clean|valid_date');
        $this->form_validation->set_rules('owner', 'Owner', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('ministry', 'Ministry', 'trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('department', 'Department', 'trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('organization', 'Organization', 'trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('categories[]', 'Categories', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('coordinators[]', 'Coordinators', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('source', 'Source', 'required|trim|strip_tags|xss_clean|is_valid_source');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|strip_tags|xss_clean|is_valid_status');
        $this->form_validation->set_rules('audience', 'Audience', 'required|trim|strip_tags|xss_clean|is_valid_audience');

        $msg = $this->input->get('msg');

        if($msg == 'invalid_event')
            $errors[] = "Invalid event.";
        elseif($msg == 'event_not_exists')
            $errors[] = "Event does not exists.";


        $event_id = $this->input->get('event_id');

        if($event_id){

            if(!$this->validation->isInteger($event_id)){
                redirect('/admin/events/add?msg=invalid_event');
                exit;
            }

            $editing_event_data = $this->event_model->get_event($event_id);

            $event_categories = $this->event_model->get_event_category($editing_event_data->id);
            $event_categories= array_column($event_categories, 'id');

            $event_coordinators = $this->event_model->get_event_coordinators($editing_event_data->id);
            $event_coordinators = array_column($event_coordinators, 'id');

            if(empty($editing_event_data)){
                redirect('/admin/events/add?msg=event_not_exists');
                exit;
            }

            $data['editing'] = true;
            $data['event_id'] = $event_id;


            $form_data['title_en'] = $event_data['title_en'] = $editing_event_data->title_en;
            $form_data['title_hi'] = $event_data['title_hi'] = $editing_event_data->title_hi;
            $form_data['title_reg'] = $event_data['title_reg'] = $editing_event_data->title_reg;
            $form_data['desc_en'] = $event_data['desc_en'] = $editing_event_data->desc_en;
            $form_data['desc_hi'] = $event_data['desc_hi'] = $editing_event_data->desc_hi;
            $form_data['keywords_en'] = $event_data['keywords_en'] = $editing_event_data->keywords_en;
            $form_data['keywords_hi'] = $event_data['keywords_hi'] = $editing_event_data->keywords_hi;
            $form_data['owner'] = $event_data['owner'] = $editing_event_data->owner;
            $form_data['nodal_officer'] = $event_data['nodal_officer'] = $editing_event_data->nodal_officer;
            $form_data['start_date'] = $event_data['start_date'] = date('d-m-Y',strtotime($editing_event_data->start_date));
            $form_data['end_date'] = $event_data['end_date'] = date('d-m-Y',strtotime($editing_event_data->end_date));
            $form_data['source'] = $event_data['source'] = $editing_event_data->source;
            $form_data['status'] = $event_data['status'] = $editing_event_data->status;
            $form_data['audience'] = $event_data['audience'] = $editing_event_data->audience;
            $form_data['thumb_image']  = $editing_event_data->thumb_image;
            $form_data['large_image'] = $editing_event_data->large_image;
            $form_data['state'] = $event_data['state'] = $editing_event_data->state;
			$form_data['state_department'] = $event_data['state_department'] = $editing_event_data->state_department;
            $form_data['ministry'] = $event_data['ministry'] = $editing_event_data->ministry;
            $form_data['department'] = $event_data['department'] = $editing_event_data->department;
            $form_data['organization'] = $event_data['organization'] = $editing_event_data->organization;
            $form_data['coordinators'] = $event_coordinators;
            $form_data['categories'] =  $event_categories;
            $form_data['updated_at'] = $event_data['updated_at'] = date('Y-m-d H:i:s');

        }

        if($this->input->post('event_submit')){

            $thumb_image = $_FILES['thumb_image'];
            $large_image = $_FILES['large_image'];

            $image_upload_status = $_POST['image_upload_status'];

            $data['form_submitting'] = true;

            $form_data['title_en'] = $event_data['title_en'] = $this->input->post('title_en');
            $form_data['title_hi'] = $event_data['title_hi'] = $this->input->post('title_hi');
            $form_data['title_reg'] = $event_data['title_reg'] = $this->input->post('title_reg');
            $form_data['desc_en'] = $event_data['desc_en'] = $this->input->post('desc_en');
            $form_data['desc_hi'] = $event_data['desc_hi'] = $this->input->post('desc_hi');
            $form_data['keywords_en'] = $event_data['keywords_en'] = $this->input->post('keywords_en');
            $form_data['keywords_hi'] = $event_data['keywords_hi'] = $this->input->post('keywords_hi');
            $form_data['owner'] = $event_data['owner'] = $this->input->post('owner');
            $form_data['nodal_officer'] = $event_data['nodal_officer'] = $this->input->post('nodal_officer');
            $form_data['start_date'] = $event_data['start_date'] =  date('Y-m-d',strtotime($this->input->post('start_date')));
            $form_data['end_date'] = $event_data['end_date'] = date('Y-m-d',strtotime($this->input->post('end_date')));;
            $form_data['source'] = $event_data['source'] = $this->input->post('source');
            $form_data['status'] = $event_data['status'] = ($user->role == 'creator') ? 'draft' : $this->input->post('status');
            $form_data['audience'] = $event_data['audience'] = $this->input->post('audience');
            $form_data['author'] = $event_data['author'] = $user->id;
            $form_data['state'] = $event_data['state'] = ($this->input->post('state') && $event_data['owner'] == 'state') ? $this->input->post('state') : '';
			$form_data['state_department'] = $event_data['state_department'] = ($this->input->post('state_department') && $event_data['owner'] == 'state') ? $this->input->post('state_department') : '';
            $form_data['ministry'] = $event_data['ministry'] = ($this->input->post('ministry') && $event_data['owner'] == 'central') ? $this->input->post('ministry') : '';
            $form_data['department'] = $event_data['department'] = ($this->input->post('department') && $event_data['owner'] == 'central') ? $this->input->post('department') : '';
            $form_data['organization'] = $event_data['organization '] = ($this->input->post('organization') && $event_data['owner'] == 'central') ? $this->input->post('organization') : '';

            $form_data['coordinators'] = $coordinators = ($this->input->post('coordinators')) ? $this->input->post('coordinators') : array();
            $form_data['categories'] = $categories = ($this->input->post('categories')) ? $this->input->post('categories') : array();

            if(!$event_id){
                $form_data['created_at'] = $event_data['created_at'] = date('Y-m-d H:i:s');
            }

            if($event_data['owner'] == 'state') {
                $this->form_validation->set_rules('state', 'State', 'required|trim|strip_tags|xss_clean');
				$this->form_validation->set_rules('state_department', 'Department', 'required|trim|strip_tags|xss_clean');
            }elseif($event_data['owner'] == 'central'){
                $this->form_validation->set_rules('ministry', 'Ministry', 'required|trim|strip_tags|xss_clean|integer');
                $this->form_validation->set_rules('department', 'Department', 'trim|strip_tags|xss_clean|integer');
            }

            if((!$event_id && $thumb_image['error'] == 4) || ($event_id && $image_upload_status['thumb_image'] && $thumb_image['error'] == 4))
                $this->form_validation->set_rules('thumb_image', 'Thumbnail Image', 'required');
            else if(!empty($thumb_image['name']) && !$this->my_form_validation->is_valid_web_image($thumb_image))
                $this->form_validation->set_rules('thumb_image', 'Thumbnail Image', 'callback_is_valid_web_thumb_image');
            else
                $form_data['thumb_image']  = $editing_event_data->thumb_image;


            if(!$event_id && $large_image['error'] == 4 || ($event_id && $image_upload_status['large_image'] && $large_image['error'] == 4))
                $this->form_validation->set_rules('large_image', 'Large Image', 'required');
            else if(!empty($large_image['name']) && !$this->my_form_validation->is_valid_web_image($large_image))
                $this->form_validation->set_rules('large_image', 'Large Image', 'callback_is_valid_web_large_image');
            else
                $form_data['large_image'] = $editing_event_data->large_image;

            if(empty($errors) && $this->form_validation->run() == TRUE){

                if($event_id && $thumb_image['error'] != 4){
                    @unlink(FCPATH . $editing_event_data->thumb_image);
                }
                if($event_id && $large_image['error'] != 4){
                    @unlink(FCPATH . $editing_event_data->large_image);
                }

                if($thumb_image['error'] != 4){
                    $rand_string = $this->validation->generateRandomAlphaNumericString(10);
                    $enc_rand_string = substr(md5(microtime().$rand_string), 1, 16);
                    $thumb_image_dyn_name = $enc_rand_string.'_'.$this->validation->sanitize_file_name($thumb_image['name']);
                    $thumb_image_path = 'uploads/events/thumb/'.$thumb_image_dyn_name;
                    $thumb_image_full_path = FCPATH . $thumb_image_path;
                    move_uploaded_file($thumb_image['tmp_name'],$thumb_image_full_path);
                    $event_data['thumb_image'] = $thumb_image_path;
                }

                if($large_image['error'] != 4){
                    $rand_string = $this->validation->generateRandomAlphaNumericString(10);
                    $enc_rand_string = substr(md5(microtime().$rand_string), 1, 16);
                    $large_image_dyn_name = $enc_rand_string.'_'.$this->validation->sanitize_file_name($large_image['name']);
                    $large_image_path = 'uploads/events/'.$large_image_dyn_name;
                    $large_image_full_path = FCPATH . $large_image_path;
                    move_uploaded_file($large_image['tmp_name'],$large_image_full_path);
                    $event_data['large_image'] = $large_image_path;
                }


                if($event_id){
                    $event_id = $this->event_model->update_event($event_data,$event_id);
                }else{
                    $event_id = $this->event_model->add_event($event_data);
                }

                $this->event_model->add_event_relationship($event_id, $categories);
                $this->event_model->add_event_relationship($event_id, $coordinators, 'coordinator');

                $audit_log['user_id'] = $user->id;
                $audit_log['user_email']= $user->email;
                $audit_log['item_id'] = $event_id;
                $audit_log['activity_time']=date('Y-m-d H:i:s');
                $audit_log['activity_result']='successful';
                $audit_log['ip_address'] = $this->validation->get_client_ip();

                if($event_id){
                    $audit_log['activity']='Edit Event';
                }else{
                    $audit_log['activity']='Add Event';
                }

                $this->log_model->add_log($audit_log);

                if($event_id){
                    redirect('/admin/events/index?msg=updated');
                }else{
                    redirect('/admin/events/index?msg=added');
                }

            }else{
                $errors = $this->form_validation->error_array();
            }

        }

        $data['title'] = ($data['editing']) ? 'Edit' : 'Add';
        $data['categories']['parents'] = $category_parents = $this->category_model->get_top_categories();

        foreach ($category_parents as $category_parent ){
            $data['categories']['childs'][$category_parent->id] = $this->category_model->get_categories(0, 0, 'created_at', 'desc', array('parent' => $category_parent->id));
        }


        $data['states'] = $this->stateind_model->get_states();
        $data['form_data'] = $form_data;
        $data['ministries'] = $this->ug_organization_model->get_ministries();
        $data['departments'] = $this->ug_organization_model->get_departments();
        $data['coordinators'] = $this->coordinator_model->get_coordinators();
        $data['user'] = $user;

        $data['errors'] = $errors;

        $this->body = 'events/add';
        $this->data = $data;
        $this->layout('inner');



    }
    public function delete()
    {

        $uid = $this->session->admin_session->id;
        $audit_log = $errors = array();

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $user = $this->admin_user_model->get_user($uid);

        if($user->role !='admin'){
            redirect('/admin/events/index?msg=auth_error');
            exit;
        }

        $use_csrf_salt = config_item('use_csrf_salt');

        if($use_csrf_salt){
            $csrf_salt=$this->session->csrf_salt;
            $hash = md5($this->security->get_csrf_hash().$csrf_salt);
        }else{
            $hash = $this->security->get_csrf_hash();
        }

        $csrf_wc_token=$this->input->get('csrf_wc_token');

        if($csrf_wc_token != $hash){
            redirect('/admin/events/index?msg=security_mismatch');
            exit;
        }else{

            $event_id =$this->input->get('event_id');

            if(!$this->validation->isInteger($event_id)){
                redirect('/admin/events/index?msg=invalid_event');
                exit;
            }


            if(empty($errors))
            {
                $event = $this->event_model->get_event($event_id);

                if(empty($event)){
                    redirect('/admin/events/index?msg=event_not_exists');
                    exit;
                }
                else
                {

                    $active_event_sessions = $this->event_model->get_active_sessions($event_id);

                    if(count($active_event_sessions) > 0){
                        redirect('/admin/events/index?msg=event_has_active_sessions');
                        exit;
                    }


                    //@unlink(FCPATH.$event->thumb_image);
                   // @unlink(FCPATH.$event->large_image);

                    //$this->event_model->delete_event($event->id);

                    $audit_log['user_id'] = $user->id;
                    $audit_log['user_email'] = $user->email;
                    $audit_log['activity'] = 'Delete Event';
                    $audit_log['item_id'] = $event->id;
                    $audit_log['activity_time'] = date('Y-m-d H:i:s');
                    $audit_log['activity_result'] = 'success';
                    $audit_log['ip_address'] = $this->validation->get_client_ip();
                    //$this->log_model->add_log($audit_log);

                    # update csrf salt in session
                    $csrf_salt = md5(uniqid(mt_rand()));
                    $this->session->csrf_salt = $csrf_salt;

                    redirect('/admin/events/index?msg=deleted');

                }
            }
        }
    }
    public function get_ug_data(){
		
		$uid = $this->session->admin_session->id;
		
		if(empty($uid)){
			redirect('/admin/dashboard/index');
			exit;
		}
		
		$userRec=$this->admin_user_model->get_user($uid);
		
		$errArr=array();
		

		$ministry_id=$this->input->get('ministry_id');
		
		if(!empty($ministry_id))
		{
		 
			if(!$this->validation->isInteger($ministry_id))
			{
				$errArr[]="Invalid Ministry ID.";
				$ministry_id='';
			}
		}
		
		
		$selected_dept_id = $this->input->get('selected_dept_id');
		
		if(!empty($selected_dept_id))
		{
		 
			if(!$this->validation->isInteger($selected_dept_id))
			{
				$errArr[]="Invalid Selected Depatment ID.";
				$selected_dept_id='';
			}
		}
		
		
		$selected_org_id=$this->input->get('selected_org_id');
		
		if(!empty($selected_org_id))
		{
		 
			if(!$this->validation->isInteger($selected_org_id))
			{
				$errArr[]="Invalid Selected Organization ID.";
				$selected_org_id='';
			}
		}
		
		
		$dept_id=$this->input->get('dept_id');
		
		if(!empty($dept_id))
		{
		 
			if(!$this->validation->isInteger($dept_id))
			{
				$errArr[]="Invalid Depatment ID.";
				$dept_id='';
			}
		}
		
		$type=$this->input->get('type');
		
		if($type!='min_dept' && $type!='min_org' && $type!='dept_org')
		{
			$errArr[]="Invalid Type of Data.";
			$type='';
		}
		
		
		if(empty($errArr))
		{
		
		
			$optArr=array();
			
			if($type=='min_dept')
				$optArr['ministry_id']=$ministry_id;
			else if($type=='min_org')
				$optArr['ministry_id']=$ministry_id;
			else if($type=='dept_org'){
				$optArr['ministry_id']=$ministry_id;
				$optArr['dept_id']=$dept_id;
			}
			
			
			$data=array();
			
			
			
			
			$deptArr = $this->ug_organization_model->get_ug_data($type, 0, 0, 'orgn_name', 'asc', $optArr);
		
			//print_r($deptArr); die;
			
			$options=''; // for select box
			
			$orgvals=array(); // for auto complete box
			
			
			if(!empty($deptArr))
			{
				
				$options.='<option value="">Select</option>';
				
				foreach($deptArr as $deptRec)
				{
					$options.='<option value="'.$this->validation->xssSafe($deptRec['orgn_id']).'" ';
					
					if($type=='min_dept'){
						
						if($deptRec['orgn_id']==$selected_dept_id)
							$options.='selected';
						
					}
					else if($type=='min_org'){
						
						if($deptRec['orgn_id']==$selected_org_id)
							$options.='selected';
					}
					else if($type=='dept_org'){
						
						if($deptRec['orgn_id']==$selected_org_id)
							$options.='selected';
					}
					
					
					$options.=' >';
					
					
					$options.=$this->validation->xssSafe($deptRec['orgn_name']);
					
					$options.='</option>'; // for select box
					
					$orgvals[]=$this->validation->xssSafe($deptRec['orgn_name']).' ['.$this->validation->xssSafe($deptRec['orgn_id']).']'; // for auto complete box
				}
			}
			
			$data['result']=$options;
			$data['orgvals']=$orgvals;
			
			echo  json_encode($data);
			
	

		}
		
		exit;
		
	}
    public function get_event(){

        $event_id = $this->input->get('event_id');

        if(empty($event_id)) return;

        if(!$this->validation->isInteger($event_id)) return;

        $event_details =  $this->event_model->get_event($event_id,array('start_date','end_date'));

        $event_details->start_date = date('d-m-Y',strtotime($event_details->start_date));
        $event_details->end_date = date('d-m-Y',strtotime($event_details->end_date));

        if(!empty($event_details)) echo json_encode(array('result' => $event_details));
        die();

    }

    public  function get_ajax_coordinators(){

        $uid = $this->session->admin_session->id;

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $response = array();
        $response['items'] = array();
        $response['total_count'] = 0;

        $limit = (isset($_REQUEST['pageSize']) && !empty($_REQUEST['pageSize']) && is_numeric($_REQUEST['pageSize'])) ? $_REQUEST['pageSize'] : 2;
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $start = ($page - 1 ) * $limit;
        $search_term = (isset($_REQUEST['searchTerm']) && strip_tags($_REQUEST['searchTerm']) == $_REQUEST['searchTerm']) ? $_REQUEST['searchTerm'] : '';
        $sort_field='created_at';
        $sort_order='desc';
        $options = array();

        /*if(strip_tags($_REQUEST['searchTerm']) !== $_REQUEST['searchTerm']){
            echo json_encode($response);
            die();
        }*/
        $options['status'] = 1;
        if(!empty($search_term)){
            $options['search_kw'] = $_REQUEST['searchTerm'];
        }

	    $coordinators = $this->coordinator_model->get_coordinators($start,$limit,$sort_field,$sort_order,$options);

	    if(empty($coordinators)) {
            echo json_encode($response);
            die();
        }
	    if(!empty($coordinators)) {

            $coordinators = array_map(function ($coordinator) {

                if ($coordinator->ministry)
                    $coordinator->ministry = $this->ug_organization_model->get_organization($coordinator->ministry);

                if ($coordinator->department)
                    $coordinator->department = $this->ug_organization_model->get_organization($coordinator->department);

                if ($coordinator->organization)
                    $coordinator->organization = $this->ug_organization_model->get_organization($coordinator->organization);

                return $coordinator;
            }, $coordinators);

            foreach ($coordinators as $coordinator) {

                $id = $coordinator->id;
                $text = $coordinator->name;

                if (isset($coordinator->ministry->orgn_name) && !empty($coordinator->ministry->orgn_name))
                    $text .= ' - ' . $coordinator->ministry->orgn_name;

                if (isset($coordinator->department->orgn_name) && !empty($coordinator->department->orgn_name))
                    $text .= '/' . $coordinator->department->orgn_name;

                if (isset($coordinator->organization->orgn_name) && !empty($coordinator->organization->orgn_name))
                    $text .= '/' . $coordinator->organization->orgn_name;

                $response['items'][] = array('id' => $id, 'text' => $text);

            }
            $response['total_count'] = $this->coordinator_model->get_coordinator_count();
        }else{
            $response['items'] = array();
            $response['total_count'] = 0;
        }
        echo json_encode($response);
	    die();
    }
	public function get_state_departments(){
		
		 $uid = $this->session->admin_session->id;
		 $response = array();

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }
		
		$state_id  = $this->input->get('state_id');
		
		if(!empty($state_id)){
			
			if(!is_numeric($state_id)){
				$response['error'] = "Invalid State ID.";
				echo  json_encode($response);
				die();
			}
			
			$get_state = $this->stateind_model->get_state($state_id);
			
			if(empty($get_state)){
				$response['error'] = "State Does not Exists.";
				echo  json_encode($response);
				die();
			}
			
			$state_code = $get_state->state_code;
			$get_state_options = array('state_id' => $state_code);
		}
	
		
		$selected_dept_id = $this->input->get('selected_dept_id');
		
		$departments = $this->sg_department_model->get_departments(0, 0, 'department_name', 'asc', $get_state_options );
		
		if(!empty($departments))
			{
				
				$options.='<option value="">Select</option>';
				
				foreach($departments as $department)
				{
					$options.='<option value="'.$this->validation->xssSafe($department->department_id).'" ';
					
					$options .= ($department->department_id == $selected_dept_id) ? 'selected' : '';
						
					$options.= ' >';
					
					$options.= $this->validation->xssSafe($department->department_name);
					
					$options.='</option>';
					
					$orgvals[]=$this->validation->xssSafe($department->department_name).' ['.$this->validation->xssSafe($department->department_id).']';
				}
			}
			
			$response['result'] = $options;
			$response['orgvals']= $orgvals;
			
			echo  json_encode($response);
		
		
		
	}
}
