<?php 
$audio = kopa_content_get_audio( get_the_content() ); 
if ( isset( $audio[0] ) ) {
    $audio = $audio[0];
} else {
    $audio = '';
}
?>
<li id="li-post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article id="post-<?php the_ID(); ?>" class="entry-item clearfix">
        <div class="entry-thumb">
            <?php if ( isset( $audio['shortcode'] ) ) {
                echo do_shortcode( $audio['shortcode'] );
            } ?>
        </div>
        <!-- entry-thumb -->
        <div class="entry-content">
            <header>
                <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
            </header>
            <?php the_excerpt(); ?>
            <a class="more-link" href="<?php the_permalink(); ?>"><?php _e( 'Read more', kopa_get_domain() ); ?></a>
        </div>
        <!-- entry-content -->
    </article>
    <!-- entry-item -->
</li>