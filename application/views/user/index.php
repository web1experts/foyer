<section class="columnGrid">
	<div class="container-fluid">
		<div class="row">
			<!-- <div class="form-group">
				 hide_field -->
				<!-- <div class="row show-serach-form hide_field">
					<div class="Haga_Fom">
						<input type="text" class="form-control" id="search_keyword" placeholder="Type Here" name="search_keyword">
						<button class="btn btn-info" id="search-data">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</div>
				</div>

				<button class="btn btn-info" id="search_here">
					<i class="fa fa-search" aria-hidden="true"></i>
				</button> -->


		<!-- 	</div> --> 
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="search_res results" style="display: none;">
					<h2><span id="search_keyword">Bookmarks Results: #</span> </h2>
			        <div id="bookmarks"></div>
			    </div>

			    <div id="group_subtabs" class="desktop_subtabs">
					<!--  Ajax Data -->
				</div>

				<div id="card-group" class="card-group desktop_bookmark">
					<!--  Ajax Data -->
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade insert_modal" id="inserttabModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('addtab') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="result_sts" style="width:100%;"></div>
				<form method="post" id="quickForm" enctype="multipart/form-data" class="insert_form">
					<div class="form-group">
						<label for="title"><?= lang('FieldTitle') ?></label>
						<input type="text" name="tab_text" class="form-control" placeholder="<?= lang('FieldTitle') ?>" required>
					</div>
					<div class="form-group">
						<label for="title"><?= lang('NickTitle') ?></label>
						<input type="text" name="tab_subtext" class="form-control" placeholder="<?= lang('NickTitle') ?>" required>
					</div>
					<input type="hidden" name="type" value="tab" />
					<input type="hidden" name="tab_for" value="user">
					<?php if (isset($_GET['p_id']) && isset($_GET['tab']) && $_GET['tab'] == 'user') { ?>
						<input type="hidden" name="user_id" value="<?= base64_decode($_GET['p_id']) ?>">
					<?php } ?>
					<button id="save_data" type="submit" class="btn btn-primary"><?= lang('FieldSubmit') ?></button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="graphicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('graphics') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="card-group" class="card-group desktop_icon">
					<div class="filter-container p-0 row">
						<?php
						if (isset($graphics) && !empty($graphics)) {
							foreach ($graphics as $graphics) {
						?>
								<div class="filtr-item col-sm-2" onclick="selecticon(this)" data-path="<?= $graphics->path ?>" data-thumb="<?= $graphics->thumb ?>" id="icon-<?= $graphics->id; ?>" data-id="<?= $graphics->id; ?>">
									<img class="card-img-top img-fluid mb-2" src="<?= $graphics->thumb; ?>" alt="icon">
									<label class="this_is_label">This is Label</label>
								</div>
						<?php
							}
							if ($total_graphics > 25) {
								echo '<button type="button" data-page="1" onclick="load_more(this)" class="load-more" data-totalpage="' . ($total_graphics / 25) . '">Load More</button>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	jQuery(function() {
		//$('[data-toggle="tooltip"]').tooltip();
		$(document).on('click', ".insert_modalbox", function() {
			var data_target = $(this).attr('data-target');
			var subtab_status=$(this).attr('data_type');
			$(".subtab_status").val(subtab_status);
			if (data_target == '#insertbookmartModal') {
				$(".selected-img").attr('src', '');
				var active_tab_id = $("li.bookmark_nav.active").attr('data-id');
				var active_tab_type = $("li.bookmark_nav.active").attr('data-type');
				$(".tabs_data").attr('name', active_tab_type + "_id").val(active_tab_id);
				$(".tabs_type").val(active_tab_type);
			}
			var insert_form = $(data_target).find('.insert_form').attr('id');
			$(data_target).modal("show");
			//Form Validation and Submmit Event

			$.validator.setDefaults({
				submitHandler: function() {
					var subtext = $("input[name=tab_subtext]").val();
					var title = $("input[name=tab_text]").val();
					$.ajax({
						url: '<?php echo base_url('/tabcontroller/save_tab'); ?>',
						type: "POST",
						data: new FormData($("#" + insert_form)[0]),
						dataType: "json",
						contentType: false,
						processData: false,
						beforeSend: function(xhr) {
							$("#status_data").hide()
							$(".insert_tabdata #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
						},
						success: function(resp) {
							$(".insert_tabdata #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
							if (resp.success_status) {
								$("#" + insert_form)[0].reset();
								$("#result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>');
								if (insert_form != 'quickbookmarkForm') {

									$("ul#tabs-list").append('<li class="nav-item bookmark_nav"><a class="nav-link" title="' + title + '" data-id="' + resp.last_id + '" data-title="tabs" href="javascript:;">' + subtext + '</a></li>');
								} else {
									$("li.bookmark_nav.active a").trigger('click');
								}
								setTimeout(function() {
									$(data_target).modal("hide");
								}, 700);
							} else {
								$("#result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
							}
							setTimeout(function() {
								$("#result_sts").html("");
							}, 1000);
						},
						error: function() {
							$(".insert_tabdata #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
							console.log("Please try after some time");
						}
					});
				}
			});

			//$('.insert_tabdata').validate({
			$("#" + insert_form).validate({
				errorElement: 'span',
				errorPlacement: function(error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function(element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function(element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	});

	$("#search_here").on('click', function() {
		$(".show-serach-form").removeClass('hide_field');
		$('#search_here').addClass('hide_field');
		$(".show-serach-form #search_keyword input").focus();
	});


	$("#search_keyword").on('submit', function(event) {
		event.preventDefault();
		$(".bookmark_tabs .bookmark_nav a#search_tab").trigger("click").parent().show();

		var keyword = $("#search_keyword input").val();


		
		if(keyword.length < 3){
		 	alert("Search suport at least 3 charactor");
		} else {
			$.ajax({
				url: '<?php echo base_url('/tabcontroller/search_data?keyword='); ?>' + keyword,
				dataType: 'json',
				beforeSend:function(){
					//$("body").addClass("modal-open");				    
				    $(".search_res #search_keyword").html("Bookmarks Results: <span class='purpleText'>#"+keyword+"</span>");
				},
				success: function(resp) {					
					if (resp.bookmarks) {
						$("#tab").html(resp.tabs);
						$("#bookmarks").html(resp.bookmarks);
					}
				},
			});
		}
	});








	$("#hide_label").change(function() {
		$(".check_visibility").toggleClass("hide_label", this.checked)
	}).change();


	$(document).ready(function() {
		setTimeout(function() {
			$.ajax({
				url: '<?php echo base_url('/tabcontroller/get_visibility'); ?>',
				dataType: 'json',
				success: function(resp) {					
					if (resp.label_visibility === '1') {
						//alert(resp.label_visibility);
						$(".check_visibility").addClass('hide_label');
					} else if (resp.label_visibility === '0') {
						//alert(resp.label_visibility);
						$(".check_visibility").removeClass('hide_label');
					}
				},
			});
		}, 1000);

	})
</script>
