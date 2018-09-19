<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Liquid_Church
 * @since Liquid Church 1.0
 */

get_header(); ?>
<div> <p> &nbsp; </p><p> &nbsp; </p><?php  echo do_shortcode( '[rev_slider alias="loveweekend"]' ); ?>
	<?php //echo do_shortcode( '[searchandfilter slug="group-server-search"]' ); ?>
</div>

<div id="primary" class="content-area" style="margin-left:20px; margin-right:20px;">
    <main id="main" class="site-main" role="main">

        <?php

		// Start the loop.
		while ( have_posts() ) : the_post();
		{

			// Include the single post content template.
			get_template_part( 'template-parts/content-lqd-group-serve', 'single' );
			//get_template_part( 'template-parts/content-lqd-group-serve');


			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			//if ( is_singular( 'lqd-group-serve' ) ) {
			    if ( is_singular( 'attachment' ) ) {
					// Parent post navigation.
					the_post_navigation( array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'liquidchurch' ),
					) );
				} elseif ( is_singular( 'post' ) ) {
					//} elseif ( is_singular( 'lqd-group-serve' ) ) {
					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'liquidchurch' ) . '</span> ' .
						               '<span class="screen-reader-text">' . __( 'Next post:', 'liquidchurch' ) . '</span> ' .
						               '<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'liquidchurch' ) . '</span> ' .
						               '<span class="screen-reader-text">' . __( 'Previous post:', 'liquidchurch' ) . '</span> ' .
						               '<span class="post-title">%title</span>',
					) );

				}
			}

			// End of the loop.
		endwhile;
		?>

    </main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
