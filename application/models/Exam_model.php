<?php

/**
 * Class Exam_model
 *
 * This manage examination data
 */

class Exam_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function import_results($data, $where)
    {
        if(!is_null($where) && is_array($where)){

            return $this->db->update("exam_results",$data, $where);
        }
        return $this->db->insert("exam_results", $data);
    }

    function import_member($data)
    {
        // if(!is_null($where) && is_array($where)){

        //     return $this->db->update("exam_results",$data, $where);
        // }
        return $this->db->insert("students", $data);
    }

    function import_application_data($data)
    {
        // if(!is_null($where) && is_array($where)){

        //     return $this->db->update("exam_results",$data, $where);
        // }
        return $this->db->insert("application", $data);
    }

    function import_student_data($student_data)
    {
        // if(!is_null($where) && is_array($where)){

        //     return $this->db->update("exam_results",$data, $where);
        // }
        return $this->db->insert("students", $student_data);
    }

    function update_student_data($student_data, $data)
    {
        $this->db->where('registration_number', $data);
        return $this->db->update("students",$student_data);
    }

    function insert_user_data($user_grp_data,$last_id)
    {
        $where = $this->db->get_where('users_groups', array('user_id'=>$last_id))->result();
        if($where){
            return $this->db->update("users_groups",$user_grp_data);
        }
        return $this->db->insert("users_groups",$user_grp_data);
    }

    function import_examinations_payment($data)
    {
        // if(!is_null($where) && is_array($where)){

        //     return $this->db->update("exam_results",$data, $where);
        // }
        return $this->db->insert("examinations_payment", $data);
    }

    function exam_registration($data)
    {
        return $this->db->insert("exam_entry_applications", $data);
    }

    function temp_exam_registration( $array_data)
    {
        return $this->db->insert("temp_exam_registered", $array_data);
    }

    function add_exam_registration($data)
    {
        return $this->db->insert("student_exam_registered", $data);
    }

    function transer_data($data)
    {
        return $this->db->insert("annual_fees", $data);
    }

    function get_exam_registered($reg_no=null,$ayear, $semester)
    {
        if (!is_null($reg_no)) {
            $this->db->where('registration_number', $reg_no);
            $this->db->where('exam_year', $ayear);
            $this->db->where('exam_semester', $semester);
        }

        return $this->db->get('student_exam_registered');
    }

    function get_courses($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }

        return $this->db->get('courses');
    }

    function get_results($reg_no, $coz_code=null,$publish=1){
        if(!is_null($publish)){
            $where = array('registration_number'=>$reg_no, 'published'=>$publish);
            $this->db->where($where);
        }

        if(!is_null($reg_no)){
            $this->db->where('registration_number' ,$reg_no);
        }

        if(!is_null($coz_code)){
            $this->db->where('course' ,$coz_code);
        }
        $this->db->where('score_marks' , '>=',51 );
        return $this->db->get('exam_results');
    }

    function get_student_results($regno){
        if($regno){
            $publish=1;
            // $where = $this->db->where(''array('registration_number'=>$regno, 'published'=>$publish);
            $this->db->where('registration_number', $regno);
            $this->db->where('published', $publish);
        }
        return $this->db->get('exam_results');
    }

    function get_student_results_by_regno($regno){
       // if($regno){
            $publish=1;
            // $where = $this->db->where(''array('registration_number'=>$regno, 'published'=>$publish);
            $this->db->where('registration_number', $regno);
            $this->db->where('published', $publish);
       // }
        return $this->db->get('exam_results');
    }

    function get_course_results($course=null, $ayear=null, $session=null){

        if(!is_null($course)){
            $this->db->where('course', $course);
        }
        if(!is_null($ayear)){
            $this->db->where('academic_year', $ayear);
        }
        if(!is_null($session)){
            $this->db->where('exam_session', $session);
        }
        return $this->db->get('exam_results');
    }

    function get_exam_sessions($id = null)
    {
        if(!is_null($id)){
            $this->db->where('id', $id);
        }
        return $this->db->get('exam_sessions');
    }


    function delete_selection($id){
        $this->db->where('id', $id);
        $this->db->delete('student_exam_registered');
    }

    function get_board_report($key=null)
    {
        if (!is_null($key)) {
            // $this->db->where('academic_year', $key);
            //}
            //return $this->db->get('exam_results');

            //SELECT code, score_marks from exam_results, courses where courses . code = exam_results . course and courses . programme_id = 1001
            //   $where = " WHERE  (FirstName LIKE '%" . $key . "%' OR LastName LIKE '%" . $key . "%' OR Regno LIKE '%" . $key . "%')";

            //official querry:
            // SELECT * from exam_results, courses
            // where courses . code = exam_results . course AND
            // academic_year='2017' AND Semester = 'Semester I' AND
            // courses . programme_id = 1001

            $academic_year = $this->common_model->get_academic_year()->row()->AYear;
            $semester = $this->common_model->get_academic_year()->row()->semester;

            $where = " WHERE (courses.code = exam_results.course AND academic_year='$academic_year' AND exam_session = '$semester' AND courses.programme_id = " . $key . ")";
            $sql = " SELECT * FROM exam_results, courses $where ";

        }
        return $this->db->query($sql);
    }

    // function get_board_report($key){
    //   $where = " WHERE  (academic_year LIKE '%" .  $key . "%')";
    //
    //   $sql = " SELECT * FROM exam_results $where ";
    //
    //
    //   return $this->db->query($sql);
    // }


    function publish($data,$regno,$academic_year,$exam_session)
    {
        if (!is_null($exam_session) && !is_null($regno) && !is_null($academic_year) && !is_null($data)) {
            $where = "registration_number = '$regno' AND exam_session = '$exam_session' AND academic_year = '$academic_year'";
            $this->db->where($where);
            $this->db->update('exam_results',$data,$where);

            return TRUE;

        }else{
            return FALSE;
        }
    }

    function get_course_code($programmecode){
        $this->db->select('code,shortname');
        $this->db->order_by('shortname', 'ASC');
        $this->db->where('programme_id' ,$programmecode);
        return $this->db->get('courses');
    }

    function get_course_marks($regno,$academic_year,$code){
        //$this->db->select('score_marks');
        return  $this->db->get_where('exam_results' ,array('registration_number' => $regno, 'academic_year' => $academic_year, 'course' => $code));
    }

    function audit_trail(){
        return $this->db->get('exam_results_audit');
    }

    function get_subject_object($code=null,$id=null){
        $CI = &get_instance();
        if(!is_null($code)){
            $CI->db->where('code',$code);
        }
        if(!is_null($id)){
            $CI->db->where('id',$id);
        }
        return  $CI->db->get('secondary_subject')->row();

    }

}