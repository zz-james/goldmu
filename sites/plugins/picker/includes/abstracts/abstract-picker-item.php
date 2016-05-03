<?php
/**
 * Abstract Picker Item class
 *
 * The Abstract Picker Item class handles individual item data
 *
 * @author      Andrea Landonio
 * @class       Picker_Item
 * @category    Abstract Class
 * @package     Picker/Includes/Abstracts
 */

class Picker_Item {

	/**
     * @var int The item (post) ID
     */
	public $id;

	/**
     * @var object The actual post object
     */
	public $post;

	/**
     * @var string The item type (default, custom type, etc)
     */
	public $item_type = null;

	/**
	 * @var string The item custom URL
	 */
	public $custom_url;

	/**
	 * @var string The item custom title
	 */
	public $custom_title;

	/**
	 * @var string The item custom excerpt
	 */
	public $custom_excerpt;

	/**
	 * @var string The item widget sidebar
	 */
	public $widget_sidebar;

	/**
	 * @var string The item widget order
	 */
	public $widget_order;

	/**
	 * __construct function gets the post object and sets the ID for the loaded item
	 *
	 * @param int|Picker_Item|WP_Post $item Item ID, post object, or item object
	 */
	public function __construct( $item ) {
		if ( is_numeric( $item ) ) {
			$this->id = absint( $item );
			$this->post = get_post( $this->id );
		} elseif ( $item instanceof Picker_Item ) {
			$this->id = absint( $item->id );
			$this->post = $item;
		} elseif ( $item instanceof WP_Post || isset( $item->ID ) ) {
			$this->id = absint( $item->ID );
			$this->post = $item;
		}
	}

	/**
	 * __get function for magic methods
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return get_post_meta( $this->id, $key, true );
	}

	/**
	 * Returns picker item post data
	 *
	 * @return object
	 */
	public function get_post_data() {
		return $this->post;
	}

	/**
	 * Returns picker item permalink
	 *
	 * @return string
	 */
	public function get_permalink() {
		return esc_url( get_permalink( $this->id ) );
	}

	/**
	 * Returns picker item title
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'picker_item_title', $this->post ? $this->post->post_title : '', $this );
	}

	/**
	 * Returns picker item excerpt
	 *
	 * @param string $max_words
	 * @param bool $use_content_if_empty
	 * @return mixed|void
	 */
    public function get_excerpt( $max_words = '', $use_content_if_empty = false ) {
        return apply_filters( 'picker_item_excerpt', $this->post ? ( ( empty( $this->post->post_excerpt ) && $use_content_if_empty) ? ( ( !empty( $max_words ) ? picker_trunc_words( picker_strip_and_trim( $this->post->post_content) , $max_words ) : $this->post->post_content ) ) : ( ( !empty( $max_words ) ? picker_trunc_words( $this->post->post_excerpt, $max_words ) : $this->post->post_excerpt ) ) ) : '', $this );
    }

	/**
	 * Returns picker item content
	 *
	 * @param string $max_words
	 * @return mixed|void
	 */
    public function get_content( $max_words = '' ) {
        return apply_filters( 'picker_item_content', $this->post ? ( ( !empty( $max_words ) ) ? picker_trunc_words( $this->post->post_content, $max_words ) : $this->post->post_content ) : '', $this );
    }

	/**
	 * Returns picker item categories
	 *
	 * @param string $sep (default: ')
	 * @param mixed '
	 * @param string $before (default: '')
	 * @param string $after (default: '')
	 * @return string
	 */
	public function get_categories( $sep = ', ', $before = '', $after = '' ) {
		return get_the_term_list( $this->id, 'category', $before, $sep, $after );
	}

	/**
	 * Returns picker item tags
	 *
	 * @param string $sep (default: ', ')
	 * @param string $before (default: '')
	 * @param string $after (default: '')
	 * @return array
	 */
	public function get_tags( $sep = ', ', $before = '', $after = '' ) {
		return get_the_term_list( $this->id, 'post_tag', $before, $sep, $after );
	}

    /**
     * Returns picker item formats
     *
     * @param string $sep (default: ', ')
     * @param string $before (default: '')
     * @param string $after (default: '')
     * @return array
     */
    public function get_formats( $sep = ', ', $before = '', $after = '' ) {
        return get_the_term_list( $this->id, 'post_format', $before, $sep, $after );
    }

	/**
	 * Gets the main item image ID
	 *
	 * @return int
	 */
	public function get_image_id() {
		if ( has_post_thumbnail( $this->id ) ) {
			$image_id = get_post_thumbnail_id( $this->id );
		} elseif ( ( $parent_id = wp_get_post_parent_id( $this->id ) ) && has_post_thumbnail( $parent_id ) ) {
			$image_id = get_post_thumbnail_id( $parent_id );
		} else {
			$image_id = 0;
		}

		return $image_id;
	}

	/**
	 * Returns the main item image
	 *
	 * @param string $size (default: 'thumbnail')
	 * @param array $attr
	 * @return mixed|string|void
	 */
	public function get_image( $size = 'thumbnail', $attr = array() ) {
		if ( has_post_thumbnail( $this->id ) ) {
			$image = get_the_post_thumbnail( $this->id, $size, $attr );
		} elseif ( ( $parent_id = wp_get_post_parent_id( $this->id ) ) && has_post_thumbnail( $parent_id ) ) {
			$image = get_the_post_thumbnail( $parent_id, $size, $attr );
		} else {
			$image = NULL;
		}

		return $image;
	}

    /**
     * Check if picker item has image
     *
     * @return mixed|string|void
     */
    public function has_image() {
        if ( has_post_thumbnail( $this->id ) ) {
            $has_image = true;
        } elseif ( ( $parent_id = wp_get_post_parent_id( $this->id ) ) && has_post_thumbnail( $parent_id ) ) {
            $has_image = true;
        } else {
            $has_image = false;
        }

        return $has_image;
    }

	/**
	 * Returns picker item custom url
	 *
	 * @return string
	 */
	public function get_custom_url() {
		return $this->custom_url;
	}

	/**
	 * Returns picker item custom title
	 *
	 * @return string
	 */
	public function get_custom_title() {
		return $this->custom_title;
	}

	/**
	 * Returns picker item custom excerpt
	 *
	 * @return string
	 */
	public function get_custom_excerpt() {
		return $this->custom_excerpt;
	}

	/**
	 * Returns picker item widget sidebar
	 *
	 * @return string
	 */
	public function get_widget_sidebar() {
		return $this->widget_sidebar;
	}

	/**
	 * Returns picker item widget order
	 *
	 * @return string
	 */
	public function get_widget_order() {
		return $this->widget_order;
	}
}
