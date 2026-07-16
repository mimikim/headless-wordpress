<?php
// returns every custom taxonomy term for passed custom post type
namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Taxonomy extends \WP_REST_Controller {

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'taxonomy';

		// wp-json/kc/taxonomy/{post-type}
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/(?P<type>[a-zA-Z]+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'type' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param );
						}
					),
				)
			)
		) );

		// returns all hangul terms for People or Noun
		// wp-json/kc/hangul/{post_type}
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/hangul/(?P<post_type>[a-zA-Z]+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_hangul' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'post_type' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param );
						}
					),
				),
				)
			)
		);
	}

	public function get_items( $request ) {
		$items  = array();
		$data   = array();
		$terms  = array();
		$params = $request->get_params();

		if ( ! empty( $params ) ) {

			// people post type combines alpha and kore taxonomy

			if ( isset( $params['type'] ) && $params['type'] === 'timeline' ) {
				$terms = get_terms([
					'taxonomy' => array( 'years' ),
					'hide_empty' => false
				]);
			}

			if ( isset( $params['type'] ) && $params['type'] === 'people' ) {
				$terms = get_terms([
					'taxonomy' => array( 'alpha_people' ),
					'hide_empty' => false,
				]);
			}

			if ( isset( $params['type'] ) && $params['type'] === 'noun' ) {
				$terms = get_terms([
					'taxonomy' => array( 'alpha_noun' ),
					'hide_empty' => false,
				]);
			}

			if ( isset( $params['type'] ) && $params['type'] === 'glossary' ) {
				$terms = get_terms([
					'taxonomy' => array( 'alpha_glossary' ),
					'hide_empty' => false,
				]);
			}
		}

		foreach( $terms as $term ) {
			$items[] = $this->return_item_array( $term );
		}

		foreach( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[]   = $this->prepare_response_for_collection( $itemdata );
		}

		return new \WP_REST_Response( $data, 200 );
	}

	public function get_hangul( $request ) {
		$items  = array();
		$data   = array();
		$terms  = array();
		$params = $request->get_params();

		if ( ! empty( $params ) ) {
			if ( isset( $params['post_type'] ) && $params['post_type'] === 'people' ) {
				$terms = get_terms([
					'taxonomy' => array( 'alpha_kore' ),
					'hide_empty' => false,
				]);
			}

			if ( isset( $params['post_type'] ) && $params['post_type'] === 'noun' ) {
				$terms = get_terms([
					'taxonomy' => array( 'kore_noun' ),
					'hide_empty' => false,
				]);
			}
		}

		foreach( $terms as $term ) {
			$items[] = $this->return_item_array( $term );
		}

		foreach( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[]   = $this->prepare_response_for_collection( $itemdata );
		}

		return new \WP_REST_Response( $data, 200 );
	}

	// checks if requester has permission
	public function get_items_permissions_check( $request ) {
		return true;
	}

	// prepare item for rest response
	public function prepare_item_for_response( $item, $request ) {
		return $item;
	}

	// returns assembled item array
	private function return_item_array( $item ) {
		$description = \WPSEO_Taxonomy_Meta::get_term_meta( $item->term_id, $item->taxonomy, 'desc' );
		$taxonomy = get_taxonomy( $item->taxonomy );
		$post_type = $taxonomy->object_type[0];

		$page_url = \HEADLESS_WORDPRESS\SCHEMA\generate_page_url( $item );
		$schema_type = \HEADLESS_WORDPRESS\SCHEMA\get_schema_page_type( $item );
		$breadcrumb_parent = \HEADLESS_WORDPRESS\SCHEMA\generate_breadcrumb_parent( $item );

		return [
			'title'       => $item->name,
			'slug'        => $item->slug,
			'taxonomy'    => $item->taxonomy,
			'count'       => $item->count,
			'description' => $description,
			'post_type'   => $post_type,
			'meta' => array(
				'name'   => ucfirst( $post_type ) . ' (' . $item->name . ')',
				'page_url' => $page_url,
				'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
				'schema_type' => $schema_type,
				'breadcrumb' => [
					'name' => ucfirst( $post_type ),
					'url' => HEADLESS_WORDPRESS_SITE_URL . $post_type . '/'
				]
			)
		];
	}

}
