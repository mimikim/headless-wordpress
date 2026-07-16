<?php

namespace HEADLESS_WORDPRESS\METABOX;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Metabox_Sources {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'set_box' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function set_box() {
		add_meta_box(
			'sources_author',
			__( 'Authors' ),
			array( $this, 'set_html' ),
			'sources',
			'side',
			'high'
		);
	}

	// display metabox
	public function set_html( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'sources_authors_nonce' );

		$authors = get_post_meta( $post->ID, '_source_authors', true );

		ob_start();
		?>

		<style>
			.metabox-label {
				color: #666;
				display: block;
				font-size: 13px;
				font-style: italic;
				line-height: 1.5;
				margin-bottom: 1em;
				margin-top: 1em;
			}
		</style>
		<label class="metabox-label">Author(s)</label>

		<textarea name="sources-authors" id="sources-authors" rows="3" cols="20"><?php echo sanitize_text_field( $authors ); ?></textarea>

		<?php
		$html = ob_get_clean();
		echo $html;
	}

	// assembles & saves meta box data
	public function save() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ){
			return;
		}

		$nonce = $_POST['sources_authors_nonce'];

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		$authors = sanitize_text_field( $_POST['sources-authors'] );

		update_post_meta( $post->ID, '_source_authors', $authors );
	}

}
