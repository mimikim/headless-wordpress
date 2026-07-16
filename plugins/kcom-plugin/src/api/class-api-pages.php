<?php
// for both Page and Posts

namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Pages extends \WP_REST_Controller {

	 public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'pages';

		// return all Posts from Nouns, People, Glossary
		// wp-json/kc/pages/index
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/index', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_all_posts' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array()
			)
		) );

		// return specific definition based on post id
		// wp-json/kc/pages/{id}
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/(?P<id>[0-9]+)', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'year' => array(
						'validate_callback' => function( $param, $request, $key ) {
							return is_numeric( $param );
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
			'post_type'      => array( 'post', 'page' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC'
		);

		if ( ! empty( $params ) ) {
			if ( isset( $params['id'] ) ) {
				$args['p'] = $params['id'];
			}
		}

		$pages = new \WP_Query( $args );

		foreach( $pages->posts as $page ) {
			$items[] = $this->return_item_array( $page );
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
			'type'    => $item->post_type,
			'content' => apply_filters( 'the_content', $item->post_content ),
			'post_type' => $item->post_name,
			'meta' => array(
				'name'   => $item->post_title,
				'page_url' => $page_url,
				'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
				'schema_type' => $schema_type,
				'breadcrumb' => $breadcrumb_parent
			)
		];
	}

	// gets all posts from Nouns, People, and Glossary
	public function get_all_posts( $request ) {
		$items  = array();

		$args = array(
			'post_type'      => array( 'noun', 'people', 'glossary' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC'
		);

		$pages = new \WP_Query( $args );

		foreach( $pages->posts as $page ) {
			$items[] = array(
				'title'   => $page->post_title,
				'slug'    => $page->post_name,
				'type'    => $page->post_type,
			);
		}

		return new \WP_REST_Response( $items, 200 );
	}

}
