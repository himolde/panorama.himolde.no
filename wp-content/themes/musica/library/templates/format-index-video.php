<?php $video = kopa_content_get_video( get_the_content() ); 
if ( isset( $video[0] ) ) {
    $video = $video[0];
} else {
    $video = '';
}
?>
<li id="li-post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article id="post-<?php the_ID(); ?>" class="entry-item clearfix">
        <div class="entry-thumb">
            <?php if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'article-list-image-size' );
            } elseif ( isset( $video['type'] ) && isset( $video['url'] ) ) {
                $video_thumbnail = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );

                if ( ! empty( $video_thumbnail ) ) { ?>
                    <img src="<?php echo $video_thumbnail; ?>" alt="<?php the_title(); ?>">
                <?php } // endif
            } // endif ?>


            <?php if ( isset( $video['url'] ) ) { ?>
                <div class="mask"><a href="<?php echo $video['url']; ?>" data-icon="&#xe163;" rel="prettyPhoto"></a></div>
            <?php } ?>
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