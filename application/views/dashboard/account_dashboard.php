<div class="row">
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 navy-bg">
            <span style="font-size: 13px;" class="font-bold">APPLICATION FEE</span>
            <h2 style="font-size: 20px;" class="font-bold text-right">
            <?php echo number_format($this->db->query("SELECT SUM(amount) as amount FROM application_payment")->row()->amount, 2); ?>
            </h2>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 blue-bg">
            <span style="font-size: 13px;" class="font-bold">ANNUAL SUBSCRIPTION</span>
            <h2 style="font-size: 20px;" class="font-bold text-right">
            <?php  echo number_format($this->db->query("SELECT SUM(amount) as amount FROM annual_fees WHERE pay_method is null")->row()->amount,2) ?>
            </h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 yellow-bg">
            <span style="font-size: 13px;" class="font-bold">EXAM PAYMENTS</span>
            <h2 style="font-size: 20px;" class="font-bold text-right">
            <?php echo number_format($this->db->query("SELECT SUM(amount) as amount FROM examinations_payment WHERE pay_method is null")->row()->amount, 2); ?>
            </h2>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="widget style1 red-bg">
            <span style="font-size: 13px;" class="font-bold">ONLINE CHARGE</span>
            <h2 style="font-size: 20px;" class="font-bold text-right">
            <?php 
            $total_charges=$this->db->query("SELECT SUM(charges) as amount FROM application_payment")->row()->amount
            +
            $this->db->query("SELECT SUM(charges) as amount FROM annual_fees WHERE pay_method is null")->row()->amount
            +
            $this->db->query("SELECT SUM(charges) as amount FROM examinations_payment WHERE pay_method is null")->row()->amount;
            echo number_format($total_charges, 2);
             ?>
            </h2>
        </div>
    </div>
</div>


<div class="row">

    <div class="ibox">
        <div class="ibox-content">
            <h3>summary</h3>
            <hr>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td><i><b>TOTAL AMOUNT COLLECTED</b></i></td>
                    <td style="text-align: right;">
                    <h2>
                    <b>
                    <?php 
                    $total = ($this->db->query("SELECT SUM(amount) as amount FROM application_payment")->row()->amount)
                    +
                    ($this->db->query("SELECT SUM(amount) as amount FROM examinations_payment WHERE pay_method is null")->row()->amount)
                    +
                    ($this->db->query("SELECT SUM(amount) as amount FROM annual_fees WHERE pay_method is null")->row()->amount);

                    echo number_format($total,2);

                    ?>
                    </b>
                    </h2>
                    </td>

                </tr>
                <tr>
                    <td><i><b>TOTAL CASH COLLECTED</b></i></td>
                    <td style="text-align: right;">
                    <h2>
                    <b>
                    <?php 
                    $total2 = ($this->db->query("SELECT SUM(amount) as amount FROM examinations_payment WHERE pay_method='Cash'")->row()->amount)
                    +
                    ($this->db->query("SELECT SUM(amount) as amount FROM annual_fees WHERE pay_method='Cash'")->row()->amount)
                    +
                    ($this->db->query("SELECT SUM(amount) as amount FROM temp_annual_fees WHERE pay_method='Cash'")->row()->amount);

                    echo number_format($total2,2);

                    ?>
                    </b>
                    </h2>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>


    </div>
</div>
