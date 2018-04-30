<?php
/**
 * Plugin uninstallation file
 *
 * @package underdev
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;

$settings_config = get_option( '_underdev_settings_config' );

foreach ( $settings_config as $section_slug => $section ) {
	delete_option( 'underdev_' . $section_slug );
	delete_site_option( 'underdev_' . $section_slug );
}

delete_option( '_underdev_settings_config' );
delete_option( '_underdev_settings_hash' );
