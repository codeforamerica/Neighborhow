<?php /* Template Name: page-about */ ?>
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
				<h3 class="page-title">Neighborhow &#8212; making it easy to find and share ways to improve your neighborhood.</h3>		
								
				<p>Try Googling <a href="https://www.google.com/#hl=en&sclient=psy-ab&q=clean+up+my+block+philadelphia&oq=clean+up+my+block+philadelphia&aq=f&aqi=&aql=&gs_l=serp.3...26273.28478.1.28688.17.9.0.0.0.2.838.1318.4-1j0j1.2.0...0.0.7Skc8fy9eIg&pbx=1&fp=1&biw=885&bih=793&bav=on.2,or.r_gc.r_pw.r_cp.r_qf.&cad=b" title="Go to search results for clean up my block" target="_blank">clean up my block</a> or <a href="https://www.google.com/#hl=en&sclient=psy-ab&q=have+a+block+party+philadelphia&oq=have+a+block+party+philadelphia&aq=f&aqi=&aql=&gs_l=serp.3...3181.3181.0.3406.1.1.0.0.0.0.0.0..0.0...0.0.O6aTnGws4t0&pbx=1&fp=1&biw=885&bih=793&bav=on.2,or.r_gc.r_pw.r_cp.r_qf.&cad=b" title="Go to search results for have a block party" target="_blank">have a block party</a> or <a href="https://www.google.com/#hl=en&sclient=psy-ab&q=start+a+neighborhood+watch+philadelphia&oq=start+a+neighborhood+watch+philadelphia&aq=f&aqi=&aql=&gs_l=serp.3...4309.4309.0.4536.1.1.0.0.0.0.0.0..0.0...0.0.P2_BW1Ll6Pk&pbx=1&fp=1&biw=885&bih=793&bav=on.2,or.r_gc.r_pw.r_cp.r_qf.&cad=b" title="Go to search results for start a neighborhood watch" target="_blank">start a neighborhood watch</a> for your city. You probably won&#39;t find what you&#39;re looking for... What you really need is probably right next door: the lessons learned from your neighbors for what works (and what doesn&#39;t). But Google searches can&#39;t help you find those.</p>

				<p>Brought to you by <a class="nhline" href="http://www.codeforamerica.org" title="Go to Code for America" target="_blank">Code for America</a> and the <a class="nhline" href="http://www.phila.gov" title="Go to City of Philadelphia" target="_blank">City of Philadelphia</a>, Neighborhow is a place to collect and share citizen knowledge about urban improvement projects like starting a blood drive or designing a mini-park.</p>
				<p>A Neighborhow Guide can be about anything you think would be useful to other people in your community. Maybe that&#39;s how to organize a block party. Maybe it&#39;s how to get a free backyard tree from the city. Or how to track blighted properties in your neighborhood.</p>

				<p><strong>If it&#39;s something you know how to do, it&#39;s probably something other people want to know how to do. So share your Neighborhow!</strong></p>	
				
				<p style="text-align:center;">
					<a style="padding:0 2em 0 1em;" href="http://www.codeforamerica.org" title="Go to Code for America" target="_blank"><img src="<?php echo $style_url;?>/images/logo_cfa_color.png" alt="Code for America logo" /></a>
					<a style="padding:0 1em 0 2em;" href="http://www.phila.gov" title="Go to City of Philadelphia" target="_blank"><img src="<?php echo $style_url;?>/images/logo_phl_color.png" alt="City of Philadelphia logo" /></a></p>

			</div><!--/ content-->

<?php get_sidebar('about');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>