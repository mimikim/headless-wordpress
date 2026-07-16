<?php

namespace HEADLESS_WORDPRESS\POST_TYPE;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Media extends Post_Type {

	public function register_custom_post_type() {
		$labels = array(
			'singular' => 'Media',
			'plural' => 'Medias',
			'slug' => 'media'
		);

		$args = array();

		$this->register_post_type( $labels, $args );
	}

}
