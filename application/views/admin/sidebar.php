<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a target="_blank" href="<?php echo base_url('/'); ?>" class="brand-link">
        <i class="fa fa-home elevation-3" style="opacity: .8"></i> 
        <span class="brand-text font-weight-light"><?= lang('admin_text') ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <?php if($this->session->userdata('login_auth')){ ?>
        <?php $session_data=$this->session->userdata('login_auth'); ?>
        <?php
        $user_img="";
        if($session_data->user_identify==1){
            $user_img="<img src='".$session_data->user_social_img."'/>";
        } else {
            if($session_data->user_img == ""){
                $no_img= base_url('assets/images/blank_img.png');
                $user_img="<img height='96px' src='".$no_img."'/>";
            } else {
                $user_uploaded_img= base_url('assets/admin/upload/users/thumbnail/');
                $user_img="<img height='96px' src='".$user_uploaded_img.$session_data->user_thumb_img."'/>";
            }
        }
        ?>
        <div class="image">
          <?= $user_img; ?>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $session_data->user_fname; ?> <?= $session_data->user_lname; ?></a>
        </div>
        <?php } ?>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
           <?php if(isset($site_menu) && !empty($site_menu)){ ?> 
              <!-- <li class="nav-item has-treeview">
                <a href="<?php echo base_url('/admin') ?>" class="nav-link<?php echo ($site_menu=='dashboard') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p><?= lang('dashboard') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li> -->

              <li class="nav-item has-treeview">
                <a href="<?php echo base_url('admin/companies'); ?>" class="nav-link<?php echo ($site_menu=='companies') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-file-image"></i>
                  <p><?= lang('companies') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>


              <li class="nav-item has-treeview">
                <a href="<?php echo base_url('admin/subtabs'); ?>" class="nav-link<?php echo ($site_menu=='subtabs') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-file"></i>
                  <p><?= lang('subtab') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li> 

              <li class="nav-item has-treeview">
                  <a href="<?= base_url('admin/teams') ?>" class="nav-link <?php echo ($site_menu=='teams') ? ' active' : ''; ?>">
                    <i class="nav-icon fas fa-file"></i>
                    <p><?= lang('teams') ?> <i class="right fas fa-angle-right"></i>

                    </p>
                  </a>
              </li>
              
              <li class="nav-item has-treeview">
                <a href="<?php echo base_url('admin/users'); ?>" class="nav-link<?php echo ($site_menu=='users') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p><?= lang('users') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>

              <li class="nav-item has-treeview">
                  <a href="<?= base_url('admin/bookmarks') ?>" class="nav-link <?php echo ($site_menu=='bookmarks') ? ' active' : ''; ?>">
                    <i class="nav-icon fas fa-file"></i>
                    <p><?= lang('bookmarks') ?> <i class="right fas fa-angle-right"></i></p>
                  </a>
              </li>

               
               <li class="nav-item has-treeview">
                <a href="<?php echo base_url('admin/graphics'); ?>" class="nav-link<?php echo ($site_menu=='graphics') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p><?= lang('graphics') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li> 


              <li class="nav-item has-treeview">
                <a href="<?php echo base_url('admin/requests'); ?>" class="nav-link<?php echo ($site_menu=='requests') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-question-circle"></i>
                  <p><?= lang('request') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>             

              

              <!-- <li class="nav-item has-treeview">
                <a href="<?= base_url('/admin/profile-setting') ?>" class="nav-link<?php echo ($site_menu=='prf_set') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-user-cog"></i>
                  <p><?= lang('ProfileSettings') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li> -->

              <?php
                $innermenu="";
                $style="none;";
                $arrow_class="fa-angle-right";
                $menu_open="";
                if(isset($inner_active_menu)){
                  $innermenu=$inner_active_menu;
                  $style="block";
                  $arrow_class="fa-angle-right";
                  $menu_open="menu-open";
                }
                ?>

              <!--<li class="nav-item has-treeview">
                <a href="<?= base_url('/admin/contact') ?>" class="nav-link<?php echo ($site_menu=='contact') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-address-card"></i>
                  <p><?= lang('contact') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>


               <li class="nav-item has-treeview">
                <a href="<?= base_url('/admin/reviews') ?>" class="nav-link<?php echo ($site_menu=='reviews') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-star"></i>
                  <p><?= lang('reviews') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>


              <li class="nav-item has-treeview">
                <a href="<?= base_url('/admin/faqs') ?>" class="nav-link<?php echo ($site_menu=='faqs') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-question-circle"></i>
                  <p><?= lang('faq') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li> -->

              <li class="nav-item has-treeview">
                <a href="<?= base_url('/admin/setting') ?>" class="nav-link<?php echo ($site_menu=='settings') ? ' active' : ''; ?>">
                  <i class="nav-icon fas fa-user-cog"></i>
                  <p><?= lang('settings') ?> <i class="right fas fa-angle-right"></i></p>
                </a>            
              </li>
          <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>