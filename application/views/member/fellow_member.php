<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');

}
if (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 = " Searching Key :<strong> " . $_GET['key'] . '</strong>';
    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }

}
?>

<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title clearfix">
                <h5>Fellow Member List</h5>
                <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('add_fellow_member/') ?>"><strong>Add New Member</strong></a>

            </div>
            <div class="ibox-content">
                <?php echo form_open(site_url('fellow_member'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
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
                <div class="row">
                    <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">

                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">S/No</th>
                            <th style="width:200px; text-align: center;">Name</th>
                            <th style="width:200px; text-align: center;">Title</th>
                            <th style="width: 50px; text-align: center;">Mobile</th>
                            <th style="width: 120px; text-align: center;">Email</th>
                            <th style="width: 120px; text-align: center;">Postal Adrress</th>
                            <th style="width: 100px; text-align: center;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = $this->uri->segment(2)+1;
                        foreach ($fellow_list as $key1 => $value1) {
                        ?>
                        <tr>
                            <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $i++; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px; text-align: left;"><?php echo $value1->name; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px; text-align: left;"><?php echo $value1->title ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->mobile; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->email; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->postal; ?></td>
                            <td style="vertical-align: middle; text-align: center"><a href="<?php echo site_url('add_fellow_member/'.$value1->id) ?>"><i class="fa fa-pencil"></i> Edit</a> |
                                <a style="color: #b64645" href="<?php echo site_url('delete_fellow/'.$value1->id) ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
                            </td>

                        </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <div><?php echo $pagination_links; ?>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>