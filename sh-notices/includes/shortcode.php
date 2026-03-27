<?php
/**
 * [sh_notices] shortcode.
 *
 * Attributes (all optional, fall back to plugin Settings values):
 *   count          – number of notices to show
 *   show_excerpt   – 0 or 1
 *   show_date      – 0 or 1
 *   show_thumbnail – 0 or 1
 *   title          – heading rendered above the list
 *
 * Examples:
 *   [sh_notices]
 *   [sh_notices count="3" show_date="0" title="Latest Notices"]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sh_notices_shortcode( $atts ) {
	$atts = shortcode_atts(
		[
			'count'          => null,
			'show_excerpt'   => null,
			'show_date'      => null,
			'show_thumbnail' => null,
			'title'          => '',
		],
		$atts,
		'sh_notices'
	);

	$args = [];

	if ( ! is_null( $atts['count'] ) ) {
		$args['count'] = absint( $atts['count'] );
	}
	if ( ! is_null( $atts['show_excerpt'] ) ) {
		$args['show_excerpt'] = (bool) $atts['show_excerpt'];
	}
	if ( ! is_null( $atts['show_date'] ) ) {
		$args['show_date'] = (bool) $atts['show_date'];
	}
	if ( ! is_null( $atts['show_thumbnail'] ) ) {
		$args['show_thumbnail'] = (bool) $atts['show_thumbnail'];
	}
	if ( ! empty( $atts['title'] ) ) {
		$args['title'] = sanitize_text_field( $atts['title'] );
	}

	return sh_notices_render( $args );
}
add_shortcode( 'sh_notices', 'sh_notices_shortcode' );
