( function( wp ) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var TextControl = wp.components.TextControl;
    var RadioControl = wp.components.RadioControl;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var Fragment = wp.element.Fragment;

    registerBlockType( 'sh-series/list', {
        title: 'Series Article List',
        icon: 'list-view',
        category: 'widgets',
        description: 'Display an ordered list of articles associated with a specific tag.',
        keywords: [ 'series', 'list', 'tag' ],
        attributes: {
            tag: {
                type: 'string',
                default: ''
            },
            class: {
                type: 'string',
                default: ''
            },
            title: {
                type: 'string',
                default: ''
            },
            strip_prefix: {
                type: 'string',
                default: '',
            },
            sort: {
                type: 'string',
                default: 'desc',
            }
        },
        // The editor UI
        edit: function( props ) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el( Fragment, {},
                // 1. Sidebar Settings
                el( InspectorControls, {},
                    el( PanelBody, { title: 'Series Settings', initialOpen: true },
                        el( TextControl, {
                            label: 'Tag Slug',
                            value: attributes.tag,
                            onChange: function( newTag ) {
                                setAttributes( { tag: newTag } );
                            },
                            help: 'Enter the exact slug of the tag (e.g., "atari-2600").'
                        } ),
                        el( RadioControl, {
                            label: 'Sort Order',
                            help: 'Choose the order of articles.',
                            selected: attributes.sort,
                            options: [
                                { label: 'Ascending (Oldest First)', value: 'asc' },
                                { label: 'Descending (Newest First)', value: 'desc' },
                            ],
                            onChange: function( newOrder ) {
                                setAttributes( { sort: newOrder } );
                            }
                        } ),
                        el( TextControl, {
                            label: 'Box Title',
                            value: attributes.title,
                            onChange: function( newTitle ) {
                                setAttributes( { title: newTitle } );
                            },
                            help: 'Optional: Title displayed at the top of the box.'
                        } ),
                        el( TextControl, {
                            label: 'Custom CSS Class',
                            value: attributes.class,
                            onChange: function( newClass ) {
                                setAttributes( { class: newClass } );
                            },
                            help: 'Optional: Add a custom class for styling.'
                        } ),
                        el( TextControl, {
                            label: 'Strip title prefix',
                            value: attributes.strip_prefix,
                            onChange: function( newPrefix ) {
                                setAttributes( { strip_prefix: newPrefix } );
                            },
                            help: 'Optional: Strip the start of the title if it matches the given value. (i.e. "Foo: Headline" becomes "Headline" if "Foo: " is given as the prefix.)'
                        } )
                    )
                ),
                // 2. Editor Preview (Placeholder)
                el( 'div', {
                        className: 'sh-series-box components-placeholder is-large',
                        style: { padding: '20px', border: '1px dashed #ccc', backgroundColor: '#f0f0f0' }
                    },
                    attributes.title ? el( 'h3', { style: { marginTop: 0 } }, attributes.title ) : null,
                    el( 'strong', {}, 'Series List Block' ),
                    el( 'div', { style: { marginTop: '10px' } },
                        attributes.tag ?
                            'Showing articles for tag: ' + attributes.tag :
                            'Please enter a tag slug in the block settings sidebar.'
                    )
                )
            );
        },
        // Save null to let PHP handle the rendering (Dynamic Block)
        save: function() {
            return null;
        }
    } );
} )( window.wp );