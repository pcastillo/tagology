<?php

// the plugin is deployed with the theme
require_once( TEMPLATEPATH . '/plugin.tagology.php' );

if(!defined('WP_THEME_URL'))
  define( 'WP_THEME_URL', get_stylesheet_directory_uri() );

/*
 * is savory in multi-user mode?
 */
function is_savory_multi_user() {
  global $tagology_plugin;
  $options = $tagology_plugin->get_options();
  return ($options['is_multiuser']);
}

/*
 * display the sidebar?
 */
function tagology_show_sidebar() {
  return false; // for now...
}

/*
 * the_date filter
 */
add_filter('the_date', 'savory_the_date_filter', 15, 4);
function savory_the_date_filter($the_date, $d, $before, $after) {
  global $tagology_plugin;
  $arc_year = get_the_time('Y');
  $arc_month = get_the_time('m');
  $arc_day = get_the_time('d');
  return sprintf ('<a href="%s">%s</a>', $tagology_plugin->get_day_link($arc_year, $arc_month, $arc_day), $the_date );
}

/*
 * tag title
 */
function savory_tag_title($prefix = '', $display = true ) {
	global $wp_query;
	if ( !is_tag() )
		return;

  $tags = $wp_query->query_vars['tag_slug__and'];

	if ( ! $tags )
		return;
  
	//$my_tag_name = "'" . implode( "' '", $tags ) . "'";
  $my_tag_name = implode( ' and ', $tags );
	if ( !empty($my_tag_name) ) {
		if ( $display )
			echo $prefix . $my_tag_name;
		else
			return $my_tag_name;
	}
}

/*
 * your bookmarks links
 */
function savory_your_bookmarks_link() {
  
  if (!is_user_logged_in())
    return false;
    
  global $current_user;
  get_currentuserinfo();
      
	$link = sprintf(
		'<a target="_top" href="%1$s" title="%2$s">%3$s</a>',
		get_author_posts_url( $current_user->ID, $current_user->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s' ), $current_user->user_nicename ) ),
		'Your Bookmarks'
	);
  echo $link;
}

/*
 * facebook share - template tag
 */
function savory_facebook_share_link($bookmark = false) {
  // http://www.facebook.com/sharer.php?u=<url to share>&t=<title of content>
  global $tagology_plugin;
  
  if (!$bookmark) {
    global $post;
    $bookmark = $post;
  }
  
  $url = get_post_meta($bookmark->ID, '_SAVORY_URL', true);
  printf( '<a href="http://www.facebook.com/sharer.php?u=%s">Facebook</a>', esc_attr( $url) ); 
}

/*
 * add to google reader link - template tag
 */
function savory_add_to_google_link($bookmark = false) {
  global $tagology_plugin;
  
  if (!$bookmark) {
    global $post;
    $bookmark = $post;
  }
  
  $url = get_post_meta($bookmark->ID, '_SAVORY_URL', true);
  printf( '<a href="http://www.google.com/ig/addtoreader?et=gEs490VY&source=ign_pLt&feedurl=%s">+reader</a>', esc_attr( $url) ); 
}

/*
 * tweet link - template tag
 */
function savory_tweet_link($bookmark = false) {
  global $tagology_plugin;
  
  if (!$bookmark) {
    global $post;
    $bookmark = $post;
  }
  
  $msg = $tagology_plugin->get_short_message($bookmark);
  printf( '<a target="twitter" href="http://twitter.com/?status=%s">tweet</a>', rawurlencode($msg) );
}

/*
 * the_bookmarklet_link
 */
function the_bookmarklet_link() {
  $link = sprintf("javascript:var e=document.createElement('script');e.setAttribute('language','javascript');e.setAttribute('src','%s');document.body.appendChild(e);void(0);",
  get_bloginfo('url').'/bookmarklet/js/');
  echo $link;
}

/*
 * template tag - echo the URL for the current link (loop)
 */
function the_savory_link() {
  global $post;
  echo get_post_meta($post->ID, '_SAVORY_URL', true);
}

/*
 * template tag - echo an IMG tag of the URL link favicon
 */
function the_favicon() {
  global $tagology_plugin;
  $url = $tagology_plugin->get_the_favicon_url();
  echo sprintf("<img class=\"favicon\" src=\"http://s2.googleusercontent.com/s2/favicons?domain=%s\"/>", $url);
}

/*
 * display popular tags - template tag 
 */
function the_popular_tags($before = "<ul class='recenttags'><li>", $between = "</li><li>", $after = "</li></ul>") {
  global $tagology_plugin;
  
  // before  
  echo $before;
  
  // each tag
  $tags = $tagology_plugin->get_popular_tags();     
  $tag_a = array_map('savory_tag_html', $tags );
  echo implode($between, $tag_a);    
  
  // after
  echo $after;   
}

/*
 * display recent tags - template tag
 */
function the_recent_tags($before = "<ul class='recenttags'><li>", $between = "</li><li>", $after = "</li></ul>") { 
  global $tagology_plugin;
  
  // before  
  echo $before;
  
  // each tag
  $tags = $tagology_plugin->get_recent_tags();     
  $tag_a = array_map('savory_tag_html', $tags );
  echo implode($between, $tag_a);    
  
  // after
  echo $after;    
}

function savory_tag_html($x) {
  return sprintf("<a href=\"" . get_tag_link( $x->term_id) . "\">%s</a>", $x->name);
}
  
/*
 * template tag - the host name of the URL link
 */
function savory_the_source() {
  global $post;
  
  $url = get_post_meta($post->ID, '_SAVORY_URL', true);
  $host = parse_url($url, PHP_URL_HOST);
  echo $host;
}

/*
 * register styles
 */
if ( !is_admin() ) {
  
  // scripts
  wp_register_script( 'savory-script' , WP_THEME_URL . '/savory.js', array( 'jquery' ) );
  wp_enqueue_script( 'savory-script' );
  
  // styles
  wp_register_style( 'blueprint', WP_THEME_URL . '/blueprint_1.0.css' );
  wp_enqueue_style( 'blueprint' );

  wp_register_style( 'stylebase', WP_THEME_URL . '/stylebase.css' );
  wp_enqueue_style( 'stylebase' );

  wp_register_style( 'themestyle', get_bloginfo('stylesheet_url') );
  wp_enqueue_style( 'themestyle' );

  // disable the richtext editor
  add_filter( 'user_can_richedit', create_function( '$a', 'return false;' ));

  // modify head actions
  //
  remove_action( 'wp_head', 'feed_links_extra', 3 );
  remove_action( 'wp_head', 'feed_links', 2 );
  remove_action( 'wp_head', 'rsd_link');
  remove_action( 'wp_head', 'wlwmanifest_link');
  remove_action( 'wp_head', 'index_rel_link');
  remove_action( 'wp_head', 'parent_post_rel_link', 10 );
  remove_action( 'wp_head', 'start_post_rel_link', 10 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
  remove_action( 'wp_head', 'rel_canonical' );
  remove_theme_support('automatic-feed-links');
  remove_action('wp_head', 'wp_generator');

  // remove filters
  add_filter('login_errors', create_function('$a', "return null;"));
  
}
?>
