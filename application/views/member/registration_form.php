<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">

        <h5>Personal Informations</h5>

    </div>
    <div class="ibox-content">

      <?php echo form_open(site_url('change_stata/?id='.$member_info->user_id.'&appID='.$member_info->id), ' class="form-horizontal ng-pristine ng-valid"') ?>

      <div class="row">
      <div class="col-lg-6">
        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">First Name : <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->FirstName:set_value('firstname')); ?>"
                         class="form-control" name="firstname">
                  <?php echo form_error('firstname'); ?>
              </div>
          </div>

        </div><br>
        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Middle Name : <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->MiddleName:set_value('middlename')); ?>"
                         class="form-control" name="middlename">
                  <?php echo form_error('middlename'); ?>
              </div>
          </div>
        </div><br>
        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Last Name : <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->LastName:set_value('lsstname')); ?>"
                         class="form-control" name="lastname">
                  <?php echo form_error('lastname'); ?>
              </div>
          </div>
        </div><br>
        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Gender: <span
                          class="required"></span></label>
          <div class="col-lg-7">
            <input type="text" value="<?php echo (isset($member_info) ? $member_info->Gender:set_value('gender')); ?>"
             class="form-control" name="gender" disabled>
            <?php echo form_error('gender'); ?>
            </div>
          </div>
        </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Birth Date : <span
                          class="required "></span></label>

              <div class="col-lg-7">
                  <input type="text" placeholder="DD-MM-YYYY"
                         value="<?php echo (isset($member_info) ? $member_info->dob:set_value('dob')); ?>"
                         class="form-control  mydate_input" name="dob" disabled>
                  <?php echo form_error('dob'); ?>
              </div>
          </div>
        </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Place of birth: <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" name ="birth_place" value="<?php echo (isset($member_info) ? $member_info->birth_place:set_value('birth_place')); ?>"
                         class="form-control" name="birth_place" disabled>
                  <?php echo form_error('birth_place'); ?>
              </div>
          </div>
        </div><br>

  </div>
    <div class="col-lg-6">

      <div class="row">
        <div class="form-group"><label class="col-lg-4 control-label">Marital Status: <span
                        class="required"></span></label>

            <div class="col-lg-7">
              <input type="text" value="<?php echo (isset($member_info) ? get_marital_status($member_info->marital_status):set_value('marital_status')); ?>"
                     class="form-control" name="marital_status">
            <?php echo form_error('marital_status'); ?>
            </div>

        </div>
      </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Nationality: <span
                          class="required"></span></label>

                          <div class="col-lg-7">
                              <input type="text" value="<?php echo (isset($member_info) ? get_country($member_info->Nationality):set_value('nationality')); ?>"
                                     class="form-control" name="email">
                              <?php echo form_error('email'); ?>
                          </div>
          </div>
        </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Disability: <span
                          class="required"></span></label>

            <div class="col-lg-7">
              <input type="text" value="<?php echo (isset($member_info) ? get_disability($member_info->Disability):set_value('disability')); ?>"
                   class="form-control" name="disability">
                <?php echo form_error('disability'); ?>
              </div>
          </div>
        </div><br>
        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Email : <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->Email:set_value('email')); ?>"
                         class="form-control" name="email" disabled>
                  <?php echo form_error('email'); ?>
              </div>
          </div>
        </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Address: <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->Mobile1:set_value('mobile')); ?>"
                         class="form-control" name="mobile">
                  <?php echo form_error('mobile'); ?>
              </div>
          </div>
        </div><br>

        <div class="row">
          <div class="form-group"><label class="col-lg-4 control-label">Mobile 2: <span
                          class="required"></span></label>

              <div class="col-lg-7">
                  <input type="text" value="<?php echo (isset($member_info) ? $member_info->Mobile2:set_value('mobile2')); ?>"
                         class="form-control" name="mobile2" disabled>
                  <?php echo form_error('mobile2'); ?>
              </div>
          </div>
        </div><br>

      </div>
    </div>

    <br><br>
        <hr style="clear:both">
        <h5>Application Informations</h5>
        <hr>
        <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="form-group"><label class="col-lg-4 control-label">Application Type: : <span
                            class="required"></span></label>

            <div class="col-lg-7">
            <input type="text" value="<?php echo (isset($member_info) ? application_type($member_info->application_type):set_value('application_type')); ?>"
                  class="form-control" name="application_type" disabled>
                <?php echo form_error('application_type'); ?>
            </div>
            </div>

          </div><br>
          <div class="row">
            <div class="form-group"><label class="col-lg-4 control-label">Have you completed O' Level (CSEE)?  : <span
                            class="required"></span></label>

          <div class="col-lg-7">
              <input type="text" value="<?php echo (isset($member_info) ? yes_no($member_info->CSEE):set_value('CSEE')); ?>"
                 class="form-control" name="CSEE" disabled>
                <?php echo form_error('mobile2'); ?>
          </div>
            </div>
          </div><br>

    </div>
      <div class="col-lg-6">
          <div class="row">
            <div class="form-group"><label class="col-lg-4 control-label">Entry Category: <span
                            class="required"></span></label>

                            <div class="col-lg-7">
                              <input type="text" value="<?php echo (isset($member_info) ? entry_type_human($member_info->entry_category):set_value('entry_category')); ?>"
                                   class="form-control" name="entry_category" disabled>
                                  <?php echo form_error('entry_category'); ?>
                              </div>
            </div>
          </div><br>


        </div>
      </div>


      <br><br>
          <hr style="clear:both">
          <h5>Application Status</h5>
          <hr>
          <div class="row">
          <div class="col-lg-6">
            <div class="row">
              <div class="form-group"><label class="col-lg-4 control-label">Current Status: <span
                  class="required"></span></label>

            <div class="col-lg-7">
              <?php
              foreach (current_status() as $key=>$value){
               $val = $value;

                }
                ?>
              <input type="text" value="<?php echo current_status((isset($member_info) ? $member_info->status:set_value('stat'))); ?>"
                     class="form-control" name="stat" disabled>
            <!--   <select class="form-control" name="status">
                <option value="">[ Select  ]</option>
                <?php
                //foreach (change_status() as $key=>$value){
                //  echo '<option '.($sel==$key ? 'selected="selected"':'').' value="' . $key . '">' . $value . '</option>';

                //  }
                  ?>
              </select> -->
            <?php echo form_error('status'); ?>
            </div>
              </div>
            </div><br>
    </div>
        <div class="col-lg-6">

          <div class="row">
            <div class="form-group"><label class="col-lg-4 control-label">Change Status: <span
                class="required"></span></label>

          <div class="col-lg-7">
            <select class="form-control" name="status">
              <option value="">[ Select  ]</option>
              <?php
              foreach (current_status() as $key=>$value){
               echo '<option  value="' . $key . '">' . $value . '</option>';

               }
                ?>
            </select>
          <?php echo form_error('status'); ?>
          </div>
            </div>

          </div><br>
          <div class="form-group"><label class="col-lg-4 control-label">Level: <span
                class="required"></span></label>

          <div class="col-lg-7">
           <select name="level"  class="form-control">
                    <option value=""> [ Select Level ]</option>
                    <?php
                    $sel = (isset($module_info) ? $module_info->level: set_value('level'));
                    foreach(levels() as $key=>$value){
                        ?>
                        <option <?php echo ($sel == $key ? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
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
