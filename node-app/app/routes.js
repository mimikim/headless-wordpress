const express  = require( 'express' );
const router   = express.Router();
const fetch    = require( 'node-fetch' );
const ROUTES   = require( './includes/pages' );
const API_CALL = require( './includes/api_call' );
const API      = require( './includes/api' );

// Homepage, About, Timeline, What Is, Hangul pages
ROUTES.static.forEach(elm => {
	router.get( elm.url, async ( req, res ) => {
		try {
			const apiResponse = await fetch( elm.endpoint );
			const apiResponseJson = await apiResponse.json();
			// console.log( apiResponseJson[0] );

			res.render( elm.template, apiResponseJson[0] );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});
});

// Sources page
router.get( '/sources', async ( req, res ) => {
	try {
		const page_endpoint = await fetch( ROUTES.other.sources.endpoint );
		const page_json = await page_endpoint.json();

		const sources = await fetch( API.sources );
		const sources_json = await sources.json();

		const local = {
			...page_json[0],
			sources: sources_json
		};

		// console.log( local );
		res.render( ROUTES.other.sources.template, local );
	} catch ( err ) {
		console.log( err );
		res.status( 500 ).send( 'Something went wrong, please try again later.' );
	}
});

// Index page
router.get( '/index', async ( req, res ) => {
	try {
		const page_endpoint = await fetch( ROUTES.other.index.endpoint );
		const page_json = await page_endpoint.json();

		const index_endpoint = await fetch( API.pages + 'index' );
		const index_json = await index_endpoint.json();

		const local = {
			...page_json[0],
			index: index_json
		};

		res.render( ROUTES.other.index.template, local );
	} catch ( err ) {
		console.log( err );
		res.status( 500 ).send( 'Something went wrong, please try again later.' );
	}
});

// Archives page & individual Archives posts
API_CALL( API.archives ).then( res => {
	const posts = res;

	// each Archives Post
	posts.forEach( elm => {
		router.get('/archives/' + elm.slug, async (req, res) => {
			console.log(elm);
			res.render( 'single-archives', elm );
		});
	});

	// Archives landing page
	router.get( '/archives', async ( req, res ) => {
		try {
			const page_endpoint = await fetch( ROUTES.other.archives.endpoint );
			const page_json = await page_endpoint.json();

			const local = {
				...page_json[0],
				archives: posts
			};
			// console.log(page_json[0]);

			res.render( ROUTES.other.archives.template, local );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});
});

// timeline taxonomy year
API_CALL( API.taxonomy + 'timeline' ).then( res => {
	res.forEach( elm => {
		router.get( '/timeline/' + elm.slug, async ( req, res ) => {
			const endpoint = await fetch( API.timeline + elm.slug );
			const json = await endpoint.json();
			const local = {
				...elm,
				response: json
			};
			// console.log(local);

			res.render( 'taxonomy-year', local );
		});
	});
});

// People - landing page, alpha taxonomy, and individual post
// /people/
// /people/alpha/
// /people/alpha/{alpha}
// /people/{post-name}
API_CALL( API.taxonomy + 'people' ).then( res => {
	// console.log(res);
	let alpha_nav = res;

	// each letter for People, if Count exists
	// /people/alpha/{alpha}
	alpha_nav.forEach( elm => {
		if ( elm.count > 0 ) {
		router.get( '/people/alpha/' + elm.slug, async ( req, res ) => {
			const endpoint = await fetch( API.people + elm.slug );
			const json = await endpoint.json();
			const local = {
				...elm,
				response: json,
				alpha_nav: alpha_nav
			};
			// console.log(local);

			res.render( 'taxonomy-people', local );
		});
		}
	});

	// people landing page
	// /people/
	router.get( '/people', async ( req, res ) => {
		try {
			const apiResponse = await fetch( ROUTES.other.people.endpoint );
			const apiResponseJson = await apiResponse.json();
			const local = {
				...apiResponseJson[0],
				alpha_nav: alpha_nav
			}
			// console.log(local);

			res.render( ROUTES.other.people.template, local );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});

	// for individual People posts and See All page
	// /people/{post-name}
	// /people/alpha/
	API_CALL( API.people ).then( res => {
		const posts = res;

		// list all posts in post type
		// /people/alpha/
		router.get( '/people/alpha', async ( req, res ) => {
			try {
				const apiResponse = await fetch( ROUTES.see_all.people.endpoint );
				const apiResponseJson = await apiResponse.json();

				const local = {
					...apiResponseJson[0],
					response: posts,
					alpha_nav: alpha_nav
				}
				// console.log(local);

				res.render( 'taxonomy-people', local );
			} catch ( err ) {
				console.log( err );
				res.status( 500 ).send( 'Something went wrong, please try again later.' );
			}
		});

		// individual posts
		// /people/{post-name}
		posts.forEach( elm => {
			router.get( '/people/' + elm.slug, async ( req, res ) => {
				const local = {
					...elm,
					alpha_nav: alpha_nav
				}
				// console.log('people single', local );
				res.render( 'single-people', local );
			});
		});
	});
});

// People - hangul taxonomy
// /people/{hangul}


// Noun - landing page, alpha taxonomy, and individual post
// /noun/
// /noun/alpha/
// /noun/alpha/{alpha}
// /noun/{name}
API_CALL( API.taxonomy + 'noun' ).then( res => {
	let alpha_nav = res;

	// each letter for Noun, if Count exists
	// /noun/alpha/{alpha}
	alpha_nav.forEach( elm => {
		if ( elm.count > 0 ) {
			router.get('/noun/alpha/' + elm.slug, async (req, res) => {
				const endpoint = await fetch(API.noun + elm.slug);
				const json = await endpoint.json();
				const local = {
					...elm,
					response: json,
					alpha_nav: alpha_nav
				};
				// console.log(local);

				res.render('taxonomy-alpha', local);
			});
		}
	});

	// Noun landing page
	// /noun/
	router.get( '/noun', async ( req, res ) => {
		try {
			const apiResponse = await fetch(  ROUTES.other.noun.endpoint );
			const apiResponseJson = await apiResponse.json();
			const local = {
				...apiResponseJson[0],
				alpha_nav: alpha_nav
			}
			// console.log(local);

			res.render(  ROUTES.other.noun.template, local );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});

	// for individual Noun (Places/Events) posts and See All
	// /noun/alpha/{alpha}
	// /noun/{name}
	API_CALL( API.noun ).then( res => {
		const nouns = res;

		// list all posts in post type
		// /noun/alpha/
		router.get( '/noun/alpha', async ( req, res ) => {
			try {
				const apiResponse = await fetch( ROUTES.see_all.people.endpoint );
				const apiResponseJson = await apiResponse.json();

				const local = {
					...apiResponseJson[0],
					response: nouns,
					alpha_nav: alpha_nav
				}
				// console.log(local);

				res.render( 'taxonomy-alpha', local );
			} catch ( err ) {
				console.log( err );
				res.status( 500 ).send( 'Something went wrong, please try again later.' );
			}
		});

		// /noun/{name}
		nouns.forEach( elm => {
			router.get( '/noun/' + elm.slug, async ( req, res ) => {
				const local = {
					...elm,
					alpha_nav: alpha_nav
				}
				// console.log('noun single', local );
				res.render( 'single-alpha', local );
			});
		});
	});
});

// Glossary - landing page, alpha taxonomy, and individual post
// /glossary/
// /glossary/alpha
// /glossary/alpha/{alpha}
// /glossary/post/{name}
API_CALL( API.taxonomy + 'glossary' ).then( res => {
	let alpha_nav = res;

	// each letter for Glossary, if Count exists
	// /glossary/alpha/{alpha}
	alpha_nav.forEach( elm => {
		if ( elm.count > 0 ) {
			router.get( '/glossary/alpha/' + elm.slug, async ( req, res ) => {
				const endpoint = await fetch( API.glossary + elm.slug );
				const json = await endpoint.json();
				const local = {
					...elm,
					response: json,
					alpha_nav: alpha_nav
				};
				// console.log(local);

				res.render( 'taxonomy-alpha', local );
			});
		}
	});

	// Glossary landing page
	// /glossary/
	router.get( '/glossary', async ( req, res ) => {
		try {
			const apiResponse = await fetch(  ROUTES.other.glossary.endpoint );
			const apiResponseJson = await apiResponse.json();
			const local = {
				...apiResponseJson[0],
				alpha_nav: alpha_nav
			}
			// console.log(local);

			res.render(  ROUTES.other.glossary.template, local );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});

	// for individual Glossary posts
	API_CALL( API.glossary ).then( res => {
		const glossaries = res;

		// list all posts in post type
		// /glossary/alpha
		router.get( '/glossary/alpha', async ( req, res ) => {
			try {
				const apiResponse = await fetch( ROUTES.see_all.people.endpoint );
				const apiResponseJson = await apiResponse.json();

				const local = {
					...apiResponseJson[0],
					response: glossaries,
					alpha_nav: alpha_nav
				}
				// console.log(local);

				res.render( 'taxonomy-alpha', local );
			} catch ( err ) {
				console.log( err );
				res.status( 500 ).send( 'Something went wrong, please try again later.' );
			}
		});

		glossaries.forEach( elm => {
			router.get( '/glossary/' + elm.slug, async ( req, res ) => {
				const local = {
					...elm,
					alpha_nav: alpha_nav
				}
				// console.log('glossary single', local );
				res.render( 'single-alpha', local );
			});
		});
	});
});

// Media page & individual Media posts
API_CALL( API.media ).then( res => {
	const medias = res;

	// each Media Post
	medias.forEach( elm => {
		router.get('/media/' + elm.slug, async (req, res) => {
			// console.log(elm);
			res.render( 'single-media', elm );
		});
	});

	// Media landing page
	router.get( '/media', async ( req, res ) => {
		try {
			const page_endpoint = await fetch( ROUTES.other.media.endpoint );
			const page_json = await page_endpoint.json();

			const local = {
				...page_json[0],
				media: medias
			};
			// console.log(page_json[0]);

			res.render( ROUTES.other.media.template, local );
		} catch ( err ) {
			console.log( err );
			res.status( 500 ).send( 'Something went wrong, please try again later.' );
		}
	});
});


module.exports = router;
