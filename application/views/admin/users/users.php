<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('users') ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#insertModal"><?= lang('add_user') ?></a>
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
                        <?php if (isset($users_data) && !empty($users_data)): ?>                                                       
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>

                            <table id="data_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= lang('image') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('email') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('tabs') ?></th>
                                        <th><?= lang('teams') ?></th>
                                        <th><?= lang('last_login') ?></th>
                                        <th><?= lang('Action') ?></th>
                                    </tr>
                                </thead>                            
                            </table>


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

<!-- Insert Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('add_user') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">

            <div class="form-group">
                <label for="user_insert_fname"><?= lang('first_name') ?></label>
                <input type="text" id="user_insert_fname" name="user_fname" class="form-control" placeholder="<?= lang('first_name') ?>" required>
            </div>

            <div class="form-group">
                <label for="user_insert_lname"><?= lang('last_name') ?></label>
                <input type="text" id="user_insert_lname" name="user_lname" class="form-control" placeholder="<?= lang('last_name') ?>" required>
            </div>

            <div class="form-group">
                <label for="user_insert_email"><?= lang('useremail') ?></label>
                <input type="text" id="user_insert_email" name="user_email" class="form-control" placeholder="<?= lang('useremail') ?>" required>
            </div>
            <div class="form-group">
                <label for="user_insert_password"><?= lang('password') ?></label>
                <input type="password" id="user_insert_password" name="user_password" class="form-control" placeholder="<?= lang('password') ?>" required>
            </div>           
            <div class="form-group">
                <label for="user_insert_country"><?= lang('status') ?></label>
                <select name="user_status" id="user_insert_status" class="form-control" required>
                    <option value="1">Active</option>
                    <option value="2">Deactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="user_role"><?= lang('user_type') ?></label>
                <select name="user_role" id="user_role" class="form-control" required>
                    <option value="1">Admin</option>
                    <option value="2">Normal</option>
                </select>
            </div>
             <div class="form-group">
                <label for="image"><?= lang('UploadImg') ?></label>
                <input type="file" name="user_image" class="form-control">
            </div>

            <button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('edit_user') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="edit_result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">

            <div class="form-group">
                <label for="user_fname"><?= lang('first_name') ?></label>
                <input type="text" id="user_fname" name="user_fname" class="form-control" placeholder="<?= lang('first_name') ?>" required>
            </div>

            <div class="form-group">
                <label for="user_lname"><?= lang('last_name') ?></label>
                <input type="text" id="user_lname" name="user_lname" class="form-control" placeholder="<?= lang('last_name') ?>" required>
            </div>

            <div class="form-group">
                <label for="user_email"><?= lang('useremail') ?></label>
                <input type="text" id="user_email" name="user_email" class="form-control" placeholder="<?= lang('useremail') ?>" required>
            </div>

            <div class="form-group">
                <label for="user_insert_password"><?= lang('password') ?></label>
                <input type="password" id="user_insert_password" name="user_password" class="form-control" placeholder="<?= lang('password') ?>" >
            </div>   
           <!--  <div class="form-group">
                <label for="user_street"><?= lang('user_street') ?></label>
                <input type="text" id="user_street" name="user_street" class="form-control dep_location" placeholder="<?= lang('user_street') ?>" required>                
            </div>

            <div class="form-group">
                <label for="user_city"><?= lang('user_city') ?></label>
                <input type="text" id="user_city" name="user_city" class="form-control" placeholder="<?= lang('user_city') ?>" required>                
            </div>

            <div class="form-group">
                <label for="user_zip"><?= lang('user_zip') ?></label>
                <input type="text" id="user_zip" name="user_zip" class="form-control" placeholder="<?= lang('user_zip') ?>" required>                
            </div>


            <div class="form-group">
                <label for="user_state"><?= lang('user_state') ?></label>
                <input type="text" id="user_state" name="user_state" class="form-control" placeholder="<?= lang('user_state') ?>" required>                
            </div>

            <div class="form-group">
                <label for="user_country"><?= lang('user_country') ?></label>
                <input type="text" id="user_country" name="user_country" class="form-control" placeholder="<?= lang('user_country') ?>" required>                
            </div> -->

            <div class="form-group">
                <label for="user_country"><?= lang('status') ?></label>
                <select name="user_status" id="user_status" class="form-control" required>
                    <option value="1">Active</option>
                    <option value="2">Deactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="user_role"><?= lang('user_type') ?></label>
                <select name="user_role" id="user_role" class="form-control" required>
                    <option value="1">Admin</option>
                    <option value="2">Normal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image"><?= lang('UploadImg') ?></label>
                <input type="file" name="user_image" class="form-control">
            </div>

            <div class="form-group">
                <label for="image"></label>
                <img id="current_image" src=""/>
            </div>
            <input type="hidden" name="edit_id" id="edit_id" value="">
            <button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
        </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        get_datatable()
        
        //$('#data_table').DataTable().ajax.reload();
        
        function get_datatable() {
            $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url('/admincontroller/data_table_users') ?>",
                    "type": "POST"
                },
                "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                    }],
                'iDisplayLength': 100,
                "ordering": false
            });
        }

        //Insert Data 
        $.validator.setDefaults({ ignore: ":hidden:not(.select2)" });           
        $.validator.setDefaults({
            submitHandler: function () {
                $.ajax({
                    url: '<?php echo base_url('/admincontroller/add_user'); ?>',
                    type: "POST",
                    data: new FormData($('.insert_data')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function (xhr) {
                        $("#status_data").hide()
                        $(".insert_data #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
                    }, success: function (resp) {
                        $(".insert_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        if (resp.success_status) {
                            $('.insert_data')[0].reset();
                            $("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                            $('#data_table').DataTable().ajax.reload();
                            
                        } else {
                            $("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
                        }
                    }, error: function () {
                        $(".insert_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        console.log("Please try after some time");
                    }
                });
            }
        });


        $('.insert_data').validate({        
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

        $("body").on("click", ".user_action", function(){
            var get_title=$(this).attr('title');
            if(get_title=="Edit"){
                $("#edit_result_sts").html('');
                var table_nm=$(this).attr('data-id');
                var table_key=$(this).attr('data-key');
                var edit_id=$(this).attr('data-value');

                $.ajax({
                    url: '<?php echo base_url('/admincontroller/edit_users'); ?>',
                    type: "POST",
                    data: {'catalogue_id':edit_id, 'tablenm':table_nm, 'table_key': table_key},
                    dataType: "json",
                    beforeSend: function (xhr) {
                        $("#editdata_"+edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
                    }, success: function (resp) {
                        $("#editdata_"+edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                        $("#user_fname").val(resp.user_fname);
                        $("#user_lname").val(resp.user_lname);
                        $("#user_email").val(resp.user_email);                        
                        // $("#user_street").val(resp.user_street);
                        // $("#user_city").val(resp.user_city);
                        // $("#user_zip").val(resp.user_zip);
                        // $("#user_state").val(resp.user_state);
                        // $("#user_country").val(resp.user_country);
                        $("#user_status").val(resp.status);
                        $(".update_data select[name=user_role]").val(resp.user_role);
                        if(resp.user_identify==1){
                            $("#current_image").attr('src', resp.user_social_img);
                        } else {
                            if(resp.user_img != null){
                                $("#current_image").show();
                                $("#current_image").attr('src', "<?php echo base_url('assets/admin/upload/users/thumbnail/'); ?>"+resp.user_thumb_img);
                            } else {                                
                                $("#current_image").hide();
                            }
                        }
                        $("#edit_id").val(resp.user_id);                        
                        $("#editModal").modal("show");
                    }, error: function () {
                        
                    }
                });
            }
        });



        //Update Data with Validation
        $.validator.setDefaults({           
            
            submitHandler: function () {
                $.ajax({
                    url: '<?php echo base_url('/admincontroller/update_user'); ?>',
                    type: "POST",
                    data: new FormData($('.update_data')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function (xhr) {
                        $("#status_data").hide()
                        $(".update_data #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
                    }, success: function (resp) {
                        $(".update_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        if (resp.success_status) {
                            // $('.update_data')[0].reset();
                            $("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                            $('#data_table').DataTable().ajax.reload();
                            setTimeout(function(){                                
                                $("#editModal .close").click()
                            }, 700);
                        } else {
                            $("#edit_result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
                        }
                    }, error: function () {
                        $(".update_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        console.log("Please try after some time");
                    }
                });
            }
        });


        $('.update_data').validate({        
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

        //End Update Data with Validation
    });
</script>
