<?php
/**
 * Picker uninstall
 *
 * Uninstalling Picker plugin cleanup cache and widget data
 *
 * @author 		Andrea Landonio
 * @category 	Core
 * @package 	Picker
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

global $wpdb;

// Get cache cleanup value
$pkr_cache_cleanup = picker_get_option_value( 'pkr_cache_cleanup' , PKR_CACHE_CLEANUP );

// Check if cache cleanup is enabled
if ( $pkr_cache_cleanup ) {
	// Get WordPress version
	$wp_version = get_bloginfo( 'version' );

	// Check WordPress version, apply deactivation rules only for WordPress or higher
	if ( $wp_version >= 3.9 ) {
		// Remove all picker widget transient data
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'", '_transient_widget_' . PKR_BASE . '%' ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE '%s'", '_transient_timeout_widget_' . PKR_BASE . '%' ) );

		// Remove all picker widget data
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name = '%s'", 'widget_picker' ) );

		// Remove all picker admin page data
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name = '%s'", 'picker' ) );
	}
}
