<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('Brands') ?>
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#insertModal"><?= lang('addBrands') ?></a></h1>
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
                        <?php if (isset($brands_data) && !empty($brands_data)): ?>
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>

                            <table id="data_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th><?= lang('FieldTitle') ?></th>                                        
                                        <th><?= lang('CreatedDate') ?></th>
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
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('addBrands') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">
            <div class="form-group">
                <label for="brand_name"><?= lang('FieldTitle') ?></label>
                <input type="text" name="brand_name" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
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
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('editBrands') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="edit_result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">
            <div class="form-group">
                <label for="brand_name"><?= lang('FieldTitle') ?></label>
                <input type="text" id="brand_name" name="brand_name" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
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
                    "url": "<?php echo base_url('/cataloguecontroller/data_table_brands') ?>",
                    "type": "POST"
                },
                "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                    }]
            });
        }


        //Insert Data            
        $.validator.setDefaults({           
            
            submitHandler: function () {
                $.ajax({
                    url: '<?php echo base_url('/cataloguecontroller/save_brand'); ?>',
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
                                $("#insertModal .close").click()
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
                var table_nm=$(this).attr('data-id');
                var table_key=$(this).attr('data-key');
                var edit_id=$(this).attr('data-value');

                $.ajax({
                    url: '<?php echo base_url('/cataloguecontroller/edit_catalogue'); ?>',
                    type: "POST",
                    data: {'catalogue_id':edit_id, 'tablenm':table_nm, 'table_key': table_key},
                    dataType: "json",
                    beforeSend: function (xhr) {
                        $("#editdata_"+edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
                    }, success: function (resp) {
                        $("#editdata_"+edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                        $("#brand_name").val(resp.brand_name);
                        $("#edit_id").val(resp.brand_id);                        
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
                    url: '<?php echo base_url('/cataloguecontroller/update_brand'); ?>',
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
                                $("#insertModal .close").click()
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