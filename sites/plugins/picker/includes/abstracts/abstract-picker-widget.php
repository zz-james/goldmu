<?php
/**
 * Abstract Widget class
 *
 * The Abstract Widget class prepare the widget functions and manage widget cache
 *
 * @author      Andrea Landonio
 * @class       Abstract_Picker_Widget
 * @category    Abstract Class
 * @package     Picker/Includes/Abstracts
 * @extends     WP_Widget
 */

abstract class Abstract_Picker_Widget extends WP_Widget {

	/**
	 * @var string The widget id
	 */
	public $widget_id;

	/**
	 * @var string The widget name
	 */
	public $widget_name;

	/**
	 * @var string The widget description
	 */
	public $widget_description;

	/**
	 * @var string The widget CSS class
	 */
	public $widget_css_class;

	/**
	 * @var int The widget width
	 */
	public $widget_width;

	/**
	 * @var string The item widget sidebar
	 */
	public $widget_sidebar;

	/**
	 * @var string The item widget order
	 */
	public $widget_order;

	/**
	 * @var array The item widget settings
	 */
	public $settings;

	/**
	 * @var string The widget publish time
	 */
	public $time_to_publish;

	/**
	 * @var string The widget expire time
	 */
	public $time_to_expire;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Set widget options
		$widget_ops = array(
			'classname' => $this->widget_css_class,
			'description' => $this->widget_description
		);

		// Set control options
		$control_opts = array(
			'width' => $this->widget_width
		);

		// Create widget
		$this->WP_Widget( $this->widget_id, $this->widget_name, $widget_ops, $control_opts );

		// Add actions
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'publish_future_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	/**
	 * Detect widget extra info
	 *
	 * @param string $widget_id
	 */
	public function get_widget_info( $widget_id ) {
		$found_sidebar = false;
		$widgets_counter = 0;
		$same_widgets_counter = 0;

		// Get active sidebars
		$active_sidebars = wp_get_sidebars_widgets();

		// Loop sidebar
		foreach ( $active_sidebars as $key => $sidebar ) {
			$current_sidebar = $key;

			// Loop sidebar widgets
			foreach ( $sidebar as $item => $value ) {
				$widgets_counter = $widgets_counter + 1;
				$same_widgets_counter = $same_widgets_counter + 1;

				// Check if current widget ID match given widget ID
				if ( ! $found_sidebar && ( $value == $widget_id ) ) {
					$found_sidebar = true;
					$this->widget_sidebar = $current_sidebar;
					$this->widget_order = $same_widgets_counter;
				}
			}
			$same_widgets_counter = 0;
		}
	}

	/**
	 * Get cached widget object
	 *
	 * @return bool|Picker_Widget_Default
	 */
	public function get_cached_widget() {
		return get_transient( 'widget_' . $this->id_base . '-' . $this->number );
	}

	/**
	 * Cache widget object
	 *
	 * @param mixed $content
	 */
	public function cache_widget( $content ) {
		if ( ! empty( $this->time_to_publish ) ||  ! empty( $this->time_to_expire ) ) {
			// Widget with a time schedule (set custom cache expiration)
			$pkr_cache_expire_scheduled = picker_get_option_value( 'pkr_cache_expire_scheduled' , PKR_CACHE_EXPIRE_SCHEDULED );
			set_transient( 'widget_' . $this->id_base . '-' . $this->number, $content, $pkr_cache_expire_scheduled );
		}
		else {
			// Widget without a time schedule (default cache expires)
			$pkr_cache_expire_default = picker_get_option_value( 'pkr_cache_expire_default' , PKR_CACHE_EXPIRE_DEFAULT );
			set_transient( 'widget_' . $this->id_base . '-' . $this->number, $content, $pkr_cache_expire_default );
		}
	}

	/**
	 * Flush widget cache object
	 */
	public function flush_widget_cache() {
		delete_transient( 'widget_' . $this->id_base . '-' . $this->number );
	}

	/**
	 * Update widget settings
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// Save old instance
		$instance = $old_instance;

		if ( ! $this->settings ) {
			return $instance;
		}

		// Loop all settings field
		foreach ( $this->settings as $key => $setting ) {

			if ( isset( $new_instance[ $key ] ) ) {
				if ( 'textarea' === $setting['type'] || 'textarea_resizable' === $setting['type'] ) {
					// Keep original text for textarea fields
					$instance[ $key ] = $new_instance[ $key ];
				}
				else {
					// Sanitize text for generic fields
					$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
				}
			}
			elseif ( 'checkbox' === $setting['type'] ) {
				$instance[ $key ] = 0;
			}
		}

		// Flush cache
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Widget form
	 *
	 * @param array $instance
	 * @return NULL
	 */
	public function form( $instance ) {
		// If there is no settings, exit
		if ( ! $this->settings ) {
			return;
		}

		// Loop settings
		foreach ( $this->settings as $key => $setting ) {

			// Get setting value
			$value = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting[ 'std' ];

			// Get extra setting attributes (there is no sanitizing at the moment)
			$attributes = '';
			if ( isset( $setting[ 'attributes' ] ) ) foreach ( $setting[ 'attributes' ] as $attribute => $attr_value ) $attributes .= ' ' . $attribute . '="' . $attr_value . '"';

			//TODO: provide other fields support (eg: radio, date, time, button, etc)

			// Show field HTML according to field type
			switch ( $setting[ 'type' ] ) {

				case "text" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<input type="text" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" value="<?php echo esc_attr( $value ) ?>" <?php echo $attributes ?> />
					</p>
					<?php
					break;

				case "textarea" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<textarea id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" <?php echo $attributes ?> ><?php echo $value ?></textarea>
					</p>
					<?php
					break;

				case "textarea_resizable" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<textarea id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" <?php echo $attributes ?> ><?php echo $value ?></textarea>
					</p>
					<?php
					break;

				case "number" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<input type="number" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" value="<?php echo esc_attr( $value ) ?>" <?php echo $attributes ?> />
					</p>
					<?php
					break;

				case "search" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<input type="text" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" value="<?php echo esc_attr( $value ) ?>" <?php echo $attributes ?> />
					</p>
					<?php
					break;

				case "select" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<select id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" <?php echo $attributes ?>>
							<?php foreach ( $setting[ 'options' ] as $option_key => $option_value ) : ?>
								<option value="<?php echo esc_attr( $option_key ) ?>" <?php selected( $option_key, $value ) ?>><?php echo esc_html( $option_value ) ?></option>
							<?php endforeach ?>
						</select>
					</p>
					<?php
					break;

				case "checkbox" :
					?>
					<p>
						<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ) ?>" value="1" <?php checked( $value, 1 ) ?> <?php echo $attributes ?> />
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
					</p>
					<?php
					break;

				case "hidden" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<input type="text" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" value="<?php echo esc_attr( $value ) ?>" <?php echo $attributes ?> />
					</p>
					<?php
					break;

				case "datetime" :
					?>
					<p>
						<label for="<?php echo $this->get_field_id( $key ) ?>"><?php echo $setting[ 'label' ] ?></label>
						<input type="text" id="<?php echo esc_attr( $this->get_field_id( $key ) ) ?>" name="<?php echo $this->get_field_name( $key ) ?>" value="<?php echo esc_attr( $value ) ?>" <?php echo $attributes ?> />
					</p>
					<?php
					break;

			}
		}

		return;
	}
}
