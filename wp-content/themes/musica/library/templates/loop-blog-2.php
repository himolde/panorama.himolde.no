<div class="masonry-wrapper">
    <ul class="masonry-container entry-list transitions-enabled centered clearfix masonry" data-item-class="masonry-box">
        <?php if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                get_template_part( 'library/templates/format-index', get_post_format() );
            } // endwhile
        } // endif ?>
    </ul>
</div>

<!-- pagination -->
<?php get_template_part('library/templates/template', 'pagination'); ?>