</div>
<!-- ./wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; <?= date('Y') ?> <a href="<?php echo base_url('/'); ?>">Bookmark</a>.</strong> All rights reserved.    
  </footer>
<!-- jQuery -->



<div class="modal fade" id="graphicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?= lang('graphics') ?></h5>
        <button type="button" class="close close_graphicModal">
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


<?php if(isset($site_menu) && !empty($site_menu)){ ?>
	<?php if($site_menu=="dashboard"){ ?>
		<!-- ChartJS -->
		<script src="<?=base_url('assets/admin/');?>plugins/chart.js/Chart.min.js"></script>
		<!-- jQuery Knob Chart -->
		<script src="<?=base_url('assets/admin/');?>plugins/jquery-knob/jquery.knob.min.js"></script>
		<!-- daterangepicker -->
		<script src="<?=base_url('assets/admin/');?>plugins/moment/moment.min.js"></script>
		<script src="<?=base_url('assets/admin/');?>plugins/daterangepicker/daterangepicker.js"></script>
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="<?=base_url('assets/admin/');?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
		<!-- Summernote -->
		<script src="<?=base_url('assets/admin/');?>plugins/summernote/summernote-bs4.min.js"></script>
		<!-- overlayScrollbars -->
		<script src="<?=base_url('assets/admin/');?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="<?=base_url('assets/admin/');?>dist/js/pages/dashboard.js"></script>
	<?php } ?>
<?php } ?>
<!-- Ekko Lightbox -->

<script src="<?=base_url('assets/admin/');?>plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('assets/admin/');?>dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=base_url('assets/admin/');?>dist/js/demo.js"></script>


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyC9acmWQ51FvAsgbk-VCLYlXG_IjO55ruo&libraries=places"></script>

<script>
function initialize() {
  var Depinput = document.getElementsByClassName('dep_location');
  var options = {
    types: ['(cities)'],
    componentRestrictions: {country: 'au'}
  };
  var autocomplete = new google.maps.places.Autocomplete(Depinput, options);
  
  var place = autocomplete.getPlace();
  var lat = place.geometry.location.lat();
  var long = place.geometry.location.lng()
  console.log(place);
  console.log('Hii');      
}
</script>


<script>
  	$(function () {
    	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
      		event.preventDefault();
      		$(this).ekkoLightbox({
        		alwaysShowClose: true
      		});
    	});
	})

function load_more(element) {
        page = $(element).data('page');
        var total = Number($(element).data('page'));
        $(element).attr('data-page',total+1);
        $.ajax({
            url: ajax_url+"/Graphicscontroller/loadMoreGraphics?page=" + page,
            type: "GET",
            dataType: "json",
        }).done(function (data) {
            isLoading = false;
            if (data.result.length == 0) {
                isDataLoading = false;
                $('#loader').hide();
                return;
            }
            var html = '';
            for (var i = 0; i <data.result.length; i++) {
                html += '<div onclick="selecticon(this)" data-path="'+data.result[i].path+'" data-thumb="'+data.result[i].thumb+'" class="filtr-item col-sm-2" id="icon-'+data.result[i].id+'" data-id="'+data.result[i].id+'">';
                html += '<img class="card-img-top img-fluid mb-2" src="'+data.result[i].thumb+'" alt="icon">'; 
                html += '</div>';
            }
            $('#loader').hide();
            $('#card-group .filter-container').append(html).show('slow');
            if(Number($(element).data('totalpage')) == total+1){
                $(".load-more").hide();
            }
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('No response');
        });
    }

function selecticon(element){
    $("input[name=icon_path]").val($(element).data('path'));
    $("input[name=icon_thumb]").val($(element).data('thumb'));
    $("input[name=graphic_id]").val($(element).data('id'));
    $(".selected-img").attr('src',$(element).data('thumb'));
    $("#graphicModal").modal('hide');
    setTimeout(function(){
      $("body").addClass("modal-open");
    }, 500);
}

function loadgraphics(path = ''){
    $.get(path,function(data){
        $('#graphicModal .modal-body #graphic-content').html(data);
        $("#graphicModal").modal('show');
        // if($("#media_form input[name='search']").val() != ''){
        //   $("#media_form input[name='search']").focus();
        //   var tmpStr = $("#media_form input[name='search']").val();
        //   $("#media_form input[name='search']").val('');
        //   $("#media_form input[name='search']").val(tmpStr);
        // }
        iconvalidate();
    });
    
}

function iconvalidate(){
    $.validator.setDefaults({
    submitHandler: function () {
        $.ajax({
          url: ajax_url+'graphicscontroller/save',
          type: "POST",
          data: new FormData($('.update_graphic')[0]),
          dataType: "json",
          contentType: false,
          processData: false,
          beforeSend: function (xhr) {
              $("#status_data").hide()
              $("#upload-icon #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
          }, success: function (resp) {
              $("#upload-icon #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
              if (resp.success_status) {
                  $('.update_graphic')[0].reset();
                  //$("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
                  loadgraphics('commoncontroller/geticons?user_id='+currentuser_id);

                  $("#graphicModal .filter-container").prepend('<div class="filtr-item col-sm-4" onclick="selecticon(this)" data-path="'+resp.path+'" data-thumb="'+resp.thumb+'" id="icon-'+resp.id+'" data-id="'+resp.id+'"><img class="card-img-top img-fluid mb-4" src="'+resp.thumb+'" alt="icon"></div>');
              } else {
                  $("#edit_result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
              }
          }, error: function () {
              $("#upload-icon #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
              
          }
      });;
      }
    });

  $('.update_graphic').validate({        
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
    });

    //End Insert data with Validation
}

jQuery(document).on("click","div.custom-pagination a",function(e){
    e.preventDefault();
    loadgraphics(jQuery(this).attr('href'));
    iconvalidate();
});


$("body").on("click", ".close_graphicModal", function(){
    $("#graphicModal").modal("hide");
    setTimeout(function(){
      $("body").addClass("modal-open");
    }, 500);
});



$(document).on("keyup","#media_form_search input[type='text']", function () {
        var get_length = $(this).val().length;
        setTimeout(function () {
            $("#media_form_search").submit();
        }, 500);
    });

$(document).on('submit',"#media_form_search",function(e){
  e.preventDefault();
  loadgraphics($("#media_form_search").attr('action')+'?search='+$("#media_form_search input[name='search']").val());
        
});




</script>

<style>
.ekko-lightbox .modal-title {
    text-transform: capitalize;
}
</style>
</body>
</html>
