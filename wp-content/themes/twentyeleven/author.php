<?php get_header(); ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
// viewer
global $current_user;
$nh_viewer_id = $current_user->ID;

// author
/*$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;
*/
?>

<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<?php // if viewer = author
/*if (is_user_logged_in() AND $nh_viewer_id === $nh_author_id) {
	$welcometxt = 'Hi, '.$nh_author_name;
	$descriptiontxt = $nh_author->description;
}
else {
	$welcometxt = $nh_author_name;
	$descriptiontxt = $nh_author->description;
}*/
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">		
			<div id="content">
<div class="author-welcome" style="border:1px solid #ddd;padding:1em;">
	<p style="float:left;margin-right:1em;">
<?php
/*$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '96','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=96&h=96&q=100&zc=1"><br/>';
echo userphoto($posts[0]->post_author);
}
else {
echo $nh_avatar.'<br/>';
}*/
?>				
	</p>
	<div class="author-elements">
		<h3 class="page-title" style=""><?php //echo $welcometxt;?></h3>
		<p>testing</p>
		<p><?php //echo $descriptiontxt;?></p>
	</div>
</div><!--/ author-welcome-->
<?php echo the_title();?>


<div class="author-posts" style="border:1px solid #ddd;">
	<div class="feat-container">

<?php           
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $_GET['author_name']) : get_userdata($_GET['author']);
?>
   
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <li>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>">
        <?php the_title(); ?></a>,
        <?php the_time('d M Y'); ?> in <?php the_category('&');?>
    </li>

<?php endwhile; else: ?>
    <p><?php _e('No posts by this author.'); ?></p>

<?php endif; ?>





<?php
/*$numposts = $wpdb->get_results("SELECT * FROM nh_posts WHERE post_author = $curauth->ID AND post_status = 'publish' OR  post_status = 'draft' OR post_status = 'publish' AND post_type = 'nh_guides'");

if ($numposts) {

?>
<h5 class="">Neighborhow Guides</h5>
<div class="">
<ul>

<?php		
	foreach ($numposts as $numpost) {	
		global $frmdb, $wpdb, $post;
		$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $numpost->ID ."'");		
?>
	<li><a href="<?php echo $app_url;?>/edit-guide?action=edit&amp;entry=<?php echo esc_attr($item_key);?>"><?php echo $numpost->post_title;?></a></li>
<?php
	}
?>
</ul>
</div>
<?php
}
else {
	echo 'no results';
}
*/
?>


		</div>
	</div><!--/ feat-container-->	
</div><!--/ profile-posts-->

			</div><!--/ content-->
<?php 
//endif;
//rewind_posts(); 
?>			
<?php get_sidebar(''); ?>
		</div><!--/ main-->
	</div><!--/ wrapper-->		
</div><!--/ row-content-->
<?php get_footer(); ?>