<?php
// endpoint for Media

namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Media extends \WP_REST_Controller {

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'media';

		// wp-json/kc/media/
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array()
			)
		) );
	}

	public function get_items( $request ) {
		$items  = array();
		$data   = array();

		$args = array(
			'post_type'      => 'media',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC'
		);

		$archives = new \WP_Query( $args );

		foreach( $archives->posts as $archive ) {
			$items[] = $this->return_item_array( $archive );
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
		$notes = get_post_meta( $item->ID, '_media_notes', true );
		$page_url = \HEADLESS_WORDPRESS\SCHEMA\generate_page_url( $item );
		$schema_type = \HEADLESS_WORDPRESS\SCHEMA\get_schema_page_type( $item );
		$breadcrumb_parent = \HEADLESS_WORDPRESS\SCHEMA\generate_breadcrumb_parent( $item );

		return [
			'title'       => $item->post_title,
			'slug'        => $item->post_name,
			'content'     => apply_filters( 'the_content', $item->post_content ),
			'note'        => wpautop( $notes ),
			'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
			'post_type'   => $item->post_type,
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
