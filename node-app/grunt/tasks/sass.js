const options = {
	dist: {
		options: {
			style: 'compressed'
		},
		files: {
			"public/css/style.css": "public/css/scss/style.scss",
			"public/css/print.css": "public/css/scss/print.scss"
		}
	}
};

module.exports = options;
