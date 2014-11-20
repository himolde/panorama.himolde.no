<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
get_header(); ?>

<div class="col-a">
    <?php kopa_breadcrumb(); ?>
    <?php get_template_part( 'library/templates/contents' ); ?>
</div>
<!-- col-a -->
<div class="sidebar col-b widget-area-2">
    <?php if ( is_active_sidebar( $sidebars[0] ) ) {
        dynamic_sidebar( $sidebars[0] );
    } ?>
</div>
<!-- col-b -->
<div class="clear"></div>
    
<?php get_footer(); ?>