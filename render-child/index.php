<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* index.php
* The theme's main template file. Required.
*/
?>

<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php // insert the original 'about' page on the home page
		if ( is_front_page() && get_post_status( 221 ) ) {
			$the_post = get_post( 221 ); //the about page to merge with home page
			$the_title = $the_post->post_title;
			$the_excerpt = $the_post->post_excerpt;
			$the_content = apply_filters( 'the_content', $the_post->post_content);
			?>
			<article id="post-221" class="post">
				<div class="entry-content">
					<?php /* <h1 class="entry-title"><?php echo $the_title; ?></h1> */ ?>
					<div class="entry-body">
						<div class="content">
							<?php echo $the_content; ?>
						</div>
					</div>
				</div>
			</article>
		<?php } ?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php render_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>