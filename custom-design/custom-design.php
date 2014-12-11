<?php
global $calibrefx;

if( $calibrefx::is_module_active( 'custom-design' ) ){
    remove_theme_support( 'calibrefx-custom-header' );
    remove_theme_support( 'calibrefx-custom-background' );

    add_action( 'init', 'custom_design_setup', 0 );
}

/********************
 * FUNCTIONS BELOW  *
 ********************/
if( is_admin() && $calibrefx::is_module_active( 'custom-design' ) ){
    add_action( 'calibrefx_theme_settings_meta_box', 'custom_design_meta_boxes' );
}

// Check if custom logo if available
function custom_design_setup(){
    add_filter('calibrefx_favicon_url', 'custom_design_favicon');

    add_action( 'wp_enqueue_scripts', 'custom_design_style' );
    add_action( 'wp_enqueue_scripts', 'custom_design_logo' );
    add_action( 'calibrefx_before_save_core', 'custom_design_save_core' );
}

function custom_design_save_core(){ 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    if(!isset($_POST['calibrefx-settings'])) return;

    $custom_logo = $_POST['calibrefx-settings']['logo']; 
    $custom_logo_id = $_POST['calibrefx-settings']['logo_id'];

    if($custom_logo && $custom_logo_id){
        global $post;

        $temp = $post;

        $post = get_post($custom_logo_id);

        if($post !== NULL){
            $logo_url = calibrefx_get_image(array('format' => 'url', 'id' => $custom_logo_id));
        }else{
            $logo_url = $custom_logo;
        }

        $image = @getimagesize($logo_url);

        if($image){ 
            $width = absint($image[0]);
            $height = absint($image[1]);  

            $_POST['calibrefx-settings']['header_image_width'] = $width;
            $_POST['calibrefx-settings']['header_image_height'] = $height;
        }

        $post = $temp;
    } 
}

function custom_design_logo(){ 
    global $post;

    $temp = $post;

    $logo = esc_attr( calibrefx_get_option( 'logo' ) );
    $logo_id = esc_attr( calibrefx_get_option( 'logo_id' ) );
    $display_text = esc_attr( calibrefx_get_option( 'header_display_text' ) );
    $text_color = esc_attr( calibrefx_get_option( 'header_text_color' ) );
    $width = esc_attr( calibrefx_get_option( 'header_image_width' ) );
    $height = esc_attr( calibrefx_get_option( 'header_image_height' ) );

    $post = get_post( $logo_id );

    if( $post !== NULL ){
        $logo_url = calibrefx_get_image( array( 'format' => 'url', 'id' => $logo_id ) );
    }else{
        $logo_url = $logo;
    }

    if( $logo_url AND !$display_text ){
        $custom_css = "#header-title { 
    background: url($logo_url) no-repeat left center; 
    width: " . $width . "px; 
    height: " . $height . "px;
}
#title, #title a, #title a:hover{ 
    display: block; 
    margin: 0; 
    overflow: hidden; 
    padding: 0;
    text-indent: -9999px; 
    width: " . $width . "px; 
    height: " . $height . "px;
}
p#description{
    display: block; 
    margin: 0; 
    overflow: hidden; 
    padding: 0;
    text-indent: -9999px; 
}";
    }else{
        $custom_css = "#title, #title a{ 
    color: $text_color
}";
    }

    $post = $temp;

    wp_add_inline_style( 'calibrefx-child-style', $custom_css );
}

function custom_design_favicon(){
	global $post;

	$temp = $post;

	$favicon = esc_attr( calibrefx_get_option( 'favicon' ) );
    $favicon_id = esc_attr( calibrefx_get_option( 'favicon_id' ) );

    $post = get_post( $favicon_id );

    if( $post !== NULL ){
    	$favicon_url = calibrefx_get_image( array( 'format' => 'url', 'id' => $favicon_id ) );
    }elseif( !empty( $favicon ) ){
    	$favicon_url = $favicon;
    }else{
    	return;
    }

    $post = $temp;

    return $favicon_url;
}

function custom_design_meta_boxes(){
    global $calibrefx;

    calibrefx_add_meta_box( 'design', 'basic', 'custom-logo', __('Logo & Favicon Settings', 'calibrefx'), 'custom_design_logo_settings', $calibrefx->theme_settings->pagehook, 'main', 'low' );
    calibrefx_add_meta_box( 'design', 'basic', 'custom-background', __('Background Settings', 'calibrefx'), 'custom_design_background_settings', $calibrefx->theme_settings->pagehook, 'main', 'low' );
}

function custom_design_logo_settings(){
    global $calibrefx;

    calibrefx_add_meta_group( 'logo-settings', 'logo-settings', __( 'Header Text & Logo Settings', 'calibrefx' ) );

    add_action( 'logo-settings_options', function() {            
        calibrefx_add_meta_option(
            'logo-settings',  // group id
            'logo', // field id and option name
            __( 'Logo Image', 'calibrefx' ), // Label
            array(
                'option_type' => 'upload',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Upload your custom logo. This will be displayed as main header image.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'logo-settings' );

    calibrefx_add_meta_group( 'show-header-text-settings', 'show-header-text-settings', '' );

    add_action( 'show-header-text-settings_options', function() {            
        calibrefx_add_meta_option(
            'show-header-text-settings',  // group id
            'header_display_text', // field id and option name
            __( 'Show header text instead your logo?', 'calibrefx' ), // Label
            array(
                'option_type' => 'checkbox',
                'option_items' => '1',
                'option_default' => '',
                'option_filter' => 'integer',
                'option_description' => __( 'Check this if you want to show header text and not header image.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'show-header-text-settings' );

    echo '<div id="show-header-text"'.( calibrefx_get_option( 'header_display_text' )  ? ' style="display:block;"' : '' ).'>';

    calibrefx_add_meta_group( 'header-text-settings', 'header-text-settings', '' );

    add_action( 'header-text-settings_options', function() {            
        calibrefx_add_meta_option(
            'header-text-settings',  // group id
            'header_text_color', // field id and option name
            __( 'Header Text Color', 'calibrefx' ), // Label
            array(
                'option_type' => 'colorpicker',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Define your header text color here.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'header-text-settings' );

    echo '</div>';    

    calibrefx_add_meta_group( 'favicon-settings', 'favicon-settings', __( 'Favicon Settings', 'calibrefx') );

    add_action( 'favicon-settings_options', function() {            
        calibrefx_add_meta_option(
            'favicon-settings',  // group id
            'favicon', // field id and option name
            __( 'Favicon Image', 'calibrefx' ), // Label
            array(
                'option_type' => 'upload',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Recommended image size 16 x 16 pixels', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    } );

    add_action( 'favicon-settings_options', function() {            
        calibrefx_add_meta_option(
            'favicon-settings',  // group id
            'favicon_test', // field id and option name
            __( 'Test', 'calibrefx' ), // Label
            array(
                'option_type' => 'texteditor',
                'option_default' => '',
                'option_filter' => 'safe_text',
                'option_description' => __( 'Recommended image size 16 x 16 pixels', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'favicon-settings' );
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#calibrefx-settings-checkbox-header_display_text').click(function(){
        if($(this).attr('checked')){
            $('#show-header-text').slideDown();
        }else{
            $('#show-header-text').slideUp();
        }
    });
});
</script>
<style type="text/css">
#show-header-text{
    display: none;
}
</style>
<?php   
}

function custom_design_background_settings(){
    global $calibrefx;

    calibrefx_add_meta_group( 'background-settings', 'background-settings', '' );

    add_action( 'background-settings_options', function() {            
        calibrefx_add_meta_option(
            'background-settings',  // group id
            'background_image', // field id and option name
            __( 'Background Image', 'calibrefx' ), // Label
            array(
                'option_type' => 'upload',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Upload your custom background image above.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'background-settings',  // group id
            'background_color', // field id and option name
            __( 'Main Body Background Color', 'calibrefx' ), // Label
            array(
                'option_type' => 'colorpicker',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Define your main body background color here.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'background-settings',  // group id
            'content_color', // field id and option name
            __( 'Content Background Color', 'calibrefx' ), // Label
            array(
                'option_type' => 'colorpicker',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( 'Define your content background color here', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'background-settings' );
}

function custom_design_style(){
    global $post;
    $temp = $post;

    $background_image = esc_attr( calibrefx_get_option( 'background_image' ) );
    $background_image_id = esc_attr( calibrefx_get_option( 'background_image_id' ) );
    $background_color = esc_attr( calibrefx_get_option( 'background_color' ) );
    $content_color = esc_attr( calibrefx_get_option( 'content_color' ));

    $custom_css = "";

    if( !empty( $background_image ) || !empty( $background_image_id ) ){
        $post = get_post( $background_image_id );

        if( $post !== NULL ){
            $background_img = calibrefx_get_image( array('format' => 'url', 'id' => $background_image_id) );
        }elseif( !empty( $background_image ) ){
            $background_img = $background_image;
        }
    }

    $background_image_css = ( !empty( $background_img ) ? "background-image :  url($background_img);" : '' );
    $background_color_css = ( !empty( $background_color ) ? "background-color : $background_color;" : '' );
    $content_color_css = ( !empty( $content_color ) ? "background-color : $content_color;" : '' );

    $custom_css = "body{
    {$background_image_css}
    {$background_color_css}
}
#inner{
    {$content_color_css}
}";

    $post = $temp;

    wp_add_inline_style( 'calibrefx-child-style', $custom_css );
}