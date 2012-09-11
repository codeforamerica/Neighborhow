<?php
/* The template for displaying Comments.The area of the page that contains both current comments and the comment form. The actual display of comments is handled by a callback to nh_comment() which is located in the functions.php file.*/
?>

<div id="comments">
	<div class="comment-count">
		<p class="hdr-comments">Join the Conversation</p>
		<p class="count-comments"><?php comments_number('', '1 comment', '% comments' );?></p>
	</div>
	
<?php if (comments_open()) : ?>	
<?php //if (get_option('comment_registration') && !is_user_logged_in()) { ?>
<?php //} ?>

<?php
$tmp_user = wp_get_current_user();
$tmp_alt = 'Photo of '.$tmp_user->display_name;
$tmp_avatar = get_avatar($tmp_user->ID, 36,'',$tmp_alt);
$comments_args = array(
	'title_reply' => '',
	'must_log_in' => 'sdfsdf',
	'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( ''. $tmp_avatar . '</span><br/><span style="font-size:.85em;">(<a href="%3$s" title="Sign out of this account">sign out</a>)</span>' ), site_url( 'index.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
	'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Add your comment" cols="45" rows="3" aria-required="true"></textarea></p>',
	'comment_notes_after' => '<p class="form-allowed-tags">You can use these HTML tags:<br/><code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt;   &lt;b&gt; &lt;blockquote&gt; &lt;cite&gt; &lt;code&gt; &lt;em&gt; &lt;i&gt; &lt;strike&gt; &lt;strong&gt; </code></p>',
	'comment_notes_after' => '',	
	'label_submit'         => __( 'Post Comment' ),
);
comment_form($comments_args);
?>
<?php endif; ?>	

<?php if (have_comments()) : ?>
	<div class="comments-list">
		<ol class="commentlist">
<?php wp_list_comments( array( 'callback' => 'nhow_comment' ) );?>
		</ol>
	</div>
<?php else : ?>
	<?php if (!comments_open()) : ?>
		<div class="comment-closed">Comments are closed.
		</div>
	<?php endif; ?>
<?php endif; ?>
</div><!--/comments -->