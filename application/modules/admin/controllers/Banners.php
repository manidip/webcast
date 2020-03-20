<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends MX_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->model('admin_user_model');
        $this->load->model('banner_model');
        $this->load->model('log_model');
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

        $msg = $this->input->get('msg');

        if($msg=='added'){
            $data['success_message'] = "Banner has been added successfully.";
        }else if($msg=='updated'){
            $data['success_message'] = "Banner has been updated successfully.";
        }else if($msg=='deleted'){
            $data['success_message'] = "Banner has been deleted successfully.";
        } else if($msg=='error'){
            $data['error_message'] = "An error has occurred.";
        }else if($msg=='delete_error'){
            $data['error_message'] = "Action could not be completed.";
        }else if($msg == 'security_mismatch'){
            $data['error_message'] = "Security tokens do not match.";
        }else if($msg=='invalid_banner'){
            $data['error_message'] = "Invalid Banner ID.";
        }else if($msg=='banner_not_exists'){
            $data['error_message'] = "Banner does not exist so it can't be deleted.";
        }

		$data['title'] = 'List';

        $search_kw = ($this->input->get('search_kw')) ? $this->input->get('search_kw') : '';
        $data['ipp'] = $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 50;
        $sort_option = ($this->input->get('sort_option')) ? $this->input->get('sort_option') : 'banners.created_at|desc';


        if(!empty($search_kw)){
            $search_kw = $this->validation->xssSafe(trim($search_kw));
            $options['search_kw'] = $data['search_kw'] = $search_kw;
        }

        if(!empty($sort_option))
        {
            list($sort_field, $sort_order) = explode('|',$sort_option);

            if(!in_array($sort_field, array('banners.title_en', 'banners.created_at','banners.start_time')))
                $sort_field = 'banners.created_at';

            if(!in_array($sort_order, array('asc', 'desc')))
                $sort_order = 'desc';

            $data['sort_option'] = $sort_field.'|'.$sort_order;

        }

        if(!empty($banner_selected)){
            $options['event'] = $banner_selected;
        }


        $all_banners = $this->banner_model->get_banners(0,0,$sort_field, $sort_order,$options);
        $data['banners']['total_items'] = count($all_banners);

        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/banners/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;



        $data['banner_data']['banners'] = $this->banner_model->get_banners($start, $limit, $sort_field, $sort_order, $options);

        $pagination_config['total_rows'] = count($all_banners);
        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();


        $data['banners'] = $this->banner_model->get_banners();

		$this->body = 'banners/index';
		$this->data = $data;
		$this->layout('inner');
		
	}
    public function add()
    {
        $uid = $this->session->admin_session->id;
        $banner_data = $data = $audit_log = $errors = array();
        $data['editing'] = $data['form_submitting'] = false;

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $user = $this->admin_user_model->get_user($uid);

        $this->form_validation->set_rules('title_en', 'Title (en)', 'required|trim|strip_tags|xss_clean|max_length[255]|is_valid_text');
        $this->form_validation->set_rules('url', 'URL', 'required|trim|strip_tags|xss_clean|valid_url');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|strip_tags|xss_clean|is_valid_status');

        if($this->input->post('display_order'))
        {
            $this->form_validation->set_rules('display_order', 'Display Order', 'trim|is_integer');
        }

        $msg = $this->input->get('msg');

        if($msg == 'invalid_banner')
            $errors[] = "Invalid banner.";
        elseif($msg == 'banner_not_exists')
            $errors[] = "Banner does not exists.";


        $banner_id = $this->input->get('banner_id');

        if($banner_id){

            if(!$this->validation->isInteger($banner_id)){
                redirect('/admin/banners/add?msg=invalid_banner');
                exit;
            }

            $editing_banner_data = $this->banner_model->get_banner($banner_id);

            if(empty($editing_banner_data)){
                redirect('/admin/banners/add?msg=banner_not_exists');
                exit;
            }

            $data['editing'] = true;
            $data['banner_id'] = $banner_id;


            $form_data['title_en'] = $banner_data['title_en'] = $editing_banner_data->title_en;
            $form_data['url'] = $banner_data['url'] = $editing_banner_data->url;
            $form_data['display_order'] = $editing_banner_data->display_order;
            $form_data['large_image'] = $editing_banner_data->large_image;
            $form_data['status'] = $banner_data['status'] = $editing_banner_data->status;
            $form_data['updated_at'] = $banner_data['updated_at'] = date('Y-m-d H:i:s');

        }

        if($this->input->post('banner_submit')){

            $thumb_image = $_FILES['thumb_image'];
            $large_image = $_FILES['large_image'];

            $data['form_submitting'] = true;

            $form_data['title_en'] = $banner_data['title_en'] = $this->input->post('title_en');
            $form_data['url'] = $banner_data['url'] = $this->input->post('url');
            $form_data['display_order'] = $banner_data['display_order'] = $this->input->post('display_order');
            $form_data['status'] = $banner_data['status'] = $this->input->post('status');

            if(!$banner_id){
                $form_data['created_at'] = $banner_data['created_at'] = date('Y-m-d H:i:s');
            }

            if(!$banner_id && $large_image['error'] == 4)
                $this->form_validation->set_rules('large_image', 'Image', 'required');


            if(empty($errors) && $this->form_validation->run() == TRUE){

                if($banner_id && $thumb_image['error'] != 4){
                    @unlink(FCPATH . $editing_banner_data->thumb_image);
                }
                if($banner_id && $large_image['error'] != 4){
                    @unlink(FCPATH . $editing_banner_data->large_image);
                }

                if($large_image['error'] != 4){
                    $rand_string = $this->validation->generateRandomAlphaNumericString(10);
                    $enc_rand_string = substr(md5(microtime().$rand_string), 1, 16);
                    $large_image_dyn_name = $enc_rand_string.'_'.$this->validation->sanitize_file_name($large_image['name']);
                    $large_image_path = 'uploads/banners/'.$large_image_dyn_name;
                    $large_image_full_path = FCPATH . $large_image_path;
                    move_uploaded_file($large_image['tmp_name'],$large_image_full_path);
                    $banner_data['large_image'] = $large_image_path;
                }


                if($banner_id){
                    $banner_id = $this->banner_model->update_banner($banner_data,$banner_id);
                }else{
                    $banner_id = $this->banner_model->add_banner($banner_data);
                }

                $audit_log['user_id'] = $user->id;
                $audit_log['user_email']= $user->email;
                $audit_log['item_id'] = $banner_id;
                $audit_log['activity_time']= date('Y-m-d H:i:s');
                $audit_log['activity_result']='successful';
                $audit_log['ip_address'] = $this->validation->get_client_ip();

                if($banner_id){
                    $audit_log['activity']='Edit Banner';
                }else{
                    $audit_log['activity']='Add Banner';
                }

                $this->log_model->add_log($audit_log);

                if($banner_id){
                    redirect('/admin/banners/index?msg=updated');
                }else{
                    redirect('/admin/banners/index?msg=added');
                }

            }else{
                $errors = $this->form_validation->error_array();
            }

        }

        $data['title'] = 'Add';

        $data['form_data'] = $form_data;

        $data['errors'] = $errors;

        $this->body = 'banners/add';
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
            redirect('/admin/dashboard/index?msg=auth_error');
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
            redirect('/admin/banners/index?msg=security_mismatch');
            exit;
        }else{

            $banner_id =$this->input->get('banner_id');

            if(!$this->validation->isInteger($banner_id)){
                redirect('/admin/banners/index?msg=invalid_banner');
                exit;
            }


            if(empty($errors))
            {
                $banner = $this->banner_model->get_banner($banner_id);

                if(empty($banner)){
                    redirect('/admin/banners/index?msg=banner_not_exists');
                    exit;
                }
                else
                {

                    @unlink(FCPATH.$banner->thumb_image);
                    @unlink(FCPATH.$banner->large_image);

                    $this->banner_model->delete_banner($banner->id);

                    $audit_log['user_id'] = $user->id;
                    $audit_log['user_email'] = $user->email;
                    $audit_log['activity'] = 'Delete Banner';
                    $audit_log['item_id'] = $banner->id;
                    $audit_log['activity_time'] = date('Y-m-d H:i:s');
                    $audit_log['activity_result'] = 'success';
                    $audit_log['ip_address'] = $this->validation->get_client_ip();
                    $this->log_model->add_log($audit_log);

                    # update csrf salt in session
                    $csrf_salt = md5(uniqid(mt_rand()));
                    $this->session->csrf_salt = $csrf_salt;

                    redirect('/admin/banners/index?msg=deleted');

                }
            }
        }
    }

}
