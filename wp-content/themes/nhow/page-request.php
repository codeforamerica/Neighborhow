<?php /* Template Name: page-request */ ?>
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
				<h3 class="page-title">Request Neighborhow for Your City</h3>
<?php
if ($_POST['frm_action'] == 'create') {}
else {
?>						
				<p>Include your city name in your message, and we&#39;ll get in touch shortly.</p>	
<?php } ?>	
				<div id="contactus"><?php echo do_shortcode('[formidable id=21]');?>
				</div>				
			</div><!--/ content-->

<?php get_sidebar('misc');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>