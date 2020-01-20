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
                <h5>Fee List</h5>
                <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                href="<?php echo site_url('fee_setup') ?>"><strong>Add Fee</strong></a>

            </div>
            <div class="ibox-content">
                <?php echo form_open(site_url('venue_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
                <div class="form-group clearfix">
                    <!-- <div class="col-md-1 pull-right no-padding">
                        <input type="submit" value="Search" class="btn btn-success btn-sm">
                    </div> -->
                    <!-- <div class="col-md-5 pull-right ">
                        <input type="text" value="<?php// echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                               class="form-control" placeholder="Search....">
                    </div> -->


                </div>
                <?php echo form_close();?>
                <div class="row">
                    <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                           style="" id="applicantlist">
                        <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">S/No</th>
                            <th style="width:100px; text-align:">Programme</th>
                            <th style="width: 150px; text-align:">Membership Type</th>
                            <th style="width: 50px; text-align: ">Examination Fee</th>
                            <th style="width: 50px; text-align: ">Annual Subscription Fee</th>
                            <!-- <th style="width: 50px; text-align: ">Action</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = $this->uri->segment(2)+1;
                        $a = 1;
                        foreach ($venue_list as $key1 => $value1) {
                            ?>
                            <tr>
                                <td style="vertical-align: middle; padding-left: 4px; text-align: center;"><?php echo $a++; ?></td>
                                <td style="vertical-align: middle; padding-left: 4px; text-align: left;"><?php echo get_code($value1->programmeID); ?></td>
                                <td style="vertical-align: middle; padding-left: 4px;">
                                <?php
                                if($value1->member_category==0){
                                    echo "Student Member";
                                }elseif($value1->member_category==1){
                                    echo "Ordinary Member";
                                }
                                 ?>
                                 </td>
                                <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->amount; ?></td>
                                <td style="vertical-align: middle; padding-left: 4px;"><?php echo $value1->annual_amount; ?></td>
                                <!-- <td style="vertical-align: middle; padding-left: 4px;"><a href="<?php // echo site_url('fee_setup/?id='.$value1->id) ?>"><i class="fa fa-pencil"></i> Edit</a></td> -->
    

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
<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>