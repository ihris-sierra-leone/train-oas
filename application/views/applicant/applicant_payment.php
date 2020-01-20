<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">
            <h5>Application and Registration Fee Payment</h5>
<!--            --><?php //if (!is_section_used('PAYMENT', $APPLICANT_MENU)) { ?>
<!--                <a class="btn btn-small btn-sm btn-success pull-right" href="javascript:void(0);" id="fake_pay">Fake-->
<!--                    Payment</a>-->
<!--            --><?php //} ?>

        </div>
    </div>

    <div class="ibox-content">


        <!--check if member has regno to skip payment part-->
<!--        --><?php //echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>

        <?php if ($APPLICANT->tiob_member == 1) { ?>
            <p>I am a TIOB Member already with registration number:</p>
            <p><strong><?php echo $APPLICANT->Regno; ?></strong></p>

            <hr>
            <div class="form-group">
                <div class="col-lg-6">
                    <input style="margin-bottom: 10px;" class="btn btn-sm btn-success pull-left" type="submit" value="Next Step"/>
                </div>
            </div>
<!--            <br><br>-->

            <?php echo form_close(); ?>

        <?php } else { ?>

            <?php if (!is_section_used('PAYMENT', $APPLICANT_MENU)) { ?>
                <div style="margin-bottom: 15px; color: green; font-weight: bold;">Payment amount required is TZS
                    : <?php
                    $member_type = $APPLICANT->member_type;
                    if ($member_type == '0') {
                        $amount_required = STUDENT_APPLICATION_FEE;
                    } else {
                        $amount_required = ORDINARY_APPLICATION_FEE;
                    }
                    echo number_format($amount_required);
                    //store reference into database
                    $reffNo = REFERENCE_START . '1' . $APPLICANT->id;
                    ?>. Please pay this amount only
                </div>
            <?php } ?>


            <h3>Your Reference Number for payment is : <?php echo $reffNo; ?></h3>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th style="text-align: center; width: 100px;">Reference</th>
                    <th style="text-align: center; width: 100px;">Mobile No</th>
                    <th style="text-align: center; width: 100px;">Receipt</th>
                    <th style="text-align: center; width: 100px;">Amount</th>
                    <th style="text-align: center; width: 150px;">Trans Time</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $payment_transaction = $this->db->where('applicant_id', $APPLICANT->id)->get("fee_statement")->result();

                $total_amount = 0;
                $total_charge = 0;

                foreach ($payment_transaction as $key => $value) {
                    $total_amount += $value->amount;
                    $total_charge += $value->charges;

                    ?>
                    <tr>
                        <td style="text-align: right;"><?php echo $key + 1; ?> .</td>
                        <td style="text-align: center;"><?php echo $value->reference ?></td>
                        <td style="text-align: center;"><?php echo $value->msisdn ?></td>
                        <td style="text-align: center;"><?php echo $value->receipt ?></td>
                        <td style="text-align: right;"><?php echo number_format(($value->amount), 2) ?></td>
                        <td style="text-align: center;"><?php echo $value->createdon ?></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
            <!--        <p>Annual Subscription Fee</p>-->
            <!--        <table class="table table-bordered">-->
            <!--            <thead>-->
            <!--            <tr>-->
            <!--                <th style="width: 50px;">S/No</th>-->
            <!--                <th style="text-align: center; width: 100px;">Reference</th>-->
            <!--                <th style="text-align: center; width: 100px;">Mobile No</th>-->
            <!--                <th style="text-align: center; width: 100px;">Receipt</th>-->
            <!--                <th style="text-align: center; width: 100px;">Amount</th>-->
            <!--                <th style="text-align: center; width: 150px;">Trans Time</th>-->
            <!--            </tr>-->
            <!--            </thead>-->
            <!--            <tbody>-->
            <!--            --><?php
            //            $applicant_id=$APPLICANT->id;
            //            $year=$this->applicant_model->get_entry_year($applicant_id)->AYear;
            //            $payment_transactio = $this->db->where(array('applicant_id'=>$APPLICANT->id))->get("annual_fees")->result();
            //
            //            $total = 0;
            //
            //            foreach ($payment_transactio as $key=>$value){
            //                $total += $value->amount;
            //                ?>
            <!--                <tr>-->
            <!--                    <td style="text-align: right;">--><?php //echo $key+1; ?><!-- .</td>-->
            <!--                    <td style="text-align: center;">--><?php //echo $value->reference ?><!--</td>-->
            <!--                    <td style="text-align: center;">--><?php //echo $value->msisdn ?><!--</td>-->
            <!--                    <td style="text-align: center;">--><?php //echo $value->receipt ?><!--</td>-->
            <!--                    <td style="text-align: right;">-->
            <?php //echo number_format(($value->amount),2) ?><!--</td>-->
            <!--                    <td style="text-align: center;">--><?php //echo $value->createdon ?><!--</td>-->
            <!--                </tr>-->
            <!--            --><?php //} ?>
            <!--            <tr>-->
            <!--                <td style="text-align: right;" colspan="4">Total</td>-->
            <!--                <td style="text-align: right;">-->
            <?php //echo number_format(($total+$total_amount+$total_charge),2) ?><!--</td>-->
            <!--                <td style="text-align: center;"></td>-->
            <!--            </tr>-->
            <!--            </tbody>-->
            <!--        </table>-->


            <?php if (!is_section_used('PAYMENT', $APPLICANT_MENU)) { ?>
                <h5 style="color: blue;">NOTE : After Payment other link will be available for you to continue to fill
                    your
                    application form. If you fail to pay the application fee within 4 days, then your basic details will
                    be
                    deleted in our system</h5>
                <div style="padding-top: 20px;">
                    <h2>Choose Method to Pay</h2>
                    <div class="clearfix">

                        <div class="col-md-4" style="text-align: center;">
                            <img style="width: 200px; height: 80px;" src="<?php echo base_url() ?>/icon/tigo_pesa.png"
                                 class="pay_method1" title="tigopesa">
                            <div style="margin-top: 10px;"><input type="radio" value="tigopesa" name="pay_method"
                                                                  class="pay_method"/></div>
                        </div>
                        <div class="col-md-4" style="text-align: center;">
                            <img style="width: 170px; height: 80px;" src="<?php echo base_url() ?>/icon/mpesa.jpg"
                                 class="pay_method1" title="mpesa">
                            <div style="margin-top: 10px;"><input type="radio" value="mpesa" name="pay_method"
                                                                  class="pay_method"/></div>

                        </div>

                        <div class="col-md-3" style="text-align: center;">
                            <img style="width: 170px; height: 80px;" src="<?php echo base_url() ?>/icon/airtel.jpg"
                                 class="pay_method1" title="airtel">
                            <div style="margin-top: 10px;"><input type="radio" value="airtel" name="pay_method"
                                                                  class="pay_method"/></div>
                        </div>

                        <div class="col-md-4" style="text-align: center;">
                            <img style="width: 200px; height: 80px;" src="<?php echo base_url() ?>/icon/halopesa3.jpg" class="pay_method1" title="halopesa" >
                            <div style="margin-top: 10px;"><input type="radio" value="halopesa" name="pay_method" class="pay_method"/></div>
                        </div>

                    </div>

                    <div id="tigopesa" style="display: none;">
                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Tigo Pesa :
                            Follow
                            steps to pay
                        </div>
                        <div style="padding-left: 100px; font-size: 15px;">
                            1. Dial <b>*150*01#</b><br/>
                            2. Select 4 <b>" Pay Bill (Lipia bili) "</b> <br/>
                            3. Select 3 <b>" Enter Busness Number "</b><br/>
                            4. Enter <b>400700</b> <br/>
                            5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                            6. Enter amount <b>" Enter <?php $member_type = $APPLICANT->member_type;
                                if ($member_type == '0') {
                                    echo number_format(STUDENT_APPLICATION_FEE);
                                } else {
                                    echo number_format(ORDINARY_APPLICATION_FEE);
                                } ?> "</b> <br/>
                            7. Enter Password <b>" Enter your account Password "</b>
                        </div>
                    </div>

                    <div id="mpesa" style="display: none;">
                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">M-Pesa :
                            Follow
                            steps to pay
                        </div>
                        <div style="padding-left: 100px; font-size: 15px;">
                            1. Dial <b>*150*00#</b><br/>
                            2. Select 4 <b>" Pay by M-Pesa (Lipa kwa M-Pesa)"</b> <br/>
                            3. Select 4 <b>" Enter Business Number (Weka namba ya kampuni) "</b><br/>
                            4. Enter Business Number (Weka Namba ya Kampuni) <b>Enter: 400700 </b> <br/>
                            5. Enter Reference Number (Weka Kumbu kumbu ya Malipo) <b>" Enter <?php echo $reffNo; ?>
                                "</b><br/>
                            6. Enter amount <b>" Enter <?php $member_type = $APPLICANT->member_type;
                                if ($member_type == '0') {
                                    echo number_format(STUDENT_APPLICATION_FEE);
                                } else {
                                    echo number_format(ORDINARY_APPLICATION_FEE);
                                } ?> "</b> <br/>
                            7. <b>" Enter your pin (Weka namba yako ya siri) "</b><br/>
                            8. <b>" Confirm payment (Thibitisha malipo) "</b>
                        </div>
                    </div>

                    <div id="airtel" style="display: none;">
                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Airtel Money :
                            Follow steps to pay
                        </div>
                        <div style="padding-left: 100px; font-size: 15px;">
                            1. Dial <b>*150*60#</b><br/>
                            2. Select 5 <b>" Make Payments "</b> <br/>
                            3. Select 3 <b>" Enter Busness Number "</b><br/>
                            4. Enter <b>400700</b> <br/>
                            5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                            6. Enter amount <b>" Enter <?php $member_type = $APPLICANT->member_type;
                                if ($member_type == '0') {
                                    echo number_format(STUDENT_APPLICATION_FEE);
                                } else {
                                    echo number_format(ORDINARY_APPLICATION_FEE);
                                } ?> "</b> <br/>
                            7. Enter Password <b>" Enter your account Password "</b><br/>
                        </div>
                    </div>
                    <div id="halopesa" style="display: none;">
                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">Halo Pesa :
                            Follow steps to pay
                        </div>
                        <div style="padding-left: 100px; font-size: 15px;">
                            1. Dial <b>*150*88#</b><br/>
                            2. Select 4 <b>" Pay Bill "</b> <br/>
                            3. Select 3 <b>" Enter Busness Number "</b><br/>
                            4. Enter <b>400700</b> <br/>
                            5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                            6. Enter amount <b>" Enter <?php $member_type = $APPLICANT->member_type;
                                if ($member_type == '0') {
                                    echo number_format(STUDENT_APPLICATION_FEE);
                                } else {
                                    echo number_format(ORDINARY_APPLICATION_FEE);
                                } ?> "</b> <br/>
                            7. Enter Password <b>" Enter your account Password "</b><br/>
                        </div>
                    </div>


                </div>
            <?php }
        } ?>

        <input type="hidden" value="" id="paid_all"/>


    </div>
</div>

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
                $("#mpesa").hide();
                $("#tigopesa").hide();
                $("#airtel").hide();
                $("#halopesa").show();
            }
        });

        <?php if(!is_section_used('PAYMENT', $APPLICANT_MENU)){ ?>
        setInterval(function () {
            $.ajax({
                type: "post",
                url: "<?php echo site_url('is_applicant_pay') ?>",
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
