<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coordinator_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function add_coordinator($dataArr)
	{		
		$this->db->insert('coordinator',$dataArr);
		return  $this->db->insert_id();
	}

	public function edit_coordinator($dataArr, $id)
	{		
		if(!empty($id))
		{
			$this->db->where('id', $id);
		}
		
		$this->db->update('coordinator', $dataArr);
	
	}

	public function delete_coordinator($id=0)
	{	
		
		$this->db->delete('coordinator', array('id' => $id)); 
	}

	public function get_coordinator($id)
	{
		
		
		$this->db->select('coordinator.*,state.state_name as address_state')
				 ->from('coordinator');
				 
		$this->db->join('state', 'coordinator.state = state.state_id', 'left');
		
	
				 
		$this->db->where('coordinator.id', $id);
			
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

	public function get_coordinators($start=0, $limit=0, $sort_field='created_at', $sort_order='desc', $optArr=array())
	{


		$this->db->select('coordinator.*, 
						  state.state_name as address_state,
						  admin_user.fname as author_fname,
						  admin_user.lname as author_lname,
						  ')
				 ->from('coordinator');
				 
		
		
		$this->db->join('state', 'coordinator.state = state.state_id', 'left');
		
		$this->db->join('admin_user', 'coordinator.author = admin_user.id', 'left');
		
				 
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('coordinator.name', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('coordinator.email', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('coordinator.mobile', $optArr['search_kw'], 'both') // before, after, both (default)
			         ->group_end();	
		}

        if(!empty($optArr['status']))
        {
            $status = ($optArr['status'] == 1) ? 1 : 0;
            $this->db->where('coordinator.active', $status);
        }
		
		
		if(!empty($optArr['author']))
		{
			$this->db->where('coordinator.author', $optArr['author']); 
		}

	
		$this->db->order_by($sort_field, $sort_order);
		
		
		if(!empty($limit))
			$this->db->limit($limit, $start);

		$query=$this->db->get();

		//echo $this->db->last_query();
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

	public function get_coordinator_count($opt=array(), $id=0)
	{	
	
	
		$this->db->select('coordinator.id') ->from('coordinator');
				 

		if(!empty($opt['email']))
		{
			$this->db->where("coordinator.email",$opt['email']);
		}
		

		if(!empty($id))
		{
			$this->db->where("coordinator.id !=",$id);
		}


		$query=$this->db->get(); // runs query()
        return $query->num_rows();
	}
	
	


	
}
?>