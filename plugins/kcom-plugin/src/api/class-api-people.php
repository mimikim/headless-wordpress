<?php
namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_People extends \WP_REST_Controller {

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'people';

		// return all people
		// wp-json/kc/people
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array()
			)
		) );

		// return all people whose name matches letter
		// wp-json/kc/people/{alpha}
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/(?P<alpha>[-a-zA-Z]{1}+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'alpha' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_string( $param );
						}
					)
				)
			)
		) );
	}

	public function get_items( $request ) {
		$items  = array();
		$data   = array();
		$params = $request->get_params();

		$args = array(
			'post_type'      => 'people',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		if ( ! empty( $params ) ) {
			if ( isset( $params['alpha'] ) ) {
				$args['starts_with'] = $params['alpha'];
			}
		}

		$people = new \WP_Query( $args );

		foreach( $people->posts as $person ) {
			$items[] = $this->return_item_array( $person );
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
		$page_url = \HEADLESS_WORDPRESS\SCHEMA\generate_page_url( $item );
		$schema_type = \HEADLESS_WORDPRESS\SCHEMA\get_schema_page_type( $item );
		$breadcrumb_parent = \HEADLESS_WORDPRESS\SCHEMA\generate_breadcrumb_parent( $item );

		return [
			'title'   => $item->post_title,
			'slug'    => $item->post_name,
			'content' => apply_filters( 'the_content', $item->post_content ),
			'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
			'post_type' => $item->post_type,
			'meta' => array(
				'name'   => $item->post_title,
				'page_url' => $page_url,
				'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
				'schema_type' => $schema_type,
				'breadcrumb' => $breadcrumb_parent
			)
		];
	}
}
