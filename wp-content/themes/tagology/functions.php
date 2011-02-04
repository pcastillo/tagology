<?php

// the plugin is deployed with the theme
require_once( TEMPLATEPATH . '/plugin.tagology.php' );

if(!defined('WP_THEME_URL'))
  define( 'WP_THEME_URL', get_stylesheet_directory_uri() );

/*
 * echo the tagology path - used in the loop
 */
function the_tagology_path() {
  global $tagology_plugin;  
  
  $url = $tagology_plugin->get_bookmark_url();
  if (!$url)
    return;
    
  $parts = parse_url($url);
  if (false !== $parts) {
    $path = @$parts['path'];
    $qs = @$parts['query'];
    $fi = @$parts['fragment'];
    
    $mash = $path;
    if (!empty($qs))
      $mash .= '?' . $qs;
    if (!empty($fi))
      $mash .= '#' . $fi;
    if ('/'!= $mash) {
      echo tagology_short_text($mash, 50, '&hellip;');
    }
  }
}

/*
 * echo the brand
 */
function the_tagology_brand() { ?>
<span id="pwrdby">Powered by <span class="tag">TAG</span><span class="ology">ology</span></span>
<?php }

/*
 * in multi-user mode?
 */
function is_tagology_multi_user() {
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
add_filter('the_date', 'tagology_the_date_filter', 15, 4);
function tagology_the_date_filter($the_date, $d, $before, $after) {
  global $tagology_plugin;
  $arc_year = get_the_time('Y');
  $arc_month = get_the_time('m');
  $arc_day = get_the_time('d');
  return sprintf ('<a href="%s">%s</a>', $tagology_plugin->get_day_link($arc_year, $arc_month, $arc_day), $the_date );
}

/*
 * tag title
 */
function tagology_tag_title($prefix = '', $display = true ) {
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
 * echo the link for the logged-in user's bookmarks
 */
function the_user_bookmarks_link() {
  
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
 * echo a link to share with facebook
 */
function the_tagology_facebook_share_link($bookmark = false) {
  // http://www.facebook.com/sharer.php?u=<url to share>&t=<title of content>
  global $tagology_plugin;
  
  // get bookmark URL
  $url = $tagology_plugin->get_bookmark_url($bookmark);
  if (!$url)
    return;
  
  // create facebook share link
  $url = tagology_mod_url ($url);
  $url = sprintf ('http://www.facebook.com/sharer.php?u=%s', urlencode ($url) );
  $url = sprintf ('<a href="%s">Facebook</a>', esc_attr( $url) );
  echo $url;
}

function tagology_mod_url ($url) {
  
  # twitter list urls don't share correctly with the facebook share bookmarklet
  # at http://www.facebook.com/share_options.php
  # e.g. http://twitter.com/#!/nprnews/egypt2011
  if (preg_match('|(https?://twitter.com)/#!/([^/]+/.+)/?|', $url, $matches)) 
    $url = $matches[1] . '/' . $matches[2];
  
  // fallback
  return $url;
}

/*
 * add to google reader link - template tag
 */
function tagology_add_to_google_link($bookmark = false) {
  global $tagology_plugin;
    
  $url = $tagology_plugin->get_bookmark_url($bookmark);
  if ($url)
    printf( '<a href="http://www.google.com/ig/addtoreader?et=gEs490VY&source=ign_pLt&feedurl=%s">+reader</a>', esc_attr( $url) ); 
}

/*
 * echo the twitter share link for a bookmark
 */
function the_tagology_tweet_link($bookmark = false) {
  global $tagology_plugin;  
  if (!$bookmark) {
    global $post;
    $bookmark = $post;
  }
  $msg = $tagology_plugin->get_short_message($bookmark);
  printf( '<a target="twitter" href="http://twitter.com/?status=%s">tweet</a>', rawurlencode($msg) );
}

/*
 * echo the bookmarklet link
 */
function the_bookmarklet_link() {
  $link = sprintf("javascript:var e=document.createElement('script');e.setAttribute('language','javascript');e.setAttribute('src','%s');document.body.appendChild(e);void(0);",
  get_bloginfo('url').'/bookmarklet/js/');
  echo $link;
}

/*
 * echo the bookmarket text
 */
function the_bookmarket_text() {
  global $tagology_plugin;
  $options = $tagology_plugin->get_options();
  $text = $options['bookmarklet_text'];
  if (empty($text))
    $text = 'Tag It!';
  echo $text;
}

/*
 * template tag - echo the URL for the current link (loop)
 */
function the_tagology_link() {
  global $tagology_plugin;
  echo $tagology_plugin->get_bookmark_url();
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
  if (0 < count($tags)) {     
    $tag_a = array_map('tagology_tag_html', $tags );
    echo implode($between, $tag_a); 
  }
  
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
  $tag_a = array_map('tagology_tag_html', $tags );
  echo implode($between, $tag_a);    
  
  // after
  echo $after;    
}

function tagology_tag_html($x) {
  return sprintf("<a href=\"" . get_tag_link( $x->term_id) . "\">%s</a>", $x->name);
}
  
/*
 * echo the host name of the bookmark - used in the loop
 */
function the_tagology_source() {
  global $tagology_plugin;
  
  $url = $tagology_plugin->get_bookmark_url();
  if ($url) {
    $host = parse_url($url, PHP_URL_HOST);
    echo $host;
  }
}

/*
 * shorten text with an optional trailing string if the length is exceeded
 */
function tagology_short_text($text, $length, $after = '') {
	if ( strlen($text) > $length ) {
    $text = substr($text,0,$length);
    echo $text . $after;
	} else {
    echo $text;
	}
}

/*
 * register styles
 */
if ( !is_admin() ) {
  
  // scripts
  wp_register_script( 'tagology-script' , WP_THEME_URL . '/tagology.js', array( 'jquery' ) );
  wp_enqueue_script( 'tagology-script' );
  
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
