<?php

/**
 * Class Member_model
 *
 * This manage member data
 */

class Member_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function add_member($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('member',$data,array('id'=>$id));
        }else{
            return $this->db->insert('member',$data);
        }
    }

    function add_fellow_member($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('fellow_member',$data,array('id'=>$id));
        }else{
            return $this->db->insert('fellow_member',$data);
        }
    }

    function change_stata($array_data,$application_status,$id=null){
        if(!is_null($id)){
            if($this->db->update('users_groups',$array_data,array('user_id'=>$id))){

              return $this->db->update('application',$application_status,array('user_id'=>$id));
            }
        }else{
            return $this->db->insert('users_groups',$array_data);
        }
    }

    function change_member_status($application_status, $uid){

        if(!is_null($uid)){

            return $this->db->update('students',$application_status,array('user_id'=>$uid));

        }

    }

    function change_programme($program_data, $id){
        if(!is_null($id)){
              return $this->db->update('application_programme_choice',$program_data,array('applicant_id'=>$id));
        }
    }

    function change_member_programme($program_data, $id){
        if(!is_null($id)){
              return $this->db->update('students',$program_data,array('id'=>$id));
        }
    }

    function change_applicationtype($application_data, $id){
        if(!is_null($id)){
              return $this->db->update('application',$application_data,array('id'=>$id));
        }
    }

  //public function insert_student_records($copy_student_records,$user_identity_number=null, $update_student_records){
    public function insert_student_records($copy_student_records,$user_identity_number=null){
        $copy_student=$copy_student_records['registration_number'];
      if(!is_null($user_identity_number) || !is_null($copy_student)){
        if(!is_null($user_identity_number) && $user_identity_number!='a'){
          return $this->db->insert('students',$copy_student_records);
        }elseif(!is_null($user_identity_number ) && $user_identity_number =='a' && !is_null($copy_student)){
          return $this->db->update('students',$copy_student_records,array('registration_number'=>$copy_student));
          }
        }

          return $this->db->insert('students',$copy_student_records);
    }

    function change_status($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('user_group',$data,array('user_id'=>$id));
        }else{
            return $this->db->insert('user_group',$data);
        }
    }

    function get_member($id=null){
      if(!is_null($id)) {
          $this->db->where('id',$id);
       }
           return $this->db->get('member');
    }

    function get_details($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
            $this->db->where('programme_id',$programmeid);
         }
             return $this->db->get('exam_fee');
      }

    function get_fellow($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }
        return $this->db->get('fellow_member');
    }

    function get_info($id=null){
      if(!is_null($id)){
          $this->db->where('user_id',$id);
       }
           return $this->db->get('application');
    }

    function search_member($key)
    {
        if (!is_null($key)) {
            $where = " WHERE  (user_id ='$key')";
              $sql = " SELECT * FROM application $where ";
        }

      return $this->db->query($sql);
    }

    function search_member_forstatus($key)
    {
        if (!is_null($key)) {
            $where = " WHERE  (user_id ='$key')";
            $sql = " SELECT * FROM students $where ";
        }

        return $this->db->query($sql);
    }

     function get_member_programme($key)
    {
        if (!is_null($key)) {
            $where = " WHERE  (user_id ='$key')";
              $sql = " SELECT * FROM students $where ";
        }

      return $this->db->query($sql);
    }

    function import_members($data,$id=null)
    {
        if (is_null($id)) {
            return $this->db->insert("students", $data);
        }

        // if ($id == 1) {
        //     $this->db->insert("users", $data);
        //     $last_user_id = $this->db->insert_id();
        //     return $last_user_id;
        // }

        // if ($id == 2) {
        //     return $this->db->insert("users_groups", $data);
        // }

        // if ($id == 3) {
        //     return $this->db->insert("application", $data);
        // }
    }

    function delete_member($id){
        $this->db->where('id', $id);
        $this->db->delete('member');
    }

    function delete_fellow_member($id){
        $this->db->where('id', $id);
        $this->db->delete('fellow_member');
    }

}
