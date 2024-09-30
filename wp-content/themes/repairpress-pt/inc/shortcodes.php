<?php
/**
 * Shortcodes for RepairPress WP theme defined
 *
 * @package RepairPress
 */


/**
 * Shortcode for Font Awesome
 * @param  array $atts
 * @return string HTML
 */
if ( ! function_exists( 'repairpress_fa_shortcode' ) && ! shortcode_exists( 'fa' ) ) {
	function repairpress_fa_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'icon'   => 'fa-home',
			'href'   => '',
			'color'  => '',
			'target' => '_self',
		), $atts ) );

		if ( empty( $href ) ) {
			return '<span class="icon-container"><span class="fa ' . esc_attr( strtolower( $icon ) ) . '" ' . ( ! empty( $color ) ? 'style="color:' . esc_attr( $color ) . ';"' : '' ) . '></span></span>';
		}
		else {
			return '<a class="icon-container" href="' . esc_url( $href ) . '" target="' . esc_attr( $target ) . '"><span class="fa ' . esc_attr( strtolower( $icon ) ) . '" ' . ( ! empty( $color ) ? 'style="color:' . esc_attr( $color ) . ';"' : '' ) . '></span></a>';
		}
	}
	add_shortcode( 'fa', 'repairpress_fa_shortcode' );
}


/**
 * Shortcode for Buttons
 * @param  array $atts
 * @return string HTML
 */
if ( ! function_exists( 'repairpress_button_shortcode' ) && ! shortcode_exists( 'button' ) ) {
	function repairpress_button_shortcode( $atts, $content = '' ) {
		extract( shortcode_atts( array(
			'style'     => 'primary',
			'href'      => '#',
			'target'    => '_self',
			'corners'   => '',
			'fa'        => null,
			'fullwidth' => false,
		), $atts ) );

		return '<a class="btn  ' . ( 'rounded' == $corners  ? 'btn-rounded' : '' ) . '  btn-' . esc_attr( strtolower( $style ) ) . ( 'true' == $fullwidth  ? '  col-xs-12' : '' ) . '" href="' . esc_url( $href ) . '" target="' . esc_attr( $target ) . '">' . ( isset( $fa )  ? '<i class="fa ' . $fa . '"></i> ' : '') . $content . '</a>';
	}
	add_shortcode( 'button', 'repairpress_button_shortcode' );
}