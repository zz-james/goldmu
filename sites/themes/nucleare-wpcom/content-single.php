<?php
/**
 * @package Nucleare
 */
 
$format = get_post_format();

/* translators: %s: Name of current post */
$content_text = sprintf(
	__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'nucleare' ),
	the_title( '<span class="screen-reader-text">"', '"</span>', false )
);

if ( ( 'video' || 'audio' ) == $format ) {
	$content = apply_filters( 'the_content', get_the_content( $content_text ) );
} else {
	$content = '';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( 'video' == $format ) {
			$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );

			if ( ! empty( $video ) ) {
				foreach( $video as $video_html ) { 
					$content = str_replace( $video_html, '', $content ); ?>
					
					<div class="entry-video jetpack-video-wrapper">
						<?php printf( '%1$s', $video_html ); ?>
					</div><!-- .entry-video.jetpack-video-wrapper -->
			<?php
				} // endforeach
			} // endif !empty ( $media )
		} // endif video == $format
		elseif ( 'audio' == $format ) {
			$audio = get_media_embedded_in_content( $content, array( 'audio' ) );
			
			if ( has_post_thumbnail() ) : ?>
				<figure class="entry-featured-image">
					<?php the_post_thumbnail( 'nucleare-normal-post' ); ?>
				</figure>
		<?php endif;
			if ( ! empty( $audio ) ) {
				foreach( $audio as $audio_html ) { 
					$content = str_replace( $audio_html, '', $content ); ?>
					<div class="entry-audio">
						<?php printf( '%1$s', $audio_html ); ?>
					</div><!-- .entry-audio -->
		<?php
				} // endforeach
			} // endif !empty ( $media )
		} // endif audio == $format
		elseif ( 'gallery' == $format && get_post_gallery() ) { ?>
			<div class="entry-gallery">
				<?php echo get_post_gallery(); ?>
			</div><!-- .entry-gallery -->
			<?php
		} // endif gallery == $format
		elseif ( has_post_thumbnail() ) { ?>
			<figure class="entry-featured-image">
				<?php the_post_thumbnail( 'nucleare-normal-post' ); ?>
			</figure>
	<?php } ?>
	<header class="entry-header">
		<div class="entry-category">
			<?php nucleare_entry_category(); ?>
		</div><!-- .entry-meta -->
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta small-part">
			<?php nucleare_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( ( 'video' || 'audio' ) == $format ) : ?>
			<?php echo $content; ?>
		<?php else : ?>
			<?php the_content(); ?>
		<?php endif; ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links small-part">' . __( 'Pages:', 'nucleare' ) . '<span>',
				'after'  => '</span></div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-bottom small-part">
			<?php nucleare_entry_footer(); ?>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
