<?php 

// Review Post type

add_action('init', 'faqs');

function faqs() {
    $labels = array(
        'name'               => __('FAQs', 'ERW_FAQ'),
        'singular_name'      => __('FAQ', 'ERW_FAQ'),
        'menu_name'          => __('FAQs', 'ERW_FAQ'),
        'name_admin_bar'     => __('FAQs', 'ERW_FAQ'),
        'parent_item_colon'  => __('Parent Item:', 'ERW_FAQ'),
        'all_items'          => __('All FAQs', 'ERW_FAQ'),
        'add_new_item'       => __('Add New FAQ', 'ERW_FAQ'),
        'add_new'            => __('Add New', 'ERW_FAQ'),
        'new_item'           => __('New FAQ', 'ERW_FAQ'),
        'edit_item'          => __('Edit FAQ', 'ERW_FAQ'),
        'update_item'        => __('Update FAQ', 'ERW_FAQ'),
        'view_item'          => __('View FAQ', 'ERW_FAQ'),
        'search_items'       => __('Search FAQs', 'ERW_FAQ'),
        'not_found'          => __('Not found', 'ERW_FAQ'),
        'not_found_in_trash' => __('Not found in Trash', 'ERW_FAQ'),
    );
    $args = array(
        'label'               => __('FAQs', 'faqs'),
        'description'         => __('El Rey Web FAQs', 'faqs'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor'),
        'taxonomies'          => array('faqs-category'),
        'hierarchical'        => true,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-format-status',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'query_var'           => 'faqs',
        'capability_type'     => 'post',
	);

    register_post_type( 'faqs' , $args );
}