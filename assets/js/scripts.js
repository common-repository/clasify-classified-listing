!function ($) {
"use strict";
$( document ).ready(function( ) {

	$('.select2-active').select2();

	$('.listing_abuse').on('click',function(){
		$('#listing_abuse_dialog').dialog('open');
	})
	$('#listing_abuse_dialog').dialog({
		title: ajax_obj.abuse_dialog,
		dialogClass: 'wp-dialog',
		autoOpen: false,
		draggable: false,
		width: 'auto',
		modal: true,
		resizable: false,
		closeOnEscape: true,
		position: {
		  my: "center",
		  at: "center",
		  of: window
		},
		open: function () {
		  // close dialog by clicking the overlay behind it
			$('.ui-widget-overlay').bind('click', function(){
				$('#listing_abuse_dialog').dialog('close');
			})
		},
		create: function () {
			// style fix for WordPress admin
			$('.ui-dialog-titlebar-close').addClass('ui-button');
			$('#listing_abuse_dialog_form').on('submit', function(e){
				e.preventDefault();
				var settings = $(this).serialize();
				$.ajax({
					type: "POST",
					url: ajax_obj.ajax_url,
					data: settings,
					dataType : 'html',
					success: function(res)
					{
						var returnval = JSON.parse(res);
						$('.listing_abuse_dialog_return').text(returnval.message);
						$('.listing_abuse_dialog_return').addClass(returnval.class);
						if(returnval.success){
							setTimeout(function(){ $('#listing_abuse_dialog').dialog('close');; }, 500);
						}
					},
					error: function()
					{
					}
				});
			})
		},
	});
	
	// Slick Slider
	$('.gallery-slider-active').slick({
	  lazyLoad: 'ondemand',
	  slidesToShow:1,
	  slidesToScroll: 1,
	  arrows: true,
	  autoplay:false,
	  fade: true,
	  dots:true,
	  autoplaySpeed: 4000,
	  prevArrow: '<button class="slick-left"><i class="fas fa-chevron-left"></i></button>',
      nextArrow: '<button class="slick-right"><i class="fas fa-chevron-right"></i></button>'
	});

	// Featured Slick Slider
	$('.featured_slick_gallery-slide').slick({
		centerMode: true,
		infinite:true,
		centerPadding: '80px',
		slidesToShow:1,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					arrows:true,
					centerMode: true,
					centerPadding: '20px',
					slidesToShow:1
				}
			},
			{
				breakpoint: 480,
				settings: {
					arrows: false,
					centerMode: true,
					centerPadding: '10px',
					slidesToShow: 1
				}
			}
		],
		prevArrow: '<button class="slick-left"><i class="fas fa-chevron-left"></i></button>',
      	nextArrow: '<button class="slick-right"><i class="fas fa-chevron-right"></i></button>'
	});
	
	// Featured Slick Slider
	$('.featured_slick_gallery-slide-single').slick({
		centerMode: true,
		centerPadding: '0px',
		slidesToShow:1,
		responsive: [
		{
			breakpoint: 768,
			settings: {
				arrows:true,
				centerMode: false,
				centerPadding: '0px',
				slidesToShow:1
			}
		},
		{
			breakpoint: 480,
			settings: {
				arrows: false,
				centerMode: false,
				centerPadding: '0px',
				slidesToShow:1
			}
		}
		]
	});
	
	// -- Compare btn

	$(".clasify-classified-plugin-collapse-btn").on("click", function (e) {
		e.preventDefault();
		$(".clasify-classified-plugin-compare-container").toggle("slide");
	});

	$("#commentform input").on("change", function () {
		var property		= $("input[name=property]:checked", "#commentform").val();
		var value_for_money	= $("input[name=value_for_money]:checked", "#commentform").val();
		var agent_support	= $("input[name=agent_support]:checked", "#commentform").val();
		var location		= $("input[name=location]:checked", "#commentform").val();
		if (property && value_for_money && agent_support && location) {
			var tatal = parseInt(property) + parseInt(value_for_money) + parseInt(agent_support) + parseInt(location);
			var avg = tatal / 4;
		} else if (property && value_for_money && agent_support) {
			var tatal = parseInt(property) + parseInt(value_for_money) + parseInt(agent_support);
			var avg = tatal / 3;
			var avg = avg.toFixed(2);
		} else if (property && value_for_money) {
			var tatal = parseInt(property) + parseInt(value_for_money);
			var avg = tatal / 2;
		} else {
			var tatal = parseInt(property);
			var avg = tatal;
		}
		$(".user_commnet_avg_rate").text(avg);
	});

});




}(jQuery);
