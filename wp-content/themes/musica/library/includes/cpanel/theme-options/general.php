<div class="kopa-content-box tab-content tab-content-1" id="tab-general">

    <!--tab-logo-favicon-icon-->
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Logo, Favicon, Apple Icon', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">

            <span class="kopa-component-title"><?php _e('Logo', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Upload your own logo.', kopa_get_domain()); ?></p>                         
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_logo_url'); ?>" id="kopa_theme_options_logo_url" name="kopa_theme_options_logo_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_logo_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
            <p class="kopa-desc"><?php _e('Logo margin', kopa_get_domain()); ?></p>
            <label class="kopa-label"><?php _e('Top margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_top'); ?>" id="kopa_theme_options_logo_margin_top" name="kopa_theme_options_logo_margin_top" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Left margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_left'); ?>" id="kopa_theme_options_logo_margin_left" name="kopa_theme_options_logo_margin_left" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Right margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_right'); ?>" id="kopa_theme_options_logo_margin_right" name="kopa_theme_options_logo_margin_right" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>

            <span class="kopa-spacer"></span>

            <label class="kopa-label"><?php _e('Bottom margin:', kopa_get_domain()); ?> </label>
            <input type="text" value="<?php echo get_option('kopa_theme_options_logo_margin_bottom'); ?>" id="kopa_theme_options_logo_margin_bottom" name="kopa_theme_options_logo_margin_bottom" class=" kopa-short-input">
            <label class="kopa-label"><?php _e('px', kopa_get_domain()); ?></label>
        </div><!--kopa-element-box-->

        <?php 
        /**
         * Footer logo
         */
        ?>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Footer logo', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Upload your own logo.', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_footer_logo'); ?>" id="kopa_theme_options_footer_logo" name="kopa_theme_options_footer_logo">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_footer_logo"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Favicon', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Upload your own favicon.', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_favicon_url'); ?>" id="kopa_theme_options_favicon_url" name="kopa_theme_options_favicon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_favicon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Apple Icons', kopa_get_domain()); ?></span>

            <p class="kopa-desc"><?php _e('Iphone (57px - 57px)', kopa_get_domain()); ?></p>   
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>" id="kopa_theme_options_apple_iphone_icon_url" name="kopa_theme_options_apple_iphone_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
            <p class="kopa-desc"><?php _e('Iphone Retina (114px - 114px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>" id="kopa_theme_options_apple_iphone_retina_icon_url" name="kopa_theme_options_apple_iphone_retina_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_iphone_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad (72px - 72px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="left" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>" id="kopa_theme_options_apple_ipad_icon_url" name="kopa_theme_options_apple_ipad_icon_url">
                <button class="left btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>

            <p class="kopa-desc"><?php _e('Ipad Retina (144px - 144px)', kopa_get_domain()); ?></p>    
            <div class="clearfix">
                <input class="" type="text" value="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>" id="kopa_theme_options_apple_ipad_retina_icon_url" name="kopa_theme_options_apple_ipad_retina_icon_url">
                <button class="btn btn-success upload_image_button" alt="kopa_theme_options_apple_ipad_retina_icon_url"><i class="icon-circle-arrow-up"></i><?php _e('Upload', kopa_get_domain()); ?></button>
            </div>
        </div><!--kopa-element-box-->


    </div><!--tab-logo-favicon-icon-->

    <?php 
    /**
     * Responsive layout
     */
    ?> 
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Responsive layout', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Enable/Disable Responsive Layout:', kopa_get_domain()); ?></span>            
            <?php
            $kopa_responsive_status = array(
                'enable' => __('Enable', kopa_get_domain()),
                'disable' => __('Disable', kopa_get_domain())
            );
            $kopa_responsive_name = "kopa_theme_options_responsive_status";
            foreach ($kopa_responsive_status as $value => $label) {
                $kopa_responsive_id = $kopa_responsive_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_responsive_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_responsive_id; ?>" name="<?php echo $kopa_responsive_name; ?>" <?php echo ($value == get_option($kopa_responsive_name, 'enable')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
    </div>
    
    <?php 
    /**
     * Header weather widget
     */
    ?> 
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Header Weather', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Display header weather:', kopa_get_domain()); ?></span>            
            <?php
            $kopa_header_headline_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $kopa_header_headline_name = "kopa_theme_options_display_weather_status";
            foreach ($kopa_header_headline_status as $value => $label) {
                $kopa_header_headline_status_id = $kopa_header_headline_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_header_headline_status_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_header_headline_status_id; ?>" name="<?php echo $kopa_header_headline_name; ?>" <?php echo ($value == get_option($kopa_header_headline_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <!-- bold title -->
            <span class="kopa-component-title"><?php _e('Title', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_weather_title', 'Weather Forecast'); ?>" id="kopa_theme_options_weather_title" name="kopa_theme_options_weather_title">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Weather data:', kopa_get_domain()); ?></span>            
            <?php
            $kopa_header_weather_data = array(
                'automatic' => __('<strong>Automatic</strong> (Detect automatically by client IP)', kopa_get_domain()),
                'custom'    => __('<strong>Custom</strong> (Type your location below)', kopa_get_domain())
            );
            $kopa_header_weather_data_name = "kopa_theme_options_weather_data";
            foreach ($kopa_header_weather_data as $value => $label) {
                $kopa_header_weather_data_id = $kopa_header_weather_data_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_header_weather_data_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_header_weather_data_id; ?>" name="<?php echo $kopa_header_weather_data_name; ?>" <?php echo ($value == get_option($kopa_header_weather_data_name, 'automatic')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <!-- bold title -->
            <span class="kopa-component-title"><?php _e('Your location search query (i.e: London,UK or New York City,NY)', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_weather_location'); ?>" id="kopa_theme_options_weather_location" name="kopa_theme_options_weather_location">
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <!-- custom override title --> 
            <span class="kopa-component-title"><?php _e('Override Title', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_weather_override_title'); ?>" id="kopa_theme_options_weather_override_title" name="kopa_theme_options_weather_override_title">
        </div>
    </div>

    <?php 
    /**
     * Show/hide breadcrumb
     */
    ?> 
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Breadcrumb', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show/Hide Breadcrumb:', kopa_get_domain()); ?></span>            
            <?php
            $kopa_breadcrumb_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $kopa_breadcrumb_status_name = "kopa_theme_options_breadcrumb_status";
            foreach ($kopa_breadcrumb_status as $value => $label) {
                $kopa_breadcrumb_id = $kopa_breadcrumb_status_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_breadcrumb_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_breadcrumb_id; ?>" name="<?php echo $kopa_breadcrumb_status_name; ?>" <?php echo ($value == get_option($kopa_breadcrumb_status_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
    </div>

    <?php 
    /**
     * Add home menu item as first item on main menu
     */
    ?> 
    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Home Menu Item', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Add home menu item as first item on main menu:', kopa_get_domain()); ?></span>            
            <?php
            $kopa_home_menu_item_status = array(
                'show' => __('Enable', kopa_get_domain()),
                'hide' => __('Disable', kopa_get_domain())
            );
            $kopa_home_menu_item_name = "kopa_theme_options_home_menu_item_status";
            foreach ($kopa_home_menu_item_status as $value => $label) {
                $kopa_home_menu_item_id = $kopa_home_menu_item_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_home_menu_item_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_home_menu_item_id; ?>" name="<?php echo $kopa_home_menu_item_name; ?>" <?php echo ($value == get_option($kopa_home_menu_item_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Footer', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Custom Left Footer', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Enter the content you want to display in your left footer (e.g. copyright text).', kopa_get_domain()); ?></p>    
            <textarea class="" rows="6" id="kopa_setting_copyrights" name="kopa_theme_options_copyright"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_copyright', sprintf( __( 'Copyright %1$s - Kopasoft. All Rights Reserved.', kopa_get_domain() ), date('Y') )))); ?></textarea>
        </div><!--kopa-element-box-->

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Google Analytics', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e('Enter Google Analytics code. This should be something like: &lt;script type="text/javascript"&gt;  ...  &lt;/script&gt;', kopa_get_domain()); ?></p>    
            <textarea class="" id="kopa_setting_tracking_code" rows="10" name="kopa_theme_options_tracking_code"><?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_tracking_code'))); ?></textarea>
        </div><!--kopa-element-box-->

    </div>

</div><!--kopa-content-box-->

