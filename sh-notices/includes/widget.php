<?php
/**
 * SH_Notices_Widget – a classic WP_Widget for use in any registered sidebar,
 * including the GeneratePress right sidebar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SH_Notices_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'sh_notices_widget',
			__( 'SH Notices', 'sh-notices' ),
			[
				'description'                 => __( 'Display a list of recent Notices.', 'sh-notices' ),
				'customize_selective_refresh' => true,
			]
		);
	}

	/**
	 * Front-end output.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title',
			empty( $instance['title'] ) ? __( 'Notices', 'sh-notices' ) : $instance['title'],
			$instance,
			$this->id_base
		);

		echo $args['before_widget']; // phpcs:ignore

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore
		}

		$render_args = [
			'count'          => ! empty( $instance['count'] ) ? (int) $instance['count'] : null,
			'show_excerpt'   => isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : null,
			'show_date'      => isset( $instance['show_date'] )    ? (bool) $instance['show_date']    : null,
			'show_thumbnail' => isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : null,
		];

		// Remove nulls so defaults fall through to option values.
		$render_args = array_filter( $render_args, fn( $v ) => ! is_null( $v ) );

		sh_notices_display( $render_args );

		echo $args['after_widget']; // phpcs:ignore
	}

	/**
	 * Admin form.
	 */
	public function form( $instance ) {
		$title          = $instance['title']          ?? __( 'Notices', 'sh-notices' );
		$count          = $instance['count']          ?? get_option( 'sh_notices_count', 5 );
		$show_excerpt   = $instance['show_excerpt']   ?? get_option( 'sh_notices_show_excerpt', 1 );
		$show_date      = $instance['show_date']      ?? get_option( 'sh_notices_show_date', 1 );
		$show_thumbnail = $instance['show_thumbnail'] ?? get_option( 'sh_notices_show_thumbnail', 1 );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'sh-notices' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
				<?php esc_html_e( 'Number of notices:', 'sh-notices' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>"
				type="number" step="1" min="1" max="50" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>"
				type="checkbox" value="1" <?php checked( 1, $show_excerpt ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
				<?php esc_html_e( 'Show excerpt', 'sh-notices' ); ?>
			</label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>"
				type="checkbox" value="1" <?php checked( 1, $show_date ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>">
				<?php esc_html_e( 'Show date', 'sh-notices' ); ?>
			</label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>"
				type="checkbox" value="1" <?php checked( 1, $show_thumbnail ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>">
				<?php esc_html_e( 'Show featured image', 'sh-notices' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Sanitise widget options on save.
	 */
	public function update( $new_instance, $old_instance ) {
		return [
			'title'          => sanitize_text_field( $new_instance['title'] ),
			'count'          => absint( $new_instance['count'] ),
			'show_excerpt'   => ! empty( $new_instance['show_excerpt'] ) ? 1 : 0,
			'show_date'      => ! empty( $new_instance['show_date'] )    ? 1 : 0,
			'show_thumbnail' => ! empty( $new_instance['show_thumbnail'] ) ? 1 : 0,
		];
	}
}

function sh_notices_register_widget() {
	register_widget( 'SH_Notices_Widget' );
}
add_action( 'widgets_init', 'sh_notices_register_widget' );
