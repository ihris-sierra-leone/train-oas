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

    if (isset($_GET['from']) && $_GET['from'] <> '') {
        $title1 .= " From :<strong> " .  format_date($_GET['from']) . '</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['to']) && $_GET['to'] <> '') {
        $title1 .= " Until :<strong> " . format_date($_GET['to']) . '</strong>';
    }

    if (isset($_GET['year']) && $_GET['year'] <> '') {
        $title1 .= " Year :<strong> " . $_GET['year'] . '</strong>';
    }

    if ($title1 == '') {
        $title1 .= " Examinations Fee in :<strong> " . date('Y') . '</strong>';
    }
    echo '<div class="alert alert-warning">' . $title1 . '</div>';

}


?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Examinations Fee</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('examFee'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">
            <div class="col-md-2 col-md-offset-1" style="padding-left: 0px;">
                <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                       class="form-control" placeholder="Search....">
            </div>
            <div class="col-md-3">
                <input name="from" placeholder="FROM : DD-MM-YYYY" class="form-control mydate_input" value="<?php echo(isset($_GET['from']) ? $_GET['from'] : '') ?>"/>

            </div>
            <div class="col-md-3">
                <input name="to" placeholder="TO : DD-MM-YYYY" class="form-control mydate_input" value="<?php echo(isset($_GET['to']) ? $_GET['to'] : '') ?>"/>            
            </div>
            <div class="col-md-2">
            <select class="form-control" name="year">
            <option value="<?php echo(isset($_GET['year']) ? $_GET['year'] : '') ?>">[ Filter by year ]</option>
            <?php
            $years=$this->common_model->get_years()->result();
            foreach($years as $key=>$year){
            ?>
            <option value="<?php echo $year->AYear; ?>"><?php echo $year->AYear; ?></option>
            <?php
            }
            ?>
            </select>             
            </div>
            <div class="col-md-1">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
        <?php echo form_close();
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">

            <thead>
                <tr>
                    <th style="width: 30px; text-align: center">S/No</th>
                    <th style="width: 200px;">Name</th>
                    <th style="width: 200px;text-align: center">RegNo</th>
                    <th style="width: 200px;text-align: center">User ID</th>
                    <th style="width: 150px; text-align: center;">Time</th>
                    <th style="width: 100px; text-align: center;">Receipt</th>
                    <th style="width: 100px; text-align: center;">Mobile Used</th>
                    <th style="width: 100px; text-align: center;">Amount</th>
                    <th style="width: 100px; text-align: center;">Charges</th>
                </tr>
                </thead>
                <tbody>
                <?php
//                $total_amount = 0;
//                $total_charge = 0;
                $page = ($this->uri->segment(2) ? ($this->uri->segment(2)+1):1 );
                foreach ($exam_fee_list as $key => $value) {
                   $total_amount += $value->amount;
                   $total_charge += $value->charges;

                    $rowkey = $this->db->get_where('students', array('user_id'=>$value->user_id))->row();
//                    var_dump($rowkey);exit;
                    if($rowkey){
                        $regno = $rowkey->registration_number;
                    }else{
                        $regno = '';
                    }
                    ?>
                    <tr>
                        <td style="text-align: right;"><?php  echo $page++; ?></td>
                        <td style="text-align: left;"><?php  echo $value->FirstName.' '.$value->MiddleName.' '.$value->LastName; ?></td>
                        <td style="text-align: center;"><?php  echo $regno; ?></td>
                        <td style="text-align: center;"><?php  echo $value->user_id; ?></td>
                        <td style="text-align: center;"><?php  echo $value->timestamp; ?></td>
                        <td  style="text-align: center;"><?php echo $value->receipt; ?></td>
                        <td  style="text-align: center;"><?php echo $value->msisdn; ?></td>
                        <td style="text-align: center;"><?php echo number_format($value->amount); ?></td>
                        <td style="text-align: center;"><?php echo number_format($value->charges); ?></td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

            <h2 align="center"> TOTAL AMOUNT <?php echo number_format($total_amount,0); ?></h2>
            <h2 align="center"> TOTAL CHARGES <?php echo number_format($total_charge,0); ?></h2>

            <div>
                <div style="clear: both;"></div>
                <a href="<?php echo site_url('report/export_examination_payment/?'.((isset($_GET) && !empty($_GET)) ? http_build_query($_GET):'') ); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click",".popup_applicant_info",function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title:title,
                content:"URL:<?php echo site_url('popup_applicant_info') ?>/"+ID+'/?status=1',
                confirmButton:'Print',
                columnClass:'col-md-10 col-md-offset-2',
                cancelButton:'Close',
                extraButton:'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm:function () {
                    window.location.href = '<?php echo site_url('print_application') ?>/'+ID;
                    return false;
                },
                cancel:function () {
                    return true;
                }

            });
        })
    });
</script>