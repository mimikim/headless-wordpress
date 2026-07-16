<?php
// schema set up functions
namespace HEADLESS_WORDPRESS\SCHEMA;

// return @type for schema
function get_schema_page_type( $item ) {
	$page_id = $item->ID;
	$post_type = get_post_type( $page_id );
	$type = 'WebPage';

	switch ( $page_id ) {
		// archives page
		case 192:
		// glossary page
		case 8:
		// media page
		case 200:
		// people page
		case 10:
		// noun page
		case 9:
		// timeline page
		case 7:

		// See All People
		case 231:
		// See All Noun
		case 232:
		// See All Glossary
		case 233:
			$type = 'CollectionPage';
			break;

		// about page
		case 3:
			$type = 'AboutPage';
			break;

		default:
			break;
	}

	// posts inside post types are itemPage
	switch ( $post_type ) {
		case 'glossary':
		case 'media':
		case 'noun':
		case 'people':
			$type = 'ItemPage';
			break;
	}

	// all Taxonomy Term pages are CollectionPage
	if ( $item->taxonomy ) {
		$type = 'CollectionPage';
	}

	return $type;
}

function generate_page_url( $item ) {
	$page_type = get_schema_page_type( $item );
	$post_type = get_post_type( $item->ID );

	// change post type for SEE ALL pages
	if ( $item->ID === 231 ) {
		$post_type = 'people';
	}
	else if ($item->ID === 232 ) {
		$post_type = 'noun';
	}
	else if ( $item->ID === 233 ) {
		$post_type = 'glossary';
	}

	if ( $item->post_name === 'headless-wordpress' ) {
		$url = HEADLESS_WORDPRESS_SITE_URL;
	}
	else if ( $page_type === 'ItemPage' ) {
		$url = HEADLESS_WORDPRESS_SITE_URL . $post_type  . '/' .$item->post_name . '/';
	}

	// Alpha "SEE ALL" pages
	else if ( $item->ID === 231 || $item->ID === 232 || $item->ID === 233 ) {
		$url = HEADLESS_WORDPRESS_SITE_URL . $post_type  . '/alpha/';
	}

	// all Taxonomy Term pages
	else if ( $item->taxonomy ) {
		$taxonomy = get_taxonomy( $item->taxonomy );
		$post_type = $taxonomy->object_type[0];
		$url = HEADLESS_WORDPRESS_SITE_URL . $post_type . '/' . $item->slug . '/';
	}

	else {
		$url = HEADLESS_WORDPRESS_SITE_URL . $item->post_name . '/';
	}

	return $url;
}

function generate_breadcrumb_parent( $item ) {
	$page_type = get_schema_page_type( $item );
	$post_type = get_post_type( $item->ID );
	$tmp = [];

	switch ( $post_type ) {
		case 'glossary':
			$tmp = get_the_title( 8 );
			break;
		case 'media':
			$tmp = get_the_title( 200 );
			break;
		case 'people':
			$tmp = get_the_title( 10 );
			break;
		case 'noun':
			$tmp = get_the_title( 9 );
			break;
		default:
			break;
	}

	// generates info for parent page of ItemPage post
	if ( $page_type === 'ItemPage' ) {
		return [
			'name' => $tmp,
			'url' => HEADLESS_WORDPRESS_SITE_URL . $post_type . '/'
		];
	}

	return [];
}
