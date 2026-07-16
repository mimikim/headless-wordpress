let options = {
	dist: {
		options: {
			transform: [
				[ 'babelify',
					{
						'presets': ['@babel/preset-env'],
						'plugins': ['transform-class-properties'],
					}
				]
			]
		},
		files: {
			'public/js/scripts.js': 'public/js/src/*.js'
		}
	}
};

module.exports = options;
