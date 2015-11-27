<?php
/**
* @package Render
* @author Themezilla (http://www.themezilla.com)
*
* footer.php
* Displays the footer for the theme.
*
*/
?>

  <?php zilla_content_end(); ?>
	</div><!-- #content -->

  <?php zilla_footer_before(); ?>
  <footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info clearfix">
    <?php zilla_footer_start(); ?>
      <div class="footer--col col2of3 clearfix">
        <div class="footer--logo">
          <img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2015/05/WAF_logo_cropped.jpg" alt="Wallenberg Academy Fellows logo">
        </div>
        <div class="footer--logo">
          <img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2015/05/su_logo.png" alt="Stockholm University logo">
        </div>
      </div>
      <div class="footer--col col1of3">
        <div class="media">
          <div class="media__img">
            <p>&copy;2014-<?php echo date('Y'); ?></p>
          </div>
          <div class="media__body">
            <p>The Stockholm Centre for the Ethics of War and Peace</p>
          </div>
        </div>
        <?php
        if(is_user_logged_in())
        {
            echo '<p class="alignright"><a href="'. wp_logout_url() .'">Log Out</a></p>';
        } else {
            echo '<p class="alignright"><a href="'. wp_login_url() .'">Log In</a></p>';
        } ?>
      </div>
    <?php zilla_footer_end(); ?>
    </div><!-- .site-info -->
  </footer><!-- #colophon -->
  <?php zilla_footer_after(); ?>
</div><!-- #page -->
</div><!-- .page-wrap -->

<?php wp_footer(); ?>
<?php zilla_body_end(); ?>
</body>
</html>
