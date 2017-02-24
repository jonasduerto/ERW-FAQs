jQuery(document).ready(function() {
    jQuery('.ERW-FAQ-add-faq-button').on('click', function(event) {
        var Post_ID = jQuery('#ERW-FAQ-post-id').val();

        var FAQs = [];
        jQuery('.ERW-FAQ-add-faq').each(function() {
            if (jQuery(this).is(':checked')) {FAQs.push(jQuery(this).val());}
            jQuery(this).prop('checked', false);
        });

        var data = 'FAQs=' + JSON.stringify(FAQs) + '&Post_ID=' + Post_ID + '&action=ERW_FAQ_add_wc_faqs';
        jQuery.post(ajaxurl, data, function(response) {
        	var Add_FAQs = jQuery.parseJSON(response);
        	jQuery(Add_FAQs).each(function(index, el) {
        		var HTML = "<tr class='ERW-FAQ-faq-row ERW-FAQ-delete-faq-row' data-faqid='" + el.ID + "'>";
				HTML += "<td><input type='checkbox' class='ERW-FAQ-delete-faq' name='Delete_FAQs[]' value='" + el.ID + "'/></td>";
				HTML += "<td>" + el.Name + "</td>";
				HTML += "</tr>";
                jQuery('.ERW-FAQ-delete-table tr:last').after(HTML);
        	});
        });

        event.preventDefault();
    })
});

jQuery(document).ready(function() {
    jQuery('.ERW-FAQ-delete-faq-button').on('click', function(event) {
        var Post_ID = jQuery('#ERW-FAQ-post-id').val();

        var FAQs = [];
        jQuery('.ERW-FAQ-delete-faq').each(function() {
            if (jQuery(this).is(':checked')) {FAQs.push(jQuery(this).val());}
            jQuery(this).prop('checked', false);
        });

        var data = 'FAQs=' + JSON.stringify(FAQs) + '&Post_ID=' + Post_ID + '&action=ERW_FAQ_delete_wc_faqs';
        jQuery.post(ajaxurl, data, function(response) {});

        jQuery(FAQs).each(function(index, el) {
        	jQuery(".ERW-FAQ-delete-faq-row[data-faqid='" + el + "']").fadeOut('500', function() {jQuery(this).remove();});
        });

        event.preventDefault();
    })
});

jQuery(document).ready(function() {
    jQuery('.ERW-FAQ-category-filter').on('change', function() {
        var Cat_ID = jQuery(this).val();

        var data = 'Cat_ID=' + Cat_ID + '&action=ERW_FAQ_wc_faq_category';
        jQuery.post(ajaxurl, data, function(response) {
            jQuery('.ERW-FAQ-faq-add-table').remove();
            jQuery('.ERW-FAQ-category-filter').after(response);
        });
    })
});