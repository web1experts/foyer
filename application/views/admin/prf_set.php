<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('ProfileSettings') ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card"> 
            <div class="row">      
                <div class="col-md-12"> 
                    <div class="card-body">          
                        <?php if (isset($prf_data) && !empty($prf_data)): ?>                          
                            <?php 
                            $prf_data = $prf_data[0];

                            if($prf_data['user_identify']==1){
                                $user_img="<img id='current_image' src='".$prf_data['user_social_img']."'/>";
                            } else {
                                if($prf_data['user_img'] == ""){
                                    $no_img= base_url('assets/images/blank_img.png');
                                    $user_img="<img id='current_image' height='96px' src='".$no_img."'/>";
                                } else {
                                    $user_uploaded_img= base_url('assets/admin/upload/users/thumbnail/');
                                    $user_img="<img id='current_image' height='96px' src='".$user_uploaded_img.$prf_data['user_thumb_img']."'/>";
                                }
                            }

                            ?> 
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>
                            <form role="form" id="admin_setting" enctype="multipart/form-data" novalidate="novalidate" autocomplete="off">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="image"></label>
                                                <?= $user_img; ?>
                                            </div>   
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="admin_email"><?= lang('useremail') ?></label>
                                                <input type="email" name="admin_email" class="form-control" id="admin_email" placeholder="<?= lang('useremail') ?>" value="<?= $prf_data['user_email']; ?>" readonly>                                        
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="admin_fname"><?= lang('first_name') ?></label>
                                                <input type="text" name="admin_fname" class="form-control" id="admin_fname" placeholder="<?= lang('first_name') ?>" value="<?= $prf_data['user_fname']; ?>">                                        
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="admin_lname"><?= lang('last_name') ?></label>
                                                <input type="text" name="admin_lname" class="form-control" id="admin_lname" placeholder="<?= lang('last_name') ?>" value="<?= $prf_data['user_lname']; ?>">                                        
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image"><?= lang('UploadImg') ?></label>
                                                <input type="file" name="user_image" class="form-control">
                                            </div>
                                        </div>
                                    </div>                                 

                                    <div id="changed_password" style="display: none;">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="admin_pwd"><?= lang('current_pwd') ?></label>
                                                    <input type="password" name="admin_current_pwd" class="form-control" id="admin_current_pwd" placeholder="<?= lang('current_pwd') ?>">  
                                                    <input type="hidden" name="hiddenToken" id="hiddenToken">                                           
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="admin_pwd"><?= lang('new_pwd') ?></label>
                                                    <input type="password" name="admin_pwd" class="form-control" id="admin_pwd" placeholder="<?= lang('new_pwd') ?>">                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="get_new_password"><?= lang('change_pwd') ?></a>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary"><?= lang('update_prf_btn') ?></button>
                                </div>
                            </form> 

                        <?php else: ?>
                            <p style="color:red; text-align:center;"><?= lang('nodata_found') ?></p>
                        <?php endif; ?>
                    </div>
                </div>        
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        $.validator.setDefaults({
            submitHandler: function () {
                $.ajax({
                    url: '<?php echo base_url('/admincontroller/update_profile'); ?>',
                    type: "POST",
                    data: new FormData($('#admin_setting')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function (xhr) {
                        $("#status_data").hide()
                        $(".card-footer button").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
                    }, success: function (resp) {
                        $(".card-footer button").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        if (resp.error_status) {
                            $("#status_data").show().removeClass("alert-success").addClass('alert-danger').html('<strong>Error!</strong> ' + resp.error_status);
                        } else {
                            $("#status_data").show().removeClass("alert-danger").addClass('alert-success').html('<strong>Updated!</strong> ' + resp.success_status);
                        }
                    }, error: function () {
                        $(".card-footer button").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        console.log("Please try after some time");
                    }
                });
            }
        });

        
        $.validator.addMethod("valueNotEquals", function(value, element, arg){
            // I use element.value instead value here, value parameter was always null
            return arg == CryptoJS.MD5(element.value).toString(); 
        }, "Value must not equal arg.");

        $('#admin_setting').validate({
            rules: {
                admin_email: {
                    required: true,
                },

                admin_fname: {
                    required: true,
                },

                admin_lname: {
                    required: true,
                },

                admin_current_pwd: {
                    required: true,
                    valueNotEquals: "<?= $prf_data['user_pass']; ?>",
                },

                admin_pwd: {
                    required: true,
                }
            },
            messages: {
                admin_email: {
                    required: "<?= lang('email_required') ?>",
                },

                admin_fname: {
                    required: "<?= lang('fname_required') ?>",
                },

                admin_lname: {
                    required: "<?= lang('lname_required') ?>",
                },

                admin_current_pwd: {
                    required: "<?= lang('pwd_required') ?>",
                    valueNotEquals: '<?= lang('current_pwd_match_error') ?>',
                },

                admin_pwd: {
                    required: "<?= lang('new_pwd') ?>",
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $("#get_new_password").click(function () {
            $("#changed_password").slideToggle("slow");
            $(this).text($(this).text() == '<?= lang('change_pwd') ?>' ? "<?= lang('change_pwd_later') ?>" : "<?= lang('change_pwd') ?>");
        });

    });
</script>
