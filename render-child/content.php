<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* content.php
* Displays the content for all posts and pages.
*/
?>

<?php
  $thumbnail   = get_the_post_thumbnail();
  $post_format = get_post_format();
  $is_gallery  = 'gallery' == $post_format;
  $single      = is_single();
  $is_longform = render_is_longform();
  $is_blog     = ( is_home() || is_paged() || is_archive() || is_search() );

  $gallery              = get_post_gallery();
  $post_content_gallery = render_strip_shortcode( 'gallery', get_the_content() );
  $pc_gallery           = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $post_content_gallery ) );

  //TZ Post Formats
  $quote          = get_post_meta( $post->ID, '_zilla_quote_quote', true );
  $quote_author   = get_post_meta( $post->ID, '_zilla_quote_author', true );
  $link           = get_post_meta( $post->ID, '_zilla_link_url', true );
  $has_tz_gallery = render_post_gallery( $post->ID ) ? true : false;
?>

<?php zilla_post_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php zilla_post_start(); ?>

  <div class="entry-content">
    <?php if ( $is_longform && ! $is_blog ) :
      if ( ! $has_tz_gallery ) {
        the_post_thumbnail( 'blog-post' );
      }
    ?>
      <div class="single-featured-image">
        <?php if ( $is_gallery ) :
          echo render_post_gallery( $post->ID, 'single-post' );
          echo $gallery;
        endif; ?>
      </div>
    <?php endif; //end longform single ?>

    <?php if ( $is_longform && ! $is_blog ) : ?><div class="longform-content"><?php endif; ?>
      <?php if ( $is_longform && ! $is_blog ) : ?><div class="longform-title-time"><?php endif; ?>
        <?php
          $is_cat = is_category();
          if ( $is_cat ) :
            $category = single_cat_title( '', false );
            echo "<span class='entry-category'>" . $category . "</span>";
          else :
            $categories = get_the_category();
            foreach ($categories as $category) {
              if ( $category->cat_ID != 1 ) {
                echo "<span class='entry-category'><a href='" . esc_url( get_category_link( $category->cat_ID ) ) . "'>" . $category->cat_name . "</a></span>";
              }
            }
          endif;
        ?>
      <?php if ( $is_longform && ! $is_blog ) :?>
        <p class="read-time"><?php render_calculate_reading_time(); ?></p>
        </div>
      <?php endif; ?>

      <?php if ( ! $single ) : ?>
        <h1 class="entry-title"><a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
      <?php else : ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php endif; ?>

      <?php if ( ! is_single() && ! ( $is_longform && is_single() ) ) :
        // On the index page, show a tz gallery if we have one, otherwise show the feat. img
        if ( $has_tz_gallery ) {
          echo render_post_gallery( $post->ID, 'blog-post' );
        } else {
          the_post_thumbnail( 'blog-post' );
        }
      endif; ?>

      <div class="entry-body">
        <div class="content">
          <?php
            if ( $single && $is_gallery ) :
              echo $pc_gallery;
            else :
              // Quote
              if ( $quote ) {
                printf(
                  '<blockquote><p>%s<cite>%s</cite></p></blockquote>',
                  $quote,
                  $quote_author
                );
              }

              // Link
              if ( $link ) {
                echo '<p><a href="' . $link . '" target="_blank">' . $link . '</a></p>';
              }

              // Video
              if ( ! is_single() ) {
                echo render_print_video_html( $post->ID );
              }

              // Audio
              // if ( render_print_audio_html( $post->ID ) ) {
              //  echo '<p>' . render_print_audio_html( $post->ID ) . '</p>';
              //}

              the_content( __( 'Continue Reading &rsaquo;', 'render' ) );
            endif;
          ?>
        </div>
        <?php
          wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'render' ),
            'after'  => '</div>',
           ) );
        ?>
        <?php render_post_tags(); ?>
        <?php esc_url( edit_post_link( __( 'Edit', 'render' ), '<span class="edit-link">', '</span>' ) ); ?>
      </div><!-- .entry-body -->
    <?php if ( $is_longform && ! $is_blog ) : ?></div><?php endif; ?>

  </div><!-- .entry-content -->

  <?php if ( ! is_page() && ! is_attachment() ) : ?>
  <footer class="entry-footer">

    <?php if ( 'post' == get_post_type() ) : ?>

    <?php get_template_part( 'author-loop' ); ?>

    <?php endif; ?>

    <?php if ( is_sticky() ) : ?>
      <?php $sticky_post_label = get_theme_mod( 'render_sticky_post_label' ); ?>
      <p class="sticky-flag"><span class="sticky-label"><?php printf( __( '%1$s', 'render' ), get_theme_mod( 'render_sticky_post_label' ) ); ?></span> <i class="icon-tz-wp-icons-sticky"></i></p>
    <?php endif; ?>

  </footer><!-- .entry-footer -->
  <?php endif; ?>
<?php zilla_post_end(); ?>
</article><!-- #post-## -->
<?php zilla_post_after(); ?>
