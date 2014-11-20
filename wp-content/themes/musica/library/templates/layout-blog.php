<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="col-a">
    <?php if ( is_active_sidebar( $sidebars[1] ) ) { ?>
    <div class="widget-area-3">
        <?php dynamic_sidebar( $sidebars[1] ); ?>
    </div>
    <?php } ?>

    <?php kopa_breadcrumb(); ?>
    <?php get_template_part('library/templates/contents'); ?>

    <?php if ( is_active_sidebar( $sidebars[2] ) ) { ?>
    <div class="widget-area-7">
        <?php dynamic_sidebar( $sidebars[2] ); ?>
    </div>
    <?php } ?>
    <!-- widget-area-7 -->
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