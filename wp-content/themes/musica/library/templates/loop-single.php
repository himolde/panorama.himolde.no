<?php if ( have_posts() ) { 
    while ( have_posts() ) {
        the_post(); 

        get_template_part( 'library/templates/format-single', get_post_format() );
?>

    <?php if ( 'show' == get_option( 'kopa_theme_options_post_about_author', 'show' ) ) { ?>
        <div class="about-author clearfix">
            <div class="about-author-detail clearfix">
                <a class="avatar-thumb" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta('ID'), 155 ); ?></a>
                <div class="author-content">
                    <header>                              
                        <a class="author-name" href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author_meta('display_name'); ?></a>
                    </header>
                    <p><?php the_author_meta( 'description' ); ?></p>
                    <p><strong><?php _e( 'View all contributions by', kopa_get_domain() ); ?></strong> <a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author_meta( 'display_name' ); ?></a></p>
                    <?php if ( get_the_author_meta( 'user_url' ) ) { ?>
                        <p><strong><?php _e( 'Website:', kopa_get_domain() ); ?></strong> <a href="<?php the_author_meta( 'user_url' ); ?>"><?php the_author_meta( 'user_url' ); ?></a></p>
                    <?php } ?>

                    <?php 
                    $author_facebook_url = get_the_author_meta( 'facebook' );
                    $author_twitter_url  = get_the_author_meta( 'twitter' );
                    $author_feed_url     = get_the_author_meta( 'feedurl' );
                    $author_gplus_url    = get_the_author_meta( 'google-plus' );
                    $author_flickr_url   = get_the_author_meta( 'flickr' );

                    if ( $author_facebook_url || $author_twitter_url || $author_feed_url || $author_gplus_url || $author_flickr_url ) {
                    ?>
                    <ul class="social-link clearfix">
                        <li><?php _e( 'Social links:', kopa_get_domain() ); ?></li>

                        <!-- facebook -->
                        <?php if ( ! empty( $author_facebook_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_facebook_url ); ?>" data-icon="&#xe1c3;"></a></li>
                        <?php } // endif ?>

                        <!-- twitter -->
                        <?php if ( ! empty( $author_twitter_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_twitter_url ); ?>" data-icon="&#xe1c7;"></a></li>
                        <?php } // endif ?>
                        
                        <!-- feed -->
                        <?php if ( ! empty( $author_feed_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_feed_url ); ?>" data-icon="&#xe1ca;"></a></li>
                        <?php } // endif ?>
                        
                        <!-- google plus -->
                        <?php if ( ! empty( $author_gplus_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_gplus_url ); ?>" data-icon="&#xe1be;"></a></li>
                        <?php } // endif ?>
                        
                        <!-- flickr -->
                        <?php if ( ! empty( $author_flickr_url ) ) { ?>
                        <li><a href="<?php echo esc_url( $author_flickr_url ); ?>" data-icon="&#xe1d3;"></a></li>
                        <?php } // endif ?>
                    </ul>
                    <?php } // endif ?>
                    <!-- social-link -->
                </div><!--author-content-->
            </div>
        </div><!--about-author-->
    <?php } // endif ?>

    <?php kopa_get_related_articles(); ?>

    <?php kopa_facebook_comments(); ?>

    <?php comments_template(); ?>

<?php } // endwhile
} // endif


