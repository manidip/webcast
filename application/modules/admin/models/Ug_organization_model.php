<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ug_organization_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
 
	public function add_organization($dataArr)
	{		
		$this->db->insert('ug_organization',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_organization($dataArr, $orgn_id)
	{		
		$this->db->where('orgn_id', $orgn_id);
		$this->db->update('ug_organization', $dataArr);
	
	}
	
	
	public function delete_organization($orgn_id=0)
	{	
		
		$this->db->delete('ug_organization', array('orgn_id' => $orgn_id)); 
	}
	
	

	public function get_organization($orgn_id)
	{
		
		$this->db->select('ug_organization.*')
				 ->from('ug_organization');
				 
		$this->db->where('ug_organization.orgn_id', $orgn_id);
			
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
	
	
		
	public function get_organizations($start=0, $limit=0, $sort_field='orgn_name', $sort_order='asc', $optArr=array())
	{
		
		$this->db->select('ug_organization.*')
				 ->from('ug_organization');
				 
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('ug_organization.orgn_name', $optArr['search_kw'], 'both') // before, after, both (default)
			         ->group_end();	
		}
		
	
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
	
	
	public function get_organization_list($limit=0, $sort_field='orgn_name', $sort_order='asc', $optArr=array())
	{
	
		$this->db->select('orgn_name')
				 ->from('ug_organization');
		
		$this->db->group_start()
					 ->like('orgn_name', $optArr['search_kw'], 'after') // before, after, both (default)
					 ->or_like('orgn_name', $optArr['search_kw'], 'both') // before, after, both (default)
			         ->group_end();	

		$this->db->order_by($sort_field, $sort_order);
		
		
		if(!empty($limit))
			$this->db->limit($limit);
		
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select(); die;
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); 
		//////////////////////////////
		
		if($query->num_rows()>0)
		{
			$rows = $query->result(); // returns object
		}
		else
		{
			$rows=array();
		}
		
		$valArr=array();
		
		foreach($rows as $row){
		
			$valArr[]=$row->title;
		}
		
		return $valArr;
	}
	
	
	public function get_ministries($start=0, $limit=0, $sort_field='orgn_name', $sort_order='asc', $optArr=array())
	{
		
		$this->db->select('ug_organization.*')
				 ->from('ug_organization');
				 
		$this->db->where('ug_organization.hide', 0);
				 
		$this->db->group_start()
				 ->where('ug_organization.ministry_id', 0) // apex bodies
				 ->or_where('ug_organization.ministry_id > ', 0)  // ministries
				 ->group_end();	
				 
		$this->db->where('ug_organization.dept_id', 0);
		$this->db->where('ug_organization.organ_id', 0);
				 
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('ug_organization.orgn_name', $optArr['search_kw'], 'both') // before, after, both (default)
			         ->group_end();	
		}
		
	
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
	
	
	public function get_ug_data($type='', $start=0, $limit=0, $sort_field='orgn_name', $sort_order='asc', $optArr=array())
	{
		
		$this->db->select('ug_organization.*')
				 ->from('ug_organization');
				 
		$this->db->where('ug_organization.hide', 0);
		
		
			
			
		if($type=='min_dept')
		{
			if(!empty($optArr['ministry_id']))
				$this->db->where('ug_organization.ministry_id', $optArr['ministry_id']);
			
			$this->db->where('ug_organization.dept_id >', 0);
		
			$this->db->where('ug_organization.organ_id', 0);
		}
		else if($type=='min_org')
		{
			if(!empty($optArr['ministry_id']))
				$this->db->where('ug_organization.ministry_id', $optArr['ministry_id']);
				
			$this->db->where('ug_organization.dept_id', 0);
		
			$this->db->where('ug_organization.organ_id >', 0);
		}
		else if($type=='dept_org')
		{
			if(!empty($optArr['ministry_id']))
				$this->db->where('ug_organization.ministry_id', $optArr['ministry_id']);
				
			if(!empty($optArr['dept_id']))
				$this->db->where('ug_organization.dept_id', $optArr['dept_id']);
			
			$this->db->where('ug_organization.organ_id >', 0);
		}
				 
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('ug_organization.orgn_name', $optArr['search_kw'], 'both') // before, after, both (default)
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