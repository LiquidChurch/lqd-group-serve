<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Liquid_Churchn
 * @since Liquid Church 1.0
 */
/** content-lqd-group-serve.php
 * 8/4/2018 ver 0.5 GC */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		/*if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'liquidchurch' ); ?></span>*/ ?>
		<?php // endif; ?>
		<?php the_title( sprintf( '<h3 class="page-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>   ' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php /*
			/* translators: %s: Name of current post */
		/*the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'liquidchurch' ),
			get_the_title()
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'liquidchurch' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'liquidchurch' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );*/
		?>
        <?php
            $string = '';
            $terms = '';
            
            /* comment out this ection to remove sign up button */
            //$terms = '<p style= "min-height:50px; max-width: 55%;"><a class="blue_btn" style="width: 40%;float: left;" href=';
            //$string .= $terms;
            //$terms = get_field('sign_up_to_serve');
		    //$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a>  ';
		//$terms = '';

        /* comment out this sec tion to remove url button */
		//$terms = '   <a class="blue_btn" style="width: 30%;float: right; " href="';
		//$string .= $terms;
		//$terms = get_field('host_url');
		//$string .=  $terms . '" target="_blank"> Host URL</a>';

        /*while (have_posts() ) : the_post();{*/
        $string .= '</p> ';
        $terms = get_the_content() . '';
        $string .=$terms .'<p>';
        $terms = get_the_term_list($id, 'DOW', '   Day(s): ', ' : ', ' : ');
        $string .=$terms;
        $terms = get_the_term_list($id, 'project-location', 'County/Campus: ', ' : ', ' : ');
        $string .=$terms;
        //$terms = get_the_term_list($id, 'FFRating', 'Family Friendly: ', ' : ', ' : ');
		$terms = get_field('family_friendly_rating');
        $string .= 'Family Friendly (min. age): ' . $terms . ' : ';
        //$terms = get_the_term_list($id, 'team-size', 'Team size: ', ' : ', '  ');
		$terms = get_field('number_of_participants');
		$string .= 'Team size: ' . $terms .'<br/>';
        $terms = get_the_term_list($id, 'Host-Org', '    Host Organisation: ', '', ' : ');
        $string .=$terms;
        //$terms = get_the_term_list($id, 'occurs', ' Occurs: ', ' : ', ' : ');
        //$string .=$terms;
        $terms = get_the_term_list($id, 'date', 'Dates: ', ' : ', '');
		$string .=$terms;
		$terms = get_the_term_list($id, 'project-type', '   Compassion Focus: ', ' : ', ' ');
		$string .=$terms;
        /*}*/
        echo $string;
        ?>
        <?php /*endwhile; */ ?>
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
