<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
        $this->load->model('admin_user_model');
	}

	public function add_event($data){
		$this->db->insert('event',$data);
		return  $this->db->insert_id();
	}

    public function add_event_relationship($event_id, $relationships, $type = 'category'){

        $relationship_data['event_id'] = $event_id;

        if(!in_array($type,array('category','coordinator'))) $type = 'category';

        $relationship_data['type'] = $type;

	    if(empty($relationships)) return;

        $this->db->delete('event_relationships', array('event_id' => $event_id,'type'=> $type));

        foreach ($relationships as $relationship){
            $relationship_data['relationship_id'] = $relationship;
            $this->db->insert('event_relationships',$relationship_data);
        }

        return  true;
    }

    public function delete_event_relationships($event_id, $type = 'all'){

        $relationship_data['event_id'] = $event_id;

        if(!in_array($type,array('category','coordinator','all'))) $type = 'all';

        if($type != 'all')
            $relationship_data['type'] = $type;

        $this->db->delete('event_relationships',$relationship_data);

        return  true;
    }

    public function get_active_sessions($event_id)
    {

        if(empty($event_id)) return array();

        $event_details = $this->get_event($event_id);

        $this->db->select('*')->from('event_sessions')
            ->where('event_sessions.event_id', $event_id)
            ->where('status', 'published')
            ->where('session_status','default')
            ->where("
            (
            start_time BETWEEN CAST('".$event_details->start_date."' AS DATE) AND CAST('".$event_details->end_date."' AS DATE) 
            OR (
                CAST(start_time AS DATE) = CAST('".$event_details->start_date."' AS DATE)
                AND CAST(end_time AS DATE) = CAST('".$event_details->start_date."' AS DATE)
                )
            )" );

        $query = $this->db->get();

        if($query->num_rows() > 0)
            return $query->result_array();

        return array();
    }

    public function get_event_category($event_id){

        if(empty($event_id)) return;

        $this->db->select('*')->from('event_relationships')
            ->where(array('event_id' => $event_id, 'type' => 'category' ) )
            ->join( 'category', 'category.id = event_relationships.relationship_id','right');

        $categories = $this->db->get();

        if($categories->num_rows() > 0)
            return $categories->result_array();

        return  array();
    }

    public function get_event_coordinators($event_id, $fields = '*'){

        if(empty($event_id)) return;

        if(!empty($fields)){
            if(is_array($fields)){
                $fields = array_map(function ($field){
                    return 'coordinator.'.$field;
                },$fields);
            }else{
                $fields = 'coordinator.'.$fields;
            }

        }

        $fields = (is_array($fields)) ? implode(',', $fields) : $fields;

        $this->db->select($fields)->from('event_relationships')
            ->where(array('event_id' => $event_id, 'type' => 'coordinator' ) )
            ->join( 'coordinator', 'coordinator.id = event_relationships.relationship_id','right');

        $categories = $this->db->get();

        if($categories->num_rows() > 0)
            return $categories->result_array();

        return  array();
    }

	public function update_event($data, $event_id){

        if(empty($event_id)) return false;

		$this->db->where('id', $event_id);
		if($this->db->update('event', $data)){
		    return $event_id;
        }
        return false;
	}

	public function delete_event($event_id = ''){

        if(empty($event_id)) return;

		$this->db->delete('event', array('id' => $event_id));
		$this->delete_event_relationships($event_id);
	}

	public function get_event($event_id = '', $fields ='*')
	{

        if(empty($event_id)) return array();

        if(!empty($fields)){
            if(is_array($fields)){
                $fields = array_map(function ($field){
                    return 'event.'.$field;
                },$fields);
            }else{
                $fields = 'event.'.$fields;
            }

        }

        $fields = (is_array($fields)) ? implode(',', $fields) : $fields;

		$this->db->select($fields)->from('event')->where('event.id', $event_id);
		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query->row();

		return array();
	}

	public function get_events($start = 0, $limit = 0, $sort_field = 'event.created_at', $sort_order = 'desc', $options = array())
	{
		$this->db->select('event.*')->from('event')->join('admin_user', 'event.author = admin_user.id', 'left');

        if(!empty($options['coordinator'])){
            $this->db->join('event_relationships', 'event.id = event_relationships.event_id', 'left');
            $this->db->where('event_relationships.relationship_id', $options['coordinator'])->where('event_relationships.type','coordinator');
        }

        if(!empty($options['status']) && in_array($options['status'],array('all','draft','published','active')) && 'all' != $options['status']){


            if(in_array($options['status'],array('draft','published'))){
                $this->db->where('event.status', $options['status']);
            }else{
                $current_date = date('Y-m-d');
                $this->db
                    ->group_start()
                    ->where('event.start_date >=', $current_date)
                    ->or_where('event.end_date >= ',$current_date)
                    ->group_end();
            }

        }


		if(!empty($options['search_kw']))
		{
			$this->db->group_start()
					 ->like('event.title_en', $options['search_kw'], 'both') // before, after, both (default)
					 ->or_like('event.title_hi', $options['search_kw'], 'both')
			         ->group_end();	
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