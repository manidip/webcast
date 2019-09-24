<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
 
	public function add_log($dataArr)
	{		
		$this->db->insert('admin_log',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_log($dataArr, $log_id)
	{		
		$this->db->where('log_id', $log_id);
		$this->db->update('admin_log', $dataArr);
	
	}
	
	
	public function get_log($log_id)
	{	
	
		$this->db->select('admin_log.*, admin_user.fname, admin_user.lname, admin_user.email')
				 ->from('admin_log');
				 
		
		$this->db->join('admin_user', 'admin_log.user_id = admin_user.id', 'left');
		
		if(!empty($log_id))
			$this->db->where('admin_log.log_id', $log_id);
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select(); die;
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
	
	
	
	public function get_log_count($user_id='')
	{

		$this->db->select('admin_log.*, admin_user.fname, admin_user.lname, admin_user.email')
				 ->from('admin_log');
				 
		
		$this->db->join('admin_user', 'admin_log.user_id = admin_user.id', 'left');
		
		if(!empty($user_id))
			$this->db->where('admin_log.user_id', $user_id);
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select(); die;
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		return $query->num_rows();
	}
	
		
	public function get_logs($start=0, $limit=0, $sort_field='admin_log.activity_time', $sort_order='desc', $optArr=array())
	{
		
		
		$this->db->select('admin_log.*, admin_user.fname, admin_user.lname, admin_user.email')
				 ->from('admin_log');
				 
		
		$this->db->join('admin_user', 'admin_log.user_id = admin_user.id', 'left');
		
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('admin_log.activity', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('admin_user.fname', $optArr['search_kw'], 'both')
					 ->or_like('admin_user.lname', $optArr['search_kw'], 'both')
					 ->or_like('admin_user.email', $optArr['search_kw'], 'both')
			         ->group_end();	
		}


		if(!empty($optArr['user_id']))
			$this->db->where('admin_log.user_id', $optArr['user_id']);
			
			
		$this->db->order_by($sort_field, $sort_order);
		
		
		if(!empty($limit))
			$this->db->limit($limit, $start);
		
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select(); die;
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		if($query->num_rows()>0)
		{
			$rows = $query->result();
			return $rows;
		}
		else
		{
			return array();
		}
	}
	
}
?>