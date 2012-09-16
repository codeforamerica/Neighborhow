<?php /* Template Name: page-contactus */ ?>
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
				<h3 class="page-title">Contact Us</h3>
<?php
if ($_POST['frm_action'] == 'create') {}
else {
?>						
				<p>You can send us a message by filling out the form below, and we&#39;ll get in touch shortly.</p>	
<?php } ?>	
				<div id="contactus"><?php echo do_shortcode('[formidable id=17]');?>
				</div>				
			</div><!--/ content-->

<?php get_sidebar('home');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>