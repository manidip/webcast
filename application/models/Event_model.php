<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}

    public function get_sessions($event_id, $sort_field = 'event_sessions.start_time', $sort_order = 'asc', $options = array())
    {

        if(empty($event_id)) return array();

        $event_details = $this->get_event($event_id);

        $current_date_time = date('Y-m-d H:i:s');

        $this->db->select('*')->from('event_sessions')->where('event_sessions.event_id', $event_id) ->where('status', 'published');

        if(in_array($options['status'], array('upcoming','vod','active'))){

            if('active' == $options['status']){

                $this->db->where('CAST(event_sessions.start_time  AS DATETIME) <= ',$current_date_time)->where('CAST(event_sessions.end_time  AS DATETIME) >= ',$current_date_time);

            }elseif('upcoming' == $options['status']){

                $this->db->where('session_status','default')
                ->where('CAST(event_sessions.start_time  AS DATETIME) >= ',$current_date_time);

            }else if('vod' == $options['status']){

                $this->db->where('session_status','vod')
                ->where('CAST(event_sessions.end_time  AS DATETIME) < ',$current_date_time);
            }
        }
           $this->db->where("
           (
           start_time BETWEEN CAST('".$event_details->start_date."' AS DATE) AND CAST('".$event_details->end_date."' AS DATE) 
           OR (
                CAST(start_time AS DATE) = CAST('".$event_details->start_date."' AS DATE)
                AND CAST(end_time AS DATE) = CAST('".$event_details->start_date."' AS DATE)
               )
            )
          ");

        $this->db->order_by($sort_field, $sort_order);

        $query = $this->db->get();


        //print_r($this->db->last_query());

        if($query->num_rows() > 0)
            return $query->result();

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
		$this->db->select('event.*,(SELECT event_sessions.start_time FROM event_sessions WHERE event_sessions.event_id = event.id ORDER BY event_sessions.start_time ASC LIMIT 1) AS session_start_time')->from('event');

		$this->db->where('event.status', 'published');

        if(!empty($options['coordinator'])){
            $this->db->join('event_relationships', 'event.id = event_relationships.event_id', 'left');
            $this->db->where('event_relationships.relationship_id', $options['coordinator'])->where('event_relationships.type','coordinator');
        }

        if(!empty($options['category']) && is_numeric($options['category']))
        {
            $this->db->join('event_relationships', 'event.id = event_relationships.event_id', 'left');
            $this->db->where('event_relationships.relationship_id', $options['category'])->where('event_relationships.type','category');
        }

        if(!empty($options['status']) && in_array($options['status'],array('all','recent','ongoing','upcoming','active')) && 'all' != $options['status']){

            $current_date = date('Y-m-d');

            if('recent' == $options['status']) {

                $this->db->where('event.end_date < ',$current_date);

            }else if('upcoming' == $options['status']) {

                $this->db->where('CAST(event.start_date  AS DATE) > ',$current_date);

            }else if('ongoing' == $options['status']) {

                $this->db->where('CAST(event.start_date  AS DATE) <= ',$current_date)->where('CAST(event.end_date  AS DATE) >= ',$current_date);

            }else if('active' == $options['status']) {

                $this->db->where('CAST(event.start_date  AS DATE) <= ',$current_date)->where('CAST(event.end_date  AS DATE) >= ',$current_date);

            }
        }
		if(!empty($options['search_kw']))
		{

			$this->db->group_start()
					 ->like('event.title_en', $options['search_kw'], 'both') // before, after, both (default)
					 ->or_like('event.title_hi', $options['search_kw'], 'both')
			         ->group_end();	
		}

        if(!empty($options['organization'])){
            $this->db->group_start()
                ->where('event.ministry =', $options['organization']) // before, after, both (default)
                ->or_where('event.department = ', $options['organization'])
                ->or_where('event.organization = ', $options['organization'])
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