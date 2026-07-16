module.exports = {
	options: {
		map: true,
		processors: [
			require( 'autoprefixer' ), // add vendor prefixes
		]
	},
	dist: {
		src: 'public/css/*.css'
	}
};


