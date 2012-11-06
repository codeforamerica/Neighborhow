<?php /* The template for displaying Comments. The area of the page that contains both current comments  and the comment form. The actual display of comments is handled by a callback to nh_comment() which is located in the functions.php file.*/
global $app_url;
$app_url = get_bloginfo('url');
?>
<div id="comments">
	<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'nhow' ); ?></p>

<?php
return;
endif;
?>

<div class="comment-count">
	<p class="hdr-comments">Join the Conversation</p>
	<p class="count-comments"><?php comments_number('', '1 comment', '% comments' );?></p>
</div>

<?php if (comments_open()) : ?>	
<?php
$tmp_user = wp_get_current_user();
$tmp_alt = 'Photo of '.$tmp_user->display_name;
$tmp_avatar = get_avatar($tmp_user->ID, 36,'',$tmp_alt);
$comments_args = array(
	'title_reply_to' => '',
	'title_reply' => '',	
	'must_log_in' => '',
	'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( ''. $tmp_avatar . '</span><br/><span style="font-size:.85em;"><a href="%3$s" title="Sign Out">sign out</a></span>' ), site_url( 'index.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
	'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Add your comment" cols="45" rows="3" aria-required="true"></textarea></p>',
	'comment_notes_after' => '<p class="form-allowed-tags">You can use these HTML tags:<br/><code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt;   &lt;b&gt; &lt;blockquote&gt; &lt;cite&gt; &lt;code&gt; &lt;em&gt; &lt;i&gt; &lt;strike&gt; &lt;strong&gt; </code></p>',
	'label_submit'         => __( 'Post Comment' ),
	'comment_notes_before'  =>  '<p class="comment-notes">' . __( 'To add your comment, <a href="'.$app_url.'/signin" title="Sign In">sign in to Neighborhow</a>, or enter your name and email address if you don&#39;t have an account.' ).'</p>'
);
comment_form($comments_args);
?>
<?php endif; ?>	
	
<?php if ( have_comments() ) : ?>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : 
// are there comments to navigate through ?>
<nav id="comment-nav-above">
<h1 class="assistive-text"><?php _e( 'Comment navigation', 'nhow' ); ?></h1>
<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'nhow' ) ); ?></div>
<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'nhow' ) ); ?></div>
</nav>
<?php endif; // check for comment navigation ?>

<div class="comments-list">
	<ol class="commentlist">
<?php
wp_list_comments( array( 'callback' => 'nh_comment' ) );
?>
	</ol>
</div>

<?php
elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
<div class="comment-closed">Sorry, comments are closed.
</div>
<?php endif; ?>
	</div>
</div><!-- #comments -->