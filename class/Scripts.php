<?php
/**
 * Enqueues scripts
 *
 * @package underdev
 */

namespace BracketSpace\underDEV;

use BracketSpace\underDEV\Utils\Files;

/**
 * Scripts class
 */
class Scripts {

	/**
	 * Files class
     *
	 * @var object
	 */
	private $files;

	/**
	 * Runtime class
     *
	 * @var object
	 */
	private $runtime;

	/**
	 * Scripts constructor
	 *
	 * @since 5.0.0
	 * @param object $runtime Plugin Runtime class.
	 * @param Files  $files   Files class.
	 */
	public function __construct( $runtime, Files $files ) {
		$this->files   = $files;
		$this->runtime = $runtime;
	}

	/**
	 * Enqueue admin scripts and styles for admin
     *
	 * @param  string $page_hook current page hook.
	 * @return void
	 */
	public function admin_enqueue_scripts( $page_hook ) {

		$allowed_hooks = array(
			$this->runtime->settings->page_hook,
		);

		wp_enqueue_script( 'underdev', $this->files->asset_url( 'js', 'scripts.min.js' ), array( 'jquery' ), $this->files->asset_mtime( 'js', 'scripts.min.js' ), false );

		wp_enqueue_style( 'underdev', $this->files->asset_url( 'css', 'style.css' ), array(), $this->files->asset_mtime( 'css', 'style.css' ) );

	}


}
