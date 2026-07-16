<?php
namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Sources extends \WP_REST_Controller {

	 public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'sources';

		// wp-json/kc/sources/
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
		$params = $request->get_params();

		$args = array(
			'post_type'      => 'sources',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC'
		);

		$sources = new \WP_Query( $args );

		foreach( $sources->posts as $source ) {
			$items[] = $this->return_item_array( $source );
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
		$authors = get_post_meta( $item->ID, '_source_authors', true );

		return [
			'title'   => $item->post_title,
			'slug'    => $item->post_name,
			'content' => apply_filters( 'the_content', $item->post_content ),
			'authors' => sanitize_text_field( $authors ),
			'description' => get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true ),
		];
	}

}
