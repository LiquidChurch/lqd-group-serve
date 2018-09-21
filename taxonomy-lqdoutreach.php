<?php
/**
 * Template Name: Group Serve Archives
 *
 * The template for displaying group serve archive pages
 * archive-lqdoutreach.php
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Liquid Groups Serve
 * @since Liquid Groups Serve 0.5
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php  echo do_shortcode( '[rev_slider alias="loveweekend"]' ); ?>
		<?php echo do_shortcode( '[searchandfilter slug="group-server-search"]' ); ?><p> &nbsp; </p>
<?php ?>
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_description( '<h1 class="page-title">', '</h1>' );
				?>
			</header><!-- .page-header -->
			<?php
			// Start the Loop.
			while ( have_posts () ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				//get_template_part( plugins_url( 'lqd-group-serve' . 'template-parts/content-lqdoutreach', get_post_format()));
				//get_template_part( dirname( __FILE__ ) . '/template-parts/content-lqdoutreach' );
				get_template_part( 'template-parts/content-lqdoutreach', get_post_format() );
				// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'liquidchurch' ),
				'next_text'          => __( 'Next page', 'liquidchurch' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'liquidchurch' ) . ' </span>',
			) );
			?>

        <?php

		// If no content, include the "No posts found" template.
		else :

			//get_template_part( 'template-parts/content', 'none');
			get_template_part(plugins_url( 'lqd-group-serve' . 'template-parts/content-none-lqdoutreach' ));

			//get_template_part( 'template-parts/content-lqdoutreach' );

		endif;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
