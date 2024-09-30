<?php
/**
 * Add the link to documentation under Appearance in the wp-admin
 */

if ( ! function_exists( 'repairpress_add_docs_page' ) ) {
	function repairpress_add_docs_page() {
		add_theme_page(
			_x( 'Documentation', 'backend', 'repairpress-pt' ),
			_x( 'Documentation', 'backend', 'repairpress-pt' ),
			'',
			'proteusthemes-theme-docs',
			'repairpress_docs_page_output'
		);
	}
	add_action( 'admin_menu', 'repairpress_add_docs_page' );

	function repairpress_docs_page_output() {
		?>
		<div class="wrap">
			<h2><?php _ex( 'Documentation', 'backend', 'repairpress-pt' ); ?></h2>

			<p>
				<strong><a href="https://www.proteusthemes.com/docs/repairpress-pt/" class="button button-primary " target="_blank"><?php _ex( 'Click here to see online documentation of the theme!', 'backend', 'repairpress-pt' ); ?></a></strong>
			</p>
		</div>
		<?php
	}
}