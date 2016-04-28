<?php
/**
 * The main template file.
 * @package Nucleare Gold
 */
?>

<?php get_header(); ?>

          <div id="main-content">
            <article>

              <?php
                get_template_part('partials/goldhero' );
              ?>

            	<!-- above this line is imported from gold -->




<nav id="access" role="navigation">
  <h3 class="assistive-text">Main menu</h3>
  <div class="skip-link"><a class="assistive-text" href="#content">Skip to primary content</a></div>
  <div class="skip-link"><a class="assistive-text" href="#secondary">Skip to secondary content</a></div>
  <div class="menu-menu-container">
    <ul id="menu-menu" class="menu">
      <li id="menu-item-776" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-776">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=106">News</a>
      </li>
      <li id="menu-item-775" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-775">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=105">Events</a>
      </li>
      <li id="menu-item-781" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-781">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=109">Publications</a>
      </li>
      <li id="menu-item-773" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-773">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=108">Students</a>
      </li>
      <li id="menu-item-774" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-774">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=107">Staff</a>
      </li>
      <li id="menu-item-779" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-779">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=74">Women in Computing</a>
      </li>
      <li id="menu-item-782" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-782">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=70">Careers</a>
      </li>
      <li id="menu-item-783" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-783">
        <a href="http://www.doc.gold.ac.uk/blog/?cat=110">Inspiration</a>
      </li>
    </ul>
  </div>
</nav>


							<div class="site-content">
								<div id="primary" class="content-area">
									<?php if ( have_posts() ) : ?>
										<?php /* Start the Loop */ ?>
										<?php while ( have_posts() ) : the_post(); ?>
											<?php
												get_template_part( 'partials/content' );
											?>
										<?php endwhile; ?>
										<?php nucleare_paging_nav(); ?>
									<?php else : ?>
										<?php get_template_part( 'partials/content', 'none' ); ?>
									<?php endif; ?>
								</div><!-- #primary -->
								<?php get_sidebar(); ?>
							</div><!-- site-content -->

            	<!-- below this line is imported from gold -->

            </article>
          </div><!-- main-content -->

<?php get_footer(); ?>