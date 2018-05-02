<?php
/**
 * Cache object, which utilises key
 *
 * @package notification/slugnamexx
 */

namespace BracketSpace\underDEV\Utils\Cache;

/**
 * Cache class
 */
class Cache {

    /**
	 * Cache unique key
     *
	 * @var string
	 */
	protected $key;

	/**
	 * Constructor
     *
	 * @param string $key cache unique key.
	 */
	public function __construct( $key ) {

		if ( empty( $key ) ) {
			trigger_error( 'Cache key cannot be empty' );
		}

		$this->key = $key;

	}

}
