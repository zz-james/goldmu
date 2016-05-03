<?php
/**
 * Configure settings and actions
 *
 * @author      Andrea Landonio
 * @category    Includes
 * @package     Picker/Includes
 */

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to the file is not permitted' );

if ( ! class_exists( 'Picker_Settings' ) ) :

class Picker_Settings {

	/**
	 * __construct function
	 */
	public function __construct() {
		// Register frontend stylesheets
		add_action( 'wp_print_styles', array( $this, 'picker_add_frontend_styles' ) );

		// Register admin stylesheets
		add_action( 'admin_print_styles', array( $this, 'picker_add_admin_styles' ) );

		// Register admin scripts
		add_action( 'admin_print_scripts', array( $this, 'picker_add_admin_scripts' ) );
	}

	/**
	 * Register frontend stylesheets
	 */
	public function picker_add_frontend_styles() {
		// Register custom frontend stylesheet
		wp_register_style( 'picker-style', plugins_url( PKR_BASE . '/css/picker.css' ) );
		wp_enqueue_style( 'picker-style' );
	}

	/**
	 * Register admin stylesheets
	 */
	public function picker_add_admin_styles() {
		// Register jQuery UI stylesheet
		wp_register_style( 'jquery-ui' , 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );

		// Register custom admin stylesheet
		wp_register_style( 'picker-admin-style', plugins_url( PKR_BASE . '/css/picker-admin.css' ) );
		wp_enqueue_style( 'picker-admin-style' );
	}

	/**
	 * Register admin scripts
	 */
	public function picker_add_admin_scripts() {
		// Register auto resize script plugin
		wp_register_script( 'picker-admin-autoresize', plugins_url( PKR_BASE . '/js/jquery/jquery.textareaAutoResize.js' ) );

		// Register auto resize script plugin
		wp_register_script( 'picker-admin-datetimepicker', plugins_url( PKR_BASE . '/js/jquery/jquery-ui-timepicker-addon.js' ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-slider' ) );

		// Register custom script plugin
		wp_register_script( 'picker-admin-script', plugins_url( PKR_BASE . '/js/picker-admin.js' ), array( 'jquery', 'picker-admin-datetimepicker', 'picker-admin-autoresize' ) );

		// Enqueue jQuery autocomplete plugin
		wp_enqueue_script( 'jquery-ui-autocomplete' );

		// Enqueue custom script plugin
		wp_enqueue_script( 'picker-admin-script' );
	}
}

endif;

return new Picker_Settings();
