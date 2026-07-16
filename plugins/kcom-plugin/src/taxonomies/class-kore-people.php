<?php
// for PEOPLE post type
// ㄱ ㄴ ㄷ ㄹ ㅁ ㅂ ㅅ ㅇ ㅈ ㅊ ㅋ ㅌ ㅍ ㅎ

namespace HEADLESS_WORDPRESS\TAXONOMIES;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Alpha_Kore extends Taxonomies {

	public function init() {
		add_action( 'init', array( $this, 'register_custom_taxonomy' ) );
//		add_action( 'init', array( $this, 'add_default_terms' ) );
	}

	public function register_custom_taxonomy() {
		$labels = array(
			'object_type' => array( 'people' ),
			'plural'      => 'Hangul',
			'singular'    => 'Hangul',
			'slug'        => 'alpha_kore'
		);

		$args = array(
			'hierarchical'  => true
		);

		$this->register_taxonomy( $labels, $args );
	}

	// inserts some predefined taxonomy terms, if empty
	public function add_default_terms() {
		$taxonomy = get_terms( 'alpha_kore', array( 'hide_empty' => false ) );

		if ( empty( $taxonomy ) ) {
			$predefined_terms = $this->return_taxonomy_terms();

			foreach ( $predefined_terms as $term ) {
				if ( ! term_exists( $term['name'], 'alpha_kore' ) ) {
					wp_insert_term( $term['name'], 'alpha_kore', array( 'slug' => $term['code'] ) );
				}
			}

		}
	}

	// returns array of predefined terms
	private function return_taxonomy_terms() {
		return array(
			'0' => array(
				'name' => 'ㅎ',
				'code' => 'ㅎ',
			),
			'1' => array(
				'name' => 'ㅍ',
				'code' => 'ㅍ',
			),
			'2' => array(
				'name' => 'ㅌ',
				'code' => 'ㅌ',
			),
			'3' => array(
				'name' => 'ㅋ',
				'code' => 'ㅋ',
			),
			'4' => array(
				'name' => 'ㅊ',
				'code' => 'ㅊ',
			),
			'5' => array(
				'name' => 'ㅈ',
				'code' => 'ㅈ',
			),
			'6' => array(
				'name' => 'ㅇ',
				'code' => 'ㅇ',
			),
			'7' => array(
				'name' => 'ㅅ',
				'code' => 'ㅅ',
			),
			'8' => array(
				'name' => 'ㅂ',
				'code' => 'ㅂ',
			),
			'9' => array(
				'name' => 'ㅁ',
				'code' => 'ㅁ',
			),
			'10' => array(
				'name' => 'ㄹ',
				'code' => 'ㄹ',
			),
			'11' => array(
				'name' => 'ㄷ',
				'code' => 'ㄷ',
			),
			'12' => array(
				'name' => 'ㄴ',
				'code' => 'ㄴ',
			),
			'13' => array(
				'name' => 'ㄱ',
				'code' => 'ㄱ',
			),
		);
	}
}
