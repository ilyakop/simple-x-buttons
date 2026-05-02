<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Widget' ) ) :

/**
 * Classic widget: SXB – Follow Button.
 *
 * @since 2.0.0
 */
class SXB_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		$widget_ops = array( 'description' => __( 'Display an X follow button.', 'simple-x-buttons' ) );
		parent::__construct( 'sxb_follow_widget', 'SXB – Follow Button', $widget_ops );
	}

	/**
	 * Front-end display.
	 *
	 * @param array $args     Display arguments.
	 * @param array $instance Widget settings.
	 */
	function widget( $args, $instance ) {
		$options = SXB_Options::get();

		$instance = apply_filters( 'sxb_widget_instance', $instance, $this );
		do_action( 'sxb_before_widget', $args, $instance, $this );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$title = apply_filters( 'widget_title', $instance['title'] ?? '' );
		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$handle = ! empty( $instance['handle'] ) ? $instance['handle'] : $options['handle'];
		$label  = ! empty( $instance['label'] )  ? $instance['label']  : $options['follow_label'];
		$style  = ! empty( $instance['style'] )  ? $instance['style']  : $options['button_style'];
		$show_handle = isset( $instance['show_handle'] ) ? (bool) $instance['show_handle'] : (bool) $options['follow_show_handle'];

		if ( $show_handle && $handle ) {
			$label .= ' @' . $handle;
		}

		if ( isset( $GLOBALS['sxb_plugin'] ) ) {
			$GLOBALS['sxb_plugin']->flag_frontend_assets();
		}
		wp_enqueue_style( 'sxb-frontend' );

		echo SXB_Button::render( array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'type'   => 'follow',
			'handle' => $handle,
			'label'  => $label,
			'style'  => $style,
			'echo'   => false,
		) );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'sxb_after_widget', $args, $instance, $this );
	}

	/**
	 * Sanitize widget form values on save.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = sanitize_text_field( $new_instance['title'] ?? '' );
		$instance['handle']      = ltrim( sanitize_text_field( $new_instance['handle'] ?? '' ), '@' );
		$instance['label']       = sanitize_text_field( $new_instance['label'] ?? '' );
		$instance['show_handle'] = isset( $new_instance['show_handle'] );

		$style_raw = $new_instance['style'] ?? '';
		$instance['style'] = in_array( $style_raw, array( 'dark', 'outline', 'ghost' ), true ) ? $style_raw : '';

		$instance = apply_filters( 'sxb_widget_update', $instance, $new_instance, $old_instance );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Current settings.
	 */
	function form( $instance ) {
		$options = SXB_Options::get();

		$title       = $instance['title']   ?? '';
		$handle      = $instance['handle']  ?? '';
		$label       = $instance['label']   ?? '';
		$style       = $instance['style']   ?? '';
		$show_handle = $instance['show_handle'] ?? true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'simple-x-buttons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'handle' ) ); ?>"><?php esc_html_e( 'Override Handle', 'simple-x-buttons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'handle' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'handle' ) ); ?>"
				type="text" value="<?php echo esc_attr( $handle ); ?>"
				placeholder="<?php echo esc_attr( $options['handle'] ); ?>" />
			<span class="description"><?php esc_html_e( 'Leave blank to use the global handle.', 'simple-x-buttons' ); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Override Label', 'simple-x-buttons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>"
				type="text" value="<?php echo esc_attr( $label ); ?>"
				placeholder="<?php echo esc_attr( $options['follow_label'] ); ?>" />
		</p>
		<p>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_handle' ) ); ?>"
					value="1" <?php checked( $show_handle ); ?> />
				<?php esc_html_e( 'Show @handle next to label', 'simple-x-buttons' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Button Style', 'simple-x-buttons' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
				<option value="" <?php selected( '', $style ); ?>><?php esc_html_e( 'Use global setting', 'simple-x-buttons' ); ?></option>
				<option value="dark"    <?php selected( 'dark',    $style ); ?>><?php esc_html_e( 'Dark',    'simple-x-buttons' ); ?></option>
				<option value="outline" <?php selected( 'outline', $style ); ?>><?php esc_html_e( 'Outline', 'simple-x-buttons' ); ?></option>
				<option value="ghost"   <?php selected( 'ghost',   $style ); ?>><?php esc_html_e( 'Ghost',   'simple-x-buttons' ); ?></option>
			</select>
		</p>
		<?php

		do_action( 'sxb_widget_form_end', $instance, $this );
	}
}

endif;
