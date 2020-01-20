<ul class="nav metismenu" id="side-menu">

<?php
    $get_group = get_user_group($CURRENT_USER->id);
       $tmp_current_campus = current_campus();
       ?>
       <li class="nav-header">
           <div class="logo-element">
               SARIS OAS
           </div>
       </li>


       <?php
       if($get_group->module_id == 1){
      //admission Module
        include 'admin_menu.php';
    }else if($get_group->module_id == 2){
        //Admission Module
        include 'panel_menu.php';
    }else if($get_group->module_id == 3){
           //Academic  Module
           include 'academic_menu.php';
       }else if($get_group->module_id == 4){
           //Member  Module
           include 'member_menu.php';
       }else if($get_group->module_id == 5){
           //Applicant  Module
           include 'applicant_menu.php';
       }else if($get_group->module_id == 9){
           //AAccountant  Module
           include 'accountant_menu.php';
       }





       ?>

    </ul>

