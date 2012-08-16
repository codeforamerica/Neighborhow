

	<footer id="colophon" role="contentinfo">

			<?php
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

<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.placeholder.js"></script>

<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/app.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/foundation.min.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.accordion.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.alerts.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.buttons.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.forms.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.mediaQueryToggle.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.navigation.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.orbit.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.reveal.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.tabs.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/jquery.foundation.tooltips.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/foundation/modernizr.foundation.js"></script>

<script>
$(document).foundationNavigation();
</script>







</body>
</html>