<?php
/**
 * Plugin Name: SH Notices
 * Plugin URI:  https://www.spillhistorie.no/
 * Description: Adds a Notices custom post type with GeneratePress sidebar and hook integration.
 * Version:     1.1.0
 * Author:      Claude, Mats Lindh <mats@lindh.no>
 * License:     GPL-2.0-or-later
 * Text Domain: sh-notices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SH_NOTICES_DIR', plugin_dir_path( __FILE__ ) );
define( 'SH_NOTICES_URL', plugin_dir_url( __FILE__ ) );
define( 'SH_NOTICES_VERSION', '1.1.0' );

require_once SH_NOTICES_DIR . 'includes/post-type.php';
require_once SH_NOTICES_DIR . 'includes/renderer.php';
require_once SH_NOTICES_DIR . 'includes/widget.php';
require_once SH_NOTICES_DIR . 'includes/generatepress.php';
require_once SH_NOTICES_DIR . 'includes/shortcode.php';
require_once SH_NOTICES_DIR . 'includes/settings.php';
require_once SH_NOTICES_DIR . 'includes/blocks.php';

/**
 * Enqueue front-end assets.
 */
function sh_notices_enqueue_assets() {
	wp_enqueue_style(
		'sh-notices',
		SH_NOTICES_URL . 'assets/css/sh-notices.css',
		[],
		SH_NOTICES_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sh_notices_enqueue_assets' );
