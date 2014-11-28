<?php
!defined( 'CONTACT_URL' ) && define( 'CONTACT_URL' , CHILD_URL . '/app/modules/contact' );

global $calibrefx;

if( $calibrefx::is_module_active( 'contact' ) ){
    add_action( 'calibrefx_meta', 'contact_load_script' );

    include_once 'contact_widget.php';
}

if( is_admin() && $calibrefx::is_module_active( 'contact' ) ){
    add_action( 'admin_init', 'contact_load_admin_scripts' );
    add_action( 'calibrefx_theme_settings_meta_section', 'contact_meta_sections' );
    add_action( 'calibrefx_theme_settings_meta_box', 'contact_meta_boxes' );
}

function contact_load_script(){
	wp_enqueue_script( 'contact-script', CONTACT_URL . '/assets/js/contact.js', array('jquery') );
    wp_enqueue_style( 'contact-style', CONTACT_URL . '/assets/css/contact.css' );
}

function contact_load_admin_scripts(){
    wp_enqueue_style( 'admin-contact-style', CONTACT_URL . '/assets/css/contact.admin.css' );
}

function contact_meta_sections(){
    global $calibrefx_target_form;
    
    calibrefx_add_meta_section( 'contact', __('Contact Settings', 'calibrefx'), $calibrefx_target_form, 20 );
}

function contact_meta_boxes(){
	global $calibrefx;

	calibrefx_add_meta_box( 'contact', 'basic', 'contact-settings', __('Contact Detail', 'calibrefx'), 'contact_settings', $calibrefx->theme_settings->pagehook, 'main', 'low' );
	calibrefx_add_meta_box( 'contact', 'basic', 'map-settings', __('Map Detail', 'calibrefx'), 'map_settings', $calibrefx->theme_settings->pagehook, 'main', 'low' );
}

function contact_settings(){
    global $calibrefx;

    calibrefx_add_meta_group( 'personal-info-settings', 'personal-info-settings', __( 'Personal Info', 'calibrefx' ) );

    add_action( 'personal-info-settings_options', function() {            
        calibrefx_add_meta_option(
            'personal-info-settings',  // group id
            'contact_name', // field id and option name
            __( 'Contact Name', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your name. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'personal-info-settings',  // group id
            'contact_email', // field id and option name
            __( 'Email Address', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __("Enter your email address. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'personal-info-settings',  // group id
            'contact_phone', // field id and option name
            __( 'Phone Number', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __("Enter your phone number. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'personal-info-settings',  // group id
            'contact_mobile_phone', // field id and option name
            __( 'Mobile Phone Number', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __("Enter your mobile phone number. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'personal-info-settings',  // group id
            'contact_address', // field id and option name
            __( 'Address', 'calibrefx' ), // Label
            array(
                'option_type' => 'textarea',
                'option_default' => '',
                'option_filter' => 'no_filter',
                'option_description' => __("Enter your address. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    });

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'personal-info-settings' );

    calibrefx_add_meta_group( 'company-info-settings', 'company-info-settings', __( 'Company Info', 'calibrefx' ) );

    add_action( 'company-info-settings_options', function() {            
        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_name', // field id and option name
            __( 'Company Name', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company name. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_email', // field id and option name
            __( 'Company Email Address', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company email address. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_phone', // field id and option name
            __( 'Company Phone Number', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company phone number. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_fax', // field id and option name
            __( 'Company Fax Number', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company fax number. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_work_day', // field id and option name
            __( 'Company Working Days', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company working days. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_work_hour', // field id and option name
            __( 'Company Working Hours', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => __( "Enter your company working hours. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'company-info-settings',  // group id
            'contact_company_address', // field id and option name
            __( 'Company Address', 'calibrefx' ), // Label
            array(
                'option_type' => 'textarea',
                'option_default' => '',
                'option_filter' => 'no_filter',
                'option_description' => __( "Enter your company address. It will be shown if using the Contact Widget.", 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    });

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'company-info-settings' );
}

function map_settings(){
    global $calibrefx;

    calibrefx_add_meta_group( 'map-embed-settings', 'map-embed-settings', __( 'Google Map Embed URL', 'calibrefx' ) );

    add_action( 'map-embed-settings_options', function() {            
        calibrefx_add_meta_option(
            'map-embed-settings',  // group id
            'map_url', // field id and option name
            __( 'Embed URL', 'calibrefx' ), // Label
            array(
                'option_type' => 'textarea',
                'option_default' => '',
                'option_filter' => 'no_filter',
                'option_description' => __( 'Enter google map embed url. Read more about this <a href="https://support.google.com/maps/answer/3544418" target="_blank">here</a>.', 'calibrefx' ),
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'map-embed-settings' );

    calibrefx_add_meta_group( 'map-embed-advanced-settings', 'map-embed-advanced-settings', '' );

    add_action( 'map-embed-advanced-settings_options', function() {            
        calibrefx_add_meta_option(
            'map-embed-advanced-settings',  // group id
            'map_advance_option', // field id and option name
            __( 'Show advanced option', 'calibrefx' ), // Label
            array(
                'option_type' => 'checkbox',
                'option_items' => '1',
                'option_default' => '',
                'option_filter' => 'integer',
                'option_description' => '',
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'map-embed-advanced-settings' );

    echo '<div id="map-detail-advance-setting"'.( calibrefx_get_option( 'map_advance_option' )  ? ' style="display:block;"' : '' ).'>';

    calibrefx_add_meta_group( 'map-embed-advanced-detail-settings', 'map-embed-advanced-detail-settings', '' );

    add_action( 'map-embed-advanced-detail-settings_options', function() {            
        calibrefx_add_meta_option(
            'map-embed-advanced-detail-settings',  // group id
            'map_x', // field id and option name
            __( 'Longitude Coordinate (x-axis)', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => 'Enter the longitude coordinate of the location on google map.',
            ), // Settings config
            1 //Priority
        );

        calibrefx_add_meta_option(
            'map-embed-advanced-detail-settings',  // group id
            'map_y', // field id and option name
            __( 'Latitude Coordinate (y-axis)', 'calibrefx' ), // Label
            array(
                'option_type' => 'textinput',
                'option_default' => '',
                'option_filter' => 'no_html',
                'option_description' => 'Enter the latitude coordinate of the location on google map.',
            ), // Settings config
            1 //Priority
        );
    } );

    calibrefx_do_meta_options( $calibrefx->theme_settings, 'map-embed-advanced-detail-settings' );

    echo '</div>';
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#calibrefx-settings-checkbox-map_advance_option').change(function(){
        if(this.checked){
            $('#map-detail-advance-setting').slideDown();
            // $('#map_advance_option').val('1');
        }else{
            $('#map-detail-advance-setting').slideUp();
            // $('#map_advance_option').val('0');
        }
    });
});
</script>
<?php
}

/* Contact Shortcode
------------------------------------------------------------ */
add_shortcode('map', 'contact_do_gmap');
function contact_do_gmap($atts, $content = null) {
    extract(shortcode_atts(array(
        'id' => '',
        'x' => '',
        'y' => '',
        'title' => '',
        'height' => '350'
    ), $atts));

    if(empty($x) || empty($y)) return;

    $id = ( $id == '' ) ? "random-googlemap-id-".rand(0,1000) : $id ;

    $output = '<div class="gmap-container"><div class="thumbnail" style="height:'.$height.'px;"><div id="'.$id.'"  class="googlemap" style="width:100%; height:'.$height.'px;"></div></div></div>';
    $output .= '<script type="text/javascript">eventMaps.push({id:"'.$id.'", x:"'.$x.'", y:"'.$y.'", title:"'.$title.'"});</script>';

    return $output;
}

add_shortcode('contact_map', 'contact_do_map');
function contact_do_map($atts, $content = null) {
    extract(shortcode_atts(array(
        'type' => 'gmap',
        'height' => '300'
    ), $atts));

    $map_x = stripslashes(esc_attr(calibrefx_get_option('map_x')));
	$map_y = stripslashes(esc_attr(calibrefx_get_option('map_y')));
	$map_url = stripslashes(esc_attr(calibrefx_get_option('map_url')));

    if($type == 'gmap'){
    	$output = '[map x="'.$map_x.'" y="'.$map_y.'" height="'.$height.'"]';
    }elseif($type == 'url'){
    	$output = '[gmap]'.html_entity_decode($map_url).'[/gmap]';
    }else{
    	$output = '<div class="alert alert-error">'.__('There is no map datas', 'calibrefx').'</div>';
    }

    return do_shortcode( $output );
}

add_shortcode('contact_name', 'contact_do_name');
function contact_do_name($atts, $content = null) {
    $contact_name = stripslashes(esc_attr(calibrefx_get_option('contact_name')));

    return $contact_name;
}

add_shortcode('contact_email', 'contact_do_email');
function contact_do_email($atts, $content = null) {
    $contact_email = stripslashes(esc_attr(calibrefx_get_option('contact_email')));

    return $contact_email;
}

add_shortcode('contact_phone', 'contact_do_phone');
function contact_do_phone($atts, $content = null) {
    $contact_phone = stripslashes(esc_attr(calibrefx_get_option('contact_phone')));

    return $contact_phone;
}

add_shortcode('contact_mobile_phone', 'contact_do_mobile_phone');
function contact_do_mobile_phone($atts, $content = null) {
    $contact_mobile_phone = stripslashes(esc_attr(calibrefx_get_option('contact_mobile_phone')));

    return $contact_mobile_phone;
}

add_shortcode('contact_address', 'contact_do_address');
function contact_do_address($atts, $content = null) {
    $contact_address = stripslashes(esc_attr(calibrefx_get_option('contact_address')));

    return $contact_address;
}

add_shortcode('contact_company_name', 'contact_do_company_name');
function contact_do_company_name($atts, $content = null) {
    $contact_company_name = stripslashes(esc_attr(calibrefx_get_option('contact_company_name')));

    return $contact_company_name;
}

add_shortcode('contact_company_email', 'contact_do_company_email');
function contact_do_company_email($atts, $content = null) {
    $contact_company_email = stripslashes(esc_attr(calibrefx_get_option('contact_company_email')));

    return $contact_company_email;
}

add_shortcode('contact_company_phone', 'contact_do_company_phone');
function contact_do_company_phone($atts, $content = null) {
    $contact_company_phone = stripslashes(esc_attr(calibrefx_get_option('contact_company_phone')));

    return $contact_company_phone;
}

add_shortcode('contact_company_fax', 'contact_do_company_fax');
function contact_do_company_fax($atts, $content = null) {
    $contact_company_fax = stripslashes(esc_attr(calibrefx_get_option('contact_company_fax')));

    return $contact_company_fax;
}

add_shortcode('contact_company_work_hour', 'contact_do_company_work_hour');
function contact_do_company_work_hour($atts, $content = null) {
    $contact_company_work_hour = stripslashes(esc_attr(calibrefx_get_option('contact_company_work_hour')));

    return $contact_company_work_hour;
}

add_shortcode('contact_company_work_day', 'contact_do_company_work_day');
function contact_do_company_work_day($atts, $content = null) {
    $contact_company_work_day = stripslashes(esc_attr(calibrefx_get_option('contact_company_work_day')));

    return $contact_company_work_day;
}

add_shortcode('contact_company_address', 'contact_do_company_address');
function contact_do_company_address($atts, $content = null) {
    $contact_company_address = stripslashes(esc_attr(calibrefx_get_option('contact_company_address')));

    return $contact_company_address;
}