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
                <?php
                if (has_nav_menu('blog-menu')) :
                  wp_nav_menu(['theme_location' => 'blog-menu', 'menu_class' => 'menu', 'container_class' => 'menu-menu-container']);
                endif;
                ?>
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
                    <?php comments_template( '', true ); ?>
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