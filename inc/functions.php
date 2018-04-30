<?php
/**
 * Global functions
 *
 * @package underdev
 */

/**
 * Gets single setting value
 *
 * @since  1.0.0
 * @param  string $setting setting name in `a/b/c` format.
 * @return mixed
 */
function underdev_get_setting( $setting ) {
	$runtime = underdev_runtime();
	return $runtime->settings->get_setting( $setting );
}

/**
 * Gets real IP address
 *
 * @since  1.0.0
 * @return string
 */
function underdev_get_ip() {

	$client  = isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '';
    $forward = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
    $remote  = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

    if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
        $ip = $client;
    } else if ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;

}
