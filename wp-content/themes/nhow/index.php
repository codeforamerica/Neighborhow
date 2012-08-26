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
<div id="container" style="">
	
<div class="row-fluid" style="background:#eee;border:1px solid red;">
	<div class="wrap" style="border:1px solid blue;">
		<div id="header" style="">
			<div id="branding" style="">	
				<h1 id="site-title"><a class="home-brand" href="<?php echo $app_url;?>" title="Go to the home page" rel="Home"><img class="logo" src="<?php echo $style_url;?>/images/logo_circle.png" height="70" alt="Neighborhow logo" /><p class="site-title">Neighborhow</p></a>
				</h1>	
				<div id="menu-header" style-"border:3px solid red !important;">
					<ul class="header-elements">
						<li class="header-element header-search <?php if ($bodyid == "search") echo $links; ?>"><a title="Search Neighborhow" href="#" ><?php get_search_form();?></a></li>
					</ul>
				</div>								
			</div><!-- #branding -->
		</div><!-- #header -->	
	</div><!--/ wrap-->
</div><!--/ row-fluid-->	

<div class="row-fluid" style="background:blue;">
	<div class="wrap">
		<div id="menu-primary" class="menu-container">
			<div class="menu">
				<ul id="menu-primary-items" class="">
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
$nh_avatar = get_avatar($nh_user_id, '22','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=22&h=22&q=100&zc=1">';

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
		</div><!-- #menu-primary .menu-container -->
	</div><!--/ wrap-->
</div><!--/ row-fluid-->

<div class="row-fluid" style="background:#fcfcfc;">
	<div class="wrap">	
		<h2 id="site-description"><span>Minimal and elegant WordPress theme with responsive layout. Optimized for mobile browsing. Free to download and use.</span></h2>
	</div><!--/ wrap-->
</div><!--/ row-fluid-->
	
<div class="row-fluid" style="background:green;">
	<div class="wrap">
		<div id="main">
			<div id="content">
				<div class="hfeed">
					<div id="post-60" class="hentry post publish post-1 odd author-galins sticky category-articles post_tag-css post_tag-development post_tag-wordpress"><a href="http://devpress.com/demo/origin/sticky-post/" title="Sticky Post"><img src="http://devpress.com/demo/origin/files/2012/01/Depositphotos_8562658_XXL-636x310.jpg" alt="Sticky Post" class="single-thumbnail featured" /></a>							
						<div class="sticky-header">
							<h2 class="entry-title"><a href="http://devpress.com/demo/origin/sticky-post/" title="Sticky Post" rel="bookmark">Sticky Post</a></h2>
							<div class="byline"><abbr class="published" title="Friday, January 6th, 2012, 2:48 pm">January 6, 2012</abbr> &middot; by <span class="author vcard"><a class="url fn n" href="http://devpress.com/demo/origin/author/galins/" title="Adriana Louvier">Adriana Louvier</a></span> &middot; in <span class="category"><a href="http://devpress.com/demo/origin/category/articles/" rel="tag">Articles</a></span> 
							</div>										
						</div><!-- .sticky-header -->

						<div class="entry-summary">
							<p>Turpis et ridiculus nec, tempor elementum amet aliquet rhoncus, pulvinar mid. Tincidunt montes, arcu, adipiscing a vel, adipiscing adipiscing! Amet! Sociis, cursus lectus, amet turpis aliquam sagittis! Rhoncus nisi! Augue, elementum. Ac, lorem vel? Adipiscing non duis elementum, nunc. Integer?&#8230;</p>										
						</div><!-- .entry-summary -->
					</div><!-- .hentry -->

					<div id="post-108" class="hentry post publish post-2 even alt author-galins category-blog post_tag-development post_tag-jquery post_tag-video post_tag-wordpress"><a href="http://devpress.com/demo/origin/responsive-video/" title="Responsive Video"><img src="http://devpress.com/demo/origin/files/2012/01/video_responsive-150x150.jpg" alt="Responsive Video" class="thumbnail featured" /></a>							
						<div class="sticky-header">
							<h2 class="entry-title"><a href="http://devpress.com/demo/origin/responsive-video/" title="Responsive Video" rel="bookmark">Responsive Video</a></h2>
							<div class="byline"><abbr class="published" title="Tuesday, January 17th, 2012, 1:54 pm">January 17, 2012</abbr> &middot; by <span class="author vcard"><a class="url fn n" href="http://devpress.com/demo/origin/author/galins/" title="Adriana Louvier">Adriana Louvier</a></span> &middot; in <span class="category"><a href="http://devpress.com/demo/origin/category/blog/" rel="tag">Blog</a></span> </div>										
						</div><!-- .sticky-header -->
						<div class="entry-summary">
							<p>Duis platea risus elementum in tortor parturient sed, pulvinar dignissim parturient a proin risus elementum sed velit natoque pid vel nunc in non, enim scelerisque turpis. Aenean mauris lundium, turpis massa diam eros nisi facilisis. Ultrices integer augue. Lacus turpis&#8230;</p>								
						</div><!-- .entry-summary -->
					</div><!-- .hentry -->

					<div id="post-75" class="hentry post publish post-3 odd author-galins category-articles post_tag-css post_tag-design post_tag-typography"><a href="http://devpress.com/demo/origin/typography/" title="Typography"><img src="http://devpress.com/demo/origin/files/2012/01/Depositphotos_4596618_XXL-150x150.jpg" alt="Typography" class="thumbnail featured" /></a>							
						<div class="sticky-header">
							<h2 class="entry-title"><a href="http://devpress.com/demo/origin/typography/" title="Typography" rel="bookmark">Typography</a></h2>
							<div class="byline"><abbr class="published" title="Friday, January 6th, 2012, 4:04 pm">January 6, 2012</abbr> &middot; by <span class="author vcard"><a class="url fn n" href="http://devpress.com/demo/origin/author/galins/" title="Adriana Louvier">Adriana Louvier</a></span> &middot; in <span class="category"><a href="http://devpress.com/demo/origin/category/articles/" rel="tag">Articles</a></span> </div>										
						</div><!-- .sticky-header -->
						<div class="entry-summary">
							<p>Et et nec porttitor eu. Mus ac eu, proin cum tortor, ac vel et! Elit porttitor! Scelerisque turpis, sit nascetur integer, penatibus tempor. Cum proin, augue in nunc duis, pellentesque nec tincidunt in ac aliquam, nisi lectus ac facilisis urna&#8230;</p>										
						</div><!-- .entry-summary -->
					</div><!-- .hentry -->
				</div><!-- .hfeed -->

				<div class="pagination loop-pagination"><span class='page-numbers current'>1</span>
<a class='page-numbers' href='http://devpress.com/demo/origin/page/2/'>2</a>
<a class="next page-numbers" href="http://devpress.com/demo/origin/page/2/">Next &rarr;</a></div>
			</div><!-- #content -->

			<div id="sidebar-primary" class="sidebar">
				<div id="hybrid-search-2" class="widget search widget-search">
					<div class="widget-wrap widget-inside">
						<h3 class="widget-title">Search</h3>			
						<div class="search">
<form method="get" class="search-form" action="http://devpress.com/demo/origin/">
<div>
<input class="search-text" type="text" name="s" value="Search this site..." onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />
<input class="search-submit button" name="submit" type="submit" value="Search" />
</div>
</form><!-- .search-form -->
						</div><!-- .search -->
					</div>
				</div>
				<div id="text-2" class="widget widget_text widget-widget_text">
					<div class="widget-wrap widget-inside">			
						<div class="textwidget">
							<p>Tincidunt tristique est habitasse sagittis tempor rhoncus natoque lorem, non dapibus scelerisque tincidunt, ac, ultricies montes etiam sagittis magna magna aliquam enim proin adipiscing ridiculus placerat in, amet eu, platea nascetur, sit, non nec dignissim! </p>
<p>Lundium porttitor porta sociis, nisi sagittis tincidunt amet amet et sagittis placerat et lundium. Proin? Duis turpis ut egestas cursus.</p>
						</div>
					</div>
				</div>
			</div><!-- #sidebar-primary .aside -->
		</div><!-- #main -->		
	</div><!--/ wrap-->
</div><!--/ row-fluid-->

<div class="row-fluid" style="background:blue;">
	<div class="wrap">	
		<div id="footer">
			<p class="copyright">Copyright &#169; 2012 <a class="site-link" href="http://devpress.com/demo/origin" title="Origin" rel="home"><span>Origin</span></a></p>
			<p class="credit">Powered by <a class="wp-link" href="http://wordpress.org" title="State-of-the-art semantic personal publishing platform"><span>WordPress</span></a> and <a class="theme-link" href="http://devpress.com/shop/origin/" title="Origin WordPress Theme"><span>Origin</span></a></p>
		</div><!-- #footer -->
	</div><!--/ wrap -->
</div><!--/ row-fluid -->


<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fancybox/jquery.fancybox-1.3.4.pack.js?ver=1.0'></script>
<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fitvids/jquery.fitvids.js?ver=1.0'></script>
<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/footer-scripts.js?ver=1.0'></script>
<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/library/js/drop-downs.js?ver=20110920'></script>

</body>
</html>