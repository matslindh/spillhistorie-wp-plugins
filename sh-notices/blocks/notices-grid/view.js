/**
 * SH Notices – Notices Grid block – view script (view.js)
 *
 * Loaded only on the front end when the block is present on the page.
 * Handles progressive-enhancement features:
 *   • Intersection Observer for scroll-triggered reveal (replaces CSS animation
 *     for browsers that support it, giving a proper scroll-based trigger).
 */

( function () {
	'use strict';

	const grids = document.querySelectorAll( '.sh-notices-grid' );
	if ( ! grids.length ) return;

	// If the browser doesn't support IntersectionObserver, the CSS animation
	// defined in style.css handles the reveal automatically — no JS needed.
	if ( ! ( 'IntersectionObserver' in window ) ) return;

	grids.forEach( function ( grid ) {
		const cards = grid.querySelectorAll( '.sh-notices-grid__card' );
		if ( ! cards.length ) return;

		// Reset the CSS animation so we can retrigger it on scroll.
		cards.forEach( function ( card ) {
			card.style.animationPlayState = 'paused';
			card.style.opacity = '0';
		} );

		const observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.style.opacity = '';
						entry.target.style.animationPlayState = 'running';
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.15 }
		);

		cards.forEach( function ( card ) {
			observer.observe( card );
		} );
	} );
} )();
