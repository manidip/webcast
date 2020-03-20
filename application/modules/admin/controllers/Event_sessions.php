<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_Sessions extends MX_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->model('admin_user_model');
        $this->load->model('category_model');
        $this->load->model('event_model');
        $this->load->model('event_session_model');

        $this->load->model('log_model');
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
            $data['success_message'] = "Event Session has been added successfully.";
        }else if($msg=='updated'){
            $data['success_message'] = "Event Session has been updated successfully.";
        }else if($msg=='deleted'){
            $data['success_message'] = "Event Session has been deleted successfully.";
        } else if($msg=='error'){
            $data['error_message'] = "An error has occurred.";
        }else if($msg=='delete_error'){
            $data['error_message'] = "Action could not be completed.";
        }else if($msg == 'security_mismatch'){
            $data['error_message'] = "Security tokens do not match.";
        }else if($msg=='invalid_event_session'){
            $data['error_message'] = "Invalid Event ID.";
        }else if($msg=='event_session_not_exists'){
            $data['error_message'] = "Event Session does not exist so it can't be deleted.";
        }else if($msg=='auth_error'){
            $data['error_message'] = "You are not authorized to perform this action.";
        }

		$data['title'] = 'List';

        $search_kw = ($this->input->get('search_kw')) ? $this->input->get('search_kw') : '';
        $data['ipp'] = $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 10;
        $data['event_selected'] = $event_selected = ($this->input->get('event') && $this->validation->isInteger($this->input->get('event'))) ? $this->input->get('event') : '';
        $sort_option = ($this->input->get('sort_option')) ? $this->input->get('sort_option') : 'event_sessions.created_at|desc';


        if(!empty($search_kw)){
            $search_kw = $this->validation->xssSafe(trim($search_kw));
            $options['search_kw'] = $data['search_kw'] = $search_kw;
        }

        if(!empty($sort_option))
        {
            list($sort_field, $sort_order) = explode('|',$sort_option);

            if(!in_array($sort_field, array('event_sessions.title_en', 'event_sessions.created_at','event_sessions.start_time')))
                $sort_field = 'event_sessions.created_at';

            if(!in_array($sort_order, array('asc', 'desc')))
                $sort_order = 'desc';

            $data['sort_option'] = $sort_field.'|'.$sort_order;

        }

        if(!empty($event_selected)){
            $options['event'] = $event_selected;
        }


        $all_event_sessions = $this->event_session_model->get_event_sessions(0,0,$sort_field, $sort_order,$options);
        $data['event_data']['total_items'] = count($all_event_sessions);

        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/event_sessions/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;



        $data['event_data']['event_sessions'] = $this->event_session_model->get_event_sessions($start, $limit, $sort_field, $sort_order, $options);

        $pagination_config['total_rows'] = count($all_event_sessions);
        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();

        $data['event_data']['event_sessions'] =  array_map(function ($event_session){

            if($event_session->event_id)
                $event_session->event = $this->event_model->get_event($event_session->event_id,array('id','title_en'));

            if($event_session->ministry)
                $event_session->ministry = $this->ug_organization_model->get_organization($event_session->ministry);

            if($event_session->department)
                $event_session->department = $this->ug_organization_model->get_organization($event_session->department);

            if($event_session->organization)
                $event_session->organization = $this->ug_organization_model->get_organization($event_session->organization);

            $event_session->coordinators = $this->event_model->get_event_coordinators($event_session->id,array('name','id'));

            if($event_session->state)
                $event_session->state = $this->stateind_model->get_state($event_session->state);

                return $event_session;
            },$data['event_data']['event_sessions']);


        $data['events'] = $this->event_model->get_events(0,0,'event.created_at','desc',array('status' => 'all'));
        $data['user'] = $user;

		$this->body = 'event_sessions/index';
		$this->data = $data;
		$this->layout('inner');
		
	}
    public function add()
    {
        $uid = $this->session->admin_session->id;
        $event_session_data = $data = $audit_log = $errors = array();
        $data['editing'] = $data['form_submitting'] = false;

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $user = $this->admin_user_model->get_user($uid);

        $this->form_validation->set_message('is_valid_web_thumb_image', 'The Thumbnail Image field is invalid.');
        $this->form_validation->set_message('is_valid_web_large_image', 'The Large Image field is invalid.');

        $this->form_validation->set_rules('event_id', 'Event', 'required|trim|strip_tags|xss_clean|integer');
        $this->form_validation->set_rules('title_en', 'Title (en)', 'required|trim|strip_tags|xss_clean|max_length[255]|is_valid_text');
        $this->form_validation->set_rules('title_hi', 'Title (hi)', 'required|trim|strip_tags|xss_clean|max_length[255]|is_valid_text');
        $this->form_validation->set_rules('desc_en', 'Description (en)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('desc_hi', 'Description (hi)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('keywords_en', 'Keywords', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('keywords_hi', 'Keywords (hi)', 'required|trim|strip_tags|xss_clean|max_length[1000]|is_valid_text');
        $this->form_validation->set_rules('speakers', 'Speakers', 'trim|strip_tags|xss_clean|is_valid_text');
        $this->form_validation->set_rules('vip', 'VIP', 'required|trim|strip_tags|xss_clean|is_valid_text');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('end_time', 'End Time', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|strip_tags|xss_clean|is_valid_status');
        $this->form_validation->set_rules('session_status', 'Session Status', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('session_embed', 'Embed Code', 'required|trim|xss_clean');

        $msg = $this->input->get('msg');

        if($msg == 'invalid_event_session')
            $errors[] = "Invalid event session.";
        elseif($msg == 'event_session_not_exists')
            $errors[] = "Event session does not exists.";


        $event_session_id = $this->input->get('event_session_id');

        if($event_session_id){

            if(!$this->validation->isInteger($event_session_id)){
                redirect('/admin/event_sessions/add?msg=invalid_event_session');
                exit;
            }

            $editing_event_session_data = $this->event_session_model->get_event_session($event_session_id);

            if(empty($editing_event_session_data)){
                redirect('/admin/events/add?msg=event_session_not_exists');
                exit;
            }

            $data['editing'] = true;
            $data['event_session_id'] = $event_session_id;


            $form_data['event_id'] = $event_session_data['event_id'] = $editing_event_session_data->event_id;
            $form_data['title_en'] = $event_session_data['title_en'] = $editing_event_session_data->title_en;
            $form_data['title_hi'] = $event_session_data['title_hi'] = $editing_event_session_data->title_hi;
            $form_data['desc_en'] = $event_session_data['desc_en'] = $editing_event_session_data->desc_en;
            $form_data['desc_hi'] = $event_session_data['desc_hi'] = $editing_event_session_data->desc_hi;
            $form_data['keywords_en'] = $event_session_data['keywords_en'] = $editing_event_session_data->keywords_en;
            $form_data['keywords_hi'] = $event_session_data['keywords_hi'] = $editing_event_session_data->keywords_hi;
            $form_data['speakers'] = $event_session_data['speakers'] = $editing_event_session_data->speakers;
            $form_data['vip'] = $event_session_data['vip'] = $editing_event_session_data->vip;
            $form_data['start_time'] = $event_session_data['start_time'] = date('d-m-Y H:i',strtotime($editing_event_session_data->start_time));
            $form_data['end_time'] = $event_session_data['end_time'] = date('d-m-Y H:i',strtotime($editing_event_session_data->end_time));
            $form_data['status'] = $event_session_data['status'] = $editing_event_session_data->status;
            $form_data['session_status'] = $event_session_data['session_status'] = $editing_event_session_data->session_status;
            $form_data['session_embed'] = $event_session_data['session_embed'] = $editing_event_session_data->session_embed;
            $form_data['thumb_image']  = $editing_event_session_data->thumb_image;
            $form_data['large_image'] = $editing_event_session_data->large_image;
            $form_data['updated_at'] = $event_session_data['updated_at'] = date('Y-m-d H:i:s');

        }

        if($this->input->post('event_session_submit')){

            $thumb_image = $_FILES['thumb_image'];
            $large_image = $_FILES['large_image'];

            $image_upload_status = $_POST['image_upload_status'];

            $data['form_submitting'] = true;

            $form_data['event_id'] = $event_session_data['event_id'] = $this->input->post('event_id');
            $form_data['title_en'] = $event_session_data['title_en'] = $this->input->post('title_en');
            $form_data['title_hi'] = $event_session_data['title_hi'] = $this->input->post('title_hi');
            $form_data['desc_en'] = $event_session_data['desc_en'] = $this->input->post('desc_en');
            $form_data['desc_hi'] = $event_session_data['desc_hi'] = $this->input->post('desc_hi');
            $form_data['keywords_en'] = $event_session_data['keywords_en'] = $this->input->post('keywords_en');
            $form_data['keywords_hi'] = $event_session_data['keywords_hi'] = $this->input->post('keywords_hi');
            $form_data['speakers'] = $event_session_data['speakers'] = $this->input->post('speakers');
            $form_data['vip'] = $event_session_data['vip'] = $this->input->post('vip');
            $form_data['start_time'] = $event_session_data['start_time'] = date('Y-m-d H:i',strtotime($this->input->post('start_time')));
            $form_data['end_time'] = $event_session_data['end_time'] = date('Y-m-d H:i',strtotime($this->input->post('end_time')));
            $form_data['status'] = $event_session_data['status'] = ($user->role == 'creator') ? 'draft' : $this->input->post('status');
            $form_data['session_status'] = $event_session_data['session_status'] = $this->input->post('session_status');
            $form_data['session_embed'] = $event_session_data['session_embed'] = $this->input->post('session_embed');
           

            if(!$event_session_id){
                $form_data['created_at'] = $event_session_data['created_at'] = date('Y-m-d H:i:s');
            }

            if(!$event_session_id && $thumb_image['error'] == 4 || ($event_session_id && $image_upload_status['thumb_image'] && $thumb_image['error'] == 4))
                $this->form_validation->set_rules('thumb_image', 'Thumbnail Image', 'required');
            else if(!empty($thumb_image['name']) && !$this->my_form_validation->is_valid_web_image($thumb_image))
                $this->form_validation->set_rules('thumb_image', 'Thumbnail Image', 'callback_is_valid_web_thumb_image');
            else
                $form_data['thumb_image']  = $editing_event_session_data->thumb_image;



            if(!$event_session_id && $large_image['error'] == 4 || ($event_session_id && $image_upload_status['thumb_image'] && $large_image['error'] == 4))
                $this->form_validation->set_rules('large_image', 'Large Image', 'required');
            else if(!empty($large_image['name']) && !$this->my_form_validation->is_valid_web_image($large_image))
                $this->form_validation->set_rules('large_image', 'Large Image', 'callback_is_valid_web_large_image');
            else
                $form_data['large_image'] = $editing_event_session_data->large_image;



            if(empty($errors) && $this->form_validation->run() == TRUE){

                if($event_session_id && $thumb_image['error'] != 4){
                    @unlink(FCPATH . $editing_event_session_data->thumb_image);
                }
                if($event_session_id && $large_image['error'] != 4){
                    @unlink(FCPATH . $editing_event_session_data->large_image);
                }

                if($thumb_image['error'] != 4){
                    $rand_string = $this->validation->generateRandomAlphaNumericString(10);
                    $enc_rand_string = substr(md5(microtime().$rand_string), 1, 16);
                    $thumb_image_dyn_name = $enc_rand_string.'_'.$this->validation->sanitize_file_name($thumb_image['name']);
                    $thumb_image_path = 'uploads/events/thumb/'.$thumb_image_dyn_name;
                    $thumb_image_full_path = FCPATH . $thumb_image_path;
                    move_uploaded_file($thumb_image['tmp_name'],$thumb_image_full_path);
                    $event_session_data['thumb_image'] = $thumb_image_path;
                }

                if($large_image['error'] != 4){
                    $enc_rand_string = substr(md5(microtime().$rand_string), 1, 16);
                    $large_image_dyn_name = $enc_rand_string.'_'.$this->validation->sanitize_file_name($large_image['name']);
                    $large_image_path = 'uploads/events/'.$large_image_dyn_name;
                    $large_image_full_path = FCPATH . $large_image_path;
                    move_uploaded_file($large_image['tmp_name'],$large_image_full_path);
                    $event_session_data['large_image'] = $large_image_path;
                }


                if($event_session_id){
                    $event_session_id = $this->event_session_model->update_event_session($event_session_data,$event_session_id);
                }else{
                    $event_session_id = $this->event_session_model->add_event_session($event_session_data);
                }

                $audit_log['user_id'] = $user->id;
                $audit_log['user_email']= $user->email;
                $audit_log['item_id'] = $event_session_id;
                $audit_log['activity_time']= date('Y-m-d H:i:s');
                $audit_log['activity_result']='successful';
                $audit_log['ip_address'] = $this->validation->get_client_ip();

                if($event_session_id){
                    $audit_log['activity']='Edit Event Session';
                }else{
                    $audit_log['activity']='Add Event Session';
                }

                $this->log_model->add_log($audit_log);

                if($event_session_id){
                    redirect('/admin/event_sessions/index?msg=updated');
                }else{
                    redirect('/admin/event_sessions/index?msg=added');
                }

            }else{
                $errors = $this->form_validation->error_array();
            }

        }

        $data['title'] = ($data['editing']) ? 'Edit' : 'Add';

        $data['form_data'] = $form_data;
        $data['events'] = $this->event_model->get_events(0,0,'event.created_at','desc',array('status' => 'upcoming'));
        $data['user'] = $user;
        $data['errors'] = $errors;

        $this->body = 'event_sessions/add';
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
            redirect('/admin/event_sessions/index?msg=auth_error');
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

            $event_session_id =$this->input->get('event_session_id');

            if(!$this->validation->isInteger($event_session_id)){
                redirect('/admin/events/index?msg=invalid_event_session');
                exit;
            }


            if(empty($errors))
            {
                $event_session = $this->event_session_model->get_event_session($event_session_id);

                if(empty($event_session)){
                    redirect('/admin/events/index?msg=event_session_not_exists');
                    exit;
                }
                else
                {

                    @unlink(FCPATH.$event_session->thumb_image);
                    @unlink(FCPATH.$event_session->large_image);

                    $this->event_session_model->delete_event_session($event_session->id);

                    $audit_log['user_id'] = $user->id;
                    $audit_log['user_email'] = $user->email;
                    $audit_log['activity'] = 'Delete Event Session.';
                    $audit_log['item_id'] = $event_session->id;
                    $audit_log['activity_time'] = date('Y-m-d H:i:s');
                    $audit_log['activity_result'] = 'success';
                    $audit_log['ip_address'] = $this->validation->get_client_ip();
                    $this->log_model->add_log($audit_log);

                    # update csrf salt in session
                    $csrf_salt = md5(uniqid(mt_rand()));
                    $this->session->csrf_salt = $csrf_salt;

                    redirect('/admin/event_sessions/index?msg=deleted');

                }
            }
        }
    }
    public function get_ug_data()
	{
		
		if(empty($this->session->admin_session->id))
		{
			redirect('/admin/dashboard/index');
			exit;
		}
		
	
		
		$uid=$this->session->ims_session->id; // logged in user
		
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
}
