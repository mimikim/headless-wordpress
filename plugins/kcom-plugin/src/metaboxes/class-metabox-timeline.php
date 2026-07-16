<?php

namespace HEADLESS_WORDPRESS\METABOX;

defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class Metabox_Timeline {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'set_box' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function set_box() {
		add_meta_box(
			'timeline_date',
			__( 'Date of Event' ),
			array( $this, 'set_html' ),
			'timeline',
			'side',
			'high'
		);
	}

	// display metabox
	public function set_html( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'timeline_date_nonce' );

		$date_of_event = get_post_meta( $post->ID, '_date_of_event', true );

		// convert date into human readable format
		$year = substr( $date_of_event, 0, 4 );
		$month = intval( substr( $date_of_event, -4, 2 ) );
		$date = intval( substr( $date_of_event, -2, 2 ) );

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
		<label class="metabox-label">Enter Date of Event (YYYY-MM-DD)</label>

		<input type="text" name="timeline-year-input" id="timeline-year-input" placeholder="YYYY" value="<?php echo $year; ?>">

		<select name="timeline-month-input" id="timeline-month-input">
			<option value="0" <?php echo ( $month === 0 ) ? 'selected' : ''; ?>>No Month</option>
			<?php for ( $x = 1; $x <= 12; $x++ ) : ?>
				<option value="<?php echo $x; ?>" <?php echo ( $month === $x ) ? 'selected' : ''; ?>>
					<?php
					$dateObj = \DateTime::createFromFormat('!m', $x );
					echo $dateObj->format('F'); ?>
				</option>
			<?php endfor; ?>
		</select>

		<select name="timeline-date-input" id="timeline-date-input">
			<option value="0" <?php echo ( $date === 0 ) ? 'selected' : ''; ?>>No Date</option>
			<?php for ( $y = 1; $y <= 31; $y++ ) : ?>
				<option value="<?php echo $y; ?>" <?php echo ( $date === $y ) ? 'selected' : ''; ?>>
					<?php echo $y; ?>
				</option>
			<?php endfor; ?>
		</select>

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

		$nonce = $_POST['timeline_date_nonce'];

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		$year = sanitize_text_field( $_POST['timeline-year-input'] );
		$month = str_pad( sanitize_text_field( $_POST['timeline-month-input'] ), 2, '0', STR_PAD_LEFT );
		$date = str_pad( sanitize_text_field( $_POST['timeline-date-input'] ), 2, '0', STR_PAD_LEFT );

		update_post_meta( $post->ID, '_date_of_event', $year . $month . $date );
	}

}
