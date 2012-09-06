<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
global $current_user;
$nh_viewer_id = $current_user->ID;
// viewer
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
// author
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;

if (is_user_logged_in() AND $nh_viewer_id === $nh_author_id) {
	$welcometxt = 'Hi, '.$nh_author_name;
	$descriptiontxt = $nh_author->description;
}
else {
	$welcometxt = $nh_author_name;
	$descriptiontxt = $nh_author->description;
}

?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">		
			<div id="content">
<div class="author-welcome" style="border:1px solid #ddd;padding:1em;">
	<p style="float:left;margin-right:1em;">
<?php
$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '96','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=96&h=96&q=100&zc=1"><br/>';
echo userphoto($posts[0]->post_author);
}
else {
echo $nh_avatar.'<br/>';
}
?>				
	</p>
	<div class="author-elements">
		<h3 class="page-title" style=""><?php echo $welcometxt;?></h3>
		<p>ksldf</p>
		<p><?php echo $descriptiontxt;?></p>
	</div>
</div><!--/ author-welcome-->



<div class="author-posts" style="border:1px solid #ddd;">
	<div class="feat-container">sdfkjsdlkj	
	</div><!--/ feat-container-->	

</div><!--/ profile-posts-->

			</div><!--/ content-->
<?php 
//endif;
//rewind_posts(); 
?>			
<?php get_sidebar('home'); ?>
		</div><!--/ main-->
	</div><!--/ wrapper-->		
</div><!--/ row-content-->
<?php get_footer(); ?>