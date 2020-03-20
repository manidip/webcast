<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization extends MY_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->library('front');
        $this->load->library('encryption');
        $this->load->model('event_model');
        $this->load->model('ug_organization_model');
        $this->load->library('pagination');
	}

	public function index($orgn_id = NULL)
	{

	    if($orgn_id == 0){
            redirect('/');
            exit;
        }

        $lang = $this->front->get_lang();
        $data['organization'] = $this->ug_organization_model->get_organization($orgn_id);


        if(empty($data['organization'])){
            redirect('/');
            exit;
        }

        $data['title'] = $data['organization']->orgn_name;

        $data['lang'] = $lang;


        $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 3;
        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "organization/".$orgn_id;
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;

        $all_orgn = $this->event_model->get_events(0,0,'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $pagination_config['total_rows'] = count($all_orgn);

        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();


        $data['events'] = $this->event_model->get_events($start, $limit, 'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'organization/hi/index';
        } else {
            $this->body = 'organization/index';
        }
        $this->layout();

    }
    public function index_by_name($orgn_name = NULL)
	{

        $lang = $this->front->get_lang();
        $data['organization'] = $this->ug_organization_model->get_organization($orgn_name,'alias');


        if(empty($data['organization'])){
            redirect('/');
            exit;
        }

        $orgn_id = $data['organization']->orgn_id;

        $data['title'] = $data['organization']->orgn_name;

        $data['lang'] = $lang;


        $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 3;
        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "organization/".$orgn_id;
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;

        $all_orgn = $this->event_model->get_events(0,0,'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $pagination_config['total_rows'] = count($all_orgn);

        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();


        $data['events'] = $this->event_model->get_events($start, $limit, 'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'organization/hi/index';
        } else {
            $this->body = 'organization/index';
        }
        $this->layout();

    }
    
    public function index_by_alias()
	{

        $alias = $this->uri->segment(1);

        $lang = $this->front->get_lang();
        $data['organization'] = $this->ug_organization_model->get_organization( $alias,'alias');

        if(empty($data['organization'])){
            redirect('/');
            exit;
        }

        $orgn_id = $data['organization']->orgn_id;
        $data['title'] = $data['organization']->orgn_name;

        $data['lang'] = $lang;


        $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 3;
        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "organization/".$orgn_id;
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;

        $all_orgn = $this->event_model->get_events(0,0,'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $pagination_config['total_rows'] = count($all_orgn);

        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();


        $data['events'] = $this->event_model->get_events($start, $limit, 'event.created_at', 'desc', $options = array('organization' => $orgn_id ));

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'organization/hi/index';
        } else {
            $this->body = 'organization/index';
        }
        $this->layout();

	}
}
