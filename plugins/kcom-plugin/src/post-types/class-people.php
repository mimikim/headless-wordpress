<?php

namespace HEADLESS_WORDPRESS\POST_TYPE;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class People extends Post_Type {

	public function register_custom_post_type() {
		$labels = array(
			'singular' => 'Person',
			'plural' => 'People',
			'slug' => 'people'
		);

		$args = array();

		$this->register_post_type( $labels, $args );
	}

}
