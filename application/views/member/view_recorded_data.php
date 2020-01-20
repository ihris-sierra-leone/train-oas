<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');

}
if (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 .= " Searching Key :<strong> " . $_GET['key'] . '</strong> &nbsp; &nbsp; &nbsp;';
    }

//    if (isset($_GET['from']) && $_GET['from'] <> '') {
//        $title1 .= " From :<strong> " .  format_date($_GET['from']) . '</strong> &nbsp; &nbsp; &nbsp;';
//    }
//
//    if (isset($_GET['to']) && $_GET['to'] <> '') {
//        $title1 .= " Until :<strong> " . format_date($_GET['to']) . '</strong>';
//    }
//
//    if (isset($_GET['year']) && $_GET['year'] <> '') {
//        $title1 .= " Year :<strong> " . $_GET['year'] . '</strong>';
//    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }

}

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Added record</h5>
        <a class="btn btn-sm btn-small btn-warning pull-right"
           href="<?php echo site_url('register_existing_member') ?>">
            <strong>Add new record</strong>
        </a>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('view_recorded_data'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">
            <div class="col-md-2 col-md-offset-1" style="padding-left: 0px;">
                <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                       class="form-control" placeholder="Search....">
            </div>

            <div class="col-md-1">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
        <?php echo form_close();
        ?>
        <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-bordered"
                   style="" id="applicantlist">
                <thead>
                <tr>
                    <th style="width: 30px; text-align: center">S/No</th>
                    <th style="width: 200px;">Name</th>
                    <th style="width: 200px;">Reg No</th>
                    <th style="width: 150px; text-align: center;">Transaction Date</th>
                    <th style="width: 100px; text-align: center;">Receipt</th>
                    <th style="width: 100px; text-align: center;">Mobile Used</th>
                    <th style="width: 100px; text-align: center;">Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $page = ($this->uri->segment(2) ? ($this->uri->segment(2)+1):1 );
                foreach ($annual_fee_list as $key => $value) {
                    $userid=$value->user_id;
                    $reg = $this->db->query("select * from students where user_id='$userid'")->row()->registration_number;


                    ?>
                    <tr>
                        <td style="text-align: right;"><?php  echo $page++; ?></td>
                        <td style="text-align: left;"><?php  echo $value->FirstName.' '.$value->MiddleName.' '.$value->LastName; ?></td>
                        <td style="text-align: center;"><?php  echo $reg; ?></td>
                        <td style="text-align: center;"><?php  echo $value->createdon; ?></td>
                        <td  style="text-align: center;"><?php echo $value->receipt; ?></td>
                        <td  style="text-align: center;"><?php echo $value->msisdn; ?></td>
                        <td style="text-align: center;"><?php echo number_format($value->amount); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div><?php echo $pagination_links; ?>
                <div style="clear: both;"></div>
<!--                <a href="--><?php //echo site_url('report/export_annual_report/?'.((isset($_GET) && !empty($_GET)) ? http_build_query($_GET):'') ); ?><!--"-->
<!--                   class="btn btn-sm btn-success">Export Excel</a>-->

            </div>
        </div>
    </div>
</div>

