<?php
/**
 * Register the "Notice" custom post type.
 *
 * Supports all standard post fields (title, editor, excerpt, thumbnail,
 * revisions, author, comments) so it behaves like a regular post in the
 * admin and with the block editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sh_notices_register_post_type() {
	$labels = [
		'name'                  => _x( 'Notices', 'post type general name', 'sh-notices' ),
		'singular_name'         => _x( 'Notice', 'post type singular name', 'sh-notices' ),
		'menu_name'             => _x( 'Notices', 'admin menu', 'sh-notices' ),
		'name_admin_bar'        => _x( 'Notice', 'add new on toolbar', 'sh-notices' ),
		'add_new'               => __( 'Add New', 'sh-notices' ),
		'add_new_item'          => __( 'Add New Notice', 'sh-notices' ),
		'new_item'              => __( 'New Notice', 'sh-notices' ),
		'edit_item'             => __( 'Edit Notice', 'sh-notices' ),
		'view_item'             => __( 'View Notice', 'sh-notices' ),
		'all_items'             => __( 'All Notices', 'sh-notices' ),
		'search_items'          => __( 'Search Notices', 'sh-notices' ),
		'parent_item_colon'     => __( 'Parent Notices:', 'sh-notices' ),
		'not_found'             => __( 'No notices found.', 'sh-notices' ),
		'not_found_in_trash'    => __( 'No notices found in Trash.', 'sh-notices' ),
		'featured_image'        => __( 'Notice Image', 'sh-notices' ),
		'set_featured_image'    => __( 'Set notice image', 'sh-notices' ),
		'remove_featured_image' => __( 'Remove notice image', 'sh-notices' ),
		'use_featured_image'    => __( 'Use as notice image', 'sh-notices' ),
		'archives'              => __( 'Notice archives', 'sh-notices' ),
		'attributes'            => __( 'Notice attributes', 'sh-notices' ),
		'insert_into_item'      => __( 'Insert into notice', 'sh-notices' ),
		'uploaded_to_this_item' => __( 'Uploaded to this notice', 'sh-notices' ),
		'items_list'            => __( 'Notices list', 'sh-notices' ),
		'items_list_navigation' => __( 'Notices list navigation', 'sh-notices' ),
		'filter_items_list'     => __( 'Filter notices list', 'sh-notices' ),
	];

	$args = [
		'labels'             => $labels,
		'description'        => __( 'Short notices with a title and description.', 'sh-notices' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'notices' ],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-megaphone',
		'show_in_rest'       => true,   // enables the block editor
		'supports'           => [
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'revisions',
			'author',
			'comments',
			'custom-fields',
		],
	];

	register_post_type( 'sh_notice', $args );
}
add_action( 'init', 'sh_notices_register_post_type' );

/**
 * Flush rewrite rules on activation so /notices/ works immediately.
 */
function sh_notices_activate() {
	sh_notices_register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( SH_NOTICES_DIR . 'sh-notices.php', 'sh_notices_activate' );

/**
 * Flush rewrite rules on deactivation.
 */
function sh_notices_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( SH_NOTICES_DIR . 'sh-notices.php', 'sh_notices_deactivate' );
