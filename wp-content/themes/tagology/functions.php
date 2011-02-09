<?php

// the plugin is deployed with the theme
require_once( TEMPLATEPATH . '/plugin.tagology.php' );

if(!defined('WP_THEME_URL'))
  define( 'WP_THEME_URL', get_stylesheet_directory_uri() );


// filters

add_filter( 'cancel_comment_reply_link', function( $html ) { return ''; } );

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
<span class="pwrdby">Powered by <span class="tag">TAG</span><span class="ology">ology</span></span>
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

/*
 * tagology comment
 */
function tagology_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 16 ); ?>
			<?php printf( __( '%s', 'tagology' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'tagology' ); ?></em>
			<br />
		<?php endif; ?>
		<div class="comment-meta commentmetadata"><span class="bar">|</span><?php
        printf( '%s' , tagology_get_time_diff_string(get_comment_time('U'), time()));
      ?><span class="bar">|</span><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">link</a>
      <span class="bar">|</span><span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
    </div><!-- .comment-meta .commentmetadata -->
		<div class="comment-body"><?php comment_text(); ?></div>
	
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

/*
 * echo a login form
 */
function tagology_login_form($args = array()) {
  // see: http://www.wprecipes.com/add-a-login-form-on-your-wordpress-theme
  $defaults = array( 'recover' => 'forgot?', 'submit' => 'Login' );
	$args = wp_parse_args( $args, $defaults );  
?>
<?php if (!(current_user_can('level_0'))){ get_currentuserinfo(); if (empty($user_login)) { $user_login = ''; } ?>
<form id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
<label for="log">username</label> <input type="text" name="log" id="log" value="<?php echo esc_html(stripslashes($user_login), 1) ?>" size="20" />
<label for="pwd">password</label> <input type="password" name="pwd" id="pwd" size="20" />
<input type="submit" name="submit" value="<?php echo $args['submit']; ?>" class="button" />
<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword"><?php echo $args['recover']; ?></a>
<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
</form>
<?php }
}

/*
 * time helper functions
 */
function tagology_get_time_difference( $start, $end )
{
  $uts['start']      =    $start ;
  $uts['end']        =    $end ;
  if( $uts['start']!==-1 && $uts['end']!==-1 )
  {
    if( $uts['end'] >= $uts['start'] )
    {
      $diff = $uts['end'] - $uts['start'];
      if( $days=intval((floor($diff/86400))) )
        $diff = $diff % 86400;
      if( $hours=intval((floor($diff/3600))) )
        $diff = $diff % 3600;
      if( $minutes=intval((floor($diff/60))) )
        $diff = $diff % 60;
      $diff    =    intval( $diff );            
      return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
    }
    else
    {
      trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
    }
  }
  else
  {
    trigger_error( "Invalid date/time data detected", E_USER_WARNING );
  }
  return( false );
}

function tagology_get_time_diff_string( $start, $end ) {
	$diff = tagology_get_time_difference( $start, $end );
	$timeclass = "";
	if (0 < $diff['days'])
		$dt = sprintf ( '%d days, %d hours ago', $diff['days'], $diff['hours'] );
	elseif (0 < $diff['hours'] )
		$dt = sprintf ( '%d hours, %d min. ago', $diff['hours'], $diff['minutes'] );
	elseif (0 == $diff['minutes'])
		$dt = 'less than a minute ago';
	else
		$dt = sprintf ( '%d min. ago', $diff['minutes'] );
  return $dt;
}

?>
