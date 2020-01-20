    <?php
include VIEWPATH.'include/pbscrum.php';
?>

<div class="col-lg-12 text-center">
    <h1>Registration Form</h1>
    <?php

    if (isset($message)) {
        echo $message;
    } else if ($this->session->flashdata('message') != '') {
        echo $this->session->flashdata('message');
    }
    ?>
</div>

<div class="row gray-bg">
    <div class="container">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5 style="color: brown;"> IMPORTANT NOTE</h5></div>
                </div>
                <div class="ibox-content">
                    <table class="table">
                        <tr>
                            <td class="no-borders"> 1. Your name must be the same as in  <?php echo ($_GET['entry'] > 6 ? 'your Academic' : 'Form IV') ?> Certificate</td>
                        </tr>
                        <tr>
                            <td> 2. Date of Birth must be the same as  in  Birth Certificate</td>
                        </tr>
                        <tr>
                            <?php if($_GET['entry'] > 6 ){ ?>
                                <td> 3. Index Number must be the same as  in  Entry Mode Certificate</td>
                            <?php }else{ ?>
                            <td> 3. Form IV index Number must be the same as  in  Form IV Certificate</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td> 4. <strong>Failure to any of the above, Your application will be disqualified  </strong></td>
                        </tr>

                    </table>

                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5>Member Basic Information</h5></div>
                </div>
                <div class="ibox-content">

                    <?php  echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>


                    <div class="form-group"><label class="col-lg-3 control-label">Registration Type  : <span class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo application_type($type); ?>"
                                   class="form-control" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Entry Type  : <span class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo entry_type($entry); ?>"
                                   class="form-control" disabled="disabled"/>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">First Name : <span
                                    class="required">*</span></label>

                        <div class="col-lg-7">
                            <input type="text" value="<?php echo set_value('firstname'); ?>"
                                   class="form-control" name="firstname">
                            <?php echo form_error('firstname'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Last Name : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('lastname'); ?>"
                                   class="form-control " name="lastname">
                            <?php echo form_error('lastname'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Middle Names : </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('middlename'); ?>"
                                   class="form-control " name="middlename">
                            <?php echo form_error('middlename'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Gender : <span
                                    class="required">*</span></label>

                        <div class="col-lg-7">
                            <select name="gender" class="form-control">
                                <option value=""> [ Select Gender ]</option>
                                <?php
                                $sel =  set_value('gender');
                                foreach ($gender_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->code ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('gender'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Birth Date : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <input type="text" placeholder="DD-MM-YYYY"
                                   value="<?php echo  set_value('dob'); ?>"
                                   class="form-control  mydate_input" name="dob">
                            <?php echo form_error('dob'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Place of Birth : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('birth_place'); ?>"
                                   class="form-control" name="birth_place">
                            <?php echo form_error('birth_place'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Marital Status : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="marital_status" class="form-control ">
                                <option value=""> [ Select Marital Status ]</option>
                                <?php
                                $sel =  set_value('marital_status');
                                foreach ($marital_status_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('marital_status'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label" style="font-size: 13px;">Country of Residence : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="residence_country" class="form-control select50">
                                <option value=""> [ Select Country ]</option>
                                <?php
                                $sel =  set_value('residence_country',(isset($_GET['NT']) ? ($_GET['NT'] == 1 ? 220 :''):''));
                                foreach ($nationality_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->Country; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('residence_country'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Nationality : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="nationality" class="form-control select51 ">
                                <option value=""> [ Select Nationality ]</option>
                                <?php
                                $sel =  set_value('nationality',(isset($_GET['NT']) ? ($_GET['NT'] == 1 ? 220 :''):''));
                                foreach ($nationality_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('nationality'); ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-3 control-label">Disability : <span
                                    class="required ">*</span></label>

                        <div class="col-lg-7">
                            <select name="disability" class="form-control ">
                                <option value=""> [ Select Disability ]</option>
                                <?php
                                $sel =  set_value('disability');
                                foreach ($disability_list as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $value->id ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('disability'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Email : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('email'); ?>"
                                   class="form-control" name="email">
                            <?php echo form_error('email'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Bank Employee (yes/no)? : <span
                                    class="required ">*</span> </label>
                        <div class="col-lg-7">
                            <select class="form-control" value="<?php echo set_value('member_type'); ?>" name="member_type" id="privileges">
                                <option value="">[ Select  ]</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                <!-- <?php
                                // foreach (yes_no() as $key=>$value){
                                //     echo '<option '.($sel==$key ? 'selected="selected"':'').' value="' . $key . '">' . $value . '</option>';

                                // }
                                ?> -->
                            </select>
                            <?php echo form_error('member_type'); ?>
                        </div>
                    </div>
                    <div class="form-group resources" style=" display: none;"><label class="col-lg-3 control-label">Cooperate Member : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                        <select class="form-control" name="cooperate">
                        <option value="">[ Select Cooperate ]</option>
                        <?php
                        $members=$this->common_model->get_cooperate()->result();
                        foreach($members as $key => $value){
                        ?>
                        <option value="<?php echo $value->id; ?>"><?php echo $value->institution_name; ?></option>
                        <?php
                         } 
                        ?>
                    </select>                            <?php echo form_error('cooperate'); ?>
                        </div>
                    </div>

                    <?php echo'check it'. $type ?>
                    <div class="form-group"><label class="col-lg-3 control-label">Programme: <span
                                    class="required ">*</span> </label>
                        <div class="col-lg-7">
                            <select class="form-control" name="programme">
                                <option value="">[ Select Programme  ]</option>
                                <?php
                                    $programme=$this->common_model->get_programme_select($type)->result();
                                foreach($programme as $key => $value){
                                ?>
                                <option value="<?php echo $value->Code; ?>"><?php echo $value->Name; ?></option>
                                <?php
                                 }
                                ?>
                            </select>
                            <?php echo form_error('programme'); ?>
                        </div>
                    </div>



                    <div style="color: brown; font-weight: bold; margin-bottom: 15px; margin-top: 10px;  border-bottom: 1px solid brown; font-size: 15px;">Login Credentials</div>

                    <div class="form-group"><label class="col-lg-3 control-label">Username : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="text"  name="username"  value="<?php echo set_value('username'); ?>"  class="form-control">
                            <?php echo form_error('username'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Password : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="password"
                                   value=""
                                   class="form-control" name="password">
                            <?php echo form_error('password'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">Confirm Password : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <input type="password"
                                   value=""
                                   class="form-control" name="conf_password">
                            <?php echo form_error('conf_password'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-3 control-label">I am not a Robot :<span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <img src="<?php echo site_url('home/capture/'.$captcha_num); ?>"/>
                            <input type="text"
                                   value="" placeholder="Type six digit code as shown above"
                                   class="form-control" name="capture">
                            <?php echo form_error('capture'); ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <div class="col-lg-offset-4 col-lg-6">
                            <input class="btn btn-sm btn-success" type="submit" value="Save Information"/>
                        </div>
                    </div>
                    <?php echo form_close(); ?>



                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#privileges").change(function () {
            var select = $(this).val();
            if (select == 1) {
                $('.resources').show();
            }
            else $('.resources').hide();
        })

        $('.mydate_input').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            endDate:"31-12-2004"
        });

        $(".select50").select2({
                theme:'bootstrap',
                placeholder:'[ Select Country ]',
                allowClear:true
            });
        $(".select51").select2({
                theme:'bootstrap',
                placeholder:'[ Select Nationality ]',
                allowClear:true
            });

            var Privileges = jQuery('#privileges');
            var select = this.value;
            Privileges.change(function () {
                if ($(this).val() == '1') {
                    $('.resources').show();
                }
                else $('.resources').hide();
            });

    })
</script>
