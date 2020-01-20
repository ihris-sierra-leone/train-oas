<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
if (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 .= " Searching Key :<strong> " . $_GET['key'] . '</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['type']) && $_GET['type'] <> '') {
        $title1 .= " Programme Name :<strong> " . get_code($_GET['type']) . '</strong>';
    }

    if (isset($_GET['from']) && $_GET['from'] <> '') {
        $title1 .= " From :<strong> " . $_GET['from'] . '</strong>';
    }

    if (isset($_GET['to']) && $_GET['to'] <> '') {
        $title1 .= " To :<strong> " . $_GET['to'] . '</strong>';
    }

    if (isset($_GET['year']) && $_GET['year'] <> '') {
        $title1 .= " Member List In :<strong> " . $_GET['year'] . '</strong>';
    }

    if (isset($_GET['name']) && $_GET['name'] <> '') {
        $title1 .= " Search key :<strong> " . $_GET['name'] . '</strong>';
    }

    if (isset($_GET['cooperate']) && $_GET['cooperate'] <> '') {
        $title1 .= " Cooperate ID :<strong> " . $_GET['cooperate'] . '</strong>';
    }

    if ($title1 == '') {
        $title1 .= " Member List In :<strong> " . date("Y") . '</strong>';
    }

    echo '<div class="alert alert-warning">' . $title1 . '</div>';
}

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Member List</h5>
<!--        <div style="text-align: right;"><a style="font-size: 11px; text-decoration: underline;"-->
<!--                                           href="--><?php //echo site_url('renotify/?resend=1'); ?><!--">Resend notification by-->
<!--                Email</a></div>-->
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('member_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">

            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp;[ Search by programme ]</option>
                    <?php
                    foreach (receive_programme_code() as $tkey => $tvalue) {
                        ?>
                        <option <?php echo(isset($_GET['type']) ? ($_GET['type'] == $tkey ? 'selected="selected"' : '') : '') ?>
                                value="<?php echo $tkey; ?>"><?php echo $tvalue ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2">
                <?php
                    $qyear = "SELECT DISTINCT(entry_year) as ayear FROM students ORDER BY entry_year DESC";
                    //$years = $this->db->query("SELECT * FROM ayear GROUP BY AYear")->result();
                    $ayear = $this->db->query($qyear)->result();
                    ?>
                <select name="year" class="form-control">
                   <option value="">[ Entry Year ]</option>
                    <?php
                    foreach ($ayear as $key => $value) {
                       ?>
                        <option <?php echo(isset($_GET['year']) ? $_GET['year'] : '') ?>
                                value="<?php echo $value ->ayear; ?>"><?php echo $value ->ayear; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('year');
                ?>
            </div>
<!--
            <div class="col-md-2">
                <input name="from" placeholder="FROM : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php /*echo(isset($_GET['from']) ? $_GET['from'] : '') */?>"/>

            </div>

            <div class="col-md-2">
                <input name="to" placeholder="TO : DD-MM-YYYY" class="form-control mydate_input"
                       value="<?php /*echo(isset($_GET['to']) ? $_GET['to'] : '') */?>"/>
            </div>
-->

            <div class="col-md-3">
                <?php
                $query = "SELECT * FROM member";
                $cooperate_member = $this->db->query($query)->result();
                ?>
                <select name="cooperate" class="form-control">
                    <option value=""> Search by corporate</option>
                    <?php
                    foreach ($cooperate_member as $key => $value) {
                        ?>
                        <option <?php echo(isset($_GET['cooperate']) ? $_GET['cooperate'] : '') ?>
                                value="<?php echo $value->id; ?>"><?php echo $value->institution_name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('cooperate');
                ?>
            </div>
            <!--       <div class="col-md-2">
          <input name="year" class="form-control" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ Year ]" value="<?php // echo(isset($_GET['year']) ? $_GET['year'] : '') ?>"/>
        </div> -->
            <div class="col-md-2">
                <input name="name" class="form-control" placeholder="Search name, Regno"
                       value="<?php echo(isset($_GET['name']) ? $_GET['name'] : '') ?>"/>
            </div>
            <div class="col-md-1">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
        <?php echo form_close(); ?>
        <hr>
        <div class="row">
           <!-- <div class="col-md-6">
                <a style="float:left"
                   href="<\?php echo site_url('report/export_member/?' . ((isset($_GET) && !empty($_GET)) ? http_build_query($_GET) : '')); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>
            </div>
            <div class="col-md-6">
                <!--<p style=""></p>?php echo $pagination_links; ?></p>-->
            </div>


            <div style="clear: both;"></div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">

                <thead>
                    <tr>
                        <th style="width: 2%; text-align: center">S/No</th>
                        <th>RegNo</th>
                        <th>Entry Year</th>
                        <th>Name</th>
<!--                        <th style="width: 100px;">Other Names</th>-->
<!--                        <th style="width: 100px;">Surname</th>-->
                        <th>Membership</th>
                        <th>Programme</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Corporate</th>
                        <th>View</th>
                        <th>Action</th>
                        <th>Change Programme</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    foreach ($member_list as $applicant_key => $applicant_value) {
                        $applicant_info = $this->db->get_where('application',array('user_id'=>$applicant_value->user_id))->row();
                        if($applicant_info){
                            $applicant_id = $applicant_info->id;
                        }
                        $corp_id=$applicant_value->cooperate;
                        if($corp_id==0){
                            $institution="NULL";
                        }else{
                            $corporates=$this->common_model->get_cooperate($corp_id)->result();
                            foreach ($corporates as $key => $value) {
                                $institution=$value->institution_name;
                            }
                        }
                        ?>
                        <tr>
                            <td style="text-align: right;"><?php echo $page++; ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->registration_number; ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->entry_year; ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->first_name.' '.$applicant_value->other_names.' '.$applicant_value->surname; ?></td>
<!--                            <td style="text-align: left;">--><?php //echo $applicant_value->other_names; ?><!--</td>-->
<!--                            <td style="text-align: left;">--><?php //echo $applicant_value->surname; ?><!--</td>-->
                            <td style="text-align: left;"><?php echo (isset($applicant_value->member_type) ? ($applicant_value->member_type<> '' ? member_type($applicant_value->member_type) : '') : ''); ?></td>
                            <td style="text-align: left;"><?php if ($applicant_value->programme_id == 0) {
                                    echo 0;
                                } else {
                                    echo receive_programme_code($applicant_value->programme_id);
                                } ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->email; ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->mobile; ?></td>
                            <td style="text-align: left;"><?php  echo $institution; ?></td>
                            <td style="text-align: left;"><?php echo '<a href="javascript:void(0);" style="display: block;" class="popup_applicant_info" ID="' . encode_id($applicant_id) . '"
                      title="' . $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname . '"><i class="fa fa-eye"></i> View</a>'; ?></td>

                            <td>
                                <a href="<?php echo site_url('member_change_status/?Code=' . $applicant_value->user_id . '&iD=' . $applicant_info->id . '&q=1'); ?>"
                                   class="">Change status</a>
                            </td>
                            <td>
                                <a href="<?php echo site_url('candidate_programme/?Code=' . $applicant_value->user_id . '&iD=' . $applicant_info->id . '&q=0'); ?>"
                                   class="">Change programme</a>
                            </td>
<!--                            <td style="text-align: left;">--><?php //echo '<a href="javascript:void(0);" style="display: block;" class="popup_applicant_info" ID="' . encode_id($applicant_value->id) . '"
//                      title="' . $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname . '"><i class="fa fa-eye"></i> View</a>'; ?><!--</td>-->

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <div><?php echo $pagination_links; ?>
                    <div style="clear: both;"></div>
                    <a href="<?php echo site_url('report/export_member/?' . ((isset($_GET) && !empty($_GET)) ? http_build_query($_GET) : '')); ?>"
                       class="btn btn-sm btn-success">Export Excel</a>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click", ".popup_applicant_info", function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title: title,
                content: "URL:<?php echo site_url('popup_applicant_info') ?>/" + ID + '/?status=1',
                confirmButton: 'Print',
                columnClass: 'col-md-10 col-md-offset-2',
                cancelButton: 'Close',
                extraButton: 'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm: function () {
                    window.location.href = '<?php echo site_url('print_application') ?>/' + ID;
                    return false;
                },
                cancel: function () {
                    return true;
                }

            });
        })
    });
</script>



