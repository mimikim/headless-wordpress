module.exports = function(grunt) {
	require( 'load-grunt-tasks' )( grunt );
	grunt.initConfig( require( './grunt' ) );

	grunt.registerTask( 'css', [ 'sass', 'postcss' ] );
	grunt.registerTask( 'js', [ 'browserify', 'babel', 'uglify' ] );
	grunt.registerTask( 'default', [ 'css', 'js' ] );
};
