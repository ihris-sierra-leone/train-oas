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
                <h5>Examination Calender</h5>
                <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php echo site_url('register_event/') ?>"><strong>Add New Examination Date</strong></a>

            </div>
            <div class="ibox-content">
                <?php echo form_open(site_url('member_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
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
                    <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                           style="" id="applicantlist">
                        <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">S/No</th>
                            <th style="width:60px; text-align: center;">Code</th>
                            <th style="width: 300px; text-align: center;">Name</th>
                            <th style="width: 100px; text-align: center;">Centre</th>
                            <th style="width: 150px; text-align: center;">Date</th>
                            <th style="width: 80px; text-align: center;">Time</th>
                            <th style="width: 50px; text-align: center;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = $this->uri->segment(2)+1;
                        foreach ($exam_list as $key1 => $value1) {
                            ?>
                            <tr>
                                <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $i++; ?></td>
                                <td style="vertical-align: middle; padding-left: 4px; text-align: left;"><?php echo get_courses($value1->coursecode,'code'); ?></td>
                                <td style="vertical-align: middle; padding-left: 4px;"><?php echo get_courses($value1->coursecode,'name') ?></td>
                                <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo "All"; ?></td>
                                <td style="vertical-align: middle; padding-left: 4px;"><?php echo format_date($value1->exam_date,false); ?></td>
                                <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->time; ?></td>
                                <td style="vertical-align: middle; text-align: center"><a href="<?php echo site_url('register_event/'.$value1->id) ?>"><i class="fa fa-pencil"></i> Edit</a></td>

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
