<?php

// remove comment from admin menu
add_action( 'admin_menu', function() {
	remove_menu_page( 'edit-comments.php' );
} );

// disable Autosaves in database
add_action( 'admin_init', 'disable_autosave' );
function disable_autosave() {
	wp_deregister_script( 'autosave' );
}
