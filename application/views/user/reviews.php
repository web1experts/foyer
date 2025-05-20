<?php
$review_title = $rev_btn_text="";
if($result_page->meta_value!=""){
    $page_meta=json_decode($result_page->meta_value);
    if($page_meta->review_title!=""){
        $review_title=$page_meta->review_title;
    }

    if($page_meta->review_btntext!=""){
        $rev_btn_text=$page_meta->review_btntext;
    }
}
?>

<div class="reviews-wrapper">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="heading-wrapper">
               <h2 class="secondary-heading text-center"><?= $review_title; ?></h2>
            </div>
         </div>
         <div class="col-md-12">
            <div class="reviews-wrapper-inner">
               <?php if(isset($reviews) && !empty($reviews)): ?>
                  <?php foreach ($reviews as $revws): ?>
                     <div class="reivew-card">
                        <div class="reivew-card-inner">
                            <h3 class="reivew-title"><?= $revws['first_name']." ". $revws['last_name'] ?></h3>
                            <div class="review-stars-wrapper">
                              <div class="review-stars-inner">
                                <?php for ($i=1; $i <= 5; $i++) { ?>
                                    <?php if($i <= $revws['rate']){ ?>                                 
                                      <span class="review-star fa fa-star checked"></span>
                                    <?php } else { ?>
                                      <span class="review-star fa fa-star"></span>
                                    <?php } ?>
                                <?php } ?>
                              </div>
                            </div>
                           <div class="reivew-content">
                              <p><?= $revws['message']; ?></p>
                           </div>
                           <i class="fas fa-quote-right"></i>
                        </div>
                     </div>
               <?php endforeach; ?>
            <?php endif; ?>

            </div>

            <?php 
            $first_name=$last_name=$email="";
            if($this->session->userdata('login_auth')){
               $session_data = $this->session->userdata('login_auth');
               $first_name=$session_data->user_fname;
               $last_name=$session_data->user_lname;
               $email=$session_data->user_email;
            ?>

            <div class="catalogue-footer text-center">
              <button type="button" class="btn btn-blue" data-toggle="modal" data-target="#rating_reviews"><?= $rev_btn_text; ?></button>
            </div>

          <?php } ?>

         </div>
      </div>
   </div>
</div>



<!-- The Modal -->
<div class="modal" id="rating_reviews">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <div class="heading-wrapper" style="margin: 0">
               <h2 class="secondary-heading"><?= lang('ratingus') ?></h2> </div>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="verify-reviews-wrapper">               
               <div class="row reviews-form-wrapper" style="padding: 0;">
                  <div class="col-md-12">
                     <form class="reviews-form validfrm_act" id="info_reviews" data-id="reviews" action="reviews-submit" method="post">                     
                        <div class="form-row">
                           <div class="form-group col-md-6">
                              <label><?= lang('name') ?></label>
                              <input type="text" class="form-control" id="" name="first_name" placeholder="<?= lang('name') ?> (required)" value="<?= $first_name; ?>" required> 
                           </div>
                           <div class="form-group col-md-6">
                              <label for=""><?= lang('surname') ?> </label>
                              <input type="text" class="form-control" id="" name="last_name"  placeholder="<?= lang('surname') ?> (optional)" value="<?= $last_name; ?>" required> 
                           </div>
                        </div>

                        <div class="form-group">
                           <label for=""><?= lang('useremail') ?></label>
                           <input type="email" class="form-control" id="" name="guest_email" value="<?= $email; ?>" placeholder="<?= lang('useremail') ?> (required)" required> 
                        </div>

                        <div class="form-group">
                           <div class="rate">
                               <input type="radio" id="star5" name="rate" value="5" />
                               <label for="star5" title="<?= lang('ratings5') ?>">5 stars</label>
                               <input type="radio" id="star4" name="rate" value="4" />
                               <label for="star4" title="<?= lang('ratings4') ?>">4 stars</label>
                               <input type="radio" id="star3" name="rate" value="3" />
                               <label for="star3" title="<?= lang('ratings3') ?>">3 stars</label>
                               <input type="radio" id="star2" name="rate" value="2" />
                               <label for="star2" title="<?= lang('ratings2') ?>">2 stars</label>
                               <input type="radio" id="star1" name="rate" value="1" checked />
                               <label for="star1" title="<?= lang('ratings1') ?>">1 star</label>                              
                           </div>
                        </div>

                        <div class="row" style="margin-bottom: 15px;">
                           <legend class="col-form-label col-sm-12 pt-0"><?= lang('recomend_us') ?></legend>
                           <div class="col-sm-12">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="Yes" checked>
                                 <label class="form-check-label" for="gridRadios1"> <?= lang('yes') ?> </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="No">
                                 <label class="form-check-label" for="gridRadios2"> <?= lang('no') ?> </label>
                              </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label for="comment"><?= lang('leave_msg') ?> (<?= lang('optional_field') ?>)</label>
                           <textarea class="form-control" rows="5" id="comment" name="message"></textarea>
                        </div>

                        <div class="form-group accept_terms_field" style="position: relative;">
                           <div class="form-check">
                              <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input accept_terms" value="1"><?= lang('accept') ?> <a href="#"><?= lang('terms') ?></a>  and <a href="#"><?= lang('privacy_policy') ?></a>
                              </label>
                           </div>

                           <input type="text" class="form-control accepted_terms" name="accept_terms" style="visibility: hidden; position: absolute;" required> 
                        </div>                           
                        
                        <button type="submit" class="btn btn-blue" id="save_data"><?= lang('send_btn') ?></button>

                        <div class="form-group" id="status_data" style="display: none; margin-top: 30px;"></div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>