// register handlebars helpers
const hbs = require( 'hbs' );

// loop through years for timeline
hbs.registerHelper( 'year-loop', function( start, end, block ) {
	let accum = '';

	for( start; start <= end; start++ ) {
		accum += block.fn(start);
	}

	return accum;
});

// returns "active" class if slug matches
hbs.registerHelper( 'if-active', function( first, second, block ) {
	return ( first === second ) ? new hbs.SafeString( 'class="active-link"' ) : '';
});

// converts date to string
hbs.registerHelper( 'convert-date', function( date ) {
	const months = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
	const year = date.slice( 0, 4 );
	const month = parseInt( date.slice( 4, 6 ) ) - 1; // months in JS start at 0
	const day = date.slice( 6, 8 );
	let new_Date;

	// if no date nor month specified, only show Year
	if ( day === '00' && month === -1 ) {
		new_Date = year;
	}
	// if no date specified, only show Month, Year
	else if ( day === '00' ) {
		new_Date = months[month] + ' ' + year;
	}
	else {
		new_Date = new Date( year, month, day ).toDateString();
	}

	return new_Date;
});

// determine next year and previous year links
hbs.registerHelper( 'year-links', function( year ) {
	let links = '';
	const prev_year = parseInt( year ) - 1;
	const next_year = parseInt( year ) + 1;

	// no less than 1800, no more than CURRENT YEAR
	if ( prev_year >= 1800 ) {
		links += `<a href="/timeline/${prev_year}">Previous Year (${prev_year})</a>`;
	}

	if ( next_year <= 2022 ) {
		links += `<a href="/timeline/${next_year}">Next Year (${next_year})</a>`;
	}

	return links;
});

// returns schema
hbs.registerHelper( 'generate-schema', function( meta ) {
	const schema = {
		"@context": "https://schema.org",
		"@graph": [
			{
				"@type": "WebSite",
				"@id": "https://www.testpage.com/#website",
				"url": "https://www.testpage.com/",
				"name": "Headless Wordpress",
				"inLanguage": "en-US"
			},
			{
				"@type": meta.schema_type,
				"@id": meta.page_url + "#website",
				"url": meta.page_url,
				"name": meta.name + " - Headless Wordpress",
				"isPartOf": {
					"@id": "https://www.testpage.com/#website"
				},
				"description": meta.description,
				"breadcrumb": {
					"@id": meta.page_url + "#breadcrumb",
				},
				"inLanguage": "en-US",
			},
			{
				"@type": "BreadcrumbList",
				"@id": meta.page_url + "#breadcrumb",
				"itemListElement": [
					{
						"@type": "ListItem",
						"position": 1,
						"name": "Home",
						"item": "https://www.testpage.com/"
					},
				]
			}
		]
	};

	// add additional breadcrumb if exists
	if ( meta.schema_type === 'CollectionPage' ) {
		schema['@graph'][2]['itemListElement'].push( {
			"@type": "ListItem",
			"position": 2,
			"name": meta.name,
			"item": meta.page_url
		} );
	}

	if (  meta.schema_type === 'ItemPage' ) {
		schema['@graph'][2]['itemListElement'].push( {
			"@type": "ListItem",
			"position": 2,
			"name": meta.breadcrumb.name,
			"item": meta.breadcrumb.url
		} );

		schema['@graph'][2]['itemListElement'].push( {
			"@type": "ListItem",
			"position": 3,
			"name": meta.name,
		} );
	}

	return JSON.stringify( schema, null, '\t' );
});
