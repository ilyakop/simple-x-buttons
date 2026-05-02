<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Shortcodes' ) ) :

/**
 * All shortcode handlers. Every method returns HTML — never echoes.
 *
 * Shortcodes: [sxb-share], [sxb-follow], [sxb-mention], [sxb-hashtag]
 *
 * @since 2.0.0
 */
class SXB_Shortcodes {

	/**
	 * [sxb-share]
	 *
	 * @param array $atts text, url, hashtags, via, label, style
	 */
	public function share( $atts ): string {
		$options = SXB_Options::get();

		$atts = shortcode_atts( array(
			'text'     => '',
			'url'      => '',
			'hashtags' => $options['share_hashtags'],
			'via'      => $options['share_via'],
			'label'    => $options['share_label'],
			'style'    => $options['button_style'],
		), $atts, 'sxb-share' );

		$this->maybe_flag_assets();

		return SXB_Button::render( array(
			'type'     => 'share',
			'text'     => sanitize_text_field( $atts['text'] ),
			'url'      => esc_url_raw( $atts['url'] ),
			'hashtags' => sanitize_text_field( $atts['hashtags'] ),
			'via'      => sanitize_text_field( $atts['via'] ),
			'label'    => sanitize_text_field( $atts['label'] ),
			'style'    => $atts['style'],
			'echo'     => false,
		) );
	}

	/**
	 * [sxb-follow handle="yourhandle"]
	 *
	 * @param array $atts handle (required), label, style
	 */
	public function follow( $atts ): string {
		$options = SXB_Options::get();

		$atts = shortcode_atts( array(
			'handle' => $options['follow_handle'],
			'label'  => $options['follow_label'],
			'style'  => $options['button_style'],
		), $atts, 'sxb-follow' );

		if ( empty( $atts['handle'] ) ) {
			return '';
		}

		$this->maybe_flag_assets();

		return SXB_Button::render( array(
			'type'   => 'follow',
			'handle' => sanitize_text_field( $atts['handle'] ),
			'label'  => sanitize_text_field( $atts['label'] ),
			'style'  => $atts['style'],
			'echo'   => false,
		) );
	}

	/**
	 * [sxb-mention handle="yourhandle"]
	 *
	 * Opens X compose with @handle pre-filled.
	 *
	 * @param array $atts handle (required), label, style
	 */
	public function mention( $atts ): string {
		$options = SXB_Options::get();

		$atts = shortcode_atts( array(
			'handle' => $options['mention_handle'],
			'label'  => '',
			'style'  => $options['button_style'],
		), $atts, 'sxb-mention' );

		if ( empty( $atts['handle'] ) ) {
			return '';
		}

		$this->maybe_flag_assets();

		return SXB_Button::render( array(
			'type'   => 'mention',
			'handle' => sanitize_text_field( $atts['handle'] ),
			'label'  => sanitize_text_field( $atts['label'] ),
			'style'  => $atts['style'],
			'echo'   => false,
		) );
	}

	/**
	 * [sxb-hashtag tag="travel"]
	 *
	 * Opens X compose with #tag pre-filled.
	 *
	 * @param array $atts tag (required), label, style
	 */
	public function hashtag( $atts ): string {
		$options = SXB_Options::get();

		$atts = shortcode_atts( array(
			'tag'   => $options['hashtag_tag'],
			'label' => '',
			'style' => $options['button_style'],
		), $atts, 'sxb-hashtag' );

		if ( empty( $atts['tag'] ) ) {
			return '';
		}

		$this->maybe_flag_assets();

		return SXB_Button::render( array(
			'type'  => 'hashtag',
			'tag'   => sanitize_text_field( $atts['tag'] ),
			'label' => sanitize_text_field( $atts['label'] ),
			'style' => $atts['style'],
			'echo'  => false,
		) );
	}

	private function maybe_flag_assets(): void {
		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}
		wp_enqueue_style( 'sxb-frontend' );
	}
}

endif;
