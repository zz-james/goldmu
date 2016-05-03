<?php
/**
 * Install related functions and actions
 *
 * @author      Andrea Landonio
 * @category    Includes
 * @package     Picker/Includes
 */

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to the file is not permitted' );

if ( ! class_exists( 'Picker_Install' ) ) :

class Picker_Install {

	/**
	 * __construct function
	 */
	public function __construct() {
		// Run this on activation
		register_activation_hook( PKR_PLUGIN_FILE, array( $this, 'activation' ) );

		// Run this on deactivation
		register_deactivation_hook( PKR_PLUGIN_FILE, array( $this, 'deactivation' ) );

		// Run this on uninstall
		register_uninstall_hook( PKR_PLUGIN_FILE, array( $this, 'deactivation' ) );
	}

	/**
	 * Activate Picker
	 */
	public function activation() {
		// Nothing to do
	}

	/**
	 * Deactivate Picker
	 */
	public function deactivation() {
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
	}
}

endif;

return new Picker_Install();
