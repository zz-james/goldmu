<?php
/**
 * Picker Item factory class
 *
 * The Picker Item factory create the right item object
 *
 * @author      Andrea Landonio
 * @class       Picker_Item_Factory
 * @category    Factory
 * @package     Picker/Includes
 */

class Picker_Item_Factory {

	/**
	 * Get picker item
	 *
	 * @param bool $item (default: false)
	 * @param array $args (default: array())
	 * @return Picker_Item
	 */
	public function get_item( $item = false, $args = array() ) {
		global $post;

		if ( false === $item ) {
			$item = $post;
		} elseif ( is_numeric( $item ) ) {
			$item = get_post( $item );
		}

		if ( ! $item ) return false;

		// Set defaults
		$classname = '';
		$post_type = '';
		$item_type = '';
		$item_id = '';

		if ( is_object ( $item ) ) {
			$item_id = absint( $item->ID );
			$item_type = $item->post_type;
		}

		if ( in_array( $post_type, array( 'custom_type' ) ) ) {
			// Create a Picker coding standards compliant class name e.g. Picker_Item_Type_Class instead of Picker_Item_type-class
		} else {
			$classname = false;
			$item_type = false;
		}

		// Filter classname so that the class can be overridden if extended.
		$classname = apply_filters( 'picker_item_class', $classname, $item_type, $post_type, $item_id );

		if ( ! class_exists( $classname ) ) $classname = 'Picker_Item_Default';

		return new $classname( $item, $args );
	}
}
