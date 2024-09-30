<?php
/**
 * The Header for RepairPress Theme
 *
 * @package RepairPress
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php endif; ?>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class( RepairPressHelpers::add_body_class() ); ?>>
	<?php wp_body_open(); ?>
	<div class="boxed-container">

	<header>
		<?php if ( 'no' !== get_theme_mod( 'top_bar_visibility', 'yes' ) ) : ?>
		<div class="top<?php echo 'hide_mobile' === get_theme_mod( 'top_bar_visibility', 'yes' ) ? '  hidden-xs  hidden-sm' : ''; ?>">
			<div class="container">
				<div class="top__tagline">
					<?php bloginfo( 'description' ); ?>
				</div>
				<!-- Top Menu -->
				<nav class="top__menu" aria-label="<?php esc_html_e( 'Top Menu', 'repairpress-pt' ); ?>">
					<?php
					if ( has_nav_menu( 'top-bar-menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'top-bar-menu',
							'container'      => false,
							'menu_class'     => 'top-navigation  js-dropdown',
							'walker'         => new Aria_Walker_Nav_Menu(),
							'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
						) );
					}
					?>
				</nav>
			</div>
		</div>
	<?php endif; ?>

		<div class="header__container">
			<div class="container">
				<div class="header<?php echo is_active_sidebar( 'navigation-widgets' ) ? '' : '  header--no-nav-widgets'; ?>">
					<div class="header__logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php
							$repairpress_logo   = get_theme_mod( 'logo_img', false );
							$repairpress_logo2x = get_theme_mod( 'logo2x_img', false );

							if ( ! empty( $repairpress_logo ) ) :
							?>
								<img src="<?php echo esc_url( $repairpress_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo esc_attr( $repairpress_logo ); ?><?php echo empty ( $repairpress_logo2x ) ? '' : ', ' . esc_url( $repairpress_logo2x ) . ' 2x'; ?>" class="img-responsive" <?php echo RepairPressHelpers::get_logo_dimensions(); ?> />
							<?php
							else :
							?>
								<h1><?php bloginfo( 'name' ); ?></h1>
							<?php
							endif;
							?>
						</a>
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#repairpress-navbar-collapse">
							<span class="navbar-toggle__text"><?php esc_html_e( 'MENU', 'repairpress-pt' ); ?></span>
							<span class="navbar-toggle__icon-bar">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</span>
						</button>
					</div>
					<div class="header__navigation  js-sticky-offset">
						<nav class="collapse  navbar-collapse" aria-label="<?php esc_html_e( 'Main Menu', 'repairpress-pt' ); ?>" id="repairpress-navbar-collapse">
							<!-- Home Icon in Navigation -->
							<?php if ( 'yes' === get_theme_mod( 'main_navigation_home_icon', 'yes' ) ) : ?>
							<a class="home-icon" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<i class="fa fa-home"></i>
							</a>
							<?php endif;

							if ( has_nav_menu( 'main-menu' ) ) {
								wp_nav_menu( array(
									'theme_location' => 'main-menu',
									'container'      => false,
									'menu_class'     => 'main-navigation  js-main-nav',
									'walker'         => new Aria_Walker_Nav_Menu(),
									'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
								) );
							}
							?>
						</nav>
					</div>
					<div class="header__widgets">
						<?php
						if ( is_active_sidebar( 'header-widgets' ) ) {
							dynamic_sidebar( 'header-widgets' );
						}
						?>
					</div>
					<?php if ( is_active_sidebar( 'navigation-widgets' ) ) : ?>
					<div class="header__navigation-widgets">
						<?php dynamic_sidebar( 'navigation-widgets' ); ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>