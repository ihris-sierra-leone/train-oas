<?php
$Paid_amount = $this->applicant_model->get_paid_amount(current_user()->applicant_id);
$amount_required = 100;
if($Paid_amount<$amount_required)
{
    redirect(site_url('applicant_payment'), 'refresh');

}

?>

<script src="<?php echo base_url(); ?>media/js/jquery.chained.remote.js"></script>
<input type="hidden" name="application_type" id="application_type" value="<?php echo $APPLICANT->application_type; ?>"/>
<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">
            <h5>Choose Programme</h5></div>
    </div>

    <div class="ibox-content">

        <div style="margin-bottom: 15px; color: green; font-weight: bold;">

            Make sure you have read our <a href="<?php echo ADMISSION_REQUIREMENT; ?>"
                                           style    ="color: red; text-decoration: underline;">Admission Requirement</a>
            before Select/Choose Programme you wish to study.
        </div>
        <?php echo form_open(current_full_url(), ' class="form"') ?>
        <div class="form-group">
            <label class="control-label">Choose program: <span class="required">*</span></label>
            <?php
            $choice1_value = set_value('choice1', (isset($mycoice) ? $mycoice->choice1 : ''));

            $choice1_value_dept = get_value('programme', array('Code' => $choice1_value), 'Departmentid');

            ?>
            <input type="hidden" value="<?php echo $choice1_value; ?>" id="choice1_value" name="selected1"/>
            <div class="col-lg-12">
                <div class="col-md-5">
                    <select id="choice1" name="choice1" class="form-control">
                        <option value="">[ Select Programme ]</option>
                        <?php foreach ($programme as $key => $value) { ?>
                            <option value="<?php echo $value->Code; ?>"><?php echo $value->Name; ?></option>
                        <?php } ?>
                    </select>
                </div>

<!--                <div class="col-md-7">-->
<!--                    <select name="choice1" id="choice1" class="form-control">-->
<!--                        <option value="">[ First Choice ]</option>-->
<!--                    </select>-->
<!--                    --><?php //echo form_error('choice1'); ?>
<!--                </div>-->

            </div>
            <div style="clear: both;"></div>
        </div>

        <?php
        // $choice2_value =  set_value('choice2',(isset($mycoice) ? $mycoice->choice2 :''));
        // $choice2_value_dept = get_value('programme',array('Code'=>$choice2_value),'Departmentid');

        ?>
        <!--  <input type="hidden" value="<?php // echo $choice2_value; ?>" id="choice2_value" name="selected1"/>
         <div class="form-group">
            <label class="control-label">Second Choice: </label>

            <div class="col-lg-12">
                <div class="col-md-5">
                    <select id="choice2_department" name="department" class="form-control">
                        <option value="">[ Select School/Department  ]</option>
                        <?php //foreach ($department as $key=>$value){ ?>
                            <option <?php //echo ($choice2_value_dept == $value->id ? 'selected="selected"':'' ); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                        <?php //} ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <select name="choice2" id="choice2" class="form-control">
                        <option value="">[ Second Choice ]</option>
                    </select>
                    <?php // echo form_error('choice2'); ?>
                </div>

            </div>
            <div style="clear: both;"></div>
        </div> -->

        <?php
        // $choice3_value =  set_value('choice3',(isset($mycoice) ? $mycoice->choice3 :''));
        // $choice3_value_dept = get_value('programme',array('Code'=>$choice3_value),'Departmentid');

        ?>
        <!-- <input type="hidden" value="<?php //echo $choice3_value; ?>" id="choice3_value" name="selected1"/>
        <div class="form-group">
            <label class="control-label">Third Choice: </label>

            <div class="col-lg-12">
                <div class="col-md-5">
                    <select id="choice3_department" name="department" class="form-control">
                        <option value="">[ Select School/Department  ]</option>
                        <?php // foreach ($department as $key=>$value){ ?>
                            <option <?php //echo ($choice3_value_dept == $value->id ? 'selected="selected"':'' ); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                        <?php //} ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <select name="choice3" id="choice3" class="form-control">
                        <option value="">[ Third Choice ]</option>
                    </select>
                    <?php // echo form_error('choice3'); ?>
                </div>

            </div>
            <div style="clear: both;"></div>
        </div> -->


        <?php
        // $choice4_value =  set_value('choice4',(isset($mycoice) ? $mycoice->choice4 :''));
        // $choice4_value_dept = get_value('programme',array('Code'=>$choice4_value),'Departmentid');

        ?>
        <!-- <input type="hidden" value="<?php // echo $choice4_value; ?>" id="choice4_value" name="selected1"/>
        <div class="form-group">
            <label class="control-label">Fourth Choice: </label>

            <div class="col-lg-12">
                <div class="col-md-5">
                    <select id="choice4_department" name="department" class="form-control">
                        <option value="">[ Select School/Department  ]</option>
                        <?php //foreach ($department as $key=>$value){ ?>
                            <option <?php // echo ($choice4_value_dept == $value->id ? 'selected="selected"':'' ); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                        <?php //} ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <select name="choice4" id="choice4" class="form-control">
                        <option value="">[ Third Choice ]</option>
                    </select>
                    <?php // echo form_error('choice4'); ?>
                </div>

            </div>
            <div style="clear: both;"></div>
        </div> -->


        <?php
        // $choice5_value =  set_value('choice5',(isset($mycoice) ? $mycoice->choice5 :''));
        // $choice5_value_dept = get_value('programme',array('Code'=>$choice5_value),'Departmentid');

        ?>
        <!--  <input type="hidden" value="<?php // echo $choice5_value; ?>" id="choice5_value" name="selected1"/>
         <div class="form-group">
            <label class="control-label">Fifth Choice: </label>

            <div class="col-lg-12">
                <div class="col-md-5">
                    <select id="choice5_department" name="department" class="form-control">
                        <option value="">[ Select School/Department  ]</option>
                        <?php // foreach ($department as $key=>$value){ ?>
                            <option <?php //echo ($choice5_value_dept == $value->id ? 'selected="selected"':'' ); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                        <?php // } ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <select name="choice5" id="choice5" class="form-control">
                        <option value="">[ Fifth Choice ]</option>
                    </select>
                    <?php // echo form_error('choice5'); ?>
                </div>

            </div>
            <div style="clear: both;"></div>
        </div> -->


        <div style="clear: both;"></div>
        <?php if ($APPLICANT->status == 0) { ?>
            <div class="form-group" style="margin-top: 10px; border-top: 1px solid #ccc; padding-top: 20px;">
                <div class="col-lg-offset-4 col-lg-6">
                    <input class="btn btn-sm btn-success" type="submit"
                           value="<?php echo(!is_section_used('PROGRAMME', $APPLICANT_MENU) ? 'Save ' : 'Edit '); ?>Information"/>
                </div>
            </div>
        <?php }else{ ?>
            <script>
                disable_edit();
            </script>
        <?php } ?>

        <?php echo form_close(); ?>

        <div style="clear: both;"></div>

    </div>


</div>

<script>

//    alert('gsgs');

    $("#choice1_department").on('change', function () {
//    alert('hahaha');
        var department = $(this).val();
        var application = $('#application_type').val();
//        alert(department);exit;
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url('popup/get_programme_selection') ?>",
            data: {
                department_id: department,
                application_type: application
            },
            success: function (data) {
//                alert(data);exit;
                $('#choice1').html(data);
            }
        });
    });
</script>


<script>

    $(document).ready(function () {

        $(".selection").select2({
            theme: 'bootstrap',
            allowClear: true,
            placeholder: '[ Select Programme ]'

        });


        $("#choice1").remoteChained({
            parents: "#choice1_department",
            url: "<?php echo site_url('popup/get_programmes') ?>",
            clear: true,
            loading: "Loading.....",
            depends: "#application_type",
            default: $("#choice1_value").val()
        });

        $("#choice2").remoteChained({
            parents: "#choice2_department",
            url: "<?php echo site_url('popup/get_programmes') ?>",
            clear: true,
            loading: "Loading.....",
            depends: "#application_type",
            default: $("#choice2_value").val()
        });

        $("#choice3").remoteChained({
            parents: "#choice3_department",
            url: "<?php echo site_url('popup/get_programmes') ?>",
            clear: true,
            loading: "Loading.....",
            depends: "#application_type",
            default: $("#choice3_value").val()
        });

        $("#choice4").remoteChained({
            parents: "#choice4_department",
            url: "<?php echo site_url('popup/get_programmes') ?>",
            clear: true,
            loading: "Loading.....",
            depends: "#application_type",
            default: $("#choice4_value").val()
        });

        $("#choice5").remoteChained({
            parents: "#choice5_department",
            url: "<?php echo site_url('popup/get_programmes') ?>",
            clear: true,
            loading: "Loading.....",
            depends: "#application_type",
            default: $("#choice5_value").val()
        });


        $("#choice1_department").trigger("change");
        $("#choice2_department").trigger("change");
        $("#choice3_department").trigger("change");
        $("#choice4_department").trigger("change");
        $("#choice5_department").trigger("change");


    });
</script>
