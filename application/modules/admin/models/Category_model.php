<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
 
	public function add_category($dataArr)
	{		
		$this->db->insert('category',$dataArr);
		return  $this->db->insert_id();
	}
	
	
	public function edit_category($dataArr, $id)
	{		
		$this->db->where('id', $id);
		$this->db->update('category', $dataArr);
	
	}
	
	
	public function delete_category($id=0)
	{	
		
		$this->db->delete('category', array('id' => $id)); 
	}
	
	

	public function get_category($id)
	{
		
		$this->db->select('category.*')
				 ->from('category');
				 
		$this->db->where('category.id', $id);
			
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
	
	
	
		
	public function get_categories($start=0, $limit=0, $sort_field='created_at', $sort_order='desc', $optArr=array())
	{
		
		
		$this->db->select('category.*, p.title as parent_title, p.title_hi as parent_title_hi, admin_user.fname, admin_user.lname')
				 ->from('category');
				 
		$this->db->join('category as p', 'category.parent = p.id', 'left');
				 
		
		$this->db->join('admin_user', 'category.author = admin_user.id', 'left');
		
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('category.title', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('category.title_hi', $optArr['search_kw'], 'both')
			         ->group_end();	
		}
		
		
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
	
	
	
	public function category_title_count($title, $id=0, $parent=0)
	{		

			
		$this->db->where('title',$title);
	
		if(!empty($id)) // in case of edit
		{
			$this->db->where("id !=",$id);
		}
		
		if($parent==0)
		{
			$this->db->where("parent", $parent);
		}
		else
		{
			if(!empty($parent))
				$this->db->where("parent", $parent);
		}
		
		
		$query=$this->db->get('category');
		
		
		//echo $this->db->last_query(); 
		
		//echo "<br/>";
		
		//echo $query->num_rows();
		
		return $query->num_rows();
	}
	
	public function category_title_count_hi($title_hi, $id=0, $parent=0)
	{	
		
		$this->db->where('title_hi',$title_hi);
	
		if(!empty($id)) // in case of edit
		{
			$this->db->where("id !=",$id);
		}
		
		
		if($parent==0)
		{
			$this->db->where("parent", $parent);
		}
		else
		{
			if(!empty($parent))
				$this->db->where("parent", $parent);
		}
		
		
		$query=$this->db->get('category');
		
		
		//echo $this->db->last_query(); 
		
		//echo "<br/>";
		
		//echo $query->num_rows();
		
		return $query->num_rows();
	}
	

	
	
	public function get_top_categories($start=0, $limit=0, $sort_field='created_at', $sort_order='desc', $optArr=array())
	{
		

		$this->db->select('category.*, admin_user.fname, admin_user.lname')
				 ->from('category');	 
		
		$this->db->join('admin_user', 'category.author = admin_user.id', 'left');
		
		if(!empty($optArr['search_kw']))
		{
			$this->db->group_start()
					 ->like('category.title', $optArr['search_kw'], 'both') // before, after, both (default)
					 ->or_like('category.title_hi', $optArr['search_kw'], 'both')
			         ->group_end();	
		}

		if(isset($optArr['active']))
		{
			$this->db->where("category.active", $optArr['active']);
		}
			

		$this->db->where("category.parent", 0);
		
			
	
		$this->db->order_by($sort_field, $sort_order);
		
		
		if(!empty($limit))
			$this->db->limit($limit, $start);
		
		
		//////print sql query before execution//////////
		//echo $this->db->get_compiled_select(); die;
		/////////////////////////

		$query=$this->db->get(); // runs query()
		
		
		//////print sql query before execution//////////
		//echo $this->db->last_query(); die;
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