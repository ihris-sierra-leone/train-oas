<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>College/School List</h5>

               <!-- <a class="btn btn-sm btn-small btn-warning pull-right" style="font-weight: bold;"
                   href="<?php /*echo site_url('school_list/?action=sync') */?>"><strong>Synchronise</strong></a>
-->
    </div>
    <div class="ibox-content">



            <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th style="width: 120px;">Type</th>
                    <th>Name</th>
                    <!--<th style="width: 250px;">Pricipal/Head</th>-->
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($school_list as $sponsork => $sponsorv) {
                    $principal = get_value('users',array('sims_map'=>$sponsorv->principal),null);
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo($sponsork + 1) ?>.</td>
                        <td><?php echo $sponsorv->type1; ?></td>
                        <td><?php echo $sponsorv->name; ?></td>
<!--                        <td><?php /*echo (!empty($principal->title) ? $principal->title.'. ':'').$principal->firstname.' '.$principal->lastname; */?></td>
-->                    </tr>
                <?php } ?>
                </tbody>

            </table>

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