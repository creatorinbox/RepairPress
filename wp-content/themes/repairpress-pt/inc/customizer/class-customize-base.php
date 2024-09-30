<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package RepairPress
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class RepairPress_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	public function __construct( WP_Customize_Manager $wp_manager ) {
		// set the private propery to instance of wp_manager
		$this->wp_customize = $wp_manager;

		// register the settings/panels/sections/controls, main method
		$this->register();

		/**
		 * Action and filters
		 */

		// render the CSS and cache it to the theme_mod when the setting is saved
		add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );

		// save logo width/height dimensions
		add_action( 'customize_save_logo_img' , array( __CLASS__, 'save_logo_dimensions' ), 10, 1 );

		// flush the rewrite rules after the OT settings are saved
		add_action( 'customize_save_after', 'flush_rewrite_rules' );

		// handle the postMessage transfer method with some dynamically generated JS in the footer of the theme
		add_action( 'wp_footer', array( $this, 'customize_footer_js' ), 30 );
	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	*
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*
	* @see add_action('customize_register',$func)
	*/
	public function register () {
		/**
		 * Settings
		 */

		// branding
		$this->wp_customize->add_setting( 'logo_img' );
		$this->wp_customize->add_setting( 'logo2x_img' );
		$this->wp_customize->add_setting( 'logo_top_margin', array( 'default' => 0 ) );

		// header
		$this->wp_customize->add_setting( 'top_bar_visibility', array( 'default' => 'yes' ) );

		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'top_bar_bg', array(
			'default' => '#25426f',
			'css_map' => array(
				'background-color' => array(
					'.top-navigation .sub-menu > li > a',
				),
				'background|linear_gradient_to_bottom(1)' => array(
					'.top',
				),
				'border-bottom-color|lighten(20)' => array(
					'.top',
				),
				'border-top-color|lighten(20)' => array(
					'.top::before',
				),
				'border-left-color|lighten(20)' => array(
					'.top::after',
				),
				'border-color|darken(5)' => array(
					'.top-navigation .sub-menu > li > a',
					'.top-navigation .sub-menu > li > .sub-menu',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'top_bar_color', array(
			'default' => '#aab0be',
			'css_map' => array(
				'color' => array(
					'.top',
					'.top-navigation > li > a',
					'.top-navigation .sub-menu > li > a',
				),
				'color|lighten(5)' => array(
					'.top-navigation > li > a:hover',
					'.top-navigation > li > a:focus',
					'.top-navigation .sub-menu > li > a:focus',
					'.top-navigation .sub-menu > li > a:hover',
				),
			)
		) ) );

		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'header_bg', array(
			'default' => '#2f538a',
			'css_map' => array(
				'background-color' => array(
					'.header__container',
					'.top::before',
					'.top::after',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'header_text_color', array(
			'default' => '#c7cedb',
			'css_map' => array(
				'color' => array(
					'.header__widgets',
					'.header .icon-box__title',
				),
				'color|lighten(22)' => array(
					'.header .icon-box__subtitle',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'breadcrumbs_bg', array(
			'default' => '#ffffff',
			'css_map' => array(
				'background-color' => array(
					'.breadcrumbs',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'breadcrumbs_color', array(
			'default' => '#3d3d3d',
			'css_map' => array(
				'color' => array(
					'.breadcrumbs a',
				),
				'color|darken(5)' => array(
					'.breadcrumbs a:hover',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'breadcrumbs_color_active', array(
			'default' => '#999999',
			'css_map' => array(
				'color' => array(
					'.breadcrumbs span > span',
				),
			)
		) ) );

		// navigation
		$this->wp_customize->add_setting( 'main_navigation_home_icon', array( 'default' => 'yes' ) );
		$this->wp_customize->add_setting( 'main_navigation_sticky', array( 'default' => 'static' ) );

		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_bg', array(
			'default' => '#ffffff',
			'css_map' => array(
				'background-color' => array(
					'.header__navigation',
					'.header__navigation-widgets|@media (min-width: 992px)',
					'.header__navigation-widgets::before|@media (min-width: 992px)',
					'.header__navigation-widgets::after|@media (min-width: 992px)',
					'.header__container::after|@media (min-width: 992px)',
					'.header--no-nav-widgets .header__navigation::after|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_color', array(
			'default' => '#aaaaaa',
			'css_map' => array(
				'color' => array(
					'.main-navigation > li > a|@media (min-width: 992px)',
					'.home-icon|@media (min-width: 992px)',
					'.main-navigation .menu-item-has-children::after|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_color_hover', array(
			'default' => '#666666',
			'css_map' => array(
				'color' => array(
					'.main-navigation > li > a:hover|@media (min-width: 992px)',
					'.home-icon:hover|@media (min-width: 992px)',
					'.home-icon:focus|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item > a:focus|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item > a:hover|@media (min-width: 992px)',
					// '.main-navigation > li[aria-expanded="true"]::after|@media (min-width: 992px)',
					'.main-navigation > li:focus::after|@media (min-width: 992px)',
					'.main-navigation > li:hover::after|@media (min-width: 992px)',
				),
				'background-color' => array(
					// '.main-navigation > li[aria-expanded="true"] > a::after|@media (min-width: 992px)',
					'.main-navigation > li:focus > a::after|@media (min-width: 992px)',
					'.main-navigation > li:hover > a::after|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_color_active', array(
			'default' => '#3baed4',
			'css_map' => array(
				'color' => array(
					'.main-navigation > .current-menu-item > a|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item > a:hover|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children:hover > a|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children:focus::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children:hover::after|@media (min-width: 992px)',
				),
				'background-color' => array(
					'.main-navigation > .current-menu-item > a::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item > a:focus::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item > a:hover::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children:focus > a::after|@media (min-width: 992px)',
					'.main-navigation > .current-menu-item.menu-item-has-children:hover > a::after|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_sub_bg', array(
			'default' => '#3baed4',
			'css_map' => array(
				'background-color' => array(
					'.main-navigation .sub-menu > li > a|@media (min-width: 992px)',
				),
				'border-color|darken(5)' => array(
					'.main-navigation .sub-menu > li:first-of-type|@media (min-width: 992px)',
				),
				'border-color|lighten(8)' => array(
					'.main-navigation .sub-menu > li > a|@media (min-width: 992px)',
					'.main-navigation .sub-menu .sub-menu > li > a|@media (min-width: 992px)',
				),
				'background-color|lighten(8)' => array(
					'.main-navigation .sub-menu > li > a:hover|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_sub_color', array(
			'default' => '#ffffff',
			'css_map' => array(
				'color' => array(
					'.main-navigation .sub-menu > li > a|@media (min-width: 992px)',
					'.main-navigation .sub-menu > li > a:hover|@media (min-width: 992px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_mobile_color', array(
			'default' => '#666666',
			'css_map' => array(
				'color' => array(
					'.main-navigation > li > a|@media (max-width: 991px)',
					'.home-icon|@media (max-width: 991px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_mobile_color_hover', array(
			'default' => '#333333',
			'css_map' => array(
				'color' => array(
					'.home-icon:focus|@media (max-width: 991px)',
					'.home-icon:hover|@media (max-width: 991px)',
					'.main-navigation > li:hover > a|@media (max-width: 991px)',
					'.main-navigation > li:focus > a|@media (max-width: 991px)',
					// '.main-navigation > li[aria-expanded="true"] > a|@media (max-width: 991px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_mobile_sub_color', array(
			'default' => '#999999',
			'css_map' => array(
				'color' => array(
					'.main-navigation .sub-menu > li > a|@media (max-width: 991px)',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_navigation_mobile_sub_color_hover', array(
			'default' => '#666666',
			'css_map' => array(
				'color' => array(
					'.main-navigation .sub-menu > li > a:hover|@media (max-width: 991px)',
				),
			)
		) ) );

		// main title area
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_title_bg_color', array(
			'default' => '#f2f2f2',
			'css_map' => array(
				'background-color' => array(
					'.main-title',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_title_bg_img', array(
			'css_map' => array(
				'background-image|url' => array(
					'.main-title',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_title_color', array(
			'default' => '#3d3d3d',
			'css_map' => array(
				'color' => array(
					'.main-title h1',
					'.main-title h2',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'main_subtitle_color', array(
			'default' => '#999999',
			'css_map' => array(
				'color' => array(
					'.main-title h3',
				),
			)
		) ) );

		// typography
		$this->wp_customize->add_setting( 'charset_setting', array( 'default' => 'latin' ) );

		// theme layout & color
		$this->wp_customize->add_setting( 'layout_mode', array( 'default' => 'wide' ) );

		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'text_color_widgets', array(
			'default' => '#999999',
			'css_map' => array(
				'color' => array(
					'body',
					'.latest-news__excerpt',
					'.content-area .icon-box__subtitle',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'text_color_content_area', array(
			'default' => '#aaaaaa',
			'css_map' => array(
				'color' => array(
					'.hentry .entry-content',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'headings_color', array(
			'default' => '#3d3d3d',
			'css_map' => array(
				'color' => array(
					'h1',
					'h2',
					'h3',
					'h4',
					'h5',
					'h6',
					'hentry__title',
					'.hentry__title a',
					'.page-box__title a',
					'.latest-news__title',
					'.accordion__panel a',
					'.icon-menu__link',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'primary_color', array(
			'default' => '#3baed4',
			'css_map' => array(
				'color' => array(
					'.header .icon-box .fa',
					'.social-icons__link:hover|@media (min-width: 992px)',
					'.content-area .icon-box .fa',
					'.step:hover .step__number',
					'.latest-news--more-news',
					'.accordion .more-link:focus',
					'.accordion .more-link:hover',
					'a',
					'.person-profile__social_icon:hover',
					'.sidebar .widget_nav_menu .menu li.current-menu-item > a',
					'body.woocommerce-page ul.products li.product a:hover img',
					'.woocommerce ul.products li.product a:hover img',
					'body.woocommerce-page ul.products li.product .price',
					'.woocommerce ul.products li.product .price',
					'body.woocommerce-page .star-rating',
					'.woocommerce .star-rating',
					'body.woocommerce-page div.product p.price',
					'body.woocommerce-page p.stars a',
					'body.woocommerce-page ul.product_list_widget .amount',
					'.woocommerce.widget_shopping_cart .total .amount',
					'body.woocommerce-page .widget_product_categories .product-categories li.current-cat>a',
					'body.woocommerce-page nav.woocommerce-pagination ul li .prev',
					'body.woocommerce-page nav.woocommerce-pagination ul li .next',
					'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a',
				),
				'color|darken(5)' => array(
					'html body.woocommerce-page nav.woocommerce-pagination ul li .next:hover',
					'html body.woocommerce-page nav.woocommerce-pagination ul li .prev:hover',
				),
				'color|darken(15)' => array(
					'a:hover',
					'a:focus',
				),
				'background-color' => array(
					'.btn-primary',
					'.navbar-toggle',
					'.person-profile__tag',
					'.testimonial__carousel',
					'.testimonial__carousel:focus',
					'.widget_calendar caption',
					'.widget_search .search-submit',
					'.pagination .prev',
					'.pagination .next',
					'body.woocommerce-page .widget_shopping_cart_content .buttons .checkout',
					'body.woocommerce-page button.button.alt',
					'body.woocommerce-page .woocommerce-error a.button',
					'body.woocommerce-page .woocommerce-info a.button',
					'body.woocommerce-page .woocommerce-message a.button',
					'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button',
					'body.woocommerce-page #payment #place_order',
					'body.woocommerce-page #review_form #respond input#submit',
					'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle',
					'body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range',
					'.woocommerce button.button.alt:disabled',
					'.woocommerce button.button.alt:disabled:hover',
					'.woocommerce button.button.alt:disabled[disabled]',
					'.woocommerce button.button.alt:disabled[disabled]:hover',
					'body.woocommerce-page nav.woocommerce-pagination ul li .prev',
					'body.woocommerce-page nav.woocommerce-pagination ul li .next',
					'body.woocommerce-page span.onsale, .woocommerce span.onsale',
					'body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a::after',
					'body.woocommerce-page .widget_product_search .search-field + input',
				),
				'background-color|darken(5)' => array(
					'.btn-primary:hover',
					'.btn-primary:focus',
					'.btn-primary.focus',
					'.btn-primary:active',
					'.btn-primary.active',
					'.navbar-toggle:hover',
					'.testimonial__carousel:hover',
					'.widget_search .search-submit:hover',
					'.widget_search .search-submit:focus',
					'.pagination .prev:hover',
					'.pagination .next:hover',
					'body.woocommerce-page .widget_shopping_cart_content .buttons .checkout:hover',
					'body.woocommerce-page button.button.alt:hover',
					'body.woocommerce-page .woocommerce-error a.button:hover',
					'body.woocommerce-page .woocommerce-info a.button:hover',
					'body.woocommerce-page .woocommerce-message a.button:hover',
					'.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover',
					'body.woocommerce-page #payment #place_order:hover',
					'body.woocommerce-page #review_form #respond input#submit:hover',
					'body.woocommerce-page nav.woocommerce-pagination ul li .prev:hover',
					'body.woocommerce-page nav.woocommerce-pagination ul li .next:hover',
					'body.woocommerce-page .widget_product_search .search-field + input:hover',
					'body.woocommerce-page .widget_product_search .search-field + input:focus',
				),
				'border-color' => array(
					'.btn-primary',
					'.pagination .prev',
					'.pagination .next',
					'body.woocommerce-page .widget_shopping_cart_content .buttons .checkout',
				),
				'border-color|darken(5)' => array(
					'.btn-primary:hover',
					'.btn-primary:focus',
					'.btn-primary.focus',
					'.btn-primary:active',
					'.btn-primary.active',
					'.pagination .prev:hover',
					'.pagination .next:hover',
				),
			)
		) ) );

		// shop
		$this->wp_customize->add_setting( 'products_per_page', array( 'default' => 9 ) );
		$this->wp_customize->add_setting( 'single_product_sidebar', array( 'default' => 'left' ) );

		// footer
		$this->wp_customize->add_setting( 'footer_widgets_layout', array( 'default' => '[4,6,8]' ) );

		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_bg_color', array(
			'default' => '#2f538a',
			'css_map' => array(
				'background-color' => array(
					'.footer-top',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_title_color', array(
			'default' => '#ffffff',
			'css_map' => array(
				'color' => array(
					'.footer-top__headings',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_text_color', array(
			'default' => '#aab5c9',
			'css_map' => array(
				'color' => array(
					'.footer-top',
					'.footer-top .textwidget',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_link_color', array(
			'default' => '#aab5c9',
			'css_map' => array(
				'color' => array(
					'.footer-top .widget_nav_menu .menu a',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_bottom_bg_color', array(
			'default' => '#223d67',
			'css_map' => array(
				'background-color' => array(
					'.footer',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_bottom_text_color', array(
			'default' => '#aab5c9',
			'css_map' => array(
				'color' => array(
					'.footer-bottom',
				),
			)
		) ) );
		$this->wp_customize->add_setting( new ProteusThemes_Customize_Setting_Dynamic_CSS( $this->wp_customize, 'footer_bottom_link_color', array(
			'default' => '#ffffff',
			'css_map' => array(
				'color' => array(
					'.footer-bottom a'
				),
			)
		) ) );
		$this->wp_customize->add_setting( 'footer_left_txt', array( 'default' => '<a href="https://www.proteusthemes.com/wordpress-themes/repairpress/">RepairPress Theme</a> Made by ProteusThemes.' ) );
		$this->wp_customize->add_setting( 'footer_center_txt', array( 'default' => '<i class="fa  fa-3x  fa-cc-visa"></i> &nbsp; <i class="fa  fa-3x  fa-cc-mastercard"></i> &nbsp; <i class="fa  fa-3x  fa-cc-amex"></i> &nbsp; <i class="fa  fa-3x  fa-cc-paypal"></i>' ) );
		$this->wp_customize->add_setting( 'footer_right_txt', array( 'default' => '&copy; 2009-2015 RepairPress. All rights reserved.' ) );

		// custom code (css/js)
		$this->wp_customize->add_setting( 'custom_js_head' );
		$this->wp_customize->add_setting( 'custom_js_footer' );
		$this->wp_customize->add_setting( 'custom_css', array( 'default' => '' ) );

		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			$css = get_theme_mod( 'custom_css', '' );

			if ( ! empty( $css ) ) {
				$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
				$return   = wp_update_custom_css_post( '/* Migrated CSS from old Theme Custom CSS setting: */' . PHP_EOL . $css . PHP_EOL . PHP_EOL . '/* New custom CSS: */' . PHP_EOL . $core_css );
				if ( ! is_wp_error( $return ) ) {
					// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
					remove_theme_mod( 'custom_css' );
				}
			}

			// Add new "CSS setting" that will only notify the users that the new "Additional CSS" field is available.
			// It can't be the same name ('custom_css'), because the core control is also named 'custom_css' and it would not display the WP core "Additional CSS" control.
			$this->wp_customize->add_setting( 'pt_custom_css', array( 'default' => '' ) );
		}

		// acf
		$this->wp_customize->add_setting( 'show_acf', array( 'default' => 'no' ) );

		// other
		$this->wp_customize->add_setting( 'blog_date_layout', array( 'default' => 'big' ) );
		$this->wp_customize->add_setting( 'google_maps_api_key' );

		/**
		 * Panel and Sections
		 */

		// one ProteusThemes panel to rule them all
		$this->wp_customize->add_panel( 'panel_repairpress', array(
			'title'       => _x( '[PT] Theme Options', 'backend', 'repairpress-pt' ),
			'description' => _x( 'All RepairPress theme specific settings.', 'backend', 'repairpress-pt' ),
			'priority'    => 10,
		) );

		// individual sections
		$this->wp_customize->add_section( 'repairpress_section_logos', array(
			'title'       => _x( 'Logo', 'backend', 'repairpress-pt' ),
			'description' => _x( 'Logo for the RepairPress theme.', 'backend', 'repairpress-pt' ),
			'priority'    => 10,
			'panel'       => 'panel_repairpress',
		) );

		// Header
		$this->wp_customize->add_section( 'repairpress_section_header', array(
			'title'       => _x( 'Header', 'backend', 'repairpress-pt' ),
			'description' => _x( 'All layout and appearance settings for the header.', 'backend', 'repairpress-pt' ),
			'priority'    => 20,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'repairpress_section_navigation', array(
			'title'       => _x( 'Navigation', 'backend', 'repairpress-pt' ),
			'description' => _x( 'Navigation for the RepairPress theme.', 'backend', 'repairpress-pt' ),
			'priority'    => 30,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'repairpress_section_main_title', array(
			'title'       => _x( 'Main Title Area', 'backend', 'repairpress-pt' ),
			'description' => _x( 'All layout and appearance settings for the main title area (regular pages).', 'backend', 'repairpress-pt' ),
			'priority'    => 33,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'repairpress_section_breadcrumbs', array(
			'title'       => _x( 'Breadcrumbs', 'backend', 'repairpress-pt' ),
			'description' => _x( 'All layout and appearance settings for breadcrumbs.', 'backend', 'repairpress-pt' ),
			'priority'    => 35,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'repairpress_section_theme_colors', array(
			'title'       => _x( 'Theme Layout &amp; Colors', 'backend', 'repairpress-pt' ),
			'priority'    => 40,
			'panel'       => 'panel_repairpress',
		) );

		if ( RepairPressHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_section( 'repairpress_section_shop', array(
				'title'       => _x( 'Shop', 'backend', 'repairpress-pt' ),
				'priority'    => 80,
				'panel'       => 'panel_repairpress',
			) );
		}

		$this->wp_customize->add_section( 'section_footer', array(
			'title'       => _x( 'Footer', 'backend', 'repairpress-pt' ),
			'description' => _x( 'All layout and appearance settings for the footer.', 'backend', 'repairpress-pt' ),
			'priority'    => 90,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'section_custom_code', array(
			'title'       => _x( 'Custom Code' , 'backend', 'repairpress-pt' ),
			'priority'    => 100,
			'panel'       => 'panel_repairpress',
		) );

		$this->wp_customize->add_section( 'section_other', array(
			'title'       => _x( 'Other' , 'backend', 'repairpress-pt' ),
			'priority'    => 150,
			'panel'       => 'panel_repairpress',
		) );

		/**
		 * Controls
		 */

		// Section: repairpress_section_logos
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo_img',
			array(
				'label'       => _x( 'Logo Image', 'backend', 'repairpress-pt' ),
				'description' => _x( 'Max height for the logo image is 120px.', 'backend', 'repairpress-pt' ),
				'section'     => 'repairpress_section_logos',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'logo2x_img',
			array(
				'label'       => _x( 'Retina Logo Image', 'backend', 'repairpress-pt' ),
				'description' => _x( '2x logo size, for screens with high DPI.', 'backend', 'repairpress-pt' ),
				'section'     => 'repairpress_section_logos',
			)
		) );
		$this->wp_customize->add_control(
			'logo_top_margin',
			array(
				'type'        => 'number',
				'label'       => _x( 'Logo top margin', 'backend', 'repairpress-pt' ),
				'description' => _x( 'In pixels.', 'backend', 'repairpress-pt' ),
				'section'     => 'repairpress_section_logos',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 120,
					'step' => 10,
				),
			)
		);

		// Section: header
		$this->wp_customize->add_control( 'top_bar_visibility', array(
			'type'        => 'select',
			'priority'    => 0,
			'label'       => _x( 'Top bar visibility', 'backend', 'repairpress-pt' ),
			'description' => _x( 'Show or hide?', 'backend', 'repairpress-pt' ),
			'section'     => 'repairpress_section_header',
			'choices'     => array(
				'yes'         => _x( 'Show', 'backend', 'repairpress-pt' ),
				'no'          => _x( 'Hide', 'backend', 'repairpress-pt' ),
				'hide_mobile' => _x( 'Hide on Mobile', 'backend', 'repairpress-pt' ),
			),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'top_bar_bg',
			array(
				'priority' => 2,
				'label'    => _x( 'Top bar background color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_header',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'top_bar_color',
			array(
				'priority' => 3,
				'label'    => _x( 'Top bar text color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_header',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'header_bg',
			array(
				'priority' => 30,
				'label'    => _x( 'Header background color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_header',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'header_text_color',
			array(
				'priority' => 32,
				'label'    => _x( 'Header text color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_header',
			)
		) );

		// Section: repairpress_section_navigation
		$this->wp_customize->add_control( 'main_navigation_home_icon', array(
			'type'        => 'select',
			'priority'    => 110,
			'label'       => _x( 'Home Icon', 'backend', 'repairpress-pt' ),
			'section'     => 'repairpress_section_navigation',
			'choices'     => array(
				'yes'         => _x( 'Show', 'backend', 'repairpress-pt' ),
				'no'          => _x( 'Hide', 'backend', 'repairpress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'main_navigation_sticky', array(
			'type'        => 'select',
			'priority'    => 115,
			'label'       => _x( 'Static or sticky navbar?', 'backend', 'repairpress-pt' ),
			'section'     => 'repairpress_section_navigation',
			'choices'     => array(
				'static' => _x( 'Static', 'backend', 'repairpress-pt' ),
				'sticky' => _x( 'Sticky', 'backend', 'repairpress-pt' ),
			),
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_bg',
			array(
				'priority' => 120,
				'label'    => _x( 'Main navigation background color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color',
			array(
				'priority' => 130,
				'label'    => _x( 'Main navigation link color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color_hover',
			array(
				'priority' => 132,
				'label'    => _x( 'Main navigation link hover color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_color_active',
			array(
				'priority' => 134,
				'label'    => _x( 'Main navigation link active color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_bg',
			array(
				'priority' => 160,
				'label'    => _x( 'Main navigation submenu background', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_sub_color',
			array(
				'priority' => 170,
				'label'    => _x( 'Main navigation submenu link color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color',
			array(
				'priority' => 190,
				'label'    => _x( 'Main navigation link color (mobile)', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_color_hover',
			array(
				'priority' => 192,
				'label'    => _x( 'Main navigation link hover color (mobile)', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_sub_color',
			array(
				'priority' => 194,
				'label'    => _x( 'Main navigation submenu link color (mobile)', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_navigation_mobile_sub_color_hover',
			array(
				'priority' => 196,
				'label'    => _x( 'Main navigation submenu link hover color (mobile)', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_navigation',
			)
		) );

		// section: repairpress_section_main_title
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_title_bg_color',
			array(
				'priority' => 10,
				'label'    => _x( 'Main title background color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_main_title',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Image_Control(
			$this->wp_customize,
			'main_title_bg_img',
			array(
				'priority' => 20,
				'label'    => _x( 'Main title background pattern', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_main_title',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_title_color',
			array(
				'priority' => 30,
				'label'    => _x( 'Main title color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_main_title',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'main_subtitle_color',
			array(
				'priority' => 31,
				'label'    => _x( 'Main subtitle color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_main_title',
			)
		) );

		// Section: repairpress_section_breadcrumbs
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_bg',
			array(
				'priority' => 10,
				'label'    => _x( 'Breadcrumbs background color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color',
			array(
				'priority' => 20,
				'label'    => _x( 'Breadcrumbs text color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_breadcrumbs',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'breadcrumbs_color_active',
			array(
				'priority' => 30,
				'label'    => _x( 'Breadcrumbs active text color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_breadcrumbs',
			)
		) );

		// Section: repairpress_section_theme_colors
		$this->wp_customize->add_control( 'layout_mode', array(
			'type'     => 'select',
			'priority' => 10,
			'label'    => _x( 'Layout', 'backend', 'repairpress-pt' ),
			'section'  => 'repairpress_section_theme_colors',
			'choices'  => array(
				'wide'  => _x( 'Wide', 'backend', 'repairpress-pt' ),
				'boxed' => _x( 'Boxed', 'backend', 'repairpress-pt' ),
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'text_color_content_area',
			array(
				'priority' => 30,
				'label'    => _x( 'Text color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'text_color_widgets',
			array(
				'priority' => 32,
				'label'    => _x( 'Text color for widgets', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'headings_color',
			array(
				'priority' => 33,
				'label'    => _x( 'Headings color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_theme_colors',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'primary_color',
			array(
				'priority' => 34,
				'label'    => _x( 'Primary color', 'backend', 'repairpress-pt' ),
				'section'  => 'repairpress_section_theme_colors',
			)
		) );

		// Section: repairpress_section_shop
		if ( RepairPressHelpers::is_woocommerce_active() ) {
			$this->wp_customize->add_control( 'products_per_page', array(
					'label'   => _x( 'Number of products per page', 'backend', 'repairpress-pt' ),
					'section' => 'repairpress_section_shop',
				)
			);
			$this->wp_customize->add_control( 'single_product_sidebar', array(
					'label'   => _x( 'Sidebar on single product page', 'backend', 'repairpress-pt' ),
					'section' => 'repairpress_section_shop',
					'type'    => 'select',
					'choices' => array(
						'none'  => _x( 'No sidebar', 'backend', 'repairpress-pt' ),
						'left'  => _x( 'Left', 'backend', 'repairpress-pt' ),
						'right' => _x( 'Right', 'backend', 'repairpress-pt' ),
					)
				)
			);
		}

		// Section: section_footer
		$this->wp_customize->add_control( new WP_Customize_Range_Control(
			$this->wp_customize,
			'footer_widgets_layout',
			array(
				'priority'    => 1,
				'label'       => _x( 'Footer widgets layout', 'backend', 'repairpress-pt' ),
				'description' => _x( 'Select number of widget you want in the footer and then with the slider rearrange the layout', 'backend', 'repairpress-pt' ),
				'section'     => 'section_footer',
				'input_attrs' => array(
					'min'     => 0,
					'max'     => 12,
					'step'    => 1,
					'maxCols' => 6,
				)
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bg_color',
			array(
				'priority' => 10,
				'label'    => _x( 'Footer background color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );
		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_title_color',
			array(
				'priority' => 30,
				'label'    => _x( 'Footer widget title color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_text_color',
			array(
				'priority' => 31,
				'label'    => _x( 'Footer text color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_link_color',
			array(
				'priority' => 32,
				'label'    => _x( 'Footer link color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_bg_color',
			array(
				'priority' => 35,
				'label'    => _x( 'Footer bottom background color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_text_color',
			array(
				'priority' => 36,
				'label'    => _x( 'Footer bottom text color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( new WP_Customize_Color_Control(
			$this->wp_customize,
			'footer_bottom_link_color',
			array(
				'priority' => 37,
				'label'    => _x( 'Footer bottom link color', 'backend', 'repairpress-pt' ),
				'section'  => 'section_footer',
			)
		) );

		$this->wp_customize->add_control( 'footer_left_txt', array(
			'type'        => 'text',
			'priority'    => 110,
			'label'       => _x( 'Footer text on the left', 'backend', 'repairpress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_center_txt', array(
			'type'        => 'text',
			'priority'    => 115,
			'label'       => _x( 'Footer text on the center', 'backend', 'repairpress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_footer',
		) );

		$this->wp_customize->add_control( 'footer_right_txt', array(
			'type'        => 'text',
			'priority'    => 120,
			'label'       => _x( 'Footer text on the right', 'backend', 'repairpress-pt' ),
			'description' => _x( 'You can use HTML: a, span, i, em, strong, img.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_footer',
		) );

		// Section: section_custom_code
		if ( function_exists( 'wp_update_custom_css_post' ) ) {
			// Show the notice of custom CSS setting migration.
			$this->wp_customize->add_control( 'pt_custom_css', array(
				'type'        => 'hidden',
				'label'       => esc_html__( 'Custom CSS', 'repairpress-pt' ),
				'description' => esc_html__( 'This field is obsolete. The existing code was migrated to the "Additional CSS" field, that can be found in the root of the customizer. This new "Additional CSS" field is a WordPress core field and was introduced in WP version 4.7.', 'repairpress-pt' ),
				'section'     => 'section_custom_code',
			) );
		}
		else {
			$this->wp_customize->add_control( 'custom_css', array(
				'type'        => 'textarea',
				'label'       => _x( 'Custom CSS', 'backend', 'repairpress-pt' ),
				'description' => sprintf( _x( '%s How to find CSS classes %s in the theme.', 'backend', 'repairpress-pt' ), '<a href="https://www.youtube.com/watch?v=V2aAEzlvyDc" target="_blank">', '</a>' ),
				'section'     => 'section_custom_code',
			) );
		}

		$this->wp_customize->add_control( 'custom_js_head', array(
			'type'        => 'textarea',
			'label'       => _x( 'Custom JavaScript (head)', 'backend', 'repairpress-pt' ),
			'description' => _x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well. Paste your Google Analytics tracking code here.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_custom_code',
		) );

		$this->wp_customize->add_control( 'custom_js_footer', array(
			'type'        => 'textarea',
			'label'       => _x( 'Custom JavaScript (footer)', 'backend', 'repairpress-pt' ),
			'description' => _x( 'You have to include the &lt;script&gt;&lt;/script&gt; tags as well.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_custom_code',
		) );

		// Section: section_other
		$this->wp_customize->add_control( 'show_acf', array(
			'type'        => 'select',
			'label'       => _x( 'Show ACF admin panel?', 'backend', 'repairpress-pt' ),
			'description' => _x( 'If you want to use ACF and need the ACF admin panel set this to <strong>Yes</strong>. Do not change if you do not know what you are doing.', 'backend', 'repairpress-pt' ),
			'section'     => 'section_other',
			'choices'     => array(
				'no'  => _x( 'No', 'backend', 'repairpress-pt' ),
				'yes' => _x( 'Yes', 'backend', 'repairpress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'blog_date_layout', array(
			'type'        => 'select',
			'label'       => _x( 'Blog Date', 'backend', 'repairpress-pt' ),
			'description' => _x( 'Big date next to blog title or Small inline date in metadata', 'backend', 'repairpress-pt' ),
			'section'     => 'section_other',
			'choices'     => array(
				'big'   => _x( 'Big, next to title', 'backend', 'repairpress-pt' ),
				'small' => _x( 'Small, in metadata', 'backend', 'repairpress-pt' ),
			),
		) );
		$this->wp_customize->add_control( 'charset_setting', array(
			'type'     => 'select',
			'label'    => _x( 'Character set for Google Fonts', 'backend' , 'repairpress-pt' ),
			'section'  => 'section_other',
			'choices'  => array(
				'latin'        => 'Latin',
				'latin-ext'    => 'Latin Extended',
				'cyrillic'     => 'Cyrillic',
				'cyrillic-ext' => 'Cyrillic Extended',
			)
		) );
		$this->wp_customize->add_control( 'google_maps_api_key', array(
			'type'        => 'text',
			'label'       => esc_html__( 'Google maps API key', 'repairpress-pt' ),
			'description' => sprintf( esc_html__( 'Input the Google maps API key in order for maps to start working. %sGet your API key%s.', 'repairpress-pt' ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">', '</a>' ),
			'section'     => 'section_other',
		) );
	}

	/**
	 * Cache the rendered CSS after the settings are saved in the DB.
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );
	 *
	 * @return void
	 */
	public function cache_rendered_css() {
		set_theme_mod( 'cached_css', $this->render_css() );
	}

	/**
	 * Get the dimensions of the logo image when the setting is saved
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_logo_img' , array( $this, 'save_logo_dimensions' ), 10, 1 );
	 *
	 * @return void
	 */
	public static function save_logo_dimensions( $setting ) {
		$logo_width_height = array();
		$img_data          = getimagesize( esc_url( $setting->post_value() ) );

		if ( is_array( $img_data ) ) {
			$logo_width_height = array_slice( $img_data, 0, 2 );
			$logo_width_height = array_combine( array( 'width', 'height' ), $logo_width_height );
		}

		set_theme_mod( 'logo_dimensions_array', $logo_width_height );
	}

	/**
	 * Render the CSS from all the settings which are of type `ProteusThemes_Customize_Setting_Dynamic_CSS`
	 *
	 * @return string text/css
	 */
	public function render_css() {
		$out = '';

		foreach ( $this->get_dynamic_css_settings() as $setting ) {
			$out .= $setting->render_css();
		}

		return $out;
	}

	/**
	 * Get only the CSS settings of type `ProteusThemes_Customize_Setting_Dynamic_CSS`.
	 *
	 * @see is_dynamic_css_setting
	 * @return array
	 */
	public function get_dynamic_css_settings() {
		return array_filter( $this->wp_customize->settings(), array( $this, 'is_dynamic_css_setting' ) );
	}

	/**
	 * Helper conditional function for filtering the settings.
	 *
	 * @see
	 * @param  mixed  $setting
	 * @return boolean
	 */
	protected function is_dynamic_css_setting( $setting ) {
		return is_a( $setting, 'ProteusThemes_Customize_Setting_Dynamic_CSS' );
	}

	/**
	 * Dynamically generate the JS for previewing the settings of type `ProteusThemes_Customize_Setting_Dynamic_CSS`.
	 *
	 * This function is better for the UX, since all the color changes are transported to the live
	 * preview frame using the 'postMessage' method. Since the JS is generated on-the-fly and we have a single
	 * entry point of entering settings along with related css properties and classes, we cannnot forget to
	 * include the setting in the customizer itself. Neat, man!
	 *
	 * @return string text/javascript
	 */
	public function customize_footer_js() {
		$settings = $this->get_dynamic_css_settings();

		?>

			<script type="text/javascript">
				( function( $ ) {
					'use strict';

				<?php
				foreach ( $settings as $key_id => $setting ) :
				?>

					wp.customize( '<?php echo esc_js( $key_id ); ?>', function( value ) {
						value.bind( function( newval ) {

						<?php
						foreach ( $setting->get_css_map() as $css_prop_raw => $css_selectors ) {
							extract( $setting->filter_css_property( $css_prop_raw ) );

							// background image needs a little bit different treatment
							if ( 'background-image' === $css_prop ) {
								echo 'newval = "url(" + newval + ")";' . PHP_EOL;
							}

							printf( '$( "%1$s" ).css( "%2$s", newval );%3$s', $setting->plain_selectors_for_all_groups( $css_prop_raw ), $css_prop, PHP_EOL );
						}
						?>

						} );
					} );

				<?php
				endforeach;
				?>

				} )( jQuery );
			</script>

		<?php
	}
}