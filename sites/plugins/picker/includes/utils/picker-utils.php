<?php
/**
 * Functions for picker item utility things
 *
 * @author 		Andrea Landonio
 * @category 	Utils
 * @package 	Picker/Includes/Utils
 */

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to the file is not permitted' );

/**
 * Main function for returning picker items, uses the Picker_Item_Factory class.
 *
 * @param mixed $item Post object or post ID of the picker item
 * @param array $args (default: array()) Contains all arguments to be used to get this picker item
 * @return Picker_Item
 */
function get_picker_item( $item = false, $args = array() ) {
    return Picker_Plugin()->factory->get_item( $item, $args );
}

/**
 * Truncate string by words
 *
 * @param string $text
 * @param int $max_words
 * @return string
 */
function picker_trunc_words( $text, $max_words ) {
    $text = strip_tags( strip_shortcodes( $text ) );
    $text_array = explode(' ',$text);
    if ( count( $text_array ) > $max_words && $max_words > 0 ) $text = implode( ' ', array_slice( $text_array, 0, $max_words ) ) . '...';
    return $text;
}

/**
 * Truncate string by chars
 *
 * @param string $text
 * @param int $max_chars
 * @return string
 */
function picker_trunc_chars( $text, $max_chars ) {
    $text = strip_tags( strip_shortcodes( $text ) );
    if ( strlen( $text ) > $max_chars ) {
        $text = substr( $text, 0, $max_chars );
        $i = strrpos( $text, " " );
        $text = substr( $text, 0, $i );
        $text = $text . " ...";
    }
    return $text;
}

/**
 * Strip and trim string
 *
 * @param string $text
 * @return string
 */
function picker_strip_and_trim( $text ) {
    $text = str_replace( ']]>', ']]&gt;', $text );
    $text = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $text );
    $text = strip_tags( $text );
    $text = strip_shortcodes( $text );
    $text = preg_replace( "/\s\s+/", " ", $text );
    return trim( stripcslashes( $text ) );
}
