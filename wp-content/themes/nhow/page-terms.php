<?php /* Template Name: page-terms */ ?>
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
				<h3 class="page-title">Terms of Service</h3>		
								
				<p>Neighborhow is a knowledge-sharing tool for citizens and their communities. Registration allows you to share information with other users about urban improvement projects.</p>
				
				<p>These Terms of Service govern your access to and use of the services on the Neighborhow website, and any information, text, graphics, photos or other materials uploaded, downloaded or appearing on the Services (collectively referred to as "Content"). By accessing or using the Services you agree to be bound by these Terms.</p>

				<p><strong>Public Information</strong> You are responsible for your use of the Services, for any Content you post to the Services, and for any consequences thereof. The Content you submit, post, or display will be visible to other users of the Services. You should only provide Content that you are comfortable sharing with others under these Terms.</p>
				
				<p><strong>Privacy</strong> Any information that you provide to Neighborhow is subject to our <a class="nhline" href="<?php echo $app_url;?>/privacy" title="View privacy policy">Privacy Policy</a>, which governs our collection and use of your information. As part of providing you the Services, we may need to contact you from time to time with service announcements or administrative messages. These communications are considered part of the Services and your Neighborhow account.</p>
				
				<p><strong>Passwords</strong> You are responsible for the password you use to access the Services and for any activities or actions under your password. We encourage you to use "strong" passwords (passwords that use a combination of upper and lower case letters, numbers and symbols) with your account.</p>
				
				<p><strong>Content</strong> All Content is the sole responsibility of the person who originated it. We may not monitor or control the Content posted via the Services and, we cannot take responsibility for such Content. Any use or reliance on any Content or materials posted via the Services or obtained by you through the Services is at your own risk.</p>
				
				<p><strong>Your Rights</strong> You retain your rights to any Content you submit, post or display on or through the Services. By submitting content to Neighborhow, you grant us a worldwide, non-exclusive, royalty-free license (with the right to sublicense) to use, copy, reproduce, process, adapt, modify, publish, transmit, display and distribute such Content in any and all media or distribution methods (now known or later developed). This just means that you're agreeing that Neighborhow can display your content on the website and make it visible to other users.</p>
				<p><strong>Neighborhow Rights</strong> The Neighborhow codebase is open-source and is available for civic and non-profit use under a BSD license. See our <a class="nhline" href="https://github.com/codeforamerica/cfa_template/blob/master/LICENSE.mkd" title="Go to Code for America BSD license">license here.</a></p>
				<p>The Neighborhow name and logo are trademarked materials and may not be used or reproduced without prior written permission.</p>
				
			</div><!--/ content-->

<?php get_sidebar('about');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>