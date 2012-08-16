<?php
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'alternate');

//includes
/*
require(STYLESHEETPATH.'/lib/paths.php');
require(STYLESHEETPATH.'/lib/nhow_functions.php');
require(STYLESHEETPATH.'/lib/breadcrumbs.php');
*/

//keywords + misc
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
//$bodyid = get_bodyid();
$links = 'active';
$generalKeys ='Neighborhow - find what you need to make your city better. Discover and share information about city improvement projects, urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.';
$metaTerm = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
$metaTerm = $metaTerm->name;
$metaTax = $metaTerm->taxonomy;

//viewer + content owner
global $current_user;
get_currentuserinfo();
$nh_user_id = $current_user->ID;
$nh_user_name = $current_user->display_name;
$nh_avatar_alt = 'Photo of '.$nh_user_name;
$nh_avatar = get_avatar($nh_user_id, 24,'',$nh_avatar_alt);
$nh_user_info = get_userdata($nh_user_id);
$nh_current_level = $current_user->user_level;
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta name="viewport" content="width=device-width,initial-scale=1" />

<title>Neighborhow</title>
<meta name="description" content="Neighborhow - find what you need to make your city better. Discover and share information about city improvement projects, urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.">
<meta name="author" content="Neighborhow">
<meta copyright="author" content="Neighborhow 2012-<?php echo date('Y');?>">
<meta name="keywords" content="<?php
if ( is_single()) {
	$keyw = get_custom($post->ID,'keyw');
	echo $keyw.' '.$metaTerm.', '.$generalKeys;
}
elseif (is_page() OR isset($metaTerm)) {
	echo $generalKeys.', '.$metaTerm;
}
else { echo $generalKeys; }
?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $style_url;?>/lib/foundation/stylesheets/foundation.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $app_url;?>/style.css" />

<link rel="shortcut icon" href="<?php echo $style_url;?>/images/favicon.ico">
<link rel="image_src" type="image/jpeg" href="<?php echo $style_url;?>/images/logo_blog.jpg"/>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php
if ( is_singular() && get_option( 'thread_comments' ) )
wp_enqueue_script( 'comment-reply' );
wp_head();
?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>

<!--share this here-->
<!--google here-->

<!-- PNG FIX for IE6 -->
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/pngfix/supersleight-min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
	
<div class="container">

	<div class="row header">
		<div class="three columns brand">
			<a href="<?php bloginfo('url');?>"><img height="70" src="<?php echo $style_url;?>/images/logo_circle.png" alt="Neighborhow logo"><p class="site-title">Neighborhow</p></a>
		</div>
		
		<div class="nine columns">
			<ul class="nav-bar">
				<li class="has-flyout"><a href="#">Cities</a>
					<a href="#" class="flyout-toggle"><span> </span></a>
					<ul class="flyout">
						<li><a href="#">Sub Nav 1</a></li>
						<li><a href="#">Sub Nav 2</a></li>
						<li><a href="#">Sub Nav 3</a></li>
					</ul>
				</li>				
				<li class="active"><a href="#">Guides</a></li>
				<li><a href="#">Resources</a></li>
				<li><a href="#">Stories</a></li>
				<li><a href="#"><?php get_search_form(); ?></a></li>				
				<li><a href="#">Sign In</a></li>				
			</ul>
		</div>
	</div>
	
<?php 
$args_menu = array(
	'theme_location'  => '',
	'menu'            => 'Nhow Menu', 
	'container'       => 'li', 
	'container_class' => 'menu-{menu slug}-container', 
	'container_id'    => '',
	'menu_class'      => 'menu', 
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);

//wp_nav_menu($args_menu); ?>