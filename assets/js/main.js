(function ($) {
	("use strict");
	
	var ajax_url = ajax_obj.ajax_url;


	// Set cookie value function
	function setCookie(cname, cvalue, exdays) {
		const d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		
		// For secure Site
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=None; Secure";

		// For non-secure Site
		// document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=None;";
		
	}

	// Set cookie value function
	/*
	function setCookie(cname, cvalue, exdays) {
		const d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		
		// For secure Site
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=None; Secure";
		

		// For non-secure Site
		// document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=None;";
	}
	*/

	// Get cookie value function
	function getCookie(cname) {
		let name = cname + "=";
		let decodedCookie = decodeURIComponent(document.cookie);
		let ca = decodedCookie.split(';');
		for(let i = 0; i <ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') {
			c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
			}
		}
		return;
	}

	// Group Meta Field Clone
    function mb_cl_group_clone(container) {
        
        // Generete Random ID
        let unique_key = (Math.random() + 1).toString(36).substring(2);

        // Clone the last child of cl_mb_clone_single class
        let group_id = container.data("group_id");
        let last = container.children('.cl_mb_clone_single').last();
        let clone = last.clone();

		// Insert clone.
        clone.insertAfter(last);

        // Insert Random ID on cloned fields
        let cloned = container.children('.cl_mb_clone_single').last().children('.column');
        cloned.each(function () {
            let group_field_id = $(this).data("group_field_id");
            let name = group_id + '[' + unique_key + '][' + group_field_id + ']';
            let group_key = $(this).children().eq(1).data("key");
            let target_input = $(this).children().last();
            if (target_input.is("input") || target_input.is("select")|| target_input.is("textarea")) {
                $(this).children().last().attr('name', name);
                $(this).children().last().attr('id', name);
            } else {
                var value = new RegExp(group_key,"g");
                $(this).html($(this).html().replace(value,unique_key));
            }
        })
        
    }

	function addGroupClone(e) {
		e.preventDefault();
		var container = $( this ).closest( '.column' );
		mb_cl_group_clone( container );
    }

	function removeClone(e) {
        e.preventDefault();
        var mb_col = $(this).closest('.column');
        if ($(mb_col).find('.cl_mb_clone_single').length !== 1) { /* Check if the clone div is the only child exist. */
            $(this).parent().remove();
        }
    }

	function single_img_upload (e) {
		e.preventDefault();
		var $img_area = $(this);
		wp_media = wp.media.frames.wp_media = wp.media({
			title: $(this).data("uploader_title"),
			button: {
				text: $(this).data("uploader_button_text"),
			},
			multiple: false, // set this to true for multiple file selection
		});

		wp_media.on("select", function () {
			attachment = wp_media.state().get("selection").first().toJSON();
			var files_featured = $img_area.siblings('.files_featured');
			files_featured.attr("src", attachment.url);
			$img_area.siblings(".single_img_id").val(attachment.id);
	
		});

		wp_media.open();
	}

	function classy_notification_pop(param){
		var rand = Math.random().toString(36).substring(2,7);
		$(".clasify-classified-plugin-alart").append('<div class="clasify-classified-plugin-desc ' + rand + '"><span class="close-button dashicons dashicons-no-alt"></span>' + param + '</div>');
		setTimeout(function(){
			$(".clasify-classified-plugin-desc." + rand).remove();
		}, 5000);
	}

	// -- Notification Close Button
	$(document).on("click" ,".clasify-classified-plugin-alart .close-button", function (e) {
		e.preventDefault();
		$(this).parent().remove();
	});

    // -- add to favorite button
    $(".add-to-favorite").on("click", function (e) {
        e.preventDefault();
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        $.post(ajax_url, {
            action: "cl_add_to_favorite",
            post_id: postid,
            user_id: userid,
        }).done(function (s) {
            if (s == "added") {
                $("#like_listing" + postid + ".add-to-favorite").addClass("cl_favorite_item");
				classy_notification_pop('Item added to favourite.');
            } else {
                $("#like_listing" + postid + ".add-to-favorite").removeClass("cl_favorite_item");
				classy_notification_pop('Item removed from favourite.');
            }
        });
    });


    // -- ADD TO COMPARE
	$(document).on("click" ,".add-to-compare", function (e) {
		e.preventDefault();
		var postid = String($(this).data("postid"));
		var result = [];
		$.ajax({
			type: "POST",
			url: ajax_obj.ajax_url,
			data: {
				action: "cl_compare_func",
			},
			beforeSend: function () {
				if (typeof getCookie('compare_listing_data') !== 'undefined') {
					result.push(getCookie('compare_listing_data'));
					result = result.toString().split(",");
					if ($.inArray(postid, result) === -1) {
						result.push(postid);
					}
				} else {
					result.push(postid);
				}
				result = result.filter(item => item);
				setCookie('compare_listing_data', result, 30);
			},
			success: function (html) {
				if (result.length != 0) {
					$(".clasify-classified-plugin-compare-wrapper").fadeIn();
				}
				$(".clasify-classified-plugin-compare-items").html(html);
				classy_notification_pop('Item added to compare.');
			},
		});
	});


	$(document).on("click" ,"#delete-listing", function (e) {
		e.preventDefault();
		var conf_val = $(this).data("warning");
		if(confirm(conf_val)) {
			var postid = String($(this).data("listing-id"));
			var result = [];
			$.ajax({
				type: "POST",
				url: ajax_obj.ajax_url,
				data: {
					action: "cl_delete_listing_func",
					listing_id: postid,
				},
				success: function (response) {
					if (response.length != 0) {
						window.location = window.location.href;
					}
				},
			});
		}

		
	});

	$(document).on("click", ".clasify-classified-plugin-compare-remove-btn",function (e) {
		e.preventDefault();
		var postid = String($(this).data("remove_compare_item"));
		var result = [];
		$.ajax({
			type: "POST",
			url: ajax_obj.ajax_url,
			data: {
				action: "cl_compare_func",
			},
			beforeSend: function () {
				if (typeof getCookie('compare_listing_data') !== 'undefined') {
					result.push(getCookie('compare_listing_data'));
					result = result.toString().split(",");
					var index = result.indexOf(postid);
					if (index !== -1) {
						result.splice(index, 1);
						$("#clasify-classified-plugin-compare-item" + postid).remove();
					}
				}
				result = result.filter(item => item);
				setCookie('compare_listing_data', result, 30);
			},
			success: function (html) {
				if (result.length === 0) {
					$(".clasify-classified-plugin-compare-wrapper").fadeOut();
				}
				$(".clasify-classified-plugin-compare-items").html(html);
			},
		});
	});

	$(document).on('click', '#cl_purchase_form #cl_login_fields input[type=submit]', function(e) {
		e.preventDefault();
		var complete_purchase_val = $(this).val();
		$(this).val(ajax_obj.purchase_loading);
		$(this).after('<span class="cl-loading-ajax cl-loading"></span>');
		var data = {
			action : 'cl_process_checkout_login',
			cl_ajax : 1,
			cl_user_login : $('#cl_login_fields #cl_user_login').val(),
			cl_user_pass : $('#cl_login_fields #cl_user_pass').val()
		};
		$.post(ajax_obj.ajax_url, data, function(data) {

			if ( $.trim(data) == 'success' ) {
				$('.cl_errors').remove();
				window.location = cl_scripts.checkout_page;
			} else {
				$('#cl_login_fields input[type=submit]').val(complete_purchase_val);
				$('.cl-loading-ajax').remove();
				$('.cl_errors').remove();
				$('#cl-user-login-submit').before(data);
			}
		});
	});

	if (getCookie('compare_listing_data')) {
		$(".clasify-classified-plugin-compare-wrapper").show();
	}

	// Select Property Types
	$('.form-control.select2').select2({
		allowClear: false
	});
	
	$(document).ready(function () {
		// Upload images.
		$('body').on('click', '.mb_img_upload_btn', function(e){
			e.preventDefault();
			var cl_mb_img_id    = $(this).attr("id");
			var cl_mb_img_name  = $(this).data("name");
			var product_images  = $('#' + cl_mb_img_id + '_cont') ;
			var button = $(this),
			aw_uploader = wp.media({
				title: 'Choose Media',
				library : {
					// uploadedTo : wp.media.view.settings.post.id, // - Uploaded to post id
					// uploadedTo : wp.media
					type : 'image'
				},
				button: {
					text: 'Use this image'
				},
				multiple: true
			}).on( 'select', function() {
				var selection = aw_uploader.state().get('selection');
				var attachment_ids = $('#' + cl_mb_img_id).val();
				selection.map( function( attachment ) {
					attachment = attachment.toJSON();
					if ( attachment.id ) {
						attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
						var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
						$('.cl_mb_placeholder#' + cl_mb_img_id).remove();
						product_images.append(
							'<div id="' + attachment.id + '" class="single_img"><input type="hidden" name="' + cl_mb_img_name + '[]" value="' + attachment.id + '"><img id="' + attachment.id + '" src="' + attachment_image + '" width="150" height="150"/><a id="' + cl_mb_img_id + '" data-img_id="' + attachment.id + '" class="cl-remove" href="javascript:void(0)">X</a></div>'
						);
					}
				});
			})
			.open();
		});

		// Remove images.
		$('body').on('click', 'a.cl-remove', function (e) {
			e.preventDefault();
			var cl_mb_img_id        = $(this).attr("id");
			var data_placeholder    = $('.cl_mb_clear_btn').data("placeholder");
			$(this).parent().remove();
			if (!$.trim($('#' + cl_mb_img_id +'_cont').html()).length) {
				$('#' + cl_mb_img_id + '_cont').replaceWith(
					'<div id="' + cl_mb_img_id + '_cont" class="components-responsive-wrapper"><img id="' + cl_mb_img_id + '" class="cl_mb_placeholder" src="' + data_placeholder + '"></div>'
				);
			}
			return false;
		});

	});

	// Clear images.
    $('body').on('click', '.cl_mb_clear_btn', function(e){
        e.preventDefault();
        var data_placeholder    = $(this).data("placeholder");
        var cl_mb_remove_id     = $(this).attr("id");
        
        $('#' + cl_mb_remove_id + '_cont').replaceWith(
            '<div id="' + cl_mb_remove_id + '_cont" class="components-responsive-wrapper"><img id="' + cl_mb_remove_id + '" class="cl_mb_placeholder" src="' + data_placeholder + '"></div>'
        );
        $('.mb_img_upload_btn#' + cl_mb_remove_id).attr("src", data_placeholder);
    });

	// -- add to favorite button
    $(".remove-from-favorite").on("click", function (e) {
        e.preventDefault();
        var postid = $(this).data("postid");
        var userid = $(this).data("userid");
        $.post(ajax_url, {
            action: "cl_add_to_favorite",
            post_id: postid,
            user_id: userid,
        }).done(function (s) {
            $("#favourite_item_" + postid).slideUp();
			setTimeout(function(){
				$("#favourite_item_" + postid).remove();
			}, 1000);
        });
    });

	// -- add to dash_overview button
    $("#dash_overview").on("click", function (e) {
		setCookie('dash_menu_activate', 'dash_overview', 30);
        e.preventDefault();
		$(this).addClass('active');
        $("#dash_overview_section").addClass('active');
		$("#dash_my_listings").removeClass('active');
		$("#dash_my_listings_section").removeClass('active');
		$("#dash_fav_listings").removeClass('active');
        $("#dash_fav_listings_section").removeClass('active');
        $("#dash_profile_section").removeClass('active');
        $("#dash_profile").removeClass('active');
	});
	// -- add to dash_my_listings button
    $("#dash_my_listings").on("click", function (e) {
		setCookie('dash_menu_activate', 'dash_my_listings', 30);
        e.preventDefault();
		$(this).addClass('active');
        $("#dash_my_listings_section").addClass('active');
		$("#dash_overview").removeClass('active');
		$("#dash_overview_section").removeClass('active');
		$("#dash_fav_listings").removeClass('active');
        $("#dash_fav_listings_section").removeClass('active');
        $("#dash_profile_section").removeClass('active');
        $("#dash_profile").removeClass('active');
    });
	// -- add to dash_fav_listings button
    $("#dash_fav_listings").on("click", function (e) {
		setCookie('dash_menu_activate', 'dash_fav_listings', 30);
        e.preventDefault();
		$(this).addClass('active');
        $("#dash_fav_listings_section").addClass('active');
        $("#dash_overview").removeClass('active');
        $("#dash_overview_section").removeClass('active');
        $("#dash_my_listings").removeClass('active');
        $("#dash_my_listings_section").removeClass('active');
        $("#dash_profile").removeClass('active');
        $("#dash_profile_section").removeClass('active');
    });
	// -- add to dash_fav_listings button
    $("#dash_profile").on("click", function (e) {
		setCookie('dash_menu_activate', 'dash_profile', 30);
        e.preventDefault();
		$(this).addClass('active');
		$("#dash_profile_section").addClass('active');
		$("#dash_overview").removeClass('active');
		$("#dash_overview_section").removeClass('active');
        $("#dash_my_listings").removeClass('active');
        $("#dash_fav_listings").removeClass('active');
        $("#dash_my_listings_section").removeClass('active');
        $("#dash_fav_listings_section").removeClass('active');
	});

	$(document).on("click", 'button.clone_group_btn', addGroupClone);
	$(document).on("click", 'button.remove_clone_btn', removeClone);

	$("#listing-equiry-form form").on("submit", function (e) {
		e.preventDefault();
		var data = $(this).serialize();
		$.post(ajax_url, data, function (response) {
			if (response.success) {
			$("#message").html(
				'<div class="alert alert-success" role="alert">' +
				response.data.message +
				"</div>"
			);
			} else {
			$("#message").html(
				'<div class="alert alert-warning" role="alert">' +
				response.data.message +
				"</div>"
			);
			}
		}).fail(function () {
			$("#message").html(
			'<div class="alert alert-warning" role="alert">' +
				response.data.message +
				"</div>"
			);
		});
	});

	 // Images Sortable
	 $('body').on('mouseenter', '.components-responsive-wrapper', function (e){
        e.preventDefault();

        /* Enable Sortable */
        $(function(){
            $('.components-responsive-wrapper').sortable({
                items: "> div",
                placeholder: 'ui-state-highlight',
                over: function(event, ui) {
                        var cl = ui.item.attr('class');
                        $('.ui-state-highlight').addClass(cl);
                    }
            }).disableSelection();
        });

    });

	

	// Upload images.
	
	$(".frontend-avatar").on("click", single_img_upload);
	$("#add-ft-img").on("click", single_img_upload);


})(jQuery);