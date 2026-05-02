<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Intent' ) ) :

/**
 * Builds X Web Intent URLs.
 *
 * All intents use https://twitter.com/intent/ — more reliable for the
 * native popup handler than x.com equivalents.
 *
 * Supported: share, follow, mention, hashtag.
 *
 * @since 2.0.0
 */
class SXB_Intent {

	const BASE = 'https://twitter.com/intent/';

	/**
	 * Build a tweet/share intent URL.
	 *
	 * @param array $args {
	 *     @type string $text      Tweet text.
	 *     @type string $url       Permalink to share.
	 *     @type string $hashtags  Comma-separated tags, no # or spaces.
	 *     @type string $via       Handle without @.
	 * }
	 */
	public static function share( array $args = array() ): string {
		$params = array();

		if ( ! empty( $args['text'] ) ) {
			$params['text'] = rawurlencode( (string) $args['text'] );
		}
		if ( ! empty( $args['url'] ) ) {
			$params['url'] = rawurlencode( (string) $args['url'] );
		}
		if ( ! empty( $args['hashtags'] ) ) {
			$params['hashtags'] = rawurlencode( (string) $args['hashtags'] );
		}
		if ( ! empty( $args['via'] ) ) {
			$params['via'] = rawurlencode( ltrim( (string) $args['via'], '@' ) );
		}

		$url = self::BASE . 'tweet?' . self::build_query( $params );

		return (string) apply_filters( 'sxb_intent_url', $url, 'share', $args );
	}

	/**
	 * Build a follow intent URL.
	 *
	 * @param string $handle X/Twitter handle without @.
	 */
	public static function follow( string $handle ): string {
		$handle = ltrim( $handle, '@' );
		$url    = self::BASE . 'follow?screen_name=' . rawurlencode( $handle );

		return (string) apply_filters( 'sxb_intent_url', $url, 'follow', array( 'handle' => $handle ) );
	}

	/**
	 * Build a tweet-to-handle (mention) intent URL.
	 *
	 * Opens the compose window with @handle pre-filled.
	 *
	 * @param string $handle X/Twitter handle without @.
	 */
	public static function mention( string $handle ): string {
		$handle = ltrim( $handle, '@' );
		$url    = self::BASE . 'tweet?screen_name=' . rawurlencode( $handle );

		return (string) apply_filters( 'sxb_intent_url', $url, 'mention', array( 'handle' => $handle ) );
	}

	/**
	 * Build a tweet-a-hashtag intent URL.
	 *
	 * Opens the compose window with #tag pre-filled.
	 *
	 * @param string $tag Hashtag without #.
	 */
	public static function hashtag( string $tag ): string {
		$tag = ltrim( $tag, '#' );
		$url = self::BASE . 'tweet?hashtags=' . rawurlencode( $tag );

		return (string) apply_filters( 'sxb_intent_url', $url, 'hashtag', array( 'tag' => $tag ) );
	}

	/**
	 * Build a query string from pre-encoded key=>value pairs.
	 */
	private static function build_query( array $params ): string {
		$parts = array();
		foreach ( $params as $key => $value ) {
			$parts[] = rawurlencode( $key ) . '=' . $value;
		}
		return implode( '&', $parts );
	}
}

endif;
