<?php /* Template Name: page-add-fdbk */ ?>
<?php
$form_error = $frm_entry->validate($_POST);
if (!empty($form_error)) {
	foreach ($form_error as $key => $value) {
		if ($key != 'form') {
			$my_form_error = 'errors';
		}
	}
}
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<?php get_header();?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">
	<div class="wrapper">	
		<div id="main">
			<div id="content">			
				<h3 class="page-title">Add Feedback</h3>
<?php
if (is_user_logged_in()) {
	if ($_GET['ref'] == 'fdbk') {
		$item_key = $_GET['item'];
		$item_post_id = nh_get_frm_id_post_id($item_key);
		$category = get_the_category($item_post_id);
		echo '<div id="message" class="frm_message">Thanks for sharing your ideas with us. Your feedback has been posted, and we&#39;ll reply to it shortly.<br/><br/>In the meantime, see what other Neighborhow users think about your feedback by visiting the ';
		foreach ($category as $cat) {
			echo '<a class="whitelink" href="'.$app_url.'/feedback/'.$cat->slug.'" title="View feedback in '.$cat->name.'">'.$cat->name.'</a>';
		}
		echo ' page.</div>';
	}
	elseif (!$_GET) {
		echo '<p><strong>Thanks in advance for sharing your ideas with us.</strong></p><p>Have a content suggestion for a Neighborhow Guide? Or an idea about a new (or existing) feature? Got a question?</p><p>Please fill out the form below. Then we&#39;ll post your feedback and reply to it shortly.</p>';
		echo '<div id="add-fdbk">'.do_shortcode('[formidable id=18]').'</div>';
	}
	

}
elseif (!is_user_logged_in()) {
	echo 'Please <a href="'.$app_url.'/signin" title="Sign In">sign in</a> to give us your feedback about Neighborhow.';
	echo '<p style="margin-top:1.5em;"><a title="Sign In now" href="'.$app_url.'/signin" class="nh-btn-blue">Sign In</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a title="Sign Up now" href="'.$app_url.'/register" class="nh-btn-blue">Sign Up</a></p>';
}
?>
			</div><!--/ content-->
<?php
if (is_user_logged_in()) {
	get_sidebar('feedback');
}
?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		