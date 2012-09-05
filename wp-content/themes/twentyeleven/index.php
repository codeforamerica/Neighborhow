<?php get_header(); ?>

<div class="row-fluid row-content">
	<div class="wrapper">
		<div id="main">
			<div id="content">
				
				<div id="site-promo">
					<h3 class="promo-title">Welcome to Neighborhow</h3>
					<h4 class="promo-copy">Neighborhow makes it easy to find and share ways to improve your neighborhood.</h4>
					<p class="promo-buttons">
					<a href="<?php echo $app_url;?>/guides" class="nh-btn-orange">Start Exploring</a>
					<a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Create a Guide</a>
					</p>
				</div><!--/ site-promo-->				
				
				<div class="hfeed">
<?php if ( have_posts() ) : ?>

<?php twentyeleven_content_nav( 'nav-above' ); ?>

<?php while ( have_posts() ) : the_post(); ?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
<?php if ( is_sticky() ) : ?>
<hgroup>
<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
<h3 class="entry-format"><?php _e( 'Featured', 'twentyeleven' ); ?></h3>
</hgroup>
<?php else : ?>
<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<?php endif; ?>

<?php if ( 'post' == get_post_type() ) : ?>
<div class="entry-meta">
<?php twentyeleven_posted_on(); ?>
</div><!-- .entry-meta -->
<?php endif; ?>

<?php if ( comments_open() && ! post_password_required() ) : ?>
<div class="comments-link">
<?php comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'twentyeleven' ) . '</span>', _x( '1', 'comments number', 'twentyeleven' ), _x( '%', 'comments number', 'twentyeleven' ) ); ?>
</div>
<?php endif; ?>
</header><!-- .entry-header -->

<?php if ( is_search() ) : // Only display Excerpts for Search ?>
<div class="entry-summary">
<?php the_excerpt(); ?>
</div><!-- .entry-summary -->
<?php else : ?>
<div class="entry-content">
<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?>
<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
</div><!-- .entry-content -->
<?php endif; ?>

<footer class="entry-meta">
<?php $show_sep = false; ?>
<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
<?php
/* translators: used between list items, there is a space after the comma */
$categories_list = get_the_category_list( __( ', ', 'twentyeleven' ) );
if ( $categories_list ):
?>
<span class="cat-links">
<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'twentyeleven' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
$show_sep = true; ?>
</span>
<?php endif; // End if categories ?>
<?php
/* translators: used between list items, there is a space after the comma */
$tags_list = get_the_tag_list( '', __( ', ', 'twentyeleven' ) );
if ( $tags_list ):
if ( $show_sep ) : ?>
<span class="sep"> | </span>
<?php endif; // End if $show_sep ?>
<span class="tag-links">
<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'twentyeleven' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
$show_sep = true; ?>
</span>
<?php endif; // End if $tags_list ?>
<?php endif; // End if 'post' == get_post_type() ?>

<?php if ( comments_open() ) : ?>
<?php if ( $show_sep ) : ?>
<span class="sep"> | </span>
<?php endif; // End if $show_sep ?>
<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'twentyeleven' ) . '</span>', __( '<b>1</b> Reply', 'twentyeleven' ), __( '<b>%</b> Replies', 'twentyeleven' ) ); ?></span>
<?php endif; // End if comments_open() ?>

<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->





<?php endwhile; ?>

<?php twentyeleven_content_nav( 'nav-below' ); ?>

<?php else : ?>

		<article id="post-0" class="">
			<header class="">
				<h1 class=""><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->
<?php endif; ?>		
			</div><!--/ hfeed-->
		</div><!--/ content -->
<?php get_sidebar('home');?>
	</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer();?>