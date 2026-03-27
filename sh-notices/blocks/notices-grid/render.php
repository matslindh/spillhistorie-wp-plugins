<?php
/**
 * Server-side render for the sh-notices/notices-grid block.
 *
 * WordPress calls this file every time the block is displayed
 * on the front end (and for the ServerSideRender preview in the editor).
 *
 * Available variables injected by WordPress:
 *   $attributes  – block attributes array
 *   $content     – inner blocks HTML (empty – we don't use inner blocks)
 *   $block       – WP_Block instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count          = isset( $attributes['count'] )          ? absint( $attributes['count'] )             : 3;
$show_excerpt   = isset( $attributes['showExcerpt'] )    ? (bool) $attributes['showExcerpt']           : true;
$show_date      = isset( $attributes['showDate'] )       ? (bool) $attributes['showDate']              : true;
$show_thumbnail = isset( $attributes['showThumbnail'] )  ? (bool) $attributes['showThumbnail']         : true;
$heading        = isset( $attributes['heading'] )        ? sanitize_text_field( $attributes['heading'] ) : '';
$mobile_cols    = isset( $attributes['mobileColumns'] )  ? absint( $attributes['mobileColumns'] )      : 1;

$query = new WP_Query( [
	'post_type'      => 'sh_notice',
	'posts_per_page' => $count,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}

// Build wrapper class list.
$wrapper_classes = [
	'sh-notices-grid',
	'sh-notices-grid--mobile-' . $mobile_cols,
];

// Merge Gutenberg colour / spacing / typography classes added by supports.
if ( ! empty( $attributes['className'] ) ) {
	$wrapper_classes[] = $attributes['className'];
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => implode( ' ', $wrapper_classes ),
] );
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<?php if ( $heading ) : ?>
		<h2 class="sh-notices-grid__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<ul class="sh-notices-grid__list" role="list">
		<?php
		$index = 0;
		while ( $query->have_posts() ) :
			$query->the_post();
			$has_thumb = has_post_thumbnail() && $show_thumbnail;
			$card_classes = 'sh-notices-grid__card';
			if ( $has_thumb ) {
				$card_classes .= ' sh-notices-grid__card--has-image';
			}
			?>
			<li class="<?php echo esc_attr( $card_classes ); ?>"
				style="--card-index:<?php echo (int) $index; ?>">

				<?php if ( $has_thumb ) : ?>
					<a class="sh-notices-grid__image-link"
					   href="<?php the_permalink(); ?>"
					   tabindex="-1"
					   aria-hidden="true">
						<?php the_post_thumbnail( 'medium', [
							'class'   => 'sh-notices-grid__image',
							'loading' => 'lazy',
							'sizes'   => '(max-width: 600px) calc(50vw - 2rem), 33vw',
						] ); ?>
					</a>
				<?php endif; ?>

				<div class="sh-notices-grid__body">
					<p class="sh-notices-grid__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</p>

					<?php if ( $show_excerpt && get_the_excerpt() ) : ?>
						<div class="sh-notices-grid__excerpt">
							<?php echo wp_kses_post( get_the_excerpt() ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $show_date ) : ?>
						<time class="sh-notices-grid__date"
						      datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					<?php endif; ?>
				</div>

			</li>
			<?php
			$index++;
		endwhile;
		wp_reset_postdata();
		?>
	</ul>

</div>
