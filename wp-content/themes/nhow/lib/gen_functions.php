<?php
/*-------------TRIM BY CHARS--------------------*/
function trim_by_chars($string, $limit, $pad) {
	$pad = $pad;

	if(strlen($string) <= $limit) { 
		$new_string = $string; 
	}
	else {
		$new_string = substr($string, 0, $limit) . $pad;
	}
	return $new_string;
}	

/*-------------TRIM BY WORDS--------------------*/
function trim_by_words($string,$limit,$pad) {
	$pad = $pad;

	$wordsExp = explode(' ',$string);
	$words = implode(' ',array_slice($wordsExp,0));

	$countWords = str_word_count($words);

	if ($countWords <= $limit) {
		$new_string = $string;
	}
	else {
		$new_string = implode(' ',array_slice($wordsExp,0,$limit)).' '.$pad;
	}
	return $new_string;	
}

/*-------------GET CAPTION--------------------*/
/* not using
function nhow_image_caption($nhow_postID) {
//  global $post;

  $thumbnail_id = get_post_thumbnail_id($nhow_postID);
  $thumbnail_image = get_posts(array(
	'ID' => $thumbnail_id, 
	'post_type' => 'attachment'));

  if ($thumbnail_image && isset($thumbnail_image[0])) {
	return $thumbnail_image[0]->post_title;
  }
}
*/

				
	
	
//STOP HERE
?>