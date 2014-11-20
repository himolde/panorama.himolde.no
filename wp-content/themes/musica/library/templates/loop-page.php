<?php if ( have_posts() ) {
    while ( have_posts() ) {
        the_post(); ?>

    <div id="page-<?php the_ID(); ?>" <?php post_class( 'elements-box' ); ?>>
        <?php the_content(); ?>
    </div>

    <div class="border-box">
        <div class="wrap-page-links clearfix">
            <div class="page-links">
                <?php wp_link_pages( array(
                    'before'   => '<span class="page-links-title">'.__( 'Pages:', kopa_get_domain() ).'</span>',
                    'after'    => '',
                    'pagelink' => __( '%', kopa_get_domain() )
                ) ); ?>
            </div><!--page-links-->
        </div><!--wrap-page-links-->
        <div class="clear"></div>
    </div><!-- border-box -->

    <?php comments_template(); ?>

<?php } // endwhile
} // endif
?>