<ul class="entry-list clearfix">
    <?php if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            get_template_part( 'library/templates/format-index', get_post_format() );
        } // endwhile
    } // endif ?>
</ul>

<!-- pagination -->
<?php get_template_part('library/templates/template', 'pagination'); ?>