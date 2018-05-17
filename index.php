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


require_once('faq_items.php');
require_once('faq_category.php');

add_shortcode( 'faqs', function ( $atts ){
 print_r($atts, true);

    // Attributes
    extract( shortcode_atts(
        array(
            'catname'       => '',
            'class_one'     => 'col-md-5 col-md-offset-1',
            'class_two'     => 'col-md-6',
            'class_title'   => 'panel-title',
            'class_cnt'     => 'listFaq',
            'show_cont'     => 'yes',
            'show_sn_share' => 'yes',
            'show_R'        => 'yes',
            'full'          => 'yes',
            'style'         => '',

        ), $atts ) );
        $args = array(
            'post_type'           => 'faqs',
            'order'               => 'ASC',
            'orderby'             => 'order',
            'showposts'           => '-1',
        );

        $loop     = new WP_Query( $args );
        $output   = '<div class="faqs row justify-content-center align-content-center">';
        $i        = 1;
        $more     = 0;
        // $count = count($loop->posts);
        $count    = 0;
        $row      = ($full == 'yes') ? 'full' : '';
        $_show_R    = ($show_R !== 'yes' ) ? '<span class="resp">'.__( 'A', 'faqs' ).': </span>'         : '' ;
        $sharing_button ="";
        if ( $loop->have_posts() ) : 
            while ( $loop->have_posts() ) : $loop->the_post();
                $lfclass = ($i%2==0) ? $class_one : $class_two ;
                $delay   = ($i%2==0) ? $i+2 : $i+1 ;

                $output .= '<div class="listFaq '.$lfclass.' '. $row .' wow fadeInUp" data-wow-delay=".'.$delay.'s">';
                $output .= '    <div class="panel-body">';

                if ($show_cont == 'yes' ) {
                    $output .= '<h4 class="'.$class_title.' question">';
                    $output .= '<span class="redon">'. $i .') </span>';
                    $output .= ''.  get_the_title() .'</h4>';
                } else {
                    $output .= '<div class="question-q-box">'.__( 'Q.', 'themeFAQs' ).' </div>';
                    $output .= '<h4 class="'.$class_title.' question">'.  get_the_title() .'</h4>';
                }

                $output .= '        <p class="answer '.$class_cnt.'">'.$_show_R.' '. do_shortcode( get_the_content() ) .'</p>';
                $output .= '    </div>';
                // if ($show_sn_share == 'yes') { $output .= $sharing_button; }
                $output .= '</div>';

                $i++;
                $count+=1;
                if( $count%2==0 ){ $output .= '<div class="clearfix"></div>'; }

            endwhile;
                $output .= '</div>';
        endif;
    return $output;
});
// Register style sheet
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style('faqs', plugins_url('/assets/css/faqs-main.css', __FILE__ ), NULL, NULL);
    wp_enqueue_script('faqsjs', plugins_url('/assets/js/faqs-main.js', __FILE__ ), NULL, NULL, true);
});















/* ==> OLD
--------------------------------------------------------------------------------------------------------------------------------------------- 
    $loop = new WP_Query( $args );
        Start the WordPress Loop after querying the posts.
    if ( $loop->have_posts() ):
        $output = '<div class="faqs">';
        $output .= '<div class="row">';
        while ( $loop->have_posts() ): $loop->the_post();
            $lfclass = ($i%2==0) ? $class_one : $class_two ;
            $delay = ($i%2==0) ? $i+2 : $i+1 ;
            $i++;
                $output .= '<div class="listFaq '.$lfclass.' wow fadeInUp" data-wow-delay=".'.$delay.'s">';
                $output .= '    <div class="panel-body">';
                $output .= '        <div class="panel-title"><span class="redon">'. $i .'</span><h4>'. get_the_title() .'</h4></div>';
                $output .= '        <p><span class="resp">'.__( 'A:', 'faqs' ).' </span>'. do_shortcode(get_the_content()) .'</p>';
                $output .= '    </div>';
                $output .= '</div>';
            if ($i%2==0): $output .= '<div class="clearfix" style="clear: both"></div>'; endif;
        endwhile; wp_reset_postdata();
        $output .= '</div>';
        $output .= '</div>';
    endif;
    $output .= '<div class="col-sm-6  ">';
        $output .= '<div class="listFaq '.$lfclass.' faq-sec pb10">'; 
           $output .= '<h4  class="title '.$class_title.'">  '.$_show_cont.'   '. get_the_title() .'</h4 >';
           $output .= '<p class="text '.$class_cnt.'">'.$_show_R.' '. do_shortcode( get_the_content() ) .'</p>';
        $output .= '</div>';
    $output .= '</div>';
<===*/