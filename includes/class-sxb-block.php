<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Block' ) ) :

/**
 * Registers all four Simple X Buttons Gutenberg blocks.
 *
 * Blocks: simple-x-buttons/share, /follow, /mention, /hashtag
 * All are dynamic — save() returns null, rendered server-side.
 *
 * @since 2.0.0
 */
class SXB_Block {

	public function register(): void {
		$options = SXB_Options::get();

		wp_register_script(
			'sxb-block-editor',
			SXB_PLUGIN_URL . 'blocks/index.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			SXB_VERSION,
			true
		);

		wp_register_style(
			'sxb-block-editor-style',
			SXB_PLUGIN_URL . 'blocks/style.css',
			array(),
			SXB_VERSION
		);

		// Pass defaults to the block editor JS.
		wp_add_inline_script(
			'sxb-block-editor',
			'window.sxbBlockDefaults = ' . wp_json_encode( array(
				'style'         => $options['button_style'],
				'shareLabel'    => $options['share_label'],
				'shareVia'      => $options['share_via'],
				'shareHashtags' => $options['share_hashtags'],
				'followHandle'  => $options['follow_handle'],
				'followLabel'   => $options['follow_label'],
				'mentionHandle' => $options['mention_handle'],
				'hashtagTag'    => $options['hashtag_tag'],
			) ) . ';',
			'before'
		);

		$common = array(
			'editor_script' => 'sxb-block-editor',
			'editor_style'  => 'sxb-block-editor-style',
			'style'         => 'sxb-frontend',
		);

		// Share block — reads post context automatically.
		register_block_type( 'simple-x-buttons/share', array_merge( $common, array(
			'render_callback' => array( $this, 'render_share' ),
			'attributes'      => array(
				'label'    => array( 'type' => 'string', 'default' => $options['share_label'] ),
				'style'    => array( 'type' => 'string', 'default' => $options['button_style'] ),
				'hashtags' => array( 'type' => 'string', 'default' => $options['share_hashtags'] ),
				'via'      => array( 'type' => 'string', 'default' => $options['share_via'] ),
			),
		) ) );

		// Follow and Mention blocks — require a handle attribute.
		register_block_type( 'simple-x-buttons/follow', array_merge( $common, array(
			'render_callback' => array( $this, 'render_handle_action' ),
			'attributes'      => array(
				'type'   => array( 'type' => 'string', 'default' => 'follow' ),
				'handle' => array( 'type' => 'string', 'default' => $options['follow_handle'] ),
				'label'  => array( 'type' => 'string', 'default' => $options['follow_label'] ),
				'style'  => array( 'type' => 'string', 'default' => $options['button_style'] ),
			),
		) ) );

		register_block_type( 'simple-x-buttons/mention', array_merge( $common, array(
			'render_callback' => array( $this, 'render_handle_action' ),
			'attributes'      => array(
				'type'   => array( 'type' => 'string', 'default' => 'mention' ),
				'handle' => array( 'type' => 'string', 'default' => $options['mention_handle'] ),
				'label'  => array( 'type' => 'string', 'default' => '' ),
				'style'  => array( 'type' => 'string', 'default' => $options['button_style'] ),
			),
		) ) );

		// Hashtag block — requires a tag attribute.
		register_block_type( 'simple-x-buttons/hashtag', array_merge( $common, array(
			'render_callback' => array( $this, 'render_hashtag' ),
			'attributes'      => array(
				'tag'   => array( 'type' => 'string', 'default' => $options['hashtag_tag'] ),
				'label' => array( 'type' => 'string', 'default' => '' ),
				'style' => array( 'type' => 'string', 'default' => $options['button_style'] ),
			),
		) ) );
	}

	public function render_share( array $attributes ): string {
		$options = SXB_Options::get();
		$this->enqueue_assets();

		return SXB_Button::render( array(
			'type'     => 'share',
			'label'    => ! empty( $attributes['label'] ) ? $attributes['label'] : $options['share_label'],
			'style'    => ! empty( $attributes['style'] ) ? $attributes['style'] : $options['button_style'],
			'text'     => is_singular() ? get_the_title() : '',
			'url'      => is_singular() ? get_permalink() : '',
			'hashtags' => ! empty( $attributes['hashtags'] ) ? $attributes['hashtags'] : $options['share_hashtags'],
			'via'      => ! empty( $attributes['via'] ) ? $attributes['via'] : $options['share_via'],
			'echo'     => false,
		) );
	}

	public function render_handle_action( array $attributes ): string {
		$type   = sanitize_key( $attributes['type'] ?? 'follow' );
		$handle = $attributes['handle'] ?? '';

		if ( empty( $handle ) ) {
			return '';
		}

		$options = SXB_Options::get();
		$this->enqueue_assets();

		return SXB_Button::render( array(
			'type'   => $type,
			'handle' => $handle,
			'label'  => $attributes['label'] ?? '',
			'style'  => ! empty( $attributes['style'] ) ? $attributes['style'] : $options['button_style'],
			'echo'   => false,
		) );
	}

	public function render_hashtag( array $attributes ): string {
		$tag = $attributes['tag'] ?? '';

		if ( empty( $tag ) ) {
			return '';
		}

		$options = SXB_Options::get();
		$this->enqueue_assets();

		return SXB_Button::render( array(
			'type'  => 'hashtag',
			'tag'   => $tag,
			'label' => $attributes['label'] ?? '',
			'style' => ! empty( $attributes['style'] ) ? $attributes['style'] : $options['button_style'],
			'echo'  => false,
		) );
	}

	private function enqueue_assets(): void {
		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}
		wp_enqueue_style( 'sxb-frontend' );
	}
}

endif;
