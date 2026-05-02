/* Simple X Buttons — Follow block editor script */
( function ( blocks, element, blockEditor, components, i18n ) {
	'use strict';

	var el         = element.createElement;
	var __         = i18n.__;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody  = components.PanelBody;
	var TextControl = components.TextControl;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	var defaults = window.sxbBlockDefaults || {};

	var X_LOGO = el( 'svg', {
		xmlns: 'http://www.w3.org/2000/svg',
		viewBox: '0 0 1200 1227',
		width: 15, height: 15,
		'aria-hidden': 'true',
		style: { fill: 'currentColor', flexShrink: 0 },
	}, el( 'path', { d: 'M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.163 519.284ZM569.165 687.828L521.697 619.934L144.011 79.6904H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.828Z' } ) );

	function getButtonClass( style ) {
		return 'sxb-button sxb-button--' + ( style || 'dark' ) + ' sxb-button--follow';
	}

	blocks.registerBlockType( 'simple-x-buttons/follow', {
		title:    __( 'X Follow Button', 'simple-x-buttons' ),
		category: 'widgets',
		icon:     'twitter',

		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var handle     = attributes.handle     !== undefined ? attributes.handle     : ( defaults.handle     || '' );
			var label      = attributes.label      !== undefined ? attributes.label      : ( defaults.label      || 'Follow on X' );
			var showHandle = attributes.showHandle !== undefined ? attributes.showHandle : ( defaults.showHandle !== false );
			var style      = attributes.style      !== undefined ? attributes.style      : ( defaults.style      || 'dark' );

			var displayLabel = label + ( showHandle && handle ? ' @' + handle : '' );

			return el(
				'div',
				useBlockProps(),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Follow Button Settings', 'simple-x-buttons' ), initialOpen: true },
						el( TextControl, {
							label:    __( 'Handle (without @)', 'simple-x-buttons' ),
							value:    handle,
							onChange: function ( v ) { setAttributes( { handle: v } ); },
							placeholder: defaults.handle || 'yourhandle',
						} ),
						el( TextControl, {
							label:    __( 'Label', 'simple-x-buttons' ),
							value:    label,
							onChange: function ( v ) { setAttributes( { label: v } ); },
						} ),
						el( ToggleControl, {
							label:    __( 'Show @handle next to label', 'simple-x-buttons' ),
							checked:  showHandle,
							onChange: function ( v ) { setAttributes( { showHandle: v } ); },
						} ),
						el( SelectControl, {
							label:   __( 'Button Style', 'simple-x-buttons' ),
							value:   style,
							options: [
								{ label: __( 'Dark',    'simple-x-buttons' ), value: 'dark'    },
								{ label: __( 'Outline', 'simple-x-buttons' ), value: 'outline' },
								{ label: __( 'Ghost',   'simple-x-buttons' ), value: 'ghost'   },
							],
							onChange: function ( v ) { setAttributes( { style: v } ); },
						} )
					)
				),
				el( 'a', {
					className: getButtonClass( style ),
					href: '#',
					onClick: function ( e ) { e.preventDefault(); },
					rel: 'noopener noreferrer',
				},
					X_LOGO,
					el( 'span', { className: 'sxb-button__label' }, displayLabel )
				)
			);
		},

		save: function () {
			// Dynamic block — rendered server-side.
			return null;
		},
	} );

}(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
) );
