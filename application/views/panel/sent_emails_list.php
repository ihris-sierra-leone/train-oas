<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
if (isset($_GET)) {
    $title1 = '';




    if (isset($_GET['from']) && $_GET['from'] <> '') {
        $frm = $_GET['from'];
        $from = format_date($frm, true);
        $title1 .= " From :<strong> " . $from . '</strong> &nbsp; &nbsp; &nbsp;';
    }



    if (isset($_GET['to']) && $_GET['to'] <> '') {
        $title1 .= " Until :<strong> " . $_GET['to'] . '</strong>';
    }

    if (isset($_GET['year']) && $_GET['year'] <> '') {
        $title1 .= " Year :<strong> " . $_GET['year'] . '</strong>';
    }

    if (isset($_GET['name']) && $_GET['name'] <> '') {
        $title1 .= " Search key :<strong> " . $_GET['name'] . '</strong>';
    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }

}

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Sent Emails List</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('sent_emails_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">

            <div class="  col-md-offset-2 col-md-4" >
                <input name="from" placeholder="FROM : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php echo(isset($_GET['from']) ? $_GET['from'] : '') ?>"/>

            </div>

            <div class="col-md-2">
                <input name="to" placeholder="TO : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php echo(isset($_GET['to']) ? $_GET['to'] : '') ?>"/>
            </div>




            <div class="col-md-4">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
        <hr style="width: 100%; color: black; height: 1px; background-color:black;" />

        <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Id</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>Mail Type</th>
                    <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $status_array=array
                (
                    4=>"Member",
                    8=>"Ordinary Member",
                    9=>"Associate Member",
                    10=>"Activation Code",
                    11=>"Reset Password",
                    12=>"Referee",
                    13=>'Forget Password'
                );
                foreach ($sent_emails_list as $applicant_key => $sent_mails_value) {
                    if($sent_mails_value->data)
                    {
                        
                    $data = json_decode($sent_mails_value->data);
                    $user_info = $this->db->where('id', $data->user_id)->get('users')->row();

                    ?>
                    <tr>
                        <td><?php echo $sent_mails_value->time_stamp?$sent_mails_value->time_stamp:''; ?></td>
                        <td><?php echo $sent_mails_value->id?$sent_mails_value->id:''; ?></td>
                        <td><?php echo $sent_mails_value->type; ?></td>
                        <td><?php echo isset($user_info->email)?$user_info->email:''; ?></td>
                        <td><?php echo isset($user_info->firstname)?$user_info->firstname:''; ?></td>
                        <td><?php echo isset($user_info->lastname) ?$user_info->lastname:''; ?></td>
                        <td><?php echo $sent_mails_value->status!='' ? $status_array[$sent_mails_value->status] :"".' Notification mail     '; ?></td>



                        <td>
                            <input type="checkbox" name="txtSelect[]" value="<?php  echo $sent_mails_value->id?>"/>

                        </td>

                <?php }?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div>
                <div style="clear: both;"></div>
                    <button type="submit" onclick="return confirm('Are you sure');"  name="resend_selected" class="btn btn-sm btn-success pull-right">Resend  Selected</button>
            </div>

        </div>
        <?php echo form_close();
        ?>
        </div>
    </div>
</div>


