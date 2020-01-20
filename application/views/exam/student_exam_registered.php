<?php

if (isset($result_error)) {
    foreach ($result_error as $key => $value) {
        echo $value;
        echo "<br/>";
    }
}

if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Student exam registered</h5>
    </div>
    <div class="ibox-content">
        <div class="panel panel-default">

            <div class="panel-body">
                <?php echo form_open(current_full_url(), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>

                <div class="col-lg-12">

                    <div class="row">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <label>Year <span class="required">*</span></label>
                                    <select name="ayear" class="form-control">
                                        <option value=""> [ Select ]</option>
                                        <?php
                                        $sel = (isset($programme_credit_info) ? $programme_credit_info->semester : set_value('year_of_study'));
                                        foreach ($year_list as $key => $value) {
                                            ?>
                                            <option <?php echo(isset($_GET['ayear']) ? ($_GET['ayear'] == $value->AYear ? 'selected="selected"' : '') : '') ?>
                                                    value="<?php echo $value->AYear; ?>"><?php echo $value->AYear; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php echo form_error('ayear'); ?>

                                </td>
                                <td>

                                    <label>Session <span class="required">*</span></label>
                                    <select name="session" class="form-control">
                                        <option value=""> [ Select ]</option>
                                        <?php
                                        $sel = (isset($programme_credit_info) ? $programme_credit_info->semester : set_value('year_of_study'));
                                        foreach ($sessions as $key => $value) {
                                            ?>
                                            <option <?php echo(isset($_GET['session']) ? ($_GET['session'] == $value->title ? 'selected="selected"' : '') : '') ?>
                                                    value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php echo form_error('session'); ?>

                                </td>
                                <td>

                                    <input class="btn btn-sm btn-success" style="margin-top: 13%" type="submit"
                                           value="<?php echo isset($programme_credit_info) ? 'Save Changes' : 'View results' ?>"/>


                                </td>

                            </tr>

                        </table>


                    </div>

                </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <?php if (isset($get_student) && count($get_student) > 0) { ?>

            <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive js-dynamitable">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th>Name</th>
                    <th>RegNO</th>
                    <th>Courses</th>
                    <th>Amount Paid</th>
                </tr>
                <tr>
                    <th><input class="js-filter  form-control" type="text" value=""></th>
                    <th><input class="js-filter  form-control" type="text" value=""></th>
                    <th><input class="js-filter  form-control" type="text" value=""></th>
                    <th><input class="js-filter  form-control" type="text" value=""></th>
                    <th><input class="js-filter  form-control" type="text" value=""></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($get_student as $key => $value) {
                    $regno = $value->registration_number;
                    $courses = $this->db->query("select GROUP_CONCAT(DISTINCT c.shortname) as courses FROM student_exam_registered e, courses c where e.registration_number='$regno' and c.id=e.course_id and exam_year='" . $_GET['ayear'] . "' and exam_semester='" . $_GET['session'] . "'  ")->row()->courses;

                    $get_user = $this->db->get_where('students', array('registration_number' => $regno))->row();
                    if ($get_user) {
                        $exam_amount = $this->db->get_where('examinations_payment', array('user_id' => $get_user->user_id, 'academic_year' => $_GET['ayear'], 'session' => $_GET['session']))->row();
                    }
//                    $student_list = $this->db->query("
//            SELECT registration_number, GROUP_CONCAT(DISTINCT coursecode) as courses FROM student_exam_registered $where
//            ")->result();
                    ?>
                    <tr>
                        <td><?php echo($key + 1) ?></td>
                        <td><?php echo $get_user->first_name.' '.$get_user->other_names.' '.$get_user->surname; ?></td>
                        <td><?php echo $value->registration_number; ?></td>
                        <td><?php echo $courses; ?></td>
                        <td><?php echo(isset($exam_amount) ? ($exam_amount != '' ? $exam_amount->amount : '') : ''); ?></td>
                        <!--                        <td>-->
                        <!--                            <a class="edit-modal" data-id="-->
                        <?php //echo $value->id; ?><!--"-->
                        <!--                               data-regno="-->
                        <?php //echo $value->registration_number ?><!--"-->
                        <!--                               data-score="--><?php //echo $value->score_marks ?><!--"-->
                        <!--                            <span class="fa fa-pencil-square-o"></span> Edit</a> |-->
                        <!--                            <a style="color: brown" class="delete-modal" data-id="-->
                        <?php //echo $value->id; ?><!--">-->
                        <!--                                <span class="fa fa-trash-o"></span> Delete</a>-->
                        <!--                        </td>-->

                    </tr>
                    <?php
                }
                ?>
                </tbody>

            </table>
        <?php } else { ?>

            <!--            <div class="alert alert-warning">NO RESULT FOUND FOR YOUR SEARCH</div>-->

        <?php } ?>
    </div>
</div>


<!-- Edit Item Modal -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModaledit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_class_update" action="<?php echo site_url('update_student_score') ?>" method="post">
                    <div class="form-group" style="display:none">
                        <label class="control-label" for="id">ID</label>
                        <input type="text" id="did" name="did" class="form-control" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="regno">Registration Number:</label>
                        <input type="text" id="regno" name="regno" class="form-control" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="title">Score Marks:</label>
                        <input type="text" id="score" name="score" class="form-control" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
                <button type="submit" id="edit" class="btn btn-success pull-left crud-submit-edit">Save Changes</button>
                <button type="button" id="update_feedback" style="display: none" class="btn btn-success pull-right">
                    <span id="update_feedback_msg"></span></button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Item Modal -->
<div class="modal fade" id="myModaldelete" tabindex="-1" role="dialog" aria-labelledby="myModaldelete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="title"></h4>
            </div>
            <div class="modal-body">

                <form id="form_expenses_delete" action="<?php echo site_url('delete_information'); ?>" method="post">
                    <div class="form-group" style="display: none">
                        <label class="control-label" for="id">ID</label>
                        <input type="text" id="id" name="id" class="form-control"/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <h4>Are you sure you want to delete this information??</h4>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">No</button>
                <button type="submit" id="submit_form_delete_expenses"
                        class="btn btn-success pull-left ">Confirm
                </button>
                <button type="button" id="delete_feedback" style="display: none" class="btn btn-success pull-right">
                    <span id="delete_feedback_msg"></span></button>
            </div>
        </div>
    </div>
</div>


<script>
    $("#programme").on('change', function () {
        var programme_id = $(this).val();
        $.ajax({
            type: 'POST',
            url: "<?php echo site_url('get_courses') ?>",
            data: {
                programme: programme_id,
            },
            success: function (data) {
//                alert(data);exit;
                $('#courses').html(data);
            }
        });
    });

    $(document).on('click', '.edit-modal', function () {
        $('#footer_action_button').text(" Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('edit');
        $('.modal-title').text('Update student score marks');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        $('#did').val($(this).data('id'));
        $('#regno').val($(this).data('regno'));
        $('#score').val($(this).data('score'));
        $('#myModaledit').modal('show');

    });

    $(document).on('click', '.delete-modal', function () {
        $('#footer_action_button').text(" Update");
        $('#footer_action_button').addClass('glyphicon-check');
        $('#footer_action_button').removeClass('glyphicon-trash');
        $('.actionBtn').addClass('btn-success');
        $('.actionBtn').removeClass('btn-danger');
        $('.actionBtn').addClass('delete');
        $('.modal-title').text('Delete Information');
        $('.deleteContent').hide();
        $('.form-horizontal').show();
        $('#id').val($(this).data('id'));
        $('#myModaldelete').modal('show');


    });

    //    function to update student score
    $('#edit').on('click', function (e) {
//        alert('am clicked');
        e.preventDefault();
        var update_class_form = $('#form_class_update').serializeArray();
        var url = $('#form_class_update').attr('action');
//        alert(url); exit;
        $.post(url, update_class_form, function (data) {
            alert(data);
            exit;
            if (data == 1) {
                $('#update_feedback').fadeOut('2000', function () {
                    $(this).show();
                });
                $('#update_feedback_msg').text('Record Updated Successfully.......!');
                setTimeout(location.reload.bind(location), 1000);
            } else {
//                    do something else here
            }
        });
    });

    //    delete record script
    $('#submit_form_delete_expenses').on('click', function (e) {
        e.preventDefault();
        var form_expenses_delete = $('#form_expenses_delete').serializeArray();
        var url = $('#form_expenses_delete').attr('action');
        $.post(url, form_expenses_delete, function (data) {
            alert(data);
            if (data == 1) {
                $('#delete_feedback').fadeOut('2000', function () {
                    $(this).show();
                });
                $('#delete_feedback_msg').text('Record deleted successfully.......!');
                setTimeout(location.reload.bind(location), 1000);
            } else {
//                    do something else here
            }
        });
    });


</script>