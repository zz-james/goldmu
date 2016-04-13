<?php
/**
 * nucleare Theme Customizer
 *
 * @package Nucleare
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nucleare_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	$wp_customize->add_section( 'nucleare_theme_options', array(
		'title' => __( 'Theme', 'nucleare' ),
	) );

	$wp_customize->add_setting( 'nucleare_hide_search', array(
		'default'           => false,
		'sanitize_callback' => 'nucleare_sanitize_checkbox'
	) );

	$wp_customize->add_control( 'nucleare_hide_search', array(
		'label'    => __( 'Hide search from header', 'nucleare' ),
		'settings' => 'nucleare_hide_search',
		'section'  => 'nucleare_theme_options',
		'type'     => 'checkbox',
	) );
}
add_action( 'customize_register', 'nucleare_customize_register' );

function nucleare_sanitize_checkbox( $input ) {
	if ( true == $input )
		return true;
	
	return false;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function nucleare_customize_preview_js() {
	wp_enqueue_script( 'nucleare_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'nucleare_customize_preview_js' );
