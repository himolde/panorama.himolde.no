<?php
/*------------------UPGRADE TO PRO VERSION------------------------*/
$xml = kopa_get_theme_info(KOPA_UPDATE_TIMEOUT);

/*------------------LATEST ITEMS UPSELL---------------------------*/
$db_latest_cache_field = 'kopa-latest-upsell-cache-' . kopa_get_domain();
$db_latest_cache_field_last_updated = 'kopa-latest-upsell-cache-last-updated-' . kopa_get_domain();
$last = get_option($db_latest_cache_field_last_updated);
$now = time();

if (!$last || (( $now - $last ) > KOPA_UPDATE_TIMEOUT)) {

    $cache = wp_remote_get( KOPA_LATEST_UPSELL_URL );

    if ( ! is_wp_error( $cache ) && 200 == $cache['response']['code'] ) {
        $cache = wp_remote_retrieve_body( $cache );
    } else {
        $cache = '';
    }

    if ($cache) {
        update_option($db_latest_cache_field, $cache);
        update_option($db_latest_cache_field_last_updated, time());
    }

    $latest_upsell_data = get_option($db_latest_cache_field);
} else {
    $latest_upsell_data = get_option($db_latest_cache_field);
}

$latest_xml = simplexml_load_string( $latest_upsell_data );

if ( is_object( $latest_xml ) && ! empty( $latest_xml ) && is_object( $xml ) && ! empty( $xml ) ) {
    if ( property_exists( $xml, 'upsell' ) ) {
        $content      = $xml->upsell;
    }
    $menu_items   = $latest_xml->menu->item;
    $info         = $latest_xml->latest->info;
    $latest_items = $latest_xml->latest->item;
?>

   <div class=" kopa-sidebar-manager clearfix" id="kopa-admin-wrapper">  
    <div class="kopa-content ">
        <div class="kopa-page-header clearfix">
            <div class="pull-left">
                <h4>Musica Wordpress Responsive Theme</h4>
            </div>
            <div class="pull-right">
                <div class="kopa-copyrights">
                    <span>Visit author URL: </span><a href="http://kopatheme.com">http://kopatheme.com</a>
                </div><!--="kopa-copyrights-->
            </div>
        </div><!--kopa-page-header-->
        <div id="template-home" class="kopa-content-box">
            <div class="kopa-box-head">
                <ul class="nav clearfix">
					<?php foreach ( $menu_items as $item ) { ?>
						<li><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></li>
					<?php } ?>
				</ul>
            </div><!--kopa-box-head-->
            <div class="kopa-box-body clearfix"> 
                <!-- Jumbotron -->
                <?php if ( isset( $content ) && ! empty( $content ) ) { ?>
				  <div class="jumbotron">
					<h1><?php echo $content->heading; ?></h1>
					<p class="lead"><?php echo $content->tagline; ?></p>
					<a class="btn btn-large btn-success" href="<?php echo $content->button->url; ?>"><?php echo $content->button->title; ?></a>
				  </div>
                <?php } ?>
				  <hr>
				  <!-- Example row of columns -->
				  <div class="row-fluid">
					<h3 class="text-center"><?php echo $info; ?></h3>
				  </div>

				  <hr>

				  <div class="row-fluid">
                    <?php $item_index = 1; ?>
					<?php foreach ( $latest_items as $item ) { ?>
					<div class="span3">
					  <h4><?php echo $item->title; ?></h4>
					  <p><a href="<?php echo $item->url; ?>"><img class="theme-thumb" src="<?php echo $item->image; ?>"></a></p>
					  <div class="clearfix">
						<a class="btn-livedemo" href="<?php echo $item->livedemo; ?>">Live Demo &raquo;</a>
						<a class="btn-buynow" href="<?php echo $item->url; ?>">Buy Now &raquo;</a>
					  </div>
					</div>
                    <?php if ( $item_index % 4 == 0 ) {
                        echo '</div><div class="row-fluid mt20">';
                    } 
                    $item_index++;
                    ?>
					<?php } ?>
				  </div>
				  <hr>

				  <div class="footer">
					<p><?php printf( __('Copyrights. &copy; %s by KOPASOFT', kopa_get_domain()), date('Y') ); ?></p>
				  </div>

            </div><!--kopa-box-body-->           
        </div><!--kopa-content-box-->       
    </div><!--kopa-content-->
</div>

<?php 
} // endif ! empty( $xml )