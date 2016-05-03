<?php
/*
Plugin Name: Picker
Description: Picker is a simple and flexible plugin which allow users to choose a specific post inside admin widgets page and display it in their site frontend.
Author: Andrea Landonio
Author URI: http://www.andrealandonio.it
Text Domain: picker
Domain Path: /languages/
Version: 1.0.0
License: GPL v3

Picker
Copyright (C) 2013-2016, Andrea Landonio - landonio.andrea@gmail.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to the file is not permitted' );

if ( ! class_exists( 'Picker_Plugin' ) ) :

    /**
     * Main Picker Class
     *
     * @author      Andrea Landonio
     * @class       Picker_Plugin
     * @category    Class
     * @package     Picker
     */
    final class Picker_Plugin {

        /**
         * @var string The plugin version
         */
        public $version = '1.0.0';

        /**
         * @var Picker_Plugin The single instance of the class
         */
        protected static $_instance = null;

        /**
         * @var Picker_Item_Factory $factory The factory class
         */
        public $factory = null;

        /**
         * Main Picker Instance
         * Ensures only one instance of Picker is loaded or can be loaded
         *
         * @return Picker_Plugin - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * __clone function because cloning is forbidden
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', PKR_DOMAIN ), '1.0.0' );
        }

        /**
         * __wakeup function because unserialize instances of this class is forbidden
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', PKR_DOMAIN ), '1.0.0' );
        }

        /**
         * __construct function
         *
         * @return Picker_Plugin
         */
        public function __construct() {
            // Auto-load classes on demand
            if ( function_exists( "__autoload" ) ) {
                spl_autoload_register( "__autoload" );
            }
            spl_autoload_register( array( $this, 'autoload' ) );

            // Define constants
            $this->define_constants();

            // Include required files
            $this->includes();

            // Hooks
            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'widgets_init', array( $this, 'include_widgets' ) );
        }

        /**
         * __get function to auto-load in-accessible properties on demand
         *
         * @param mixed $key
         * @return mixed
         */
        public function __get( $key ) {
            if ( method_exists( $this, $key ) ) {
                return $this->$key();
            }
            return false;
        }

        /**
         * Auto-load classes on demand to reduce memory consumption
         *
         * @param mixed $class
         */
        public function autoload( $class ) {
            $path = null;
            $class = strtolower( $class );
            $file = 'class-' . str_replace( '_', '-', $class ) . '.php';

            if ( strpos( $class, 'picker_admin' ) === 0 ) {
                $path = $this->plugin_path() . '/includes/admin/';
            } elseif ( strpos( $class, 'picker_utils' ) === 0 ) {
                $path = $this->plugin_path() . '/includes/utils/';
            } elseif ( strpos( $class, 'picker_widget_' ) === 0 ) {
                $path = $this->plugin_path() . '/includes/widgets/';
            }

            if ( $path && is_readable( $path . $file ) ) {
                include_once( $path . $file );
                return;
            }

            // Fallback
            if ( strpos( $class, 'picker_' ) === 0 ) {
                $path = $this->plugin_path() . '/includes/';
            }

            if ( $path && is_readable( $path . $file ) ) {
                include_once( $path . $file );
                return;
            }
        }

        /**
         * Define constants
         */
        private function define_constants() {
            define( 'PKR_PLUGIN_FILE', __FILE__ );
            define( 'PKR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            define( 'PKR_PLUGIN_SITE', '#' );
            define( 'PKR_VERSION', $this->version );
            define( 'PKR_LOCALE_IT', 'it_IT' );
            define( 'PKR_DOMAIN', 'picker' );
            define( 'PKR_BASE', 'picker' );

            if ( ! defined( 'PKR_DELIMITER' ) ) {
                define( 'PKR_DELIMITER', '|' );
            }

            // Default values for admin settings variables
            define( 'PKR_POST_LIST_ITEMS', 50 );
            define( 'PKR_CACHE_ENABLED', true );
            define( 'PKR_CACHE_CLEANUP', true );
            define( 'PKR_CACHE_EXPIRE_DEFAULT', 60 * 60 * 24 );
            define( 'PKR_CACHE_EXPIRE_SCHEDULED', 60 * 5 );
        }

        /**
         * Include required core files used in admin and on the frontend
         */
        private function includes() {
            // Include generics files
            include_once( 'includes/core.php' );
            include_once( 'includes/install.php' );
            include_once( 'includes/settings.php' );

            // Include admin classes
            if ( is_admin() ) {
                include_once( 'includes/admin/class-picker-admin.php' );
            }

            // Include abstract classes
            include_once( 'includes/abstracts/abstract-picker-item.php' );

            // Include classes and factories
            include_once( 'includes/class-picker-item-factory.php' );
        }

        /**
         * Include core widgets
         */
        public function include_widgets() {
            include_once( 'includes/abstracts/abstract-picker-widget.php' );

            // Register widgets
            register_widget( 'Picker_Widget_Default' );
        }

        /**
         * Init Picker when WordPress initialises
         */
        public function init() {
            // Set up localization
            $this->load_plugin_textdomain();

            // Load class Picker Item Factory to create new item instances
            $this->factory = new Picker_Item_Factory();

        }

        /**
         * Load Localisation files
         *
         * Note: the first-loaded translation file overrides any following ones if the same translation is present
         */
        public function load_plugin_textdomain() {
            $locale = apply_filters( 'plugin_locale', get_locale(), PKR_DOMAIN );
            $dir = trailingslashit( WP_LANG_DIR );

            /**
             * Global Locale. Looks in:
             * 	- WP_LANG_DIR/Picker/Picker-LOCALE.mo
             * 	- picker/languages/Picker-LOCALE.mo (which if not found falls back to:)
             * 	- WP_LANG_DIR/plugins/Picker-LOCALE.mo
             */
            load_textdomain( PKR_DOMAIN, $dir . 'picker/picker-' . $locale . '.mo' );
            load_plugin_textdomain( PKR_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
        }

        /********************
         * Helper functions *
         ********************/

        /**
         * Get plugin url
         *
         * @return string
         */
        public function plugin_url() {
            return untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        /**
         * Get plugin path
         *
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * Get template path
         *
         * @return string
         */
        public function template_path() {
            return apply_filters( 'picker_template_path', 'picker/' );
        }

        /**
         * Get Ajax URL
         *
         * @return string
         */
        public function ajax_url() {
            return admin_url( 'admin-ajax.php', 'relative' );
        }
    }

endif;

/**
 * Returns the main instance of Picker to prevent the need to use globals.
 *
 * @return Picker_Plugin
 */
function Picker_Plugin() {
    return Picker_Plugin::instance();
}

// Global for backwards compatibility.
$GLOBALS['Picker_Plugin'] = Picker_Plugin();
