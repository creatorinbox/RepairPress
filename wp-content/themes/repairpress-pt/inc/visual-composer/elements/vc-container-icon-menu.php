<?php

/**
 * Icon Menu container content element for the Visual Composer editor,
 * that allows nesting of the Icon Menu Item VC content element
 */

if ( ! class_exists( 'PT_VC_Container_Icon_Menu' ) ) {
	class PT_VC_Container_Icon_Menu extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_container_icon_menu'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$items = PT_VC_Helper_Functions::get_child_elements_data( $content );

			$instance = array(
				'items' => $items,
			);

			ob_start();
				the_widget( 'PW_Icon_Menu', $instance );
			return ob_get_clean();
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'                    => _x( 'Icon Menu', 'backend', 'repairpress-pt' ),
				'base'                    => $this->shortcode_name(),
				'category'                => _x( 'Content', 'backend', 'repairpress-pt' ),
				'icon'                    => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'as_parent'               => array( 'only' => 'pt_vc_icon_menu_item' ),
				'content_element'         => true,
				'show_settings_on_create' => false,
				'js_view'                 => 'VcColumnView',
			) );
		}
	}

	// Initialize the class
	new PT_VC_Container_Icon_Menu;

	// The "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_Pt_Vc_Container_Icon_Menu extends WPBakeryShortCodesContainer {}
	}
}