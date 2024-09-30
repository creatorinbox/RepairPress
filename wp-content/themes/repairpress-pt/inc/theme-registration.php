<?php
/**
 * Register automatic updates for this theme.
 */

use ProteusThemes\ThemeRegistration\ThemeRegistration;

class RepairPressThemeRegistration {
	function __construct() {
		$this->enable_theme_registration();
	}

	/**
	 * Load theme registration and automatic updates.
	 */
	private function enable_theme_registration() {
		$config = array(
			'item_name'        => 'RepairPress',
			'theme_slug'       => 'repairpress-pt',
			'item_id'          => 2805,
			'tf_item_id'       => 13065600,
			'customizer_panel' => 'panel_repairpress',
			'build'            => 'pt',
		);
		$pt_theme_registration = ThemeRegistration::get_instance( $config );
	}
}

if ( ! REPAIRPRESS_DEVELOPMENT && ! defined( 'ENVATO_HOSTED_SITE' ) ) {
	$repairpress_theme_registration = new RepairPressThemeRegistration();
}
