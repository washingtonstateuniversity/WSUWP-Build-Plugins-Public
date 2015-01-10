module.exports = function(grunt) {
	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= pkg.version %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},
			build: {
				src: 'js/analytics.js',
				dest: 'js/analytics.min.js'
			}
		},
		jshint: {
			files: ['js/analytics.js'],
			options: {
// options here to override JSHint defaults
				boss: true,
				curly: true,
				eqeqeq: true,
				eqnull: true,
				expr: true,
				immed: true,
				noarg: true,
				onevar: false,
				smarttabs: true,
				trailing: true,
				undef: true,
				unused: true,
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true,
					window:true
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-jshint');

	// Default task(s).
	grunt.registerTask('default', ['jshint', 'uglify']);
};