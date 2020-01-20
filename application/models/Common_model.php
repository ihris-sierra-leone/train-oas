<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 2:23 PM
 */
class Common_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function save_remote_school($data){
        foreach ($data as $key=>$value){
            $row = $this->db->where('id',$value->id)->get('college_schools')->row();
            if($row){
                unset($value->id);
                $this->db->update('college_schools',$value,array('id'=>$row->id));
            }else{
                $this->db->insert('college_schools',$value);
            }
        }
    }

    function save_remote_department($data){
        foreach ($data as $key=>$value){
            $row = $this->db->where('id',$value->id)->get('department')->row();
            if($row){
                unset($value->id);
                $this->db->update('department',$value,array('id'=>$row->id));
            }else{
                $this->db->insert('department',$value);
            }
        }
    }

    function save_remote_programme($data){
        foreach ($data as $key=>$value){
            $row = $this->db->where('id',$value->id)->get('programme')->row();
            if($row){
                unset($value->id);
                $this->db->update('programme',$value,array('id'=>$row->id));
            }else{
                $this->db->insert('programme',$value);
            }
        }
    }

    function display_sessions(){
        return $this->db->get('exam_sessions');
    }


    function save_remote_users($data){
        foreach ($data as $key=>$value){
            unset($value->v2_allowed);
            unset($value->default_pass);
            $value->sims_map = $value->id;
            $row = $this->db->where('sims_map',$value->id)->get('users')->row();
            if($row){

                unset($value->id);
                unset($value->password);
                unset($value->last_login);
                $this->db->update('users',$value,array('sims_map'=>$row->id));
            }else{
                unset($value->id);
                $this->db->insert('users',$value);
                $last_id = $this->db->insert_id();
                    $this->db->insert("users_groups",array('user_id'=>$last_id,'group_id'=>6));
            }
        }
    }

    /**
     * Get Campus
     * @param null $id
     * @return mixed
     */
    function get_campus($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('campus');
    }

    /**
     * Get Academic Years
     * @param null $campus
     * @param null $name
     * @param null $status
     * @return mixed
     */
    function get_academic_year($name=null,$status=1,$limit=null){

        if(!is_null($name)){
            $this->db->where('AYear',$name);
        }

        if(!is_null($status)){
            $this->db->where('Status',$status);
        }
        $this->db->order_by('AYear','DESC');

        if(!is_null($limit)){
            $this->db->limit($limit);
        }

        return $this->db->get('ayear');
    }

    function delete_record($id, $table)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
            $this->db->delete($table);
            return true;
        }

    }

    function get_expire_date($uid = null){

        if(!is_null($uid)){
            $this->db->where('user_id',$uid);
        }

        $this->db->order_by('id','DESC');


            return $this->db->get('annual_fees');
    }

    function get_membership_amount($student=null,$ordinary=null, $limit=null){

        if(!is_null($student)){
            $this->db->where('member_category',$student);
        }

        if(!is_null($ordinary)){
            $this->db->where('member_category',$ordinary);
        }

        if(!is_null($limit)){
            $this->db->limit($limit);
        }

        return $this->db->get('exam_fee');
    }

    /**
     * Get current session
     * @param null $status
     * @return id
     */
    // function get_exam_session($id=null){
    //     if (!is_null($id)) {
    //         $this->db->where('id', $id);
    //     }else{
    //         $this->db->where('status',1);
    //     }
    //
    //     $this->db->limit(1);
    //
    //     return $this->db->get('exam_sessions')->row();
    // }

    function get_exam_session($id=null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('exam_sessions');
    }

    function get_studylevel($id=null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('studylevel');
    }

    function get_cooperate_member($id=null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db-get('member');
    }

    function get_certificates($id=null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('certifications');
    }

    /**
     * Get current year
     * @param null $status
     * @return AYear
     */
    function get_current_year(){

        $this->db->where('Status',1);

        $this->db->limit(1);

        return $this->db->get('ayear')->row();
    }


    /**
     * Get Module List
     * @param null $id
     * @return mixed
     */
    function get_module($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('courses');
    }

    /**
     * Get Module Roles
     * @param $module_id
     * @return mixed
     */
    function get_module_section($module_id)
    {


        $this->db->where('module_id', $module_id);
        return $this->db->get('module_section')->result();
    }

    /**
     * Get Module Roles
     * @param $module_id
     * @return mixed
     */
    function get_module_role($module_id, $section = null, $role = null)
    {
        $current_user_group = get_user_group();
        if ($current_user_group->id != 13) {
            $this->db->where('only_developer', 0);
        }

        if (!is_null($section)) {
            $this->db->where('section', $section);
        }

        if (!is_null($role)) {
            $this->db->where('role', $role);
        }

        $this->db->where('module_id', $module_id);
        return $this->db->get('module_role')->result();
    }


    /**
     * Get User Title (Mr, Mrs etc)
     * @param null $id
     * @return mixed
     */
    function get_users_title($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('user_title');
    }


    /**
     * Get Disability List
     * @param null $id
     * @return mixed
     */
    function get_disability($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('disability');
    }

    /**
     * Get Entry Category List
     * @param null $id
     * @return mixed
     */

    function get_entrycategory($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('enttrycategory');
    }

    /**
     * Get Nationality List
     * @param null $id
     * @return mixed
     */
    function get_nationality($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get('nationality');
    }

    /**
     * Get Gender List
     * @param null $id
     * @return mixed
     */
    function get_gender($code = null, $id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        if (!is_null($code)) {
            $this->db->where('code', $code);
        }
        return $this->db->get('gender');
    }

    /**
     * Get Schools or Colleges
     * @param null $id
     * @return mixed
     */
    function get_college_school($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }
        $this->db->order_by('type1','ASC');
        $this->db->order_by('name','ASC');
        return $this->db->get('college_schools');
    }
    /**
     * Get department List
     * @param null $id
     * @return mixed
     */
    function get_department($id=null,$school_id=null) {
        if (!is_null($id)) {
            if(is_array($id)){
                $this->db->where_in('id', $id);
            }else {
                $this->db->where('id', $id);
            }
        }
        if (!is_null($school_id)) {
            if(is_array($school_id)){
                $this->db->where_in('school_id', $school_id);
            }else{
                $this->db->where('school_id', $school_id);
            }
        }

        return $this->db->get('department');
    }

    /**
     * Get Semester List
     * @param null $limit
     * @return mixed
     */
    function get_semester($limit=null){
        if(!is_null($limit)){
            $this->db->limit($limit);
        }
        return $this->db->get("semester")->result();
    }


    function get_payment_status(){
        return $this->db->get("payment_status");
    }

    function get_application_deadline(){
        return $this->db->get("application_deadline");
    }

    function add_programme($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('programme',$data,array('id'=>$id));
        }else{
            return $this->db->insert('programme',$data);
        }
    }


    function add_center($data, $id=null){
        if(!is_null($id)){
            return $this->db->update('examination_centers',$data,array('id'=>$id));
        }else{
            return $this->db->insert('examination_centers',$data);
        }
    }

    function register_venue($data,$id=null){
        if(!is_null($id)){
            return $this->db->update('venue',$data,array('id'=>$id));
        }else{
            return $this->db->insert('venue',$data);
        }
    }


    function get_venue($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }
        return $this->db->get('venue');
    }

    function add_module($array_data,$id=null){
        if(!is_null($id)){
            return $this->db->update('courses',$array_data,array('id'=>$id));
        }else{
            return $this->db->insert('courses',$array_data);
        }
    }

    function add_department($array_data,$id=null){
        if(!is_null($id)){
            return $this->db->update('department',$array_data,array('id'=>$id));
        }else{
            return $this->db->insert('department',$array_data);
        }
    }


    function add_certificate($array_data,$id=null){
        if(!is_null($id)){
            return $this->db->update('certifications',$array_data,array('id'=>$id));
        }else{
            return $this->db->insert('certifications',$array_data);
        }
    }

    function add_studylevel($array_data,$id=null){
        if(!is_null($id)){
            return $this->db->update('studylevel',$array_data,array('id'=>$id));
        }else{
            return $this->db->insert('studylevel',$array_data);
        }
    }

    function add_session($array_data,$id=null){
        if(!is_null($id)){
            return $this->db->update('exam_sessions',$array_data,array('id'=>$id));
        }else{
            return $this->db->insert('exam_sessions',$array_data);
        }
    }


    function  get_programme($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('programme');
    }

    function get_programme_select($type=null)
    {
        if(!is_null($type)){
            $this->db->where('type', $type);
            $this->db->where('active', 1);
        }
        return $this->db->get('programme');
    }

    function  get_cooperate($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('member');
    }


    function  get_years($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('ayear');
    }


    function  get_exam_center($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('examination_centers');
    }


    function get_marital_status($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get("maritalstatus");
    }


    function get_recommendation_area($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('recommandation_area');
    }


    function session_edit($session_id){
       $this->db->where('id', $session_id);
       return $this->db->get('exam_sessions')->result();
    }


    function module_edit($module_id){
        $this->db->where('id', $module_id);
       return $this->db->get('module')->result();
    }


     function studylevel_edit($level_id){
       $this->db->where('id', $level_id);
       return $this->db->get('college_schools')->result();
    }



    function save_certificates($ArrayData){
        $this->db->insert('certifications', $ArrayData);
    }


    // function save_module($dataArray){
    //     $this->db->insert('module', $dataArray);
    // }


    function save_sessions($sessionArray){
        $this->db->insert('exam_sessions', $sessionArray);
    }


    function save_studylevel($DataArray){
        $this->db->insert('college_schools', $DataArray);
    }

    /**
     * Get Exam Course List
     * @param null $id
     * @return mixed
     */
    function get_courses()
    {
        return $this->db->get('courses');
    }

    function remove_course($courseID,$regno){
        $this->db->where('registration_number', $regno);
        $this->db->where('course_id', $courseID);
        return $this->db->delete('temp_exam_registered');
    }

    function delete_course($courseID,$regno){
        $this->db->where('registration_number', $regno);
        $this->db->where('course_id', $courseID);
        return $this->db->delete('student_exam_registered');
    }

    function delete_center($id){
        $this->db->where('id', $id);
        $this->db->delete('examination_centers');
    }

    function delete_venue($id){
        $this->db->where('id', $id);
        $this->db->delete('venue');
    }


    function delete_cert($id){
       $this->db->where('id', $id);
        $this->db->delete('certifications');
    }


    function delete_module($id){
        $this->db->where('id', $id);
        $this->db->delete('courses');
    }

    function delete_department($id){
        $this->db->where('id', $id);
        $this->db->delete('department');
    }

    function delete_programme($id){
        $this->db->where('id', $id);
        $this->db->delete('programme');
    }

     function delete_session($id){
        $this->db->where('id', $id);
        $this->db->delete('exam_sessions');
    }


    function delete_studylevel($id){
        $this->db->where('id', $id);
        $this->db->delete('college_schools');
    }


    /**
     * Get Student
     * @param null $id
     * @return mixed
     */

//    function get_student($id=null){
//        if(!is_null($id)){
//            $this->db->where('user_id',$id);
//        }
//
//        return $this->db->get('students');
//    }


    function get_student($id=null,$regno=null){
        if(!is_null($id)){
            $this->db->where('user_id',$id);
        }
        if(!is_null($regno)){
            $this->db->where('registration_number',$regno);
        }
        return $this->db->get('students');
    }


    function get_course($code=null){
        if(!is_null($code)){
            $this->db->where('code',$code);
        }

        return $this->db->get('courses');
    }


    function  get_membertype($id=null){
        if(!is_null($id)){
            $this->db->where('id',$id);
        }
        return $this->db->get('member_type');
    }




}
