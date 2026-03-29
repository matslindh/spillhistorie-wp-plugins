<?php
/**
 * GeneratePress hook integration.
 *
 * 1. Reads the hook name stored in settings and auto-injects the notices list.
 * 2. Registers a GeneratePress "Elements" hook location so editors can also
 *    target it via the GP Elements block editor (GP Premium ≥ 2.0).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Auto-inject notices into the GP hook selected in Settings → Notices.
 *
 * Runs after `init` so options are available and the post type is registered.
 */
function sh_notices_gp_auto_inject() {
	$hook = get_option( 'sh_notices_gp_hook', '' );

	if ( empty( $hook ) ) {
		return;
	}

	$priority = (int) get_option( 'sh_notices_gp_priority', 10 );

	add_action( $hook, 'sh_notices_gp_render', $priority );
}
add_action( 'after_setup_theme', 'sh_notices_gp_auto_inject' );

/**
 * Callback used by the auto-inject hook.
 */
function sh_notices_gp_render() {
	sh_notices_display();
}

/**
 * Register a named hook location inside GeneratePress Elements so that
 * GP Premium users can also add the notices via the block-based Elements UI.
 *
 * The location appears under Appearance → Elements → Add New → Hook.
 */
function sh_notices_gp_register_element_location() {
	if ( ! function_exists( 'generate_do_element_action' ) ) {
		return; // GP Premium not active.
	}

	// This is a passive declaration — it just makes the hook name available
	// in the GP Elements "Hook" dropdown. Actual output is handled by GP.
	do_action( 'generate_hook_location', 'sh_notices_output', __( 'SH Notices Output', 'sh-notices' ) );
}
add_action( 'wp', 'sh_notices_gp_register_element_location' );

/**
 * The action hook that GP Elements targets when a user chooses
 * "sh_notices_output" as their hook location.
 *
 * You can also call this directly in a child-theme template:
 *   do_action( 'sh_notices_output' );
 */
add_action( 'sh_notices_output', 'sh_notices_display' );

/**
 * Right-sidebar helper: renders notices directly inside the GP right sidebar.
 *
 * Usage in a child-theme functions.php:
 *
 *   add_action( 'generate_before_right_sidebar_content', 'sh_notices_in_right_sidebar' );
 *
 * Or enable it from Settings → Notices by selecting
 * "generate_before_right_sidebar_content" (or _after_) as the hook.
 */
function sh_notices_in_right_sidebar() {
	sh_notices_display( [ 'title' => __( 'Notices', 'sh-notices' ) ] );
}


function sh_notices_maybe_inject_in_loop() {
    if ( ! get_option( 'sh_notices_inject_on_front' ) ) {
        return;
    }

    $after_n = (int) get_option( 'sh_notices_inject_after_n', 3 );

    add_action( 'generate_after_do_template_part', function () use ( $after_n ) {
        if ( ! is_home() && ! is_front_page() ) {
            return;
        }
        static $count = 0;
        $count++;
        if ( $count === $after_n ) {
            sh_notices_display( [ 'title' => get_option('sh_notices_grid_heading'), 'wrapper_class' => 'generate-columns grid-100 masonry-post', 'inner_class' => 'inside-article', 'count' => 2 ] );
        }
    } );
}

add_action( 'after_setup_theme', 'sh_notices_maybe_inject_in_loop' );
