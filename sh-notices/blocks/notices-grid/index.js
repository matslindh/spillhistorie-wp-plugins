/**
 * SH Notices – Notices Grid block
 * Editor script (index.js)
 *
 * Uses server-side rendering via render.php, so the edit component
 * shows a live ServerSideRender preview while all controls live in
 * the block inspector sidebar.
 */

import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	useBlockProps,
	BlockControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	TextControl,
	SelectControl,
	Placeholder,
	Spinner,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';
import metadata from './block.json';

/**
 * Edit component – rendered only inside the block editor.
 */
function Edit( { attributes, setAttributes } ) {
	const {
		count,
		showExcerpt,
		showDate,
		showThumbnail,
		heading,
		mobileColumns,
	} = attributes;

	// Check whether any notices exist so we can show a helpful placeholder.
	const hasNotices = useSelect( ( select ) => {
		const posts = select( 'core' ).getEntityRecords( 'postType', 'sh_notice', {
			per_page: 1,
			status: 'publish',
		} );
		return posts && posts.length > 0;
	}, [] );

	const blockProps = useBlockProps( {
		className: 'sh-notices-grid-editor-wrap',
	} );

	return (
		<>
			{ /* ── Inspector sidebar ── */ }
			<InspectorControls>
				<PanelBody title={ __( 'Display', 'sh-notices' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Heading', 'sh-notices' ) }
						help={ __( 'Optional heading displayed above the grid.', 'sh-notices' ) }
						value={ heading }
						onChange={ ( val ) => setAttributes( { heading: val } ) }
					/>
					<RangeControl
						label={ __( 'Number of notices', 'sh-notices' ) }
						value={ count }
						onChange={ ( val ) => setAttributes( { count: val } ) }
						min={ 1 }
						max={ 12 }
					/>
					<ToggleControl
						label={ __( 'Show excerpt', 'sh-notices' ) }
						checked={ showExcerpt }
						onChange={ ( val ) => setAttributes( { showExcerpt: val } ) }
					/>
					<ToggleControl
						label={ __( 'Show date', 'sh-notices' ) }
						checked={ showDate }
						onChange={ ( val ) => setAttributes( { showDate: val } ) }
					/>
					<ToggleControl
						label={ __( 'Show featured image', 'sh-notices' ) }
						checked={ showThumbnail }
						onChange={ ( val ) => setAttributes( { showThumbnail: val } ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Layout', 'sh-notices' ) } initialOpen={ false }>
					<SelectControl
						label={ __( 'Mobile columns', 'sh-notices' ) }
						help={ __( 'Desktop always shows 3 columns.', 'sh-notices' ) }
						value={ mobileColumns }
						options={ [
							{ label: __( '1 column (stacked)', 'sh-notices' ), value: 1 },
							{ label: __( '2 columns', 'sh-notices' ), value: 2 },
						] }
						onChange={ ( val ) => setAttributes( { mobileColumns: Number( val ) } ) }
					/>
				</PanelBody>
			</InspectorControls>

			{ /* ── Canvas (server-side preview) ── */ }
			<div { ...blockProps }>
				{ hasNotices === undefined && (
					<Placeholder label={ __( 'Notices Grid', 'sh-notices' ) }>
						<Spinner />
					</Placeholder>
				) }
				{ hasNotices === false && (
					<Placeholder
						icon="megaphone"
						label={ __( 'Notices Grid', 'sh-notices' ) }
						instructions={ __(
							'No published notices found. Add some notices under Notices → Add New.',
							'sh-notices'
						) }
					/>
				) }
				{ hasNotices && (
					<ServerSideRender
						block="sh-notices/notices-grid"
						attributes={ attributes }
						LoadingResponsePlaceholder={ () => (
							<Placeholder label={ __( 'Loading notices…', 'sh-notices' ) }>
								<Spinner />
							</Placeholder>
						) }
					/>
				) }
			</div>
		</>
	);
}

registerBlockType( metadata.name, {
	...metadata,
	edit: Edit,
	// save returns null → server-side rendered
	save: () => null,
} );
