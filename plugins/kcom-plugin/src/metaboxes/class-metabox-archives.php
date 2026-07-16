<?php
// metabox for "Archives" or posts

namespace HEADLESS_WORDPRESS\METABOX;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Metabox_Archives {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'set_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function set_boxes() {
		// authors
		add_meta_box(
			'archive_authors',
			__( 'Authors', HEADLESS_WORDPRESS_TEXT_DOMAIN ),
			array( $this, 'set_html' ),
			'post',
			'side',
			'high'
		);

		// links

		// additional notes
		add_meta_box(
			'archive_additional_notes',
			__( 'Additional Notes', HEADLESS_WORDPRESS_TEXT_DOMAIN ),
			array( $this, 'set_html_notes' ),
			'post',
			'normal',
			'default'
		);
	}

	public function set_html( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'archive_authors_nonce' );
		$authors = get_post_meta( $post->ID, '_archive_authors', true );
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

		<textarea name="archive-authors" id="archive-authors" rows="3" cols="20"><?php echo sanitize_textarea_field( $authors ); ?></textarea>

		<?php
		$html = ob_get_clean();
		echo $html;
	}

	public function set_html_notes( $post ) {
		$notes = get_post_meta( $post->ID, '_archive_notes', true );
		wp_editor( $notes, 'archive-notes', array(
			'textarea_rows' => 10
		) );
	}

	public function save() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ){
			return;
		}

		$nonce = $_POST['archive_authors_nonce'];

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		$authors = sanitize_textarea_field( $_POST['archive-authors'] );
		$notes = wp_kses_post( $_POST['archive-notes'] );

		update_post_meta( $post->ID, '_archive_authors', $authors );
		update_post_meta( $post->ID, '_archive_notes', $notes );
	}

}
