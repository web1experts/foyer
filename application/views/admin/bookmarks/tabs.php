<?php

$sorted_tabs_arr = (isset($alltabs['sorted_data'])) ? $alltabs['sorted_data'] : [];
$cmp_data = (isset($alltabs['company_tabs'])) ? $alltabs['company_tabs'] : [];
$team_data = (isset($alltabs['team_tabs'])) ? $alltabs['team_tabs'] : [];
$usertabs = (isset($alltabs['usertabs'])) ? $alltabs['usertabs'] : [];
$current_user = $this->session->userdata('login_auth');

if ($current_user->user_role == '1') :
?>
	<input type="hidden" name="userid" value="<?= $user_id ?>">
<?php endif; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><?= lang('tabs') ?></h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item active"><?= lang('tabs') ?></li>
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

				<div class="col-md-12">

					<div class="col-12">
						<div class="card card-primary">

							<div class="card-body">
								<div>
									<nav class="navbar navbar-expand-lg">
										<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
											<span class="navbar-toggler-icon"></span>
										</button>

										<div class="collapse navbar-collapse" id="navbarSupportedContent">

											<ul class="navbar-nav mr-auto ui-sortable bookmark_tabs" unselectable="on" id="tabs-list">
												<?php
												if (isset($sorted_tabs_arr) && is_array($sorted_tabs_arr) && !empty($sorted_tabs_arr)) {
													for ($i = 0; $i < count($sorted_tabs_arr); $i++) {

												?>

														<li class="nav-item bookmark_nav <?php if ($i == 0) {
																								echo "active";
																							} ?>" data-title="<?= $sorted_tabs_arr[$i]['name']; ?>" data-type="<?= $sorted_tabs_arr[$i]['type'] ?>" data-id="<?= $sorted_tabs_arr[$i]['id']; ?>">
															<a href="javascript:void(0)" class="nav-link bookmark_<?= $sorted_tabs_arr[$i]['type'] ?>" title="<?= $sorted_tabs_arr[$i]['name']; ?>" data-id="<?= $sorted_tabs_arr[$i]['id']; ?>" data-title="<?= $sorted_tabs_arr[$i]['type'] ?>"><?= $sorted_tabs_arr[$i]['name']; ?></a>
														</li>


													<?php }
												} else {
													if (isset($cmp_data) && !empty($cmp_data)) : ?>

														<?php $i = 1; ?>
														<?php foreach ($cmp_data as $all_cmp) {
														?>
															<li class="nav-item bookmark_nav <?php if ($i == 1) {
																									echo "active";
																								} ?>" data-title="<?= $all_cmp['cmp_nick_title']; ?>" data-type="company" data-id="<?= $all_cmp['cmp_id']; ?>">
																<a href="javascript:void(0)" class="nav-link bookmark_company" title="<?= $all_cmp['cmp_text']; ?>" data-id="<?= $all_cmp['cmp_id']; ?>" data-title="company"><?= $all_cmp['cmp_nick_title']; ?></a>
															</li>
															<?php $i++; ?>
														<?php } ?>
													<?php endif; ?>

													<?php if (isset($team_data) && !empty($team_data)) : ?>
														<?php foreach ($team_data as $all_team) { ?>
															<li class="nav-item bookmark_nav" data-title="<?= $all_team['nick_title']; ?>" data-type="team" data-id="<?= $all_team['id']; ?>">
																<a href="javascript:void(0)" class="nav-link bookmark_team" title="<?= $all_team['name']; ?>" data-id="<?= $all_team['id']; ?>" data-title="team"><?= $all_team['nick_title']; ?></a>
															</li>
															<?php $i++; ?>
														<?php } ?>
													<?php endif; ?>

													<li class="nav-item bookmark_nav" data-title="<?php echo @$userdata->user_fname . ' ' . @$userdata->user_lname; ?>" data-type="user" data-id="<?php echo @$userdata->user_id; ?>">
														<a class="nav-link" title="<?php echo @$userdata->user_fname . ' ' . @$userdata->user_lname; ?>" data-id="<?= @$userdata->user_id; ?>" data-title="user" href="javascript:;"><?php echo @$userdata->user_fname . ' ' . @$userdata->user_lname; ?></a>
													</li>

													<?php


													if (isset($usertabs) && !empty($usertabs)) :
														foreach ($usertabs as $tab) :
													?>
															<li class="nav-item bookmark_nav" data-title="<?php echo $tab['sub_title']; ?>" data-type="tabs" data-id="<?php echo $tab['id']; ?>">
																<a href="javascript:;" class="nav-link"><?php echo $tab['sub_title']; ?></a>
															</li>

												<?php endforeach;
													endif;
												} ?>

											</ul>

											<a href="javascript:;" id="insert-new-tab" data-toggle="modal" data-target="#inserttabModal"><i class="fa fa-plus"></i></a>

										</div>
									</nav>
								</div>
								<div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div id="card-group" class="card-group desktop_bookmark">
							<div class="card wow zoomIn unsortable">
								<a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal">
									<img class="card-img-top" src="<?= base_url('/assets/images/plus.png'); ?>">
								</a>
							</div>
						</div>
					</div>
					<!-- /.row -->
	</section>
	<!-- /.content -->
</div>

<div class="modal fade insert_modal" id="inserttabModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('addtab') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="result_sts" style="width:100%;"></div>
				<form method="post" id="quickForm" enctype="multipart/form-data" class="insert_form">
					<div class="form-group">
						<label for="title"><?= lang('FieldTitle') ?></label>
						<input type="text" name="tab_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
					</div>
					<div class="form-group">
						<label for="title"><?= lang('NickTitle') ?></label>
						<input type="text" name="tab_subtext" class="form-control" placeholder="<?= lang('NickTitle') ?>" required>
					</div>

					<input type="hidden" name="user_id" value="<?= $user_id ?>">
					<input type="hidden" name="type" value="tab" />
					<input type="hidden" name="tab_for" value="user">
					<button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="graphicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('graphics') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="card-group" class="card-group desktop_icon">
					<div class="filter-container p-0 row">
						<?php

						if (isset($graphics) && !empty($graphics)) {
							foreach ($graphics as $graphics) { ?>
								<div class="filtr-item col-sm-2" onclick="selecticon(this)" data-path="<?= $graphics->path ?>" data-thumb="<?= $graphics->thumb ?>" id="icon-<?= $graphics->id; ?>" data-id="<?= $graphics->id; ?>">
									<img class="card-img-top img-fluid mb-2" src="<?= $graphics->thumb; ?>" alt="icon">

								</div>
						<?php }

							if ($total_graphics > 25) {
								echo '<button type="button" data-page="1" onclick="load_more(this)" class="load-more" data-totalpage="' . ($total_graphics / 25) . '">Load More</button>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade insert_modal" id="insertbookmartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('addbookmark') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="result_sts" style="width:100%;"></div>
				<form method="post" id="quickbookmarkForm" enctype="multipart/form-data" class="insert_form">
					<div class="form-group">
						<label for="title"><?= lang('FieldTitle') ?></label>
						<input type="text" name="bookmark_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
					</div>
					<div class="form-group">
						<label for="title"><?= lang('bookmark_url') ?></label>
						<input type="text" name="bookmark_url" class="form-control" placeholder="<?= lang('FieldUrl') ?>" required>
					</div>
					<input type="hidden" name="company_id" value="" id="tab-company">
					<input type="hidden" name="team_id" value="" id="tab-team">
					<input type="hidden" name="user_id" value="" id="tab-user">
					<input type="hidden" name="tab_id" value="" id="tab-tabs">
					<div class="form-group bookmark-field">
						<input type="file" name="bookmark_image" class="form-control">
						<a href="javascript::void(0)" type="button" class="btn btn-sm" data-toggle="modal" data-target="#graphicModal"><span><b>OR </b> </span>Select from library</a>
						<input type="hidden" name="icon_path" value="">
						<input type="hidden" name="icon_thumb" value="">
					</div>
					<img class="selected-img" src="" />
					<div class="form-group">
						<input type="hidden" name="type" value="bookmark" />
						<button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	$(function() {
		$("body").on("click", ".bookmark_tabs .bookmark_nav a", function(event) {
			event.preventDefault();
			var $this = $(this);
			var type = $this.attr('data-title');
			var type_id = $this.attr('data-id');
			$(".bookmark_tabs li").removeClass("active");
			$(this).parent().addClass("active");
			// if(type=="company" || type=="team" || type == "user") {
			bookmark_tabs(type, type_id, $this);
			// }
		});

		$(document).on('click', ".insert_modalbox", function() {
			var data_target = $(this).attr('data-target');
			if (data_target == '#insertbookmartModal') {
				$(".selected-img").attr('src', '');
				$("#tab-" + $("li.bookmark_nav.active").data('type')).val($("li.bookmark_nav.active").data('id'));

			}
			var insert_form = $(data_target).find('.insert_form').attr('id');

			$(data_target).modal("show");


			//Form Validation and Submmit Event

			$.validator.setDefaults({
				submitHandler: function() {
					var subtext = $("input[name=tab_subtext]").val();
					$.ajax({
						url: '<?php echo base_url('/tabcontroller/save_tab'); ?>',
						type: "POST",
						data: new FormData($("#" + insert_form)[0]),
						dataType: "json",
						contentType: false,
						processData: false,
						beforeSend: function(xhr) {
							$("#status_data").hide()
							$(".insert_tabdata #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
						},
						success: function(resp) {
							$(".insert_tabdata #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
							if (resp.success_status) {
								$("#" + insert_form)[0].reset();
								$("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>');
								if (data_target !== '#insertbookmartModal') {
									$('<li class="nav-item bookmark_nav"><a class="nav-link" href="javascript:;">' + subtext + '</a></li>').insertBefore("li#insert-new-tab");
								} else {
									$("li.bookmark_nav.active").trigger('click');
								}
								setTimeout(function() {
									$(data_target).modal("show");
								}, 700);
							} else {
								$("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
							}
						},
						error: function() {
							$(".insert_tabdata #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
							console.log("Please try after some time");
						}
					});
				}
			});

			//$('.insert_tabdata').validate({
			$("#" + insert_form).validate({
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
		});


		function bookmark_tabs(type, type_id, $this) {
			$.ajax({
				url: ajax_url + "/bookmarks-tabs",
				type: "POST",
				data: {
					'type': type,
					'type_id': type_id
				},
				dataType: "json",
				beforeSend: function(xhr) {
					$(".desktop_bookmark").html('<div class="circleLoader"><div class="inner-circles-loader"></div></div>');
				},
				success: function(resp) {
					if (resp.code == 200) {
						var append_html = "";
						$.each(resp.data, function(k, v) {
							var image_url = ajax_url + "/assets/admin/upload/bookmarks/thumbnail/" + v.thumb;
							var bookmark_url = v.url;
							var bookmark_id = v.id;
							var bookmark_title = v.name;
							append_html += "<div class='card wow zoomIn animated' id='" + bookmark_id + "' style='visibility: visible; animation-name: zoomIn;'>";
							append_html += "<a target='_blank' href='" + bookmark_url + "'>";
							append_html += "<img class='card-img-top' src='" + image_url + "' alt='" + bookmark_title + "'>";
							append_html += "</a>";
							append_html += "</div>";
						});
						$(".desktop_bookmark").html(append_html);
						booksmarkssort();
						$(".desktop_bookmark").append('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal"><img class="card-img-top" src="' + ajax_url + '/assets/images/plus.png"></a></div>');
					} else {
						Swal.fire({
							title: "Error!",
							text: resp.message,
							confirmButtonColor: '#000',
							confirmButtonText: 'Close',
							showCloseButton: true,
							icon: "info"
						});
						$(".desktop_bookmark").html(resp.message);
						$(".desktop_bookmark").html('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal"><img class="card-img-top" src="' + ajax_url + '/assets/images/plus.png"></a></div>');
					}
				},
				error: function() {
					console.log("Failed to load bookmark tabs");
				}
			});
		}



		list = $('#tabs-list');
		/* sortables */
		list.sortable({
			opacity: 0.7,
			update: function() {
				var user_id = '';
				if ($("input[name=userid]").length > 0) {
					user_id = $("input[name=userid]").val();
				}
				var sortOrder = [];
				list.children('li').each(function() {
					sortOrder.push($(this).data('type') + '&' + $(this).data('id') + '&' + $(this).data('title'));
				});
				//  console.log(ajax_url);
				$.ajax({
					method: "POST",
					url: ajax_url + 'Tabcontroller/savetab_order',
					data: {
						order: sortOrder,
						user_id: user_id
					},
					success: function(data) {
						console.log(data);
					}
				});
			}
		});


		if ($('#card-group').length > 0 && $('#card-group').children().length > 0) {
			booksmarkssort();
		}


	});

	function booksmarkssort() {
		reorder = $('#card-group');
		reorder.sortable({
			opacity: 0.7,
			update: function() {
				var counter = 0;
				var sortreOrder = [];
				reorder.children('div.card').each(function() {
					sortreOrder[counter] = $(this).attr('id');
					counter++;
				});
				var activeid = $("#tabs-list li.active a").attr('data-id');
				var activedatatype = $("#tabs-list li.active").attr('data-type');
				$.ajax({
					method: "POST",
					url: ajax_url + 'bookmarkuserscontroller/save_bookmark',
					data: {
						order: sortreOrder,
						active: activeid,
						datatype: activedatatype
					},
					success: function(data) {
						console.log(data);
					}
				});
			}
		});

	}
</script>
