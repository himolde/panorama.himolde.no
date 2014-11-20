<?php

function panorama_post_thumbnail_caption() {
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
