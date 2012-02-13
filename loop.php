<div class="postdate">
<?php the_date('M j'); ?>
</div>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<?php if (is_tagology_multi_user()) : ?>
<div class="pop"><span><?php the_saved_count(); ?></span></div>
<?php endif; ?>
<div class="title">
<a rel="external nofollow" name="post-<?php the_ID(); ?>" href="<?php the_tagology_link(); ?>"><?php the_title(); ?></a>
<span class="path"><?php the_tagology_path(); ?></span>
</div>
<div class="source">
<!--&#x25B9;--><?php the_favicon(); ?><?php the_tagology_source(); ?>
<span class="bar">|</span><?php comments_popup_link('discuss', '1 comment', 
'% comments', 'comments-link', ''); ?>
<span class="tools"> <?php edit_post_link( 'edit', '<span class="bar">|</span>', ''); ?>
<span class="bar">|</span><?php the_tagology_tweet_link(); ?>
<span class="bar">|</span><?php the_tagology_facebook_share_link(); ?>
<?php tagology_save_link('<span class="bar">|</span>'); ?>
</span></div>



<ul class="tags">
<?php the_tags( '<li>','</li><li>','</li>' ); ?>
<?php if (is_tagology_multi_user()) : ?>
<li><span class="author">
<?php
 	global $authordata;
	$link = sprintf(
		'<a href="%1$s" title="%2$s">',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) )
	);
  echo $link . get_avatar( $post->post_author, $size = '16') . '</a>';
?>
</span></li>
<?php endif; ?>
</ul>
<div class="clearfix"></div>
<?php do_action('tagology_embeds'); ?>
</div>

