var sitebase_url = ajax_url;
$(function () {
	$("body").on("click", ".profile_handle", function () {
		if ($(this).hasClass('edit_profile')) {
			$(this).html('<i class="fas fa-eye"></i>');
			$(".profile_handle").removeClass('edit_profile').addClass('view_profile');
			$(".profile-update").slideDown("slow");
			$(".inner_profile").hide();
		} else {
			$(this).html('<i class="fas fa-pencil-alt"></i>');
			$(".profile_handle").removeClass('view_profile').addClass('edit_profile');
			$(".profile-update").hide();
			$(".inner_profile").slideDown("slow");
		}
	});

	if ($("#tabs-list").children().length > 0) {
		if (!$("#tabs-list li").eq(0).hasClass('active')) {
			$("#tabs-list li").eq(0).addClass('active');
		}

		if ($("#tabs-list li").eq(0).children('a').data('thumb') != '') {
			var src = ($("#tabs-list li").eq(0).children('a').data('title') == 'user') ? sitebase_url + 'assets/admin/upload/users/thumbnail/' + $("#tabs-list li").eq(0).children('a').data('thumb') : $("#tabs-list li").eq(0).children('a').data('thumb');
			$(".navbar-brand").html('<img src="' + src + '" />' + $("#tabs-list li").eq(0).children('a').attr('title'));
		} else {
			$(".navbar-brand").html('<i class="fa fa-home"></i>' + $("#tabs-list li").eq(0).children('a').attr('title'));
		}
	} else {
		$("#tab-toggler").hide();
	}

	$("body").on("click", ".accept_terms", function () {
		if ($(this).is(':checked')) {
			$("#accept_terms-error").hide();
			$(".accepted_terms").val("yes");
		} else {
			$("#accept_terms-error").show();
			$(".accepted_terms").val('');
		}
	});


	//Contact form submited validation and insert
	$.validator.setDefaults({
		submitHandler: function () {
			var form_nm = $(".validfrm_act").attr('data-id');
			var form_action = $(".validfrm_act").attr('action');
			$.ajax({
				url: ajax_url + "/" + form_action,
				type: "POST",
				data: new FormData($('#info_' + form_nm)[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$("#info_" + form_nm + " #status_data").hide()
					$("#info_" + form_nm + " #save_data").prop("disabled", true).html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #428894;"></i>').css('background', '#ddd');
				}, success: function (resp) {
					$("#info_" + form_nm + " #save_data").prop("disabled", false).html('Submit').css('background', '#428894');
					if (resp.success) {
						$("#info_" + form_nm)[0].reset();
						swal(resp.success, {
							icon: "success"
						});
						$("#info_" + form_nm + " #status_data").show();
						$("#info_" + form_nm + " #status_data").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success + '</div>');
					} else {
						swal(resp.error, {
							icon: "error"
						});
						$("#info_" + form_nm + " #status_data").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error + '</div>');
					}
				}, error: function () {
					swal("Please try again later", {
						icon: "error"
					});
					$("#info_" + form_nm + " #save_data").prop("disabled", false).html('Submit').css('background', '#428894');
				}
			});
		}
	});


	$('.validfrm_act').validate({
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
	//Contact form submited validation and insert 



	$("body").on("click", ".catalogue_filter input[type='checkbox'], .catalogue_filter input[type='radio']", function () {
		$(".catalogue_filter").submit();
	});

	$("body").on("submit", ".catalogue_filter", function (event) {
		var form_action = $(".catalogue_filter").attr('action');
		event.preventDefault();

		$.ajax({
			url: ajax_url + "/" + form_action,
			type: "POST",
			data: $(".catalogue_filter").serialize(),
			dataType: "json",
			beforeSend: function (xhr) {
				$("#filter_resp").css('display', 'inline-block');
				$("#filter_resp").html('<div class="loader_ajax"></div>');
			}, success: function (resp) {
				var gethtml = "";
				if (resp.Success) {
					$(".catalogue-footer").show();
					$("#filter_resp").css('display', 'grid');
					$.each(resp.filter_data, function (k, v) {
						var catalogue_img = "";
						if (v.catalog_image == "") {
							var no_img = ajax_url + 'assets/images/slider_blank.png';
							var catalogue_img = "<img src='" + $no_img + "'/>";
						} else {
							var user_uploaded_img = ajax_url + 'assets/admin/upload/catalogues/' + v.catalog_image;
							var catalogue_img = "<img src='" + user_uploaded_img + "'/>";
						}
						gethtml += '<div class="catalogue-card"><div class="catalogue-card-img">' + catalogue_img + '</div><div class="catalogue-card-content"><h3 class="catalogue-card-title">' + v.catalog_title + '</h3></div></div>';

					});

				} else {
					$("#filter_resp").css('display', 'inline-block');
					var gethtml = '<div class="noresult_found"><p class="text-danger">' + resp.Error + '</p></div>';
					$(".catalogue-footer").hide();
				}
				$("#filter_resp").html(gethtml);
			}, error: function () {

			}
		});
	});



	$("body").on("click", ".close_account", function () {
		var get_id = $(this).attr('id');
		var data_msg = $(this).attr('data-msg');
		var sure_msg = $(this).attr('data-title');
		swal({
			title: sure_msg,
			text: data_msg,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						url: ajax_url + "/close-account",
						type: "post",
						dataType: "json",
						data: { "close_id": get_id },
						success: function (resp) {
							if (resp.success) {
								swal(resp.success, {
									icon: "success"
								})
									.then((value) => {
										window.location.href = resp.trigger_url;
									})
							} else {
								swal(resp.error, {
									icon: "error",
								});
							}
						}
					});
				}
			});
	});


	$("body").on("click", ".email_info", function () {
		var email_msg = $(this).attr('data-id');
		swal(email_msg, {
			icon: "info",
		});
	});

	$(document).on('click', '#editor-btn', function () {
		if ($("#card-group").children('.card').length > 0) {
			$("#card-group").children('.card').each(function (e) {
				$(this).children('div.dropdown').after("<a href='javascript:void(0)' class='delete_bookmark_data' data-id='" + $(this).attr('id') + "'><i class='fa fa-times'></i></a>");
			});
			$(this).hide();
			$(".done_editing").show();
		}
	});

	
	$(document).on('click', '.done_editing', function () {
		$(".desktop_bookmark .delete_bookmark_data").remove();
		$(this).hide();
		$("#editor-btn").show();
	});
	

	if ($(window).width() < 767) {
		$("body").on("click", ".usr_prf_link", function(){
			if(!$(".userDropdown .dropdown").hasClass("show")){
				$(".userDropdown .dropdown").addClass("show");
				$(".userDropdown .dropdown button").attr('aria-expanded', 'true');
				$(".userDropdown .dropdown .dropdown-menu").addClass("show");
			} else {
				$(".userDropdown .dropdown").removeClass("show");
				$(".userDropdown .dropdown button").attr('aria-expanded', 'false');
				$(".userDropdown .dropdown .dropdown-menu").removeClass("show");
			}
		});

		$("#navbarSupportedContent").css("visibility", "hidden");
		$(window).on('load', function() {
			$("#navbarSupportedContent").css("visibility", "visible");
			setTimeout(function(){
				$("#tab-toggler").trigger("click");
			},500);
		});
	}


	$("body").on("click", ".bookmark_tabs .bookmark_nav a", function (event) {	
		event.preventDefault();
		
		var $this = $(this);
		var type_id = $this.attr('data-id');
		$(".bookmark_tabs li").removeClass("active");
		$(this).parent().addClass("active");
        
		if(type_id=="search"){
			$(".search_res").show();
			$(".desktop_bookmark").hide();
		} else {
			//alert("dsdsd");
			$(".search_res").hide();
			$(".desktop_bookmark").show();

			$(".navbar-toggler").trigger("click");
			
			var type = $this.attr('data-title');
			
			

			$(".done_editing").hide();
			$("#editor-btn").show();

			if ($(this).data('thumb') != '') {
				var src = ($(this).data('title') == 'user') ? sitebase_url + 'assets/admin/upload/users/thumbnail/' + $(this).data('thumb') : $(this).data('thumb');
				$(".navbar-brand").html('<img src="' + src + '" />' + $(this).attr('title'));
			} else {
				$(".navbar-brand").html('<i class="fa fa-home"></i>' + $(this).attr('title'));
			}
			var subtype="no";
			bookmark_tabs(type, type_id, $this, subtype);
		}
	});

	function escape(s, preserveCR) {
		preserveCR = preserveCR ? '&#13;' : '\n';
		return ('' + s) /* Forces the conversion to string. */
			.replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
			.replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
			.replace(/"/g, '&quot;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			/*
			You may add other replacements here for HTML only 
			(but it's not necessary).
			Or for XML, only if the named entities are defined in its DTD.
			*/
			.replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
			.replace(/[\r\n]/g, preserveCR);
		;
	}


	$("body").on("click", ".desktop_subtabs .bookmark_subtabs li.subtabs_listing a", function(){
		var $this=$(this);
		var type= $this.attr('data-title');
		var type_id= $this.attr('data-id');
		var subtype="yes";
		$(".card-group").show();
		$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing").removeClass("active_subtab");
		$this.parent().addClass("active_subtab");
		var get_text=$this.html();
		$(".logo .navbar-brand").html(get_text);
		bookmark_tabs(type, type_id, $this, subtype);
	});

	$("body").on("click", ".back_subtabs a", function(){
		$(".hide_subtabs").show();
		$(".back_subtabs").hide();
		$(".card-group").hide();
		var get_img=$(".bookmark_tabs li.active a").attr('data-thumb');
		var get_text=$(".bookmark_tabs li.active a").html();
		$(".logo .navbar-brand").html('<img src="'+get_img+'">'+get_text);
		$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing").removeClass("active_subtab");
		$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing").show();
	});

	function bookmark_tabs(type, type_id, $this, subtype) {
		$.ajax({
			url: ajax_url + "bookmarks-tabs",
			type: "POST",
			data: {
				'type': type,
				'type_id': type_id
			},
			dataType: "json",
			beforeSend: function (xhr) {
				$(".desktop_bookmark").html('');
				if(subtype=="no"){
					$(".desktop_subtabs").html('');
				}
			}, 
			success: function (resp) {

				if (resp.code == 200) {
					if(subtype=="yes"){
						$(".hide_subtabs").hide();
						$(".back_subtabs").show();
						$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing").hide();
						$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing.active_subtab").show();
					}

					$(".desktop_bookmark").html('<div class="circleLoader"><div class="inner-circles-loader"></div></div>');
					var append_html = "";
					var data_lenth = $(resp.data).length;
					if (data_lenth == 0) {
						append_html += '<p class="text-warning fa-2x mb-0">No Bookmarks to show</p>';
					} else {
						$.each(resp.data, function (k, v) {
							var image_url = v.thumbnail;
							var bookmark_url = v.url;
							var bookmark_id = v.id;
							var bookmark_title = v.name;

							var desc = v.comment;
							if (typeof desc === 'undefined' || desc === null) {
								var comment = v.name;
							} else {
								var comment = escape(desc);
							}
							//console.log(comment);
							append_html += "<div class='card wow zoomIn test-123' id='" + bookmark_id + "'>";
							append_html += "<div class='tooltip'>";							
							append_html += "<p>"+comment+"</p>";							
							append_html += "</div>";
							append_html += "<div class='dropdown dropdown-select'>";
							append_html += "<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-ellipsis-h'></i></button>";
							append_html += "<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu1' x-placement='bottom-start' style='position: absolute; transform: translate3d(30px, 36px, 0px); top: 0px; left: 0px; will-change: transform;'>";

							if (user_role == 1) {
								append_html += "<li class='delete-bk'><a href='javascript:void(0)' class='delete_bookmark_data' data-id='" + bookmark_id + "'>Delete</a></li>";
							} else if (user_role == "2" && type == 'user' || user_role == '2' && type == 'tabs') {
								append_html += "<li class='delete-bk'><a href='javascript:void(0)' class='delete_bookmark_data' data-id='" + bookmark_id + "'>Delete</a></li>";
							}


							append_html += "<li><a target='_blank' href='" + v.url + "'>View</a></li>";
							append_html += "<li><a class='bookmark_tooltip' href='javascript:void(0)' data-view='" + comment + "'>Info</a></li>";
							append_html += "</ul>";
							append_html += "</div>";


							append_html += "<a target='_blank' href='" + bookmark_url + "'>";
							append_html += "<img class='card-img-top' src='" + image_url + "' alt='" + bookmark_title + "'>";
							append_html += "<label class='text-white mt-2 check_visibility'>" + bookmark_title + "</label>";
							append_html += "</a>";
							append_html += "</div>";
						});
					}
					// console.log(append_html);
					$(".desktop_bookmark").html('');
					$(".desktop_bookmark").html(append_html);
					if (user_role == "2" && $("li.bookmark_nav.active").attr('data-type') == 'user' || user_role == "2" && $("li.bookmark_nav.active").attr('data-type') == 'tabs') {
						booksmarkssort();
					} else if (user_role == "1") {
						booksmarkssort();
					}
					if (user_role == "2" && type == 'user' || user_role == '2' && type == 'tabs') {
						$(".desktop_bookmark").append('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal" data_type="'+subtype+'"><img class="card-img-top" src="' + sitebase_url + '/assets/images/plus.png"></a></div>');
					} else if (user_role == "1") {
						$(".desktop_bookmark").append('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal" data_type="'+subtype+'"><img class="card-img-top" src="' + sitebase_url + '/assets/images/plus.png"></a> </div>');
					}
					if ($("#add-new-bk").length > 0) {
						$("#add-new-bk").remove();
					}
					$('[data-toggle="tooltip"]').tooltip();
				} else if(resp.code == 201){
					$(".desktop_subtabs").html('<div class="circleLoader"><div class="inner-circles-loader"></div></div>');
					var append_html = "<ul class='bookmark_subtabs' id='tabs-list'>";

					if(resp.sub_status=="hide"){
						var subtab_default="Hide Subtab";
						var subtab_status="no";
						var subtab_display="display:block";
						$(".card-group").show();
					} else {
						var subtab_default="Show Subtab";
						var subtab_status="yes";
						var subtab_display="display:none";
						$(".card-group").hide();
					}

					append_html += "<li class='back_subtabs' style='display:none;'><a href='javascript:void(0)' class='nav-link default_bookmark backsubtab' title='Back to subtab'><svg data-name='Layer 3' id='Layer_3' viewBox='0 0 32 32'><defs><style>.cls-1,.cls-2{fill:none;stroke:#0832ff;}.cls-1{stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}.cls-2{stroke-miterlimit:10;}.cls-3{fill:#0832ff;}</style></defs><title/><path class='cls-1' d='M13.5,19.5v4.08l-9-8.08,9-8.08V11.5s14.17-.75,14.17,12.17C23.25,16.5,13.5,19.5,13.5,19.5Z'/><line class='cls-2' x1='6.53' x2='13.8' y1='17.59' y2='11.24'/><polygon class='cls-3' points='4.5 15.5 5.88 16.63 13.44 9.75 13.5 7.42 4.5 15.5'/></svg>Back to subtabs</a></li>";
					append_html += "<li class='hide_subtabs'><a href='javascript:void(0)' class='nav-link default_bookmark hidebookmark' title='Subtab Status' data-id='"+subtab_status+"'>"+'<svg viewBox="0 0 32 32"><defs><style>.cls-1{fill:#d8e1ef;}.cls-2{fill:#0593ff;}.cls-3{fill:#0e6ae0;}</style></defs><title/><g data-name="Eye Disable" id="Eye_Disable"><path class="cls-1" d="M29.93,15.54C28,10.35,22.49,7,16,7S4,10.35,2.07,15.54A.92.92,0,0,0,2,15.9V16a.92.92,0,0,0,.07.36c2,5.22,7.48,8.59,14,8.59S28,21.62,29.93,16.46A.92.92,0,0,0,30,16.1v-.2A.92.92,0,0,0,29.93,15.54Z"/><circle class="cls-2" cx="16" cy="16" r="6"/><path class="cls-3" d="M7,26a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42l18-18a1,1,0,1,1,1.42,1.42l-18,18A1,1,0,0,1,7,26Z"/></g></svg>'+subtab_default+"</a></li>";

					$.each(resp.data, function (k, v) {
						var sub_url = v.thumbnail;
						if(resp.type=="company"){
							var sub_id = v.cmp_id;
							var sub_title = v.cmp_nick_title;
						} else {
							var sub_id = v.id;
							var sub_title = v.nick_title;
						}
						

						append_html += "<li class='subtabs_listing' data-title='Google' data-type='"+resp.type+"' data-id='"+sub_id+"' style='"+subtab_display+"'>";
						append_html += "<a href='javascript:void(0)' class='nav-subtab' title='"+sub_title+"' data-id='"+sub_id+"' data-title='"+resp.type+"' data-thumb='"+sub_url+"'>";
						append_html += "<img src='"+sub_url+"'/>";
						append_html += "<span>"+sub_title+"</span>";
						append_html += "</a>";
						append_html += "</li>";
					});

					append_html += "</ul>";
					$(".desktop_subtabs").html(append_html);
					
				} else {
					/*Swal.fire({
						title: "Error!",
						text: resp.message,
						confirmButtonColor: '#000',
						confirmButtonText: 'Close',
						showCloseButton: true,
						icon: "info"
					});*/

					if(subtype=="yes"){
						$(".hide_subtabs").hide();
						$(".back_subtabs").show();
						$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing").hide();
						$(".desktop_subtabs .bookmark_subtabs li.subtabs_listing.active_subtab").show();
					}

					$(".desktop_bookmark").html('');
					if (user_role == "2" && type == 'user' || user_role == '2' && type == 'tabs') {
						$(".desktop_bookmark").append('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal" data_type="'+subtype+'"><img class="card-img-top" src="' + sitebase_url + '/assets/images/plus.png"></a></div>');
					} else if (user_role == "1") {
						$(".desktop_bookmark").append('<div  class="card wow zoomIn unsortable"><a id="bookmark" class="insert_modalbox" href="javascript:void(0)" data-target="#insertbookmartModal" data_type="'+subtype+'"><img class="card-img-top" src="' + sitebase_url + '/assets/images/plus.png"></a></div>');
					} else {
						$(".desktop_bookmark").append('<p class="text-warning fa-2x mb-0">No Bookmarks to show</p>');
					}
					if ($("#add-new-bk").length > 0) {
						$("#add-new-bk").remove();
					}
				}
			}, error: function () {
				console.log("Failed to load bookmark tabs");
			}
		});
	}


	$("body").on("click", ".hide_subtabs a", function(event){
		event.preventDefault();
		var $this=$(this);
		var get_status=$this.attr("data-id");
		var get_text=$this.html();
		manage_subtab(get_status, $this, get_text);
	});


	function manage_subtab(get_status, $this, get_text){
		$.ajax({
			url: ajax_url + "manage-subtab",
			type: "POST",
			data: {
				"manage": "subtab",
				"status": get_status
			},
			dataType: "json",
			beforeSend: function () {
				$this.html('<i class="fa fa-spinner fa-spin"></i>');
			}, success: function (resp) {
				//swal("Success!", resp.message, "success");
				
				if(get_status=="no"){
					$this.html('<svg data-name="Layer 3" id="Layer_3" viewBox="0 0 32 32"><defs><style>.cls-1{fill:#0832ff;}</style></defs><title/><path class="cls-1" d="M16,25.47c-7.53,0-13.41-8.54-13.66-8.91L2,16l.38-.56C2.59,15.07,8.47,6.53,16,6.53s13.41,8.54,13.66,8.91L30,16l-.38.56C29.41,16.93,23.53,25.47,16,25.47ZM4.41,16C5.8,17.82,10.55,23.47,16,23.47S26.2,17.83,27.59,16C26.2,14.18,21.45,8.53,16,8.53S5.8,14.17,4.41,16Z"/><path class="cls-1" d="M16,11a4.14,4.14,0,0,0-.48,0,.34.34,0,0,0-.31.33.34.34,0,0,0,.1.24h0a2.59,2.59,0,0,1,.74,1.8A2.63,2.63,0,0,1,13.42,16a2.58,2.58,0,0,1-1.78-.72h0a.35.35,0,0,0-.6.18A4.13,4.13,0,0,0,11,16a5,5,0,1,0,5-5Z"/></svg>'+"Show Subtab");
					$this.attr('data-id', 'yes');
					$(".bookmark_subtabs li.subtabs_listing").hide();
					$(".card-group").hide();
				} else {
					$this.html('<svg viewBox="0 0 32 32"><defs><style>.cls-1{fill:#d8e1ef;}.cls-2{fill:#0593ff;}.cls-3{fill:#0e6ae0;}</style></defs><title/><g data-name="Eye Disable" id="Eye_Disable"><path class="cls-1" d="M29.93,15.54C28,10.35,22.49,7,16,7S4,10.35,2.07,15.54A.92.92,0,0,0,2,15.9V16a.92.92,0,0,0,.07.36c2,5.22,7.48,8.59,14,8.59S28,21.62,29.93,16.46A.92.92,0,0,0,30,16.1v-.2A.92.92,0,0,0,29.93,15.54Z"/><circle class="cls-2" cx="16" cy="16" r="6"/><path class="cls-3" d="M7,26a1,1,0,0,1-.71-.29,1,1,0,0,1,0-1.42l18-18a1,1,0,1,1,1.42,1.42l-18,18A1,1,0,0,1,7,26Z"/></g></svg>'+"Hide Subtab");
					$this.attr('data-id', 'no');
					$(".bookmark_subtabs li.subtabs_listing").show();
					$(".card-group").show();
				}
			}, error: function(){
				$this.html(get_text);
				console.log("Error! Saving subtabs issue")
			}
		});
	}


	$("body").on("click", ".bookmark_tooltip", function () {
		var $this = $(this);
		var get_attr = $this.parent().parent().parent().parent().attr('aria-describedby');

		if ($this.hasClass("active")) {
			$this.removeClass("active").html("Info");
			$("#" + get_attr).css("opacity", "0");
		} else {
			$("#" + get_attr).css("opacity", "1");
			$this.addClass("active").html("hide");
		}

	});



	list = $('#tabs-list');
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function () {

			var sortOrder = [];
			list.children('li').each(function () {
				sortOrder.push($(this).data('type') + '&' + $(this).data('id') + '&' + $(this).data('title'));
			});
			//  console.log(ajax_url);
			$.ajax({
				method: "POST",
				url: ajax_url + 'Tabcontroller/savetab_order',
				data: { order: sortOrder, user_id: currentuser_id },
				success: function (data) {
					console.log(data);
				}
			});
		}
	});


	if ($('#card-group').length > 0 && $('#card-group').children().length > 0) {
		if (user_role == "2" && $("li.bookmark_nav.active").attr('data-type') == 'user' || user_role == "2" && $("li.bookmark_nav.active").attr('data-type') == 'tabs') {
			booksmarkssort();
		} else if (user_role == "1") {
			booksmarkssort();
		}
	}

	$("body").on("click", ".user_action", function () {
		var get_title = $(this).attr('title');
		var tablenm = $(this).attr('data-id');
		var table_id = $(this).attr('data-key');
		var table_value = $(this).attr('data-value');

		if (get_title == "Delete") {
			swal({
				title: "Are you sure?",
				text: "You want to delete this",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							url: sitebase_url + "/delete-data",
							type: "post",
							dataType: "json",
							data: { "table": tablenm, 'table_id': table_id, 'table_value': table_value },
							success: function (resp) {
								if (resp.success) {
									$("#deletedata_" + table_value).parent().parent().fadeOut("slow");
								} else {
									swal(resp.error, {
										icon: "error",
									});
								}
							}
						});
					}
				});
		}
	});


	$("#bookmark_form input[type='text']").on("keyup", function () {
		var get_length = $(this).val().length;
		setTimeout(function () {
			$("#bookmark_form").submit();
		}, 500);
	});

	$("#media_form input[type='text']").on("keyup", function () {
		var get_length = $(this).val().length;
		setTimeout(function () {
			$("#media_form").submit();
		}, 500);
	});



	$.validator.setDefaults({
		submitHandler: function () {
			var subtext = $("input[name=tab_subtext]").val();
			var title = $("input[name=tab_text]").val();

			$.ajax({
				url: ajax_url + 'graphicscontroller/media_search_field',
				type: "POST",
				data: new FormData($("#bookmark_form")[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$("#bookmark_form_data row").html('<div id="spinner"></div>');
				}, success: function (resp) {
					if (resp.code == 200) {
						var media_html = '';
						$.each(resp.bookmark, function (k, v) {
							var bookmark_url = v.url;
							var bookmark_id = v.id;
							var bookmark_thumb = v.thumb;
							var bookmark_name = v.name;
							media_html += "<div class='filtr-item'>";
							media_html += '<a class="assign_bookmark_scheme" href="javascript:void(0)"  data-id="' + bookmark_id + '">';
							media_html += '<img class="card-img-top img-fluid mb-2" src="' + bookmark_thumb + '" alt="' + bookmark_name + '">';
							media_html += '<span class="media_title">' + bookmark_name + '</span>';
							media_html += '</a>';
							media_html += '</div>';
						});

						if (resp.count > 12) {
							media_html += '<button type="button" data-page="1" class="load_more_data" data-limit="12" data-offset="12" data-type="bookmark">Load More</button>';
						}
						$("#bookmark_form_data .row").html(media_html);
					} else {
						swal("Error!", resp.message, "error");
					}
				}, error: function () {
					swal("Error!", "Failed to load media from server", "error");
				}
			});
		}
	});

	//$('.insert_tabdata').validate({
	$("#bookmark_form").validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-input').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});


	$.validator.setDefaults({
		submitHandler: function () {

			var subtext = $("input[name=tab_subtext]").val();
			var title = $("input[name=tab_text]").val();

			$.ajax({
				url: ajax_url + 'graphicscontroller/media_search_field',
				type: "POST",
				data: new FormData($("#media_form")[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$("#media_form_data row").html('<div id="spinner"></div>');
				}, success: function (resp) {
					if (resp.code == 200) {
						var media_html = '';
						$.each(resp.media, function (k, v) {
							var media_path = v.path;
							var media_thumb = v.thumb;
							var media_id = v.id;
							var media_user_id = v.user_id;
							var media_name = v.name;
							media_html += '<div class="filtr-item" onclick="selecticon(this)" data-path="' + media_path + '" data-thumb="' + media_thumb + '" id="icon-' + media_id + '" data-id="' + media_id + '">';
							media_html += '<img class="card-img-top img-fluid mb-2" src="' + media_thumb + '" alt="' + media_name + '">';
							media_html += '<span class="media_title">' + media_name + '</span>';
							media_html += '</div>';
						});
						if (resp.count > 12) {
							media_html += '<button type="button" data-page="1" class="load_more_data" data-limit="12" data-offset="12" data-type="media">Load More</button>';
						}
						$("#media_form_data .row").html(media_html);
					} else {
						swal("Error!", resp.message, "error");
					}
				}, error: function () {
					swal("Error!", "Failed to load media from server", "error");
				}
			});
		}
	});


	$("body").on("click", ".load_more_data", function () {
		var $this = $(this);
		var offset = $this.attr('data-offset');
		var limit = $this.attr('data-limit');
		var type = $this.attr('data-type');
		var search_field = $this.parent().parent().parent().find('.search_field').val();
		$.ajax({
			url: ajax_url + 'graphicscontroller/media_search_field_load_more',
			type: "POST",
			data: {
				'offset': offset,
				'limit': limit,
				'type': type,
				'search_data': search_field
			},
			dataType: "json",
			beforeSend: function (xhr) {
				$("#media_form_data row").html('<div id="spinner"></div>');
			}, success: function (resp) {
				if (resp.code == 200) {
					var media_html = '';
					$.each(resp.data, function (k, v) {
						

						if (type == 'bookmark') {
							media_html += '<div class="filtr-item">';
							media_html += '<div class="tooltip"><p>' + v.name + '</p></div>';
							media_html += '<a class="assign_bookmark_scheme" href="javascript:void(0)" data-id="' + v.id + '">';
							media_html += '<img class="card-img-top img-fluid mb-2" src="' + v.thumb + '" alt="icon"/>';
							media_html += '<span class="media_title">' + v.name + '</span>';
							media_html += '</a>';
							media_html += '</div>';
						} else {
							var media_path = v.path;
							var media_thumb = v.thumb;
							var media_id = v.id;
							var media_user_id = v.user_id;
							var media_name = v.name;
							media_html += '<div class="filtr-item" onclick="selecticon(this)" data-path="' + media_path + '" data-thumb="' + media_thumb + '" id="icon-' + media_id + '" data-id="' + media_id + '">';
							media_html += '<img class="card-img-top img-fluid mb-2" src="' + media_thumb + '" alt="' + media_name + '">';
							media_html += '<span class="media_title">' + media_name + '</span>';
							media_html += '</div>';
						}
					});

					var get_lenth = $this.parent().find('.filtr-item').length;
					

					if (resp.type == "media") {
						$("#media_form_data .row .filtr-item:nth-child("+get_lenth+")").after(media_html).fadeIn("slow");
						//media_html += '<button type="button" data-page="1" class="load_more_data" data-limit="4" data-offset="'+resp.offset+'" data-type="'+resp.type+'">Load More</button>';
					} else {
						var nth_child=get_lenth;
						$("#bookmark_form_data .row .filtr-item:nth-child("+nth_child+")").after(media_html).fadeIn("slow");
					}
					var get_lenth = $this.parent().find('.filtr-item').length;
					if (resp.count > get_lenth + 12) {
						$this.parent().find('.load_more_data').attr('data-offset', resp.offset);
					} else {
						$this.parent().find('.load_more_data').hide();
					}

				} else {
					swal("Error!", resp.message, "error");
				}
			}, error: function () {
				swal("Error!", "Failed to load media from server", "error");
			}
		});
	});


	//$('.insert_tabdata').validate({
	$("#media_form").validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-input').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});


	$(document).on("keyup", "#media_form_search input[type='text']", function () {
		var get_length = $(this).val().length;
		setTimeout(function () {
			$("#media_form_search").submit();
		}, 500);
	});

	$(document).on('submit', "#media_form_search", function (e) {
		e.preventDefault();
		loadgraphics($("#media_form_search").attr('action') + '?search=' + $("#media_form_search input[name='search']").val());

	});



	//Request form JS
	$.validator.setDefaults({
		submitHandler: function () {
			$.ajax({
				url: ajax_url + 'graphicscontroller/requestFormSave',
				type: "POST",
				data: new FormData($(".requestForm")[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$("#media_form_data row").html('<div id="spinner"></div>');
				}, success: function (resp) {
					if (resp.code == 200) {
						swal("Success!", resp.message, "success");
						$(".requestForm")[0].reset();
					} else {
						swal("Error!", resp.message, "error");
					}
				}, error: function () {
					swal("Error!", "Failed to load media from server", "error");
				}
			});
		}
	});


	$(".requestForm").validate({
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
	//End Request form js



	//Upload Graphic from Frontend Modal

	$.validator.setDefaults({
		submitHandler: function () {
			$.ajax({
				url: ajax_url + 'graphicscontroller/save',
				type: "POST",
				data: new FormData($('.browseFile')[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$(".browseFile button").html('<i class="fa fa-spinner fa-spin"></i>');
				},
				success: function (resp) {
					if (resp.success_status) {
						$('.browseFile')[0].reset();
						$(".browseFile button").html('Upload');
						$("#media_form").submit();
						$("ul#custom-tabs-one-tab li:first-child a").trigger("click");
					} else {
						swal("Error!", resp.error_status, "error");
					}
				},
				error: function () {
					$(".browseFile button").html('Upload');
					swal("Error!", "Failed to upload media on server", "error");
				}
			});
			;
		}
	});

	$('.browseFile').validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-input').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});
	//Upload Graphic from Frontend Modal


	$("body").on("click", "#media_form_data .filtr-item", function () {
		$("#media_form_data .filtr-item").removeClass("active_media");
		$(this).addClass("active_media");
		var get_path = $(this).attr('data-path');
		var get_thumb = $(this).attr('data-thumb');
		$(".media_image_new").val(get_path);
		$(".media_thumb_new").val(get_thumb);
		$("input[name=media_id]").val($(this).attr('data-id'));
		$("#selected_images").html('<img src="' + get_thumb + '"/>');
	});


	//Create Bookmark from Frontend Modal    
	$.validator.setDefaults({
		submitHandler: function () {
			var media_path = $(".media_image_new").val();
			var media_thumb = $(".media_thumb_new").val();
			if (media_path == "" || media_thumb == "") {
				swal("Error!", "Please select at least one media", "error");
			} else {
				$.ajax({
					url: ajax_url + 'graphicscontroller/create_bookmark',
					type: "POST",
					data: new FormData($('.createNew')[0]),
					dataType: "json",
					contentType: false,
					processData: false,
					beforeSend: function (xhr) {
						$(".browseFile button").html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function (resp) {
						if (resp.code == 200) {
							swal("Success!", resp.message, "success");
							$(".media_image_new").val('');
							$(".media_thumb_new").val('');
							$("#bookmark_form").submit();
							$('.createNew')[0].reset();
						} else {
							swal("Error!", resp.message, "error");
						}
					},
					error: function () {
						$(".browseFile button").html('Upload');
						swal("Error!", "Failed to upload media on server", "error");
					}
				});
			}
		}
	});

	$('.createNew').validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-input').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});
	//Create Bookmark from Frontend Modal


	//Assign Bookmark scheme
	$("body").on("click", ".assign_bookmark_scheme", function () {
		var bookmark_id = $(this).attr('data-id');
		var type_id = $(".tabs_data").val();
		var type = $(".tabs_type").val();

		var subtab_status=$(".subtab_status").val();
		
		if(subtab_status=="yes"){
			type_id=$(".bookmark_subtabs .active_subtab").attr('data-id');
			type=$(".bookmark_subtabs .active_subtab").attr('data-type');
		}

		$.ajax({
			url: ajax_url + 'graphicscontroller/assign_bookmark',
			type: "POST",
			dataType: "json",
			data: {
				'bookmark_id': bookmark_id,
				'type_id': type_id,
				'type': type
			},
			beforeSend: function (xhr) {
				$(".browseFile button").html('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function (resp) {
				if (resp.code == 200) {
					swal("Success!", resp.message, "success");
					$(".media_image_new").val('');
					$(".media_thumb_new").val('');
					//$("#bookmark_form").submit();
					$('.createNew')[0].reset();
				} else {
					swal("Error!", resp.message, "error");
				}
			},
			error: function () {
				$(".browseFile button").html('Upload');
				swal("Error!", "There is some technical issue", "error");
			}
		});

	});
	//End ASsign bookmark scheme


	$("body").on("click", "#close_assign_modal span", function () {
		$("#insertbookmartModal").modal("hide");
		var subtab_status=$(".subtab_status").val();
		
		if(subtab_status=="yes"){
			$(".bookmark_subtabs li.active_subtab a").trigger("click");
		} else {
			$(".bookmark_tabs li.active a").trigger("click");	
		}
	});


	$(".bookmark_tabs li:nth-child(1) a").trigger("click");


});

function booksmarkssort() {
	reorder = $('#card-group');
	reorder.sortable({
		//opacity: 0.7,
		//items: "div:not(.unsortable)",
		update: function () {
			var counter = 0;
			var sortreOrder = [];
			reorder.children('div.card').each(function () {
				sortreOrder[counter] = $(this).attr('id');
				counter++;
			});
			//console.log(sortreOrder);
			var activeid = $("#tabs-list li.active a").attr('data-id');
			var activedatatype = $("#tabs-list li.active").attr('data-type');
			$.ajax({
				method: "POST",
				url: ajax_url + 'bookmarkuserscontroller/save_bookmark',
				data: {
					order: sortreOrder,
					active: activeid,
					datatype: activedatatype
				},
				success: function (data) {
					console.log(data);
				}
			});
		}
	});
    reorder.disableSelection();

	$("body").on("click", ".delete_bookmark_data", function (event) {
		event.stopPropagation();

		swal({
			title: "Are you sure?",
			text: "You want to unassign this bookmark",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
			.then((willDelete) => {
				if (willDelete) {

					var $this = $(this);
					$this.css('pointer-events', 'none');
					var bookmark_id = $this.attr('data-id');
					var type = $("li.bookmark_nav.active").attr('data-type');
					var type_id = $("li.bookmark_nav.active").attr('data-id');

					$.ajax({
						url: ajax_url + 'graphicscontroller/unassign_bookmark',
						type: "POST",
						dataType: "json",
						data: {
							'bookmark_id': bookmark_id,
							'type_id': type_id,
							'type': type
						},
						beforeSend: function (xhr) {
							$this.parent().prepend('<div id="spinner" style="display:block;"></div>');
						},
						success: function (resp) {
							if (resp.code == 200) {
								swal("Success!", resp.message, "success");
								$this.parent().fadeOut("slow");
							} else {
								swal("Error!", resp.message, "error");
							}
						},
						error: function () {
							swal("Error!", "There is some technical issue", "error");
						}
					});
				}
			});
	});
}

function load_more(element) {
	page = $(element).data('page');
	var total = Number($(element).data('page'));
	$(element).attr('data-page', total + 1);
	$.ajax({
		url: sitebase_url + "/Graphicscontroller/loadMoreGraphics?page=" + page,
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
		for (var i = 0; i < data.result.length; i++) {
			html += '<div onclick="selecticon(this)" data-path="' + data.result[i].path + '" data-thumb="' + data.result[i].thumb + '" class="filtr-item col-sm-2" id="icon-' + data.result[i].id + '" data-id="' + data.result[i].id + '">';
			html += '<img class="card-img-top img-fluid mb-2" src="' + data.result[i].thumb + '" alt="icon">';
			html += '</div>';
		}
		$('#loader').hide();
		$('#card-group .filter-container').append(html).show('slow');
		if (Number($(element).data('totalpage')) == total + 1) {
			$(".load-more").hide();
		}
	}).fail(function (jqXHR, ajaxOptions, thrownError) {
		console.log('No response');
	});
}

function selecticon(element) {
	$("input[name=icon_path]").val($(element).data('path'));
	$("input[name=icon_thumb]").val($(element).data('thumb'));
	$(".selected-img").attr('src', $(element).data('thumb'));
	$("#graphicModal").modal('hide');
}

function loadgraphics(path = '') {
	$.get(path, function (data) {
		$('#graphicModal .modal-body #graphic-content').html(data);
		$("#graphicModal").modal('show');
		iconvalidate();
	});

}

function iconvalidate() {
	$.validator.setDefaults({
		submitHandler: function () {
			$.ajax({
				url: ajax_url + 'graphicscontroller/save',
				type: "POST",
				data: new FormData($('.update_data')[0]),
				dataType: "json",
				contentType: false,
				processData: false,
				beforeSend: function (xhr) {
					$("#status_data").hide()
					$("#upload-icon #save_data").prop("disabled", true).addClass('btn-default').removeClass('btn-primary').html('Please Wait <i class="fas fa-sync-alt fa-spin" style="font-size: 16px;color: #007bff;"></i>');
				}, success: function (resp) {
					$("#upload-icon #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');
					if (resp.success_status) {
						$('.update_data')[0].reset();
						$("#edit_result_sts").html('<div class="alert alert-success alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' + resp.success_status + '</div>')
						loadgraphics('commoncontroller/geticons?user_id=' + currentuser_id);
					} else {
						$("#edit_result_sts").html('<div class="alert alert-danger alert-dismissible fade in" style="opacity: 1;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' + resp.error_status + '</div>')
					}
				}, error: function () {
					$("#upload-icon #save_data").prop("disabled", false).addClass('btn-primary').removeClass('btn-default').html('Submit');

				}
			});
			;
		}
	});

	$('.update_data').validate({
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
}

jQuery(document).on("click", "div.custom-pagination a", function (e) {
	e.preventDefault();
	loadgraphics(jQuery(this).attr('href'));
	iconvalidate();
});

//End Insert data with Validation


jQuery(document).on('click', ".user-edit-action", function (e) {
	jQuery("#insert-new-tab , .delete-bk").toggleClass('d-none');
	if (jQuery("#insert-new-tab").hasClass('d-none')) {
		jQuery(".user-edit-action").html('Edit');
	} else {
		jQuery(".user-edit-action").html('Save');
	}
});


// jQuery(document).on('click', ".user-edit-action", function (e) {
// 	jQuery("#insert-new-tab , .delete-bk").toggleClass('d-none');
// 	if (jQuery("#insert-new-tab").hasClass('d-none')) {
// 		jQuery(".user-edit-action").html('Edit');
// 	} else {
// 		jQuery(".user-edit-action").html('Save');
// 	}
// });
// var hide_label =jQuery(".profile-form-wrapper input[type='checkbox']").val();
// if (hide_label==1){
            
//             // alert("js is working");
//      jQuery("#bookmarks p").css("display", "none");div #bookmarks a p {
//     /* display: none;
	
// }

