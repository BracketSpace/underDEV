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
	        __( 'underDEV Settings', 'underDEV', 'notification-slugnamexx' ),
	        'underDEV',
	        'manage_options',
	        'underdev',
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

		$maintenance = $this->add_section( __( 'Maintenance', 'underdev', 'notification-slugnamexx' ), 'maintenance' );

		$maintenance->add_group( __( 'Access', 'underdev', 'notification-slugnamexx' ), 'access' )
			->add_field( array(
				'name'     => __( 'Block access', 'underdev', 'notification-slugnamexx' ),
				'slug'     => 'block',
				'default'  => 'unlocked',
				'addons'   => array(
					'pretty'  => true,
					'options' => array(
						'unlocked' => __( 'Unlocked', 'notification-slugnamexx' ),
						'admin'    => __( 'Locked for anyone except logged in admins', 'notification-slugnamexx' ),
						'ip'       => __( 'Locked for anyone except IPs', 'notification-slugnamexx' ),
					)
				),
				'render'   => array( new CoreFields\Select(), 'input' ),
				'sanitize' => array( new CoreFields\Select(), 'sanitize' ),
			) )
			->add_field( array(
				'name'        => __( 'Allowed IPs', 'underdev', 'notification-slugnamexx' ),
				'slug'        => 'allowed_ips',
				'default'     => '',
				'description' => sprintf( __( 'IPs allowed to access the website, comma separated list. Your IP is: %s', 'notification-slugnamexx' ), '<code>' . underdev_get_ip() . '</code>' ),
				'render'      => array( new CoreFields\Text(), 'input' ),
				'sanitize'    => array( new CoreFields\Text(), 'sanitize' ),
			) )
			->description( __( 'Website access settings', 'underdev', 'notification-slugnamexx' ) );

	}

}
