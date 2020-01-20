<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
$check = $this->db->get("run_selection")->row();
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Applicant Selection</h5>
        <?php if(!$check){ ?>
            <a class="pull-right btn btn-warning" href="<?php echo site_url('run_selection'); ?>">Run Selection</a>
        <?php } ?>
    </div>
    <div class="ibox-content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 50px;  text-align: center;">S/No</th>
                <th style="">Programme Name</th>
                <th style="width: 100px;  text-align: center;">Duration</th>
                <th style="width: 10px;  text-align: center;">Eligible</th>
                <th style="width: 100px;  text-align: center;">Selected</th>
                <th style="width: 100px;  text-align: center;">Rejected</th>
                <th style="width: 150px;  text-align: center;">List</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($programme_list as $key=>$value){
                $check_row = $this->db->where('ProgrammeCode',$value->Code)->get("run_selection")->row();
                $programme_info = get_value('programme',array('Code'=>$value->Code),null);
                $duration = array(2,3);
                if($value->type == 2){
                    unset($duration[0]);
                    $duration = array_values($duration);
                }
                foreach ($duration as $l=>$d){


                    $counter_eligible = $this->db->query("SELECT count(ap.id) as counter FROM application_elegibility as ae 
                     INNER JOIN application as ap ON (ae.applicant_id=ap.id) WHERE ap.application_type='$value->type' AND ap.duration='$d' AND  
                      ae.ProgrammeCode='$value->Code' AND ae.status=1 ")->row();

                    ?>
                    <tr>
                        <?php if( $l == 0){?>
                            <td style="text-align: right; vertical-align: middle;" rowspan="<?php echo count($duration); ?>"><?php echo $key+1; ?>.</td>
                            <td style="vertical-align: middle; <?php echo ($check_row ? 'color:blue;':''); ?>" rowspan="<?php echo count($duration); ?>"><?php echo $value->Name; ?></td>
                        <?php } ?>
                        <td style="text-align: center; vertical-align: middle;"><?php echo $d ?></td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo number_format($counter_eligible->counter); ?></td>
                        <td style="text-align: center; vertical-align: middle;"><?php echo  number_format(0); ?></td>

                        <td style="text-align: center; vertical-align: middle;"><?php echo number_format(0); ?></td>
                        <?php if( $l == 0){?>
                            <td style="text-align: left; vertical-align: middle;" rowspan="<?php echo count($duration); ?>">
                             <!--   <a class="view_data" title="<?php /*echo $programme_info->Name; */?>" PROGRAMME="<?php /*echo $value->Code;  */?>" APP_TYPE="<?php /*echo $value->type; */?>" href="javascript:void(0);"><i class="fa fa-eye"></i> View </a>
                                <br/>
                                <br/>
                                <a  href="<?php /*echo site_url('report/applicant_byProgramme/?programme='.$value->Code.'&type='.$value->type) */?>"><i class="fa fa-download"></i> Export </a>
                            -->
                            </td>
                        <?php } ?>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    $(document).ready(function () {

        <?php if($check){ ?>
        setInterval(function(){
            $.ajax({
                type:"post",
                url:"<?php echo site_url('run_selection_active') ?>",
                datatype:"html",
                success:function(data)
                {
                    if(data == '1'){
                        window.location.reload();
                    }
                    //do something with response data
                }
            });
        },5000)
        <?php } ?>

        $(".view_data").click(function () {
            var programme_code = $(this).attr('PROGRAMME');
            var application_type = $(this).attr('APP_TYPE');
            var title = $(this).attr('title');
            $.confirm({
                title: '<div style="font-size: 20px;">'+title+'</div>',
                content:'URL:<?php echo site_url('popup/applicant_byProgramme/') ?>?programme='+programme_code+'&type='+application_type,
                confirmButton:'Export',
                columnClass:'col-md-12',
                cancelButton:'Close',
                theme:'bootstrap',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm:function () {
                    window.location.href = '<?php echo site_url('report/applicant_byProgramme/') ?>?programme='+programme_code+'&type='+application_type;
                    return false;
                },
                cancel:function () {
                    return true;
                }
            });
        });
    });
</script>