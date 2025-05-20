<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3><?= lang('login') ?> <a class="manual_login active" href="javascript:void(0)">Manual</a></h3>
				<div class="d-flex justify-content-end social_icon">
					<!-- <a href="<?php echo base_url('/logincontroller/auth/Facebook/'); ?>"><span><i class="fab fa-facebook-square"></i></span></a> -->

					<!-- <a href="<?php echo base_url('/logincontroller/auth/Google/'); ?>"><span><i class="fab fa-google-plus-square"></i></span></a> -->

				</div>
			</div>
			<div class="card-body">

				<div id="accordion">
					<div class="card-primary card_gmail">
						<div class="card-header1">
							<h4 class="card-title w-100">
								<a class="d-block w-100" id="gmail_auth" href="<?php echo base_url('/logincontroller/auth/Google/'); ?>" aria-expanded="false">
								<!-- <i class="fab fa-google-plus-g"></i> -->
								<img src="<?= base_url('assets/images/thumb_image.png'); ?>" alt="gmail login" />
								</a>
							</h4>
							<div class="form-check">
								<input type="checkbox" name="remember_me" class="form-check-input" id="remember_me_box_gmail" checked>
								<label class="form-check-label text-white" for="remember_me_box">Remember me</label>
							</div>

							

							<div class="card-footer">
								<?php if ($this->session->flashdata('showmsg')) { ?>
									<p style="color:#ca4f4f; font-weight: bold;"><?php echo $this->session->flashdata('showmsg'); ?></p>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="card-danger card_email">
						<div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
							<div class="card-body">
								<?php if ($this->session->flashdata('success')) { ?>
									<p style="color:#fff;"><?php echo $this->session->flashdata('success'); ?></p>
								<?php } ?>
								<form class="needs-validation" method="post" action="<?php echo base_url('/logincontroller/check_authenticate');
																						?>" novalidate>
									<div class="input-group form-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-user"></i></span>
										</div>
										<input type="text" class="form-control" placeholder="<?= lang('email_text') ?>" name="user_email" required>
										<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
									</div>

									<div class="input-group form-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-key"></i></span>
										</div>
										<input type="password" class="form-control" placeholder="<?= lang('password_text') ?>" name="user_password" required>
										<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
									</div>
									<div class="form-check">
										<input type="checkbox" name="remember_me" class="form-check-input" id="remember_me_box">
										<label class="form-check-label text-white" for="remember_me_box">Remember me</label>
									</div>
									<div class="form-group">
										<input type="submit" value="<?= lang('login_btn') ?>" class="btn float-right login_btn">
									</div>



								</form>
								<?php if ($this->session->flashdata('login_error')) { ?>
									<p style="color:#ca4f4f; font-weight: bold;"><?php echo $this->session->flashdata('login_error'); ?></p>
								<?php } ?>

								<div class="card-footer">
									<!-- <div class="d-flex justify-content-center">
                                            <p style="color: #fff;"><c?= lang('Dont_have_account_text') ?><a href="< ?php echo base_url('/register') ?>"> < ?= lang('register') ?></a></p>
                                    </div> -->

									<div class="d-flex justify-content-center">
										<p><a href="<?php echo base_url('/forgot-password'); ?>" style="color: #fff;"><?= lang('forgot_password_text') ?></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
$(function(){
	$("#gmail_auth").click(function(event){
		event.preventDefault();
		var get_link=$(this).attr('href');
		if($("#remember_me_box_gmail").prop("checked") == true){
            window.location.href=get_link+"?remember=yes";
        }
        else if($("#remember_me_box_gmail").prop("checked") == false){
            window.location.href=get_link;
        }
	});
});
</script>