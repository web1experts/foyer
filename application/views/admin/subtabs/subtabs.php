<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('subtab') ?>
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#insertModal"><?= lang('add_subtab') ?></a>
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
                        <?php if (isset($users_data) && !empty($users_data)) : ?>
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>

                            <table id="data_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= lang('logo') ?></th>
                                        <th><?= lang('subtab_col') ?></th>
                                        <th><?= lang('subtab_subTitle') ?></th>
                                        <!-- <th><?= lang('users') ?></th>
                                        <th><?= lang('tab') ?></th> -->
                                        <th><?= lang('bookmarks') ?></th>
                                        <th><?= lang('Action') ?></th>
                                    </tr>
                                </thead>
                            </table>


                        <?php else : ?>
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
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('add_subtab') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="result_sts" style="width:100%;"></div>

                <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">
                    <div class="form-group">
                        <label for="title"><?= lang('subtabTitle') ?></label>
                        <input type="text" name="subtab_title" class="form-control" placeholder="<?= lang('subtabTitle') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="title"><?= lang('subtabsubTitle') ?></label>
                        <input type="text" name="subtabsub_title" class="form-control" placeholder="<?= lang('subtabsubTitle') ?>" required>
                    </div>


                    <div class="form-group">
                        <label for="image"><?= lang('cmpuploadMedia') ?></label>
                        <br />
                        <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page="1" onclick="loadgraphics('<?= base_url() ?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
                        <input type="hidden" name="icon_path" value="">
                        <input type="hidden" name="icon_thumb" value="">
                        <input type="hidden" name="graphic_id" value="">
                    </div>
                    <img class="selected-img" src="" style="width: 150px;" />
                    <div class="form-group">
                        <button id="save_data" type="submit" class="btn btn-primary">Submit</button>
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
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('EditCatalogue') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit_result_sts" style="width:100%;"></div>
                <form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">

                    <div class="form-group">
                        <label for="title"><?= lang('subtabTitle') ?></label>
                        <input type="text" id="subtab_title" name="subtab_title" class="form-control" placeholder="<?= lang('subtabTitle') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="title"><?= lang('subtabsubTitle') ?></label>
                        <input type="text" id="subtabsub_title" name="subtabsub_title" class="form-control" placeholder="<?= lang('subtabsubTitle') ?>" required>
                    </div>

                    

                    <div class="form-group">
                        <label for="image"><?= lang('cmpuploadMedia') ?></label>
                        <br />

                        <a href="javascript:void(0)" type="button" class="btn btn-sm" data-page="1" onclick="loadgraphics('<?= base_url() ?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
                    </div>


                    <label for="image"></label>
                    <img id="current_image" class="selected-img" src="" style="width: 150px;" />
                    <input type="hidden" name="icon_path" value="">
                    <input type="hidden" name="icon_thumb" value="">
                    <input type="hidden" name="graphic_id" value="">
                    <div class="form-group">
                        <input type="hidden" name="edit_id" id="edit_id" value="">

                        <input type="hidden" name="update_cmp_id" id="update_cmp_id"/>
                        <input type="hidden" name="update_team_id" id="update_team_id"/>

                        <button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
        get_datatable();

        //$('#data_table').DataTable().ajax.reload();        
        function get_datatable() {
            $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                'iDisplayLength': 100,
                "ajax": {
                    "url": "<?php echo base_url('/subtabscontroller/data_subtabtable') ?>",
                    "type": "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "ordering": false
            });
        }

        //Insert Data with validations    
        $.validator.setDefaults({

            submitHandler: function() {
                $.ajax({
                    url: '<?php echo base_url('/subtabscontroller/save_subtab'); ?>',
                    type: "POST",
                    data: new FormData($('.insert_data')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function(xhr) {
                        $("#status_data").hide()
                        $(".insert_data #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
                    },
                    success: function(resp) {
                        $(".insert_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        if (resp.success_status) {
                            $('.insert_data')[0].reset();
                            $("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>');
                            $('#data_table').DataTable().ajax.reload();
                            setTimeout(function() {
                                $("#insertModal .close").click()
                            }, 700);
                        } else {
                            $("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.error_status + '</div>')
                        }
                    },
                    error: function() {
                        $(".insert_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        console.log("Please try after some time");
                    }
                });
            }
        });

        $('.insert_data').validate({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //End Insert data with validation




        //Edit Data
        $("body").on("click", ".user_action", function() {
            var get_title = $(this).attr('title');
            if (get_title == "Edit") {
                $("#edit_result_sts").html('');
                var table_nm = $(this).attr('data-id');
                var table_key = $(this).attr('data-key');
                var edit_id = $(this).attr('data-value');

                $.ajax({
                    url: '<?php echo base_url('/subtabscontroller/edit_subtab'); ?>',
                    type: "POST",
                    data: {
                        'catalogue_id': edit_id,
                        'tablenm': table_nm,
                        'table_key': table_key
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        $("#editdata_" + edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
                    },
                    success: function(resp) {
                        $("#editdata_" + edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                        $("#subtab_title").val(resp.subtab_text);
                        $("#subtabsub_title").val(resp.subtab_nick_title);
                        $("#edit_id").val(edit_id);
                        $("#update_cmp_id").val(resp.cmp_id);
                        $("#update_team_id").val(resp.team_id);
                        
                        $("#current_image").attr('src', resp.thumbnail);

                        if (resp.user_id != '' && resp.user_id != null) {
                            var user_ids = resp.user_id.split(',');
                            for (var i = 0; i < user_ids.length; i++) {
                                jQuery('#company_users option[value="' + user_ids[i].trim() + '"]').attr('selected', 'selected');
                            }
                            $("#company_users").select2("destroy").select2();
                        }
                        $("#editModal").modal("show");
                    },
                    error: function() {

                    }
                });
            }
        });


        //Update Data with Validation
        $.validator.setDefaults({

            submitHandler: function() {
                $.ajax({
                    url: '<?php echo base_url('/subtabscontroller/update_subtab'); ?>',
                    type: "POST",
                    data: new FormData($('.update_data')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function(xhr) {
                        $("#status_data").hide()
                        $(".update_data #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
                    },
                    success: function(resp) {
                        console.log('------------------', resp);

                        $(".update_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        if (resp.success_status) {
                            $('.update_data')[0].reset();
                            $("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                            $('#data_table').DataTable().ajax.reload();
                            $("#editModal").modal("hide");
                        } else {
                            $("#edit_result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
                        }
                    },
                    error: function() {
                        $(".update_data #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
                        console.log("Please try after some time");
                    }
                });
            }
        });


        $('.update_data').validate({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //End Update Data with Validation
    });
</script>