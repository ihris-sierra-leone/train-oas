<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Search Member</h5>

    </div>
    <div class="ibox-content">
      <?php echo form_open('member_registration_form', ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Membership Number / Name : <span
                    class="required"></span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : ''); ?>" class="form-control" name="key">
                <?php echo form_error('key'); ?>
            </div>
        </div>


        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-8">
                <input class="btn btn-sm btn-success" type="submit" value="Search"/>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>
