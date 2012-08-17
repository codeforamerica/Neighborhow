<?php
// TODO
//  redo as loop page and restrict to X items
// fix all stuff not working here
// fix nav menu

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'alternate');

$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

//includes
/*
require(STYLESHEETPATH.'/lib/paths.php');
require(STYLESHEETPATH.'/lib/nhow_functions.php');
require(STYLESHEETPATH.'/lib/breadcrumbs.php');
*/

//classes + keywords
/*
$bodyid = get_bodyid();
$links = 'active';
$generalKeys ='Neighborhow - find what you need to make your city better. Discover and share information about city improvement projects, urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.';
$metaTerm = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
$metaTerm = $metaTerm->name;
$metaTax = $metaTerm->taxonomy;
*/

//viewer + content owner
global $current_user;
get_currentuserinfo();
$nhow_user_id = $current_user->ID;
$nhow_user_name = $current_user->display_name;
$nhow_avatar_alt = 'Photo of '.$nhow_user_name;
$nhow_avatar = get_avatar($nhow_user_id, 24,'',$nhow_avatar_alt);
$nhow_user_info = get_userdata($nhow_user_id);
$nhow_current_level = $current_user->user_level;

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
<meta name="description" content="Neighborhow - find what you need to make your city better. Discover and share information about city improvement projects, urban improvement projects, tactical urbanism, neighbors, and neighbor knowledge.">
<meta name="author" content="Neighborhow">
<meta copyright="author" content="Neighborhow 2012-<?php echo date('Y');?>">
<meta name="keywords" content="<?php
/*
if ( is_single()) {
	$keyw = get_custom($post->ID,'keyw');
	echo $keyw.' '.$metaTerm.', '.$generalKeys;
}
elseif (is_page() OR isset($metaTerm)) {
	echo $generalKeys.', '.$metaTerm;
}
else { echo $generalKeys; }
*/
?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<?php // images ?>
<link rel="shortcut icon" href="<?php echo $style_url;?>/images/favicon.ico">
<link rel="image_src" type="image/jpeg" href="<?php echo $style_url;?>/images/logo_blog.jpg"/>

<?php // media-queries.js (fallback) ?>
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

<?php // stylesheets ?>
<link rel="stylesheet" href="<?php echo $style_url; ?>/style.css">
<link rel="stylesheet" href="<?php echo $style_url; ?>/lib/bootstrap.min.css">

<?php // fonts ?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>

<!--share this here-->
<!--google here-->

<?php // PNG FIX for IE6 ?>
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/pngfix/supersleight-min.js"></script>
<![endif]-->

</head>

<body <?php //body_class();?> id="<?php //echo $bodyid;?>">

<div id="container">
	<div class="wrap">
		
		<div id="header">
			<div id="branding"><a href="<?php echo $app_url;?>" title="Go to the home page" rel="Home"><img class="logo" src="<?php echo $style_url;?>/images/logo_circle.png" height="60" alt="Neighborhow logo" /><p class="site-title">Neighborhow</p></a>
			</div><!--/branding-->

			<div id="menu-primary2" class="menu-container">
				<div class="menu2">
					<ul id="menu-primary-items" class="">
						<li class="dropdown" id="menu1"><a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">Cities <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="menu-item"><a href="" >Typography</a></li>
								<li class="menu-item"><a href="" >Archives</a></li>
								<li class="menu-item"><a href="" >Lots of Comments</a></li>
								<li class="menu-item"><a href="" >Articles</a></li>
							</ul>
						</li>
						<li class="menu-item current-menu-item"><a href="<?php echo $app_url;?>/guides">Guides</a></li>	
						<li class="menu-item"><a href="" >Stories</a></li>
						<li class="menu-item"><a href="" >Resources</a></li>			
						<li class="menu-item"><a href="" >Blog</a></li>
						<!--li class="menu-item"><a href="http://demo.alienwp.com/origin/contact/" >Search</a></li-->
						<li class="menu-item"><a href="" >Sign In</a></li>
					</ul>
				</div>
			</div><!--/menu-->

			<h2 class="home" id="site-description"><span>Minimal and elegant WordPress theme with responsive layout. Optimized for mobile browsing. Free to download and use.</span></h2>
		</div>