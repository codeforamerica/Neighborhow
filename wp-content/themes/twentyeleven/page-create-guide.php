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
//ar_dump($test);
echo '</pre>';


echo '<pre>';
//print_r($errors);
echo '</pre>';


/*global $post;
foreach ($_FILES as $file => $array) {
	if (substr($file,0,5) == "step-") {			
		if (!empty($array['name'])) {
			$key_value = $file;
//			$field_name = $posted_field->name;
//			$field_id = $posted_field->id;
			
			$newupload = insert_attachment($key_value,$post_id);
			
			$tmp_field = '154';
			if ($key_value = $tmp_field) {
				echo 'yes';
//$_POST['frm_wp_post_custom']['154=step-image-0'] = $key_value;
			update_post_meta($post->ID,'step-image-0',$key_value);
			}
			
		}
	}
}
*/	


/*	global $post;
	$tmp = $_FILES;
	foreach ($tmp as $key => $value) {

		if (substr($key,0,5) == "step-") {			
			if (!empty($value['name'])) {			
							
				$key_value = $key;
				$field_name = $posted_field->name;
				$field_id = $posted_field->id;
					
// DO VALIDATION HERE
// THEN :			
				if ($key_value === $field_name) {
// update item_meta	- tmp processing ??
					$_POST['item_meta'][$field_id] = $key_value;
// update frm_wp_post_custom - inserts into backend view
					$tmp_field_name = $field_id.'='.$key_value;
					$_POST['frm_wp_post_custom'][$tmp_field_name] = $key_value;
				}				
			}
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

<?php echo do_shortcode('[formidable id=8]'); ?>

<?php the_content(); ?>

<?php endwhile;?>
		
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<!--script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/createguide.js"></script-->

<?php get_footer(); ?>		
