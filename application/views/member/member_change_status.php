<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">

        <h5>Membership status &nbsp;|&nbsp;<?php echo $member_info->first_name;?>&nbsp;-&nbsp;<?php echo $member_info->registration_number; ?></h5>

    </div>
    <div class="ibox-content">

        <?php echo form_open(site_url('change_member_status_action/?id=' . $member_info->user_id . '&appID=' . $member_info->id), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="form-group"><label class="col-lg-4 control-label">Current Status: <span
                                    class="required"></span></label>

                        <div class="col-lg-7">
                            <?php
                            foreach (current_status() as $key => $value) {
                                $val = $value;

                            }
                            ?>
                            <input type="text"
                                   value="<?php echo member_type(isset($member_info) ? $member_info->member_type : set_value('stat')); ?>"
                                   class="form-control" name="stat" disabled>
                            <?php echo form_error('status'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group"><label class="col-lg-4 control-label">Corporate: <span
                                    class="required"></span></label>

                        <div class="col-lg-7">
                            <?php
                            $id=$member_info->cooperate;

                            if($id == 0 || $id == null) {
                                $corporate = "Null";

                            }else{
                                $corporate = $this->common_model->get_cooperate($id)->row()->institution_name;
                            }
                            ?>
                            <input type="text"
                                   value="<?php echo $corporate; ?>"
                                   class="form-control" name="stat" disabled>
                            <?php echo form_error('status'); ?>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="col-lg-6">

                    <div class="form-group"><label class="col-lg-4 control-label">Change Status: <span
                                    class="required"></span></label>

                        <div class="col-lg-7">
                            <select class="form-control" name="status" id="privileges"
                                    onclick="craateUserJsObject.ShowPrivileges();">
                                <option value="">[ Select ]</option>
                                <?php
                                foreach (current_status() as $key => $value) {
                                    echo '<option  value="' . $key . '">' . $value . '</option>';

                                }
                                ?>
                            </select>
                            <?php echo form_error('status'); ?>
                        </div>
                    </div>

                    <div class="form-group resources" style=" display: none;"><label class="col-lg-4 control-label">Cooperate
                            Member : <span
                                    class="required ">*</span> </label>

                        <div class="col-lg-7">
                            <select class="form-control" name="cooperate">
                                <option value="">[ Select Cooperate ]</option>
                                <?php
                                $members = $this->common_model->get_cooperate()->result();
                                foreach ($members as $key => $value) {
                                    ?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->institution_name; ?></option>
                                    <?php
                                }
                                ?>
                            </select> <?php echo form_error('cooperate'); ?>
                        </div>
                    </div>

                    <div class="form-group"><label class="col-lg-4 control-label">Level: <span
                                    class="required"></span></label>

                        <div class="col-lg-7">
                            <select name="level" class="form-control">
                                <option value=""> [ Select Level ]</option>
                                <?php
                                $sel = (isset($module_info) ? $module_info->level : set_value('level'));
                                foreach (levels() as $key => $value) {
                                    ?>
                                    <option <?php echo($sel == $key ? 'selected="selected"' : ''); ?>
                                            value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php echo form_error('level'); ?>
                        </div>
                    </div>


            </div>

            <div class="row">
                <div class="form-group">
                    <div class="col-lg-offset-4 col-lg-8">
                        <input class="btn btn-sm btn-success" type="submit" value="Save Changes"/>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>


        </div>
    </div>

</div>
</div>

<script>
    $(function () {
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>
<script>
    $(document).ready(function () {

        $('.mydate_input').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            endDate: "31-12-2004"
        });

        $(".select50").select2({
            theme: 'bootstrap',
            placeholder: '[ Select Country ]',
            allowClear: true
        });
        $(".select51").select2({
            theme: 'bootstrap',
            placeholder: '[ Select Nationality ]',
            allowClear: true
        });

        var Privileges = jQuery('#privileges');
        var select = this.value;
        Privileges.change(function () {
            if ($(this).val() == '8') {
                $('.resources').show();
            }
            else $('.resources').hide();
        });

    })
</script>
