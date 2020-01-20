<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Certification List</h5>

   <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('add_certificate/') ?>"><strong>Add New Certificate</strong></a>

    </div>
    <!--certification_list-->
    <div class="ibox-content">

      <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th>Name</th>
                    <th style="width: 150px;">Value</th>
                    <th style="width: 150px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($certification as $key => $value) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo($key + 1) ?>.</td>
                        <td><?php echo $value->name; ?></td>
                        <td><?php echo $value->value; ?></td>
                        <td style="vertical-align: middle; text-align: center">
                        <a href="<?php echo site_url('add_certificate/'.$value->id) ?>"><i class="fa fa-pencil"></i> Edit</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                        <a style="color: #b64645" href="<?php echo site_url('delete_certificate/'.$value->id) ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
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