<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}

?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Enter Registration Number to Search for a Student</h5>
        <?php echo form_open(site_url('search_member_form'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="col-md-1 pull-right no-padding">
            <input type="submit" value="Search" class="btn btn-success btn-sm">
        </div>
        <div class="col-md-5 pull-right ">
            <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                   class="form-control" placeholder="Search....">
        </div>
    </div>
    <?php
    echo form_close();

    if (isset($student_info) && !empty($student_info) <> ''){
    ?>
    <div class="ibox-title clearfix">
        <table cellspacing="0" cellpadding="0" class="table table-bordered"
               style="" id="applicantlist">
            <thead>
            <tr>
                <th style="width: 30px; text-align: center">S/No</th>
                <th style="width: 200px;">Name</th>
                <th style="width: 100px; text-align: center;">Birth Date</th>
                <th style="width: 100px;">Application Type</th>
                <th style="width: 100px;">Application Date</th>
                <th style="width: 100px; text-align: center;">Mobile</th>
                <th style="width: 60px;">Action</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $page = 1;
            foreach ($applicant_list as $applicant_key => $applicant_value) {
                $mobile1=$this->db->query("select * from students where user_id='".$applicant_value->user_id."'  ")->row()->mobile;
                if($mobile1)
                {
                    $mobile=$mobile1;
                }else{
                    $mobile="";
                }

                ?>
                <tr>
                    <td style="text-align: right;"><?php echo $page; ?></td>
                    <td style="text-align: left;"><?php echo $applicant_value->FirstName . ' ' . $applicant_value->MiddleName . ' ' . $applicant_value->LastName; ?></td>
                    <td style="text-align: center;"><?php echo format_date($applicant_value->dob); ?></td>
                    <td><?php echo application_type($applicant_value->application_type); ?></td>
                    <td><?php echo format_date($applicant_value->createdon); ?></td>
                    <td style="text-align: center;"><?php echo $mobile; ?></td>
                    <td style="text-align: left;"><?php echo '<a href="javascript:void(0);" style="display: block;" class="popup_applicant_info" ID="' . encode_id($applicant_value->id) . '"
                      title="' . $applicant_value->FirstName . ' ' . $applicant_value->MiddleName . ' ' . $applicant_value->LastName . '"><i class="fa fa-eye"></i> View</a>'; ?></td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>
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
</script>

