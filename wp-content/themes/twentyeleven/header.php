<?php
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
$nh_user_info = get_userdata($nh_user_id);

$nh_user_name = $current_user->first_name.' '.$current_user->last_name;
$nh_user_display_name = $current_user->display_name;
if ($nh_user_name === ' ') {
	$nh_user_name = $nh_user_display_name;
}
else {
	$nh_user_name = $nh_user_name;
}
// CLASSES + KEYW
$bodyid = get_bodyid();
$links = 'current-item';

?><!DOCTYPE html>
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
<meta name="viewport" content="width=device-width; initial-scale=1.0">

<title><?php wp_title('Neighborhow &#187; ', true, 'left'); ?></title>

<meta name="description" content="Neighborhow makes it easy to find and share ways to improve your neighborhood. Make your city better with urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.">
<meta name="author" content="Neighborhow">
<meta copyright="author" content="Neighborhow 2012-<?php echo date('Y');?>">
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

<body <?php body_class('no-js'); ?> id="<?php echo $bodyid;?>">

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
echo '<li class="nhnav-item sub-menu ';
if ($bodyid == 'cities '.$city->name) {
	echo $links;
}
echo '">';
echo '<a title="View all Guides and Resources for '.$city->name.'" href="'.get_term_link($city->slug,'nh_cities').'">'.$city->name.'</a>';
echo '</li>';
}
?>	
						</ul>
					<li class="nhnav-item <?php if ($bodyid == "guides") echo $links; ?>"><a title="View all Neighborhow Guides" href="<?php echo $app_url;?>/guides">Guides</a></li>	
					<!--li class="nhnav-item <?php if ($bodyid == "stories") echo $links; ?>"><a title="View all Neighborhow Stories" href="<?php echo $app_url;?>/stories">Stories</a></li-->
					<li class="nhnav-item <?php if ($bodyid == "resources") echo $links; ?>"><a title="View all Neighborhow Resources" href="<?php echo $app_url;?>/resources">Resources</a></li>
					<li class="nhnav-item <?php if ($bodyid == "blog") echo $links; ?>"><a title="View Neighborhow Blog" href="<?php echo $app_url;?>/blog">Blog</a></li>
<?php
if (is_user_logged_in()) {
?>
					<li id="menu2" class="nhnav-item nhnav-avatar dropdown <?php if ($bodyid == "profile" OR $bodyid == "settings") echo $links; ?>"><a class="dropdown-toggle" data-toggle="dropdown" title="View your Neighborhow profile" href="#menu2">
<?php
$nh_avatar_alt = 'Photo of '.$nh_user_display_name;
$nh_avatar = get_avatar($nh_user_id, '18','identicon',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
	echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=18&h=18&q=100&zc=1">';
}
else {
	echo $nh_avatar;
}
?> <?php  echo $nh_user_display_name;?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nhnav-item sub-menu <?php if ($bodyid == "profile") echo $links; ?>"><a href="<?php echo $app_url;?>/author/<?php echo $current_user->user_login;?>" title="Your profile">Profile</a></li>
							<li class="nhnav-item sub-menu <?php if ($bodyid == "settings") echo $links; ?>"><a href="<?php echo $app_url;?>/settings" title="Settings">Settings</a></li>							
							<li class="nhnav-item sub-menu"><a href="<?php echo wp_logout_url('home_url()');?>" title="Your account">Sign Out</a></li>							
						</ul>
					</li>
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