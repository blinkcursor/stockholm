<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* sidebar.php
* Displays the sidebar and widgets.
*/
?>

<?php zilla_sidebar_before(); ?>
<div id="secondary" class="widget-area" role="complementary">

  <?php if ( !is_post_type_archive( 'event' ) ) { ?>
  <aside class="widget">
    <h1 class="widget-title">Upcoming events</h1>
<?php 
    $now = date('Y-m-d');
//    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    $post_args = array(
//      'paged'          => $paged,
      'post_type'      => 'event',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
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

    $sidebar_events = new WP_Query( $post_args ); ?>

    <?php if ( $sidebar_events->have_posts() ) : ?>
        <?php /* Start the Loop */ ?>
        <?php while ( $sidebar_events->have_posts() ) : $sidebar_events->the_post(); ?>
          <?php $event_date = get_post_meta( get_the_ID(), 'event_starts', true);
                $event_date_nice = date( 'd M Y', strtotime($event_date) );
                $event_link = get_post_meta( get_the_ID(), 'external_link', true);
                $event_cats = get_the_category();
                $external = false;
                if ($event_cats) {
                  $external = ( $event_cats[0]->slug == 'external' );
                }
                ?>
          <?php // display list of event dates and titles ?>
                <p class="event-sidebar"><span class="event-date"><?php echo $event_date_nice; ?></span><span class="event-title"><?php if ($external) { echo '<span class="dashicons dashicons-external"></span>'; } ?><a href="<?php if ($external) { echo $event_link; } else { esc_url( the_permalink() ); } ?>" rel="bookmark"><?php the_title(); ?></a></span></p>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php wp_reset_query(); ?>
    <p class="sidebar-note"><span class="dashicons dashicons-external"></span> - External links</p>
  </aside>
  <?php } ?>


  <?php zilla_sidebar_start(); ?>
  <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

    <aside id="render-intro" class="widget widget_text">
      <h1 class="widget-title"><?php _e( 'About Render', 'render' ); ?></h1>
      <div class="textwidget">
        <p><?php _e( 'Make the most of this sidebar by populating with with widgets! Show off your Instagram feed, highlight your most popular topics, or link to archives. Play around with order and content to create an informative and cohesive reading experience.', 'render' ); ?></p>
      </div>
    </aside>

    <aside id="meta" class="widget">
      <h1 class="widget-title"><?php _e( 'Meta', 'render' ); ?></h1>
      <ul>
        <?php wp_register(); ?>
        <li><?php wp_loginout(); ?></li>
        <?php wp_meta(); ?>
      </ul>
    </aside>

  <?php endif; // end sidebar widget area ?>
  <?php zilla_sidebar_end(); ?>
</div><!-- #secondary -->
<?php zilla_sidebar_after(); ?>
