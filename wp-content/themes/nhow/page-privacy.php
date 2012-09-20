<?php /* Template Name: page-privacy */ ?>
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="content" class="about">
				<h3 class="page-title">Privacy Policy</h3>		
								
				<p>Neighborhow is a knowledge-sharing tool for citizens and their communities. Registration allows you to share information with other users about urban improvement projects.</p>

				<p>This Privacy Policy describes how and when Neighborhow collects, uses and shares your information when you use our Services. When using any of our Services you consent to the collection, transfer, manipulation, storage, disclosure and other uses of your information as described in this Privacy Policy. Irrespective of which country you reside in or supply information from, you authorize Neighborhow to use your information in the United States and any other country where Neighborhow operates.</p>

				<p>If you have any questions or comments about this Privacy Policy, please contact us at <a class="nhline" href="mailto:information@neighborhow.org">information@neighborhow.org</a></p>
				<p><strong>Information Collected When You Create an Account</strong> When you create a Neighborhow account, you provide some personal information, such as your name, username, password, city, and email address. This information is listed publicly on our Services, including on your profile page.</p>
				
				<p>You may add optional information to your profile page, such as a short bio, your organization, or a photo of yourself. When you provide this optional information, you are agreeing to make it publicly visible on our Services.
				
				<p><strong>Information Collected When You Create Content</strong> When you create a Neighborhow Guide or submit Feedback, you provide some personal information, such as your name and username. This information is listed publicly on our Services alongside the content you create.</p>

				<p><strong>Log Data</strong> Log Data: We use Google Analytics to record information ("Log Data") created by your use of the Services. Log Data may include information such as your IP address, browser type, operating system, the referring web page, pages visited, location, your mobile carrier, device and application IDs, search terms, and cookie information. We receive Log Data when you interact with our Services, for example, when you visit our websites. Neighborhow uses Log Data to measure, customize, and improve our services.</p>
				
				<p><strong>Privacy Policy Changes</strong> We may revise this Privacy Policy from time to time. The most current version will always be at <a class="nhline" href="<?php echo $app_url;?>/privacy" title="View privacy policy">http://neighborhow.org/privacy</a>. If we make any changes to this policy, we will provide notice on this page and to all registered users. By continuing to access or use the Services after those changes become effective, you agree to the terms of the revised Privacy Policy.</p>
				
				<p><strong>Change Log</strong><br/>20 Sep 2012: Initial privacy policy</p>
				
			</div><!--/ content-->

<?php get_sidebar('about');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>