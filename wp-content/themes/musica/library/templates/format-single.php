<?php 
$kopa_display_featured_image = get_option( 'kopa_theme_options_featured_image_status', 'show' );
?>
<div class="kopa-single-1">
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>              
        <?php if ( 'show' == $kopa_display_featured_image && has_post_thumbnail() ) { ?>
        <div class="entry-thumb">
            <?php the_post_thumbnail( 'article-list-image-size' ); ?>
        </div>
        <?php } // endif ?>
        <header>
            <h4 class="entry-title"><?php the_title(); ?></h4>
            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
            <?php comments_popup_link( '0', '1', '%', 'entry-comments' ); ?>
            <span class="entry-view"><?php echo (int) get_post_meta( get_the_ID(), 'kopa_' . kopa_get_domain() . '_total_view', true ); ?></span>
        </header>
        
        <div class="elements-box">
            <?php the_content(); ?>
        </div>

        <div class="border-box">
            <?php if ( get_the_terms( get_the_ID(), 'post_tag' ) ) { ?>
                <div class="tag-box">
                    <span><?php _e( 'Tagged with:', kopa_get_domain() ); ?></span>
                    <?php the_tags( '', ', ', '' ); ?>
                </div><!--tag-box-->
            <?php } // endif ?>

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
        
        <?php kopa_get_socials_link(); ?>

        <footer class="clearfix">
            <?php get_template_part( 'library/templates/template', 'post-navigation' ); ?>
        </footer>
    </div><!--entry-box-->
</div>