<?php
/**
 * Plugin Name: underDEV
 * Description: underDEV Maintenance plugin
 * Author: BracketSpace
 * Author URI: https://bracketspace.com
 * Version: 1.0.0
 * License: GPL3
 * Text Domain: underdev
 * Domain Path: /languages
 *
 * @package underdev
 */

/**
 * Plugin's autoload function
 *
 * @param  string $class class name.
 * @return mixed         false if not plugin's class or void
 */
function bracketspace_underdev_autoload( $class ) {

	$parts = explode( '\\', $class );

	if ( array_shift( $parts ) != 'BracketSpace' ) {
		return false;
	}

	if ( array_shift( $parts ) != 'underDEV' ) {
		return false;
	}

	$file = trailingslashit( dirname( __FILE__ ) ) . trailingslashit( 'class' ) . implode( '/', $parts ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	}

}
spl_autoload_register( 'bracketspace_underdev_autoload' );

/**
 * Requirements check
 */
$requirements = new BracketSpace\underDEV\Utils\Requirements( 'underDEV', array(
	'php' => '5.3',
	'wp'  => '4.6',
) );

if ( ! $requirements->satisfied() ) {
	add_action( 'admin_notices', array( $requirements, 'notice' ) );
	return;
}

/**
 * Boots up the plugin
 *
 * @return object Runtime class instance
 */
function underdev_runtime() {

	global $underdev_runtime;

	if ( empty( $underdev_runtime ) ) {
		$underdev_runtime = new BracketSpace\underDEV\Runtime( __FILE__ );
	}

	return $underdev_runtime;

}

$runtime = underdev_runtime();
$runtime->boot();
