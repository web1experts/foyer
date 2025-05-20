<?php
$contact_title="";
if($result_page->meta_value!=""){
    $page_meta=json_decode($result_page->meta_value);
    if($page_meta->contact_title!=""){
        $contact_title=$page_meta->contact_title;
    }
}
?>
<div class="contact-wrapper">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="heading-wrapper">
               <h2 class="secondary-heading text-center"><?= $contact_title; ?></h2>
            </div>
         </div>
      </div>
      <div class="row contact-wrapper-inner">
         <div class="col-md-12">
            <?php 
            $first_name=$last_name=$email="";
            if($this->session->userdata('login_auth')){
               $session_data = $this->session->userdata('login_auth');
               $first_name=$session_data->user_fname;
               $last_name=$session_data->user_lname;
               $email=$session_data->user_email;
            }
            ?>


            <form class="contactform validfrm_act" id="info_contact" data-id="contact" action="contact-submit" method="post">
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="first_name">Name</label>
                     <input type="text" class="form-control" id="first_name" placeholder="Name (required)" name="first_name" value="<?= $first_name; ?>" required />
                  </div>
                  <div class="form-group col-md-6">
                     <label for="last_name">Surname </label>
                     <input type="text" class="form-control" id="last_name" placeholder="Surname (optional)" value="<?= $last_name; ?>" name="last_name">
                  </div>
               </div>

               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Email (required)" name="email" value="<?= $email; ?>" required/>
               </div>

               <div class="form-group">
                  <label for="subject">Subject</label>
                  <input type="text" class="form-control" id="subject" placeholder="" name="subject" required />
               </div>

               <div class="form-group">
                  <label for="comment">Leave a message</label>
                  <textarea class="form-control" rows="5" id="comment" name="message" required></textarea>
               </div>

               <div class="form-group accept_terms_field" style="position: relative;">
                 <div class="form-check">
                    <label class="form-check-label">
                       <input type="checkbox" class="form-check-input accept_terms" value="1"><?= lang('accept') ?> <a href="#"><?= lang('terms') ?></a>  and <a href="#"><?= lang('privacy_policy') ?></a>
                    </label>
                 </div>

                 <input type="text" class="form-control accepted_terms" name="accept_terms" style="visibility: hidden; position: absolute;" required> 
              </div>

               <button type="submit" class="btn btn-blue" id="save_data">Send</button>

               <div class="form-group" id="status_data" style="display: none; margin-top: 30px;"></div>
            </form>
         </div>
      </div>
   </div>
</div>