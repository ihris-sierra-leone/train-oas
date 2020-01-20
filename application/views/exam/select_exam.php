<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');

}
if (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 = " Searching Key :<strong> " . $_GET['key'] . '</strong>';
    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }
}
$has_access = has_role($MODULE_ID, 'create_programme', $GROUP_ID, 'SETTINGS');

//get current user_id
$userid = $CURRENT_USER->id;

//echo $userid;

$regno = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->registration_number;
$memberid = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->member_type;

$programmeID = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->programme_id;
//echo $memberid;exit;
$level = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->level;

$amount = $this->db->query("select * from exam_fee where programmeID=$programmeID and member_category=$memberid ")->row()->amount;

$count = $this->db->query("SELECT COUNT(course_id) as count FROM temp_exam_registered WHERE registration_number='$regno'")->row()->count;
//get tatal amount for exams registered
$total_amount = ($amount * $count);

$active_year = $this->common_model->get_academic_year()->row()->AYear;
$semester = $this->common_model->get_academic_year()->row()->semester;

?>

<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title clearfix">
                <div class="col-md-4">
                    <h5>Examination Selection</h5>
                </div>
                <div class="col-md-8">
                    <p style="float:right">Note: Your Examamination fee per course is
                        <b><?php echo number_format(($amount), 2); ?>TZS</b></p>
                </div>


            </div>

            <div class="ibox-content">
                <?php
                $applicant_id = $APPLICANT->id;
                $uid = $CURRENT_USER->id;
                //$annual_fee=$this->db->query("SELECT * FROM annual_fees WHERE user_id='$userid' AND academic_year='$active_year' ")->result();
                //if(!$annual_fee){
                $reference=REFERENCE_START.'1'.$applicant_id;
                $reference_annual=REFERENCE_START.'2'.$applicant_id;
                $date=$this->db->query("select * from fee_statement where (reference='$reference' or reference='$reference_annual' ) and academic_year=".date('Y'))->row();
                if(!$date)
                {
                    $date = $this->common_model->get_expire_date($uid)->row();
                }

                if ($date) {
                    $start_date = date("Y-m-d", strtotime($date->createdon));
                    $expire_date = date('Y-m-d', strtotime('+1 year', strtotime($start_date)));
                    $today_date = date('Y-m-d');
                    if ($today_date > $expire_date) {
                        echo "<div class='alert alert-warning'><i class='fa fa-info'></i> Sorry, To undertake course registration you should make you sure you have paid for annual subscription fee </div>";
                    } else { ?>

                        <?php echo form_open(site_url('registered_exam_list'), ' class="form-horizontal ng-pristine ng-valid"') ?>
                        <div class="form-group no-padding">
                            <div class="col-lg-5">
                                <?php
                                //                            $q = "SELECT * FROM students WHERE user_id = '$userid'";
                                //                            $pid = $this->db->query($q)->result();
                                $get_programme = $this->db->get_where('students', array('user_id' => $userid))->row();
                                $pcode = $get_programme->programme_id;
                                //                            foreach ($pid as $pid) {
                                //                                $pcode = $pid->programme_id;
                                //                            }
                                //get programme id
                                $id = $this->db->get_where('programme', array('Code' => $pcode))->row()->id;
                                //                            $sq = "SELECT * FROM programme WHERE Code = '$pcode'";
                                //                            $sl = $this->db->query($sq)->result();
                                //                            foreach ($sl as $key) {
                                //                                $id = $key->id;
                                //                            }

                                //          if(!is_null($level)){
                                //              $where = " WHERE level='$level' AND programme_id='$id' ";
                                //          }else{
                                //              $where = " WHERE programme_id='$id' ";
                                //          }
                                //                            $query = "SELECT * FROM courses  WHERE programme_id='$id' ";
                                //                            $courses = $this->db->query($query)->result();
                                $courses = $this->db->get_where('courses', array('programme_id' => $id))->result();
                                ?>
                                <select name="course" class="form-control">
                                    <option value=""> [ Select course ]</option>
                                    <?php
                                    foreach ($courses as $key => $value) {
                                        ?>
                                        <option <?php echo(isset($_GET['course']) ? ($_GET['course'] == $key ? 'selected="selected"' : '') : '') ?>
                                                value="<?php echo $value->id; ?>"><?php echo $value->name; ?>
                                            (<?php echo $value->shortname; ?>)
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <?php
                                echo form_error('course');
                                ?>
                            </div>
                            <div class="col-lg-5">
                                <?php
                                $query = "SELECT * FROM examination_centers";
                                $centers = $this->db->query($query)->result();
                                ?>
                                <select name="center" class="form-control">
                                    <option value=""> [ Select Center ]</option>
                                    <?php
                                    foreach ($centers as $key => $value) {
                                        ?>
                                        <option <?php echo(isset($_GET['center']) ? ($_GET['center'] == $key ? 'selected="selected"' : '') : '') ?>
                                                value="<?php echo $value->id; ?>"><?php echo $value->center_name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <?php
                                echo form_error('center');
                                ?>
                            </div>
                            <div class="col-md-2">
                                <input class="btn btn-sm btn-success" type="submit" value="ADD"/>
                            </div>
                        </div>
                        <hr>
                        <?php
                        $courses = $this->db->query("SELECT * FROM temp_exam_registered WHERE registration_number='$regno' ")->result();
                        if ($courses) {
                            ?>

                            <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                                   style="" id="selected_exam_list">
                                <thead>
                                <tr>
                                    <th style="width:60px;">SNo</th>
                                    <th style="width: 300px;">Name</th>
                                    <th style="width: 100px;">Centre</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = $this->uri->segment(2) + 1;
                                foreach ($courses as $key => $courseValue) {
                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle; padding-left: 4px;"><?php echo $i++; ?></td>
                                        <td style="vertical-align: middle; padding-left: 4px;"><?php echo get_courses($courseValue->course_id); ?></td>
                                        <td style="vertical-align: middle; padding-left: 4px;"><?php echo get_centre($courseValue->center_id); ?></td>
                                        <td style="vertical-align: middle; padding-left: 4px;">
                                            <a href="<?php echo site_url('remove_course/?Code=' . $courseValue->course_id . '&iD=' . $courseValue->registration_number); ?>"
                                               class=""><i class="fa fa-times"></i>&nbsp;Remove</a>
                                        </td>

                                    </tr>
                                    <?php
                                }
                                $payment_transaction = $this->db->where(['user_id' => $userid, 'academic_year' => $active_year, 'session' => $semester])->get("examinations_payment")->result();
                                $exam = 0;
                                $total_required = 0;
                                $total_exam_annual = $total_amount;

                                ?>
                                </tbody>
                            </table>


                            <div style="margin-bottom: 15px; color: green; font-weight: bold;">Payment amount required
                                is
                                TZS : <?php echo number_format(($total_exam_annual), 2); ?>. Please pay this amount only
                            </div>
                            <?php $reffNo = REFERENCE_START . '3' . $APPLICANT->id; ?>
                            <h3>Your Reference Number for payment is : <?php echo $reffNo; ?></h3>
                            <div class="row">
                                <div class="ibox-heading">
                                    <div class="ibox-title">
                                        <h5>Application Fee Payment</h5>
                                        <?php if (!is_section_used('PAYMENT', $APPLICANT_MENU)) { ?>
                                            <!--  <a class="btn btn-small btn-sm btn-success pull-right" href="javascript:void(0);" id="fake_exam_pay">Fake Payment</a>-->
                                        <?php } ?>

                                    </div>

                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 50px;">S/No</th>
                                    <th style="text-align: center; width: 100px;">Reference</th>
                                    <th style="text-align: center; width: 100px;">Mobile No</th>
                                    <th style="text-align: center; width: 100px;">Receipt</th>
                                    <th style="text-align: center; width: 100px;">Amount</th>
                                    <th style="text-align: center; width: 100px;">Transaction Time</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($payment_transaction as $key => $value) {
                                    $examAmount = $value->amount + $value->charges;
                                    $total_required += $examAmount;
                                    ?>
                                    <tr>
                                        <td style="text-align: right;"><?php echo $key + 1; ?> .</td>
                                        <td style="text-align: center;"><?php echo $value->reference; ?></td>
                                        <td style="text-align: center;"><?php echo $value->msisdn ?></td>
                                        <td style="text-align: center;"><?php echo $value->receipt ?></td>
                                        <td style="text-align: right;"><?php echo number_format(($examAmount), 2) ?></td>
                                        <td style="text-align: right;"><?php echo $value->createdon; ?></td>

                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td style="text-align: right;" colspan="4">Total Amount</td>
                                    <td style="text-align: right;"
                                        colspan="3"><?php echo number_format(($total_required), 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php if (!is_section_used('PAYMENT', $APPLICANT_MENU)) { ?>
                                <div style="padding-top: 20px;">
                                    <h2>Choose Method to Pay</h2>
                                    <div class="clearfix">

                                        <div class="col-md-4" style="text-align: center;">
                                            <img style="width: 200px; height: 80px;"
                                                 src="<?php echo base_url() ?>/icon/tigo_pesa.png" class="pay_method1"
                                                 title="tigopesa">
                                            <div style="margin-top: 10px;"><input type="radio" value="tigopesa"
                                                                                  name="pay_method" class="pay_method"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="text-align: center;">
                                            <img style="width: 170px; height: 80px;"
                                                 src="<?php echo base_url() ?>/icon/mpesa.jpg" class="pay_method1"
                                                 title="mpesa">
                                            <div style="margin-top: 10px;"><input type="radio" value="mpesa"
                                                                                  name="pay_method" class="pay_method"/>
                                            </div>

                                        </div>
                                        <div class="col-md-3" style="text-align: center;">
                                            <img style="width: 170px; height: 80px;"
                                                 src="<?php echo base_url() ?>/icon/airtel.jpg" class="pay_method1"
                                                 title="airtel">
                                            <div style="margin-top: 10px;"><input type="radio" value="airtel"
                                                                                  name="pay_method" class="pay_method"/>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="tigopesa" style="display: none;">
                                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">
                                            Tigo Pesa : Follow steps to pay
                                        </div>
                                        <div style="padding-left: 100px; font-size: 15px;">
                                            1. Dial <b>*150*01#</b><br/>
                                            2. Select 4 <b>" Pay Bill (Lipia bili) "</b> <br/>
                                            3. Select 3 <b>" Enter Busness Number "</b><br/>
                                            4. Enter <b>400700</b> <br/>
                                            5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                            6. Enter amount <b>" Enter <?php echo number_format($total_exam_annual); ?>
                                                "</b> <br/>
                                            7. Enter Password <b>" Enter your account Password "</b>
                                        </div>
                                    </div>

                                    <div id="mpesa" style="display: none;">
                                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">
                                            M-Pesa : Follow steps to pay
                                        </div>
                                        <div style="padding-left: 100px; font-size: 15px;">
                                            1. Dial <b>*150*00#</b><br/>
                                            2. Select 4 <b>" Pay by M-Pesa (Lipa kwa M-Pesa)"</b> <br/>
                                            3. Select 4 <b>" Enter Business Number (Weka namba ya kampuni) "</b><br/>
                                            4. Enter Business Number (Weka Namba ya Kampuni) <b>Enter: 400700 </b> <br/>
                                            5. Enter Reference Number (Weka Kumbu kumbu ya Malipo) <b>"
                                                Enter <?php echo $reffNo; ?>"</b><br/>
                                            6. Enter amount <b>" Enter <?php echo number_format($total_exam_annual); ?>
                                                "</b> <br/>
                                            7. <b>" Enter your pin (Weka namba yako ya siri) "</b><br/>
                                            8. <b>" Confirm payment (Thibitisha malipo) "</b>
                                        </div>
                                    </div>

                                    <div id="airtel" style="display: none;">
                                        <div style="font-size: 16px; font-weight: bold; padding-top: 20px; color: brown;">
                                            Airtel Money : Follow steps to pay
                                        </div>
                                        <div style="padding-left: 100px; font-size: 15px;">
                                            1. Dial <b>*150*60#</b><br/>
                                            2. Select 5 <b>" Make Payments "</b> <br/>
                                            3. Select 3 <b>" Enter Busness Number "</b><br/>
                                            4. Enter <b>400700</b> <br/>
                                            5. Enter Reference Number <b>" Enter <?php echo $reffNo; ?>"</b><br/>
                                            6. Enter amount <b>" Enter <?php echo number_format($total_exam_annual); ?>
                                                "</b> <br/>
                                            7. Enter Password <b>" Enter your account Password "</b><br/>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php }
                    }
                } else {
                    echo "<div class='alert alert-warning'><i class='fa fa-info'></i> Sorry, To undertake course registration you should make you sure you have paid for annual subscription fee </div>";
                } ?>


                <input type="hidden" value="" id="paid_all"/>


            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#fake_exam_pay").click(function () {
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
            } else if (pay_method == 'airtel') {
                $("#airtel").show();
                $("#tigopesa").hide();
                $("#mpesa").hide();
            } else if (pay_method == 'mpesa') {
                $("#mpesa").show();
                $("#tigopesa").hide();
                $("#airtel").hide();
            }
        });

        <?php if(!is_section_used('PAYMENT', $APPLICANT_MENU)){ ?>
        setInterval(function () {
            $.ajax({
                type: "post",
                url: "<?php echo site_url('is_examination_pay') ?>",
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
