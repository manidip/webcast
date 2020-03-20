<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends MY_Controller {

	public function __construct(){

        parent::__construct();

        $this->load->library('front');
        $this->load->library('encryption');
        $this->load->model('category_model');
        $this->load->model('event_model');
        $this->load->model('event_session_model');

        $this->load->library('pagination');

	}

	public function index($event_id = NULL, $session_id = NULL)
	{

	    if($event_id == 0){
            redirect('/');
            exit;
        }

        if(!is_null($session_id) && $session_id == 0){
            redirect('/events/'.$event_id);
            exit;
        }

        $lang = $this->front->get_lang();
        $data['event'] = $this->event_model->get_event($event_id);



        if(empty($data['event'])){
            redirect('/');
            exit;
        }
        if($lang == 'hi') $data['title'] = (!empty($data['event']->title_hi)) ? $data['event']->title_hi : $data['event']->title_en;
        else $data['title'] = $data['event']->title_en;


        $data['lang'] = $lang;

        $active_sessions = $this->event_model->get_sessions($event_id,'event_sessions.start_time','asc',array('status' => 'active'));
	    $org_active_session = (count($active_sessions) > 0 ) ? $active_sessions[0] : 0;
        $now_active_session = (count($active_sessions) > 0 && is_null($session_id)) ? array_shift($active_sessions) : $this->event_session_model->get_event_session($session_id) ;

        if(!is_null($session_id) && $session_id == $org_active_session->id ) {

            $active_sessions = array_filter($active_sessions, function ($active_session) use($org_active_session){
                return ($active_session->id !== $org_active_session->id);
            }) ;
        }


        $data['active_sessions'] = $active_sessions ;
        $data['now_active_session'] = $now_active_session ;
        $data['upcoming_sessions'] = $this->event_model->get_sessions($event_id,'event_sessions.start_time','asc',array('status' => 'upcoming'));
        $data['vod_sessions'] = $this->event_model->get_sessions($event_id,'event_sessions.start_time','desc', array('status' => 'vod'));

        $data['vod_sessions'] = array_filter($data['vod_sessions'], function ($vod_session) use($session_id){
            return ($vod_session->id !== $session_id);
        }) ;

        $this->data = $data;

        if($lang=='hi') {
            $this->body = 'events/hi/index';
        } else {
            $this->body = 'events/index';
        }
        $this->layout();

	}
}
