<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= lang('teams') ?></h1>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#insertModal"><?= lang('add_team') ?></a>
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
                        <?php if (isset($teams_data) && !empty($teams_data)) : ?>
                            <div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p></p>
                            </div>

                            <table id="data_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= lang('FieldImage') ?></th>
                                        <th><?= lang('TeamTitle') ?></th>
                                        <th><?= lang('TeamTabTitle') ?></th>
                                        <th><?= lang('FieldDesc') ?></th>
                                        <th><?= lang('companies') ?></th>
                                        <th><?= lang('users') ?></th>
                                        <th><?= lang('tabs') ?></th>
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
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('add_team') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="result_sts" style="width:100%;"></div>
                <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">
                    <div class="form-group">
                        <label for="title"><?= lang('TeamTitle') ?></label>
                        <input type="text" name="team_text" class="form-control" placeholder="<?= lang('TeamTitle') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="title"><?= lang('TeamTabTitle') ?></label>
                        <input type="text" name="nick_title" class="form-control" placeholder="<?= lang('TeamTabTitle') ?>" required>
                    </div>

                    <?php if(isset($subtabs) && !empty($subtabs)){ ?>
                        <div class="form-group">
                            <label for="Description">Subtabs</label>
                            <select class="select2" id="assign_subtabs" name="assign_subtabs[]" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                                <?php foreach($subtabs as $subtb): ?>
                                    <option value="<?= $subtb['subtab_id']; ?>"><?= $subtb['subtab_text']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label for="Description">Description</label>
                        <textarea type="text" name="slider_desc" class="form-control" placeholder="Description" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image"><?= lang('cmpuploadMedia') ?></label>
                        <br />
                        <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page="1" onclick="loadgraphics('<?= base_url() ?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
                    </div> 
                    
                    <label for="image"></label>
                    <img class="selected-img" src="" style="width: 150px;" />
                    <input type="hidden" name="icon_path" value="">
                    <input type="hidden" name="icon_thumb" value="">
                    <input type="hidden" name="graphic_id" value="">

                    <div class="form-group">
                        <label for="cat_text"><?= lang('companies') ?></label>
                        <select class="form-control select2" required style="width: 100%;" name="cmp_id" data-placeholder="<?= lang('selectuserbox') ?>">
                            <?php if (isset($company_data) && !empty($company_data)) : ?>
                                <?php foreach ($company_data as $company) : ?>
                                    <option value="<?= $company['cmp_id'] ?>"><?= $company['cmp_text'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
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
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('edit_team') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="edit_result_sts" style="width:100%;"></div>
                <form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">
                    <div class="form-group">
                        <label for="title"><?= lang('TeamTitle') ?></label>
                        <input type="text" id="team_text" name="team_text" class="form-control" placeholder="<?= lang('TeamTitle') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="title"><?= lang('TeamTabTitle') ?></label>
                        <input type="text" name="nick_title" id="nick_title" class="form-control" placeholder="<?= lang('TeamTabTitle') ?>" required>
                    </div>

                    <?php //if(isset($subtabs) && !empty($subtabs)){ ?>
                        <div class="form-group">
                            <label for="Description">Subtabs</label>
                            <select name="assign_subtabs[]" class="select2" id="edit_assign_subtabs" multiple="multiple" data-placeholder="Select a Subtab" style="width: 100%;">
                                
                            </select>
                        </div>
                    <?php //} ?>

                    <div class="form-group">
                        <label for="Description"><?= lang('FieldDesc') ?></label>
                        <textarea type="text" name="slider_desc" id="banner_desc" class="form-control" placeholder="Description" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image"><?= lang('cmpuploadMedia') ?></label>
                        <br />
                        <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page="1" onclick="loadgraphics('<?= base_url() ?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
                    </div>
                    <label for="image"></label>
                    <img id="current_image" class="selected-img" src="" style="width: 150px;" />
                    <input type="hidden" name="icon_path" value="">
                    <input type="hidden" name="icon_thumb" value="">
                    <input type="hidden" name="graphic_id" value="">
                    <div class="form-group">
                        <label for="cat_text"><?= lang('companies') ?></label>
                        <select class="form-control select2" required style="width: 100%;" name="cmp_id" id="cmp_ids" data-placeholder="<?= lang('selectuserbox') ?>">
                            <?php if (isset($company_data) && !empty($company_data)) : ?>
                                <?php foreach ($company_data as $company) : ?>
                                    <option value="<?= $company['cmp_id'] ?>"><?= $company['cmp_text'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="edit_id" id="edit_id" value="">
                        <button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function() {
        get_datatable()

        //$('#data_table').DataTable().ajax.reload();        
        function get_datatable() {
            $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                'iDisplayLength': 100,
                "ajax": {
                    "url": "<?php echo base_url('/teamcontroller/data_table_teams') ?>",
                    "type": "POST"
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "ordering": false
            });
        }


        //Insert Data 
        $.validator.setDefaults({
            ignore: ":hidden:not(.select2)"
        });
        $.validator.setDefaults({
            submitHandler: function() {
                $.ajax({
                    url: '<?php echo base_url('/teamcontroller/save_team'); ?>',
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
                            $("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
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

        //End Insert data with Validation



        $("body").on("click", ".user_action", function() {
            var get_title = $(this).attr('title');
            if (get_title == "Edit") {
                $("#edit_result_sts").html('');
                var table_nm = $(this).attr('data-id');
                var table_key = $(this).attr('data-key');
                var edit_id = $(this).attr('data-value');

                $.ajax({
                    url: '<?php echo base_url('/teamcontroller/edit_team'); ?>',
                    type: "POST",
                    data: {
                        'team_id': edit_id,
                        'tablenm': table_nm,
                        'table_key': table_key
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        $('#edit_assign_subtabs').empty();
                        $("#editdata_" + edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
                    },
                    success: function(resp) {
                        //console.log('----------------',resp);
                        $("#editdata_" + edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                        $("#team_text").val(resp.name);
                        $("#nick_title").val(resp.nick_title);
                        $("#banner_desc").val(resp.team_desc);
                        // $("#editModal input[name=icon_path]").val(resp.logo);
                        // $("#editModal input[name=icon_thumb]").val(resp.thumb);
                        $("#editModal input[name=graphic_id]").val(resp.graphic_id);
                        if (resp.company_id !== null) {
                            $('#cmp_ids option[value="' + resp.company_id.trim() + '"]').attr('selected', 'selected');
                            $("#cmp_ids").select2("destroy").select2();
                        }

                        $("#edit_id").val(resp.id);
                        if (resp.thumbnail !== null) {
                            $("#current_image").attr('src', resp.thumbnail);
                        } else {
                            $("#current_image").css("height", "100px").attr('src', "<?php echo base_url('assets/images/slider_blank.png'); ?>");
                        }

                        if (resp.sbtabs=="yes") {
                            $.each(resp.sbtabs_data, function(key, value) {
                                $('#edit_assign_subtabs').append($("<option></option>").attr("selected", "selected").attr("value", value.subtab_id).text(value.subtab_text)); 
                            });
                        }

                        if (resp.subtabs=="yes") {
                            $.each(resp.subtabs_data, function(subkey, sub_val) {   
                                $('#edit_assign_subtabs').append($("<option></option>").attr("value", sub_val.subtab_id).text(sub_val.subtab_text)); 
                            });
                        }
                        $("#edit_assign_subtabs").select2("destroy").select2();

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
                    url: '<?php echo base_url('/teamcontroller/update_team'); ?>',
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

        $(document).on('click', ".btn-warning", function() {
            if (typeof $(this).attr('data-target') !== 'undefined' && $(this).attr('data-target') !== false) {
                $(".selected-img").attr('src', '');
            }
        });

        //End Update Data with Validation
    });
</script>