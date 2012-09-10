<form style="" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input style="" data-provide="typeahead" type="text" name="s" id="s" placeholder="<?php if ( is_search() ) echo esc_attr( get_search_query() ); else esc_attr_e( 'Find a Neighborhow Guide', 'nh' ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />

	<input type="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'nh' ); ?>" />
</form>
