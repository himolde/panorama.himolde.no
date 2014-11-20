<?php 
$audio = kopa_content_get_audio( get_the_content() );
if ( isset( $audio[0] ) ) {
    $audio = $audio[0];
} else {
    $audio = '';
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
        
    <?php if ( isset( $audio['shortcode'] ) ) { ?>                
        <div class="entry-thumb">
            <?php echo do_shortcode( $audio['shortcode'] ); ?>               
        </div>
    <?php } // endif ?>
    <header>
        <h4 class="entry-title"><?php the_title(); ?></h4>
        <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
        <?php comments_popup_link( '0', '1', '%', 'entry-comments' ); ?>
        <span class="entry-view"><?php echo (int) get_post_meta( get_the_ID(), 'kopa_' . kopa_get_domain() . '_total_view', true ); ?></span>
    </header>
    
    <div class="elements-box">
        <?php $content = get_the_content(); 
        $content = preg_replace( '/\[audio.*].*\[\/audio]/', '', $content );
        $content = preg_replace( '/\[soundcloud].*\[\/soundcloud]/', '', $content );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        ?>
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