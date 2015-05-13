<?php 
// Generic Wordpress helper functions

// 02. No Self Ping
// 04. Allow "rel" Links
// 05. Add RSS links to <head> section
// 06. Disable Admin Bar
// 07. Clean Up wp_head
// 08. Clean up the <head>
// 09. call the slug with the_slug();
// 10. Remove hints on LogIn failure
// 11. Remove WP version # from head
// 12. Featured Image Caption
// 13. Allow svg as featured image
// 14. HTML5 search form
// 15. Disable the emoji's

// 02. No Self Ping
  function no_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
      if ( 0 === strpos( $link, $home ) )
        unset($links[$l]);
  }
  add_action( 'pre_ping', 'no_self_ping' );

// 04. Allow "rel" Links
  function allow_rel() {
    global $allowedtags;
    $allowedtags['a']['rel'] = array ();
  }
  add_action( 'wp_loaded', 'allow_rel' );

// 05. Add RSS links to <head> section
  //automatic_feed_links();

// 06. Disable Admin Bar
  // Disable the Admin Bar By Default
  add_filter( 'show_admin_bar', '__return_false' );
  // Remove the Admin Bar preference in user profile to remove temptation...
  remove_action( 'personal_options', '_admin_bar_preferences' );

// 07. Clean Up wp_head
  remove_action('wp_head', 'feed_links_extra', 3); // Displays the links to the extra feeds such as category feeds
  remove_action('wp_head', 'feed_links', 2); // Displays the links to the general feeds: Post and Comment Feed
  remove_action('wp_head', 'rsd_link'); // Displays the link to the Really Simple Discovery service endpoint, EditURI link
  remove_action('wp_head', 'wlwmanifest_link'); // Displays the link to the Windows Live Writer manifest file.
  remove_action('wp_head', 'index_rel_link'); // index link
  remove_action('wp_head', 'parent_post_rel_link'); // prev link
  remove_action('wp_head', 'start_post_rel_link'); // start link
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head'); // Displays relational links for the posts adjacent to the current post.
  remove_action('wp_head', 'wp_generator'); // Displays the XHTML generator that is generated on the wp_head hook, WP version

// 08. Clean up the <head>
  function removeHeadLinks() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
  }
  add_action('init', 'removeHeadLinks');
  remove_action('wp_head', 'wp_generator');

// 09. call the slug with the_slug();
  function the_slug($echo=true){
    $slug = basename(get_permalink());
    do_action('before_slug', $slug);
    $slug = apply_filters('slug_filter', $slug);
    if( $echo ) echo $slug;
    do_action('after_slug', $slug);
    return $slug;
  }

// 10. Remove hints on LogIn failure
  add_filter('login_errors',create_function('$a', "return null;"));

// 11. Remove WP version # from head
  remove_action('wp_head', 'wp_generator');

// 12. Featured Image Caption
  function the_post_thumbnail_caption() {
    global $post;

    $thumbnail_id    = get_post_thumbnail_id($post->ID);
    $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

    if ($thumbnail_image && isset($thumbnail_image[0])) {
      echo '<span class="featuredImageCaption">'.$thumbnail_image[0]->post_excerpt.'</span>';
    }
  }

// 13. Allow svg as featured image
  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
  
// 14. HTML5 search form
  add_theme_support( 'html5', array( 'search-form' ) );

// 15. Disable the emoji's
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}
