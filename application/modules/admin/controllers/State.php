<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State extends MX_Controller {

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
		
            $this->load->model('stateind_model');
			$this->load->model('log_model');	
			
			
			$this->load->library('my_form_validation');
			$this->load->library('pagination');
			$this->load->library('admin');
			
			$this->admin->check_sess_timeout();
	}

	public function index()
	{

		$uid = $this->session->admin_session->id;

		if(empty($uid)){
			redirect('/admin/home/index');
			exit;
		}


		$data_posted = false;
		$existing_alias = array();

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			$data_posted = true;
			
			$aliases_to_update = (isset($_POST['alias'])) ? $_POST['alias'] : array();
		
			foreach ($aliases_to_update as $id => $alias) {

				if(!$this->my_form_validation->is_valid_text($alias)) continue;


				if(empty($alias)){
					$this->stateind_model->edit_state(array('alias' => $alias), $id);
					$this->save_routes();
				}else {
					if($alias == trim($alias) && strpos($alias, ' ') !== false){
						$existing_alias[$id] = 'No space allowed';
					}elseif(!preg_match('/^[a-zA-Z0-9]+$/',$alias)){
						$existing_alias[$id] = 'Only alphanumeric charecters allowed';
					}elseif($this->stateind_model->alias_exists($alias,$id)){
						$existing_alias[$id] = 'Alias already exists.';
					}else{
						$this->stateind_model->edit_state(array('alias' => str_replace('"','',$alias)), $id);
						$this->save_routes();
					}
				}
					
			}
            if(!empty($existing_alias)){
                $data['error_message'] = "Please fix below issues.";
            }else{
                $data['success_message'] = "Alias Updated.";
            }

		}

		$user = $this->admin_user_model->get_user($uid);
	
		$all_states = $this->stateind_model->get_states(0, 0, 'state_name', 'asc' );
		
		$data['state_data']['states'] = $all_states;
		$data['state_data']['total_items'] = count($all_states);

		$data['data_posted'] = $data_posted;
		$data['existing_alias'] = $existing_alias;

		$this->body = 'state/index';
		$this->data = $data;
		$this->layout('inner');

		
	}

    public function save_routes() {

        $routes = $this->get_all_routes();

        $data = array();

        if (!empty($routes ) && is_array($routes)) {

            $data[] = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';

            foreach ($routes as $route) {
                $data[] = '$route[\'' . $route['uri'] . '\'] = \'' . $route['controller'] . '/' . $route['action'] . '\';';
            }
            $output = implode("\n", $data);

            write_file(APPPATH . 'cache/state.routes.php', $output);
        }


    }

    public function get_all_routes(){

        $states = $this->stateind_model->get_states();


        $routes = array_map(function($state){

            $uri = ( $state->alias ) ? $state->alias : $state->state_code;
            $data['uri'] = strtolower($uri).'/([a-z]+)';
            $data['controller'] = 'state_department';
            $data['action'] = 'index_by_alias/$1';
            return $data;

        },$states);

        return $routes;
    }

	public function update_alias(){

		$uid = $this->session->admin_session->id;
		$response = array();

		if(empty($uid)){
			redirect('/admin/home/index');
			exit;
		}

		$user = $this->admin_user_model->get_user($uid);

		$state_id = (isset($_GET['state_id']) && !empty($_GET['state_id'])) ? $_GET['state_id'] : '';

		if(empty($state_id)){
			echo json_encode(array('error' => 'Invalid state ID'));
			die();
		}

		$alias = (isset($_GET['alias']) && !empty($_GET['alias'])) ? trim(strtolower($_GET['alias'])) : '';

		if(!$this->my_form_validation->is_valid_text($alias) || strip_tags($alias) !== $alias) {
			echo json_encode(array('error' => 'Invalid Alias.'));
			die();
		}

		if(strpos($alias, ' ') > 0){
			echo json_encode(array('error' => 'No Space Allowed.'));
			die();
		}

		if($this->stateind_model->alias_exists($alias,$state_id)){
			echo json_encode(array('error' => 'Alias Already Exists'));
			die();
		}

		$this->stateind_model->edit_state(array('alias' => $alias), $state_id);
		$this->save_routes();

		echo json_encode(array('success' => 'Alias Updated.'));
		die();

	}

}
