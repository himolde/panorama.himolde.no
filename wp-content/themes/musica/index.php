<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div id="main-content">
    <div class="wrapper clearfix">
        <div class="col-a">
            <?php kopa_breadcrumb(); ?>
            <?php get_template_part('library/templates/contents'); ?>
        </div>
        <!-- col-a -->
        <div class="sidebar col-b widget-area-2">
            <?php if ( is_active_sidebar( $sidebars[0] ) ) {
                dynamic_sidebar( $sidebars[0] );
            } ?>
        </div>
        <!-- col-b -->
        <div class="clear"></div>
    </div>
    <!-- wrapper -->
    <div class="wrapper">
        <div class="widget-area-7">
            <?php if ( is_active_sidebar( $sidebars[1] ) ) {
                dynamic_sidebar( $sidebars[1] );
            } ?> 
        </div>
    <!-- widget-area-7 -->  
    </div>
      
</div>
<!-- main-content -->

<?php get_footer(); ?>