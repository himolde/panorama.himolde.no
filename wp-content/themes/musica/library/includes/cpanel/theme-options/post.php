<div id="tab-single-post" class="kopa-content-box tab-content tab-content-1">    

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Featured Post Thumbnail', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show/Hide Featured Post Thumbnail', kopa_get_domain()); ?></span>            
            <?php
            $kopa_featured_image_post_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $kopa_featured_image_name = "kopa_theme_options_featured_image_status";
            foreach ($kopa_featured_image_post_status as $value => $label) {
                $kopa_featured_image_status_id = $kopa_featured_image_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_featured_image_status_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_featured_image_status_id; ?>" name="<?php echo $kopa_featured_image_name; ?>" <?php echo ($value == get_option($kopa_featured_image_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('About Author', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        
        <div class="kopa-element-box kopa-theme-options">            
            <?php
            $about_author_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain())
            );
            $about_author_name = "kopa_theme_options_post_about_author";
            foreach ($about_author_status as $value => $label) {
                $about_author_id = $about_author_name . "_{$value}";
                ?>
                <label  for="<?php echo $about_author_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $about_author_id; ?>" name="<?php echo $about_author_name; ?>" <?php echo ($value == get_option($about_author_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // endforeach
            ?>
        </div>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Sharing Buttons', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <?php
        $sharing_buttons = array(
            'facebook'  => __('Facebook', kopa_get_domain()),
            'twitter'   => __('Twitter', kopa_get_domain()),
            'google'    => __('Google', kopa_get_domain()),
            'linkedin'  => __('LinkedIn', kopa_get_domain()),
            'pinterest' => __('Pinterest', kopa_get_domain()),
            'email'     => __('Email', kopa_get_domain())
        );
        $sharing_button_status = array(
            'show' => __('Show', kopa_get_domain()),
            'hide' => __('Hide', kopa_get_domain())
        );

        foreach ($sharing_buttons as $slug => $title):
            ?>
            <div class="kopa-element-box kopa-theme-options">
                <span class="kopa-component-title"><?php echo $title; ?></span>                        
                <?php
                $sharing_button_name = "kopa_theme_options_post_sharing_button_{$slug}";
                foreach ($sharing_button_status as $value => $label):
                    $sharing_button_id = $sharing_button_name . "_{$value}";
                    ?>
                    <label  for="<?php echo $sharing_button_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo $value; ?>" id="<?php echo $sharing_button_id; ?>" name="<?php echo $sharing_button_name; ?>" <?php echo ($value == get_option($sharing_button_name, 'show')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                    <?php
                endforeach;
                ?>
            </div>
            <?php
        endforeach;
        ?>
    </div>

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Related Posts', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->

    <div class="kopa-box-body">

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Get By', kopa_get_domain()); ?></span>
            <select class="" id="kopa_theme_options_post_related_get_by" name="kopa_theme_options_post_related_get_by">
                <?php
                $post_related_get_by = array(
                    'hide'     => __('-- Hide --', kopa_get_domain()),
                    'post_tag' => __('Tag', kopa_get_domain()),
                    'category' => __('Category', kopa_get_domain())
                );
                foreach ($post_related_get_by as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value == get_option('kopa_theme_options_post_related_get_by', 'post_tag')) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Limit', kopa_get_domain()); ?></span>
            <input type="number" value="<?php echo get_option('kopa_theme_options_post_related_limit', 10); ?>" id="kopa_theme_options_post_related_limit" name="kopa_theme_options_post_related_limit">
        </div>
    </div><!--tab-theme-skin-->  

    <div class="kopa-box-head">
        <i class="icon-hand-right"></i>
        <span class="kopa-section-title"><?php _e('Facebook Comments', kopa_get_domain()); ?></span>
    </div><!--kopa-box-head-->
    <div class="kopa-box-body">
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Show/Hide Facebook Comments', kopa_get_domain()); ?></span>            
            <?php
            $kopa_facebook_comments_status = array(
                'show' => __('Show', kopa_get_domain()),
                'hide' => __('Hide', kopa_get_domain()),
            );
            $kopa_facebook_comments_name = "kopa_theme_options_facebook_comments_status";
            foreach ($kopa_facebook_comments_status as $value => $label) {
                $kopa_facebook_comments_id = $kopa_facebook_comments_name . "_{$value}";
                ?>
                <label  for="<?php echo $kopa_facebook_comments_id; ?>" class="kopa-label-for-radio-button"><input type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo $kopa_facebook_comments_id; ?>" name="<?php echo $kopa_facebook_comments_name; ?>" <?php echo ($value == get_option($kopa_facebook_comments_name, 'hide')) ? 'checked="checked"' : ''; ?>><?php echo $label; ?></label>
                <?php
            } // end foreach
            ?>
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Language:', kopa_get_domain()); ?></span>
            <input type="text" value="<?php echo get_option('kopa_theme_options_facebook_comments_language', 'en_US'); ?>" id="kopa_theme_options_facebook_comments_language" name="kopa_theme_options_facebook_comments_language">
        </div>
        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('App ID:', kopa_get_domain()); ?></span>
            <p class="kopa-desc"><?php _e( 'To setup Moderation, enter your', kopa_get_domain() ); ?> <a href="http://developers.facebook.com/docs/appsonfacebook/tutorial/" target="_blank"><?php _e( 'Facebook Application ID', kopa_get_domain() ); ?></a> <?php _e( 'below', kopa_get_domain() ); ?>.</p>
            <input type="text" value="<?php echo get_option('kopa_theme_options_facebook_comments_app_id'); ?>" id="kopa_theme_options_facebook_comments_app_id" name="kopa_theme_options_facebook_comments_app_id">
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Number of posts:', kopa_get_domain()); ?></span>
            <input type="number" min="1" value="<?php echo get_option('kopa_theme_options_facebook_comments_num_of_posts', 10); ?>" id="kopa_theme_options_facebook_comments_num_of_posts" name="kopa_theme_options_facebook_comments_num_of_posts">
        </div>

        <div class="kopa-element-box kopa-theme-options">
            <span class="kopa-component-title"><?php _e('Color Scheme:', kopa_get_domain()); ?></span>
            <select name="kopa_theme_options_facebook_comments_colorscheme" id="kopa_theme_options_facebook_comments_colorscheme">
                <?php 
                $kopa_facebook_comments_colorschemes_list = array(
                    'light' => __('Light', kopa_get_domain()),
                    'dark'  => __('Dark', kopa_get_domain()),
                ); 
                $kopa_facebook_comments_colorscheme = get_option('kopa_theme_options_facebook_comments_colorscheme', 'light');
                foreach ( $kopa_facebook_comments_colorschemes_list as $value => $label ) { ?>
                    <option value="<?php echo $value; ?>" <?php selected( $value, $kopa_facebook_comments_colorscheme ); ?>><?php echo $label; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

</div><!--tab-container-->