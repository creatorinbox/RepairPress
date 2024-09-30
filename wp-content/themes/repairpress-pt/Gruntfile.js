module.exports = function ( grunt ) {
	// Auto-load the needed grunt tasks
	// require('load-grunt-tasks')(grunt);
	require( 'load-grunt-tasks' )( grunt, { pattern: ['grunt-*'] } );
	var sass = require( 'sass' );

	var config = {
		tmpdir:                  '.tmp/',
		phpFileRegex:            '[^/]+\.php$',
		phpFileInSubfolderRegex: '.*?\.php$',
		themeSlug:               'repairpress-pt',
	};

	// configuration
	grunt.initConfig( {
		pgk: grunt.file.readJSON( 'package.json' ),

		config: config,

		// https://github.com/sindresorhus/grunt-sass
		sass: {
			options: {
				outputStyle:    'expanded',
				sourceMap:      true,
				includePaths:   ['bower_components/bootstrap-sass/assets/stylesheets'],
				implementation: sass,
			},
			build: {
				files: [{
					expand: true,
					cwd:    'assets/sass/',
					src:    '*.scss',
					ext:    '.css',
					dest:   config.tmpdir,
				}]
			}
		},

		// Apply several post-processors to your CSS using PostCSS.
		// https://github.com/nDmitry/grunt-postcss
		postcss: {
			options: {
				map:      true,
				processors: [
					require('autoprefixer')({browsers: ['last 2 versions', 'ie 10']}),
				]
			},
			build: {
				expand: true,
				cwd:    config.tmpdir,
				src:    '*.css',
				dest:   './',
			},
			minified: {
				options: {
					processors: [
						require('autoprefixer')({
							browsers: ['last 2 versions', 'ie 10', 'ie 11', 'Safari >= 8']
						}),
						require('cssnano')({
							discardComments: {removeAllButFirst: true}
						}),
					]
				},
				expand: true,
				cwd:    config.tmpdir,
				src:    '*.css',
				ext:    '.min.css',
				dest:   './',
			},
		},

		// https://npmjs.org/package/grunt-contrib-watch
		watch: {
			// autoprefix the files
			autoprefixer: {
				files: ['<%= config.tmpdir %>*.css'],
				tasks: ['postcss:build'],
			},

			// compile scss
			sass: {
				options: {
					atBegin: true,
				},
				files:   ['assets/sass/**/*.scss'],
				tasks:   ['sass:build'],
			},

			// PHP
			reloadBrowser: {
				options: {
					livereload: true,
				},
				files: ['**/*.php', 'assets/js/{,*/}*.js', '*.css'],
			},

			// lint SCSS
			scsslint: {
				files: ['assets/sass/**/*.scss'],
				tasks: ['lint'],
			},
		},

		// requireJS optimizer
		// https://github.com/gruntjs/grunt-contrib-requirejs
		requirejs: {
			build: {
				// Options: https://github.com/jrburke/r.js/blob/master/build/example.build.js
				options: {
					baseUrl:                 '',
					mainConfigFile:          'assets/js/main.js',
					optimize:                'uglify2',
					preserveLicenseComments: false,
					useStrict:               true,
					wrap:                    true,
					name:                    'bower_components/almond/almond',
					include:                 'assets/js/main',
					out:                     'assets/js/main.min.js'
				}
			}
		},

		// https://github.com/gruntjs/grunt-contrib-copy
		copy: {
			// create new directory for deployment
			build: {
				expand: true,
				dot:    false,
				dest:   config.themeSlug + '/',
				src:    [
					'*.css',
					'*.php',
					'screenshot.{jpg,png}',
					'Gruntfile.js',
					'composer.json',
					'composer.lock',
					'package.json',
					'bower.json',
					'wpml-config.xml',
					'assets/**',
					'template-parts/**',
					'bower_components/picturefill/dist/picturefill.min.js',
					'bower_components/requirejs/require.js',
					'bower_components/bootstrap-sass/assets/javascripts/bootstrap/**',
					'bower_components/font-awesome/css/font-awesome.min.css',
					'bower_components/font-awesome/fonts/**',
					'bower_components/mustache/mustache.min.js',
					'bin/acf.xml',
					'bundled-plugins/**',
					'inc/**',
					'languages/**',
					'vendor/**',
				],
				flatten: false
			},
			proteusWidgetsTranslations: {
				expand:  true,
				flatten: true,
				src:     'vendor/proteusthemes/proteuswidgets/languages/*',
				dest:    'languages/proteuswidgets/',
			}
		},

		// https://github.com/gruntjs/grunt-contrib-clean
		clean: {
			build: [
				config.themeSlug + '/vendor/league/plates/doc',
				config.themeSlug + '/vendor/tgmpa/tgm-plugin-activation/example.php',
				config.themeSlug + '/vendor/tgmpa/tgm-plugin-activation/plugins/tgm-example-plugin.zip',
				config.themeSlug + '/vendor/league/plates/example',
				config.themeSlug + '/vendor/primozcigler/wordpress-one-click-demo-install/example.php',
				config.themeSlug + '/vendor/proteusthemes/proteuswidgets/tests',
				config.themeSlug + '/vendor/proteusthemes/wai-aria-walker-nav-menu/README.md',
			]
		},

		// https://github.com/gruntjs/grunt-contrib-compress
		compress: {
			build: {
				options: {
					archive: config.themeSlug + '.zip',
					mode:    'zip'
				},
				src: config.themeSlug + '/**'
			},
			eddThemeVersion: {
				options: {
					archive: 'edd-' + config.themeSlug + '.zip',
					mode:    'zip'
				},
				src: config.themeSlug + '/**'
			},
			tfThemeVersion: {
				options: {
					archive: 'tf-' + config.themeSlug + '.zip',
					mode:    'zip'
				},
				src: config.themeSlug + '/**'
			}
		},

		// https://www.npmjs.com/package/grunt-wp-i18n
		makepot: {
			theme: {
				options: {
					domainPath:      'languages/',
					include:         [config.phpFileRegex, '^inc/'+config.phpFileRegex, '^template-parts/'+config.phpFileRegex],
					mainFile:        'style.css',
					potComments:     'Copyright (C) {year} ProteusThemes \n# This file is distributed under the GPL 2.0.',
					potFilename:     config.themeSlug + '.pot',
					potHeaders:      {
						poedit:                 true,
						'report-msgid-bugs-to': 'http://support.proteusthemes.com/',
					},
					type:            'wp-theme',
					updateTimestamp: false,
					updatePoFiles:   true,
				}
			},
		},

		// https://www.npmjs.com/package/grunt-wp-i18n
		addtextdomain: {
			options: {
				updateDomains: true
			},
			target: {
				files: {
					src: [
						'*.php',
						'inc/**/*.php',
						'woocommerce/**/*.php',
						'template-parts/*.php'
					]
				}
			}
		},

		// https://www.npmjs.com/package/grunt-po2mo
		po2mo: {
			files: {
				src:    'languages/*.po',
				expand: true,
			},
		},

		// https://github.com/yoniholmes/grunt-text-replace
		replace: {
			composerIssue: {
				src: 'vendor/composer/platform_check.php',
				overwrite:    true,
				replacements: [{
					from: "fwrite(STDERR, 'Composer detected issues in your platform:' . PHP_EOL.PHP_EOL . implode(PHP_EOL, $issues) . PHP_EOL.PHP_EOL);",
					to: ''
				}],
			},
			autoloadIssue: {
				src: 'vendor/autoload.php',
				overwrite:    true,
				replacements: [{
					from: "fwrite(STDERR, $err);",
					to: ''
				}],
			},
			theme_version: {
				src:          'style.css',
				overwrite:    true,
				replacements: [{
					from: '0.0.0-tmp',
					to:   function () {
						grunt.option( 'version_replaced_flag', true );
						return grunt.option( 'longVersion' );
					}
				}],
			},
			tgmpaIssue: {
				src: 'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php',
				overwrite: true,
				replacements: [{
					from: "else {\n				$this->page_hook = call_user_func( 'add_submenu_page', $args['parent_slug'], $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'] );\n			}",
					to: ''
				}]
			},
			noThemeRegistrationExceptions: {
				src:  config.themeSlug + '/functions.php',
				overwrite: true,
				replacements: [{
					from: "\n	'theme-registration',",
					to: ''
				}]
			},
			tfExceptions: {
				src:  config.themeSlug + '/inc/theme-registration.php',
				overwrite: true,
				replacements: [{
					from: "'build'            => 'pt',",
					to: "'build'            => 'tf',"
				}]
			},
			localJqueryUiCss: {
				src: 'vendor/proteusthemes/wp-customizer-utilities/class-pt-customize-control-range.php',
				overwrite: true,
				replacements: [{
					from: "sprintf( '//cdnjs.cloudflare.com/ajax/libs/jqueryui/%s/jquery-ui.min.css', $ui->ver )",
					to: "get_template_directory_uri() . '/assets/jquery-ui.min.css'"
				}]
			}
		},

		// https://github.com/ahmednuaman/grunt-scss-lint
		// https://github.com/brigade/scss-lint#disabling-linters-via-source
		// config file: https://github.com/brigade/scss-lint/blob/master/config/default.yml
		scsslint: {
			allFiles: [
				'assets/sass/**/*.scss',
			],
			options: {
				// bundleExec: true,
				config: '.scss-lint.yml',
				// reporterOutput: 'scss-lint-report.xml',
				colorizeOutput: true
			},
		},

		// https://github.com/gruntjs/grunt-contrib-jshint
		// http://jshint.com/docs/options/
		jshint: {
			options: {
				curly:   true,
				bitwise: true,
				eqeqeq:  true,
				freeze:  true,
				unused:  true,
				undef:   true,
				browser: true,
				globals: {
					jQuery:         true,
					Modernizr:      true,
					RepairPressVars: true,
					module:         true,
					define:         true,
					require:        true,
				}
			},
			allFiles: [
				'Gruntfile.js',
				'assets/js/*.js',
				'!assets/js/{modernizr,fix.}*',
				'!assets/js/*.min.js',
			]
		},

	} );

	// when developing
	grunt.registerTask( 'default', [
		'build',
		'watch',
	] );

	// build assets
	grunt.registerTask( 'build', [
		'sass:build',
		'postcss:build',
		'requirejs:build',
	] );

	// update languages files
	grunt.registerTask( 'theme_i18n', [
		'copy:proteusWidgetsTranslations',
		'addtextdomain',
		'makepot:theme',
		'po2mo',
	] );

	// CI
	// build assets
	grunt.registerTask( 'ci', 'Builds all assets on the CI, needs to be called with --theme_version arg.', function () {
		// get theme version, provided from cli
		var version = grunt.option( 'theme_version' ) || null;

		// check if version is string and is in semver.org format (at least a start)
		if ( 'string' === typeof version && /^v\d{1,2}\.\d{1,2}\.\d{1,2}/.test( version ) ) { // regex that version starts like v1.2.3
			var longVersion = version.substring( 1 ).trim(),
				shortVersion = longVersion.match( /\d{1,2}\.\d{1,2}\.\d{1,2}/ )[0],
				tasksToRun = [
					'build',
					'replace:composerIssue',
					'replace:autoloadIssue',
					'replace:theme_version',
					'check_theme_replace',
					'theme_i18n',
					'replace:localJqueryUiCss'
				];

			grunt.option( 'longVersion', longVersion );

			if ( shortVersion === longVersion ) { // perform theme update, add flag file
				grunt.log.writeln( 'Uploading the theme builds' );
				grunt.log.writeln( '===========================' );

				if ( grunt.file.isFile( './deploy-zipball' ) ) {
					grunt.fail.warn( 'File for flagging theme builds already exists.', 1 );
				}
				else {
					// write a dummy file, if this one exists later on build a zip for theme builds
					grunt.file.write( './deploy-zipball', 'lets go!' );
				}
			}

			grunt.task.run( tasksToRun );
		}
		else {
			grunt.fail.warn( 'Version to be replaced in style.css is not specified or valid.\nUse: grunt <your-task> --theme_version=v1.2.3\n', 3 );
		}
	} );

	// build the theme files
	grunt.registerTask( 'buildTheme', [
		'copy:build',
		'clean:build',
	] );

	// create installable zip for TF
	grunt.registerTask( 'tfZip', [
		'replace:tfExceptions',
		'compress:tfThemeVersion',
	] );

	// create installable zip with no theme registration
	grunt.registerTask( 'noThemeRegistraionZip', [
		'replace:noThemeRegistrationExceptions',
		'compress:build',
	] );

	// lint the code
	grunt.registerTask( 'lint', [
		'scsslint',
		'jshint',
	] );

	// check if the search-replace was performed
	grunt.registerTask( 'check_theme_replace', 'Check if the search-replace for theme version was preformed.', function () {
		if ( true !== grunt.option( 'version_replaced_flag' ) ) {
			grunt.fail.warn( 'Search-replace task error - no theme version replaced.' );
		}
	} );
};
