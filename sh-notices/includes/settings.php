<?php
/**
 * Settings page for SH Notices.
 *
 * Options
 * -------
 * sh_notices_count          – how many notices to display (default 5)
 * sh_notices_show_excerpt   – show excerpt below title (default 1)
 * sh_notices_show_date      – show post date (default 1)
 * sh_notices_show_thumbnail – show featured image when present (default 1)
 * sh_notices_gp_hook        – which GeneratePress hook to auto-inject into
 * sh_notices_gp_priority    – action priority for the hook (default 10)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sh_notices_register_settings() {
	register_setting( 'sh_notices_options', 'sh_notices_count', [
		'type'              => 'integer',
		'default'           => 5,
		'sanitize_callback' => 'absint',
	] );
	register_setting( 'sh_notices_options', 'sh_notices_show_excerpt', [
		'type'              => 'integer',
		'default'           => 1,
		'sanitize_callback' => 'absint',
	] );
	register_setting( 'sh_notices_options', 'sh_notices_show_date', [
		'type'              => 'integer',
		'default'           => 1,
		'sanitize_callback' => 'absint',
	] );
	register_setting( 'sh_notices_options', 'sh_notices_show_thumbnail', [
		'type'              => 'integer',
		'default'           => 1,
		'sanitize_callback' => 'absint',
	] );
	register_setting( 'sh_notices_options', 'sh_notices_gp_hook', [
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'sanitize_key',
	] );
	register_setting( 'sh_notices_options', 'sh_notices_gp_priority', [
		'type'              => 'integer',
		'default'           => 10,
		'sanitize_callback' => 'absint',
	] );

	add_settings_section(
		'sh_notices_general',
		__( 'Display Settings', 'sh-notices' ),
		null,
		'sh-notices'
	);

	add_settings_field( 'sh_notices_count', __( 'Number of notices', 'sh-notices' ),
		'sh_notices_field_count', 'sh-notices', 'sh_notices_general' );
	add_settings_field( 'sh_notices_show_excerpt', __( 'Show excerpt', 'sh-notices' ),
		'sh_notices_field_show_excerpt', 'sh-notices', 'sh_notices_general' );
	add_settings_field( 'sh_notices_show_date', __( 'Show date', 'sh-notices' ),
		'sh_notices_field_show_date', 'sh-notices', 'sh_notices_general' );
	add_settings_field( 'sh_notices_show_thumbnail', __( 'Show featured image', 'sh-notices' ),
		'sh_notices_field_show_thumbnail', 'sh-notices', 'sh_notices_general' );

	add_settings_section(
		'sh_notices_gp',
		__( 'GeneratePress Hook Integration', 'sh-notices' ),
		function() {
			echo '<p>' . esc_html__( 'Auto-inject the notices list into any GeneratePress action hook. Leave blank to disable auto-injection (use the widget or shortcode instead).', 'sh-notices' ) . '</p>';
		},
		'sh-notices'
	);

	add_settings_field( 'sh_notices_gp_hook', __( 'Hook name', 'sh-notices' ),
		'sh_notices_field_gp_hook', 'sh-notices', 'sh_notices_gp' );
	add_settings_field( 'sh_notices_gp_priority', __( 'Hook priority', 'sh-notices' ),
		'sh_notices_field_gp_priority', 'sh-notices', 'sh_notices_gp' );

    register_setting( 'sh_notices_options', 'sh_notices_inject_on_front', [
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
    ] );

    register_setting( 'sh_notices_options', 'sh_notices_inject_after_n', [
            'type'              => 'integer',
            'default'           => 3,
            'sanitize_callback' => 'absint',
    ] );

    add_settings_section(
            'sh_notices_front',
            __( 'Front Page Loop Injection', 'sh-notices' ),
            function () {
                echo '<p>' . esc_html__(
                                'Inject the notices widget into the front page post loop after a specific article.',
                                'sh-notices'
                        ) . '</p>';
            },
            'sh-notices'
    );

    register_setting( 'sh_notices_options', 'sh_notices_go_to_archive_link_text', [
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
    ] );

    add_settings_field(
            'sh_notices_go_to_archive_link_text',
            __( 'Link text for "go to archive link"', 'sh-notices' ),
            'sh_notices_field_go_to_archive_link_text',
            'sh-notices',
            'sh_notices_general'
    );


    register_setting( 'sh_notices_options', 'sh_notices_grid_heading', [
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
    ] );

    add_settings_field(
            'sh_notices_grid_heading',
            __( 'Default grid heading', 'sh-notices' ),
            'sh_notices_field_grid_heading',
            'sh-notices',
            'sh_notices_general'
    );

    add_settings_field(
            'sh_notices_inject_on_front',
            __( 'Enable injection', 'sh-notices' ),
            'sh_notices_field_inject_on_front',
            'sh-notices',
            'sh_notices_front'
    );

    add_settings_field(
            'sh_notices_inject_after_n',
            __( 'Inject after article', 'sh-notices' ),
            'sh_notices_field_inject_after_n',
            'sh-notices',
            'sh_notices_front'
    );

}
add_action( 'admin_init', 'sh_notices_register_settings' );

/* ── Field renderers ──────────────────────────────────────────────────── */

function sh_notices_field_count() {
	$v = (int) get_option( 'sh_notices_count', 5 );
	echo '<input type="number" min="1" max="50" name="sh_notices_count" value="' . esc_attr( $v ) . '" class="small-text">';
}

function sh_notices_field_show_excerpt() {
	$v = get_option( 'sh_notices_show_excerpt', 1 );
	echo '<input type="checkbox" id="sh-notices-show-excerpt" name="sh_notices_show_excerpt" value="1"' . checked( 1, $v, false ) . '> ';
	echo '<label for="sh-notices-show-excerpt">' . esc_html__( 'Display the excerpt beneath the notice title', 'sh-notices' ) . '</label>';
}

function sh_notices_field_show_date() {
	$v = get_option( 'sh_notices_show_date', 1 );
	echo '<input type="checkbox" id="show-notices-show-date" name="sh_notices_show_date" value="1"' . checked( 1, $v, false ) . '> ';
	echo '<label for="show-notices-show-date">' . esc_html__( 'Display the publication date', 'sh-notices' ) . '</label>';
}

function sh_notices_field_show_thumbnail() {
	$v = get_option( 'sh_notices_show_thumbnail', 1 );
	echo '<input type="checkbox" id="sh-notices-show-thumbnail" name="sh_notices_show_thumbnail" value="1"' . checked( 1, $v, false ) . '> ';
	echo '<label for="sh-notices-show-thumbnail">' . esc_html__( 'Display the featured image when set', 'sh-notices' ) . '</label>';
}

function sh_notices_field_gp_hook() {
	$v = get_option( 'sh_notices_gp_hook', '' );

	$hooks = [
		''                                         => __( '— Disabled —', 'sh-notices' ),
		'generate_before_header'                   => 'generate_before_header',
		'generate_after_header'                    => 'generate_after_header',
		'generate_before_main_content'             => 'generate_before_main_content',
		'generate_after_main_content'              => 'generate_after_main_content',
		'generate_before_content'                  => 'generate_before_content',
		'generate_after_content'                   => 'generate_after_content',
		'generate_before_right_sidebar_content'    => 'generate_before_right_sidebar_content',
		'generate_after_right_sidebar_content'     => 'generate_after_right_sidebar_content',
		'generate_before_footer'                   => 'generate_before_footer',
		'generate_after_footer'                    => 'generate_after_footer',
	];

	echo '<select name="sh_notices_gp_hook">';
	foreach ( $hooks as $hook_key => $hook_label ) {
		echo '<option value="' . esc_attr( $hook_key ) . '"' . selected( $v, $hook_key, false ) . '>' . esc_html( $hook_label ) . '</option>';
	}
	echo '</select>';
}

function sh_notices_field_gp_priority() {
	$v = (int) get_option( 'sh_notices_gp_priority', 10 );
	echo '<input type="number" min="1" max="999" name="sh_notices_gp_priority" value="' . esc_attr( $v ) . '" class="small-text">';
}

function sh_notices_field_inject_on_front() {
    $v = get_option( 'sh_notices_inject_on_front', 0 );
    echo '<input type="checkbox" name="sh_notices_inject_on_front" id="sh-notices-inject-on-front" value="1"' . checked( 1, $v, false ) . '> ';
    echo '<label for="sh-notices-inject-on-front">' . esc_html__( 'Show notices widget inside the front page post loop', 'sh-notices' ) . '</label>';
}

function sh_notices_field_inject_after_n() {
    $v = (int) get_option( 'sh_notices_inject_after_n', 3 );
    echo '<select name="sh_notices_inject_after_n">';
    foreach ( [ 1, 2, 3, 4, 5 ] as $n ) {
        echo '<option value="' . esc_attr( $n ) . '"' . selected( $v, $n, false ) . '>'
                . sprintf( esc_html__( 'After article %d', 'sh-notices' ), $n )
                . '</option>';
    }
    echo '</select>';
}

function sh_notices_field_go_to_archive_link_text() {
    $v = get_option( 'sh_notices_go_to_archive_link_text' , '' );
    echo '<input type="text" class="regular-text" name="sh_notices_go_to_archive_link_text" value="' . esc_attr( $v ) . '">';
    echo '<p class="description">' . esc_html__( 'Shown as the link text on the archive link.', 'sh-notices' ) . '</p>';
}

function sh_notices_field_grid_heading() {
    $v = get_option( 'sh_notices_grid_heading', '' );
    echo '<input type="text" class="regular-text" name="sh_notices_grid_heading" value="' . esc_attr( $v ) . '">';
    echo '<p class="description">' . esc_html__( 'Shown above the notices grid block when no heading is set on the block itself. Leave blank to show no heading.', 'sh-notices' ) . '</p>';
}


/* ── Admin menu ───────────────────────────────────────────────────────── */

function sh_notices_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=sh_notice',
		__( 'SH Notices Settings', 'sh-notices' ),
		__( 'Settings', 'sh-notices' ),
		'manage_options',
		'sh-notices-settings',
		'sh_notices_settings_page'
	);
}
add_action( 'admin_menu', 'sh_notices_admin_menu' );

function sh_notices_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sh_notices_options' );
			do_settings_sections( 'sh-notices' );
			submit_button();
			?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Usage', 'sh-notices' ); ?></h2>
		<table class="widefat" style="max-width:700px">
			<tbody>
				<tr>
					<td><strong><?php esc_html_e( 'Shortcode', 'sh-notices' ); ?></strong></td>
					<td><code>[sh_notices]</code><br>
					<code>[sh_notices count="3" show_excerpt="1" show_date="0" show_thumbnail="1"]</code></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Widget', 'sh-notices' ); ?></strong></td>
					<td><?php esc_html_e( 'Add the "SH Notices" widget to any sidebar via Appearance → Widgets.', 'sh-notices' ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'GeneratePress hook', 'sh-notices' ); ?></strong></td>
					<td><?php esc_html_e( 'Select a hook above to auto-inject notices globally, or use generate_do_element_action() in your child-theme for per-page control.', 'sh-notices' ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'PHP function', 'sh-notices' ); ?></strong></td>
					<td><code>sh_notices_render( [ 'count' => 5 ] );</code></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}
