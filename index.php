<?php
/**
 * Plugin Name: ElReyWeb's FAQ
 * Plugin URI: http://elreyweb.com
 * Description: FAQ by ElReyWeb.com
 * Version: 1.9.01
 * Author: ElReyWeb's Team 
 * Author URI: http://elreyweb.com
 * License: 
 */


global $sitepress;
global $ERW_FAQ_message;
global $faqs_Full_Version;

define( 'ERW_FAQ_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ERW_FAQ_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define('WP_DEBUG', true);

register_activation_hook(__FILE__,'Set_ERW_FAQ_Options');
add_filter('upgrader_post_install', 'Set_ERW_FAQ_Options');


add_action('admin_menu' , function() {
    global $submenu;
    remove_menu_page('edit.php?post_type=faqs');
    add_menu_page( 'FAQs', 'FAQs', 'edit_posts', 'ERW-FAQ-Options', function () {        
        if (!isset($_GET['DisplayPage'])) {$_GET['DisplayPage'] = "";}
        include( plugin_dir_path( __FILE__ ) . '/../init/AdminHeader.php');
        if ($_GET['DisplayPage'] == "" or $_GET['DisplayPage'] == "Dashboard") {include( plugin_dir_path( __FILE__ ) . '/../init/DashboardPage.php');}
        if ($_GET['DisplayPage'] == "Options") {include( plugin_dir_path( __FILE__ ) . '/../init/OptionsPage.php');}
        include( plugin_dir_path( __FILE__ ) . '/../init/AdminFooter.php');
    }, null, '49.1' );
    add_submenu_page('ERW-FAQ-Options', 'FAQ Options', 'FAQ Settings', 'edit_posts', 'ERW-FAQ-Options&DisplayPage=Options', 'ERW_FAQ_Output_Pages');
        $submenu['ERW-FAQ-Options'][5] = $submenu['ERW-FAQ-Options'][1];
        $submenu['ERW-FAQ-Options'][1] = array( 'FAQs', 'edit_posts', "edit.php?post_type=faqs", "FAQs" );
        $submenu['ERW-FAQ-Options'][2] = array( 'Add New', 'edit_posts', "post-new.php?post_type=faqs", "Add New" );
        $submenu['ERW-FAQ-Options'][3] = array( 'FAQ Categories', 'manage_categories', "edit-tags.php?taxonomy=faqs-category&post_type=faqs", "FAQ Categories" );
	$submenu['ERW-FAQ-Options'][0][0] = "Dashboard";
	ksort($submenu['ERW-FAQ-Options']);
}, 1);














add_action('init',function() {
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
});

add_action( 'init', function () {
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
} , 0 );

require_once 'faq_sortable.php';

/* Hooks neccessary admin tasks */
if ( is_admin() ){
    add_action('widgets_init', function () {
        global $ERW_FAQ_message;
        global $ews_faqs_import;
        if (isset($_GET['Action'])) {
                switch ($_GET['Action']) {
                    case "ERW_FAQ_UpdateOptions":
                        $ERW_FAQ_message = ERW_FAQ_UpdateOptions();
                        break;
                    default:
                        $ERW_FAQ_message = __("The form has not worked correctly. Please contact the plugin developer.", 'ERW_FAQP');
                        break;
                }
            }
    });

    /* Add any update or error notices to the top of the admin page */
    add_action('admin_notices',function(){
        global $ERW_FAQ_message;
            if (isset($ERW_FAQ_message)) {
                if (isset($ERW_FAQ_message['Message_Type']) and $ERW_FAQ_message['Message_Type'] == "Update") {echo "<div class='updated'><p>" . $ERW_FAQ_message['Message'] . "</p></div>";}
                    if (isset($ERW_FAQ_message['Message_Type']) and $ERW_FAQ_message['Message_Type'] == "Error") {echo "<div class='error'><p>" . $ERW_FAQ_message['Message'] . "</p></div>";}
            }
    });
    add_action('admin_enqueue_scripts', function ($hook) {
        global $post;
        if ((isset($_GET['post_type']) && $_GET['post_type'] == 'faqs') or
            (isset($_GET['page']) && $_GET['page'] == 'ERW-FAQ-Options')) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('scporderjs', plugins_url('', __FILE__) . '/assets/scporder.js', array('jquery'), null, true);
            wp_enqueue_style('scporder', plugins_url('', __FILE__) . '/assets/scporder.css', array(), null);
            wp_enqueue_script('sortable', plugins_url( '../assets/js/sorttable.js', __FILE__ ), array('jquery') );
            wp_enqueue_script('faqs Admin', plugins_url( '../assets/js/Admin.js', __FILE__ ), array('jquery') );
            wp_enqueue_script('spectrum', plugins_url( '../assets/js/spectrum.js', __FILE__ ), array('jquery') );
        }

        if ($hook == 'edit.php' or $hook == 'post-new.php' or $hook == 'post.php') {
            if ($post->post_type == 'product') {
                wp_enqueue_script('ERW-FAQ-wc-admin', plugins_url( '../assets/js/ERW-FAQ-wc-admin.js', __FILE__), array('jquery') );
            }
        }
    }, 10, 1);

    add_action('admin_head', function () {
        wp_enqueue_style( 'ERW-FAQ-admin', plugins_url( '../assets/css/Admin.css', __FILE__ ) );
        wp_enqueue_style('font-awesome', plugins_url( '../assets/css/font-awesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'ERW-FAQ-spectrum', plugins_url( '../assets/css/spectrum.css', __FILE__ ) );
    });

}

add_shortcode( 'faqs', function ( $atts ){
 print_r($atts, true);

    // Attributes
    extract( shortcode_atts(
        array(
            'catname' => '',
        ), $atts )
    );
    $args = array(
        'post_type' => 'faqs',
        'showposts' => '-1',
        'tax_query' => array(
            array(
            'taxonomy' => 'faqs-category',
            'terms' => array($catname),
            'field' => 'slug',
            )
        )
    );
    $loop = new WP_Query( $args );
    $i    = 0;
        //Start the WordPress Loop after querying the posts.
    if ( $loop->have_posts() ):
        $output = '<div class="faqs">';
        $output .= '<div class="row">';
        while ( $loop->have_posts() ): $loop->the_post();
            $lfclass = ($i%2==0) ? 'listFaq col-md-4 col-md-offset-2' : 'listFaq col-md-6';
            $i++;
                $output .= '<div class="listFaq '.$lfclass.'">';
                $output .= '    <div class="panel-body">';
                $output .= '        <div class="panel-title"><span class="redon">'. $i .') </span>'. get_the_title() .'</div>';
                $output .= '        <p><span class="resp">'.__( 'A:', 'faqs' ).' </span>'. do_shortcode(get_the_content()) .'</p>';
                $output .= '    </div>';
                $output .= '</div>';
            if ($i%2==0): $output .= '<div class="clearfix" style="clear: both"></div>'; endif;
        endwhile; wp_reset_postdata();
        $output .= '</div>';
        $output .= '</div>';
    endif;

    return $output;

} );
// Add Shortcode


add_action('admin_notices', function ($Called = "No") {
    global $pagenow;
	$screen = get_current_screen();
	$parent_slug = get_admin_page_parent();

    if ($Called != "Yes" and (!isset($_GET['post_type']) or $_GET['post_type'] != "faqs")) {return;} 
    
	$faqs_dashboard  = ($_GET['DisplayPage'] == "" or $_GET['DisplayPage'] == "Dashboard") ? 'nav-tab-active' : '' ;
	$faqs_list       = (isset($_GET['post_type']) and $_GET['post_type'] == 'faqs' and $pagenow == 'edit.php') ? 'nav-tab-active' : '' ;
	$faqs_add_new    = (isset($_GET['post_type']) and $_GET['post_type'] == 'faqs' and $pagenow == 'post-new.php') ? 'nav-tab-active' : '' ;
	$faqs_categories = (isset($_GET['post_type']) and $_GET['post_type'] == 'faqs' and $_GET['taxonomy'] == "faqs-category") ? 'nav-tab-active' : '' ;
	$faqs_settings   = ($_GET['DisplayPage'] == "Options") ? 'nav-tab-active' : '' ;

?>
    <div class="ERW_FAQ_Menu">
        <h2 class="nav-tab-wrapper">
        <a id="Dashboard_Menu" href='admin.php?page=ERW-FAQ-Options' class="MenuTab nav-tab <?php echo $faqs_dashboard; ?>">Dashboard</a>
        <a id="FAQs_Menu" href='edit.php?post_type=faqs' class="MenuTab nav-tab <?php echo $faqs_list; ?>">FAQs</a>
        <a id="Add_New_Menu" href='post-new.php?post_type=faqs' class="MenuTab nav-tab <?php echo $faqs_add_new; ?>">Add New</a>
        <a id="FAQ_Categories_Menu" href='edit-tags.php?taxonomy=faqs-category&post_type=faqs' class="MenuTab nav-tab <?php echo $faqs_categories; ?>">Categories</a>
        <a id="Options_Menu" href='admin.php?page=ERW-FAQ-Options&DisplayPage=Options' class="MenuTab nav-tab <?php echo $faqs_settings; ?>">Settings</a>
        </h2>
    </div>
<?php } );

function toplevel_page_ERW_current_menu(){
	$screen      = get_current_screen();
	$parent_slug = get_admin_page_parent();

	if ( ($parent_slug == 'edit.php?post_type=faqs' && $screen->id == 'faqs') || ($parent_slug == 'edit.php?post_type=faqs' && $screen->id == 'edit-faqs') || $screen->id == 'edit-faqs-category' ): ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#toplevel_page_ERW-FAQ-Options').addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu opensub');
				$('#toplevel_page_ERW-FAQ-Options > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
			});
		</script>
	<?php endif;

	if ( $_GET['DisplayPage'] == "" or $_GET['DisplayPage'] == "Dashboard"): ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('a[href$="ERW-FAQ-Options"]').parent().addClass('current');
				$('a[href$="ERW-FAQ-Options"]').addClass('current');
			});
		</script>
	<?php endif;
	if ( $_GET['DisplayPage'] == "Options" ): ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('a[href$="ERW-FAQ-Options&DisplayPage=Options"]').parent().addClass('current');
				$('a[href$="ERW-FAQ-Options&DisplayPage=Options"]').addClass('current');
				$('a[href$="ERW-FAQ-Options"]').parent().removeClass('current');
				$('a[href$="ERW-FAQ-Options"]').removeClass('current');
			});
		</script>
	<?php endif;
}
add_action('admin_head', 'toplevel_page_ERW_current_menu', 50);

function ERW_FAQ_Get_Categories($post_id) {
    echo get_the_term_list($post_id, 'faqs-category', '', ', ', '').PHP_EOL;
}

include "init/Meta_Boxes.php";
include "init/Widgets.php";


add_action('activated_plugin','save_faqs_error');
function save_faqs_error(){
        update_option('plugin_error',  ob_get_contents());
        file_put_contents("Error.txt", ob_get_contents());
}