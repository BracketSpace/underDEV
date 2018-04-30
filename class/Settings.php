<?php
/**
 * Settings class
 *
 * @package underdev
 */

namespace BracketSpace\underDEV;

use BracketSpace\underDEV\Utils\Settings as SettingsAPI;
use BracketSpace\underDEV\Utils\Settings\CoreFields;

/**
 * Settings class
 */
class Settings extends SettingsAPI {

	/**
	 * Settings constructor
	 */
	public function __construct() {
		parent::__construct( 'underdev' );
	}

	/**
	 * Register Settings page under Tools menu
	 *
	 * @action admin_menu
	 *
	 * @return void
	 */
	public function register_page() {

		$this->page_hook = add_management_page(
	        __( 'underDEV Settings', 'underDEV' ),
	        'underDEV',
	        'manage_options',
	        'settings',
	        array( $this, 'settings_page' )
	    );

	}

	/**
	 * Registers Settings
	 *
	 * @action wp_loaded
	 *
	 * @return void
	 */
	public function register_settings() {

		$maintenance = $this->add_section( __( 'Maintenance', 'underdev' ), 'maintenance' );

		$maintenance->add_group( __( 'Access', 'underdev' ), 'access' )
			->add_field( array(
				'name'     => __( 'Block access', 'underdev' ),
				'slug'     => 'block',
				'default'  => 'unlocked',
				'addons'   => array(
					'pretty'  => true,
					'options' => array(
						'unlocked' => __( 'Unlocked' ),
						'admin'    => __( 'Locked for anyone except logged in admins' ),
						'ip'       => __( 'Locked for anyone except IPs' ),
					)
				),
				'render'   => array( new CoreFields\Select(), 'input' ),
				'sanitize' => array( new CoreFields\Select(), 'sanitize' ),
			) )
			->description( __( 'Website access settings', 'underdev' ) );

	}

}
