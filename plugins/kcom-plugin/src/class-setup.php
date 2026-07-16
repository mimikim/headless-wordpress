<?php
/**
 * API Endpoints:
 * 		wp-json/kc/timeline/
 * 		wp-json/kc/timeline/{century}
 * 		wp-json/kc/timeline/{year}
 * 		wp-json/kc/timeline/post/{postID}

 * 		wp-json/kc/people/
 * 		wp-json/kc/people/{name}
 * 		wp-json/kc/people/{id}

 * 		wp-json/kc/glossary/
 * 		wp-json/kc/glossary/{name}
 * 		wp-json/kc/glossary/{id}
 * 
 * 		wp-json/kc/noun/
 * 		wp-json/kc/noun/{name}
 * 		wp-json/kc/noun/{id}
 * 
 * 		wp-json/kc/sources/
 * 		wp-json/kc/sources/{name}
 * 		wp-json/kc/sources/{id}
 * 
 * 		wp-json/kc/pages/
 * 		wp-json/kc/pages/{name}
 * 		wp-json/kc/pages/{id}
 *
 * 		wp-json/kc/taxonomies/
 */

namespace HEADLESS_WORDPRESS;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

// post types
require_once( 'post-types/class-post-type.php' );
require_once( 'post-types/class-glossary.php' );
require_once( 'post-types/class-media.php' );
require_once( 'post-types/class-noun.php' );
require_once( 'post-types/class-people.php' );
require_once( 'post-types/class-sources.php' );
require_once( 'post-types/class-timeline.php' );

// taxonomies
require_once( 'taxonomies/class-taxonomy.php' );
require_once( 'taxonomies/class-alpha-glossary.php' );
require_once( 'taxonomies/class-alpha-noun.php' );
require_once( 'taxonomies/class-alpha-people.php' );
require_once( 'taxonomies/class-century.php' );
require_once( 'taxonomies/class-media-type.php' );
require_once( 'taxonomies/class-years.php' );

// metaboxes
require_once( 'metaboxes/class-metabox-archives.php' );
require_once( 'metaboxes/class-metabox-media.php' );
require_once( 'metaboxes/class-metabox-sources.php' );
require_once( 'metaboxes/class-metabox-timeline.php' );

// api
require_once( 'api/class-api-archives.php' );
require_once( 'api/class-api-glossary.php' );
require_once( 'api/class-api-media.php' );
require_once( 'api/class-api-noun.php' );
require_once( 'api/class-api-pages.php' );
require_once( 'api/class-api-people.php' );
require_once( 'api/class-api-sources.php' );
require_once( 'api/class-api-taxonomy.php' );
require_once( 'api/class-api-timeline.php' );
require_once( 'api/class-api-timeline-footer.php' );

require_once( 'helpers.php' );

class Setup {

	public function __construct() {
		$this->create_instances();
		add_filter( 'posts_where', array( $this, 'alpha_filter' ), 10, 2 );
	}

	public function create_instances() {
		new POST_TYPE\Post_Type();
		new POST_TYPE\Glossary();
		new POST_TYPE\Media();
		new POST_TYPE\Noun();
		new POST_TYPE\People();
		new POST_TYPE\Sources();
		new POST_TYPE\Timeline();

		new TAXONOMIES\Alpha_Glossary();
		new TAXONOMIES\Alpha_Noun();
		new TAXONOMIES\Alpha_People();
		new TAXONOMIES\Century();
		new TAXONOMIES\Media_Type();
		new TAXONOMIES\Years();
//		new TAXONOMIES\Alpha_Kore();

		new METABOX\Metabox_Archives();
		new METABOX\Metabox_Media();
		new METABOX\Metabox_Sources();
		new METABOX\Metabox_Timeline();

		new API\API_Archives();
		new API\API_Glossary();
		new API\API_Media();
		new API\API_Noun();
		new API\API_Pages();
		new API\API_People();
		new API\API_Sources();
		new API\API_Taxonomy();
		new API\API_Timeline();
		new API\API_Timeline_Footer();
	}

	// https://wordpress.stackexchange.com/questions/298888/wp-query-where-title-begins-with-a-specific-letter/298913#298913
	function alpha_filter( $where, $query ) {
		global $wpdb;

		$starts_with = esc_sql( $query->get( 'starts_with' ) );

		if ( $starts_with ) {
			$where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
		}

		return $where;
	}

}
