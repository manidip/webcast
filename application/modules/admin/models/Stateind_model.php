<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stateind_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function add_state($dataArr)
	{		
		$this->db->insert('state',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_state($dataArr, $state_id)
	{		
		$this->db->where('state_id', $state_id);
		$this->db->update('state', $dataArr);
	
	}
	
	
	public function get_state($state_id)
	{		
		
		$this->db->where("state_id",$state_id);
		$query=$this->db->get("state");

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

	public function get_state_by_code($state_code)
	{	
		
		$this->db->where("state_code",$state_code);
		$query=$this->db->get("state");
		
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

    public function alias_exists($alias,$state_id)
    {

        $this->db->select('count(*) as count')->from('state');

        $this->db->where('state.alias', $alias);

        $this->db->where('state.state_id !=', $state_id);

        $query = $this->db->get();

        $result = array_shift($query->result_array());

        return ($result['count'] > 0) ? true : false;
    }
	
	public function get_states()
	{		

		$query = $this->db->get("state");
		return ($query->num_rows() > 0) ? $query->result(): array();
	}
	
	
}
?>