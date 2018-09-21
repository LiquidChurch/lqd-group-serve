<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Liquid_Churchn
 * @since Liquid Church 1.0
 */
/** content-lqdoutreach.php
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
		?>
        <?php
            $string = '';
            $terms = '';
        /*while (have_posts() ) : the_post();{*/
        $string .= '</p> ';
        $terms = get_the_content() . '';
        $string .=$terms .'<p>';
        $terms = get_the_term_list($id, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
        $string .=$terms;
        $terms = get_the_term_list($id, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
        $string .=$terms;
        $terms = get_the_term_list($id, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
        $string .=$terms;
        $terms = get_the_term_list($id, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
        $string .=$terms;
        $terms = '<p style= "min-height:40px; max-width: 55%;"><a class="blue_btn" style="width:200px;float: left;" href=';
        $string .= $terms;
        $terms = get_field('sign_up_to_serve');
        $string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
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
