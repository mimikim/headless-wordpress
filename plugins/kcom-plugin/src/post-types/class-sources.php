<?php

namespace HEADLESS_WORDPRESS\POST_TYPE;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Sources extends Post_Type {

	public function register_custom_post_type() {
		$labels = array(
			'singular' => 'Source',
			'plural' => 'Sources',
			'slug' => 'sources'
		);

		$args = array();

		$this->register_post_type( $labels, $args );
	}

}
