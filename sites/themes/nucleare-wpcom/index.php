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