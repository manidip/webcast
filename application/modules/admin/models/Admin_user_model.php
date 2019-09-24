<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_user_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	// http://www.codeigniter.com/user_guide/database/queries.html
	// http://www.codeigniter.com/user_guide/database/examples.html
	// http://www.codeigniter.com/user_guide/database/query_builder.html
	// http://www.codeigniter.com/user_guide/database/results.html
	// $error = $this->db->error(); 
	 /*
	 function login($email,$password)
	 {
	  $this->db->where("email",$email);
	  $this->db->where("password",$password);
	
	  $query=$this->db->get('admin_user');
	  if($query->num_rows()>0)
	  {
	   foreach($query->result() as $rows)
	   {
		//add all data to session
		$newdata = array(
		  'id'  => $rows->id,
		  'name'  => $rows->username,
		  'email'    => $rows->email,
		  'logged_in'  => TRUE,
		);
	   }
	   $this->session->set_userdata($newdata);
	   return true;
	  }
	  return false;
	 }*/
 
	public function add_user($dataArr)
	{		
		$this->db->insert('admin_user',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_user($dataArr, $id)
	{		
		$this->db->where('id', $id);
		$this->db->update('admin_user', $dataArr);
	
	}
	
	
	public function get_user_count($opt=array(), $id=0)
	{	
	
		$this->db->select('count(admin_user.id) as user_count')
				 ->from('admin_user');
				 
				 
		if(!empty($opt['email']))
		{
			$this->db->where("admin_user.email",$opt['email']);
		}
		
		
				
		if(!empty($id))
		{
			$this->db->where("admin_user.id !=",$id);
		}
		
		/////print sql query before execution//////////
		//echo $this->db->get_compiled_select();  die;
		//die;
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); die;
		//////////////////////////////
		
		$row = $query->row_array();
		
		//print_r($row); die;

		return $row['user_count'];
	}
	
	public function key_exists($key)
	{	
		
		$this->db->where("activation_key",$key);
		$query=$this->db->get('admin_user');
		
		if($query->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function email_exists($email)
	{	
		
		$this->db->where("email",$email);
		$query=$this->db->get('admin_user');
		
		if($query->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get_user_by_key($key)
	{		
		
		$this->db->where("activation_key",$key);
		$query=$this->db->get('admin_user');

		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	

	
	public function get_user($id)
	{
		
		
		$this->db->select('admin_user.*, state.state_name')
				 ->from('admin_user');
				 
		$this->db->join('state', 'admin_user.state = state.state_id', 'left');

	
		$this->db->where('admin_user.id', $id);
			

		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select();
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	
	public function get_user_by_email($email)
	{	
		
		$this->db->where("email",$email);
		$query=$this->db->get('admin_user');
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	
	
	
	public function get_active_user($id)
	{		
		
		$this->db->where("id", $id);
		$this->db->where("active", 1);
		$query=$this->db->get('admin_user');
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	
	
	public function get_active_user_by_email($email)
	{		
		
		$this->db->where("email", $email);
		$this->db->where("active", 1);
		$query=$this->db->get('admin_user');
			
		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	
		
	public function get_prev_passwords($id, $limit=1)
	{
		
		$this->db->from('admin_user_password'); // insted of get mention table name here as get runs the query
		$this->db->where('user_id', $id);
		
		$this->db->order_by('password_date', 'DESC');
		
		$this->db->limit($limit);
		
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select();
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		if($query->num_rows()>0)
		{
			$rows = $query->result();
			
			//print_r($rows);
			//die;
			
			return $rows;
		}
		else
		{
			return array();
		}
	}
	
	
	public function add_pass_history($dataArr)
	{		
		$this->db->insert('admin_user_password',$dataArr);
		return  $this->db->insert_id();
	}
	
	public function fp_key_exists($fp_key)
	{	
		
		$this->db->where('fp_key',$fp_key);
		$query=$this->db->get('admin_user_reset_password');
		
		if($query->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public function fp_uid_exists($user_id)
	{	
		
		$this->db->where('user_id',$user_id);
		$query=$this->db->get('admin_user_reset_password');
		
		if($query->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function add_fp_key($dataArr)
	{		
		$this->db->insert('admin_user_reset_password',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function update_fp_key($dataArr, $user_id)
	{		
		$this->db->where('user_id', $user_id);
		$this->db->update('admin_user_reset_password',$dataArr);

	}
	
	
	public function get_fp_details($fp_key)
	{	
		
		$this->db->where('fp_key',$fp_key);
		$query=$this->db->get('admin_user_reset_password');
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			
			return $row;
		}
		else
		{
			return array();
		}
	}
	
	
	public function delete_fp_key($fp_key)
	{		
		$this->db->where('fp_key', $fp_key);
		$this->db->delete('admin_user_reset_password');

	}
	
	
	
	public function get_users($start=0, $limit=0, $sort_field='admin_user.created', $sort_order='desc', $optArr=array())
	{
		
		
		$this->db->select('admin_user.*, state.state_name, author.fname as author_fname, author.lname as author_lname')
				 ->from('admin_user');
				 
		$this->db->join('state', 'admin_user.state = state.state_id', 'left');
		
		$this->db->join('admin_user as author', 'author.id = admin_user.author', 'left');
				 
		
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('admin_user.fname', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('admin_user.lname', $optArr['search_kw'], 'both')
					 ->or_like('admin_user.email', $optArr['search_kw'], 'both')
					 ->or_like('admin_user.mobile', $optArr['search_kw'], 'both')
			         ->group_end();	
		}
		
			
		if($optArr['active']!='')
			$this->db->where('admin_user.active', $optArr['active']);
		
		
		
		
		$this->db->order_by($sort_field, $sort_order);
		
		if(!empty($limit))
			$this->db->limit($limit, $start);
		
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select();
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		if($query->num_rows()>0)
		{
			$rows = $query->result();
			
			//print_r($rows);
			//die;
			
			return $rows;
		}
		else
		{
			return array();
		}
	}
	
	public function user_email_count($email, $id=0)
	{		

			
		$this->db->where('email',$email);
	
		if(!empty($id)) // in case of edit
		{
			$this->db->where("id !=",$id);
		}
		
		
		$query=$this->db->get('admin_user');
		
		
		//echo $this->db->last_query();  die;
		
		//echo "<br/>";
		
		//echo $query->num_rows();
		
		return $query->num_rows();
	}
	
	
		
	public function delete_user($id=0)
	{
		$this->db->delete('admin_user', array('id' => $id)); 
	}
	
}
?>