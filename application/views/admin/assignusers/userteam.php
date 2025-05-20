<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <p class="text-dark">User:
                        <?= (isset($selected_user) && !empty($selected_user))?$selected_user->user_fname.' '. $selected_user->user_lname:''; ?>
                    </p>
                    <h1><?= lang('teams') ?></h1>

                </div>
                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                        data-target="#insertModal"><?= lang('add_team') ?></a>
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
                        <?php if (isset($teams_data) && $teams_data > 0): ?>
                        <div id="status_data" class="alert alert-danger alert-dismissible fade show"
                            style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p></p>
                        </div>

                        <table id="data_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= lang('FieldImage') ?></th>
                                    <th><?= lang('FieldTitle') ?></th>
                                    <th><?= lang('TabTitle') ?></th>
                                    <th><?= lang('FieldDesc') ?></th>
                                    <th><?= lang('tabs') ?></th>
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
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('add_team') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="result_sts" style="width:100%;"></div>
        <form method="post" id="quickForm" enctype="multipart/form-data" class="insert_data">
            <div class="form-group">
                <label for="title"><?= lang('FieldTitle') ?></label>
                <input type="text" name="team_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
            </div>

            <div class="form-group">
                <label for="title"><?= lang('NickTitle') ?></label>
                <input type="text" name="nick_title" class="form-control" placeholder="<?= lang('NickTitle') ?>" required>
            </div>

            <div class="form-group">
                <label for="Description">Description</label>
                <textarea type="text" name="slider_desc" class="form-control"   placeholder="Description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="image">Upload Media </label>
                <br/>                
                <a href="javascript::void(0)" type="button" class="btn btn-sm" data-page = "1" onclick="loadgraphics('<?= base_url()?>commoncontroller/geticons?user_id=');" data-target="#graphicModal">Select from library</a>
            </div>


            
                <label for="image"></label>
                <img class="selected-img" src="" style="width: 150px;"/>
                <input type="hidden" name="icon_path" value="">
                <input type="hidden" name="icon_thumb" value="">
            
            <div class="form-group">
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
                "url": "<?php echo base_url('/assignusercontroller/data_table_userteam') ?>",
                "type": "POST",
                "data": {
                    'user_id': "<?php echo $_REQUEST['user'] ?>",
                    'tb': "<?php echo $_REQUEST['tb'] ?>",
                    'assign_to':'user'
                },
            },
            "columnDefs": [{
                "targets": [0],
                "orderable": false
            }],
            "ordering": false
        });
    }

    //Insert Data 
        $.validator.setDefaults({ ignore: ":hidden:not(.select2)" });           
        $.validator.setDefaults({
            submitHandler: function () {
                $.ajax({
                    url: '<?php echo base_url('/teamcontroller/save_team'); ?>',
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
                            $("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>');
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


    $("body").on("click", ".admin_action", function() {
        var $this = $(this);
        var get_type = $this.attr('data-title');
        var get_value = $this.attr('data-value');

        if (get_type == "remove") {
            var message = "You want to remove?";
        } else {
            var message = "You want to add?";
        }
        swal({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "<?php echo base_url('assignusercontroller/add_team_to_user'); ?>",
                        type: "post",
                        dataType: "json",
                        data: {
                            'user_id': "<?php echo $_REQUEST['user'] ?>",
                            'tb': "teams",
                            'type': get_type,
                            'team_id': get_value,
                        },
                        success: function(resp) {
                            if (resp.success) {
                                if (get_type == "remove") {
                                    $this.removeClass('btn-danger').addClass(
                                            "btn-success").attr('data-title', 'add')
                                        .html("Assign");
                                } else {
                                    $this.removeClass('btn-success').addClass(
                                            "btn-danger").attr('data-title', 'remove')
                                        .html("Unassigned");;
                                }
                                swal(resp.success, {
                                    icon: "success",
                                });
                            } else {
                                swal(resp.error, {
                                    icon: "error",
                                });
                            }
                            
                        }
                    });
                }
            });

    });


    

});
</script>