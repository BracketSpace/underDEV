<?php
/**
 * Runtime
 *
 * @package underdev
 */

namespace BracketSpace\underDEV;

use BracketSpace\underDEV\Utils;

/**
 * Runtime class
 */
class Runtime {

	/**
	 * Class constructor
	 *
	 * @since 5.0.0
	 * @param string $plugin_file plugin main file full path.
	 */
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Loads needed files
	 *
	 * @since  5.0.0
	 * @return void
	 */
	public function boot() {

		$this->singletons();

		require_once $this->files->file_path( 'inc/functions.php' );

		$this->actions();

	}

	/**
	 * Creates needed classes
	 * Singletons are used for a sake of performance
	 *
	 * @since  5.0.0
	 * @return void
	 */
	public function singletons() {

		$this->files    = new Utils\Files( $this->plugin_file );
		$this->settings = new Settings();
		$this->scripts  = new Scripts( $this, $this->files );

	}

	/**
	 * All WordPress actions this plugin utilizes
	 *
	 * @since  5.0.0
	 * @return void
	 */
	public function actions() {

		add_action( 'admin_menu', array( $this->settings, 'register_page' ) );
		add_action( 'wp_loaded', array( $this->settings, 'register_settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this->scripts, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Returns new View object
	 *
	 * @since  5.0.0
	 * @return View view object
	 */
	public function view() {
		return new Utils\View( $this->files );
	}

	/**
	 * Returns new Ajax object
	 *
	 * @since  5.0.0
	 * @return Ajax ajax object
	 */
	public function ajax() {
		return new Utils\Ajax();
	}

}
