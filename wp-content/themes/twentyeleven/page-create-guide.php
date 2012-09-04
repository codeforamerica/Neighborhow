<?php
/*
Template Name: page-create-guide
*/

echo '<pre>';
print_r($_POST);
echo '</pre>';

echo '<pre>';
print_r($_FILES);
echo '</pre>';

echo '<pre>';
print_r($_FILES['item_meta']);
echo '</pre>';

echo '<pre>';
print_r($errors);
echo '</pre>';


// set $post_id to the id of the post you want to attach 
// these uploads to (or 'null' to just handle the uploads
// without attaching to a post)

/*if ($_FILES) {
	$test = $_FILES['item_meta'];
	var_dump($test);
  foreach ($test as $file => $array) {
	echo '<pre>files meta<br/>';
	print_r($test);
	echo 'named here : ';
	var_dump($test['name']);
	echo '</pre>';	
	
    $newupload = insert_attachment($test,$post_id);
    // $newupload returns the attachment id of the file that 
    // was just uploaded. Do whatever you want with that now.
  }
}
*/

?>
<?php get_header();?>
<div class="row-fluid row-breadcrumbs">
	<div class="wrapper">
		<div id="nhbreadcrumb">
<?php //nhow_breadcrumb(); ?>
		</div>
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->

<div class="row-fluid row-content">
	<div class="wrapper">	
		<div id="main">

<?php
$nhow_authorID = $posts[0]->post_author;
$nhow_postID = $post->ID;
$nhow_authorAlt = 'Photo of '.get_the_author(); 
echo get_avatar($nhow_authorID,30,'',$nhow_authorAlt);
echo '&nbsp;&nbsp;';
the_author_posts_link();
?>
<?php while ( have_posts() ) : the_post(); ?>

<?php echo do_shortcode('[formidable id=7]'); ?>

<?php the_content(); ?>

<?php endwhile;?>
		
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<!--script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/createguide.js"></script-->

<?php get_footer(); ?>		
