<?php
/**
 * RepairPress functions and definitions
 *
 * @author Marko Prelec <marko.prelec@proteusnet.com>
 * @author Gregor Capuder <gregor.capuder@proteusnet.com>
 * @author Primoz Cigler <primoz@proteusnet.com>
 */

// Display informative message if PHP version is less than 5.3.2
if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
	die( sprintf( 'This theme requires <strong>PHP 5.3.2+</strong> to run. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.<br> Your current version of PHP: <strong>%s</strong>', phpversion() ) );
}


// Composer autoloader
require_once( get_template_directory() . '/vendor/autoload.php' );


/**
 * Define the version variable to assign it to all the assets (css and js)
 */
define( 'REPAIRPRESS_WP_VERSION', wp_get_theme()->get( 'Version' ) );


/**
 * Define the development constant
 */
if ( ! defined( 'REPAIRPRESS_DEVELOPMENT' ) ) {
	define( 'REPAIRPRESS_DEVELOPMENT', false );
}


/**
 * Helper functions used in the theme
 */
require_once get_template_directory() . '/inc/helpers.php';


/**
 * Advanced Custom Fields calls to require the plugin within the theme
 */
RepairPressHelpers::load_file( '/inc/acf.php' );


/**
 * Theme support and thumbnail sizes
 */
if ( ! function_exists( 'repairpress_theme_setup' ) ) {
	function repairpress_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on RepairPress, use a find and replace
		 * to change 'repairpress-pt' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'repairpress-pt', get_template_directory() . '/languages' );

		/**
		 * Loads separate textdomain for the proteuswidgets which are included with composer.
		 */
		load_theme_textdomain( 'proteuswidgets', get_template_directory() . '/languages/proteuswidgets' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// WooCommerce basic support
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		/**
		 * Let WordPress manage the document title.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'repairpress-jumbotron-slider-l', 1920, 600, true );
		add_image_size( 'repairpress-jumbotron-slider-m', 960, 300, true );
		add_image_size( 'repairpress-jumbotron-slider-s', 480, 150, true );

		// Menus
		// add_theme_support( 'menus' );
		register_nav_menu( 'main-menu', _x( 'Main Menu', 'backend', 'repairpress-pt' ) );
		register_nav_menu( 'top-bar-menu', _x( 'Top Bar Menu', 'backend', 'repairpress-pt' ) );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// add excerpt support for pages
		add_post_type_support( 'page', 'excerpt' );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'repairpress_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
	add_action( 'after_setup_theme', 'repairpress_theme_setup' );
}


/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @see https://codex.wordpress.org/Content_Width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140; /* pixels */
}


/**
 * Enqueue CSS stylesheets
 */
if ( ! function_exists( 'repairpress_enqueue_styles' ) ) {
	function repairpress_enqueue_styles() {
		wp_enqueue_style( 'repairpress-main', get_stylesheet_uri(), array(), REPAIRPRESS_WP_VERSION );

		// custom WooCommerce CSS (enqueue it only if the WooCommerce plugin is active)
		if ( RepairPressHelpers::is_woocommerce_active() ) {
			wp_enqueue_style( 'repairpress-woocommerce', get_template_directory_uri() . '/woocommerce.css' , array( 'repairpress-main' ) , REPAIRPRESS_WP_VERSION );
		}
	}
	add_action( 'wp_enqueue_scripts', 'repairpress_enqueue_styles' );
}


/**
 * Enqueue Google Web Fonts.
 */
if ( ! function_exists( 'repairpress_enqueue_google_web_fonts' ) ) {
	function repairpress_enqueue_google_web_fonts() {
		wp_enqueue_style( 'google-fonts', RepairPressHelpers::google_web_fonts_url(), array(), null );
	}
	add_action( 'wp_enqueue_scripts', 'repairpress_enqueue_google_web_fonts' );
}


/**
 * Enqueue JS scripts
 */
if ( ! function_exists( 'repairpress_enqueue_scripts' ) ) {
	function repairpress_enqueue_scripts() {
		// modernizr for the frontend feature detection
		wp_enqueue_script( 'repairpress-modernizr', get_template_directory_uri() . '/assets/js/modernizr.custom.24530.js', array(), null );

		// picturefill for the support of the <picture> element today
		wp_enqueue_script( 'repairpress-picturefill', get_template_directory_uri() . '/bower_components/picturefill/dist/picturefill.min.js', array( 'repairpress-modernizr' ), '2.2.1' );

		// google maps
		wp_register_script( 'repairpress-gmaps', RepairPressHelpers::get_google_maps_api_url(), array(), null, true );

		// requirejs
		wp_register_script( 'requirejs', get_template_directory_uri() . '/bower_components/requirejs/require.js', array(), null, true );

		// array for main.js dependencies
		$main_deps = array( 'jquery', 'underscore', 'repairpress-gmaps' );

		// main JS file, conditionally
		if ( true === REPAIRPRESS_DEVELOPMENT ) {
			$main_deps[] = 'requirejs';
			wp_enqueue_script( 'repairpress-main', get_template_directory_uri() . '/assets/js/main.js', $main_deps, REPAIRPRESS_WP_VERSION, true );
		}
		else {
			wp_enqueue_script( 'repairpress-main', get_template_directory_uri() . '/assets/js/main.min.js', $main_deps, REPAIRPRESS_WP_VERSION, true );
		}

		// Pass data to the main script
		wp_localize_script( 'repairpress-main', 'RepairPressVars', array(
			'pathToTheme'  => get_template_directory_uri(),
		) );

		// for nested comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'repairpress_enqueue_scripts' );
}

if ( ! function_exists( 'repairpress_unregister_default_widgets' ) ) {
	function repairpress_unregister_default_widgets() {
		unregister_widget('WP_Widget_Block');
	}
}
add_action('widgets_init', 'repairpress_unregister_default_widgets', 11);

/**
 * Register admin JS scripts
 */
if ( ! function_exists( 'repairpress_admin_enqueue_scripts' ) ) {
	function repairpress_admin_enqueue_scripts() {
		// mustache for ProteusWidgets
		wp_enqueue_script( 'mustache.js', get_template_directory_uri() . '/bower_components/mustache/mustache.min.js' );

		// enqueue admin utils js
		wp_enqueue_script( 'repairpress-admin-utils', get_template_directory_uri() . '/assets/admin/js/admin.js', array( 'jquery', 'underscore', 'backbone', 'mustache.js' ) );

		// register fa CSS, enqueued in ProteusWidgets
		wp_register_style( 'font-awesome', get_template_directory_uri() . '/bower_components/font-awesome/css/font-awesome.min.css', array(), '4.4.0' );

		// enqueue CSS for admin area
		wp_enqueue_style( 'admin-css', get_template_directory_uri() . '/assets/admin/css/admin.css' );

	}
	add_action( 'admin_enqueue_scripts', 'repairpress_admin_enqueue_scripts' );
}


/**
 * Require the files in the folder /inc/
 */
$repairpress_files_to_require = array(
	'theme-widgets',
	'theme-vc-include',
	'theme-sidebars',
	'filters',
	'compat',
	'shortcodes',
	'theme-customizer',
	'woocommerce',
	'theme-registration',
);

// Conditionally require the includes files, based if they exist in the child theme or not
foreach ( $repairpress_files_to_require as $file ) {
	RepairPressHelpers::load_file( sprintf( '/inc/%s.php', $file ) );
}


/**
 * WIA-ARIA nav walker and accompanying JS file
 */

if ( ! function_exists( 'repairpress_wai_aria_js' ) ) {
	function repairpress_wai_aria_js() {
		wp_enqueue_script( 'wp-wai-aria', get_template_directory_uri() . '/vendor/proteusthemes/wai-aria-walker-nav-menu/wai-aria.js', array( 'jquery' ), null, true );
	}
	add_action( 'wp_enqueue_scripts', 'repairpress_wai_aria_js' );
}


/**
 * Require some files only when in admin
 */
if ( is_admin() ) {
	// other files
	$repairpress_admin_files_to_require = array(
		// custom code
		'tgm-plugin-activation',
		'documentation-link',
	);

	foreach ( $repairpress_admin_files_to_require as $file ) {
		RepairPressHelpers::load_file( sprintf( '/inc/%s.php', $file ) );
	}
}
