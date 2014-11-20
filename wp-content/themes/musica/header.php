<?php 
$kopa_logo = get_option('kopa_theme_options_logo_url');
$kopa_socials = array(
    'twitter'   => get_option( 'kopa_theme_options_social_links_twitter_url' ),
    'facebook'  => get_option( 'kopa_theme_options_social_links_facebook_url' ),
    'gplus'     => get_option( 'kopa_theme_options_social_links_gplus_url' ),
    'pinterest' => get_option( 'kopa_theme_options_social_links_pinterest_url' ),
    'dribbble'  => get_option( 'kopa_theme_options_social_links_dribbble_url' ),
    'rss'       => get_option( 'kopa_theme_options_social_links_rss_url' ),
);
$kopa_theme_options_social_link_target = get_option('kopa_theme_options_social_link_target', '_self');
$kopa_display_weather = get_option( 'kopa_theme_options_display_weather_status', 'show' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">                   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php kopa_print_page_title(); ?></title>     
    <link rel="profile" href="http://gmpg.org/xfn/11">           
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    
    <?php if ( get_option('kopa_theme_options_favicon_url') ) { ?>       
        <link rel="shortcut icon" type="image/x-icon"  href="<?php echo get_option('kopa_theme_options_favicon_url'); ?>">
    <?php } ?>
    
    <?php if ( get_option('kopa_theme_options_apple_iphone_icon_url') ) { ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_ipad_icon_url') ) { ?>
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_iphone_retina_icon_url') ) { ?>
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_ipad_retina_icon_url') ) { ?>
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>">        
    <?php } ?>

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/PIE_IE678.js"></script>
    <![endif]-->
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="page-header">
    <div id="header-top">
        <div class="wrapper clearfix">
            <div id="logo-image">
            <?php if ( ! empty( $kopa_logo ) ) { ?>
                <a href="<?php echo home_url(); ?>"><img src="<?php echo esc_url( $kopa_logo ); ?>" alt="<?php bloginfo('name'); ?>"></a>
            <?php } else { ?>
                <h1 id="kopa-logo-text"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
            <?php } ?>
            </div>
            <div id="header-left" class="clearfix">
                <ul class="socials-link clearfix">
                    <?php if ( ! empty( $kopa_socials['twitter'] ) ) { ?>
                        <li><a class="kp-twitter-icon" href="<?php echo esc_url( $kopa_socials['twitter'] ); ?>" data-icon="&#xe1c9;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a></li>
                    <?php } ?>

                    <?php if ( ! empty( $kopa_socials['facebook'] ) ) { ?>
                        <li><a class="kp-facebook-icon" href="<?php echo esc_url( $kopa_socials['facebook'] ); ?>" data-icon="&#xe1c5;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a></li>
                    <?php } ?>

                    <?php if ( ! empty( $kopa_socials['gplus'] ) ) { ?>
                        <li><a class="kp-gplus-icon" href="<?php echo esc_url( $kopa_socials['gplus'] ); ?>" data-icon="&#xe1c1;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a></li>
                    <?php } ?>

                    <?php if ( ! empty( $kopa_socials['pinterest'] ) ) { ?>
                        <li><a class="kp-pinterest-icon" href="<?php echo esc_url( $kopa_socials['pinterest'] ); ?>" data-icon="&#xe1fd;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a></li>
                    <?php } ?>

                    <?php if ( ! empty( $kopa_socials['dribbble'] ) ) { ?>
                        <li><a class="kp-dribbble-icon" href="<?php echo esc_url( $kopa_socials['dribbble'] ); ?>" data-icon="&#xe1da;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a></li>
                    <?php } ?>

                    <?php if ( $kopa_socials['rss'] != 'HIDE' ) { 
                        if ( empty( $kopa_socials['rss'] ) ) {
                            $kopa_socials['rss'] = get_bloginfo('rss2_url');
                        }
                    ?>
                        <li>
                            <a class="kp-rss-icon" href="<?php echo esc_url( $kopa_socials['rss'] ); ?>" data-icon="&#xe1cc;" target="<?php echo $kopa_theme_options_social_link_target; ?>"></a>
                        </li>
                    <?php } ?>
                </ul>
                <!-- socials-link -->

                <?php if ( 'show' == $kopa_display_weather ) {
                    kopa_weather_widget();
                } ?>
                    
            </div>
            <!-- header-left -->
        </div>
        <!-- wrapper -->
    </div>
    <!-- header-top -->
    <div id="header-bottom">
        <div class="wrapper clearfix">
            <nav id="main-nav">
            <?php 
            if ( has_nav_menu( 'main-nav' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'main-nav',
                    'container'      => '',
                    'menu_id'        => 'main-menu',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s clearfix">%3$s</ul>'
                ) );

                $mobile_menu_walker = new kopa_mobile_menu();
                wp_nav_menu( array(
                    'theme_location' => 'main-nav',
                    'container'      => 'div',
                    'container_id'   => 'mobile-menu',
                    'menu_id'        => 'toggle-view-menu',
                    'items_wrap'     => '<span>'.__( 'Menu', kopa_get_domain() ).'</span><ul id="%1$s">%3$s</ul>',
                    'walker'         => $mobile_menu_walker
                ) );
            } ?>
            </nav>
            <!-- main-nav -->
            <div class="search-box clearfix">

                <?php get_search_form(); ?>

            </div>
            <!--search-box-->
        </div>
        <!-- wrapper -->
    </div>
    <!-- header-bottom -->
</header>
<!-- page-header -->

<div id="main-content">
    <div class="wrapper clearfix">