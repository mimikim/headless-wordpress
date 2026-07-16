<?php

namespace HEADLESS_WORDPRESS\TAXONOMIES;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Media_Type extends Taxonomies {

	public function init() {
		add_action( 'init', array( $this, 'register_custom_taxonomy' ) );
	}

	public function register_custom_taxonomy() {
		$labels = array(
			'object_type' => array( 'media' ),
			'plural'      => 'Types',
			'singular'    => 'Type',
			'slug'        => 'media-type'
		);

		$args = array(
			'hierarchical'  => true
		);

		$this->register_taxonomy( $labels, $args );
	}

}
