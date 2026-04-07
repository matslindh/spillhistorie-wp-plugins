<?php
/**
 * Core rendering logic for the notices list.
 *
 * sh_notices_render( $args ) – returns HTML string.
 * sh_notices_display( $args ) – echoes the result.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sh_notices_external_links( string $html ) : string {
    return preg_replace(
            '/<a\s/i',
            '<a target="_blank" rel="noopener noreferrer" ',
            $html
    );
}

/**
 * Build and return the notices HTML.
 *
 * @param array $args {
 *   @type int    $count          Number of notices to show. Default: option value or 5.
 *   @type bool   $show_excerpt   Show excerpt. Default: option value or true.
 *   @type bool   $show_date      Show date. Default: option value or true.
 *   @type bool   $show_thumbnail Show thumbnail. Default: option value or true.
 *   @type string $title          Widget / section title. Default: 'Notices'.
 * }
 * @return string HTML output.
 */
function sh_notices_render( array $args = [] ) : string {
	$defaults = [
		'count'          => (int) get_option( 'sh_notices_count', 5 ),
		'show_excerpt'   => (bool) get_option( 'sh_notices_show_excerpt', 1 ),
		'show_date'      => (bool) get_option( 'sh_notices_show_date', 1 ),
		'show_thumbnail' => (bool) get_option( 'sh_notices_show_thumbnail', 1 ),
		'title'          => '',
        'go_to_archives_link_text' => get_option('sh_notices_go_to_archive_link_text', 'Archives'),
	];

	$args = wp_parse_args( $args, $defaults );

	$query = new WP_Query( [
		'post_type'      => 'sh_notice',
		'posts_per_page' => absint( $args['count'] ),
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	] );

	if ( ! $query->have_posts() ) {
		return '';
	}

	ob_start();
?>
    <?php if ( !empty( $args['wrapper_class'] ) ) : ?>
        <div class="<?php echo ( $args['wrapper_class'] ); ?>">
    <?php endif; ?>
    <div class="sh-notices-wrap <?php echo ( $args['inner_class'] ?? '' ); ?>">
		<?php if ( ! empty( $args['title'] ) ) : ?>
			<h2 class="sh-notices-heading"><?php echo esc_html( $args['title'] ); ?></h2>
		<?php endif; ?>

		<ul class="sh-notices-list">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<li class="sh-notice-item<?php echo has_post_thumbnail() && $args['show_thumbnail'] ? ' sh-notice-item--has-thumbnail' : ''; ?>">

					<?php if ( has_post_thumbnail() && $args['show_thumbnail'] ) : ?>
						<span class="sh-notice-thumbnail">
							<?php the_post_thumbnail( 'thumbnail', [ 'loading' => 'lazy' ] ); ?>
						</span>
					<?php endif; ?>

					<div class="sh-notice-body">
						<p class="sh-notice-title">
							<?php the_title(); ?>
						</p>

                        <?php if ( $args['show_date'] ) : ?>
                            <time class="sh-notice-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                <?php echo esc_html( get_the_date() ); ?>
                            </time>
                        <?php endif; ?>

						<?php if ( $args['show_excerpt'] && get_the_excerpt() ) : ?>
							<div class="sh-notice-excerpt"><?php echo wp_kses_post( sh_notices_external_links( get_the_content() ) ); ?></div>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>

        <div style="text-align: right;">
            <a href="<?php echo esc_url(get_post_type_archive_link( 'sh_notice' )); ?>"><?php echo esc_html( $args['go_to_archives_link_text'] ); ?> &rarr;</a>
        </div>
	</div>
    <?php if ( !empty( $args['wrapper_class'] ) ) : ?>
        </div>
    <?php endif; ?>
	<?php
	return ob_get_clean();
}

/**
 * Echo the notices HTML.
 *
 * @param array $args Same as sh_notices_render().
 */
function sh_notices_display( array $args = [] ) : void {
	echo sh_notices_render( $args ); // phpcs:ignore WordPress.Security.EscapeOutput
}
