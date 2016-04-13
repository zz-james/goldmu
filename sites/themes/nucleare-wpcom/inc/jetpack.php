<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Nucleare
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function nucleare_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'page',
		'footer_widgets' => array( 'footer-sidebar-1', 'footer-sidebar-2', 'footer-sidebar-3' ),
	) );
	
	add_theme_support( 'jetpack-responsive-videos' );
	
	add_theme_support( 'site-logo', array( 'size' => 'nucleare-logo' ) );
	
	add_image_size( 'nucleare-logo', '700', '300' );
}
add_action( 'after_setup_theme', 'nucleare_jetpack_setup' );
