<?php
// TODO
//  redo as loop page and restrict to X items for each archive

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'alternate');

// INCLUDES
require(STYLESHEETPATH.'/lib/paths.php');
require(STYLESHEETPATH.'/lib/gen_functions.php');
require(STYLESHEETPATH.'/lib/breadcrumbs.php');

global $style_url;
global $app_url;
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

// CURRENT USER
global $current_user;
get_currentuserinfo();
$nh_user_id = $current_user->ID;
$nh_user_name = $current_user->display_name;
$nh_user_info = get_userdata($nh_user_id);
$nh_current_level = $current_user->user_level;


// CLASSES + KEYW
$bodyid = get_bodyid();
$links = 'current-item';
$genkeys ='Neighborhow, Discover and share what you need to make your city better. City improvement projects, urban improvement projects, tactical urbanism, neighbors, neighbor knowledge.';
$keytags = wp_get_post_tags($post->ID);	
$keyw = get_custom($post->ID,'keyw');
$keycities = wp_get_post_terms($post->ID,'nh_cities','orderby=name&order=DESC');

$keymeta = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); 
$keymeta = $keymeta->name;
//$metaTax = $metaTerm->taxonomy;
?>
<!DOCTYPE html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title('', true, 'right'); ?></title>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<meta name="description" content="Neighborhow - Discover and share what you need to make your city better. City improvement projects, urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.">
<meta name="author" content="Neighborhow">
<meta copyright="author" content="Neighborhow 2012-<?php echo date('Y');?>">
<meta name="keywords" content="<?php
if (is_home()) { //OK
	echo $genkeys;
}
elseif (is_single() AND $post->post_type === 'post') {
	if ($keyw) {
		echo $keyw;
	}
	if ($keytags) {
		foreach ($keytags as $keytag) {
		echo ', '.$keytag->name;
		}
	}
	echo ', '.$genkeys;
}
elseif (is_single() AND $post->post_type === 'nh_guides') {
	if ($keyw) {
		echo $keyw;
	}
	if ($keytags) {
		foreach ($keytags as $keytag) {
		echo ', '.$keytag->name;
		}
	}
	if ($keycities) {
		foreach ($keycities as $keycity) {
		echo ', '.$keycity->name;
		}
	}
	echo ', '.$genkeys;
}
elseif (is_archive() AND isset($keymeta)) { //OK
	if ($keymeta) {
		echo $keymeta;
	}
	echo ', '.$genkeys;
}
else {echo $genkeys;}
?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<?php // images ?>
<link rel="shortcut icon" href="<?php echo $style_url;?>/images/favicon.ico">
<link rel="image_src" type="image/jpeg" href="<?php echo $style_url;?>/images/logo_blog.jpg"/>

<?php // MEDIA QUERIES.JS (fallback) ?>
<!--[if lt IE 9]>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
	
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head();?>

<?php // STYLESHEETS ?>
<link rel="stylesheet" href="<?php echo $style_url; ?>/lib/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $style_url; ?>/style.css">

<?php // fonts ?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Bitter:400,700,400italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic' rel='stylesheet' type='text/css'>

<!--share this here-->
<!--google here-->

<?php // PNG FIX for IE6 ?>
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/pngfix/supersleight-min.js"></script>
<![endif]-->

</head>

<body <?php body_class();?> id="<?php echo $bodyid;?>">
		
<div class="row-fluid row-header">
	<div class="wrapper">
		<div id="banner">
			<div id="brand">	
				<div id="site-title"><a class="home-brand" href="<?php echo $app_url;?>" title="Go to the home page" rel="Home"><img class="logo" src="<?php echo $style_url;?>/images/logo_circle.png" height="70" alt="Neighborhow logo" /><h3 class="site-title">Neighborhow</h3></a>
				</div>	
				<div id="menu-header">
					<ul class="header-elements">
						<li class="header-element header-search <?php if ($bodyid == "search") echo $links; ?>"><a title="Search Neighborhow" href="#" ><?php get_search_form();?></a></li>
					</ul>
				</div>								
			</div><!--/ brand-->
		</div><!--/ banner-->	
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->	

<div class="row-fluid row-nav">
	<div class="wrapper">
		<div id="nhnavigation" class="nav-container">
			<div class="nhnav">
				<ul id="nhnav-items" class="">
					<li class="nhnav-item dropdown <?php 
$findit = 'cities';
$pos = strpos($bodyid,$findit);
if ($pos == "cities")
echo $links; 
?>" id="menu1"><a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">Cities <b class="caret"></b></a>
						<ul class="dropdown-menu">
<?php
$cities = get_terms('nh_cities');
foreach ($cities as $city) {
echo '<li class="nhnav-item sub-menu">';
echo '<a title="View all Guides and Resources for '.$city->name.'" href="'.get_term_link($city->slug,'nh_cities').'">'.$city->name.'</a>';
echo '</li>';
}
?>	
						</ul>
					<li class="nhnav-item <?php if ($bodyid == "guides") echo $links; ?>"><a title="View all Neighborhow Guides" href="<?php echo $app_url;?>/guides">Guides</a></li>	
					<li class="nhnav-item <?php if ($bodyid == "stories") echo $links; ?>"><a title="View all Neighborhow Stories" href="<?php echo $app_url;?>/stories">Stories</a></li>
					<li class="nhnav-item <?php if ($bodyid == "resources") echo $links; ?>"><a title="View all Neighborhow Resources" href="<?php echo $app_url;?>/resources">Resources</a></li>
					<li class="nhnav-item <?php if ($bodyid == "blog") echo $links; ?>"><a title="View Neighborhow Blog" href="<?php echo $app_url;?>/blog">Blog</a></li>
<?php
if (is_user_logged_in()) {
?>

					<li class="nhnav-item nhnav-avatar <?php if ($bodyid == "profile") echo $links; ?>"><a title="View your Neighborhow profile" href="<?php echo $app_url;?>/author/<?php echo $nh_user_name;?>">
<?php
$nh_avatar_alt = 'Photo of '.$nh_user_name;
$nh_avatar = get_avatar($nh_user_id, '18','identicon',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=18&h=18&q=100&zc=1">';

}
else {
echo $nh_avatar;
}
?> <?php echo $nh_user_name;?></a>
					</li>
					<li class="nhnav-item"><a title="Sign out of Neighborhow" href="<?php echo wp_logout_url('home_url()');?>">Sign Out</a></li>
<?php
}
else {
?>
					<li class="nhnav-item <?php if ($bodyid == "signin") echo $links; ?>"><a title="Sign In now" href="<?php echo $app_url;?>/login" >Sign In</a></li>
					<li class="nhnav-item <?php if ($bodyid == "signup") echo $links; ?>"><a title="Sign Up now" href="<?php echo $app_url;?>/register" >Sign Up</a>
					</li>
<?php
}
?>	
				</ul>
			</div>
		</div><!--/ nhnavigation-->
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->

<div class="row-fluid row-promo">
	<div class="wrapper">	
		<div id="site-promo">
			<h2>Neighborhow makes it easy to find and share ways to improve your neighborhood.</h2>
			<p class="buttons">
			<a href="<?php echo $app_url;?>/guides" class="nh-btn-orange">Start Exploring</a><br/>
			<a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Create a Guide</a>
			</p>
		</div><!--/ site-promo-->
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
	
<div class="row-fluid row-content">
	<div class="wrapper">
		<div id="main">
			<div id="content">
				<div class="hfeed">
<?php 
// sticky posts show automatically
// dont need to query for them 
$sticky_id = $post->ID;
$do_not_duplicate = $sticky_id;
if ($sticky_id) {
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($sticky_id), 'full');	
}
?>					
					<div id="post-<?php echo $sticky_id;?>" class="hentry sticky sticky-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>"><img class="sticky-img" src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=636&h=320&q=100&zc=1" alt="Photo of <?php echo the_title();?>" alt="Photo of <?php echo the_title();?>" class="single-thumbnail featured" /></a>	

						<div class="entry-details">
							<div class="entry-txt" style="">
								<p class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></p>
								<p class="entry-excerpt">
<?php 
$entry_exc = get_the_excerpt();
$entry_exc = trim_by_chars($entry_exc,'66',' &#187;');
echo $entry_exc;?><br/>
								<span class="nh-meta"><?php echo the_date('j M Y');?></span>
								</p>
							</div>
							<div class="entry-author">
								<p class="entry-avatar"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>">
<?php 
$nh_author_name = get_the_author();
$nh_author_alt = 'Photo of '.$nh_author_name;
$nh_author_id = get_the_author_meta('ID');
$nh_author_avatar = get_avatar($nh_author_id, '36','identicon',$nh_author_alt);
$nh_author_photo_url = nh_get_avatar_url($nh_author_avatar);
if ($nh_author_photo_url) {
	echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_author_photo_url.'&w=36&h=36&q=100&zc=1">';
}
else {
	echo $nh_author_avatar;
}
?> <?php //echo $nh_user_name;?>


							
								</a></p>
								<p class="author vcard author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><span>by</span> <?php the_author();?></a></p>
							</div>										
						</div><!--/ entry-details-->
<?php
//endif;
//endwhile;
//wp_reset_query();
?>						
					</div><!--/ sticky-div-->
					<div class="break break-feature"></div>					
<?php
$args2 = array(
'posts_per_page' => 4,
'post_status' => 'publish',
'cat' => '-1', //exclude blog posts
'orderby' => 'date',
'order' => 'DESC'
);
$query2 = new WP_Query($args2);
while ( $query2->have_posts() ) : $query2->the_post();
$feat_id = $post->ID; 
if( $feat_id == $do_not_duplicate ) continue;
$featImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($feat_id), 'full');
?>
					<div class="feat-container">
						<div id="post-<?php echo $feat_id;?>" class="hentry feat-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $featImgSrc[0];?>&w=150&h=150&q=100&zc=1" alt="Photo of <?php echo the_title();?>" class="thumbnail featured" /></a>	
						</div>					
						<div class="feat-details">
							<p class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></p>
							<div class="feat-summary"><?php echo the_excerpt();?>											
							</div><!--/ feat-summary-->
							<p class="author vcard author-link">
								<span class="byline"><?php echo the_date('j M Y');?></span>
								&nbsp;&middot;&nbsp;<span class="byline">by</span> <a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><?php the_author();?></a>
			
<?php 
if (get_the_terms($feat_id,'nh_cities')) {
	$cities = get_the_terms($feat_id,'nh_cities');
	$countcity = count($cities);
	echo '&nbsp;&middot;&nbsp;<span class="byline">for</span> ';
	$i = 1;
	foreach ($cities as $city) {
		$city_name = $city->name;		
		$city_url = get_term_link($city->slug,'nh_cities');		
		if ($i < $countcity) {
			echo '<a rel="tag" href="'.esc_url($city_url).'" title="'.$city_name.'">'.$city_name.'</a> + ';
		}
		else {		
			echo '<a rel="tag" href="'.esc_url($city_url).'" title="'.$city_name.'">'.$city_name.'</a>';
		}
		$i++;
	}
}
?>							
			 
<?php 
$feat_cats = get_the_category($feat_id);
$countcats = count($feat_cats);
if ($countcats > 0) {
	echo '&nbsp;&middot;&nbsp;<span class="byline">in</span> ';
	$j = 1;
	foreach ($feat_cats as $feat_cat) {
		$feat_cat_id = get_cat_ID($feat_cat->cat_name);
		$feat_cat_url = get_category_link($feat_cat_id);

		if ($j < $countcats) {
			echo '<a rel="tag" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a> + ';
		}
		else {		
			echo '<a rel="tag" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a>';
		}
		$j++;
	}
}
?> 
								</span></p> 	
						</div><!--/ feat-details-->
					</div><!--/ feat-container-->
<?php 
endwhile;  
?>					
				</div><!-- .hfeed -->

				<!--div class="pagination loop-pagination"><span class='page-numbers current'>1</span>
<a class='page-numbers' href='http://devpress.com/demo/origin/page/2/'>2</a>
<a class="next page-numbers" href="http://devpress.com/demo/origin/page/2/">Next &rarr;</a></div-->

			</div><!--/ content -->

			<div id="sidebar-nh" class="sidebar-nh">
				<div class="widget-side">
					<h5 class="widget-title">Make Neighborhow Better</h5>
					<div class="widget-copy">
						<ul>
							<li><a href="<?php echo $app_url;?>/contact" title="Submit a story">Submit a story</a> about something you&#39;ve done to improve your neighborhood</li>							
							<li>Improve Neighborhow by <a href="<?php echo $app_url;?>/feedback" title="Give feedback">giving us your feedback</a></li>
							<li>Help decide what&#39;s next for Neighborhow &#8212; <a href="<?php echo $app_url;?>/blog" title="Help decide what&#39;s next for Neighborhow">vote on the features and content you want</a></li>
							<li>If you&#39;re a city who wants Neighborhow in your city, <a href="<?php echo $app_url;?>/contact" title="Contact us">contact us</a>!</li>
							<!--li>Other questions or comments? <a href="<?php echo $app_url;?>/contact" title="Contact us">Contact us</a>.</li-->
						</ul>
					</div>
				</div><!--/ widget-->							
				<div class="widget-side">
					<h5 class="widget-title">About Neighborhow</h5>
					<div class="widget-copy">
						<p>Nam tincidunt pharetra odio, vel pulvinar magna eleifend eu. Etiam faucibus tellus dui, consequat volutpat nunc. Nullam lobortis vulputate leo eget pulvinar. Morbi venenatis ultricies lorem, sit amet facilisis nibh porta sed.</p><p>Nulla suscipit lobortis enim, ac congue lorem fringilla. <a href="<?php echo $app_url;?>/about" title="Read more about what Neighborhow is">read the whole story ></a></p>
					</div>
				</div><!--/ widget-->
<?php
if (is_user_logged_in()) :
?>			
				<div class="widget-side">
					<div class="widget-buttons">
						<a href="#" class="nh-btn-blue">Sign In</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="#" class="nh-btn-blue">Sign Up</a>
					</div>
				</div><!--/ widget-->
<?php
endif;
?>				

			</div><!--/ sidebar-->
		</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->

<div class="row-fluid row-footer">
	<div class="wrapper">	
		<div id="footer">
			<div class="span4 odd clearfix">
				<h3>About Neighborhow</h3>
				<ul>
					<li><a class="noline footer-link" title="See how Neighborhow works" href="<?php echo $app_url;?>/about">How It Works</a></li>
					<li><a class="noline footer-link" title="Read Frequently Asked Questions" href="<?php echo $app_url;?>/faqs">Frequently Asked Questions</a></li>	
					<li><a class="noline footer-link" title="See our Terms and Conditions" href="<?php echo $app_url;?>/terms">Terms &amp; Conditions</a></li>
					<li><a class="noline footer-link" title="Read our Privacy Policy" href="<?php echo $app_url;?>/privacy">Privacy Policy</a></li>														
				</ul>
			</div><!-- /span4 -->

			<div class="span4 middle clearfix">
				<h3>Contact</h3>
				<ul>
					<li><a class="noline footer-link" title="Get in touch with us" href="<?php echo $app_url;?>/contact">Email Us</a></li>
						<li><a class="noline footer-link" title="Find out what we&#39;ve been up to" href="<?php echo $app_url;?>/blog">Read the Blog</a></li>					
					<li><p class="footerp">Find us on:</p></li>
					<li>
						<ul class="sub-footer">
							<li><a target="_blank" title="Follow Neighborhow on Twitter" href="https://twitter.com/Neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/twitter.png" alt="Twitter logo" width="26" /></a></li>
							<li><a target="_blank" title="Like Neighborhow on Facebook" href="http://www.facebook.com/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/fb.png" alt="Facebook logo" width="26" /></a></li>
							<li><a target="_blank" title="Visit Neighborhow on Github" href="https://github.com/codeforamerica/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/github.png" alt="Github logo" width="26" /></a></li>
						</ul>
					</li>
				</ul>			
			</div><!-- /span4 -->
			<div class="span4 odd clearfix partnerul">
				<h3>Partners</h3>
				<ul>
					<li><p class="footerp">Neighborhow is a collaboration between the <a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia">City of Philadelphia</a> and <a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America">Code for America</a>.</p></li>
					<li class="partners"><a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia"><img width="70" src="<?php echo $style_url;?>/images/logo_phl.png" alt="City of Philadelphia logo"></a></li>	
					<li class="partners cfa"><a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America"><img width="70" src="<?php echo $style_url;?>/images/logo_cfa.png" alt="Code for America logo"></a></li>														
				</ul>
			</div><!-- /span4 -->			
		</div><!-- #footer -->
	</div><!--/ wrapper -->
</div><!--/ row-fluid -->
<?php wp_footer();?>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-collapse.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-transition.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-alert.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-modal.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-dropdown.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-scrollspy.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-tab.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-tooltip.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-popover.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-button.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-carousel.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-typeahead.js"></script>
<script src="<?php bloginfo('stylesheet_directory');?>/lib/js/application.js"></script>


<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fancybox/jquery.fancybox-1.3.4.pack.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fitvids/jquery.fitvids.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/footer-scripts.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/library/js/drop-downs.js?ver=20110920'></script>

<script>
$(document).ready(function() {
/*	$('.fancybox').fancybox({
		autosize:false,
		height:340,
		width:500,
		helpers : {
			title : null            
			}
	});
*/
	$('.dropdown-toggle').dropdown();
		
// STOP HERE		
});
</script>

</body>
</html>