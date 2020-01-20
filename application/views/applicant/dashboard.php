<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">Dashboard</div>
    </div>


    <div class="ibox-content">Welcome !, Please use menu at the right side to navigate in the system
        <br/><br/>
    <div class="col-md-12 clearfix">
        <div class="col-md-8">
            <?php   $get_status_id = "SELECT status FROM application WHERE user_id='$CURRENT_USER->id'";
                    $feed = $this->db->query($get_status_id)->row();
               echo '<b>Application Status :</b>'. current_status($feed->status).'<br/>';

               ?>
            </div>
        </div>
        <br/><br/>

    </div>
</div>