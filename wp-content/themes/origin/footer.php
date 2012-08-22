<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package Origin
 * @subpackage Template
 */
?>
				<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

				<?php do_atomic( 'close_main' ); // origin_close_main ?>

		</div><!-- #main -->

		<?php do_atomic( 'after_main' ); // origin_after_main ?>

		<?php get_sidebar( 'subsidiary' ); // Loads the sidebar-subsidiary.php template. ?>		

		<?php do_atomic( 'before_footer' ); // origin_before_footer ?>

		<div id="footer">

			<?php do_atomic( 'open_footer' ); // origin_open_footer ?>

			<div class="footer-content">
				
				<?php echo apply_atomic_shortcode( 'footer_content', hybrid_get_setting( 'footer_insert' ) ); ?>

				<?php do_atomic( 'footer' ); // origin_footer ?>

			</div>

			<?php do_atomic( 'close_footer' ); // origin_close_footer ?>

		</div><!-- #footer -->

		<?php do_atomic( 'after_footer' ); // origin_after_footer ?>
		
		</div><!-- .wrap -->

	</div><!-- #container -->

	<?php do_atomic( 'close_body' ); // origin_close_body ?>

	<?php wp_footer(); // wp_footer ?>
	
</body>
</html>