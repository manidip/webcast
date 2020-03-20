<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_Model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}

	public function add_banner($data){
		$this->db->insert('banners',$data);
		return  $this->db->insert_id();
	}

	public function update_banner($data, $banner_id){
		$this->db->where('id', $banner_id);
		if($this->db->update('banners', $data)){
		    return $banner_id;
        }
        return false;
	}

	public function delete_banner($id=0){
		$this->db->delete('banners', array('id' => $id));
	}

	public function get_banner($id)
	{
		
		$this->db->select('*')->from('banners')->where('banners.id', $id);
		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query->row();

		return array();
	}

	public function get_banners($start = 0, $limit = 0, $sort_field = 'banners.created_at', $sort_order = 'desc', $options = array())
	{
		$this->db->select('banners.*')->from('banners');

		if(isset($options['search_kw']) && !empty($options['search_kw']))
		{
            $options['search_kw'] = explode(' ',$options['search_kw']);
            $count = 1;
			$this->db->group_start();
               foreach ($options['search_kw']  as $search_kw){
                   if($count = 1){
                       $this->db->or_like('banners.title_en', $search_kw, 'both');
                   }else{
                       $this->db->or_like('banners.title_en', $search_kw, 'both');
                   }
                   $count ++;
               }

            $this->db->group_end();
		}

		$this->db->order_by($sort_field, $sort_order);

		if(!empty($limit))
			$this->db->limit($limit, $start);

		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		    return $query->result();

		return array();
	}

}
?>