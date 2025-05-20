<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">			
					<p class="text-dark"><?= ucfirst($_REQUEST['tab'])?>: <?= (isset($selected_tab) && !empty($selected_tab))?$selected_tab->name:''; ?></p>
					<h1 class="m-0 text-dark"><?=lang('bookmarks')?></h1>
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
					<div class="card card-primary">
						<div class="card-body">
							<div>
								<nav class="navbar navbar-expand-lg">
									<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
										<span class="navbar-toggler-icon"></span>
									</button>
								</nav>
								<div class="col-md-12">
									<div id="card-group">
										<?php if (isset($bookmarks) && !empty($bookmarks)) { ?>
											<?php foreach ($bookmarks as $key => $bookmark) { ?>
												<div class="card wow zoomIn animated" id="<?= $bookmark['id'] ?>"><a target="_blank" href="<?= $bookmark['url'] ?>"><img class="card-img-top" src="<?= $bookmark['thumb'] ?>" alt="<?= $bookmark['name'] ?>"></a></div>
											<?php } ?>

										<?php } else { ?>
											<!-- <p class="text-danger">There are no bookmarks.</p> -->
										<?php } ?>
										<div class="card wow zoomIn unsortable animated" style="visibility: visible; animation-name: zoomIn;">
											<a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal">
												<img class="card-img-top" src="http://localhost/CI_bookmarks/assets/images/plus.png">
											</a>
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>


<div class="modal fade" id="insertbookmartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content" style="width: 100%;">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?= lang('add_bookmarks') ?></h5>
				<button type="button" id="close_modal" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="edit_result_sts" style="width:100%;"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="card-body">
							<div id="status_data" class="alert alert-danger alert-dismissible fade show" style="display: none;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<p></p>
							</div>


							<table id="data_table" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th><?= lang('FieldImage') ?></th>
										<th><?= lang('FieldTitle') ?></th>
										<th><?= lang('FieldUrl') ?></th>

										<th><?= lang('Date') ?></th>
										<th><?= lang('Action') ?></th>
									</tr>
								</thead>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$(function() {
	reorder = $('#card-group');
	reorder.sortable({
		opacity: 0.7,
		items: "div:not(.unsortable)",
		update: function() {
			var counter = 0;
			var sortreOrder = [];
			reorder.children('div.card').each(function() {
				sortreOrder[counter] = $(this).attr('id');
				counter++;
			});
			var activeid = "<?php echo $parent_id; ?>";
			var activedatatype = "<?php echo $type; ?>";
			$.ajax({
				method: "POST",
				url: ajax_url + 'bookmarkuserscontroller/save_bookmark',
				dataType: "json",
				data: {
					order: sortreOrder,
					active: activeid,
					datatype: activedatatype
				},
				success: function(data) {
					swal(data.success_status, {
						icon: "success"
					});
				}
			});
		}
	});
});


$("#bookmark").on("click", function() {
	$("#insertbookmartModal").modal("show");
	setTimeout(function() {
		$('#data_table').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?php echo base_url('/bookmarkcontroller/data_table') ?>",
				"type": "POST",
				"data": {
					'post_id': "<?php echo $_REQUEST['p_id'] ?>",
					'table': "<?php echo $_REQUEST['tab'] ?>",
				},
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false
			}],
			"ordering": false
		});
	}, 500);
});

$("#close_modal").on("click", function() {
	location.reload();
});


$("body").on("click", ".admin_action", function() {
	//alert('xx');
	var $this = $(this);
	var get_type = $this.attr('data-title');
	var get_bookmark_id = $this.attr('data-value');
	var get_parent_id = $this.attr('data-id');

	if (get_type == "remove") {
		var message = "You want to remove?";
	} else {
		var message = "You want to add?";
	}
	swal({
		title: "Are you sure?",
		text: message,
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.ajax({
				url: "<?php echo base_url('/bookmarkcontroller/save_meta_data'); ?>",
				type: "post",
				dataType: "json",
				data: {
					'bookmark_id': get_bookmark_id,
					'datatype': '<?php echo $_REQUEST['tab'] ?>',
					'type': get_type,
					'parent_id': get_parent_id,
				},
				success: function(resp) {
                //console.log('---------------',resp);
                // alert(get_type);

					//if (resp.success) {
						if (get_type == "remove") {							
							$this.removeClass('btn-danger').addClass("btn-success").attr('data-title', 'add').html("Assign");
						} else {
							$this.removeClass('btn-success').addClass("btn-danger").attr('data-title', 'remove').html("Unassigned");;
						}
						swal(resp.success, {
							icon: "success",
						});
					/*} else {
						swal(resp.error, {
							icon: "error",
						});
					}*/
				}
			});
		}
	});
});
</script>

