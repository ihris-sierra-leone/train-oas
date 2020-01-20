
<div class="row">
    <div class="col-md-5">
       <div class="ibox">
           <div class="ibox-heading">
               <div class="ibox-title">
                   <h5>Programme List</h5>
               </div>
           </div>
           <div class="ibox-content no-padding no-margins panel_height">
               <table class="table" style="font-size: 14px; width: 100%;"><thead>
                   <tr>
                       <th style="width: 10%;">S/No</th>
                       <th>Programme Name</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php
                   foreach ($programme_list as $key=>$value){ ?>
                       <tr>
                           <td><?php echo ($key+1); ?></td>
                           <td><?php echo '<a class="link_clicked" href="'.site_url('programme_setting_panel/'.$value->Code).'" target="iframe2">'.$value->Name.'</a>'; ?></td>
                       </tr>
                   <?php } ?>
                   </tbody>
               </table>

           </div>
       </div>
    </div>

    <div class="col-md-7 no-padding">
        <div class="ibox">
            <div class="ibox-heading">
                <div class="ibox-title">
                    <h5>Eligible Criteria Settings</h5>
                </div>
            </div>
            <div class="ibox-content panel_height2" style="padding-right: 0px;">
                <iframe name="iframe2" id="iframe2" style="width: 100%; border: 0px;" onload="resizeIframe(this)" scrolling="yes" border="0" src="<?php echo site_url('programme_setting_panel') ?>" >Your browser does not support this part, Please use latest mozilla, Google Chrome, Opera, Latest Version of Internet Explore</iframe>
            </div>
        </div>
    </div>
</div>



<script>
    function resizeIframe(iframe) {
        iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
    }
        $(document).ready(function(){

        $(".link_clicked").click(function () {
          $("#iframe2").removeAttr('height');
        });


        });

</script>