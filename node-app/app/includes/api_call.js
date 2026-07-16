const fetch = require( 'node-fetch' );

async function fetchAPI( url ) {
	try {
		// after this line, our function will wait for the `fetch()` call to be settled
		// the `fetch()` call will either return a Response or throw an error
		const response = await fetch( url );
		if ( ! response.ok ) {
			throw new Error( `HTTP error: ${response.status}` );
		}
		// after this line, our function will wait for the `response.json()` call to be settled
		// the `response.json()` call will either return the JSON object or throw an error

		return await response.json();
	}
	catch(error) {
		console.error(error);
	}
}

module.exports = fetchAPI;
