<div class="postdate">
<?php the_date('M j'); ?>
</div>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<div class="title">
<?php the_favicon(); ?>
<a rel="external nofollow" href="<?php the_savory_link(); ?>"><?php the_title(); ?></a></div>
<div class="source">
<?php if (is_savory_multi_user()) : ?>
<span class="author"><?php the_author_posts_link(); ?></span> | 
<?php endif; ?>
<!--&#x25B9;--><?php savory_the_source(); ?><span class="tools"> <?php edit_post_link( 'edit', ' | ', ''); ?>
&nbsp;|&nbsp;<?php savory_tweet_link(); ?>
&nbsp;|&nbsp;<?php savory_facebook_share_link(); ?>
<?php /* not ready yet savory_add_to_google_link(); */ ?>
</span></div>
<ul class="tags"><?php the_tags( '<li>','</li><li>','</li>' ); ?></ul>
<div class="clearfix"></div>
</div>

