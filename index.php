<?php
/**
 * Plugin Name: ElReyWeb's FAQ
 * Plugin URI: http://elreyweb.com
 * Description: FAQ by ElReyWeb.com
 * Version: 1.9.2
 * Author: ElReyWeb's Team 
 * Author URI: http://elreyweb.com
 * License: 
 */


require_once('faq_items.php');
require_once('faq_category.php');

add_shortcode( 'faqs', function ( $atts ){
    extract( shortcode_atts( array(
        'catname'       => '',
        'show_cont'     => 'yes',
        'show_sn_share' => 'yes',
        'show_R'        => 'yes',
        'style'         => '',
        'col'           => false,
        'show_num'      => false,
        'bs'            => 3,
    ), $atts ) );
    global $post;
    $loop  = new WP_Query( array(
        'post_type' => 'faqs',
        'order'     => 'ASC',
        'orderby'   => 'order',
        'showposts' => '-1'
    ));
    $i        = 0;
    $row      = $col ? 'col-1' : 'col-2';
    $count    = count($loop->posts);
    $dct      = $count/2 ;
    $output   = '<div class="faqs">';
        if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post(); $i++;
            if ($count = $dct) {
                $output .= '<div class="col-md-5 col-md-offset-1">';
            } else {
                $output .= '<div class="col-md-6">';
            }
            if ($show_num) {
                $output .= '<div class="listFaq '.$row.'">';
                $output .= '    <div class="panel-body">';
                $output .= '        <span class="redon">'. $i .'</span><h4 class="panel-title question">'. get_the_title() .'</h4>';
                $output .= '        <p class="answer"><span class="resp">'.__( 'A:', 'themeFAQs' ).' </span>'. do_shortcode(get_the_content()) .'</p>';
                $output .= '    </div>';
                $output .= '</div>';
            } else {
                if ($i == $dct+1) {
                    $output .= '  </div>';
                    $output .= '<div class="col-md-6">';
                }
                $output .= '  <div class="animated fadeInLeft wow" data-wow-delay=".'. $i .'s">';
                $output .= '    <div class="question-q-box">'.__( 'Q.', 'themeFAQs' ).'</div>';
                $output .= '    <h4 class="question">'. get_the_title() .'</h4>';
                $output .= '    <p class="answer">' . do_shortcode(get_the_content()) . '</p>';
                $output .= '  </div>';
            }
            $output .= '</div>';

        endwhile;
    //    wp_reset_postdata();
        endif;
    $output .= '</div>';

    return $output;
});

// Register style sheet
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style('faqs', plugins_url('/assets/css/faqs-main.css', __FILE__ ), NULL, NULL);
    wp_enqueue_script('faqsjs', plugins_url('/assets/js/faqs-main.js', __FILE__ ), NULL, NULL, true);
});