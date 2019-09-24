<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
 
	public function add_event($dataArr)
	{		
		$this->db->insert('event',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_event($dataArr, $id)
	{		
		$this->db->where('id', $id);
		$this->db->update('event', $dataArr);
	
	}
	
	
	public function delete_event($id=0)
	{	
		$this->db->delete('event', array('id' => $id)); 
	}
	
	

	public function get_event($id)
	{
		
		$this->db->select('event.*')
				 ->from('event');
				 
		$this->db->where('event.id', $id);
			
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
	
	
	
		
	public function get_events($start=0, $limit=0, $sort_field='event.created_at', $sort_order='desc', $optArr=array())
	{
		
		
		$this->db->select('event.*')
				 ->from('event');
				 
		$this->db->join('admin_user', 'event.author = admin_user.id', 'left');
		
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('event.title', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('event.title_hi', $optArr['search_kw'], 'both')
			         ->group_end();	
		}
		
		/*
		if(isset($optArr['active']))
		{
			$this->db->where("event.active", $optArr['active']);
		}
		
		if(isset($optArr['parent']))
		{
			$this->db->where("event.parent", $optArr['parent']);
		}
	*/

			
	
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
			
			//print_r($rows);
			//die;
			
			return $rows;
		}
		else
		{
			return array();
		}
	}
	
	
}
?>