<?php
declare(strict_types=1);

/**
 * Template tags for theme developers.
 *
 * @since 2.0.0
 */

if ( ! function_exists( 'sxb_share_button' ) ) {
	/**
	 * Output an X share button.
	 *
	 * @param array $args Arguments passed to SXB_Button::render().
	 */
	function sxb_share_button( array $args = array() ): void {
		$options  = SXB_Options::get();
		$defaults = array(
			'type'     => 'share',
			'echo'     => false,
			'text'     => is_singular() ? get_the_title() : '',
			'url'      => is_singular() ? get_permalink() : '',
			'hashtags' => $options['share_hashtags'],
			'via'      => $options['share_via'],
		);
		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}
		wp_enqueue_style( 'sxb-frontend' );
		echo SXB_Button::render( array_merge( $defaults, $args ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'sxb_button_bar' ) ) {
	/**
	 * Output a button bar for a post.
	 *
	 * @param int|null $post_id Post ID. Defaults to current post.
	 * @param string   $context Context identifier passed through to filters/actions.
	 * @param array    $buttons Override which button types to show.
	 */
	function sxb_button_bar( ?int $post_id = null, string $context = 'manual', array $buttons = array() ): void {
		$post_id = $post_id ?? get_the_ID();
		if ( ! $post_id ) {
			return;
		}
		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}
		wp_enqueue_style( 'sxb-frontend' );
		echo SXB_Button_Bar::render( $post_id, $context, $buttons ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
