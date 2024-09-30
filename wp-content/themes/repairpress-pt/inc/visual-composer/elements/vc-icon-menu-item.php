<?php

/**
 * Icon Menu Item content element for the Visual Composer editor,
 * that can only be used in the Icon Menu container
 */

if ( ! class_exists( 'PT_VC_Icon_Menu_Item' ) ) {
	class PT_VC_Icon_Menu_Item extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_icon_menu_item'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'title'   => '',
				'link'    => '',
				'icon'    => '',
				'new_tab' => '',
				), $atts );

			// Extract the icon class without the first 'fa' part
			$icon         = explode( ' ', $atts['icon'] );
			$atts['icon'] = $icon[1];

			// The PHP_EOL is added so that it can be used as a separator between multiple accordion items
			return PHP_EOL . json_encode( $atts );
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'     => _x( 'Icon Menu Item', 'backend', 'repairpress-pt' ),
				'base'     => $this->shortcode_name(),
				'category' => _x( 'Content', 'backend', 'repairpress-pt' ),
				'icon'     => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'as_child' => array( 'only' => 'pt_vc_container_icon_menu' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => _x( 'Title', 'backend', 'repairpress-pt' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'Link URL', 'backend', 'repairpress-pt' ),
						'param_name' => 'link',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => _x( 'Open link in new tab', 'backend', 'repairpress-pt' ),
						'param_name' => 'new_tab',
					),
					array(
						'type'        => 'iconpicker',
						'heading'     => _x( 'Select icon', 'backend', 'repairpress-pt' ),
						'param_name'  => 'icon',
						'value'       => 'fa fa-laptop',
						'description' => _x( 'Select icon from library.', 'backend', 'repairpress-pt' ),
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 50,
						),
					),
				)
			) );
		}
	}

	// Initialize the class
	new PT_VC_Icon_Menu_Item;
}