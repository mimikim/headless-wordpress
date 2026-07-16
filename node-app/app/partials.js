// for each hbs file, register partial
// https://handlebarsjs.com/guide/partials.html#partials

const fs = require('fs');
const hbs = require('hbs');
const path = require('path');
const partialsPath = path.join(__dirname, '../views/partials/');
const API_CALL = require( './includes/api_call' );
const API = require("./includes/api");

module.exports = function() {
	fs.readdir( partialsPath, ( err, partials ) => {
		partials.forEach( fileName => {
			let expressionName = fileName.replace('.hbs', '');

			fs.readFile( path.join(__dirname, '../views/partials/' + fileName ), 'utf8', ( err, fileContents ) => {
				hbs.registerPartial( expressionName, fileContents );
			});
		} );
	});
};

// modals for timeline-footer.hbs
API_CALL( API.timeline_footer ).then( res => {
	res.forEach( elm => {
		let string = '';
		let year = '';

		for ( let i = 0; i < elm.years.length; i++ ) {
			// start of years, eg 1800
			if ( parseInt( elm.years[i].title ) % 10 === 0 ) {
				year += `<h3>${elm.years[i].title}s</h3>`;
				year += '<ul class="timeline-years">';
			}

			if ( elm.years[i].count > 0 ) {
				year += `<li><a href="/timeline/${elm.years[i].title}">${elm.years[i].title}</a></li>`;
			} else {
				year += `<li>${elm.years[i].title}</li>`;
			}

			// end of years, eg: 1809
			if ( elm.years[i].title.slice( -1 ) === '9' ) {
				year += '</ul>';
			}
		}

		string = `<div class="dialogs">
<div role="dialog" id="${elm.slug}-modal" aria-labelledby="${elm.slug}-modal-label" aria-modal="true" class="hidden">
<button type="button" onclick="closeDialog(this)" class="dialog-close">Close Window</button>
<h2 id="${elm.slug}-modal-label" class="dialog-label">${elm.name}</h2>
<span class="small-title">Select Year</span>
<div class="years-container">
${year}
</div>
</div>
</div>`;

		hbs.registerPartial( 'modal-' + elm.slug, string );
	});
});
