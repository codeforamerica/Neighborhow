<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
<?php 
global $current_user;

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
echo $curauth;
?>

<?php 
//for creating links from ind pages
/*global $frmdb, $wpdb, $post;
 $item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post->ID ."'");?>
 <a href="/new-entry?action=edit&entry=<?php echo $item_key;?>">Edit entry</a>*/
?>


<?php
$args2 = array(
'post_type' => array('nh_guides','post'),
'posts_per_page' => '-1',
//'post_author' => $curauth->ID,
'post_status' => 'any',
'orderby' => 'date',
'order' => 'DESC',
);

$nh_query = new WP_Query( $args2 );
//var_dump($nh_query);

while ( $nh_query->have_posts() ) : $nh_query->the_post();
	echo '<li>lkejrlekj';
	the_title();
	echo '</li>';
endwhile;

wp_reset_postdata();


//get_posts($args);

?>
<?php if (have_posts()) : ?>
<?php while(have_posts()) : the_post();?>
	
		<h5 class="widget-title">Some links for authors</h5>
		<div class="widget-copy">
			<div><?php do_shortcode('[display-frm-data id=display-edit-guide]');?></div>
				
<?php //if (is_page('edit-guide')) {;?>				
	
<?php 
echo do_shortcode('[frm-entry-links id=9  field_key=guide-title logged_in=1 edit=1]'); 
?>
<?php //} ?>									
				<!--ul>
					<li><a href="<?php echo $app_url;?>/lostpwd" title="Forgot password">Link Two</a></li>
				</ul-->
<?php
endwhile;
endif;
?>			
		</div>			
	</div><!--/ widget-->

</div><!--/ sidebar-->