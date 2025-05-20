
<div class="done_editing" style="display: none;">
  <a href="javascript:void(0)"><i class="fa fa-check"></i>Done</a>
</div>

<!-- Modal -->
<div class="modal fade" id="graphicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog graphic_modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('graphics') ?></h5>
        <button type="button" class="close"data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="col-md-12">
                <div id="edit_result_sts" style="width:100%;"></div>
                <form action ="admin/graphics-save" id="upload-icon" class="update_graphic" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                              <label>Graphic Title</label>
                              <input type="text" name="icon_name" class="form-control" placeholder="<?= lang('iconText') ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                              <label>Choose Graphic</label>
                              <input type="file" class="form-control" name="graphic" required>
                            </div>
                        </div>
                        <div class="col-lg-2 text-md-right text-sm-right">
                          <button type="submit" id="save_data" class="btn btn-sm btn-primary"><?= lang('BtnSave') ?></button>
                        </div>
                    </div>
                </form>
              </div>
              <div class="col-lg-5 graphic-tab">
                        <form class="searchForm" method="post" action="<?= base_url('/commoncontroller/geticons');?>" id="media_form_search" novalidate="novalidate" _lpchecked="1">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <div class="form-input">
                                    <input type="text" name="search"  placeholder="Search" value="<?= @$search_term?>"  class="form-control search_field">
                                </div>
                                <input type="hidden" name="search_type" value="media">
                            </div>
                        </form>
                    </div>
              <hr>
              <div class="row" id="graphic-content"></div>
      </div>
    </div>
  </div>
</div>


<?php 
$CI = & get_instance();
$this->load->view('insert_bookmark_modal');
?>

<?php


  if (isset($result_bookmark) && !empty($result_bookmark)):
    $bg_json = $result_bookmark->meta_value;
    $bg_string = json_decode($bg_json);    
?>

<style type="text/css">
  body{
    background: url("<?php echo base_url('assets/admin/upload/settings/'.$bg_string->logo_image);?>");
  }

  #insertbookmartModal .modal-body{
      background: url("<?php echo base_url('assets/admin/upload/settings/'.$bg_string->logo_image);?>"); 
  }
</style>


<?php endif; ?>

<script>
$(function(){
  $("body").on("click", ".btn_test", function(){
      var get_link=$(".url_testing").val();
      if(get_link.length!=0){
        window.open(get_link,'_blank');
      }
  });

  $("body").on("click", "#bookmarkSearch_bar #close-all", function(){
      //$("#bookmarkSearch #close-all").on('click', function() {
      $("body").removeClass("modal-open");
      $(".modal-backdrop").remove();
      $('#bookmarkSearch_bar').removeClass("show").hide();
      
  });

  /*$("body").on("click", ".bookmark_tabs li:first-child a", function(){
      alert("testRock");
  });*/
  
});
</script>

<script type="text/javascript">
    new WOW().init();
  </script>
</body>
</html>