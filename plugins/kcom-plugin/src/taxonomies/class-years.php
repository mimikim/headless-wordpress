<?php

namespace HEADLESS_WORDPRESS\TAXONOMIES;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Years extends Taxonomies {

	public function init() {
		add_action( 'init', array( $this, 'register_custom_taxonomy' ) );
//		add_action( 'init', array( $this, 'add_default_terms' ) );
	}

	public function register_custom_taxonomy() {
		$labels = array(
			'object_type' => array( 'timeline' ),
			'plural'      => 'Years',
			'singular'    => 'Year',
			'slug'        => 'years'
		);

		$args = array(
			'hierarchical'  => true
		);

		$this->register_taxonomy( $labels, $args );
	}

	// inserts some predefined taxonomy terms, if empty
	public function add_default_terms() {
		$taxonomy = get_terms( 'years', array( 'hide_empty' => false ) );

		if ( empty( $taxonomy ) ) {
			$predefined_terms = $this->return_taxonomy_terms();

			foreach ( $predefined_terms as $term ) {
				if ( ! term_exists( $term['name'], 'years' ) ) {
					wp_insert_term( $term['name'], 'years', array( 'slug' => $term['code'] ) );
				}
			}
		}
	}

	// returns array of predefined terms
	private function return_taxonomy_terms() {
		$years = array();

		// 1800-2022
		for ( $y = 1800; $y <= 2022; $y++ ) {
			$years[] = array(
				'name' => $y,
				'code' => $y
			);
		}

		return $years;
	}

}
