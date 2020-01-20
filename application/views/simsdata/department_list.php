<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}

?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Department List</h5>

        <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
           href="<?php echo site_url('add_department/') ?>"><strong>Add New Department</strong></a>
    </div>
    <div class="ibox-content">
        <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="width: 50px;">S/No</th>
                <th style="width: 300px;">Name</th>
                <th style="width: 100px;">Address</th>
                <th style="width: 100px;">Email</th>
                <th style="width: 100px;">Phone</th>
                <th style="width: 100px;">Action</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $page = ($this->uri->segment(2) ? ($this->uri->segment(2)+1):1 );
            foreach ($department_list as $key => $value) {
                ?>
                <tr>
                    <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo $page++; ?>.</td>
                    <td><?php echo $value->Name; ?></td>
                    <td><?php echo $value->Address; ?></td>
                    <td><?php echo $value->Email; ?></td>
                    <td><?php echo $value->LandLine; ?></td>
                    <td style="vertical-align: middle; text-align: center">
                        <a href="<?php echo site_url('add_department/'.$value->id) ?>"><i class="fa fa-pencil"></i> Edit</a> |
                        <a style="color: #b64645" href="<?php echo site_url('delete_department/'.$value->id) ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>

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

        $(".select2_search1").select2({
            theme: "bootstrap",
            placeholder: " [ Select College/School ] ",
            allowClear: true
        });

    })
</script>