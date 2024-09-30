<?php

// Only require these files, if the Visual Composer plugin is activated
if ( defined( 'WPB_VC_VERSION' ) ) {

	// Require Visual Composer classes
	require_once( get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-shortcode.php' );
	require_once( get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-custom-param-types.php' );
	require_once( get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-helpers.php' );

	// Require Visual Composer RepairPress front page template
	RepairPressHelpers::load_file( '/inc/visual-composer/templates/vc-home-page-template.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/templates/vc-our-services-template.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/templates/vc-about-us-template.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/templates/vc-contact-us-template.php' );

	// Require custom VC elements for RepairPress theme
	RepairPressHelpers::load_file( '/inc/visual-composer/elements/vc-call-to-action.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/elements/vc-counter.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/elements/vc-container-icon-menu.php' );
	RepairPressHelpers::load_file( '/inc/visual-composer/elements/vc-icon-menu-item.php' );

	// Custom visual composer shortcodes for the theme from the Visual Composer Elements (PHP Composer package)
	$repairpress_custom_vc_shortcodes = array(
		'brochure-box',
		'facebook',
		'featured-page',
		'icon-box',
		'latest-news',
		'skype',
		'opening-time',
		'social-icon',
		'container-social-icons',
		'location',
		'container-google-maps',
		'testimonial',
		'container-testimonials',
		'container-number-counter',
		'accordion-item',
		'container-accordion',
		'step',
		'container-steps',
		'person-profile',
		// 'call-to-action',         -> VC element is not compatible with the widget used in RepairPress theme (because of subtitle field)
		// 'counter',                -> in RepairPress theme the counter is a bit different (no icon is used), so we created a custom counter
	);

	foreach ( $repairpress_custom_vc_shortcodes as $file ) {
		RepairPressHelpers::load_file( sprintf( '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/shortcodes/vc-%s.php', $file ) );
	}
}