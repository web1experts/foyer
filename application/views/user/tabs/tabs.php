<!-- Content Wrapper. Contains page content -->
<div class="profile-wrapper">
	<div class="container">
		<div class="bg-color">

			<!-- Default box -->
			<div class="row gutters-sm">
				<div class="col-md-4 mb-3 profile-side">
					<div class="card mb-3">
						<div class="card-body">
							<div class="d-flex flex-column align-items-center text-center">
								<form method="post" class="profile-update prfimg_update" style="visibility: hidden; position: absolute;">
									<input type="file" name="profile_image">
									<input type="submit">
								</form>

								<?php
								$user_img = "";
								$prf_data = $this->session->userdata('login_auth');
								if ($prf_data->user_identify == 1) {
									$user_img = "<img class='rounded-circle' width='150' src='" . $prf_data->user_social_img . "'/>";
								} else {
									if ($prf_data->user_img == "") {
										$no_img = base_url('assets/images/blank_img.png');
										$user_img = "<img class='rounded-circle' width='150' src='" . $no_img . "'/>";
									} else {
										$user_uploaded_img = base_url('assets/admin/upload/users/thumbnail/');
										$user_img = "<img class='rounded-circle' width='150' src='" . $user_uploaded_img . $prf_data->user_thumb_img . "'/>";
									}
								}

								?>

								<a href="javascript:void(0)" class="change_profile_image"><?= $user_img; ?></a>
								<div class="mt-3">
									<h4><?php echo $prf_data->user_fname . " " . $prf_data->user_lname; ?></h4>
								</div>
								
							</div>
						</div>
					</div>
					<div class="buttonwrapper">
						<a class="btn btn-blue mb-3" href="<?= base_url(); ?>"><?= lang('back_to_home') ?></a>
						<a class="btn btn-blue mb-3" href="<?= base_url('profile') ?>"><?= lang('account') ?></a>
						<button class="btn btn-blue mb-3" data-user="<?= $prf_data->user_id; ?>" data-page="1" onclick="loadgraphics('<?= base_url('commoncontroller/geticons?user_id=' . $prf_data->user_id); ?>');" data-target="#graphicModal"><?= lang('graphics') ?></button>
						<a class="btn btn-blue mb-3" href="<?= base_url('/tab/manage_tabs'); ?>"><?= lang('manage_tabs') ?></a>
						<a class="btn btn-danger close_account" href="<?= base_url('/logout') ?>"><?= lang('logout') ?></a>
					</div>
				</div>
				<div class="col-md-8">
					<div class="account-profile">
						<?php if (isset($tabs_data) && !empty($tabs_data)) : ?>
							<div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<p></p>
							</div>

							<table id="data_table" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th><?= lang('FieldTitle') ?></th>
										<th><?= lang('NickTitle') ?></th>
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
			<!-- /.card -->
		</div>
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('edit_tab') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="edit_result_sts" style="width:100%;"></div>
				<form method="post" id="quickForm" enctype="multipart/form-data" class="update_data">
					<div class="form-group">
						<label for="title"><?= lang('FieldTitle') ?></label>
						<input type="text" id="tab_text" name="tab_text" class="form-control" placeholder="Title" required>
					</div>

					<div class="form-group">
						<label for="title"><?= lang('NickTitle') ?></label>
						<input type="text" name="tab_subtext" id="tab_subtext" class="form-control" placeholder="<?= lang('NickTitle') ?>" required>
					</div>
					<input type="hidden" name="type" value="tab" />
					<input type="hidden" name="tab_for" value="user">
					<input type="hidden" name="edit_id" id="edit_id" value="">
					<button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
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
				"ajax": {
					"url": "<?php echo base_url('/tabcontroller/data_table_tabs') ?>",
					"type": "POST"
				},
				"columnDefs": [{
					"targets": [0],
					"orderable": false
				}],
				"ordering": false
			});
		}




		$("body").on("click", ".user_action", function() {
			var get_title = $(this).attr('title');
			if (get_title == "Edit") {
				var table_nm = $(this).attr('data-id');
				var table_key = $(this).attr('data-key');
				var edit_id = $(this).attr('data-value');

				$.ajax({
					url: '<?php echo base_url('/tabcontroller/edit_tab'); ?>',
					type: "POST",
					data: {
						'tab_id': edit_id,
						'tablenm': table_nm,
						'table_key': table_key
					},
					dataType: "json",
					beforeSend: function(xhr) {
						$("#editdata_" + edit_id).html('<i class="fas fa-sync-alt fa-spin" style="font-size: 14px; color: #fff;"></i>');
					},
					success: function(resp) {
						$("#editdata_" + edit_id).html('<i class="fa fa-pencil-alt" style="font-size: 14px; color: #fff;"></i>');
						$("#tab_text").val(resp.title);
						$("#tab_subtext").val(resp.sub_title);

						$("#edit_id").val(resp.id);

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
					url: '<?php echo base_url('/tabcontroller/update_tab'); ?>',
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
