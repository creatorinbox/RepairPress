<?php

$repairpress_blog_id    = absint( get_option( 'page_for_posts' ) );
$repairpress_style_attr = '';
$repairpress_shop_id    = absint( get_option( 'woocommerce_shop_page_id', 0 ) );

// custom bg
$repairpress_bg_id = get_the_ID();

if ( is_home() || is_singular( 'post' ) ) {
	$repairpress_bg_id = $repairpress_blog_id;
}

// woocommerce
if ( RepairPressHelpers::is_woocommerce_active() && is_woocommerce() ) {
	$repairpress_bg_id = $repairpress_shop_id;
}

$repairpress_style_array = array();

if ( get_field( 'background_image', $repairpress_bg_id ) ) {
	$repairpress_style_array = array(
		'background-image'      => get_field( 'background_image', $repairpress_bg_id ),
		'background-position'   => get_field( 'background_image_horizontal_position', $repairpress_bg_id ) . ' ' . get_field( 'background_image_vertical_position', $repairpress_bg_id ),
		'background-repeat'     => get_field( 'background_image_repeat', $repairpress_bg_id ),
		'background-attachment' => get_field( 'background_image_attachment', $repairpress_bg_id ),
	);
}

$repairpress_style_array['background-color'] = get_field( 'background_color', $repairpress_bg_id );

$repairpress_style_attr = RepairPressHelpers::create_background_style_attr( $repairpress_style_array );

?>

<div class="main-title" style="<?php echo esc_attr( $repairpress_style_attr ); ?>">
	<div class="container">
		<?php
		$repairpress_main_tag = 'h1';
		$repairpress_subtitle = false;

		if ( is_home() || ( is_single() && 'post' === get_post_type() ) ) {
			$repairpress_title    = get_the_title( $repairpress_blog_id );
			$repairpress_subtitle = get_field( 'subtitle', $repairpress_blog_id );

			if ( is_single() ) {
				$repairpress_main_tag = 'h2';
			}
		} elseif ( RepairPressHelpers::is_woocommerce_active() && is_woocommerce() ) {
				ob_start();
				woocommerce_page_title();
				$repairpress_title    = ob_get_clean();
				$repairpress_subtitle = get_field( 'subtitle', (int) get_option( 'woocommerce_shop_page_id' ) );

				if ( is_product() ) {
					$repairpress_main_tag = 'h2';
				}
		} elseif ( is_category() || is_tag() || is_author() || is_post_type_archive() || is_tax() || is_day() || is_month() || is_year() ) {
			$repairpress_title = get_the_archive_title();
		} elseif ( is_search() ) {
			$repairpress_title = esc_html__( 'Search Results For' , 'repairpress-pt' ) . ' &quot;' . get_search_query() . '&quot;';
		} elseif ( is_404() ) {
			$repairpress_title = esc_html__( 'Error 404' , 'repairpress-pt' );
		} else {
			$repairpress_title    = get_the_title();
			$repairpress_subtitle = get_field( 'subtitle' );
		}

		?>

		<?php printf( '<%1$s class="main-title__primary">%2$s</%1$s>', wp_kses_post( $repairpress_main_tag ), wp_kses_post( $repairpress_title ) ); ?>

		<?php if ( $repairpress_subtitle ) : ?>
			<h3 class="main-title__secondary"><?php echo esc_html( $repairpress_subtitle ); ?></h3>
		<?php endif; ?>
	</div>
</div>