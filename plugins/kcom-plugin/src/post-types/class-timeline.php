<?php

namespace HEADLESS_WORDPRESS\POST_TYPE;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Timeline extends Post_Type {

	public function register_custom_post_type() {
		$labels = array(
			'singular' => 'Timeline',
			'plural' => 'Timelines',
			'slug' => 'timeline'
		);

		$args = array();

		$this->register_post_type( $labels, $args );
	}
}
