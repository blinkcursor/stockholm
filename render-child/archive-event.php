<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* archive.php
* Displays all archive pages.
*/
?>

<?php get_header(); ?>

  <section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <header class="page-header">
        <h1 class="section-title">Past Events</h1>
      </header><!-- .page-header -->

    <?php
    // Run 2 queries, one for past events, one for upcoming events
    $now = date('Y-m-d');
    $archive_args = array(
      'post_type'      => 'event',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'cat'            => -68,
      'meta_key'       => 'event_starts',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_query'     => array(
                            array(
                              'key'     => 'event_starts',
                              'compare' => '<',
                              'value'   => $now
                              )
        )
    ); ?>

    <?php $archive_events = new WP_Query( $archive_args ); ?>

    <?php if ( $archive_events->have_posts() ) : ?>
        <article class="post">
          <div class="entry-content">
            <div class="entry-body">
              <div class="content">
                <?php /* Start the Loop */ ?>
                <?php while ( $archive_events->have_posts() ) : $archive_events->the_post(); ?>
                  <?php $event_date = get_post_meta( get_the_ID(), 'event_starts', true);
                        $event_date_nice = date( 'd M Y', strtotime($event_date) ); ?>



                  <?php // display list of event dates and titles ?>
                  <p class="event-archive"><span class="date--fixed-width"><?php echo $event_date_nice; ?></span><a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark"> <?php the_title(); ?></a></p>
                <?php endwhile; ?>
              </div>
            </div>
          </div>
        </article>
    <?php endif; ?>

    <?php
    $upcoming_args = array(
      'post_type'      => 'event',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'cat'            => -68,
      'meta_key'       => 'event_starts',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_query'     => array(
                            array(
                              'key'     => 'event_starts',
                              'compare' => '>=',
                              'value'   => $now
                              )
        )
    );
    $upcoming_events = new WP_Query( $upcoming_args );
    ?>

      <header class="page-header">
        <h1 class="section-title">Upcoming Events</h1>
      </header><!-- .page-header -->

    <?php if ( $upcoming_events->have_posts() ) : ?>
      <?php /* Start the Loop */ ?>
      <?php while ( $upcoming_events->have_posts() ) : $upcoming_events->the_post(); ?>
        <?php // display list of event excerpts ?>
        <?php $event_date = get_post_meta( get_the_ID(), 'event_starts', true);
              $event_date_nice = date( 'd M Y', strtotime($event_date) );
         ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
          <div class="entry-content">
            <p class="event-archive"><span class="date--fixed-width"><?php echo $event_date_nice; ?></span><a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></p>
            <div class="entry-body">
              <div class="content">
                <?php the_excerpt(); ?>
              </div>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <?php get_template_part( 'content', 'none' ); ?>
    <?php endif; ?>
    </main><!-- #main -->
  </section><!-- #primary -->

  <?php get_sidebar(); ?>
<?php get_footer(); ?>