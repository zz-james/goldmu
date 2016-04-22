<?php
/**
 * @package Nucleare Gold
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php
		  if(is_single()) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		  } else {
			the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
		  }
		?>

		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta small-part">
				<?php nucleare_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->


		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->


	<footer class="entry-footer">
		<div class="entry-bottom small-part">
			<?php edit_post_link( __( 'Edit', 'nucleare' ), '<span class="edit-link"><i class="fa fa-wrench space-right"></i>', '</span>' ); ?>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->