<div class="profile-wrapper">
   <div class="container">
      <div class="bg-color">
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
                    $user_img="";
                    if($prf_data->user_identify==1){
                        $user_img="<img class='rounded-circle' width='150' src='".$prf_data->user_social_img."'/>";
                    } else {
                        if($prf_data->user_img == ""){
                            $no_img= base_url('assets/images/blank_img.png');
                            $user_img="<img class='rounded-circle' width='150' src='".$no_img."'/>";
                        } else {
                            $user_uploaded_img= base_url('assets/admin/upload/users/thumbnail/');
                            $user_img="<img class='rounded-circle' width='150' src='".$user_uploaded_img.$prf_data->user_thumb_img."'/>";
                        }
                    }

                    ?>

                    <a href="javascript:void(0)" class="change_profile_image"><?= $user_img; ?></a>
                    <div class="mt-3">
                        <h4><?php echo $prf_data->user_fname." ".$prf_data->user_lname; ?></h4>
                    </div>
                  </div>
               </div>
            </div>
            <div class="buttonwrapper">
               <a class="btn btn-blue mb-3" href="<?= base_url();?>"><?= lang('back_to_home') ?></a>
               <a class="btn btn-blue mb-3" href="<?= base_url('profile') ?>"><?= lang('account') ?></a>
               <button class="btn btn-blue mb-3" data-user="<?= $prf_data->user_id; ?>" data-page = "1" onclick="loadgraphics('<?= base_url('commoncontroller/geticons?user_id='.$prf_data->user_id);?>');" data-target="#graphicModal"><?= lang('graphics') ?></button>
               <a class="btn btn-blue mb-3" href="tab/manage_tabs"><?= lang('manage_tabs') ?></a>
               <a class="btn btn-danger close_account" href="<?= base_url('/logout') ?>"><?= lang('logout') ?></a>
            </div>
         </div>
         <div class="col-md-8">
       <div class="account-profile">
            <div class="profile-head">
               <h2><?= lang('account') ?></h2>
               <p><?= lang('personal_info_text') ?></p>
            </div>
            <div class="profile-info">
               <p><?= lang('Login email') ?></p>
               <p><?php echo $prf_data->user_email; ?> <a style="color: #212529" href="javascript:void(0)" class="email_info" data-id="<?= lang('email_changed_text') ?>"><i class="fas fa-info-circle"></i></a> <a style="color: #212529" href="javascript:void(0)" class="profile_handle edit_profile"><i class="fas fa-pencil-alt"></i></a></p>
            </div>
            <div class="profile-form-wrapper">
               <ul class="inner_profile">
                  <li><span><?= lang('name') ?>: </span> <strong><?= $prf_data->user_fname; ?> <?= $prf_data->user_lname; ?></strong></li>
                  <li><span><?= lang('email') ?>: </span> <strong><?= $prf_data->user_email; ?></strong></li>

                  <li><span><?= lang('phone_text') ?>: </span> <strong><?php if($prf_data->user_phone!="") { echo $prf_data->user_phone;  } else { echo "-N/A-"; }; ?></strong></li>

                  
               </ul>



               <form method="post" class="profile-update prf_update validate_form" style="display: none;">
                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="inputEmail4"><?= lang('first_name') ?></label>
                        <input type="text" class="form-control" id="inputEmail4" placeholder="<?= lang('first_name') ?>" name="first_name" value="<?= $prf_data->user_fname; ?>" required>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="inputPassword4"><?= lang('last_name') ?></label>
                        <input type="text" class="form-control" id="inputPassword4" placeholder="<?= lang('last_name') ?>" name="last_name" value="<?= $prf_data->user_lname; ?>" required>
                     </div>
                  </div>

                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="test"><?= lang('email') ?></label>
                        <input type="email" class="form-control" id="" placeholder="<?= lang('email') ?>" value="<?= $prf_data->user_email; ?>" name="user_email" readonly required/>
                        <small id="emailHelp" class="form-text text-muted"><?= lang('never_shared_text') ?></small>                        
                     </div>
                     <div class="form-group col-md-6">
                        <label for="phone_text"><?= lang('phone_text') ?></label>
                        <input type="text" class="form-control" id="phone_text" placeholder="<?= lang('for_example') ?> +541147434671" name="user_phone" value="prf_data" required>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="inputPassword"><?= lang('password') ?></label>
                     <input type="text" class="form-control" id="" placeholder="<?= lang('password') ?>" name="password" value="" >
                     <span>Leave blank if you don't want to update password</span>
                  </div>
                  <div class="form-group">
                  <input type="checkbox" id="hide_label" name="label_visibility" value="<?= $prf_data->label_visibility; ?>"<?php if ($prf_data->label_visibility == 1){?> checked <?php } else { ?>  <?php } ?>>  <label for="hide label"><?= lang('Hide_label') ?></label>
                  </div>
                  <button id="update_btn" type="submit" class="btn btn-blue"><?= lang('update_info_btn') ?></button>
               </form>

               <div id="result_sts" style="display: none; margin-top: 20px;"></div>
            </div>
      </div>
         </div>
      </div>
    </div>
   </div>
</div>


<script>
   $.validator.setDefaults({
      submitHandler: function () {
          $.ajax({
              url: '<?php echo base_url('/update-profile'); ?>',
              type: "POST",
              data: new FormData($('.prf_update')[0]),
              dataType: "json",
              contentType: false,
              processData: false,
              beforeSend: function (xhr) {
                  $("#result_sts").hide();
                  $(".prf_update #update_btn").prop("disabled", true).addClass('btn-default').removeClass('btn-blue').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
              }, success: function (resp) {
                  $("#result_sts").show();
                  $(".prf_update #update_btn").prop("disabled", false).addClass('btn-blue').removeClass('btn-default').html('Update Information');
                  if (resp.success_status) {
                      swal("Done!", resp.success_status, "success");                      
                      $("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>');
                  } else {
                      swal("Error!", resp.error_status, "error");
                      $("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.error_status + '</div>')
                  }
              }, error: function () {
                  $(".prf_update #update_btn").prop("disabled", false).addClass('btn-blue').removeClass('btn-default').html('Update Informations');
                  console.log("Please try after some time");
              }
          });
      }
  });
</script>