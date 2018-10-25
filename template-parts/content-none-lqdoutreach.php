<?php
/**
 * The template part for displaying a message that posts cannot be found
  *
 * @package WordPress
 * @subpackage Liquid_Churchn
 * @since Liquid Church 1.0
 */
?>
<!-- .entry-content -->
<div class="entry-content">
	<section class="no-results not-found">
		<header class="page-header">
			<h1 class="page-title"><?php _e( 'Nothing Found', 'liquidchurch' ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

				<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'liquidchurch' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

			<?php elseif ( is_search() ) : ?>

				<p><?php _e( "Sorry, we couldn't find any opportunities for the filters you set.", 'liquidchurch' ); ?></p>
				<?php get_search_form(); ?>

			<?php else : ?>

			<?php endif; ?>
		</div><!-- .page-content -->
	</section><!-- .no-results -->
</div><!-- .entry-content -->