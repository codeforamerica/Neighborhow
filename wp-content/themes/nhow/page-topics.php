<?php /* Template Name: page-topics*/ ?>
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="content">

				<h3 class="page-title">Neighborhow Topics</h3>
	
				<div id="list-ideas">
					<ul class="list-ideas">			
<?php 
// Get the tags
$tag_args = array(
	'pad_counts' => true, 
	'get' => 'all',
	'post_status' => 'publish',
	'orderby' => 'date',
	'cat' => $cat_id
);
$tags = get_terms('post_tag',$tag_args);
foreach ($tags as $tag) {
	$tag_count = $tag->count;
	if ($tag_count) {
		echo '<li class="nhline" style="margin:.75em 0 .75em 0;border-top:1px solid #ccc;padding-top:1em;">';
		echo '<a class="nhline" href="'.$app_url.'/topics/'.$tag->slug.'" title="View all content tagged as '.$tag->name.'">'.$tag->name.'</a>';
		if ($tag_count == '1') {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$tag_count.'&nbsp;Guide</span></span>';
		}
		elseif ($tag_count > 1) {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$tag_count.'&nbsp;Guides</span></span>';
		}
	}
}
// TODO - count other post categories also
?>
					</ul>			
				</div>
								
			</div><!--/ content-->
<?php get_sidebar('misc');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>