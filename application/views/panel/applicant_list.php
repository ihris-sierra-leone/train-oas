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

    if ($title1 == '') {
        $title1 .= "Applicant List in  :<strong> " . date('Y') . '</strong>';
    }
    echo '<div class="alert alert-warning">' . $title1 . '</div>';

}

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Applicant List</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('applicant_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">
            <div class="col-lg-2">
                <select class="form-control" name="status">
                    <option value="">[Filter by status]</option>
                    <?php
                    foreach (search_status() as $key => $value) {
                        ?>
                        <option <?php echo(isset($_GET['status']) ? ($_GET['status'] == $value ? 'selected="selected"' : '') : '') ?>
                                value="<?php echo $key ?>"><?php echo $value ?></option>;
                        <?php
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <input name="from" placeholder="FROM : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php echo(isset($_GET['from']) ? $_GET['from'] : '') ?>"/>

            </div>

            <div class="col-md-2">
                <input name="to" placeholder="TO : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php echo(isset($_GET['to']) ? $_GET['to'] : '') ?>"/>
            </div>

            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">&nbsp;&nbsp;[Programme]</option>
                    <?php
                    foreach (application_type() as $tkey => $tvalue) {
                        ?>
                        <option <?php echo(isset($_GET['type']) ? ($_GET['type'] == $tkey ? 'selected="selected"' : '') : '') ?>
                                value="<?php echo $tkey; ?>"><?php echo $tvalue ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input name="name" class="form-control" placeholder="[Filter First name]"
                       value="<?php echo(isset($_GET['name']) ? $_GET['name'] : '') ?>"/>
            </div>
            <div class="col-md-1">
                <input name="year" class="form-control" placeholder="Year"
                       value="<?php echo(isset($_GET['year']) ? $_GET['year'] : '') ?>"/>
            </div>
            <div class="col-md-1">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">
                <thead>
                <tr>
                    <th>S/No</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Mobile</th>
                    <th>Amount</th>
                    <th>Corporate</th>
                    <th>Status</th>
                    <th>Account Age</th>
                    <th>Action</th>
                    <th>Action</th>
                    <th>Programme</th>
                    <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $page = ($this->uri->segment(2) ? ($this->uri->segment(2) + 1) : 1);
                foreach ($applicant_list as $applicant_key => $applicant_value) {
                    if ($applicant_value->submitted === '0') {
                        $application_status = "Incomplete";
                    } else {
                        $application_status = "Submitted";
                    }

                    $corp_id = $applicant_value->cooperate;
                    if ($corp_id == 0) {
                        $institution = "NULL";
                    } else {
                        $corporates = $this->common_model->get_cooperate($corp_id)->row();

                        $institution = $corporates->institution_name;
                    }
                    //  echo $corp_id;exit;
                    $amount_paid = $this->db->select_sum('amount')->where(array('applicant_id' => $applicant_value->id))->get('application_payment')->row();
//                    $amount_paid = $this->db->from('');
//                    $this->db->where(array('applicant_id' => $applicant_value->id, 'flag' => 1));
//                    $amount_paid = $this->db->get('fee_statement')->row();
//                    get_where('fee_statement', array('applicant_id'=>$applicant_value->id, 'flag'=>1))->row();

                    ?>
                    <tr>
                        <td style="text-align: right;"><?php echo $page++; ?></td>
                        <td style="text-align: left;"><?php echo $applicant_value->FirstName . ' ' . $applicant_value->MiddleName . ' ' . $applicant_value->LastName; ?></td>
                        <td style="text-align: center;"><?php echo format_date($applicant_value->dob); ?></td>
                        <td><?php echo(isset($applicant_value->application_type) ? ($applicant_value->application_type <> '' ? application_type($applicant_value->application_type) : 'NULL') : 'NULL'); ?></td>
                        <td><?php echo format_date($applicant_value->createdon); ?></td>
                        <td style="text-align: center;"><?php echo $applicant_value->Mobile1; ?></td>
                        <td><?php echo(isset($amount_paid) ? (count($amount_paid) > 0 ? $amount_paid->amount : 'NULL') : 'NULL') ?></td>
                        <td style="text-align: center;">
                            <?php
                            echo $institution;
                            ?>
                        </td>
                        <td style="text-align: center;"><?php echo $application_status; ?></td>
                        <td style="text-align: center;"><?php echo GetNumberOfDaysBetweenDates($applicant_value->createdon); ?></td>

                        <td style="text-align: left;"><?php echo '<a href="javascript:void(0);" style="display: block;" class="popup_applicant_info" ID="' . encode_id($applicant_value->id) . '"
                      title="' . $applicant_value->FirstName . ' ' . $applicant_value->MiddleName . ' ' . $applicant_value->LastName . '"><i class="fa fa-eye"></i> View</a>'; ?></td>
                        <td>
                            <a href="<?php echo site_url('member_registration_form/?Code=' . $applicant_value->user_id . '&iD=' . $applicant_value->id . '&q=0'); ?>"
                               class="">Change status</a>
                        </td>
                        <td>
                            <a href="<?php echo site_url('set_programme/?Code=' . $applicant_value->user_id . '&iD=' . $applicant_value->id . '&q=0'); ?>"
                               class="">Change programme</a>
                        </td>


                        <td>
                            <input type="checkbox" name="txtSelect[]" value="<?php  echo $applicant_value->id?>"
                                    <?php if(($applicant_value->submitted!=0) or
                                             ($applicant_value->submitted==0 and (int)$amount_paid->amount != 0 ) or
                                             ($applicant_value->submitted==0 and GetNumberOfDaysBetweenDates($applicant_value->createdon)<=30))
                                    echo"disabled"; ?> />

                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div>
                <div style="clear: both;"></div>
                <a href="<?php echo site_url('report/export_applicant/?' . ((isset($_GET) && !empty($_GET)) ? http_build_query($_GET) : '')); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>

                <button type="submit" onclick="return confirm_submit('Are you sure');"  name="delete_selected" class="btn btn-sm btn-success pull-right">Delete Selected</button>
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
