<?php
/**
 * Core functions and actions
 *
 * @author      Andrea Landonio
 * @category    Includes
 * @package     Picker/Includes
 */

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to the file is not permitted' );

// Include core functions (available in both admin and frontend)
include( 'utils/picker-utils.php' );

/**
 * Get other templates passing attributes and including the file
 *
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function picker_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    $located = picker_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0.0' );
        return;
    }

    // Allow 3rd party plugin filter template file from their plugin
    $located = apply_filters( 'picker_get_template', $located, $template_name, $args, $template_path, $default_path );

    do_action( 'picker_before_template_part', $template_name, $template_path, $located, $args );

    include($located);

    do_action( 'picker_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion
 *
 * This is the load order:
 *	 theme / $template_path / $template_name (default: theme template folder)
 *	 theme / $template_name (default: theme root folder)
 *	 $default_path / $template_name (default: plugin templates folder)
 *
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function picker_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path ) {
        $template_path = Picker_Plugin()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = Picker_Plugin()->plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority
    $template = locate_template( array(
        trailingslashit($template_path) . $template_name,
        $template_name
    ) );

    // Get default template
    if ( ! $template ) {
        $template = $default_path . $template_name;
    }

    // Return what we found
    return apply_filters( 'picker_locate_template', $template, $template_name, $template_path );
}

/**
 * Retrieve option value
 *
 * @param $key
 * @param string $default
 *
 * @return string
 */
function picker_get_option_value( $key, $default = '' ) {
    $options = get_option( PKR_BASE );
    return ( $options[ $key ] != '-' ) ? $options[ $key ] : $default;
}

/**
 * Suggest AJAX lookup function
 */
function picker_suggest_lookup() {
    // Get search value and query database searching valid posts
    $posts = get_posts( array(
        's' => trim( esc_attr( strip_tags( $_REQUEST[ 'q' ] ) ) ),
        'post_status' => 'any',
        'post_type' => 'post',
        'posts_per_page' => 20,
        'orderby' => 'title',
        'order' => 'ASC',
    ) );

    $suggestions = array();
    global $post;

    // Loop query result sets
    foreach ( $posts as $post ):
        setup_postdata( $post );
        // Create suggestion item object
        $suggestion = array();
        $suggestion[ 'label' ] = picker_trunc_words( $post->post_title, 10 );
        $suggestion[ 'link' ] = get_permalink();
        $suggestion[ 'id' ] = $post->ID;
        $suggestions[] = $suggestion;
    endforeach;

    // Return JSONP callback
    echo $_GET["callback"] . "(" . json_encode( $suggestions ) . ")";
    exit;
}
add_action( 'wp_ajax_picker_suggest_lookup', 'picker_suggest_lookup' );
add_action( 'wp_ajax_nopriv_picker_suggest_lookup', 'picker_suggest_lookup' );
