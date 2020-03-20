<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}


	public function get_category($id)
	{
		
		$this->db->select('category.*')
				 ->from('category');
				 
		$this->db->where('category.id', $id);

		$query=$this->db->get();

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

	public function get_categories($start=0, $limit=0, $sort_field='created_at', $sort_order='desc', $optArr=array())
	{

		$this->db->select('category.*')->from('category');

		if(isset($optArr['active']))
		{
			$this->db->where("category.active", $optArr['active']);
		}
		
		if(isset($optArr['parent']))
		{
			$this->db->where("category.parent", $optArr['parent']);
		}

		$this->db->order_by($sort_field, $sort_order);
		
		
		if(!empty($limit))
			$this->db->limit($limit, $start);

		$query=$this->db->get();

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

	public function get_top_categories($start=0, $limit=0, $sort_field='created_at', $sort_order='desc', $optArr=array())
	{
		

		$this->db->select('category.*')->from('category');

		if(isset($optArr['active']))
		{
			$this->db->where("category.active", $optArr['active']);
		}

		$this->db->where("category.parent", 0);

		$this->db->order_by($sort_field, $sort_order);

		if(!empty($limit))
			$this->db->limit($limit, $start);

		$query=$this->db->get(); // runs query()

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