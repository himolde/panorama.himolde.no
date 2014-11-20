<?php
add_action('admin_menu', 'kopa_admin_menu_themes_introduction');

function kopa_admin_menu_themes_introduction() {
    // check whether or not display upsell page
    $xml = kopa_get_theme_info( KOPA_UPDATE_TIMEOUT );

    if ( ! is_object( $xml ) ||
         ( is_object( $xml ) && ! property_exists($xml, 'upsell') ) ) {
        return;
    }

    // Kopatheme Introduction Page
    $page_kopa_cpanel_themes_introduction = add_theme_page(
            __('Kopatheme - Premium WordPress Themes and Web Templates', kopa_get_domain()), __('Kopatheme Premium Templates', kopa_get_domain()), 'edit_themes', 'kopa_cpanel_themes_introduction', 'kopa_cpanel_themes_introduction'
    );
    add_action('admin_print_styles-' . $page_kopa_cpanel_themes_introduction, 'kopa_admin_themes_introduction_print_styles');
}

function kopa_admin_themes_introduction_print_styles() {
    wp_enqueue_style('kopa-admin-bootstrap-style', get_template_directory_uri() . '/library/css/bootstrap/css/bootstrap.min.css', array(), null);
    wp_enqueue_style('kopa-admin-bootstrap-responsive-style', get_template_directory_uri() . '/library/css/bootstrap/css/bootstrap-responsive.min.css', array(), null);
    wp_enqueue_style('kopa-admin-themes-introduction-style', get_template_directory_uri() . '/library/includes/upsell/css/themes-introduction.css', array(), null);
}

function kopa_cpanel_themes_introduction() {
    include trailingslashit(get_template_directory()) . '/library/includes/upsell/themes-introduction.php';
}


// update to premium notices
add_action('admin_notices', 'kopa_update_premium_version_notices');

function kopa_update_premium_version_notices() {
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'kopa_cpanel_themes_introduction' ) {
        return;
    }

    global $current_user;
        $user_id = $current_user->ID;

    $xml = kopa_get_theme_info(KOPA_UPDATE_TIMEOUT);
    if ( is_object($xml) && property_exists($xml, 'nag') ) {
        $content = $xml->nag;
    }

    if ( isset( $content ) && ! empty( $content ) && ! get_user_meta( $user_id, 'kopa_nag_ignore_upsell' ) ) {
        $out = '<div class="updated kopa_update_info">';
        $out .= sprintf('<p><strong>%1$s - <a href="%2$s" target="_blank">%3$s</a></strong></p>', $content->title, $content->btnurl, $content->btntitle);

        $current_page_url = $_SERVER['REQUEST_URI'];
        $current_page_url = add_query_arg( 'kopa_nag_ignore_upsell', '0', $current_page_url );
        $out .= sprintf('<p><strong><a href="%1$s">%2$s</a></strong></p>', $current_page_url, __('Hide This Notice', kopa_get_domain()));
        $out .= '</div>';

        echo $out;
    }
}

add_action('admin_init', 'kopa_nag_ignore_upsell');

function kopa_nag_ignore_upsell() {
    global $current_user;
        $user_id = $current_user->ID;

    if ( isset( $_GET['kopa_nag_ignore_upsell'] ) && '0' == $_GET['kopa_nag_ignore_upsell'] )  {
        add_user_meta($user_id, 'kopa_nag_ignore_upsell', 'true', true);
    }
}