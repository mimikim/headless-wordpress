(function() {
	const button = document.querySelector( 'button.mobile-menu-toggle' );
	const body = document.body;

	button.addEventListener( 'click', function() {
		if ( body.classList.contains( 'open' ) ) {
			body.classList.remove( 'open' );
			button.classList.remove( 'open' );
		} else {
			body.classList.add( 'open' );
			button.classList.add( 'open' );
		}
	});
})();
