<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Nucleare
 */

$hidesearch = get_theme_mod( 'nucleare_hide_search', false );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'nucleare' ); ?></a>

	<div class="navigation-bar clear">
		<div class="navigation-block" style="display:none">

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="menu" aria-expanded="false"><i class="fa fa-bars"></i><span class="screen-reader-text"><?php _e( 'Open Menu', 'nucleare' ); ?></span></button>
				<?php // wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->

			<?php if ( ! $hidesearch || has_nav_menu( 'social' ) ) : ?>
				<div class="social-links">
					<?php wp_nav_menu( array( 'theme_location' => 'social', 'link_before' => '<span class="screen-reader-text">', 'link_after' => '</span>', 'fallback_cb' => false ) ); ?>

					<?php if ( ! $hidesearch ) : ?>
						<div class="open-search top-search"><i class="fa fa-search"><span class="screen-reader-text"><?php _e( 'Search', 'nucleare' ); ?></span></i></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! $hidesearch ) : ?>
		<div class="search-full">
			<div class="search-container">
				<?php get_search_form(); ?>
				<span><a class="close-search"><i class="fa fa-close space-right"></i><?php _e( 'Close', 'nucleare' ); ?></a></span>
			</div>
		</div>
	<?php endif; ?>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php if ( get_header_image() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="" class="custom-header">
				</a>
			<?php endif; // End header image check. ?>
			<?php if ( function_exists( 'jetpack_the_site_logo' ) && has_site_logo() ) : ?>
				<?php jetpack_the_site_logo(); ?>
			<?php endif; ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
