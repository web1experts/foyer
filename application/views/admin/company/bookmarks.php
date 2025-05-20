
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= lang('bookmarks') ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">              
              <li class="breadcrumb-item active"><?= lang('bookmarks') ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          
          <div class="col-md-12">

            <div class="col-12">
            <div class="card card-primary">
             
              <div class="card-body">
                <div>
                  <nav class="navbar navbar-expand-lg">
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                </nav>

                <div class="col-md-12">
                  <div id="card-group">
                    <?php if(isset($bookmarks) && !empty($bookmarks)){
                      foreach ($bookmarks as $key => $bookmark) {                        
                      ?>
                        <div class="card wow zoomIn animated" id="<?= $bookmark['id'] ?>"><a target="_blank" href="<?= $bookmark['url']?>"><img class="card-img-top" src="<?= $bookmark['thumb']?>" alt="<?= $bookmark['name']?>"></a></div>
                    <?php } } ?>
                  </div>
                </div>
                <div>
            </div>
          </div>
          </div>
        </div>
        
        <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<script type="text/javascript">
  $(function(){
    reorder = $('#card-group');
    reorder.sortable({
        opacity: 0.7,
        items: "div:not(.unsortable)",
        update: function() {
           var counter = 0;
           var sortreOrder = [];
            reorder.children('div.card').each(function(){
                sortreOrder[counter] = $(this).attr('id');
            counter++;
            });
            var activeid = "<?php echo $company_id;?>";
            var activedatatype = 'company';
            $.ajax({
                method:"POST",
                url: ajax_url+'bookmarkuserscontroller/save_bookmark',
                dataType: "json",
                data: {
                    order:sortreOrder,
                    active:activeid,
                    datatype:activedatatype 
                },
                success: function(data){
                  swal(data.success_status, {
                        icon: "success"
                      });
                }
            }); 
        }
    });
  });
</script>
  