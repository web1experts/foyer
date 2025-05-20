<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Change Password</h3>
				<!--<div class="d-flex justify-content-end social_icon">					
					<span><i class="fab fa-twitter-square"></i></span>
				</div>-->
			</div>
			<div class="card-body">
				<?php if($this->session->flashdata('twitter_success')){ ?>
					<p style="color:#fff;"><?= $this->session->flashdata('twitter_success'); ?></p>									
				<?php } ?>
				<form class="needs-validation" id="quickForm" action="<?php echo base_url('/'); ?>/logincontroller/setnew_pwd" method="post" autocomplete="off" novalidate>
	                <div class="input-group form-group" >	
	                	<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>                    
	                    <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Email" name="user_email" value="<?php
	                    if (isset($user_id)) {
	                        echo $user_id;
	                    }
	                    ?>" readonly="true" required>                    
	                </div>
	                <div class="input-group form-group">
	                	<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
	                    <input type="password" class="form-control" placeholder="New Password" name="user_password" id="pass" required>  
	                    <div class="invalid-feedback">Please fill out this field.</div>                
	                </div>

	                <div class="input-group form-group">	                    
	                	<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
	                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="cpass" required>
	                    <div class="invalid-feedback" id="custom_message">Please fill out this field.</div>
	                </div>

	                <div class="form-group">
						<input type="submit" value="Submit" class="btn float-right login_btn">
					</div>
	            </form>
			</div>
			<div class="card-footer">
				<?php if($this->session->flashdata('twitter_errors')){ ?>
					<p style="color:#ca4f4f; font-weight: bold;"><?= $this->session->flashdata('twitter_errors'); ?></p>									
				<?php } ?>				
				<div class="d-flex justify-content-center links">
					<?= lang('already_account_text') ?><a href="<?php echo base_url('/') ?>login" style="color:#ca4f4f; font-weight: bold;">Login</a>
				</div>
			</div>
		</div>
	</div>
</div>