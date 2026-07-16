/**
	wp-json/kc/people/
	wp-json/kc/glossary/
	wp-json/kc/noun/
	wp-json/kc/sources/
	wp-json/kc/archives/

	wp-json/kc/pages/index
	wp-json/kc/pages/{id}

	wp-json/kc/taxonomy/{type}

	wp-json/kc/hangul/{post_type}
*/

const baseURL = ( global.env === 'dev' ) ? 'http://localhost:8888/kcom/wp-json/kc/' : 'https://sub.testsite.com/wp-json/kc/';

module.exports = {
	archives: baseURL + 'archives/',
	glossary: baseURL + 'glossary/',
	media: baseURL + 'media/',
	noun: baseURL + 'noun/',
	pages: baseURL + 'pages/',
	people: baseURL + 'people/',
	sources: baseURL + 'sources/',
	timeline: baseURL + 'timeline/',
	taxonomy: baseURL + 'taxonomy/',
	timeline_footer: baseURL + 'timeline-footer',
	hangul: baseURL + 'hangul/', // alpha_kore tax for People
};
