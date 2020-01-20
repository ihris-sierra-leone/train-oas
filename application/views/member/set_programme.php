<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">

        <h5>Programme Informations</h5>

    </div>
    <div class="ibox-content">

      <?php echo form_open('change_programme/'.$member_info->id, ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="form-group"><label class="col-lg-4 control-label">From :<span
                            class="required"></span></label>

            <div class="col-lg-7">
            <input type="text" value="<?php echo (isset($member_info) ? application_type($member_info->application_type):set_value('application_type')); ?>"
                  class="form-control" name="programme" disabled>
                <?php echo form_error('application_type'); ?>
            </div>
            </div>

          </div><br>

    </div>
      <div class="col-lg-6">
      <div class="row">
              <div class="form-group"><label class="col-lg-4 control-label">To: <span
                  class="required"></span></label>

            <div class="col-lg-7">
              <select class="form-control" name="programme">
                <option value="">[ Select Type ]</option>
                  <?php
                  $sel = (isset($_GET['programme']) ? $_GET['programme'] : '');
                  foreach (application_type() as $key=>$value){
                  echo '<option '.($sel==$key ? 'selected="selected"':'').' value="'.$key.'">'.$value.'</option>';
                   }
                  ?>
                 </select>
            <?php echo form_error('programme'); ?>
            </div>
              </div>
            </div><br>


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
    $(function(){
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

    })
</script>
