<?php
/**
 * Access worker class
 *
 * @package underdev
 */

namespace BracketSpace\underDEV\Worker;

use BracketSpace\underDEV\Utils\Files;

/**
 * Access class
 */
class Access  {

	/**
	 * Files class
     *
	 * @var object
	 */
	private $files;

	/**
	 * Scripts constructor
	 *
	 * @since 1.0.0
	 * @param Files $files Files class.
	 */
	public function __construct( Files $files ) {
		$this->files = $files;
	}

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

	/**
	 * Admin bar notice
	 *
	 * @action admin_bar_menu
	 *
	 * @since  1.0.0
	 * @param object $admin_bar admin bar object.
	 * @return void
	 */
	public function admin_bar_notice( $admin_bar ) {

		if ( underdev_get_setting( 'maintenance/access/block' ) == 'unlocked' ) {
			return;
		}

		$admin_bar->add_menu( array(
		    'id'     => 'underdev-access-blocked',
            'parent' => 'top-secondary',
		    'title'  => __( 'Public access blocked' ),
		    'href'   => admin_url( 'tools.php?page=underdev&section=maintenance' ),
		) );

	}

	/**
	 * Enqueue access worker scripts
     *
     * @action admin_enqueue_scripts
     * @action wp_enqueue_scripts
     *
	 * @param  string $page_hook current page hook.
	 * @return void
	 */
	public function admin_enqueue_scripts( $page_hook ) {

		wp_enqueue_style( 'underdev-access', $this->files->asset_url( 'css/workers', 'access.css' ), array(), $this->files->asset_mtime( 'css/workers', 'access.css' ) );

	}

}
