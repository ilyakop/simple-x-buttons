<?php
declare(strict_types=1);
/**
 * Runs when the plugin is deleted (not deactivated).
 * Removes all plugin data from the database.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove main options.
delete_option( 'sxb_options' );

// Remove any transients with the sxb_ prefix.
global $wpdb;
$wpdb->query(
	"DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_sxb_%' OR option_name LIKE '_transient_timeout_sxb_%'"
);

// Remove per-user dismissal meta.
$wpdb->query(
	"DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'sxb_%'"
);
