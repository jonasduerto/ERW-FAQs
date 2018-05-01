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
    // Attributes
    extract( 
        shortcode_atts( array(
            'catname'      => '',
            'class_title'  => 'panel-title',
            'class_cnt'    => 'listFaq',
            'show_cont'    => 'yes',
            'show_sn_share'=> 'yes',
            'show_R'       => 'yes',
            'style'        => '',
            'sec_title'    =>  'Frequent questions',
            'sec_subtitle' => 'Thank you again for visiting our website and if you have any questions please feel free to contact us at anytime.'), 
        $atts )
    );

    faqs_section(
        'Frequent questions',
        'Thank you again for visiting our website and if you have any questions please feel free to contact us at anytime.',
        '',
        $_show_cont,
        $_show_R
    );
});


// Register style sheet
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style('faqs', plugins_url('assets/css/faqs-main.css'), NULL, NULL);
    wp_enqueue_script('faqsjs', plugins_url('assets/js/faqs-main.js'), array('jquery'), NULL, true);
});


function faqs_section(
    $sec_title='',
    $sec_subtitle='',
    $style='',
    $_show_cont='',
    $_show_R='') { ?>

<section class="section page bg-grey wow fadeIn" id="faqs">
    <div class="container pt-5 pb-5">
        <div class="row text-center clearfix justify-content-center">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="heading pb-3">
                    <h1 class="fw900 text-white"><?php echo $sec_title; ?></h1>
                    <p class="text-muted"><?php echo $sec_subtitle; ?></p>
                </div>
            </div>
        </div>
        <div class="faqs row justify-content-center align-content-center">
            <?php faqs_section_loop(); ?>
        </div>
    </div>
</section>

<?php $content .= ob_get_clean();
    return $content;
}

function faqs_section_loop() {
    global $post;
    $loop   = new WP_Query( array('post_type'=>'faqs','order'=>'ASC','orderby'=>'order','showposts'=>'-1') );
    $i      = 1;
    $count  = 0;
    ob_start();

    if ( $loop->have_posts() ) : 
        while ( $loop->have_posts() ) : $loop->the_post();
            $_show_cont = ($show_cont == 'yes' ) ? '<span class="redon">'. $i .') </span>' : '' ;
            $_show_R    = ($show_R    == 'yes' ) ? '<span class="resp">R: </span>'         : '' ; ?>

            <div class="col-sm-6">
                <div class="listFaq <?php echo $style; ?> faq-sec pb10">
                    <h4 class="title"> 
                        <?php echo $_show_cont; ?>
                        <?php echo get_the_title(); ?>
                    </h4 >
                    <p class="text">
                        <?php echo $_show_R; ?>
                        <?php echo $sec_subtitle; ?>
                        <?php echo do_shortcode( get_the_content() ); ?>
                    </p>
                </div>
            </div>

            <?php

       endwhile;
    endif;
    $content .= ob_get_clean();
    return $content;
}