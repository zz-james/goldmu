<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Nucleare Gold
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

  <!-- imported from gold -->
  <div class="site-wrapper">
    <?php get_template_part( 'partials/goldheader' ); ?>

    <main id="content" role="main">
      <div class="pusher-wrapper">
        <?php get_template_part( 'partials/goldnav' ); // the sidebar push nav lives here ?>

        <div class="main-container push-this">
          <?php get_template_part( 'partials/goldbreadcrumb' ); ?>