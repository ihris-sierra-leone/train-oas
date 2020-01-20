<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>

<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Publish Examinations</h5>

    </div>
    <div class="ibox-content">
        <div class="row">
            <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                   style="" id="publish">
                <thead>
                <tr>
                    <th style="width: 10px; text-align: center;">S/No</th>
                    <th style="width: 20px; text-align: center;">Code</th>
                    <th style="width: 100px;">Programme Name</th>
                    <th style="width: 20px; text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $programme_list = $this->common_model->get_programme()->result();
                $i = 1;
                foreach (@$programme_list as $key1 => $value1) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $i++; ?></td>
                        <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $value1->Code; ?></td>
                        <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->Name; ?></td>
                        <td style="vertical-align: middle; padding-left: 4px; text-align: center">
                        <a href="<?php echo site_url('publish/?code='.$value1->Code.'&action=1') ?>"></i> PUBLISH </a> |
                            <a href="<?php echo site_url('publish/?code='.$value1->Code.'&action=0') ?>"></i> UNPUBLISH </a>
                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
    </div>

</div>
</div>

<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>

