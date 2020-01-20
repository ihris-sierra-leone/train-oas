<?php
include VIEWPATH.'include/pbscrum.php';
?>
    <?php
    $is_app_open = true;
    if(get_application_deadline() < date('Y-m-d')){
        $is_app_open =false;
    } ?>
<div class="row">
<div class="container">
    <div class="col-md-6" style="border-right: 1px solid #c4e3f3;">
        <h2 style="text-align: center;">APPLICATION PROCEDURES</h2>

        <div class="row">
            <div class="col-lg-12">

                            <ul class="procedure">
                                <li><span class="head_title">Step 1 :: Read Application Requirements</span>
                                    <div class="procedure_content">
                                        <p>Before starting registration, Please click this link to read <a style="font-weight: bold;" href="<?php echo ADMISSION_REQUIREMENT; ?>"> Admission Requirements</a></p>


                                        <p>Prepare a list of Scanned documents for Attachments <br/>
                                            <span >All Applicants must have</span>
                                            <ul class="list_data">
                                            <li>Recent Digital or Scanned Passport size Photo with blue as background</li>
                                            <li>Scanned original Birth Certificate</li>
                                            <li>Applicant with O-Level Certificate (CSEE) : Scanned original O-Level Education Certificate in PDF or Image</li>
                                            <li>Applicant with A-Level Certificate (ACSEE): Scanned original O-Level and A-Level Education Certificate in PDF or Image</li>
                                            <li>Applicant with VETA Certificate : Scanned original O-Level AND VETA Certificate in PDF or Image</li>
                                        </ul>

                                    </div>
                                </li>
                                <li><span class="head_title">Step 2 :: START APPLICATION/BASIC INFORMATION</span>
                                    <div class="procedure_content">
                                        <p>Click on the button at the right side to start.
                                            Carefully select your correct current education level. Your Form Four Index Number will be used as your <strong>username</strong> and make sure you remember your <strong>password</strong>. Provide all your basic details and then after saving system will login automatic for you. You must provide a valid email address (Active email Address).</p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 3 :: Contact Information</span>
                                    <div class="procedure_content">
                                        <p >After Completing Step One System will login based on password provided in step One and redirect you to fill your contacts details. Please fill all required field in this section</p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 5 :: Profile Picture</span>
                                    <div class="procedure_content">
                                        <p >In this section upload scanned Passport size.</p>
                                    </div>
                                </li>

                                <li><span class="head_title">Step 4 :: Next of Kin (Parents/Guardian)</span>
                                    <div class="procedure_content">
                                        <p >This section require you to fill in Parents/Guardians Information and Contacts Information (Name,Contacts e.t.c)</p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 5 :: Application Fee Payment</span>
                                    <div class="procedure_content">
                                        <p>System offer Mobile Payment Process. You can pay application fee either by <strong>TIGO PESA</strong>, <strong>M-PESA</strong> or <strong>AIRTEL MONEY</strong> or <strong>ZANTEL</strong>. Application fee is TZS : <?php echo number_format(APPLICATION_FEE); ?>. In this section you will get your  <strong>Payment Reference Number</strong>.<br/><br/>Steps on how to pay will be available by selecting/Clicking payment Method under this section. After Payment the system will recognise your payment and the page will be updated.</p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 6 :: Education Background</span>
                                    <div class="procedure_content">
                                        <p>This section require all applicants to fill in his/her Educational Background. The Information entered in this part must be the same as in your Certificates, Failure to that your application will be rejected.</p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 7 :: Attachment&nbsp;</span>
                                    <div class="procedure_content">
                                        <p >Upload all the required scanned original documents as listed in Step One. </p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 8 :: Choose/Select Programme to Study</span>
                                    <div class="procedure_content">
                                        <p>Before Select/Choose Programmes, Please make sure you have read our <strong>Admission Requirements</strong>. You will be required to select/Choose three (3) Programmes from available list as First Choice, Second Choice and Third Choice.  </p>
                                    </div>
                                </li>
                                <li><span class="head_title">Step 9 :: Review && Submit your Application&nbsp;</span>
                                    <div class="procedure_content">
                                        <p >Before you submit your application. You are required to review all of the Information for correctness. Edit to correct in case of any error. Remember after submission you will not be able to change any information. So be carefully to review and correct all information before submit. </p>
                                    </div>
                                </li>
                            </ul>

                        <p><strong>NOTE : You will be able to see the status of your application in your account once verification process opened</strong></p>



            </div>


        </div>




    </div>



    <div class="col-md-6" style="border-left: 1px solid #c4e3f3;">

        <h2 style="text-align: center;">START APPLICATION</h2>
        <div class="row">
            <div class="col-lg-12">
                <?php if($is_app_open){ ?>
                <ul class="procedure">
                    <li><span class="head_title">Please select correct option below</span>
                    </li>
                </ul>
                    <?php  echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
                    <div class="form-group"><label class="col-lg-6 control-label">Application Type  : <span class="required">*</span></label>

                        <div class="col-lg-6">
                            <select class="form-control" id="type">
                                <option value="">[ Select Type ]</option>
                                <?php
                                $sel = (isset($_GET['type']) ? $_GET['type'] : '');
                                foreach (application_type() as $key=>$value){
                                    echo '<option '.($sel==$key ? 'selected="selected"':'').' value="'.$key.'">'.$value.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <?php if(isset($_GET) && isset($_GET['type']) && $_GET['type'] <> ''){ ?>
                        <div class="form-group"><label class="col-lg-6 control-label">Have you completed O' Level (CSEE)?  : <span class="required">*</span></label>

                            <div class="col-lg-6">
                                <select class="form-control" id="CSEE">
                                    <option value="">[ Select  ]</option>
                                    <?php
                                    $sel = (isset($_GET['CSEE']) ? $_GET['CSEE'] : -1);
                                    foreach (yes_no() as $key=>$value){
                                            echo '<option '.($sel==$key ? 'selected="selected"':'').' value="' . $key . '">' . $value . '</option>';

                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(isset($_GET) && isset($_GET['CSEE']) && $_GET['CSEE'] <> '' && $_GET['CSEE'] == 1){ ?>
                        <div class="form-group"><label class="col-lg-6 control-label">Where did you complete your O' Level (CSEE)?  : <span class="required">*</span></label>

                            <div class="col-lg-6">
                                <select class="form-control" id="NT">
                                    <option value="">[ Select  ]</option>
                                    <?php
                                    $sel = (isset($_GET['NT']) ? $_GET['NT'] : -1);
                                    foreach (CSEE_type() as $key=>$value){
                                        echo '<option '.($sel==$key ? 'selected="selected"':'').' value="' . $key . '">' . $value . '</option>';

                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php }
                    if(isset($_GET) && isset($_GET['CSEE']) && $_GET['CSEE'] <> '' && $_GET['CSEE'] == 0){ ?>

                        <div class="form-group"><label class="col-lg-6 control-label">Do you have VETA?  : <span class="required">*</span></label>

                            <div class="col-lg-6">
                                <select class="form-control" id="VT">
                                    <option value="">[ Select  ]</option>
                                    <?php
                                    $sel = (isset($_GET['VT']) ? $_GET['VT'] : -1);
                                    foreach (yes_no() as $key=>$value){
                                        echo '<option '.($sel==$key ? 'selected="selected"':'').' value="' . $key . '">' . $value . '</option>';

                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                     <?php } if(isset($_GET) && isset($_GET['CSEE']) && $_GET['CSEE'] <> '' && $_GET['CSEE'] == 0 &&  isset($_GET['VT'])  && $_GET['VT'] == 0){

                        echo show_alert('Sorry You do not have adequate qualification to proceed with this application. Please review your answers above and try again.','info');
                  }if(isset($_GET) && ((isset($_GET['NT']) && $_GET['NT'] <> '') || (isset($_GET['VT']) && $_GET['VT'] <> '' && $_GET['VT'] == 1 ) )){ ?>
                    <div class="form-group"><label class="col-lg-6 control-label">Entry Category  : <span class="required">*</span></label>

                        <div class="col-lg-6">
                            <select class="form-control" id="entry">
                                <option value="">[ Select Entry Category ]</option>
                                <?php
                                foreach (entry_type_human() as $key=>$value){
                                    if($_GET['type'] == 2 && in_array($key,array(11,12,13,4))) {
                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                    }else if(isset($_GET['VT']) && $_GET['VT'] == 1 && $_GET['type'] == 1 && in_array($key,array('1.5')) ){
                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                    }else if($_GET['type'] == 1 && isset($_GET['NT']) && $_GET['NT'] == 1 && in_array($key,array(1,2,9,10,3))) {
                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                    }else if($_GET['type'] == 3 && in_array($key,array(7,8))) {
                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <div style="font-size: 11px;">Please select highest level of Education you have</div>
                        </div>
                    </div>
<?php } ?>
                    <?php echo form_close(); ?>

                <?php }else{
                    echo show_alert('Application Closed !!','info');
                } ?>
            </div>

        </div>

    </div>

</div>

</div>

    <script>
        $(document).ready(function () {
            $("#type").change(function () {
                var type = $(this).val();
                window.location.href = "<?php echo site_url('registration_start/?type=') ?>"+type;
            });

            $.urlParam = function(name){
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results==null){
                    return null;
                }
                else{
                    return decodeURI(results[1]) || 0;
                }
            }

            $("#CSEE").change(function () {
                var CSEE = $(this).val();
                if(CSEE!= '') {
                    window.location.href = "<?php echo site_url('registration_start/?type=') ?>"+$.urlParam('type')+"&CSEE="+CSEE;
                }
            });

            $("#NT").change(function () {
                var NT = $(this).val();
                if(NT!= '') {
                    window.location.href = "<?php echo site_url('registration_start/?type=') ?>"+$.urlParam('type')+"&CSEE="+$.urlParam('CSEE')+"&NT="+NT;
                }
            });

            $("#VT").change(function () {
                var VT = $(this).val();
                if(VT!= '') {
                    window.location.href = "<?php echo site_url('registration_start/?type=') ?>"+$.urlParam('type')+"&CSEE="+$.urlParam('CSEE')+"&VT="+VT;
                }
            });

            $("#entry").change(function () {
                var entry = $(this).val();
                if(entry!= '') {
                   window.location.href = "<?php echo site_url('application_start/?type=') ?>"+$.urlParam('type')+"&CSEE="+$.urlParam('CSEE')+"&NT="+$.urlParam('NT')+"&entry="+entry;
                }
            });
        })
    </script>
