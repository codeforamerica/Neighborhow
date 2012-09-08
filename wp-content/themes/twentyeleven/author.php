<?php get_header(); ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
// viewer
global $current_user;
$nh_viewer_id = $current_user->ID;

// author
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;
?>

<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<?php // if viewer = author
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
	<div class="feat-container">
<?php // guides only - link to edit view
/*$type = 'nh_guides';
$gdeargs = array(
	'author' => $nh_viewer_id,
	'post_type' => 'nh_guides',
	'post_status' => array('publish','draft','pending'),
	'paged' => $paged,
//	'posts_per_page' => -1,
//	'orderby' => 'date',
//	'order' => DESC
);
*/
query_posts( array( 'post_type' => array('post', 'nh_guides') ) );
?>

<h5 class="">Neighborhow Guides</h5>
<div class="">
<ul>
<?php while (have_posts()) : the_post(); 
echo the_title();
echo $post->ID;?>		

<?php //while( $gde_query->have_posts() ) : $gde_query->the_post();?>
<li>
<?php 
global $frmdb, $wpdb, $post;
$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post->ID ."'");
?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&amp;entry=<?php echo esc_attr($item_key);?>&frm_action=edit"><?php the_title();?></a> <span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php the_modified_date('j M Y');?> at <?php the_modified_date('g: i a');?></span>
</li>
<?php endwhile;?>
</ul>
</div>
<?php //endif;
wp_reset_query();
?>		
<?php // other post types - link to view
$type2 = 'post';
$postargs = array(
	'author' => $nh_viewer_id,
	'post_type' => $type2,
	'post_status' => array('publish','draft','pending'),
	'paged' => $paged,
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order' => DESC
);
$gdetemp = $wp_query; //assign ordinal for later use  
$post_query = null;
$post_query = new WP_Query($postargs); 
if ( $post_query->have_posts() ):
?>		
<h5 class="">Neighborhow Posts</h5>
<div class="">
<ul>
<?php while( $post_query->have_posts() ) : $post_query->the_post();?>
<li>
<a href="<?php echo the_permalink();?>"><?php the_title();?></a> <span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php the_modified_date('j M Y');?> at <?php the_modified_date('g: i a');?></span>	
</li>
<?php endwhile;?>
</ul>
</div>
<?php endif;
wp_reset_query();
?>

		</div>
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