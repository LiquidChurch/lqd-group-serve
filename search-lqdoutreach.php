<?php
/**
 * The template for displaying search results pages
 *
 * @package WordPress
 * @subpackage Liquid_Church
 * @since Liquid Church 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <div><p> &nbsp; </p><p> &nbsp; </p>
			<?php  echo do_shortcode( '[rev_slider alias="loveweekend"]' ); ?>
			<?php echo do_shortcode( '[searchandfilter slug="group-server-search"]' ); ?><p> &nbsp; </p>
            </div>
		<?php if ( have_posts() ) : ?>
			<!-- .entry-content -->
				<div class="entry-content">

			<header class="page-header">
                <p> &nbsp; </p>
                <!-- remove header ....
				<h1 class="page-title"><?php printf( __( 'Search Results', 'liquidchurch' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
            </header><!-- .page-header -->

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();


				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				//get_template_part( plugins_url( 'lqd-group-serve' . 'template-parts/content-lqdoutreach', 'search' ));
				get_template_part( 'template-parts/content-lqdoutreach', 'search' );
				//get_template_part( 'template-parts/content-lqdoutreach');


				// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'liquidchurch' ),
				'next_text'          => __( 'Next page', 'liquidchurch' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'liquidchurch' ) . ' </span>',
			) );
?>
				</div><!-- .entry-content -->
				<!-- .entry-content -->
				<div class="entry-content">
<?php
		// If no content, include the "No posts found" template.
		else :
			get_template_part( plugins_url( 'lqd-group-serve' . 'template-parts/content-none-lqdoutreach' ));

		endif;
		?>
			</div><!-- .entry-content -->
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
