<?php
/**
 * nucleare functions and definitions
 *
 * @package Nucleare
 */

if ( ! function_exists( 'nucleare_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nucleare_setup() {

	global $content_width;

	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	if ( ! isset( $content_width ) ) {
		$content_width = 809; /* pixels */
	}

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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		// 'primary' => __( 'Primary Menu', 'nucleare' ),
		'social'  => __( 'Social Links', 'nucleare' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		 'aside', 'image', 'video', 'quote', 'link', 'audio', 'gallery'
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'nucleare_custom_background_args', array(
		'default-color' => 'ffffff',
	) ) );
}
endif; // nucleare_setup
add_action( 'after_setup_theme', 'nucleare_setup' );




if ( ! function_exists( 'nucleare_content_width' ) ) :

function nucleare_content_width() {
	global $content_width;

	if ( is_page_template( 'full-width-page.php' ) ) {
		$content_width = 996; /* pixels */
	}
}
endif; // nucleare_setup

add_action( 'template_redirect', 'nucleare_content_width' );

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

	register_sidebar( array(
		'name'          => __( 'Footer Widgets 1', 'nucleare' ),
		'id'            => 'footer-sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widgets 2', 'nucleare' ),
		'id'            => 'footer-sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widgets 3', 'nucleare' ),
		'id'            => 'footer-sidebar-3',
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
	wp_enqueue_style( 'nucleare-style', get_stylesheet_uri() );

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons/genericons.css', array(), '3.3' );
	wp_enqueue_style( 'nucleare-fonts', nucleare_fonts_url(), array(), null );
	wp_enqueue_style( 'nucleare-font-awesome', get_template_directory_uri() .'/css/font-awesome.css' );

	wp_enqueue_script( 'nucleare-custom', get_template_directory_uri() . '/js/nucleare.js', array( 'jquery' ), '20150409', true );
	wp_enqueue_script( 'nucleare-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'nucleare-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nucleare_scripts' );

/**
 * Register Google Fonts
 */
function nucleare_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
	 * supported by Roboto, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$roboto = _x( 'on', 'Roboto font: on or off', 'nucleare' );

	/* Translators: If there are characters in your language that are not
	 * supported by Playfair Display, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$playfair = _x( 'on', 'Playfair Display font: on or off', 'nucleare' );

	if ( 'off' !== $roboto && 'off' !== $playfair ) {
		$font_families = array();

		if ( 'off' !== $roboto ) {
			$font_families[] = 'Roboto:400,700,700italic,400italic';
		}

		if ( 'off' !== $playfair ) {
			$font_families[] = 'Playfair Display:400,400italic,700italic,700';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;

}

/**
 * Enqueue Google Fonts for Editor Styles
 */
function nucleare_editor_styles() {
    add_editor_style( array( 'editor-style.css', nucleare_fonts_url() ) );
}
add_action( 'after_setup_theme', 'nucleare_editor_styles' );

/**
 * Enqueue Google Fonts for custom headers
 */
function nucleare_admin_scripts( $hook_suffix ) {

	wp_enqueue_style( 'nucleare-fonts', nucleare_fonts_url(), array(), null );

}
add_action( 'admin_print_styles-appearance_page_custom-header', 'nucleare_admin_scripts' );


function nucleare_modify_read_more_link() {
	return '<a class="more-link" href="' . esc_url( get_permalink() ) . '">' . __( 'Read More &raquo;', 'nucleare' ) . '</a>';
}
add_filter( 'the_content_more_link', 'nucleare_modify_read_more_link' );

/**
 * Returns the URL from the post.
 *
 * @uses get_the_link() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @return string URL
 */
function nucleare_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
* Remove the 1st gallery shortcode from gallery post format content.
*/
function nucleare_strip_first_gallery( $content ) {
	if ( 'gallery' == get_post_format() && 'post' == get_post_type() ) {
		$regex = '/\[gallery.*]/';
		$content = preg_replace( $regex, '', $content, 1 );
	}

	return $content;
}
add_filter( 'the_content', 'nucleare_strip_first_gallery' );

/**
 * Custom Header support.
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


// updater for WordPress.com themes
if ( is_admin() )
	include dirname( __FILE__ ) . '/inc/updater.php';
