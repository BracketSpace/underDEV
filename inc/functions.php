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
