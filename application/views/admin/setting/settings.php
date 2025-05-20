<?php error_reporting(0); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Settings</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="card">
			<div class="row">
				<div class="card-body">
					<div class="col-12 col-sm-12 col-lg-12">
						<div class="card card-primary card-outline card-outline-tabs">
							<div class="card-header p-0 border-bottom-0">
								<ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="custom-tabs-header" data-toggle="pill" href="#custom-tabs-three-header" role="tab" aria-controls="custom-tabs-three-header" aria-selected="true">Header</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-tabs-footer" data-toggle="pill" href="#custom-tabs-three-footer" role="tab" aria-controls="custom-tabs-three-footer" aria-selected="false">Footer</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-tabs-email" data-toggle="pill" href="#custom-tabs-three-email" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Email</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-tabs-other" data-toggle="pill" href="#custom-tabs-three-other" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Bookmark Page Settings</a>
									</li>

								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content" id="custom-tabs-three-tabContent">
									<div class="tab-pane fade active show" id="custom-tabs-three-header" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">Header Settings</h3>
											</div>
											<!-- /.card-header -->
											<!-- form start -->
											<form action="<?= base_url('settingscontroller/insert_settings'); ?>" id="adding_header" method="post" novalidate="" autocomplete="off" enctype="multipart/form-data">

												<?php
												$header_string = array();
												if (isset($result_header) && !empty($result_header)) :
													$header_json = $result_header->meta_value;
													$header_string = json_decode($header_json);
													$up_id = $result_header->set_id;
													echo "<input type='hidden' name='header_edit' value='$up_id'>";
												endif;
												?>

												<div class="card-body">
													<div class="form-group">
														<label for="login_text">Login Text</label>
														<input type="text" class="form-control" id="login_text" name="login_text" placeholder="Login Text" value="<?php
																																									if (isset($header_string->login_text)) {
																																										echo $header_string->login_text;
																																									}
																																									?>">
														<input type="hidden" class="form-control" name="type" value="header">

													</div>

													<div class="form-group">
														<label for="register_text">Site Title</label>
														<input type="text" class="form-control" id="site_title" name="site_title" placeholder="Site Title" value="<?php
																																									if (isset($header_string->site_title)) {
																																										echo $header_string->site_title;
																																									}
																																									?>">
													</div>


													<!-- <div class="form-group">
                                                        <label for="logo_files">Upload Logo</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="logo_files" name="logo_files">
                                                                <label class="custom-file-label" for="logo_files">Header Logo</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="">Upload</span>
                                                            </div>
                                                        </div>
                                                    </div> -->

													<?php if (isset($header_string->logo_image)) { ?>
														<?php if ($header_string->logo_image != "") { ?>
															<div class="form-group hidden_logo_files">
																<img src="<?= base_url('/assets/admin/upload/settings/thumbnail/') . $header_string->logo_thumb; ?>" />
																<input type="hidden" name="logo_image" value="<?php echo $header_string->logo_image; ?>" />
																<input type="hidden" name="logo_thumb" value="<?php echo $header_string->logo_thumb; ?>" />
															</div>

															<div class="form-group">
																<a href="javascript:void(0)" id="logo_files" class="btn btn-sm btn-default reset_files_btn">Reset Logo</a>
															</div>
														<?php } ?>
													<?php } ?>




													<div class="form-group">
														<label for="logo_files">Favicon Image</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="logo_files" name="favicon_files">
																<label class="custom-file-label" for="favicon_files">Favicon</label>
															</div>
															<div class="input-group-append">
																<span class="input-group-text" id="">Upload</span>
															</div>
														</div>
													</div>



													<?php if (isset($header_string->fav_thumb)) { ?>
														<?php if ($header_string->fav_thumb != "") { ?>
															<div class="form-group hidden_favicon_files">
																<img src="<?= base_url('/assets/admin/upload/settings/thumbnail/') . $header_string->fav_thumb; ?>" />
																<input type="hidden" name="fav_image" value="<?php echo $header_string->fav_image; ?>" />
																<input type="hidden" name="fav_thumb" value="<?php echo $header_string->fav_thumb; ?>" />
															</div>

															<div class="form-group">
																<a href="javascript:void(0)" id="favicon_files" class="btn btn-sm btn-default reset_files_btn">Reset Favicon</a>
															</div>
														<?php } ?>
													<?php } ?>

												</div>
												<!-- /.card-body -->

												<div class="card-footer">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>


									<div class="tab-pane fade" id="custom-tabs-three-footer" role="tabpanel" aria-labelledby="custom-tabs-three-profile-footer">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">Footer Settings</h3>
											</div>
											<!-- /.card-header -->
											<!-- form start -->
											<form action="<?= base_url('settingscontroller/insert_settings'); ?>" id="adding_footer" method="post" novalidate="" autocomplete="off" enctype="multipart/form-data">
												<?php
												$footer_string = array();
												if (isset($result_footer) && !empty($result_footer)) :
													$footer_json = $result_footer->meta_value;
													$footer_string = json_decode($footer_json);
													$up_id = $result_footer->set_id;
													echo "<input type='hidden' name='footer_edit' value='$up_id'>";
												endif;
												?>

												<div class="card-body">
													<div class="form-group">
														<label for="copyright_text">Coyright Text</label>
														<input type="text" class="form-control" id="copyright_text" name="copyright_text" placeholder="Copyright Text" value="<?php
																																												if (isset($footer_string->copyright_text)) {
																																													echo $footer_string->copyright_text;
																																												}
																																												?>">
														<input type="hidden" class="form-control" name="type" value="footer">
													</div>
												</div>
												<!-- /.card-body -->

												<div class="card-footer">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>


									<div class="tab-pane fade" id="custom-tabs-three-email" role="tabpanel" aria-labelledby="custom-tabs-email">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">Email Settings</h3>
											</div>
										</div>
										<!-- /.card-header -->
										<!-- form start -->

										<div class="card card-default">
											<div class="card-header">
												<h3 class="card-title">Forgot Email Settings</h3>
											</div>
											<form action="<?= base_url('settingscontroller/insert_settings'); ?>" id="adding_header" method="post" novalidate="" autocomplete="off" enctype="multipart/form-data">


												<?php
												$fg_string = array();
												if (isset($result_fg) && !empty($result_fg)) :
													$fg_json = $result_fg->meta_value;
													$fg_string = json_decode($fg_json);
													$fg_id = $result_fg->set_id;
													echo "<input type='hidden' name='fg_edit' value='" . $fg_id . "'>";
												endif;
												?>

												<div class="card-body">
													<div class="form-group">
														<label for="reset_pwd_text">Reset Password Heading</label>
														<input type="text" class="form-control" id="reset_pwd_text" name="reset_pwd_text" placeholder="Reset Password Text" value="<?= $fg_string->reset_pwd_text; ?>">
														<input type="hidden" class="form-control" name="type" value="forgot_password">

													</div>

													<div class="form-group">
														<label for="reset_desc1">Reset Password Content 1</label>
														<textarea type="text" class="form-control" id="reset_desc1" name="reset_desc1" placeholder="Content 1"><?= $fg_string->reset_desc1; ?></textarea>
													</div>

													<div class="form-group">
														<label for="reset_desc2">Reset Password Content 2</label>
														<textarea type="text" class="form-control" id="reset_desc2" name="reset_desc2" placeholder="Content 2"><?= $fg_string->reset_desc2; ?></textarea>
													</div>

													<div class="form-group">
														<label for="reset_desc3">Reset Password Content 3</label>
														<textarea type="text" class="form-control" id="reset_desc3" name="reset_desc3" placeholder="Content 3"><?= $fg_string->reset_desc3; ?></textarea>
													</div>

													<div class="form-group">
														<label for="reset_desc2">Reset Button Text</label>
														<input type="text" class="form-control" id="reset_pwd_text" name="reset_btn_text" placeholder="Reset Button Text" value="<?= $fg_string->reset_btn_text; ?>">
													</div>

												</div>
												<!-- /.card-body -->

												<div class="card-footer">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>

									<div class="tab-pane fade" id="custom-tabs-three-other" role="tabpanel" aria-labelledby="custom-tabs-other">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">Bookmark Page Settings</h3>
											</div>
										</div>

										<!-- /.card-header -->
										<!-- form start -->

										<div class="card card-default">
											<div class="card-header">
												<h3 class="card-title">Page Title Settings</h3>
											</div>


											<form action="<?= base_url('settingscontroller/insert_settings'); ?>" id="adding_header" method="post" novalidate="" autocomplete="off" enctype="multipart/form-data">

												<?php
												$bookmark_string = array();
												if (isset($result_bookmark) && !empty($result_bookmark)) :
													$bg_json = $result_bookmark->meta_value;
													$bg_string = json_decode($bg_json);
													$up_id = $result_bookmark->set_id;
													echo "<input type='hidden' name='background_edit' value='$up_id'>";
												endif;

												$user_data = $this->session->userdata('login_auth');
												$checked =  ($user_data->label_visibility == 1) ? 'checked' : '';
												?>

												<div class="card-body">

													<input type="checkbox" id="hide_label" name="label_visibility" <?= $checked ?>><label class='text-dark'> Hide label</label>
													<div class="form-group">
														<label for="logo_files">Background Image</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="logo_files" name="favicon_files">
																<label class="custom-file-label" for="favicon_files">Background Image</label>
															</div>
															<div class="input-group-append">
																<span class="input-group-text" id="">Upload</span>
															</div>
														</div>
													</div>



													<?php if (isset($bg_string->logo_thumb)) { ?>
														<?php if ($bg_string->logo_thumb != "") { ?>
															<div class="form-group hidden_background_files">
																<img src="<?= base_url('/assets/admin/upload/settings/thumbnail/') . $bg_string->logo_thumb; ?>" />
																<input type="hidden" name="logo_image" value="<?php echo $bg_string->logo_image; ?>" />
																<input type="hidden" name="logo_thumb" value="<?php echo $bg_string->logo_thumb; ?>" />
															</div>

															<div class="form-group">
																<a href="javascript:void(0)" id="background_files" class="btn btn-sm btn-default reset_files_btn">Reset Background</a>
															</div>
														<?php } ?>
													<?php } ?>




													<div class="form-group">
														<label for="logo_files">Login Background Image</label>
														<div class="input-group">
															<div class="custom-file">
																<input type="file" class="custom-file-input" id="login_files" name="login_file">
																<label class="custom-file-label" for="login_files">Login Background Image</label>
															</div>
															<div class="input-group-append">
																<span class="input-group-text" id="">Upload</span>
															</div>
														</div>
													</div>



													<?php if (isset($bg_string->login_thumb)) { ?>
														<?php if ($bg_string->login_thumb != "") { ?>
															<div class="form-group hidden_login_background">
																<img src="<?= base_url('/assets/admin/upload/settings/thumbnail/') . $bg_string->login_thumb; ?>" />
																<input type="hidden" name="login_image" value="<?php echo $bg_string->login_image; ?>" />
																<input type="hidden" name="login_thumb" value="<?php echo $bg_string->login_thumb; ?>" />
															</div>

															<div class="form-group">
																<a href="javascript:void(0)" id="login_background" class="btn btn-sm btn-default reset_files_btn">Reset Background</a>
															</div>
														<?php } ?>
													<?php } ?>


													<input type="hidden" class="form-control" name="type" value="bookmark_background">
												</div>
												<!-- /.card-body -->

												<div class="card-footer">
													<button type="submit" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>

								</div>
							</div>
							<!-- /.card -->
						</div>

						<?php if ($this->session->flashdata('setting_errors')) { ?>
							<script>
								swal("<?php echo $this->session->flashdata('setting_errors'); ?>", {
									icon: "error"
								});
							</script>
						<?php unset($_SESSION['setting_errors']);
						} ?>

						<?php if ($this->session->flashdata('setting_success')) { ?>
							<script>
								swal("<?php echo $this->session->flashdata('setting_success'); ?>", {
									icon: "success"
								});
							</script>
						<?php unset($_SESSION['setting_success']);
						} ?>


					</div>
				</div>
			</div>
		</div>
	</section>
</div>



<!-- use jquery to add more fields -->
<div class="d-none social_default">
	<div class="inner-details inner-details-default input-group">
		<input type="text" class="form-control" id="social_icons_class" name="social_icons_class[]" placeholder="Icon Class i.g fa fa-facebook">
		<input type="text" class="form-control" id="social_url" name="social_url[]" placeholder="Social Url">
		<a class="btn btn-danger btn-sm  remove_section" href="javascript:void(0)"><i class="fa fa-times"></i></a>
	</div>
</div>

<div class="d-none menu_default1">
	<div class="inner-details inner-details-default input-group">
		<input type="text" class="form-control" id="menus_text1" name="menus_text1[]" placeholder="Menu Text">
		<input type="text" class="form-control" id="menus_link1" name="menus_link1[]" placeholder="Menu Link">
		<a class="btn btn-danger btn-sm  remove_section" href="javascript:void(0)"><i class="fa fa-times"></i></a>
	</div>
</div>

<div class="d-none menu_default2">
	<div class="inner-details inner-details-default input-group">
		<input type="text" class="form-control" id="menus_text2" name="menus_text2[]" placeholder="Menu Text">
		<input type="text" class="form-control" id="menus_link2" name="menus_link2[]" placeholder="Menu Link">
		<a class="btn btn-danger btn-sm  remove_section" href="javascript:void(0)"><i class="fa fa-times"></i></a>
	</div>
</div>
<!-- Jquery use -->

<style>
	.card.card-outline-tabs {
		border-top: 0px;
	}

	.inner-details {
		position: relative;
	}

	a.remove_section {
		position: absolute;
		right: 0;
		height: 38px;
		padding: 12px;
	}

	.inner-details-default {
		margin-bottom: 10px;
	}
</style>

<script>
	$(function() {
		$(".custom_parameters").click(function() {
			var get_id = $(this).attr('data-id');

			if (get_id == "social_icons") {
				var html = $(".social_default").html();
				$(".social_icons .inner-details-default:last-child").after(html);
			} else if (get_id == "add_menu1") {
				var html = $(".menu_default1").html();
				$(".add_menu1 .inner-details-default:last-child").after(html);
			} else if (get_id == "add_menu2") {
				var html = $(".menu_default2").html();
				$(".add_menu2 .inner-details-default:last-child").after(html);
			}
		});

		$("body").on("click", ".form-group .inner-details a.remove_section", function() {
			$(this).parent().remove();
		});

		$("body").on("click", ".reset_files_btn", function() {
			var reset_data = $(this).attr('id');
			$(".hidden_" + reset_data + " input").val('');
			$(".hidden_" + reset_data + " img").remove();
		});
	});
</script>
