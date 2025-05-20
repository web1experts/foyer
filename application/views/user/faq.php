<?php
$faq_title="";
if($result_page->meta_value!=""){
    $page_meta=json_decode($result_page->meta_value);
    if($page_meta->faq_title!=""){
        $faq_title=$page_meta->faq_title;
    }
}
?>
<div class="heading-wrapper">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h2 class="secondary-heading text-center"><?= $faq_title; ?></h2>
         </div>
      </div>
   </div>
</div>
<div class="faq-wrapper">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="faq-box-wrapper">
               <?php if(isset($faq) && !empty($faq)): ?>
                  <?php foreach ($faq as $faqs): ?>
                     <div class="faq-box">
                        <h3 class="faq-heading"><?= $faqs['faq_question']; ?></h3>
                        <span class="faq-ans"><?= $faqs['faq_answer']; ?></span>
                     </div>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>