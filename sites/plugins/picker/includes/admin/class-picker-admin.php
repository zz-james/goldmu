<?php
/**
 * Picker Admin class
 *
 * The Picker Admin class manage admin page options
 *
 * @author      Andrea Landonio
 * @class       Picker_Admin
 * @category    Admin
 * @package     Picker/Includes/Admin
 */

/**
 * Picker_Admin class
 */
class Picker_Admin {

	/**
	 * @var array $errors An array containing all error messages
	 */
	private static $errors = array();

	/**
	 * @var array $messages An array containing all generic messages
	 */
	private static $messages = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add actions
		add_action( 'admin_init', array( $this, 'picker_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'picker_admin_page' ) );
	}

	/**
	 * Add settings before the admin page is rendered
	 */
	function picker_admin_init() {
		register_setting( 'picker_admin_options', PKR_DOMAIN );
		add_settings_section( 'picker_admin_main', '', array( $this, 'picker_admin_options_show' ), 'picker_plugin' );
	}

	/**
	 * Add admin option page
	 */
	public function picker_admin_page() {
		add_options_page( 'Picker', 'Picker', 'manage_options', PKR_BASE, array( $this, 'picker_admin_settings' ) );
	}

	/**
	 * Render admin settings page
	 */
	public function picker_admin_settings() {
		settings_fields( 'picker_admin_options' );

		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			self::picker_admin_save_settings();
		}

		// Add any posted messages
		if ( ! empty( $_GET['wc_error'] ) ) {
			self::picker_admin_add_error( stripslashes( $_GET['wc_error'] ) );
		}

		if ( ! empty( $_GET['wc_message'] ) ) {
			self::picker_admin_add_message( stripslashes( $_GET['wc_message'] ) );
		}

		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo plugins_url( 'picker/images/icon32.png' ) ?>" /></div>
			<h2><?php _e( 'Picker settings', PKR_DOMAIN ) ?></h2>

			<?php
			// Show messages
			self::show_messages();
			?>

			<form method="post" action="">
				<p>
					<?php _e( 'In this page you can manage all plugin variables and configuration with the follow fields. If you leave blank settings fields Picker plugin provide these variables with a constant value.', PKR_DOMAIN ) ?><br />
					<span class="description"><?php _e( 'Note: Additional information about the plugin at', PKR_DOMAIN ) ?> <a href="<?php echo PKR_PLUGIN_SITE ?>" title="<?php _e( 'Plugin site', PKR_DOMAIN ) ?>"><?php _e( 'Picker plugin site', PKR_DOMAIN ) ?></a></span><br /><br />
				</p>
				<?php
				// Prints out all settings of picker settings page
				do_settings_sections('picker_plugin');
				?>
				<input id="formaction" name="picker_admin_action" type="hidden" value="picker_admin_update" /><br />
				<p>
					<input name="submit" type="submit" class="button-primary" value="<?php _e( 'Save', PKR_DOMAIN ) ?>" />
				</p>
			</form>

		</div>
		<?php
	}

	/**
	 * Render admin settings page options and fields
	 */
	public function picker_admin_options_show() {
		// Read saved options
		$options = get_option( PKR_BASE );

		// If options already saved, use these, instead use defaults
		if ( $options ) {
			// Get single values
			$pkr_post_list_items = $options['pkr_post_list_items'];
			$pkr_cache_enabled = $options['pkr_cache_enabled'];
			$pkr_cache_cleanup = $options['pkr_cache_cleanup'];
			$pkr_cache_expire_default = $options['pkr_cache_expire_default'];
			$pkr_cache_expire_scheduled = $options['pkr_cache_expire_scheduled'];
		}
		else {
			// User defaults
			$pkr_post_list_items = PKR_POST_LIST_ITEMS;
			$pkr_cache_enabled = PKR_CACHE_ENABLED;
			$pkr_cache_cleanup = PKR_CACHE_CLEANUP;
			$pkr_cache_expire_default = PKR_CACHE_EXPIRE_DEFAULT;
			$pkr_cache_expire_scheduled = PKR_CACHE_EXPIRE_SCHEDULED;
		}
		?>

		<h3 class="title"><?php _e( 'General settings', PKR_DOMAIN ) ?></h3>
		<p class="description">
			<?php _e( 'This section contains all generics configuration about the plugin.', PKR_DOMAIN ) ?>
		</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="pkr_post_list_items"><?php _e( 'Post list items', PKR_DOMAIN ) ?></label></th>
					<td>
						<select name="pkr_post_list_items" id="pkr_post_list_items">
							<option value="-">-</option>
							<option value="10" <?php echo ( $pkr_post_list_items == 10 ) ? 'selected="selected"' : '' ?>>10</option>
							<option value="20" <?php echo ( $pkr_post_list_items == 20 ) ? 'selected="selected"' : '' ?>>20</option>
							<option value="50" <?php echo ( $pkr_post_list_items == 50 ) ? 'selected="selected"' : '' ?>>50</option>
							<option value="100" <?php echo ( $pkr_post_list_items == 100 ) ? 'selected="selected"' : '' ?>>100</option>
							<option value="200" <?php echo ( $pkr_post_list_items == 200 ) ? 'selected="selected"' : '' ?>>200</option>
							<option value="500" <?php echo ( $pkr_post_list_items == 500 ) ? 'selected="selected"' : '' ?>>500</option>
						</select>
						<p class="description">
							<?php _e( 'Sets the number of items shown in the post list field inside Picker widget.', PKR_DOMAIN ) ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e( 'Cache settings', PKR_DOMAIN ) ?></h3>
		<p class="description">
			<?php _e( 'This section contains all the cache configuration about the plugin. You can enable data caching, choose the expiration times for transient data and define the behavior during plugin\'s deactivation. <br/>The plugin deactivation management is available only for WordPress versions equal or higher than 3.9.' , PKR_DOMAIN ) ?>
		</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e( 'Enable cache', PKR_DOMAIN ) ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Enable cache', PKR_DOMAIN ) ?></span></legend>
							<label for="pkr_cache_enabled">
								<input type="checkbox" id="pkr_cache_enabled" name="pkr_cache_enabled" value="1" <?php echo ( $pkr_cache_enabled || $pkr_cache_enabled == 1 ) ? 'checked="checked"' : '' ?> />
								<?php _e( 'Enable caching for plugin data', PKR_DOMAIN ) ?>
							</label>
							<p class="description">
								<?php _e( 'Select field if you want enable this feature.', PKR_DOMAIN ) ?>
							</p>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Enable cache cleanup on plugin deactivation', PKR_DOMAIN ) ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e( 'Enable cache cleanup on plugin deactivation', PKR_DOMAIN ) ?></span></legend>
							<label for="pkr_cache_cleanup">
								<input type="checkbox" id="pkr_cache_cleanup" name="pkr_cache_cleanup" value="1" <?php echo ( $pkr_cache_cleanup || $pkr_cache_cleanup == 1 ) ? 'checked="checked"' : '' ?> />
								<?php _e( 'Clean up cache on plugin\'s deactivation ', PKR_DOMAIN ) ?>
							</label>
							<p class="description">
								<?php _e( 'Select field if you want enable this feature.', PKR_DOMAIN ) ?>
							</p>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="pkr_cache_expire_default"><?php _e( 'Default cache expiration', PKR_DOMAIN ) ?></label></th>
					<td>
						<select name="pkr_cache_expire_default" id="pkr_cache_expire_default">
							<option value="-">-</option>
							<option value="300" <?php echo ( $pkr_cache_expire_default == 300 ) ? 'selected="selected"' : '' ?>>5 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="600" <?php echo ( $pkr_cache_expire_default == 600 ) ? 'selected="selected"' : '' ?>>10 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="1800" <?php echo ( $pkr_cache_expire_default == 1800 ) ? 'selected="selected"' : '' ?>>30 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="3600" <?php echo ( $pkr_cache_expire_default == 3600 ) ? 'selected="selected"' : '' ?>>1 <?php _e( 'hour', PKR_DOMAIN ) ?></option>
							<option value="10800" <?php echo ( $pkr_cache_expire_default == 10800 ) ? 'selected="selected"' : '' ?>>3 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="21600" <?php echo ( $pkr_cache_expire_default == 21600 ) ? 'selected="selected"' : '' ?>>6 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="43200" <?php echo ( $pkr_cache_expire_default == 43200 ) ? 'selected="selected"' : '' ?>>12 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="86400" <?php echo ( $pkr_cache_expire_default == 86400 ) ? 'selected="selected"' : '' ?>>1 <?php _e( 'day', PKR_DOMAIN ) ?></option>
						</select>
						<p class="description">
							<?php _e( 'Set the cache expiration value for standard items.', PKR_DOMAIN ) ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="pkr_cache_expire_scheduled"><?php _e( 'Scheduled items cache expiration', PKR_DOMAIN ) ?></label></th>
					<td>
						<select name="pkr_cache_expire_scheduled" id="pkr_cache_expire_scheduled">
							<option value="-">-</option>
							<option value="300" <?php echo ( $pkr_cache_expire_scheduled == 300 ) ? 'selected="selected"' : '' ?>>5 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="600" <?php echo ( $pkr_cache_expire_scheduled == 600 ) ? 'selected="selected"' : '' ?>>10 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="1800" <?php echo ( $pkr_cache_expire_scheduled == 1800 ) ? 'selected="selected"' : '' ?>>30 <?php _e( 'minutes', PKR_DOMAIN ) ?></option>
							<option value="3600" <?php echo ( $pkr_cache_expire_scheduled == 3600 ) ? 'selected="selected"' : '' ?>>1 <?php _e( 'hour', PKR_DOMAIN ) ?></option>
							<option value="10800" <?php echo ( $pkr_cache_expire_scheduled == 10800 ) ? 'selected="selected"' : '' ?>>3 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="21600" <?php echo ( $pkr_cache_expire_scheduled == 21600 ) ? 'selected="selected"' : '' ?>>6 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="43200" <?php echo ( $pkr_cache_expire_scheduled == 43200 ) ? 'selected="selected"' : '' ?>>12 <?php _e( 'hours', PKR_DOMAIN ) ?></option>
							<option value="86400" <?php echo ( $pkr_cache_expire_scheduled == 86400 ) ? 'selected="selected"' : '' ?>>1 <?php _e( 'day', PKR_DOMAIN ) ?></option>
						</select>
						<p class="description">
							<?php _e( 'Set the cache expiration value for scheduled items (widget items with a valid publish/expire time).', PKR_DOMAIN ) ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save admin settings
	 */
	public static function picker_admin_save_settings() {
		try {
			$options = array();

			// Read post data values
			$pkr_post_list_items = $_POST[ 'pkr_post_list_items' ];
			$pkr_cache_enabled = $_POST[ 'pkr_cache_enabled' ];
			$pkr_cache_cleanup = $_POST[ 'pkr_cache_cleanup' ];
			$pkr_cache_expire_default = $_POST[ 'pkr_cache_expire_default' ];
			$pkr_cache_expire_scheduled = $_POST[ 'pkr_cache_expire_scheduled' ];

			// Save post daa to options array
			$options[ 'pkr_post_list_items' ] = $pkr_post_list_items;
			$options[ 'pkr_cache_enabled' ] = ( ! empty ( $pkr_cache_enabled ) && $pkr_cache_enabled != NULL ) ? true : false;
			$options[ 'pkr_cache_cleanup' ] = ( ! empty ( $pkr_cache_cleanup ) && $pkr_cache_cleanup != NULL ) ? true : false;
			$options[ 'pkr_cache_expire_default' ] = $pkr_cache_expire_default;
			$options[ 'pkr_cache_expire_scheduled' ] = $pkr_cache_expire_scheduled;

			// Update option to database
			update_option( PKR_BASE, $options );

			// All is fine, add update successful message
			self::picker_admin_add_message( __( 'Settings saved.', PKR_DOMAIN ) );
		}
		catch ( Exception $e ) {
			// An exception occured, add error message
			self::picker_admin_add_error( __( 'Error', PKR_DOMAIN ) . ': ' . $e->getMessage() );
		}
	}

	/**
	 * Add a generic message
	 *
	 * @param string $text
	 */
	public static function picker_admin_add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error message
	 *
	 * @param string $text
	 */
	public static function picker_admin_add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output generic and errors messages
	 *
	 * @return string
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error fade"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated fade"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}
}

return new Picker_Admin();
