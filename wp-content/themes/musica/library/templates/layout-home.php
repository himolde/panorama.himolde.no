<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="col-a">
    <div class="widget-area-3">
        <?php if ( is_active_sidebar( $sidebars[1] ) ) {
            dynamic_sidebar( $sidebars[1] );
        } // sidebar area 2 ?>
    </div>
    <!-- widget-area-3 -->
    <div class="widget-area-4">
        <?php if ( is_active_sidebar( $sidebars[2] ) ) {
            dynamic_sidebar( $sidebars[2] );
        } // sidebar area 3 ?>
    </div>
    <!-- widget-area-4 -->
    <div class="widget-area-5">
        <?php if ( is_active_sidebar( $sidebars[3] ) ) {
            dynamic_sidebar( $sidebars[3] );
        } // sidebar area 4 ?>
    </div>
    <!-- widget-area-5 -->
    <div class="widget-area-8">
        <?php if ( is_active_sidebar( $sidebars[4] ) ) {
            dynamic_sidebar( $sidebars[4] );
        } // sidebar area 5 ?>
    </div>
    <!-- widget-area-8 -->
    <div class="clear"></div>
    <div class="widget-area-6">
        <?php if ( is_active_sidebar( $sidebars[5] ) ) {
            dynamic_sidebar( $sidebars[5] );
        } // sidebar area 6 ?>
    </div>
    <!-- widget-area-6 -->
    <div class="widget-area-9">
        <?php if ( is_active_sidebar( $sidebars[6] ) ) {
            dynamic_sidebar( $sidebars[6] );
        } // sidebar area 7 ?>
    </div>
    <!-- widget-area-9 -->
    <div class="widget-area-10">
        <?php if ( is_active_sidebar( $sidebars[7] ) ) {
            dynamic_sidebar( $sidebars[7] );
        } // sidebar area 8 ?>
    </div>
    <!-- widget-area-10 -->
    <div class="widget-area-11">
        <?php if ( is_active_sidebar( $sidebars[8] ) ) {
            dynamic_sidebar( $sidebars[8] );
        } // sidebar area 9 ?>
    </div>
    <!-- widget-area-11 -->
    <div class="clear"></div>
    
    <div class="widget-area-12">
        <?php if ( is_active_sidebar( $sidebars[9] ) ) {
            dynamic_sidebar( $sidebars[9] );
        } // sidebar area 10 ?>
    </div>
    <!-- widget-area-12 -->
    
    <div class="widget-area-13">
        <?php if ( is_active_sidebar( $sidebars[10] ) ) {
            dynamic_sidebar( $sidebars[10] );
        } // sidebar area 11 ?>
    </div>
    <!-- widget-area-13 -->
    
    <div class="widget-area-14">
        <?php if ( is_active_sidebar( $sidebars[11] ) ) {
            dynamic_sidebar( $sidebars[11] );
        } // sidebar area 12 ?>
    </div>
    <!-- widget-area-14 -->
    
    <div class="widget-area-15">
        <?php if ( is_active_sidebar( $sidebars[12] ) ) {
            dynamic_sidebar( $sidebars[12] );
        } // sidebar area 13 ?>
    </div>
    <!-- widget-area-15 -->
    
    <div class="clear"></div>

</div>
<!-- col-a -->
<div class="sidebar col-b widget-area-2">
    <?php if ( is_active_sidebar( $sidebars[0] ) ) {
        dynamic_sidebar( $sidebars[0] );
    } // sidebar area 1 ?>
</div>
<!-- col-b -->
<div class="clear"></div>

<?php get_footer(); ?>
