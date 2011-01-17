<html>
<head>
<style>
BODY { font-family: "Helvetica Neue", Arial, sans-serif; margin: 0; padding: 0; background:#eee;}
#body { display: none; }
A.cancel { font-size: .8em; }
INPUT { display: inline; }
INPUT:focus { outline: none; }
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

// get parameters
global $wp_query;
global $tagology_plugin;
$url = $wp_query->query_vars['url'];
$title = $wp_query->query_vars['title'];
$hosturl = sprintf('http://%s/', parse_url($url, PHP_URL_HOST));
$taglist = '';

// check user
if (!is_user_logged_in()) {
  echo '<img src="' . WP_THEME_URL . '/bookmarklet/rinfo.png"/>';
  $loc = wp_login_url($url);
  echo "&nbsp;Please <a target=\"_top\" href=\"$loc\">login</a> first!"; 
  $display_form = false;  
}

// get the bookmark if it exists
$bookmark = $tagology_plugin->bookmark_exists($url);
if ($bookmark)
  $taglist = $tagology_plugin->get_tags($bookmark);

if ($display_form) {
$action = get_bloginfo('url') . '/bookmarklet/new/';
?>
  <form id="savory_form" style="" action="<?php echo $action; ?>" method="POST">
    <div style="display:inline;border:1px solid gray;background:white;width:300px;padding:2px 3px 3px 3px;">
      <img style="position:relative;top: 2px;" src="<?php echo WP_THEME_URL . '/bookmarklet/torange.png'; ?>"/>
      <input style="border:none;" id="tags" type="text" name="tags" value="<?php echo esc_attr($taglist); ?>"/>
    </div>&nbsp;
    <input type="hidden" name="url" value="<?php echo esc_attr($url); ?>"/>
    <input type="hidden" name="title" value="<?php echo esc_attr($title); ?>"/>
    <input type="submit" value="Savor It!"/>
<?php if ($bookmark) : ?>
  or <?php savory_tweet_link($bookmark); ?>
  &nbsp;|&nbsp;<?php savory_facebook_share_link($bookmark); ?>
  &nbsp;|&nbsp;<?php savory_your_bookmarks_link(); ?>
<?php else: ?>
  or <?php savory_your_bookmarks_link(); ?>
<?php endif; ?>
  </form>
<?php } ?>
</div>
</body>
</html>
