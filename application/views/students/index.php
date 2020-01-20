<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Centre List</h5>
               <!-- <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php /*echo site_url('school_list/?action=sync') */?>"><strong>Synchronise</strong></a>
-->
    </div>
    <div class="ibox-content">

      <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th>Center Name</th>
                    <th style="width: 150px;">Center Code</th>
                    <th style="width: 250px;">Center Venue</th>
                    <th style="width: 50px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($students as $key => $value) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo($key + 1) ?>.</td>
                        <td><?php echo $value->first_name; ?></td>
                        <td><?php echo $value->surname; ?></td>
                        <td><?php echo $value->gender; ?></td>
                        <td style="vertical-align: middle; text-align: center"><a href="<?php echo site_url('edit_center_list/'.$value->id) ?>"><i class="fa fa-pencil"></i> Edit</a></td>
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