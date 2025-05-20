<!DOCTYPE html>
<html>

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php
            if (isset($page_title)) {
                echo $page_title;
            } else {
                echo "Foyer";
            }
            ?> | Hagan Realty</title>

        <?php
        $userdata = $this->session->userdata('login_auth');
        $header_meta = array();
        $fav_img_path = $site_title = $login_text = $logo_img = $fav_img = "";
    
        if (isset($result_header->meta_value) && $result_header->meta_value != "") {
            $header_meta = json_decode($result_header->meta_value);
            $fav_img = $header_meta->fav_image;
            if ($fav_img != "") {
                $explode_get_type = explode(".", $fav_img);
                $image_type = $explode_get_type[1];
                $fav_img_path = base_url('assets/admin/upload/settings/') . $fav_img;
            }

            if (@$header_meta->logo_image != "") {
                $logo_img = base_url('assets/admin/upload/settings/') . $header_meta->logo_image;
            }

            if (@$header_meta->site_title != "") {
                $site_title = $header_meta->site_title;
            }

            if (@$header_meta->login_text != "") {
                $login_text = $header_meta->login_text;
            }
        }

        $home_menu = $list_menu = $catalogue_menu = $reviews_menu = $contact_menu = $faq_menu = "";
        if (isset($menus->meta_value) && $menus->meta_value != "") {
            $menu_meta = json_decode($menus->meta_value);

            if ($menu_meta->home_menu != "") {
                $home_menu = $menu_meta->home_menu;
            }

            if ($menu_meta->list_menu != "") {
                $list_menu = $menu_meta->list_menu;
            }

            if ($menu_meta->catalogue_menu != "") {
                $catalogue_menu = $menu_meta->catalogue_menu;
            }

            if ($menu_meta->reviews_menu != "") {
                $reviews_menu = $menu_meta->reviews_menu;
            }

            if ($menu_meta->contact_menu != "") {
                $contact_menu = $menu_meta->contact_menu;
            }

            if ($menu_meta->faq_menu != "") {
                $faq_menu = $menu_meta->faq_menu;
            }
        }
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php if (isset($image_type)) { ?>
            <link rel="icon" type="image/<?= $image_type; ?>" href="<?= $fav_img_path; ?>" sizes="16x16" />
        <?php } ?>
        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/animate.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/bootstrap-reboot.min.css" type="text/css">
        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/bootstrap-grid.min.css" type="text/css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/bootstrap.min.css">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/style.css">
        <link href="<?= base_url('assets/users/'); ?>fontawesome-free-5/css/all.css" rel="stylesheet">
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->

        <link rel="stylesheet" href="<?= base_url('assets/users/'); ?>css/common.css?v=<?= time(); ?>">


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/users/'); ?>js/wow.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="<?= base_url('assets/users/'); ?>js/bootstrap.min.js"></script>
        <script>
            var ajax_url = "<?php echo base_url(); ?>";
            var currentuser_id = "<?php echo (isset($selected_user_id))?$selected_user_id:$this->session->userdata('login_auth')->user_id; ?>";
        </script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script> -->

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        
        <!-- jquery-validation -->
        <script src="<?= base_url('assets/admin/'); ?>plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="<?= base_url('assets/admin/'); ?>plugins/jquery-validation/additional-methods.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="<?= base_url('assets/users/'); ?>js/sweetalert.min.js"></script>
        <script>
            $(function () {
                $('.validate_form').validate();
            });

            var user_role = "<?php echo $userdata->user_role; ?>";
        </script>
        <script src="<?= base_url('assets/users/'); ?>js/custom.js?v=<?= time(); ?>"></script>
        <!-- DataTables -->
        <link rel="stylesheet" href="<?= base_url('assets/admin/'); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.css">
        <!-- DataTables -->
        <script src="<?= base_url('assets/admin/'); ?>plugins/datatables/jquery.dataTables.js"></script>
        <script src="<?= base_url('assets/admin/'); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
        <!-- SweetAlert2 -->
        <!-- <script src="< ?=base_url('assets/admin/');?>plugins/sweetalert2/sweetalert2.min.js"></script> -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <style>
            label.error {
                color: red;
                display: inline-block;
                margin-top: 6px;
                margin-bottom: 0;
                font-weight: 500;
            }
        </style>
    </head>

    <body>
        <header class="wow fadeInDown">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3 col-lg-6 col-xl-7">
                        <nav class="navbar navbar-expand-lg">
                            <?php if (isset($page_title)) {?>
                                <?php if($page_title=="Foyer | Hagan Realty"){ ?>
                            <button class="navbar-toggler" id="tab-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                Tabs
                            </button>
                        <?php } } ?>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                                <ul class="navbar-nav mr-auto ui-sortable bookmark_tabs" unselectable="on" id="tabs-list">

                                    <?php
                                    if (isset($sorted_tabs_arr) && is_array($sorted_tabs_arr) && !empty($sorted_tabs_arr)) {
                                        $sorted_tabs_arr = array_values($sorted_tabs_arr);
                                        for ($i = 0; $i < count($sorted_tabs_arr); $i++) {
                                            ?>

                                            <li class="nav-item bookmark_nav <?php
                                            if ($i == 0) {
                                                echo "active";
                                            }
                                            ?>"
                                                data-title="<?= $sorted_tabs_arr[$i]['name']; ?>"
                                                data-type="<?= $sorted_tabs_arr[$i]['type'] ?>"
                                                data-id="<?= $sorted_tabs_arr[$i]['id']; ?>">
                                                <a href="javascript:void(0)"
                                                   class="nav-link bookmark_<?= $sorted_tabs_arr[$i]['type'] ?>"
                                                   title="<?= $sorted_tabs_arr[$i]['name']; ?>"
                                                   data-id="<?= $sorted_tabs_arr[$i]['id']; ?>"
                                                   data-title="<?= $sorted_tabs_arr[$i]['type'] ?>" data-thumb="<?= ($sorted_tabs_arr[$i]['type'] == 'user')?@$sorted_tabs_arr[$i]['thumb']:getGraphicsThumb(@$sorted_tabs_arr[$i]['graphic_id']) ?>"><?= $sorted_tabs_arr[$i]['name']; ?></a>
                                            </li>


                                            <?php
                                        }
                                    } else {

                                        if (isset($cmp_data) && !empty($cmp_data)):
                                            ?>

                                            <?php $i = 1; ?>
                                            <?php foreach ($cmp_data as $all_cmp) { ?>
                                                <li class="nav-item bookmark_nav <?php
                                                if ($i == 1) {
                                                    echo "active";
                                                }
                                                ?>"
                                                    data-title="<?= $all_cmp['cmp_nick_title']; ?>" data-type="company"
                                                    data-id="<?= $all_cmp['cmp_id']; ?>">
                                                    <a href="javascript:void(0)" class="nav-link bookmark_company"
                                                       title="<?= $all_cmp['cmp_nick_title']; ?>" data-id="<?= $all_cmp['cmp_id']; ?>"
                                                       data-title="company" data-thumb="<?= getGraphicsThumb(@$all_cmp['graphic_id']) ?>"><?= $all_cmp['cmp_nick_title']; ?></a>
                                                </li>
                                                <?php $i++; ?>
                                            <?php } ?>
                                        <?php endif; ?>

                                        <?php if (isset($team_data) && !empty($team_data)): ?>
                                            <?php
                                            $i = 0;
                                            foreach ($team_data as $all_team) {
                                                ?>
                                                <li class="nav-item bookmark_nav" data-title="<?= $all_team['nick_title']; ?>"
                                                    data-type="team" data-id="<?= $all_team['id']; ?>">
                                                    <a href="javascript:void(0)" class="nav-link bookmark_team"
                                                       title="<?= $all_team['nick_title']; ?>" data-id="<?= $all_team['id']; ?>"
                                                       data-title="team" data-thumb="<?= getGraphicsThumb(@$all_team['graphic_id'])?>"><?= $all_team['nick_title']; ?></a>
                                                </li>
                                                <?php $i++; ?>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if ($this->uri->segment(1) == '' || isset($_GET['p_id']) && @$_GET['tab'] == 'user') { 

                                            $selected_userdata = (isset($selected_user_id))?get_single_row_data('register', $selected_user_id, 'user_id'):$userdata;


                                            ?>
                                            <li class="nav-item bookmark_nav"
                                                data-title="<?php echo @$selected_userdata->user_fname . ' ' . @$selected_userdata->user_lname; ?>"
                                                data-type="user" data-id="<?php echo @$selected_userdata->user_id; ?>">
                                                <a class="nav-link"
                                                   title="<?php echo @$selected_userdata->user_fname . ' ' . @$selected_userdata->user_lname; ?>"
                                                   data-id="<?= @$selected_userdata->user_id; ?>" data-title="user"
                                                   href="javascript:;" data-thumb="<?= @$selected_userdata->user_thumb_img ?>"><?php echo @$selected_userdata->user_fname . ' ' . @$selected_userdata->user_lname; ?></a>
                                            </li>
                                        <?php } ?>
                                        <?php
                                        if (isset($usertabs) && !empty($usertabs)):
                                            foreach ($usertabs as $tab):
                                                ?>
                                                <li class="nav-item bookmark_nav" data-title="<?php echo $tab['sub_title']; ?>"
                                                    data-type="tabs" data-id="<?php echo $tab['id']; ?>">
                                                    <a href="javascript:;" class="nav-link" title="<?php echo $tab['sub_title']; ?>"
                                                       data-id="<?= $tab['id']; ?>" data-title="tabs"
                                                       href="javascript:;"  data-thumb=""><?php echo $tab['sub_title']; ?></a>
                                                </li>

                                                <?php
                                            endforeach;
                                        endif;
                                    }
                                    ?>

                                    <li class="nav-item bookmark_nav" style="display: none;">
                                        <a href="javascript:void(0)" data-id="search" id="search_tab" class="nav-link" title="Search">Search</a>
                                    </li>

                                </ul>
                                <?php if ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'admin' && isset($_GET['tab'] ) && $_GET['tab'] == 'user') { ?>
                                    <a href="javascript:void(0);" id="insert-new-tab" class="insert_modalbox"
                                       data-target="#inserttabModal"><i class="fa fa-plus"></i></a>
                                   <?php } ?>

                                <?php if (isset($page_title)) {?>
                                    <?php if($page_title=="Home"){ ?>
                                      <div class="form-group">
                                        <!-- hide_field -->
                                        <div class="row show-serach-form hide_field">
                                            <div class="Haga_Fom">
                                                <form method="post" id="search_keyword">
                                                    <input type="text" class="form-control" id="" placeholder="Type Here" name="search_keyword" autofocus>
                                                    <button type="submit" class="btn btn-info" id="search-data"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <button class="btn btn-info" id="search_here">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </nav>
                    </div>
                    <div class="col-sm-9 col-lg-6 col-xl-5 text-right">
                        <div class="d-flex flex-wrap">
                            <div class='userDropdown d-flex align-items-center'>
                                <?php if ($this->session->userdata('login_auth') != "") { ?>
                                    <?php $session_data = $this->session->userdata('login_auth'); ?>
    
                                    <?php
                                    $user_img = "";
                                    if ($session_data->user_identify == 1) {
                                        $user_img = "<img class='avatar' width='40' src='" . $session_data->user_social_img . "'/>";
                                    } else {
                                        if ($session_data->user_img == "") {
                                            $no_img = base_url('assets/images/blank_img.png');
                                            $user_img = "<img class='avatar' width='40' src='" . $no_img . "'/>";
                                        } else {
                                            $user_uploaded_img = base_url('assets/admin/upload/users/thumbnail/');
                                            $user_img = "<img class='avatar' width='40' src='" . $user_uploaded_img . $session_data->user_thumb_img . "'/>";
                                        }
                                    }
                                    ?>
                                    <h3>
                                        <a href="javascript:void(0)" class="nav-item nav-link usr_prf_link">
                                            <?= $user_img; ?><span><?= lang('Hello') ?></span>
                                            <span><?php echo $session_data->user_fname . " " . $session_data->user_lname; ?></span>
                                        </a>
                                    </h3>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="ml-md-3"><i class='fa fa-cog'></i></span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="<?= base_url("/profile"); ?>"><?= lang('profile') ?></a>
                                            <a class="dropdown-item" id="editor-btn" href="javascript:void(0)"><?= lang('edit_text') ?></a>
                                            <a class="dropdown-item" href="<?= base_url('/logout') ?>"> <?= lang('logout') ?></a>
                                        </div>
                                    </div>
                                    
                                    <?php } else { ?>
                                    <h3><a href="<?= base_url("/login"); ?>">Login</a> <span><i class='fa fa-cog'></i></span></h3>
                                <?php } ?>
                            </div>
    
                            <?php if ($this->session->userdata('login_auth') != "") { ?>
                                <?php $session_data = $this->session->userdata('login_auth'); ?>
    
                                <?php if ($session_data->user_role == '1') { ?>
                                    <div class='userDropdown adminLink'>
                                        <h3>
                                            <a href="<?php echo base_url('/admin/companies'); ?>" class="nav-item nav-link user-action">Go to admin<b class="caret"></b></a>
                                        </h3>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <!-- <div class='userDropdown adminLink'>
                                <h3>
                                    <a href="javascript::void(0)" class="nav-item nav-link user-edit-action">Edit</a>
                                </h3>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <div class="logo">
                            <a class="navbar-brand" href="javascript::void(0)"><i class='fa fa-home'></i> Hagan Realty</a>
                        </div>
                    </div>
                </div>
        </header>