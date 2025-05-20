<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= lang('graphics') ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><?= lang('graphics') ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div id="edit_result_sts" style="width:100%;"></div>
                    <form action="admin/graphics-save" id="upload-icon" class="update_data" method="post"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="icon_name" placeholder="Icon name" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="graphic" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" id="save_data" class="btn btn-sm btn-primary">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                        <div class="row">
                            <?php 
                                $search="";
                                if(isset($_REQUEST['name'])){
                                    $search=$_REQUEST['name']; 
                            } ?>
                            <form class="mb-3 col-sm-12" id="find_data">
                                <label for="Serach"><?= lang('search') ?></label>
                                <input class="form-control d-inline-block mr-1 w-auto" type="text" name="search" id="search" value="<?= $search; ?>" />
                                <input type="submit" value="Find" class="btn btn-sm btn-primary">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary clear_search">Clear</a>
                            </form>
                        </div>
                        <div class="card card-primary">
                            <div class="card-body">
                                <div id="card-group">
                                    <div class="filter-container p-0 row">
                                        <?php if(isset($graphics) && !empty($graphics)){?>
                                        <ul class="graphics_list">
                                            <?php foreach ($graphics as $graphics) { ?>
                                            <li>
                                                <div class="filtr-item graphics_details" id="icon-<?= $graphics->id;?>"
                                                    data-id="<?= $graphics->id; ?>">
                                                    <a href="javascript:void(0)"
                                                        class="tack_data_<?= $graphics->id;?> btn btn-danger btn-sm user_action edit_btn"
                                                        title="edit" data-trigger="hover"
                                                        id="updatedata_<?= $graphics->id;?> " data-id="graphics"
                                                        data-key="id" data-value="<?= $graphics->id;?> "
                                                        data-name="<?= $graphics->name;?> "
                                                        data-path="<?= $graphics->path;?>"
                                                        data-thumb="<?= $graphics->thumb;?>">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </a>

                                                    <a href="javascript:void(0)"
                                                        class="tack_data_<?= $graphics->id;?> btn btn-danger btn-sm user_action dlt-btn"
                                                        title="Delete" data-trigger="hover"
                                                        id="deletedata_<?= $graphics->id;?> " data-id="graphics"
                                                        data-key="id" data-value="<?= $graphics->id;?> "
                                                        data-path="<?= $graphics->path;?>"
                                                        data-thumb="<?= $graphics->thumb;?>"><i
                                                            class="fa fa-times"></i></a>

                                                    <a href="<?= $graphics->thumb;?>?text=<?= $graphics->id;?>"
                                                        data-toggle="lightbox" data-title="icon">
                                                        <img src="<?= $graphics->thumb;?>?text=<?= $graphics->id;?>"
                                                            class="img-fluid mb-2 " alt="white sample" />
                                                        <div class="text-center">
                                                            <span class="icon-title"
                                                                style="display: block;"><?= $graphics->name;?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                        <p class="paginationList"><?php echo $paginate['links']; ?></p>
                                        <?php }else{ ?>
                                        <p class="text-danger">There are no graphics.</p>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- Modal -->
            <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Graphics</h5>
                            <button type="button" id="closethisModal" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="updateGraphics" class="update_graphic_data" method="POST"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="name" placeholder="Icon name" class="form-control"
                                                required id="name">
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="graphic_file">
                                            <input type="hidden" class="form-control" name="id" id="my_id">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" id="update_form" class="btn btn-sm btn-primary"><?= lang('BtnSave') ?></button>
                                    </div>
                                </div>
                            </form>
                            <img src="" alt="" id="my_file" class="img-fluid mb-2 " alt="white sample" />
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
$("#find_data").on('submit', function(event) {
    event.preventDefault();
    var text = $("#search").val();
    url = "<?php echo base_url('/graphicscontroller/index?name='); ?>"+text;
    window.location.replace(url);
})

$("body").on("click", ".clear_search", function(){
    var text = $("#search").val().length;
    if(text==0){
        swal({
            title: "Warning",
            text: "Search box already cleared",
            icon: "warning",
            button: "Close",
        });
    } else {
        window.location.href='<?php echo base_url('/admin/graphics') ?>';
    }
});

jQuery(document).ready(function($) {
    // $(document).on('submit','#upload-icon',function(e){
    //   e.preventDefault();
    $.validator.setDefaults({
        submitHandler: function() {
            $.ajax({
                url: '<?php echo base_url('/graphicscontroller/save'); ?>',
                type: "POST",
                data: new FormData($('.update_data')[0]),
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function(xhr) {
                    $("#status_data").hide();
                    $("#card-group .filter-container p.text-danger").hide();
                    $(".update_data #save_data").prop("disabled", true).addClass(
                        'btn-default').removeClass('btn-primary').html(
                        'Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>'
                    );
                },
                success: function(resp) {
                    $(".update_data #save_data").prop("disabled", false).addClass(
                        'btn-primary').removeClass('btn-default').html('Submit');
                    if (resp.success_status) {
                        $('.update_data')[0].reset();
                        //$("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                        location.reload();
                    } else {
                        $("#edit_result_sts").html(
                            '<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
                            resp.error_status + '</div>')
                    }
                },
                error: function() {
                    $(".update_data #save_data").prop("disabled", false).addClass(
                        'btn-primary').removeClass('btn-default').html('Submit');
                    console.log("Please try after some time");
                }
            });;
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

    //End Insert data with Validation

    $("body").on("click", ".user_action", function() {
        var get_title = $(this).attr('title');
        var tablenm = $(this).attr('data-id');
        var table_id = $(this).attr('data-key');
        var table_value = $(this).attr('data-value');
        var path = $(this).attr('data-path');
        var thumb = $(this).attr('data-thumb');

        if (get_title == "Delete") {
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
                            url: ajax_url + "/delete-data",
                            type: "post",
                            dataType: "json",
                            data: {
                                "table": tablenm,
                                'table_id': table_id,
                                'table_value': table_value,
                                'path': path,
                                'thumb': thumb
                            },
                            success: function(resp) {
                                if (resp.success) {
                                    $("#icon-" + table_value).fadeOut("slow");
                                    $("#icon-" + table_value).remove();
                                    setTimeout(function() {
                                        var count_graphic = $(
                                            "#card-group .filter-container .filtr-item"
                                        ).length;
                                        if (count_graphic == 0) {
                                            $("#card-group .filter-container p.text-danger")
                                                .show();
                                        }
                                    }, 400);
                                } else {
                                    swal(resp.error, {
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    }
                });
        } else {
            $("#updateModal").modal("show");
            var id = $(this).attr('data-value');
            var name = $(this).attr('data-name');
            var file = $(this).attr('data-path');

            $("#name").val(name);
            $("#my_id").val(id);
            $("#my_file").attr('src', file);

            $("#update_form").on("click", function() {
                /*   $.validator.setDefaults({ 
                      submitHandler: function() { */
                $.ajax({
                    url: '<?php echo base_url('/graphicscontroller/update'); ?>',
                    type: "POST",
                    data: new FormData($('.update_graphic_data')[0]),
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function(xhr) {
                        $("#status_data").hide();
                        $("#card-group .filter-container p.text-danger")
                            .hide();
                        $(".update_graphic_data #save_data").prop(
                            "disabled", true).addClass(
                            'btn-default').removeClass(
                            'btn-primary').html(
                            'Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>'
                        );
                    },
                    success: function(resp) {
                        location.reload();
                        $(".update_graphic_data #save_data").prop(
                            "disabled", false).addClass(
                            'btn-primary').removeClass(
                            'btn-default').html('Submit');
                        if (resp.success_status) {
                            $('.update_graphic_data')[0].reset();
                            //$("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                            $("#card-group .filter-container")
                                .prepend(
                                    '<div  class="filtr-item col-sm-2" id="icon-' +
                                    resp.id + '" data-id="' +
                                    resp.id +
                                    '"><a href="javascript:void(0)" class="tack_data_' +
                                    resp.id +
                                    ' btn btn-danger btn-sm user_action dlt-btn" title="Delete" data-trigger="hover" id="deletedata_' +
                                    resp.id +
                                    ' " data-id="graphics" data-key="id" data-value="' +
                                    resp.id + ' " data-path="' +
                                    resp.path +
                                    '" data-thumb="' + resp
                                    .thumb +
                                    '"><i class="fa fa-times"></i></a><img class="img-fluid mb-2" src="' +
                                    resp.thumb +
                                    '" alt="icon"><span class="icon-title" style="display:block;">' +
                                    resp.icon_name +
                                    '</span></div>');
                        } else {
                            $("#edit_result_sts").html(
                                '<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
                                resp.error_status + '</div>'
                            )
                        }
                    },
                    error: function() {
                        $(".update_graphic_data #save_data").prop(
                            "disabled", false).addClass(
                            'btn-primary').removeClass(
                            'btn-default').html('Submit');
                        console.log(
                            "Please try after some time");
                    }
                });;
                /* }
                }); */
            })

        }
    });
});

/* $("#closethisModal").on("click", function() {
	location.reload();
}); */

$("body").on("click", ".user_action", function() {
    var get_title = $(this).attr('title');
    if (get_title == "Edit") {
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
                $("#editdata_" + edit_id).html(
                    '<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>'
                );
            },
            success: function(resp) {
                $("#editdata_" + edit_id).html(
                    '<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
                $("#team_text").val(resp.name);
                $("#nick_title").val(resp.nick_title);
                $("#banner_desc").val(resp.team_desc);
                $("#editModal input[name=icon_path]").val(resp.logo);
                $("#editModal input[name=icon_thumb]").val(resp.thumb);
                if (resp.user_ids !== null) {
                    var userids = resp.user_ids.split(',');
                    for (var u = 0; u < userids.length; u++) {
                        $('#teamuser_box option[value="' + userids[u].trim() + '"]').attr(
                            'selected', 'selected');
                    }
                    $("#teamuser_box").select2("destroy").select2();
                }

                $("#edit_id").val(resp.id);
                if (resp.thumb !== null) {
                    $("#current_image").attr('src', resp.thumb);
                } else {
                    $("#current_image").css("height", "100px").attr('src',
                        "<?php echo base_url('assets/images/slider_blank.png'); ?>");
                }
                $("#editModal").modal("show");
            },
            error: function() {

            }
        });
    }
});
</script>
<style type="text/css">
.desktop_icon {
    background: none;
    margin: 20px -10px;
}

.desktop_icon .card {
    flex: 0 0 10%;
    max-width: 20%;
    padding: 0 10px 0px;
    border-radius: 0;
    box-shadow: none;
    background: none;
    border: 0;
}

.desktop_icon .card img {
    border: 1px solid #ddd;
    border-radius: 0.75rem;
    padding: 1rem;
    background: #ffffff;
}

.paginationList * {
    display: inline-block;
    background: #999;
    color: #fff;
    padding: 5px 10px;
    border: 1px solid #ffffff;
}

.paginationList strong {
    background: #555;
}

.paginationList *:hover,
.paginationList *:focus {
    background: #444444;
    color: #fff;
}

#card-group .card {

    position: relative;
}


#card-group .card .dlt-btn {
    background: transparent !important;
    border: oldlace;
    float: right;
    padding: 0;
    color: #dc3545 !important;
    text-align: right;
    position: absolute;
    right: 8px;
    top: -7px;
    z-index: 9999;
    opacity: 0;
}


#card-group .card:hover .dlt-btn {
    opacity: 1;
    cursor: 'pointer;';
}
</style>