<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>StudyLevel List</h5>

             <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('add_studylevel/') ?>"><strong>Add Study Level</strong></a>
    </div>
    <div class="ibox-content">

        <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th>Name</th>
                    <th style="width: 150px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($studylevel_list as $key => $value) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo($key + 1) ?>.</td>
                        <td><?php echo $value->name; ?></td>
                        <td style="vertical-align: middle; text-align: center">
                        <a href="<?php echo site_url('add_studylevel/'.$value->id); ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                        <a style="color: #b64645" href="<?php echo site_url('delete_studylevel/'.$value->id) ?>" onclick = "return confirm('confirm to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
                        </td>

                    </tr>
                <?php
                 }
                ?>
                </tbody>

            </table>


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
