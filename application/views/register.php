<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3><?= lang('register') ?></h3>
				<!--<div class="d-flex justify-content-end social_icon">					
					<span><i class="fab fa-twitter-square"></i></span>
				</div>-->
				<!-- <div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>					
				</div> -->
			</div>
			<div class="card-body">
				<?php if($this->session->flashdata('success')){ ?>											
					<p style="color:#fff;"><?php echo $this->session->flashdata('errors'); ?></p>						
				<?php } ?>	
				
				<form class="needs-validation" action="<?php echo base_url('/logincontroller/save_register') ?>" method="post" novalidate>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" placeholder="<?= lang('first_name') ?>" name="first_name" id="first_name" required>
						<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
					</div>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" placeholder="<?= lang('last_name') ?>" name="last_name" id="last_name" required>
						<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
					</div>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="email" class="form-control" placeholder="<?= lang('useremail') ?>" name="email" id="email" required>
						<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
					</div>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="<?= lang('password_text') ?>" name="pass" id="pass" required>
						<div class="invalid-feedback"><?= lang('required_field_error') ?></div>
					</div>

					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" placeholder="<?= lang('confirm_password_text') ?>" name="cpass" id="cpass" required>
						<div class="invalid-feedback" id="custom_message"><?= lang('required_field_error') ?></div>
					</div>
					
					<div class="row align-items-center remember">
						<input type="checkbox" name="terms" required> <?= lang('accept') ?> <a href="<?php echo base_url('/term-of-use') ?>"> <?= lang('terms') ?> </a>  <?= lang('and') ?>&nbsp;&nbsp;<a href="<?php echo base_url('/privacy-policy') ?>"> <?= lang('privacy_policy') ?></a>
						<div class="invalid-feedback" id="custom_message"><?= lang('required_field_error') ?></div>
					</div>

					<!-- <div class="row align-items-center remember">
						<a href="#"><input type="checkbox" name="privacy_policy" required>Privacy Policy</a>
						<div class="invalid-feedback" id="custom_message">Please fill out this field.</div>
					</div> -->
					<!-- <div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-phone fa-rotate-180"></i></span>
						</div>
						<input type="text" class="form-control" placeholder="Phone Number" name="phone_no" id="phone_no" required>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div> -->

					<div class="form-group">
						<input type="submit" value="Register" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					<?= lang('already_account_text') ?><a href="<?php echo base_url('/') ?>login"><?= lang('login_btn') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
