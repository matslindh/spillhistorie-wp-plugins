<?php
/**
 * Register Gutenberg blocks provided by SH Notices.
 *
 * Uses register_block_type() with the block.json metadata API (WP 5.8+).
 * Each block directory must contain a block.json file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sh_notices_register_blocks() {
	$blocks_dir = SH_NOTICES_DIR . 'blocks/';

	// Register every block that has a block.json.
	foreach ( glob( $blocks_dir . '*/block.json' ) as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
}
add_action( 'init', 'sh_notices_register_blocks' );
