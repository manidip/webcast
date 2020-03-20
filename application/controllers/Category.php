<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->library('front');
        $this->load->model('category_model');
        $this->load->model('event_model');

        $this->load->library('pagination');
    }

    public function index($category_id = NULL)
    {

        if($category_id == 0){
            redirect('/');
            exit;
        }

        $lang = $this->front->get_lang();


        $data['category'] = $this->category_model->get_category($category_id);

        if($lang == 'hi') $data['title'] = (!empty($data['category']->title_hi)) ? $data['category']->title_hi : $data['category']->title;
        else $data['title'] = $data['category']->title_en;


        $data['lang'] = $lang;

        $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 3;
        $pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "category/".$category_id;
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
        $limit = $ipp;



        $all_events = $this->event_model->get_events(0,0,'event.created_at', 'desc', $options = array('category' => $category_id ));
        $data['events'] = $this->event_model->get_events($start, $limit, 'event.created_at', 'desc', $options = array('category' => $category_id ));


        $pagination_config['total_rows'] = count($all_events);

        $this->pagination->initialize($pagination_config);


        $data['pagination_links'] = $this->pagination->create_links();


        $this->data = $data;

        if($lang == 'hi') {
            $this->body = 'category/hi/index';
        } else {
            $this->body = 'category/index';
        }

        $this->layout();

    }

}
