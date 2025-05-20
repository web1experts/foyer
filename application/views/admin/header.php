<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    <?php 
    if(isset($site_header) && !empty($site_header)){ 
      echo $site_header;
    } else {
      echo "Dashboard";
    }
    ?>



  </title>
<?php
$ci = & get_instance();
//load databse library
$ci->load->database();
$ci->load->model('super_dbmodel');

$fav_data = $ci->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'header'));


$fav_image=base_url(). "assets/images/fav.png";
$image_type="png";

if (isset($fav_data->meta_value) && $fav_data->meta_value != "") {
    $fav_img_data = json_decode($fav_data->meta_value);
    $fav_img=$fav_img_data->fav_image;
    if ($fav_img != "") {
        $explode_get_type = explode(".", $fav_img);
        $image_type = $explode_get_type[1];        
        $fav_img_path = base_url('assets/admin/upload/settings/') . $fav_img;
    }
}   




?>
<?php if (isset($image_type)) { ?>  
    <link rel="icon" type="image/<?= $image_type; ?>" href="<?= $fav_img_path; ?>" sizes="16x16" />
<?php } ?>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

   <!-- Select2 -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/jqvmap/jqvmap.min.css">
  <!-- DataTables -->  
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?=base_url('assets/admin/');?>admin_style.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <script src="<?=base_url('assets/admin/');?>plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?=base_url('assets/admin/');?>plugins/jquery-ui/jquery-ui.min.js"></script>
  <script>
    var ajax_url="<?php echo base_url(); ?>"; 
    var currentuser_id = "<?php echo $this->session->userdata('login_auth')->user_id;?>";
</script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="<?=base_url('assets/admin/');?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 -->
  <script src="<?=base_url('assets/admin/');?>plugins/select2/js/select2.full.min.js"></script>

  <!-- DataTables -->
  <script src="<?=base_url('assets/admin/');?>plugins/datatables/jquery.dataTables.js"></script>
  <script src="<?=base_url('assets/admin/');?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>  

  
  <!-- jquery-validation -->
  <script src="<?=base_url('assets/admin/');?>plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="<?=base_url('assets/admin/');?>plugins/jquery-validation/additional-methods.min.js"></script>


  <!-- SweetAlert2 -->
  <script src="<?=base_url('assets/admin/');?>plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  


  <script>
      $(function () {          
          $('.validate_form').validate();
          $('.select2').select2();

          //Common Delete function

          $("body").on("click", ".user_action", function(){
              var get_title=$(this).attr('title');
              var tablenm= $(this).attr('data-id');
              var table_id= $(this).attr('data-key');
              var table_value= $(this).attr('data-value');

              if(get_title=="Delete"){
                  swal({
                      title: "Are you sure?",
                      text: "You want to delete this",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                  })
                  .then((willDelete) => {
                      if (willDelete) {           
                        $.ajax({
                            url:"<?php echo base_url('/delete-data'); ?>",
                            type: "post",
                            dataType: "json",                              
                            data: { "table": tablenm, 'table_id':table_id, 'table_value': table_value},                 
                            success: function(resp){
                              if(resp.success){                        
                                  $("#deletedata_"+table_value).parent().parent().fadeOut("slow");
                              } else {
                                  swal(resp.error, {
                                    icon: "error",
                                  });
                              }
                            }
                        });
                      } 
                  });
              }
          });



          $("body").on("click", ".clone_other_lang", function(){
              var tablenm=$(this).attr('data-table');
              swal({
                  title: "Are you sure you want to clone?",
                  text: "Note: This action will delete other language data and clone english data into other language",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                  if (willDelete) {           
                    $.ajax({
                        url:"<?php echo base_url('/cloning-data'); ?>",
                        type: "post",
                        dataType: "json",                              
                        data: { "table": tablenm},                 
                        success: function(resp){
                          console.log(resp);
                        }
                    });
                  } 
              });
          });
      });
  </script>

  <style>
    span.active {
        font-weight: bold;
        color: #71a4b0;
    }
    label.error {
      color: red;
      font-weight: normal !important;
      display: inline-block;
      width: 100%;
  }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">


<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li> 

        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" title="English" href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/english">
              <img src="<?=base_url('assets/images/flags/');?>en.jpg"> <span <?php if($this->session->userdata('site_lang') == 'english') { echo 'class="active"';} else { echo 'class="active"'; } ?>>English</span>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
          <a class="nav-link" title="English" href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/french">
              <img src="<?=base_url('assets/images/flags/');?>fr.png"> <span <?php if($this->session->userdata('site_lang') == 'french') echo 'class="active"'; ?>>French</span>
          </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" title="English" href="<?php echo base_url(); ?>LanguageSwitcher/switchLang/spanish">
                <img src="<?=base_url('assets/images/flags/');?>sp.png"> <span <?php if($this->session->userdata('site_lang') == 'spanish') echo 'class="active"'; ?>>Spanish</span>
            </a>
        </li> -->
    </ul>   

    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">        
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user-cog"></i>          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">          
          <div class="dropdown-divider"></div>          
          <a href="#" class="dropdown-item">
            <i class="fas fa-user-cog mr-2"></i> <?= lang('profile') ?>            
          </a>
          <div class="dropdown-divider"></div>
          <?php if($this->session->userdata('login_auth')){ ?>
            <?php $session_data=$this->session->userdata('login_auth'); ?>
            <a href="<?php echo base_url('admin/profile-setting'); ?>" class="dropdown-item">                
              <?= lang('name') ?>: 
              <span class="float-right text-muted text-sm"><?= $session_data->user_fname; ?> <?= $session_data->user_lname; ?></span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url('admin/profile-setting'); ?>" class="dropdown-item">   
              <?= lang('email') ?>: 
              <span class="float-right text-muted text-sm"><?= $session_data->user_email; ?></span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= base_url('/logout') ?>" class="dropdown-item"><?= lang('logout') ?></a>
          <?php } ?>
          
      </li>      
    </ul>
  </nav>
  <!-- /.navbar -->
