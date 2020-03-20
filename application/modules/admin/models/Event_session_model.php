<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_Session_Model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}

	public function add_event_session($data){
		$this->db->insert('event_sessions',$data);
		return  $this->db->insert_id();
	}

	public function update_event_session($data, $event_session_id){
		$this->db->where('id', $event_session_id);
		if($this->db->update('event_sessions', $data)){
		    return $event_session_id;
        }
        return false;
	}

	public function delete_event_session($id=0){
		$this->db->delete('event_sessions', array('id' => $id));
	}

	public function get_event_session($id)
	{
		
		$this->db->select('*')->from('event_sessions')->where('event_sessions.id', $id);
		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query->row();

		return array();
	}

	public function get_event_sessions($start = 0, $limit = 0, $sort_field = 'event_sessions.created_at', $sort_order = 'desc', $options = array())
	{
		$this->db->select('event_sessions.*')->from('event_sessions')->join('admin_user', 'event_sessions.author = admin_user.id', 'left');


        if(isset($options['event']) && !empty($options['event'])){
            $this->db->join('event', 'event_sessions.event_id = event.id', 'left');
            $this->db->where('event.id',$options['event']);
        }


		if(isset($options['search_kw']) && !empty($options['search_kw']))
		{
            $options['search_kw'] = explode(' ',$options['search_kw']);
            $count = 1;
			$this->db->group_start();
               foreach ($options['search_kw']  as $search_kw){
                   if($count = 1){
                       $this->db->or_like('event_sessions.title_en', $search_kw, 'both');
                       $this->db->or_like('event_sessions.title_hi', $search_kw, 'both');
                   }else{
                       $this->db->or_like('event_sessions.title_en', $search_kw, 'both');
                       $this->db->or_like('event_sessions.title_hi', $search_kw, 'both');
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