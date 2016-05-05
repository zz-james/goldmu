<?php
/**
 * nucleare gold functions and definitions
 *
 * @package Nucleare Gold
 */

/**
 * returns true if on a login or register page
 * @return boolean 
 */
function is_login_or_register() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

// redirect for the home page
add_action( 'init', 'wpse182623_redirect_home_page_only' );
function wpse182623_redirect_home_page_only() {
    if( get_current_blog_id() == 1 && !is_admin() && !is_login_or_register() ) {
        wp_redirect( 'http://gold.ac.uk' );
        exit;
    }
}


if ( ! function_exists( 'nucleare_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nucleare_setup() {

	/*
	 * make it so an image can be chosen as the hero for the theme
	 * and the hero text colour can be changed
	 */
	$args = array(
		'width'         => 980,
		'height'        => 200,
		'uploads'       => true,
		'default-text-color' => 'fff',
		'header-text'        => true,
	);
	add_theme_support( 'custom-header' , $args );


 	register_nav_menu('blog-menu',__( 'Blog Menu' ));


	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on nucleare, use a find and replace
	 * to change 'nucleare' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'nucleare', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'nucleare-normal-post' , 810, 9999 );

}
endif; // nucleare_setup
add_action( 'after_setup_theme', 'nucleare_setup' );


/**
 * add some custom things to the customizer
 * @param [type] $wp_customize : object from the customiser
 */
function theme_customize_register( $wp_customize ) {
  //All our sections, settings, and controls will be added here

	$wp_customize->add_section("pi", array(
		"title"    => __("Posting Info", "customizer_postinfo_sections"),
		"priority" => 30,
	));

	$wp_customize->add_setting("show_date", array(
		"default"   => "show",
		"transport" => "refresh",
	));

	$wp_customize->add_setting("show_author", array(
		"default"   => "show",
		"transport" => "refresh",
	));


	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"show_date",
		array(
			"label"    => __("Show the post date", "customizer_postinfo_label"),
			"section"  => "pi",
			"settings" => "show_date",
			"type"     => "radio",
			"choices"  => array(
				"show" => __("Show Post Dates"),
				"hide" => __("Hide Post Dates")
			)
		)
	));

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"show_author",
		array(
			"label"    => __("Show the author", "customizer_postauthor_label"),
			"section"  => "pi",
			"settings" => "show_author",
			"type"     => "radio",
			"choices"  => array(
				"show" => __("Show Post Author"),
				"hide" => __("Hide Post Author")
			)
		)
	));


	$wp_customize->add_section("bc", array(
		"title"    => __("Breadcrumb Link", "customizer_breadcrumblink_sections"),
		"priority" => 500,
	));

	$wp_customize->add_setting("breadcrumb_link", array(
		"default"   => "",
		"transport" => "refresh",
	));

	$wp_customize->add_setting("breadcrumb_href", array(
		"default"   => "",
		"transport" => "refresh",
	));

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"breadcrumb_link",
		array(
			"label"    => __("Breadcrumb Link Text", "customizer_breadcrumblink_label"),
			"section"  => "bc",
			"settings" => "breadcrumb_link",
			"type" => "text",
		)
	));

	$wp_customize->add_control(new WP_Customize_Control(
		$wp_customize,
		"breadcrumb_href",
		array(
			"label"    => __("Breadcrumb Link Address (start with http)", "customizer_breadcrumbhref_label"),
			"section"  => "bc",
			"settings" => "breadcrumb_href",
			"type" => "text",
		)
	));


}
add_action( 'customize_register', 'theme_customize_register' );





/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function nucleare_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'nucleare' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'nucleare_widgets_init' );



/**
 * Enqueue scripts and styles.
 */
function nucleare_scripts() {
	wp_enqueue_style( "gold", "http://www.gold.ac.uk/_assets/css/style-min.css" );
	wp_enqueue_style( 'nucleare-style', get_stylesheet_uri() );
    wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.3.min.js', array(), null, true );
	wp_enqueue_script( 'nucleare-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', array('jquery'), '1', true );
	wp_enqueue_script( 'cookies', get_template_directory_uri() . '/js/jquery.cookiebar.min.js', array('jquery'), '1', true );
}
add_action( 'wp_enqueue_scripts', 'nucleare_scripts' );

function nucleare_modify_read_more_link() {
	return '<a class="more-link" href="' . esc_url( get_permalink() ) . '">' . __( 'Read More &raquo;', 'nucleare' ) . '</a>';
}
add_filter( 'the_content_more_link', 'nucleare_modify_read_more_link' );




/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';


