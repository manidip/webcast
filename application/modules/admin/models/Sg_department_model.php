<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sg_department_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
 
	public function add_department($dataArr)
	{		
		$this->db->insert('sg_department',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_department($dataArr, $department_id)
	{		
		$this->db->where('department_id', $department_id);
		$this->db->update('sg_department', $dataArr);
	
	}
	
	
	public function delete_department($department_id=0)
	{	
		
		$this->db->delete('sg_department', array('department_id' => $department_id)); 
	}
	
	

	public function get_department($department_id)
	{
		
		$this->db->select('sg_department.*')
				 ->from('sg_department');
				 
		$this->db->where('sg_department.department_id', $department_id);
			
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
	
	
	
	
	
	public function get_departments($start=0, $limit=0, $sort_field='department_name', $sort_order='asc', $optArr=array())
	{
		
		$this->db->select('sg_department.*')
				 ->from('sg_department');
				 
				 
		if(!empty($optArr['state_id']))
		{
			$this->db->where('sg_department.state_id', $optArr['state_id']);
		}
	 
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('sg_department.department_name', $optArr['search_kw'], 'both') // before, after, both (default)
			         ->group_end();	
		}
		
	
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
			$rows = $query->result_array();
			return $rows;
		}
		else
		{
			return array();
		}
	}
	
}
?>