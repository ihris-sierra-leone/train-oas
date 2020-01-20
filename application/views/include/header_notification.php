<ul class="nav navbar-top-links navbar-right" >
    <?php
     if($CURRENT_USER->group_id == 4 ) {
         if($CURRENT_USER->id <> '') {
             $regno = $this->db->get_where('students', array('user_id' => $CURRENT_USER->id))->row();
             if(!is_null($regno)) {
                 echo "<b>" . $regno->registration_number . "</b>&nbsp;";
             }
         }
     } ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            <img style="height: 30px; width: 30px; margin-top: -10px; margin-right: 5px;" class="img-circle" src="<?php echo ($CURRENT_USER->profile <> '' ? HTTP_PROFILE_IMG.$CURRENT_USER->profile : HTTP_PROFILE_IMG.'default.jpg')  ?>" alt="Image">
            <span style="display: inline-block; margin-top: -10px; "><?php echo $CURRENT_USER->title.'. '.$CURRENT_USER->firstname;?></span>
            <b style="display: inline-block; margin-top: -10px;" class="caret"></b>
        </a>
        <ul class="dropdown-menu" style="width: 200px;">
            <li class="divider"></li>
            <li>
                <a href="<?php echo site_url('login_history') ?>">
                    <div>My Login History </div>
                </a>

            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo site_url('change_password') ?>">
                    <div>Change Password </div>
                </a>

            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo site_url('logout'); ?>">
                    <div>Logout </div>
                </a>

            </li>
            <?php  if($CURRENT_USER->id == 1){ ?>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo site_url('manage_database'); ?>">
                        <div>Manage Database</div>
                    </a>

                </li>
            <?php } ?>
            </ul>

    </li>

    <li>
        <a href="<?php echo site_url('logout'); ?>">
            <i class="fa fa-sign-out"></i> Log out
        </a>
    </li>
</ul>
