<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	
	
	public function __construct()
	{
			parent::__construct();
			
			$this->load->library('front');
            $this->load->model('category_model');
            $this->load->model('event_model');
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
     * config/department.routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html

    */
	 
	 
	 public function index()
        {

            $lang = $this->front->get_lang();
            $catwise_events = array();

            if($lang=='hi') $data['title'] ='मुख्य पृष्ठ';
            else $data['title'] = 'Home';

            if($lang=='hi') $this->body = 'home/hi/index';
            else $this->body = 'home/index';

            $data['lang'] = $lang;

            $categories = $this->category_model->get_categories(0, 0, 'created_at', 'desc');

            foreach ($categories as $category){
                $ecat = array();
                $ecat['category'] = $category;
                $ecat['events'] = $this->event_model->get_events(0,10,'event.created_at','desc',array('category' => $category->id));
                $catwise_events[] = $ecat;
            }

            $data['recent_events'] = $this->event_model->get_events(0,5,'event.start_date','desc',array('status' => 'recent'));
            $data['upcoming_events'] = $this->event_model->get_events(0,5,'event.start_date','asc',array('status' => 'upcoming'));
            $data['ongoing_events'] = $this->event_model->get_events(0,0,'event.start_date','asc',array('status' => 'ongoing'));
            $data['categories_events'] = $catwise_events;



            $this->data = $data;
            $this->layout();
        }
}
