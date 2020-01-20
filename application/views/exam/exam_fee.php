<?php
    if (isset($message)) {
        echo $message;
    } else if ($this->session->flashdata('message') != '') {
        echo $this->session->flashdata('message');

    }
    
    if (isset($_GET)) {
    $title1 = '';

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
        <h5>Member List</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('exam_fee'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
      <div class="form-group no-padding">

      <div class="col-md-2">
          <input name="name" class="form-control" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ Filter Name ]" value="<?php echo(isset($_GET['name']) ? $_GET['name'] : '') ?>"/>
        </div>
      <div class="col-md-2">
          <input type="submit" value="Search" class="btn btn-success btn-sm">
      </div>
     </div>
      <?php echo form_close();?>
  <hr>
<div class="row">

      <div style="clear: both;"></div>
        
        <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="table table-bordered"
                   style="" id="applicantlist">
                <thead>
                    <th style="width: 30px; text-align: center">S/No</th>
                    <th >RegNo</th>
                    <th >First Name</th>
                    <th >Surname </th>
                    <th >Annual subscription </th>
                    <th >Exam Fee </th>

                </thead>
                <tbody>
                <?php
                $page = ($this->uri->segment(2) ? ($this->uri->segment(2)+1):1 );
                foreach ($member_list as $applicant_key => $applicant_value) {
                     $num=$applicant_value->user_id;
                    
                    ?>
                    <tr>
                      <?php echo form_open('pay_code/?Code='.$applicant_value->registration_number, 'class="form-horizontal ng-pristine ng-valid"') ?>
                        <td style="text-align: left;"><?php  echo $page++; ?></td>
                        <td style="text-align: left;"><?php  echo $applicant_value->registration_number; ?></td>
                        <td style="text-align: left;"><?php  echo $applicant_value->first_name; ?></td>
                        <td style="text-align: left;"><?php  echo $applicant_value->surname; ?>
                        </td>
                        <td style="text-align: left;">
                          <?php
                          $shio="SELECT * FROM annual_fees WHERE user_id='$num' ";
                          $pay_fee=$this->db->query($shio)->result(); 
                          if($pay_fee){ 
                          $annual='1';
                        }else{
                          $annual='0';
                        }

                        if($annual=='1'){
                         ?>

                        <p>Paid</p>

                          <?php
                           }else{
                          ?>
                          <input type="checkbox" value="1" name="status">
                          <?php
                          }
                          ?>
                        </td>
                          <td style="text-align: left;">
                          <?php
                          $fes="SELECT * FROM examinations_payment WHERE user_id='$num' ";
                          $pay=$this->db->query($fes)->result();
                          if($pay){
                          $exam='2';
                        }else{
                          $exam='0';
                        }
                          if($exam=='2'){
                             echo "Paid";
                           }else{
                          ?>
                          <input type="checkbox" value="2" name="status2">
                          <?php
                          }
                          ?>
                    </tr>
                    <?php
                     }
                  ?>
                </tbody>
            </table>
            <div><?php echo $pagination_links; ?>
                <div style="clear: both;"></div>
                <a href="<?php echo site_url('report/export_member/?'.((isset($_GET) && !empty($_GET)) ? http_build_query($_GET):'') ); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>

            </div>
        </div>

    </div>
</div>
</div>
