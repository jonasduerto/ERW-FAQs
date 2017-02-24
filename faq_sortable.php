<?php 
add_action('admin_init', function() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT ID 
        FROM $wpdb->posts 
        WHERE post_type = 'faqs' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') 
        ORDER BY menu_order ASC
    ");
    foreach ($results as $key => $result) {
        $wpdb->update($wpdb->posts, array('menu_order' => $key + 1), array('ID' => $result->ID));
    }
});
add_action('wp_ajax_update-menu-order', function () {
    global $wpdb;

    parse_str($_POST['order'], $data);

    if (!is_array($data))
        return false;

    $id_arr = array();
    foreach ($data as $key => $values) {
        foreach ($values as $position => $id) {
            $id_arr[] = $id;
        }
    }

    $menu_order_arr = array();
    foreach ($id_arr as $key => $id) {
        $results = $wpdb->get_results("SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval($id));
        foreach ($results as $result) {
            $menu_order_arr[] = $result->menu_order;
        }
    }

    sort($menu_order_arr);

    foreach ($data as $key => $values) {
        foreach ($values as $position => $id) {
            $wpdb->update($wpdb->posts, array('menu_order' => $menu_order_arr[$position]), array('ID' => intval($id)));
        }
    }
});

add_action('manage_edit-faqs_columns', function($faqs_columns) {
  $faqs_columns['menu_order'] = "Order";
  return $faqs_columns;
});

add_action('manage_faqs_posts_custom_column',function ($name){
  global $post;

  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
});

add_filter('manage_edit-faqs_sortable_columns',function($columns){
  $columns['menu_order'] = 'menu_order';
  return $columns;
});