<li id="li-post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <article id="post-<?php the_ID(); ?>" class="entry-item clearfix">
        <div class="entry-thumb">
            <?php if ( has_post_thumbnail() ) { ?>
                    <?php the_post_thumbnail( 'article-list-image-size' ); ?> 
                    <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="&#xe125;"></a></div>
            <?php } // endif ?>
        </div>

        <div class="entry-content">
            <header>
                <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <?php if ( $post->post_type != 'page' ) { ?>
                <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
            <?php } // endif ?>
            </header>
            <?php the_excerpt(); ?>
            <a class="more-link" href="<?php the_permalink(); ?>"><?php _e( 'Read more', kopa_get_domain() ); ?></a>
        </div>
        <!-- entry-content -->
    </article>
    <!-- entry-item -->
</li>