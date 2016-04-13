<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Nucleare
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( has_post_thumbnail() ) { ?>
			<figure class="entry-featured-image">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'nucleare-normal-post' ); ?></a>
			</figure>
	<?php }
	?>
	<header class="entry-header">
		<div class="entry-page-title">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'nucleare' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	
	<footer class="entry-footer">
		<div class="entry-bottom small-part">
			<?php edit_post_link( __( 'Edit', 'nucleare' ), '<span class="edit-link"><i class="fa fa-wrench space-right"></i>', '</span>' ); ?>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
