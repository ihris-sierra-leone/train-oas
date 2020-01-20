<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Course List</h5>

           <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('add_module/') ?>"><strong>Add New Course</strong></a>

    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('module_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group clearfix">
            <div class="col-md-1 pull-right no-padding">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
            <div class="col-md-5 pull-right ">
                <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                       class="form-control" placeholder="Search....">
            </div>
        </div>
        <?php echo form_close();?>
        <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th style="width: 100px;">Code</th>
                    <th style="width: 300px;">Name</th>
                    <th style="width: 100px;">Short Name</th>
                    <th style="width: 100px;">Level</th>
                    <th style="width: 100px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $page = ($this->uri->segment(2) ? ($this->uri->segment(2)+1):1 );
                foreach ($module_list as $key => $value) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo $page++; ?>.</td>
                        <td><?php echo $value->code; ?></td>
                        <td><?php echo $value->name; ?></td>
                        <td><?php echo $value->shortname; ?></td>
                        <td><?php echo levels($value->level); ?></td>
                        <td style="vertical-align: middle; text-align: center">
                        <a href="<?php echo site_url('add_module/'.$value->id) ?>"><i class="fa fa-pencil"></i> Edit</a> |
                            <a style="color: #b64645" href="<?php echo site_url('delete_module/'.$value->id) ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php
                 }
                ?>
                </tbody>
            </table>
        <div>
            <div style="clear: both;"></div>
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
