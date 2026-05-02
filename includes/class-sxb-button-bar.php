<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Button_Bar' ) ) :

/**
 * Renders a wrapped group of buttons for a given post.
 *
 * Which buttons appear is controlled by the placement's button list in options.
 * Share, Follow, Mention, and Hashtag are all supported.
 *
 * @since 2.0.0
 */
class SXB_Button_Bar {

	/**
	 * Render a button bar and return the HTML string.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $context  after_content|before_content|manual|...
	 * @param array  $buttons  Override which button types to render. Defaults to options.
	 */
	public static function render( int $post_id, string $context = 'after_content', array $buttons = array() ): string {
		$options = SXB_Options::get();
		$post    = get_post( $post_id );

		if ( ! $post ) {
			return '';
		}

		if ( empty( $buttons ) ) {
			$key     = $context . '_buttons';
			$buttons = isset( $options[ $key ] ) ? (array) $options[ $key ] : array( 'share' );
		}

		if ( empty( $buttons ) ) {
			return '';
		}

		do_action( 'sxb_before_button_bar', $post_id, $context );

		ob_start();
		echo '<div class="sxb-button-bar sxb-button-bar--' . esc_attr( $context ) . '">';

		foreach ( $buttons as $type ) {
			echo SXB_Button::render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				self::build_args( $type, $post_id, $options )
			);
		}

		echo '</div>';

		$html = (string) ob_get_clean();
		$html = (string) apply_filters( 'sxb_button_bar_html', $html, $post_id, $context );

		do_action( 'sxb_after_button_bar', $post_id, $context );

		return $html;
	}

	private static function build_args( string $type, int $post_id, array $options ): array {
		$base = array( 'type' => $type, 'echo' => false );

		switch ( $type ) {
			case 'share':
				if ( $options['share_include_title'] ) {
					$base['text'] = get_the_title( $post_id );
				}
				if ( $options['share_include_url'] ) {
					$base['url'] = get_permalink( $post_id );
				}
				$hashtags = '';
				if ( $options['share_include_tags'] ) {
					$hashtags = self::get_post_hashtags( $post_id );
				}
				if ( ! empty( $options['share_hashtags'] ) ) {
					$hashtags = $hashtags ? $hashtags . ',' . $options['share_hashtags'] : $options['share_hashtags'];
				}
				if ( $hashtags ) {
					$base['hashtags'] = $hashtags;
				}
				if ( ! empty( $options['share_via'] ) ) {
					$base['via'] = $options['share_via'];
				}
				$base['label'] = $options['share_label'];
				break;

			case 'follow':
				// Silently skipped by SXB_Button::render() if handle is empty.
				$base['handle'] = $options['follow_handle'] ?? '';
				$base['label']  = $options['follow_label'] ?? '';
				break;

			case 'mention':
				$base['handle'] = $options['mention_handle'] ?? '';
				break;

			case 'hashtag':
				$tag = $options['hashtag_tag'] ?? '';
				if ( ! empty( $options['hashtag_include_tags'] ) ) {
					$post_tags = self::get_post_hashtags( $post_id );
					if ( $post_tags ) {
						$tag = $tag ? $tag . ',' . $post_tags : $post_tags;
					}
				}
				if ( $tag ) {
					$base['tag'] = $tag;
				}
				break;
		}

		return $base;
	}

	private static function get_post_hashtags( int $post_id ): string {
		$tags = get_the_tags( $post_id );
		if ( ! $tags || is_wp_error( $tags ) ) {
			return '';
		}
		$limit = max( 1, (int) apply_filters( 'sxb_hashtag_limit', 5 ) );
		$tags  = array_slice( $tags, 0, $limit );
		$slugs = array_map( static function ( \WP_Term $tag ): string {
			return preg_replace( '/\s+/', '', $tag->name );
		}, $tags );
		return implode( ',', array_filter( $slugs ) );
	}
}

endif;
