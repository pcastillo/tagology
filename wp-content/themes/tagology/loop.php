<div class="postdate">
<?php the_date('M j'); ?>
</div>
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<div class="title">
<a rel="external nofollow" name="post-<?php the_ID(); ?>" href="<?php the_savory_link(); ?>"><?php the_title(); ?></a>
<span class="path"><?php the_tagology_path(); ?></span>
</div>
<div class="source">
<!--&#x25B9;--><?php the_favicon(); ?><?php the_tagology_source(); ?><span class="tools"> <?php edit_post_link( 'edit', ' | ', ''); ?>
&nbsp;|&nbsp;<?php savory_tweet_link(); ?>
&nbsp;|&nbsp;<?php the_tagology_facebook_share_link(); ?>
</span></div>
<ul class="tags">
<li><span class="author">
<?php if (is_tagology_multi_user()) : ?>
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
<?php the_tags( '<li>','</li><li>','</li>' ); ?></ul>
<div class="clearfix"></div>
</div>

