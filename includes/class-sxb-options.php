<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Options' ) ) :

/**
 * Options CRUD, defaults, and sanitization.
 *
 * @since 2.0.0
 */
class SXB_Options {

	const OPTION_KEY = 'sxb_options';

	/** All supported button types. */
	const BUTTON_TYPES = array( 'share', 'follow', 'mention', 'hashtag' );

	public static function defaults(): array {
		$defaults = array(
			// Button appearance.
			'button_style'  => 'dark',
			'intent_window' => 'popup',

			// Placements — which post types and buttons to inject.
			'after_content_enabled'     => 1,
			'after_content_post_types'  => array( 'post' ),
			'after_content_buttons'     => array( 'share' ),
			'before_content_enabled'    => 0,
			'before_content_post_types' => array( 'post' ),
			'before_content_buttons'    => array( 'share' ),

			// Share button.
			'share_label'         => 'Share on X',
			'share_include_title' => 1,
			'share_include_url'   => 1,
			'share_include_tags'  => 0,
			'share_hashtags'      => '',
			'share_via'           => '',

			// Follow button.
			'follow_handle' => '',
			'follow_label'  => 'Follow on X',

			// Mention (tweet to @handle) button.
			'mention_handle' => '',

			// Hashtag (tweet #tag) button.
			'hashtag_tag'          => '',
			'hashtag_include_tags' => 0,
		);

		return apply_filters( 'sxb_default_options', $defaults );
	}

	public static function get(): array {
		$stored = get_option( self::OPTION_KEY, array() );
		return wp_parse_args( $stored, self::defaults() );
	}

	public static function save( array $options ): void {
		update_option( self::OPTION_KEY, $options );
	}

	public static function sanitize( $raw ): array {
		if ( ! is_array( $raw ) ) {
			$raw = array();
		}

		$defaults = self::defaults();
		$clean    = array();

		$clean['button_style']  = in_array( $raw['button_style'] ?? '', array( 'dark', 'outline', 'ghost' ), true ) ? $raw['button_style'] : $defaults['button_style'];
		$clean['intent_window'] = in_array( $raw['intent_window'] ?? '', array( 'popup', 'new_tab' ), true ) ? $raw['intent_window'] : $defaults['intent_window'];

		$clean['after_content_enabled']     = ! empty( $raw['after_content_enabled'] ) ? 1 : 0;
		$clean['after_content_post_types']  = self::sanitize_post_types( $raw['after_content_post_types'] ?? array() );
		$clean['after_content_buttons']     = self::sanitize_button_types( $raw['after_content_buttons'] ?? array() );
		$clean['before_content_enabled']    = ! empty( $raw['before_content_enabled'] ) ? 1 : 0;
		$clean['before_content_post_types'] = self::sanitize_post_types( $raw['before_content_post_types'] ?? array() );
		$clean['before_content_buttons']    = self::sanitize_button_types( $raw['before_content_buttons'] ?? array() );

		$clean['share_label']         = isset( $raw['share_label'] ) ? sanitize_text_field( $raw['share_label'] ) : $defaults['share_label'];
		$clean['share_include_title'] = ! empty( $raw['share_include_title'] ) ? 1 : 0;
		$clean['share_include_url']   = ! empty( $raw['share_include_url'] ) ? 1 : 0;
		$clean['share_include_tags']  = ! empty( $raw['share_include_tags'] ) ? 1 : 0;
		$clean['share_hashtags']      = isset( $raw['share_hashtags'] ) ? sanitize_text_field( $raw['share_hashtags'] ) : '';
		$clean['share_via']           = isset( $raw['share_via'] ) ? ltrim( sanitize_text_field( $raw['share_via'] ), '@' ) : '';

		$clean['follow_handle'] = isset( $raw['follow_handle'] ) ? ltrim( sanitize_text_field( $raw['follow_handle'] ), '@' ) : '';
		$clean['follow_label']  = isset( $raw['follow_label'] ) ? sanitize_text_field( $raw['follow_label'] ) : $defaults['follow_label'];

		$clean['mention_handle'] = isset( $raw['mention_handle'] ) ? ltrim( sanitize_text_field( $raw['mention_handle'] ), '@' ) : '';

		$clean['hashtag_tag']          = isset( $raw['hashtag_tag'] ) ? ltrim( sanitize_text_field( $raw['hashtag_tag'] ), '#' ) : '';
		$clean['hashtag_include_tags'] = ! empty( $raw['hashtag_include_tags'] ) ? 1 : 0;

		return $clean;
	}

	private static function sanitize_post_types( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}
		return array_values( array_map( 'sanitize_key', $value ) );
	}

	private static function sanitize_button_types( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}
		return array_values( array_filter( $value, static function ( $v ): bool {
			return in_array( $v, self::BUTTON_TYPES, true );
		} ) );
	}
}

endif;
