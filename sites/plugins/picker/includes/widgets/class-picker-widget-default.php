<?php
/**
 * Default Picker Widget class
 *
 * The Default Picker Widget class manage all defaults picker widget creating settings, updating and displaying data
 *
 * @author      Andrea Landonio
 * @class       Picker_Widget_Default
 * @category    Widgets
 * @package     Picker/Includes/Widgets
 * @extends     Abstract_Picker_Widget
 */

class Picker_Widget_Default extends Abstract_Picker_Widget {

    /**
     * __construct function create widget info and names, then prepare all widget settings
     */
    public function __construct() {
        $this->widget_css_class = 'picker_widget';
        $this->widget_description = __( 'Displays a single post, selected from a list or provided by ID, on site frontend.', PKR_DOMAIN );
        $this->widget_id = 'picker';
        $this->widget_name = __( 'Picker', PKR_DOMAIN );
        $this->widget_width = 500;

        // Get post list items value
        $pkr_post_list_items = picker_get_option_value( 'pkr_post_list_items' , PKR_POST_LIST_ITEMS );

        // Query posts to fill in post list field
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $pkr_post_list_items,
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $list_posts = get_posts($args);

        // Create posts array and set first array items as "none" option
        $posts = array();
        $posts[ 0 ] = __( '-', PKR_DOMAIN );

        // Loop posts
        foreach ( $list_posts as $list_post ):
            $status_post = $list_post->post_status;
            $posts[ $list_post->ID ] = picker_trunc_words( $list_post->post_title, 15 ) . ( ( $status_post != "publish" ) ? " (" . $status_post . ")" : "" );
        endforeach;

        // Set widget settings
        $this->settings = array(
            'published' => array(
                'type' => 'checkbox',
                'label' => __( 'Select to publish the widget:', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'published'
                )
            ),
            'title' => array(
                'type' => 'text',
                'std' => __( 'Temporary title', PKR_DOMAIN ),
                'label' => __( 'Widget title (not shown on frontend):', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'widefat'
                )
            ),
            'article' => array(
                'type' => 'select',
                'options' => $posts,
                'std' => NULL,
                'label' => __( 'Choose a post to display in the widget (order by latest posts):', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'widefat articles'
                )
            ),
            'id' => array(
                'type' => 'search',
                'label' => __( 'Force a post by ID or search it by keywords:', PKR_DOMAIN ),
                'attributes' => array(
                    'size' => 16,
                    'maxlength' => 10,
                    'class' => 'widefat id_search'
                )
            ),
            'time_to_publish' => array(
                'type' => 'datetime',
                'label' => __( 'Post visible from (format "mm/dd/yyyy HH:mi"):', PKR_DOMAIN ),
                'attributes' => array(
                    'size' => 15,
                    'maxlength' => 16,
                    'class' => 'time_to_publish'
                )
            ),
            'time_to_expire' => array(
                'type' => 'datetime',
                'label' => __( 'Post visible to (format "mm/dd/yyyy HH:mi"):', PKR_DOMAIN ),
                'attributes' => array(
                    'size' => 15,
                    'maxlength' => 16,
                    'class' => 'time_to_expire'
                )
            ),
            'custom_url' => array(
                'type' => 'text',
                'label' => __( 'Alternative post URL:', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'widefat url'
                )
            ),
            'custom_title' => array(
                'type' => 'text',
                'label' => __( 'Alternative post title:', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'widefat'
                )
            ),
            'custom_excerpt' => array(
                'type' => 'textarea_resizable',
                'label' => __( 'Alternative post excerpt:', PKR_DOMAIN ),
                'attributes' => array(
                    'class' => 'widefat textarea_resizable',
                    'style' => 'height:45px;'
                )
            )
        );

        // Call parent constructor
        parent::__construct();
    }

    /**
     * Display the widget on the screen
     *
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get cache enabled value
        $pkr_cache_enabled = picker_get_option_value( 'pkr_cache_enabled' , PKR_CACHE_ENABLED );

        // Get picker item object from cache
        if ( $pkr_cache_enabled && false !== ( $cache = $this->get_cached_widget() ) ) {
            // Widget found in cache, set picker post object from cache transient data
            global $picker_item;
            $picker_item = $cache;

            // Open output buffer
            ob_start();
            extract( $args );

            // Widget starts
            echo ( isset( $before_widget ) ) ? $before_widget : "";

            // Call layout template
            picker_get_template( 'picker-widget-default.php', array( 'use_cache' => true ) );

            // Widget ends
            echo ( isset( $after_widget ) ) ? $after_widget : "";

            // Read, flush and close output buffer
            $content = ob_get_clean();
            ob_end_flush();

            // Display widget content
            echo $content;

            // No other code required, exit
            return;
        }

        // Open output buffer
        ob_start();
        extract( $args );

        // Read instance settings values
        $article = ( ! empty( $instance[ 'id' ] ) ) ? $instance[ 'id' ] : $instance[ 'article' ];
        if ( empty( $article ) ) return; // If there is no selected article, no widget displayed
        $published = $instance[ 'published' ];

        if ( isset( $published ) && ! empty( $published ) && $published == 1 ) {

            // This is the actual function of the plugin, it fills the widget with the customized post
            $widget_posts = new WP_Query( array( 'p' => $article ) );
            $widget_posts_counter = 0;

            // Read instance time values
            $time_to_publish = $instance[ 'time_to_publish' ];
            $time_to_expire = $instance[ 'time_to_expire' ];

            while ( $widget_posts->have_posts() && $widget_posts_counter < 1 ) {

                // Get current post
                $widget_posts->the_post();
                $post = get_post();

                // Retrieve post time (use GMT time) and format dates
                $current_unix_time = current_time( 'timestamp', 1 );
                if ( ! empty( $time_to_publish ) ) {
                    if ( get_locale() == PKR_LOCALE_IT ) {
                        // Italian date format "dd/mm/yy"
                        $time_to_publish = substr( $time_to_publish, 6, 4 ) . "-" . substr( $time_to_publish, 3, 2 ) . "-" . substr( $time_to_publish, 0, 2 ) . " " . substr( $time_to_publish, 11, 2 ) . ":" . substr( $time_to_publish, 14, 2 ) . ":00";
                    }
                    else {
                        // Default date format "mm/dd/yy"
                        $time_to_publish = substr( $time_to_publish, 6, 4 ) . "-" . substr( $time_to_publish, 0, 2 ) . "-" . substr( $time_to_publish, 3, 2 ) . " " . substr( $time_to_publish, 11, 2 ) . ":" . substr( $time_to_publish, 14, 2 ) . ":00";
                    }
                }
                if ( ! empty( $time_to_expire ) ) {
                    if ( get_locale() == PKR_LOCALE_IT ) {
                        // Italian date format "dd/mm/yy"
                        $time_to_expire = substr( $time_to_expire, 6, 4 ) . "-" . substr( $time_to_expire, 3, 2 ) . "-" . substr( $time_to_expire, 0, 2 ) . " " . substr( $time_to_expire, 11, 2 ) . ":" . substr( $time_to_expire, 14, 2 ) . ":00";
                    }
                    else {
                        // Default date format "mm/dd/yy"
                        $time_to_expire = substr( $time_to_expire, 6, 4 ) . "-" . substr( $time_to_expire, 0, 2 ) . "-" . substr( $time_to_expire, 3, 2 ) . " " . substr( $time_to_expire, 11, 2 ) . ":" . substr( $time_to_expire, 14, 2 ) . ":00";
                    }
                }

                // Convert widget schedule times to unix timestamp
                $time_to_publish_unix = date( 'U', strtotime( $time_to_publish ) );
                $time_to_expire_unix = date( 'U', strtotime( $time_to_expire ) );

                // Save widget schedule time to object
                $this->time_to_publish = $time_to_publish_unix;
                $this->time_to_expire = $time_to_expire_unix;

                // Check if post is visible
                if ( $post->post_status == "publish" && ( empty( $time_to_publish ) || $current_unix_time > $time_to_publish_unix ) && ( empty( $time_to_expire ) || $current_unix_time < $time_to_expire_unix ) ) {

                    // Widget starts
                    echo ( isset( $before_widget ) ) ? $before_widget : "";

                    // Set widget sidebar and order
                    $this->get_widget_info( $this->id_base . '-' . $this->number );

                    // Read custom picker item values
                    $custom_fields_args = array(
                        'custom_url' => ( ! empty( $instance[ 'custom_url' ] ) ) ? esc_url( $instance[ 'custom_url' ] ) : '',
                        'custom_title' => ( ! empty( $instance[ 'custom_title' ] ) ) ? sanitize_text_field( $instance[ 'custom_title' ] ) : '',
                        'custom_excerpt' => ( ! empty( $instance[ 'custom_excerpt' ] ) ) ? $instance[ 'custom_excerpt' ] : '',
                        'widget_sidebar' => $this->widget_sidebar,
                        'widget_order' => $this->widget_order
                    );

                    // Set picker post object
                    global $picker_item;
                    $picker_item = get_picker_item( $post->ID, $custom_fields_args );

                    // Call layout template
                    picker_get_template( 'picker-widget-default.php', array( 'use_cache' => false ) );

                    // Widget ends
                    echo ( isset( $after_widget ) ) ? $after_widget : "";

                    // Save picker item object to cache
                    if ( $pkr_cache_enabled ) $this->cache_widget( $picker_item );

                    $widget_posts_counter = $widget_posts_counter + 1;
                }
            }

            // Reset post data
            wp_reset_postdata();
        }

        // Read, flush and close output buffer
        $content = ob_get_clean();
        ob_end_flush();

        // Display widget content
        echo $content;
    }
}
