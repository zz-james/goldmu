<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Nucleare
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
	
		<?php get_sidebar( 'footer' ); ?>
		
		<div class="site-info small-part">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'nucleare' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'nucleare' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'nucleare' ), 'Nucleare', '<a target="_blank" href="http://crestaproject.com/" rel="designer">CrestaProject</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<a href="#top" class="to-top"><i class="fa fa-angle-up fa-lg"><span class="screen-reader-text"><?php _e( 'Back to top', 'nucleare' ); ?></span></i></a>
<?php wp_footer(); ?>

</body>
</html>
