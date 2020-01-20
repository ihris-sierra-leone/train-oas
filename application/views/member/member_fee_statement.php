<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">

        <h5>My Fee Statement</h5>
        <!--        <a class="btn btn-small btn-sm btn-success pull-right" href="javascript:void(0);" id="fake_pay">Fake Payment</a>-->


    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-12">
                <?php
                $uid = $CURRENT_USER->id;
                $applicant_id = $APPLICANT->id;
                $annual = $this->db->query("select * from fee_statement where user_id='$uid' ")->result();
                ?>

                        <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive">
                            <thead>
                            <th style="width: 2%;">SNO</th>
                            <th>Date Paid</th>
                            <th>Receipt No</th>
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
                                <td><?php echo $value->receipt; ?></td>
                                <td><?php echo $value->amount; ?></td>
                                <?php
                                $a++;
                                }
                                ?>
                            </tbody>
                        </table>


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
