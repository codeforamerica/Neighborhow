<?php 
// simple breadcrumbs  based on http://justintadlock.com/archives/2009/04/05/breadcrumb-trail-wordpress-plugin
// breadcrumbs for Buddypress based on Breadcrumbs Everywhere Plugin for Buddypress

function nhow_breadcrumb( $args = array() ) {
	global $post;
	
	// Set up the default arguments for the breadcrumb
	$defaults = array(
		'separator' => '&#187;',
		'before' => '',
		'after' => false,
		'front_page' => true,
		'show_home' => __('Home','nhow'),
		'format' => 'flat', // Implement later
		'echo' => true,
	);

	// Parse the arguments and extract them for easy variable naming
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	// Put spaces around the separator
	$separator = '<span class="divider">' . $separator . '</span>';
	
	// If it is the front page
	// Return no value
	if ( is_front_page() && !$front_page )
		return;

	if ( ( is_home() && is_front_page() ) && ( !$front_page ) )
		return;
	
	// Begin the breadcrumb
	$breadcrumb = '<ul class="breadcrumb breadcrumbs"><li>';
	$breadcrumb .= $before;
	if ( $show_home ) :
		$breadcrumb .= ' <a class="noline" href="' . home_url() . '" title="Go to Neighborhow home" rel="home" class="trail-begin">' . $show_home . '</a> ';
		if ( !is_home() && !is_front_page() )
			$breadcrumb .=  $separator;
	endif;

/*--------for Wordpress-----------*/
	// Pages
	if ( is_page() && !is_front_page()) :
		$parents = array();
		$parent_id = $post->post_parent;
		while ( $parent_id ) :
			$page = get_page( $parent_id );
			$parents[]  = '<a class="noline" href="' . get_permalink( $page->ID ) . '" title="' . get_the_title( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a> ' . $separator;
			$parent_id  = $page->post_parent;
		endwhile;
		$parents = array_reverse( $parents );
		$breadcrumb .= join( ' ', $parents );
		$breadcrumb .= get_the_title();

	// If home or front page
	elseif ( is_front_page() && $front_page ) :
		$breadcrumb = '<ul class="breadcrumb breadcrumbs"><li>' . $before . ' ' . $show_home;

	// If attachment
	elseif ( is_attachment() ) :
		$breadcrumb .= '<a class="noline" href="' . get_permalink( $post->post_parent ) . '" title="' . get_the_title( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a>';
		$breadcrumb .= $separator;
		$breadcrumb .= get_the_title();

	// Single posts - post
	elseif ( is_single() && $post->post_type == 'post') :
		$categories = get_the_category( ', ' );
		if ( $categories ) :
			foreach ( $categories as $cat ) :
				$cats[] = '<a class="noline" href="' . get_category_link( $cat->term_id ) . '" title="' . $cat->name . '">' . $cat->name . '</a>';
			endforeach;
			$breadcrumb .= join( ', ', $cats );
			$breadcrumb .= $separator;
		endif;
		$breadcrumb .= single_post_title( false, false );
		
	// Single posts - custom post
	elseif ( is_single() && $post->post_type == 'nh_guides' OR $post->post_type == 'nh_projects') :
		$posttype = $post->post_type;
		$posttypeUC = ucfirst($posttype);
		$posttypeLink = '<a class="noline" href="'.get_bloginfo('url').'/'.$posttype.'" title="'.$posttypeUC.'">Neighborhow '.$posttypeUC.'</a>';
		$breadcrumb .= $posttypeLink;
		$breadcrumb .= $separator;
		$breadcrumb .= single_post_title( false, false );		

	// Archives - custom posts
	elseif ( is_archive() && $post->post_type == 'guides' OR $post->post_type == 'projects') :
		$posttype = $post->post_type;
		$posttypeUC = ucfirst($posttype);
		$breadcrumb .= 'Neighborhow '.$posttypeUC;

	// Categories
	elseif ( is_category() ) :
		$pages = get_pages( array(
			'title_li' => '',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'categories.php',
			'echo' => 0
		) );
		if ( $pages && $pages[0]->ID !== get_option( 'page_on_front') )
			$breadcrumb .= '<a class="noline" href="' . get_page_link( $pages[0]->ID ) . '" title="' . $pages[0]->post_title . '">' . $pages[0]->post_title . '</a>' . $separator;
	// Category parents
		$cat = intval( get_query_var( 'cat' ) );
		$parent = &get_category( $cat );
		$parents = null;
		if ( is_wp_error( $parent ) )
			$parents = false;
		if ( $parent->parent && ( $parent->parent != $parent->term_id ) )
			$parents = get_category_parents( $parent->parent, true, $separator, false );

		if ( $parents ) $breadcrumb .= $parents;
		$breadcrumb .= single_cat_title( false, false );

	// Tags
	elseif ( is_tag() ) :
		$pages = get_pages( array(
			'title_li' => '',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'tags.php',
			'echo' => 0
		) );
		if ( $pages && $pages[0]->ID !== get_option( 'page_on_front' ) )
			$breadcrumb .= '<a class="noline" href="' . get_page_link( $pages[0]->ID ) . '" title="' . $pages[0]->post_title . '">' . $pages[0]->post_title . '</a>' . $separator;
		$breadcrumb .= single_tag_title( false, false );

	// Authors
	elseif ( is_author() ) :
		$pages = get_pages( array(
			'title_li' => '',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'authors.php',
			'echo' => 0
		) );
		if ( $pages && $pages[0]->ID !== get_option( 'page_on_front' ) )
			$breadcrumb .= '<a class="noline" href="' . get_page_link( $pages[0]->ID ) . '" title="' . $pages[0]->post_title . '">' . $pages[0]->post_title . '</a>' . $separator;
		$breadcrumb .= wp_title( false, false, false );
	
	// Search
	elseif ( is_search() ) :
//		$breadcrumb .= '<span class="trail-end">';
		$breadcrumb .= sprintf( __('Search results for &quot;%1$s&quot;', 'nhow'),  esc_attr( get_search_query() ) );
//		$breadcrumb .= '</span>';

	elseif ( is_date() ) :
		$pages = get_pages( array(
			'title_li' => '',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'archives.php',
			'echo' => 0
		) );
		if ( $pages && $pages[0]->ID !== get_option( 'page_on_front' ) )
			$breadcrumb .= '<a class="noline" href="' . get_page_link( $pages[0]->ID ) . '" title="' . $pages[0]->post_title . '">' . $pages[0]->post_title . '</a>' . $separator;

	// Day
		if ( is_day() ) :
			$breadcrumb .= '<a class="noline" href="' . get_year_link( get_the_time( __('Y', 'nhow') ) ) . '" title="' . get_the_time( __('Y', 'nhow') ) . '">' . get_the_time( __('Y', 'nhow') ) . '</a>' . $separator;
			$breadcrumb .= '<a class="noline" href="' . get_month_link( get_the_time( __('Y', 'nhow') ), get_the_time( __('m', 'nhow') ) ) . '" title="' . get_the_time( __('F', 'nhow') ) . '">' . get_the_time( __('F', 'nhow') ) . '</a>' . $separator;
			$breadcrumb .= '<span class="trail-end">' . get_the_time( __('j', 'nhow') ) . '</span>';

	// Week
		elseif ( get_query_var( 'w' ) ) :
			$breadcrumb .= '<a class="noline" href="' . get_year_link( get_the_time( __('Y', 'nhow') ) ) . '" title="' . get_the_time( __('Y', 'nhow') ) . '">' . get_the_time( __('Y', 'nhow') ) . '</a>' . $separator;
			$breadcrumb .= '<span class="trail-end">' . sprintf( __('Week %1$s', 'hybrid' ), get_the_time( __('W', 'nhow') ) ) . '</span>';

	// Month
		elseif ( is_month() ) :
			$breadcrumb .= '<a class="noline" href="' . get_year_link( get_the_time( __('Y', 'nhow') ) ) . '" title="' . get_the_time( __('Y', 'nhow') ) . '">' . get_the_time( __('Y', 'nhow') ) . '</a>' . $separator;
			$breadcrumb .= '<span class="trail-end">' . get_the_time( __('F', 'nhow') ) . '</span>';

	// Year
		elseif ( is_year() ) :
			$breadcrumb .= '<span class="trail-end">' . get_the_time( __('Y', 'nhow') ) . '</span>';

		endif;

	// 404
	elseif ( is_404() ) :
		$breadcrumb .= __('404 Not Found', 'nhow');

	endif;

/*--------for Buddypress-----------*/	

	

	// End the breadcrumb
	$breadcrumb .= $after . '</li></ul>';
	
	echo $breadcrumb;
}

?>