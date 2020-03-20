<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->library('front');
        $this->load->model('event_model');
        $this->load->library('pagination');
    }

    public function index()
    {

        $lang = $this->front->get_lang();

        if(!isset($_GET['s'])){
            redirect('/?lang='.$lang);
            exit;
        }

        $search_string = htmlentities($_GET['s']);

        if($lang == 'hi') $data['title'] = $search_string.' के लिए परिणाम दिखा रहा है';
        else $data['title'] = 'Showing Results For: '.$search_string;


        $data['lang'] = $lang;



        $data['events'] = $this->event_model->get_events($start = 0, $limit = 0, $sort_field = 'event.created_at', $sort_order = 'desc', array('search_kw' => $search_string ));
        $data['search_string'] = $search_string;

        $this->data = $data;
        $this->body = 'search/index';
        $this->layout();

    }

}
