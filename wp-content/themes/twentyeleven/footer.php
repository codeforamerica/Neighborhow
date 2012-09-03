<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>

			<div id="site-generator">
				<?php do_action( 'twentyeleven_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyeleven' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'twentyeleven' ), 'WordPress' ); ?></a>
			</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>


<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.ui.position.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.ui.widget.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.ui.autocomplete.js"></script>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".next-steps").hide();
	
	$("#addstep2").click(function() {
		$('#step-container-2').show();
	});	

	$("#addstep3").click(function() {
		$('#step-container-3').show();
	});

	$('.remove').live('click', function() {
		$(this).closest('.step-container').hide();		
              
	});


// STOP HERE
});

</script>

</body>
</html>