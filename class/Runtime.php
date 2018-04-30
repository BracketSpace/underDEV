<?php
/**
 * Runtime
 *
 * @package underdev
 */

namespace BracketSpace\underDEV;

use BracketSpace\underDEV\Utils;
use BracketSpace\underDEV\Worker;

/**
 * Runtime class
 */
class Runtime {

	/**
	 * Required instead of a static variable inside the add_doc_hooks method
	 * for the sake of unit testing
	 *
	 * @var array
	 */
	protected $_called_doc_hooks = array();

	/**
	 * Pattern for doc hooks
	 *
	 * @var string
	 */
	protected $doc_hooks_pattern = '#\* @(?P<type>filter|action|shortcode)\s+(?P<name>[a-z0-9\-\.\/_]+)(\s+(?P<priority>\d+))?#';

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 * @param string $plugin_file plugin main file full path.
	 */
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Loads needed files
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function boot() {

		$this->singletons();
		$this->workers();

		require_once $this->files->file_path( 'inc/functions.php' );

	}

	/**
	 * Creates needed classes
	 * Singletons are used for a sake of performance
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function singletons() {

		$this->files    = $this->create_class( new Utils\Files( $this->plugin_file ) );
		$this->settings = $this->create_class( new Settings() );
		$this->scripts  = $this->create_class( new Scripts( $this, $this->files ) );

	}

	/**
	 * Creates all workers
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function workers() {

		$this->create_class( new Worker\Access() );

	}

	/**
	 * Returns new View object
	 *
	 * @since  1.0.0
	 * @return View view object
	 */
	public function view() {
		return new Utils\View( $this->files );
	}

	/**
	 * Returns new Ajax object
	 *
	 * @since  1.0.0
	 * @return Ajax ajax object
	 */
	public function ajax() {
		return new Utils\Ajax();
	}

	/**
	 * Creates the class and registers all the hooks
	 * Optionally stores it as a class property
	 *
	 * @param  string $class class instance.
	 * @return mixed class instance.
	 */
	public function create_class( $class ) {
		$this->add_doc_hooks( $class );
		return $class;
	}

	/**
	 * Hooks a function on to a specific filter
	 *
	 * @param string $name     The hook name.
	 * @param array  $callback The class object and method.
	 * @param array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	public function add_filter( $name, $callback, $args = array() ) {

		// Merge defaults.
		$args = array_merge(
			array(
				'priority'  => 10,
				'arg_count' => PHP_INT_MAX,
			), $args
		);

		return $this->_add_hook( 'filter', $name, $callback, $args );

	}

	/**
	 * Hooks a function on to a specific action
	 *
	 * @param string $name     The hook name.
	 * @param array  $callback The class object and method.
	 * @param array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	public function add_action( $name, $callback, $args = array() ) {

		// Merge defaults.
		$args = array_merge(
			array(
				'priority'  => 10,
				'arg_count' => PHP_INT_MAX,
			), $args
		);

		return $this->_add_hook( 'action', $name, $callback, $args );

	}

	/**
	 * Hooks a function on to a specific shortcode
	 *
	 * @param string $name     The shortcode name.
	 * @param array  $callback The class object and method.
	 * @return mixed
	 */
	public function add_shortcode( $name, $callback ) {
		return $this->_add_hook( 'shortcode', $name, $callback );
	}

	/**
	 * Hooks a function on to a specific action/filter
	 *
	 * @param string $type     The hook type. Options are action/filter.
	 * @param string $name     The hook name.
	 * @param array  $callback The class object and method.
	 * @param array  $args     An array with priority and arg_count.
	 * @return mixed
	 */
	protected function _add_hook( $type, $name, $callback, $args = array() ) {

		$priority  = isset( $args['priority'] ) ? $args['priority'] : 10;
		$arg_count = isset( $args['arg_count'] ) ? $args['arg_count'] : PHP_INT_MAX;

		$function = sprintf( '\add_%s', $type );

		$retval = \call_user_func( $function, $name, $callback, $priority, $arg_count );

		return $retval;

	}

	/**
	 * Add actions/filters/shortcodes from the methods of a class based on DocBlocks
	 *
	 * @param object $object The class object.
	 */
	public function add_doc_hooks( $object = null ) {

		if ( is_null( $object ) ) {
			$object = $this;
		}

		$class_name = get_class( $object );

		if ( isset( $this->_called_doc_hooks[ $class_name ] ) ) {
			return;
		}

		$this->_called_doc_hooks[ $class_name ] = true;
		$reflector                              = new \ReflectionObject( $object );

		foreach ( $reflector->getMethods() as $method ) {

			$doc       = $method->getDocComment();
			$arg_count = $method->getNumberOfParameters();

			if ( preg_match_all( $this->doc_hooks_pattern, $doc, $matches, PREG_SET_ORDER ) ) {

				foreach ( $matches as $match ) {

					$type = $match['type'];
					$name = $match['name'];

					$priority = empty( $match['priority'] ) ? 10 : intval( $match['priority'] );
					$callback = array( $object, $method->getName() );

					call_user_func( array( $this, "add_{$type}" ), $name, $callback, compact( 'priority', 'arg_count' ) );

				}
			}
		}

	}

	/**
	 * Removes the added DocBlock hooks
	 *
	 * @param object $object The class object.
	 */
	public function remove_doc_hooks( $object = null ) {

		if ( is_null( $object ) ) {
			$object = $this;
		}

		$class_name = get_class( $object );
		$reflector  = new \ReflectionObject( $object );

		foreach ( $reflector->getMethods() as $method ) {

			$doc = $method->getDocComment();

			if ( preg_match_all( $this->doc_hooks_pattern, $doc, $matches, PREG_SET_ORDER ) ) {

				foreach ( $matches as $match ) {

					$type = $match['type'];
					$name = $match['name'];

					$priority = empty( $match['priority'] ) ? 10 : intval( $match['priority'] );
					$callback = array( $object, $method->getName() );

					call_user_func( "remove_{$type}", $name, $callback, $priority );

				}
			}
		}

		unset( $this->_called_doc_hooks[ $class_name ] );

	}

	/**
	 * Plugin's destructor
	 */
	public function __destruct() {
		$this->remove_doc_hooks();
	}

}
