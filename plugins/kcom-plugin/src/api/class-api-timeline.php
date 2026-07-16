<?php
/**
* https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
*/

namespace HEADLESS_WORDPRESS\API;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class API_Timeline extends \WP_REST_Controller {

	 public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$base = 'timeline';

		// returns all Timeline posts
		// wp-json/kc/timeline/
//		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base, array(
//			array(
//				'methods'             => \WP_REST_Server::READABLE,
//				'callback'            => array( $this, 'get_items' ),
//				'permission_callback' => array( $this, 'get_items_permissions_check' ),
//				'args'                => array()
//			)
//		) );

		// return timeline posts only from a certain century
		// wp-json/kc/timeline/{century}
//		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/(?P<century>[0-9]{2}[a-zA-Z-]{3}[centuryCENTURY]+)', array(
//			array(
//				'methods'             => \WP_REST_Server::READABLE,
//				'callback'            => array( $this, 'get_items' ),
//				'permission_callback' => array( $this, 'get_items_permissions_check' ),
//				'args'                => array(
//					'century' => array(
//						'validate_callback' => function( $param, $request, $key ) {
//							return is_string( $param );
//						}
//					)
//				)
//			)
//		) );

		// return timeline posts from only a certain year
		// wp-json/kc/timeline/{year}
		register_rest_route( HEADLESS_WORDPRESS_API_NAMESPACE, '/' . $base . '/(?P<year>[0-9]{4})', array(
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
					// add sorting ASC and DESC by date
				)
			)
		) );

	}

	public function get_items( $request ) {
		$items  = array();
		$data   = array();
		$params = $request->get_params();

		$args = array(
			'post_type'      => 'timeline',
			'posts_per_page' => -1,
			'meta_key'       => '_date_of_event',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC'
		);

		if ( ! empty( $params ) ) {
//			if ( isset( $params['century'] ) ) {
//				$args['tax_query'][] = array(
//					'taxonomy' => 'century',
//					'field'    => 'slug',
//					'terms'    => $params['century']
//				);
//			}

			if ( isset( $params['year'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'years',
					'field'    => 'slug',
					'terms'    => strval( $params['year'] )
				);
			}
		}

		$timelines = new \WP_Query( $args );

		foreach( $timelines->posts as $timeline ) {
			$items[] = $this->return_item_array( $timeline );
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
//		$century     = $this->return_taxonomy_array( $item->ID, 'century' );
//		$years       = $this->return_taxonomy_array( $item->ID, 'year' );
		$date        = get_post_meta( $item->ID, '_date_of_event', true );
		$description = get_post_meta( $item->ID, '_yoast_wpseo_metadesc', true );

		return [
			'title'       => $item->post_title,
			'slug'        => $item->post_name,
			'date'        => $date,
			'content'     => apply_filters( 'the_content', $item->post_content ),
			'description' => $description,
		];
	}

	// returns taxonomy array
	private function return_taxonomy_array( $ID, $taxonomy_name ) {
		$arr        = get_the_terms( $ID, $taxonomy_name );
		$return_arr = array();

		foreach( $arr as $term ) {
			$return_arr[] = array(
				'name'     => $term->name,
				'slug'     => $term->slug,
				'taxonomy' => $term->taxonomy,
			);
		}
		return $return_arr;
	}

}
