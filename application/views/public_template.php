<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>OAS | SARIS</title>
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>media/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="<?php echo base_url(); ?>media/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>media/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>media/css/login.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>media/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>media/css/plugins/select2/select2.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>media/css/select2-bootstrap.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="<?php echo base_url(); ?>media/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url(); ?>media/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>media/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo base_url(); ?>media/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Data picker -->
    <script src="<?php echo base_url(); ?>media/js/jquery-confirm.min.js"></script>
    <link href="<?php echo base_url(); ?>media/css/jquery-confirm.min.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>media/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>media/js/plugins/select2/select2.full.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>media/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>media/css/procedure.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>media/js/script1.js"></script>
<style>
    body{
        color: #333;
        font-size: 14px;
    }
</style>
</head>
<body class="<?php echo (isset($applicant_dashboard) ? 'gray-bg':'white-bg'); ?>">
<div class="header-div" style="background-color: #d9dcea;  border-bottom: 5px solid #553dae;">
    <div class="container">
        <div class="row">

            <div style="background: transparent; height: 80px; margin-left: 10px; overflow: hidden;" >
                <div style="float: left; width: 150px; height: 80px; margin: 0px 0px 0px 20px;  border: 0px solid red;">
                    <img src="<?php echo base_url() ?>images/logo.png"
                         style="width: 55px; margin: 0px 0px 0px 0px; height: 80px;"/>
                </div>
                <div style="float: left; margin: 0px 0px 0px 10px;">
                    <div style="color: #553dae; font-size: 25px; margin: 0px 0px 0px 10px; font-weight: bold;"><?php

                        $organisation_info = get_collage_info();  echo $organisation_info->Name;

                        ?></div>
                    <div style="color: #553dae; font-size: 18px;   margin: 0px 0px 0px 10px;">MEMBERSHIP REGISTRATION & EXAMINATIONS SYSTEM


                    </div>
                </div>
                <div style="clear: both;"></div>

            </div>
        </div>
    </div>
</div>
<div class="block white">
    <div class="row">
            <div class="wrapper wrapper-content animate fadeInUpBig">
                <?php
                    if (isset($content) && isset($data)) {
                        $this->load->view($content, $data);
                    } else {
                        $this->load->view($content);
                    }
                    ?>
            </div>
            <div class="footer fixed" style="margin-left:0px !important; border-top: 1px solid #ccc;">
              <!--   <div class="pull-right">
                    Design and Developed by <strong><a href="http://www.zalongwa.com" target="_blank">Zalongwa Technologies</a>
                </div> -->

                <div>
                    <strong> &copy; <?php echo date('Y'); ?> &nbsp; &nbsp; <a
                                href="<?php echo $organisation_info->Site; ?>"
                                target="_blank"><?php echo $organisation_info->Name; ?></a></strong>
                </div>
            </div>
    </div>
</div>

<!-- Custom and plugin javascript -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>media/js/inspinia.js"></script>
<script src="<?php echo base_url(); ?>media/js/plugins/pace/pace.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
    function numbersonly(e,value,maxvalue){
        var unicode=e.charCode? e.charCode : e.keyCode
        if (unicode!=8 && unicode!=46 && unicode!=9){ //if the key isn't the backspace key (which we should allow)
            if (unicode<48||unicode>57 || value>maxvalue) //if not a number
                return false //disable key press
        }
    }
</script>
<!-- ******************************POP UP************************************ -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModal" aria-hidden="true"
     data-backdrop="dynamic" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="display: none;" id="model_header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" id="modal_body">
                <div style="height: 100px; line-height: 100px; text-align: center;"><img
                            src="<?php echo base_url(); ?>icon/loader.gif"/> Loading....
                </div>
            </div>
            <div class="modal-footer" style="display: none;" id="model_footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
</body>
</html>
