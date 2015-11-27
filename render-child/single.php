<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* single.php
* Displayed for single posts.
*/
?>

<?php get_header(); ?>

  <?php $is_longform = render_is_longform(); ?>

  <div id="primary" class="content-area <?php if ( $is_longform ) :?>longform<?php endif;?>">
    <main id="main" class="site-main" role="main">
    <?php while ( have_posts() ) : the_post(); ?>

      <?php get_template_part( 'content' ); ?>

      <?php //render_post_nav(); ?>

      <?php zilla_comments_before(); ?>
      <?php
        // If comments are open or we have at least one comment, load up the comment template
        if ( comments_open() || '0' != get_comments_number() ) :
          comments_template();
        endif;
      ?>
      <?php zilla_comments_after(); ?>

    <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php if ( ! $is_longform ) : get_sidebar(); endif; ?>
<?php get_footer(); ?>
