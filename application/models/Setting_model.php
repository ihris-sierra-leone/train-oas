<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 6:15 PM
 */
class Setting_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_sec_category($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get("secondary_category");
    }


    function get_sec_subject($id=null,$status=null){
        $where = "WHERE 1=1 ";
        if(!is_null($id)){
            $where.= " AND s.id='$id' ";
        }


        if(!is_null($status)){
            $where .=" AND s.status = '$status' ";
        }
        $sql = " SELECT s.*  FROM secondary_subject as s   $where ORDER BY name ASC ";
        return $this->db->query($sql);
    }


    function add_sec_subject($data,$id=null){
        if(!is_null($id)){
            return $this->db->update("secondary_subject",$data,array('id'=>$id));
        }else{
            return $this->db->insert("secondary_subject",$data);
        }
    }

    /**
     * Add New Academic or Semester
     *
     * @param $data
     * @return mixed
     */
    function add_ayear($data){
        $this->db->update('ayear',array('Status'=>0));
        $this->db->where('AYear',$data['AYear']);
        $this->db->where('semester',$data['semester']);
        $check = $this->db->get('ayear')->row();
        if($check){
            return $this->db->update('ayear',array('Status'=>1),array('id'=>$check->id));
        }else{
            $this->db->insert('ayear',array('AYear'=>$data['AYear'],'semester'=>$data['semester'],'Status'=>1 ));
        }
    }

    function programme_setting_criteria($data){
        $row = $this->db->where(array('ProgrammeCode'=>$data['ProgrammeCode'],'AYear'=>$data['AYear'],'entry'=>$data['entry']))->get('application_criteria_setting')->row();
        if($row){
            $data['modifiedby'] = $data['createdby'];
            unset($data['createdby']);
            $data['modifiedon'] = $data['createdon'];
            unset($data['createdon']);
            return $this->db->update("application_criteria_setting",$data,array('id'=>$row->id));
        }else{
            return $this->db->insert("application_criteria_setting",$data);
        }
    }

    function get_selection_criteria($programme,$ayear,$entry){
       $this->db->where(array('ProgrammeCode'=>$programme,'AYear'=>$ayear,'entry'=>$entry));
       return $this->db->get("application_criteria_setting")->row();
    }



}