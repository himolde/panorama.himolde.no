<?php 
$gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() );
?>

<li id="li-post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article id="post-<?php the_ID(); ?>" class="entry-item gallery-post clearfix">
        <div class="entry-thumb">
        <?php if ( ! empty( $gallery_ids ) ) { ?>
            <div class="flexslider kp-post-slider">
                <ul class="slides">
                <?php foreach ( $gallery_ids as $id ) { ?>
                    <?php if ( wp_attachment_is_image( $id ) ) { ?>
                        <li>
                            <?php echo wp_get_attachment_image( $id, 'article-list-image-size' ); ?>
                        </li>
                    <?php } // endif ?>
                <?php } // endforeach ?>
                </ul>
            </div>
        <?php } // endif ?>
        </div>
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