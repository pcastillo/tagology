<html xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<title><?php if (is_home () ) { bloginfo('name'); echo ' - '; bloginfo('description'); }	
elseif (is_single() ) { single_post_title(); echo ' - ' ; bloginfo('name'); }
elseif (is_page() ) { single_post_title(); echo ' - ' ; bloginfo('name'); }	
elseif (is_category() ) { echo ucwords(single_cat_title('',false)); echo ' Category - ' ; bloginfo('name'); }
elseif (is_tag() ) { echo "Tagged with "; echo ucwords(tagology_tag_title('',false)); echo " - " ; bloginfo('name'); }
elseif (is_day() || is_month() || is_year() ) { echo 'Archives:'; wp_title(''); }	
elseif (is_author()) { echo 'Profile of '; wp_title(''); }	
else { wp_title('',true); } ?></title>
<link rel="alternate" type="application/atom+xml" href="<?php the_tagology_feed_url(); ?>" /> 
<link href='http://fonts.googleapis.com/css?family=Cabin:bold' rel='stylesheet' type='text/css'>
<link rel='stylesheet' id='blueprint-css'  href='<?php echo WP_THEME_URL; ?>/blueprint_1.0.css' type='text/css' media='screen,projection' /> 
<!--[if lt IE 8]>
  <link rel="stylesheet" href="<?php echo WP_THEME_URL; ?>/blueprint_1.0.ie.css" type="text/css" media="screen, projection">
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="dashboard">
<div class="container">
<div id="dash">
<div class="inner">
<div class="inner2">	
<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
<<?php echo $heading_tag; ?> id="site-title">
  <span>
    <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
  </span>  
</<?php echo $heading_tag; ?>>
<?php the_tagology_brand(); ?>
<span id="dashmeta">
<?php 
  if (is_user_logged_in()) { 
    wp_get_current_user();
    global $current_user;
    echo $current_user->display_name;
  ?> 
  <span class="bar">|</span>
  <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout">logout</a>
<?php } else { ?>
<span id="loginformwrapper" style="display:none;">
<?php tagology_login_form(); ?>
</span>
<a id="loginlink" href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">login</a>
<?php } ?>
</span> <!-- .dashmeta -->
</div>
</div>
</div>
</div>
</div>
<!-- header -->
<div id="headerboard">
<div class="container">
<div id="header">
<div class="inner">
<div class="inner2">	
<?php /* get_template_part( 'theme-header' ); */ ?>
</div>
</div>
</div>
</div>
</div>
<!-- sub header -->
<div id="subheaderboard">
<div class="container">
<div id="subheader">
<div class="inner">
<div class="inner2">	
<?php /* get_template_part( 'theme-subheader' ); */ ?>
</div>
</div>
</div>
</div>
</div>
