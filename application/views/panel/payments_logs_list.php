<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
i/*f (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 .= " Searching Key :<strong> " . $_GET['key'] . '</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['type']) && $_GET['type'] <> '') {
        $title1 .= " Application Type :<strong> " . application_type($_GET['type']) . '</strong>';
    }

    if (isset($_GET['from']) && $_GET['from'] <> '') {
        $frm = $_GET['from'];
        $from = format_date($frm, true);
        $title1 .= " From :<strong> " . $from . '</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['status']) && $_GET['status'] <> '') {
        $title1 .= " List of :<strong> " . search_status($_GET['status']) . ' &nbsp;</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['to']) && $_GET['to'] <> '') {
        $title1 .= " Until :<strong> " . $_GET['to'] . '</strong>';
    }

    if (isset($_GET['year']) && $_GET['year'] <> '') {
        $title1 .= " Year :<strong> " . $_GET['year'] . '</strong>';
    }

    if (isset($_GET['name']) && $_GET['name'] <> '') {
        $title1 .= " Search key :<strong> " . $_GET['name'] . '</strong>';
    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }

}*/

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Payments Log List</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('logs/payments_log/'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>DATE</th>
                    <th>FIRSTNAME</th>
                    <th>LAST NAME</th>
                    <th>MSISDN</th>
                    <th>RECEIPT</th>
                    <th>REFERENCE</th>
                    <th>AMOUNT</th>
                    <th>DATA</th>
                    <!--<th>STATUS</th>-->
                    <th>SELECT</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $i=0;
                foreach ($payments_logs as $Logs_key => $payment_log_value) {
                    $applicant_id = substr($payment_log_value->reference, 4);
                    $client_info = $this->applicant_model->get_applicant($applicant_id);

                    $i +=1;
                    ?>
                    <tr>

                        <td><?php echo $i; ?></td>
                        <td><?php echo $payment_log_value->createdon; ?></td>
                        <td><?php echo $client_info->FirstName; ?></td>
                        <td><?php echo $client_info->LastName; ?></td>
                        <td><?php echo $payment_log_value->msisdn; ?></td>
                        <td><?php echo $payment_log_value->receipt; ?></td>
                        <td><?php echo $payment_log_value->reference; ?></td>
                        <td><?php echo $payment_log_value->amount; ?></td>
                        <td><?php echo $payment_log_value->data; ?></td>


                        <td>
                            <input type="checkbox" name="txtSelect[]" value="<?php  echo $payment_log_value->reference?>"
                                   />

                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div>
                <div style="clear: both;"></div>
               <!-- <a href="</?php echo site_url('report/export_applicant/?' . ((isset($_GET) && !empty($_GET)) ? http_build_query($_GET) : '')); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>
-->
                <button type="submit" onclick="return confirm_submit('Are you sure');"  name="push_selected" class="btn btn-sm btn-success pull-right">Push Payment</button>
            </div>

        </div>
        <?php echo form_close();
        ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click", ".popup_applicant_info", function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title: title,
                content: "URL:<?php echo site_url('popup_applicant_info') ?>/" + ID + '/?status=1',
                confirmButton: 'Print',
                columnClass: 'col-md-10 col-md-offset-2',
                cancelButton: 'Close',
                extraButton: 'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm: function () {
                    window.location.href = '<?php echo site_url('print_application') ?>/' + ID;
                    return false;
                },
                cancel: function () {
                    return true;
                }

            });
        })
    });

function confirm_submit(message) {
    if(confirm(message))
    {
       return true
    }else
        return false
}
</script>
