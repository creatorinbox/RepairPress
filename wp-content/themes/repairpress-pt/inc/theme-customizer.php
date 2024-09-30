<?php
/**
 * Load the Customizer with some custom extended addons
 *
 * @package RepairPress
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

/**
 * This funtion is only called when the user is actually on the customizer page
 * @param  WP_Customize_Manager $wp_customize
 */
if ( ! function_exists( 'repairpress_customizer' ) ) {
	function repairpress_customizer( $wp_customize ) {
		// add required files
		RepairPressHelpers::load_file( '/inc/customizer/class-customize-base.php' );

		new RepairPress_Customizer_Base( $wp_customize );
	}
	add_action( 'customize_register', 'repairpress_customizer' );
}


/**
 * Takes care for the frontend output from the customizer and nothing else
 */
if ( ! function_exists( 'repairpress_customizer_frontend' ) && ! class_exists( 'RepairPress_Customize_Frontent' ) ) {
	function repairpress_customizer_frontend() {
		RepairPressHelpers::load_file( '/inc/customizer/class-customize-frontend.php' );
		$repairpress_customize_frontent = new RepairPress_Customize_Frontent();
	}
	add_action( 'init', 'repairpress_customizer_frontend' );
}
