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
				foreach( $video as $video_html ) { ?>
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
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'nucleare-normal-post' ); ?>
						<figcaption><p><i class="fa fa-music"></i></p></figcaption>
					</a>
				</figure>
		<?php endif;
			if ( ! empty( $audio ) ) {
				foreach( $audio as $audio_html ) { ?>
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
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'nucleare-normal-post' ); ?>
					<figcaption><p><i class="fa fa-file-text"></i></p></figcaption>
				</a>
			</figure>
	<?php } ?>
	<header class="entry-header">
		<?php
			if ( 'link' == $format ) {
				the_title( '<h1 class="entry-title"><a href="' . esc_url( nucleare_get_link_url() ) . '">', '<i class="fa fa-external-link"></i></a></h1>' );
			} else {
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			} ?>
		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta small-part">
				<?php nucleare_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( ( 'video' || 'audio' || 'image' ) == $format ) : ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php else : ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-footer">
		<div class="entry-bottom small-part">
			<?php edit_post_link( __( 'Edit', 'nucleare' ), '<span class="edit-link"><i class="fa fa-wrench space-right"></i>', '</span>' ); ?>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->