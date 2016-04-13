<?php
/**
 * The sidebar containing the footer widget areas.
 *
 * @package Nucleare
 */

if ( ! is_active_sidebar( 'footer-sidebar-1' ) && ! is_active_sidebar( 'footer-sidebar-2' ) && ! is_active_sidebar( 'footer-sidebar-3' ) ) {
	return;
}
?>

<div id="tertiary" class="widget-areas clear" role="complementary">
	<?php if ( is_active_sidebar( 'footer-sidebar-1' ) ) : ?>
		<div class="widget-area" id="footer-sidebar-1">
			<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
		</div>
	<?php endif; ?>
	
	<?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
		<div class="widget-area" id="footer-sidebar-2">
			<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
		</div>
	<?php endif; ?>
	
	<?php if ( is_active_sidebar( 'footer-sidebar-3' ) ) : ?>
		<div class="widget-area" id="footer-sidebar-3">
			<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
		</div>
	<?php endif; ?>
</div><!-- #secondary -->
