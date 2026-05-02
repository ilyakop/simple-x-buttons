<?php
declare(strict_types=1);
/**
 * Plugin Name: Simple X Buttons
 * Plugin URI:  https://topdevs.net/simple-x-buttons/
 * Description: Add X (Twitter) follow and share buttons to your WordPress site. No API key, no external scripts, no rate limits. Pure Web Intents.
 * Version:     2.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * Author:      topdevs.net
 * Author URI:  https://topdevs.net
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-x-buttons
 *
 * This plugin is not affiliated with, endorsed by, or sponsored by X Corp.
 * All trademarks belong to their respective owners.
 */

define( 'SXB_VERSION', '2.0.0' );
define( 'SXB_PLUGIN_PATH',     plugin_dir_path( __FILE__ ) );
define( 'SXB_PLUGIN_URL',      plugin_dir_url( __FILE__ ) );
define( 'SXB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Check whether Simple Social Pro (X module) is active.
 */
function sxb_is_pro_active(): bool {
	return function_exists( 'SSP_X_Pro::init' ) || class_exists( 'SSP_X_Pro' );
}

// Autoload SXB_ classes from includes/ and admin/.
spl_autoload_register( function ( string $class_name ): void {
	if ( strpos( $class_name, 'SXB_' ) !== 0 ) {
		return;
	}

	$file_slug = strtolower( str_replace( '_', '-', $class_name ) );
	$file_name = 'class-' . $file_slug . '.php';

	$locations = array(
		SXB_PLUGIN_PATH . 'includes/' . $file_name,
		SXB_PLUGIN_PATH . 'admin/'    . $file_name,
	);

	foreach ( $locations as $path ) {
		if ( file_exists( $path ) ) {
			require_once $path;
			return;
		}
	}
} );

// Template tags are functions, not a class — load explicitly.
require_once SXB_PLUGIN_PATH . 'includes/class-sxb-template-tags.php';

// Bootstrap on plugins_loaded so add-ons can hook in at priority > 10.
add_action( 'plugins_loaded', function (): void {
	$GLOBALS['sxb_plugin'] = new SXB_Plugin();
}, 5 );
