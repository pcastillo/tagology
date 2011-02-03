<html>
<head>
<style>
* { font-size: 10pt !important;font-family:"Helvetica Neue",Arial,sans-serif;}
BODY { margin:0;padding:0;background:#eee;}
</style>
</head>
<body>
<?php

// get parameters
global $wp_query;

$url = stripslashes($wp_query->query_vars['url']);
$desc = stripslashes($wp_query->query_vars['title']);
$taglist = $wp_query->query_vars['tags'];

$hash = md5($url);
$time = gmdate('Y-m-d H:i:s') . ' GMT';

// check user
if (!is_user_logged_in()) {
  echo '<img src="' . WP_THEME_URL . '/bookmarklet/rinfo.png"/>';
  $loc = wp_login_url($url);
  echo "&nbsp;Please <a target=\"_top\" href=\"$loc\">login</a> first!";   
}

// attempt to add it
global $tagology_plugin;
if ($tagology_plugin->insert_bookmark($hash, $url, $desc, $taglist, $time)) {
 echo '<img src="' . WP_THEME_URL . '/bookmarklet/success.png"/>';
 echo "&nbsp;Success!";
 $bookmark = $this->bookmark_exists($url);
?>
&nbsp;|&nbsp;<?php savory_tweet_link($bookmark); ?>
&nbsp;|&nbsp;<?php the_tagology_facebook_share_link($bookmark); ?>
&nbsp;|&nbsp;<?php savory_your_bookmarks_link(); ?>

<?php
}
else {
 echo '<img src="' . WP_THEME_URL . '/bookmarklet/rinfo.png"/>';
?>
&nbsp;Houston, we have a problem!
<!--<a class="cancel" href="javascript:savory_cancel();void(0);">Close</a>-->
<?php
}
?>
</form>
</body>
</html>
