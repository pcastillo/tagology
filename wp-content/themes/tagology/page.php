<?php get_header(); ?>
<div id="contentboard">
<div class="container">
<?php if (trough_sidebar_left()) trough_get_template_part( 'theme-sidebar', 'page' ); ?>
<div id="content" class="column <?php trough_styles('content'); ?>">
<div class="inner">
<div class="inner2">	
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part( 'loop', 'page' ); ?>
    <?php /* comments_template(); */ ?>
  <?php endwhile; ?>
<?php endif; ?>
</div><!--/inner2-->
</div><!--/inner-->
</div><!--/content-->
<?php if (!trough_sidebar_left()) trough_get_template_part( 'theme-sidebar', 'page' ); ?>
</div><!--/container-->
</div><!--/contentboard-->
<?php get_footer(); ?>
