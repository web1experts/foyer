<?php
$ct=$cft=$cfbt=$cfct=$cfpt=$cfrt=$cbt=$cfrmin=$cfrmax="";

if($result_page->meta_value!=""){
    $page_meta=json_decode($result_page->meta_value);
    
    if($page_meta->catalogue_title!=""){
        $ct=$page_meta->catalogue_title;
    }
    if($page_meta->catalogue_filter_title!=""){
        $cft=$page_meta->catalogue_filter_brand_title;
    } 
    if($page_meta->catalogue_filter_brand_title!=""){
        $cfbt=$page_meta->catalogue_filter_brand_title;
    }
    if($page_meta->catalogue_filter_catagory_title!=""){
        $cfct=$page_meta->catalogue_filter_catagory_title;
    }
    if($page_meta->catalogue_filter_popularity_title!=""){
        $cfpt=$page_meta->catalogue_filter_popularity_title;
    }
    if($page_meta->catalogue_filter_range_title!=""){
        $cfrt=$page_meta->catalogue_filter_range_title;
    }
    if($page_meta->catamore_btntxt!=""){
        $cbt=$page_meta->catamore_btntxt;
    }
    if($page_meta->catalogue_filter_maxrange_title!=""){
        $cfrmax=$page_meta->catalogue_filter_maxrange_title;
    }
    if($page_meta->catalogue_filter_minrange_title!=""){
        $cfrmin=$page_meta->catalogue_filter_minrange_title;
    }
}
?>


<div class="catalogue-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
           <div class="filter-header">
              <h2 class="secondary-heading"><?= $cft; ?></h2>
           </div>
           <div class="filter-wrapper">
              <div class="card">
                 <form method="post" class="catalogue_filter" action="catalogue-filter">
                    <article class="card-group-item">
                       <header class="card-header">
                          <h6 class="title"><?= $cfct; ?> </h6>
                       </header>
                       <div class="filter-content">
                          <div class="card-body">
                            <?php if(isset($cats) && !empty($cats)): ?>
                              <?php foreach ($cats as $prd_ct): ?>
                              <label class="form-check">
                                  <input class="form-check-input" type="checkbox" name="cats[]" value="<?php echo $prd_ct['cat_id']; ?>"> 
                                  <span class="form-check-label"><?php echo $prd_ct['cat_name']; ?></span> 
                              </label>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </div>
                       </div>
                    </article>
                    <!-- card-group-item.// -->
                    <article class="card-group-item">
                       <header class="card-header">
                          <h6 class="title"><?= $cfbt; ?> </h6>
                       </header>
                       <div class="filter-content">
                          <div class="card-body">
                             <?php if(isset($brands) && !empty($brands)): ?>
                              <?php foreach ($brands as $prd_brands): ?>
                              <label class="form-check">
                                  <input class="form-check-input" type="checkbox" name="brnads[]" value="<?php echo $prd_brands['brand_id']; ?>"> 
                                  <span class="form-check-label"><?php echo $prd_brands['brand_name']; ?></span> 
                              </label>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </div>
                          <!-- card-body.// -->
                       </div>
                    </article>
                    <!-- card-group-item.// -->
                    <article class="card-group-item">
                       <header class="card-header">
                          <h6 class="title"><?= $cfpt; ?> </h6>
                       </header>
                       <div class="filter-content">
                          <div class="card-body">
                             <label class="form-check">
                             <input class="form-check-input" type="radio" name="popularity" value="5"> 
                             <span class="form-check-label">5 Star</span> 
                             </label>
                             <label class="form-check">
                             <input class="form-check-input" type="radio" name="popularity" value="4"> 
                             <span class="form-check-label">4 Star</span> 
                             </label>
                             <label class="form-check">
                             <input class="form-check-input" type="radio" name="popularity" value="3"> 
                             <span class="form-check-label">3 Star</span> 
                             </label>
                          </div>
                          <!-- card-body.// -->
                       </div>
                    </article>
                    <!-- card-group-item.// -->
                    <article class="card-group-item">
                       <header class="card-header">
                          <h6 class="title"><?= $cfrt; ?> </h6>
                       </header>
                       <div class="filter-content">
                          <div class="card-body">
                             <div class="form-row">
                                <div class="form-group col-md-6">
                                   <label><?= $cfrmin; ?></label>
                                   <input type="number" class="form-control" name="price_min" id="inputEmail4" placeholder="$0"> 
                                </div>
                                <div class="form-group col-md-6 text-right">
                                   <label><?= $cfrmax; ?></label>
                                   <input type="number" class="form-control" name="price_max" placeholder="$1,0000"> 
                                </div>
                             </div>
                          </div>
                          <!-- card-body.// -->
                       </div>
                    </article>
                    <!-- card-group-item.// -->
                 </form>
              </div>
              <!-- card.// -->
           </div>
        </div>


        <div class="col-md-8">
          <div class="catalogue-header">
            <h2 class="text-center secondary-heading"><?= $ct; ?></h2> </div>
          <div class="catalogue-wrapper-inner">
            <div class="catalogue-grid">
              <div class="catalogue-grid-inner" id="filter_resp">
                <!-- Catalogue Card inner -->
                <?php if(isset($catalogue) && !empty($catalogue)): ?>
                  <?php foreach ($catalogue as $catlog): ?>
                    <?php 
                    $catalogue_img="";
                    if($catlog['catalog_image']=="") {                
                        $no_img= base_url('assets/images/slider_blank.png');
                        $catalogue_img="<img src='".$no_img."'/>";
                    } else {
                        $user_uploaded_img= base_url('assets/admin/upload/catalogues/').$catlog['catalog_image'];
                        $catalogue_img="<img src='".$user_uploaded_img."'/>";
                    }
                    ?>
                    <div class="catalogue-card">
                      <div class="catalogue-card-img"> 
                          <?= $catalogue_img; ?> 
                      </div>

                      <div class="catalogue-card-content">
                        <h3 class="catalogue-card-title"><?= $catlog['catalog_title']; ?></h3> 
                      </div>
                    </div>
                    <?php endforeach; ?>
               <?php endif; ?>
                
              </div>
            </div>
            <div class="catalogue-footer text-center">
              <button type="button" class="btn btn-blue"><?= $cbt; ?></button>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>