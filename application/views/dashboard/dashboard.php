<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 navy-bg">
            <span style="font-size: 13px;" class="font-bold">ACCOUNT CREATED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(id) as counter FROM application")->row()->counter); ?></h2>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 blue-bg">
            <span style="font-size: 13px;" class="font-bold">APPLICATION SUBMITTED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(id) as counter FROM application WHERE submitted=1")->row()->counter); ?></h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 yellow-bg">
            <span style="font-size: 13px;" class="font-bold">NO. OF APPLICATION PAID</span>
            <h2 style="font-size: 20px;" class="font-bold text-right"><?php echo number_format($this->db->query("SELECT count(id) as counter FROM application_payment")->row()->counter); ?></h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 red-bg">
            <span style="font-size: 13px;" class="font-bold">FEE COLLECTED</span>
            <h2 style="font-size: 20px;" class="font-bold text-right">
            <?php 
            $total = ($this->db->query("SELECT SUM(amount) as amount FROM application_payment")->row()->amount)
            +
            ($this->db->query("SELECT SUM(amount) as amount FROM examinations_payment")->row()->amount)
            +
            ($this->db->query("SELECT SUM(amount) as amount FROM annual_fees")->row()->amount);

            echo number_format($total,2);          
            ?>
            </h2>
        </div>
    </div>
</div>


<div class="row">

    <div class="ibox">
        <div class="ibox-content">
            <h3>Applicant Choice By Programmes</h3>
            <?php
            $programme_list = $this->common_model->get_programme()->result();
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">S/No</th>
                    <th>Programme Name</th>
                    <th style="width: 300px; text-align: left;">Applicant Applied</th>
                    <!-- <th style="width: 100px; text-align: center;">2<sup>nd</sup> Choice</th>
                    <th style="width: 100px; text-align: center;">3<sup>rd</sup> Choice</th>
                    <th style="width: 100px; text-align: center;">4<sup>th</sup> Choice</th>
                    <th style="width: 100px; text-align: center;">5<sup>th</sup> Choice</th> -->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($programme_list as $key=>$value){
                    $first_choice =  $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice1='$value->Code'")->row();
                    // $second_choice = $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice2='$value->Code'")->row();
                    // $third_choice =  $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice3='$value->Code'")->row();
                    // $fourth_choice = $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice4='$value->Code'")->row();
                    // $fifth_choice =  $this->db->query("SELECT COUNT(id) as counter FROM application_programme_choice WHERE choice5='$value->Code'")->row();
                    ?>
                <tr>
                    <td style="text-align: right;"><?php echo ($key+1) ?>.</td>
                    <td><?php echo $value->Name; ?></td>
                    <td style="text-align: left;"><?php echo number_format($first_choice->counter); ?></td>
                    <!-- <td style="text-align: right;"><?php// echo number_format($second_choice->counter); ?></td>
                    <td style="text-align: right;"><?php// echo number_format($third_choice->counter); ?></td>
                    <td style="text-align: right;"><?php// echo number_format($fourth_choice->counter); ?></td>
                    <td style="text-align: right;"><?php// echo number_format($fifth_choice->counter); ?></td> -->
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>


    </div>
</div>
