<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MX_Controller {

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
        $ipp = 50;
        $uid = $this->session->admin_session->id;
        $options = array();

        if(empty($uid)){
            redirect('/admin/home/index');
            exit;
        }

        $data['title'] = 'List';

        $logs = $this->log_model->get_logs();
        $data['total_items'] = count($logs);

        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/logs/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;

        $data['logs'] = $this->log_model->get_logs($start, $limit);

        $pagination_config['total_rows'] = count($logs);
        $this->pagination->initialize($pagination_config);

        $data['pagination_links'] = $this->pagination->create_links();

        $this->body = 'logs/index';

        $this->data = $data;

        $this->layout('inner');

    }


}
