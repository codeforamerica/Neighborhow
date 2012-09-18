<?php /* Template Name: page-edit-guide */ 
// This WP PAGE doesnt know who the author of
// FRM Entries is or any info about FRM POSTS
// bc the author of this PAGE is Admin and the
// PAGE Post ID is the PAGE Post ID.
// We are using the URL to assume the FRM Entry Key
// From there we get the FRM Entry ID and from there
// we get the Entry's POST ID.

// Find out if errors exist so we dont show
// both errors and update msgs at the same time
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
<h3 class="page-title">Edit Your Content</h3>

<?php
// Get location
//$tmp = $_SERVER['REQUEST_URI'];
//$uri = parse_url($tmp);
//$base = $uri['query'];

// Get guide cat id
$guide_cat = get_category_id('guides');
$stories_cat = get_category_id('stories');
$resources_cat = get_category_id('resources');
$blog_cat = get_category_id('blog');

// Get current user
global $current_user;

// Get the entry info
$item_key = $_GET['entry'];
$item_id = nh_get_frm_key_id($item_key);
$item_post_id = nh_get_frm_id_post_id($item_id);
$entry_info = get_post($item_post_id);

$btn_preview = '<li style="float:right;margin-left:1em;"><a href="'.$app_url.'/?post_type=post&p='.esc_attr($item_post_id).'&preview=true" title="See what your Guide will look like" target="_blank"><button class="nh-btn-blue">Preview Guide</button></a></li>';
?>

<?php
$mypost = get_post($item_post_id);

if ($current_user->ID == $mypost->post_author AND is_user_logged_in()) {
// LOGGED IN AND IS AUTHOR
	if ($mypost->post_status == 'draft') {
		if ($_GET['ref'] == 'create') {	
			echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Thank you for writing a Neighborhow Guide!</strong><p>Your Guide has been saved as a Draft, so you can keep working on it until you&#39;re ready to publish.</div>';
			echo '<div class="block-instruct"><p class="instructions">When you&#39;re ready to publish this Neighborhow Guide, click "Publish Guide." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends!</p></div>';
			echo '<ul>';
			echo $btn_preview;
			echo '<li style="float:right;">';
			nh_frontend_delete_link($item_post_id);
			echo '</li>';
			echo '<li style="float:left;">';
			$button = nh_show_publish_button($item_post_id);
			echo '</li>';
			echo '</ul>';
			echo '<div style="clear:both;"></div>';
			echo '<div id="edit-gde"'.do_shortcode('[formidable id=9]').'</div>';
		}
		elseif ($_GET['ref'] == 'update') {	
			// if error dont show update msg
			if (isset($my_form_error)) { }
			else {
				echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to this Guide were saved!</strong></div>';
			}
				echo '<div class="block-instruct"><p class="instructions">When you&#39;re ready to publish this Neighborhow Guide, click "Publish Guide." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends!</p></div>';
			echo '<ul>';
			echo $btn_preview;
			echo '<li style="float:right;">';
			nh_frontend_delete_link($item_post_id);
			echo '</li>';
			echo '<li style="float:left;">';
			$button = nh_show_publish_button($item_post_id);
			echo '</li>';
			echo '</ul>';
			echo '<div style="clear:both;"></div>';
			echo '<div id="edit-gde"'.do_shortcode('[formidable id=9]').'</div>';
		}
		elseif (isset($_GET['action'])) {
			echo '<div class="block-instruct"><p class="instructions">When you&#39;re ready to publish this Neighborhow Guide, click "Publish Guide." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends!</p></div>';
			echo '<ul>';
			echo $btn_preview;
			echo '<li style="float:right;">';
			nh_frontend_delete_link($item_post_id);
			echo '</li>';
			echo '<li style="float:left;">';
			$button = nh_show_publish_button($item_post_id);
			echo '</li>';
			echo '</ul>';
//			echo '<div style="clear:both;"></div>';
			echo '<div id="edit-gde"'.do_shortcode('[formidable id=9]').'</div>';
		}
		// if user went to entry w/o &action
		elseif (!isset($_GET['action']) AND !isset($_GET['ref'])) {
			echo '<div class="block-instruct"><p class="instructions">Looking for your Neighborhow Guides? Use the menu on the right to select an item to edit.</p></div>';
		}	
	}

	elseif ($mypost->post_status == 'pending') {
		if ($_GET['ref'] == 'review') {
			echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>This Neighborhow Guide was submitted for review!</strong></div>';
			echo '<p class="instructions">Neighborhow Editors will quickly review your Guide. Then they&#39;ll email you when it&#39;s posted so you can share the link with your friends</p>';
			echo '<div class="block-instruct"><p class="instructions">Click "Preview" to see what it will look like when it&#39;s published. If you want to work on another Guide, select it from the list on the right.</p>';
			echo '<p><a href="'.$app_url.'/?post_type=post&p='.$item_post_id.'&preview=true" title="See what it will look like" target="_blank"><button class="nh-btn-blue">Preview Guide</button></a></p></div>';
		}
		elseif (isset($_GET['action'])) {
			echo '<div class="block-instruct"><p class="instructions"><strong>This Neighborhow Guide is being reviewed.</strong></p>';
			echo '<p class="instructions">Neighborhow Editors will email you when it&#39;s posted so you can share the link with your friends</p></div>';
			echo '<div class="block-instruct"><p class="instructions">Click "Preview" to see what it will look like when it&#39;s published. If you want to work on another Guide, select it from the list on the right.</p>';
			echo '<p><a href="'.$app_url.'/?post_type=post&p='.$item_post_id.'&preview=true" title="See what it will look like" target="_blank"><button class="nh-btn-blue">Preview Guide</button></a></p></div>';
		}
		// if user went to entry w/o &action
		elseif (!isset($_GET['action']) AND !isset($_GET['ref'])) {
			echo '<div class="block-instruct"><p class="instructions">Looking for your Neighborhow Guides? Use the menu on the right to select an item to edit.</p></div>';
		}
	}

	elseif ($mypost->post_status == 'publish') {
		if ($_GET['ref'] == 'update') {	
			// if error dont show update msg
			if (isset($my_form_error)) { }
			else {			
				echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to this Guide were saved!</strong></div>';
			}
//			echo '<div class="block-instruct"><p class="instructions"><strong>This <a href="'.get_permalink($item_post_id).'" title="View your Neighborhow Guide" target="_blank">Neighborhow Guide</a> has been published!</strong></p>';
			echo '<div class="block-instruct"><p>When you&#39;re ready to publish again, click "Publish Guide" to send it back to Neighborhow Editors for review.</p></div>';
			echo '<ul>';
			echo $btn_preview;
			echo '<li style="float:right;">';
			nh_frontend_delete_link($item_post_id);
			echo '</li>';
			echo '<li style="float:left;">';
			$button = nh_show_publish_button($item_post_id);
			echo '</li>';
			echo '</ul>';
			echo '<div style="clear:both;"></div>';
			echo '<div id="edit-gde"'.do_shortcode('[formidable id=9]').'</div>';
		}
		elseif (isset($_GET['action'])) {
			// if error dont show update msg
			if (isset($my_form_error)) { }
			else {
			echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>This <a href="'.get_permalink($item_post_id).'" title="View your Neighborhow Guide" target="_blank">Neighborhow Guide</a> has been published!</strong></div>';
			}
//			echo '<div class="block-instruct"><p class="instructions"><strong>This <a href="'.get_permalink($item_post_id).'" title="View your Neighborhow Guide" target="_blank">Neighborhow Guide</a> has been published!</strong></p>';
			echo '<div class="block-instruct"><p class="instructions">You can still make changes to this Guide. But when you click "Save Guide," the Guide will go back to "Draft" status and won&#39;t be visible to people while it&#39;s in progress.</p>';
			echo '<p>When you&#39;re ready to publish again, click "Publish Guide" to send it back to Neighborhow Editors for review.</p></div>';
			echo '<ul>';
			echo $btn_preview;
			echo '<li style="float:right;">';
			nh_frontend_delete_link($item_post_id);
			echo '</li>';
			echo '<li style="float:left;">';
			$button = nh_show_publish_button($item_post_id);
			echo '</li>';
			echo '</ul>';
			echo '<div style="clear:both;"></div>';
			echo '<div id="edit-gde"'.do_shortcode('[formidable id=9]').'</div>';
		}
		// if user went to entry w/o &action
		elseif (!isset($_GET['action']) AND !isset($_GET['ref'])) {
			echo '<div class="block-instruct"><p class="instructions">Looking for your Neighborhow Guides? Use the menu on the right to select an item to edit.</p></div>';
		}
	}
}
// LOGGED IN AND NOT AUTHOR
elseif ($current_user->ID != $mypost->post_author AND is_user_logged_in()) {
	$guideargs = array(
		'author' => $current_user->ID,
		'post_status' => array('pending','publish','draft'),
		'cat' => $guide_cat
	);
	$gde_query = new WP_Query($guideargs); 
	if ($_GET['ref'] == 'delete') {
		echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Your Guide has been deleted.</strong></div>';
	}
	if ($gde_query->have_posts()) {
		echo '<div class="block-instruct"><p class="instructions">Looking for your Neighborhow Guides? Use the menu on the right to select an item to edit.</p></div>';	
	}
	else {
		echo '<div>You haven&#39;t created any Neighborhow Guides yet. <a href="'.$app_url.'/create-guide" title="Create a Neighborhow Guide">Get started</a> now, or <a href="'.$app_url.'/guides" title="Explore Neighborhow Guides">explore other Guides</a> for inspiration.</div>';
	}		
}
// NOT LOGGED IN
elseif (!is_user_logged_in()) {
	echo '<div class="block-instruct"><p class="instructions">Please <a href="'.$app_url.'/signin" title="Sign In now">sign in</a> to edit content.</p>';
	echo '<p style="margin-top:1.5em;"><a title="Sign In now" href="'.$app_url.'/signin" class="nh-btn-blue">Sign In</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a title="Sign Up now" href="'.$app_url.'/register" class="nh-btn-blue">Sign Up</a></p></div>';
}
wp_reset_query();
?>
			</div><!--/ content-->	

<div id="sidebar-nh" class="sidebar-nh">
<div class="widget-side">

<?php
// VIEWER IS AUTHOR	
$pub_date = get_the_modified_date('j M Y');
if (is_user_logged_in()) {
	if ($current_user->ID == $mypost->post_author) {
		$count = custom_get_user_posts_count($current_user->ID,array(
			'post_type' =>'post',
			'post_status'=> array('publish','draft','pending'),
			'cat' => $guide_cat
			));
		if ($count > 0) {
			// Guides
			$guideargs = array(
				'author' => $current_user->ID,
				'post_status' => array('pending','publish','draft'),
				'cat' => $guide_cat
				);
			$guidequery = new WP_Query($guideargs);
			if ($guidequery->have_posts()) {
				echo '<h5>Neighborhow Guides</h5>';
				echo '<ul class="bullets-edit">';	
				while ($guidequery->have_posts()) {
					$guidequery->the_post();
					$post_key = nh_get_frm_entry_key($post->ID); ?>		
					<li class="bullets-edit"><a href="<?php echo $app_url;?>/edit-guide?entry=<?php echo $post_key;?>&action=edit" title="View <?php the_title();?>"><?php the_title(); ?></a>
	<?php
	$status = get_post_status();
	if ($status == 'publish') {
		$newstatus = 'Published';
		echo '<span>Status: '.$newstatus.' Last saved: '.$pub_date.'</span>';
	}
	if ($status == 'draft') {
		$newstatus = 'Draft';
		echo '<span>Status: '.$newstatus.' Last saved: '.$pub_date.'</span>';
	}
	if ($status == 'pending') {
		$newstatus = 'Pending Review';
		echo '<span class="pending">Submitted on '.$pub_date.' and pending review. When it&#39;s published, you&#39;ll be able to edit it again. <a href="'.$app_url.'/?post_type=post&p='.$post->ID.'&preview=true" title="See what it will look like" target="_blank">Preview</a> it here.</span>';
	}
	?>
					</li>
	<?php
				}
				echo '</ul>';
			}
			wp_reset_postdata();
	// TODO - when resources, blog, other content is
	// editable via front end, they will have their
	// own edit-X pages with a panel like this one.
	// TODO - are these 2 conditions the same??						
		}
	}

	// VIEWER IS NOT AUTHOR	
	// - show his content not the authors
	elseif ($current_user->ID != $mypost->post_author) {
		$count = custom_get_user_posts_count($current_user->ID,array(
			'post_type' =>'post',
			'post_status'=> array('publish','draft','pending'),
			'cat' => $guide_cat		
		));
		if ($count > 0) {
			// Guides
			$guideargs = array(
				'author' => $current_user->ID,
				'post_status' => array('pending','publish','draft'),
				'cat' => $guide_cat
				);
			$guidequery = new WP_Query($guideargs);
			if ($guidequery->have_posts()) {
				echo '<h5>Neighborhow Guides</h5>';
				echo '<ul class="bullets-edit">';	
				while ($guidequery->have_posts()) {
					$guidequery->the_post();
					$post_key = nh_get_frm_entry_key($post->ID); ?>		
					<li class="bullets-edit"><a href="<?php echo $app_url;?>/edit-guide?entry=<?php echo $post_key;?>&action=edit" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
	<?php
				}
				echo '</ul>';
			}
			wp_reset_postdata();
		}
	}
}
elseif (!is_user_logged_in()) { }
?>

</div><!--/ widget copy-->	
</div><!--/ widget-->
</div><!--/ sidebar-->			
			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		