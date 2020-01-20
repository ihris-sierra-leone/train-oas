<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}


?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <?php echo form_open(current_full_url(), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">
            <div class="col-lg-4">
                <select class="form-control" name="corporate">
                    <option value="">[Filter by Corporate]</option>
                    <?php
                    foreach ($member_list as $key=>$value){
                        ?>
                        <option <?php echo(isset($_GET['corporate']) ? ($_GET['corporate'] == $value ? 'selected="selected"' : '') : '') ?>  value="<?php echo $value->id; ?>"><?php echo $value->institution_name; ?></option>;
                        <?php
                    }
                    ?>
                </select>
            </div>

<!--            <div class="col-md-4">-->
<!--                <select class="form-control" name="programme">-->
<!--                    <option value="">&nbsp;&nbsp;[Programme]</option>-->
<!--                    --><?php
//                    foreach (application_type() as $tkey => $tvalue) {
//                        ?>
<!--                        <option --><?php //echo(isset($_GET['programme']) ? ($_GET['programme'] == $tkey ? 'selected="selected"' : '') : '') ?>
<!--                            value="--><?php //echo $tkey; ?><!--">--><?php //echo $tvalue ?><!--</option>-->
<!--                        --><?php
//                    }
//                    ?>
<!--                </select>-->
<!--            </div>-->

            <div class="col-md-3">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
    </div>
    <?php
    echo form_close();

    if (isset($students_list) && !empty($students_list) <> ''){
//        echo "hapa"; exit;
    ?>
    <div class="ibox-title clearfix">
        <h4>Corporate candidates list</h4>
        <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="width: 50px;">S/No</th>
                <th style="width: 100px;">First Name</th>
                <th style="width: 100px;">Last Name</th>
                <th>Registration Number</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $key = 0;
            foreach($students_list as $key => $value) {
            ?>
                <tr>
                <td><?php echo $key+1 ?></td>
                <td><?php echo $value->	first_name; ?></td>
                <td><?php echo $value->surname; ?></td>
                <td><?php echo $value->registration_number; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <div><?php echo $pagination_links; ?>
            <div style="clear: both;"></div>
            <a href="<?php echo site_url('report/export_corporate_candidates/?'.((isset($_GET) && !empty($_GET)) ? http_build_query($_GET):'') ); ?>"
               class="btn btn-sm btn-success">Export Excel</a>
    </div>
</div>
<?php } ?>
</div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click",".popup_student_remark",function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title:title,
                content:"URL:<?php echo site_url('popup_student_remark') ?>/"+ID+'/?status=1',
                confirmButton:'Print',
                columnClass:'col-md-10 col-md-offset-2',
                cancelButton:'Close',
                extraButton:'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm:function () {
                    window.location.href = '<?php echo site_url('print_student_remark') ?>/'+ID;
                    return false;
                },
                cancel:function () {
                    return true;
                }

            });
        })
    });
</script>

