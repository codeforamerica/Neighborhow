<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 *
 * @package Origin
 * @subpackage Template
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
<!-- Mobile viewport optimized -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<?php if ( hybrid_get_setting( 'origin_favicon_url' ) ) { ?>
<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo hybrid_get_setting( 'origin_favicon_url' ); ?>" />
<?php } ?>

<!-- Title -->
<title><?php hybrid_document_title(); ?></title>

<!-- Stylesheet -->	
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- WP Head -->
<?php wp_head(); ?>

</head>

<body class="<?php hybrid_body_class(); ?>">

	<?php do_atomic( 'open_body' ); // origin_open_body ?>

	<div id="container">
		
		<div class="wrap">

			<?php do_atomic( 'before_header' ); // origin_before_header ?>
	
			<div id="header">
	
				<?php do_atomic( 'open_header' ); // origin_open_header ?>
	
					<div id="branding">
						
						<?php origin_site_title(); ?>
						
					</div><!-- #branding -->
					
					<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>
					
					<?php hybrid_site_description(); ?>
	
					<?php do_atomic( 'header' ); // origin_header ?>
	
				<?php do_atomic( 'close_header' ); // origin_close_header ?>
	
			</div><!-- #header -->
	
			<?php do_atomic( 'after_header' ); // origin_after_header ?>
	
			<?php do_atomic( 'before_main' ); // origin_before_main ?>
	
			<div id="main">
	
				<?php do_atomic( 'open_main' ); // origin_open_main ?>