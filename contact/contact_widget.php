<?php
add_action( 'widgets_init', create_function( '', "register_widget('CFX_Contact_Widget');" ) );

class CFX_Contact_Widget extends WP_Widget {

    protected $defaults;

    /**
     * Constructor
     */
    function __construct() {

        $this->defaults = array(
           'title' => '',
           'map_type' => 'gmap',
           'map_height' => '180',
           'show_map' => 1,
           'show_personal_info' => 1,
           'personal_info_title' => 'Personal Info',
           'show_name' => 1,
           'show_email' => 1,
           'show_phone' => 1,
           'show_mobile_phone' => 1,
           'show_address' => 1,
           'show_company_info' => 1,
           'company_info_title' => 'Company Info',
           'show_company_name' => 1,
           'show_company_email' => 1,
           'show_company_phone' => 1,
           'show_company_fax' => 1,
           'show_company_work_day' => 1,
           'show_company_work_hour' => 1,
           'show_company_address' => 1,
           'show_address' => 1,
        );

        $widget_ops = array(
            'classname' => 'contact-widget',
            'description' => __( 'Display contact information', 'calibrefx' ),
        );

 
        $this->WP_Widget( 'contact-widget', __( 'Contact Widget (Calibrefx)', 'calibrefx' ), $widget_ops );
    }

    /**
     * Display widget content.
     *
     * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget
     */
    function widget( $args, $instance ) {
        extract( $args );
        $instance = wp_parse_args( (array) $instance, $this->defaults );
		
        echo $before_widget;

        if( !empty( $instance['title'] ) )
            echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
        ?>
        <div class="contact-wrapper">
            <?php if( $instance['show_map'] ){ ?>
            <div class="contact-map">
                <?php echo do_shortcode( '[contact_map type="' . $instance['map_type'] . '" height="' . $instance['map_height'] . '"]' ); ?>
            </div>
            <?php } ?>
            <?php if( $instance['show_personal_info'] ){ ?>
            <div class="personal-detail">
                <?php if( $instance['personal_info_title'] ){ ?>
                <h5 class="contact-name-title"><?php echo stripslashes( $instance['personal_info_title'] ); ?></h5>
                <?php } ?>
                <?php
                    $personal_info_output = '<ul class="personal-info fa-ul">';
                    if( $instance['show_name'] ){
                        $personal_info_output .= '<li class="contact-name"><i class="fa fa-li fa-user fa-fw icon-contact-name"></i>'.do_shortcode( '[contact_name]' ).'</li>';
                    }
                    if( $instance['show_email'] ){
                        $personal_info_output .= '<li class="contact-email"><i class="fa fa-li fa-envelope fa-fw icon-contact-email"></i>'.do_shortcode( '[contact_email]' ).'</li>';
                    }
                    if( $instance['show_phone'] ){
                        $personal_info_output .= '<li class="contact-phone"><i class="fa fa-li fa-phone fa-fw icon-contact-phone"></i>'.do_shortcode( '[contact_phone]' ).'</li>';
                    }
                    if( $instance['show_mobile_phone'] ){
                        $personal_info_output .= '<li class="contact-fax"><i class="fa fa-li fa-mobile fa-fw icon-contact-mobile"></i>'.do_shortcode( '[contact_mobile_phone]' ).'</li>';
                    }
                    if( $instance['show_address'] ){
                        $personal_info_output .= '<li class="contact-address"><i class="fa fa-li fa-home fa-fw icon-contact-address"></i>'.nl2br(do_shortcode( '[contact_address]' )).'</li>';
                    }
                    $personal_info_output .= '</ul>';

                    $personal_info_output = apply_filters( 'calibrefx_personal_info_output', $personal_info_output );

                    echo $personal_info_output;
                ?>
                
            </div>
            <?php } ?>

            <?php if( $instance['show_company_info'] ){ ?>
            <div class="company-detail">
                <?php if( $instance['company_info_title'] ){ ?>
                <h5 class="company-name-title"><?php echo stripslashes( $instance['company_info_title'] ); ?></h5>
                <?php } ?>
                <?php
                    $company_info_output = '<ul class="company-info fa-ul">';
                    if( $instance['show_company_name'] ){
                        $company_info_output .= '<li class="company-name"><i class="fa fa-li fa-users fa-fw icon-company-name"></i>'.do_shortcode( '[contact_company_name]' ).'</li>';
                    }
                    if( $instance['show_company_email'] ){
                        $company_info_output .= '<li class="company-email"><i class="fa fa-li fa-envelope fa-fw icon-company-email"></i>'.do_shortcode( '[contact_company_email]' ).'</li>';
                    }
                    if( $instance['show_company_phone'] ){
                        $company_info_output .= '<li class="company-phone"><i class="fa fa-li fa-phone fa-fw icon-company-phone"></i>'.do_shortcode( '[contact_company_phone]' ).'</li>';
                    }
                    if( $instance['show_company_fax'] ){
                        $company_info_output .= '<li class="company-fax"><i class="fa fa-li fa-files-o fa-fw icon-company-fax"></i>'.do_shortcode( '[contact_company_fax]' ).'</li>';
                    }
                    if( $instance['show_company_work_day'] ){
                        $company_info_output .= '<li class="company-day"><i class="fa fa-li fa-calendar fa-fw icon-company-day"></i>'.do_shortcode( '[contact_company_work_day]' ).'</li>';
                    }
                    if( $instance['show_company_work_hour'] ){
                        $company_info_output .= '<li class="company-hour"><i class="fa fa-li fa-clock-o fa-fw icon-company-hour"></i>'.do_shortcode( '[contact_company_work_hour]' ).'</li>';
                    }
                    if( $instance['show_company_address'] ){
                        $company_info_output .= '<li class="company-address"><i class="fa fa-li fa-building-o fa-fw icon-company-address"></i>'.nl2br(do_shortcode( '[contact_company_address]' )).'</li>';
                    }
                    $company_info_output .= '</ul>';

                    $company_info_output = apply_filters( 'calibrefx_company_info_output', $company_info_output );

                    echo $company_info_output;
                ?>
                
            </div>
            <?php } ?>
        </div>
        <?php
        echo $after_widget;
    }

    /**
     * Update a particular instance.
     */
    function update($new_instance, $old_instance) {
        if( empty($new_instance['show_personal_info']) ) $new_instance['show_personal_info'] = 0;
        if( empty($new_instance['show_name']) ) $new_instance['show_name'] = 0;
        if( empty($new_instance['show_email']) ) $new_instance['show_email'] = 0;
        if( empty($new_instance['show_phone']) ) $new_instance['show_phone'] = 0;
        if( empty($new_instance['show_mobile_phone']) ) $new_instance['show_mobile_phone'] = 0;
        if( empty($new_instance['show_address']) ) $new_instance['show_address'] = 0;
        if( empty($new_instance['show_map']) ) $new_instance['show_map'] = 0;

        if( empty($new_instance['show_company_info']) ) $new_instance['show_company_info'] = 0;
        if( empty($new_instance['show_company_name']) ) $new_instance['show_company_name'] = 0;
        if( empty($new_instance['show_company_email']) ) $new_instance['show_company_email'] = 0;
        if( empty($new_instance['show_company_phone']) ) $new_instance['show_company_phone'] = 0;
        if( empty($new_instance['show_company_fax']) ) $new_instance['show_company_fax'] = 0;
        if( empty($new_instance['show_company_address']) ) $new_instance['show_company_address'] = 0;
        if( empty($new_instance['show_company_work_hour']) ) $new_instance['show_company_work_hour'] = 0;
        if( empty($new_instance['show_company_work_day']) ) $new_instance['show_company_work_day'] = 0;

        return $new_instance;
    }

    /**
     * Display the settings update form.
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        ?>
		<p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'calibrefx' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>

        <hr class="div" />

        <p>
            <label for="<?php echo $this->get_field_id('show_map'); ?>"><strong><?php _e( 'Show Map', 'calibrefx' ); ?>:</strong></label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_map' ); ?>" name="<?php echo $this->get_field_name( 'show_map' ); ?>" value="1" <?php if( $instance['show_map'] ) echo 'checked="checked"'; ?>/>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('map_type'); ?>"><?php _e( 'Map Type', 'calibrefx' ); ?>:</label><br />
            <select name="<?php echo $this->get_field_name( 'map_type' ); ?>" id="<?php echo $this->get_field_id( 'map_type' ); ?>">
                <option value="gmap"<?php selected( 'gmap', $instance['map_type'], true ); ?>>Google Map using Coordinates</option>
                <option value="url"<?php selected( 'url', $instance['map_type'], true ); ?>>Google Map using Iframe</option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('map_height'); ?>"><?php _e( 'Map Height', 'calibrefx' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'map_height' ); ?>" name="<?php echo $this->get_field_name( 'map_height' ); ?>" value="<?php echo esc_attr( $instance['map_height'] ); ?>" class="widefat" />
        </p>
        <p class="description" style="padding-bottom: 0px"><?php _e( 'Enter height for map. default: 180px', 'calibrefx' ); ?></p>

        <hr class="div" />

        <p>
            <label for="<?php echo $this->get_field_id('show_personal_info'); ?>"><strong><?php _e( 'Show Personal Info', 'calibrefx' ); ?>:</strong></label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_personal_info' ); ?>" name="<?php echo $this->get_field_name( 'show_personal_info' ); ?>" value="1" <?php if($instance['show_personal_info']) echo 'checked="checked"'; ?>/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('personal_info_title'); ?>"><?php _e( 'Personal Info Title', 'calibrefx' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'personal_info_title' ); ?>" name="<?php echo $this->get_field_name( 'personal_info_title' ); ?>" value="<?php echo esc_attr( $instance['personal_info_title'] ); ?>" class="widefat" />
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_name' ); ?>" name="<?php echo $this->get_field_name( 'show_name' ); ?>" value="1" <?php if( $instance['show_name'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_name'); ?>"><?php _e( 'Show Contact Name', 'calibrefx' ); ?></label>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_email' ); ?>" name="<?php echo $this->get_field_name( 'show_email' ); ?>" value="1" <?php if( $instance['show_email'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_email'); ?>"><?php _e( 'Show Email Address', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_phone' ); ?>" name="<?php echo $this->get_field_name( 'show_phone' ); ?>" value="1" <?php if( $instance['show_phone'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_phone'); ?>"><?php _e( 'Show Phone Number', 'calibrefx' ); ?></label>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_mobile_phone' ); ?>" name="<?php echo $this->get_field_name( 'show_mobile_phone' ); ?>" value="1" <?php if( $instance['show_mobile_phone'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_mobile_phone'); ?>"><?php _e( 'Show Mobile Phone Number', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_address' ); ?>" name="<?php echo $this->get_field_name( 'show_address' ); ?>" value="1" <?php if( $instance['show_address'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_address'); ?>"><?php _e( 'Show Address', 'calibrefx' ); ?></label>
        </p>

        <hr class="div" />

        <p>
            <label for="<?php echo $this->get_field_id('show_map'); ?>"><strong><?php _e( 'Show Map', 'calibrefx' ); ?>:</strong></label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_map' ); ?>" name="<?php echo $this->get_field_name( 'show_map' ); ?>" value="1" <?php if( $instance['show_map'] ) echo 'checked="checked"'; ?>/>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('map_type'); ?>"><?php _e( 'Map Type', 'calibrefx' ); ?>:</label><br />
            <select name="<?php echo $this->get_field_name( 'map_type' ); ?>" id="<?php echo $this->get_field_id( 'map_type' ); ?>">
                <option value="gmap"<?php selected( 'gmap', $instance['map_type'], true ); ?>>Google Map using Coordinates</option>
                <option value="url"<?php selected( 'url', $instance['map_type'], true ); ?>>Google Map using Iframe</option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('map_height'); ?>"><?php _e( 'Map Height', 'calibrefx' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'map_height' ); ?>" name="<?php echo $this->get_field_name( 'map_height' ); ?>" value="<?php echo esc_attr( $instance['map_height'] ); ?>" class="widefat" />
        </p>
        <p class="description" style="padding-bottom: 0px"><?php _e( 'Enter height for map. default: 180px', 'calibrefx' ); ?></p>

        <hr class="div" />

        <p>
            <label for="<?php echo $this->get_field_id('show_company_info'); ?>"><strong><?php _e( 'Show Company Info', 'calibrefx' ); ?>:</strong></label>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_info' ); ?>" name="<?php echo $this->get_field_name( 'show_company_info' ); ?>" value="1" <?php if( $instance['show_company_info'] ) echo 'checked="checked"'; ?>/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('company_info_title'); ?>"><?php _e( 'Company Info Title', 'calibrefx' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'company_info_title' ); ?>" name="<?php echo $this->get_field_name( 'company_info_title' ); ?>" value="<?php echo esc_attr( $instance['company_info_title'] ); ?>" class="widefat" />
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_name' ); ?>" name="<?php echo $this->get_field_name( 'show_company_name' ); ?>" value="1" <?php if( $instance['show_company_name'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_name'); ?>"><?php _e( 'Show Company Name', 'calibrefx' ); ?></label>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_email' ); ?>" name="<?php echo $this->get_field_name( 'show_company_email' ); ?>" value="1" <?php if( $instance['show_company_email'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_email'); ?>"><?php _e( 'Show Company Email Address', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_phone' ); ?>" name="<?php echo $this->get_field_name( 'show_company_phone' ); ?>" value="1" <?php if( $instance['show_company_phone'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_phone'); ?>"><?php _e( 'Show Company Phone Number', 'calibrefx' ); ?></label>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_fax' ); ?>" name="<?php echo $this->get_field_name( 'show_company_fax' ); ?>" value="1" <?php if( $instance['show_company_fax'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_fax'); ?>"><?php _e( 'Show Company Fax Number', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_work_day' ); ?>" name="<?php echo $this->get_field_name( 'show_company_work_day' ); ?>" value="1" <?php if( $instance['show_company_work_day'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_work_day'); ?>"><?php _e( 'Show Company Working Days', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_work_hour' ); ?>" name="<?php echo $this->get_field_name( 'show_company_work_hour' ); ?>" value="1" <?php if( $instance['show_company_work_hour'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_work_hour'); ?>"><?php _e( 'Show Company Working Hours', 'calibrefx' ); ?></label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'show_company_address' ); ?>" name="<?php echo $this->get_field_name( 'show_company_address' ); ?>" value="1" <?php if( $instance['show_company_address'] ) echo 'checked="checked"'; ?>/>
            <label for="<?php echo $this->get_field_id('show_company_address'); ?>"><?php _e( 'Show Company Address', 'calibrefx' ); ?></label>
        </p>
        <?php
    }

}