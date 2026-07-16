<?php

namespace HEADLESS_WORDPRESS\TAXONOMIES;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Century extends Taxonomies {

	public function init() {
		add_action( 'init', array( $this, 'register_custom_taxonomy' ) );
//		add_action( 'init', array( $this, 'add_default_terms' ) );
	}

	public function register_custom_taxonomy() {
		$labels = array(
			'object_type' => array( 'timeline' ),
			'plural'      => 'Centuries',
			'singular'    => 'Century',
			'slug'        => 'century'
		);

		$args = array(
			'hierarchical'  => true
		);

		$this->register_taxonomy( $labels, $args );
	}

	// inserts some predefined taxonomy terms, if empty
	public function add_default_terms() {
		$taxonomy = get_terms( 'century', array( 'hide_empty' => false ) );

		if ( empty( $taxonomy ) ) {
			$predefined_terms = $this->return_taxonomy_terms();

			foreach ( $predefined_terms as $term ) {
				if ( ! term_exists( $term['name'], 'century' ) ) {
					wp_insert_term( $term['name'], 'century', array( 'slug' => $term['code'] ) );
				}
			}
		}
	}

	// returns array of predefined terms
	private function return_taxonomy_terms() {
		return array(
			'0' => array(
				'name' => '21st Century',
				'code' => '21st-century',
			),
			'1' => array(
				'name' => '20th Century',
				'code' => '20th-century',
			),
			'2' => array(
				'name' => '19th Century',
				'code' => '19th-century',
			),
		);
	}

}
