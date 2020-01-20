<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5> Packaging List </h5>
    </div>


    <div class="ibox-content">
        <div class="row">
            <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                   style="" id="applicantlist">
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
                $i = $this->uri->segment(2)+1;
                foreach ($programme_list as $key) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $i++; ?></td>
                        <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $key->Code; ?></td>
                        <td style="vertical-align: middle; padding-left: 4px;"><?php echo $key->Name; ?></td>
                        <td style="vertical-align: middle; text-align: center">
                            <div>
                                <div style="clear: both;"></div>
                                <a href="<?php echo site_url('report/package/?Code='.$key->Code.'&iD='.$key->id); ?>" class="btn btn-sm btn-success">Excel</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div><?php echo @$pagination_links; ?>
                <div style="clear: both;"></div>
            </div>
        </div>
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