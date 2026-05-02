<?php
declare(strict_types=1);

if ( ! class_exists( 'SXB_Plugin' ) ) :

/**
 * Main plugin class — singleton, owns all hook registrations.
 *
 * @since 2.0.0
 */
class SXB_Plugin {

	public string $plugin_path;
	public string $plugin_url;

	/** Flag: have we already enqueued frontend assets this request? */
	public bool $frontend_assets_enqueued = false;

	/**
	 * Constructor — mirrors SFPlugin pattern exactly.
	 */
	public function __construct() {
		$this->plugin_path = SXB_PLUGIN_PATH;
		$this->plugin_url  = SXB_PLUGIN_URL;

		$this->add_actions();
		$this->add_shortcodes();
	}

	/**
	 * Register all hooks.
	 */
	protected function add_actions(): void {
		add_action( 'init',                       array( $this, 'load_textdomain' ) );
		add_action( 'init',                       array( $this, 'register_blocks' ) );
		add_action( 'admin_menu',                 array( $this, 'plugin_menu' ) );
		add_action( 'admin_notices',              array( $this, 'admin_notice' ) );
		add_action( 'admin_init',                 array( $this, 'register_settings' ) );
		add_action( 'admin_init',                 array( $this, 'remove_settings_saved_notice' ) );
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts',         array( $this, 'register_frontend_assets' ) );
		add_action( 'wp_footer',                  array( $this, 'maybe_enqueue_frontend_assets' ) );

		// Content injection.
		add_filter( 'the_content', array( new SXB_Content(), 'inject' ), 20 );

		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		// Allow add-ons to hook in.
		do_action( 'sxb_add_actions', $this );
	}

	/**
	 * Register shortcodes.
	 */
	protected function add_shortcodes(): void {
		$shortcodes = new SXB_Shortcodes();
		add_shortcode( 'sxb-share',   array( $shortcodes, 'share' ) );
		add_shortcode( 'sxb-follow',  array( $shortcodes, 'follow' ) );
		add_shortcode( 'sxb-mention', array( $shortcodes, 'mention' ) );
		add_shortcode( 'sxb-hashtag', array( $shortcodes, 'hashtag' ) );

		do_action( 'sxb_add_shortcodes' );
	}

	public function load_textdomain(): void {
		load_plugin_textdomain( 'simple-x-buttons', false, dirname( SXB_PLUGIN_BASENAME ) . '/languages' );
	}

	public function plugin_menu(): void {
		add_options_page(
			__( 'Simple X Buttons', 'simple-x-buttons' ),
			__( 'Simple X Buttons', 'simple-x-buttons' ),
			'manage_options',
			'sxb_plugin',
			array( $this, 'plugin_menu_view' )
		);
	}

	public function plugin_menu_view(): void {
		$options = SXB_Options::get();
		include $this->plugin_path . 'admin/views/settings-page.php';
	}

	public function admin_notice(): void {
		// Pro upsell notice — disabled until Pro is ready.
		// if ( ! current_user_can( 'manage_options' ) ) {
		// 	return;
		// }
		// if ( sxb_is_pro_active() ) {
		// 	return;
		// }
		// $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		// if ( ! $screen || 'settings_page_sxb_plugin' !== $screen->id ) {
		// 	return;
		// }
		// $user_id    = get_current_user_id();
		// $snooze_key = 'sxb_pro_notice_snooze_' . $user_id;
		// if ( isset( $_GET['sxb_hide_pro_notice'] ) && check_admin_referer( 'sxb_hide_pro_notice' ) ) {
		// 	update_user_meta( $user_id, 'sxb_hide_pro_notice', 1 );
		// }
		// if ( get_user_meta( $user_id, 'sxb_hide_pro_notice', true ) ) {
		// 	return;
		// }
		// if ( isset( $_GET['sxb_snooze_pro_notice'] ) && check_admin_referer( 'sxb_snooze_pro_notice' ) ) {
		// 	set_transient( $snooze_key, 1, 14 * DAY_IN_SECONDS );
		// 	return;
		// }
		// if ( get_transient( $snooze_key ) ) {
		// 	return;
		// }
		// $upgrade_url = esc_url( add_query_arg( array(
		// 	'utm_source'   => 'wp-admin',
		// 	'utm_medium'   => 'notice',
		// 	'utm_campaign' => 'sxb-upgrade',
		// ), apply_filters( 'sxb_pro_upgrade_url', 'https://topdevs.net/simple-social-pro/' ) ) );
		// $snooze_url  = esc_url( wp_nonce_url( add_query_arg( 'sxb_snooze_pro_notice', '1' ), 'sxb_snooze_pro_notice' ) );
		// $hide_url    = esc_url( wp_nonce_url( add_query_arg( 'sxb_hide_pro_notice', '1' ), 'sxb_hide_pro_notice' ) );
		// echo '<div class="notice notice-info is-dismissible"><p>';
		// echo wp_kses_post( sprintf(
		// 	'Pro adds a floating follow button, popup trigger, and display rules. <a href="%1$s" target="_blank" rel="noopener noreferrer"><strong>Get Simple Social Pro →</strong></a> &nbsp; <a href="%2$s">Remind me later</a> · <a href="%3$s">Don\'t show again</a>',
		// 	$upgrade_url, $snooze_url, $hide_url
		// ) );
		// echo '</p></div>';
	}

	public function register_settings(): void {
		register_setting( 'sxb_options', 'sxb_options', array( 'sanitize_callback' => array( 'SXB_Options', 'sanitize' ) ) );
	}

	public function remove_settings_saved_notice(): void {
		if ( empty( $_GET['settings-updated'] ) || empty( $_GET['page'] ) || 'sxb_plugin' !== $_GET['page'] ) {
			return;
		}
		$errors = get_transient( 'settings_errors' );
		if ( ! is_array( $errors ) ) {
			return;
		}
		$filtered = array_values( array_filter( $errors, static function ( array $e ): bool {
			return ! ( isset( $e['setting'], $e['code'] ) && 'general' === $e['setting'] && 'settings_updated' === $e['code'] );
		} ) );
		if ( empty( $filtered ) ) {
			delete_transient( 'settings_errors' );
		} else {
			set_transient( 'settings_errors', $filtered, 30 );
		}
	}

	public function enqueue_admin_scripts(): void {
		wp_enqueue_style( 'sxb-admin-style', $this->plugin_url . 'assets/css/sxb-admin.css', array(), SXB_VERSION );
	}

	/**
	 * Register (but don't enqueue) frontend assets so handles are available
	 * for block style registration and explicit wp_enqueue_style() calls.
	 */
	public function register_frontend_assets(): void {
		wp_register_style( 'sxb-frontend', $this->plugin_url . 'assets/css/simple-x-buttons.css', array(), SXB_VERSION );
		wp_register_script( 'sxb-frontend', $this->plugin_url . 'assets/js/simple-x-buttons.js', array(), SXB_VERSION, true );
	}

	/**
	 * Enqueue frontend CSS/JS — only if the content filter flagged it needed.
	 */
	public function maybe_enqueue_frontend_assets(): void {
		if ( ! $this->frontend_assets_enqueued ) {
			return;
		}
		wp_enqueue_style( 'sxb-frontend' );
		wp_enqueue_script( 'sxb-frontend' );
	}

	/**
	 * Signal that frontend assets will be needed.
	 */
	public function flag_frontend_assets(): void {
		$this->frontend_assets_enqueued = true;
	}

	public function register_blocks(): void {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		( new SXB_Block() )->register();
	}

	public function plugin_row_meta( array $links, string $file ): array {
		if ( $file !== SXB_PLUGIN_BASENAME ) {
			return $links;
		}
		$extra = array( '<a href="' . esc_url( menu_page_url( 'sxb_plugin', false ) ) . '">' . esc_html__( 'Settings', 'simple-x-buttons' ) . '</a>' );
		// Upgrade to Pro link — disabled until Pro is ready.
		// if ( ! sxb_is_pro_active() ) {
		// 	$upgrade_url = esc_url( add_query_arg( array(
		// 		'utm_source'   => 'wp-admin',
		// 		'utm_medium'   => 'plugin-row',
		// 		'utm_campaign' => 'sxb-upgrade',
		// 	), apply_filters( 'sxb_pro_upgrade_url', 'https://topdevs.net/simple-social-pro/' ) ) );
		// 	$extra[] = '<a href="' . $upgrade_url . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Upgrade to Pro', 'simple-x-buttons' ) . '</a>';
		// }
		return array_merge( $links, $extra );
	}

	/**
	 * Used by Pro to check free plugin presence.
	 */
	public static function is_pro_active(): bool {
		return sxb_is_pro_active();
	}
}

endif;
