<html>
<head>
<style>
* { font-size: 10pt !important;font-family:"Helvetica Neue",Arial,sans-serif;}
BODY { margin:0;padding:0;background:#eee;}
#body {display:none;}
A.cancel {font-size:0.8em;}
INPUT {display:inline;}
INPUT:focus {outline:none;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type='text/javascript'> 
/* <![CDATA[ */
jQuery(document).ready(function($) {
  $('#body').fadeIn(500, function() { $('#tags').focus(); } );
});
/* ]]> */
</script> 
</head>
<body>
<div id="body">
<?php

$display_form = true;

function savory_social($bookmark) {
  global $tagology_plugin;
  $msg = $tagology_plugin->get_short_message($bookmark);
  echo $msg;
  echo '<img src="' . WP_THEME_URL . '/bookmarklet/twitter.png"/>';
}

/*
 * echo a login form
 */
function tagology_login_form() {
  // see: http://www.wprecipes.com/add-a-login-form-on-your-wordpress-theme
?>
<?php if (!(current_user_can('level_0'))){ get_currentuserinfo(); if (empty($user_login)) { $user_login = ''; } ?>
<form action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
<label for="log">username</label> <input type="text" name="log" id="log" value="<?php echo esc_html(stripslashes($user_login), 1) ?>" size="20" />
<label for="pwd">password</label> <input type="password" name="pwd" id="pwd" size="20" />
<input type="submit" name="submit" value="Login" class="button" />
<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
</form>
<a href="<?php echo get_option('home'); ?>/wp-login.php?action=lostpassword">Recover password</a>
<?php }
}

// get parameters
global $wp_query;
global $tagology_plugin;
$url = $wp_query->query_vars['url'];
$title = $wp_query->query_vars['title'];
$hosturl = sprintf('http://%s/', parse_url($url, PHP_URL_HOST));
$taglist = '';

// check user
if (!is_user_logged_in()) {
  //echo '<img src="' . WP_THEME_URL . '/bookmarklet/rinfo.png"/>';
  //$loc = wp_login_url($url);
  //echo "&nbsp;Please <a target=\"_top\" href=\"$loc\">login</a> first!"; 
  tagology_login_form();
  $display_form = false;  
}

// get the bookmark if it exists
$bookmark = $tagology_plugin->bookmark_exists($url);
if ($bookmark)
  $taglist = $tagology_plugin->get_tags($bookmark);

if ($display_form) {
$action_new = get_bloginfo('url') . '/bookmarklet/new/';
$action_logout = get_bloginfo('url') . '/bookmarklet/logout/';
?>
  <form id="savory_form" style="" action="<?php echo $action_new; ?>" method="POST">
    <div style="display:inline;border:1px solid gray;background:white;width:300px;padding:2px 3px 3px 3px;">
      <img style="position:relative;top: 2px;" src="<?php echo WP_THEME_URL . '/bookmarklet/torange.png'; ?>"/>
      <input style="border:none;" id="tags" type="text" name="tags" value="<?php echo esc_attr($taglist); ?>"/>
    </div>&nbsp;
    <input type="hidden" name="url" value="<?php echo esc_attr($url); ?>"/>
    <input type="hidden" name="title" value="<?php echo esc_attr($title); ?>"/>
    <input type="submit" value="<?php the_bookmarket_text(); ?>"/>
<?php if ($bookmark) : ?>
  or <?php the_tagology_tweet_link($bookmark); ?>
  &nbsp;|&nbsp;<?php the_tagology_facebook_share_link($bookmark); ?>
  &nbsp;|&nbsp;<?php the_user_bookmarks_link(); ?>
  &nbsp;|&nbsp;<a href="<?php echo wp_logout_url($action_logout); ?>" title="Logout">Logout</a>
<?php else: ?>
  or <?php the_user_bookmarks_link(); ?>
  &nbsp;|&nbsp;<a href="<?php echo wp_logout_url($action_logout); ?>" title="Logout">Logout</a>
<?php endif; ?>
  </form>
<?php } ?>
</div>
</body>
</html>
