<?php
/**
 * The template part for displaying individual group serve
 * event listings on an archive page.
 * content-single-lqd-group-serve.php
 * @package WordPress
 * @subpackage Liquid Group Serve
 * @since Liquid Group Serve 0.5
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'liquidchurch' ); ?></span>
		<?php endif; ?>


		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
            /*
			/* Return individual fields for display
			 * And format as desired for display
             *
             */
            /*while (have_posts() ) : the_post();
                /*$FFRating = get_the_term_list( $id, 'FFRating', '', ' : ' );
                ?>
            <li>FFRating: <?php echo $FFRating; ?></li>
            <li><?php get_the_term_list($id, 'FFRating', '', ' : ') ?></li>
            <?php
		/*$query .= the_post();
		while( $query->have_posts() ) {
		    $query .= the_post();*/
            $string = '';
            $terms = '';
		while (have_posts() ) : the_post();{
			/* comment out this section to remove sign up button */$string = '';
			//$terms = '';
			//$terms = '<p style= "min-height:50px; max-width: 65%;"><a class="blue_btn" style="width: 35%;float: left;" href=';
			//$string .= $terms;
			//$terms = get_field('sign_up_to_serve');
			//$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a>  ';
			//$terms = '';

			/* comment out this section to remove URL button */
			//$terms = '   <a class="blue_btn" style="width: 30%;float: right; " href="';
			//$string .= $terms;
			//$terms = get_field('host_url');
			//$string .=  $terms . '" target="_blank"> Host URL</a>';
       // $terms = get_the_term_list($id, 'Host-URL','  : Host site link : ', '', ' : ');
		//$string .=$terms;
		$terms = get_the_term_list($id, 'project-type', ' Compassion Focus: ', ' : ', ' ');
		$string .=$terms;
		$terms = get_the_content() . '';
		$string .=$terms;
		$terms = get_the_term_list($id, 'DOW', '   Day: ', ' : ', ' : ');
		$string .=$terms;
		$terms = get_the_term_list($id, 'project-location', 'Location: ', ' : ', ' : ');
		$string .=$terms;
			//$terms = get_the_term_list($id, 'FFRating', 'Family Friendly: ', ' : ', ' : ');
			$terms = get_field('family_friendly_rating');
			$string .= 'Family Friendly (min. age): ' . $terms . ' : ';
			//$terms = get_the_term_list($id, 'team-size', 'Team size: ', ' : ', '  ');
			$terms = get_field('number_of_participants');
		$string .=$terms . '<br/>';
		$terms = get_the_term_list($id, 'Host-Org', '   Host Organisation: ', '', '');
		$string .=$terms;
		$terms = get_the_term_list($id, 'occurs', ' Occurs: ', ' : ', ' : ');
		$string .=$terms;
		$terms = get_the_term_list($id, 'date', 'Dates: ', ' : ', '');
		}
		echo $string;
		?>
        <?php endwhile; ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php liquidchurch_entry_meta(); ?>
		<?php
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'liquidchurch' ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
