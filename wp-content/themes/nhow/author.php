<?php get_header(); ?>
<?php
//get viewer
global $current_user;
get_currentuserinfo();
$viewer_id = $current_user->ID;
$viewer = get_userdata($viewer_id);

//get profile owner
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
//get id
$nhauthor_id = $curauth->ID;
//get data
$nhauthor = get_userdata($nhauthor_id);
//get some info
$nhauthor_name = $nhauthor->first_name.' '.$nhauthor->last_name;
$nhauthor_avatar_alt = 'Photo of '.$nhauthor_name;
$nhauthor_avatar = get_avatar($nhauthor_id, '96','',$nhauthor_avatar_alt);
?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div id="main">
		<div id="content">
			<div class="profile-div" style="border:1px solid #ddd;">
				<div class="profile-welcome" style="border:1px solid red;">
					<h3 class="page-title"><?php echo $nhauthor_avatar;?>
<?php 
if ($nhauthor_id == $viewer_id) {
	echo '&nbsp;&nbsp;Hi, '.$nhauthor_name;
}
else {
	echo '&nbsp;&nbsp;'.$nhauthor_name.'&#39;s Profile';
}
?>
</h3>

<?php
//box around profile info
//then loop through content - guides, resources, blog posts, comments
// sidebar for public = get your own
// sidebar for private = edit profile and click to guides


?>


<?php
$args = array(
'author'  => $curauth_id,	
'posts_per_page' => -1,
'post_status' => 'publish',
'orderby' => 'date',
'order' => 'DESC'
);
$query1 = new WP_Query($args);
$post_count = $query1->found_posts;
if ($post_count > 0) :
?>		
					<p>View or edit your Neighborhow content. You can also edit your Settings using the links on the right.</p>
				</div><!--/ profile-welcome-->
<?php
while ($query1->have_posts()) : $query1->the_post();
$feat_id = $post->ID;
$featImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($feat_id), 'full');
?>
					<div class="feat-container">sdfkjsdlkj	
						</div>					
						<div class="feat-details">

						</div><!--/ feat-details-->
					</div><!--feat-container-->	
<?php 
endwhile;
else :
?>
					<p>Get started! Explore or create a guide!</p>
				</div><!--/ profile-welcome-->
<?php 
endif; // end if post count > 0
//endif; // end if logged in
?>			
							

			</div><!--/ profile-div-->
		</div><!--/ content-->
<?php get_sidebar('login-profile'); ?>		
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>	
					
					
					
					
					