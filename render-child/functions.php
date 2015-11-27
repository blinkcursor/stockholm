<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), '20150407' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style', '20150407' ) );
    wp_enqueue_style( 'dashicons' ); // add dashicons to front end
}


/* ==========================================================================
   Setup Child Theme's textdomain.
   Translations can be filed in the /languages/ directory.
   ========================================================================== */
function hanna_child_theme_setup() {
    load_child_theme_textdomain( 'hanna-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'hanna_child_theme_setup' );


/* ==========================================================================
   KILL WORDPRESS TOOLBAR 
   ========================================================================== */
//add_filter('show_admin_bar', '__return_false');

/* ==========================================================================
   HELPER FUNCTIONS
   ========================================================================== */
/* Handy for debugging */
function printr ( $object , $name = '' ) {
	print ( '\'' . $name . '\' : ' ) ;
	print ( '<pre>');
	if ( is_array ( $object ) ) {
		print_r ( $object ) ; 
	} else {
		var_dump ( $object ) ;
	}
	print ( '</pre><br>' ) ;
}



/* ==========================================================================
   SET-UP CPTS
   ========================================================================== */

if ( defined( 'AWESOME_CPT' ) ) {

/* Events
   ========================================================================== */
  $event = new Awesome_Post_Type( array(
      'id'   => 'event',
      'name' => array(
          'singular' => 'event',
          'plural'   => 'events'
      ),
      'args' => array(
          'menu_icon'     => 'dashicons-calendar-alt',
          'menu_position' => 20,
          'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
          'has_archive' => 'events',
          'taxonomies'  => array('category')
      )
  ) );

// fix -- category failing to be set up correctly
function add_categories_to_events() {
  register_taxonomy_for_object_type( 'category', 'event' );  
}
add_action('init', 'add_categories_to_events', 100);


  /* Set-up metaboxes
     ========================================================================== */
  $event_date = new Awesome_Meta_Box( array(
      'id' => 'event_date',
      'title' => 'Event Date',
      'post_types' => array( 'event' ),
      'context' => 'normal',
      'priority' => 'high',
      'fields' => array(
        array(
          'id'  =>  'event_starts',
          'type'  =>  'text',
          'label' =>  'Event starts'
          )
      )
  ) );
  $event_link = new Awesome_Meta_Box( array(
      'id' => 'event_link',
      'title' => 'External Link',
      'post_types' => array( 'event' ),
      'context' => 'normal',
      'priority' => 'high',
      'fields' => array(
        array(
          'id'  =>  'external_link',
          'type'  =>  'text',
          'label' =>  'External Link'
          )
      )
  ) );
}


/* ==========================================================================
   ENQUEUE ADMIN SCRIPTS
   ========================================================================== */
function enqueue_theme_admin_scripts() {
  wp_enqueue_script( 'theme-admin-js', get_stylesheet_directory_uri() . '/js/theme-admin.js', 'jquery', '20150408', true );
}
add_action( 'admin_enqueue_scripts', 'enqueue_theme_admin_scripts' );

/* Set up datepicker to work when entering date of event
   ========================================================================== */
function event_add_datepicker(){
  global $post;
  if ( $post->post_type == 'event' && is_admin() ) {
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'jquery-ui-structure-style', get_stylesheet_directory_uri() . '/css/jquery-ui.structure.min.css' );
    wp_enqueue_style( 'jquery-ui-datepicker-style', get_stylesheet_directory_uri() . '/css/jquery-ui.theme.min.css' );
  }
}
add_action( 'admin_enqueue_scripts', 'event_add_datepicker');

/* ==========================================================================
   Exclude Articles category from homepage blog
   ========================================================================== */
function excludeCat($query) {
  if ( $query->is_home ) {
    $query->set('cat', '-67');
  }
  return $query;
}
add_filter('pre_get_posts', 'excludeCat');


/* ==========================================================================
   ADD USER CONTACT DETAILS VIA FILTER (since WP 3.7)
   ========================================================================== */
add_filter( 'user_contactmethods', 'add_user_contact_fields', 10, 2 );
function add_user_contact_fields($methods, $user) {
  $methods = array(
    'twitter'   =>  'Twitter alias',
    'facebook'  =>  'Facebook link',
    'google'    =>  'Google+ link'
    );
  return $methods;
}

/* ==========================================================================
   ADD EXTRA USER PROFILE FIELDS
   ========================================================================== */
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
function my_show_extra_profile_fields( $user ) { ?>
  <h3>Additional information</h3>
  <table class="form-table">
    <tr>
      <th><label for="position">Position</label></th>
      <td>
        <input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description">Position & institution</span>
      </td>
    </tr>
    <tr>
      <th><label for="extended">Extended profile</label></th>
      <td>
        <textarea name="extended" id="extended" class="regular-text" rows="12" cols="30"><?php echo esc_attr( get_the_author_meta( 'extended', $user->ID ) ); ?></textarea>
        <p class="description">Appears on full profile page.<br>Wrap titles in &lt;h2&gt;title goes here&lt;/h2&gt; tags</p>
      </td>
    </tr>
  </table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );
function my_save_extra_profile_fields( $user_id ) {

  if ( !current_user_can( 'edit_user', $user_id ) )
    return false;

  update_usermeta( $user_id, 'position', $_POST['position'] );
  update_usermeta( $user_id, 'extended', $_POST['extended'] );
}

/* ==========================================================================
   CUSTOM DYNAMIC MENU FOR EVENTS
   ========================================================================== */
add_filter( 'jcs/post_query_args', 'jc_post_query_args', 10, 1 );
function jc_post_query_args($post_args){
    // Set up loop with conditional query based on event date
    $now = date('Y-m-d');

    $post_args = array(
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

    return $post_args;
}
add_action( 'jcs/item_classes', 'jc_edit_item_classes', 10, 3 );
function jc_edit_item_classes($classes, $item_id, $item_type){
 
    $classes[] = "menu-item";
    return $classes;
}


/* ==========================================================================
   CUSTOMISE READ MORE... TEXT & LINK
   ========================================================================== */
remove_filter( 'excerpt_more', 'excerpt_render' );
add_filter( 'excerpt_more', 'new_excerpt_more' );
function new_excerpt_more($more) {
  global $post;
  return '<a class="moretag" href="'. get_permalink($post->ID) . '">&nbsp;&nbsp;Continue reading...</a>';
}

/* ==========================================================================
   RENDER AUTHOR V-CARD
   ========================================================================== */
function render_author_vcard( $user_id, $size ) {
  $user_info = get_userdata( $user_id );
//  $user_meta = get_user_meta( $user_id );

  $avatar_size = ($size == "full") ? 148: 96;
  if ( $size == "mini" ) {
    $name = $user_info->display_name;
  } else {
    $name = $user_info->nickname;
  }

  $render = '
  <div class="author-info media">
    <div class="author-avatar media__img">' .
      get_avatar($user_id, $avatar_size) . '
    </div><!-- .author-avatar -->
    <div class="author-description media__body">
      <h3 class="author-title"><a href="' . get_author_posts_url( $user_id ) . '">' . $name . '</a></h3>
      <h4 class="author-position">' . $user_info->position . '</h4>';

  if ( $size == 'full' || $size == 'extended' ) {
    $render .= '
    <p class="author-bio">' .
      nl2br( $user_info->description ) . '
    </p>';
  }

  $render .= '
    <div class="author-social">';
    if ( $user_info->twitter ) { $render .= '<a href="http://twitter.com/' . $user_info->twitter . '"><span class="dashicons dashicons-twitter"></span> </a>'; }
    if ( $user_info->facebook ) { $render .= '<a href="' . $user_info->facebook . '"><span class="dashicons dashicons-facebook"></span> </a>'; }
    if ( $user_info->google ) { $render .= '<a href="' . $user_info->google . '"><span class="dashicons dashicons-googleplus"></span> </a>'; }
    $render .= '<a href="mailto:' . $user_info->user_email . '"><span class="dashicons dashicons-email-alt"></span> </a>';
    if ( $user_info->user_url ) { $render .= '<a href="' . $user_info->user_url . '"><span class="dashicons dashicons-admin-links"></span> </a>'; }
    $render .= '</div><!-- author-social -->';

  if ( $size != "extended") {
    $render .= '
      <div class="author-posts">
        <a class="author-link" href="' . esc_url( get_author_posts_url( $user_id ) ) . '" rel="author">'; 
    if ( $user_info->roles[0] == 'author' ) {
      $render .= sprintf( __( 'View profile and posts of %s', 'render-child' ), $user_info->display_name );
    } else {
      $render .= sprintf( __( 'View profile of %s', 'render-child' ), $user_info->display_name );
    }
    $render .= '
        </a>
      </div>';
  }

  $render .= '</div><!-- .author-description -->';

  if ( $size == "extended" ) {
    $render .= '<div class="author-extended">' . wpautop( $user_meta[extended][0] ) . '</div><!-- .extended -->';
  }

  $render .= '</div><!-- .author-info -->';

  echo $render;
}

/* ==========================================================================
   FIX WPAUTOP FUCKUPS
   ========================================================================== */
function filter_ptags_on_images($content)
{
  $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
  return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
}
add_filter('the_content', 'filter_ptags_on_images');


function shortcode_empty_paragraph_fix($content)
  {   
    $array = array (
      '<p>[' => '[', 
      ']</p>' => ']', 
      ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
  }
add_filter('the_content', 'shortcode_empty_paragraph_fix');


/* ==========================================================================
   Set up new user roles (only needs doing once, on activation)
   ========================================================================== */
add_action('after_switch_theme', 'setup_roles');
function setup_roles () {
  add_role( 'board', 'Board Member', array( 'read' => true ) );
  add_role( 'affiliate', 'Affiliated Researcher', array( 'read' => true ) );
  remove_role( 'subscriber' );
  remove_role( 'editor' );
  // add Contributors as authors of posts
  $role = get_role( 'contributor' );
  $role->add_cap( 'publish_posts' );
  $role->add_cap( 'edit_published_posts' );
  $role->add_cap( 'upload_files' );
  $role->add_cap( 'delete_published_posts' );
  $role->add_cap( 'level_0' );
  $role->add_cap( 'level_1' );
  $role->add_cap( 'level_2' );
}
