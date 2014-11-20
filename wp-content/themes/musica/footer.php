<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
$total = count( $sidebars );

$footer_sidebar[0] = ($kopa_setting) ? $sidebars[$total - 4] : 'sidebar_14';
$footer_sidebar[1] = ($kopa_setting) ? $sidebars[$total - 3] : 'sidebar_15';
$footer_sidebar[2] = ($kopa_setting) ? $sidebars[$total - 2] : 'sidebar_16';
$footer_sidebar[3] = ($kopa_setting) ? $sidebars[$total - 1] : 'sidebar_17';

/* get options */
// footer logo
$kopa_footer_logo = get_option( 'kopa_theme_options_footer_logo' );

$kopa_theme_options_copyright = get_option( 'kopa_theme_options_copyright', sprintf( __( 'Copyright %1$s - Kopasoft. All Rights Reserved.', kopa_get_domain() ), date('Y') ) );
$kopa_theme_options_copyright = htmlspecialchars_decode( stripslashes( $kopa_theme_options_copyright ) );
$kopa_theme_options_copyright = apply_filters( 'the_content', $kopa_theme_options_copyright );
?>
    </div> <!-- wrapper -->
</div> <!-- main-content -->

<div id="bottom-sidebar">
    <div class="wrapper">
        <header>
            <div id="bottom-logo">
                <a href="<?php echo home_url(); ?>">
                    <?php if ( ! empty( $kopa_footer_logo ) ) { ?>
                    <img src="<?php echo esc_url( $kopa_footer_logo ); ?>" alt="<?php bloginfo('name'); ?>">
                    <?php } else {
                        bloginfo('name');
                    } ?>
                </a>
            </div>
            <p id="back-top">
                <a href="#top"></a>
            </p>
            <!-- back-top -->
        </header>
        <div class="row-fluid">
            <?php foreach ( $footer_sidebar as $sidebar_index => $sidebar ) {
                if ( is_active_sidebar( $footer_sidebar[ $sidebar_index ] ) ) { ?> 
                <div class="span3">
                    <?php dynamic_sidebar( $sidebar ); ?>
                </div>
            <?php } // endif
            } // endforeach ?>
        </div>
        <!-- row-fluid -->
    </div>
    <!-- wrapper -->
</div>
<!-- bottom-sidebar-->

<footer id="page-footer">
    <div class="wrapper clearfix">
        
        <div id="copyright"><?php echo $kopa_theme_options_copyright;  ?></div>
        
        <?php 
        if ( has_nav_menu( 'footer-nav' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'footer-nav',
                'container'      => '',
                'menu_id'        => 'footer-menu',
                'items_wrap'     => '<ul id="%1$s" class="clearfix">%3$s</ul>',
                'depth'          => -1, // flat all items
            ) );
        }
        ?>
        
    </div>
    <!-- wrapper -->
</footer>
<!-- page-footer -->

<?php wp_footer(); ?>
    
</body>

</html>