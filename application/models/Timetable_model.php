<?php

/**
 * Class Timetable_model
 *
 * This manage member data
 */

class Timetable_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function exam_register($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('exam_register',$data,array('id'=>$id));
        }else{
            return $this->db->insert('exam_register',$data);
        }
    }


    function get_exam_register($data=null,$id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        if(!is_null($data)){
            $this->db->where('coursecode',$data);
        }
        return $this->db->get('exam_register');
    }



}
