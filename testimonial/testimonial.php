<?php

!defined( 'TESTIMONIAL_URL' ) && define( 'TESTIMONIAL_URL', CHILD_URL . '/app/modules/testimonial' );

global $calibrefx;

$calibrefx->hooks->add( 'init', 'testimonial_register_posttype' );
function testimonial_register_posttype() {
    global $calibrefx;

    $args = array(
        'labels' => array(
            'name' => __( 'Testimonials', 'calibrefx' ),
            'singular_name' => __( 'Testimonial', 'calibrefx' ),
            'add_new_item' => __( 'Add New Testimonial', 'calibrefx' ),
            'edit_item' => __( 'Edit Testimonial', 'calibrefx' ),
            'new_item' => __( 'New Testimonial', 'calibrefx' ),
            'edit_item' => __( 'Edit Testimonial', 'calibrefx' ),
            'view_item' => __( 'View Testimonial', 'calibrefx' ),
            'search_items' => __( 'Search Testimonial', 'calibrefx' ),
            'not_found' => __( 'Testimonial Not Found', 'calibrefx' ),
            'not_found_in_trash' => __( 'No testimonial found in Trash', 'calibrefx' ),
        ),
        'description' => __( 'Testimonial', 'calibrefx' ),
        'public' => true,
        'menu_position' => 26,
        'show_ui' => true,
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'testimonial' ),
        'menu_icon' => TESTIMONIAL_URL . '/assets/img/icon-testimonial.png'
    );

    register_post_type( 'testimonial', $args );
    add_post_type_support( 'testimonial', array( 'thumbnail', 'title', 'editor' ) );

    $tax_args = array(
        'labels' => array(
            'name' => __( 'Testimonial Categories', 'calibrefx' ),
            'singular_name' => __( 'Testimonial Category', 'calibrefx' ),
            'add_new_item' => __( 'Add New Testimonial Category', 'calibrefx' ),
            'edit_item' => __( 'Edit Testimonial Category', 'calibrefx' ),
            'update_item' => __( 'Update Testimonial Category', 'calibrefx' ),
            'new_item' => __( 'New Testimonial Category', 'calibrefx' ),
            'edit_item' => __( 'Edit Testimonial Category', 'calibrefx' ),
            'view_item' => __( 'View Testimonial Category', 'calibrefx' ),
            'search_items' => __( 'Search Testimonial Category', 'calibrefx' ),
            'menu_name' => __( 'Testimonial Categories', 'calibrefx' ),
        ),
        'public' => true,
        'show_ui' => true,
        'rewrite' => array( 'slug' => 'testimonial-category', 'hierarchical' => true ),
        'hierarchical' => true
    );

    register_taxonomy( 'testimonial-category', 'testimonial', $tax_args );

    $calibrefx->hooks->add( 'admin_menu', 'testimonial_metabox', 5 );
    $calibrefx->hooks->add( 'save_post', 'testimonial_metabox_save', 1, 2 );
}

function testimonial_metabox() {
    add_meta_box( 'testimonial_detail_metabox', __( 'Testimonial Details', 'calibrefx' ), 'testimonial_detail_metabox', 'testimonial', 'normal', 'high' );
}

function testimonial_detail_metabox(){
    wp_nonce_field( 'testimonial_detail_action', 'testimonial_detail_nonce' );
?>

<p><label for="testimonial_job_title" style="display:block"><b><?php _e( 'Testimonee Job Title', 'calibrefx' ); ?></b></label></p>
<p><input class="large-text" type="text" name="testimonial_job_title" id="testimonial_job_title" value="<?php echo esc_attr( calibrefx_get_custom_field( 'testimonial_job_title' ) ); ?>" /></p>
<p class="description"><?php _e( 'Enter job title of your testimonee.', 'calibrefx' ); ?></p>

<p><label for="testimonial_location" style="display:block"><b><?php _e( 'Testimonee Location', 'calibrefx' ); ?></b></label></p>
<p><input class="large-text" type="text" name="testimonial_location" id="testimonial_location" value="<?php echo esc_attr( calibrefx_get_custom_field('testimonial_location' ) ); ?>" /></p>
<p class="description"><?php _e( 'Enter location of your testimonee.', 'calibrefx' ); ?></p>

<?php
}

function testimonial_metabox_save( $post_id, $post ){
    global $calibrefx;

    if( !$calibrefx->security->verify_nonce( 'testimonial_detail_action', 'testimonial_detail_nonce' ) ){    
        return $post_id; 
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        return;
    if ( defined( 'DOING_CRON' ) && DOING_CRON )
        return;

    if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ) )
        return $post_id;    
        
    $testimonial_job_title = sanitize_text_field( $_POST['testimonial_job_title'] );
    
    if ( $testimonial_job_title )
        update_post_meta( $post_id, 'testimonial_job_title', $testimonial_job_title );
    else
        delete_post_meta( $post_id, 'testimonial_job_title' );

    $testimonial_location = sanitize_text_field( $_POST['testimonial_location'] );
    
    if ( $testimonial_location )
        update_post_meta( $post_id, 'testimonial_location', $testimonial_location );
    else
        delete_post_meta( $post_id, 'testimonial_location' );
}

add_shortcode( 'list_testimonial', 'list_testimonial' );
function list_testimonial($atts, $content = null){
    extract( shortcode_atts( array(
        'id' => '',
        'count' => 5
    ), $atts ) );

    $args = array(
        'post_type'=> 'testimonial',
        'posts_per_page' => $count
    );              

    $after = '';
    $before = '';

    $output = "";
    $before .= '<div class="testimonial-wrapper"><div class="quote-left"></div>';
    $the_query = new WP_Query( $args );
    if( $the_query->have_posts() ) : 
        while ( $the_query->have_posts() ) : $the_query->the_post(); 
            $job_title = calibrefx_get_custom_field( 'testimonial_job_title' );
            $location = calibrefx_get_custom_field( 'testimonial_location' );
            $attr = '';

            if( !empty( $job_title ) ) $attr .= ', <span class="job-title">' . $job_title . '</span>';
            if( !empty( $location ) ) $attr .= ' <span class="testimonial-location">(' . $location . ')</span>';

            $output .= '<div class="testimonial-content-wrapper">';
            $output .= '<div class="testimonial-content">' . get_the_content() . '</div>';
            $output .= '<div class="testimonial-name">' . get_the_title() . $attr . '</div>';
            $output .= '</div>';
        endwhile;
    endif;
    $after .= '<div class="quote-right"></div></div>';
    wp_reset_query();
    wp_reset_postdata();
    return $before . do_shortcode( '[slider auto_height="container" speed="500" interval="5000" fx="fadeout" next_prev="1"]' . $output . '[/slider]' ) . $after;
}

$calibrefx->hooks->add( 'calibrefx_meta', 'testimonial_load_scripts' );
function testimonial_load_scripts(){
    wp_enqueue_style( 'testimonial-style', TESTIMONIAL_URL . '/assets/css/testimonial.css' );
}