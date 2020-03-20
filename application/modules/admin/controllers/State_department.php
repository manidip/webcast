<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State_Department extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
		
            $this->load->model('sg_department_model');
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
					$this->sg_department_model->edit_department(array('alias' => $alias), $id);
				}else {
					if($alias == trim($alias) && strpos($alias, ' ') !== false){
						$existing_alias[$id] = 'No space allowed';
					}elseif(!preg_match('/^[a-zA-Z0-9]+$/',$alias)){
						$existing_alias[$id] = 'Only alphanumeric charecters allowed';
					}elseif($this->sg_department_model->alias_exists($alias,$id)){
						$existing_alias[$id] = 'Alias already exists.';
					}else{
						$this->sg_department_model->edit_department(array('alias' => str_replace('"','',$alias)), $id);
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
		$options = array();

		$search_kw = ($this->input->get('search_kw')) ? $this->input->get('search_kw') : '';
		$data['ipp'] = $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 10;

		if(!empty($search_kw)){
            $search_kw = $this->validation->xssSafe(trim($search_kw));
            $options['search_kw'] = $data['search_kw'] = $search_kw;
		}
		
	
		$all_departments = $this->sg_department_model->get_departments(0, 0, 'department_name', 'asc', $options );


		$pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/state_department/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
		$limit = $ipp;

		$data['state_department_data']['departments'] = $this->sg_department_model->get_departments($start, $limit, 'department_name', 'asc', $options );

		$pagination_config['total_rows'] = $data['state_department_data']['total_items'] = count($all_departments);
        $this->pagination->initialize($pagination_config);


		$data['pagination_links'] = $this->pagination->create_links();
		$data['data_posted'] = $data_posted;
		$data['existing_alias'] = $existing_alias;

		$this->body = 'state_department/index';
		$this->data = $data;
		$this->layout('inner');

		
	}

	public function update_alias(){

		$uid = $this->session->admin_session->id;
		$response = array();

		if(empty($uid)){
			redirect('/admin/home/index');
			exit;
		}

		$user = $this->admin_user_model->get_user($uid);

		$department_id = (isset($_GET['department_id']) && !empty($_GET['department_id'])) ? $_GET['department_id'] : '';

		if(empty($department_id)){
			echo json_encode(array('error' => 'Invalid department ID'));
			die();
		}

		$alias = (isset($_GET['alias']) && !empty($_GET['alias'])) ? trim(strtolower($_GET['alias'])) : '';

		if(!empty($alias)){

            if(!$this->my_form_validation->is_valid_text($alias) || strip_tags($alias) !== $alias) {
                echo json_encode(array('error' => 'Invalid Alias.'));
                die();
            }

            if(strpos($alias, ' ') > 0){
                echo json_encode(array('error' => 'No Space Allowed.'));
                die();
            }

            if($this->sg_department_model->alias_exists($alias,$department_id)){
                echo json_encode(array('error' => 'Alias Already Exists'));
                die();
            }
        }


		$this->sg_department_model->edit_department(array('alias' => $alias), $department_id);

		echo json_encode(array('success' => 'Alias Updated.'));
		die();

	}

}
