<?php
/**
 * Url field class
 *
 * @package notification/slugnamexx
 */

namespace BracketSpace\underDEV\Utils\Settings\CoreFields;

/**
 * Url class
 */
class Url {

	/**
	 * Url field
     *
	 * @param  Field $field Field instance.
	 * @return void
	 */
	public function input( $field ) {

		echo '<label><input type="url" id="' . $field->input_id() . '" name="' . $field->input_name() . '" value="' . $field->value() . '" class="widefat"></label>';

	}

	/**
	 * Sanitize input value
     *
	 * @param  string $value saved value.
	 * @return string        sanitized url
	 */
	public function sanitize( $value ) {

		return esc_url_raw( $value );

	}

}
