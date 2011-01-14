<?php get_header(); ?>
<div id="contentboard">
<div class="container">
<div id="content">
<div class="inner">
<div class="inner2">
<?php /* get_search_form(); */ ?>
<form role="search" method="get" id="searchform" action="<?php bloginfo('url'); ?>" autocomplete="off">
	<label class="screen-reader-text" for="s">Search </label>
	<input type="text" value="" name="s" id="s">
	<input type="submit" id="searchsubmit" value="Search">
</form>
POPULAR: 
<?php the_popular_tags(); ?>
<br/>
RECENT:
<?php the_recent_tags(); ?>	
<br/>
<br/>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part( 'loop', 'index' ); ?>
  <?php endwhile; ?>  
  <div class="paginate">
    <?php if(function_exists('wp_paginate')) { wp_paginate(); } ?>
  </div>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>
