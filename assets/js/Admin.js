/* Used to show and hide the admin tabs for UPCP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

/* This code is required to make changing the FAQ order a drag-and-drop affair */
jQuery(document).ready(function() {
	jQuery('.ERW-FAQ-list').sortable({
		items: '.ERW-FAQ-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=faqs_update_order';
			jQuery.post(ajaxurl, order, function(response) {});
		}
	});
});

function ShowOptionTab(TabName) {
	jQuery(".faqs-option-set").each(function() {
		jQuery(this).addClass("faqs-hidden");
	});
	jQuery("#"+TabName).removeClass("faqs-hidden");
	
	// var activeContentHeight = jQuery("#"+TabName).innerHeight();
	// jQuery(".faqs-options-page-tabbed-content").animate({
	// 	'height':activeContentHeight
	// 	}, 500);
	// jQuery(".faqs-options-page-tabbed-content").height(activeContentHeight);

	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
}

jQuery(document).ready(function() {
	SetCustomFieldDeleteHandlers();

	jQuery('.ERW-FAQ-add-custom-field').on('click', function(event) {
		var Counter = jQuery(this).data('nextid');
		var Max_ID = jQuery(this).data('maxid');

		var HTML = "<tr id='ERW-FAQ-custom-field-row-" + Counter + "'>";
		HTML += "<td><input type='hidden' name='Custom_Field_" + Counter + "_ID' value='" + Max_ID + "' /><a class='ERW-FAQ-delete-custom-field' data-fieldid='" + Counter + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Custom_Field_" + Counter + "_Name'></td>";
		HTML += "<td><select name='Custom_Field_" + Counter + "_Type'>";
		HTML += "<option value='text'>Text</option>";
		HTML += "<option value='textarea'>Text Area</option>";
		HTML += "<option value='select'>Select Box</option>";
		HTML += "<option value='radio'>Radio Buttons</option>";
		HTML += "<option value='checkbox'>Checkbox</option>";
		HTML += "<option value='file'>File</option>";
		HTML += "<option value='link'>Link</option>";
		HTML += "<option value='date'>Date</option>";
		HTML += "<option value='datetime'>Date/Time</option>";
		HTML += "</select></td>";
		HTML += "<td><input type='text' name='Custom_Field_" + Counter + "_Values'></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#ERW-FAQ-custom-fields-table tr:last').before(HTML);

		Counter++;
		Max_ID++;
		jQuery(this).data('nextid', Counter); //updates but doesn't show in DOM
		jQuery(this).data('maxid', Max_ID);

		SetCategoryDeleteHandlers();

		event.preventDefault();
	});
});

function SetCustomFieldDeleteHandlers() {
	jQuery('.ERW-FAQ-delete-custom-field').on('click', function(event) {
		var ID = jQuery(this).data('fieldid');
		var tr = jQuery('#ERW-FAQ-custom-field-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('.ERW-FAQ-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.ERW-FAQ-spectrum').css('display', 'inline');

	jQuery('.ERW-FAQ-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = ERW_FAQ_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.ERW-FAQ-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = ERW_FAQ_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function ERW_FAQ_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}