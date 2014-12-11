<?php
!defined( 'GALLERY_URL' ) && define( 'GALLERY_URL', CHILD_URL . '/app/modules/gallery' );

global $calibrefx;

if( $calibrefx::is_module_active( 'gallery' ) ){
    add_filter( 'query_vars', 'gallery_custom_page_variables' );
    add_action( 'init', 'gallery_register_posttype' );
    add_action( 'init', 'gallery_rewrite_rules' );
    
    $calibrefx->hooks->calibrefx_meta = array(
        array( 'function' => 'gallery_load_scripts', 'priority' => 10 )
    );
}

function gallery_custom_page_variables( $public_query_vars ) {
    $public_query_vars[] = 'gallery_category';

    return $public_query_vars;
}

function gallery_rewrite_rules(){
    add_rewrite_rule( '([^/]+)/gallery-category/([^/]+)/?$', 'index.php?pagename=$matches[1]&gallery_category=$matches[2]', 'top' );
}

function gallery_register_posttype() {
    $args = array(
        'labels' => array(
            'name' => __( 'Galleries', 'calibrefx' ),
            'singular_name' => __( 'Gallery', 'calibrefx' ),
            'add_new_item' => __( 'Add New Gallery Item', 'calibrefx' ),
            'edit_item' => __( 'Edit Gallery Item', 'calibrefx' ),
            'new_item' => __( 'New Gallery Item', 'calibrefx' ),
            'edit_item' => __( 'Edit Gallery Item', 'calibrefx' ),
            'view_item' => __( 'View Gallery Item', 'calibrefx' ),
            'search_items' => __( 'Search Gallery', 'calibrefx' ),
            'not_found' => __( 'Gallery Item Not Found', 'calibrefx' ),
            'not_found_in_trash' => __( 'No gallery item found in Trash', 'calibrefx' ),
        ),
        'description' => __( 'Gallery', 'calibrefx' ),
        'public' => true,
        'menu_position' => 26,
        'show_ui' => true,
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'galleries' ),
        'menu_icon' => GALLERY_URL . '/assets/img/icon-gallery.png'
    );

    register_post_type( 'galleries', $args);
    add_post_type_support( 'galleries', array( 'thumbnail', 'title', 'editor' ));

    $tax_args = array(
        'labels' => array(
            'name' => __( 'Gallery Categories', 'calibrefx' ),
            'singular_name' => __( 'Gallery Category', 'calibrefx' ),
            'add_new_item' => __( 'Add New Gallery Category', 'calibrefx' ),
            'edit_item' => __( 'Edit Gallery Category', 'calibrefx' ),
            'update_item' => __( 'Update Gallery Category', 'calibrefx' ),
            'new_item' => __( 'New Gallery Category', 'calibrefx' ),
            'edit_item' => __( 'Edit Gallery Category', 'calibrefx' ),
            'view_item' => __( 'View Gallery Category', 'calibrefx' ),
            'search_items' => __( 'Search Gallery Category', 'calibrefx' ),
            'menu_name' => __( 'Gallery Categories', 'calibrefx' ),
        ),
        'public' => true,
        'show_ui' => true,
        'rewrite' => array( 'slug' => 'gallery-category', 'hierarchical' => true),
        'hierarchical' => true
    );

    register_taxonomy( 'gallery-category', 'galleries', $tax_args);

    add_image_size( 'gallery-thumbnail', apply_filters( 'gallery_thumb_width', '480' ), apply_filters( 'gallery_thumb_height', '265' ), true);
}

function gallery_load_scripts(){
    global $wp_scripts, $wp_styles; 

    foreach( $wp_scripts->registered as $script ){
        if( stripos( $script->src, 'fancybox' ) === FALSE ) wp_enqueue_script( 'jquery.fancybox', GALLERY_URL . '/assets/js/jquery.fancybox.js', array( 'jquery' ) );
        if( stripos( $script->src, 'lazyload' ) === FALSE ) wp_enqueue_script( 'jquery.lazyload', GALLERY_URL . '/assets/js/jquery.lazyload.min.js', array( 'jquery' ) );
    }

    wp_enqueue_script( 'jquery.isotope', GALLERY_URL . '/assets/js/jquery.isotope.js', array( 'jquery' ) );
    wp_enqueue_script( 'cfx-sort-gallery-script', GALLERY_URL . '/assets/js/sortgallery.js', array( 'jquery' ) );

    foreach( $wp_styles->registered as $style ){
        if( stripos( $style->src, 'fancybox' ) === FALSE ) wp_enqueue_style( 'jquery.fancybox', GALLERY_URL . '/assets/css/jquery.fancybox.css' );
    }
    wp_enqueue_style( 'cfx-sort-gallery-style', GALLERY_URL . '/assets/css/sortgallery.css' );
}

add_shortcode( 'galleries', 'gallery_do_shortcode' );
function gallery_do_shortcode( $atts, $content = null ) {
    global $wp_query, $post;

    $temp = $wp_query;
    $page = $post;

    extract( shortcode_atts( array(
        'type' => 'isotope', 
        'items_per_page' => 9,
        'items_per_row' => 3,
        'cat' => get_query_var( 'gallery_category' ),
        'order' => 'desc',
        'orderby' => 'ID' 
    ), $atts ) );

    $output = '';

    if( $type == 'isotope' ){ 
        $query = new WP_Query( array(
        	'post_type' => 'galleries',
        	'posts_per_page' => '-1',
        	'order' => $order,
        	'orderby' => $orderby
        ) );

        if( $query->have_posts() ) :
    	
    		$output .= '<ul id="cat-filter" class="item-filter"><li><a data-categories="*" class="active">All</a></li>';

        	$taxonomies = get_terms( 'gallery-category' );

    		foreach( $taxonomies as $cat ){
    			$output .= '<li><a data-categories="' . $cat->slug . '">' . $cat->name . '</a></li>';
    		}

    		$output .= '</ul>';

        	$output .= '<div class="sortgallery-wrapper">';
    	
    		$i = 1;
    	    while( $query->have_posts() ) : $query->the_post();

    	    	$title = get_the_title_limit( 40 );
    	    	$desc = get_the_content();

    	    	$img_src = calibrefx_get_image( array( 'format' => 'url', 'size' => 'gallery-thumbnail' ) );
    			$img_full_src = calibrefx_get_image( array( 'format' => 'url', 'size' => 'full' ) );
    			
    			$img_html = '<img src="' . $img_src . '" data-original="' . $img_src . '" class="gallery-image" />';

    	    	$terms = wp_get_post_terms( get_the_ID(), 'gallery-category' );

    			$gallerycats = '';

    			foreach ( $terms as $term ) {
    				$gallerycats .= ' ' . $term->slug;
    			}

    	    	$html = '
                	<div class="box-item sortgallery-col sortgallery-item' . $gallerycats . '">
                		<div class="sortgallery-thumb box-image">
                			<a href="' . $img_full_src . '" class="sortgallery-link box-image-link" rel="gallery" title="' . $title . '">' . $img_html . '</a>
                		</div>
                		<div class="box-desc">
                			<a href="' . $img_full_src . '" class="sortgallery-link">' . $title . '</a>
                		</div>
                	</div><!-- end .sortgallery-item -->';

    			$output .= apply_filters( 'gallery_item_html_output', $html, $gallerycats, $img_full_src, $img_html, $title, $desc );
    			
    			$i++;

    	    endwhile;

    	    $output .= '</div><!-- end .sortgallery-wrapper -->';
        
        endif;
    }elseif( $type == 'normal' ){
        if( empty( $cat ) ){
            $query = new WP_Query( array(
                'post_type' => 'galleries',
                'posts_per_page' => $items_per_page,
                'order' => 'desc',
                'orderby' => 'ID',
                'paged' => get_query_var( 'paged' )
            ) );
        }else{
            $query = new WP_Query( array(
                'post_type' => 'galleries',
                'posts_per_page' => $items_per_page,
                'order' => 'desc',
                'orderby' => 'ID',
                'paged' => get_query_var( 'paged' ),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'gallery-category',
                        'field' => 'slug',
                        'terms' => $cat
                    )
                )
            ) );
        }

        $wp_query = $query;

        if( $query->have_posts() ) :
    
            if(!empty( $cat )){
                $output .= '<ul id="cat-filter" class="item-filter not-to-filter"><li><a href="' . get_permalink( $page->ID ) . '">All</a></li>';
            }else{
                $output .= '<ul id="cat-filter" class="item-filter not-to-filter"><li><a href="' . get_permalink( $page->ID ) . '" class="active">All</a></li>';
            }
            

            $terms = get_terms( 'gallery-category' );

            foreach( $terms as $term ){
                if( $term->slug == $cat ){
                    $output .= '<li><a href="' . get_permalink($page->ID) . 'gallery-category/' . $term->slug . '/" class="active">' . $term->name . '</a></li>';
                }else{
                    $output .= '<li><a href="' . get_permalink($page->ID).'gallery-category/' . $term->slug . '/">' . $term->name . '</a></li>';    
                }
                
            }

            $output .= '</ul>';

            $output .= '<div class="sortgallery-wrapper not-to-isotope">';
    
            $i = 1;
            while( $query->have_posts() ) : $query->the_post();

                $title = get_the_title_limit( 40 );
                $desc = get_the_content();

                $img_src = calibrefx_get_image( array( 'format' => 'url', 'size' => 'gallery-thumbnail' ) );
                $img_full_src = calibrefx_get_image( array( 'format' => 'url', 'size' => 'full' ) );
            
                $img_html = '<img src="' . $img_src . '" data-original="' . $img_src . '" class="gallery-image" />';

                $terms = wp_get_post_terms( get_the_ID(), 'gallery-category' );

                $gallerycats = '';

                foreach ( $terms as $term ) {
                    $gallerycats .= ' ' . $term->slug;
                }

                if( $i % 3 == 1 ) $output .= '<div class="sortgallery-row ' . calibrefx_row_class() . '">';

                $html = '
                    <div class="box-item col-xs-12 col-sm-4 col-md-4 col-lg-4 sortgallery-item' . $gallerycats . '">
                        <div class="sortgallery-thumb box-image">
                            <a href="' . $img_full_src . '" class="sortgallery-link box-image-link" rel="gallery" title="' . $title . '">' . $img_html . '</a>
                        </div>
                        <div class="box-desc">
                            <a href="' . $img_full_src . '" class="sortgallery-link">' . $title . '</a>
                        </div>
                    </div><!-- end .sortgallery-item -->';

                $output .= apply_filters( 'gallery_item_html_output', $html, $gallerycats, $img_full_src, $img_html, $title, $desc );
            
                if( $i % 3 == 0 || $i == $query->post_count ) $output .= '</div>';

                $i++;

            endwhile;

            $output .= '</div><!-- end .sortgallery-wrapper -->';

            ob_start();
            calibrefx_numeric_posts_nav();
            $nav = ob_get_contents();
            ob_end_clean();

            $output .= '<div class="sortgallery-nav-wrapper">' . $nav . '</div>';

        endif;
    }

    wp_reset_query();
    wp_reset_postdata();

    $wp_query = $temp;

    return do_shortcode( $output );
}