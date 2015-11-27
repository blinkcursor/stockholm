<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* author.php
* Displays all author pages.
*/
?>

<?php get_header(); ?>

  <section id="primary" class="content-area page">
    <main id="main" class="site-main" role="main">

      <header class="page-header">
      <?php get_template_part( 'author-page' ); ?>
      </header><!-- .page-header -->

    <?php if ( have_posts() ) : ?>

      <h1 class="section-title">Entries written by <?php echo get_the_author(); ?></h1>

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

    <?php endif; ?>

    </main><!-- #main -->
  </section><!-- #primary -->

<?php get_footer(); ?>
