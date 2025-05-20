<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3><?= lang('forgot_password') ?></h3>
				<!--<div class="d-flex justify-content-end social_icon">					
					<span><i class="fab fa-twitter-square"></i></span>
				</div>-->
			</div>
			<div class="card-body">
				<?php if($this->session->flashdata('success')){ ?>
					<p style="color:#fff;"><?= $this->session->flashdata('success'); ?></p>									
				<?php } ?>
				<form class="needs-validation" action="<?php echo base_url('/logincontroller/check_email') ?>" method="post" novalidate>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="email" class="form-control" placeholder="<?= lang('email_text') ?>" name="user_email" id="email" required>
						<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
					</div>

					<div class="form-group">
						<input type="submit" value="<?= lang('forgot_btn') ?>" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<?php if($this->session->flashdata('errors')){ ?>
					<p style="color:#ca4f4f; font-weight: bold;"><?= $this->session->flashdata('user_errors'); ?></p>
				<?php } ?>

				<div class="d-flex justify-content-center links">
					<?= lang('already_account_text') ?><a href="<?php echo base_url('/login') ?>" style="color:#ca4f4f; font-weight: bold;"><?= lang('login') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>