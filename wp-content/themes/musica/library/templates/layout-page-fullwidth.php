<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
get_header();
?>

<?php kopa_breadcrumb(); ?>
<?php get_template_part('library/templates/contents'); ?>
    
<?php get_footer(); ?>