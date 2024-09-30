<?php
/**
 * Register sidebars for RepairPress
 *
 * @package RepairPress
 */

function repairpress_sidebars() {
	// Blog Sidebar
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Blog Sidebar', 'backend', 'repairpress-pt' ),
			'id'            => 'blog-sidebar',
			'description'   => esc_html_x( 'Sidebar on the blog layout.', 'backend', 'repairpress-pt' ),
			'class'         => 'blog  sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// Regular Page Sidebar
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Regular Page Sidebar', 'backend', 'repairpress-pt' ),
			'id'            => 'regular-page-sidebar',
			'description'   => esc_html_x( 'Sidebar on the regular page.', 'backend', 'repairpress-pt' ),
			'class'         => 'sidebar',
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="sidebar__headings">',
			'after_title'   => '</h4>',
		)
	);

	// woocommerce shop sidebar
	if ( RepairPressHelpers::is_woocommerce_active() ) {
		register_sidebar(
			array(
				'name'          => esc_html_x( 'Shop Sidebar', 'backend' , 'repairpress-pt' ),
				'id'            => 'shop-sidebar',
				'description'   => esc_html_x( 'Sidebar for the shop page', 'backend' , 'repairpress-pt' ),
				'class'         => 'sidebar',
				'before_widget' => '<div class="widget  %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="sidebar__headings">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Header
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Header', 'backend', 'repairpress-pt' ),
			'id'            => 'header-widgets',
			'description'   => esc_html_x( 'Header widget area for Icon Box and Buttons.', 'backend', 'repairpress-pt' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Navigation
	register_sidebar(
		array(
			'name'          => esc_html_x( 'Navigation', 'backend', 'repairpress-pt' ),
			'id'            => 'navigation-widgets',
			'description'   => esc_html_x( 'Widgets next to the main menu. Anticipated for the Social Icons widget. If no widgets here, menu will have more space (fullwidth).', 'backend', 'repairpress-pt' ),
			'before_widget' => '<div class="widget  %2$s">',
			'after_widget'  => '</div>',
		)
	);

	// Footer
	$footer_widgets_num = count( RepairPressHelpers::footer_widgets_layout_array() );

	// only register if not 0
	if ( $footer_widgets_num > 0 ) {
		register_sidebar(
			array(
				'name'          => esc_html_x( 'Footer', 'backend', 'repairpress-pt' ),
				'id'            => 'footer-widgets',
				'description'   => sprintf( esc_html_x( 'Footer area works best with %d widgets. This number can be changed in the Appearance &rarr; Customize &rarr; Theme Options &rarr; Footer.', 'backend', 'repairpress-pt' ), $footer_widgets_num ),
				'before_widget' => '<div class="col-xs-12  col-md-__col-num__"><div class="widget  %2$s">', // __col-num__ is replaced dynamically in filter 'dynamic_sidebar_params'
				'after_widget'  => '</div></div>',
				'before_title'  => '<h6 class="footer-top__headings">',
				'after_title'   => '</h6>',
			)
		);
	}
}
add_action( 'widgets_init', 'repairpress_sidebars' );