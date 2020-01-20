<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">

        <h5>Annual Subscription Fee</h5>
<!--        <a class="btn btn-small btn-sm btn-success pull-right" href="javascript:void(0);" id="fake_pay">Fake Payment</a>-->


    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-12">
                <?php
                $applicant_id = $APPLICANT->id;
                $uid = $CURRENT_USER->id;
                $ActiveYear = $this->common_model->get_academic_year()->row()->AYear;
                //$date = $this->common_model->get_expire_date($uid)->row();
                $reference=REFERENCE_START.'1'.$applicant_id;
                $reference_annual=REFERENCE_START.'2'.$applicant_id;
                $date=$this->db->query("select * from fee_statement where (reference='$reference' or reference='$reference_annual' ) and academic_year=".date('Y'))->row();
                if(!$date)
                {
                    $date = $this->common_model->get_expire_date($uid)->row();
                }

                //                var_dump($date);exit;
                // echo $expire_date;exit;
                $studentinfo = $this->db->get_where('students', array('user_id' => $uid))->row();
                $member_type = $studentinfo->member_type;
//                $applicant_id = $APPLICANT->id;
                $annual_amount = $this->db->get_where('exam_fee', array('member_category' => $member_type))->row()->annual_amount;
//                $payment_transaction = $this->db->where(array('user_id' => $uid, 'academic_year' => $ActiveYear))->get("annual_fees")->result();
                $annual = $this->db->query("select * from fee_statement where reference='$reference' or reference='$reference_annual'  ")->result();
                if(!$annual)
                {
                    $annual = $this->db->query("select * from annual_fees where user_id='$uid' ")->result();
                }
                ?>

                <?php

                if($date){
                    $start_date = date("Y-m-d", strtotime($date->createdon));
                    $expire_date = date('Y-m-d', strtotime('+1 year', strtotime($start_date)));
                    $today_date = date('Y-m-d');

                    if ($today_date > $expire_date) { ?>
<!--                        //anadaiwa-->
                        <div style="margin-bottom: 15px; color: green; font-weight: bold;">
                            <b style="color:black">Current session study status:</b>&nbsp;&nbsp;<?php echo "<code>NOT PAID</code>"; ?><br><br>
                            Payment amount required is TZS : <?php echo number_format(($annual_amount), 2); ?>. Please pay
                            this
                            amount only
                        </div>
                        <?php $reffNo = REFERENCE_START . '2' . $applicant_id; ?>
                        <h3>Your Reference Number for payment is : <?php echo $reffNo; ?></h3>

                        <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
                            <thead>
                            <th>SNO</th>
                            <th>Date Paid</th>
                            <th>Amount</th>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($annual as $key => $value) {
                            ?>
                            <tr>
                                <td><?php echo $a; ?></td>
                                <td><?php echo $value->createdon; ?></td>
                                <td><?php echo $value->amount + $value->charges; ?></td>
                                <?php
                                $a++;
                                }
                                ?>
                            </tbody>
                        </table>

                        <div style="padding-top: 20px;">
                            <h2>Choose Method to Pay</h2>
                            <div class="clearfix">

                                <div class="col-md-4" style="text-align: center;">
                                    <img style="width: 200px; height: 80px;"
                                         src="<?php echo base_url() ?>/icon/tigo_pesa.png" class="pay_method1"
                                         title="tigopesa">
                                    <div style="margin-top: 10px;"><input type="radio" value="tigopesa"
                                                                          name="pay_method" class="pay_method"/></div>
                                </div>
<!--                                <div class="col-md-4" style="text-align: center;">-->
<!--                                    <img style="width: 170px; height: 80px;"-->
<!--                                         src="--><?php //echo base_url() ?><!--/icon/mpesa.jpg" class="pay_method1"-->
<!--                                         title="mpesa">-->
<!--                                    <div style="margin-top: 10px;"><input type="radio" value="mpesa" name="pay_method"-->
<!--                                                                          class="pay_method"/></div>-->
<!---->
<!--                                </div>-->

                                <div class="col-md-3" style="text-align: center;">
                                 <img style="width: 170px; height: 80px;" src="<?php  echo base_url() ?>/icon/airtel.jpg" class="pay_method1" title="airtel" >
                                 <div style="margin-top: 10px;"><input type="radio" value="airtel" name="pay_method" class="pay_method"/></div>
                             </div>

                                <div class="col-md-3" style="text-align: center;">
                                    <img style="width: 170px; height: 80px;" src="<?php  echo base_url() ?>/icon/halopesa3.jpg" class="pay_method1" title="halopesa" >
                                    <div style="margin-top: 10px;"><input type="radio" value="halopesa" name="pay_method" class="pay_method"/></div>
                                </div>



                            </div>

                            <div id="tigopesa" style="display: none;">
                                <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Tigo
                                    Pesa : Follow steps to pay
                                </div>
                                <div style="padding-left: 100px; font-size: 15px;">
                                    1. Dial <b>*150*01#</b><br/>
                                    2. Select 4 <b>" Pay Bill "</b> <br/>
                                    3. Select 3 <b>" Enter Busness Number "</b><br/>
                                    4. Enter <b>400700</b> <br/>
                                    5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                    6. Enter amount <br/>
                                    7. Enter Password <b>" Enter your account Password "</b>
                                </div>
                            </div>

                            <div id="mpesa" style="display: none;">
                                <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">M-Pesa
                                    : Follow steps to pay
                                </div>
                                <div style="padding-left: 100px; font-size: 15px;">
                                    1. Dial <b>*150*00#</b><br/>
                                    2. Select 4 <b>" Pay Bill "</b> <br/>
                                    3. Select 4 <b>" Enter Busness Number "</b><br/>
                                    4. Enter <b>400700</b> <br/>
                                    5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                    6. Enter amount <br/>
                                    7. Enter Password <b>" Enter your account Password "</b><br/>
                                    8. Enter 1 <b>" To agree "</b>
                                </div>
                            </div>

                            <div id="airtel" style="display: none;" >
                               <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Airtel Money : Follow steps to pay</div>
                               <div style="padding-left: 100px; font-size: 15px;">
                                   1. Dial <b>*150*60#</b><br/>
                                   2. Select  5  <b>" Make Payments "</b> <br/>
                                   3. Select 3  <b>" Enter Busness Name "</b><br/>
                                   4. Enter <b>400700</b> <br/>
                                   5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                   6. Enter amount  <b>" Enter <?php echo number_format($annual); ?> "</b> <br/>
                                   7. Enter Password  <b>" Enter your account Password "</b><br/>
                               </div>
                           </div>
                            <div id="halopesa" style="display: none;">
                                <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Halo
                                    Pesa : Follow steps to pay
                                </div>
                                <div style="padding-left: 100px; font-size: 15px;">
                                    1. Dial <b>*150*88#</b><br/>
                                    2. Select 4 <b>" Pay Bill "</b> <br/>
                                    3. Select 3 <b>" Enter Busness Number "</b><br/>
                                    4. Enter <b>400700</b> <br/>
                                    5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                    6. Enter amount <br/>
                                    7. Enter Password <b>" Enter your account Password "</b>
                                </div>
                            </div>



                        </div>





                  <?php } else{
//                        hadaiwi
                        $echo = "<code>Paid</code>";
                        ?>
                        <div class="row">
                            <div class="col-md-8">
                                <b>Current session study status:</b>&nbsp;&nbsp;
                                <?php if (isset($echo)) {
                                    echo $echo;
                                } ?>
                            </div>
                        </div>
                        <hr>
                        <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
                            <thead>
                            <th>SNO</th>
                            <th>Date Paid</th>
                            <th>Amount</th>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($annual as $key => $value) {
                            ?>
                            <tr>
                                <td><?php echo $a; ?></td>
                                <td><?php echo $value->createdon; ?></td>
                                <td><?php echo $value->amount + $value->charges; ?></td>
                                <?php
                                $a++;
                                }
                                ?>
                            </tbody>
                        </table>


                   <?php }
                }else{
//                    anadaiwa
                    ?>

                    <div style="margin-bottom: 15px; color: green; font-weight: bold;">
                        <b style="color:black">Current session study status:</b>&nbsp;&nbsp;<?php echo "<code>NOT PAID</code>"; ?><br><br>
                        Payment amount required is TZS : <?php echo number_format(($annual_amount), 2); ?>. Please pay
                        this
                        amount only
                    </div>
                    <?php $reffNo = REFERENCE_START . '2' . $applicant_id; ?>
                    <h3>Your Reference Number for payment is : <?php echo $reffNo; ?></h3>


                    <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
                        <thead>
                        <th>SNO</th>
                        <th>Date Paid</th>
                        <th>Amount</th>
                        </thead>
                        <tbody>
                        <?php
                        $a = 1;
                        foreach ($annual as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $a; ?></td>
                            <td><?php echo $value->createdon; ?></td>
                            <td><?php echo $value->amount + $value->charges; ?></td>
                            <?php
                            $a++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <div style="padding-top: 20px;">
                        <h2>Choose Method to Pay</h2>
                        <div class="clearfix">

                            <div class="col-md-4" style="text-align: center;">
                                <img style="width: 200px; height: 80px;"
                                     src="<?php echo base_url() ?>/icon/tigo_pesa.png" class="pay_method1"
                                     title="tigopesa">
                                <div style="margin-top: 10px;"><input type="radio" value="tigopesa"
                                                                      name="pay_method" class="pay_method"/></div>
                            </div>
                            <div class="col-md-4" style="text-align: center;">
                                <img style="width: 170px; height: 80px;"
                                     src="<?php echo base_url() ?>/icon/mpesa.jpg" class="pay_method1"
                                     title="mpesa">
                                <div style="margin-top: 10px;"><input type="radio" value="mpesa" name="pay_method"
                                                                      class="pay_method"/></div>

                            </div>

                             <div class="col-md-3" style="text-align: center;">
                                 <img style="width: 170px; height: 80px;" src="<?php  echo base_url() ?>/icon/airtel.jpg" class="pay_method1" title="airtel" >
                                 <div style="margin-top: 10px;"><input type="radio" value="airtel" name="pay_method" class="pay_method"/></div>
                             </div>

                            <div class="col-md-3" style="text-align: center;">
                                <img style="width: 170px; height: 80px;" src="<?php  echo base_url() ?>/icon/halopesa3.jpg" class="pay_method1" title="halopesa" >
                                <div style="margin-top: 10px;"><input type="radio" value="halopesa" name="pay_method" class="pay_method"/></div>
                            </div>



                        </div>

                        <div id="tigopesa" style="display: none;">
                            <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Tigo
                                Pesa : Follow steps to pay
                            </div>
                            <div style="padding-left: 100px; font-size: 15px;">
                                1. Dial <b>*150*01#</b><br/>
                                2. Select 4 <b>" Pay Bill "</b> <br/>
                                3. Select 3 <b>" Enter Busness Number "</b><br/>
                                4. Enter <b>400700</b> <br/>
                                5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                6. Enter amount <br/>
                                7. Enter Password <b>" Enter your account Password "</b>
                            </div>
                        </div>

                        <div id="mpesa" style="display: none;">
                            <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">M-Pesa
                                : Follow steps to pay
                            </div>
                            <div style="padding-left: 100px; font-size: 15px;">
                                1. Dial <b>*150*00#</b><br/>
                                2. Select 4 <b>" Pay Bill "</b> <br/>
                                3. Select 4 <b>" Enter Busness Number "</b><br/>
                                4. Enter <b>400700</b> <br/>
                                5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                6. Enter amount <br/>
                                7. Enter Password <b>" Enter your account Password "</b><br/>
                                8. Enter 1 <b>" To agree "</b>
                            </div>
                        </div>

                         <div id="airtel" style="display: none;" >
                               <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Airtel Money : Follow steps to pay</div>
                               <div style="padding-left: 100px; font-size: 15px;">
                                   1. Dial <b>*150*60#</b><br/>
                                   2. Select  5  <b>" Make Payments "</b> <br/>
                                   3. Select 3  <b>" Enter Busness Name "</b><br/>
                                   4. Enter <b>400700</b> <br/>
                                   5. Enter Reference Number <b>" Enter <?php //echo $reffNo; ?>"</b><br/>
                                   6. Enter amount  <b>" Enter <?php //echo number_format($annual); ?> "</b> <br/>
                                   7. Enter Password  <b>" Enter your account Password "</b><br/>
                               </div>
                           </div>

                        <div id="halopesa" style="display: none;">
                            <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Halo
                                Pesa : Follow steps to pay
                            </div>
                            <div style="padding-left: 100px; font-size: 15px;">
                                1. Dial <b>*150*88#</b><br/>
                                2. Select 4 <b>" Pay Bill "</b> <br/>
                                3. Select 3 <b>" Enter Busness Number "</b><br/>
                                4. Enter <b>400700</b> <br/>
                                5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                6. Enter amount <br/>
                                7. Enter Password <b>" Enter your account Password "</b>
                            </div>
                        </div>



                    </div>

                    <?php
                }


                ?>



                <input type="hidden" value="" id="paid_all"/>



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

    })
</script>

<script>
    $(document).ready(function () {
        $("#fake_pay").click(function () {
            $(this).html('Please wait.....');
            $.ajax({
                url: '<?php echo site_url('applicant/fakepay') ?>',
                type: 'POST'
            });
        });


        $(".pay_method1").click(function () {
            var title = $(this).attr('title');
            $('input:radio[name=pay_method][value=' + title + ']').prop("checked", "checked").change();
        });


        $(".pay_method").change(function () {
            var pay_method = $(this).val();
            if (pay_method == 'tigopesa') {
                $("#tigopesa").show();
                $("#airtel").hide();
                $("#mpesa").hide();
                $("#halopesa").hide();
            } else if (pay_method == 'airtel') {
                $("#airtel").show();
                $("#tigopesa").hide();
                $("#mpesa").hide();
                $("#halopesa").hide();
            } else if (pay_method == 'mpesa') {
                $("#mpesa").show();
                $("#tigopesa").hide();
                $("#airtel").hide();
                $("#halopesa").hide();
            }else if(pay_method =='halopesa')
            {
                $("#halopesa").show();
                $("#mpesa").hide();
                $("#tigopesa").hide();
                $("#airtel").hide();
            }
        });

        <?php if(!is_section_used('PAYMENT', $APPLICANT_MENU)){ ?>
        setInterval(function () {
            $.ajax({
                type: "post",
                url: "<?php echo site_url('is_annual_paid') ?>",
                datatype: "html",
                success: function (data) {
                    if (data == '1') {
                        window.location.reload();
                    }
                    //do something with response data
                }
            });
        }, 3000)
        <?php } ?>
    });
</script>
