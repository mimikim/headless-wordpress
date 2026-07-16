<?php
// returns years organized by century (for rendering timeline-footer.hbs)
namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Timeline_Footer extends \WP_REST_Controller {

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'timeline-footer';

		// wp-json/kc/timeline-footer/
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
		$data = array(
			array(
				'name' => '19th Century',
				'slug' => '19th-century',
				'years' => []
			),
			array(
				'name' => '20th Century',
				'slug' => '20th-century',
				'years' => []
			),
			array(
				'name' => '21st Century',
				'slug' => '21st-century',
				'years' => []
			),
		);

		// 19th century
		$nineteenth = get_terms([
			'taxonomy' => array( 'years' ),
			'hide_empty' => false,
			'number' => 100
		]);

		// 20th century
		$twentieth = get_terms([
			'taxonomy' => array( 'years' ),
			'hide_empty' => false,
			'number' => 100,
			'offset' => 100
		]);

		// 21st century
		$twentyfirst = get_terms([
			'taxonomy' => array( 'years' ),
			'hide_empty' => false,
			'number' => 100,
			'offset' => 200
		]);

		foreach( $nineteenth as $term ) {
			$data[0]['years'][] = $this->return_item_array( $term );
		}

		foreach( $twentieth as $term ) {
			$data[1]['years'][] = $this->return_item_array( $term );
		}

		foreach( $twentyfirst as $term ) {
			$data[2]['years'][] = $this->return_item_array( $term );
		}

		return new \WP_REST_Response( $data, 200 );
	}

	public function get_items_permissions_check( $request ) {
		return true;
	}

	// returns assembled item array
	private function return_item_array( $item ) {
		return [
			'title' => $item->name,
			'count' => $item->count,
		];
	}

}
