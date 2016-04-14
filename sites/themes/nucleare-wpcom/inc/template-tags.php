<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Nucleare Gold
 */

if ( ! function_exists( 'nucleare_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function nucleare_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'nucleare' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( '<div class="meta-nav"><i class="fa fa-lg fa-angle-left space-right"></i><span class="small-part">'. __( 'Older Posts', 'nucleare' ) .'</span></div>' ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( '<div class="meta-nav"><span class="small-part">'. __( 'Newer Posts', 'nucleare' ) .'</span><i class="fa fa-lg fa-angle-right space-left"></i></div>' ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'nucleare_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function nucleare_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'nucleare' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<div class="the-navigation-arrow"><i class="fa prev-next fa-2x fa-angle-left"></i></div> <div class="meta-nav"><span class="small-part">'. __( 'Previous Post', 'nucleare' ) .'</span><div class="next-prev-name">%title</div></div>', 'Previous post link', 'nucleare' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '<div class="meta-nav"><span class="small-part">'. __( 'Next Post', 'nucleare' ) .'</span><div class="next-prev-name">%title</div></div> <div class="the-navigation-arrow"><i class="fa prev-next fa-2x fa-angle-right"></i></div>', 'Next post link',     'nucleare' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'nucleare_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function nucleare_posted_on() {

	$format = get_post_format();
	$formats = get_theme_support( 'post-formats' );

	if ( $format && in_array( $format, $formats[0] ) ) {
		$format_string = get_post_format_string( $format );
		echo '<span><a class="entry-format entry-format-' . esc_attr( $format ) . '" href="' . esc_url( get_post_format_link( $format ) ) . '" title="' . esc_attr( sprintf( __( 'All %s posts', 'nucleare' ), $format_string ) ) . '">' . $format_string . '</a></span>';
	}
	
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( '<i class="fa fa-clock-o space-left-right"></i>%s', 'post date', 'nucleare' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( '<i class="fa fa-user space-left-right"></i>%s', 'post author', 'nucleare' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
	
	if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) {
		echo '<span class="comments-link"><i class="fa fa-comments-o space-left-right"></i>';
		comments_popup_link( __( 'Leave a comment', 'nucleare' ), __( '1 Comment', 'nucleare' ), __( '% Comments', 'nucleare' ) );
		echo '</span>';
	}

}
endif;

if ( ! function_exists( 'nucleare_entry_category' ) ) :
function nucleare_entry_category() {
	if ( 'post' == get_post_type() ) {
		$categories_list = get_the_category_list( __( ' / ', 'nucleare' ) );
		if ( $categories_list && nucleare_categorized_blog() ) {
			printf( '<span class="cat-links">%1$s</span>', $categories_list );
		}
	}
}
endif;

if ( ! function_exists( 'nucleare_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function nucleare_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'nucleare' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( '<i class="fa fa-tags space-right"></i>%1$s', 'nucleare' ) . '</span>', $tags_list );
		}
	}

	edit_post_link( __( 'Edit', 'nucleare' ), '<span class="edit-link"><i class="fa fa-wrench space-right"></i>', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'nucleare' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'nucleare' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'nucleare' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'nucleare' ), get_the_date( _x( 'Y', 'yearly archives date format', 'nucleare' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'nucleare' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'nucleare' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'nucleare' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'nucleare' ) ) );
	} elseif ( is_tax( 'post_format', 'post-format-aside' ) ) {
		$title = _x( 'Asides', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
		$title = _x( 'Galleries', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
		$title = _x( 'Images', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
		$title = _x( 'Videos', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
		$title = _x( 'Quotes', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
		$title = _x( 'Links', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
		$title = _x( 'Statuses', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
		$title = _x( 'Audio', 'post format archive title', 'nucleare' );
	} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
		$title = _x( 'Chats', 'post format archive title', 'nucleare' );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'nucleare' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'nucleare' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'nucleare' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function nucleare_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'nucleare_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'nucleare_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so nucleare_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so nucleare_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in nucleare_categorized_blog.
 */
function nucleare_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'nucleare_categories' );
}
add_action( 'edit_category', 'nucleare_category_transient_flusher' );
add_action( 'save_post',     'nucleare_category_transient_flusher' );