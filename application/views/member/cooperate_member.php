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

$has_access = has_role($MODULE_ID,'create_programme',$GROUP_ID,'SETTINGS');
?>

<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-title clearfix">
                <h5>Corporate Member List</h5>
                <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('add_member/') ?>"><strong>Add New Member</strong></a>

            </div>
            <div class="ibox-content">
                <?php echo form_open(site_url('cooperate_member'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
                <div class="form-group clearfix">
                    <div class="col-md-1 pull-right no-padding">
                        <input type="submit" value="Search" class="btn btn-success btn-sm">
                    </div>
                <div class="col-md-5 pull-right ">
                 <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key" class="form-control" placeholder="Search....">
                    </div>

                </div>
                <?php echo form_close();?>
                <div class="row">
                    <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">

                    <thead>
                        <tr>
                            <th style="width: 50px;">S/No</th>
                            <th style="width:250px;">Name</th>
                            <th style="width: 50px;">Mobile</th>
                            <th style="width: 50px;">Telephone</th>
                            <th style="width: 50px;">Fax</th>
                            <th style="width: 50px;">Email</th>
                            <th style="width: 150px;">Postal Adrress</th>
                            <th style="width: 150px;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = $this->uri->segment(2)+1;
                        foreach ($member_list as $key1 => $value1) {
                        ?>
                        <tr>
                            <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $i++; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px; text-align: left;"><?php echo $value1->institution_name; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->mobile; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->telephone; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->fax; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->email; ?></td>
                            <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->postal; ?></td>
                            <td style="vertical-align: middle; text-align: center"><a href="<?php echo site_url('add_member/'.$value1->id) ?>"><i class="fa fa-pencil"></i> Edit</a> |
                                <a style="color: #b64645" href="<?php echo site_url('delete_member/'.$value1->id) ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fa fa-trash"></i> Delete</a>
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
        </div>
    </div>
</div>
