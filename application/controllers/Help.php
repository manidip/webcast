<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends MY_Controller {

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
            $this->body = 'help/hi/index';
        } else {
            $this->body = 'help/index';
        }
        $this->layout();

	}

    public function system_requirements()
    {

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'help/system_requirements/hi/index';
        } else {
            $this->body = 'help/system_requirements/index';
        }
        $this->layout();

    }

    public function downloads()
    {

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'help/downloads/hi/index';
        } else {
            $this->body = 'help/downloads/index';
        }
        $this->layout();

    }

    public function troubleshooting()
    {

        $lang = $this->front->get_lang();

        $data['lang'] = $lang;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'help/troubleshooting/hi/index';
        } else {
            $this->body = 'help/troubleshooting/index';
        }
        $this->layout();

    }

}
