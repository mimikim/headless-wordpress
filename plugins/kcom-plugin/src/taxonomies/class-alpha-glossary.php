<?php

namespace HEADLESS_WORDPRESS\TAXONOMIES;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Alpha_Glossary extends Taxonomies {

	public function init() {
		add_action( 'init', array( $this, 'register_custom_taxonomy' ) );
		add_action( 'init', array( $this, 'add_default_terms' ) );
	}

	public function register_custom_taxonomy() {
		$labels = array(
			'object_type' => array( 'glossary' ),
			'plural'      => 'Alpha',
			'singular'    => 'Alpha',
			'slug'        => 'alpha_glossary'
		);

		$args = array(
			'hierarchical'  => true
		);

		$this->register_taxonomy( $labels, $args );
	}

	// inserts some predefined taxonomy terms, if empty
	public function add_default_terms() {
		$taxonomy = get_terms( 'alpha_glossary', array( 'hide_empty' => false ) );

		if ( empty( $taxonomy ) ) {
			$predefined_terms = $this->return_taxonomy_terms();

			foreach ( $predefined_terms as $term ) {
				if ( ! term_exists( $term['name'], 'alpha_glossary' ) ) {
					wp_insert_term( $term['name'], 'alpha_glossary', array( 'slug' => $term['code'] ) );
				}
			}

		}
	}

	// returns array of predefined terms
	private function return_taxonomy_terms() {
		return array(
			'0' => array(
				'name' => 'Z',
				'code' => 'z',
			),
			'1' => array(
				'name' => 'Y',
				'code' => 'y',
			),
			'2' => array(
				'name' => 'X',
				'code' => 'x',
			),
			'3' => array(
				'name' => 'W',
				'code' => 'w',
			),
			'4' => array(
				'name' => 'V',
				'code' => 'v',
			),
			'5' => array(
				'name' => 'U',
				'code' => 'u',
			),
			'6' => array(
				'name' => 'T',
				'code' => 't',
			),
			'7' => array(
				'name' => 'S',
				'code' => 's',
			),
			'8' => array(
				'name' => 'R',
				'code' => 'r',
			),
			'9' => array(
				'name' => 'Q',
				'code' => 'q',
			),
			'10' => array(
				'name' => 'P',
				'code' => 'p',
			),
			'11' => array(
				'name' => 'O',
				'code' => 'o',
			),
			'12' => array(
				'name' => 'N',
				'code' => 'n',
			),
			'13' => array(
				'name' => 'M',
				'code' => 'm',
			),
			'14' => array(
				'name' => 'L',
				'code' => 'l',
			),
			'15' => array(
				'name' => 'K',
				'code' => 'k',
			),
			'16' => array(
				'name' => 'J',
				'code' => 'j',
			),
			'17' => array(
				'name' => 'I',
				'code' => 'i',
			),
			'18' => array(
				'name' => 'H',
				'code' => 'h',
			),
			'19' => array(
				'name' => 'G',
				'code' => 'g',
			),
			'20' => array(
				'name' => 'F',
				'code' => 'f',
			),
			'21' => array(
				'name' => 'E',
				'code' => 'e',
			),
			'22' => array(
				'name' => 'D',
				'code' => 'd',
			),
			'23' => array(
				'name' => 'C',
				'code' => 'c',
			),
			'24' => array(
				'name' => 'B',
				'code' => 'b',
			),
			'25' => array(
				'name' => 'A',
				'code' => 'a',
			),
		);
	}

}
