/* Simple X Buttons — shared Gutenberg editor script (share, follow, mention, hashtag) */
( function ( blocks, element, blockEditor, components, i18n ) {
	'use strict';

	var el               = element.createElement;
	var __               = i18n.__;
	var useBlockProps    = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody        = components.PanelBody;
	var TextControl      = components.TextControl;
	var SelectControl    = components.SelectControl;

	var defaults = window.sxbBlockDefaults || {};

	var X_LOGO = el( 'svg', {
		xmlns:         'http://www.w3.org/2000/svg',
		viewBox:       '0 0 1200 1227',
		width:         15,
		height:        15,
		'aria-hidden': 'true',
		style:         { fill: 'currentColor', flexShrink: 0 },
	}, el( 'path', { d: 'M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.163 519.284ZM569.165 687.828L521.697 619.934L144.011 79.6904H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.828Z' } ) );

	var STYLE_OPTIONS = [
		{ label: __( 'Dark',    'simple-x-buttons' ), value: 'dark'    },
		{ label: __( 'Outline', 'simple-x-buttons' ), value: 'outline' },
		{ label: __( 'Ghost',   'simple-x-buttons' ), value: 'ghost'   },
	];

	function btnClass( style, type ) {
		return 'sxb-button sxb-button--' + ( style || 'dark' ) + ' sxb-button--' + type;
	}

	function placeholder( title ) {
		return el( 'div', { className: 'sxb-block-placeholder' },
			el( 'span', {}, title + ' — ' + __( 'enter a handle in the sidebar', 'simple-x-buttons' ) )
		);
	}

	// ── Share block ──────────────────────────────────────────────────────────

	blocks.registerBlockType( 'simple-x-buttons/share', {
		title:       __( 'X Share Button', 'simple-x-buttons' ),
		description: __( 'Share the current post on X.', 'simple-x-buttons' ),
		category:    'widgets',
		icon:        'share',

		edit: function ( props ) {
			var attr     = props.attributes;
			var set      = props.setAttributes;
			var label    = attr.label    !== undefined ? attr.label    : ( defaults.shareLabel    || 'Share on X' );
			var style    = attr.style    !== undefined ? attr.style    : ( defaults.style         || 'dark' );
			var hashtags = attr.hashtags !== undefined ? attr.hashtags : ( defaults.shareHashtags || '' );
			var via      = attr.via      !== undefined ? attr.via      : ( defaults.shareVia      || '' );

			return el(
				'div', useBlockProps(),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Share Button Settings', 'simple-x-buttons' ), initialOpen: true },
						el( TextControl, {
							label:    __( 'Label', 'simple-x-buttons' ),
							value:    label,
							onChange: function ( v ) { set( { label: v } ); },
						} ),
						el( SelectControl, {
							label:    __( 'Button Style', 'simple-x-buttons' ),
							value:    style,
							options:  STYLE_OPTIONS,
							onChange: function ( v ) { set( { style: v } ); },
						} ),
						el( TextControl, {
							label:       __( 'Custom Hashtags', 'simple-x-buttons' ),
							value:       hashtags,
							onChange:    function ( v ) { set( { hashtags: v } ); },
							placeholder: 'wordpress,blogging',
							help:        __( 'Comma-separated, no # sign.', 'simple-x-buttons' ),
						} ),
						el( TextControl, {
							label:       __( 'Via Handle', 'simple-x-buttons' ),
							value:       via,
							onChange:    function ( v ) { set( { via: v } ); },
							placeholder: 'yourhandle',
							help:        __( 'Without the @ sign.', 'simple-x-buttons' ),
						} )
					)
				),
				el( 'a', {
					className: btnClass( style, 'share' ),
					href:      '#',
					onClick:   function ( e ) { e.preventDefault(); },
				},
					X_LOGO,
					el( 'span', { className: 'sxb-button__label' }, label || 'Share on X' )
				)
			);
		},

		save: function () { return null; },
	} );

	// ── Follow block ─────────────────────────────────────────────────────────

	blocks.registerBlockType( 'simple-x-buttons/follow', {
		title:       __( 'X Follow Button', 'simple-x-buttons' ),
		description: __( 'Follow you on X.', 'simple-x-buttons' ),
		category:    'widgets',
		icon:        'admin-users',

		edit: function ( props ) {
			var attr   = props.attributes;
			var set    = props.setAttributes;
			var handle = attr.handle !== undefined ? attr.handle : ( defaults.followHandle || '' );
			var label  = attr.label  !== undefined ? attr.label  : ( defaults.followLabel  || 'Follow on X' );
			var style  = attr.style  !== undefined ? attr.style  : ( defaults.style        || 'dark' );

			return el(
				'div', useBlockProps(),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Follow Button Settings', 'simple-x-buttons' ), initialOpen: true },
						el( TextControl, {
							label:       __( 'Handle (without @)', 'simple-x-buttons' ),
							value:       handle,
							onChange:    function ( v ) { set( { handle: v } ); },
							placeholder: 'yourhandle',
						} ),
						el( TextControl, {
							label:    __( 'Label', 'simple-x-buttons' ),
							value:    label,
							onChange: function ( v ) { set( { label: v } ); },
						} ),
						el( SelectControl, {
							label:    __( 'Button Style', 'simple-x-buttons' ),
							value:    style,
							options:  STYLE_OPTIONS,
							onChange: function ( v ) { set( { style: v } ); },
						} )
					)
				),
				handle
					? el( 'a', {
						className: btnClass( style, 'follow' ),
						href:      '#',
						onClick:   function ( e ) { e.preventDefault(); },
					},
						X_LOGO,
						el( 'span', { className: 'sxb-button__label' }, label || 'Follow on X' )
					)
					: placeholder( __( 'X Follow Button', 'simple-x-buttons' ) )
			);
		},

		save: function () { return null; },
	} );

	// ── Mention block ────────────────────────────────────────────────────────

	blocks.registerBlockType( 'simple-x-buttons/mention', {
		title:       __( 'X Mention Button', 'simple-x-buttons' ),
		description: __( 'Tweet to a specific X handle.', 'simple-x-buttons' ),
		category:    'widgets',
		icon:        'admin-comments',

		edit: function ( props ) {
			var attr   = props.attributes;
			var set    = props.setAttributes;
			var handle = attr.handle !== undefined ? attr.handle : ( defaults.mentionHandle || '' );
			var label  = attr.label  !== undefined ? attr.label  : '';
			var style  = attr.style  !== undefined ? attr.style  : ( defaults.style || 'dark' );
			var displayLabel = label || ( handle ? 'Tweet to @' + handle : 'Tweet to Us' );

			return el(
				'div', useBlockProps(),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Mention Button Settings', 'simple-x-buttons' ), initialOpen: true },
						el( TextControl, {
							label:       __( 'Handle (without @)', 'simple-x-buttons' ),
							value:       handle,
							onChange:    function ( v ) { set( { handle: v } ); },
							placeholder: 'yourhandle',
						} ),
						el( TextControl, {
							label:       __( 'Label (optional)', 'simple-x-buttons' ),
							value:       label,
							onChange:    function ( v ) { set( { label: v } ); },
							placeholder: handle ? 'Tweet to @' + handle : 'Tweet to Us',
							help:        __( 'Leave blank to auto-generate from handle.', 'simple-x-buttons' ),
						} ),
						el( SelectControl, {
							label:    __( 'Button Style', 'simple-x-buttons' ),
							value:    style,
							options:  STYLE_OPTIONS,
							onChange: function ( v ) { set( { style: v } ); },
						} )
					)
				),
				handle
					? el( 'a', {
						className: btnClass( style, 'mention' ),
						href:      '#',
						onClick:   function ( e ) { e.preventDefault(); },
					},
						X_LOGO,
						el( 'span', { className: 'sxb-button__label' }, displayLabel )
					)
					: placeholder( __( 'X Mention Button', 'simple-x-buttons' ) )
			);
		},

		save: function () { return null; },
	} );

	// ── Hashtag block ────────────────────────────────────────────────────────

	blocks.registerBlockType( 'simple-x-buttons/hashtag', {
		title:       __( 'X Hashtag Button', 'simple-x-buttons' ),
		description: __( 'Tweet with a specific hashtag pre-filled.', 'simple-x-buttons' ),
		category:    'widgets',
		icon:        'tag',

		edit: function ( props ) {
			var attr  = props.attributes;
			var set   = props.setAttributes;
			var tag   = attr.tag   !== undefined ? attr.tag   : ( defaults.hashtagTag || '' );
			var label = attr.label !== undefined ? attr.label : '';
			var style = attr.style !== undefined ? attr.style : ( defaults.style || 'dark' );
			var displayLabel = label || ( tag ? 'Tweet #' + tag : 'Tweet' );

			return el(
				'div', useBlockProps(),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Hashtag Button Settings', 'simple-x-buttons' ), initialOpen: true },
						el( TextControl, {
							label:       __( 'Hashtag (without #)', 'simple-x-buttons' ),
							value:       tag,
							onChange:    function ( v ) { set( { tag: v } ); },
							placeholder: 'travel',
						} ),
						el( TextControl, {
							label:       __( 'Label (optional)', 'simple-x-buttons' ),
							value:       label,
							onChange:    function ( v ) { set( { label: v } ); },
							placeholder: tag ? 'Tweet #' + tag : 'Tweet',
							help:        __( 'Leave blank to auto-generate from tag.', 'simple-x-buttons' ),
						} ),
						el( SelectControl, {
							label:    __( 'Button Style', 'simple-x-buttons' ),
							value:    style,
							options:  STYLE_OPTIONS,
							onChange: function ( v ) { set( { style: v } ); },
						} )
					)
				),
				tag
					? el( 'a', {
						className: btnClass( style, 'hashtag' ),
						href:      '#',
						onClick:   function ( e ) { e.preventDefault(); },
					},
						X_LOGO,
						el( 'span', { className: 'sxb-button__label' }, displayLabel )
					)
					: el( 'div', { className: 'sxb-block-placeholder' },
						el( 'span', {}, __( 'X Hashtag Button', 'simple-x-buttons' ) + ' — ' + __( 'enter a hashtag in the sidebar', 'simple-x-buttons' ) )
					)
			);
		},

		save: function () { return null; },
	} );

}(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
) );
