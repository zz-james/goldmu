<?php
/**
 * Default Picker Item class
 *
 * The Picker Item class handles individual item data extending Abstract Picker Item class
 *
 * @author      Andrea Landonio
 * @class       Picker_Item_Default
 * @category    Class
 * @package     Picker/Includes
 */

class Picker_Item_Default extends Picker_Item {

	/**
	 * __construct function
	 *
	 * @param int|Picker_Item|WP_Post $item Item ID, post object, or item object
	 * @param array $args
	 */
	public function __construct( $item, $args = array() ) {
		// Set item type
		$this->item_type = 'default';

		// Set custom fields
		$this->custom_url = $args[ 'custom_url' ];
		$this->custom_title = $args[ 'custom_title' ];
		$this->custom_excerpt = $args[ 'custom_excerpt' ];
		$this->widget_sidebar = $args[ 'widget_sidebar' ];
		$this->widget_order = $args[ 'widget_order' ];

		// Call parent constructor
		parent::__construct( $item );
	}

	/**
	 * Returns picker item title
	 *
	 * @return string
	 */
	public function get_title() {
		// Override default picker item title management
		$title = $this->post->post_title;
		return apply_filters( 'picker_item_title', $title, $this );
	}
}
