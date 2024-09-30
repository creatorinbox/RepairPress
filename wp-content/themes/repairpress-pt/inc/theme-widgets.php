<?php
/**
 * Load here all the individual widgets
 *
 * @package RepairPress
 */

// ProteusWidgets init
new ProteusWidgets;

// Require the individual widgets
add_action( 'widgets_init', function () {
	// custom widgets in the theme
	$repairpress_custom_widgets = array(
		'widget-call-to-action',
		'widget-icon-menu',
		'widget-title-with-button',
	);

	foreach ( $repairpress_custom_widgets as $file ) {
		RepairPressHelpers::load_file( sprintf( '/inc/widgets/%s.php', $file ) );
	}

	// Relying on composer's autoloader, just provide classes from ProteusWidgets
	register_widget( 'PW_Brochure_Box' );
	register_widget( 'PW_Facebook' );
	register_widget( 'PW_Featured_Page' );
	register_widget( 'PW_Google_Map' );
	register_widget( 'PW_Icon_Box' );
	register_widget( 'PW_Latest_News' );
	register_widget( 'PW_Opening_Time' );
	register_widget( 'PW_Skype' );
	register_widget( 'PW_Social_Icons' );
	register_widget( 'PW_Testimonials' );
	register_widget( 'PW_Accordion' );
	register_widget( 'PW_Person_Profile' );
	register_widget( 'PW_Number_Counter' );
	register_widget( 'PW_Steps' );
} );