<?php

function panorama_himolde_enqueue_styles() {
	$parent_style = 'twentyfourteen-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'panorama_himolde', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'panorama_himolde_enqueue_styles' );

function panorama_himolde_post_thumbnail_caption() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
	        return;
	}

	if ( is_singular() ) {
		$post_id = get_the_ID();

		$thumb_id = get_post_thumbnail_id($post_id);
		$thumb = get_post($thumb_id);
		printf('<div class="wp-caption-text">%s</div>', $thumb->post_excerpt);
	}
}
