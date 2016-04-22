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
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

<div id="page">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'nucleare' ); ?></a>


<!-- imported from gold -->


  <div class="site-wrapper">

    <header class="header header-container" role="banner">
      <div class="wide-wrapper clearfix">
      <a href="http://gold.ac.uk/" class="header__link"><h1 class="site-logo header__logo">Goldsmiths - University of London</h1></a>
        <ul class="header__charms">
          <li><a class="header__charm--text" href="#">Staff & students</a></li>
          <li><a class="header__charm charm-search" href="http://www.gold.ac.uk/search-results/">Search</a></li>
          <li><a class="header__charm charm-menu" href="#">Main menu</a></li>
        </ul>
      </div>
    </header>



    <main id="content" role="main">
      <div class="pusher-wrapper">

        <section>
          <h2 class="visuallyhidden">Navigation</h2>

          <nav class="primary-nav pusher" role="navigation">
            <h2 class="visuallyhidden">Primary navigation</h2>
            <ul id="main-menu">
              <li><span class="currentbranch0">Home</span></li>
              <!-- navigation object : Main menu -->
              <li id="loading">Loading</li>

            </ul>
          </nav>

        </section>

        <nav class="staff-students-nav pusher" role="navigation">
          <h2 class="visuallyhidden">Staff + students navigation</h2>
          <ul id="staff-menu">
            <!-- navigation object : Main Nav number 2 -->
              <li id="loading">Loading</li>
          </ul>
        </nav>

        <div class="main-container push-this">


          <div class="breadcrumb">
            <div class="wide-wrapper clearfix">
              <div class="breadcrumb-wrapper"> <!-- new -->

                <div class="dropdown-nav">In this section
                  <div class="touchButton"><span class="touchButton--plus"></span><span class="touchButton--minus"></span></div>
                </div>

                <nav class="secondary-nav" role="navigation">
                  <h2 class="visuallyhidden">Breadcrumb navigation</h2>
                  <ul>
                    <li class="expanded">
                      <a href="/departments/">Departments</a>
                      <div class="touchButton"><span class="touchButton--plus"></span><span class="touchButton--minus"></span></div>
                    </li>
                    <li class="expanded">
                      <a href="/computing/">Psychology</a>
                      <div class="touchButton"><span class="touchButton--plus"></span><span class="touchButton--minus"></span></div>
                    </li>
                    <li class="expanded">
                      <a href="/computing/research/">Forensic Psychology Blog</a>
                      <div class="touchButton"><span class="touchButton--plus"></span><span class="touchButton--minus"></span></div>
                    </li>
                  </ul>
                </nav>

                <a class="social-expand">Open social sharing</a><!-- new and some part removed -->
                <div class="social-hubs-container">
                  <ul class="social-hubs social-hubs--breadcrumb">
                    <li><a href="https://twitter.com/intent/tweet?text=Computing+research&amp;url=http%3A%2F%2Fwww.gold.ac.uk%2Fcomputing%2Fresearch%2F&amp;via=goldsmithsuol">Twitter</a></li>
                    <!--<li><a href="https://linkedin.com/">Linkedin</a></li>-->
                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=http://www.gold.ac.uk/computing/research/" onclick="return fbs_click(450, 350)" target="_blank" title="Share on Facebook">Facebook</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>



          <div id="maincontent">

<article>
<header>
  <div class="hero-container">
    <div class="hero hero--simple">
        <div class="hero__wrapper wrapper">
            <div class="hero__content">
                <h1 class="hero__heading"><?php bloginfo( 'name' ); ?></h1>
            </div>
        </div>
    </div> <!-- #hero -->
  </div> <!-- #hero-container -->
</header>

<!-- above this line is imported from gold -->






	<!-- <header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php if ( get_header_image() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="" class="custom-header">
				</a>
			<?php endif; // End header image check. ?>
<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<!-- <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>  -->
		<!-- </div> --><!-- .site-branding -->
	<!-- </header> --><!-- #masthead -->

	<div id="content" class="site-content">
