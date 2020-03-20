<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends MY_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->library('front');

	}

	public function index()
	{

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'services/hi/index';
        } else {
            $this->body = 'services/index';
        }
        $this->layout();

	}

    public function our_services()
    {

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'services/our_services/hi/index';
        } else {
            $this->body = 'services/our_services/index';
        }
        $this->layout();

    }

    public function avail_services()
    {

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'services/avail_services/hi/index';
        } else {
            $this->body = 'services/avail_services/index';
        }
        $this->layout();

    }

}
