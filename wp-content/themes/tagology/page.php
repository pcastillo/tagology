<?php get_header(); ?>
<div id="contentboard">
<div class="container">
<?php get_template_part( 'theme-sidebar', 'single' ); ?>
<div id="content" class="column last">
<div class="inner">
<div class="inner2">	
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part( 'loop', 'single' ); ?>
    <?php /* comments_template(); */ ?>
  <?php endwhile; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>
