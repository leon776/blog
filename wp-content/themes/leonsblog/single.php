<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<nav class="nav-single">
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '【上一篇】', 'Previous post link', '' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '<span class="meta-nav">' . _x( '【下一篇】', 'Next post link', '' ) . '</span> %title' ); ?></span>
				</nav><!-- .nav-single -->
				<?php setPostViews($post->ID);?>
				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>