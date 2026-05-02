<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Content' ) ) :

/**
 * Injects the button bar into post content via the_content filter.
 *
 * @since 2.0.0
 */
class SXB_Content {

	/**
	 * Hooked to the_content at priority 20.
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	public function inject( string $content ): string {
		if ( ! is_singular() ) {
			return $content;
		}

		$post_id   = get_the_ID();
		$post_type = get_post_type( $post_id );
		$options   = SXB_Options::get();

		$after_content  = (bool) $options['after_content_enabled'];
		$before_content = (bool) $options['before_content_enabled'];

		if ( ! $after_content && ! $before_content ) {
			return $content;
		}

		$after_types  = (array) $options['after_content_post_types'];
		$before_types = (array) $options['before_content_post_types'];

		$append  = $after_content  && in_array( $post_type, $after_types,  true );
		$prepend = $before_content && in_array( $post_type, $before_types, true );

		if ( ! $append && ! $prepend ) {
			return $content;
		}

		if ( $append && ! apply_filters( 'sxb_should_inject', true, $post_id, 'after_content' ) ) {
			$append = false;
		}
		if ( $prepend && ! apply_filters( 'sxb_should_inject', true, $post_id, 'before_content' ) ) {
			$prepend = false;
		}

		if ( ! $append && ! $prepend ) {
			return $content;
		}

		// Flag frontend assets as needed.
		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}

		if ( $prepend ) {
			$content = SXB_Button_Bar::render( $post_id, 'before_content' ) . $content;
		}
		if ( $append ) {
			$content = $content . SXB_Button_Bar::render( $post_id, 'after_content' );
		}

		return $content;
	}
}

endif;
