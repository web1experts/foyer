<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('bookmarks') ?></h1>                    
                </div>

                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#insertModal"><?= lang('add_bookmark') ?></a>
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
                        <?php if (isset($bookmarks_data) && !empty($bookmarks_data)): ?>
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>

                            <table id="data_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= lang('FieldImage') ?></th>
                                        <th><?= lang('FieldTitle') ?></th>
                                        <th><?= lang('FieldUrl') ?></th>
                                       <!--  <th><?= lang('companies') ?></th>
                                        <th><?= lang('teams') ?></th>
                                        <th><?= lang('users') ?></th> -->
                                        <th><?= lang('FieldDesc') ?></th>
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



<!-- Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('add_bookmark') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">
            <div class="form-group">
                <label for="title"><?= lang('FieldTitle') ?></label>
                <input type="text" name="bookmark_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
            </div>
            <div class="form-group">
                <label for="title"><?= lang('FieldUrl') ?></label>
                <input type="text" name="bookmark_url" class="form-control" placeholder="<?= lang('FieldUrl') ?>" required>
            </div>


            <div class="form-group">
                <label for="title"><?= lang('FieldDesc') ?></label>
                <textarea name="bookmark_comment" class="form-control" placeholder="<?= lang('FieldDesc') ?>" rows='5'></textarea>
            </div>

           <div class="form-group">
                <label for="image"><?= lang('uploadMedia') ?></label>
                <br/>                
                <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page = "1" onclick="loadgraphics('<?= base_url()?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
                <input type="hidden" name="icon_path" value="">
                <input type="hidden" name="icon_thumb" value="">
                <input type="hidden" name="graphic_id" value="">
            </div>
            <img class="selected-img" src="" style="width: 150px;"/>
            
            <div class="form-group">
                <button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
            </div>
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
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('edit_bookmark') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="edit_result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">
            <div class="form-group">
                <label for="title"><?= lang('FieldTitle') ?></label>
                <input type="text" id="bookmark_text" name="bookmark_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
            </div>
            <div class="form-group">
                <label for="title"><?= lang('FieldUrl') ?></label>
                <input type="text" id="bookmark_url" name="bookmark_url" class="form-control" placeholder="<?= lang('FieldUrl') ?>" required>
            </div>


            <div class="form-group">
                <label for="title"><?= lang('FieldDesc') ?></label>
                <textarea name="bookmark_comment" id="bookmark_comment" class="form-control" placeholder="<?= lang('FieldDesc') ?>" rows='5'></textarea>
            </div>
            
            <div class="form-group">
                <label for="image"><?= lang('uploadMedia') ?></label>
                <br/>                
                <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page = "1" onclick="loadgraphics('<?= base_url()?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
            </div>

            <div class="form-group">
                <label for="image"></label>
                <img id="current_image" class="selected-img" src="" style="width: 150px;" />
                <input type="hidden" name="icon_path" value="">
                <input type="hidden" name="icon_thumb" value="">
                <input type="hidden" name="graphic_id" value="">
            </div>
            
            <!-- <div class="form-group">
                <label for="bookmark_users"><?= lang('users') ?></label>
                <select class="form-control select2" id="bookmark_users" style="width: 100%;" name="users[]" multiple="" data-placeholder="<?= lang('selectuserbox') ?>" >                    
                    <?php if(isset($users_data) && !empty($users_data)): ?>
                    <?php foreach ($users_data as $user): ?>
                        <option value="<?= $user['user_id'] ?>"><?= $user['user_fname'].' '.$user['user_lname'] ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>                
            </div> -->
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
          
        function get_datatable() {
		
            $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url('/bookmarkcontroller/data_table_bookmarks') ?>",
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
                    url: '<?php echo base_url('/bookmarkcontroller/save_bookmark'); ?>',
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
                            setTimeout(function(){                                
                                $("#insertModal").modal("hide");
                            }, 700);
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

        //End Insert data with Validation



        $("body").on("click", ".user_action", function(){
            var get_title=$(this).attr('title');
            if(get_title=="Edit"){
                $("#edit_result_sts").html('');
                var table_nm=$(this).attr('data-id');
                var table_key=$(this).attr('data-key');
                var edit_id=$(this).attr('data-value');

                $.ajax({
                    url: '<?php echo base_url('/bookmarkcontroller/edit_bookmark'); ?>',
                    type: "POST",
                    data: {'team_id':edit_id, 'tablenm':table_nm, 'table_key': table_key},
                    dataType: "json",
                    beforeSend: function (xhr) {
                        $("#editdata_"+edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
                    }, success: function (resp) {
                        $("#editdata_"+edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                        $("#bookmark_text").val(resp.name);
                        $("#edit_id").val(resp.id);
                        $("#bookmark_url").val(resp.url);
                        // $("#editModal input[name=icon_path]").val(resp.image);
                        // $("#editModal input[name=icon_thumb]").val(resp.thumb);
                        $("#editModal input[name=graphic_id]").val(resp.graphic_id);
                        if (resp.thumbnail !== null) {
                            $("#current_image").attr('src', resp.thumbnail);
                        } else {
                            $("#current_image").css("height", "100px").attr('src', "<?php echo base_url('assets/images/slider_blank.png'); ?>");
                        }
                        $("#editModal #bookmark_comment").val(resp.comment);
                        
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
                    url: '<?php echo base_url('/bookmarkcontroller/update_bookmark'); ?>',
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
                            $('.update_data')[0].reset();
                            $("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                            $('#data_table').DataTable().ajax.reload();
                            setTimeout(function(){                                
                                $("#editModal").modal("hide");
                            }, 600);
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

        $(document).on('click',".btn-warning",function(){
            var attr = $(this).attr('data-target');

            // For some browsers, `attr` is undefined; for others,
            // `attr` is false.  Check for both.
            if (typeof attr !== 'undefined' && attr !== false) {
                $(".selected-img").attr('src','');
            }
        });
    });


</script>
