<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Button' ) ) :

/**
 * Renders a single X intent button.
 *
 * Supported types: share, follow, mention, hashtag.
 *
 * @since 2.0.0
 */
class SXB_Button {

	/**
	 * Current X wordmark SVG path (the "M" shape, not the old bird).
	 */
	const X_LOGO_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 1227" width="15" height="15" aria-hidden="true" focusable="false"><path d="M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.163 519.284ZM569.165 687.828L521.697 619.934L144.011 79.6904H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.828Z"/></svg>';

	/**
	 * Render a button and return (or echo) the HTML.
	 *
	 * @param array $args {
	 *     @type string $type      Required. share|follow|mention|hashtag.
	 *     @type string $label     Button label text.
	 *     @type string $style     dark|outline|ghost. Falls back to global setting.
	 *     @type string $text      For share: prefill tweet text.
	 *     @type string $url       For share: permalink to share.
	 *     @type string $hashtags  For share: comma-separated tags, no #.
	 *     @type string $via       For share: via handle without @.
	 *     @type string $handle    For follow/mention: X handle without @.
	 *     @type string $tag       For hashtag: tag without #.
	 *     @type string $target    _blank (default) or _self.
	 *     @type string $class     Extra CSS classes.
	 *     @type bool   $echo      Whether to echo (default true).
	 * }
	 */
	public static function render( array $args = array() ): string {
		$options = SXB_Options::get();

		$defaults = array(
			'type'     => 'share',
			'label'    => '',
			'style'    => $options['button_style'],
			'text'     => '',
			'url'      => '',
			'hashtags' => '',
			'via'      => '',
			'handle'   => '',
			'tag'      => '',
			'target'   => '_blank',
			'class'    => '',
			'echo'     => true,
		);

		$args = wp_parse_args( $args, $defaults );

		$type  = sanitize_key( $args['type'] );
		$style = in_array( $args['style'], array( 'dark', 'outline', 'ghost' ), true ) ? $args['style'] : 'dark';

		// Guard: follow/mention require a handle; hashtag requires a tag.
		if ( in_array( $type, array( 'follow', 'mention' ), true ) && empty( $args['handle'] ) ) {
			return '';
		}
		if ( 'hashtag' === $type && empty( $args['tag'] ) ) {
			return '';
		}

		$target      = in_array( $args['target'], array( '_blank', '_self' ), true ) ? $args['target'] : '_blank';
		$extra_class = $args['class'] ? ' ' . esc_attr( $args['class'] ) : '';

		$popup_attr = '';
		if ( 'popup' === $options['intent_window'] ) {
			$popup_attr = ' data-sxb-popup="1"';
		}

		$href  = self::build_intent_url( $type, $args );
		$label = $args['label'] !== '' ? $args['label'] : self::default_label( $type, $options, $args );

		$svg_fill = 'dark' === $style ? ' sxb-logo--white' : '';
		$svg      = str_replace( '<svg ', '<svg class="sxb-logo' . $svg_fill . '" ', self::X_LOGO_SVG );

		$class = 'sxb-button sxb-button--' . $style . ' sxb-button--' . $type . $extra_class;

		$html = sprintf(
			'<a href="%s" class="%s" target="%s" rel="noopener noreferrer"%s>%s<span class="sxb-button__label">%s</span></a>',
			esc_url( $href ),
			esc_attr( $class ),
			esc_attr( $target ),
			$popup_attr,
			$svg,
			esc_html( $label )
		);

		$html = (string) apply_filters( 'sxb_button_html', $html, $type, $args );

		if ( $args['echo'] ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped above.
		}

		return $html;
	}

	private static function build_intent_url( string $type, array $args ): string {
		switch ( $type ) {
			case 'share':
				return SXB_Intent::share( array(
					'text'     => $args['text'],
					'url'      => $args['url'],
					'hashtags' => $args['hashtags'],
					'via'      => $args['via'],
				) );
			case 'follow':
				return SXB_Intent::follow( $args['handle'] );
			case 'mention':
				return SXB_Intent::mention( $args['handle'] );
			case 'hashtag':
				return SXB_Intent::hashtag( $args['tag'] );
			default:
				return '#';
		}
	}

	private static function default_label( string $type, array $options, array $args = array() ): string {
		switch ( $type ) {
			case 'share':
				return $options['share_label'] ?: 'Share on X';
			case 'follow':
				return $options['follow_label'] ?: 'Follow on X';
			case 'mention':
				$handle = $args['handle'] ?? '';
				return $handle ? 'Tweet to @' . $handle : __( 'Tweet to Us', 'simple-x-buttons' );
			case 'hashtag':
				$tag = $args['tag'] ?? '';
				if ( $tag ) {
					$first = explode( ',', $tag )[0];
					return strpos( $tag, ',' ) !== false ? 'Tweet' : 'Tweet #' . $first;
				}
				return __( 'Tweet', 'simple-x-buttons' );
			default:
				return __( 'Open on X', 'simple-x-buttons' );
		}
	}
}

endif;
