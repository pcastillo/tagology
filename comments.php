<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 */
?>
<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'tagology' ); ?></p>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>
<?php
	// You can start editing here -- including this comment!
?>

<?php comment_form(array(
  'title_reply' => '',   
  'title_reply_to' => '',
  /* cancel_reply_link is made empty by a filter */
  'must_log_in' => '<p class="must-log-in">' .  sprintf( __( '<a href="%s"><button>Log In to post a comment.</button></a>' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</p>',
  'comment_notes_after' => '',
  'logged_in_as' => '',
  'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="60" rows="4" aria-required="true"></textarea></p>',
)); ?>

<?php if ( have_comments() ) : ?>
  <?php if (false /* don't create comment list header */) : ?>
			<h3 id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'tagology' ),
			number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
			?></h3>
  <?php endif; ?>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'tagology' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'tagology' ) ); ?></div>
			</div> <!-- .navigation -->
<?php endif; // check for comment navigation ?>

      <ol class="commentlist">
        <?php wp_list_comments( array( 'callback' => 'tagology_comment', 'login_text' => 'reply', 'reply_text' => 'reply' ) ); ?>
      </ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'tagology' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'tagology' ) ); ?></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed.' ); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

</div><!-- #comments -->
