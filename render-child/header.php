<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* header.php
* Displays the header for the theme.
*/
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php zilla_meta_head(); ?>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<?php zilla_head(); ?>
</head>

<?php
  $thumbnail             = get_post() ? get_the_post_thumbnail() : false;
  $gallery               = get_post_gallery();
  $has_header_image      = get_header_image();
  $single                = is_single();
  $home                  = ( is_home() || is_front_page() );
  $internal_page         = ( ( is_singular() || is_archive() ) && ! is_front_page() );
  $is_video_format       = 'video' == get_post_format();
  $is_gallery_format     = 'gallery' == get_post_format();
  $render_image          = get_theme_mod( 'render_hero_image' );
  $render_video_webm     = get_theme_mod( 'render_hero_video_webm' );
  $render_video_mp4      = get_theme_mod( 'render_hero_video_mp4' );
  $has_video             = ( $render_video_webm || $render_video_mp4 );
  $has_hero              = ( $render_image || $has_video );
  $single_with_image     = ( $single && $thumbnail );
  $has_title_description = get_header_textcolor();
  $has_description       = get_bloginfo( 'description' );
  $header_style          = get_theme_mod( 'render_select_alt_header_type' );
  $has_tz_gallery        = render_post_gallery( get_the_ID() ) ? true : false;
?>

<body <?php body_class(); ?>>
<?php zilla_body_start(); ?>

<?php if ( is_front_page() ) : ?>
  <?php if ( $render_image && ! $has_video ) : ?>
    <div class="home-featured-content"><div class="header-overlay"></div></div>
  <?php endif;
  if ( $has_video || ( $render_image && $has_video ) ) : ?>
    <div class="home-featured-content video">
      <div class="header-overlay"></div>
      <video autoplay="autoplay" controls="false" muted loop poster="<?php echo esc_url( $render_image ); ?>">
        <source src="<?php echo esc_url( $render_video_webm ); ?>" type="video/webm">
        <source src="<?php echo esc_url( $render_video_mp4 ); ?>" type="video/mp4">
      </video>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div class="page-wrap <?php if ( $has_hero && ! $internal_page && ! is_search() ) :?>with-hero<?php endif; ?>">
<div id="page" class="container-page hfeed site">

  <?php zilla_header_before(); ?>
  <header id="masthead" class="site-header <?php if ( $has_hero && ! $single && !( $internal_page && $has_hero && ! $thumbnail ) ) :?>thumbnail<?php endif; ?> <?php if ( 'compact' == $header_style ) :?>compact<?php endif; ?> <?php if ( $has_header_image ) :?>with-logo<?php endif; ?>" role="banner">
    <?php zilla_header_start(); ?>
    <div class="site-branding <?php if ( $has_header_image ) :?>with-logo<?php endif; ?> <?php if ( 'blank' == $has_title_description ) :?>no-title-description<?php endif; ?>">
      <?php if ( $has_header_image ) : ?>
        <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
          <img src="<?php esc_url( header_image() ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="">
        </a>
        <img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2015/05/su_logo.png" alt="Stockholm University logo" class="logo--extra">
        <img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2015/05/WAF_logo_cropped.jpg" alt="Wallenberg Academy Fellows logo" class="logo--extra">
      <?php endif; ?>
      <div class="title-description">
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <?php if ( $has_description ) : ?>
          <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
        <?php endif; ?>
      </div>
      <div class="menu-toggle">
        <div class="hamburger-icon">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <i class="icon-tz-wp-icons-close"></i>
      </div>
    </div>
    <?php zilla_nav_before(); ?>
    <nav id="site-navigation" class="main-navigation <?php if ( render_social_icons() ) : ?>with-social<?php endif; ?>" role="navigation">
      <a class="skip-link screen-reader-text visuallyhidden" href="#content"><?php _e( 'Skip to content', 'render' ); ?></a>
      <div class="nav-search">
        <?php get_template_part( 'search', 'navigation' ); ?>
        <i class="icon-tz-wp-icons-search search-toggle"></i>
      </div>
      <?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => 3 ) ); ?>
      <?php echo render_social_icons(); ?>
    </nav><!-- #site-navigation -->
    <?php zilla_nav_after(); ?>
  <?php zilla_header_end(); ?>
  </header><!-- #masthead -->
  <?php zilla_header_after(); ?>

  <?php if ( has_nav_menu( 'top-bar' ) ) : ?>
  <header class="fixed-header">
    <?php if ( $has_header_image ) : ?>
      <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <img src="<?php esc_url( header_image() ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="">
      </a>
    <?php else : ?>
      <h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
    <?php endif; ?>
    <nav class="main-navigation" role="navigation">
      <div class="nav-search">
        <?php get_template_part( 'search', 'navigation' ); ?>
        <i class="icon-tz-wp-icons-search search-toggle"></i>
      </div>
      <?php wp_nav_menu( array(
        'theme_location'  => 'top-bar',
        'depth'           => 3,
        'container_class' => 'main-nav-links',
        'fallback_cb'     => '',
      ) ); ?>
    </nav><!-- #site-navigation -->
  </header>
  <?php endif; ?>

  <?php if ( $single && ! render_is_longform() ) :
    if ( $thumbnail || $has_tz_gallery || ( $gallery && $is_gallery_format ) || $is_video_format ) : ?>
      <div class="single-featured-image">
        <?php if ( ! $has_tz_gallery ) {
          the_post_thumbnail( 'single-post' );
        } ?>
        <?php
          if ( $is_gallery_format ) {
            echo render_post_gallery( $post->ID, 'single-post' );
            echo $gallery;
          } elseif ( $is_video_format ) {
            echo render_print_video_html( $post->ID );
          }
        ?>
      </div>
  <?php endif;
  endif; ?>

  <div id="content" class="site-content <?php if ( $has_hero && ! $internal_page && ! is_search() ) :?>with-hero<?php endif; ?>">
  <?php zilla_content_start(); ?>
