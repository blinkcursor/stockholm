<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* page-people.php
* Displays the page 'people'
*/
?>


<?php get_header(); ?>

  <div id="primary" class="content-area page">
    <main id="main" class="site-main" role="main">
    <?php zilla_post_before(); ?>
      <article <?php post_class(); ?>>
        <?php zilla_post_start(); ?>
        <div class="entry-content">
          <h1 class="entry-title">Staff</h1>
        <?php the_post_thumbnail( 'blog-post' ); ?>
        </div><!-- .entry-content -->
          <?php 
          $user_query = new WP_User_Query( array( 'role' => 'Author' ) );
          // User Loop
          if ( ! empty( $user_query->results ) ) {
            $size = "mini";
            foreach ( $user_query->results as $user ) { ?>
              <footer class="entry-footer">
              <?php render_author_vcard( $user->ID, $size ); ?>
              </footer>
            <?php } ?>
          <?php } ?>
      </article>

      <?php // Display Board Members
      $user_query = new WP_User_Query( array( 'role' => 'board' ) );
      // User Loop
      if ( ! empty( $user_query->results ) ) { ?>
        <article <?php post_class(); ?>>
          <div class="entry-content">
            <h1 class="entry-title">Board Members</h1>
          </div>
          <?php
          $size = "mini";
          foreach ( $user_query->results as $user ) { ?>
          <footer class="entry-footer">
            <?php render_author_vcard( $user->ID, $size ); ?>
          </footer>
          <?php } ?>
        </article>
      <?php }?>

      <?php // Display Affiliated Researchers
      $user_query = new WP_User_Query( array( 'role' => 'affiliate' ) );
      // User Loop
      if ( ! empty( $user_query->results ) ) { ?>
        <article <?php post_class(); ?>>
          <div class="entry-content">
            <h1 class="entry-title">Affiliated Researchers</h1>
          </div>
          <?php
          $size = "mini";
          foreach ( $user_query->results as $user ) { ?>
          <footer class="entry-footer">
            <?php render_author_vcard( $user->ID, $size ); ?>
          </footer>
          <?php } ?>
        </article>
      <?php }?>

      <?php // DISPLAY THE PEOPLE PAGE CONTENT ?>
      <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content">
          <div class="entry-body">
            <div class="content">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </article>
      <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
