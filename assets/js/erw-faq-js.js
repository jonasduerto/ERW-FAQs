jQuery(function(){ //DOM Ready
    faqsSetClickHandlers();
    faqsSetAutoCompleteClickHandlers();
    faqsSetRatingHandlers();
    faqsSetExpandCollapseHandlers();
});

function runEffect(display, post_id) {
    var selectedEffect = reveal_effect;
    // most effect types need no options passed by default
    var options = {};
    // some effects have required parameters
    if ( selectedEffect === "size" ) {
      options = { to: { width: 200, height: 60 } };
    }
    // run the effect
    if (display == "show") {jQuery( "#faqs-body-"+post_id ).show( selectedEffect, options, 500, handleStyles(display, post_id) );}
	if (display == "hide") {jQuery( "#faqs-body-"+post_id ).hide( selectedEffect, options, 500, handleStyles(display, post_id) );}
};

// callback function to bring a hidden box back
function handleStyles(display, post_id) {
	if (display == "show") {setTimeout(function() {jQuery('#faqs-body-'+post_id).removeClass("ERW-FAQ-hidden"); }, 500 );}
	if (display == "hide") {setTimeout(function() {jQuery('#faqs-body-'+post_id).addClass("ERW-FAQ-hidden");}, 500 );}
};

function faqsSetClickHandlers() {
	jQuery('.faqs-faq-toggle').off('click').on('click', function(event) {
		var post_id = jQuery(this).attr("data-postid");
		
		event.preventDefault();
		
		var selectedIDString = 'faqs-body-'+post_id;
		
		if (jQuery('#'+selectedIDString).hasClass("ERW-FAQ-hidden")) {
			ERW_FAQ_Reveal_FAQ(post_id, selectedIDString);
		}
		else {
			ERW_FAQ_Hide_FAQ(post_id);
		}
	});

	jQuery('.faqs-faq-category-title-toggle').off('click').on('click', function(event) {
		var category_id = jQuery(this).attr("data-categoryid");
		
		if (jQuery('#faqs-faq-category-body-'+category_id).hasClass("faqs-faq-category-body-hidden")) {
			jQuery('#faqs-faq-category-body-'+category_id).removeClass("faqs-faq-category-body-hidden");
		}
		else {
			jQuery('#faqs-faq-category-body-'+category_id).addClass("faqs-faq-category-body-hidden");
		}
	});

	jQuery('.faqs-back-to-top-link').off('click').on('click', function(event) {
		event.preventDefault();

		jQuery('html, body').animate({scrollTop: jQuery("#faqs-faq-list").offset().top -80}, 100);
	});

	jQuery('.faqs-faq-header-link').off('click').on('click', function(event) {
		event.preventDefault();

		var faqID = jQuery(this).data("postid");
		if (jQuery('#faqs-body-'+faqID).hasClass('ERW-FAQ-hidden')) {
			var selectedIDString = 'faqs-body-'+faqID;
			ERW_FAQ_Reveal_FAQ(faqID, selectedIDString);
		}
		jQuery('html, body').animate({scrollTop: jQuery("#faqs-post-"+faqID).offset().top -20}, 100);
	});
}

function faqsSetAutoCompleteClickHandlers() {
	jQuery('#faqs-ajax-text-input').on('keyup', function() {
		if (typeof autocompleteQuestion === 'undefined' || autocompleteQuestion === null) {autocompleteQuestion = "No";}
		if (autocompleteQuestion == "Yes") {
			jQuery('#faqs-ajax-text-input').autocomplete({
				source: questionTitles,
				minLength: 3,
				appendTo: "#ERW-FAQ-jquery-ajax-search",
				select: function(event, ui) {
					jQuery(this).val(ui.item.value);
        			faqs_Ajax_Reload();
				}
			});
			jQuery('#faqs-ajax-text-input').autocomplete( "enable" );
		}
	}); 
}

function ERW_FAQ_Reveal_FAQ(post_id, selectedIDString) {
	var data = 'post_id=' + post_id + '&action=faqs_record_view';
    jQuery.post(ajaxurl, data, function(response) {});

    jQuery('#ERW-FAQ-post-symbol-'+post_id).html(jQuery('#ERW-FAQ-post-symbol-'+post_id).html().toUpperCase());

	jQuery('#faqs-excerpt-'+post_id).addClass("ERW-FAQ-hidden");

	if (reveal_effect != "none") {runEffect("show", post_id); }
	else {jQuery('#faqs-body-'+post_id).removeClass("ERW-FAQ-hidden"); }
			
	if (faq_accordion) {
		jQuery('.faqs-faq-div').each(function() {
			if (jQuery(this).data("postid") != post_id) {
		  		ERW_FAQ_Hide_FAQ(jQuery(this).data("postid"));
			} else{
				jQuery(this).addClass("ERW-FAQ-post-active");
			}
		});
	}
	else {
		jQuery('#faqs-post-'+post_id).addClass("ERW-FAQ-post-active");
	}
}

function ERW_FAQ_Hide_FAQ(post_id) {
	jQuery('#faqs-excerpt-'+post_id).removeClass("ERW-FAQ-hidden");

	if (reveal_effect != "none") {runEffect("hide", post_id);}
	else {jQuery('#faqs-body-'+post_id).addClass("ERW-FAQ-hidden");}
	jQuery('#faqs-post-'+post_id).removeClass("ERW-FAQ-post-active");
	jQuery('#ERW-FAQ-post-symbol-'+post_id).html(jQuery('#ERW-FAQ-post-symbol-'+post_id).html().toLowerCase());
}

jQuery(document).ready(function() {
	if (typeof(faq_scroll) == "undefined") {faq_scroll = false;}
	if (faq_scroll) {
    	jQuery('.faqs-faq-title').click(function(){
    		var faqID = jQuery(this).attr('id'); 
    		jQuery('html, body').animate({scrollTop: jQuery(this).offset().top -80}, 100);
    	});
	}

    jQuery("#faqs-ajax-search-btn").click(function(){
		faqs_Ajax_Reload();
    });

	jQuery('#faqs-ajax-form').submit( function(event) {
		event.preventDefault();
		faqs_Ajax_Reload();
	});

	jQuery('#faqs-ajax-text-input').keyup(function() {
		faqs_Ajax_Reload();
	});

	if (typeof(Display_FAQ_ID) != "undefined" && Display_FAQ_ID !== null) {
		Display_FAQ_ID_Pos = Display_FAQ_ID.indexOf('-');
		Display_FAQ_ID = Display_FAQ_ID.substring(0, Display_FAQ_ID_Pos);
		var selectedIDString = jQuery('.faqs-body-'+Display_FAQ_ID).attr('id');
		Display_FAQ_ID = selectedIDString.substring(10);
		ERW_FAQ_Reveal_FAQ(Display_FAQ_ID, selectedIDString);
		jQuery('html, body').delay(800).animate({scrollTop: jQuery("#"+selectedIDString).offset().top - 180}, 300);
	}
});

var RequestCount = 0;
function faqs_Ajax_Reload() {
    var Question = jQuery('.faqs-text-input').val();
    var include_cat = jQuery('#faqs-include-category').val();
    var exclude_cat = jQuery('#faqs-exclude-category').val();
    var orderby = jQuery('#faqs-orderby').val();
    var order = jQuery('#faqs-order').val();
    var post_count = jQuery('#faqs-post-count').val();

    jQuery('#faqs-ajax-results').html('<h3>' + ERW_FAQ_php_data.retrieving_results + '</h3>');
    RequestCount = RequestCount + 1;

    var data = 'Q=' + Question + '&include_category=' + include_cat + '&exclude_category=' + exclude_cat + '&orderby=' + orderby + '&order=' + order + '&post_count=' + post_count + '&request_count=' + RequestCount + '&action=faqs_search';
    jQuery.post(ajaxurl, data, function(response) {
        response = response.substring(0, response.length - 1);
		var parsed_response = jQuery.parseJSON(response);
		if (parsed_response.request_count == RequestCount) {
			jQuery('#faqs-ajax-results').html(parsed_response.message);
       		faqsSetClickHandlers();
       		faqsSetRatingHandlers();
       	}
    });
}

function faqsSetRatingHandlers() {
	jQuery('.ERW-FAQ-rating-button').off('click');
	jQuery('.ERW-FAQ-rating-button').on('click', function() {
		var FAQ_ID = jQuery(this).data('ratingfaqid');
		jQuery('*[data-ratingfaqid="' + FAQ_ID + '"]').off('click');

		var Current_Count = jQuery(this).html();
		Current_Count++;
		jQuery(this).html(Current_Count);

		if (jQuery(this).hasClass("ERW-FAQ-up-vote")) {Vote_Type = "Up";}
		else {Vote_Type = "Down";}

		var data = '&FAQ_ID=' + FAQ_ID + '&Vote_Type=' + Vote_Type + '&action=faqs_update_rating';
    	jQuery.post(ajaxurl, data, function(response) {
    	});
	});
}

function faqsSetExpandCollapseHandlers() {
	jQuery('.ERW-FAQ-expand-all').on('click', function() {
		jQuery('.faqs-faq-toggle').each(function() {
			var post_id = jQuery(this).attr("data-postid");
			var selectedIDString = 'faqs-body-'+post_id;
			ERW_FAQ_Reveal_FAQ(post_id, selectedIDString);
		});
		jQuery('.faqs-faq-category-inner').removeClass('faqs-faq-category-body-hidden');
		jQuery('.ERW-FAQ-collapse-all').removeClass('ERW-FAQ-hidden');
		jQuery('.ERW-FAQ-expand-all').addClass('ERW-FAQ-hidden');
	});
	jQuery('.ERW-FAQ-collapse-all').on('click', function() {
		jQuery('.faqs-faq-toggle').each(function() {
			var post_id = jQuery(this).attr("data-postid");
			ERW_FAQ_Hide_FAQ(post_id);
		});
		if (jQuery('.faqs-faq-category-title-toggle').length > 0) {jQuery('.faqs-faq-category-inner').addClass('faqs-faq-category-body-hidden');}
		jQuery('.ERW-FAQ-expand-all').removeClass('ERW-FAQ-hidden');
		jQuery('.ERW-FAQ-collapse-all').addClass('ERW-FAQ-hidden');
	});
}

/*jQuery(document).ready(function() {
  jQuery('a[href*=#]:not([href=#])').click(function() {
  	var post_id = jQuery(this).attr("data-postid"); 
    var selectedIDString = 'faqs-body-'+post_id;
    
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
      if (target.length) {

    jQuery('html,body').on("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove", function(){
       jQuery('html,body').stop();
    });
		
		if (jQuery('#'+selectedIDString).hasClass("ERW-FAQ-hidden")) {
			ERW_FAQ_Reveal_FAQ(post_id, selectedIDString);
		}

        jQuery('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        //return false;
      }
    }
  });
});*/
