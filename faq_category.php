<?php 


// Custom Taxonomy

function add_console_taxonomies() {

    $labels = array(
        'name'              => __('FAQ Categories', 'ERW_FAQ'),
        'singular_name'     => __('FAQ Category', 'ERW_FAQ'),
        'search_items'      => __('Search FAQ Categories', 'ERW_FAQ'),
        'all_items'         => __('All FAQ Categories', 'ERW_FAQ'),
        'parent_item'       => __('Parent FAQ Category', 'ERW_FAQ'),
        'parent_item_colon' => __('Parent FAQ Category:', 'ERW_FAQ'),
        'edit_item'         => __('Edit FAQ Category', 'ERW_FAQ'),
        'update_item'       => __('Update FAQ Category', 'ERW_FAQ'),
        'add_new_item'      => __('Add New FAQ Category', 'ERW_FAQ'),
        'new_item_name'     => __('New FAQ Category Name', 'ERW_FAQ'),
        'menu_name'         => __('FAQ Categories', 'ERW_FAQ'),
    );
    $args = array(
        'rewrite' => array(
	        'slug' => 'faqs-category', // This controls the base slug that will display before each term
		),
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'query_var' => true

    );
    register_taxonomy('faqs-category', array('faqs'), $args);
}
add_action( 'init', 'add_console_taxonomies', 0 );
