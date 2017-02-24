<?php 
global $wpdb; 
global $pagenow;
?>
<div class="ewd-dashboard-middle">
	<div id="col-full">
		<h3 class="ERW-FAQ-dashboard-h3">FAQ Summary</h3>
		<table class='ERW-FAQ-overview-table wp-list-table widefat fixed striped posts'>
			<thead>
				<tr>
					<th><?php _e("Title", 'EWD_ABCO'); ?></th>
					<th><?php _e("Views", 'EWD_ABCO'); ?></th>
					<th><?php _e("Categories", 'EWD_ABCO'); ?></th>					
				</tr>
			</thead>
			<tbody>
				<?php
					$args = array(
						'post_type' => 'faqs',
						'orderby'   => 'meta_value_num',
						// 'meta_key'  => 'faqs_view_count'
					);

					$Dashboard_FAQs_Query = new WP_Query($args);
					$Dashboard_FAQs = $Dashboard_FAQs_Query->get_posts();
					
					if (sizeOf($Dashboard_FAQs) == 0) {
						echo "<tr><td colspan='3'>" . __("No FAQs to display yet. Create an FAQ and then view it for it to be displayed here.", 'ERW_FAQ') . "</td></tr>";
					} else {
						foreach ($Dashboard_FAQs as $Dashboard_FAQ) { ?>
							<tr>
								<td><a href='post.php?post=<?php echo $Dashboard_FAQ->ID;?>&action=edit'><?php echo $Dashboard_FAQ->post_title; ?></a></td>
								<td><?php echo get_post_meta($Dashboard_FAQ->ID, 'faqs_view_count', true); ?></td>
								<td><?php echo ERW_FAQ_Get_Categories($Dashboard_FAQ->ID); ?></td>
							</tr>
						<?php }
					}
				?>
			</tbody>
		</table>
		<br class="clear" />
	</div>
</div>

<div id="ewd-dashboard-top" class="metabox-holder">
	<div id="ewd-dashboard-box-orders" class="ERW-FAQ-dashboard-box" >
	  	<div class="ewd-dashboard-box-icon"><i class="fa fa-question-circle-o" aria-hidden="true"></i></div>
		<div class="ewd-dashboard-box-value-and-field-container">
		  <div class="ewd-dashboard-box-value"><span class="displaying-num"><?php echo $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='faqs' AND post_status='publish'"); ?></span>
		  </div>
		  <div class="ewd-dashboard-box-field">FAQs</div>
		</div>
	</div>
	<div id="ewd-dashboard-box-views" class="ERW-FAQ-dashboard-box" >
	  	<div class="ewd-dashboard-box-icon"><i class="fa fa-eye" aria-hidden="true"></i></div>
		<div class="ewd-dashboard-box-value-and-field-container">
		  <div class="ewd-dashboard-box-value"><?php echo $wpdb->get_var("SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key='faqs_view_count' and post_id!='0'"); ?>
		  </div>
		  <div class="ewd-dashboard-box-field">Views</div>
		</div>
	</div>
	<div id="ewd-dashboard-box-links" class="ERW-FAQ-dashboard-box" >
	  	<div class="ewd-dashboard-box-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></div>
		<div class="ewd-dashboard-box-value-and-field-container">
		  <div class="ewd-dashboard-box-value"><?php echo $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='faqs_view_count' and post_id!='0' ORDER BY cast(meta_value as unsigned) DESC"); ?>
		  </div>
		  <div class="ewd-dashboard-box-field">Most FAQ Views</div>
		</div>
	</div>
</div>
<?php
function strposX($haystack, $needle, $number){
    if($number == '1'){
        return strpos($haystack, $needle);
    }elseif($number > '1'){
        return strpos($haystack, $needle, strposX($haystack, $needle, $number - 1) + strlen($needle));
    }else{
        return error_log('Error: Value for parameter $number is out of range');
    }
}

?>
