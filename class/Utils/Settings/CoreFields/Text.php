<?php
/**
 * Text field class
 *
 * @package notification/slugnamexx
 */

namespace BracketSpace\underDEV\Utils\Settings\CoreFields;

/**
 * Text class
 */
class Text {

	/**
	 * Text field
     *
	 * @param  Field $field Field instance.
	 * @return void
	 */
	public function input( $field ) {

		echo '<label><input type="text" id="' . $field->input_id() . '" name="' . $field->input_name() . '" value="' . $field->value() . '" class="widefat"></label>';

	}

	/**
	 * Sanitize input value
     *
	 * @param  string $value Saved value.
	 * @return string        Sanitized text
	 */
	public function sanitize( $value ) {

		return sanitize_text_field( $value );

	}

}
