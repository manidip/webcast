<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization extends MX_Controller { //class Home extends CI_Controller  class Home extends MY_Controller 

	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('admin_user_model');
		
            $this->load->model('ug_organization_model');
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
					$this->ug_organization_model->edit_organization(array('alias' => $alias), $id);
					$this->save_routes();
				}else {
					if($alias == trim($alias) && strpos($alias, ' ') !== false){
						$existing_alias[$id] = 'No space allowed';
					}elseif(!preg_match('/^[a-zA-Z0-9]+$/',$alias)){
						$existing_alias[$id] = 'Only alphanumeric charecters allowed';
					}elseif($this->ug_organization_model->alias_exists($alias,$id)){
						$existing_alias[$id] = 'Alias already exists.';
					}else{
						$this->ug_organization_model->edit_organization(array('alias' => str_replace('"','',$alias)), $id);
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
		$options = array();

		$search_kw = ($this->input->get('search_kw')) ? $this->input->get('search_kw') : '';
		$data['ipp'] = $ipp = ($this->input->get('ipp') && $this->validation->isInteger($this->input->get('ipp'))) ? $this->input->get('ipp') : 10;

		if(!empty($search_kw)){
            $search_kw = $this->validation->xssSafe(trim($search_kw));
            $options['search_kw'] = $data['search_kw'] = $search_kw;
		}
		
	
		$all_organizations = $this->ug_organization_model->get_organizations(0, 0, 'orgn_name', 'asc', $options );

		$pagination_config = $this->config->item('pagination_config');
        $pagination_config['base_url'] = base_url() . "admin/organization/index";
        $pagination_config['per_page'] = $ipp;

        $page = ($this->input->get($pagination_config['query_string_segment'])) ? $this->input->get($pagination_config['query_string_segment']) : 1;
        $page = ($this->validation->isInteger($page)) ? $page : 1;

        $start = ($page - 1) * $ipp;
		$limit = $ipp;
		
		$data['organization_data']['organizations'] = $this->ug_organization_model->get_organizations($start, $limit, 'orgn_name', 'asc', $options );

		$pagination_config['total_rows'] = $data['organization_data']['total_items'] = count($all_organizations);
        $this->pagination->initialize($pagination_config);


		$data['pagination_links'] = $this->pagination->create_links();
		$data['data_posted'] = $data_posted;
		$data['existing_alias'] = $existing_alias;

		$this->body = 'organization/index';
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

            write_file(APPPATH . 'cache/organization.routes.php', $output);
        }
	}

	public function get_all_routes(){

		$all_organizations = $this->ug_organization_model->get_organizations(0, 0, 'orgn_name', 'asc', array('only_url_alias' => true,'return') );
		
		$all_organizations = array_map(function($organization){
			
			$data['uri'] = $organization->alias;
			$data['controller'] = 'organization';
			$data['action'] = 'index_by_alias';
			return $data;

		},$all_organizations);

		return $all_organizations;
	}

	public function update_alias(){

		$uid = $this->session->admin_session->id;
		$response = array();

		if(empty($uid)){
			redirect('/admin/home/index');
			exit;
		}

		$user = $this->admin_user_model->get_user($uid);

		$orgn_id = (isset($_GET['orgn_id']) && !empty($_GET['orgn_id'])) ? $_GET['orgn_id'] : '';

		if(empty($orgn_id)){
			echo json_encode(array('error' => 'Invalid organization ID'));
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

            if($this->ug_organization_model->alias_exists($alias,$orgn_id)){
                echo json_encode(array('error' => 'Alias Already Exists'));
                die();
            }
        }

		$this->ug_organization_model->edit_organization(array('alias' => $alias), $orgn_id);
		$this->save_routes();

		echo json_encode(array('success' => 'Alias Updated.'));
		die();

	}

}
