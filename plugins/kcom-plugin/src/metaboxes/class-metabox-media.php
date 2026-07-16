<?php
namespace HEADLESS_WORDPRESS\METABOX;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Metabox_Media {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'set_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function set_boxes() {
		add_meta_box(
			'media_notes',
			__( 'Notes', HEADLESS_WORDPRESS_TEXT_DOMAIN ),
			array( $this, 'set_html' ),
			'media',
			'side',
			'high'
		);
	}

	public function set_html( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'media_notes_nonce' );
		$notes = get_post_meta( $post->ID, '_media_notes', true );
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
		<label class="metabox-label">Notes</label>

		<textarea name="media_notes" id="media_notes" rows="3" cols="20"><?php echo sanitize_textarea_field( $notes ); ?></textarea>

		<?php
		$html = ob_get_clean();
		echo $html;
	}

	public function save() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ){
			return;
		}

		$nonce = $_POST['media_notes_nonce'];

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		$notes = sanitize_textarea_field( $_POST['media_notes'] );

		update_post_meta( $post->ID, '_media_notes', $notes );
	}

}
