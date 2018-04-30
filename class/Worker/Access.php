<?php
/**
 * Access worker class
 *
 * @package underdev
 */

namespace BracketSpace\underDEV\Worker;

/**
 * Access class
 */
class Access  {

	/**
	 * Work method
	 *
	 * @action wp
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function work() {

		$setting = underdev_get_setting( 'maintenance/access/block' );

		if ( $setting == 'unlocked' ) {
			return;
		}

		if ( $setting == 'admin' && ! current_user_can( 'manage_options' ) ) {

			$this->drop_message();

		} else if ( $setting == 'ip' ) {

			$ips = underdev_get_setting( 'maintenance/access/allowed_ips' );
			if ( ! in_array( underdev_get_ip(), explode( ',', $ips ) ) ) {
				$this->drop_message();
			}

		}

	}

	/**
	 * Drops the maintenance message
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function drop_message() {

		$message  = '<h1>' . __( 'Planned maintenance in progress' ) . '</h1>';
		$message .= '<p>' . __( 'The requested service is temporarily not available due to maintenance. Please try again later.' ) . '</p>';
		$message .= '<p>' . __( 'We are sorry for the inconvenience.' ) . '</p>';

		wp_die( $message, __( 'Planned maintenance' ), array( 'response' => 200 ) );

	}

}
