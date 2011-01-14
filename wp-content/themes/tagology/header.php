<html xmlns:og="http://opengraphprotocol.org/schema/">
<head>
<title><?php if (is_home () ) { bloginfo('name'); echo ' - '; bloginfo('description'); }	
elseif (is_single() ) { single_post_title(); echo ' - ' ; bloginfo('name'); }
elseif (is_page() ) { single_post_title(); echo ' - ' ; bloginfo('name'); }	
elseif (is_category() ) { echo ucwords(single_cat_title('',false)); echo ' Category - ' ; bloginfo('name'); }
elseif (is_tag() ) { echo "Tagged with "; echo ucwords(savory_tag_title('',false)); echo " - " ; bloginfo('name'); }
elseif (is_day() || is_month() || is_year() ) { echo 'Archives:'; wp_title(''); }	
else { wp_title('',true); } ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="dashboard">
<div class="container">
<div id="dash">
<div class="inner">
<div class="inner2">	
<!-- dashboard -->
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
<?php get_template_part( 'theme-header' ); ?>
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
<?php get_template_part( 'theme-subheader' ); ?>
</div>
</div>
</div>
</div>
</div>
